<?php
// Check cart contents in database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visionkart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "📦 Cart Contents in Database:\n\n";

$result = $conn->query("SELECT * FROM cart");

if ($result && $result->num_rows > 0) {
    echo "Found " . $result->num_rows . " items in cart:\n\n";
    while($row = $result->fetch_assoc()) {
        echo "Cart ID: " . $row['id'] . "\n";
        echo "Session ID: " . ($row['session_id'] ?? 'NULL') . "\n";
        echo "User ID: " . ($row['user_id'] ?? 'NULL') . "\n";
        echo "Product ID: " . $row['product_id'] . "\n";
        echo "Quantity: " . $row['quantity'] . "\n";
        echo "Created: " . $row['created_at'] . "\n";
        echo "-------------------\n";
    }
} else {
    echo "✅ Cart is empty!\n";
}

echo "\n🔧 To clear all cart items, run:\n";
echo "TRUNCATE TABLE cart;\n";

$conn->close();
?>
