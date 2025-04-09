<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'wealthso_x1');
define('DB_PASS', 'wealthso_x1');
define('DB_NAME', 'wealthso_x1');

// Create a connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
