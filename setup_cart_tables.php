<?php
/**
 * Setup Cart and Session Tables
 * Run this script to create the necessary tables for cart functionality
 */

require_once 'config.php';

$conn = getDBConnection();

echo "Creating cart and session tables...\n\n";

// Create user_sessions table for managing guest and user sessions
$sql_sessions = "CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + INTERVAL 30 DAY),
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_sessions) === TRUE) {
    echo "✓ Table 'user_sessions' created successfully\n";
} else {
    echo "✗ Error creating user_sessions table: " . $conn->error . "\n";
}

// Create cart table
$sql_cart = "CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_product` (`session_id`, `product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_cart) === TRUE) {
    echo "✓ Table 'cart' created successfully\n";
} else {
    echo "✗ Error creating cart table: " . $conn->error . "\n";
}

// Create wishlist table
$sql_wishlist = "CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_product` (`session_id`, `product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql_wishlist) === TRUE) {
    echo "✓ Table 'wishlist' created successfully\n";
} else {
    echo "✗ Error creating wishlist table: " . $conn->error . "\n";
}

echo "\n✓ All cart-related tables created successfully!\n";
echo "\nYou can now use the cart API at: http://localhost/vini/api_cart.php\n";

$conn->close();
?>
