<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Referral System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #E8F5FD; /* Light Telegram background */
            color: #1C1C1E; /* Dark text for better contrast */
            text-align: center;
            padding: 20px;
        }
        
        h1 {
            color: #0088CC; /* Telegram blue */
            font-size: 2.5em;
        }

        h2 {
            margin-top: 30px;
            color: #333;
        }

        #referrerInfo {
            font-size: 1.2em;
            margin: 20px 0;
            color: #555;
        }

        button {
            background-color: #0088CC; /* Telegram button color */
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0077B3; /* Darker shade on hover */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #fff;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px;
            font-size: 1.1em;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h1>Telegram Referral System</h1>
    <p id="referrerInfo"></p>
    <button onclick="inviteFriend()">🤝 Invite a Friend</button>
    <button onclick="copyInviteLink()">📋 Copy Invite Link</button> <!-- New Copy Link Button -->

    <h2>Your Referrals</h2>
    <ul id="referralList"></ul>
</body>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
        Telegram.WebApp.ready();

        const user = Telegram.WebApp.initDataUnsafe.user;
        const startapp = Telegram.WebApp.initDataUnsafe.start_param || '';

        async function saveReferral(userId, startapp) {
            try {
                const response = await fetch('referral.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userId, startapp })
                });
                const data = await response.json();
                console.log('Referral saved:', data);
            } catch (error) {
                console.error('Error saving referral:', error);
            }
        }

        async function fetchReferralData(userId) {
            try {
                const response = await fetch(`referral.php?userId=${userId}`);
                const data = await response.json();
                if (data.referrer) {
                    document.getElementById('referrerInfo').innerText = `Referred by user ID: ${data.referrer}`;
                }
                const referralList = document.getElementById('referralList');
                data.referrals.forEach(referral => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `User ID: ${referral}`;
                    referralList.appendChild(listItem);
                });
            } catch (error) {
                console.error('Error fetching referral data:', error);
            }
        }

        function inviteFriend() {
            const inviteLink = `https://t.me/DropAlertsBot/open?startapp=${user.id}`;
            Telegram.WebApp.openLink(inviteLink);
        }

        function copyInviteLink() {
            const inviteLink = `https://t.me/DropAlertsBot/open?startapp=${user.id}`;
            navigator.clipboard.writeText(inviteLink).then(() => {
                alert('Invite link copied to clipboard!');
            }).catch(error => {
                console.error('Error copying link:', error);
                alert('Failed to copy link.');
            });
        }

        if (startapp) {
            saveReferral(user.id, startapp);
        }

        fetchReferralData(user.id);
    </script>
</body>
</html>