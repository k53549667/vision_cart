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

// Sample customers
$sampleCustomers = [
    [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+91 9876543210',
        'orders_count' => 5,
        'total_spent' => 9500.00,
        'joined_date' => '2024-01-15'
    ],
    [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'phone' => '+91 9876543211',
        'orders_count' => 3,
        'total_spent' => 5700.00,
        'joined_date' => '2024-02-20'
    ],
    [
        'name' => 'Mike Johnson',
        'email' => 'mike@example.com',
        'phone' => '+91 9876543212',
        'orders_count' => 7,
        'total_spent' => 13300.00,
        'joined_date' => '2024-01-10'
    ],
    [
        'name' => 'Sarah Williams',
        'email' => 'sarah@example.com',
        'phone' => '+91 9876543213',
        'orders_count' => 2,
        'total_spent' => 3800.00,
        'joined_date' => '2024-03-05'
    ],
    [
        'name' => 'David Brown',
        'email' => 'david@example.com',
        'phone' => '+91 9876543214',
        'orders_count' => 4,
        'total_spent' => 7600.00,
        'joined_date' => '2024-02-28'
    ]
];

// Insert customers
foreach ($sampleCustomers as $customer) {
    $check = getRow("SELECT id FROM customers WHERE email = ?", [$customer['email']]);
    if (!$check) {
        $sql = "INSERT INTO customers (name, email, phone, orders_count, total_spent, joined_date)
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $customer['name'],
            $customer['email'],
            $customer['phone'],
            $customer['orders_count'],
            $customer['total_spent'],
            $customer['joined_date']
        ];
        executeQuery($sql, $params);
        echo "Added customer: {$customer['name']}\n";
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