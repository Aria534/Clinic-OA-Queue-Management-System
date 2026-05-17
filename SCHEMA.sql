-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 03:25 AM
-- Server version: 8.0.45
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `patient_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `patient_email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `queue_number` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` enum('pending','confirmed','in_queue','serving','completed','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `patient_name`, `patient_email`, `service_id`, `appointment_date`, `appointment_time`, `queue_number`, `status`, `started_at`, `finished_at`, `notes`, `created_at`, `updated_at`) VALUES
(20, NULL, 'Leizl Casilao', NULL, 2, '2026-04-07', '10:08:04', 'D-001', 'completed', '2026-04-07 10:09:23', '2026-04-07 10:25:22', NULL, '2026-04-07 10:08:04', '2026-04-07 10:25:22'),
(21, NULL, 'wertyuiokjhgfd', NULL, 2, '2026-04-07', '10:10:09', 'D-002', 'completed', '2026-04-07 10:25:25', '2026-04-07 10:25:29', NULL, '2026-04-07 10:10:09', '2026-04-07 10:25:29'),
(22, NULL, '\';lkjhgvfcx', NULL, 1, '2026-04-07', '10:10:35', 'G-001', 'completed', '2026-04-07 10:25:29', '2026-04-07 10:26:05', NULL, '2026-04-07 10:10:35', '2026-04-07 10:26:05'),
(23, NULL, 'Jela Jeminez', NULL, 3, '2026-04-07', '10:25:01', 'B-001', 'completed', '2026-04-07 10:25:22', '2026-04-07 10:25:25', NULL, '2026-04-07 10:25:01', '2026-04-07 10:25:25'),
(24, NULL, 'Leizl C. Lintag', NULL, 1, '2026-04-07', '10:26:59', 'G-002', 'serving', NULL, NULL, NULL, '2026-04-07 10:26:59', '2026-04-07 10:27:14'),
(25, NULL, 'Hazel Faith Acierto', NULL, 2, '2026-04-07', '10:28:14', 'D-003', 'serving', NULL, NULL, NULL, '2026-04-07 10:28:14', '2026-04-07 10:28:30'),
(26, NULL, 'Aira Verola', NULL, 3, '2026-04-07', '10:29:00', 'B-002', 'serving', NULL, NULL, NULL, '2026-04-07 10:29:00', '2026-04-07 10:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-03-28-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1774667519, 1),
(2, '2026-03-28-000002', 'App\\Database\\Migrations\\CreateServicesTable', 'default', 'App', 1774667519, 1),
(3, '2026-03-28-000003', 'App\\Database\\Migrations\\CreateAppointmentsTable', 'default', 'App', 1774667519, 1),
(4, '2026-03-28-000004', 'App\\Database\\Migrations\\CreateQueueLogsTable', 'default', 'App', 1774667519, 1),
(5, '2026-03-28-000005', 'App\\Database\\Migrations\\CreateSchedulesTable', 'default', 'App', 1774667519, 1);

-- --------------------------------------------------------

--
-- Table structure for table `queue_logs`
--

CREATE TABLE `queue_logs` (
  `id` int UNSIGNED NOT NULL,
  `appointment_id` int UNSIGNED NOT NULL,
  `action` enum('called','serving','completed','skipped') COLLATE utf8mb4_general_ci NOT NULL,
  `acted_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_logs`
--

INSERT INTO `queue_logs` (`id`, `appointment_id`, `action`, `acted_by`, `created_at`) VALUES
(23, 20, 'serving', 1, '2026-04-07 10:09:23'),
(24, 20, 'completed', 1, '2026-04-07 10:25:22'),
(25, 23, 'serving', 1, '2026-04-07 10:25:22'),
(26, 23, 'completed', 1, '2026-04-07 10:25:25'),
(27, 21, 'serving', 1, '2026-04-07 10:25:25'),
(28, 21, 'completed', 1, '2026-04-07 10:25:29'),
(29, 22, 'serving', 1, '2026-04-07 10:25:29'),
(30, 22, 'completed', 1, '2026-04-07 10:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `queue_tickets`
--

CREATE TABLE `queue_tickets` (
  `id` int UNSIGNED NOT NULL,
  `queue_number` int UNSIGNED NOT NULL,
  `patient_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `status` enum('waiting','serving','completed','skipped') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'waiting',
  `date` date NOT NULL,
  `called_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_tickets`
--

INSERT INTO `queue_tickets` (`id`, `queue_number`, `patient_name`, `service_id`, `status`, `date`, `called_at`, `completed_at`, `created_at`) VALUES
(1, 1, 'Aira P. Verola', 1, 'serving', '2026-05-14', '2026-05-14 19:28:43', NULL, '2026-05-14 19:28:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int UNSIGNED NOT NULL,
  `day_of_week` tinyint(1) NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `duration` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `department_code` varchar(5) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'G'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `duration`, `is_active`, `created_at`, `updated_at`, `department_code`) VALUES
(1, 'General Consultation', 'General check-up with a doctor', 30, 1, '2026-03-28 03:12:34', '2026-03-28 03:12:34', 'G'),
(2, 'Dental Check-up', 'Oral health examination', 45, 1, '2026-03-28 03:12:34', '2026-03-28 03:12:34', 'D'),
(3, 'Blood Test', 'Laboratory blood work', 15, 1, '2026-03-28 03:12:34', '2026-03-28 03:12:34', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('patient','admin','doctor') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'patient',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@clinic.com', '$2y$10$nMo52A81KUB6mnWd/1nSp.u.Erkrbh7RHYfQKdhDlJ/JKb.WN4vN2', NULL, 'admin', '2026-03-28 03:12:34', '2026-03-28 03:12:34'),
(2, 'Aira Verola', 'airaverola@gmail.com', '$2y$10$rdtWlubmZ0qOpdirXiIAu.h46fd.CL8fKWSuyODj7SFwF8DFKezZ2', '09682834735', 'patient', '2026-03-28 12:39:56', '2026-03-28 12:39:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_user_id_foreign` (`user_id`),
  ADD KEY `appointments_service_id_foreign` (`service_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queue_logs`
--
ALTER TABLE `queue_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queue_logs_appointment_id_foreign` (`appointment_id`);

--
-- Indexes for table `queue_tickets`
--
ALTER TABLE `queue_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_date_status` (`date`,`status`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `queue_logs`
--
ALTER TABLE `queue_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `queue_tickets`
--
ALTER TABLE `queue_tickets`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `queue_logs`
--
ALTER TABLE `queue_logs`
  ADD CONSTRAINT `queue_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
