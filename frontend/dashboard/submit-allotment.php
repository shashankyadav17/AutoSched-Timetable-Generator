<?php
require '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = $_POST['semester'];
    $facultySelections = $_POST['faculty'];

    // 1. Allot Faculty
    foreach ($facultySelections as $subject_id => $sections) {
        foreach ($sections as $section => $faculty_id) {
            $stmt = $conn->prepare("REPLACE INTO faculty_allotment (subject_id, section, faculty_id, semester_id) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("isii", $subject_id, $section, $faculty_id, $semester_id);
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }
            $stmt->close();
        }
    }

    // 2. Determine year and semester string for notification
    $year = ($semester_id <= 2) ? 2 : 3;
    $semNum = ($semester_id % 2 === 1) ? 1 : 2;
    $message = "Faculty allotted for {$year}-{$semNum}";

    // 3. Insert notification into admin_notifications
    $stmt = $conn->prepare("INSERT INTO admin_notifications (message, year, semester, created_at) VALUES (?, ?, ?, NOW())");

    if ($stmt) {
        $stmt->bind_param("sii", $message, $year, $semNum); // Bind all 3 variables
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();

    // 4. Redirect with success message
    header("Location: allot-faculty.php?success=1");
    exit;
} else {
    die("Invalid request method.");
}
?>
