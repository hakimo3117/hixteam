<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 400px;
            text-align: center;
            padding: 20px 10px;
            margin: 10px 0;
        }

        .task-list {
            width: 100%;
        }

        .task-card {
            background-color: #ffffff; /* White background */
            padding: 15px;
            border: 1px solid #000000; /* Black border */
            border-radius: 10px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .task-card:hover {
            transform: scale(1.02);
        }

        .task-card.completed {
            background-color: #f0f0f0; /* Light gray for completed */
            border: 2px solid #1abc9c; /* Green border */
            opacity: 0.8;
            text-decoration: line-through;
        }

        button {
            background-color: #000000; /* Black button */
            color: #ffffff; /* White text */
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        button:hover {
            background-color: #333333; /* Slightly darker hover effect */
        }

        .icon-check {
            font-size: 20px;
            color: #1abc9c; /* Green checkmark */
            display: none;
        }

        .icon-check.completed {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="greeting">Complete Your Tasks</h1>
        <div class="task-list" id="task-list">
            <div class="task-card" data-task-id="1" data-channel="@AIEPARTY">
                <p>Join AIEPARTY</p>
                <button class="join-channel" data-channel="@AIEPARTY">Join Channel</button>
                <button class="check-task" data-channel="@AIEPARTY" style="display: none;">Check Membership</button>
                <i class="fas fa-check icon-check"></i>
            </div>
            <!-- Add more tasks here -->
            <div class="task-card" data-task-id="1" data-channel="@AIEPARTY">
                <p>Join ProWebLab</p>
                <button class="join-channel" data-channel="ProWebLab">Join Channel</button>
                <button class="check-task" data-channel="@ProWebLab" style="display: none;">Check Membership</button>
                <i class="fas fa-check icon-check"></i>
            </div>
        </div>
        <p id="result-message"></p>
    </div>

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

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
    Telegram.WebApp.ready(); // Initialize Telegram Web App

    const user = Telegram.WebApp.initDataUnsafe.user;
    const userId = user.id;

    // Function to load tasks and mark completed ones
    function loadTasks() {
        fetch('/check_membership.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'load_tasks',
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            const taskListElement = document.getElementById('task-list');
            taskListElement.innerHTML = ''; // Clear task list

            if (data.incompleteTasks && data.incompleteTasks.length > 0) {
                data.incompleteTasks.forEach(task => {
                    // Dynamically create task cards for incomplete tasks
                    const taskCard = document.createElement('div');
                    taskCard.classList.add('task-card');
                    taskCard.dataset.taskId = task.task_id;

                    taskCard.innerHTML = `
                        <p>${task.task_description}</p>
                        <button class="join-channel" data-channel="${task.channel_username}">Join Channel</button>
                        <button class="check-task" data-channel="${task.channel_username}" style="display: none;">Check Membership</button>
                        <i class="fas fa-check icon-check"></i>
                    `;

                    taskListElement.appendChild(taskCard);

                    // Add event listeners for buttons
                    addEventListeners(taskCard);
                });
            } else {
                document.getElementById('result-message').innerText = "🎉 All tasks completed!";
            }
        })
        .catch(error => {
            console.error('Error loading tasks:', error);
        });
    }

    function addEventListeners(taskCard) {
        const joinButton = taskCard.querySelector('.join-channel');
        const checkButton = taskCard.querySelector('.check-task');

        if (joinButton) {
            joinButton.addEventListener('click', function () {
                const channelUsername = this.dataset.channel;
                Telegram.WebApp.openTelegramLink(`https://t.me/${channelUsername.replace('@', '')}`);
                checkButton.style.display = 'inline-block';
            });
        }

        if (checkButton) {
            checkButton.addEventListener('click', function () {
                const taskId = taskCard.dataset.taskId;
                const channelUsername = this.dataset.channel;

                fetch('/check_membership.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'check_membership',
                        user_id: userId,
                        task_id: taskId,
                        channel_username: channelUsername
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.isMember) {
                        taskCard.remove(); // Remove completed task from the list

                        // Check if all tasks are completed
                        if (data.allTasksCompleted) {
                            document.getElementById('result-message').innerText = "🎉 All tasks completed!";
                            document.getElementById('task-list').innerHTML = ''; // Clear task list
                        }
                    } else {
                        document.getElementById('result-message').innerText = `You are not a member of ${channelUsername}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    }

    // Load completed tasks when the page loads
    loadTasks();

    // Join the channel when the "Join Channel" button is clicked
    document.querySelectorAll('.join-channel').forEach(button => {
        button.addEventListener('click', function () {
            const channelUsername = this.dataset.channel;

            // Open Telegram link to join the channel
            Telegram.WebApp.openTelegramLink(`https://t.me/${channelUsername.replace('@', '')}`);

            // Show the "Check Membership" button after the user clicks to join the channel
            const checkButton = this.nextElementSibling; // Get the "Check Membership" button
            checkButton.style.display = 'inline-block';
        });
    });

    // Check membership when the "Check Membership" button is clicked
    document.querySelectorAll('.check-task').forEach(button => {
        button.addEventListener('click', function () {
            const taskId = this.closest('.task-card').dataset.taskId;
            const channelUsername = this.dataset.channel;

            fetch('/check_membership.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'check_membership',
                    user_id: userId,
                    task_id: taskId,
                    channel_username: channelUsername
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.isMember) {
                    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
                    taskCard.classList.add('completed');
                    taskCard.querySelector('.icon-check').classList.add('completed');
                    button.style.display = 'none'; // Hide the check membership button after completion

                    // Check if all tasks are completed
                    if (data.allTasksCompleted) {
                        document.getElementById('result-message').innerText = "🎉 All tasks completed!";
                        document.getElementById('task-list').innerHTML = ''; // Clear task list
                    }
                } else {
                    document.getElementById('result-message').innerText = `You are not a member of ${channelUsername}`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
</body>
</html>

    
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

</body>
</html>
