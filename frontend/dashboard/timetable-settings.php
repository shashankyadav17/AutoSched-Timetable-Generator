<?php
session_start();

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<script>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);
}


require '../db.php';

$subjects = [];
$selected_semester = $_POST['semester_id'] ?? '';
$periods_per_day = $_POST['periods_per_day'] ?? '';
$period_duration = $_POST['period_duration'] ?? '';
$theory_duration = $_POST['theory_duration'] ?? '';
$lab_duration = $_POST['lab_duration'] ?? '';
$working_days = $_POST['working_days'] ?? [];

if (!empty($selected_semester)) {
    $query = "SELECT s.subject_id, s.subject_name 
              FROM subjects s
              INNER JOIN semester_subject ss ON s.subject_id = ss.subject_id
              WHERE ss.semester_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $selected_semester);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timetable Settings</title>
    <link rel="stylesheet" href="/AutoSched-Timetable-Generator/frontend/dashboard-style.css">
    <style>
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .form-section h3 {
            margin-top: 0;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #3498db;
    color: white;
    padding: 15px 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 60px;
    z-index: 1000;
}

.logo-title {
    font-size: 20px;
    font-weight: bold;
}

.welcome-message {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
}

.logout-form {
    margin: 0;
}

.logout-btn {
    background-color: crimson;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: darkred;
}
    </style>
</head>
<body>
<div class="top-bar">
    <div class="logo-title"><strong>AutoSched</strong></div>
    <div class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</div>
    <form method="POST" action="../logout.php" class="logout-form">
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

    <div class="main-wrapper">
        <div class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="#">Manage Subjects</a>
            <a href="timetable-settings.php" class="active">Timetable Settings</a>
            <a href="#">Generate Timetable</a>
            <a href="#">Manage Faculty</a>
        </div>

        <div class="main-content">
            <h2>Timetable Configuration</h2>

            <form method="POST" action="timetable-settings.php">
                <label for="semester_id">Select Current Semester:</label>
                <select name="semester_id" id="semester_id" required onchange="this.form.submit()">
                    <option value="">--Select--</option>
                    <option value="1" <?= $selected_semester == '1' ? 'selected' : '' ?>>2-1</option>
                    <option value="2" <?= $selected_semester == '2' ? 'selected' : '' ?>>2-2</option>
                    <option value="3" <?= $selected_semester == '3' ? 'selected' : '' ?>>3-1</option>
                    <option value="4" <?= $selected_semester == '4' ? 'selected' : '' ?>>3-2</option>
                </select>

                <div class="form-section">
                    <h3>General Settings</h3>
                    <label>Periods per day:</label>
                    <input type="number" name="periods_per_day" value="<?= htmlspecialchars($periods_per_day) ?>" required><br>
                    <label>Duration of each period (in minutes):</label>
                    <input type="number" name="period_duration" value="<?= htmlspecialchars($period_duration) ?>" required><br>
                    <label>Theory class duration (in minutes):</label>
                    <input type="number" name="theory_duration" value="<?= htmlspecialchars($theory_duration) ?>" required><br>
                    <label>Lab class duration (in minutes):</label>
                    <input type="number" name="lab_duration" value="<?= htmlspecialchars($lab_duration) ?>" required><br>

                    <label>Working Days:</label><br>
                    <div class="checkbox-group">
                        <?php
                        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        foreach ($days as $day): ?>
                            <label>
                                <input type="checkbox" name="working_days[]" value="<?= $day ?>" <?= in_array($day, $working_days) ? 'checked' : '' ?>>
                                <?= $day ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (!empty($subjects)): ?>
                    <div class="form-section">
                        <h3>Subjects Configuration</h3>
                        <table border="1" cellpadding="6">
                            <tr><th>Subject</th><th>Type</th><th>Lectures/Week</th></tr>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                    <td>
                                        <select name="subject_type[<?php echo $subject['subject_id']; ?>]">
                                            <option value="theory">Theory</option>
                                            <option value="lab">Lab</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="lectures[<?php echo $subject['subject_id']; ?>]" required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <button type="submit" formaction="submit-settings.php">Save Settings</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
