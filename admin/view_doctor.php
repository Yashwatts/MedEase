<?php
$servername="localhost";
$username="root";
$password="";  // Fixed the typo: passwrod -> password
$dbname="hospital";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Use the correct database connection variable ($conn)
    $query = "SELECT * FROM doctors WHERE id='$id'";
    $res = mysqli_query($conn, $query);

    // Fetch the row as an associative array
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
    } else {
        echo "No doctor found with the specified ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Details</title>
</head>
<body>
    <div class="container">
        <?php if (isset($row)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Doctor Name</td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td><?php echo htmlspecialchars($row['country']); ?></td>
                </tr>
                <tr>
                    <td>Salary</td>
                    <td><?php echo htmlspecialchars($row['salary']); ?></td>
                </tr> 
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
