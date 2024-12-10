-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 09, 2024 at 10:04 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muzak`
--

-- --------------------------------------------------------

--
-- Table structure for table `listen_log`
--

CREATE TABLE `listen_log` (
  `entry_id` int NOT NULL,
  `Album` text COLLATE utf8mb4_general_ci NOT NULL,
  `Artist` text COLLATE utf8mb4_general_ci NOT NULL,
  `release_date` date NOT NULL,
  `listen_date` date NOT NULL,
  `music_platform` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `collection_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listen_log`
--

INSERT INTO `listen_log` (`entry_id`, `Album`, `Artist`, `release_date`, `listen_date`, `music_platform`, `collection_status`) VALUES
(1, 'Queen II', 'Queen', '1974-03-08', '2018-03-08', 'Spotify', 'CD'),
(2, 'The College Dropout', 'Kanye West', '2004-02-10', '2024-10-04', 'Spotify', 'N/A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listen_log`
--
ALTER TABLE `listen_log`
  ADD PRIMARY KEY (`entry_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listen_log`
--
ALTER TABLE `listen_log`
  MODIFY `entry_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
