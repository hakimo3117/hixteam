<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect Your TON Wallet</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://unpkg.com/@tonconnect/ui@latest/dist/tonconnect-ui.min.js"></script>

    <style>
        body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    background: linear-gradient(180deg, #8e77ff, #240047);
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center vertically */
    align-items: center; /* Center horizontally */
    height: 100vh;
    color: #ecf0f1;
    overflow: hidden;
}

.container {
    background-color: rgba(255, 255, 255, .1);
    backdrop-filter: blur(10px);
    padding: 20px; /* Reduced padding */
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, .3);
    text-align: center;
    max-width: 300px; /* Smaller max width */
    width: 80%; /* Adjusted width */
    margin: 0 auto; /* Centered container */
    overflow: hidden; /* Hides overflow */
}
        .navbar {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background: rgba(255, 255, 255, .2);
            border-radius: 20px;
            padding: 10px 0;
            width: 100%;
            max-width: 400px;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ecf0f1;
            text-decoration: none;
            transition: color .3s;
        }
        .nav-item:hover {
            color: #1abc9c;
        }
        .nav-item img {
            width: 24px;
            height: 24px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Connect Your TON Wallet</h1>

        <!-- Button for TON Connect UI -->
<div id="ton-connect" style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px;"></div>
<div id="disconnect-wallet" style="display: none; text-align: center; background-color: #007bff; color: #ffffff; padding: 10px 20px; border-radius: 30px; cursor: pointer;">Disconnect Wallet</div>

    </div>

<script>
    // Check if the app is opened via Telegram
    function isTelegramWebApp() {
        return (typeof window.Telegram !== 'undefined' && window.Telegram.WebApp);
    }

    // Redirect if not opened via Telegram
    if (!isTelegramWebApp()) {
        document.body.innerHTML = '<h2 style="color: #ecf0f1;">Please open this web app through Telegram.</h2>';
        setTimeout(() => {
            window.location.href = 'https://telegram.me/DropAlertsBot'; // Replace with your bot's link
        }, 3000); // Redirect after 3 seconds
    } else {
        window.Telegram.WebApp.ready();

        // Initialize the TON Connect UI
        const tonConnectUI = new TON_CONNECT_UI.TonConnectUI({
            manifestUrl: 'https://x-1.me/manifest.json', // Replace with your actual manifest URL
            buttonRootId: 'ton-connect'
        });

        // Function to update UI with connected wallet
        function updateUIWithConnectedWallet(wallet) {
            document.getElementById('wallet-info').innerHTML = `<h2>Connected Wallet Address: ${wallet.account.address}</h2>`;
            document.getElementById('disconnect-wallet').style.display = 'block'; // Show the disconnect button
        }

        // Connect to wallet using openModal()
        async function connectToWallet() {
            try {
                await tonConnectUI.connectWallet(); // Open the connection modal
                
                const connectedWallet = tonConnectUI.wallet; // Get the connected wallet
                if (connectedWallet) {
                    console.log('Wallet connected:', connectedWallet);
                    updateUIWithConnectedWallet(connectedWallet); // Update UI with the connected wallet
                    sendWalletToServer(connectedWallet); // Send connected wallet to backend
                }
            } catch (error) {
                console.error("Error connecting to wallet:", error);
                document.getElementById('wallet-info').innerHTML = `<h2 style="color: red;">${error.message}</h2>`;
            }
        }

        // Disconnect from the wallet
        async function disconnectWallet() {
            try {
                await tonConnectUI.disconnect(); // Disconnect from wallet
                console.log('Wallet disconnected');
                document.getElementById('wallet-info').innerHTML = '<h2>Your wallet has been disconnected.</h2>';
                document.getElementById('disconnect-wallet').style.display = 'none'; // Hide the disconnect button
            } catch (error) {
                console.error("Error disconnecting from wallet:", error);
                document.getElementById('wallet-info').innerHTML = '<h2 style="color: red;">Error disconnecting. Please try again.</h2>';
            }
        }

        // Send the connected wallet to the server
        async function sendWalletToServer(wallet) {
            try {
                const response = await fetch('save-wallet.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ wallet: wallet.account.address })
                });

                if (response.ok) {
                    console.log('Wallet address stored successfully.');
                } else {
                    console.error('Failed to store wallet address.');
                }
            } catch (error) {
                console.error('Error storing wallet address:', error);
            }
        }

        // Check if a wallet is already connected on page load
        (async () => {
            const connectedWallet = tonConnectUI.wallet; // Get the connected wallet on load
            if (connectedWallet) {
                updateUIWithConnectedWallet(connectedWallet); // Show the connected wallet info
            }
        })();

        // Call connectToWallet when the button is clicked
        document.getElementById('ton-connect').onclick = async () => {
            const currentWallet = tonConnectUI.wallet; // Get the connected wallet
            if (currentWallet) {
                updateUIWithConnectedWallet(currentWallet);
            } else {
                await connectToWallet(); // Proceed to connect if not connected
            }
        };

        // Call disconnectWallet when the disconnect button is clicked
        document.getElementById('disconnect-wallet').onclick = disconnectWallet;
    }
</script>

<script>
  document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
  }, false);
</script>

<script>
  document.addEventListener("keydown", function (e) {
    // Disable F12 key
    if (e.key === "F12") {
      e.preventDefault();
    }
    // Disable Ctrl+Shift+I (Chrome DevTools shortcut)
    if (e.ctrlKey && e.shiftKey && e.key === "I") {
      e.preventDefault();
    }
    // Disable Ctrl+Shift+J (Console shortcut)
    if (e.ctrlKey && e.shiftKey && e.key === "J") {
      e.preventDefault();
    }
    // Disable Ctrl+U (View page source shortcut)
    if (e.ctrlKey && e.key === "u") {
      e.preventDefault();
    }
  });
</script>

<script>
  console.clear = function() {};
  console.error = function() {};
  console.warn = function() {};
</script>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="/" class="nav-item">
            <img src="https://cdn-icons-png.flaticon.com/512/9073/9073032.png" alt="Home Icon"> <!-- Home Icon -->
            Home
        </a>
        <a href="/user/wallet.php" class="nav-item">
            <img src="https://cdn-icons-png.flaticon.com/512/10527/10527630.png" alt="Wallet Icon"> <!-- Wallet Icon -->
            Wallet
        </a>
        <a href="/user/task.php" class="nav-item">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828833.png" alt="Tasks Icon"> <!-- Tasks Icon -->
            Tasks
        </a>
        <a href="/user/invite.php" class="nav-item">
            <img src="https://cdn-icons-png.flaticon.com/512/1828/1828844.png" alt="Invite Icon"> <!-- Invite Icon -->
            Invite
        </a>
    </nav>
</body>
</html>
