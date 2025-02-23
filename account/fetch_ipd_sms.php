<?php
$connect = new mysqli('localhost', 'root', '', 'live_chat');

$query = "SELECT * FROM messages ORDER BY created_at ASC";
$result = $connect->query($query);

while ($row = $result->fetch_assoc()) {
    $class = ($row['sender'] === 'admin') ? 'sent' : 'received'; // Use 'sent' for admin (manager) and 'received' for user
    $icon = ($row['sender'] === 'user') ? '<i class="fa-solid fa-circle" style="color:#07fe03; font-size: 8px;"></i>' : '';

    echo "<div class='message $class'>
            <div class='sender'>{$row['sender']} $icon</div>
            <div>{$row['message']}</div>
          </div>";
}
?>
