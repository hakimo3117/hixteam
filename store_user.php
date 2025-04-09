<?php
// Database connection parameters
$host = 'localhost'; // Change to your host
$dbname = 'wealthso_x1'; // Change to your database name
$user = 'wealthso_x1'; // Change to your database username
$pass = 'wealthso_x1'; // Change to your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);

    // Ensure user data is received
    if (!isset($data['id'])) {
        echo json_encode(["status" => "error", "message" => "User ID is required."]);
        exit;
    }

    // Prepare a statement to check if the user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        // User exists, check for changes
        $updateNeeded = false;

        // Check for changes in each relevant field
        foreach (['username', 'first_name', 'last_name', 'is_premium'] as $field) {
            if ($existingUser[$field] !== $data[$field]) {
                $updateNeeded = true;
                break;
            }
        }

        // Update the user if changes are detected
        if ($updateNeeded) {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, is_premium = :is_premium WHERE id = :id");
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':is_premium', $data['is_premium']);
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
        }

        // Return user balance along with success message
        echo json_encode(["status" => "success", "message" => "User data updated.", "balance" => $existingUser['balance']]);

    } else {
        // User does not exist, insert new user
        $stmt = $pdo->prepare("INSERT INTO users (id, username, first_name, last_name, is_premium) VALUES (:id, :username, :first_name, :last_name, :is_premium)");
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':is_premium', $data['is_premium']);
        $stmt->execute();

        // Return balance as 0 for newly inserted user
        echo json_encode(["status" => "success", "message" => "User data inserted.", "balance" => 0]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>