<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = $_POST['semester_id'];
    $periods_per_day = $_POST['periods_per_day'];
    $period_duration = $_POST['period_duration'];
    $theory_duration = $_POST['theory_duration'];
    $lab_duration = $_POST['lab_duration'];
    $working_days = $_POST['working_days'] ?? [];
    $subject_types = $_POST['subject_type'];
    $lectures = $_POST['lectures'];

    // ✅ Validation
    $num_working_days = count($working_days);
    $total_available_periods = $periods_per_day * $num_working_days;

    $total_required_lectures = 0;
    foreach ($lectures as $subject_id => $count) {
        $total_required_lectures += intval($count);
    }

    if ($total_required_lectures > $total_available_periods) {
        $_SESSION['error'] = "Total required lectures ($total_required_lectures) exceed available periods ($total_available_periods). Please reduce lecture counts or increase working days/periods.";
        header("Location: timetable-settings.php");
        exit;
    }

    // 🧹 Delete old settings for this semester
    $conn->query("DELETE FROM general_settings WHERE semester_id = $semester_id");
    $conn->query("DELETE FROM subject_settings WHERE semester_id = $semester_id");

    // 💾 Insert general settings
    $working_days_str = implode(",", $working_days);
    $stmt = $conn->prepare("INSERT INTO general_settings 
        (semester_id, periods_per_day, period_duration, theory_duration, lab_duration, working_days) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiis", $semester_id, $periods_per_day, $period_duration, $theory_duration, $lab_duration, $working_days_str);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "Error saving general settings.";
        header("Location: timetable-settings.php");
        exit;
    }

    // 💾 Insert subject settings
    foreach ($subject_types as $subject_id => $type) {
        $lecture_count = intval($lectures[$subject_id]);

        $stmt2 = $conn->prepare("INSERT INTO subject_settings 
            (subject_id, semester_id, subject_type, lectures_per_week) 
            VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iisi", $subject_id, $semester_id, $type, $lecture_count);

        if (!$stmt2->execute()) {
            $_SESSION['error'] = "Error saving subject settings for subject ID $subject_id.";
            header("Location: timetable-settings.php");
            exit;
        }
    }

    $_SESSION['success'] = "Settings saved successfully!";
    header("Location: timetable-settings.php");
    exit;
}
?>
