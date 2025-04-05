-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 12:16 AM
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
(1, '2025-04-10', '09:00:00', '2025-04-05 21:51:58'),
(2, '2025-04-10', '10:00:00', '2025-04-05 21:51:58'),
(3, '2025-04-11', '11:00:00', '2025-04-05 21:51:58'),
(4, '2025-04-12', '14:00:00', '2025-04-05 21:51:58'),
(5, '2025-04-12', '15:00:00', '2025-04-05 21:51:58'),
(6, '2025-04-13', '09:00:00', '2025-04-05 21:51:58'),
(7, '2025-04-13', '10:00:00', '2025-04-05 21:51:58'),
(8, '2025-04-14', '11:00:00', '2025-04-05 21:51:58'),
(9, '2025-04-15', '14:00:00', '2025-04-05 21:51:58'),
(10, '2025-04-15', '15:00:00', '2025-04-05 21:51:58'),
(11, '2025-04-16', '09:30:00', '2025-04-05 21:51:58'),
(12, '2025-04-16', '13:00:00', '2025-04-05 21:51:58'),
(13, '2025-04-17', '10:30:00', '2025-04-05 21:51:58'),
(14, '2025-04-17', '15:30:00', '2025-04-05 21:51:58'),
(15, '2025-04-18', '08:00:00', '2025-04-05 21:51:58'),
(16, '2025-04-18', '12:00:00', '2025-04-05 21:51:58'),
(17, '2025-04-19', '14:30:00', '2025-04-05 21:51:58'),
(18, '2025-04-19', '16:00:00', '2025-04-05 21:51:58'),
(19, '2025-04-20', '09:00:00', '2025-04-05 21:51:58'),
(20, '2025-04-20', '11:30:00', '2025-04-05 21:51:58'),
(21, '2025-04-21', '10:00:00', '2025-04-05 21:51:58'),
(22, '2025-04-21', '13:30:00', '2025-04-05 21:51:58'),
(23, '2025-04-22', '15:00:00', '2025-04-05 21:51:58'),
(24, '2025-04-22', '17:00:00', '2025-04-05 21:51:58'),
(25, '2025-04-23', '08:30:00', '2025-04-05 21:51:58'),
(26, '2025-04-23', '12:30:00', '2025-04-05 21:51:58'),
(27, '2025-04-24', '14:00:00', '2025-04-05 21:51:58'),
(28, '2025-04-24', '16:30:00', '2025-04-05 21:51:58'),
(29, '2025-04-25', '09:30:00', '2025-04-05 21:51:58'),
(30, '2025-04-25', '11:00:00', '2025-04-05 21:51:58');

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
(41, 'p002', 'd002', NULL, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(42, 'p001', 'd006', NULL, 1100.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(43, 'p004', 'd009', NULL, 900.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(44, 'p002', 'd012', NULL, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(45, 'p001', 'd001', NULL, 1000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(46, 'p004', 'd004', NULL, 900.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(47, 'p002', 'd007', NULL, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(48, 'p005', 'd010', NULL, 1100.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(49, 'p003', 'd013', NULL, 1100.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(50, 'p001', NULL, 1, 500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(51, 'p002', NULL, 2, 1000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(52, 'p004', NULL, 4, 5000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(53, 'p001', NULL, 6, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(54, 'p003', NULL, 8, 4000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(55, 'p004', NULL, 9, 900.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(56, 'p001', NULL, 11, 1500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(57, 'p003', NULL, 13, 2000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(58, 'p005', NULL, 15, 400.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(59, 'p002', NULL, 17, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(60, 'p004', NULL, 19, 1500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(61, 'p001', NULL, 1, 500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(62, 'p003', NULL, 3, 800.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(63, 'p005', NULL, 5, 600.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(64, 'p002', NULL, 7, 1500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(65, 'p004', NULL, 9, 900.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(66, 'p001', NULL, 11, 1500.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(67, 'p003', NULL, 13, 2000.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(68, 'p005', NULL, 15, 400.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39'),
(69, 'p002', NULL, 17, 1200.00, 'Due', '2025-04-05 21:41:39', '2025-04-05 21:41:39');

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
(1, 'p001', 'd001', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(2, 'p002', 'd002', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(3, 'p003', 'd003', 'Cancelled by Doctor', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(4, 'p004', 'd004', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(5, 'p005', 'd005', 'Missed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(6, 'p001', 'd006', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(7, 'p002', 'd007', 'Cancelled by Patient', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(8, 'p003', 'd008', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(9, 'p004', 'd009', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(10, 'p005', 'd010', 'Missed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(11, 'p001', 'd011', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(12, 'p002', 'd012', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(13, 'p003', 'd013', 'Cancelled by Patient', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(14, 'p004', 'd014', 'Missed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(15, 'p005', 'd015', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(16, 'p001', 'd001', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(17, 'p002', 'd002', 'Cancelled by Doctor', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(18, 'p003', 'd003', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(19, 'p004', 'd004', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(20, 'p005', 'd005', 'Missed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(21, 'p001', 'd006', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(22, 'p002', 'd007', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(23, 'p003', 'd008', 'Cancelled by Patient', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(24, 'p004', 'd009', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(25, 'p005', 'd010', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(26, 'p001', 'd011', 'Missed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(27, 'p002', 'd012', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(28, 'p003', 'd013', 'Completed', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(29, 'p004', 'd014', 'Cancelled by Doctor', '2025-04-05 21:27:32', '2025-04-05 21:27:32'),
(30, 'p005', 'd015', 'Scheduled', '2025-04-05 21:27:32', '2025-04-05 21:27:32');

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
(1, 'Cardiology', 'd001', 10, '2025-04-05 21:10:36', '2025-04-05 21:59:04'),
(2, 'Neurology', 'd007', 8, '2025-04-05 21:10:36', '2025-04-05 21:59:56'),
(3, 'Orthopedics', 'd008', 7, '2025-04-05 21:10:36', '2025-04-05 21:59:46'),
(4, 'Pediatrics', 'd004', 9, '2025-04-05 21:10:36', '2025-04-05 22:00:19'),
(5, 'General Medicine', 'd005', 12, '2025-04-05 21:10:36', '2025-04-05 22:00:28');

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
('d001', 'Rahim', 'Hossain', 'rahim.hossain@gmail.com', '$2y$10$f/jTmvSchzaW5QzvSe81u.y7beXruJ1x4L.ohhdJFr9mr144xp6rO', 'Male', '01711223344', '1980-05-12', 150000.00, 1000.00, 'Cardiologist', 'Mon-Fri 9AM-5PM', 1, '2025-04-05 21:16:09', '2025-04-05 22:09:42', NULL),
('d002', 'Fatima', 'Begum', 'fatima.begum@gmail.com', 'pass123', 'Female', '01722334455', '1982-08-15', 145000.00, 1200.00, 'Neurologist', 'Tue-Sat 10AM-6PM', 2, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d003', 'Karim', 'Ahmed', 'karim.ahmed@gmail.com', 'pass123', 'Male', '01733445566', '1978-03-20', 160000.00, 1100.00, 'Orthopedic Surgeon', 'Mon-Fri 8AM-4PM', 3, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d004', 'Ayesha', 'Khatun', 'ayesha.khatun@gmail.com', 'pass123', 'Female', '01744556677', '1985-11-10', 140000.00, 900.00, 'Pediatrician', 'Wed-Sun 9AM-5PM', 4, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d005', 'Siddiq', 'Rahman', 'siddiq.rahman@gmail.com', 'pass123', 'Male', '01755667788', '1975-07-25', 155000.00, 1000.00, 'General Physician', 'Mon-Fri 10AM-6PM', 5, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d006', 'Nusrat', 'Jahan', 'nusrat.jahan@gmail.com', 'pass123', 'Female', '01766778899', '1983-09-30', 148000.00, 1100.00, 'Cardiologist', 'Tue-Sat 9AM-5PM', 1, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d007', 'Hasan', 'Miah', 'hasan.miah@gmail.com', 'pass123', 'Male', '01777889900', '1981-01-15', 152000.00, 1200.00, 'Neurologist', 'Mon-Fri 8AM-4PM', 2, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d008', 'Shirin', 'Akter', 'shirin.akter@gmail.com', 'pass123', 'Female', '01788990011', '1984-04-22', 142000.00, 1000.00, 'Orthopedic Surgeon', 'Wed-Sun 10AM-6PM', 3, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d009', 'Mahmud', 'Islam', 'mahmud.islam@gmail.com', 'pass123', 'Male', '01799001122', '1979-06-18', 158000.00, 900.00, 'Pediatrician', 'Mon-Fri 9AM-5PM', 4, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d010', 'Ruma', 'Sultana', 'ruma.sultana@gmail.com', 'pass123', 'Female', '01800112233', '1986-12-05', 146000.00, 1100.00, 'General Physician', 'Tue-Sat 10AM-6PM', 5, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d011', 'Jamil', 'Uddin', 'jamil.uddin@gmail.com', 'pass123', 'Male', '01811223344', '1980-02-28', 150000.00, 1000.00, 'Cardiologist', 'Mon-Fri 8AM-4PM', 1, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d012', 'Laila', 'Parvin', 'laila.parvin@gmail.com', 'pass123', 'Female', '01822334455', '1983-10-14', 144000.00, 1200.00, 'Neurologist', 'Wed-Sun 9AM-5PM', 2, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d013', 'Arif', 'Chowdhury', 'arif.chowdhury@gmail.com', 'pass123', 'Male', '01833445566', '1977-08-09', 157000.00, 1100.00, 'Orthopedic Surgeon', 'Mon-Fri 10AM-6PM', 3, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d014', 'Sabina', 'Yasmin', 'sabina.yasmin@gmail.com', 'pass123', 'Female', '01844556677', '1985-03-17', 143000.00, 900.00, 'Pediatrician', 'Tue-Sat 9AM-5PM', 4, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL),
('d015', 'Tarek', 'Aziz', 'tarek.aziz@gmail.com', 'pass123', 'Male', '01855667788', '1982-07-21', 154000.00, 1000.00, 'General Physician', 'Mon-Fri 8AM-4PM', 5, '2025-04-05 21:16:09', '2025-04-05 21:16:09', NULL);

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
('d001', 1, 'p001', '2025-04-18', '2025-04-23', 'Normal', '2025-04-05 21:36:02', '2025-04-05 22:09:14'),
('d001', 11, 'p001', '2025-04-18', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 22:14:31'),
('d001', 16, 'p001', '2025-04-18', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d002', 2, 'p002', '2025-04-10', '2025-04-12', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d002', 12, 'p002', '2025-04-10', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d002', 17, 'p002', '2025-04-19', '2025-04-21', 'Deficiency', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d003', 3, 'p003', '2025-04-11', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d003', 13, 'p003', '2025-04-11', '2025-04-13', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d003', 18, 'p003', '2025-04-20', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d004', 4, 'p004', '2025-04-12', '2025-04-14', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d004', 14, 'p004', '2025-04-12', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d004', 19, 'p004', '2025-04-21', '2025-04-23', 'Positive', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d005', 5, 'p005', '2025-04-12', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d005', 15, 'p005', '2025-04-12', '2025-04-14', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d005', 20, 'p005', '2025-04-22', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d006', 1, 'p001', '2025-04-21', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 22:13:26'),
('d006', 6, 'p001', '2025-04-13', '2025-04-15', 'High cholesterol', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d006', 16, 'p001', '2025-04-13', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d007', 2, 'p002', '2025-04-22', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d007', 7, 'p002', '2025-04-13', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d007', 17, 'p002', '2025-04-13', '2025-04-15', 'Deficiency', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d008', 3, 'p003', '2025-04-22', '2025-04-24', 'Fracture detected', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d008', 8, 'p003', '2025-04-14', '2025-04-16', 'Spinal issue', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d008', 18, 'p003', '2025-04-14', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d009', 4, 'p004', '2025-04-23', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d009', 9, 'p004', '2025-04-15', '2025-04-17', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d009', 19, 'p004', '2025-04-15', '2025-04-17', 'Negative', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d010', 5, 'p005', '2025-04-23', '2025-04-25', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d010', 10, 'p005', '2025-04-15', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d010', 20, 'p005', '2025-04-15', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d011', 6, 'p001', '2025-04-24', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d011', 11, 'p001', '2025-04-16', '2025-04-18', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d012', 7, 'p002', '2025-04-24', '2025-04-26', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d012', 12, 'p002', '2025-04-16', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d013', 8, 'p003', '2025-04-25', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d013', 13, 'p003', '2025-04-17', '2025-04-19', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d014', 9, 'p004', '2025-04-25', '2025-04-27', 'Elevated', '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d014', 14, 'p004', '2025-04-17', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d015', 10, 'p005', '2025-04-25', NULL, NULL, '2025-04-05 21:36:02', '2025-04-05 21:36:02'),
('d015', 15, 'p005', '2025-04-18', '2025-04-20', 'Normal', '2025-04-05 21:36:02', '2025-04-05 21:36:02');

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
('d001', 'd001', '2025-04-05', NULL, '2025-04-05 21:59:04', '2025-04-05 21:59:04'),
('d004', 'd004', '2025-04-06', NULL, '2025-04-05 22:00:19', '2025-04-05 22:00:19'),
('d005', 'd005', '2025-04-06', NULL, '2025-04-05 22:00:28', '2025-04-05 22:00:28'),
('d007', 'd007', '2025-04-05', NULL, '2025-04-05 21:59:56', '2025-04-05 21:59:56'),
('d008', 'd008', '2025-04-05', NULL, '2025-04-05 21:59:46', '2025-04-05 21:59:46');

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
('p001', 'Penicillin', 'Hypertension, Chronic Back Pain', '2025-04-05 21:30:09', '2025-04-05 21:30:09'),
('p002', 'Dust, Pollen', 'Asthma, High Blood Pressure', '2025-04-05 21:30:09', '2025-04-05 21:30:09'),
('p003', 'None', 'Diabetes, Recent Fracture', '2025-04-05 21:30:09', '2025-04-05 21:30:09'),
('p004', 'Seafood', 'Gastritis, Mild Asthma', '2025-04-05 21:30:09', '2025-04-05 21:30:09'),
('p005', 'Sulfa Drugs', 'Type 2 Diabetes, High Cholesterol', '2025-04-05 21:30:09', '2025-04-05 21:30:09');

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
('n001', 'Salma', 'Akter', 'salma.akter@gmail.com', '$2y$10$6HiAAHv9.CKF0EacHm2ZcOPE8qN3W0NqhwSbm5CC9YpgC9Rxls/zm', 'Female', '01911223344', '1990-05-10', 35000.00, 'Morning', 1, '2025-04-05 21:16:27', '2025-04-05 22:11:15', NULL),
('n002', 'Rashed', 'Khan', 'rashed.khan@gmail.com', 'pass123', 'Male', '01922334455', '1988-08-22', 36000.00, 'Noon', 2, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n003', 'Monira', 'Begum', 'monira.begum@gmail.com', 'pass123', 'Female', '01933445566', '1992-03-15', 34000.00, 'Evening', 3, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n004', 'Kamal', 'Hossain', 'kamal.hossain@gmail.com', 'pass123', 'Male', '01944556677', '1987-11-30', 37000.00, 'Night', 4, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n005', 'Rina', 'Parvin', 'rina.parvin@gmail.com', 'pass123', 'Female', '01955667788', '1991-07-12', 35000.00, 'Rotational', 5, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n006', 'Sohel', 'Rana', 'sohel.rana@gmail.com', 'pass123', 'Male', '01966778899', '1989-09-25', 36000.00, 'Morning', 1, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n007', 'Tania', 'Sultana', 'tania.sultana@gmail.com', 'pass123', 'Female', '01977889900', '1993-01-18', 34000.00, 'Noon', 2, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n008', 'Imran', 'Ali', 'imran.ali@gmail.com', 'pass123', 'Male', '01988990011', '1986-04-05', 37000.00, 'Evening', 3, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n009', 'Shabnam', 'Jahan', 'shabnam.jahan@gmail.com', 'pass123', 'Female', '01999001122', '1990-06-20', 35000.00, 'Night', 4, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n010', 'Firoz', 'Miah', 'firoz.miah@gmail.com', 'pass123', 'Male', '01600112233', '1988-12-15', 36000.00, 'Rotational', 5, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n011', 'Nasrin', 'Akter', 'nasrin.akter@gmail.com', 'pass123', 'Female', '01611223344', '1992-02-28', 34000.00, 'Morning', 1, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n012', 'Jahangir', 'Alam', 'jahangir.alam@gmail.com', 'pass123', 'Male', '01622334455', '1987-10-10', 37000.00, 'Noon', 2, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n013', 'Rokeya', 'Begum', 'rokeya.begum@gmail.com', 'pass123', 'Female', '01633445566', '1991-08-05', 35000.00, 'Evening', 3, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n014', 'Anwar', 'Hossain', 'anwar.hossain@gmail.com', 'pass123', 'Male', '01644556677', '1989-03-22', 36000.00, 'Night', 4, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL),
('n015', 'Shamima', 'Khatun', 'shamima.khatun@gmail.com', 'pass123', 'Female', '01655667788', '1993-07-17', 34000.00, 'Rotational', 5, '2025-04-05 21:16:27', '2025-04-05 21:16:27', NULL);

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
('n001', 1, 'p001', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n001', 9, 'p004', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n002', 2, 'p002', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n002', 11, 'p001', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n003', 4, 'p004', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n003', 13, 'p003', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n004', 6, 'p001', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n004', 15, 'p005', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n005', 8, 'p003', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n005', 17, 'p002', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n006', 9, 'p004', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n007', 11, 'p001', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n008', 13, 'p003', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n009', 15, 'p005', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n010', 17, 'p002', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n011', 19, 'p004', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n012', 1, 'p001', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n013', 3, 'p003', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n014', 5, 'p005', '2025-04-05 21:38:45', '2025-04-05 21:38:45'),
('n015', 7, 'p002', '2025-04-05 21:38:45', '2025-04-05 21:38:45');

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
('p001', 'Abdul', 'Karim', 'abdul.karim@gmail.com', '$2y$10$dL3rNGe1KhLBx6L17iAEx..2FaDeODiOwzv7B4J7E0n26M60TjGyK', 'Male', 'A+', '1990-04-15', '12/A', 'Mirpur Road', 'Dhaka', '1216', 'Bangladesh', '2025-04-05 21:17:08', '2025-04-05 22:15:00', NULL),
('p002', 'Sultana', 'Razia', 'sultana.razia@gmail.com', 'pass123', 'Female', 'B+', '1985-07-20', '45/B', 'Gulshan Avenue', 'Dhaka', '1212', 'Bangladesh', '2025-04-05 21:17:08', '2025-04-05 21:17:08', NULL),
('p003', 'Iqbal', 'Hossain', 'iqbal.hossain@gmail.com', 'pass123', 'Male', 'O+', '1978-09-10', '78/C', 'Chittagong Road', 'Chittagong', '4000', 'Bangladesh', '2025-04-05 21:17:08', '2025-04-05 21:17:08', NULL),
('p004', 'Rokeya', 'Sultana', 'rokeya.sultana@gmail.com', 'pass123', 'Female', 'AB-', '1995-12-25', '23/D', 'Sylhet Street', 'Sylhet', '3100', 'Bangladesh', '2025-04-05 21:17:08', '2025-04-05 21:17:08', NULL),
('p005', 'Moin', 'Uddin', 'moin.uddin@gmail.com', 'pass123', 'Male', 'A-', '1982-03-18', '56/E', 'Khulna Lane', 'Khulna', '9100', 'Bangladesh', '2025-04-05 21:17:08', '2025-04-05 21:17:08', NULL);

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
('p001', '01712345678', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p001', '01987654321', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p002', '01723456789', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p002', '01976543210', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p003', '01734567890', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p003', '01965432109', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p004', '01745678901', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p004', '01954321098', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p005', '01756789012', '2025-04-05 21:17:24', '2025-04-05 21:17:24'),
('p005', '01943210987', '2025-04-05 21:17:24', '2025-04-05 21:17:24');

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
('d001', 'Rahim', 'Hossain', 'rahim.hossain@gmail.com', '$2y$10$f/jTmvSchzaW5QzvSe81u.y7beXruJ1x4L.ohhdJFr9mr144xp6rO', 'Male', '01711223344', '1980-05-12', 150000.00, 1, '2025-04-05 21:15:05', '2025-04-05 22:01:28'),
('d002', 'Fatima', 'Begum', 'fatima.begum@gmail.com', 'pass123', 'Female', '01722334455', '1982-08-15', 145000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d003', 'Karim', 'Ahmed', 'karim.ahmed@gmail.com', 'pass123', 'Male', '01733445566', '1978-03-20', 160000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d004', 'Ayesha', 'Khatun', 'ayesha.khatun@gmail.com', 'pass123', 'Female', '01744556677', '1985-11-10', 140000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d005', 'Siddiq', 'Rahman', 'siddiq.rahman@gmail.com', 'pass123', 'Male', '01755667788', '1975-07-25', 155000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d006', 'Nusrat', 'Jahan', 'nusrat.jahan@gmail.com', 'pass123', 'Female', '01766778899', '1983-09-30', 148000.00, 1, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d007', 'Hasan', 'Miah', 'hasan.miah@gmail.com', 'pass123', 'Male', '01777889900', '1981-01-15', 152000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d008', 'Shirin', 'Akter', 'shirin.akter@gmail.com', 'pass123', 'Female', '01788990011', '1984-04-22', 142000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d009', 'Mahmud', 'Islam', 'mahmud.islam@gmail.com', 'pass123', 'Male', '01799001122', '1979-06-18', 158000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d010', 'Ruma', 'Sultana', 'ruma.sultana@gmail.com', 'pass123', 'Female', '01800112233', '1986-12-05', 146000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d011', 'Jamil', 'Uddin', 'jamil.uddin@gmail.com', 'pass123', 'Male', '01811223344', '1980-02-28', 150000.00, 1, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d012', 'Laila', 'Parvin', 'laila.parvin@gmail.com', 'pass123', 'Female', '01822334455', '1983-10-14', 144000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d013', 'Arif', 'Chowdhury', 'arif.chowdhury@gmail.com', 'pass123', 'Male', '01833445566', '1977-08-09', 157000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d014', 'Sabina', 'Yasmin', 'sabina.yasmin@gmail.com', 'pass123', 'Female', '01844556677', '1985-03-17', 143000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('d015', 'Tarek', 'Aziz', 'tarek.aziz@gmail.com', 'pass123', 'Male', '01855667788', '1982-07-21', 154000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n001', 'Salma', 'Akter', 'salma.akter@gmail.com', '$2y$10$6HiAAHv9.CKF0EacHm2ZcOPE8qN3W0NqhwSbm5CC9YpgC9Rxls/zm', 'Female', '01911223344', '1990-05-10', 35000.00, 1, '2025-04-05 21:15:05', '2025-04-05 22:10:17'),
('n002', 'Rashed', 'Khan', 'rashed.khan@gmail.com', 'pass123', 'Male', '01922334455', '1988-08-22', 36000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n003', 'Monira', 'Begum', 'monira.begum@gmail.com', 'pass123', 'Female', '01933445566', '1992-03-15', 34000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n004', 'Kamal', 'Hossain', 'kamal.hossain@gmail.com', 'pass123', 'Male', '01944556677', '1987-11-30', 37000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n005', 'Rina', 'Parvin', 'rina.parvin@gmail.com', 'pass123', 'Female', '01955667788', '1991-07-12', 35000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n006', 'Sohel', 'Rana', 'sohel.rana@gmail.com', 'pass123', 'Male', '01966778899', '1989-09-25', 36000.00, 1, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n007', 'Tania', 'Sultana', 'tania.sultana@gmail.com', 'pass123', 'Female', '01977889900', '1993-01-18', 34000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n008', 'Imran', 'Ali', 'imran.ali@gmail.com', 'pass123', 'Male', '01988990011', '1986-04-05', 37000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n009', 'Shabnam', 'Jahan', 'shabnam.jahan@gmail.com', 'pass123', 'Female', '01999001122', '1990-06-20', 35000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n010', 'Firoz', 'Miah', 'firoz.miah@gmail.com', 'pass123', 'Male', '01600112233', '1988-12-15', 36000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n011', 'Nasrin', 'Akter', 'nasrin.akter@gmail.com', 'pass123', 'Female', '01611223344', '1992-02-28', 34000.00, 1, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n012', 'Jahangir', 'Alam', 'jahangir.alam@gmail.com', 'pass123', 'Male', '01622334455', '1987-10-10', 37000.00, 2, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n013', 'Rokeya', 'Begum', 'rokeya.begum@gmail.com', 'pass123', 'Female', '01633445566', '1991-08-05', 35000.00, 3, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n014', 'Anwar', 'Hossain', 'anwar.hossain@gmail.com', 'pass123', 'Male', '01644556677', '1989-03-22', 36000.00, 4, '2025-04-05 21:15:05', '2025-04-05 21:15:05'),
('n015', 'Shamima', 'Khatun', 'shamima.khatun@gmail.com', 'pass123', 'Female', '01655667788', '1993-07-17', 34000.00, 5, '2025-04-05 21:15:05', '2025-04-05 21:15:05');

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
(1, 'Blood Sugar Test', 500.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(2, 'ECG', 1000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(3, 'X-Ray Chest', 800.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(4, 'MRI Brain', 5000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(5, 'CBC', 600.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(6, 'Lipid Profile', 1200.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(7, 'Ultrasound Abdomen', 1500.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(8, 'CT Scan Spine', 4000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(9, 'Liver Function Test', 900.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(10, 'Kidney Function Test', 1000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(11, 'Thyroid Profile', 1500.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(12, 'HbA1c', 800.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(13, 'Bone Density Test', 2000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(14, 'Echocardiogram', 2500.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(15, 'Malaria Test', 400.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(16, 'Urine Analysis', 300.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(17, 'Vitamin D Test', 1200.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(18, 'PSA Test', 1000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(19, 'Dengue Test', 1500.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07'),
(20, 'HIV Test', 2000.00, '2025-04-05 21:20:51', '2025-04-05 21:50:07');

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
(1, '2025-04-10', 'Paracetamol 500mg 1 tablet daily', 'Rest for 2 weeks', 'p001', 'd001', '2025-04-05 21:46:37'),
(2, '2025-04-10', 'Amlodipine 5mg 2 tablets twice daily', 'Avoid oily food', 'p002', 'd002', '2025-04-05 21:46:37'),
(3, '2025-04-11', 'Ceftriaxone 1g 1 injection weekly', 'Follow-up in 1 month', 'p003', 'd003', '2025-04-05 21:46:37'),
(4, '2025-04-12', 'Omeprazole 20mg 1 tablet at night', 'Drink plenty of water', 'p004', 'd004', '2025-04-05 21:46:37'),
(5, '2025-04-12', 'Metformin 500mg 1 capsule daily', 'Exercise regularly', 'p005', 'd005', '2025-04-05 21:46:37'),
(6, '2025-04-13', 'Ibuprofen 400mg 1 tablet twice daily', 'Avoid heavy lifting', 'p001', 'd006', '2025-04-05 21:46:37'),
(7, '2025-04-13', 'Losartan 50mg 1 tablet daily', 'Monitor blood pressure', 'p002', 'd007', '2025-04-05 21:46:37'),
(8, '2025-04-14', 'Amoxicillin 500mg 2 capsules thrice daily', 'Complete the course', 'p003', 'd008', '2025-04-05 21:46:37'),
(9, '2025-04-15', 'Salbutamol 4mg 1 tablet as needed', 'Avoid dust exposure', 'p004', 'd009', '2025-04-05 21:46:37'),
(10, '2025-04-15', 'Atorvastatin 20mg 1 tablet at night', 'Reduce fatty food intake', 'p005', 'd010', '2025-04-05 21:46:37'),
(11, '2025-04-16', 'Diclofenac 50mg 1 tablet twice daily', 'Take with food', 'p001', 'd011', '2025-04-05 21:46:37'),
(12, '2025-04-16', 'Furosemide 40mg 1 tablet daily', 'Check weight daily', 'p002', 'd012', '2025-04-05 21:46:37'),
(13, '2025-04-17', 'Ciprofloxacin 500mg 1 tablet twice daily', 'Stay hydrated', 'p003', 'd013', '2025-04-05 21:46:37'),
(14, '2025-04-18', 'Montelukast 10mg 1 tablet at night', 'Avoid allergens', 'p004', 'd014', '2025-04-05 21:46:37'),
(15, '2025-04-19', 'Gliclazide 80mg 1 tablet daily', 'Monitor blood sugar', 'p005', 'd015', '2025-04-05 21:46:37');

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
('d001', 'Rahim', 'Hossain', 'rahim.hossain@gmail.com', '$2y$10$f/jTmvSchzaW5QzvSe81u.y7beXruJ1x4L.ohhdJFr9mr144xp6rO', '2025-04-05 21:10:36', '2025-04-05 22:01:28'),
('d002', 'Fatima', 'Begum', 'fatima.begum@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d003', 'Karim', 'Ahmed', 'karim.ahmed@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d004', 'Ayesha', 'Khatun', 'ayesha.khatun@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d005', 'Siddiq', 'Rahman', 'siddiq.rahman@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d006', 'Nusrat', 'Jahan', 'nusrat.jahan@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d007', 'Hasan', 'Miah', 'hasan.miah@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d008', 'Shirin', 'Akter', 'shirin.akter@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d009', 'Mahmud', 'Islam', 'mahmud.islam@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d010', 'Ruma', 'Sultana', 'ruma.sultana@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d011', 'Jamil', 'Uddin', 'jamil.uddin@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d012', 'Laila', 'Parvin', 'laila.parvin@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d013', 'Arif', 'Chowdhury', 'arif.chowdhury@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d014', 'Sabina', 'Yasmin', 'sabina.yasmin@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('d015', 'Tarek', 'Aziz', 'tarek.aziz@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n001', 'Salma', 'Akter', 'salma.akter@gmail.com', '$2y$10$6HiAAHv9.CKF0EacHm2ZcOPE8qN3W0NqhwSbm5CC9YpgC9Rxls/zm', '2025-04-05 21:10:36', '2025-04-05 22:10:17'),
('n002', 'Rashed', 'Khan', 'rashed.khan@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n003', 'Monira', 'Begum', 'monira.begum@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n004', 'Kamal', 'Hossain', 'kamal.hossain@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n005', 'Rina', 'Parvin', 'rina.parvin@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n006', 'Sohel', 'Rana', 'sohel.rana@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n007', 'Tania', 'Sultana', 'tania.sultana@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n008', 'Imran', 'Ali', 'imran.ali@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n009', 'Shabnam', 'Jahan', 'shabnam.jahan@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n010', 'Firoz', 'Miah', 'firoz.miah@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n011', 'Nasrin', 'Akter', 'nasrin.akter@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n012', 'Jahangir', 'Alam', 'jahangir.alam@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n013', 'Rokeya', 'Begum', 'rokeya.begum@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n014', 'Anwar', 'Hossain', 'anwar.hossain@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('n015', 'Shamima', 'Khatun', 'shamima.khatun@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('p001', 'Abdul', 'Karim', 'abdul.karim@gmail.com', '$2y$10$dL3rNGe1KhLBx6L17iAEx..2FaDeODiOwzv7B4J7E0n26M60TjGyK', '2025-04-05 21:10:36', '2025-04-05 22:11:44'),
('p002', 'Sultana', 'Razia', 'sultana.razia@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('p003', 'Iqbal', 'Hossain', 'iqbal.hossain@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('p004', 'Rokeya', 'Sultana', 'rokeya.sultana@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36'),
('p005', 'Moin', 'Uddin', 'moin.uddin@gmail.com', 'pass123', '2025-04-05 21:10:36', '2025-04-05 21:10:36');

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
  MODIFY `appt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bill_detail`
--
ALTER TABLE `bill_detail`
  MODIFY `bill_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  MODIFY `trtplan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
