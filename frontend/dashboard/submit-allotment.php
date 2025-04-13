<?php
require '../db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = $_POST['semester'];
    $facultySelections = $_POST['faculty'];

    foreach ($facultySelections as $subject_id => $sections) {
        foreach ($sections as $section => $faculty_id) {
            // Insert or update into faculty_allotment
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

    // Close the database connection
    $conn->close();

    // Redirect back to the allot-faculty.php page with a success message
    header("Location: allot-faculty.php?success=1");
    exit;
} else {
    die("Invalid request method.");
}
?>