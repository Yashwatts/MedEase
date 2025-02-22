<?php
session_start(); // Ensure you start the session to access session variables
$conn = new mysqli('localhost', 'root', '', 'hospital');
$connect = new mysqli('localhost', 'root', '', 'live_chat');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Assuming you have the admin's username stored in session
$adminUsername = $_SESSION['admin']; // Adjust based on your session variable

// Fetch the full name from the admin table
$query = "SELECT full_name FROM admin WHERE username = '$adminUsername'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$fullName = $row['full_name'];

$message = $_POST['message'];
$query = "INSERT INTO messages (message, sender) VALUES ('$message', '$fullName')";
$connect->query($query);
?>
