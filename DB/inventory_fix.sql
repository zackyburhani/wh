-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 19, 2018 at 12:42 PM
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
('0001', 'Admin', 'Administrator Sweden', '0001', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2018-06-19 10:28:52', '1', 'sweden_flag.png', '0001'),
('0002', 'adminzevantem', 'Administrator Zaventem', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:15:40', '1', 'belgium_flag.png', '0002'),
('0003', 'adminarlon', 'Administrator Arlon', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:19:56', '1', 'belgium_flag.png', '0003'),
('0004', 'admincroatia', 'Administrator Croatia', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:20:59', '1', 'croatia_flag.png', '0004'),
('0005', 'adminczechrepublic', 'Administrator Czech Republic', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:22:36', '1', 'czechia_flag.png', '0005'),
('0006', 'adminodense', 'Administrator Odense', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:23:42', '1', 'denmark_flag.png', '0006'),
('0007', 'adminparisnord', 'Administrator Paris Nord', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:24:32', '1', 'france_flag.png', '0007'),
('0008', 'adminalamsutera', 'Administrator Alam Sutera', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:25:28', '1', 'indonesia_flag.png', '0008'),
('0009', 'admindubin', 'Administrator Dubin', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:26:39', '1', 'ireland_flag.png', '0009'),
('0010', 'adminitaly', 'Administrator Italy', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:27:18', '1', 'italy_flag.png', '0010'),
('0011', 'admintsuruhama', 'Administrator Tsuruhama', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:28:33', '1', 'japan_flag.png', '0011'),
('0012', 'admindamansara', 'Administrator Damansara', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:29:11', '1', 'malaysian_flag.png', '0012'),
('0013', 'adminmorocco', 'Administrator Morocco', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:30:20', '1', 'morocco_flag.png', '0013'),
('0014', 'adminpoland', 'Administrator Poland', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:32:36', '1', 'polland_flag.png', '0014'),
('0015', 'adminnetherlands', 'Administrator Netherlands', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:33:24', '1', 'netherland_flag.png', '0015'),
('0016', 'adminportugal', 'Administrator Portugal', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:34:08', '1', 'portugal_flag.png', '0016'),
('0017', 'adminbalayadacha', 'Administrator Belaya Dacha', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:35:04', '1', 'russia_flag.png', '0017'),
('0018', 'adminsaudiarabia', 'Administrator Saudi Arabia', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:36:17', '1', 'saudi_flag.png', '0019'),
('0019', 'adminserbia', 'Administrator Serbia', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:37:05', '1', 'serbia_flag.png', '0020'),
('0020', 'admintampines', 'Administrator Tampines', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:37:52', '1', 'singapore_flag.png', '0021'),
('0021', 'adminsouthkorea', 'Administrator South Korea', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:38:39', '1', 's_korea_flag.png', '0022'),
('0022', 'adminspain', 'Administrator Spain', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:39:20', '1', 'spain_flag.png', '0023'),
('0023', 'adminkaohsiung', 'Administrator Kaohsiung', '0002', '8cb2237d0679ca88db6464eac60da96345513964', '2018-06-19 12:40:04', '1', 'taiwan_flag.png', '0024');

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
  `diameter` decimal(5,2) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `stock` int(20) NOT NULL,
  `safety_stock` int(20) DEFAULT NULL,
  `id_package` varchar(10) DEFAULT NULL,
  `id_subcategories` varchar(20) DEFAULT NULL,
  `id_location` varchar(10) DEFAULT NULL
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

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id_location`, `unit`, `floor`, `room`, `id_warehouse`) VALUES
('0001', 'hj', 'hj', 'hj', '0001');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id_message` int(10) UNSIGNED NOT NULL,
  `subject` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `message` text,
  `from_warehouse` varchar(10) DEFAULT NULL,
  `to_warehouse` varchar(10) DEFAULT NULL,
  `status` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id_package` varchar(10) NOT NULL,
  `nm_package` varchar(20) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `length` decimal(5,2) DEFAULT NULL,
  `diameter` decimal(5,2) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `width` decimal(5,2) DEFAULT NULL,
  `jml_stock` int(20) DEFAULT NULL,
  `safety_stock` int(20) DEFAULT NULL,
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
('0001', 'administrator', '0', '0001'),
('0002', 'admin', '1', '0001'),
('0003', 'manager', '2', '0001'),
('0004', 'Employee', '3', '0001');

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
('0001', 'IKEA Sweden', 'Sweden', 'Sweden', '1', 100000000, '0.00', '60.12816100000001', '18.643501000000015'),
('0002', 'IKEA Zaventem', 'Belgium', 'Weiveldlaan 19, 1930 Zaventem, Belgium', '1', 100000000, '0.00', '50.8716695', '4.492763599999989'),
('0003', 'IKEA Arlon', 'Belgium', 'Rue de Grass 100, 6700 Arlon, Belgium', '1', 100000000, '0.00', '49.6371676', '5.890511800000013'),
('0004', 'IKEA Croatia', 'Croatia', 'Alfreda Nobela, 10361, Otok Svibovski, Croatia', '1', 100000000, '0.00', '45.7916019', '16.157924099999946'),
('0005', 'IKEA Czech Republic', 'Czechia', 'RudnÃ¡ 3114/114, 700 30 Ostrava-jih, Czechia', '1', 1000000000, '0.00', '49.7989283', '18.227742499999977'),
('0006', 'IKEA Odense', 'Denmark', 'Ã˜rbÃ¦kvej 91, 5220 Odense, Denmark', '1', 100000000, '0.00', '55.3809151', '10.430416899999955'),
('0007', 'IKEA Paris Nord', 'France', '164 Avenue de la Plaine de France, 95500 Gonesse, France', '1', 100000000, '0.00', '48.97498849999999', '2.4945433999999977'),
('0008', 'IKEA Alam Sutera', 'Indonesia', 'Jalan Jalur Sutera Boulevard Kav.45, Kunciran, Pinang, Kunciran, Pinang, Kota Tangerang, Banten 15320, Indonesia', '1', 100000000, '0.00', '-6.2200456', '106.66323549999993'),
('0009', 'IKEA DUBIN', 'Ireland', 'St Margaret\'s Rd, Dublin, Ireland', '1', 100000000, '0.00', '53.4059294', '-6.283512999999971'),
('0010', 'IKEA Italy', 'Italy', 'Via dell\'Artigianato, 7, 20061 Carugate MI, Italy', '1', 100000000, '0.00', '45.5504511', '9.331781399999954'),
('0011', 'IKEA Tsuruhama', 'Japan', '2 Chome-24-55 Tsurumachi, TaishÅ-ku, ÅŒsaka-shi, ÅŒsaka-fu 551-0023, Japan', '0', 100000000, '0.00', '34.6369348', '135.44965479999996'),
('0012', 'IKEA Damansara', 'Malaysia', '2, Jalan PJU 7/2, Mutiara Damansara, 47800 Petaling Jaya, Selangor, Malaysia', '1', 100000000, '0.00', '3.1570193', '101.61293269999999'),
('0013', 'IKEA Morocco', 'Morocco', 'AÃ¯n Harrouda, Morocco', '1', 100000000, '0.00', '33.6353103', '-7.450382900000022'),
('0014', 'IKEA Poland', 'Poland', 'Malborska 51, 03-286 Warszawa, Poland', '1', 100000000, '0.00', '52.3022994', '21.079825400000004'),
('0015', 'IKEA Netherlands', 'Netherlands', 'Olof Palmestraat 1, 2616 LN Delft, Netherlands', '1', 100000000, '0.00', '52.01291399999999', '4.381651899999952'),
('0016', 'IKEA Portugal', 'Portugal', 'Av. Dr. Ã“scar Lopes, LeÃ§a da Palmeira, Portugal', '1', 100000000, '0.00', '41.2082057', '-8.685803699999951'),
('0017', 'IKEA Belaya Dacha', 'Russia', '1-Y Pokrovskiy Proyezd, 4, Kotelniki, Moskovskaya oblast\', Russia, 140055', '1', 100000000, '0.00', '55.6536415', '37.84765760000005'),
('0019', 'IKEA Saudi Arabia', 'Saudi Arabia', 'Prince Mohammed Bin Abdulaziz St, Jeddah Saudi Arabia', '1', 100000000, '0.00', '21.5577623', '39.180507600000055'),
('0020', 'IKEA Serbia', 'Serbia', 'Belgrade, Serbia', '1', 100000000, '0.00', '44.786568', '20.44892159999995'),
('0021', 'IKEA Tampines', 'Singapore', '60 Tampines North Drive 2, Singapore 528764', '1', 100000000, '0.00', '1.3741837', '103.93221970000002'),
('0022', 'IKEA South Korea', 'South Korea', 'Iljing-ro, Seoksu 2(i)-dong, Manan-gu, Anyang, Gyeonggi-do, South Korea', '1', 100000000, '0.00', '37.4191564', '126.89473989999999'),
('0023', 'IKEA Spain', 'Spain', 'Avinguda de la Granvia, 115, 08908 L\'Hospitalet de Llobregat, Barcelona, Spain', '1', 100000000, '0.00', '41.3555427', '2.124105399999962'),
('0024', 'IKEA Kaohsiung', 'Taiwan', 'No. 1201è™Ÿ, Zhonghua 5th Road, Qianzhen District, Kaohsiung City, Taiwan 806', '1', 100000000, '0.00', '22.605706', '120.30393000000004');

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
  ADD KEY `id_subcategories` (`id_subcategories`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_location`),
  ADD KEY `id_warehouse` (`id_warehouse`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `from_warehouse` (`from_warehouse`),
  ADD KEY `to_warehouse` (`to_warehouse`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`id_subcategories`) REFERENCES `sub_categories` (`id_subcategories`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`id_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`from_warehouse`) REFERENCES `warehouse` (`id_warehouse`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`to_warehouse`) REFERENCES `warehouse` (`id_warehouse`);

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
