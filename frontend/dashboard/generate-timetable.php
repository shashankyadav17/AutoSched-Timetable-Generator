<?php
session_start();
require '../db.php';

$selected_semesters = isset($_POST['semester_ids']) ? $_POST['semester_ids'] : [];
$semester_list = [1 => '2-1', 2 => '2-2', 3 => '3-1', 4 => '3-2'];

$all_summaries = [];
$subject_summaries = [];
$errors = [];
$generated_data = [];
$exec_output = [];
$exec_error = [];

function run_shell_command($cmd, &$output, &$error) {
    $php_path = 'D:\\xampp\\php\\php.exe';
    $python_path = 'python'; // Adjust to full path if needed

    $cmd = str_replace('{php}', $php_path, $cmd);
    $cmd = str_replace('{python}', $python_path, $cmd);

    $descriptorspec = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];
    $process = proc_open($cmd, $descriptorspec, $pipes);
    if (is_resource($process)) {
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $return_value = proc_close($process);
        return $return_value;
    }
    return -1;
}

// Load generated data if exists
if (file_exists("../../backend/generated_timetable_all.json")) {
    $json = json_decode(file_get_contents("../../backend/generated_timetable_all.json"), true);
    if ($json) {
        $generated_data = $json;
    }
}

// If no semesters selected but generated data exists, populate selected semesters from generated data keys
if (empty($selected_semesters) && !empty($generated_data)) {
    $selected_semesters = [];
    foreach ($generated_data as $sem_key => $_) {
        $sem_id = intval(str_replace('sem_', '', $sem_key));
        $selected_semesters[] = $sem_id;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_now'])) {
    if (!empty($selected_semesters)) {
        foreach ($selected_semesters as $sem_id) {
            $stmt = $conn->prepare("SELECT * FROM general_settings WHERE semester_id = ?");
            $stmt->bind_param("i", $sem_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $errors[] = "General settings missing for semester $sem_id";
                continue;
            }
            $general = $res->fetch_assoc();

            $stmt2 = $conn->prepare("SELECT COUNT(*) as count FROM subject_settings WHERE semester_id = ?");
            $stmt2->bind_param("i", $sem_id);
            $stmt2->execute();
            $count = $stmt2->get_result()->fetch_assoc()['count'];
            if ($count == 0) {
                $errors[] = "Subject settings missing for semester $sem_id";
                continue;
            }

            $general['working_days'] = implode(', ', array_map('trim', explode(',', $general['working_days'])));
            $all_summaries[$sem_id] = $general;

            $stmt3 = $conn->prepare("SELECT subject_name, subject_type, lectures_per_week FROM subjects s JOIN subject_settings ss ON s.subject_id = ss.subject_id WHERE ss.semester_id = ?");
            $stmt3->bind_param("i", $sem_id);
            $stmt3->execute();
            $subject_summaries[$sem_id] = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        $sem_str = implode(",", $selected_semesters);
        $ret1 = run_shell_command("{php} ../../backend/export_timetable_data.php $sem_str", $out1, $err1);
        $exec_output[] = $out1;
        $exec_error[] = $err1;

        $ret2 = run_shell_command("{python} ../../backend/generate_timetable.py", $out2, $err2);
        $exec_output[] = $out2;
        $exec_error[] = $err2;

        if ($ret1 === 0 && $ret2 === 0) {
            $json = json_decode(file_get_contents("../../backend/generated_timetable_all.json"), true);
            $generated_data = $json;
        } else {
            $errors[] = "Error running timetable generation scripts.";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_now'])) {
    $ret3 = run_shell_command("{php} ../../backend/save_generated_timetable.php", $out3, $err3);
    $exec_output[] = $out3;
    $exec_error[] = $err3;

    if ($ret3 === 0) {
        // 🛠 After saving into database, generate Excel
        $ret4 = run_shell_command("{python} ../../backend/generate_excel.py", $out4, $err4);
        $exec_output[] = $out4;
        $exec_error[] = $err4;

        if ($ret4 === 0) {
            $saved = true;
        } else {
            $errors[] = "Error generating Excel file.";
        }
    } else {
        $errors[] = "Error saving timetable to database.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Timetable</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        .form-section { margin: 20px 0; background: #f0f0f0; padding: 15px; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
        select[multiple] { width: 300px; height: 120px; }
    </style>
</head>
<body>
<div class="top-bar">
    <div class="logo-title"><strong>AutoSched</strong></div>
    <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</div>
</div>

<div class="main-wrapper">
    <div class="sidebar">
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="timetable-settings.php">Timetable Settings</a>
        <a href="generate-timetable.php" class="active">Generate Timetable</a>
    </div>

    <div class="main-content">
        <h2>Generate Timetable</h2>

        <form method="POST">
            <label><strong>Select Semesters (Ctrl+Click for multiple):</strong></label><br>
            <select name="semester_ids[]" multiple>
                <?php foreach ($semester_list as $id => $label): ?>
                    <option value="<?= $id ?>" <?= in_array($id, $selected_semesters) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit" name="generate_now">Generate Timetable</button>
        </form>

        <?php if (!empty($errors)): ?>
            <div class="form-section error">
                <h4>⚠️ Errors Found:</h4>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($exec_output) || !empty($exec_error)): ?>
            <div class="form-section">
                <h4>🖥️ Script Output:</h4>
                <?php foreach ($exec_output as $out): ?>
                    <pre><?= htmlspecialchars($out) ?></pre>
                <?php endforeach; ?>
                <?php foreach ($exec_error as $err): ?>
                    <?php if (!empty(trim($err))): ?>
                        <pre style="color:red;"><?= htmlspecialchars($err) ?></pre>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($all_summaries)): ?>
            <div class="form-section">
                <h3>📝 General Settings Summary</h3>
                <?php foreach ($all_summaries as $sem_id => $s): ?>
                    <h4><?= $semester_list[$sem_id] ?></h4>
                    <p><strong>Periods/Day:</strong> <?= $s['periods_per_day'] ?> | <strong>Duration:</strong> <?= $s['period_duration'] ?> mins</p>
                    <p><strong>Theory Duration:</strong> <?= $s['theory_duration'] ?> | <strong>Lab Duration:</strong> <?= $s['lab_duration'] ?></p>
                    <p><strong>Start Time:</strong> <?= $s['start_time'] ?> | <strong>Break After Period:</strong> <?= $s['break_after_period'] ?> (<?= $s['break_duration'] ?> mins)</p>
                    <p><strong>Working Days:</strong> <?= $s['working_days'] ?></p>
                    <hr>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($subject_summaries)): ?>
            <div class="form-section">
                <h3>📚 Subject Settings Summary</h3>
                <?php foreach ($subject_summaries as $sem_id => $subjects): ?>
                    <h4><?= $semester_list[$sem_id] ?></h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Type</th>
                                <th>Lectures/Week</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $sub): ?>
                                <tr>
                                    <td><?= htmlspecialchars($sub['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($sub['subject_type']) ?></td>
                                    <td><?= htmlspecialchars($sub['lectures_per_week']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

<?php if (!empty($generated_data) && !empty($all_summaries) && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <form method="POST">
    <input type="hidden" name="semester_ids[]" value="<?= implode('" value="', $selected_semesters) ?>">
    <button type="submit" name="save_now">✅ Save Timetable</button>
    <?php if (isset($saved) && $saved): ?>
        <a href="/AutoSched-Timetable-Generator/backend/download_timetable.php" style="margin-left: 10px;">
            📥 Download Excel
        </a>
    <?php endif; ?>
</form>

            <br>
            <?php foreach ($generated_data as $sem => $sections): ?>
                <?php
                    // Extract integer semester id from string key like "sem_3"
                    $sem_id = intval(str_replace('sem_', '', $sem));
                ?>
                <h3>📘 Timetable for Semester <?= $semester_list[$sem_id] ?? "Sem $sem_id" ?></h3>
                <?php foreach ($sections as $section => $grid): ?>
                    <h4>Section <?= htmlspecialchars($section) ?></h4>
                    <table>
                        <tr>
                            <th>Day / Hour</th>
                            <?php
                            $per_day = $all_summaries[$sem_id]['periods_per_day'];
                            $break_after = $all_summaries[$sem_id]['break_after_period'];
                            $break_duration = $all_summaries[$sem_id]['break_duration'];
                            $start_time_str = $all_summaries[$sem_id]['start_time'];
                            $period_duration = $all_summaries[$sem_id]['period_duration'];

                            // Convert start_time string to DateTime object
                            $start_time = DateTime::createFromFormat('H:i', $start_time_str);
                            if (!$start_time) {
                                // Try 12-hour format with am/pm
                                $start_time = DateTime::createFromFormat('g:i a', strtolower($start_time_str));
                            }
                            if (!$start_time) {
                                // Fallback to 9:00 AM if parsing fails
                                $start_time = new DateTime('09:00');
                            }

                            for ($i = 1; $i <= $per_day; $i++):
                                if ($i === $break_after + 1):
                                    // Calculate break start and end time
                                    $break_start = clone $start_time;
                                    $break_start->modify('+'.($period_duration * $break_after).' minutes');
                                    $break_end = clone $break_start;
                                    $break_end->modify('+'.$break_duration.' minutes');
                                    echo "<th>Break<br>" . $break_start->format('g:i') . " - " . $break_end->format('g:i') . "</th>";
                                endif;

                                // Calculate period start and end time
                                $period_start = clone $start_time;
                                if ($i > $break_after) {
                                    $period_start->modify('+'.$break_duration.' minutes');
                                }
                                $period_start->modify('+'.($period_duration * ($i - 1)).' minutes');
                                $period_end = clone $period_start;
                                $period_end->modify('+'.$period_duration.' minutes');

                                echo "<th>Period $i<br>" . $period_start->format('g:i') . " - " . $period_end->format('g:i') . "</th>";
                            endfor;
                            ?>
                        </tr>
                        <?php
                        $all_days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        foreach ($all_days as $day):
                            $slots = $grid[$day] ?? array_fill(0, $per_day, "");
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($day) ?></td>
                                <?php
                                $break_after_inserted = false;
                                foreach ($slots as $i => $slot):
                                    $period_number = $i + 1;
                                    if ($period_number === $break_after + 1 && !$break_after_inserted):
                                        echo "<td><strong>Break</strong></td>";
                                        $break_after_inserted = true;
                                    endif;
                                    echo "<td>" . htmlspecialchars($slot ?: "") . "</td>";
                                endforeach;
                                ?>
                            </tr>
                        <?php endforeach; ?>
                    </table><br>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php elseif (isset($saved) && $saved): ?>
            <p class="success"> Timetable saved successfully to final_timetable.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
