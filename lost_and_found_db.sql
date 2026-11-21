-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2026 at 02:57 PM
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
-- Database: `lost_and_found_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `claim_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `claim_description` text NOT NULL,
  `claim_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `admin_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`claim_id`, `item_id`, `user_id`, `claim_description`, `claim_date`, `status`, `admin_remarks`) VALUES
(1, 2, 1, 'I believe this purple mouse belongs to me. I lost it in the computer laboratory.', '2026-11-17 20:54:15', 'Pending', 'Waiting for admin verification.'),
(3, 3, 2, 'This water bottle belongs to me. It has a small sticker on the side.', '2026-11-17 20:55:19', 'Approved', 'Claim approved after checking the ownership details.'),
(4, 5, 4, 'I lost a red folding umbrella near the main entrance.', '2026-11-17 20:57:03', 'Rejected', 'The ownership details could not be verified.'),
(5, 2, 6, 'blah blah', '2026-11-18 14:18:14', 'Rejected', ''),
(6, 2, 6, 'blah', '2026-11-18 14:28:48', 'Rejected', 'not enough evidence'),
(7, 2, 6, 'blah againn', '2026-11-18 14:40:35', 'Pending', ''),
(8, 2, 6, 'blah 3', '2026-11-18 15:07:39', 'Pending', ''),
(9, 2, 6, 'blah 4', '2026-11-18 15:36:21', 'Pending', ''),
(10, 2, 6, 'his is my mouse. It has a small scratch on the side.', '2026-11-19 23:58:54', 'Pending', ''),
(11, 10, 6, 'it has my ic', '2026-11-20 00:22:26', 'Pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `claim_reports`
--

CREATE TABLE `claim_reports` (
  `report_id` int(11) NOT NULL,
  `claim_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `report_reason` text NOT NULL,
  `report_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `report_type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(150) NOT NULL,
  `item_date` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `user_id`, `item_name`, `category`, `report_type`, `description`, `location`, `item_date`, `image`, `status`) VALUES
(1, 1, 'Black Wallet', 'Personal Belongings', 'Lost', 'A black leather wallet containing identification cards.', 'Level 3 Cubicle 5', '2026-11-02', 'wallet.jpg', 'Pending'),
(2, 2, 'Purple Mouse', 'Electronics', 'Found', 'A purple wireless computer mouse.', 'Level 5 Room 501', '2026-11-03', 'mouse.jpg', 'Found'),
(3, 3, 'Water Bottle', 'Personal Belongings', 'Found', 'A reusable water bottle left behind.', 'College Canteen', '2026-11-04', 'bottle.jpg', 'Found'),
(4, 4, 'Macbook Charger', 'Electronics', 'Lost', 'A Macbook laptop charger with a white cable.', 'Study Area', '2026-11-01', 'macbook charger.jpg', 'Pending'),
(5, 5, 'Red Umbrella', 'Others', 'Found', 'A red folding umbrella.', 'Main Entrance', '2026-10-31', 'red umbrella.jpg', 'Found'),
(6, 1, 'Pink Jacket', 'Clothing', 'Lost', 'A pink jacket left in the computer laboratory.', 'Network Lab', '2026-10-30', 'pink jacket.jpg', 'Pending'),
(7, 7, 'black wallet', 'Personal Belongings', 'Lost', 'brand rav', 'college library', '2026-11-18', '1794968604_11_621dff4d-38c4-4282-8068-5119db0c9a71.webp', 'Open'),
(8, 6, 'black wallet', 'Personal Belongings', 'Lost', 'Brand Polo', 'Cafeteria', '2026-11-19', '1795100034_images.jpg', 'Open'),
(9, 6, 'black wallet', 'Personal Belongings', 'Lost', 'Brand Crocodile', 'Cubicles', '2026-11-19', '1795100809_crocodile.jpg', 'Open'),
(10, 6, 'black wallet', 'Personal Belongings', 'Found', 'looks like crocodile brand', 'Cubicles', '2026-11-20', '1795105261_crocodile.jpg', 'Found');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `student_staff_id` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `user_type`, `student_staff_id`, `created_at`, `status`) VALUES
(1, 'Adam Lee', 'adam@gmail.com', 'password123', 'Student', 'STU001', '2026-11-17 20:41:48', 'active'),
(2, 'Sarah Tan', 'sarah@gmail.com', 'password123', 'Student', 'STU002', '2026-11-17 20:41:48', 'Active'),
(3, 'Daniel Lim', 'daniel@gmail.com', 'password123', 'Staff', 'STA001', '2026-11-17 20:41:48', 'Active'),
(4, 'Emily Wong', 'emily@gmail.com', 'password123', 'Student', 'STU003', '2026-11-17 20:41:48', 'Active'),
(5, 'Michael Chan', 'michael@gmail.com', 'password123', 'Staff', 'STA002', '2026-11-17 20:41:48', 'Active'),
(6, 'John', 'john@gmail.com', '$2y$10$tlJ2WjPhxmJISYzr/1ul5OM0W9UWhgsldIc4LL510c.WhqQSaLDAu', 'Student', 'P01234567', '2026-11-18 10:14:45', 'Active'),
(7, 'nava', 'nava@gmail.com', '$2y$10$CaaHtNkGFUJEiSWFHNq7huw2NtU6u10T0gIIXprsbxILSxvae7GkS', 'Student', 'p123', '2026-11-18 10:19:45', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `fk_claims_items` (`item_id`),
  ADD KEY `fk_claims_users` (`user_id`);

--
-- Indexes for table `claim_reports`
--
ALTER TABLE `claim_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `claim_id` (`claim_id`),
  ADD KEY `reporter_id` (`reporter_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_items_users` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `claim_reports`
--
ALTER TABLE `claim_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `fk_claims_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_claims_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `claim_reports`
--
ALTER TABLE `claim_reports`
  ADD CONSTRAINT `claim_reports_ibfk_1` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`claim_id`),
  ADD CONSTRAINT `claim_reports_ibfk_2` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
