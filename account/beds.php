<?php
// beds_details.php
include 'header.php';
include 'connection.php';

// Check if the admin is logged in (you can use session or any other method to store admin's username)
$admin_username = $_SESSION['account_branch'] ?? '';  // Assuming admin username is stored in session

// Fetch the hospital_name and hospital_location of the logged-in admin
$admin_query = "SELECT hospital_name, hospital_location FROM account_branch WHERE username = '$admin_username'";
$admin_result = mysqli_query($connect, $admin_query);

if ($admin_result && mysqli_num_rows($admin_result) > 0) {
    $admin_data = mysqli_fetch_assoc($admin_result);
    $hospital_name = $admin_data['hospital_name'];
    $hospital_location = $admin_data['hospital_location'];

    // Fetch beds that match the admin's hospital and location
    $query = "SELECT bed_type, total_beds, available_beds 
              FROM beds 
              WHERE hospital_name = '$hospital_name' AND hospital_location = '$hospital_location'";
    $result = mysqli_query($connect, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connect));
    }
} else {
    die("Admin not found or not logged in.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #78AEC6;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bed Details</h2>

    <table>
        <thead>
            <tr>
                <th>Bed Type</th>
                <th>Total Beds</th>
                <th>Available Beds</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['bed_type'] . "</td>";
                    echo "<td>" . $row['total_beds'] . "</td>";
                    echo "<td>" . $row['available_beds'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No beds found for your branch.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="sidenav" style="margin-top: -347px; margin-left: -2px;">
    <?php include("sidenav.php"); ?>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
