<?php
require_once 'config.php';

echo "Seeding database with sample data...\n";

// Sample products
$sampleProducts = [
    [
        'name' => 'Vincent Chase Round Classic',
        'category' => 'Eyeglasses',
        'subcategory' => 'Round',
        'hsn' => '9004',
        'brand' => 'Vincent Chase',
        'price' => 1900.00,
        'gst' => 12.00,
        'stock' => 45,
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=100&h=100&fit=crop'
    ],
    [
        'name' => 'Cat-Eye Transparent',
        'category' => 'Eyeglasses',
        'subcategory' => 'Cat-Eye',
        'hsn' => '9004',
        'brand' => 'Vincent Chase',
        'price' => 1900.00,
        'gst' => 12.00,
        'stock' => 32,
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=100&h=100&fit=crop'
    ],
    [
        'name' => 'Clubmaster Classic',
        'category' => 'Sunglasses',
        'subcategory' => 'Clubmaster',
        'hsn' => '9004',
        'brand' => 'John Jacobs',
        'price' => 2000.00,
        'gst' => 12.00,
        'stock' => 28,
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=100&h=100&fit=crop'
    ],
    [
        'name' => 'OJOS Clear Round',
        'category' => 'Eyeglasses',
        'subcategory' => 'Transparent',
        'hsn' => '9004',
        'brand' => 'OJOS',
        'price' => 750.00,
        'gst' => 12.00,
        'stock' => 56,
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1509695507497-903c140c43b0?w=100&h=100&fit=crop'
    ],
    [
        'name' => 'VisionKart Air Round',
        'category' => 'Eyeglasses',
        'subcategory' => 'Round',
        'hsn' => '9004',
        'brand' => 'VisionKart Air',
        'price' => 1900.00,
        'gst' => 12.00,
        'stock' => 38,
        'status' => 'active',
        'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=100&h=100&fit=crop'
    ]
];

// Insert products
foreach ($sampleProducts as $product) {
    $check = getRow("SELECT id FROM products WHERE name = ?", [$product['name']]);
    if (!$check) {
        $sql = "INSERT INTO products (name, category, subcategory, hsn, brand, price, gst, stock, status, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [
            $product['name'],
            $product['category'],
            $product['subcategory'],
            $product['hsn'],
            $product['brand'],
            $product['price'],
            $product['gst'],
            $product['stock'],
            $product['status'],
            $product['image']
        ];
        executeQuery($sql, $params);
        echo "Added product: {$product['name']}\n";
    }
}

// Sample customers - now seeded into users table
$sampleCustomers = [
    [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '+91 9876543210',
        'password' => password_hash('customer123', PASSWORD_DEFAULT)
    ],
    [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane@example.com',
        'phone' => '+91 9876543211',
        'password' => password_hash('customer123', PASSWORD_DEFAULT)
    ],
    [
        'first_name' => 'Mike',
        'last_name' => 'Johnson',
        'email' => 'mike@example.com',
        'phone' => '+91 9876543212',
        'password' => password_hash('customer123', PASSWORD_DEFAULT)
    ],
    [
        'first_name' => 'Sarah',
        'last_name' => 'Williams',
        'email' => 'sarah@example.com',
        'phone' => '+91 9876543213',
        'password' => password_hash('customer123', PASSWORD_DEFAULT)
    ],
    [
        'first_name' => 'David',
        'last_name' => 'Brown',
        'email' => 'david@example.com',
        'phone' => '+91 9876543214',
        'password' => password_hash('customer123', PASSWORD_DEFAULT)
    ]
];

// Insert customers into users table
foreach ($sampleCustomers as $customer) {
    $check = getRow("SELECT id FROM users WHERE email = ?", [$customer['email']]);
    if (!$check) {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role, status)
                VALUES (?, ?, ?, ?, ?, 'customer', 'active')";
        $params = [
            $customer['first_name'],
            $customer['last_name'],
            $customer['email'],
            $customer['phone'],
            $customer['password']
        ];
        executeQuery($sql, $params);
        echo "Added customer: {$customer['first_name']} {$customer['last_name']}\n";
    }
}

// Sample orders
$sampleOrders = [
    [
        'id' => 'ORD-001',
        'customer_name' => 'John Doe',
        'products' => 'Round Eyeglasses',
        'total_amount' => 1900.00,
        'order_date' => '2024-12-05',
        'status' => 'pending'
    ],
    [
        'id' => 'ORD-002',
        'customer_name' => 'Jane Smith',
        'products' => 'Cat-Eye Frames',
        'total_amount' => 1900.00,
        'order_date' => '2024-12-04',
        'status' => 'completed'
    ],
    [
        'id' => 'ORD-003',
        'customer_name' => 'Mike Johnson',
        'products' => 'Clubmaster',
        'total_amount' => 2000.00,
        'order_date' => '2024-12-03',
        'status' => 'processing'
    ],
    [
        'id' => 'ORD-004',
        'customer_name' => 'Sarah Williams',
        'products' => 'Transparent Round',
        'total_amount' => 750.00,
        'order_date' => '2024-12-02',
        'status' => 'completed'
    ],
    [
        'id' => 'ORD-005',
        'customer_name' => 'David Brown',
        'products' => 'Round + Cat-Eye',
        'total_amount' => 3800.00,
        'order_date' => '2024-12-01',
        'status' => 'cancelled'
    ]
];

// Insert orders
foreach ($sampleOrders as $order) {
    $check = getRow("SELECT id FROM orders WHERE id = ?", [$order['id']]);
    if (!$check) {
        $sql = "INSERT INTO orders (id, customer_name, products, total_amount, order_date, status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $order['id'],
            $order['customer_name'],
            $order['products'],
            $order['total_amount'],
            $order['order_date'],
            $order['status']
        ];
        executeQuery($sql, $params);
        echo "Added order: {$order['id']}\n";
    }
}

echo "Database seeding completed!\n";
?>