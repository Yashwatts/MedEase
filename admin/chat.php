<?php
include('header.php');
if (!isset($_SESSION['admin']) || !isset($_SESSION['full_name'])) {
    header("Location: ../login/login.php"); // Redirect if not logged in
    exit();
}

$adminName = $_SESSION['full_name'];


$connect = new mysqli('localhost', 'root', '', 'live_chat');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_POST['delete'])) {
    $query = "DELETE FROM messages";
    $connect->query($query);
    echo json_encode(["status" => "success"]);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .chat-box {
            width: 1200px;
            height: 620px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgb(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: absolute;
            left: 55%;
            top: 10%;
            transform: translateX(-50%);
        }
        .chat-header {
            height: 40px;
            background-color: #303030;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 10px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            justify-content: space-between;
        }
        .messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .message {
        margin-bottom: 10px;
        max-width: 70%;
        padding: 10px;
        border-radius: 10px;
        overflow-wrap: break-word;
        display: flex; /* Make it a flex container */
        flex-direction: column; /* Allow sender to stack on top */
    }
    .user1 {
        align-self: flex-start;/* User's messages align left */
        background-color: #f0f0f0;
        color: #333;
        border: 2px solid #ccc;
    }
    .admin {
        align-self: flex-end; /* Admin's messages align right */
        background-color: green;
        color: #fff; /* Ensure readability */
    }
    .sender {
        align-self: flex-start;
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 12px;
        text-align: right; /* Align sender name to the right */
    }
        .message-input {
            display: flex;
            border-top: 1px solid #ccc;
        }
        .message-input input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 0 0 0 10px;
            outline: none;
        }
        .message-input button {
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 0 0 10px 0;
            cursor: pointer;
        }
        .delete-button {
            cursor: pointer;
            color: red;
            font-size: 18px;
        }
        #Welcome{
            margin-top: 5px;
        }
        #dot{
            margin-left: -150px;
        }

    </style>
</head>
<body>
    
    <div class="chat-box">
    <div class="chat-header">
        <h6 id="Welcome">Hey, <?php echo htmlspecialchars($adminName); ?>!</h6>
        <button class="delete-button" onclick="deleteMessages()">
            <i class="fas fa-trash" style="color: #c93520;" title="Clear chat"></i>
        </button>
    </div>
    <div class="messages" id="messages">
        <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['sender'] === 'admin' ? 'admin' : 'user1' ?>">
                <div class="sender"><?= htmlspecialchars($msg['sender']) ?></div>
                <?= htmlspecialchars($msg['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="message-input">
        <input type="text" id="message" placeholder="Type your message..." autocomplete="off">
        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>
<div class="sidenav" style="margin-top: -2px; height: 43rem;">
        <?php include("sidenav.php"); ?>
    </div>
    <script>
        function fetchMessages() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_messages.php", true);
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById("messages").innerHTML = this.responseText;
                    const messagesDiv = document.getElementById("messages");
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            };
            xhr.send();
        }

        function sendMessage() {
            const message = document.getElementById("message").value;
            if (message.trim() === "") return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById("message").value = '';
                    fetchMessages();
                }
            };
            xhr.send("message=" + encodeURIComponent(message) + "&sender=user");
        }

        function deleteMessages() {
            if (confirm("Are you sure you want to delete all messages?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (this.status === 200) {
                        document.getElementById("messages").innerHTML = '';
                    }
                };
                xhr.send("delete=1");
            }
        }

        // Auto-fetch messages every 2 seconds
        setInterval(fetchMessages, 2000);

        // Fetch messages on page load
        fetchMessages();

        // Send message on pressing Enter
        document.getElementById("message").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>
