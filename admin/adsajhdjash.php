<?php
session_start();
// Restrict access to logged-in admins only
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to the login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Admin Panel</h1>
        <div class="list-group">
            <a href="user.php" class="list-group-item list-group-item-action">View User Information</a>
            <a href="#" class="list-group-item list-group-item-action disabled">Manage Users (Coming Soon)</a>
            <a href="#" class="list-group-item list-group-item-action disabled">View Site Statistics (Coming Soon)</a>
            <a href="withdraw.php" class="list-group-item list-group-item-action">Withdraw Request</a>
        </div>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>