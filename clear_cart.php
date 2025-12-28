<?php
// Clear all cart items
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visionkart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "🗑️  Clearing cart...\n\n";

if ($conn->query("TRUNCATE TABLE cart") === TRUE) {
    echo "✅ All cart items cleared successfully!\n";
    echo "Cart is now empty.\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();
?>
