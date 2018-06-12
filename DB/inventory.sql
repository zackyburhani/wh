-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 10, 2018 at 06:49 PM
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
-- Table structure for table `bpack`
--

CREATE TABLE `bpack` (
  `id_bpack` varchar(10) NOT NULL,
  `id_package` varchar(10) DEFAULT NULL,
  `id_item` varchar(10) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_categories` varchar(10) NOT NULL,
  `nm_categories` varchar(20) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `detil_po`
--

CREATE TABLE `detil_po` (
  `id_po` varchar(10) DEFAULT NULL,
  `date_po` date DEFAULT NULL,
  `qty` int(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL,
  `total_weight` int(20) DEFAULT NULL,
  `id_item` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `id_employer` varchar(10) NOT NULL,
  `username` varchar(60) NOT NULL,
  `nm_employer` varchar(30) NOT NULL,
  `id_position` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`id_employer`, `username`, `nm_employer`, `id_position`, `password`, `last_login`, `status`, `image`, `id_warehouse`) VALUES
('0001', 'Admin', 'Administrator', '0001', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2018-06-10 18:10:59', '1', 'admin_v01D_support.png', '0001');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` varchar(10) NOT NULL,
  `nm_item` varchar(20) DEFAULT NULL,
  `colour` varchar(20) DEFAULT NULL,
  `width` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `length` decimal(5,2) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `stock` int(20) NOT NULL,
  `safety_stock` int(20) DEFAULT NULL,
  `id_package` varchar(10) DEFAULT NULL,
  `id_subcategories` varchar(20) DEFAULT NULL,
  `id_location` varchar(10) DEFAULT NULL,
  `id_bpack` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id_location` varchar(10) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `floor` varchar(20) DEFAULT NULL,
  `room` varchar(20) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id_package` varchar(10) NOT NULL,
  `nm_package` varchar(20) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `lenght` decimal(5,2) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `width` decimal(5,2) DEFAULT NULL,
  `jml_stock` int(20) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po`
--

CREATE TABLE `po` (
  `id_po` varchar(10) NOT NULL,
  `date_po` date NOT NULL,
  `id_warehouse` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id_position` varchar(20) NOT NULL,
  `nm_position` varchar(20) DEFAULT NULL,
  `level_user` enum('0','1','2','3') DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id_position`, `nm_position`, `level_user`, `id_warehouse`) VALUES
('0001', 'administrator', '0', '0001');

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `id_shipment` varchar(10) NOT NULL,
  `date_shipment` date DEFAULT NULL,
  `id_po` varchar(10) DEFAULT NULL,
  `id_warehouse` varchar(10) DEFAULT NULL,
  `id_employer` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id_subcategories` varchar(10) NOT NULL,
  `nm_subcategories` varchar(20) DEFAULT NULL,
  `id_categories` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('0001', 'IKEA Sweden', 'Sweden', 'Sweden', '1', 100000, '93600.00', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bpack`
--
ALTER TABLE `bpack`
  ADD PRIMARY KEY (`id_bpack`),
  ADD KEY `id_package` (`id_package`),
  ADD KEY `id_item` (`id_item`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categories`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `detil_po`
--
ALTER TABLE `detil_po`
  ADD KEY `id_po` (`id_po`),
  ADD KEY `id_item` (`id_item`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`id_employer`),
  ADD KEY `id_position` (`id_position`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_location` (`id_location`),
  ADD KEY `id_package` (`id_package`),
  ADD KEY `id_subcategories` (`id_subcategories`),
  ADD KEY `id_bpack` (`id_bpack`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_location`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id_package`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `po`
--
ALTER TABLE `po`
  ADD PRIMARY KEY (`id_po`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id_position`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`id_shipment`),
  ADD KEY `id_employer` (`id_employer`),
  ADD KEY `id_po` (`id_po`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id_subcategories`),
  ADD KEY `id_categories` (`id_categories`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id_warehouse`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bpack`
--
ALTER TABLE `bpack`
  ADD CONSTRAINT `bpack_ibfk_1` FOREIGN KEY (`id_package`) REFERENCES `package` (`id_package`),
  ADD CONSTRAINT `bpack_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `detil_po`
--
ALTER TABLE `detil_po`
  ADD CONSTRAINT `detil_po_ibfk_1` FOREIGN KEY (`id_po`) REFERENCES `po` (`id_po`),
  ADD CONSTRAINT `detil_po_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`),
  ADD CONSTRAINT `detil_po_ibfk_3` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `employer`
--
ALTER TABLE `employer`
  ADD CONSTRAINT `employer_ibfk_1` FOREIGN KEY (`id_position`) REFERENCES `position` (`id_position`),
  ADD CONSTRAINT `employer_ibfk_2` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`id_location`) REFERENCES `location` (`id_location`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`id_package`) REFERENCES `package` (`id_package`),
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`id_subcategories`) REFERENCES `sub_categories` (`id_subcategories`),
  ADD CONSTRAINT `item_ibfk_4` FOREIGN KEY (`id_bpack`) REFERENCES `bpack` (`id_bpack`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `package_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `po`
--
ALTER TABLE `po`
  ADD CONSTRAINT `po_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`id_employer`) REFERENCES `employer` (`id_employer`),
  ADD CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`id_po`) REFERENCES `po` (`id_po`),
  ADD CONSTRAINT `shipment_ibfk_3` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id_categories`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
