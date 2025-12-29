<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visionkart_db');

// Create connection
function getDBConnection() {
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Set charset to utf8
        $conn->set_charset("utf8");
    }

    return $conn;
}

// Helper function to execute queries
function executeQuery($sql, $params = []) {
    $conn = getDBConnection();
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    $result = $stmt->execute();

    if ($result) {
        if (strpos(strtoupper($sql), 'SELECT') === 0) {
            return $stmt->get_result();
        } else {
            return $stmt->affected_rows;
        }
    }

    return false;
}

// Helper function to get single row
function getRow($sql, $params = []) {
    $result = executeQuery($sql, $params);
    return $result ? $result->fetch_assoc() : null;
}

// Helper function to get all rows
function getRows($sql, $params = []) {
    $result = executeQuery($sql, $params);
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}
?>