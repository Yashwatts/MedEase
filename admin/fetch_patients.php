<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = 'localhost'; // Adjust if needed
$user = 'root';
$password = '';
$dbname = 'hospital'; // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch patient details
$sql = "SELECT full_name, hospital_name, age, gender, phone_no FROM hospital_bookings";
$result = $conn->query($sql);

// Check if query ran successfully
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare the data to send as JSON
$patients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
} else {
    echo json_encode([]); // Send empty array if no data found
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($patients);

// Close the connection
$conn->close();
?>
