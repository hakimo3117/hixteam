<?php
require 'config.php';

// Get form input
$username = $_POST['username'] ?? '';
$method = $_POST['method'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);
$address = $_POST['address'] ?? '';

// Validate inputs
if (empty($username) || empty($method) || empty($amount) || empty($address)) {
    die("Invalid request.");
}

// Fetch user balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($amount < 1000) {
    die("The minimum withdrawal amount is 1000 points.");
}

// Deduct balance and record withdrawal
$conn->begin_transaction();
try {
    $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE username = ?");
    $stmt->bind_param("ds", $amount, $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO withdrawals (username, method, amount, address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssds", $username, $method, $amount, $address);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    // Styled success message
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Withdrawal Success</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f7fc;
                text-align: center;
                margin: 0;
                padding: 20px;
            }
            .success-message {
                margin: 50px auto;
                padding: 20px;
                background-color: #e6f9e9;
                color: #2d7a31;
                border: 2px solid #2d7a31;
                border-radius: 8px;
                width: 80%;
                max-width: 400px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            .success-message h1 {
                margin: 0;
                font-size: 24px;
            }
            .success-message p {
                font-size: 16px;
                margin: 10px 0 0;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #4A90E2;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-size: 16px;
            }
            a:hover {
                background-color: #357ABD;
            }
        </style>
    </head>
    <body>
        <div class="success-message">
            <h1>Success!</h1>
            <p>Your withdrawal request has been submitted successfully.</p>
            <p><strong>Amount:</strong> ' . number_format($amount, 2) . '</p>
            <p><strong>Method:</strong> ' . htmlspecialchars($method) . '</p>
            <p><strong>Address:</strong> ' . htmlspecialchars($address) . '</p>
            <a href="/">Return to Home</a>
        </div>
    </body>
    </html>';
} catch (Exception $e) {
    $conn->rollback();
    die("Error processing withdrawal: " . $e->getMessage());
}
?>
