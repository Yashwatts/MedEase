<?php
$connect = new mysqli('localhost', 'root', '', 'live_chat');

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch messages from the messages table
$query = "SELECT * FROM messages ORDER BY created_at ASC";
$result = $connect->query($query);

if (!$result) {
    die("Query failed: " . $connect->error);
}

while ($row = $result->fetch_assoc()) {
    // Get the sender
    $sender = $row['sender'];

    // Fetch the full name from the admin or manager table in the hospital database
    $adminQuery = "SELECT full_name FROM hospital.admin WHERE username = '$sender'";
    $adminResult = $connect->query($adminQuery);

    if (!$adminResult) {
        die("Admin query failed: " . $connect->error);
    }

    // Fetch the full name if available
    $fullName = $sender; // Fallback to sender username if not found
    if ($adminRow = $adminResult->fetch_assoc()) {
        $fullName = $adminRow['full_name'];
    }

    // Class determination based on sender
    $class = ($sender === 'admin' || $sender === 'manager') ? 'sent' : 'received';
    $icon = ($sender === 'user') ? '<i class="fa-solid fa-circle" style="color:#07fe03; font-size: 8px;"></i>' : '';

    echo "<div class='message $class'>
            <div class='sender'>{$fullName} $icon</div>
            <div>{$row['message']}</div>
          </div>";
}

?>
