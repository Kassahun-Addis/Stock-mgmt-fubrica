-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table stock.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int NOT NULL DEFAULT '0',
  `brand_status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.brands: ~13 rows (approximately)
INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_active`, `brand_status`) VALUES
	(1, 'Gap', 1, 2),
	(2, 'Forever 21', 1, 2),
	(3, 'Gap', 1, 2),
	(4, 'Forever 21', 1, 2),
	(5, 'Adidas', 1, 2),
	(6, 'Gap', 1, 2),
	(7, 'Forever 21', 1, 2),
	(8, 'Adidas', 1, 2),
	(9, 'Gap', 1, 2),
	(10, 'Forever 21', 1, 2),
	(11, 'Adidas', 1, 1),
	(12, 'Gap', 1, 1),
	(13, 'Forever 21', 1, 1);

-- Dumping structure for table stock.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int NOT NULL AUTO_INCREMENT,
  `categories_name` varchar(255) NOT NULL,
  `categories_active` int NOT NULL DEFAULT '0',
  `categories_status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.categories: ~8 rows (approximately)
INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_active`, `categories_status`) VALUES
	(1, 'Sports ', 1, 2),
	(2, 'Casual', 1, 2),
	(3, 'Casual', 1, 2),
	(4, 'Sport', 1, 2),
	(5, 'Casual', 1, 2),
	(6, 'Sport wear', 1, 2),
	(7, 'Casual wear', 1, 1),
	(8, 'Sports ', 1, 1);

-- Dumping structure for table stock.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` int NOT NULL,
  `payment_status` int NOT NULL,
  `order_status` int NOT NULL DEFAULT '0',
  `purchase` varchar(50) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `fs_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.orders: ~0 rows (approximately)
INSERT INTO `orders` (`order_id`, `order_date`, `client_name`, `client_contact`, `sub_total`, `vat`, `total_amount`, `discount`, `grand_total`, `paid`, `due`, `payment_type`, `payment_status`, `order_status`, `purchase`, `serial_no`, `fs_no`) VALUES
	(1, '2024-02-29', 'ermias', '091298', '1000.00', '130.00', '1130.00', '0', '1130.00', '1130', '0.00', 2, 1, 1, NULL, NULL, '9999');

-- Dumping structure for table stock.order_item
CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL DEFAULT '0',
  `product_id` int NOT NULL DEFAULT '0',
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `order_item_status` int NOT NULL DEFAULT '0',
  `purchase` varchar(50) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `fs_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.order_item: ~13 rows (approximately)
INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `rate`, `total`, `order_item_status`, `purchase`, `serial_no`, `fs_no`) VALUES
	(1, 1, 7, '4', '250', '1000.00', 1, '200', '333', NULL);

-- Dumping structure for table stock.product
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `product_image` text NOT NULL,
  `brand_id` int NOT NULL,
  `categories_id` int NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `active` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `purchase` varchar(50) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.product: ~10 rows (approximately)
INSERT INTO `product` (`product_id`, `product_name`, `product_image`, `brand_id`, `categories_id`, `quantity`, `rate`, `active`, `status`, `purchase`, `serial_no`) VALUES
	(1, 'Half pant', '../assests/images/stock/2847957892502c7200.jpg', 1, 2, '19', '1500', 2, 2, '100', NULL),
	(2, 'T-Shirt', '../assests/images/stock/163965789252551575.jpg', 2, 2, '9', '1200', 2, 2, '250', NULL),
	(3, 'Half Pant', '../assests/images/stock/13274578927924974b.jpg', 5, 3, '18', '1200', 2, 2, '200', NULL),
	(4, 'T-Shirt', '../assests/images/stock/12299578927ace94c5.jpg', 6, 3, '29', '1000', 2, 2, '66', NULL),
	(5, 'Half Pant', '../assests/images/stock/24937578929c13532e.jpg', 8, 5, '17', '1200', 2, 2, '300', '555'),
	(6, 'Polo T-Shirt', '../assests/images/stock/10222578929f733dbf.jpg', 9, 5, '29', '1200', 2, 2, '250', '5555'),
	(7, 'Half Pant', '../assests/images/stock/1770257893463579bf.jpg', 11, 7, '21', '250', 1, 1, '200', '333'),
	(8, 'Polo T-shirt', '../assests/images/stock/136715789347d1aea6.jpg', 12, 7, '9', '1200', 1, 1, '1000', '111'),
	(9, 'Nike Air force', '../assests/images/stock/95283770465df1cd7a7955.png', 11, 8, '0', '500', 1, 1, '450', ''),
	(10, 'aaa', '../assests/images/stock/38399682065df57b9c12c8.png', 11, 7, '41', '200', 1, 1, '150', '4445522200');

-- Dumping structure for table stock.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.users: ~0 rows (approximately)
INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES
	(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
