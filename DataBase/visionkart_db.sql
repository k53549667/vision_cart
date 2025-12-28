-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 08:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visionkart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('admin','manager') DEFAULT 'admin',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `role`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$WcqgFD2dw9WYklMAZb.SV.uuTfVPX9oAe/UtHPquMu/rR5p6e97gW', 'admin@visionkart.com', 'admin', NULL, '2025-12-25 15:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `products_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `products_count`, `created_at`) VALUES
(1, 'Round', 'fa-circle', 2, '2025-12-25 15:20:13'),
(2, 'Cat-Eye', 'fa-cat', 2, '2025-12-25 15:20:13'),
(3, 'Clubmaster', 'fa-glasses', 1, '2025-12-25 15:20:13'),
(4, 'Transparent', 'fa-eye', 1, '2025-12-25 15:20:13'),
(5, 'Aviator', 'fa-plane', 0, '2025-12-25 15:40:18'),
(6, 'Wayfarer', 'fa-glasses', 0, '2025-12-25 15:40:18'),
(7, 'Oval', 'fa-circle', 0, '2025-12-25 15:40:18'),
(8, 'Square', 'fa-square', 0, '2025-12-25 15:40:18'),
(9, 'Rectangle', 'fa-square', 0, '2025-12-25 15:40:18');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `orders_count` int(11) DEFAULT 0,
  `total_spent` decimal(10,2) DEFAULT 0.00,
  `joined_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `orders_count`, `total_spent`, `joined_date`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john@example.com', '+91 9876543210', 5, 9500.00, '2024-01-15', '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(2, 'Jane Smith', 'jane@example.com', '+91 9876543211', 3, 5700.00, '2024-02-20', '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(3, 'Mike Johnson', 'mike@example.com', '+91 9876543212', 7, 13300.00, '2024-01-10', '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(4, 'Sarah Williams', 'sarah@example.com', '+91 9876543213', 2, 3800.00, '2024-03-05', '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(5, 'David Brown', 'david@example.com', '+91 9876543214', 4, 7600.00, '2024-02-28', '2025-12-25 15:20:19', '2025-12-25 15:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `products` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` date DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `customer_name`, `products`, `total_amount`, `order_date`, `status`, `payment_method`, `shipping_address`, `created_at`, `updated_at`) VALUES
('ORD-001', NULL, 'John Doe', 'Round Eyeglasses', 1900.00, '2024-12-05', 'pending', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-002', NULL, 'Jane Smith', 'Cat-Eye Frames', 1900.00, '2024-12-04', 'completed', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-003', NULL, 'Mike Johnson', 'Clubmaster', 2000.00, '2024-12-03', 'processing', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-004', NULL, 'Sarah Williams', 'Transparent Round', 750.00, '2024-12-02', 'completed', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-005', NULL, 'David Brown', 'Round + Cat-Eye', 3800.00, '2024-12-01', 'cancelled', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL,
  `gst` decimal(5,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) DEFAULT NULL,
  `hsn` varchar(20) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `gst` decimal(5,2) DEFAULT 12.00,
  `stock` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `image` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `subcategory`, `hsn`, `brand`, `price`, `gst`, `stock`, `status`, `image`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Vincent Chase Round Classic', 'Eyeglasses', 'Round', '9004', 'Vincent Chase', 1900.00, 12.00, 45, 'active', 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100&h=100&fit=crop', NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(2, 'Cat-Eye Transparent', 'Eyeglasses', 'Cat-Eye', '9004', 'Vincent Chase', 1900.00, 12.00, 32, 'active', 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=100&h=100&fit=crop', NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(3, 'Clubmaster Classic', 'Sunglasses', 'Clubmaster', '9004', 'John Jacobs', 2000.00, 12.00, 28, 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=100&h=100&fit=crop', NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(4, 'OJOS Clear Round', 'Eyeglasses', 'Transparent', '9004', 'OJOS', 750.00, 12.00, 56, 'active', 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=100&h=100&fit=crop', NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(5, 'VisionKart Air Round', 'Eyeglasses', 'Round', '9004', 'VisionKart Air', 1900.00, 12.00, 38, 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=100&h=100&fit=crop', NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(6, 'v', 'eyeglasses', 'cat-eye', '1234', 'John Jacobs', 1222.00, 12.00, 45, 'active', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAAAgAAAAcAHMAUgBHAEJYWVogAAAAAAAAb6IAADj1AAADkFhZWiAAAAAAAABimQAAt4UAABjaWFlaI', 'shgwhgjg', '2025-12-25 16:15:13', '2025-12-25 16:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` enum('received','pending','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
