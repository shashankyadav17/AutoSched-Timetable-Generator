-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 12:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autosched`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `name`) VALUES
(1, 'admin', 'admin@123', 'Main Admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `message`, `year`, `semester`, `created_at`) VALUES
(1, 'Faculty allotted for 2-1', 2, 1, '2025-04-15 18:38:44'),
(2, 'Faculty allotted for 3-1', 3, 1, '2025-04-17 17:04:40'),
(3, 'Faculty allotted for 2-1', 2, 1, '2025-05-19 18:22:35'),
(4, 'Faculty allotted for 3-1', 3, 1, '2025-05-19 18:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `total_hours` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `employee_id`, `name`, `email`, `total_hours`) VALUES
(1074, 390, 'Dr. Y J Nagendra Kumar', 'nagendra.griet@example.com', 0),
(1075, 525, 'Dr. K. Prasanna Lakshmi', 'prasanna.griet@example.com', 0),
(1076, 112, 'Dr. Y Srilalitha', 'srilalitha.griet@example.com', 0),
(1077, 118, 'Dr. V N Ganapathi Raju', 'ganapathi.griet@example.com', 0),
(1078, 1581, 'Dr. N Rajasekhar', 'rajasekhar.griet@example.com', 0),
(1079, 558, 'Sri. P Gopala Krishna', 'gopalakrishna.griet@example.com', 0),
(1080, 559, 'Sri. G Vijendar Reddy', 'vijendarreddy.griet@example.com', 0),
(1081, 1584, 'Dr. V Akila', 'akila.griet@example.com', 0),
(1082, 1606, 'Dr. K Ravi Kiran', 'ravikiran.griet@example.com', 0),
(1083, 1603, 'Dr. Ch Vidyadhari', 'vidyadhari.griet@example.com', 0),
(1084, 1755, 'Dr. R V S S Naigi', 'naigi.griet@example.com', 0),
(1085, 802, 'Mrs. M Pavithra', 'pavithra.griet@example.com', 0),
(1086, 845, 'Mrs. T N P Madhuri', 'madhuri.tnp.griet@example.com', 0),
(1087, 848, 'Mr. P K Abhilash', 'abhilash.griet@example.com', 0),
(1088, 914, 'Mr. K Sandeep', 'sandeep.griet@example.com', 0),
(1089, 1284, 'Mrs. P Bharathi', 'bharathi.griet@example.com', 0),
(1090, 1602, 'Mrs. P Prathibha Swaraj', 'prathibha.griet@example.com', 0),
(1091, 1611, 'Mrs. A Srilakshmi', 'srilakshmi.griet@example.com', 0),
(1092, 1644, 'Mrs. N Mounika', 'mounika.griet@example.com', 0),
(1093, 1652, 'Dr. G Prasanthi', 'prasanthi.griet@example.com', 0),
(1094, 1709, 'Mrs. V Sailaja', 'sailaja.griet@example.com', 0),
(1095, 1710, 'Mrs. D Preethi', 'preethi.griet@example.com', 0),
(1096, 1721, 'Mrs. G D Vijaya Lakshmi', 'vijayalakshmi.griet@example.com', 0),
(1097, 1746, 'Mrs. M Kanaka Durga Bella', 'kanakadurgabella.griet@example.com', 0),
(1098, 1768, 'Mrs. Y Prasanna', 'yprasanna.griet@example.com', 0),
(1099, 1778, 'Mrs. M Usha Rani', 'usharani.griet@example.com', 0),
(1100, 1781, 'Mrs. A Vani Pushpavathi', 'vani.griet@example.com', 0),
(1101, 1783, 'Mrs. K Naga Manasa', 'manasa.kn.griet@example.com', 0),
(1102, 1790, 'Ms. Alekya', 'alekya.griet@example.com', 0),
(1103, 1795, 'Mrs. R Madhuri', 'rmadhuri.griet@example.com', 0),
(1104, 1799, 'K Manasa', 'manasa.k.griet@example.com', 0),
(1105, 1800, 'Mrs. Vemula Divya', 'divya.griet@example.com', 0),
(1106, 1801, 'Mrs. T. Nishitha', 'nishitha.griet@example.com', 0),
(1107, 1810, 'Ms. I Laxmi Bindu', 'laxmibindu.griet@example.com', 0),
(1108, 1814, 'Mrs.Chilukuri Sujatha', 'sujatha.griet@example.com', 0),
(1109, 1821, 'Mrs.K.Bhavani', 'bhavani.griet@example.com', 0),
(1110, 1823, 'Mrs.L.Hema Lalitha', 'hemalalitha.griet@example.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_allotment`
--

CREATE TABLE `faculty_allotment` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section` char(1) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_allotment`
--

INSERT INTO `faculty_allotment` (`id`, `subject_id`, `section`, `faculty_id`, `semester_id`) VALUES
(192, 1, 'A', 1074, 1),
(193, 1, 'B', 1074, 1),
(194, 1, 'C', 1098, 1),
(195, 2, 'A', 1075, 1),
(196, 2, 'B', 1075, 1),
(197, 2, 'C', 1074, 1),
(198, 3, 'A', 1090, 1),
(199, 3, 'B', 1090, 1),
(200, 3, 'C', 1075, 1),
(201, 4, 'A', 1076, 1),
(202, 4, 'B', 1106, 1),
(203, 4, 'C', 1077, 1),
(204, 5, 'A', 1079, 1),
(205, 5, 'B', 1076, 1),
(206, 5, 'C', 1079, 1),
(207, 6, 'A', 1087, 1),
(208, 6, 'B', 1110, 1),
(209, 6, 'C', 1087, 1),
(210, 7, 'A', 1092, 1),
(211, 7, 'B', 1107, 1),
(212, 7, 'C', 1091, 1),
(213, 8, 'A', 1077, 1),
(214, 8, 'B', 1097, 1),
(215, 8, 'C', 1107, 1),
(216, 9, 'A', 1081, 1),
(217, 9, 'B', 1081, 1),
(218, 9, 'C', 1092, 1),
(219, 21, 'A', 1086, 3),
(220, 21, 'B', 1096, 3),
(221, 21, 'C', 1105, 3),
(222, 22, 'A', 1087, 3),
(223, 22, 'B', 1095, 3),
(224, 22, 'C', 1095, 3),
(225, 23, 'A', 1080, 3),
(226, 23, 'B', 1093, 3),
(227, 23, 'C', 1104, 3),
(228, 24, 'A', 1096, 3),
(229, 24, 'B', 1081, 3),
(230, 24, 'C', 1096, 3),
(231, 25, 'A', 1105, 3),
(232, 25, 'B', 1085, 3),
(233, 25, 'C', 1085, 3),
(234, 26, 'A', 1091, 3),
(235, 26, 'B', 1099, 3),
(236, 26, 'C', 1103, 3),
(237, 27, 'A', 1090, 3),
(238, 27, 'B', 1106, 3),
(239, 27, 'C', 1106, 3),
(240, 28, 'A', 1097, 3),
(241, 28, 'B', 1097, 3),
(242, 28, 'C', 1090, 3),
(243, 29, 'A', 1100, 3),
(244, 29, 'B', 1100, 3),
(245, 29, 'C', 1091, 3);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_leaves`
--

CREATE TABLE `faculty_leaves` (
  `leave_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `applied_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_leaves`
