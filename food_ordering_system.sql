-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: localhost    Database: food_ordering_system
-- ------------------------------------------------------
-- Server version	8.4.7

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrator','admin@foodorder.com','super_admin','2026-05-21 16:26:41','2026-05-16 19:57:53'),(2,'modern','$2y$10$DCLCBvHgdIcYYzTNSikeT.Ul5/w/DAwsf0o4OXsQNHKZmSHq7bcJS','Administrator','admin@foodorder.com','admin','2026-05-20 18:11:35','2026-05-16 20:25:23');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,1,3,2,'2026-05-16 19:57:53','2026-05-16 19:57:53'),(2,1,18,1,'2026-05-16 19:57:53','2026-05-16 19:57:53'),(3,2,5,1,'2026-05-16 19:57:53','2026-05-16 19:57:53');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Pizza','fa-pizza-slice','Delicious Italian pizzas made with fresh ingredients',1,'2026-05-16 19:57:53'),(2,'Burger','fa-hamburger','Juicy burgers with premium beef and fresh toppings',1,'2026-05-16 19:57:53'),(3,'Drinks','fa-mug-hot','Refreshing beverages and soft drinks',1,'2026-05-16 19:57:53'),(4,'Salads','fa-leaf','Healthy fresh salads with organic vegetables',1,'2026-05-16 19:57:53'),(5,'Desserts','fa-ice-cream','Sweet treats and desserts',1,'2026-05-16 19:57:53'),(6,'Pasta','fa-utensils','Authentic Italian pasta dishes',1,'2026-05-16 19:57:53'),(7,'Seafood','fa-fish','Fresh seafood specials',1,'2026-05-16 19:57:53'),(8,'Chinese','fa-egg','Authentic Chinese cuisine',1,'2026-05-16 19:57:53');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `daily_sales`
--

