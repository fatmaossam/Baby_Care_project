-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2026 at 02:36 AM
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
-- Database: `baby_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `babies`
--

CREATE TABLE `babies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `birth_weight` decimal(5,2) DEFAULT NULL,
  `birth_height` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `babies`
--

INSERT INTO `babies` (`id`, `user_id`, `name`, `birth_date`, `gender`, `birth_weight`, `birth_height`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tiem', '2025-06-15', 'Male', 3.20, 50.00, 'Test baby for development', '2026-09-07 18:41:54', '2026-09-07 18:41:54'),
(2, 3, 'Fares', '2023-09-15', 'Boy', 3.60, 50.00, 'nop', '2026-09-09 04:11:50', '2026-09-09 04:11:50'),
(4, 3, 'Yassin', '2020-12-26', 'Boy', 4.00, 50.00, '', '2026-09-09 06:43:28', '2026-09-09 06:43:28'),
(6, 4, 'Yassin Soliman', '2020-12-27', 'Boy', 3.00, 50.00, '', '2026-09-10 01:58:19', '2026-09-10 01:58:19'),
(7, 4, 'TIA', '2026-05-10', 'Girl', 4.00, 40.00, 'My little girl', '2026-09-10 02:03:57', '2026-09-10 02:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `baby_allergies`
--

CREATE TABLE `baby_allergies` (
  `id` int(11) NOT NULL,
  `baby_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `reaction_type` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baby_allergies`
--

INSERT INTO `baby_allergies` (`id`, `baby_id`, `food_id`, `reaction_type`, `notes`, `created_at`) VALUES
(1, 1, 3, 'Vomiting', 'feeling bad', '2026-09-08 01:55:34'),
(2, 1, 10, 'Skin rash', '', '2026-09-08 02:09:32'),
(3, 6, 4, 'Diarrhea', '', '2026-09-10 01:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `baby_meals`
--

CREATE TABLE `baby_meals` (
  `id` int(11) NOT NULL,
  `baby_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `meal_type` varchar(50) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `reaction` varchar(100) DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baby_meals`
--

INSERT INTO `baby_meals` (`id`, `baby_id`, `food_id`, `meal_type`, `quantity`, `reaction`, `date_time`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'Solid food', '', '', '2026-09-08 01:00:00', '', '2026-09-08 01:17:42', '2026-09-08 01:17:42'),
(2, 2, 10, 'Solid food', '', 'happy', '2026-09-09 09:00:00', '', '2026-09-09 06:34:24', '2026-09-09 06:34:24'),
(3, 2, 10, 'Solid food', '', 'happy', '2026-09-09 14:00:00', '', '2026-09-09 18:46:24', '2026-09-09 18:46:24'),
(4, 4, 9, 'Solid food', '', 'love it', '2026-09-09 06:44:00', '', '2026-09-10 00:38:27', '2026-09-10 00:38:27'),
(6, 6, 12, 'Solid food', '', 'happy', '2026-09-10 17:01:00', '', '2026-09-10 02:00:46', '2026-09-10 02:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `baby_vaccinations`
--

CREATE TABLE `baby_vaccinations` (
  `id` int(11) NOT NULL,
  `baby_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `date_taken` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baby_vaccinations`
--

INSERT INTO `baby_vaccinations` (`id`, `baby_id`, `vaccine_id`, `due_date`, `date_taken`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-06-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(2, 1, 2, '2025-06-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(3, 1, 3, '2025-06-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(4, 1, 4, '2025-08-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(5, 1, 5, '2025-08-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(6, 1, 4, '2025-10-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(7, 1, 5, '2025-10-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(8, 1, 4, '2025-12-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(9, 1, 5, '2025-12-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(10, 1, 6, '2026-03-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(11, 1, 6, '2026-06-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(12, 1, 7, '2026-06-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(13, 1, 6, '2026-12-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(14, 1, 7, '2026-12-15', '2026-09-07', '', '2026-09-08 03:19:33', '2026-09-08 04:41:29'),
(15, 1, 8, '2026-12-15', NULL, NULL, '2026-09-08 03:19:33', '2026-09-08 03:19:33'),
(16, 6, 5, '2026-06-11', '2026-06-11', 'take it', '2026-09-10 02:28:17', '2026-09-10 02:28:17'),
(20, 7, 9, '2026-09-10', '2026-09-10', '', '2026-09-10 03:17:01', '2026-09-10 03:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `daily_records`
--

CREATE TABLE `daily_records` (
  `id` int(11) NOT NULL,
  `baby_id` int(11) NOT NULL,
  `record_type` varchar(20) NOT NULL,
  `details` text DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_records`
--

INSERT INTO `daily_records` (`id`, `baby_id`, `record_type`, `details`, `start_time`, `end_time`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sleep', 'Slept for 2 hours', '2026-09-07 10:00:00', '2026-09-07 12:00:00', '', '2026-09-07 20:06:18', '2026-09-07 20:09:53'),
(3, 1, 'Diaper', 'Wet', '2026-09-07 13:00:00', NULL, 'The diaper is wet', '2026-09-07 21:01:12', '2026-09-07 21:01:12'),
(4, 1, 'Feeding', 'Breast milk', '2026-09-07 14:00:00', NULL, '', '2026-09-07 21:56:35', '2026-09-07 21:56:35'),
(5, 1, 'Feeding', 'Solid food', '2026-09-07 21:30:00', NULL, '', '2026-09-08 00:02:10', '2026-09-08 00:02:10'),
(6, 1, 'Feeding', 'Solid food - 2 spoon', '2026-09-08 00:00:00', NULL, 'Reaction: happy', '2026-09-08 01:02:17', '2026-09-08 01:02:17'),
(8, 1, 'Feeding', 'Solid food', '2026-09-08 01:00:00', NULL, '', '2026-09-08 01:17:42', '2026-09-08 01:17:42'),
(9, 2, 'Sleep', 'Slept for 8 hours', '2026-09-09 00:32:00', '2026-09-09 08:32:00', '', '2026-09-09 06:32:37', '2026-09-09 06:33:30'),
(10, 2, 'Feeding', 'Solid food', '2026-09-09 09:00:00', NULL, 'Reaction: happy', '2026-09-09 06:34:24', '2026-09-09 06:34:24'),
(11, 2, 'Feeding', 'Solid food', '2026-09-09 14:00:00', NULL, 'Reaction: happy', '2026-09-09 18:46:24', '2026-09-09 18:46:24'),
(12, 4, 'Feeding', 'Solid food', '2026-09-09 06:44:00', NULL, 'Reaction: love it', '2026-09-10 00:38:27', '2026-09-10 00:38:27'),
(14, 2, 'Feeding', 'Solid food - 120', '2026-09-10 17:00:00', NULL, '', '2026-09-10 00:40:48', '2026-09-10 01:39:49'),
(16, 2, 'Diaper', 'Dirty', '2026-09-10 19:45:00', NULL, 'nothing', '2026-09-10 01:41:02', '2026-09-10 01:41:02'),
(17, 6, 'Feeding', 'Solid food', '2026-09-10 17:01:00', NULL, 'Reaction: happy', '2026-09-10 02:00:46', '2026-09-10 02:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `food_type` varchar(50) NOT NULL,
  `age_from` int(11) DEFAULT NULL,
  `age_to` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `food_type`, `age_from`, `age_to`, `description`, `created_at`) VALUES
(1, 'Banana', 'Fruits', 6, NULL, 'Soft and naturally sweet, rich in potassium and easy for babies to eat.', '2026-09-07 23:14:20'),
(2, 'Apple', 'Fruits', 6, NULL, 'A nutritious fruit that can be served cooked or as a smooth puree.', '2026-09-07 23:14:20'),
(3, 'Avocado', 'Fruits', 6, NULL, 'A creamy food rich in healthy fats and important nutrients.', '2026-09-07 23:14:20'),
(4, 'Carrot', 'Vegetables', 6, NULL, 'A source of beta-carotene that works well when steamed and pureed.', '2026-09-07 23:14:20'),
(5, 'Sweet Potato', 'Vegetables', 6, NULL, 'Naturally sweet, soft and packed with vitamins and fiber.', '2026-09-07 23:14:20'),
(6, 'Broccoli', 'Vegetables', 6, NULL, 'A nutrient-rich vegetable that can be steamed until soft.', '2026-09-07 23:14:20'),
(7, 'Oats', 'Grains', 6, NULL, 'A filling grain that can be prepared as a smooth baby-friendly porridge.', '2026-09-07 23:14:20'),
(8, 'Rice Cereal', 'Grains', 6, NULL, 'A simple grain option with a soft texture suitable for early feeding.', '2026-09-07 23:14:20'),
(9, 'Chicken', 'Proteins', 6, NULL, 'A good source of protein and iron when cooked thoroughly and served softly.', '2026-09-07 23:14:20'),
(10, 'Egg', 'Proteins', 6, NULL, 'A nutrient-dense food that should be cooked thoroughly before serving.', '2026-09-07 23:14:20'),
(11, 'Lentils', 'Proteins', 6, NULL, 'A plant-based source of protein and iron with a soft texture when cooked.', '2026-09-07 23:14:20'),
(12, 'Yogurt', 'Dairy', 6, NULL, 'Plain full-fat yogurt can provide calcium and protein for growing babies.', '2026-09-07 23:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `language` varchar(50) DEFAULT 'English',
  `timezone` varchar(80) DEFAULT '(GMT+02:00) Cairo',
  `about` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `push_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `tips_reminders` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `user_type` enum('mother','father','sister') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `birth_date`, `gender`, `address`, `language`, `timezone`, `about`, `profile_image`, `two_factor_enabled`, `email_notifications`, `push_notifications`, `tips_reminders`, `created_at`, `updated_at`, `role`, `user_type`) VALUES
(1, 'Test User', 'test@gmail.com', '$2y$10$M1XnDCLrhi2FSeQ4lN/b6.3Z2YGrCvCnCGTFB5qrSbkccCzbYCFYK', NULL, NULL, NULL, NULL, 'English', '(GMT+02:00) Cairo', NULL, NULL, 0, 0, 0, 1, '2026-09-07 18:06:43', '2026-09-08 23:44:59', 'user', NULL),
(2, 'Fatma Hossam Fathy', 'sarah.test@gmail.com', '$2y$10$s.dtU9xXb.f8xxvQSKSYK.GpPDuHuivefMrHFNYNFfftpFCCwFyGG', NULL, NULL, NULL, NULL, 'English', '(GMT+02:00) Cairo', NULL, NULL, 0, 1, 1, 1, '2026-09-08 20:38:20', '2026-09-08 20:38:20', 'user', 'mother'),
(3, 'Mariam Mohamed', 'mariam.ahmed2026@gmail.com', '$2y$10$mCJrGDvLMG.YHFvxGAEsh.e/z6biBkFzK2ueh1QxhPsBujn8x4TDu', '01123456723', '', '', 'Naser city', 'English', '', '', 'assets/uploads/profile/profile_3_d8cefd23fea02bbf.jpg', 0, 1, 0, 0, '2026-09-08 21:58:15', '2026-09-10 01:37:13', 'user', 'mother'),
(4, 'Menna Hossam', 'menna@gmail.com', '$2y$10$f8EVLUNox.uY7I.pMPLtuumtUKQIWTXKkjmg1UroH9u/k7/X3vzUC', '01211458554', '', 'Female', 'Naser city', 'English', '(GMT+02:00) Cairo', 'I\'m a pretty mom', 'assets/uploads/profile/profile_4_2417feae9ab70b36.jpg', 0, 1, 0, 1, '2026-09-09 18:53:10', '2026-09-10 01:59:59', 'user', 'mother'),
(5, 'Marwa hossam', 'marwa@gmail.com', '$2y$10$m/H66J9h1Pn.MCceheA/c.q7mGBshp36eNarZ0W7STHtSYQNGw0o6', '01123456790', NULL, NULL, NULL, 'English', '(GMT+02:00) Cairo', NULL, NULL, 0, 1, 1, 1, '2026-09-10 03:19:07', '2026-09-10 03:19:07', 'user', 'mother');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `recommended_age` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`id`, `name`, `description`, `recommended_age`, `created_at`) VALUES
(1, 'BCG', 'Protects against tuberculosis (TB).', 'At birth', '2026-09-08 03:05:33'),
(2, 'Hepatitis B', 'Protects against hepatitis B virus infection.', 'Within 24 hours after birth', '2026-09-08 03:05:33'),
(3, 'Oral Polio (OPV)', 'Protects against poliovirus infection.', 'At birth', '2026-09-08 03:05:33'),
(4, 'Pentavalent', 'Protects against diphtheria, pertussis, tetanus, hepatitis B, and Haemophilus influenzae type B.', '2, 4, 6 months', '2026-09-08 03:05:33'),
(5, 'Inactivated Polio (IPV)', 'Protects against poliovirus infection.', '2, 4, 6 months', '2026-09-08 03:05:33'),
(6, 'Oral Polio Booster', 'Booster doses of oral polio vaccine to maintain protection against poliovirus.', '9, 12, 18 months', '2026-09-08 03:05:33'),
(7, 'MMR', 'Protects against measles, mumps, and rubella.', '12, 18 months', '2026-09-08 03:05:33'),
(8, 'DPT Booster', 'Booster vaccine against diphtheria, pertussis, and tetanus.', '18 months', '2026-09-08 03:05:33'),
(9, 'Meningococcal (Meningitis)', 'Protects against meningococcal disease, including epidemic meningitis.', 'School age', '2026-09-08 03:05:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `babies`
--
ALTER TABLE `babies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `baby_allergies`
--
ALTER TABLE `baby_allergies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baby_id` (`baby_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `baby_meals`
--
ALTER TABLE `baby_meals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baby_id` (`baby_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `baby_vaccinations`
--
ALTER TABLE `baby_vaccinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baby_id` (`baby_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

--
-- Indexes for table `daily_records`
--
ALTER TABLE `daily_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baby_id` (`baby_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `babies`
--
ALTER TABLE `babies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `baby_allergies`
--
ALTER TABLE `baby_allergies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `baby_meals`
--
ALTER TABLE `baby_meals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `baby_vaccinations`
--
ALTER TABLE `baby_vaccinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `daily_records`
--
ALTER TABLE `daily_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `babies`
--
ALTER TABLE `babies`
  ADD CONSTRAINT `babies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `baby_allergies`
--
ALTER TABLE `baby_allergies`
  ADD CONSTRAINT `baby_allergies_ibfk_1` FOREIGN KEY (`baby_id`) REFERENCES `babies` (`id`),
  ADD CONSTRAINT `baby_allergies_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`);

--
-- Constraints for table `baby_meals`
--
ALTER TABLE `baby_meals`
  ADD CONSTRAINT `baby_meals_ibfk_1` FOREIGN KEY (`baby_id`) REFERENCES `babies` (`id`),
  ADD CONSTRAINT `baby_meals_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`);

--
-- Constraints for table `baby_vaccinations`
--
ALTER TABLE `baby_vaccinations`
  ADD CONSTRAINT `baby_vaccinations_ibfk_1` FOREIGN KEY (`baby_id`) REFERENCES `babies` (`id`),
  ADD CONSTRAINT `baby_vaccinations_ibfk_2` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`id`);

--
-- Constraints for table `daily_records`
--
ALTER TABLE `daily_records`
  ADD CONSTRAINT `daily_records_ibfk_1` FOREIGN KEY (`baby_id`) REFERENCES `babies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
