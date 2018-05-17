﻿# Host: localhost  (Version 5.5.5-10.1.30-MariaDB)
# Date: 2018-05-17 11:58:01
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "bpack"
#

DROP TABLE IF EXISTS `bpack`;
CREATE TABLE `bpack` (
  `id_bpack` varchar(10) NOT NULL,
  `id_package` varchar(10) NOT NULL,
  `id_item` varchar(10) NOT NULL,
  `qty` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  PRIMARY KEY (`id_bpack`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "bpack"
#


#
# Structure for table "categories"
#

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id_categories` varchar(10) NOT NULL,
  `nm_categories` varchar(20) NOT NULL,
  PRIMARY KEY (`id_categories`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "categories"
#


#
# Structure for table "detil_po"
#

DROP TABLE IF EXISTS `detil_po`;
CREATE TABLE `detil_po` (
  `id_po` varchar(10) NOT NULL,
  `date_po` date NOT NULL,
  `qty` int(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  `total_weight` int(20) NOT NULL,
  `id_item` varchar(10) NOT NULL,
  PRIMARY KEY (`id_po`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "detil_po"
#


#
# Structure for table "employer"
#

DROP TABLE IF EXISTS `employer`;
CREATE TABLE `employer` (
  `id_employer` varchar(10) NOT NULL,
  `nm_employer` varchar(30) NOT NULL,
  `id_position` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`id_employer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "employer"
#


#
# Structure for table "item"
#

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id_item` varchar(10) NOT NULL,
  `nm_item` varchar(20) NOT NULL,
  `colour` varchar(20) NOT NULL,
  `width` int(20) NOT NULL,
  `height` int(20) NOT NULL,
  `lenght` int(20) NOT NULL,
  `weight` int(20) NOT NULL,
  `stock` int(20) NOT NULL,
  `id_package` varchar(10) NOT NULL,
  `id_subcategories` varchar(20) NOT NULL,
  `id_location` varchar(10) NOT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "item"
#


#
# Structure for table "location"
#

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id_location` varchar(10) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `floor` varchar(20) NOT NULL,
  `room` varchar(20) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  PRIMARY KEY (`id_location`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "location"
#


#
# Structure for table "package"
#

DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id_package` varchar(10) NOT NULL,
  `nm_package` varchar(20) NOT NULL,
  `height` int(20) NOT NULL,
  `lenght` int(20) NOT NULL,
  `weight` int(20) NOT NULL,
  `width` int(20) NOT NULL,
  `jml_stock` int(20) NOT NULL,
  PRIMARY KEY (`id_package`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "package"
#


#
# Structure for table "po"
#

DROP TABLE IF EXISTS `po`;
CREATE TABLE `po` (
  `id_po` varchar(10) NOT NULL,
  `date_po` date NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  PRIMARY KEY (`id_po`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "po"
#


#
# Structure for table "position"
#

DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `id_position` varchar(20) NOT NULL,
  `nm_position` varchar(20) NOT NULL,
  PRIMARY KEY (`id_position`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "position"
#


#
# Structure for table "shipment"
#

DROP TABLE IF EXISTS `shipment`;
CREATE TABLE `shipment` (
  `id_shipment` varchar(10) NOT NULL,
  `date_shipment` date NOT NULL,
  `id_po` varchar(10) NOT NULL,
  `id_warehouse` varchar(10) NOT NULL,
  `id_employer` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "shipment"
#


#
# Structure for table "sub_categories"
#

DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE `sub_categories` (
  `id_subcategories` varchar(10) NOT NULL,
  `nm_categories` varchar(20) NOT NULL,
  `id_categories` varchar(10) NOT NULL,
  PRIMARY KEY (`id_subcategories`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "sub_categories"
#


#
# Structure for table "warehouse"
#

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse` (
  `id_warehouse` varchar(10) NOT NULL,
  `nm_warehouse` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `heavy_max` int(10) NOT NULL,
  `heavy_consumed` int(10) NOT NULL,
  PRIMARY KEY (`id_warehouse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "warehouse"
#

