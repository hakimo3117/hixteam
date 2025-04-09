<?php
// checkAds.php

require 'config.php'; // Include the config file for database connection

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Validate the presence of `user_id`
    if (!isset($_GET['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User ID is required."]);
        exit;
    }

    $userId = $_GET['user_id'];

    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the `ads_watched` count for the user
    $stmt = $pdo->prepare("SELECT ads_watched FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["status" => "success", "ads_watched" => (int)$user['ads_watched']]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}