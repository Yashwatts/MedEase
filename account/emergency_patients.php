<?php
// connection.php
include 'header.php';
$servername = "localhost"; // Change as needed
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "hospital"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the admin's hospital location from the admin table
$admin_username = $_SESSION['account_branch'] ?? ''; // Adjust this to match how you're storing the admin's session data

if (empty($admin_username)) {
    die("Error: Admin username is not set in the session.");
}

// Fetch hospital location for the admin
$location_sql = "SELECT hospital_location FROM account_branch WHERE username = ?";
$location_stmt = $conn->prepare($location_sql);

if ($location_stmt === false) {
    die("MySQL prepare failed: " . $conn->error);
}

// Bind and execute
$location_stmt->bind_param("s", $admin_username);
$location_stmt->execute();
$location_result = $location_stmt->get_result();

if ($location_result->num_rows > 0) {
    $location_row = $location_result->fetch_assoc();
    $admin_hospital_location = $location_row['hospital_location'];
} else {
    die("Error: Admin hospital location not found.");
}

// Fetch data from the hospital_bookings table for the specific location
$sql = "SELECT full_name, age, gender, phone_no, hospital_name, booking_type, status 
        FROM hospital_bookings 
        WHERE LOWER(location) = LOWER(?)"; // Case-insensitive query
$stmt = $conn->prepare($sql);

// Check if prepare() was successful
if ($stmt === false) {
    die("MySQL prepare failed: " . $conn->error);
}

// Bind and execute
$stmt->bind_param("s", $admin_hospital_location);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Patients</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 60rem;
            border-collapse: collapse;
            margin-top: 50px;
            margin-left: 20rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #78AEC6;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<h1 style="text-align: center; margin-top: 5px">Emergency Patients</h1>

<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Phone Number</th>
            <th>Hospital Name</th>
            <th>Booking Type</th>
            <th>Status</th> <!-- New Status column -->
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['booking_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td> <!-- Display Status -->
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No bookings found for this location.</td> <!-- Adjusted colspan -->
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Close the statements and database connection
$stmt->close();
$location_stmt->close();
$conn->close();
?>

<div class="sidenav" style="margin-top: -227px; height: 50rem; margin-left: -2px;">
    <?php include("sidenav.php"); ?>
</div>
</body>
</html>
