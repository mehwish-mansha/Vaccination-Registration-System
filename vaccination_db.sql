-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 03:00 PM
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
-- Database: `vaccination_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `preferred_date` date NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `admin_remark` varchar(255) DEFAULT NULL,
  `result` enum('pending','vaccinated','not_vaccinated') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `child_id`, `vaccine_id`, `hospital_id`, `preferred_date`, `status`, `admin_remark`, `result`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, '2026-12-08', 'pending', NULL, 'pending', '2026-11-13 10:55:24', '2026-11-13 10:55:24'),
(2, 1, 4, 1, '2026-12-08', 'pending', NULL, 'pending', '2026-11-13 10:56:08', '2026-11-13 10:56:08'),
(3, 1, 2, 1, '2026-11-22', 'approved', 'I APPROVE!', 'pending', '2026-11-13 10:57:04', '2026-11-13 11:09:04'),
(4, 1, 6, 4, '2026-12-31', 'approved', '...', 'vaccinated', '2026-11-13 11:18:58', '2026-11-13 11:21:11'),
(5, 1, 7, 3, '2026-12-31', 'pending', NULL, 'pending', '2026-11-13 11:35:31', '2026-11-13 11:35:31'),
(6, 1, 8, 3, '2026-12-22', 'pending', NULL, 'pending', '2026-11-13 11:40:52', '2026-11-13 11:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Boy','Girl') NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`id`, `parent_id`, `name`, `dob`, `gender`, `blood_group`, `guardian_name`, `created_at`) VALUES
(1, 2, 'child1', '2024-12-12', 'Boy', 'AB+', 'sana', '2026-11-13 10:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(80) NOT NULL DEFAULT 'Karachi',
  `phone` varchar(20) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `user_id`, `name`, `address`, `city`, `phone`, `active`, `created_at`) VALUES
(1, NULL, 'Aga Khan University Hospital', 'Stadium Road', 'Karachi', '021-34930051', 1, '2026-11-13 10:21:50'),
(2, NULL, 'Indus Hospital', 'Korangi Crossing', 'Karachi', '021-35112709', 1, '2026-11-13 10:21:50'),
(3, NULL, 'Civil Hospital Karachi', 'Baba-e-Urdu Road', 'Karachi', '021-99215740', 1, '2026-11-13 10:21:50'),
(4, 3, 'Saifee Hospital', 'St-1, Block F North Nazimabad Town', 'Karachi', '012302183093', 1, '2026-11-13 10:39:43'),
(5, NULL, 'E-VaxCare Test Hospital', 'Main Road, Karachi', 'Karachi', '03111234567', 1, '2026-11-13 13:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','parent','hospital') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `created_at`) VALUES
(1, 'System Admin', 'admin@vms.local', NULL, '$2y$10$ez0RpWs8G.NLXI6FRQXUjeegag8tWI4mZzTxf3cp/aY0Y.GQcP6yu', 'admin', '2026-11-13 10:21:50'),
(2, 'sana', 'sana@gmail.com', '93576767887', '$2y$10$Lhefs6AoA5BU8Pbd7nKREeyx0qH4kJLuEPSZBXAaCcF60V3cxDspm', 'parent', '2026-11-13 10:35:59'),
(3, 'Saim', 'saim@gmail.com', '012302183093', '$2y$10$G97c/c7CyVBDnCWx0i/6deMzJtxXDfhw.C78.rGhKDNregGqsxQIS', 'hospital', '2026-11-13 10:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_records`
--

CREATE TABLE `vaccination_records` (
  `id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `given_on` date NOT NULL,
  `status` enum('vaccinated','not_vaccinated') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccination_records`
--

INSERT INTO `vaccination_records` (`id`, `child_id`, `vaccine_id`, `hospital_id`, `appointment_id`, `given_on`, `status`) VALUES
(1, 1, 6, 4, 4, '2026-11-13', 'vaccinated');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `age_weeks` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`id`, `name`, `age_weeks`, `description`, `available`) VALUES
(1, 'BCG', 0, 'Protects against tuberculosis', 1),
(2, 'OPV-0', 0, 'Oral polio vaccine, birth dose', 1),
(3, 'Pentavalent-1', 6, 'DPT, Hepatitis B and Hib, first dose', 1),
(4, 'Pentavalent-2', 10, 'DPT, Hepatitis B and Hib, second dose', 1),
(5, 'Pentavalent-3', 14, 'DPT, Hepatitis B and Hib, third dose', 1),
(6, 'IPV', 14, 'Injectable polio vaccine', 1),
(7, 'Measles-1', 39, 'Measles and rubella, first dose', 1),
(8, 'Measles-2', 65, 'Measles and rubella, second dose', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vaccine_id` (`vaccine_id`),
  ADD KEY `idx_appt_status` (`status`,`result`),
  ADD KEY `idx_appt_child` (`child_id`),
  ADD KEY `idx_appt_hospital` (`hospital_id`);

--
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_children_parent` (`parent_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vaccination_records`
--
ALTER TABLE `vaccination_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `vaccine_id` (`vaccine_id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `idx_rec_child` (`child_id`),
  ADD KEY `idx_rec_date` (`given_on`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vaccination_records`
--
ALTER TABLE `vaccination_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`);

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vaccination_records`
--
ALTER TABLE `vaccination_records`
  ADD CONSTRAINT `vaccination_records_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vaccination_records_ibfk_2` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`),
  ADD CONSTRAINT `vaccination_records_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  ADD CONSTRAINT `vaccination_records_ibfk_4` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
