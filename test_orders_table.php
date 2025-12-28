<?php
require_once 'config.php';

try {
    $conn = getDBConnection();
    
    // Check if orders table exists
    $result = $conn->query("SHOW TABLES LIKE 'orders'");
    
    if ($result && $result->num_rows > 0) {
        echo "✓ Orders table exists\n\n";
        
        // Show table structure
        $cols = $conn->query("DESCRIBE orders");
        echo "Table structure:\n";
        while($col = $cols->fetch_assoc()) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        
        // Check if order_items table exists
        $result2 = $conn->query("SHOW TABLES LIKE 'order_items'");
        if($result2 && $result2->num_rows > 0) {
            echo "\n✓ Order_items table exists\n";
        } else {
            echo "\n✗ Order_items table does NOT exist\n";
        }
        
        // Count orders
        $countResult = $conn->query("SELECT COUNT(*) as total FROM orders");
        $count = $countResult->fetch_assoc();
        echo "\nTotal orders in database: {$count['total']}\n";
        
    } else {
        echo "✗ Orders table does NOT exist\n";
        echo "\nCreating orders table...\n";
        
        $sql = "CREATE TABLE IF NOT EXISTS orders (
            id VARCHAR(50) PRIMARY KEY,
            customer_id VARCHAR(100),
            customer_name VARCHAR(100) NOT NULL,
            products TEXT,
            total_amount DECIMAL(10,2) NOT NULL,
            order_date DATE NOT NULL,
            status VARCHAR(50) DEFAULT 'pending',
            payment_method VARCHAR(50),
            shipping_address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->query($sql);
        echo "✓ Orders table created\n";
        
        // Create order_items table
        $sql2 = "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id VARCHAR(50) NOT NULL,
            product_id INT,
            product_name VARCHAR(200),
            quantity INT DEFAULT 1,
            price DECIMAL(10,2),
            gst DECIMAL(10,2),
            total DECIMAL(10,2),
            INDEX idx_order_id (order_id)
        )";
        
        $conn->query($sql2);
        echo "✓ Order_items table created\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
