<?php
session_start();
$sender_id = $_SESSION['admin']; // Currently logged-in admin
$receiver_id = $_GET['receiver_id']; // Receiver admin ID

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages between the current admin and the selected admin
$query = "SELECT sender_id, message, created_at 
          FROM messages 
          WHERE (sender_id = ? AND receiver_id = ?) 
             OR (sender_id = ? AND receiver_id = ?) 
          ORDER BY created_at ASC";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}
$stmt->bind_param('iiii', $sender_id, $receiver_id, $receiver_id, $sender_id);

$stmt->execute();
$result = $stmt->get_result();

// Display messages
while ($row = $result->fetch_assoc()) {
    $align = ($row['sender_id'] == $sender_id) ? 'right' : 'left';
    echo "<div style='text-align: $align;'>{$row['message']} <br><small>{$row['created_at']}</small></div>";
}

$stmt->close();
$conn->close();
?>
