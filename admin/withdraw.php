<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

require 'config.php';

// Fetch withdrawal requests
$withdrawals = $conn->query("SELECT * FROM withdrawals ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #4A90E2;
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 10px;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #4A90E2;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f9ff;
        }
        td {
            font-size: 14px;
            color: #555;
        }
        .copy-btn {
            padding: 5px 10px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .copy-btn:hover {
            background-color: #357ABD;
        }
        .container {
            width: 100%;
            padding: 10px 0;
        }
        .btn {
            display: inline-block;
            margin: 20px auto;
            text-decoration: none;
            background-color: #4A90E2;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s;
            display: block;
            max-width: 200px;
            text-align: center;
        }
        .btn:hover {
            background-color: #357ABD;
        }
    </style>
    <script>
        // Function to copy address to clipboard
        function copyToClipboard(address) {
            navigator.clipboard.writeText(address).then(() => {
                alert("Address copied: " + address);
            }).catch(err => {
                alert("Failed to copy address: " + err);
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <h2>Withdrawal Requests</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Address</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $withdrawals->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['method']); ?></td>
                <td>Point <?= number_format($row['amount'], 2); ?></td>
                <td>
                    <?= htmlspecialchars($row['address']); ?>
                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($row['address']); ?>')">Copy</button>
                </td>
                <td><?= $row['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="/admin" class="btn">Back to Admin Panel</a>
    </div>
</body>
</html>