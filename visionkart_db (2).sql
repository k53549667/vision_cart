-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2025 at 01:20 PM
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
(1, 'admin', '$2y$10$WcqgFD2dw9WYklMAZb.SV.uuTfVPX9oAe/UtHPquMu/rR5p6e97gW', 'admin@visionkart.com', 'admin', '2025-12-29 11:27:40', '2025-12-25 15:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Cat-Eye', 'fa-cat', 1, '2025-12-25 15:20:13'),
(3, 'Clubmaster', 'fa-glasses', 1, '2025-12-25 15:20:13'),
(4, 'Transparent', 'fa-eye', 1, '2025-12-25 15:20:13'),
(5, 'Aviator', 'fa-plane', 0, '2025-12-25 15:40:18'),
(6, 'Wayfarer', 'fa-glasses', 0, '2025-12-25 15:40:18'),
(7, 'Oval', 'fa-circle', 0, '2025-12-25 15:40:18'),
(8, 'Square', 'fa-square', 1, '2025-12-25 15:40:18'),
(9, 'Rectangle', 'fa-square', 0, '2025-12-25 15:40:18'),
(10, 'Hustler', 'fa-tag', 0, '2025-12-29 08:21:30'),
(11, 'vini', 'fa-tag', 0, '2025-12-29 08:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
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

INSERT INTO `orders` (`id`, `user_id`, `customer_id`, `customer_name`, `products`, `total_amount`, `order_date`, `status`, `payment_method`, `shipping_address`, `created_at`, `updated_at`) VALUES
('ORD-001', NULL, NULL, 'John Doe', 'Round Eyeglasses', 1900.00, '2024-12-05', 'pending', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-002', NULL, NULL, 'Jane Smith', 'Cat-Eye Frames', 1900.00, '2024-12-04', 'completed', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-003', NULL, NULL, 'Mike Johnson', 'Clubmaster', 2000.00, '2024-12-03', 'processing', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-004', NULL, NULL, 'Sarah Williams', 'Transparent Round', 750.00, '2024-12-02', 'completed', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-20251229-4812', NULL, NULL, 'sadnjk', 'Vintage Cat-Eye Pink', 2241.00, '2025-12-29', 'completed', '', 'sadnjk\n9876543210\nsdkjfl\nkddsnkl\ndnndslkn, Maharashtra - 444605', '2025-12-29 11:12:33', '2025-12-29 11:17:26'),
('ORD-20251229-6285', NULL, NULL, 'Tejas gawande', 'John Jacobs Round Wire', 2949.00, '2025-12-29', 'completed', '', 'Tejas gawande\n9876543210\n02\namravati\namravati, Maharashtra - 444605', '2025-12-29 08:19:16', '2025-12-29 10:45:06'),
('ORD-20251229-9023', NULL, NULL, 'vaishu', 'Air by Hustler', 1416.00, '2025-12-29', 'completed', '', 'vaishu\n9876543210\n53\n23\n112, Maharashtra - 12323', '2025-12-29 10:56:55', '2025-12-29 10:57:18');

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

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `gst`, `total`) VALUES
(1, 'ORD-20251229-6285', 9, 'John Jacobs Round Wire', 1, 2499.00, 450.00, 2949.00),
(2, 'ORD-20251229-9023', 39, 'Air by Hustler', 1, 1200.00, 216.00, 1416.00),
(3, 'ORD-20251229-4812', 13, 'Vintage Cat-Eye Pink', 1, 1899.00, 342.00, 2241.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) DEFAULT NULL,
  `frametype` varchar(50) DEFAULT NULL,
  `hsn` varchar(20) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `gst` decimal(5,2) DEFAULT 12.00,
  `stock` int(11) DEFAULT 0,
  `color` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `image` varchar(500) DEFAULT NULL,
  `video` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `subcategory`, `frametype`, `hsn`, `brand`, `price`, `original_price`, `gst`, `stock`, `color`, `status`, `image`, `video`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Vincent Chase Round Classic', 'Eyeglasses', 'Round', NULL, '9004', 'Vincent Chase', 1900.00, NULL, 12.00, 45, NULL, 'active', 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100&h=100&fit=crop', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(2, 'Cat-Eye Transparent', 'Eyeglasses', 'Cat-Eye', NULL, '9004', 'Vincent Chase', 1900.00, NULL, 12.00, 32, NULL, 'active', 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=100&h=100&fit=crop', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(3, 'Clubmaster Classic', 'Sunglasses', 'Clubmaster', NULL, '9004', 'John Jacobs', 2000.00, NULL, 12.00, 28, NULL, 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=100&h=100&fit=crop', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(4, 'OJOS Clear Round', 'Eyeglasses', 'Transparent', NULL, '9004', 'OJOS', 750.00, NULL, 12.00, 56, NULL, 'active', 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=100&h=100&fit=crop', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(5, 'VisionKart Air Round', 'Eyeglasses', 'Round', NULL, '9004', 'VisionKart Air', 1900.00, NULL, 12.00, 38, NULL, 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=100&h=100&fit=crop', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
(8, 'Hustler', 'eyeglasses', 'square', 'full-rim', 'HSN5689', 'VisionKart Air', 1599.00, 2199.00, 5.00, 500, 'Militry Green', 'active', 'https://m.media-amazon.com/images/I/41W1LjqHaVL._SX679_.jpg', '', 'Best in class Hustler Series with , full frame Frame.', '2025-12-27 10:36:50', '2025-12-27 10:40:25'),
(9, 'John Jacobs Round Wire', 'Eyeglasses', 'Round', 'full-rim', NULL, 'John Jacobs', 2499.00, 3299.00, 12.00, 50, 'Silver', 'active', 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop', NULL, 'Classic wire round frames inspired by vintage styles', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(10, 'VisionKart Air Round Lite', 'Eyeglasses', 'Round', 'rimless', NULL, 'VisionKart Air', 1599.00, 1999.00, 12.00, 50, 'Gold', 'active', 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop', NULL, 'Ultra-lightweight round frames for all-day comfort', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(11, 'Retro Round Black', 'Eyeglasses', 'Round', 'full-rim', NULL, 'Vincent Chase', 1799.00, 2199.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=400&h=400&fit=crop', NULL, 'Bold black round frames with a retro vibe', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(12, 'Glamour Cat-Eye', 'Eyeglasses', 'Cat-Eye', 'full-rim', NULL, 'John Jacobs', 2199.00, 2799.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop', NULL, 'Elegant cat-eye frames for a sophisticated look', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(13, 'Vintage Cat-Eye Pink', 'Eyeglasses', 'Cat-Eye', 'full-rim', NULL, 'Vincent Chase', 1899.00, 2399.00, 12.00, 50, 'Brown', 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop', NULL, 'Feminine cat-eye with tortoise pattern', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(14, 'Bold Cat-Eye', 'Eyeglasses', 'Cat-Eye', 'full-rim', NULL, 'VisionKart Air', 1699.00, 2099.00, 12.00, 50, 'Blue', 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop', NULL, 'Statement cat-eye frames in vibrant blue', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(15, 'Classic Cat-Eye Transparent', 'Eyeglasses', 'Cat-Eye', 'full-rim', NULL, 'John Jacobs', 2099.00, 2599.00, 12.00, 50, 'Transparent', 'active', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop', NULL, 'Modern transparent cat-eye frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(16, 'Classic Clubmaster', 'Eyeglasses', 'Clubmaster', 'half-rim', NULL, 'Vincent Chase', 2299.00, 2899.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', NULL, 'Iconic clubmaster style with gold accents', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(17, 'Clubmaster Tortoise', 'Eyeglasses', 'Clubmaster', 'half-rim', NULL, 'John Jacobs', 2499.00, 3099.00, 12.00, 50, 'Brown', 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', NULL, 'Premium tortoise clubmaster frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(18, 'Modern Clubmaster', 'Sunglasses', 'Clubmaster', 'half-rim', NULL, 'VisionKart Air', 1999.00, 2499.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', NULL, 'Contemporary clubmaster sunglasses', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(19, 'Clubmaster Gold', 'Eyeglasses', 'Clubmaster', 'half-rim', NULL, 'Vincent Chase', 2699.00, 3299.00, 12.00, 50, 'Gold', 'active', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop', NULL, 'Luxury gold-accented clubmaster', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(20, 'Crystal Clear Round', 'Eyeglasses', 'Transparent', 'full-rim', NULL, 'VisionKart Air', 999.00, 1499.00, 12.00, 50, 'Transparent', 'active', 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop', NULL, 'Minimalist transparent round frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(21, 'Clear Square Modern', 'Eyeglasses', 'Transparent', 'full-rim', NULL, 'John Jacobs', 1499.00, 1999.00, 12.00, 50, 'Transparent', 'active', 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop', NULL, 'Contemporary clear square frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(22, 'Transparent Oval', 'Eyeglasses', 'Transparent', 'full-rim', NULL, 'Vincent Chase', 1299.00, 1799.00, 12.00, 50, 'Transparent', 'active', 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop', NULL, 'Elegant transparent oval frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(23, 'Clear Wayfarer', 'Eyeglasses', 'Transparent', 'full-rim', NULL, 'VisionKart Air', 1199.00, 1599.00, 12.00, 50, 'Transparent', 'active', 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=400&h=400&fit=crop', NULL, 'Classic wayfarer in crystal clear', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(24, 'Classic Aviator Gold', 'Sunglasses', 'Aviator', 'full-rim', NULL, 'Vincent Chase', 2499.00, 3199.00, 12.00, 50, 'Gold', 'active', 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=400&h=400&fit=crop', NULL, 'Iconic gold aviator sunglasses', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(25, 'Aviator Pilot', 'Sunglasses', 'Aviator', 'full-rim', NULL, 'John Jacobs', 2699.00, 3399.00, 12.00, 50, 'Silver', 'active', 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=400&h=400&fit=crop', NULL, 'Original pilot-style aviators', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(26, 'Modern Aviator Black', 'Eyeglasses', 'Aviator', 'full-rim', NULL, 'VisionKart Air', 1899.00, 2399.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=400&h=400&fit=crop', NULL, 'Contemporary black aviator frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(27, 'Bold Square Black', 'Eyeglasses', 'Square', 'full-rim', NULL, 'Vincent Chase', 1899.00, 2399.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop', NULL, 'Bold square frames for a strong look', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(28, 'Square Tortoise', 'Eyeglasses', 'Square', 'full-rim', NULL, 'John Jacobs', 2199.00, 2799.00, 12.00, 50, 'Brown', 'active', 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop', NULL, 'Classic tortoise square frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(29, 'Minimal Square', 'Eyeglasses', 'Square', 'rimless', NULL, 'VisionKart Air', 1599.00, 1999.00, 12.00, 50, 'Silver', 'active', 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=400&h=400&fit=crop', NULL, 'Minimalist rimless square frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(30, 'Professional Rectangle', 'Eyeglasses', 'Rectangle', 'full-rim', NULL, 'John Jacobs', 2099.00, 2699.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop', NULL, 'Professional rectangle frames for business', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(31, 'Slim Rectangle', 'Eyeglasses', 'Rectangle', 'half-rim', NULL, 'Vincent Chase', 1799.00, 2299.00, 12.00, 50, 'Silver', 'active', 'https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop', NULL, 'Slim half-rim rectangle frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(32, 'Wide Rectangle', 'Eyeglasses', 'Rectangle', 'full-rim', NULL, 'VisionKart Air', 1699.00, 2099.00, 12.00, 50, 'Brown', 'active', 'https://images.unsplash.com/photo-1614715838608-dd527c46231d?w=400&h=400&fit=crop', NULL, 'Wide rectangle frames for larger faces', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(33, 'Classic Wayfarer Black', 'Sunglasses', 'Wayfarer', 'full-rim', NULL, 'Vincent Chase', 2199.00, 2799.00, 12.00, 50, 'Black', 'active', 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop', NULL, 'Iconic black wayfarer sunglasses', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(34, 'Wayfarer Tortoise', 'Eyeglasses', 'Wayfarer', 'full-rim', NULL, 'John Jacobs', 2399.00, 2999.00, 12.00, 50, 'Brown', 'active', 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop', NULL, 'Classic tortoise wayfarer frames', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(35, 'Modern Wayfarer', 'Eyeglasses', 'Wayfarer', 'full-rim', NULL, 'VisionKart Air', 1899.00, 2399.00, 12.00, 50, 'Blue', 'active', 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=400&h=400&fit=crop', NULL, 'Contemporary wayfarer in navy blue', '2025-12-29 07:33:52', '2025-12-29 07:33:52'),
(39, 'Air by Hustler', 'eyeglasses', 'round', 'full-rim', 'HSN5689', 'Vincent Chase', 1200.00, 1000.00, 5.00, 100, 'Green', '', 'uploads/products/product_1767005588_69525d94a10b5.jpeg', '', 'dfjklsd', '2025-12-29 10:53:25', '2025-12-29 11:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(20) DEFAULT NULL,
  `supplier_email` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) DEFAULT 18.00,
  `gst_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash',
  `invoice_number` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` enum('received','pending','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier`, `supplier_phone`, `supplier_email`, `city`, `product_name`, `category`, `quantity`, `cost_price`, `selling_price`, `gst_percentage`, `gst_amount`, `total_amount`, `payment_method`, `invoice_number`, `purchase_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Hustler Pvt.Ltd', '9876543210', 'hustler@gmail.com', 'Mumbai', 'Hustler Air militry green', 'Eyeglasses', 500, 1099.00, 1599.00, 5.00, 27475.00, 576975.00, 'Cash', 'INV-5689HXC', '2025-12-27', 'received', 'Consingment Received successfuly', '2025-12-27 09:46:02', '2025-12-27 09:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `email_verified`, `verification_token`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`, `last_login`, `status`) VALUES
(1, 'admin@visionkart.com', '$2y$10$4HZlF9Q8o2SzVi6Umh3RNeKnNdRqdPfFVyLH/BSJnHRqcYfYDLQYO', 'Admin', 'User', NULL, 'admin', 1, NULL, NULL, NULL, '2025-12-27 07:52:37', '2025-12-27 08:15:34', '2025-12-27 08:15:34', 'active'),
(2, 'testuser@example.com', '$2y$10$0lw/uvMwjqCtJrt2v.82I.9c1Oe322RC.urYy.6c.hIDFQW0Noyzy', 'Test', 'User', '+91 1234567890', 'customer', 0, 'c363831d816ea453f5fce70c92fdda0964dc16eeaa7b8077eb685abe40e8ab10', NULL, NULL, '2025-12-27 08:15:25', '2025-12-27 08:15:25', '2025-12-27 08:15:25', 'active'),
(3, 'test@gmail.com', '$2y$10$xFrWwpoQaR7XSeUFNWPiP.RQPHWD8r.NJFGsROoSy56rK0AzQCV3G', 'Test', 'Account', '9876543210', 'customer', 0, 'c968f2e4c57f97cc3cf98f7c03692d4cdecdff7ad4649504935d461c4577ff72', NULL, NULL, '2025-12-27 08:17:00', '2025-12-29 10:56:07', '2025-12-29 10:56:07', 'active'),
(4, 'pavan@gmail.com', '$2y$10$NuHHndvYJRffOxHzH2m4r.Jo84Ny580lnfziHMCS5wlXKxjyIAGBm', 'pavan', 'gawande', '9876543210', 'customer', 0, 'a6839eb0668a6926ebdc9efeed146e5f73aeff1b43df390b25b9bda7ded5b235', NULL, NULL, '2025-12-29 08:08:51', '2025-12-29 08:08:51', '2025-12-29 08:08:51', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('home','work','other') DEFAULT 'home',
  `full_name` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) DEFAULT 'India',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 30 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `session_id`, `user_id`, `created_at`, `updated_at`, `expires_at`) VALUES
(1, 'vk_576ad5874445c00dd8802031ba3d61df_1766744628', NULL, '2025-12-26 10:23:48', '2025-12-26 10:24:04', '2026-01-25 10:24:04'),
(2, 'vk_2e834629cd55068462205b81a60c7081_1766744663', NULL, '2025-12-26 10:24:23', '2025-12-26 10:24:23', '2026-01-25 10:24:23'),
(3, 'vk_663abbc40fb5cffed0a5b9faa5269c8e_1766744765', NULL, '2025-12-26 10:26:05', '2025-12-26 10:26:05', '2026-01-25 10:26:05'),
(4, 'vk_f2f90a191381ab00207b22f53685f912_1766818346', NULL, '2025-12-27 06:52:26', '2025-12-29 11:13:01', '2026-01-28 11:13:01'),
(5, 'vk_7b499f5e47536b9fdaf60f93df147aa3_1766818395', NULL, '2025-12-27 06:53:15', '2025-12-27 06:53:15', '2026-01-26 06:53:15'),
(6, 'vk_92e8c5f8f3e01ced0b7c49d2e3f7fd10_1766823003', NULL, '2025-12-27 08:10:03', '2025-12-27 08:10:03', '2026-01-26 08:10:03'),
(7, 'vk_644140ace19d4d7df548a27ebfe78a25_1766825281', NULL, '2025-12-27 08:48:01', '2025-12-27 08:48:01', '2026-01-26 08:48:01'),
(8, 'vk_fa180f8b3142f6abeaf6c1cad68762b3_1766826609', NULL, '2025-12-27 09:10:09', '2025-12-27 09:10:09', '2026-01-26 09:10:09'),
(9, 'vk_df4b7f9eaecdb0fad5c926d3a0a8a60b_1766829560', NULL, '2025-12-27 09:59:20', '2025-12-27 09:59:20', '2026-01-26 09:59:20'),
(10, 'vk_ecf4aceb645b76a451ed73f42b9fb441_1766829811', NULL, '2025-12-27 10:03:31', '2025-12-27 10:03:31', '2026-01-26 10:03:31'),
(11, 'vk_ecc302550699dc2e3db3febe8d4f6df5_1766829832', NULL, '2025-12-27 10:03:52', '2025-12-27 10:03:52', '2026-01-26 10:03:52'),
(12, 'vk_9a0c8e7c416162a905b2583b1398d0bf_1766830173', NULL, '2025-12-27 10:09:33', '2025-12-27 10:09:33', '2026-01-26 10:09:33'),
(13, 'vk_1961ac9d1052b7505828f5942c62eaa3_1766990412', NULL, '2025-12-29 06:40:12', '2025-12-29 06:40:12', '2026-01-28 06:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_product` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_verification_token` (`verification_token`),
  ADD KEY `idx_reset_token` (`reset_token`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_product` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
