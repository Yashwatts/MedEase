<?php
include "header.php";

// Check if the session variable is set
if (!isset($_SESSION['doctor'])) {
    die("You are not logged in as a doctor.");
}

$doctor_name = $_SESSION['doctor']; // Use the correct session key

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch patients with completed payments from the appointment table
$completed_patients_query = "
    SELECT name, email, phone, gender, department, date, time, date_reg 
    FROM appointment 
    WHERE doctor = '$doctor_name' 
    AND payment_status = 'Completed'";

$completed_patients_result = $conn->query($completed_patients_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patients with Completed Payments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }

        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }

        th {
            background-color: #78AEC6;
            color: white;
            padding: 15px;
        }

        td {
            padding: 15px;
        }
        .sidenav {
            height: 100%;
            position: fixed;
            width: 250px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidenav">
    <?php include("sidenav.php"); ?>
</div>
<div class="container mt-4">
    <h2 style="text-align: center;">Patients Details</h2>
    <div class="table-responsive mt-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Date Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display patients with completed payments
                if ($completed_patients_result->num_rows > 0) {
                    while ($patient_row = $completed_patients_result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($patient_row['name']) . "</td>
                            <td>" . htmlspecialchars($patient_row['email']) . "</td>
                            <td>" . htmlspecialchars($patient_row['phone']) . "</td>
                            <td>" . htmlspecialchars($patient_row['gender']) . "</td>
                            <td>" . htmlspecialchars($patient_row['department']) . "</td>
                            <td>" . htmlspecialchars($patient_row['date']) . "</td>
                            <td>" . htmlspecialchars($patient_row['time']) . "</td>
                            <td>" . htmlspecialchars($patient_row['date_reg']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No completed payment records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
