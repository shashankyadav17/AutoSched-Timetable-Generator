<?php
include '../db.php';

function getGeneralSettings() {
    $res = mysqli_query($GLOBALS['conn'], "SELECT * FROM general_settings LIMIT 1");
    $settings = mysqli_fetch_assoc($res);
    
    // Convert comma-separated working days into array
    $settings['working_days'] = explode(',', $settings['working_days']);

    return $settings;
}


function getSubjectsWithFaculty($semester_id) {
    $subjects = [];
    $q = "SELECT s.*, fa.faculty_id, fa.section 
          FROM subjects s 
          JOIN faculty_allotment fa ON s.subject_id = fa.subject_id 
          WHERE fa.semester_id = $semester_id
        ";
    $res = mysqli_query($GLOBALS['conn'], $q);
    while ($row = mysqli_fetch_assoc($res)) {
        $subjects[] = $row;
    }
    return $subjects;
}

function initEmptyGrid($days, $periods_per_day) {
    $grid = [];
    foreach ($days as $day) {
        for ($p = 1; $p <= $periods_per_day; $p++) {
            $grid[$day][$p] = null;
        }
    }
    return $grid;
}

function facultyAvailable($facultyBusySlots, $faculty_id, $day, $periods) {
    foreach ($periods as $p) {
        if (isset($facultyBusySlots[$faculty_id][$day][$p])) return false;
    }
    return true;
}

function placeLabInGrid(&$grid, &$facultyBusySlots, $subject, $settings) {
    $placed = false;
    $days = $settings['working_days'];
    $ppd = $settings['periods_per_day'];

    foreach ($days as $day) {
        for ($p = 1; $p <= $ppd - 2; $p++) {
            $periods = [$p, $p + 1, $p + 2];
            if ($grid[$day][$p] === null && $grid[$day][$p + 1] === null && $grid[$day][$p + 2] === null) {
                if (facultyAvailable($facultyBusySlots, $subject['faculty_id'], $day, $periods)) {
                    foreach ($periods as $period) {
                        $grid[$day][$period] = [
                            'subject' => $subject['name'],
                            'faculty' => $subject['faculty_id'],
                            'type' => 'Lab'
                        ];
                        $facultyBusySlots[$subject['faculty_id']][$day][$period] = true;
                    }
                    $placed = true;
                    break 2;
                }
            }
        }
    }
    return $placed;
}

function placeTheoryInGrid(&$grid, &$facultyBusySlots, $subject, $settings) {
    $count = 0;
    $max = $subject['lectures_per_week'];
    $days = $settings['working_days'];
    $ppd = $settings['periods_per_day'];

    foreach ($days as $day) {
        for ($p = 1; $p <= $ppd; $p++) {
            if ($grid[$day][$p] === null &&
                facultyAvailable($facultyBusySlots, $subject['faculty_id'], $day, [$p])) {

                // Avoid >2 consecutive same subject
                if ($p >= 2 && isset($grid[$day][$p - 1]) && $grid[$day][$p - 1]['subject'] == $subject['name']) {
                    $prev = isset($grid[$day][$p - 2]) ? $grid[$day][$p - 2]['subject'] ?? '' : '';
                    if ($prev == $subject['name']) continue; // would make 3 in a row
                }

                $grid[$day][$p] = [
                    'subject' => $subject['name'],
                    'faculty' => $subject['faculty_id'],
                    'type' => 'Theory'
                ];
                $facultyBusySlots[$subject['faculty_id']][$day][$p] = true;
                $count++;
                if ($count == $max) return true;
            }
        }
    }
    return false;
}

function generateTimetable($semester_id) {
    $settings = getGeneralSettings();
    $subjects = getSubjectsWithFaculty($semester_id);
    $sections = ['A', 'B', 'C'];
    $timetable = [];

    foreach ($sections as $section) {
        $grid = initEmptyGrid($settings['working_days'], $settings['periods_per_day']);
        $facultyBusySlots = [];

        // Get only this section's subjects
        $sectionSubjects = array_filter($subjects, fn($s) => $s['section'] === $section);

        // Place Labs first
        foreach ($sectionSubjects as $subj) {
            if (strtolower($subj['type']) === 'lab') {
                placeLabInGrid($grid, $facultyBusySlots, $subj, $settings);
            }
        }

        // Then place Theory
        foreach ($sectionSubjects as $subj) {
            if (strtolower($subj['type']) === 'theory') {
                placeTheoryInGrid($grid, $facultyBusySlots, $subj, $settings);
            }
        }

        $timetable[$section] = $grid;
    }

    return $timetable;
}
?>
