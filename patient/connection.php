<?php
// Database connection credentials
$servername = "localhost";
$username = "root";   // Your MySQL username
$password = "";       // Your MySQL password
$dbname = "hospital"; // The name of your database

// Create connection
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
