<?php
include 'header.php';
// Database connection settings
$host = 'localhost'; // Database host
$db_name = 'hospital'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

// Create a connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$loggedInAdminUsername = $_SESSION['admin'];

// Fetch the logged-in admin's hospital location
$hospitalLocationQuery = "SELECT hospital_location FROM admin WHERE username = ?";
$stmt = $conn->prepare($hospitalLocationQuery);
$stmt->bind_param("s", $loggedInAdminUsername);
$stmt->execute();
$stmt->bind_result($hospitalLocation);
$stmt->fetch();
$stmt->close();

// Fetch all admins based on hospital location and their corresponding bed availability
if ($hospitalLocation) {
    $query = "SELECT a.full_name, a.email, a.phone, a.hospital_name, a.hospital_location, 
                     b.bed_type, b.total_beds, b.available_beds 
              FROM admin a 
              LEFT JOIN beds b ON a.hospital_name = b.hospital_name AND a.hospital_location = b.hospital_location 
              WHERE a.hospital_location = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $hospitalLocation);
} else {
    $query = "SELECT a.full_name, a.email, a.phone, a.hospital_name, a.hospital_location, 
                     b.bed_type, b.total_beds, b.available_beds 
              FROM admin a 
              LEFT JOIN beds b ON a.hospital_name = b.hospital_name AND a.hospital_location = b.hospital_location";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 1100px;
            border-collapse: collapse;
            margin-left: 300px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Admin Details</h1>

    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Hospital Name</th>
                <th>Hospital Location</th>
                <th>Bed Type</th>
                <th>Total Beds</th>
                <th>Available Beds</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['full_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['hospital_name']}</td>
                            <td>{$row['hospital_location']}</td>
                            <td>{$row['bed_type']}</td>
                            <td>{$row['total_beds']}</td>
                            <td>{$row['available_beds']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No admins found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="sidenav" style="margin-top: -256px; height: 50rem; margin-left: -2px;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close(); // Close the database connection
?>
