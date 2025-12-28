<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visionkart_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "📋 Products Table Structure:\n\n";

$result = $conn->query("DESCRIBE products");
if ($result) {
    echo str_pad("Field", 25) . str_pad("Type", 35) . str_pad("Null", 8) . "Key\n";
    echo str_repeat("-", 75) . "\n";
    while ($row = $result->fetch_assoc()) {
        echo str_pad($row['Field'], 25) . 
             str_pad($row['Type'], 35) . 
             str_pad($row['Null'], 8) . 
             $row['Key'] . "\n";
    }
}

$conn->close();
?>
