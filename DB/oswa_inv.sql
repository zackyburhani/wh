-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2018 at 11:49 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9



--
-- Database: `oswa_inv`
--
CREATE DATABASE IF NOT EXISTS `oswa_inv` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `oswa_inv`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(6, 'Handphone'),
(4, 'Kabel Jumper'),
(1, 'Laptop'),
(2, 'Printer'),
(7, 'Sepatu'),
(5, 'Speaker');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(5) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `warehouse_id` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `date`, `warehouse_id`) VALUES
(10, 'JBL', 12, '10.00', '20.00', 5, '2018-04-18 11:14:39', 3),
(11, 'Lenovo', 50, '20.00', '200.00', 1, '2018-04-18 11:17:13', 2),
(12, 'Fiber', 20, '100.00', '2000.00', 4, '2018-04-18 11:17:58', 2),
(13, 'Ovo', 120, '12.00', '50.00', 5, '2018-04-18 12:00:13', 3),
(14, 'Hp', 13, '122.00', '344.00', 1, '2018-04-18 13:34:12', 1),
(15, 'ASUS', 12, '232.00', '2122.00', 1, '2018-04-18 14:10:14', 2),
(17, 'Xoami', 23, '76.00', '238.00', 6, '2018-04-18 14:29:27', 1),
(19, 'Laptop Asus', 12, '121.00', '12.00', 1, '2018-04-18 14:30:30', 1),
(21, 'Acer', 123, '324.00', '23.00', 1, '2018-04-18 14:31:37', 1),
(22, 'Fiber Optik', 123, '54.00', '321.00', 4, '2018-04-18 15:40:29', 1),
(28, 'Hp', 4, '122.00', '344.00', 1, '2018-04-30 12:04:09', 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `q_produk`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `q_produk`;
CREATE TABLE `q_produk` (
`id` int(11) unsigned
,`name` varchar(255)
,`quantity` int(5)
,`buy_price` decimal(25,2)
,`sale_price` decimal(25,2)
,`categorie_id` int(11) unsigned
,`nama_kategori` varchar(60)
,`date` datetime
,`warehouse_id` int(11) unsigned
,`name_warehouse` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, ' Admin User', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2018-05-03 11:44:51'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2018-04-19 08:14:32'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2018-04-19 08:13:20'),
(4, 'gudang', 'gudang', 'gudang', 4, 'no_image.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'special', 2, 1),
(3, 'User', 3, 1),
(4, 'gudang', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL,
  `name_warehouse` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `name_warehouse`) VALUES
(1, 'Blok A'),
(2, 'Blok B'),
(3, 'Blok C'),
(4, 'Blok D'),
(5, 'Blok E'),
(6, 'Blok F'),
(7, 'Blok G'),
(9, 'Blok H');

-- --------------------------------------------------------

--
-- Structure for view `q_produk`
--
DROP TABLE IF EXISTS `q_produk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `q_produk`  AS  select `products`.`id` AS `id`,`products`.`name` AS `name`,`products`.`quantity` AS `quantity`,`products`.`buy_price` AS `buy_price`,`products`.`sale_price` AS `sale_price`,`products`.`categorie_id` AS `categorie_id`,`categories`.`name` AS `nama_kategori`,`products`.`date` AS `date`,`products`.`warehouse_id` AS `warehouse_id`,`warehouse`.`name_warehouse` AS `name_warehouse` from ((`products` join `warehouse` on((`products`.`warehouse_id` = `warehouse`.`id`))) join `categories` on((`products`.`categorie_id` = `categories`.`id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`,`warehouse_id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouse` (`name_warehouse`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


