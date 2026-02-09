-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 08:51 AM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `role`, `last_login`, `created_at`, `failed_attempts`, `locked_until`) VALUES
(1, 'admin', '$2y$10$WcqgFD2dw9WYklMAZb.SV.uuTfVPX9oAe/UtHPquMu/rR5p6e97gW', 'admin@visionkart.com', 'admin', '2026-01-16 04:26:47', '2025-12-25 15:20:13', 0, NULL);

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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `session_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(22, 'vk_97436ecb14634b4a80d377249b40732f_1768539955', 25, 1, '2026-01-16 05:20:24', '2026-01-16 05:20:24'),
(23, 'vk_97436ecb14634b4a80d377249b40732f_1768539955', 24, 1, '2026-01-16 05:21:04', '2026-01-16 05:21:04');

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
(5, 'Aviator', 'fa-plane', 1, '2025-12-25 15:40:18'),
(6, 'Wayfarer', 'fa-glasses', 0, '2025-12-25 15:40:18'),
(7, 'Oval', 'fa-circle', 0, '2025-12-25 15:40:18'),
(8, 'Square', 'fa-square', 1, '2025-12-25 15:40:18'),
(9, 'Rectangle', 'fa-square', 0, '2025-12-25 15:40:18'),
(10, 'Aqua Lenses', 'fa-tag', 0, '2025-12-31 04:27:53'),
(11, 'Test Sub Category 1', 'fa-tag', 0, '2025-12-31 04:49:17'),
(12, 'Test sub Category 3', 'fa-tag', 0, '2025-12-31 06:23:19'),
(13, 'vini', 'fa-tag', 0, '2026-01-16 04:37:40');

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
('ORD-005', NULL, NULL, 'David Brown', 'Round + Cat-Eye', 3800.00, '2024-12-01', 'cancelled', NULL, NULL, '2025-12-25 15:20:19', '2025-12-25 15:20:19'),
('ORD-20251231-2031', 2, NULL, 'Test User', 'Test Product', 1000.00, '2025-12-31', 'pending', 'COD', 'Test Address', '2025-12-31 04:55:50', '2025-12-31 05:10:12'),
('ORD-20251231-3688', 2, NULL, 'Test User', 'Test Product', 1000.00, '2025-12-31', 'pending', 'COD', 'Test Address', '2025-12-31 04:55:31', '2025-12-31 05:10:12'),
('ORD-20251231-4779', 3, NULL, 'Test Account', 'Test 1', 1416.00, '2025-12-31', 'pending', 'COD', 'Test Account\n9876543210\n01\n0sdnjk\ndsahk, Maharashtra - 444602', '2025-12-31 05:04:53', '2025-12-31 05:04:53'),
('ORD-20251231-7381', 3, NULL, 'Test Account', 'Modern Clubmaster', 2239.00, '2025-12-31', 'pending', 'COD', 'Test Account\n47754545554\ndsfioohsdiu\ndsfnkljsd\nds fkj, Karnataka - sdskcndflk', '2025-12-31 08:29:57', '2025-12-31 08:29:57'),
('ORD-20251231-8658', 2, NULL, 'Test User', 'Test Product', 1000.00, '2025-12-31', 'pending', 'COD', 'Test Address', '2025-12-31 04:55:43', '2025-12-31 05:10:12'),
('ORD-20251231-9423', 4, NULL, 'vini nimbhorkhar', 'Glamour Cat-Eye', 2463.00, '2025-12-31', 'pending', 'COD', 'vini nimbhorkhar\n9175592863\n121\ngadge nagar\namravati, Maharashtra - 666409', '2025-12-31 12:25:06', '2025-12-31 12:25:06'),
('ORD-20260105-8738', NULL, NULL, 'navya kh', 'Slim Rectangle', 2015.00, '2026-01-05', 'pending', 'COD', 'navya kh\n9665546503\n121\nakola\namravati, Maharashtra - 666409', '2026-01-05 09:42:18', '2026-01-05 09:42:18'),
('ORD-20260116-5016', 7, NULL, 'mai r', 'mini', 1575.00, '2026-01-16', 'pending', 'COD', 'mai r\n9175592863\n121\ngadge nagar\namravati, Maharashtra - 666409', '2026-01-16 04:35:13', '2026-01-16 04:35:13'),
('ORD-20260116-8816', NULL, NULL, 'vaishu ni', 'Clubmaster Gold', 3023.00, '2026-01-16', 'pending', 'COD', 'vaishu ni\n9876543210\nddd\ngadge nagar\namravati, Maharashtra - 446611', '2026-01-16 05:06:58', '2026-01-16 05:06:58'),
('TEST-20251231-0247', NULL, NULL, 'API Test User', 'Test Product', 1500.00, '2025-12-31', 'pending', 'COD', 'Test Address, City, State - 123456', '2025-12-31 04:58:12', '2025-12-31 04:58:12'),
('TEST-20251231-1776', NULL, NULL, 'API Test User', 'Test Product', 1500.00, '2025-12-31', 'pending', 'COD', 'Test Address, City, State - 123456', '2025-12-31 04:58:05', '2025-12-31 04:58:05'),
('TEST-20251231-9616', NULL, NULL, 'API Test User', 'Test Product', 1500.00, '2025-12-31', 'pending', 'COD', 'Test Address, City, State - 123456', '2025-12-31 05:01:12', '2025-12-31 05:01:12');

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
(1, 'ORD-20251231-9423', 12, 'Glamour Cat-Eye', 1, 2199.00, 264.00, 2463.00),
(2, 'ORD-20260105-8738', 31, 'Slim Rectangle', 1, 1799.00, 216.00, 2015.00),
(3, 'ORD-20260116-5016', 39, 'mini', 1, 1500.00, 75.00, 1575.00),
(4, 'ORD-20260116-8816', 19, 'Clubmaster Gold', 1, 2699.00, 324.00, 3023.00);

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
(6, 'v', 'eyeglasses', 'cat-eye', NULL, '1234', 'John Jacobs', 1222.00, NULL, 12.00, 45, NULL, 'active', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAAAgAAAAcAHMAUgBHAEJYWVogAAAAAAAAb6IAADj1AAADkFhZWiAAAAAAAABimQAAt4UAABjaWFlaI', NULL, 'shgwhgjg', '2025-12-25 16:15:13', '2025-12-25 16:15:13'),
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
(37, 'Test 1', 'Contact Lenses', 'Test Sub Category 1', 'half-rim', 'HSN5645', 'John Jacobs', 1200.00, 1000.00, 18.00, 100, 'Green', '', 'uploads/products/product_1767156596_6954ab74eac31.jpeg,uploads/products/product_1767156601_6954ab79839ca.jpeg,uploads/products/product_1767156603_6954ab7ba061c.jpeg,uploads/products/product_1767156624_6954ab90e7b6b.jpeg', '', 'Test Description.', '2025-12-31 04:50:39', '2025-12-31 08:46:23'),
(39, 'mini', 'Eyeglasses', 'Aviator', 'full-rim', 'HSN5689', 'John Jacobs', 1500.00, 1200.00, 5.00, 12, 'Green', 'active', 'assets/images/product_1768537881_6969bf191ada1.jpeg,assets/images/product_1768537884_6969bf1c82cfb.jpeg,assets/images/product_1768537885_6969bf1da186c.jpeg,assets/images/product_1768537887_6969bf1f22699.jpeg', '', 'gjhggjg', '2026-01-16 04:32:23', '2026-01-16 04:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(20) DEFAULT NULL,
  `gst_no` varchar(20) DEFAULT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `items` text DEFAULT NULL COMMENT 'JSON array of product items',
  `total_items` int(11) DEFAULT 0,
  `subtotal` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier`, `supplier_phone`, `gst_no`, `supplier_email`, `city`, `product_name`, `category`, `quantity`, `cost_price`, `selling_price`, `gst_percentage`, `gst_amount`, `total_amount`, `payment_method`, `invoice_number`, `purchase_date`, `status`, `notes`, `created_at`, `updated_at`, `items`, `total_items`, `subtotal`) VALUES
