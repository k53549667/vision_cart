<?php
// Test customers API

$ch = curl_init('http://localhost/vini/api_customers.php?action=list');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response:\n";
echo $response;
echo "\n\n";

$data = json_decode($response, true);
if (is_array($data)) {
    echo "✓ API returned valid JSON\n";
    echo "Number of customers: " . count($data) . "\n";
    if (count($data) > 0) {
        echo "First customer: " . $data[0]['name'] . " (" . $data[0]['email'] . ")\n";
    }
} else {
    echo "✗ API did not return valid JSON\n";
}
?>
