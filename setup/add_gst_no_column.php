<?php
/**
 * Migration script to add gst_no column to purchases table
 * Run this script once to update the database
 */

require_once '../config.php';

echo "<h2>Adding GST No column to purchases table</h2>";

try {
    $conn = getDBConnection();
    
    // Check if column already exists
    $result = $conn->query("SHOW COLUMNS FROM purchases LIKE 'gst_no'");
    
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Column 'gst_no' already exists in purchases table.</p>";
    } else {
        // Add the gst_no column after supplier_phone
        $sql = "ALTER TABLE purchases ADD COLUMN gst_no VARCHAR(20) NULL AFTER supplier_phone";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>✓ Column 'gst_no' added successfully to purchases table.</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding column: " . $conn->error . "</p>";
        }
    }
    
    echo "<p><a href='../admin/admin-pages/admin.php#purchases'>Go to Admin Panel - Purchases</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}
?>