(2, 'Test Supler 1', '9876564123', NULL, NULL, 'Amravati', NULL, NULL, NULL, NULL, NULL, 18.00, 15000.00, 315000.00, 'Cash', 'INV-CCX56', '2025-12-31', 'received', 'Test Notes....', '2025-12-31 07:38:11', '2025-12-31 07:38:11', '[{\"name\":\"Hustler Air\",\"category\":\"Eyeglasses\",\"subcategory\":\"Clubmaster\",\"hsn\":\"9004\",\"quantity\":100,\"purchase_price\":1000,\"sell_price\":1200,\"gst_percentage\":5,\"gst_amount\":5000,\"total\":105000},{\"name\":\"Vincent Chase\",\"category\":\"Eyeglasses\",\"subcategory\":\"Round\",\"hsn\":\"9005\",\"quantity\":100,\"purchase_price\":2000,\"sell_price\":2200,\"gst_percentage\":5,\"gst_amount\":10000,\"total\":210000}]', 200, 300000.00),
(3, 'dsofjlk', '9876543210', NULL, NULL, 'dsnfjkh', NULL, NULL, NULL, NULL, NULL, 18.00, 15000.00, 315000.00, 'Cash', 'dsfknasd', '2025-12-31', 'received', 'nodtes', '2025-12-31 08:01:02', '2025-12-31 08:01:02', '[{\"name\":\"Test p1\",\"category\":\"ASDTR\",\"subcategory\":\"dsjhf\",\"hsn\":\"9004\",\"quantity\":100,\"purchase_price\":1000,\"sell_price\":1200,\"gst_percentage\":5,\"gst_amount\":5000,\"total\":105000},{\"name\":\"TEst p2\",\"category\":\"Sunglasses\",\"subcategory\":\"Aviator\",\"hsn\":\"9005\",\"quantity\":100,\"purchase_price\":2000,\"sell_price\":2200,\"gst_percentage\":5,\"gst_amount\":10000,\"total\":210000}]', 200, 300000.00),
(4, 'aaaa', '9876543210', NULL, NULL, 'Mumbai', NULL, NULL, NULL, NULL, NULL, 18.00, 36.00, 336.00, 'Cash', '12346', '2025-12-31', 'received', 'hhh', '2025-12-31 14:51:05', '2025-12-31 14:51:05', '[{\"name\":\"12\",\"category\":\"Sunglasses\",\"subcategory\":\"Round\",\"hsn\":\"9004\",\"quantity\":1,\"purchase_price\":100,\"sell_price\":100,\"gst_percentage\":12,\"gst_amount\":12,\"total\":112},{\"name\":\"56\",\"category\":\"aa\",\"subcategory\":\"Round\",\"hsn\":\"9004\",\"quantity\":1,\"purchase_price\":200,\"sell_price\":10,\"gst_percentage\":12,\"gst_amount\":24,\"total\":224}]', 2, 300.00),
(5, 'vini', '9876543210', '123acs5ffffgfggf4545', NULL, 'Mumbai', NULL, NULL, NULL, NULL, NULL, 18.00, 2160.00, 20160.00, 'Cash', 'INV-5689HXC', '2026-01-16', 'received', '', '2026-01-16 04:37:13', '2026-01-16 04:37:13', '[{\"name\":\"vininnnn\",\"category\":\"Sunglasses\",\"subcategory\":\"Sports\",\"hsn\":\"9004\",\"quantity\":15,\"purchase_price\":1200,\"sell_price\":1500,\"gst_percentage\":12,\"gst_amount\":2160,\"total\":20160}]', 15, 18000.00);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_categories`
--

CREATE TABLE `purchase_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('category','subcategory') NOT NULL,
  `parent_category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_categories`
