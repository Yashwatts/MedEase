<?php
include('header.php');

// Database connections
$hospital_conn = new mysqli('localhost', 'root', '', 'hospital');
$chat_conn = new mysqli('localhost', 'root', '', 'live_chat');

// Fetch the manager's full name from the 'account_branch' table
$manager_query = "SELECT full_name FROM account_branch LIMIT 1";
$manager_result = $hospital_conn->query($manager_query);
$manager_name = ($manager_result->num_rows > 0) ? $manager_result->fetch_assoc()['full_name'] : 'Manager';

if (isset($_POST['delete'])) {
    $query = "DELETE FROM messages";
    $chat_conn->query($query);
    echo json_encode(["status" => "success"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Chat System</title>
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
            height: 60px;
            background-color: #303030;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 10px;
            font-weight: bold;
            justify-content: space-between;
            border-radius: 10px 10px 0 0;
        }
        .manager-name {
            font-size: 18px;
        }
        .delete-button {
            cursor: pointer;
            color: red;
            font-size: 18px;
        }
        .messages {
            flex: 1;
            padding: 10px;
            overflow: auto;
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
        }
        .sent {
        align-self: flex-start; /* Align sent messages to the right */
        background-color: #007bff; /* Color for sent messages */
        color: white; /* Text color for sent messages */
    }
    .received {
        align-self: flex-end; /* Align received messages to the left */
        background-color: #f0f0f0; /* Color for received messages */
        color: black; /* Text color for received messages */
    }
        .user {
            align-self: flex-end;
            background-color: #f0f0f0;
            color: black;
            border: 2px solid #ccc;
        }
        .sender {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
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
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 0 0 10px 0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-box">
        <div class="chat-header">
            <span class="manager-name">Hey, <?= htmlspecialchars($manager_name) ?>!</span>
            <button class="delete-button" onclick="deleteMessages()">
                <i class="fas fa-trash" style="color: #c93520;" title="Clear chat"></i>
            </button>
        </div>
        <div class="messages" id="messages">
            <!-- Messages will be loaded here -->
        </div>
        <div class="message-input">
            <input type="text" id="message" placeholder="Type your message..." autocomplete="off">
            <button onclick="sendMessage('<?= $manager_name ?>')" style="background-color: black;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <div class="sidenav" style="margin-top: -2px;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>

<script>
    function fetchMessages() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_ipd_sms.php", true);
        xhr.onload = function () {
            if (this.status === 200) {
                document.getElementById("messages").innerHTML = this.responseText;
                const messagesDiv = document.getElementById("messages");
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        };
        xhr.send();
    }

    setInterval(fetchMessages, 2000); // Fetch new messages every 2 seconds

    function sendMessage(managerName) {
        const message = document.getElementById("message").value;
        if (message.trim() === "") return;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "send_message.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.status === 200) {
                document.getElementById("message").value = ''; // Clear input
                fetchMessages();
            }
        };
        xhr.send(`message=${encodeURIComponent(message)}&sender=${encodeURIComponent(managerName)}`);
    }

    function deleteMessages() {
        if (confirm("Are you sure you want to delete all messages?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById("messages").innerHTML = ''; // Clear messages
                }
            };
            xhr.send("delete=1");
        }
    }

    document.getElementById("message").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            sendMessage('<?= $manager_name ?>');
        }
    });

    fetchMessages(); // Initial fetch on page load
</script>
