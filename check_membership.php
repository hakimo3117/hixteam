<?php
header('Content-Type: application/json');

// Include database connection file
include 'config.php'; // Adjust this to your actual database connection file

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method. POST required.']);
    exit;
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate action type
if (!isset($data['action'])) {
    echo json_encode(['error' => 'Action not specified.']);
    exit;
}

$action = $data['action'];

// Handle different actions
if ($action === 'check_membership') {
    // Check membership logic
    if (isset($data['user_id']) && isset($data['channel_username']) && isset($data['task_id'])) {
        $telegramId = $data['user_id'];
        $channelUsername = $data['channel_username'];
        $taskId = $data['task_id'];

        // Telegram bot token (ensure this remains secure)
        $botToken = '7696778640:AAEYfaquHd9VWx5zSwg6JqkszpxbixalRWo';

        // API URL to check membership status
        $url = "https://api.telegram.org/bot$botToken/getChatMember?chat_id=" . urlencode($channelUsername) . "&user_id=" . urlencode($telegramId);

        // Initialize CURL to make a request to the Telegram API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set a timeout for the request

        $response = curl_exec($ch);

        // Check for CURL errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            echo json_encode(['error' => 'CURL error: ' . $error]);
            exit;
        }

        curl_close($ch);

        // Decode the Telegram API response
        $responseData = json_decode($response, true);

        // Check if the API call was successful
        if (isset($responseData['ok']) && $responseData['ok']) {
            // Get the membership status
            $status = $responseData['result']['status'];
            $isMember = in_array($status, ['creator', 'administrator', 'member']);

            if ($isMember) {
                // Save task completion in the database
                $stmt = $conn->prepare("INSERT INTO user_tasks (user_id, task_id, completed_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE completed_at = NOW()");
                $stmt->bind_param("ii", $telegramId, $taskId);

                if ($stmt->execute()) {
                    // Add reward logic
                    $reward = 10; // Define the reward amount

                    // Fetch user balance
                    $userStmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
                    $userStmt->bind_param("i", $telegramId);
                    $userStmt->execute();
                    $userResult = $userStmt->get_result();

                    if ($userRow = $userResult->fetch_assoc()) {
                        $newBalance = $userRow['balance'] + $reward;

                        // Update user balance
                        $updateStmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
                        $updateStmt->bind_param("di", $newBalance, $telegramId);

                        if ($updateStmt->execute()) {
                            echo json_encode([
                                'isMember' => true,
                                'message' => 'Task marked as completed. Reward added.',
                                'reward' => $reward,
                                'new_balance' => $newBalance
                            ]);
                        } else {
                            echo json_encode([
                                'isMember' => true,
                                'error' => 'Failed to update user balance.'
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'isMember' => true,
                            'error' => 'User not found in the database.'
                        ]);
                    }

                    $userStmt->close();
                } else {
                    echo json_encode(['isMember' => true, 'error' => 'Failed to save task completion in database.']);
                }

                $stmt->close();
            } else {
                echo json_encode(['isMember' => false, 'message' => 'User is not a member of the channel.']);
            }
        } else {
            // Handle errors from the Telegram API response
            $errorMessage = $responseData['description'] ?? 'Unknown error';
            echo json_encode(['error' => 'Telegram API error: ' . $errorMessage]);
        }
    } else {
        // Handle missing user_id, task_id, or channel_username in the request
        echo json_encode(['error' => 'Invalid data: user_id, task_id, and channel_username are required.']);
    }
} elseif ($action === 'load_tasks') {
    // Load incomplete tasks logic
    if (isset($data['user_id'])) {
        $userId = $data['user_id'];

        // Query to get incomplete tasks
        $query = "
            SELECT t.id as task_id, t.description as task_description, t.channel_username
            FROM tasks t
            LEFT JOIN user_tasks ut ON t.id = ut.task_id AND ut.user_id = ?
            WHERE ut.task_id IS NULL
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $incompleteTasks = [];
        while ($row = $result->fetch_assoc()) {
            $incompleteTasks[] = $row;
        }

        echo json_encode(['incompleteTasks' => $incompleteTasks]);
    } else {
        echo json_encode(['error' => 'User ID is required to load tasks.']);
    }
} else {
    echo json_encode(['error' => 'Invalid action.']);
}

// Close the database connection
$conn->close();
?>