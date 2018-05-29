-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2018 at 05:47 AM
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
  `id_package` varchar(10) NOT NULL,
  `id_item` varchar(10) NOT NULL,
  `qty` int(10) NOT NULL,
  `total` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_categories` varchar(10) NOT NULL,
  `nm_categories` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_categories`, `nm_categories`) VALUES
('0001', 'elektronik'),
('0002', 'olahraga'),
('0003', 'handphone'),
('0004', 'kamera'),
('0005', 'dapur');

-- --------------------------------------------------------

--
-- Table structure for table `detil_po`
--

CREATE TABLE `detil_po` (
  `id_po` varchar(10) NOT NULL,
  `date_po` date NOT NULL,
  `qty` int(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  `total_weight` int(20) NOT NULL,
  `id_item` varchar(10) NOT NULL
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
  `status` varchar(1) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`id_employer`, `username`, `nm_employer`, `id_position`, `password`, `last_login`, `status`, `image`) VALUES
('0001', 'Admin', 'Administrator', '0001', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2018-05-29 05:38:36', '1', 'admin_v01D_support.png'),
('0002', 'zackyburhani', 'Zacky Burhani Hotib', '0002', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2018-05-28 20:12:43', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` varchar(10) NOT NULL,
  `nm_item` varchar(20) NOT NULL,
  `colour` varchar(20) NOT NULL,
  `width` int(20) NOT NULL,
  `height` int(20) NOT NULL,
  `length` int(20) NOT NULL,
  `weight` int(20) NOT NULL,
  `stock` int(20) NOT NULL,
  `id_package` varchar(10) NOT NULL,
  `id_subcategories` varchar(20) DEFAULT NULL,
  `id_location` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id_item`, `nm_item`, `colour`, `width`, `height`, `length`, `weight`, `stock`, `id_package`, `id_subcategories`, `id_location`) VALUES
('0001', 'Samsung LED TV 2', 'White', 200, 200, 200, 200, 2000, '1', '0005', '2');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id_location` varchar(10) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `floor` varchar(20) NOT NULL,
  `room` varchar(20) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id_package` varchar(10) NOT NULL,
  `nm_package` varchar(20) NOT NULL,
  `height` int(20) NOT NULL,
  `lenght` int(20) NOT NULL,
  `weight` int(20) NOT NULL,
  `width` int(20) NOT NULL,
  `jml_stock` int(20) NOT NULL
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
  `nm_position` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id_position`, `nm_position`) VALUES
('0001', 'administrator'),
('0002', 'Manager'),
('0003', 'Employee');

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `id_shipment` varchar(10) NOT NULL,
  `date_shipment` date NOT NULL,
  `id_po` varchar(10) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  `id_employer` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id_subcategories` varchar(10) NOT NULL,
  `nm_subcategories` varchar(20) NOT NULL,
  `id_categories` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id_subcategories`, `nm_subcategories`, `id_categories`) VALUES
('0001', 'Sepatu Running', '0002'),
('0002', 'Laptop', '0001'),
('0003', 'TV', '0001'),
('0004', 'Iphone X', '0003'),
('0005', 'DSLR', '0004'),
('0006', 'Kompor', '0005');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id_warehouse` varchar(10) NOT NULL,
  `nm_warehouse` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `heavy_max` int(10) NOT NULL,
  `heavy_consumed` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bpack`
--
ALTER TABLE `bpack`
  ADD PRIMARY KEY (`id_bpack`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categories`);

--
-- Indexes for table `detil_po`
--
ALTER TABLE `detil_po`
  ADD PRIMARY KEY (`id_po`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`id_employer`),
  ADD KEY `id_position` (`id_position`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_location`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id_package`);

--
-- Indexes for table `po`
--
ALTER TABLE `po`
  ADD PRIMARY KEY (`id_po`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id_position`);

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
-- Constraints for table `employer`
--
ALTER TABLE `employer`
  ADD CONSTRAINT `employer_ibfk_1` FOREIGN KEY (`id_position`) REFERENCES `position` (`id_position`);

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id_categories`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
