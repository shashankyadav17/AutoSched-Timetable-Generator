<?php
include '../db.php';

function getGeneralSettings() {
    $res = mysqli_query($GLOBALS['conn'], "SELECT * FROM general_settings LIMIT 1");
    $settings = mysqli_fetch_assoc($res);
    $settings['working_days'] = explode(',', $settings['working_days']);
    return $settings;
}

function getSubjectsWithFaculty($semester_id) {
    $subjects = [];
    $q = "
        SELECT s.subject_name AS name, s.subject_id, ss.subject_type, ss.lectures_per_week, fa.faculty_id, fa.section 
        FROM subjects s 
        JOIN subject_settings ss ON s.subject_id = ss.subject_id AND ss.semester_id = $semester_id
        JOIN faculty_allotment fa ON s.subject_id = fa.subject_id AND fa.semester_id = $semester_id
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

function placeLabInGrid(&$grid, &$facultyBusySlots, $subject, $settings, &$sectionLabsPerDay) {
    $placed = false;
    $days = $settings['working_days'];
    $ppd = $settings['periods_per_day'];

    foreach ($days as $day) {
        // Ensure only one lab per day
        if (isset($sectionLabsPerDay[$day]) && $sectionLabsPerDay[$day] >= 1) {
            continue;
        }

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
                    $sectionLabsPerDay[$day] = ($sectionLabsPerDay[$day] ?? 0) + 1; // Track labs per day
                    $placed = true;
                    break 2;
                }
            }
        }
    }
    return $placed;
}

function placeTheoryInGrid(&$grid, &$facultyBusySlots, $subject, $settings, &$sectionSubjectCountPerDay) {
    $count = 0;
    $max = $subject['lectures_per_week'];
    $days = $settings['working_days'];
    $ppd = $settings['periods_per_day'];

    foreach ($days as $day) {
        // Ensure a subject is scheduled at most twice per day
        if (isset($sectionSubjectCountPerDay[$day][$subject['name']]) && $sectionSubjectCountPerDay[$day][$subject['name']] >= 2) {
            continue;
        }

        for ($p = 1; $p <= $ppd; $p++) {
            if ($grid[$day][$p] === null &&
                facultyAvailable($facultyBusySlots, $subject['faculty_id'], $day, [$p])) {

                if ($p >= 2 && isset($grid[$day][$p - 1]) && $grid[$day][$p - 1]['subject'] == $subject['name']) {
                    $prev = isset($grid[$day][$p - 2]) ? $grid[$day][$p - 2]['subject'] ?? '' : '';
                    if ($prev == $subject['name']) continue;
                }

                $grid[$day][$p] = [
                    'subject' => $subject['name'],
                    'faculty' => $subject['faculty_id'],
                    'type' => 'Theory'
                ];
                $facultyBusySlots[$subject['faculty_id']][$day][$p] = true;
                $sectionSubjectCountPerDay[$day][$subject['name']] = ($sectionSubjectCountPerDay[$day][$subject['name']] ?? 0) + 1; // Track subject count per day
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
        $sectionLabsPerDay = []; // Track labs per day
        $sectionSubjectCountPerDay = []; // Track subject counts per day

        $sectionSubjects = array_filter($subjects, fn($s) => $s['section'] === $section);

        foreach ($sectionSubjects as $subj) {
            if (strtolower($subj['subject_type']) === 'lab') {
                placeLabInGrid($grid, $facultyBusySlots, $subj, $settings, $sectionLabsPerDay);
            }
        }

        foreach ($sectionSubjects as $subj) {
            if (strtolower($subj['subject_type']) === 'theory') {
                placeTheoryInGrid($grid, $facultyBusySlots, $subj, $settings, $sectionSubjectCountPerDay);
            }
        }

        $timetable[$section] = $grid;
    }

    return $timetable;
}
?>