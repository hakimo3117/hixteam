<?php
require 'config.php';

// Fetch JSON data
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';

if (empty($username)) {
    echo json_encode(["error" => "Invalid request."]);
    exit;
}

// Fetch user data from the database
$stmt = $conn->prepare("SELECT username, balance FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(["error" => "User not found."]);
}
?>
