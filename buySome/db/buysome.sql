-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 19, 2022 at 02:22 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buysome`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `quantity` int(10) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `username`, `password`, `phone_number`, `address`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
('3e331db7-dbb3-4022-a2b3-db5ed11df561', 'danny', '$2b$10$rMNwisLvTbWwaAqjliZGceBiPLOftxxTrtOZwOFoWilD1S2brmWO.', '+233546038303', 'Accra - labadi', '2022-04-02 13:08:13', '2022-04-02 13:08:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `extokens`
--

CREATE TABLE `extokens` (
  `token` varchar(255) NOT NULL,
  `customer_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `status` enum('pending','success','failed','reverted','delived') NOT NULL,
  `order_email` varchar(255) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `customer_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `status`, `order_email`, `shipping_address`, `customer_id`, `txn_id`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
(1, 'reverted', 'danny@gmail.com', 'Legon - madina', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '50cd9a3145', '2022-04-09 08:25:54', '2022-04-12 14:00:26', NULL),
(2, 'reverted', 'danny@gmail.com', 'legon estate', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '522c08bca9', '2022-04-09 08:32:26', '2022-04-12 14:01:54', NULL),
(4, 'reverted', 'moda@gmail.com', 'Legon cites', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '60f00fd422', '2022-04-09 08:54:34', '2022-04-12 14:02:30', NULL),
(6, 'reverted', 'magi@gmail.com', 'Linda - Sport', '3e331db7-dbb3-4022-a2b3-db5ed11df561', 'a3c042b5b0', '2022-04-09 09:09:32', '2022-04-14 12:57:34', NULL),
(8, 'reverted', 'mante@gmail.com', 'labadi', '3e331db7-dbb3-4022-a2b3-db5ed11df561', 'bc8548f138', '2022-04-09 09:26:00', '2022-04-14 13:00:24', NULL),
(9, 'success', 'Ndidi@gmail.com', 'Las Pamas', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '868d6d81c5', '2022-04-09 09:27:40', '2022-04-09 09:27:41', NULL),
(10, 'reverted', 'dannyDee@gmail.com', 'Labadi Estates', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '7215eb0bb5', '2022-04-17 18:54:10', '2022-04-17 19:00:25', NULL),
(11, 'success', 'dannyDee@gmail.com', 'Lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '6eea81b86d', '2022-04-29 01:42:54', '2022-04-29 01:42:54', NULL),
(12, 'reverted', 'dannyDee@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '16f3e2aee6', '2022-05-02 16:36:43', '2022-05-02 16:38:51', NULL),
(13, 'reverted', 'danny@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', 'cb2340fe9a', '2022-05-05 16:55:33', '2022-05-05 16:58:54', NULL),
(14, 'reverted', 'danny@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '2e77d496cf', '2022-05-17 01:27:45', '2022-05-17 01:52:20', NULL),
(15, 'success', 'mandy@gmail.com', 'Lagos', '3e331db7-dbb3-4022-a2b3-db5ed11df561', 'cb0bcc3d81', '2022-05-17 01:50:33', '2022-05-17 01:50:33', NULL),
(16, 'success', 'queen@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '1089b82aa2', '2022-05-18 13:43:33', '2022-05-18 13:43:33', NULL),
(17, 'success', 'la@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '83a4b2b70c', '2022-05-18 13:48:00', '2022-05-18 13:48:00', NULL),
(18, 'success', 'danny@gmail.com', 'lapaz', '3e331db7-dbb3-4022-a2b3-db5ed11df561', '441a631926', '2022-05-18 17:03:40', '2022-05-18 17:03:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `quantity` int(10) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `quantity`, `order_id`, `product_id`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
(1, 1, 1, 1, '2022-04-09 08:25:54', '2022-04-09 08:25:54', NULL),
(2, 1, 2, 1, '2022-04-09 08:32:26', '2022-04-09 08:32:26', NULL),
(3, 1, 4, 1, '2022-04-09 08:54:34', '2022-04-09 08:54:34', NULL),
(4, 1, 6, 1, '2022-04-09 09:09:32', '2022-04-09 09:09:32', NULL),
(5, 1, 8, 1, '2022-04-09 09:26:01', '2022-04-09 09:26:01', NULL),
(6, 1, 9, 1, '2022-04-09 09:27:41', '2022-04-09 09:27:41', NULL),
(7, 1, 10, 1, '2022-04-17 18:54:10', '2022-04-17 18:54:10', NULL),
(8, 1, 11, 1, '2022-04-29 01:42:54', '2022-04-29 01:42:54', NULL),
(9, 1, 12, 1, '2022-05-02 16:36:43', '2022-05-02 16:36:43', NULL),
(10, 1, 13, 1, '2022-05-05 16:55:33', '2022-05-05 16:55:33', NULL),
(11, 1, 14, 1, '2022-05-17 01:27:45', '2022-05-17 01:27:45', NULL),
(12, 1, 15, 1, '2022-05-17 01:50:33', '2022-05-17 01:50:33', NULL),
(13, 1, 16, 2, '2022-05-18 13:43:33', '2022-05-18 13:43:33', NULL),
(14, 1, 17, 1, '2022-05-18 13:48:00', '2022-05-18 13:48:00', NULL),
(15, 1, 18, 2, '2022-05-18 17:03:40', '2022-05-18 17:03:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `description`, `image_url`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
(1, 'Plaid mens shirts slim fit cotton', 540.99, 46, '2019 mens dress shirts male clothes social long sleeve casual shirt men plus size', 'https://sc04.alicdn.com/kf/HTB1gWZSbBKw3KVjSZFOq6yrDVXae.jpg', '2022-04-02 11:58:50', '2022-05-18 13:46:50', NULL),
(2, 'Mens hot shoe md455', 300.99, 98, 'normal size, large size, medium size. All occasions', 'https://www.thefashionisto.com/wp-content/uploads/2020/12/Black-Leather-Loafers-Shoes-Mens.jpg', '2022-05-18 13:37:35', '2022-05-18 17:00:55', NULL),
(3, 'Mens classic shoe md56', 350.5, 100, 'normal size, large size, medium size. All occasions. Large stock, free shipping', 'https://cdn.hiconsumption.com/wp-content/uploads/2022/03/Best-Dress-Shoes-for-Men-0-Hero.jpg', '2022-05-18 13:39:46', '2022-05-18 13:39:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sequelizemeta`
--

