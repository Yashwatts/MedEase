<?php
session_start(); // Ensure this is at the top of the file

// Debugging: Check if session variable 'username' is set
if (!isset($_SESSION['patient'])) {
    // Print session data for debugging
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    die('User not logged in.');
}

$patient_username = $_SESSION['patient']; // Get the logged-in username

// Ensure POST data is set
if (!isset($_POST['title']) || !isset($_POST['message'])) {
    die('Required fields are missing.');
}

$title = $_POST['title'];
$message = $_POST['message'];

include 'connection.php'; // Ensure you have the correct database connection file

// Prepare the query to insert complaint
$query = "INSERT INTO complaint (title, message, patient_username) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($connect, $query);

if ($stmt === false) {
    die('Prepare failed: ' . mysqli_error($connect));
}

// Bind parameters and execute the statement
mysqli_stmt_bind_param($stmt, 'sss', $title, $message, $patient_username);

if (mysqli_stmt_execute($stmt)) {
    echo "Complaint submitted successfully.";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
