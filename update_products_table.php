<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visionkart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "🔄 Adding missing columns to products table...\n\n";

$alterQueries = [
    "ALTER TABLE products ADD COLUMN frametype VARCHAR(50) AFTER subcategory",
    "ALTER TABLE products ADD COLUMN color VARCHAR(50) AFTER stock",
    "ALTER TABLE products ADD COLUMN video VARCHAR(500) AFTER image",
    "ALTER TABLE products ADD COLUMN original_price DECIMAL(10,2) AFTER price"
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

echo "\n✅ Products table updated!\n";

// Show current table structure
echo "\n📋 Updated Products Table Structure:\n";
$result = $conn->query("DESCRIBE products");
if ($result) {
    echo str_pad("Field", 25) . str_pad("Type", 35) . "\n";
    echo str_repeat("-", 60) . "\n";
    while ($row = $result->fetch_assoc()) {
        echo str_pad($row['Field'], 25) . str_pad($row['Type'], 35) . "\n";
    }
}

$conn->close();
?>
