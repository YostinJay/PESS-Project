-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2020 at 06:57 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pessdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `patrolcar`
--

CREATE TABLE `patrolcar` (
  `patrolcarId` varchar(10) CHARACTER SET latin1 NOT NULL,
  `patrolcarStatusId` varchar(1) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patrolcar`
--

INSERT INTO `patrolcar` (`patrolcarId`, `patrolcarStatusId`) VALUES
('QX1111J', '4'),
('QX1234A', '2'),
('QX1342G', '4'),
('QX2222K', '3'),
('QX2288D', '3'),
('QX3456B', '3'),
('QX5555D', '3'),
('QX8723W', '4'),
('QX8769P', '4'),
('QX8923T', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patrolcar`
--
ALTER TABLE `patrolcar`
  ADD PRIMARY KEY (`patrolcarId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
