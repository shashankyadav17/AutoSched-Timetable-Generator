<?php
require __DIR__ . '/../frontend/db.php';


$jsonFile = __DIR__ . "/generated_timetable_all.json";
if (!file_exists($jsonFile)) die("❌ File not found.");

$data = json_decode(file_get_contents($jsonFile), true);

$conn->query("DELETE FROM final_timetable");

foreach ($data as $semester_key => $sections) {
    $semester_id = intval(str_replace('sem_', '', $semester_key)); // Extract int semester id from string like "sem_3"

    $stmt = $conn->prepare("SELECT break_after_period FROM general_settings WHERE semester_id = ?");
    $stmt->bind_param("i", $semester_id);
    $stmt->execute();
    $settings = $stmt->get_result()->fetch_assoc();
    $break_after = $settings ? (int)$settings['break_after_period'] : 0;

    foreach ($sections as $section => $days) {
        foreach ($days as $day => $slots) {
            foreach ($slots as $index => $entry) {
                $period_number = $index + 1;

                if (!empty($entry)) {
                    if (preg_match('/^(.*) \((.*)\)$/', $entry, $matches)) {
                        $subject = $matches[1];
                        $faculty = $matches[2];
                    } else {
                        $subject = $entry;
                        $faculty = '';
                    }

                    $stmt = $conn->prepare("
                        INSERT INTO final_timetable (semester_id, section, day, period_number, subject_name, faculty_name)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param("ississ", $semester_id, $section, $day, $period_number, $subject, $faculty);
                    $stmt->execute();
                }

                // Insert break after specified period
                if ($break_after && $period_number === $break_after) {
                    $break_period = $period_number + 1;
                    $stmt = $conn->prepare("
                        INSERT INTO final_timetable (semester_id, section, day, period_number, subject_name, faculty_name)
                        VALUES (?, ?, ?, ?, 'Break', '')
                    ");
                    $stmt->bind_param("issi", $semester_id, $section, $day, $break_period);
                    $stmt->execute();
                }
            }
        }
    }
}

echo "✅ Timetable with breaks saved successfully to final_timetable table.";

// Call generate_excel.py to create Excel file
$python_path = 'python'; // Adjust if needed
$script_path = __DIR__ . '/generate_excel.py';

$cmd = escapeshellcmd("$python_path $script_path");
exec($cmd . ' 2>&1', $output, $return_var);

if ($return_var !== 0) {
    echo "\n❌ Error generating Excel file.\n";
    echo implode("\n", $output);
} else {
    echo "\n✅ Excel timetable saved as generated_timetable.xlsx\n";
}
