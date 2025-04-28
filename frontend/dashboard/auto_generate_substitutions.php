<?php
require_once '../db.php';

function assign_substitutes_for_leave($leave_id) {
    global $conn;

    // 1. Get leave details
    $leave = $conn->query("SELECT * FROM faculty_leaves WHERE leave_id = $leave_id")->fetch_assoc();
    if (!$leave) {
        return;
    }
    $faculty_id = $leave['faculty_id'];
    $start_date = $leave['start_date'];
    $end_date = $leave['end_date'];

    // 2. Loop through each day
    $periods_per_day = 8; // Assuming 8 periods, adjust if needed
    $days_of_week = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    $period_dates = [];
    $current = strtotime($start_date);
    $end = strtotime($end_date);

    while ($current <= $end) {
        $day_name = $days_of_week[date('w', $current)];
        if ($day_name == "Sunday") { 
            $current = strtotime("+1 day", $current);
            continue; 
        }

        $date_str = date('Y-m-d', $current);

        // 3. Fetch classes where this faculty teaches on that day
        $sql = "SELECT * FROM final_timetable WHERE faculty_name = (
                    SELECT name FROM faculty WHERE faculty_id = $faculty_id
                ) AND day = '$day_name'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $period_number = $row['period_number'];
            $section = $row['section'];
            $semester_id = $row['semester_id'];
            $subject_name = $row['subject_name'];

            // 4. Find available substitute faculty for this slot
            $available_faculty = [];

            // Prefer faculty teaching same section and semester
            $q = "SELECT DISTINCT f.faculty_id, f.name
                  FROM faculty_allotment fa
                  JOIN faculty f ON fa.faculty_id = f.faculty_id
                  WHERE fa.section = '$section' AND fa.semester_id = $semester_id
                  AND f.faculty_id != $faculty_id";
            $res = $conn->query($q);
            while ($fac = $res->fetch_assoc()) {
                $fac_id = $fac['faculty_id'];
                // Check if this faculty is free during that period
                $check = $conn->query("SELECT COUNT(*) as c FROM final_timetable WHERE faculty_name = '".$fac['name']."' AND day = '$day_name' AND period_number = $period_number")->fetch_assoc();
                if ($check['c'] == 0) {
                    $available_faculty[] = $fac_id;
                }
            }

            // If no preferred faculty, search globally anyone free
            if (empty($available_faculty)) {
                $res = $conn->query("SELECT faculty_id, name FROM faculty WHERE faculty_id != $faculty_id");
                while ($fac = $res->fetch_assoc()) {
                    $fac_id = $fac['faculty_id'];
                    $check = $conn->query("SELECT COUNT(*) as c FROM final_timetable WHERE faculty_name = '".$fac['name']."' AND day = '$day_name' AND period_number = $period_number")->fetch_assoc();
                    if ($check['c'] == 0) {
                        $available_faculty[] = $fac_id;
                    }
                }
            }

            // 5. If found, assign one
            if (!empty($available_faculty)) {
                $substitute_id = $available_faculty[array_rand($available_faculty)]; // Pick randomly
                $conn->query("INSERT INTO substitution_assignments
                            (leave_id, date, day, period_number, section, semester_id, original_faculty_id, substitute_faculty_id, subject_name)
                            VALUES
                            ($leave_id, '$date_str', '$day_name', $period_number, '$section', $semester_id, $faculty_id, $substitute_id, '$subject_name')
                ");
            }
        }

        $current = strtotime("+1 day", $current);
    }
}
?>
