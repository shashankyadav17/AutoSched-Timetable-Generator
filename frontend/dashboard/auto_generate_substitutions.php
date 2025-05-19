<?php
require_once '../db.php';



function assign_substitutes_for_leave($leave_id) {
    global $conn;

    function find_available_substitutes($section, $semester_id, $day_name, $period_number, $exclude_faculty_ids) {
        global $conn;
        $available_faculty = [];

        // Prefer faculty teaching same section and semester
        $placeholders = implode(',', array_map('intval', $exclude_faculty_ids));
        $q = "SELECT DISTINCT f.faculty_id, f.name
              FROM faculty_allotment fa
              JOIN faculty f ON fa.faculty_id = f.faculty_id
              WHERE fa.section = '$section' AND fa.semester_id = $semester_id
              AND f.faculty_id NOT IN ($placeholders)";
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
            $placeholders = implode(',', array_map('intval', $exclude_faculty_ids));
            $res = $conn->query("SELECT faculty_id, name FROM faculty WHERE faculty_id NOT IN ($placeholders)");
            while ($fac = $res->fetch_assoc()) {
                $fac_id = $fac['faculty_id'];
                $check = $conn->query("SELECT COUNT(*) as c FROM final_timetable WHERE faculty_name = '".$fac['name']."' AND day = '$day_name' AND period_number = $period_number")->fetch_assoc();
                if ($check['c'] == 0) {
                    $available_faculty[] = $fac_id;
                }
            }
        }
        return $available_faculty;
    }

    // Recursive function to assign substitutes for a faculty on a given date
    function assign_substitutes_for_faculty_on_date($faculty_id, $date_str, $day_name, $leave_id, &$processed_faculty_dates) {
        global $conn;

        $key = $faculty_id . '_' . $date_str;
        if (isset($processed_faculty_dates[$key])) {
            // Already processed this faculty on this date to avoid infinite recursion
            return;
        }
        $processed_faculty_dates[$key] = true;

        // Fetch classes where this faculty teaches on that day
        $sql = "SELECT * FROM final_timetable WHERE faculty_name = (
                    SELECT name FROM faculty WHERE faculty_id = $faculty_id
                ) AND day = '$day_name'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $period_number = $row['period_number'];
            $section = $row['section'];
            $semester_id = $row['semester_id'];
            $subject_name = $row['subject_name'];

            // Find available substitute faculty for this slot excluding current faculty
            $available_faculty = find_available_substitutes($section, $semester_id, $day_name, $period_number, [$faculty_id]);

            if (!empty($available_faculty)) {
                $substitute_id = $available_faculty[array_rand($available_faculty)]; // Pick randomly
                $conn->query("INSERT INTO substitution_assignments
                            (leave_id, date, day, period_number, section, semester_id, original_faculty_id, substitute_faculty_id, subject_name)
                            VALUES
                            ($leave_id, '$date_str', '$day_name', $period_number, '$section', $semester_id, $faculty_id, $substitute_id, '$subject_name')
                ");

                // Get names for notification
                $original_faculty = $conn->query("SELECT name FROM faculty WHERE faculty_id = $faculty_id")->fetch_assoc()['name'];
                $substitute_faculty = $conn->query("SELECT name FROM faculty WHERE faculty_id = $substitute_id")->fetch_assoc()['name'];

                // Escape variables
                $esc_date_str = $conn->real_escape_string($date_str);
                $esc_subject_name = $conn->real_escape_string($subject_name);
                $esc_section = $conn->real_escape_string($section);
                $esc_original_faculty = $conn->real_escape_string($original_faculty);
                $esc_substitute_faculty = $conn->real_escape_string($substitute_faculty);

                // Message for original faculty
                $msg1 = "✅ Your leave on $esc_date_str (Period $period_number) for $esc_subject_name has been approved and a substitute has been assigned.";
                $conn->query("INSERT INTO notifications (user_type, user_id, message)
                VALUES ('faculty', $faculty_id, '$msg1')");

                // Message for substitute faculty
                $msg2 = "📌 You have been assigned a substitution for $esc_subject_name ($esc_section-$semester_id) on $esc_date_str during Period $period_number.";
                $conn->query("INSERT INTO notifications (user_type, user_id, message)
                            VALUES ('faculty', $substitute_id, '$msg2')");

                // Notify students once per semester and section
                $msg3 = "ℹ️ Your teacher $esc_original_faculty is on leave on $esc_date_str during Period $period_number for $esc_subject_name. $esc_substitute_faculty will take the class.";

                // Check if notification already exists for this semester, section, period, date, and message to avoid duplicates
                $check_query = "SELECT COUNT(*) as c FROM notifications WHERE user_type = 'student' AND semester_id = $semester_id AND section = '$esc_section' AND message = '".$conn->real_escape_string($msg3)."'";
                $check_res = $conn->query($check_query);
                $count = $check_res->fetch_assoc()['c'];

                if ($count == 0) {
                    $conn->query("INSERT INTO notifications (user_type, user_id, message, semester_id, section)
                    VALUES ('student', NULL, '$msg3', $semester_id, '$esc_section')");
                }
            }
        }

        // Now handle re-substitution of substitute classes assigned to this faculty on this date
        $sub_query = "SELECT * FROM substitution_assignments WHERE substitute_faculty_id = $faculty_id AND date = '$date_str'";
        $sub_result = $conn->query($sub_query);
        while ($sub_row = $sub_result->fetch_assoc()) {
            $period_number = $sub_row['period_number'];
            $section = $sub_row['section'];
            $semester_id = $sub_row['semester_id'];
            $subject_name = $sub_row['subject_name'];
            $original_faculty_id = $sub_row['original_faculty_id'];
            $leave_id_sub = $sub_row['leave_id'];

            // Find available substitute faculty for this slot excluding current faculty
            $available_faculty = find_available_substitutes($section, $semester_id, $day_name, $period_number, [$faculty_id]);

            if (!empty($available_faculty)) {
                $new_substitute_id = $available_faculty[array_rand($available_faculty)]; // Pick randomly

                // Update substitution_assignments table with new substitute
                $conn->query("UPDATE substitution_assignments SET substitute_faculty_id = $new_substitute_id WHERE leave_id = $leave_id_sub AND date = '$date_str' AND period_number = $period_number AND section = '$section' AND semester_id = $semester_id");

                // Get names for notification
                $original_faculty = $conn->query("SELECT name FROM faculty WHERE faculty_id = $original_faculty_id")->fetch_assoc()['name'];
                $new_substitute_faculty = $conn->query("SELECT name FROM faculty WHERE faculty_id = $new_substitute_id")->fetch_assoc()['name'];

                // Escape variables
                $esc_date_str = $conn->real_escape_string($date_str);
                $esc_subject_name = $conn->real_escape_string($subject_name);
                $esc_section = $conn->real_escape_string($section);
                $esc_original_faculty = $conn->real_escape_string($original_faculty);
                $esc_new_substitute_faculty = $conn->real_escape_string($new_substitute_faculty);

                // Message for new substitute faculty
                $msg2 = "📌 You have been assigned a substitution for $esc_subject_name ($esc_section-$semester_id) on $esc_date_str during Period $period_number.";
                $conn->query("INSERT INTO notifications (user_type, user_id, message)
                            VALUES ('faculty', $new_substitute_id, '$msg2')");

                // Notify students once per semester and section
                $msg3 = "ℹ️ Your teacher $esc_original_faculty is on leave on $esc_date_str during Period $period_number for $esc_subject_name. $esc_new_substitute_faculty will take the class.";

                // Check if notification already exists for this semester, section, period, date, and message to avoid duplicates
                $check_query = "SELECT COUNT(*) as c FROM notifications WHERE user_type = 'student' AND semester_id = $semester_id AND section = '$esc_section' AND message = '".$conn->real_escape_string($msg3)."'";
                $check_res = $conn->query($check_query);
                $count = $check_res->fetch_assoc()['c'];

                if ($count == 0) {
                    $conn->query("INSERT INTO notifications (user_type, user_id, message, semester_id, section)
                    VALUES ('student', NULL, '$msg3', $semester_id, '$esc_section')");
                }

                // Check if the new substitute faculty is also on leave on this date, recursively assign substitutes
                $leave_check = $conn->query("SELECT leave_id FROM faculty_leaves WHERE faculty_id = $new_substitute_id AND start_date <= '$date_str' AND end_date >= '$date_str'")->fetch_assoc();
                if ($leave_check) {
                    assign_substitutes_for_faculty_on_date($new_substitute_id, $date_str, $day_name, $leave_check['leave_id'], $processed_faculty_dates);
                }
            }
        }
    }

    // Main function logic
    $leave = $conn->query("SELECT * FROM faculty_leaves WHERE leave_id = $leave_id")->fetch_assoc();
    if (!$leave) {
        return;
    }
    $faculty_id = $leave['faculty_id'];
    $start_date = $leave['start_date'];
    $end_date = $leave['end_date'];
    $days_of_week = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    $current = strtotime($start_date);
    $end = strtotime($end_date);

    $processed_faculty_dates = [];

    while ($current <= $end) {
        $day_name = $days_of_week[date('w', $current)];
        if ($day_name == "Sunday") {
            $current = strtotime("+1 day", $current);
            continue;
        }
        $date_str = date('Y-m-d', $current);

        assign_substitutes_for_faculty_on_date($faculty_id, $date_str, $day_name, $leave_id, $processed_faculty_dates);

        $current = strtotime("+1 day", $current);
    }
}
?>