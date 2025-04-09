<?php
require 'config.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $input['userId'];
    $referrerId = $input['startapp']; // Use the startapp parameter as referrer ID

    if (!$userId || !$referrerId) {
        echo json_encode(['error' => 'Missing userId or referrerId']);
        exit;
    }

    // Check if the user exists; if not, add them
    $stmt = $conn->prepare("INSERT IGNORE INTO users (id, referrer_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $referrerId);
    $stmt->execute();

    // Insert referral into referrals table
    $stmt = $conn->prepare("INSERT INTO referrals (referrer_id, referee_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $referrerId, $userId);
    $stmt->execute();

    // Update referral count for referrer
    $stmt = $conn->prepare("UPDATE users SET referral_count = referral_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $referrerId);
    $stmt->execute();

    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['userId'] ?? null;
    if (!$userId) {
        echo json_encode(['error' => 'Missing userId']);
        exit;
    }

    // Get referrer for the user
    $stmt = $conn->prepare("SELECT referrer_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($referrer);
    $stmt->fetch();
    $stmt->close();

    // Get referrals made by this user
    $stmt = $conn->prepare("SELECT referee_id FROM referrals WHERE referrer_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $referrals = [];
    while ($row = $result->fetch_assoc()) {
        $referrals[] = $row['referee_id'];
    }
    
    echo json_encode(['referrals' => $referrals, 'referrer' => $referrer]);
    exit;
}

echo json_encode(['error' => 'Invalid request method']);
exit;
?>