--

INSERT INTO `purchase_categories` (`id`, `name`, `type`, `parent_category`, `created_at`) VALUES
(1, 'TPC', 'category', NULL, '2025-12-31 07:53:30'),
(2, 'TPSC', 'subcategory', 'TPC', '2025-12-31 07:53:39'),
(3, 'ASDTR', 'category', NULL, '2025-12-31 07:58:15'),
(4, 'dsjhf', 'subcategory', 'ASDTR', '2025-12-31 07:58:46'),
(5, 'aa', 'category', NULL, '2025-12-31 14:50:16');

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
(3, 'test@gmail.com', '$2y$10$F3Uj5ozwfFdt.kVbfaIYwOiOnSLMxJ5nJKAFb2htnvmh1cQJtk8Be', 'Test', 'Account', '9876543210', 'customer', 0, 'c968f2e4c57f97cc3cf98f7c03692d4cdecdff7ad4649504935d461c4577ff72', NULL, NULL, '2025-12-27 08:17:00', '2025-12-31 08:45:55', '2025-12-31 08:45:55', 'active'),
(4, 'vini111@gmail.com', '$2y$10$0nrZQbz.3fW3Czttuee91OXdSkz.Te1QNandHIEzmnka9DiZUFxGq', 'vini', 'nimbhorkhar', '9175592863', 'customer', 0, 'dd2adb77602e9614efee23681db881e2fb96a0b0931e152a29062e05308efaec', NULL, NULL, '2025-12-31 12:23:39', '2025-12-31 14:44:43', '2025-12-31 14:44:43', 'active'),
(5, 'TESTUSER@GMAIL.COM', '$2y$10$IXoZ3NMiPOWC.41itlUoM.zOn6BLGoadegM/B7VZMIu8.thKIAtV6', 'TEST', 'USER', '1234567890', 'customer', 0, '4f786ad91d9802d432fa5b2d14f43dc991eaa6e8abb9c5eedd6a71c5d04209ae', NULL, NULL, '2025-12-31 13:09:16', '2025-12-31 13:10:30', '2025-12-31 13:10:30', 'active'),
(6, 'pp@aakxd.com', '$2y$10$7sKkINeLt6vMlQQjTQNaR.FgRFUk/u5lW4SM0RuwlIhAx7V9a70ti', 'Prathamesh', 'Pakade', '8010', 'customer', 0, 'c73d619c7cd75d11769b6369c04891d80bef0e571a617401fd2d830725742741', '63f0398a07a7a2a84036bf04a95f307e7814e28626bb21f39be1165e9a71e5f1', '2026-01-06 08:27:19', '2026-01-06 06:20:26', '2026-01-06 06:27:19', '2026-01-06 06:25:50', 'active'),
(7, 'm123@gmail.com', '$2y$10$pzs36ebxl4JQusiHdaeoL..l8p0YdPPS6FHjffTFtkOAyjDPuQoF6', 'mai', 'r', '9766967533', 'customer', 0, 'daaad5eaecbd7b306a12ca05154b66b15b5c9afd4509828ef6f3c5b88ce4a1b0', NULL, NULL, '2026-01-16 04:34:09', '2026-01-16 05:21:29', '2026-01-16 05:21:29', 'active');

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
(5, 'vk_7b499f5e47536b9fdaf60f93df147aa3_1766818395', NULL, '2025-12-27 06:53:15', '2025-12-27 06:53:15', '2026-01-26 06:53:15'),
(6, 'vk_92e8c5f8f3e01ced0b7c49d2e3f7fd10_1766823003', NULL, '2025-12-27 08:10:03', '2025-12-27 08:10:03', '2026-01-26 08:10:03'),
(7, 'vk_644140ace19d4d7df548a27ebfe78a25_1766825281', NULL, '2025-12-27 08:48:01', '2025-12-27 08:48:01', '2026-01-26 08:48:01'),
(8, 'vk_fa180f8b3142f6abeaf6c1cad68762b3_1766826609', NULL, '2025-12-27 09:10:09', '2025-12-27 09:10:09', '2026-01-26 09:10:09'),
(9, 'vk_df4b7f9eaecdb0fad5c926d3a0a8a60b_1766829560', NULL, '2025-12-27 09:59:20', '2025-12-27 09:59:20', '2026-01-26 09:59:20'),
(10, 'vk_ecf4aceb645b76a451ed73f42b9fb441_1766829811', NULL, '2025-12-27 10:03:31', '2025-12-27 10:03:31', '2026-01-26 10:03:31'),
(11, 'vk_ecc302550699dc2e3db3febe8d4f6df5_1766829832', NULL, '2025-12-27 10:03:52', '2025-12-27 10:03:52', '2026-01-26 10:03:52'),
(12, 'vk_9a0c8e7c416162a905b2583b1398d0bf_1766830173', NULL, '2025-12-27 10:09:33', '2025-12-27 10:09:33', '2026-01-26 10:09:33'),
(13, 'vk_1961ac9d1052b7505828f5942c62eaa3_1766990412', NULL, '2025-12-29 06:40:12', '2025-12-29 06:40:12', '2026-01-28 06:40:12'),
(14, 'vk_4d6602f0341a39f8aabfb1c40580e531_1767156365', NULL, '2025-12-31 04:46:05', '2025-12-31 04:46:05', '2026-01-30 04:46:05'),
(15, 'vk_18d275b6aeda7b0d222888ddcdfce5a8_1767157327', NULL, '2025-12-31 05:02:07', '2025-12-31 05:02:07', '2026-01-30 05:02:07'),
(16, 'vk_35275ad3bd5e905db8ee113593bbb370_1767157327', NULL, '2025-12-31 05:02:07', '2025-12-31 05:02:07', '2026-01-30 05:02:07'),
(17, 'vk_3c47ae5d48009835fa58a4e85e67166e_1767157334', NULL, '2025-12-31 05:02:14', '2025-12-31 05:02:14', '2026-01-30 05:02:14'),
(18, 'vk_b2ffc55a823b01f50a39bd1325dfa7ce_1767157342', NULL, '2025-12-31 05:02:22', '2025-12-31 05:02:22', '2026-01-30 05:02:22'),
(19, 'vk_f869b4ab5074db05310f26968b444599_1767157348', NULL, '2025-12-31 05:02:28', '2025-12-31 05:02:28', '2026-01-30 05:02:28'),
(20, 'vk_97436ecb14634b4a80d377249b40732f_1768539955', NULL, '2026-01-16 05:05:55', '2026-01-16 05:21:04', '2026-02-15 05:21:04');

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
-- Indexes for table `purchase_categories`
--
ALTER TABLE `purchase_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name_type` (`name`,`type`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_categories`
--
ALTER TABLE `purchase_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
