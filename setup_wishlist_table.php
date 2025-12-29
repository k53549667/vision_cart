<?php
/**
 * Setup Wishlist Table
 * Creates the wishlist table if it doesn't exist
 */

require_once 'config.php';

echo "<h2>VisionKart - Wishlist Table Setup</h2>";
echo "<pre>";

$conn = getDBConnection();

// Check if wishlist table exists
$result = $conn->query("SHOW TABLES LIKE 'wishlist'");
if ($result->num_rows > 0) {
    echo "✅ Wishlist table already exists\n";
    
    // Show table structure
    echo "\nTable Structure:\n";
    $structure = $conn->query("DESCRIBE wishlist");
    while ($row = $structure->fetch_assoc()) {
        echo "  - {$row['Field']}: {$row['Type']} {$row['Key']}\n";
    }
    
    // Show row count
    $count = $conn->query("SELECT COUNT(*) as count FROM wishlist")->fetch_assoc();
    echo "\nTotal wishlist items: {$count['count']}\n";
} else {
    echo "Creating wishlist table...\n";
    
    $sql = "CREATE TABLE wishlist (
        id INT(11) NOT NULL AUTO_INCREMENT,
        session_id VARCHAR(255) NOT NULL,
        product_id INT(11) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_product (session_id, product_id),
        KEY product_id (product_id),
        CONSTRAINT wishlist_ibfk_1 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->query($sql)) {
        echo "✅ Wishlist table created successfully!\n";
    } else {
        echo "❌ Error creating table: " . $conn->error . "\n";
    }
}

echo "\n--- All Tables in Database ---\n";
$tables = $conn->query("SHOW TABLES");
while ($row = $tables->fetch_array()) {
    echo "  📁 " . $row[0] . "\n";
}

echo "</pre>";
echo "<br><a href='test-wishlist.html'>Test Wishlist API</a> | ";
echo "<a href='dashboard.php#wishlist'>Go to Dashboard Wishlist</a> | ";
echo "<a href='index.php'>Home</a>";
?>
