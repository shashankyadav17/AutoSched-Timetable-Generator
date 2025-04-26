<?php
require __DIR__ . '/../frontend/db.php';

$semester_ids = isset($argv[1]) ? explode(',', $argv[1]) : [1];

$output = ['semesters' => []];

foreach ($semester_ids as $sem_id) {
    $sem_id = (int)$sem_id;
    $data = [];

    $gen = $conn->prepare("SELECT * FROM general_settings WHERE semester_id = ?");
    $gen->bind_param("i", $sem_id);
    $gen->execute();
    $general = $gen->get_result()->fetch_assoc();
    $general['working_days'] = array_map('trim', explode(',', $general['working_days']));
    $data['general_settings'] = $general;

    $stmt = $conn->prepare("SELECT s.subject_id, s.subject_name, ss.subject_type, ss.lectures_per_week FROM subjects s JOIN subject_settings ss ON s.subject_id = ss.subject_id WHERE ss.semester_id = ?");
    $stmt->bind_param("i", $sem_id);
    $stmt->execute();
    $data['subjects'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = $conn->prepare("SELECT fa.faculty_id, f.name as faculty_name, fa.subject_id, fa.section FROM faculty_allotment fa JOIN faculty f ON fa.faculty_id = f.faculty_id WHERE fa.semester_id = ?");
    $stmt->bind_param("i", $sem_id);
    $stmt->execute();
    $data['faculty_allotments'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $output['semesters'][$sem_id] = $data;
}

file_put_contents("timetable_input_all_semesters.json", json_encode($output, JSON_PRETTY_PRINT));
echo "✅ Export complete to timetable_input_all_semesters.json\n";
