<?php
$conn = new mysqli('localhost', 'root', '', 'hospital');
$connect = new mysqli('localhost', 'root', '', 'live_chat');

$message = $_POST['message'];
$sender = $_POST['sender'];

$query = "INSERT INTO messages (message, sender) VALUES ('$message', '$sender')";
$connect->query($query);
?>
