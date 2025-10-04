-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2025 at 03:39 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progresstracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `description` text,
  `updated_at` datetime DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `office` enum('Johor','Cyberjaya','Seremban','Ipoh','Pengkalan Hulu') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone_number`, `approved`, `description`, `updated_at`, `staff_id`, `office`) VALUES
(8, 'abong', '0137631976', 1, 'setel', '2025-09-21 04:57:00', 3, 'Johor');

-- --------------------------------------------------------

--
-- Table structure for table `client_description_history`
--

CREATE TABLE `client_description_history` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `old_description` text,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client_description_history`
--

INSERT INTO `client_description_history` (`id`, `client_id`, `old_description`, `changed_at`) VALUES
(7, 8, 'setel', '2025-09-21 04:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `phase_images`
--

CREATE TABLE `phase_images` (
  `id` int NOT NULL,
  `phase_id` int NOT NULL,
  `image` longblob NOT NULL,
  `image_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_phases`
--

CREATE TABLE `project_phases` (
  `id` int NOT NULL,
  `client_id` int DEFAULT NULL,
  `phase_name` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('marketing','site_supervisor') NOT NULL,
  `office` enum('Johor','Cyberjaya','Seremban','Ipoh','Pengkalan Hulu') NOT NULL DEFAULT 'Johor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `office`) VALUES
(3, 'danziq', '$2y$10$ZFJasiQoGI1QKVxvHDfymuepD9nn7fFpXPLboa5zae75JVsPhHUqW', 'marketing', 'Johor'),
(4, 'yencet', '$2y$10$Z1UYQgp7BOsMfXoXhLW7NO/qxRXUyPiC61qJstlt67celfMJxrxLe', 'marketing', 'Cyberjaya'),
(5, 'polis', '$2y$10$mdJETLwzmQ.x83E9DUb3seFwth.B2KLpC2OqzKn.iL9ylCQAOv..K', 'site_supervisor', 'Ipoh'),
(6, 'ainaa', '$2y$10$ztpJmpvEx8h6J8HYg6i9Q.0at8jylgLQFEVNCpwGrhCXB7BHWQkES', 'site_supervisor', 'Johor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `fk_staff` (`staff_id`);

--
-- Indexes for table `client_description_history`
--
ALTER TABLE `client_description_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `phase_images`
--
ALTER TABLE `phase_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phase_id` (`phase_id`);

--
-- Indexes for table `project_phases`
--
ALTER TABLE `project_phases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `client_description_history`
--
ALTER TABLE `client_description_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `phase_images`
--
ALTER TABLE `phase_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project_phases`
--
ALTER TABLE `project_phases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_staff` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `client_description_history`
--
ALTER TABLE `client_description_history`
  ADD CONSTRAINT `client_description_history_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phase_images`
--
ALTER TABLE `phase_images`
  ADD CONSTRAINT `phase_images_ibfk_1` FOREIGN KEY (`phase_id`) REFERENCES `project_phases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_phases`
--
ALTER TABLE `project_phases`
  ADD CONSTRAINT `project_phases_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
