-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2022 at 11:54 PM
-- Server version: 8.0.29-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Database: `StocksStore`
--

CREATE DATABASE IF NOT EXISTS `StocksStore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `StocksStore`;

-- --------------------------------------------------------

--
-- Table structure for table `Stocks`
--

CREATE TABLE `Stocks` (
  `ID` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `quantity` int NOT NULL,
  `avgPrice` float(6,2) NOT NULL,
  `targetPrice` int DEFAULT NULL,
  `stopLoss` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Stocks`
--

INSERT INTO `Stocks` (`ID`, `name`, `symbol`, `quantity`, `avgPrice`, `targetPrice`, `stopLoss`) VALUES
(1, 'Airtel', 'BHARTIARTL', 2, 719.65, NULL, NULL),
(2, 'NTPC', 'NTPC', 2, 128.00, NULL, NULL),
(3, 'Tata Consumer Products', 'TATACONSUM', 3, 777.10, NULL, NULL),
(4, 'Tata Motors DVR', 'TATAMTRDVR', 2, 264.35, NULL, NULL),
(5, 'Bank of Baroda', 'BANKBARODA', 16, 100.26, 120, NULL),
(6, 'Power Grid Corporation', 'POWERGRID', 8, 241.33, NULL, NULL),
(7, 'Steel Authority of India', 'SAIL', 20, 106.30, NULL, NULL),
(8, 'Man Industries', 'MANINDS', 3, 96.20, NULL, NULL),
(9, 'Vodafone Idea', 'IDEA', 15, 14.65, NULL, NULL),
(10, 'Start Cement', 'STARCEMENT', 1, 116.70, NULL, NULL),
(11, 'Zenlabs Ethica', '530697', 1, 51.90, NULL, NULL),
(12, 'Trident', 'TRIDENT', 1, 63.00, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Stocks`
--
ALTER TABLE `Stocks`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Stocks`
--
ALTER TABLE `Stocks`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;
