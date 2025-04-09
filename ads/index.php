<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Ads</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src='//whephiwums.com/sdk.js' data-zone='9191675' data-sdk='show_9191675'></script> <!-- Ad system -->
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

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #000000; /* Black text */
        }

        #timer {
            margin: 20px 0;
            font-size: 18px;
            color: #757575; /* Gray text */
        }

        button {
            background-color: #000000; /* Black background */
            color: #ffffff; /* White text */
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 5px;
            width: 200px;
        }

        button:hover {
            background-color: #333333; /* Darker black on hover */
        }

        #message {
            margin-top: 20px;
            font-size: 16px;
            color: #ff3d3d; /* Red for errors/messages */
        }
        

    .stats {
        font-size: 18px;
        margin: 5px 0;
    }

    .progress-container {
        background: #eaeaea;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
    }

    .progress-bar {
        height: 20px;
        background: #4caf50;
        width: 0%;
        text-align: center;
        line-height: 20px;
        color: #fff;
        font-size: 14px;
    }
    </style>
</head>
<body>
    <h1>Ads Center</h1>
    <div id="timer"></div>
    <button id="watch-ads">Watch Ads</button>
    <button id="cancel">Back</button>
    <div id="message"></div>

<!-- Stats Card -->
<div id="stats-card">
    <div class="stats">Total Ads: <span id="total-ads">100</span></div>
    <div class="stats">Ads Watched: <span id="ads-watched">0</span></div>
    <div class="stats">Rewards Earned: <span id="rewards-earned">0</span></div>
    <div class="progress-container">
        <div class="progress-bar" id="progress-bar"></div>
    </div>
</div>


    <script>
        Telegram.WebApp.ready(); // Initialize Telegram WebApp
        const watchAdsButton = document.getElementById('watch-ads');
        const cancelButton = document.getElementById('cancel');
        const messageEl = document.getElementById('message');
        const user_id = Telegram.WebApp.initDataUnsafe.user.id;

        function loadAd() {
            show_8489663()
                .then(() => {
                    claimReward(); // Automatically claim reward after ad is viewed
                })
                .catch(err => {
                    console.error('Error loading ad:', err);
                    messageEl.textContent = 'Error loading ad.';
                });
        }

        function claimReward() {
            fetch('adreward.php', {
                method: 'POST',
                body: JSON.stringify({ user_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageEl.textContent = 'Reward added!';
                } else {
                    messageEl.textContent = data.message || 'Failed to claim reward.';
                }
            })
            .catch(error => {
                messageEl.textContent = 'Error claiming reward.';
                console.error('Error:', error);
            });
        }

        watchAdsButton.addEventListener('click', () => {
            messageEl.textContent = ''; // Clear any previous message
            loadAd();
        });

        cancelButton.addEventListener('click', () => {
            window.location.href = '/'; // Go to homepage
        });
        
        // Stats elements
const totalAdsEl = document.getElementById('total-ads');
const adsWatchedEl = document.getElementById('ads-watched');
const rewardsEarnedEl = document.getElementById('rewards-earned');
const progressBarEl = document.getElementById('progress-bar');

const totalAds = 100; // Total ads available
totalAdsEl.textContent = totalAds;

// Update stats dynamically
function updateStats() {
    fetch(`checkAds.php?user_id=${user_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const adsWatched = data.ads_watched || 0;
                const rewardsEarned = adsWatched * 10; // Assume 10 units reward per ad

                adsWatchedEl.textContent = adsWatched;
                rewardsEarnedEl.textContent = rewardsEarned;

                const progressPercent = Math.min((adsWatched / totalAds) * 100, 100);
                progressBarEl.style.width = `${progressPercent}%`;
                progressBarEl.textContent = `${Math.round(progressPercent)}%`;
            } else {
                messageEl.textContent = data.message || 'Failed to load stats.';
            }
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            messageEl.textContent = 'Error loading stats.';
        });
}

// Call this function initially to load stats on page load
updateStats();

    </script>
</body>
</html>