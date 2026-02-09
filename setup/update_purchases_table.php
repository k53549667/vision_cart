<?php
/**
 * Update Purchases Table - Add New Columns
 * Adds comprehensive fields for purchase management
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visionkart_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "🔄 Updating purchases table...\n\n";

// Add new columns to purchases table
$alterQueries = [
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS category VARCHAR(100) AFTER product_name",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS city VARCHAR(100) AFTER supplier",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS gst_percentage DECIMAL(5,2) DEFAULT 18.00 AFTER selling_price",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS gst_amount DECIMAL(10,2) DEFAULT 0.00 AFTER gst_percentage",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS total_amount DECIMAL(10,2) AFTER gst_amount",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'Cash' AFTER total_amount",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS invoice_number VARCHAR(100) AFTER payment_method",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS supplier_phone VARCHAR(20) AFTER supplier",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS supplier_email VARCHAR(255) AFTER supplier_phone",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS notes TEXT AFTER status",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at"
];

$success = 0;
$skipped = 0;

foreach ($alterQueries as $query) {
    try {
        if ($conn->query($query) === TRUE) {
            $success++;
            echo "✅ Column added successfully\n";
        } else {
            if (strpos($conn->error, 'Duplicate column name') !== false) {
                $skipped++;
                echo "⏭️  Column already exists, skipping\n";
            } else {
                echo "❌ Error: " . $conn->error . "\n";
            }
        }
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            $skipped++;
            echo "⏭️  Column already exists, skipping\n";
        } else {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n📊 Summary:\n";
echo "✅ Successfully added: $success columns\n";
echo "⏭️  Skipped (already exist): $skipped columns\n";

echo "\n✅ Purchases table updated successfully!\n";

// Show current table structure
echo "\n📋 Current Purchases Table Structure:\n";
$result = $conn->query("DESCRIBE purchases");
if ($result) {
    echo str_pad("Field", 25) . str_pad("Type", 30) . str_pad("Null", 8) . "Key\n";
    echo str_repeat("-", 70) . "\n";
    while ($row = $result->fetch_assoc()) {
        echo str_pad($row['Field'], 25) . 
             str_pad($row['Type'], 30) . 
             str_pad($row['Null'], 8) . 
             $row['Key'] . "\n";
    }
}

$conn->close();
?>
