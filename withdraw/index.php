<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="<?php echo '/js/WD9ou5m.min.js'; ?>"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        h1 {
            text-align: center;
            color: #000000;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        #content {
            width: 90%;
            max-width: 400px;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #000000;
            border-radius: 10px;
            text-align: center;
        }
        .notice {
            background-color: #ffffcc;
            color: #cc0000;
            font-weight: bold;
            padding: 10px;
            border: 2px solid #cc0000;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }
        input, select {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #000000;
            border-radius: 6px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="number"] {
            appearance: textfield;
        }
        button {
            padding: 12px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #333333;
        }
        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
        }
        .success-message {
            color: #2d7a31;
            text-align: center;
            font-weight: bold;
        }
        .back-button {
            margin: 20px auto;
            padding: 10px 20px;
            background: #000000;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            transition: background 0.3s ease;
        }
        .back-button:hover {
            background: #333333;
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="/" class="back-button">← Back</a>

    <h1>Withdraw</h1>
    <div id="content"></div>
</body>
</html>