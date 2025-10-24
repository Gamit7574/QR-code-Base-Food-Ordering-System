-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 03:59 PM
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
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `mobile`, `email`, `password`) VALUES
(13, 'NIDHI', '9327637881', 'gamitabhi6353@gmail.com', '$2y$10$EieByRO.z6tgyYYL0QvYdOIeKWeFgaKTpad7.Ehe5/eoLiaNWJd8C');

-- --------------------------------------------------------

--
-- Table structure for table `bill_flags`
--

CREATE TABLE `bill_flags` (
  `table_number` int(11) NOT NULL,
  `bill_sent` tinyint(1) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `report_saved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_flags`
--

INSERT INTO `bill_flags` (`table_number`, `bill_sent`, `sent_at`, `report_saved`) VALUES
(1, 1, '2025-05-05 15:34:33', 0),
(2, 1, '2025-05-10 16:38:59', 1),
(3, 1, '2025-05-05 15:51:37', 0),
(4, 1, '2025-05-10 00:41:01', 1),
(5, 1, '2025-06-07 21:10:18', 1),
(6, 1, '2025-05-05 01:55:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` double DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `table_number` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bill_sent` tinyint(1) DEFAULT 0,
  `last_updated` int(11) DEFAULT unix_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_name`, `price`, `description`, `quantity`, `total_price`, `name`, `phone`, `table_number`, `status`, `created_at`, `bill_sent`, `last_updated`) VALUES
(356, 'Chole Batture', 60.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-04-24 04:11:43', 0, 1746387206),
(358, 'Chole Batture', 60.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-04-24 04:24:35', 0, 1746387206),
(359, 'Paneer Tikka fry', 130.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-04-24 04:24:35', 0, 1746387206),
(360, 'Paavbhaji', 90.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-04-24 04:26:17', 0, 1746387206),
(361, 'Paneer Tikka fry', 130.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-04-24 04:26:17', 0, 1746387206),
(364, 'Chole Batture', 60.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-05-02 21:16:47', 0, 1746387206),
(382, 'Chole Batture', 60.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-05-03 04:59:51', 0, 1746387206),
(383, 'Paneer Tikka fry', 130.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-05-03 04:59:51', 0, 1746387206),
(385, 'Chole Batture', 60.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-05-03 05:07:16', 0, 1746387206),
(386, 'Paavbhaji', 90.00, '', 1, NULL, 'GAMIT ABHISHEKBHAI SUMANBHAI', '9327637881', 4, 'completed', '2025-05-03 05:07:16', 0, 1746387206);

-- --------------------------------------------------------

--
-- Table structure for table `order_updates`
--

CREATE TABLE `order_updates` (
  `id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_updates`
--

INSERT INTO `order_updates` (`id`, `last_update`) VALUES
(1, '2025-08-15 17:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verify`
--

CREATE TABLE `otp_verify` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `table_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verify`
--

INSERT INTO `otp_verify` (`id`, `name`, `phone`, `table_number`) VALUES
(20, 'ABHISHEK', '9327637881', 2),
(37, 'Nidhi', '9664903265', 3),
(40, 'Nidhi Chaudhari ', '9106595585', 2),
(41, 'Viral Prajapati ', '9925127260', 2),
(67, 'Jay', '8849444570', 6),
(78, 'Gamit indarjit', '9327071085', 5),
(87, 'PATEL PARIMAL ANILBHAI', '6351877450', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_url`) VALUES
(10, 'Paavbhaji', 90.00, 'uploads/paavbhaji.jpg'),
(11, 'Chole Batture', 60.00, 'uploads/chole-bhature.webp'),
(12, 'Paneer Tikka fry', 130.00, 'uploads/paneertika fry.jpg'),
(16, 'Manchurian', 60.00, 'uploads/manchurian.webp'),
(18, 'pizza', 120.00, 'uploads/pizza.jpg'),
(20, 'burger', 70.00, 'uploads/burger.jpg'),
(21, 'french fries', 30.00, 'uploads/french.webp'),
(25, 'khichdi', 40.00, 'uploads/khichdi (1).jpg'),
(26, 'kadi khichdi', 50.00, 'uploads/OIP.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `table_number` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `billed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `name`, `phone`, `table_number`, `product_name`, `price`, `quantity`, `total_price`, `billed_at`) VALUES
(42, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-07 14:52:50'),
(48, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 11:05:21'),
(49, 'ABHISHEK', '9327637881', 2, 'Paavbhaji', 90.00, 1, 90.00, '2025-05-10 11:05:21'),
(50, 'ABHISHEK', '9327637881', 2, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 11:14:50'),
(51, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 11:14:50'),
(52, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 11:22:54'),
(53, 'ABHISHEK', '9327637881', 2, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 11:22:54'),
(54, 'ABHISHEK', '9327637881', 2, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 11:35:34'),
(55, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 11:35:34'),
(56, 'ABHISHEK', '9327637881', 2, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 11:35:34'),
(57, 'ABHISHEK', '9327637881', 2, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 11:35:34'),
(58, 'ABHISHEK', '9327637881', 2, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 11:38:59'),
(59, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'icecream', 5.00, 1, 5.00, '2025-05-10 15:49:44'),
(60, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 15:58:13'),
(61, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 16:01:35'),
(62, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paavbhaji', 90.00, 1, 90.00, '2025-05-10 16:01:35'),
(63, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 16:04:21'),
(64, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paneer Tikka fry', 130.00, 1, 130.00, '2025-05-10 16:04:21'),
(65, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'pizza', 120.00, 1, 120.00, '2025-05-10 16:04:21'),
(66, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paavbhaji', 90.00, 1, 90.00, '2025-05-10 16:16:04'),
(67, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 16:16:04'),
(68, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paavbhaji', 90.00, 1, 90.00, '2025-05-10 16:40:25'),
(69, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Chole Batture', 60.00, 1, 60.00, '2025-05-10 16:40:25'),
(70, 'GAMIT ABHISHEKBHAI SUMANBHAI ', '9327637881', 5, 'Paavbhaji', 90.00, 1, 90.00, '2025-05-10 16:42:06'),
(71, 'Gamit indarjit', '9327071085', 5, 'Paavbhaji', 90.00, 1, 90.00, '2025-06-07 16:10:18'),
(72, 'Gamit indarjit', '9327071085', 5, 'Chole Batture', 60.00, 1, 60.00, '2025-06-07 16:10:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bill_flags`
--
ALTER TABLE `bill_flags`
  ADD PRIMARY KEY (`table_number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_updates`
--
ALTER TABLE `order_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_verify`
--
ALTER TABLE `otp_verify`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=568;

--
-- AUTO_INCREMENT for table `order_updates`
--
ALTER TABLE `order_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `otp_verify`
--
ALTER TABLE `otp_verify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