CREATE TABLE `sequelizemeta` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sequelizemeta`
--

INSERT INTO `sequelizemeta` (`name`) VALUES
('20220210092115-sys_users.js'),
('20220210193718-products.js'),
('20220212012731-customers.js'),
('20220212021841-cart.js'),
('20220212084136-extokens.js'),
('20220403171334-transactions.js'),
('20220403171339-orders.js'),
('20220403171340-order_details.js');

-- --------------------------------------------------------

--
-- Table structure for table `sys_users`
--

CREATE TABLE `sys_users` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `full_name` varchar(20) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `role` enum('super_admin','admin') NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sys_users`
--

INSERT INTO `sys_users` (`id`, `username`, `password`, `full_name`, `phone_number`, `role`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
('a0609849-0eb2-4964-8660-4489f6b828f9', 'magnito', '$2b$10$HlIO12P79BT2oAU1vWwj6OIKVBGHKSaDMRTVIYH6yECaRuSuu7qzC', 'Magnum Magnet', '+233547555463', 'super_admin', '2022-04-02 11:57:53', '2022-04-02 11:57:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` varchar(255) NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `status`, `createdAt`, `updatedAt`, `deletedAt`) VALUES
('1089b82aa2', 'success', '2022-05-18 13:43:33', '2022-05-18 13:43:33', NULL),
('16f3e2aee6', 'success', '2022-05-02 16:36:41', '2022-05-02 16:36:41', NULL),
('26a466a44d', 'success', '2022-04-09 09:15:31', '2022-04-09 09:15:31', NULL),
('2e77d496cf', 'success', '2022-05-17 01:27:42', '2022-05-17 01:27:42', NULL),
('441a631926', 'success', '2022-05-18 17:03:39', '2022-05-18 17:03:39', NULL),
('4875d94c54', 'success', '2022-04-29 01:17:01', '2022-04-29 01:17:01', NULL),
('50cd9a3145', 'success', '2022-04-09 08:01:21', '2022-04-09 08:01:21', NULL),
('522c08bca9', 'success', '2022-04-09 08:31:13', '2022-04-09 08:31:13', NULL),
('5e52ce4287', 'success', '2022-04-09 07:30:33', '2022-04-09 07:30:33', NULL),
('60f00fd422', 'success', '2022-04-09 08:51:17', '2022-04-09 08:51:17', NULL),
('6eea81b86d', 'success', '2022-04-29 01:42:52', '2022-04-29 01:42:52', NULL),
('7215eb0bb5', 'success', '2022-04-17 18:54:07', '2022-04-17 18:54:07', NULL),
('83a4b2b70c', 'success', '2022-05-18 13:47:57', '2022-05-18 13:47:57', NULL),
('868d6d81c5', 'success', '2022-04-09 09:27:39', '2022-04-09 09:27:39', NULL),
('a3c042b5b0', 'success', '2022-04-09 09:06:00', '2022-04-09 09:06:00', NULL),
('bc8548f138', 'success', '2022-04-09 09:25:59', '2022-04-09 09:25:59', NULL),
('cb0bcc3d81', 'success', '2022-05-17 01:50:31', '2022-05-17 01:50:31', NULL),
('cb2340fe9a', 'success', '2022-05-05 16:55:31', '2022-05-05 16:55:31', NULL),
('d463cb0e8b', 'success', '2022-04-29 01:40:49', '2022-04-29 01:40:49', NULL),
('urjd04304094', 'failed', '2022-04-03 18:16:41', '2022-04-03 18:16:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `extokens`
--
ALTER TABLE `extokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `txd_id` (`txn_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sequelizemeta`
--
ALTER TABLE `sequelizemeta`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sys_users`
--
ALTER TABLE `sys_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `extokens`
--
ALTER TABLE `extokens`
  ADD CONSTRAINT `extokens_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`txn_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
