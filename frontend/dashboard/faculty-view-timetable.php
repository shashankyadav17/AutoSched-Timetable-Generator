<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../home.html");
    exit;
}

require_once '../db.php';
$faculty_id = $_SESSION['faculty_id'];
$name = $_SESSION['username'];

if (!$faculty_id) {
    die("Faculty not found.");
}

// 1. Fetch all faculty's assigned subjects and sections
$sql = "SELECT fa.semester_id, fa.section, s.subject_name
        FROM faculty_allotment fa
        JOIN subjects s ON fa.subject_id = s.subject_id
        WHERE fa.faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$faculty_assignments = [];
while ($row = $result->fetch_assoc()) {
    $faculty_assignments[] = [
        'semester_id' => $row['semester_id'],
        'section' => $row['section'],
        'subject_name' => $row['subject_name']
    ];
}
$stmt->close();

if (empty($faculty_assignments)) {
    die("No subjects assigned to this faculty.");
}

// 2. Fetch timetable settings (assume faculty teaches in semesters they are allotted)
$semester_id = $faculty_assignments[0]['semester_id']; // take first semester for timings
$sql = "SELECT periods_per_day, period_duration, start_time, break_after_period, break_duration
        FROM general_settings
        WHERE semester_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$settings = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$settings) {
    die("Timetable settings missing for assigned semester.");
}

// 3. Prepare periods timings
$periods_per_day = $settings['periods_per_day'];
$period_duration = $settings['period_duration'];
$start_time_str = $settings['start_time'];
$break_after_period = $settings['break_after_period'];
$break_duration = $settings['break_duration'];

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$periods = [];

$start_time = DateTime::createFromFormat('H:i', $start_time_str);
if (!$start_time) {
    $start_time = DateTime::createFromFormat('g:i a', strtolower($start_time_str));
}
if (!$start_time) {
    $start_time = new DateTime('09:00');
}

for ($i = 1; $i <= $periods_per_day; $i++) {
    if ($i == $break_after_period + 1) {
        $break_start = clone $start_time;
        $break_start->modify('+' . ($period_duration * $break_after_period) . ' minutes');
        $break_end = clone $break_start;
        $break_end->modify('+' . $break_duration . ' minutes');
        $periods[] = [
            'label' => 'Break',
            'time' => $break_start->format('g:i A') . ' - ' . $break_end->format('g:i A'),
            'is_break' => true
        ];
    }

    $period_start = clone $start_time;
    if ($i > $break_after_period) {
        $period_start->modify('+' . $break_duration . ' minutes');
    }
    $period_start->modify('+' . ($period_duration * ($i - 1)) . 'minutes');
    $period_end = clone $period_start;
    $period_end->modify('+' . $period_duration . ' minutes');

    $periods[] = [
        'label' => "Period $i",
        'time' => $period_start->format('g:i A') . ' - ' . $period_end->format('g:i A'),
        'is_break' => false
    ];
}

// 4. Now search in final_timetable where subject_name, section and semester_id matches
$timetable = [];

// Build condition
$conditions = [];
$params = [];
$types = '';
foreach ($faculty_assignments as $assign) {
    $conditions[] = "(semester_id = ? AND section = ? AND subject_name = ?)";
    $types .= 'iss';
    $params[] = $assign['semester_id'];
    $params[] = $assign['section'];
    $params[] = $assign['subject_name'];
}

$sql = "SELECT day, period_number, subject_name, section, semester_id
        FROM final_timetable
        WHERE " . implode(' OR ', $conditions) . "
        ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), period_number";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $day = $row['day'];
    $period_number = $row['period_number'];
    $subject = $row['subject_name'];
    $section = $row['section'];
    $semester = $row['semester_id'];

    $semester_label = match($semester) {
        1 => '2-1', 2 => '2-2', 3 => '3-1', 4 => '3-2',
        default => "Sem $semester"
    };

    $timetable[$day][$period_number] = "$subject ($semester_label-$section)";
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Timetable</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        table.timetable {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table.timetable th, table.timetable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table.timetable th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="left-section">
        <img src="../assets/image.png" alt="Logo" style="height:40px;">
        <div class="title">AutoSched</div>
    </div>
    <div class="welcome">Welcome, <?= htmlspecialchars($name); ?> 👋</div>
    <form method="POST" action="../logout.php">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="sidebar">
    <a href="faculty-dashboard.php">Dashboard</a>
    <a href="faculty-view-timetable.php" class="active">View Timetable</a>
    <a href="#">Apply Leave</a>
    <?php if ((int)$faculty_id === 1074): ?>
        <a href="#">Allot Leaves</a>
        <a href="allot-faculty.php">Allot Faculty</a>
    <?php endif; ?>
</div>

<div class="main-content">
    <h2>Your Teaching Timetable</h2>

    <table class="timetable">
        <thead>
            <tr>
                <th>Day / Period</th>
                <?php foreach ($periods as $period): ?>
                    <th><?= htmlspecialchars($period['label']) ?><br><small><?= htmlspecialchars($period['time']) ?></small></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($days as $day): ?>
                <tr>
                    <td><?= htmlspecialchars($day) ?></td>
                    <?php
                    $period_counter = 1;
                    foreach ($periods as $period):
                        if ($period['is_break']) {
                            echo "<td><strong>Break</strong></td>";
                        } else {
                            if (isset($timetable[$day][$period_counter])) {
                                echo "<td><strong>" . htmlspecialchars($timetable[$day][$period_counter]) . "</strong></td>";
                            } else {
                                echo "<td>Leisure</td>";
                            }
                            $period_counter++;
                        }
                    endforeach;
                    ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>
