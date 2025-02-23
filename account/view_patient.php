<?php
session_start(); // Ensure session is started
include("connection.php");

// Check if an ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $patientId = $_GET['id'];

    // Prepare SQL query to prevent SQL injection
    $stmt = $connect->prepare("SELECT * FROM patient WHERE id = ?");
    
    if ($stmt === false) {
        die("Failed to prepare the SQL statement: " . $connect->error);
    }

    // Bind parameters
    $stmt->bind_param("i", $patientId);

    // Execute query
    if (!$stmt->execute()) {
        die("Failed to execute the SQL statement: " . $stmt->error);
    }

    // Fetch result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
        // Display patient details

        echo "<p>Name: " . htmlspecialchars($patient['full_name']) . "</p>";
        echo "<p>Age: " . htmlspecialchars($patient['age']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($patient['email']) . "</p>";
        echo "<p>Phone: " . htmlspecialchars($patient['phone']) . "</p>";
    } else {
        echo "No patient found with the given ID.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "No patient ID provided.";
}

// Close connection
$connect->close();
?>
