<?php
// Include header and database connection
include("header.php");

$connect = new mysqli("localhost", "root", "", "hospital");

// Check the database connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Get the logged-in admin's username from the session
$admin_username = $_SESSION['account_branch'] ?? '';

// Fetch the hospital name and location from account_branch based on the logged-in admin
$branch_query = "SELECT hospital_name, hospital_location FROM account_branch WHERE username = ?";
$stmt = $connect->prepare($branch_query);
$stmt->bind_param("s", $admin_username);
$stmt->execute();
$branch_result = $stmt->get_result();

if ($branch_result->num_rows > 0) {
    $branch = $branch_result->fetch_assoc();
    $hospital_name = trim(strtolower($branch['hospital_name']));
    $hospital_location = trim(strtolower($branch['hospital_location']));

    // Fetch matching appointments from general_appointment
    $appointment_query = "
        SELECT name, email, phone, gender, doctor, date, time, date_reg 
        FROM general_appointment 
        WHERE LOWER(TRIM(hospital_name)) = ? 
        AND LOWER(TRIM(hospital_location)) = ?";
    
    $appointment_stmt = $connect->prepare($appointment_query);
    $appointment_stmt->bind_param("ss", $hospital_name, $hospital_location);
    $appointment_stmt->execute();
    $appointments = $appointment_stmt->get_result();
} else {
    $appointments = null;  // No matching branch details found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Appointments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            margin: 20px;
            width: 90%;
            text-align: center;
        }
        .table {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 50px;
        }
        th {
            background-color: #78AEC6;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container table-container">
        <h3 class="text-center my-4">Hospital Appointments</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($appointments && $appointments->num_rows > 0) {
                    while ($row = $appointments->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['phone']) . "</td>
                                <td>" . htmlspecialchars($row['gender']) . "</td>
                                <td>" . htmlspecialchars($row['doctor']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['time']) . "</td>
                                <td>" . htmlspecialchars($row['date_reg']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No appointments found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<div class="sidenav" style="margin-top: -250px; margin-left: -5px;">
            <?php include("sidenav.php"); ?>
        </div>
</body>
</html>

<?php
// Close the database connection
$connect->close();
?>
