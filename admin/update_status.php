<?php
include 'connection.php'; // Ensure this file connects to your database

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);

    // Update query
    $sql = "UPDATE complaint SET status='$status' WHERE id=$id";
    if (mysqli_query($connect, $sql)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($connect);
    }

    // Close connection
    mysqli_close($connect);
}
?>
