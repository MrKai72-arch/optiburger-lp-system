-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 04:32 PM
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
-- Database: `optiburger_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `burgers`
--

CREATE TABLE `burgers` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `taste_score` int(11) NOT NULL,
  `calories` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `protein` int(11) DEFAULT 0,
  `fat` int(11) DEFAULT 0,
  `carbs` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `burgers`
--

INSERT INTO `burgers` (`id`, `code`, `name`, `price`, `taste_score`, `calories`, `image_path`, `protein`, `fat`, `carbs`) VALUES
(1, 'x1', 'Ramly Special', 6.00, 9, 400, 'images/burgers/ramly.png', 25, 30, 45),
(2, 'x2', 'Double Cheese', 5.00, 8, 500, 'images/burgers/cheese.png', 28, 35, 40),
(3, 'x3', 'Crispy Chicken', 4.50, 7, 350, 'images/burgers/chicken.png', 30, 25, 35),
(4, 'x4', 'Veggie Burger', 4.00, 5, 300, 'images/burgers/veggie.png', 15, 15, 50);

-- --------------------------------------------------------

--
-- Table structure for table `lp_problems`
--

CREATE TABLE `lp_problems` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `problem_title` varchar(100) DEFAULT NULL,
  `budget` decimal(10,2) NOT NULL,
  `max_burgers` int(11) DEFAULT 2,
  `objective_type` varchar(20) DEFAULT 'maximize',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lp_problems`
--

INSERT INTO `lp_problems` (`id`, `user_id`, `problem_title`, `budget`, `max_burgers`, `objective_type`, `created_at`) VALUES
(1, 4, 'My Lunch Budget', 20.00, 2, 'maximize', '2026-05-12 17:35:30'),
(2, 4, 'my luch journey', 20.00, 1, 'maximize', '2026-05-19 03:48:07'),
(3, 4, 'My Lunch Budget', 15.00, 2, 'maximize', '2026-05-19 14:50:19'),
(4, 4, ' luunchy', 39.50, 3, 'maximize', '2026-05-19 18:19:46'),
(5, 4, 'My Lunch Budget', 15.00, 2, 'maximize', '2026-06-03 03:15:26'),
(6, 4, 'My Lunch Budget', 15.00, 2, 'maximize', '2026-06-03 03:16:31'),
(7, 4, 'my luch journey', 20.00, 3, 'maximize', '2026-06-04 17:32:37'),
(8, 4, 'My Lunch Budget', 15.00, 2, 'maximize', '2026-06-06 15:35:06'),
(9, 4, 'my luch journey', 15.00, 2, 'maximize', '2026-06-07 14:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `lp_solutions`
--

CREATE TABLE `lp_solutions` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `burger_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `optimal_value` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lp_solutions`
--

INSERT INTO `lp_solutions` (`id`, `problem_id`, `burger_id`, `quantity`, `optimal_value`) VALUES
(1, 1, 3, 1, 12.00),
(2, 1, 4, 1, 12.00),
(3, 2, 1, 1, 9.00),
(4, 3, 1, 1, 17.00),
(5, 3, 2, 1, 17.00),
(6, 4, 1, 1, 24.00),
(7, 4, 2, 1, 24.00),
(8, 4, 3, 1, 24.00),
(9, 5, 1, 1, 17.00),
(10, 5, 2, 1, 17.00),
(11, 6, 1, 1, 17.00),
(12, 6, 2, 1, 17.00),
(13, 7, 1, 1, 22.00),
(14, 7, 2, 1, 22.00),
(15, 7, 4, 1, 22.00),
(16, 8, 1, 1, 17.00),
(17, 8, 2, 1, 17.00),
(18, 9, 1, 1, 17.00),
(19, 9, 2, 1, 17.00);

-- --------------------------------------------------------

--
-- Table structure for table `saved_plans`
--

CREATE TABLE `saved_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `burger_ids` text DEFAULT NULL,
  `total_taste` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `created_at`) VALUES
(4, 'student1', '$2y$10$Ky2j77devgeavAIE4BNN5uShRwj.qZ.BUalUISGRtgSdl4sSOZx8q', 'Ali bin Ahmad', '2026-05-12 11:20:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `burgers`
--
ALTER TABLE `burgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lp_problems`
--
ALTER TABLE `lp_problems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lp_solutions`
--
ALTER TABLE `lp_solutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`),
  ADD KEY `burger_id` (`burger_id`);

--
-- Indexes for table `saved_plans`
--
ALTER TABLE `saved_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `burgers`
--
ALTER TABLE `burgers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lp_problems`
--
ALTER TABLE `lp_problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lp_solutions`
--
ALTER TABLE `lp_solutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `saved_plans`
--
ALTER TABLE `saved_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lp_problems`
--
ALTER TABLE `lp_problems`
  ADD CONSTRAINT `lp_problems_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lp_solutions`
--
ALTER TABLE `lp_solutions`
  ADD CONSTRAINT `lp_solutions_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `lp_problems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lp_solutions_ibfk_2` FOREIGN KEY (`burger_id`) REFERENCES `burgers` (`id`);

--
-- Constraints for table `saved_plans`
--
ALTER TABLE `saved_plans`
  ADD CONSTRAINT `saved_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
