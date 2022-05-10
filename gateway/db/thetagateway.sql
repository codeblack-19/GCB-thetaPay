-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 07, 2022 at 01:03 PM
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
-- Database: `thetagateway`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountNo` int(11) NOT NULL,
  `pinCode` varchar(100) NOT NULL,
  `balance` float NOT NULL,
  `businessName` varchar(100) NOT NULL,
  `secreteKey` varchar(64) NOT NULL,
  `status` enum('notverified','verified','blocked','') NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `account_keys`
--

CREATE TABLE `account_keys` (
  `id` int(11) NOT NULL,
  `apiKey` varchar(300) NOT NULL,
  `publicKey` varchar(300) NOT NULL,
  `appName` varchar(50) NOT NULL,
  `date` varchar(100) NOT NULL,
  `accountNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `cvv` int(4) NOT NULL,
  `expiry_mm` int(2) NOT NULL,
  `card_no` bigint(20) NOT NULL,
  `holder_name` varchar(100) NOT NULL,
  `expiry_yy` int(2) NOT NULL,
  `balance` float NOT NULL,
  `secreteCode` int(10) NOT NULL,
  `bankName` enum('ECOBANK','GCB','CALBANK','ACCESS BANK') NOT NULL,
  `bankAcct_No` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`cvv`, `expiry_mm`, `card_no`, `holder_name`, `expiry_yy`, `balance`, `secreteCode`, `bankName`, `bankAcct_No`) VALUES
(332, 3, 4020271367359028, 'Duncan Yehowa', 25, 99994400, 399484, 'ECOBANK', 1101010101),
(566, 2, 4532602790008819, 'Mary Jones', 50, 999495000, 493889, 'CALBANK', 1102020202),
(343, 4, 4532602790008839, 'ThetaPay', 35, 2948.72, 299440, 'GCB', 1104040404),
(667, 6, 4556569287652063, 'Jeffery Mills', 45, 788775000, 765432, 'ACCESS BANK', 1103030303);

-- --------------------------------------------------------

--
-- Table structure for table `card_payment`
--

CREATE TABLE `card_payment` (
  `id` int(11) NOT NULL,
  `txnId` varchar(24) NOT NULL,
  `card_no` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` varchar(16) NOT NULL,
  `status` enum('initiated','pending','success','failed') NOT NULL,
  `amount` float NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type` enum('deposit','webpayment','transfer','cashout') NOT NULL,
  `refunded` tinyint(1) NOT NULL DEFAULT 0,
  `webhook` varchar(200) NOT NULL,
  `token` varchar(400) DEFAULT NULL,
  `currency` enum('GHS','USD','EUR') NOT NULL,
  `medium` enum('internal','card','bank') DEFAULT NULL,
  `createdAt` varchar(100) NOT NULL,
  `updatedAt` varchar(100) DEFAULT NULL,
  `accountNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) NOT NULL,
  `recipient_acctNo` int(11) NOT NULL,
  `txnId` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `firstname` varchar(10) NOT NULL,
  `lastname` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `authToken` varchar(500) NOT NULL,
  `role` enum('customer','super_admin','admin') DEFAULT 'customer',
  `createdAt` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `phone`, `email`, `authToken`, `role`, `createdAt`) VALUES
('c87ba4123459', 'Magnito', 'Fenny', '$2y$10$3sT3j5aR5hykNYuiwwkT8.mSZTfpiz58h/7f6RyopF1qJ1Mfaxm/O', '+233959884993', 'magnito1428@gmail.com', '', 'super_admin', '2022/03/25 14:1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountNo`),
  ADD UNIQUE KEY `secreteKey` (`secreteKey`) USING BTREE,
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `account_keys`
--
ALTER TABLE `account_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appName` (`appName`),
  ADD KEY `accountNo` (`accountNo`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`card_no`),
  ADD UNIQUE KEY `bankAcct_No` (`bankAcct_No`);

--
-- Indexes for table `card_payment`
--
ALTER TABLE `card_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `txnId` (`txnId`),
  ADD KEY `card_no` (`card_no`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountNo` (`accountNo`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `txnId` (`txnId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `account_keys`
--
ALTER TABLE `account_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `card_payment`
--
ALTER TABLE `card_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `account_keys`
--
ALTER TABLE `account_keys`
  ADD CONSTRAINT `account_keys_ibfk_1` FOREIGN KEY (`accountNo`) REFERENCES `accounts` (`accountNo`) ON DELETE CASCADE;

--
-- Constraints for table `card_payment`
--
ALTER TABLE `card_payment`
  ADD CONSTRAINT `card_payment_ibfk_1` FOREIGN KEY (`txnId`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `card_payment_ibfk_2` FOREIGN KEY (`card_no`) REFERENCES `cards` (`card_no`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`accountNo`) REFERENCES `accounts` (`accountNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`txnId`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
