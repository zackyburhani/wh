-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 10, 2018 at 05:40 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id_warehouse` varchar(10) NOT NULL,
  `nm_warehouse` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `address` text,
  `status` varchar(10) DEFAULT NULL,
  `heavy_max` bigint(10) DEFAULT NULL,
  `heavy_consumed` decimal(10,2) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id_warehouse`, `nm_warehouse`, `country`, `address`, `status`, `heavy_max`, `heavy_consumed`, `latitude`, `longitude`) VALUES
('0001', 'IKEA Sweden', 'Sweden', 'Sweden', '1', 100000000, '99684.00', '60.12816100000001', '18.643501000000015'),
('0002', 'IKEA Indonesia', 'Indonesia', 'Alam Sutra', '0', 1000000000000, '75.00', '-0.789275', '113.92132700000002'),
('0003', 'IKEA Japan', 'Japan', 'Akihabara', '1', 100000000000, '99775.00', '36.204824', '138.252924'),
('0004', 'IKEA Tarim', 'Yemen', 'Tarim, Yemen', '0', 1000000000000, '0.00', '16.0567117', '49.00598519999994');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id_warehouse`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