DROP TABLE IF EXISTS `daily_sales`;
/*!50001 DROP VIEW IF EXISTS `daily_sales`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `daily_sales` AS SELECT 
 1 AS `sale_date`,
 1 AS `total_orders`,
 1 AS `total_revenue`,
 1 AS `average_order_value`,
 1 AS `telebirr_sales`,
 1 AS `chapa_sales`,
 1 AS `cash_sales`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS ((`quantity` * `price`)) STORED,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES (1,1,1,1,12.99,'2026-05-16 19:57:53'),(2,1,17,1,2.99,'2026-05-16 19:57:53'),(3,2,2,1,14.99,'2026-05-16 19:57:53'),(4,3,4,2,15.99,'2026-05-16 19:57:53'),(5,3,20,1,3.99,'2026-05-16 19:57:53'),(6,4,9,1,9.99,'2026-05-16 19:57:53'),(7,4,10,1,11.99,'2026-05-16 19:57:53'),(8,4,11,1,10.99,'2026-05-16 19:57:53'),(9,4,29,1,6.99,'2026-05-16 19:57:53'),(10,5,47,1,9.99,'2026-05-16 20:12:18'),(11,6,49,1,60.00,'2026-05-17 08:06:29'),(12,7,15,1,12.99,'2026-05-17 08:17:40'),(13,7,49,1,60.00,'2026-05-17 08:17:40'),(14,8,48,1,8.99,'2026-05-17 08:56:14'),(15,9,23,1,1.99,'2026-05-17 17:01:25'),(16,10,2,1,14.99,'2026-05-17 18:28:38'),(18,11,31,1,4.99,'2026-05-20 16:40:20'),(19,12,45,1,5.99,'2026-05-21 05:37:26'),(20,12,57,1,8.00,'2026-05-21 05:37:26'),(21,13,15,1,12.99,'2026-05-21 06:21:42'),(22,13,48,1,8.99,'2026-05-21 06:21:42'),(23,13,51,1,2.30,'2026-05-21 06:21:42'),(24,14,58,10,21.00,'2026-05-21 06:29:24');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_log`
--

DROP TABLE IF EXISTS `order_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `old_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_status_log_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_log`
--

LOCK TABLES `order_status_log` WRITE;
/*!40000 ALTER TABLE `order_status_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `order_summary`
--

DROP TABLE IF EXISTS `order_summary`;
/*!50001 DROP VIEW IF EXISTS `order_summary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `order_summary` AS SELECT 
 1 AS `id`,
 1 AS `order_number`,
 1 AS `customer_name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `total_amount`,
 1 AS `delivery_fee`,
 1 AS `grand_total`,
 1 AS `payment_method`,
 1 AS `order_status`,
 1 AS `created_at`,
 1 AS `items_count`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) DEFAULT '2.99',
  `grand_total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Cash',
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `estimated_delivery_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_status` (`order_status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD1001',1,22.98,2.99,25.97,'Cash','completed','delivered','Bole Road, Addis Ababa, Ethiopia','+251911234567',NULL,'6:30 PM','2026-05-17 07:50:53','2026-05-11 19:57:53'),(2,'ORD1002',2,14.99,2.99,17.98,'Telebirr','completed','delivered','Kazanchis, Addis Ababa, Ethiopia','+251912345678',NULL,'7:15 PM',NULL,'2026-05-13 19:57:53'),(3,'ORD1003',1,35.97,2.99,38.96,'Chapa','paid','delivered','Bole Road, Addis Ababa, Ethiopia','+251911234567',NULL,'8:00 PM',NULL,'2026-05-15 19:57:53'),(4,'ORD1004',3,42.96,2.99,45.95,'Telebirr','pending','cancelled','Piassa, Addis Ababa, Ethiopia','+251913456789',NULL,'8:30 PM',NULL,'2026-05-16 19:57:53'),(5,'ORD202605166486',4,9.99,2.99,12.98,'Telebirr','pending','delivered','21 stert apartiment','0918951504','','11:57 PM','2026-05-17 07:53:37','2026-05-16 20:12:18'),(6,'ORD202605176191',4,72.99,2.99,75.98,'Telebirr','pending','delivered','mexiko mame aperkiment room 123','0918951504','','11:51 AM','2026-05-17 08:19:11','2026-05-17 08:06:29'),(7,'ORD202605174719',4,72.99,2.99,75.98,'Telebirr','pending','delivered','mexico addis buileing room 21','0918951504','call me before you knock the door','12:02 PM','2026-05-17 14:38:45','2026-05-17 08:17:40'),(8,'ORD202605179030',4,8.99,2.99,11.98,'Telebirr','pending','delivered','21','0918951504','','12:41 PM','2026-05-17 14:38:49','2026-05-17 08:56:14'),(9,'ORD202605177924',4,1.99,2.99,4.98,'Cash','pending','delivered','Bole Road , 4th Floor, Suite 412, Dembel City Center','0918951504','','08:46 PM','2026-05-21 05:39:02','2026-05-17 17:01:25'),(10,'ORD202605178320',5,20.19,2.99,23.18,'Chapa','pending','delivered','robot','0908838736','','10:13 PM','2026-05-17 18:30:15','2026-05-17 18:28:38'),(11,'ORD202605203045',4,4.99,2.99,7.98,'Telebirr','pending','delivered','Bole Road , 4th Floor, Suite 412, Dembel City Center','0918951504','','08:25 PM','2026-05-20 16:45:12','2026-05-20 16:40:20'),(12,'ORD202605218299',6,13.99,2.99,16.98,'Telebirr','pending','delivered','burayu kata fikru intsa','0908838736','','09:22 AM','2026-05-21 05:39:47','2026-05-21 05:37:26'),(13,'ORD202605211902',7,24.28,2.99,27.27,'Chapa','pending','delivered','bhu','0908838736','','10:06 AM','2026-05-21 06:25:16','2026-05-21 06:21:42'),(14,'ORD202605212280',7,210.00,2.99,212.99,'Telebirr','pending','delivered','bhu','0908838736','','10:14 AM','2026-05-21 06:29:54','2026-05-21 06:29:24');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_order_id` (`order_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `stock` int DEFAULT '10',
  `is_available` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_price` (`price`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Margherita Pizza','Fresh mozzarella, tomato sauce, basil, olive oil',12.99,'Pizza','Margherita Pizza.jpg',20,1,'2026-05-16 19:57:53','2026-05-17 14:24:15'),(2,'Pepperoni Pizza','Pepperoni, mozzarella, tomato sauce, oregano',14.99,'Pizza','Pepperoni Pizza.jpg',14,1,'2026-05-16 19:57:53','2026-05-17 18:28:38'),(3,'Hawaiian Pizza','Ham, pineapple, mozzarella, tomato sauce',13.99,'Pizza','Hawaiian Pizza.jpg',12,1,'2026-05-16 19:57:53','2026-05-17 14:30:12'),(4,'BBQ Chicken Pizza','BBQ sauce, grilled chicken, red onions, cilantro',15.99,'Pizza','BBQ Chicken Pizza.jpg',10,1,'2026-05-16 19:57:53','2026-05-17 14:29:37'),(5,'Vegetarian Pizza','Mushrooms, bell peppers, olives, onions, corn',13.49,'Pizza','Vegetarian Pizza.jpg',18,1,'2026-05-16 19:57:53','2026-05-17 14:29:13'),(6,'Meat Lovers Pizza','Pepperoni, sausage, beef, bacon, ham',16.99,'Pizza','Meat Lovers Pizza.jpg',8,1,'2026-05-16 19:57:53','2026-05-17 14:28:18'),(7,'Four Cheese Pizza','Mozzarella, parmesan, gorgonzola, ricotta',14.49,'Pizza','Four Cheese Pizza.jpg',14,1,'2026-05-16 19:57:53','2026-05-17 14:28:47'),(8,'Supreme Pizza','Pepperoni, sausage, mushrooms, onions, peppers',15.49,'Pizza','Supreme Pizza.jpg',11,1,'2026-05-16 19:57:53','2026-05-17 14:28:01'),(9,'Classic Cheeseburger','Beef patty, cheddar cheese, lettuce, tomato, pickles',9.99,'Burger','Classic Cheeseburger.jpg',25,1,'2026-05-16 19:57:53','2026-05-17 14:27:24'),(10,'Bacon Burger','Beef patty, crispy bacon, cheddar, BBQ sauce',11.99,'Burger','Bacon Burger.jpg',20,1,'2026-05-16 19:57:53','2026-05-17 14:27:04'),(11,'Chicken Burger','Grilled chicken breast, lettuce, mayo, tomato',10.99,'Burger','Chicken Burger.jpg',22,1,'2026-05-16 19:57:53','2026-05-17 14:26:44'),(12,'Double Burger','Double beef patty, double cheese, special sauce',13.99,'Burger','Double Burger.jpg',15,1,'2026-05-16 19:57:53','2026-05-17 14:26:28'),(13,'Veggie Burger','Plant-based patty, avocado, lettuce, vegan mayo',10.49,'Burger','Vegetarian Pizza.jpg',10,1,'2026-05-16 19:57:53','2026-05-17 14:26:12'),(14,'Mushroom Swiss Burger','Beef patty, sauteed mushrooms, Swiss cheese',11.49,'Burger','Mushroom Swiss Burger.jpg',18,1,'2026-05-16 19:57:53','2026-05-17 14:25:57'),(15,'Texas BBQ Burger','Beef patty, onion rings, BBQ sauce, cheddar',12.99,'Burger','Texas BBQ Burger.jpg',10,1,'2026-05-16 19:57:53','2026-05-21 06:21:42'),(16,'Coca Cola','Refreshing soft drink (500ml)',2.99,'Drinks','Coca Cola.jpg',100,1,'2026-05-16 19:57:53','2026-05-17 14:25:01'),(17,'Pepsi','Crisp and refreshing (500ml)',2.99,'Drinks','Pepsi.jpg',100,1,'2026-05-16 19:57:53','2026-05-17 14:23:18'),(18,'Sprite','Lemon-lime flavored soda (500ml)',2.99,'Drinks','Sprite.jpg',100,1,'2026-05-16 19:57:53','2026-05-17 14:22:59'),(19,'Fresh Orange Juice','Freshly squeezed orange juice',4.99,'Drinks','Fresh Orange Juice.jpg',50,1,'2026-05-16 19:57:53','2026-05-17 14:22:43'),(20,'Lemonade','Homemade lemonade with mint',3.99,'Drinks','Lemonade.jpg',60,1,'2026-05-16 19:57:53','2026-05-17 14:22:25'),(21,'Iced Tea','Refreshing iced tea with lemon',3.49,'Drinks','Iced Tea.jpg',75,1,'2026-05-16 19:57:53','2026-05-17 14:22:06'),(22,'Milkshake','Vanilla/Chocolate/Strawberry milkshake',5.99,'Drinks','Milkshake.jpg',40,1,'2026-05-16 19:57:53','2026-05-17 14:21:49'),(23,'Mineral Water','Pure spring water (500ml)',1.99,'Drinks','Mineral Water.jpg',199,1,'2026-05-16 19:57:53','2026-05-17 17:01:25'),(24,'Caesar Salad','Romaine lettuce, parmesan, croutons, caesar dressing',7.99,'Salads','Caesar Salad.jpg',30,1,'2026-05-16 19:57:53','2026-05-17 14:20:47'),(25,'Greek Salad','Cucumber, tomatoes, feta cheese, olives, oregano',8.99,'Salads','Garden Salad.jpg',25,1,'2026-05-16 19:57:53','2026-05-17 14:20:20'),(26,'Garden Salad','Mixed greens, tomatoes, cucumbers, carrots',6.99,'Salads','Garden Salad.jpg',35,1,'2026-05-16 19:57:53','2026-05-17 14:19:58'),(27,'Chicken Salad','Grilled chicken, mixed greens, avocado, corn',10.99,'Salads','Chicken Salad.jpg',20,1,'2026-05-16 19:57:53','2026-05-17 14:19:43'),(28,'Tuna Salad','Fresh tuna, mixed greens, eggs, tomatoes',11.99,'Salads','Tuna Salad.jpg',15,1,'2026-05-16 19:57:53','2026-05-17 14:18:26'),(29,'Chocolate Cake','Rich chocolate cake with ganache',5.99,'Desserts','Chocolate Cake.jpg',40,1,'2026-05-16 19:57:53','2026-05-17 14:18:11'),(30,'Cheesecake','New York style cheesecake with berry sauce',6.99,'Desserts','Cheesecake.jpg',35,1,'2026-05-16 19:57:53','2026-05-17 14:17:51'),(31,'Ice Cream Sundae','Vanilla ice cream with chocolate sauce',4.99,'Desserts','Ice Cream Sundae.jpg',44,1,'2026-05-16 19:57:53','2026-05-20 16:40:20'),(32,'Brownie','Warm chocolate brownie with nuts',4.49,'Desserts','Brownie.jpg',50,1,'2026-05-16 19:57:53','2026-05-17 14:17:04'),(33,'Fruit Tart','Fresh seasonal fruits on custard',5.49,'Desserts','Fruit Tart.jpg',25,1,'2026-05-16 19:57:53','2026-05-17 14:16:35'),(34,'Spaghetti Bolognese','Classic meat sauce with spaghetti',11.99,'Pasta','Spaghetti Bolognese.jpg',20,1,'2026-05-16 19:57:53','2026-05-17 14:16:21'),(35,'Fettuccine Alfredo','Creamy parmesan sauce with fettuccine',12.99,'Pasta','Fettuccine Alfredo.jpg',18,1,'2026-05-16 19:57:53','2026-05-17 14:16:04'),(36,'Penne Arrabbiata','Spicy tomato sauce with penne pasta',10.99,'Pasta','Penne Arrabbiata.jpg',22,1,'2026-05-16 19:57:53','2026-05-17 14:15:30'),(37,'Lasagna','Layered pasta with meat sauce and cheese',13.99,'Pasta','Lasagna.jpg',15,1,'2026-05-16 19:57:53','2026-05-17 14:15:03'),(38,'Pesto Pasta','Fresh basil pesto with pine nuts',11.49,'Pasta','Pesto Pasta.jpg',16,1,'2026-05-16 19:57:53','2026-05-17 14:14:43'),(39,'Grilled Salmon','Fresh salmon with lemon butter sauce',18.99,'Seafood','Grilled Salmon.jpg',10,1,'2026-05-16 19:57:53','2026-05-17 14:14:14'),(40,'Shrimp Scampi','Shrimp in garlic butter sauce with pasta',16.99,'Seafood','Shrimp Scampi.jpg',12,1,'2026-05-16 19:57:53','2026-05-17 14:13:55'),(41,'Fish & Chips','Crispy battered fish with fries',14.99,'Seafood','Fish & Chips.jpg',18,1,'2026-05-16 19:57:53','2026-05-17 14:13:40'),(42,'Calamari','Crispy fried calamari with marinara',12.99,'Seafood','Calamari.jpg',20,1,'2026-05-16 19:57:53','2026-05-17 14:13:26'),(43,'Kung Pao Chicken','Spicy stir-fry with peanuts and vegetables',13.99,'Chinese','Kung Pao Chicken.jpg',15,1,'2026-05-16 19:57:53','2026-05-17 14:13:00'),(44,'Fried Rice','Egg fried rice with vegetables',8.99,'Chinese','Fried Rice.jpg',30,1,'2026-05-16 19:57:53','2026-05-17 14:12:42'),(45,'Spring Rolls','Crispy vegetable spring rolls (4 pcs)',5.99,'Chinese','Spring Rolls.jpg',39,1,'2026-05-16 19:57:53','2026-05-21 05:37:26'),(46,'Sweet & Sour Chicken','Crispy chicken with sweet & sour sauce',12.99,'Chinese','1.jpg',18,1,'2026-05-16 19:57:53','2026-05-17 14:34:37'),(47,'Chow Mein','Stir-fried noodles with vegetables',9.99,'Chinese','Chow Mein.jpg',24,1,'2026-05-16 19:57:53','2026-05-17 14:11:23'),(48,'Dim Sum','Assorted steamed dumplings (6 pcs)',8.99,'Chinese','Dim Sum.jpg',18,1,'2026-05-16 19:57:53','2026-05-21 06:21:42'),(49,'Kitfo',' It consists of minced raw beef, marinated in mitmita and niter kibbeh',12.00,'Ethiopian','Screenshot 2026-05-17 093422.png',9,1,'2026-05-17 06:36:13','2026-05-17 17:55:59'),(50,'Gomen','It consists of collard greens cooked with garlic, onion, and Ethiopian spices in seasoned butter or oil.',2.00,'Ethiopian','1779040740_Gomen_jpg',10,1,'2026-05-17 17:59:00','2026-05-17 18:16:14'),(51,'Buticha','A flavorful Ethiopian chickpea dish prepared with mashed legumes, fresh herbs, and tangy seasonings.”',2.30,'Ethiopian','1779041363_Buticha_jpg',9,1,'2026-05-17 18:09:23','2026-05-21 06:21:42'),(52,'Fossolia','A light vegetable dish made with sautéed green beans and carrots seasoned with Ethiopian spices.',4.50,'Ethiopian','1779041635_Fossolia_jpg',10,1,'2026-05-17 18:13:55','2026-05-17 18:16:21'),(53,'Beyayinat be siga','A colorful combination platter of assorted Ethiopian vegetarian dishes served on injera.',6.00,'Ethiopian','1779041711_beyaynatina_sig_jpg',10,1,'2026-05-17 18:15:11','2026-05-17 18:16:29'),(54,'Kik Alicha','A mild split pea stew simmered with turmeric, garlic, and traditional Ethiopian seasonings.',3.00,'Ethiopian','1779041757_Kik_Alicha_jpg',10,1,'2026-05-17 18:15:57','2026-05-17 18:16:37'),(55,'Misir Wat','A spicy lentil stew prepared with red lentils, berbere spice, and slow-cooked onions',4.00,'Ethiopian','1779041869_Misir_wot_jpg',10,1,'2026-05-17 18:17:49','2026-05-17 18:17:49'),(57,'Qurx','A hearty beef stew cooked in a spicy berbere sauce with onions and Ethiopian butter.',8.00,'Ethiopian','1779042169_qurx_jpg',9,1,'2026-05-17 18:22:49','2026-05-21 05:37:26'),(58,'steck','defghjkl',21.00,'Burger','1779344819_Mushroom_Swiss_Burger_jpg',9,1,'2026-05-21 06:26:59','2026-05-21 06:29:24'),(59,'Shawarma',' is a popular street food in the Middle East that originated in the Levant during the Ottoman Empire.',18.00,'Arabic food','1779346256_Beef_Shawarma_____mycomfortcook_com_jpg',10,1,'2026-05-21 06:50:56','2026-05-21 07:29:15'),(60,'Kitfo','SADFGHJK',9.00,'Desserts','1779347917_Cheesecake_jpg',10,1,'2026-05-21 07:18:37','2026-05-21 07:28:52');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_product_id` (`product_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_chk_1` CHECK (((`rating` >= 1) and (`rating` <= 5)))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,1,5,'Best pizza in town! Highly recommended.','2026-05-16 19:57:53'),(2,1,2,4,'Great taste, delivery was fast.','2026-05-16 19:57:53'),(3,2,3,5,'Love the Hawaiian pizza!','2026-05-16 19:57:53'),(4,2,17,4,'Always fresh and cold.','2026-05-16 19:57:53'),(5,3,9,5,'Juicy burger, amazing taste!','2026-05-16 19:57:53');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `top_products`
--

DROP TABLE IF EXISTS `top_products`;
/*!50001 DROP VIEW IF EXISTS `top_products`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `top_products` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `category`,
 1 AS `total_sold`,
 1 AS `times_ordered`,
 1 AS `price`,
 1 AS `image`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John Doe','john@example.com','+251911234567','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Bole Road, Addis Ababa, Ethiopia','2026-05-16 19:57:53','2026-05-16 19:57:53'),(2,'Jane Smith','jane@example.com','+251912345678','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Kazanchis, Addis Ababa, Ethiopia','2026-05-16 19:57:53','2026-05-16 19:57:53'),(3,'Michael Brown','michael@example.com','+251913456789','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Piassa, Addis Ababa, Ethiopia','2026-05-16 19:57:53','2026-05-16 19:57:53'),(4,'ameya','ameyamodern4@gmail.com','0918951504','$2y$10$vBQ0hyeZizhf2JJY7myEKeS3W9JUdLv51rXQhY6.FQoYIW.NANtdK','Bole Road , 4th Floor, Suite 412, Dembel City Center','2026-05-16 20:10:48','2026-05-17 09:25:54'),(5,'Abduseek','abduseek12@gmail.com','0908838736','$2y$10$WmdXEuvfwYH3rZpCmNgW9e5O0JzXmdD0m5RP7TetQNsZaTf9dKtFW',NULL,'2026-05-17 18:27:07','2026-05-17 18:27:07'),(6,'dhabesa','dhabesa@gmail.com','0908838736','$2y$10$57gRiUJ2NRqklt5.s2nVrO4g2eoykN426LgmXxOyrfbhE/pgsxM1m','burayu kata fikru intsa','2026-05-21 05:29:55','2026-05-21 05:35:36'),(7,'mati','mati@gmail.com','0908838736','$2y$10$ENSoe7gGYmKZ.kuxTkYSPOiybQhipUIKs60cSW/EdKm/GKvAf15Kq','bhu','2026-05-21 06:19:05','2026-05-21 06:21:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `daily_sales`
--

/*!50001 DROP VIEW IF EXISTS `daily_sales`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `daily_sales` AS select cast(`orders`.`created_at` as date) AS `sale_date`,count(0) AS `total_orders`,sum(`orders`.`grand_total`) AS `total_revenue`,avg(`orders`.`grand_total`) AS `average_order_value`,sum((case when (`orders`.`payment_method` = 'Telebirr') then `orders`.`grand_total` else 0 end)) AS `telebirr_sales`,sum((case when (`orders`.`payment_method` = 'Chapa') then `orders`.`grand_total` else 0 end)) AS `chapa_sales`,sum((case when (`orders`.`payment_method` = 'Cash') then `orders`.`grand_total` else 0 end)) AS `cash_sales` from `orders` where (`orders`.`order_status` = 'delivered') group by cast(`orders`.`created_at` as date) order by `sale_date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `order_summary`
--

/*!50001 DROP VIEW IF EXISTS `order_summary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `order_summary` AS select `o`.`id` AS `id`,`o`.`order_number` AS `order_number`,`u`.`fullname` AS `customer_name`,`u`.`email` AS `email`,`u`.`phone` AS `phone`,`o`.`total_amount` AS `total_amount`,`o`.`delivery_fee` AS `delivery_fee`,`o`.`grand_total` AS `grand_total`,`o`.`payment_method` AS `payment_method`,`o`.`order_status` AS `order_status`,`o`.`created_at` AS `created_at`,count(`oi`.`id`) AS `items_count` from ((`orders` `o` join `users` `u` on((`o`.`user_id` = `u`.`id`))) left join `order_items` `oi` on((`o`.`id` = `oi`.`order_id`))) group by `o`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `top_products`
--

/*!50001 DROP VIEW IF EXISTS `top_products`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `top_products` AS select `p`.`id` AS `id`,`p`.`name` AS `name`,`p`.`category` AS `category`,sum(`oi`.`quantity`) AS `total_sold`,count(distinct `oi`.`order_id`) AS `times_ordered`,`p`.`price` AS `price`,`p`.`image` AS `image` from (`products` `p` join `order_items` `oi` on((`p`.`id` = `oi`.`product_id`))) group by `p`.`id` order by `total_sold` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-21 19:44:36
