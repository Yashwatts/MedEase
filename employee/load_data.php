<?php
include("connection.php");

if (isset($_POST['refNumber'])) {
    $refNumber = mysqli_real_escape_string($connect, $_POST['refNumber']); // Sanitize input

    // Query to fetch the patient details
    $query = "SELECT full_name, age, gender FROM patient WHERE refNumber = '$refNumber'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row); // Return the patient data as JSON
    } else {
        echo json_encode(['error' => 'Invalid UHID']); // Return an error if no match found
    }
}

mysqli_close($connect); // Close the database connection
?>
