/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_account_role` (`role_id`),
  CONSTRAINT `fk_account_role` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `account` (`id`, `google_id`, `username`, `email`, `fullname`, `password`, `created_at`, `role_id`) VALUES
	(1, NULL, 'huy', NULL, NULL, '$2y$12$JwBTFmm0KBSUbKAqRAw5puZ1LtKgqwLvqT7g.h30rRziIkpo3zzDu', '2024-11-27 02:57:27', 1),
	(3, NULL, 'user1', NULL, 'user1', '$2y$12$ES3lrMQBmpB/bEvJENGvvOMqcBP/B7ROC.S8i70its8zbtsanGGNa', '2024-12-11 01:55:40', 5),
	(4, NULL, 'user2', NULL, 'user2', '$2y$12$Nbbp53jsDhKar1hZodxglOpAS8.u/tVltSrrsY82vwSOOt3oeYp8e', '2024-12-11 03:02:56', 1),
	(5, NULL, 'user3', NULL, 'user3', '$2y$12$woB7svCC/ow9IRjG7QKlZO2vzJTB5P0gTi9n/iPCPXbFYw2Hj/QZS', '2024-12-11 03:44:31', 5);

CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='insert into (name , description) values ("laptop","laptop nice choose");';

INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'laptop', 'laptop nice choose'),
	(2, 'CPU', 'power very strong');

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `orders` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
	(1, 'nguyen tuan huy', '0344403943', 'lam dong', '2024-11-27 02:33:23'),
	(2, 'nguyen van linh', '0909098766', 'nha trang', '2024-11-27 02:58:31'),
	(3, 'fdaf fdsfda', '0344403943', 'fwf', '2024-12-10 12:24:49');

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 1, 2, 280000.00),
	(2, 1, 3, 1, 28000.00),
	(3, 2, 1, 1, 280000.00),
	(4, 2, 3, 1, 28000.00),
	(5, 3, 1, 1, 280000.00),
	(6, 3, 4, 1, 32321.00);

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
	(1, 'view_products', 'Can view product catalog'),
	(2, 'create_product', 'Can add new products'),
	(3, 'edit_product', 'Can modify existing products'),
	(4, 'delete_product', 'Can remove products'),
	(5, 'view_orders', 'Can view order information'),
	(6, 'create_order', 'Can place new orders'),
	(7, 'manage_users', 'Can manage user accounts'),
	(8, 'view_categories', 'Can view product categories'),
	(9, 'create_category', 'Can create new product categories'),
	(10, 'edit_category', 'Can modify product categories');

CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
	(3, 'asus thin gf 65', 'mo ta', 28000.00, 'uploads/information technology.jpg', 1),
	(4, '3qd', 'sss', 32321.00, 'uploads/Thành phố Cyberpunk_ (3).jpg', 2),
	(5, 'huy', 'fda', 223.00, 'uploads/springsecurity6.jpg', 1);

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(1, 2),
	(2, 2),
	(4, 2),
	(1, 3),
	(2, 3),
	(4, 3),
	(1, 4),
	(2, 4),
	(4, 4),
	(1, 5),
	(2, 5),
	(3, 5),
	(1, 6),
	(2, 6),
	(3, 6),
	(5, 6),
	(1, 7),
	(1, 8),
	(2, 8),
	(4, 8),
	(1, 9),
	(2, 9),
	(4, 9),
	(1, 10),
	(2, 10),
	(4, 10);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES
	(1, 'admin', 'Full system access and control'),
	(2, 'manager', 'Partial administrative access'),
	(3, 'sales_rep', 'Sales and order management'),
	(4, 'inventory_manager', 'Product and category management'),
	(5, 'customer', 'Basic browsing and ordering');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
