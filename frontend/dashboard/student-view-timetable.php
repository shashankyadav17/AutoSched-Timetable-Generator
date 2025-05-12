<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../home.html");
    exit;
}

$name = $_SESSION['username'];
require_once '../db.php';

// Fetch student semester and section
$stmt = $conn->prepare("SELECT semester_id, section FROM students WHERE TRIM(roll_number) = TRIM(?)");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($semester_id, $section);
if (!$stmt->fetch()) {
    die("Student details not found.");
}
$stmt->close();

// Fetch general settings for the semester
$stmt = $conn->prepare("SELECT periods_per_day, period_duration, start_time, break_after_period, break_duration
                        FROM general_settings WHERE semester_id = ?");
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$stmt->bind_result($periods_per_day, $period_duration, $start_time_str, $break_after_period, $break_duration);
if (!$stmt->fetch()) {
    die("General settings not found for semester.");
}
$stmt->close();

// Fetch timetable for semester and section
$sql = "SELECT day, period_number, subject_name, faculty_name 
        FROM final_timetable 
        WHERE semester_id = ? AND section = ? 
        ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), period_number";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $semester_id, $section);
$stmt->execute();
$result = $stmt->get_result();

$timetable = [];
while ($row = $result->fetch_assoc()) {
    $timetable[$row['day']][$row['period_number']] = [
        'subject_name' => $row['subject_name'],
        'faculty_name' => $row['faculty_name']
    ];
}
$stmt->close();

// Fetch faculty allotment for display
$sql = "SELECT fa.subject_id, s.subject_name, f.name AS faculty_name
        FROM faculty_allotment fa
        JOIN subjects s ON fa.subject_id = s.subject_id
        JOIN faculty f ON fa.faculty_id = f.faculty_id
        WHERE fa.semester_id = ? AND fa.section = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $semester_id, $section);
$stmt->execute();
$result = $stmt->get_result();

$faculty_allotments = [];
while ($row = $result->fetch_assoc()) {
    $faculty_allotments[] = [
        'subject_name' => $row['subject_name'],
        'faculty_name' => $row['faculty_name']
    ];
}
$stmt->close();

$conn->close();

// Days of the week
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// Build periods array with break inserted
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
    $period_start->modify('+' . ($period_duration * ($i - 1)) . ' minutes');
    $period_end = clone $period_start;
    $period_end->modify('+' . $period_duration . ' minutes');

    $periods[] = [
        'label' => "Period $i",
        'time' => $period_start->format('g:i A') . ' - ' . $period_end->format('g:i A'),
        'is_break' => false
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Timetable</title>
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
        .faculty-allotment {
            margin-top: 30px;
        }
        .faculty-allotment table {
            width: 60%;
            border-collapse: collapse;
        }
        .faculty-allotment th, .faculty-allotment td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .faculty-allotment th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="left-section">
        <div class="logo-title"><strong>AutoSched</strong></div>
    </div>
    <div class="welcome">Welcome, <?= htmlspecialchars($name); ?> 👋</div>
    <form method="POST" action="../logout.php">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="sidebar">
    <a href="student-dashboard.php">Dashboard</a>
    <a href="student-view-timetable.php" class="active">View Timetable</a>
</div>

<div class="main-content">
    <h2>Your Timetable (Section: <?= htmlspecialchars($section); ?>, Semester: <?= htmlspecialchars($semester_id); ?>)</h2>

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
                    $break_inserted = false;
                    $period_counter = 1;
                    foreach ($periods as $period):
                        if ($period['is_break']) {
                            echo "<td><strong>Break</strong></td>";
                        } else {
                            if (isset($timetable[$day][$period_counter])) {
                                echo "<td><strong>" . htmlspecialchars($timetable[$day][$period_counter]['subject_name']) . "</strong><br><small>" . htmlspecialchars($timetable[$day][$period_counter]['faculty_name']) . "</small></td>";
                            } else {
                                echo "<td>-</td>";
                            }
                            $period_counter++;
                        }
                    endforeach;
                    ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="faculty-allotment">
        <h3>Faculty Allotment</h3>
        <?php if (count($faculty_allotments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Faculty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faculty_allotments as $fa): ?>
                        <tr>
                            <td><?= htmlspecialchars($fa['subject_name']) ?></td>
                            <td><?= htmlspecialchars($fa['faculty_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No faculty allotment found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
