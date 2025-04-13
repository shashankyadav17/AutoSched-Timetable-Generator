<?php
require '../db.php'; // your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = $_POST['sem']; // Use directly
    $year = $_POST['year']; // Still useful for tracking, though not used in DB queries
    if ($year == 3) {
        $semester_id += 2; // Add 2 to the semester_id if year is 3
    }
    // Step 1: Get all subject_ids for the selected semester
    $stmt = $conn->prepare("SELECT subject_id FROM semester_subject WHERE semester_id = ?");
    $stmt->bind_param("i", $semester_id);
    $stmt->execute();
    $subjectResult = $stmt->get_result();

    $subjectIds = [];
    while ($row = $subjectResult->fetch_assoc()) {
        $subjectIds[] = $row['subject_id'];
    }
    $stmt->close();

    if (empty($subjectIds)) {
        echo "<p>No subjects found for the selected semester.</p>";
        exit;
    }

    // Step 2: Fetch subject details
    $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
    $types = str_repeat('i', count($subjectIds));

    $stmt = $conn->prepare("SELECT subject_id, subject_name FROM subjects WHERE subject_id IN ($placeholders)");
    $stmt->bind_param($types, ...$subjectIds);
    $stmt->execute();
    $subjects = $stmt->get_result();

    // Step 3: Fetch all faculty teaching these subjects
    $stmt = $conn->prepare("SELECT DISTINCT f.faculty_id, f.name
                            FROM faculty f
                            JOIN faculty_subjects fs ON f.faculty_id = fs.faculty_id
                            WHERE fs.subject_id IN ($placeholders)");
    $stmt->bind_param($types, ...$subjectIds);
    $stmt->execute();
    $facultyResult = $stmt->get_result();

    $facultyList = [];
    while ($row = $facultyResult->fetch_assoc()) {
        $facultyList[] = $row;
    }
    $stmt->close();
    // Step 4: Generate Allotment Form
    if ($subjects->num_rows > 0) {
        echo '<input type="hidden" name="semester" value="' . htmlspecialchars($semester_id) . '">';
    
        echo '<table border="1" cellpadding="10" cellspacing="0">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Subject Name</th>';
        echo '<th>Section A</th>';
        echo '<th>Section B</th>';
        echo '<th>Section C</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    
        while ($row = $subjects->fetch_assoc()) {
            $subjectId = $row['subject_id'];
            $subjectName = $row['subject_name'];
    
            echo '<tr>';
            echo '<td>' . htmlspecialchars($subjectName) . '</td>';
    
            foreach (['A', 'B', 'C'] as $section) {
                $selectName = "faculty[" . $subjectId . "][" . $section . "]";
                echo '<td>';
                echo '<select name="' . $selectName . '" required>';
                echo '<option value="">-- Select Faculty --</option>';
                foreach ($facultyList as $faculty) {
                    echo '<option value="' . $faculty['faculty_id'] . '">' . htmlspecialchars($faculty['name']) . '</option>';
                }
                echo '</select>';
                echo '</td>';
            }
    
            echo '</tr>';
        }
    
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No subjects found for selected semester.</p>';
    }
}  
$conn->close();
?>