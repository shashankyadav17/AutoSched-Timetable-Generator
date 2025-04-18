<?php
session_start();
require '../db.php';
include_once 'generate-timetable-logic.php';


$selected_semester = $_POST['semester_id'] ?? '';
$summary = null;
$subjects = [];
$timetable = [];
$settings = [];

if (!empty($selected_semester)) {
    // Fetch general settings
    $stmt = $conn->prepare("SELECT * FROM general_settings WHERE semester_id = ?");
    $stmt->bind_param("i", $selected_semester);
    $stmt->execute();
    $general_result = $stmt->get_result();
    $summary = $general_result->fetch_assoc();

    // Fetch subject settings
    $stmt2 = $conn->prepare("SELECT s.subject_name, ss.subject_type, ss.lectures_per_week 
        FROM subject_settings ss 
        JOIN subjects s ON ss.subject_id = s.subject_id 
        WHERE ss.semester_id = ?");
    $stmt2->bind_param("i", $selected_semester);
    $stmt2->execute();
    $subjects = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    // If timetable generate button clicked
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_now'])) {
        $settings = $summary;

        // Convert working_days string to array
        if (isset($settings['working_days'])) {
            $settings['working_days'] = array_map('trim', explode(',', $settings['working_days']));
        } else {
            $settings['working_days'] = [];
        }

        // Generate timetable
        $timetable = generateTimetable($selected_semester);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Generate Timetable</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        .form-section {
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td, table th {
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="logo-title"><strong>AutoSched</strong></div>
        <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</div>
    </div>

    <div class="main-wrapper">
        <div class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="timetable-settings.php">Timetable Settings</a>
            <a href="generate-timetable.php" class="active">Generate Timetable</a>
        </div>

        <div class="main-content">
            <h2>Generate Timetable</h2>

            <form method="POST" action="generate-timetable.php">
                <label>Select Semester:</label>
                <select name="semester_id" required onchange="this.form.submit()">
                    <option value="">--Select--</option>
                    <option value="1" <?= $selected_semester == '1' ? 'selected' : '' ?>>2-1</option>
                    <option value="2" <?= $selected_semester == '2' ? 'selected' : '' ?>>2-2</option>
                    <option value="3" <?= $selected_semester == '3' ? 'selected' : '' ?>>3-1</option>
                    <option value="4" <?= $selected_semester == '4' ? 'selected' : '' ?>>3-2</option>
                </select>
            </form>
            <?php
function getPeriodTimes($start_time, $period_duration, $periods_per_day, $break_after_period, $break_duration) {
    $times = [];
    $current_time = strtotime($start_time);

    for ($i = 1; $i <= $periods_per_day; $i++) {
        $start = date('g:i A', $current_time);
        $current_time += $period_duration * 60;
        $end = date('g:i A', $current_time);
        $times[] = "$start - $end";

        if ($i == $break_after_period) {
            $current_time += $break_duration * 60; // Skip break
        }
    }

    return $times;
}
?>


            <?php if ($summary): ?>
                <div class="form-section">
                    <h3>General Settings Summary</h3>
                    <p><strong>Periods per Day:</strong> <?= $summary['periods_per_day'] ?></p>
                    <p><strong>Period Duration:</strong> <?= $summary['period_duration'] ?> mins</p>
                    <p><strong>Theory Duration:</strong> <?= $summary['theory_duration'] ?> mins</p>
                    <p><strong>Lab Duration:</strong> <?= $summary['lab_duration'] ?> mins</p>
                    <p><strong>Start Time:</strong> <?= $summary['start_time'] ?></p>
                    <p><strong>Break After Period:</strong> <?= $summary['break_after_period'] ?></p>
                    <p><strong>Break Duration:</strong> <?= $summary['break_duration'] ?> mins</p>
                    <p><strong>Working Days:</strong> <?= $summary['working_days'] ?></p>
                </div>

                <div class="form-section">
                    <h3>Subjects Configuration</h3>
                    <table>
                        <tr>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Lectures/Week</th>
                        </tr>
                        <?php foreach ($subjects as $sub): ?>
                            <tr>
                                <td><?= htmlspecialchars($sub['subject_name']) ?></td>
                                <td><?= ucfirst($sub['subject_type']) ?></td>
                                <td><?= $sub['lectures_per_week'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <form method="POST" action="generate-timetable.php">
                    <input type="hidden" name="semester_id" value="<?= $selected_semester ?>">
                    <button type="submit" name="generate_now">Generate Timetable</button>
                </form>
                <?php if (!empty($timetable)): ?>
    <?php foreach ($timetable as $section => $grid): ?>
        <h3><?= "Timetable for Section $section" ?></h3>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Day / Hour</th>
                    <?php for ($p = 1; $p <= $settings['periods_per_day']; $p++): ?>
                        <th>Period <?= $p ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings['working_days'] as $day): ?>
                    <tr>
                        <td><?= $day ?></td>
                        <?php for ($p = 1; $p <= $settings['periods_per_day']; $p++): ?>
                            <?php
                            $entry = $grid[$day][$p] ?? null;
                            if ($entry) {
                                echo "<td>{$entry['subject']}<br><small>F: {$entry['faculty']}</small></td>";
                            } else {
                                echo "<td></td>";
                            }
                            ?>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br><br>
    <?php endforeach; ?>
<?php endif; ?>

            <?php elseif ($selected_semester): ?>
                <p style="color:red;">Settings not configured for selected semester. Please check Timetable Settings first.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
