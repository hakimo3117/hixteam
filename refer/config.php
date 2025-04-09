<?php
// config.php

// Database configuration
define('DB_HOST', 'localhost'); // Database host
define('DB_USER', 'wealthso_x1'); // Database username
define('DB_PASS', 'wealthso_x1'); // Database password
define('DB_NAME', 'wealthso_x1'); // Database name

// Create a connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
