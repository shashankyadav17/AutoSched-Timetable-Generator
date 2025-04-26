<?php
$file = __DIR__ . '/output/generated_timetable.xlsx';

if (!file_exists($file)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="generated_timetable.xlsx"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));

readfile($file);
exit;
?>
