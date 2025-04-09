<?php
// adreward.php

require 'config.php'; // Include the config file for database connection

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Decode the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate the input data
    if (!isset($data['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User ID is required."]);
        exit;
    }

    $userId = $data['user_id'];

    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user exists and fetch their current balance and ads watched count
    $stmt = $pdo->prepare("SELECT balance, ads_watched FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $adsWatched = (int)$user['ads_watched'];
        $totalAds = 100; // Update this if more ads are added

        // Ensure the user has not already watched all ads
        if ($adsWatched >= $totalAds) {
            echo json_encode(["status" => "error", "message" => "You have already watched all available ads."]);
            exit;
        }

        // Update balance and increment ads watched count
        $reward = 10; // Define the reward amount
        $newBalance = $user['balance'] + $reward;
        $newAdsWatchedCount = $adsWatched + 1;

        // Update the database
        $stmt = $pdo->prepare("UPDATE users SET balance = :balance, ads_watched = :ads_watched WHERE id = :id");
        $stmt->bindParam(':balance', $newBalance, PDO::PARAM_INT);
        $stmt->bindParam(':ads_watched', $newAdsWatchedCount, PDO::PARAM_INT);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Reward successfully added.",
            "new_balance" => $newBalance
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}