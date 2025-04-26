<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../home.html");
    exit;
}

$name = $_SESSION['username'];
require_once '../db.php';

// Fetch faculty_id
$stmt = $conn->prepare("SELECT faculty_id FROM faculty WHERE TRIM(name) = TRIM(?)");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($faculty_id);
if (!$stmt->fetch()) {
    die("Faculty not found.");
}
$stmt->close();

// Fetch all semesters where this faculty is allotted
$sql = "SELECT DISTINCT fa.semester_id, gs.periods_per_day, gs.period_duration, gs.start_time, gs.break_after_period, gs.break_duration
        FROM faculty_allotment fa
        JOIN general_settings gs ON fa.semester_id = gs.semester_id
        WHERE fa.faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

$semester_settings = [];
while ($row = $result->fetch_assoc()) {
    $semester_settings[$row['semester_id']] = $row;
}
$stmt->close();

if (empty($semester_settings)) {
    die("No timetable assigned to this faculty.");
}

// For now assume faculty is only handling one semester
$semester_id = array_key_first($semester_settings);
$settings = $semester_settings[$semester_id];

// Prepare timetable structure
$periods_per_day = $settings['periods_per_day'];
$period_duration = $settings['period_duration'];
$start_time_str = $settings['start_time'];
$break_after_period = $settings['break_after_period'];
$break_duration = $settings['break_duration'];

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$periods = [];

// Time calculation
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
    $period_start->modify('+' . ($period_duration * ($i - 1)) . ' minutes');
    $period_end = clone $period_start;
    $period_end->modify('+' . $period_duration . ' minutes');

    $periods[] = [
        'label' => "Period $i",
        'time' => $period_start->format('g:i A') . ' - ' . $period_end->format('g:i A'),
        'is_break' => false
    ];
}

// Now fetch the periods the faculty is teaching
$sql = "SELECT ft.day, ft.period_number, ft.subject_name, ft.section, ft.semester_id
        FROM final_timetable ft
        WHERE ft.faculty_name = ?
        ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), period_number";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

$timetable = [];
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

    $timetable[$day][$period_number] = "$subject (".$semester_label." ".$section.")";
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
