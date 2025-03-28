-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 10:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admint`
--

CREATE TABLE `admint` (
  `admin_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admint`
--

INSERT INTO `admint` (`admin_id`, `password`, `created_at`, `updated_at`) VALUES
('admin', 'admin', '2025-03-28 13:24:22', '2025-03-28 13:24:22');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appt_id` int(11) NOT NULL,
  `appt_date` date NOT NULL,
  `appt_time` time NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appt_id`, `appt_date`, `appt_time`, `updated_at`) VALUES
(1, '2025-03-04', '13:02:22', '2025-03-22 03:02:46'),
(2, '2025-03-12', '11:38:16', '2025-03-22 03:39:30'),
(3, '2025-03-12', '20:39:34', '2025-03-22 03:39:50'),
(4, '2024-10-09', '14:39:53', '2025-03-22 03:40:08'),
(5, '2025-03-22', '00:00:10', '2025-03-24 16:03:10'),
(6, '2025-03-24', '00:00:00', '2025-03-24 19:32:49'),
(7, '2025-03-26', '10:00:00', '2025-03-24 19:52:48'),
(8, '2025-03-29', '10:45:00', '2025-03-25 19:45:12'),
(9, '2025-03-29', '00:00:00', '2025-03-25 20:42:08'),
(10, '2025-03-28', '12:00:00', '2025-03-28 03:53:25'),
(11, '2025-03-30', '13:30:00', '2025-03-28 05:29:48'),
(12, '2025-04-01', '13:37:00', '2025-03-28 05:38:01'),
(13, '2025-03-28', '20:14:00', '2025-03-28 07:14:18'),
(14, '2025-03-29', '02:45:00', '2025-03-28 20:43:02');

-- --------------------------------------------------------

--
-- Table structure for table `bill_detail`
--

CREATE TABLE `bill_detail` (
  `bill_detail_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `doctor_user_id` varchar(20) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `charge_amount` decimal(10,2) NOT NULL,
  `status` enum('Due','Paid') NOT NULL DEFAULT 'Due',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_detail`
--

INSERT INTO `bill_detail` (`bill_detail_id`, `patient_user_id`, `doctor_user_id`, `test_id`, `charge_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'p003', 'd001', NULL, 700.00, 'Due', '2025-03-25 18:12:31', '2025-03-25 18:12:31'),
(2, 'p003', NULL, 1, 350.00, 'Due', '2025-03-25 18:12:56', '2025-03-25 18:12:56'),
(3, 'p003', NULL, 2, 50.00, 'Due', '2025-03-25 18:35:58', '2025-03-25 18:35:58'),
(4, 'p004', 'd001', NULL, 700.00, 'Paid', '2025-03-25 20:44:26', '2025-03-25 20:44:26'),
(5, 'p004', NULL, 7, 800.00, 'Paid', '2025-03-25 20:50:44', '2025-03-25 20:50:44'),
(6, 'p004', 'd001', NULL, 700.00, 'Due', '2025-03-26 17:55:04', '2025-03-26 17:55:04'),
(7, 'p004', 'd001', NULL, 700.00, 'Due', '2025-03-26 17:55:22', '2025-03-26 17:55:22'),
(8, 'p001', 'd001', NULL, 700.00, 'Due', '2025-03-26 17:58:19', '2025-03-26 17:58:19'),
(9, 'p002', 'd001', NULL, 700.00, 'Due', '2025-03-26 17:59:30', '2025-03-26 17:59:30'),
(10, 'p004', NULL, 8, 600.00, 'Due', '2025-03-28 07:10:37', '2025-03-28 07:10:37'),
(11, 'p004', NULL, 5, 4000.00, 'Due', '2025-03-28 07:18:25', '2025-03-28 07:18:25'),
(12, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 07:26:42', '2025-03-28 07:26:42'),
(13, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:18:18', '2025-03-28 20:18:18'),
(14, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:18:29', '2025-03-28 20:18:29'),
(15, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:21:56', '2025-03-28 20:21:56'),
(16, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:22:09', '2025-03-28 20:22:09'),
(17, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:22:21', '2025-03-28 20:22:21'),
(18, 'p003', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:33:48', '2025-03-28 20:33:48'),
(19, 'p003', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:48:28', '2025-03-28 20:48:28'),
(20, 'p003', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:57:52', '2025-03-28 20:57:52'),
(21, 'p004', 'd003', NULL, 500.00, 'Due', '2025-03-28 20:59:00', '2025-03-28 20:59:00'),
(22, 'p003', 'd003', NULL, 500.00, 'Due', '2025-03-28 21:05:21', '2025-03-28 21:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `checkup`
--

CREATE TABLE `checkup` (
  `appt_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `doctor_user_id` varchar(20) NOT NULL,
  `appt_status` enum('Scheduled','Completed','Cancelled by Doctor','Cancelled by Patient','Missed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkup`
--

INSERT INTO `checkup` (`appt_id`, `patient_user_id`, `doctor_user_id`, `appt_status`, `created_at`, `updated_at`) VALUES
(1, 'p001', 'd001', 'Completed', '2025-03-22 03:04:03', '2025-03-26 17:58:19'),
(2, 'p001', 'd002', '', '2025-03-22 03:40:37', '2025-03-22 03:40:37'),
(3, 'p002', 'd002', '', '2025-03-22 03:40:50', '2025-03-22 03:40:50'),
(4, 'p002', 'd001', '', '2025-03-22 03:41:25', '2025-03-26 17:59:35'),
(5, 'p004', 'd002', 'Missed', '2025-03-24 16:03:10', '2025-03-25 19:47:31'),
(6, 'p004', 'd001', 'Completed', '2025-03-24 19:32:49', '2025-03-25 20:44:26'),
(7, 'p004', 'd001', 'Completed', '2025-03-24 19:52:48', '2025-03-25 20:28:59'),
(8, 'p004', 'd002', 'Completed', '2025-03-25 19:45:12', '2025-03-25 19:49:19'),
(9, 'p004', 'd001', 'Cancelled by Patient', '2025-03-25 20:42:08', '2025-03-28 20:42:30'),
(10, 'p003', 'd003', 'Scheduled', '2025-03-28 03:53:25', '2025-03-28 21:05:48'),
(11, 'p003', 'd003', 'Scheduled', '2025-03-28 05:29:48', '2025-03-28 21:05:55'),
(12, 'p003', 'd003', 'Scheduled', '2025-03-28 05:38:01', '2025-03-28 21:02:11'),
(13, 'p004', 'd003', 'Scheduled', '2025-03-28 07:14:18', '2025-03-28 21:02:19'),
(14, 'p004', 'd003', 'Scheduled', '2025-03-28 20:43:02', '2025-03-28 21:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `dept_head` varchar(50) DEFAULT NULL,
  `staff_count` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `dept_name`, `dept_head`, `staff_count`, `created_at`, `updated_at`) VALUES
(1, 'Orthopedics', 'd003', NULL, '2025-03-19 16:31:47', '2025-03-28 06:39:13'),
(2, 'Dermatology', NULL, NULL, '2025-03-19 16:31:47', '2025-03-28 00:21:46'),
(3, 'Cardiology', NULL, NULL, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
(4, 'Neurology', NULL, NULL, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
(5, 'Pediatrics', NULL, NULL, '2025-03-19 16:31:47', '2025-03-19 16:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `doc_fee` decimal(10,2) DEFAULT NULL,
  `specialization` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `phone`, `dob`, `salary`, `doc_fee`, `specialization`, `availability`, `dept_id`, `created_at`, `updated_at`, `session_id`) VALUES
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', 'Male', '025695198085149', '1985-02-25', 10000.00, 700.00, 'Orthopedics', 'Mon-Wed-Fri 10 AM - 12 PM', 1, '2025-03-19 16:31:47', '2025-03-27 23:43:58', NULL),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', 'Female', '01887654321', '1988-06-15', 65000.00, 750.00, 'Dermatology', '10AM - 4PM', 2, '2025-03-19 16:31:47', '2025-03-19 16:31:47', NULL),
('d003', 'Xaima', 'Zaman', 'xaimazaman@gmail.com', '$2y$10$vhFtlSUi9poyTuyuIwOMpuw.j2M/hYchEREj.nJrNNaa9HkQowCKO', 'Female', '01795158222', '2005-04-28', 50000.00, 500.00, NULL, 'ST RA 8 AM', 1, '2025-03-27 23:27:30', '2025-03-28 19:36:50', '8s1hop0e78avhlfavft0essks3'),
('d004', 'Xayan', 'Zaman', 'zayan@gmail.com', '$2y$10$qdWosb1GJjsYYI5YN.augO1rTmVQm.SUwayqgUsnHYf4Hq4BcDcNW', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-28 15:28:08', '2025-03-28 19:36:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doc_test_patient`
--

CREATE TABLE `doc_test_patient` (
  `doctor_user_id` varchar(20) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `pres_date` date NOT NULL,
  `test_date` date DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doc_test_patient`
--

INSERT INTO `doc_test_patient` (`doctor_user_id`, `test_id`, `patient_user_id`, `pres_date`, `test_date`, `result`, `created_at`, `updated_at`) VALUES
('d001', 1, 'p001', '2025-03-04', '2025-03-06', 'Low RBC', '2025-03-23 22:01:28', '2025-03-24 03:34:39'),
('d001', 1, 'p003', '2025-03-24', '2025-03-25', 'Low RBC', '2025-03-25 04:57:48', '2025-03-25 07:22:45'),
('d001', 1, 'p004', '2025-03-24', '2025-03-31', 'die', '2025-03-24 19:33:45', '2025-03-25 19:54:24'),
('d001', 1, 'p004', '2025-03-26', '2025-03-31', 'die', '2025-03-24 19:54:21', '2025-03-25 19:54:24'),
('d001', 2, 'p001', '2025-03-04', NULL, NULL, '2025-03-23 22:01:28', '2025-03-23 22:01:28'),
('d001', 2, 'p002', '2024-10-09', '2025-03-08', 'broken bone', '2025-03-24 01:30:49', '2025-03-24 03:40:50'),
('d001', 3, 'p001', '2025-03-04', NULL, NULL, '2025-03-23 22:10:00', '2025-03-23 22:10:00'),
('d001', 3, 'p002', '2024-10-09', NULL, NULL, '2025-03-24 01:30:49', '2025-03-24 01:30:49'),
('d001', 4, 'p001', '2025-03-04', NULL, NULL, '2025-03-24 02:25:34', '2025-03-24 02:25:34'),
('d001', 4, 'p002', '2024-10-09', NULL, NULL, '2025-03-24 01:30:49', '2025-03-24 01:30:49'),
('d001', 4, 'p004', '2025-03-26', NULL, NULL, '2025-03-24 19:54:21', '2025-03-24 19:54:21'),
('d001', 5, 'p001', '2025-03-04', NULL, NULL, '2025-03-23 22:18:49', '2025-03-23 22:18:49'),
('d001', 6, 'p001', '2025-03-04', NULL, NULL, '2025-03-23 23:21:40', '2025-03-23 23:21:40'),
('d001', 7, 'p001', '2025-03-04', '2025-03-07', 'Positive', '2025-03-23 22:18:49', '2025-03-24 03:39:57'),
('d001', 7, 'p004', '2025-03-26', '2025-03-27', 'positive', '2025-03-24 19:53:57', '2025-03-25 20:50:44'),
('d001', 8, 'p002', '2024-10-09', NULL, NULL, '2025-03-24 01:30:49', '2025-03-24 01:30:49'),
('d001', 8, 'p004', '2025-03-26', '2025-03-28', 'die', '2025-03-24 19:54:21', '2025-03-28 07:10:37'),
('d001', 8, 'p004', '2025-03-29', '2025-03-28', 'die', '2025-03-26 19:50:31', '2025-03-28 07:10:37'),
('d001', 9, 'p002', '2024-10-09', NULL, NULL, '2025-03-24 01:30:49', '2025-03-24 01:30:49'),
('d001', 10, 'p001', '2025-03-04', NULL, NULL, '2025-03-25 05:04:03', '2025-03-25 05:04:03'),
('d001', 10, 'p002', '2024-10-09', NULL, NULL, '2025-03-24 10:59:03', '2025-03-24 10:59:03'),
('d001', 10, 'p004', '2025-03-24', '2025-03-28', 'die', '2025-03-24 19:33:50', '2025-03-25 20:04:55'),
('d001', 10, 'p004', '2025-03-26', '2025-03-28', 'die', '2025-03-24 19:54:21', '2025-03-25 20:04:55'),
('d002', 1, 'p004', '2025-03-29', '2025-03-31', 'die', '2025-03-25 19:47:09', '2025-03-25 19:54:24'),
('d002', 4, 'p003', '2025-03-17', '2025-03-20', 'mathay somossa', '2025-03-25 04:58:37', '2025-03-25 07:24:04'),
('d002', 7, 'p001', '2025-03-04', '2025-03-10', 'Positive', '2025-03-23 22:18:49', '2025-03-23 22:18:49'),
('d002', 10, 'p004', '2025-03-29', '2025-03-28', 'die', '2025-03-25 19:47:09', '2025-03-25 20:04:55'),
('d003', 5, 'p004', '2025-03-28', '2025-03-31', 'crai', '2025-03-28 07:16:19', '2025-03-28 07:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `hod`
--

CREATE TABLE `hod` (
  `doc_id` varchar(20) NOT NULL,
  `head_id` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hod`
--

INSERT INTO `hod` (`doc_id`, `head_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
('d001', 'd001', '2025-03-27', '2025-03-27', '2025-03-27 18:20:16', '2025-03-27 18:20:38'),
('d003', 'd003', '2025-03-28', NULL, '2025-03-28 06:39:13', '2025-03-28 06:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `medicalhistory`
--

CREATE TABLE `medicalhistory` (
  `patient_user_id` varchar(20) NOT NULL,
  `allergies` text DEFAULT NULL,
  `pre_conditions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicalhistory`
--

INSERT INTO `medicalhistory` (`patient_user_id`, `allergies`, `pre_conditions`, `created_at`, `updated_at`) VALUES
('p001', 'Dust', 'Asthma', '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
('p002', 'Penicillin', 'Diabetes', '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
('p003', 'Dust', 'Asthma', '2025-03-28 04:55:13', '2025-03-28 04:55:13'),
('p004', 'Janina', 'janina', '2025-03-28 07:13:44', '2025-03-28 07:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `nurse`
--

CREATE TABLE `nurse` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `duty_hour` enum('Morning','Noon','Evening','Night','Rotational') DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurse`
--

INSERT INTO `nurse` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `phone`, `dob`, `salary`, `duty_hour`, `dept_id`, `created_at`, `updated_at`, `session_id`) VALUES
('n001', 'Anika', 'Rahman', 'anika.rahman@gmail.com', 'password123', 'Female', NULL, NULL, 10000.00, 'Noon', 1, '2025-03-24 13:25:59', '2025-03-27 21:44:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_test_patient`
--

CREATE TABLE `nurse_test_patient` (
  `nurse_user_id` varchar(20) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurse_test_patient`
--

INSERT INTO `nurse_test_patient` (`nurse_user_id`, `test_id`, `patient_user_id`, `created_at`, `updated_at`) VALUES
('n001', 1, 'p004', '2025-03-25 19:54:24', '2025-03-25 19:54:24'),
('n001', 7, 'p004', '2025-03-25 20:50:44', '2025-03-25 20:50:44'),
('n001', 10, 'p004', '2025-03-25 20:04:13', '2025-03-25 20:04:13');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `hno` varchar(10) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `blood_group`, `dob`, `hno`, `street`, `city`, `zip`, `country`, `created_at`, `updated_at`, `session_id`) VALUES
('p001', 'Shanto', 'Ahmed', 'shanto.ahmed@gmail.com', 'password123', 'Male', 'A+', '1992-05-20', '56', 'Gulshan', 'Dhaka', '1212', 'Bangladesh', '2025-03-19 16:31:47', '2025-03-19 16:31:47', NULL),
('p002', 'Sumi', 'Parveen', 'sumi.parveen@gmail.com', 'password123', 'Female', 'B-', '1995-03-15', '24', 'Banani', 'Dhaka', '1213', 'Bangladesh', '2025-03-19 16:31:47', '2025-03-19 16:31:47', NULL),
('p003', 'Samiul', 'Islam', 'samiulsamin.17@gmail.com', '$2y$10$o1NAiYsMM4XNs7Su67l9MeWrxgFcYhDaxiAs5kBZ0Rqqit9m/zFzG', 'Male', 'A+', '2003-04-18', '17/A', 'Shantibagh', 'Dhaka', '1217', 'Bangladesh', '2025-03-21 06:47:11', '2025-03-28 05:42:10', 'pc2mtfb65td77e553uui2mehaf'),
('p004', 'Xaima', 'Zaman', 'xaima.nsu@gmail.com', '$2y$10$s6HyahngF6KDhSyI1qiLO.JMPdbH24vxj/4rBbX3mlKfDIgLCdsyu', 'Female', 'AB+', '2005-04-28', '4/4', 'Block - B, Lalmatia', 'Dhaka', '1207', 'Bangladesh', '2025-03-21 10:51:49', '2025-03-28 20:58:40', 'vvjtl3qj59u4c97ls1j4mrdpvh'),
('p005', 'Xahiya', 'Zaman', 'xahiyazaman@gmail.com', '$2y$10$YdqKqHDcoQPXfzrIOu0oq.FpVJpXaDXg0fs5G9HYUVxzRyAZykWyG', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-24 16:12:50', '2025-03-24 16:12:50', NULL),
('p006', 'Xayan', 'Zaman', 'zayan@gmail.com', '$2y$10$JbRT5iov2hMgbXYLKscygOag1EXkGCiyZMM53lXN5ECkqqSw4dUaO', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-28 02:44:07', '2025-03-28 02:44:07', NULL),
('p007', 'Nafis', 'Arpon', 'nafismuktashid@hotmail.com', '$2y$10$GZd5BAf.pztQgutp5bfosOQUOCk2PXPbli5Ifdm5kidUA/dsEi4Ly', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-28 05:14:47', '2025-03-28 05:46:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_mobile`
--

CREATE TABLE `patient_mobile` (
  `patient_user_id` varchar(20) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_mobile`
--

INSERT INTO `patient_mobile` (`patient_user_id`, `mobile`, `created_at`, `updated_at`) VALUES
('p003', '01534594026', '2025-03-28 04:55:13', '2025-03-28 04:55:13'),
('p003', '01999999999', '2025-03-28 04:55:13', '2025-03-28 04:55:13'),
('p004', '01577098615', '2025-03-28 07:16:02', '2025-03-28 07:16:02'),
('p004', '01795158222', '2025-03-28 07:16:02', '2025-03-28 07:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `phone`, `dob`, `salary`, `dept_id`, `created_at`, `updated_at`) VALUES
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', 'Male', '025695198085149', '1985-02-25', 10000.00, 1, '2025-03-19 16:31:47', '2025-03-27 23:43:58'),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', 'Female', '01887654321', '1988-06-15', 65000.00, 2, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('d003', 'Xaima', 'Zaman', 'xaimazaman@gmail.com', '$2y$10$vhFtlSUi9poyTuyuIwOMpuw.j2M/hYchEREj.nJrNNaa9HkQowCKO', 'Female', '01795158222', '2005-04-28', 50000.00, 1, '2025-03-27 23:27:30', '2025-03-28 13:00:15'),
('d004', 'Xayan', 'Zaman', 'zayan@gmail.com', '$2y$10$qdWosb1GJjsYYI5YN.augO1rTmVQm.SUwayqgUsnHYf4Hq4BcDcNW', 'Male', NULL, NULL, NULL, NULL, '2025-03-28 15:28:08', '2025-03-28 15:28:08'),
('n001', 'Anika', 'Rahman', 'anika.rahman@gmail.com', 'password123', 'Female', '01911223344', '1993-11-10', 10000.00, 1, '2025-03-19 16:31:47', '2025-03-27 21:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `test_id` int(11) NOT NULL,
  `test_name` varchar(50) NOT NULL,
  `test_cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`test_id`, `test_name`, `test_cost`, `created_at`, `updated_at`) VALUES
(1, 'Blood Test', 500.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(2, 'X-Ray', 1500.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(3, 'ECG', 700.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(4, 'CT Scan', 3000.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(5, 'MRI', 4000.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(6, 'Ultrasound', 1200.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(7, 'COVID-19 Test', 800.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(8, 'Liver Function Test', 600.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(9, 'Kidney Function Test', 800.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59'),
(10, 'Blood Pressure Test', 400.00, '2025-03-19 16:33:59', '2025-03-19 16:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `treatmentplan`
--

CREATE TABLE `treatmentplan` (
  `trtplan_id` int(11) NOT NULL,
  `prescribe_date` date NOT NULL,
  `dosage` text DEFAULT NULL,
  `suggestion` text DEFAULT NULL,
  `patient_user_id` varchar(20) DEFAULT NULL,
  `doctor_user_id` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatmentplan`
--

INSERT INTO `treatmentplan` (`trtplan_id`, `prescribe_date`, `dosage`, `suggestion`, `patient_user_id`, `doctor_user_id`, `updated_at`) VALUES
(1, '2025-03-01', 'Ibuprofen 400mg, twice a day for 7 days', 'Rest and avoid physical strain.', 'p001', 'd001', '2025-03-19 16:33:59'),
(2, '2025-03-02', 'Paracetamol 500mg, every 6 hours as needed for pain', 'Monitor temperature and stay hydrated.', 'p002', 'd002', '2025-03-19 16:33:59'),
(3, '2025-03-04', NULL, NULL, 'p001', 'd001', '2025-03-24 02:48:34'),
(4, '2025-03-04', 'meow', 'meow', 'p001', 'd001', '2025-03-23 23:30:12'),
(5, '2024-10-09', 'hello', 'hi', 'p002', 'd001', '2025-03-24 02:45:51'),
(6, '2025-03-24', 'hfh', 'hfh', 'p004', 'd001', '2025-03-24 19:34:08'),
(7, '2025-03-04', 'Paraceetamol', '2 bela', 'p001', 'd001', '2025-03-25 05:04:29'),
(8, '2025-03-29', 'khao', 'ghumao', 'p004', 'd002', '2025-03-25 19:48:48'),
(9, '2025-03-29', 'aaaaaaaaaaa', 'aaaaaaaaaaaaaa', 'p004', 'd001', '2025-03-26 19:50:40'),
(10, '2025-03-27', 'paracetamol 2 bela', 'saradin ghuman', 'p003', 'd003', '2025-03-28 04:08:33'),
(11, '2025-03-28', 'Tumen 2 bela\nFexo 1x14 days\nMontair 1x30 days at night\nVentolin Inhaler as per emergency', 'early bed, sleep tight', 'p003', 'd001', '2025-03-28 04:19:31'),
(12, '2025-03-28', 'bbbbbbbbbb', 'bbbbbbbbb', 'p004', 'd003', '2025-03-28 07:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-24 19:12:13'),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('d003', 'Xaima', 'Zaman', 'xaimazaman@gmail.com', '$2y$10$vhFtlSUi9poyTuyuIwOMpuw.j2M/hYchEREj.nJrNNaa9HkQowCKO', '2025-03-27 23:27:30', '2025-03-28 13:00:15'),
('d004', 'Xayan', 'Zaman', 'zayan@gmail.com', '$2y$10$qdWosb1GJjsYYI5YN.augO1rTmVQm.SUwayqgUsnHYf4Hq4BcDcNW', '2025-03-28 15:28:08', '2025-03-28 15:28:08'),
('n001', 'Anika', 'Rahman', 'anika.rahman@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p001', 'Shanto', 'Ahmed', 'shanto.ahmed@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p002', 'Sumi', 'Parveen', 'sumi.parveen@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p003', 'Samiul', 'Islam', 'samiulsamin.17@gmail.com', '$2y$10$o1NAiYsMM4XNs7Su67l9MeWrxgFcYhDaxiAs5kBZ0Rqqit9m/zFzG', '2025-03-21 06:47:11', '2025-03-21 06:47:11'),
('p004', 'Xaima', 'Zaman', 'xaima.nsu@gmail.com', '$2y$10$s6HyahngF6KDhSyI1qiLO.JMPdbH24vxj/4rBbX3mlKfDIgLCdsyu', '2025-03-21 10:51:49', '2025-03-21 10:51:49'),
('p005', 'Xahiya', 'Zaman', 'xahiyazaman@gmail.com', '$2y$10$YdqKqHDcoQPXfzrIOu0oq.FpVJpXaDXg0fs5G9HYUVxzRyAZykWyG', '2025-03-24 16:12:50', '2025-03-24 16:12:50'),
('p006', 'Xayan', 'Zaman', 'zayan@gmail.com', '$2y$10$JbRT5iov2hMgbXYLKscygOag1EXkGCiyZMM53lXN5ECkqqSw4dUaO', '2025-03-28 02:44:07', '2025-03-28 02:44:07'),
('p007', 'Nafis', 'Arpon', 'nafismuktashid@hotmail.com', '$2y$10$GZd5BAf.pztQgutp5bfosOQUOCk2PXPbli5Ifdm5kidUA/dsEi4Ly', '2025-03-28 05:14:47', '2025-03-28 05:14:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admint`
--
ALTER TABLE `admint`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appt_id`);

--
-- Indexes for table `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD PRIMARY KEY (`bill_detail_id`);

--
-- Indexes for table `checkup`
--
ALTER TABLE `checkup`
  ADD PRIMARY KEY (`appt_id`,`patient_user_id`,`doctor_user_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`),
  ADD KEY `dept_head` (`dept_head`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `doc_test_patient`
--
ALTER TABLE `doc_test_patient`
  ADD PRIMARY KEY (`doctor_user_id`,`test_id`,`patient_user_id`,`pres_date`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `patient_user_id` (`patient_user_id`);

--
-- Indexes for table `hod`
--
ALTER TABLE `hod`
  ADD PRIMARY KEY (`doc_id`,`head_id`,`start_date`),
  ADD KEY `head_id` (`head_id`);

--
-- Indexes for table `medicalhistory`
--
ALTER TABLE `medicalhistory`
  ADD PRIMARY KEY (`patient_user_id`);

--
-- Indexes for table `nurse`
--
ALTER TABLE `nurse`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `nurse_test_patient`
--
ALTER TABLE `nurse_test_patient`
  ADD PRIMARY KEY (`nurse_user_id`,`test_id`,`patient_user_id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `patient_user_id` (`patient_user_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `patient_mobile`
--
ALTER TABLE `patient_mobile`
  ADD PRIMARY KEY (`patient_user_id`,`mobile`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  ADD PRIMARY KEY (`trtplan_id`),
  ADD KEY `patient_user_id` (`patient_user_id`),
  ADD KEY `doctor_user_id` (`doctor_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bill_detail`
--
ALTER TABLE `bill_detail`
  MODIFY `bill_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  MODIFY `trtplan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkup`
--
ALTER TABLE `checkup`
  ADD CONSTRAINT `checkup_ibfk_1` FOREIGN KEY (`appt_id`) REFERENCES `appointment` (`appt_id`) ON DELETE CASCADE;

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`dept_head`) REFERENCES `hod` (`head_id`) ON DELETE SET NULL;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `staff` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`) ON DELETE CASCADE;

--
-- Constraints for table `doc_test_patient`
--
ALTER TABLE `doc_test_patient`
  ADD CONSTRAINT `doc_test_patient_ibfk_1` FOREIGN KEY (`doctor_user_id`) REFERENCES `doctor` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doc_test_patient_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doc_test_patient_ibfk_3` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `hod`
--
ALTER TABLE `hod`
  ADD CONSTRAINT `hod_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hod_ibfk_2` FOREIGN KEY (`head_id`) REFERENCES `staff` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `medicalhistory`
--
ALTER TABLE `medicalhistory`
  ADD CONSTRAINT `medicalhistory_ibfk_1` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `nurse`
--
ALTER TABLE `nurse`
  ADD CONSTRAINT `nurse_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `staff` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`) ON DELETE CASCADE;

--
-- Constraints for table `nurse_test_patient`
--
ALTER TABLE `nurse_test_patient`
  ADD CONSTRAINT `nurse_test_patient_ibfk_1` FOREIGN KEY (`nurse_user_id`) REFERENCES `nurse` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_test_patient_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nurse_test_patient_ibfk_3` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_mobile`
--
ALTER TABLE `patient_mobile`
  ADD CONSTRAINT `patient_mobile_ibfk_1` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`) ON DELETE CASCADE;

--
-- Constraints for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  ADD CONSTRAINT `treatmentplan_ibfk_1` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatmentplan_ibfk_2` FOREIGN KEY (`doctor_user_id`) REFERENCES `doctor` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
