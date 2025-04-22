<?php
require '../frontend/db.php';

$semester_id = $_GET['semester_id'] ?? 1; // Default to 2-1 if not passed
$data = [];

// 1. General Settings
$gen = $conn->prepare("SELECT * FROM general_settings WHERE semester_id = ?");
$gen->bind_param("i", $semester_id);
$gen->execute();
$general = $gen->get_result()->fetch_assoc();
$general['working_days'] = array_map('trim', explode(',', $general['working_days']));
$data['general_settings'] = $general;

// 2. Subjects + Type + Lectures/week
$query = "
    SELECT s.subject_id, s.subject_name, ss.subject_type, ss.lectures_per_week
    FROM subjects s
    JOIN subject_settings ss ON s.subject_id = ss.subject_id
    WHERE ss.semester_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$data['subjects'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 3. Faculty Allotments (which faculty teaches what subject & section)
$query = "
    SELECT fa.faculty_id, f.name as faculty_name, fa.subject_id, fa.section
    FROM faculty_allotment fa
    JOIN faculty f ON f.faculty_id = fa.faculty_id
    WHERE fa.semester_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$data['faculty_allotments'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 4. Save as JSON
file_put_contents("timetable_input.json", json_encode($data, JSON_PRETTY_PRINT));

echo "✅ JSON export created successfully as 'timetable_input.json'";
