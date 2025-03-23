-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 04:31 PM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appt_id` int(11) NOT NULL,
  `appt_date` date NOT NULL,
  `appt_time` time NOT NULL,
  `patient_user_id` varchar(11) DEFAULT NULL,
  `doctor_user_id` varchar(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appt_id`, `appt_date`, `appt_time`, `patient_user_id`, `doctor_user_id`, `updated_at`) VALUES
(1, '2025-03-04', '13:02:22', 'p001', 'd001', '2025-03-23 15:28:47'),
(2, '2025-03-12', '11:38:16', 'p002', 'd001', '2025-03-23 15:28:58'),
(3, '2025-03-12', '20:39:34', 'p003', 'd002', '2025-03-23 15:29:09'),
(4, '2024-10-09', '14:39:53', 'p004', 'd002', '2025-03-23 15:29:36');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `bill_date` date NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `due` decimal(10,2) DEFAULT NULL,
  `bill_status` enum('Paid','Unpaid','Pending') DEFAULT NULL,
  `patient_user_id` varchar(20) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_detail`
--

CREATE TABLE `bill_detail` (
  `bill_detail_id` int(11) NOT NULL,
  `charge_amount` decimal(10,2) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `doctor_user_id` varchar(20) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkup`
--

CREATE TABLE `checkup` (
  `appt_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `doctor_user_id` varchar(20) NOT NULL,
  `appt_status` enum('Scheduled','Ongoing','Completed','Cancelled','Missed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkup`
--

INSERT INTO `checkup` (`appt_id`, `patient_user_id`, `doctor_user_id`, `appt_status`, `created_at`, `updated_at`) VALUES
(1, 'p001', 'd001', 'Ongoing', '2025-03-22 03:04:03', '2025-03-22 05:24:42'),
(2, 'p001', 'd002', 'Ongoing', '2025-03-22 03:40:37', '2025-03-22 03:40:37'),
(3, 'p002', 'd002', 'Ongoing', '2025-03-22 03:40:50', '2025-03-22 03:40:50'),
(4, 'p002', 'd001', 'Ongoing', '2025-03-22 03:41:25', '2025-03-22 04:19:03');

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
(1, 'Orthopedics', NULL, NULL, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
(2, 'Dermatology', NULL, NULL, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
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
  `availability` varchar(20) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `phone`, `dob`, `salary`, `doc_fee`, `specialization`, `availability`, `dept_id`, `created_at`, `updated_at`) VALUES
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', 'Male', '01812345678', '1985-02-25', 60000.00, 700.00, 'Orthopedics', '9AM - 5PM', 1, '2025-03-19 16:31:47', '2025-03-22 00:49:02'),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', 'Female', '01887654321', '1988-06-15', 65000.00, 750.00, 'Dermatology', '10AM - 4PM', 2, '2025-03-19 16:31:47', '2025-03-19 16:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `doc_test_patient`
--

CREATE TABLE `doc_test_patient` (
  `doctor_user_id` varchar(20) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `order_date` date NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('p002', 'Penicillin', 'Diabetes', '2025-03-19 16:33:59', '2025-03-19 16:33:59');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nurse_test_patient`
--

CREATE TABLE `nurse_test_patient` (
  `nurse_user_id` varchar(20) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_user_id` varchar(20) NOT NULL,
  `performed_date` date NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `hno` varchar(10) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`user_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `blood_group`, `dob`, `hno`, `street`, `city`, `zip`, `country`, `created_at`, `updated_at`) VALUES
('p001', 'Shanto', 'Ahmed', 'shanto.ahmed@gmail.com', 'password123', 'Male', 'A+', '1992-05-20', '56', 'Gulshan', 'Dhaka', '1212', 'Bangladesh', '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('p002', 'Sumi', 'Parveen', 'sumi.parveen@gmail.com', 'password123', 'Female', 'B-', '1995-03-15', '24', 'Banani', 'Dhaka', '1213', 'Bangladesh', '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('p003', 'Samiul', 'Islam', 'samiulsamin.17@gmail.com', '$2y$10$o1NAiYsMM4XNs7Su67l9MeWrxgFcYhDaxiAs5kBZ0Rqqit9m/zFzG', 'Male', 'A+', '2003-04-18', '17/A', 'Shantibagh', 'Dhaka', '1217', 'Bangladesh', '2025-03-21 06:47:11', '2025-03-23 13:30:23'),
('p004', 'Xaima', 'Zaman', 'xaima.nsu@gmail.com', '$2y$10$s6HyahngF6KDhSyI1qiLO.JMPdbH24vxj/4rBbX3mlKfDIgLCdsyu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 10:51:49', '2025-03-21 10:51:49');

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

-- --------------------------------------------------------

--
-- Table structure for table `patient_test`
--

CREATE TABLE `patient_test` (
  `patient_user_id` varchar(20) NOT NULL,
  `test_id` int(11) NOT NULL,
  `test_date` date NOT NULL,
  `result` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', 'Male', '01812345678', '1985-02-25', 60000.00, 1, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', 'Female', '01887654321', '1988-06-15', 65000.00, 2, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('n001', 'Anika', 'Rahman', 'anika.rahman@gmail.com', 'password123', 'Female', '01911223344', '1993-11-10', 25000.00, 3, '2025-03-19 16:31:47', '2025-03-19 16:31:47'),
('n002', 'Mahi', 'Sultana', 'mahi.sultana@gmail.com', 'password123', 'Female', '01922334455', '1990-08-22', 28000.00, 3, '2025-03-19 16:31:47', '2025-03-19 16:31:47');

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
(2, '2025-03-02', 'Paracetamol 500mg, every 6 hours as needed for pain', 'Monitor temperature and stay hydrated.', 'p002', 'd002', '2025-03-19 16:33:59');

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
('d001', 'Dr. Kamal', 'Hossain', 'dr.kamal.hossain@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('d002', 'Dr. Shilpi', 'Begum', 'dr.shilpi.begum@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('n001', 'Anika', 'Rahman', 'anika.rahman@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('n002', 'Mahi', 'Sultana', 'mahi.sultana@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p001', 'Shanto', 'Ahmed', 'shanto.ahmed@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p002', 'Sumi', 'Parveen', 'sumi.parveen@gmail.com', 'password123', '2025-03-19 16:17:03', '2025-03-19 16:17:03'),
('p003', 'Samiul', 'Islam', 'samiulsamin.17@gmail.com', '$2y$10$o1NAiYsMM4XNs7Su67l9MeWrxgFcYhDaxiAs5kBZ0Rqqit9m/zFzG', '2025-03-21 06:47:11', '2025-03-21 06:47:11'),
('p004', 'Xaima', 'Zaman', 'xaima.nsu@gmail.com', '$2y$10$s6HyahngF6KDhSyI1qiLO.JMPdbH24vxj/4rBbX3mlKfDIgLCdsyu', '2025-03-21 10:51:49', '2025-03-21 10:51:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appt_id`),
  ADD KEY `fk_patient_user` (`patient_user_id`),
  ADD KEY `fk_doctor_user` (`doctor_user_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `patient_user_id` (`patient_user_id`);

--
-- Indexes for table `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD PRIMARY KEY (`bill_detail_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `doctor_user_id` (`doctor_user_id`),
  ADD KEY `test_id` (`test_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `doc_test_patient`
--
ALTER TABLE `doc_test_patient`
  ADD PRIMARY KEY (`doctor_user_id`,`test_id`,`patient_user_id`,`order_date`),
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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `nurse_test_patient`
--
ALTER TABLE `nurse_test_patient`
  ADD PRIMARY KEY (`nurse_user_id`,`test_id`,`patient_user_id`,`performed_date`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `patient_user_id` (`patient_user_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `patient_mobile`
--
ALTER TABLE `patient_mobile`
  ADD PRIMARY KEY (`patient_user_id`,`mobile`);

--
-- Indexes for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD PRIMARY KEY (`patient_user_id`,`test_id`,`test_date`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
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
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_detail`
--
ALTER TABLE `bill_detail`
  MODIFY `bill_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `treatmentplan`
--
ALTER TABLE `treatmentplan`
  MODIFY `trtplan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_doctor_user` FOREIGN KEY (`doctor_user_id`) REFERENCES `doctor` (`user_id`),
  ADD CONSTRAINT `fk_patient_user` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`);

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD CONSTRAINT `bill_detail_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bill` (`bill_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_detail_ibfk_2` FOREIGN KEY (`doctor_user_id`) REFERENCES `doctor` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_detail_ibfk_3` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`) ON DELETE CASCADE;

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
-- Constraints for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD CONSTRAINT `patient_test_ibfk_1` FOREIGN KEY (`patient_user_id`) REFERENCES `patient` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_test_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`) ON DELETE CASCADE;

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
