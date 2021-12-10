-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2021 at 07:12 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_tracker_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `description` text COLLATE utf8_bin DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8_bin NOT NULL,
  `expense_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `description`, `file_path`, `expense_id`) VALUES
(1, 'test fajl 1', 'uploads/file1.pdf', 3),
(2, 'test fajl 2', 'uploads/file2.pdf', 3),
(3, 'test fajl 3', 'uploads/file3.jpg', 3),
(5, 'test dokument', 'uploads/documents/61ae49e569b93.pdf', 4),
(6, 'drugi racun', 'uploads/documents/61ae4a0b0158c.pdf', 4),
(7, 'sken racuna', 'uploads/documents/61ae4b7795e1f.pdf', 5),
(8, 'dokument', 'uploads/documents/61ae4cef7872d.pdf', 6);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL,
  `expense_subtype_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `amount`, `date`, `user_id`, `expense_type_id`, `expense_subtype_id`) VALUES
(3, 150, '2021-12-02 23:00:00', 1, 2, 1),
(4, 50, '2021-11-30 23:00:00', 1, 1, NULL),
(5, 30, '2021-11-30 23:00:00', 1, 2, 3),
(6, 15, '2021-11-30 23:00:00', 1, 2, 2),
(7, 85, '2021-12-04 23:00:00', 1, 3, NULL),
(8, 81, '1977-01-05 23:00:00', 1, 2, 1),
(9, 150, '2021-12-05 23:00:00', 1, 1, NULL),
(10, 10, '2021-12-09 23:00:00', 1, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expense_subtypes`
--

CREATE TABLE `expense_subtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `expense_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `expense_subtypes`
--

INSERT INTO `expense_subtypes` (`id`, `name`, `expense_type_id`) VALUES
(1, 'Računi za struju', 2),
(2, 'Računi za vodu', 2),
(3, 'Računi za telefon', 2);

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `color` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `name`, `color`) VALUES
(1, 'Gorivo', '#47D900'),
(2, 'Računi', '#FFCE56'),
(3, 'Obrazovanje', '#36A2EB');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 'Bojan', 'Lozo', 'lozobojan@gmail.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `user_expense_type`
--

CREATE TABLE `user_expense_type` (
  `user_id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_expense_type`
--

INSERT INTO `user_expense_type` (`user_id`, `expense_type_id`) VALUES
(1, 3),
(1, 2),
(1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attachment_expense` (`expense_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_expense_type_expense` (`expense_type_id`),
  ADD KEY `fk_expense_subtype_expense` (`expense_subtype_id`),
  ADD KEY `fk_user_expense` (`user_id`);

--
-- Indexes for table `expense_subtypes`
--
ALTER TABLE `expense_subtypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subtype_type` (`expense_type_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_unique_users_email` (`email`);

--
-- Indexes for table `user_expense_type`
--
ALTER TABLE `user_expense_type`
  ADD KEY `fk_user_type_user` (`user_id`),
  ADD KEY `fk_user_type_type` (`expense_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expense_subtypes`
--
ALTER TABLE `expense_subtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `fk_attachment_expense` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fk_expense_subtype_expense` FOREIGN KEY (`expense_subtype_id`) REFERENCES `expense_subtypes` (`id`),
  ADD CONSTRAINT `fk_expense_type_expense` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`),
  ADD CONSTRAINT `fk_user_expense` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `expense_subtypes`
--
ALTER TABLE `expense_subtypes`
  ADD CONSTRAINT `fk_subtype_type` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`);

--
-- Constraints for table `user_expense_type`
--
ALTER TABLE `user_expense_type`
  ADD CONSTRAINT `fk_user_type_type` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`),
  ADD CONSTRAINT `fk_user_type_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
