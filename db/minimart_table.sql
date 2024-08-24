-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2024 at 03:28 PM
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
-- Database: `minimart_table`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `names` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `userNote` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `names`, `detail`, `userNote`, `create_at`, `updated_at`, `image`) VALUES
(4, 'Zombie', '11', 'user', '2024-08-01 13:48:08', '2024-08-13 06:21:54', '../uploads/66bafb82a7b55_Zombie Tsunami.jpg'),
(5, 'Mobile', '1', 'admin', '2024-08-06 01:54:48', '2024-08-14 02:58:43', '../uploads/66bafb7390f47_mobile.jpg'),
(14, 'Clash of clans', '7', 'admin', '2024-08-08 14:54:42', '2024-08-14 03:24:39', '../uploads/66bafb60a0665_Clash of Clans.jpg'),
(15, 'Candy Crush Saga', '71', 'user', '2024-08-08 14:56:10', '2024-08-13 06:23:08', '../uploads/66bafbcc81fd6_Candy Crush Saga1.jpg'),
(27, 'Free fire', '4', 'user', '2024-08-13 05:10:22', '2024-08-13 06:22:41', '../uploads/66baeabee57b4_freefire.jpg'),
(28, 'Ball boll', '3', 'user', '2024-08-13 05:11:09', '2024-08-13 06:22:27', '../uploads/66bafba3ebb10_Ball Pool.jpg'),
(29, 'Candy', '4', 'admin', '2024-08-13 06:40:32', '2024-08-14 03:24:32', '../uploads/66bb005876308_Candy Crush Saga.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `num_table` int(11) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('OFF','NO') NOT NULL DEFAULT 'NO',
  `user_sale` text DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `id_room`, `num_table`, `total`, `description`, `status`, `user_sale`, `create_at`) VALUES
(1, 6, 1, 66.00, NULL, 'OFF', NULL, '2024-08-19 09:10:13'),
(5, 10, 1, 99.00, NULL, 'OFF', NULL, '2024-08-19 09:18:22'),
(6, 10, 1, 198.00, NULL, 'OFF', NULL, '2024-08-19 09:18:45'),
(7, 8, 5, 330.00, NULL, 'OFF', NULL, '2024-08-19 09:19:26'),
(8, 8, 5, 198.00, NULL, 'OFF', NULL, '2024-08-19 09:19:48'),
(9, 11, 2, 55.00, NULL, 'OFF', NULL, '2024-08-19 09:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `product_id`, `order_id`, `quantity`, `status`, `created_at`, `price`) VALUES
(33, 2, 1, 2, 1, '2024-08-19 09:10:13', 33),
(37, 1, 5, 1, 1, '2024-08-19 09:18:22', 22),
(38, 2, 5, 1, 1, '2024-08-19 09:18:22', 33),
(39, 3, 5, 1, 1, '2024-08-19 09:18:22', 44),
(40, 1, 6, 2, 1, '2024-08-19 09:18:45', 22),
(41, 2, 6, 2, 1, '2024-08-19 09:18:45', 33),
(42, 3, 6, 2, 1, '2024-08-19 09:18:45', 44),
(43, 1, 7, 2, 1, '2024-08-19 09:19:26', 22),
(44, 2, 7, 2, 1, '2024-08-19 09:19:26', 33),
(45, 3, 7, 5, 1, '2024-08-19 09:19:26', 44),
(46, 1, 8, 2, 1, '2024-08-19 09:19:48', 22),
(47, 2, 8, 2, 1, '2024-08-19 09:19:48', 33),
(48, 3, 8, 2, 1, '2024-08-19 09:19:48', 44),
(49, 1, 9, 1, 1, '2024-08-19 09:21:29', 22),
(50, 2, 9, 1, 1, '2024-08-19 09:21:29', 33);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `names` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `names`, `price`, `qty`, `status`) VALUES
(1, 'apple', 22, 2, 1),
(2, 'na', 33, 2, 1),
(3, 'ta', 44, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `names` varchar(255) NOT NULL,
  `table` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `userNote` text NOT NULL,
  `total` int(11) NOT NULL,
  `dob` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `names`, `table`, `status`, `userNote`, `total`, `dob`) VALUES
(1, 'VIP', 2, 1, '', 50000, '2022-08-14 08:42:03'),
(5, 'Gamming', 2, 1, 'admin', 90000000, '2024-01-14 08:42:03'),
(6, 'Private', 1, 1, 'admin', 60000000, '2024-05-14 08:42:03'),
(7, 'Pubclic', 10, 1, 'admin', 100000000, '2024-08-14 08:49:59'),
(8, 'Sweet', 5, 1, 'admin', 1000, '2024-08-15 17:21:34'),
(9, 'Family', 3, 1, 'admin', 20000, '2024-03-14 08:42:03'),
(10, 'General', 10, 1, 'admin', 800000000, '2023-12-14 08:42:03'),
(11, 'វៃសិច', 2, 1, 'admin', 0, '2024-08-17 10:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `names` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `rol` enum('admin','user','manager') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `names`, `email`, `pass`, `rol`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', '2024-08-13 03:15:11'),
(3, 'user', 'user@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', '2024-08-13 03:23:05'),
(4, 'manager', 'manager@gmail.com', '1d0258c2440a8d19e716292b231e3190', 'manager', '2024-08-13 03:41:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_room` (`id_room`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `room` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `room` (`id`),
  ADD CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
