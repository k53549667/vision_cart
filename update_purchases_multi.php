<?php
/**
 * Migration script to add multi-product support columns to purchases table
 */

require_once 'config.php';

echo "<h2>Updating Purchases Table for Multi-Product Support</h2>";

$conn = getDBConnection();

// Add new columns if they don't exist
$alterQueries = [
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS items TEXT DEFAULT NULL COMMENT 'JSON array of product items'",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS total_items INT(11) DEFAULT 0",
    "ALTER TABLE purchases ADD COLUMN IF NOT EXISTS subtotal DECIMAL(10,2) DEFAULT 0.00"
];

$success = true;

foreach ($alterQueries as $query) {
    try {
        // Check if column exists first (for MySQL versions that don't support IF NOT EXISTS in ALTER)
        $columnName = '';
        if (preg_match('/ADD COLUMN (?:IF NOT EXISTS )?(\w+)/', $query, $matches)) {
            $columnName = $matches[1];
        }
        
        // Check if column already exists
        $checkQuery = "SHOW COLUMNS FROM purchases LIKE '$columnName'";
        $result = $conn->query($checkQuery);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: blue;'>✓ Column '$columnName' already exists</p>";
            continue;
        }
        
        // Simplified query without IF NOT EXISTS for compatibility
        $simpleQuery = str_replace('IF NOT EXISTS ', '', $query);
        
        if ($conn->query($simpleQuery)) {
            echo "<p style='color: green;'>✓ Added column '$columnName' successfully</p>";
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        // Column might already exist
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "<p style='color: blue;'>✓ Column already exists (skipped)</p>";
        } else {
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
            $success = false;
        }
    }
}

// Rename gst_amount to total_gst if needed (optional - we can use both)
echo "<br><h3>Migration Complete!</h3>";
echo "<p>The purchases table now supports multi-product entries.</p>";
echo "<p>New columns:</p>";
echo "<ul>";
echo "<li><strong>items</strong> - JSON array containing all product items</li>";
echo "<li><strong>total_items</strong> - Total quantity of all items</li>";
echo "<li><strong>subtotal</strong> - Subtotal before GST</li>";
echo "</ul>";

echo "<p>Existing 'gst_amount' column will be used as 'total_gst' for new entries.</p>";

if ($success) {
    echo "<br><p style='background: #d4edda; padding: 10px; border-radius: 5px;'>✓ All migrations completed successfully! You can now use multi-product purchases.</p>";
} else {
    echo "<br><p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>✗ Some migrations failed. Please check the errors above.</p>";
}

echo "<br><a href='admin.php#purchases' style='background: #00bac7; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Panel</a>";
?>
