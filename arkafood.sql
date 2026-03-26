-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2026 at 03:24 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arkafood`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(2, 'rinaldi', '$2y$10$UdXCF43tlzfsd15sSsaqcuiR9hwfeWXRdASR0D.lHp/2L4cLU.kr2', '2025-11-20 12:56:17'),
(4, 'admin3', '$2y$10$bfB8HdQjQccjthx8/bm5TO8qJXAwpfXjUSqS.eWVcAA2m.oapLcQu', '2025-11-27 04:03:22'),
(5, 'kaka', '$2y$10$ezS/aGHShP6BCufuEQE6T.V3ISG0ryMH/13CyNa5Nkahl9638qfJe', '2026-01-15 11:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `customer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Lainnya'),
(1, 'Makanan Ringan'),
(2, 'Minuman'),
(3, 'Paket Hemat');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'kaka', '2303010122@unper.ac.id', '$2y$10$s3mvpv1HXObYKQFmsHrEWuVslOCUBKSlyBR4VoOAg/dPytXj/oogO', '085150838337', 'tasikmalaya', '2026-01-14 07:21:45'),
(999, 'Dummy Buyer', 'dummy@arkafood.com', 'password123', '08123456789', NULL, '2026-01-15 10:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE `directors` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ord` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`id`, `name`, `title`, `image`, `ord`) VALUES
(1, 'Eka Mulyana', 'Direktur Arka Food', 'uploads/directors/d_691f1cf100d3a6.95421111.png', 0),
(2, 'Rinaldi Satia', 'Chief Executive Ofiicer', 'uploads/directors/d_691f1d25800de2.14147894.JPG', 2),
(3, 'Azriel Aulia R', 'Chief Finance ', 'uploads/directors/d_691f1d5a2c9043.64590541.jpg', 3),
(4, 'Rivan', 'Chief of Production', 'uploads/directors/d_6922c51613dc33.55503022.jpg', 4),
(5, 'Wildan Ibnu S', 'Chief of Creative', 'uploads/directors/d_6922c543957dd2.84041610.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_general_ci,
  `content` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `image`, `published_at`, `created_at`) VALUES
(1, 'Peluncuran Produk Baru', 'peluncuran-produk-baru', 'Arka Food meluncurkan lini produk terbaru untuk memenuhi kebutuhan pasar.', 'Detail peluncuran produk baru...', 'assets/images/news/newsproduk.png', '2025-11-09 09:00:00', '2025-11-20 11:38:54'),
(2, 'Kolaborasi Spesial', 'kolaborasi-spesial', 'Kolaborasi istimewa dengan chef ternama dalam pengembangan produk.', 'Detail kolaborasi...', 'assets/images/news/newskk.png', '2025-11-05 10:00:00', '2025-11-20 11:38:54');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `payment_method_id` int DEFAULT NULL,
  `voucher_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `quantity` int DEFAULT '1',
  `total_price` decimal(10,2) DEFAULT '0.00',
  `shipping_cost` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','confirmed','shipped','cancelled','completed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `courier` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tracking_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `product_id`, `customer_name`, `phone`, `address`, `payment_method_id`, `voucher_code`, `discount_amount`, `quantity`, `total_price`, `shipping_cost`, `status`, `courier`, `tracking_number`, `created_at`) VALUES
(1, 999, NULL, 'Budi Santoso', NULL, NULL, NULL, NULL, '0.00', 1, '150000.00', '0.00', 'completed', NULL, NULL, '2026-01-15 11:00:35'),
(2, 999, NULL, 'Siti Aminah', NULL, NULL, NULL, NULL, '0.00', 1, '75000.00', '0.00', 'completed', NULL, NULL, '2026-01-15 11:00:35'),
(3, 999, NULL, 'Rudi Hartono', NULL, NULL, NULL, NULL, '0.00', 1, '200000.00', '0.00', 'completed', NULL, NULL, '2026-01-15 11:00:35'),
(4, 999, NULL, 'Dewi Persik', NULL, NULL, NULL, NULL, '0.00', 1, '50000.00', '0.00', 'completed', NULL, NULL, '2026-01-15 11:00:35'),
(5, 999, NULL, 'Andi Lau', NULL, NULL, NULL, NULL, '0.00', 1, '120000.00', '0.00', 'completed', NULL, NULL, '2026-01-15 11:00:35'),
(6, 1, NULL, 'kaka', '085150838337', 'tasikmalaya (Catatan: )', 4, 'PENGGUNABARU', '17500.00', 1, '17500.00', '0.00', 'confirmed', NULL, NULL, '2026-01-15 11:03:29'),
(7, 1, NULL, 'kaka', '085150838337', 'tasikmalaya\r\n (Catatan: )', 2, NULL, '0.00', 1, '21000.00', '0.00', 'shipped', 'J&T Express', 'JP1321663524', '2026-01-15 11:20:52'),
(8, 1, NULL, 'kaka', '085150838337', 'garut\r\n (Catatan: )', 1, NULL, '0.00', 1, '14000.00', '0.00', 'cancelled', 'J&T Express', 'JP1216628316', '2026-01-15 11:24:51'),
(9, 1, NULL, 'kaka', '085150838337', 'jakarta\r\n ()', 3, 'TEST', '100000.00', 1, '5000.00', '0.00', 'shipped', 'J&T Express', 'JP3324910371', '2026-01-15 12:01:20'),
(10, 1, NULL, 'kaka', '085150838337', 'tasikmalaya ()', 4, 'DISKON10', '2100.00', 1, '18900.00', '0.00', 'cancelled', 'J&T Express', 'JP3723752880', '2026-01-15 12:06:58'),
(11, 1, NULL, 'kaka', '085150838337', 'tasikmalaya', 1, NULL, '0.00', 1, '7000.00', '0.00', 'confirmed', 'J&T Express', 'JP5729512474', '2026-01-17 15:01:52'),
(12, 1, NULL, 'kaka', '085150838337', 'tasikmalaya', 2, NULL, '0.00', 1, '71500.00', '0.00', 'confirmed', 'J&T Express', 'JP4775220165', '2026-01-17 15:30:26'),
(13, 1, NULL, 'kaka', '085150838337', 'tasikmalaya', 1, NULL, '0.00', 1, '35000.00', '0.00', 'pending', 'J&T Express', 'JP5072977352', '2026-01-19 05:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 1, 50, '15000.00', '750000.00'),
(2, 2, 2, 40, '15000.00', '600000.00'),
(3, 3, 3, 35, '15000.00', '525000.00'),
(4, 4, 1, 10, '15000.00', '150000.00'),
(5, 5, 4, 5, '12000.00', '60000.00'),
(6, 6, 1, 2, '7000.00', '14000.00'),
(7, 6, 2, 2, '7000.00', '14000.00'),
(8, 6, 3, 1, '7000.00', '7000.00'),
(9, 7, 1, 3, '7000.00', '21000.00'),
(10, 8, 1, 2, '7000.00', '14000.00'),
(11, 9, 2, 15, '7000.00', '105000.00'),
(12, 10, 1, 1, '7000.00', '7000.00'),
(13, 10, 2, 2, '7000.00', '14000.00'),
(14, 11, 1, 1, '7000.00', '7000.00'),
(15, 12, 1, 11, '6500.00', '71500.00'),
(16, 13, 1, 1, '7000.00', '7000.00'),
(17, 13, 2, 2, '7000.00', '14000.00'),
(18, 13, 3, 2, '7000.00', '14000.00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(50) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `number`, `description`, `is_active`) VALUES
(1, 'Transfer BCA', '1234567890', 'a.n Arka Food', 1),
(2, 'DANA', '082116726900', 'a.n Admin Arka', 1),
(3, 'COD (Bayar di Tempat)', '-', 'Bayar tunai saat kurir sampai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Makanan',
  `size` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '100',
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_visible` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `size`, `slug`, `description`, `price`, `stock`, `image`, `created_at`, `updated_at`, `is_visible`) VALUES
(1, 'Jamur Krezi Original', 'Makanan Ringan', '250gr', 'jamur-tiram-original', 'Jamur tiram crispy dengan bumbu original yang renyah dan gurih.', '7000.00', 29, 'assets/images/jkTiramOriginal.png', '2025-11-20 11:38:54', '2026-01-19 05:13:38', 1),
(2, 'Jamur Krispy Tiram Pedas', 'Makanan Ringan', 'Pedas Level 3', 'jamur-tiram-pedas', 'Jamur tiram crispy dengan bumbu pedas yang menggugah selera.', '7000.00', 24, 'assets/images/jkTiramPedas.png', '2025-11-20 11:38:54', '2026-01-19 05:13:38', 1),
(3, 'Kulit Krezi Pedas', 'Makanan Ringan', '500gr', 'kulit-krezi-pedas', 'Kulit Krezi Pedas dengan kualitas premium dan rasa pedas yang menggugah selera.', '7000.00', 27, 'assets/images/kkOriginal.png', '2025-11-20 11:38:54', '2026-01-19 05:13:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('about_content', 'Arka Food adalah produsen makanan ringan berkualitas yang berbasis di Tasikmalaya. Kami berkomitmen untuk menghadirkan camilan lezat, higienis, dan terjangkau bagi seluruh masyarakat Indonesia. Produk unggulan kami meliputi Jamur Crispy dan Kulit Ayam yan'),
('about_image', 'assets/images/logo3.png'),
('about_title', 'Tentang Arka Food'),
('reseller_disc_1', '500'),
('reseller_disc_2', '1000'),
('reseller_qty_1', '10'),
('reseller_qty_2', '20');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_finance_by_category`
-- (See below for the actual view)
--
CREATE TABLE `view_finance_by_category` (
`category` varchar(50)
,`total_items_sold` bigint
,`total_revenue` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
  `value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT '0.00',
  `max_usage` int DEFAULT '0',
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `type`, `value`, `min_purchase`, `max_usage`, `expiry_date`, `is_active`) VALUES
(1, 'ARKAHEMAT', 'fixed', '5000.00', '20000.00', 0, '2030-12-31', 1),
(2, 'DISKON10', 'percent', '10.00', '0.00', 0, '2030-12-31', 1),
(5, 'PENGGUNABARU', 'percent', '50.00', '0.00', 0, '2030-12-31', 1),
(7, 'TEST', 'fixed', '100000.00', '0.00', 0, '2026-01-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_usage`
--

CREATE TABLE `voucher_usage` (
  `id` int NOT NULL,
  `voucher_code` varchar(50) NOT NULL,
  `customer_id` int NOT NULL,
  `used_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `voucher_usage`
--

INSERT INTO `voucher_usage` (`id`, `voucher_code`, `customer_id`, `used_at`) VALUES
(1, 'TEST', 1, '2026-01-15 19:01:20'),
(2, 'DISKON10', 1, '2026-01-15 19:06:58');

-- --------------------------------------------------------

--
-- Structure for view `view_finance_by_category`
--
DROP TABLE IF EXISTS `view_finance_by_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_finance_by_category`  AS SELECT `p`.`category` AS `category`, count(`oi`.`id`) AS `total_items_sold`, sum(`oi`.`subtotal`) AS `total_revenue` FROM ((`order_items` `oi` join `products` `p` on((`oi`.`product_id` = `p`.`id`))) join `orders` `o` on((`oi`.`order_id` = `o`.`id`))) WHERE (`o`.`status` = 'completed') GROUP BY `p`.`category``category`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`customer_id`,`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_code` (`voucher_code`,`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `voucher_usage`
--
ALTER TABLE `voucher_usage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
