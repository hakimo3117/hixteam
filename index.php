<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Web App</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #ffffff; /* White background */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #000000; /* Black text */
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 400px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #02a7ff; /* Black border */
            background-size: cover;
            background-position: center;
            margin-bottom: 20px;
        }
        .user-name {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-card {
            width: 100%;
            text-align: center;
        }
        .info-card p {
            margin: 10px 0;
            font-size: 18px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container" id="profile-container">
        <div class="profile-pic" id="profile-pic" style="background-image: url('https://via.placeholder.com/100');"></div>
        <div class="user-name" id="user-name">User Name</div>
        <div class="info-card" id="info-card">
            <p id="balance">Balance: Loading...</p>
            <p id="premium-status">Premium: No</p>
        </div>
    </div>
</body>
</html>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="<?php echo '/js/BV2s3xkm.min.js'; ?>"></script>
</body>
</html>


    
<!-- Navigation Bar -->
<nav style="display: flex; justify-content: space-around; align-items: center; background: #ffffff; border: 1px solid #02a7ff; border-radius: 20px; padding: 10px 0; width: 100%; max-width: 400px; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
    <a href="/" style="display: flex; flex-direction: column; align-items: center; color: #000000; text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.3s;">
        <i class="fas fa-home" style="font-size: 24px; margin-bottom: 5px;"></i>
        Home
    </a>
    <a href="/ads" style="display: flex; flex-direction: column; align-items: center; color: #000000; text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.3s;">
        <i class="fas fa-wallet" style="font-size: 24px; margin-bottom: 5px;"></i>
        Earn
    </a>
    <a href="/user/task.php" style="display: flex; flex-direction: column; align-items: center; color: #000000; text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.3s;">
        <i class="fas fa-tasks" style="font-size: 24px; margin-bottom: 5px;"></i>
        Tasks
    </a>
    <a href="/withdraw" style="display: flex; flex-direction: column; align-items: center; color: #000000; text-decoration: none; font-size: 14px; font-weight: 500; transition: color 0.3s;">
        <i class="fas fa-coins" style="font-size: 24px; margin-bottom: 5px;"></i>
        Wallet
    </a>
</nav>






<script>
  console.clear = function() {};
  console.error = function() {};
  console.warn = function() {};
</script>


</body>
</html>