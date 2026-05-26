-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 07:44 PM
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
-- Database: `profile_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('Administrator','Moderator','Member','Beginner') NOT NULL DEFAULT 'Member',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `reactions` int(11) NOT NULL DEFAULT 0,
  `post_count` int(11) NOT NULL DEFAULT 0,
  `joined_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `last_name`, `email`, `username`, `role`, `status`, `avatar`, `points`, `reactions`, `post_count`, `joined_at`, `created_at`, `updated_at`) VALUES
(1, 'Nicole', 'Peralta', 'niksperalta222@gmail.com', 'nicoke', 'Beginner', 'inactive', 'uploads/avatars/1779725388_16d7e2da3ed235596d44.webp', 8, 10, 8, '2026-05-25 16:09:48', '2026-05-25 16:09:48', '2026-05-26 16:29:59'),
(2, 'Unique ', 'Salonga', 'uniquesalonga1@gmail.com', 'unique_iv', 'Moderator', 'inactive', 'uploads/avatars/1779734471_35f69c9728a075c8af66.jpg', 229, 999, 30, '2026-05-25 18:41:11', '2026-05-25 18:41:11', '2026-05-25 19:46:37'),
(3, 'Claire', 'Cotrill', 'clairocotrill777@gmail.com', 'clayrow.0', 'Administrator', 'active', 'uploads/avatars/1779735280_17d498ec5efe72265062.jpg', 8, 5, 66, '2026-05-25 18:54:40', '2026-05-25 18:54:40', '2026-05-25 20:49:11'),
(4, 'Rico', 'Blanco', 'ricoblanco00@gmail.com', 'ricoco', 'Member', 'inactive', 'uploads/avatars/1779738853_3418c90ec273899b630a.jpg', 1000, 214, 46, '2026-05-25 19:54:13', '2026-05-25 19:54:13', '2026-05-25 20:39:59'),
(5, 'Yeng', 'Constantino', 'yengcons22@gmail.com', 'yeng08', 'Member', 'active', 'uploads/avatars/1779738977_3bc6d47309f8b07e0309.jpg', 0, 0, 1, '2026-05-25 19:56:17', '2026-05-25 19:56:17', '2026-05-25 20:48:54'),
(6, 'April', 'Custorio', 'nixcustorio@gmail.com', 'nix.uni', 'Administrator', 'active', 'uploads/avatars/1779739022_77f2dec62ababd582db6.jpg', 99, 99, 99, '2026-05-25 19:57:02', '2026-05-25 19:57:02', '2026-05-25 19:57:02'),
(10, 'Stephen', 'Dranto', 'stephdran@gmail.com', 'phends.tee', 'Moderator', 'inactive', 'uploads/avatars/1779811946_17bf7942a3ce3df46ca4.png', 644, 784, 85, '2026-05-26 16:12:26', '2026-05-26 16:12:26', '2026-05-26 16:29:32'),
(11, 'Steve', 'Lacy', 'stevelacy67@gmail.com', 'steve_xx0', 'Administrator', 'active', 'uploads/avatars/1779812582_20f54bebae95a93674db.jpg', 233, 88, 7, '2026-05-26 16:23:02', '2026-05-26 16:23:02', '2026-05-26 16:23:02'),
(12, 'Justin', 'Bieber', 'justinbee@gmail.com', 'just_.in', 'Moderator', 'active', 'uploads/avatars/1779812724_524f55c3f7780aeb0d18.png', 844, 3544, 33, '2026-05-26 16:25:24', '2026-05-26 16:25:24', '2026-05-26 16:25:24'),
(13, 'Chito', 'Miranda', 'chitomiranda888@gmail.com', 'chitzmiranda', 'Member', 'active', 'uploads/avatars/1779812956_6b2ebd3d5177c4414d6a.jpg', 574, 546, 44, '2026-05-26 16:29:16', '2026-05-26 16:29:16', '2026-05-26 16:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-05-24-082505', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1779611627, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