--

INSERT INTO `faculty_leaves` (`leave_id`, `faculty_id`, `start_date`, `end_date`, `reason`, `status`, `applied_on`) VALUES
(3, 1074, '2025-04-29', '2025-04-30', 'health issues', 'Approved', '2025-04-28 18:25:38'),
(4, 1074, '2025-04-30', '2025-04-30', 'fever', 'Approved', '2025-04-29 10:13:01'),
(5, 1074, '2025-04-30', '2025-04-30', 'Fever', 'Approved', '2025-04-29 16:04:04'),
(6, 1075, '2025-05-09', '2025-05-10', 'cold', 'Approved', '2025-05-08 15:00:29'),
(7, 1074, '2025-05-13', '2025-05-13', 'fever', 'Approved', '2025-05-12 07:12:45'),
(8, 1074, '2025-05-20', '2025-05-20', 'headache', 'Approved', '2025-05-19 09:23:09'),
(9, 1074, '2025-05-21', '2025-05-21', 'cold', 'Approved', '2025-05-19 09:37:15'),
(10, 1074, '2025-05-21', '2025-05-21', 'fever', 'Approved', '2025-05-19 12:59:51'),
(11, 1074, '2025-05-21', '2025-05-21', 'fever', 'Approved', '2025-05-19 13:32:26'),
(12, 1074, '2025-05-21', '2025-05-21', '', 'Approved', '2025-05-19 13:35:55'),
(13, 1087, '2025-05-21', '2025-05-21', '', 'Approved', '2025-05-19 14:07:01'),
(14, 1074, '2025-05-21', '2025-05-21', '', 'Approved', '2025-05-19 14:38:39'),
(15, 1081, '2025-05-21', '2025-05-21', '', 'Approved', '2025-05-19 14:40:05'),
(16, 1074, '2025-05-21', '2025-05-21', 'cold', 'Approved', '2025-05-19 17:44:58'),
(17, 1074, '2025-05-22', '2025-05-22', 'Cold and fever', 'Approved', '2025-05-19 18:27:43'),
(18, 1074, '2025-05-22', '2025-05-22', 'cold', 'Approved', '2025-05-20 08:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_subjects`
--

CREATE TABLE `faculty_subjects` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_subjects`
--

INSERT INTO `faculty_subjects` (`id`, `faculty_id`, `subject_id`) VALUES
(1, 1074, 1),
(2, 1074, 2),
(3, 1075, 2),
(4, 1075, 3),
(5, 1076, 5),
(6, 1076, 4),
(7, 1077, 4),
(8, 1077, 8),
(9, 1078, 13),
(10, 1078, 18),
(11, 1079, 19),
(12, 1079, 31),
(13, 1080, 23),
(14, 1080, 33),
(15, 1083, 12),
(16, 1083, 34),
(17, 1084, 10),
(18, 1084, 17),
(19, 1085, 25),
(20, 1085, 32),
(21, 1086, 37),
(22, 1086, 21),
(23, 1087, 22),
(24, 1087, 35),
(25, 1088, 12),
(26, 1088, 16),
(27, 1089, 10),
(28, 1089, 11),
(29, 1090, 28),
(30, 1090, 27),
(31, 1091, 29),
(32, 1091, 26),
(33, 1092, 17),
(34, 1092, 7),
(35, 1093, 23),
(36, 1093, 30),
(37, 1094, 33),
(38, 1094, 32),
(39, 1095, 25),
(40, 1095, 36),
(41, 1096, 20),
(42, 1096, 21),
(43, 1097, 13),
(44, 1097, 8),
(45, 1098, 10),
(46, 1098, 1),
(47, 1099, 26),
(48, 1099, 31),
(49, 1100, 10),
(50, 1100, 29),
(51, 1101, 33),
(52, 1101, 32),
(53, 1102, 37),
(54, 1102, 20),
(55, 1103, 26),
(56, 1103, 35),
(57, 1104, 23),
(58, 1104, 36),
(59, 1105, 21),
(60, 1105, 25),
(61, 1106, 4),
(62, 1106, 27),
(63, 1107, 8),
(64, 1107, 7),
(65, 1108, 35),
(66, 1108, 20),
(67, 1109, 32),
(68, 1109, 33),
(71, 1110, 37),
(72, 1110, 21),
(73, 1081, 9),
(74, 1081, 24),
(75, 1082, 11),
(76, 1082, 18),
(77, 1090, 3),
(78, 1091, 7),
(79, 1092, 9),
(80, 1093, 16),
(81, 1094, 19),
(82, 1095, 22),
(83, 1096, 24),
(84, 1097, 28),
(85, 1098, 30),
(86, 1099, 34),
(87, 1087, 6),
(88, 1110, 6),
(89, 1088, 14),
(90, 1106, 14),
(91, 1089, 15),
(92, 1108, 15),
(93, 1079, 5);

-- --------------------------------------------------------

--
-- Table structure for table `final_timetable`
--

CREATE TABLE `final_timetable` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `day` varchar(20) NOT NULL,
  `period_number` int(11) NOT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `faculty_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `final_timetable`
--

INSERT INTO `final_timetable` (`id`, `semester_id`, `section`, `day`, `period_number`, `subject_name`, `faculty_name`, `created_at`) VALUES
(3811, 1, 'A', 'Monday', 1, 'Database Management Systems', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3812, 1, 'A', 'Monday', 2, 'Database Management Systems Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3813, 1, 'A', 'Monday', 3, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3814, 1, 'A', 'Monday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3815, 1, 'A', 'Monday', 4, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3816, 1, 'A', 'Monday', 5, 'Java Programming Lab', 'Mrs. N Mounika', '2025-05-20 08:10:29'),
(3817, 1, 'A', 'Monday', 6, 'Database Management Systems Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3818, 1, 'A', 'Monday', 7, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3819, 1, 'A', 'Wednesday', 1, 'Database Management Systems', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3820, 1, 'A', 'Wednesday', 2, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3821, 1, 'A', 'Wednesday', 3, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3822, 1, 'A', 'Wednesday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3823, 1, 'A', 'Wednesday', 4, 'Java Programming Lab', 'Mrs. N Mounika', '2025-05-20 08:10:29'),
(3824, 1, 'A', 'Wednesday', 5, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3825, 1, 'A', 'Wednesday', 6, 'Digital Electronics Lab', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3826, 1, 'A', 'Wednesday', 7, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3827, 1, 'A', 'Thursday', 1, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3828, 1, 'A', 'Thursday', 2, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3829, 1, 'A', 'Thursday', 3, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3830, 1, 'A', 'Thursday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3831, 1, 'A', 'Thursday', 4, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3832, 1, 'A', 'Thursday', 5, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3833, 1, 'A', 'Thursday', 6, 'Digital Electronics Lab', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3834, 1, 'A', 'Thursday', 7, 'Database Management Systems Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3835, 1, 'A', 'Friday', 1, 'Value Ethics and Gender Culture', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3836, 1, 'A', 'Friday', 2, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3837, 1, 'A', 'Friday', 3, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3838, 1, 'A', 'Friday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3839, 1, 'A', 'Friday', 4, 'Digital Electronics Lab', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3840, 1, 'A', 'Friday', 5, 'Database Management Systems', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3841, 1, 'A', 'Friday', 6, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3842, 1, 'A', 'Friday', 7, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3843, 1, 'A', 'Saturday', 1, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3844, 1, 'A', 'Saturday', 2, 'Database Management Systems', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3845, 1, 'A', 'Saturday', 3, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3846, 1, 'A', 'Saturday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3847, 1, 'A', 'Saturday', 4, 'Database Management Systems', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3848, 1, 'A', 'Saturday', 5, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3849, 1, 'A', 'Saturday', 6, 'Java Programming Lab', 'Mrs. N Mounika', '2025-05-20 08:10:29'),
(3850, 1, 'A', 'Saturday', 7, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3851, 1, 'B', 'Monday', 1, 'Probability and Statistics', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3852, 1, 'B', 'Monday', 2, 'Value Ethics and Gender Culture', 'Mrs. N Mounika', '2025-05-20 08:10:29'),
(3853, 1, 'B', 'Monday', 3, 'Probability and Statistics', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3854, 1, 'B', 'Monday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3855, 1, 'B', 'Monday', 4, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3856, 1, 'B', 'Monday', 5, 'Discrete Mathematics', 'Sri. P Gopala Krishna', '2025-05-20 08:10:29'),
(3857, 1, 'B', 'Monday', 6, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3858, 1, 'B', 'Monday', 7, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3859, 1, 'B', 'Wednesday', 1, 'Probability and Statistics', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3860, 1, 'B', 'Wednesday', 2, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3861, 1, 'B', 'Wednesday', 3, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3862, 1, 'B', 'Wednesday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3863, 1, 'B', 'Wednesday', 4, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3864, 1, 'B', 'Wednesday', 5, 'Database Management Systems', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3865, 1, 'B', 'Wednesday', 6, 'Database Management Systems', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3866, 1, 'B', 'Wednesday', 7, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3867, 1, 'B', 'Thursday', 1, 'Probability and Statistics', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3868, 1, 'B', 'Thursday', 2, 'Java Programming Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3869, 1, 'B', 'Thursday', 3, 'Database Management Systems', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3870, 1, 'B', 'Thursday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3871, 1, 'B', 'Thursday', 4, 'Database Management Systems', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3872, 1, 'B', 'Thursday', 5, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3873, 1, 'B', 'Thursday', 6, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3874, 1, 'B', 'Thursday', 7, 'Discrete Mathematics', 'Sri. P Gopala Krishna', '2025-05-20 08:10:29'),
(3875, 1, 'B', 'Friday', 1, 'Digital Logic Design', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3876, 1, 'B', 'Friday', 2, 'Discrete Mathematics', 'Sri. P Gopala Krishna', '2025-05-20 08:10:29'),
(3877, 1, 'B', 'Friday', 3, 'Discrete Mathematics', 'Sri. P Gopala Krishna', '2025-05-20 08:10:29'),
(3878, 1, 'B', 'Friday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3879, 1, 'B', 'Friday', 4, 'Database Management Systems Lab', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3880, 1, 'B', 'Friday', 5, 'Database Management Systems Lab', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3881, 1, 'B', 'Friday', 6, 'Database Management Systems', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3882, 1, 'B', 'Friday', 7, 'Probability and Statistics', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3883, 1, 'B', 'Saturday', 1, 'Database Management Systems Lab', 'Dr. V N Ganapathi Raju', '2025-05-20 08:10:29'),
(3884, 1, 'B', 'Saturday', 2, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3885, 1, 'B', 'Saturday', 3, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3886, 1, 'B', 'Saturday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3887, 1, 'B', 'Saturday', 4, 'Discrete Mathematics', 'Sri. P Gopala Krishna', '2025-05-20 08:10:29'),
(3888, 1, 'B', 'Saturday', 5, 'Java Programming', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3889, 1, 'B', 'Saturday', 6, 'Java Programming Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3890, 1, 'B', 'Saturday', 7, 'Java Programming Lab', 'Ms. I Laxmi Bindu', '2025-05-20 08:10:29'),
(3891, 1, 'C', 'Monday', 1, 'Java Programming', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3892, 1, 'C', 'Monday', 2, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3893, 1, 'C', 'Monday', 3, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3894, 1, 'C', 'Monday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3895, 1, 'C', 'Monday', 4, 'Java Programming Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3896, 1, 'C', 'Monday', 5, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3897, 1, 'C', 'Monday', 6, 'Java Programming Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3898, 1, 'C', 'Monday', 7, 'Database Management Systems', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3899, 1, 'C', 'Wednesday', 1, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3900, 1, 'C', 'Wednesday', 2, 'Database Management Systems', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3901, 1, 'C', 'Wednesday', 3, 'Java Programming', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3902, 1, 'C', 'Wednesday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3903, 1, 'C', 'Wednesday', 4, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3904, 1, 'C', 'Wednesday', 5, 'Database Management Systems Lab', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3905, 1, 'C', 'Wednesday', 6, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3906, 1, 'C', 'Wednesday', 7, 'Digital Logic Design', 'Mrs. Y Prasanna', '2025-05-20 08:10:29'),
(3907, 1, 'C', 'Thursday', 1, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3908, 1, 'C', 'Thursday', 2, 'Digital Electronics Lab', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3909, 1, 'C', 'Thursday', 3, 'Database Management Systems', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3910, 1, 'C', 'Thursday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3911, 1, 'C', 'Thursday', 4, 'Java Programming', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3912, 1, 'C', 'Thursday', 5, 'Database Management Systems', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3913, 1, 'C', 'Thursday', 6, 'Digital Logic Design', 'Mrs. Y Prasanna', '2025-05-20 08:10:29'),
(3914, 1, 'C', 'Thursday', 7, 'Digital Logic Design', 'Mrs. Y Prasanna', '2025-05-20 08:10:29'),
(3915, 1, 'C', 'Friday', 1, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3916, 1, 'C', 'Friday', 2, 'Value Ethics and Gender Culture', 'Mrs. N Mounika', '2025-05-20 08:10:29'),
(3917, 1, 'C', 'Friday', 3, 'Digital Logic Design', 'Mrs. Y Prasanna', '2025-05-20 08:10:29'),
(3918, 1, 'C', 'Friday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3919, 1, 'C', 'Friday', 4, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3920, 1, 'C', 'Friday', 5, 'Probability and Statistics', 'Dr. K. Prasanna Lakshmi', '2025-05-20 08:10:29'),
(3921, 1, 'C', 'Friday', 6, 'Java Programming Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3922, 1, 'C', 'Friday', 7, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3923, 1, 'C', 'Saturday', 1, 'Java Programming', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3924, 1, 'C', 'Saturday', 2, 'Database Management Systems Lab', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3925, 1, 'C', 'Saturday', 3, 'Digital Logic Design', 'Mrs. Y Prasanna', '2025-05-20 08:10:29'),
(3926, 1, 'C', 'Saturday', 4, 'Break', '', '2025-05-20 08:10:29'),
(3927, 1, 'C', 'Saturday', 4, 'Database Management Systems Lab', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3928, 1, 'C', 'Saturday', 5, 'Discrete Mathematics', 'Dr. Y Srilalitha', '2025-05-20 08:10:29'),
(3929, 1, 'C', 'Saturday', 6, 'Java Programming', 'Dr. Y J Nagendra Kumar', '2025-05-20 08:10:29'),
(3930, 1, 'C', 'Saturday', 7, 'Database Management Systems', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3931, 3, 'A', 'Monday', 1, 'Software Engineering', 'Mrs. T N P Madhuri', '2025-05-20 08:10:29'),
(3932, 3, 'A', 'Monday', 2, 'Web Programming Lab', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3933, 3, 'A', 'Monday', 3, 'Software Engineering', 'Mrs. T N P Madhuri', '2025-05-20 08:10:29'),
(3934, 3, 'A', 'Monday', 4, 'Web Programming', 'K Manasa', '2025-05-20 08:10:29'),
(3935, 3, 'A', 'Monday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3936, 3, 'A', 'Monday', 5, 'Internet of Things and Applications Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3937, 3, 'A', 'Monday', 6, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3938, 3, 'A', 'Monday', 7, 'Introduction to Data Science', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(3939, 3, 'A', 'Tuesday', 1, 'Web Programming', 'K Manasa', '2025-05-20 08:10:29'),
(3940, 3, 'A', 'Tuesday', 2, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3941, 3, 'A', 'Tuesday', 3, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3942, 3, 'A', 'Tuesday', 4, 'Introduction to Data Science', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(3943, 3, 'A', 'Tuesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3944, 3, 'A', 'Tuesday', 5, 'Software Engineering', 'Mrs. T N P Madhuri', '2025-05-20 08:10:29'),
(3945, 3, 'A', 'Tuesday', 6, 'Internet of Things and Applications Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3946, 3, 'A', 'Tuesday', 7, 'Constitution of India', 'Mrs. A Vani Pushpavathi', '2025-05-20 08:10:29'),
(3947, 3, 'A', 'Wednesday', 1, 'Data Warehousing and Data Mining', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3948, 3, 'A', 'Wednesday', 2, 'Constitution of India', 'Mrs. A Vani Pushpavathi', '2025-05-20 08:10:29'),
(3949, 3, 'A', 'Wednesday', 3, 'Web Programming', 'K Manasa', '2025-05-20 08:10:29'),
(3950, 3, 'A', 'Wednesday', 4, 'Constitution of India', 'Mrs. A Vani Pushpavathi', '2025-05-20 08:10:29'),
(3951, 3, 'A', 'Wednesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3952, 3, 'A', 'Wednesday', 5, 'Software Engineering', 'Mrs. T N P Madhuri', '2025-05-20 08:10:29'),
(3953, 3, 'A', 'Wednesday', 6, 'Web Programming', 'K Manasa', '2025-05-20 08:10:29'),
(3954, 3, 'A', 'Wednesday', 7, 'Data Warehousing and Data Mining', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3955, 3, 'A', 'Thursday', 1, 'Data Warehousing and Data Mining', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3956, 3, 'A', 'Thursday', 2, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3957, 3, 'A', 'Thursday', 3, 'Internet of Things and Applications Lab', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3958, 3, 'A', 'Thursday', 4, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3959, 3, 'A', 'Thursday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3960, 3, 'A', 'Thursday', 5, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3961, 3, 'A', 'Thursday', 6, 'Web Programming', 'K Manasa', '2025-05-20 08:10:29'),
(3962, 3, 'A', 'Thursday', 7, 'Web Programming Lab', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3963, 3, 'A', 'Saturday', 1, 'Data Warehousing and Data Mining', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3964, 3, 'A', 'Saturday', 2, 'Introduction to Data Science', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(3965, 3, 'A', 'Saturday', 3, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(3966, 3, 'A', 'Saturday', 4, 'Data Warehousing and Data Mining', 'Dr. V Akila', '2025-05-20 08:10:29'),
(3967, 3, 'A', 'Saturday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3968, 3, 'A', 'Saturday', 5, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3969, 3, 'A', 'Saturday', 6, 'Software Engineering', 'Mrs. T N P Madhuri', '2025-05-20 08:10:29'),
(3970, 3, 'A', 'Saturday', 7, 'Web Programming Lab', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(3971, 3, 'B', 'Monday', 1, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(3972, 3, 'B', 'Monday', 2, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(3973, 3, 'B', 'Monday', 3, 'Internet of Things and Applications Lab', 'Mrs. M Usha Rani', '2025-05-20 08:10:29'),
(3974, 3, 'B', 'Monday', 4, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3975, 3, 'B', 'Monday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3976, 3, 'B', 'Monday', 5, 'Software Engineering', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3977, 3, 'B', 'Monday', 6, 'Web Programming', 'Dr. G Prasanthi', '2025-05-20 08:10:29'),
(3978, 3, 'B', 'Monday', 7, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3979, 3, 'B', 'Tuesday', 1, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(3980, 3, 'B', 'Tuesday', 2, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3981, 3, 'B', 'Tuesday', 3, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(3982, 3, 'B', 'Tuesday', 4, 'Web Programming', 'Dr. G Prasanthi', '2025-05-20 08:10:29'),
(3983, 3, 'B', 'Tuesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3984, 3, 'B', 'Tuesday', 5, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3985, 3, 'B', 'Tuesday', 6, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(3986, 3, 'B', 'Tuesday', 7, 'Artificial Intelligence', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(3987, 3, 'B', 'Wednesday', 1, 'Internet of Things and Applications Lab', 'Mrs. M Usha Rani', '2025-05-20 08:10:29'),
(3988, 3, 'B', 'Wednesday', 2, 'Web Programming', 'Dr. G Prasanthi', '2025-05-20 08:10:29'),
(3989, 3, 'B', 'Wednesday', 3, 'Introduction to Data Science', 'Mrs. M Pavithra', '2025-05-20 08:10:29'),
(3990, 3, 'B', 'Wednesday', 4, 'Internet of Things and Applications Lab', 'Mrs. M Usha Rani', '2025-05-20 08:10:29'),
(3991, 3, 'B', 'Wednesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(3992, 3, 'B', 'Wednesday', 5, 'Introduction to Data Science', 'Mrs. M Pavithra', '2025-05-20 08:10:29'),
(3993, 3, 'B', 'Wednesday', 6, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(3994, 3, 'B', 'Wednesday', 7, 'Artificial Intelligence Lab Using Python', 'Mrs. M Kanaka Durga Bella', '2025-05-20 08:10:29'),
(3995, 3, 'B', 'Thursday', 1, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(3996, 3, 'B', 'Thursday', 2, 'Artificial Intelligence', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(3997, 3, 'B', 'Thursday', 3, 'Artificial Intelligence', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(3998, 3, 'B', 'Thursday', 4, 'Software Engineering', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(3999, 3, 'B', 'Thursday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4000, 3, 'B', 'Thursday', 5, 'Introduction to Data Science', 'Mrs. M Pavithra', '2025-05-20 08:10:29'),
(4001, 3, 'B', 'Thursday', 6, 'Web Programming', 'Dr. G Prasanthi', '2025-05-20 08:10:29'),
(4002, 3, 'B', 'Thursday', 7, 'Software Engineering', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(4003, 3, 'B', 'Saturday', 1, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(4004, 3, 'B', 'Saturday', 2, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(4005, 3, 'B', 'Saturday', 3, 'Web Programming', 'Dr. G Prasanthi', '2025-05-20 08:10:29'),
(4006, 3, 'B', 'Saturday', 4, 'Software Engineering', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(4007, 3, 'B', 'Saturday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4008, 3, 'B', 'Saturday', 5, 'Artificial Intelligence', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(4009, 3, 'B', 'Saturday', 6, 'Artificial Intelligence', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(4010, 3, 'B', 'Saturday', 7, 'Software Engineering', 'Mrs.L.Hema Lalitha', '2025-05-20 08:10:29'),
(4011, 3, 'C', 'Monday', 1, 'Internet of Things and Applications Lab', 'Mrs. R Madhuri', '2025-05-20 08:10:29'),
(4012, 3, 'C', 'Monday', 2, 'Web Programming', 'Sri. G Vijendar Reddy', '2025-05-20 08:10:29'),
(4013, 3, 'C', 'Monday', 3, 'Software Engineering', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(4014, 3, 'C', 'Monday', 4, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(4015, 3, 'C', 'Monday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4016, 3, 'C', 'Monday', 5, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(4017, 3, 'C', 'Monday', 6, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(4018, 3, 'C', 'Monday', 7, 'Introduction to Data Science', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(4019, 3, 'C', 'Tuesday', 1, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(4020, 3, 'C', 'Tuesday', 2, 'Introduction to Data Science', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(4021, 3, 'C', 'Tuesday', 3, 'Artificial Intelligence Lab Using Python', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(4022, 3, 'C', 'Tuesday', 4, 'Artificial Intelligence Lab Using Python', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(4023, 3, 'C', 'Tuesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4024, 3, 'C', 'Tuesday', 5, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(4025, 3, 'C', 'Tuesday', 6, 'Web Programming', 'Sri. G Vijendar Reddy', '2025-05-20 08:10:29'),
(4026, 3, 'C', 'Tuesday', 7, 'Web Programming', 'Sri. G Vijendar Reddy', '2025-05-20 08:10:29'),
(4027, 3, 'C', 'Wednesday', 1, 'Introduction to Data Science', 'Mrs. D Preethi', '2025-05-20 08:10:29'),
(4028, 3, 'C', 'Wednesday', 2, 'Internet of Things and Applications Lab', 'Mrs. R Madhuri', '2025-05-20 08:10:29'),
(4029, 3, 'C', 'Wednesday', 3, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(4030, 3, 'C', 'Wednesday', 4, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(4031, 3, 'C', 'Wednesday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4032, 3, 'C', 'Wednesday', 5, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(4033, 3, 'C', 'Wednesday', 6, 'Internet of Things and Applications Lab', 'Mrs. R Madhuri', '2025-05-20 08:10:29'),
(4034, 3, 'C', 'Wednesday', 7, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(4035, 3, 'C', 'Thursday', 1, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(4036, 3, 'C', 'Thursday', 2, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(4037, 3, 'C', 'Thursday', 3, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(4038, 3, 'C', 'Thursday', 4, 'Software Engineering', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(4039, 3, 'C', 'Thursday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4040, 3, 'C', 'Thursday', 5, 'Artificial Intelligence Lab Using Python', 'Mrs. P Prathibha Swaraj', '2025-05-20 08:10:29'),
(4041, 3, 'C', 'Thursday', 6, 'Software Engineering', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(4042, 3, 'C', 'Thursday', 7, 'Web Programming Lab', 'Mrs. T. Nishitha', '2025-05-20 08:10:29'),
(4043, 3, 'C', 'Saturday', 1, 'Artificial Intelligence', 'Mr. P K Abhilash', '2025-05-20 08:10:29'),
(4044, 3, 'C', 'Saturday', 2, 'Data Warehousing and Data Mining', 'Mrs. G D Vijaya Lakshmi', '2025-05-20 08:10:29'),
(4045, 3, 'C', 'Saturday', 3, 'Software Engineering', 'Mrs. Vemula Divya', '2025-05-20 08:10:29'),
(4046, 3, 'C', 'Saturday', 4, 'Web Programming', 'Sri. G Vijendar Reddy', '2025-05-20 08:10:29'),
(4047, 3, 'C', 'Saturday', 5, 'Break', '', '2025-05-20 08:10:29'),
(4048, 3, 'C', 'Saturday', 5, 'Web Programming', 'Sri. G Vijendar Reddy', '2025-05-20 08:10:29'),
(4049, 3, 'C', 'Saturday', 6, 'Constitution of India', 'Mrs. A Srilakshmi', '2025-05-20 08:10:29'),
(4050, 3, 'C', 'Saturday', 7, 'Software Engineering', 'Mrs. Vemula Divya', '2025-05-20 08:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `periods_per_day` int(11) DEFAULT NULL,
  `period_duration` int(11) DEFAULT NULL,
  `theory_duration` int(11) DEFAULT NULL,
  `lab_duration` int(11) DEFAULT NULL,
  `working_days` varchar(100) DEFAULT NULL,
  `start_time` time NOT NULL DEFAULT '09:00:00',
  `break_after_period` int(11) DEFAULT 4,
  `break_duration` int(11) DEFAULT 35
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `semester_id`, `periods_per_day`, `period_duration`, `theory_duration`, `lab_duration`, `working_days`, `start_time`, `break_after_period`, `break_duration`) VALUES
(8, 1, 7, 50, 50, 50, 'Monday,Wednesday,Thursday,Friday,Saturday', '09:00:00', 3, 40),
(9, 3, 7, 50, 50, 50, 'Monday,Tuesday,Wednesday,Thursday,Saturday', '09:00:00', 4, 35);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_type` enum('student','faculty') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `section` varchar(10) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_type`, `user_id`, `message`, `seen`, `created_at`, `section`, `semester_id`) VALUES
(176, 'faculty', 1074, '✅ Your leave on 2025-05-22 (Period 3) for Digital Logic Design has been approved and a substitute has been assigned.', 0, '2025-05-20 13:41:24', NULL, NULL),
(177, 'faculty', 1076, '📌 You have been assigned a substitution for Digital Logic Design (A-1) on 2025-05-22 during Period 3.', 0, '2025-05-20 13:41:24', NULL, NULL),
(178, 'student', NULL, 'ℹ️ Your teacher Dr. Y J Nagendra Kumar is on leave on 2025-05-22 during Period 3 for Digital Logic Design. Dr. Y Srilalitha will take the class.', 0, '2025-05-20 13:41:24', 'A', 1),
(179, 'faculty', 1074, '✅ Your leave on 2025-05-22 (Period 5) for Digital Logic Design has been approved and a substitute has been assigned.', 0, '2025-05-20 13:41:24', NULL, NULL),
(180, 'faculty', 1087, '📌 You have been assigned a substitution for Digital Logic Design (A-1) on 2025-05-22 during Period 5.', 0, '2025-05-20 13:41:24', NULL, NULL),
(181, 'student', NULL, 'ℹ️ Your teacher Dr. Y J Nagendra Kumar is on leave on 2025-05-22 during Period 5 for Digital Logic Design. Mr. P K Abhilash will take the class.', 0, '2025-05-20 13:41:24', 'A', 1),
(182, 'faculty', 1074, '✅ Your leave on 2025-05-22 (Period 6) for Digital Logic Design has been approved and a substitute has been assigned.', 0, '2025-05-20 13:41:24', NULL, NULL),
(183, 'faculty', 1090, '📌 You have been assigned a substitution for Digital Logic Design (B-1) on 2025-05-22 during Period 6.', 0, '2025-05-20 13:41:24', NULL, NULL),
(184, 'student', NULL, 'ℹ️ Your teacher Dr. Y J Nagendra Kumar is on leave on 2025-05-22 during Period 6 for Digital Logic Design. Mrs. P Prathibha Swaraj will take the class.', 0, '2025-05-20 13:41:24', 'B', 1),
(185, 'faculty', 1074, '✅ Your leave on 2025-05-22 (Period 4) for Java Programming has been approved and a substitute has been assigned.', 0, '2025-05-20 13:41:24', NULL, NULL),
(186, 'faculty', 1079, '📌 You have been assigned a substitution for Java Programming (C-1) on 2025-05-22 during Period 4.', 0, '2025-05-20 13:41:24', NULL, NULL),
(187, 'student', NULL, 'ℹ️ Your teacher Dr. Y J Nagendra Kumar is on leave on 2025-05-22 during Period 4 for Java Programming. Sri. P Gopala Krishna will take the class.', 0, '2025-05-20 13:41:24', 'C', 1);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semester_id`, `name`) VALUES
(1, '2-1'),
(2, '2-2'),
(3, '3-1'),
(4, '3-2');

-- --------------------------------------------------------

--
-- Table structure for table `semester_subject`
--

CREATE TABLE `semester_subject` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester_subject`
--

INSERT INTO `semester_subject` (`id`, `semester_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 2, 10),
(11, 2, 11),
(12, 2, 12),
(13, 2, 13),
(14, 2, 14),
(15, 2, 15),
(16, 2, 16),
(17, 2, 17),
(18, 2, 18),
(19, 2, 19),
(20, 2, 20),
(21, 3, 21),
(22, 3, 22),
(23, 3, 23),
(24, 3, 24),
(25, 3, 25),
(26, 3, 26),
(27, 3, 27),
(28, 3, 28),
(29, 3, 29),
(30, 4, 30),
(31, 4, 31),
(32, 4, 32),
(33, 4, 33),
(34, 4, 34),
(35, 4, 35),
(36, 4, 36),
(37, 4, 37);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `roll_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `semester_id` int(11) NOT NULL,
  `section` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `roll_number`, `name`, `email`, `date_of_birth`, `semester_id`, `section`) VALUES
(1, '22A01', 'Ananya Reddy', 'ananya.reddy@example.com', '2004-05-15', 1, 'A'),
(2, '22A02', 'Ravi Teja', 'ravi.teja@example.com', '2004-07-22', 1, 'A'),
(3, '22A03', 'Kiran Kumar', 'kiran.k@example.com', '2004-09-10', 1, 'A'),
(4, '22A04', 'Meghana D', 'meghana.d@example.com', '2004-03-18', 1, 'A'),
(5, '22A05', 'Sandeep P', 'sandeep.p@example.com', '2004-11-04', 1, 'A'),
(6, '22A06', 'Lavanya C', 'lavanya.c@example.com', '2004-01-25', 1, 'A'),
(7, '22A07', 'Vamshi K', 'vamshi.k@example.com', '2004-06-12', 1, 'A'),
(8, '22A08', 'Deepika R', 'deepika.r@example.com', '2004-08-09', 1, 'A'),
(9, '22A09', 'Manoj K', 'manoj.k@example.com', '2004-04-30', 1, 'A'),
(10, '22A10', 'Harika V', 'harika.v@example.com', '2004-10-19', 1, 'A'),
(11, '22B01', 'Nikhil S', 'nikhil.s@example.com', '2004-02-05', 1, 'B'),
(12, '22B02', 'Sneha P', 'sneha.p@example.com', '2004-12-15', 1, 'B'),
(13, '22B03', 'Rohith C', 'rohith.c@example.com', '2004-07-17', 1, 'B'),
(14, '22B04', 'Yamuna M', 'yamuna.m@example.com', '2004-09-21', 1, 'B'),
(15, '22B05', 'Gopi N', 'gopi.n@example.com', '2004-03-09', 1, 'B'),
(16, '22B06', 'Neha A', 'neha.a@example.com', '2004-06-27', 1, 'B'),
(17, '22B07', 'Ashok L', 'ashok.l@example.com', '2004-11-12', 1, 'B'),
(18, '22B08', 'Kavya S', 'kavya.s@example.com', '2004-10-08', 1, 'B'),
(19, '22B09', 'Tarun V', 'tarun.v@example.com', '2004-08-18', 1, 'B'),
(20, '22B10', 'Bhavana R', 'bhavana.r@example.com', '2004-01-03', 1, 'B'),
(21, '22C01', 'Chaitanya S', 'chaitanya.s@example.com', '2004-05-10', 1, 'C'),
(22, '22C02', 'Navya R', 'navya.r@example.com', '2004-06-21', 1, 'C'),
(23, '22C03', 'Surya V', 'surya.v@example.com', '2004-07-19', 1, 'C'),
(24, '22C04', 'Divya P', 'divya.p@example.com', '2004-08-15', 1, 'C'),
(25, '22C05', 'Sathvik K', 'sathvik.k@example.com', '2004-09-12', 1, 'C'),
(26, '22C06', 'Alekhya T', 'alekhya.t@example.com', '2004-10-25', 1, 'C'),
(27, '22C07', 'Ajay G', 'ajay.g@example.com', '2004-11-04', 1, 'C'),
(28, '22C08', 'Sanvika M', 'sanvika.m@example.com', '2004-12-14', 1, 'C'),
(29, '22C09', 'Hemanth J', 'hemanth.j@example.com', '2004-01-19', 1, 'C'),
(30, '22C10', 'Bharath Y', 'bharath.y@example.com', '2004-02-28', 1, 'C'),
(31, '21A01', 'Sravan K', 'sravan.k@example.com', '2003-04-04', 3, 'A'),
(32, '21A02', 'Reshma Y', 'reshma.y@example.com', '2003-05-30', 3, 'A'),
(33, '21A03', 'Vinay T', 'vinay.t@example.com', '2003-06-13', 3, 'A'),
(34, '21A04', 'Ramya B', 'ramya.b@example.com', '2003-07-02', 3, 'A'),
(35, '21A05', 'Lokesh G', 'lokesh.g@example.com', '2003-08-14', 3, 'A'),
(36, '21A06', 'Bhargavi N', 'bhargavi.n@example.com', '2003-09-25', 3, 'A'),
(37, '21A07', 'Sushma J', 'sushma.j@example.com', '2003-10-07', 3, 'A'),
(38, '21A08', 'Varun D', 'varun.d@example.com', '2003-11-17', 3, 'A'),
(39, '21A09', 'Jahnavi P', 'jahnavi.p@example.com', '2003-12-22', 3, 'A'),
(40, '21A10', 'Tejaswi M', 'tejaswi.m@example.com', '2003-01-28', 3, 'A'),
(41, '21B01', 'Sanjana R', 'sanjana.r@example.com', '2003-03-11', 3, 'B'),
(42, '21B02', 'Rithvik P', 'rithvik.p@example.com', '2003-04-22', 3, 'B'),
(43, '21B03', 'Anusha K', 'anusha.k@example.com', '2003-05-30', 3, 'B'),
(44, '21B04', 'Krishna M', 'krishna.m@example.com', '2003-06-10', 3, 'B'),
(45, '21B05', 'Harsha V', 'harsha.v@example.com', '2003-07-27', 3, 'B'),
(46, '21B06', 'Swathi N', 'swathi.n@example.com', '2003-08-08', 3, 'B'),
(47, '21B07', 'Vishal A', 'vishal.a@example.com', '2003-09-16', 3, 'B'),
(48, '21B08', 'Jayanth R', 'jayanth.r@example.com', '2003-10-05', 3, 'B'),
(49, '21B09', 'Pavani L', 'pavani.l@example.com', '2003-11-26', 3, 'B'),
(50, '21B10', 'Revanth T', 'revanth.t@example.com', '2003-12-30', 3, 'B'),
(51, '21C01', 'Shreya G', 'shreya.g@example.com', '2003-01-17', 3, 'C'),
(52, '21C02', 'Anil K', 'anil.k@example.com', '2003-02-14', 3, 'C'),
(53, '21C03', 'Lavanya P', 'lavanya.p@example.com', '2003-03-19', 3, 'C'),
(54, '21C04', 'Manish Y', 'manish.y@example.com', '2003-04-24', 3, 'C'),
(55, '21C05', 'Teja N', 'teja.n@example.com', '2003-05-12', 3, 'C'),
(56, '21C06', 'Karthik S', 'karthik.s@example.com', '2003-06-09', 3, 'C'),
(57, '21C07', 'Sowmya R', 'sowmya.r@example.com', '2003-07-05', 3, 'C'),
(58, '21C08', 'Aditya V', 'aditya.v@example.com', '2003-08-22', 3, 'C'),
(59, '21C09', 'Niharika A', 'niharika.a@example.com', '2003-09-30', 3, 'C'),
(60, '21C10', 'Yashwanth B', 'yashwanth.b@example.com', '2003-10-20', 3, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`) VALUES
(1, 'GR22A2067', 'Digital Logic Design'),
(2, 'GR22A2068', 'Java Programming'),
(3, 'GR22A2005', 'Probability and Statistics'),
(4, 'GR22A2069', 'Database Management Systems'),
(5, 'GR22A2075', 'Discrete Mathematics'),
(6, 'GR22A2058', 'Digital Electronics Lab'),
(7, 'GR22A2071', 'Java Programming Lab'),
(8, 'GR22A2072', 'Database Management Systems Lab'),
(9, 'GR22A2002', 'Value Ethics and Gender Culture'),
(10, 'GR22A2077', 'Design and Analysis of Algorithms'),
(11, 'GR22A2073', 'Computer Organization'),
(12, 'GR22A2004', 'Economics & Accounting for Engineers'),
(13, 'GR22A2081', 'Data Communication and Computer Networks'),
(14, 'GR22A2074', 'Operating Systems'),
(15, 'GR22A2082', 'Design and Analysis of Algorithms using Java Lab'),
(16, 'GR22A2083', 'Operating Systems and Sci Lab'),
(17, 'GR22A2084', 'Data Communication and Computer Networks Lab'),
(18, 'GR22A2001', 'Environmental Science'),
(19, 'GR22A2108', 'Effective Technical Communication'),
(20, 'GR22A2109', 'Real-time Research Project'),
(21, 'GR22A3052', 'Software Engineering'),
(22, 'GR22A3070', 'Artificial Intelligence'),
(23, 'GR22A3053', 'Web Programming'),
(24, 'GR22A3069', 'Data Warehousing and Data Mining'),
(25, 'GR22A3056', 'Introduction to Data Science'),
(26, 'GR22A3057', 'Internet of Things and Applications Lab'),
(27, 'GR22A3058', 'Web Programming Lab'),
(28, 'GR22A3074', 'Artificial Intelligence Lab Using Python'),
(29, 'GR20A2003', 'Constitution of India'),
(30, 'GR22A3115', 'Automata and Compiler Design'),
(31, 'GR22A3140', 'Machine Learning'),
(32, 'GR22A3123', 'Full Stack Development'),
(33, 'GR22A3124', 'Unix Programming'),
(34, 'GR22A3127', 'User-Centric Human Computer Interaction'),
(35, 'GR22A3142', 'Machine Learning Lab'),
(36, 'GR22A3128', 'Full Stack Development Lab'),
(37, 'GR22A3089', 'Mini Project with Seminar');

-- --------------------------------------------------------

--
-- Table structure for table `subject_settings`
--

CREATE TABLE `subject_settings` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `subject_type` enum('theory','lab') NOT NULL,
  `lectures_per_week` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_settings`
--

INSERT INTO `subject_settings` (`id`, `subject_id`, `semester_id`, `subject_type`, `lectures_per_week`) VALUES
(64, 1, 1, 'theory', 5),
(65, 2, 1, 'theory', 5),
(66, 3, 1, 'theory', 5),
(67, 4, 1, 'theory', 5),
(68, 5, 1, 'theory', 5),
(69, 6, 1, 'lab', 3),
(70, 7, 1, 'lab', 3),
(71, 8, 1, 'lab', 3),
(72, 9, 1, 'theory', 1),
(73, 21, 3, 'theory', 5),
(74, 22, 3, 'theory', 5),
(75, 23, 3, 'theory', 5),
(76, 24, 3, 'theory', 5),
(77, 25, 3, 'theory', 3),
(78, 26, 3, 'lab', 3),
(79, 27, 3, 'lab', 3),
(80, 28, 3, 'lab', 3),
(81, 29, 3, 'theory', 3);

-- --------------------------------------------------------

--
-- Table structure for table `substitution_assignments`
--

CREATE TABLE `substitution_assignments` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `period_number` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `original_faculty_id` int(11) DEFAULT NULL,
  `substitute_faculty_id` int(11) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `substitution_assignments`
--

INSERT INTO `substitution_assignments` (`id`, `leave_id`, `date`, `day`, `period_number`, `section`, `semester_id`, `original_faculty_id`, `substitute_faculty_id`, `subject_name`, `status`) VALUES
(56, 18, '2025-05-22', 'Thursday', 3, 'A', 1, 1074, 1076, 'Digital Logic Design', 'Assigned'),
(57, 18, '2025-05-22', 'Thursday', 5, 'A', 1, 1074, 1087, 'Digital Logic Design', 'Assigned'),
(58, 18, '2025-05-22', 'Thursday', 6, 'B', 1, 1074, 1090, 'Digital Logic Design', 'Assigned'),
(59, 18, '2025-05-22', 'Thursday', 4, 'C', 1, 1074, 1079, 'Java Programming', 'Assigned');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_allotment`
--
ALTER TABLE `faculty_allotment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_id` (`subject_id`,`section`,`semester_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `faculty_leaves`
--
ALTER TABLE `faculty_leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `final_timetable`
--
ALTER TABLE `final_timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `semester_subject`
--
ALTER TABLE `semester_subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `roll_number` (`roll_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `subject_settings`
--
ALTER TABLE `subject_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `substitution_assignments`
--
ALTER TABLE `substitution_assignments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1111;

--
-- AUTO_INCREMENT for table `faculty_allotment`
--
ALTER TABLE `faculty_allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `faculty_leaves`
--
ALTER TABLE `faculty_leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `final_timetable`
--
ALTER TABLE `final_timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4051;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `semester_subject`
--
ALTER TABLE `semester_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `subject_settings`
--
ALTER TABLE `subject_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `substitution_assignments`
--
ALTER TABLE `substitution_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_allotment`
--
ALTER TABLE `faculty_allotment`
  ADD CONSTRAINT `faculty_allotment_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `faculty_allotment_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`);

--
-- Constraints for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD CONSTRAINT `faculty_subjects_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `semester_subject`
--
ALTER TABLE `semester_subject`
  ADD CONSTRAINT `semester_subject_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
  ADD CONSTRAINT `semester_subject_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`);

--
-- Constraints for table `subject_settings`
--
ALTER TABLE `subject_settings`
  ADD CONSTRAINT `subject_settings_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
