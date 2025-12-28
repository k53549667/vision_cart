<?php
require_once 'config.php';

$query = "SELECT DISTINCT category FROM products ORDER BY category";
$result = $conn->query($query);

echo "Categories in database:\n\n";
while ($row = $result->fetch_assoc()) {
    echo "- " . $row['category'] . "\n";
}
?>