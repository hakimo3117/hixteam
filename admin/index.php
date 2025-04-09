<?php
// Start session
session_start();

// Include the config.php for database connection
include('config.php');

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: adsajhdjash.php");
    exit();
}

// Initialize error message
$error_message = null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin_username = trim($_POST['username']);
    $admin_password = trim($_POST['password']);

    // Prevent SQL injection
    $admin_username = $conn->real_escape_string($admin_username);

    // Query to check if the admin credentials are correct
    $sql = "SELECT * FROM admin WHERE username='$admin_username'";
    $result = $conn->query($sql);

    // Check if username exists
    if ($result->num_rows > 0) {
        // Fetch the admin record
        $row = $result->fetch_assoc();

        // Verify the password with the hashed value stored in the database
        if (password_verify($admin_password, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin_username; // Store the username for traceability
            header("Location: adsajhdjash.php"); // Redirect to the admin dashboard
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password.";
        }
    } else {
        // Invalid username
        $error_message = "Invalid username or password.";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Admin Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>