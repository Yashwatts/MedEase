<?php
include("header.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for approving or denying appointments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $id = $_POST['id'];
        $sql = "UPDATE general_appointment SET status = 'Accepted' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("location: patient_registration.php");
            exit();
        } else {
            echo "Error Updating record: " . $conn->error;
        }
    } elseif (isset($_POST['deny'])) {
        $id = $_POST['id'];
        $sql = "UPDATE general_appointment SET status = 'Denied' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("location: general_appointment.php");
            exit();
        } else {
            echo "Error Updating record: " . $conn->error;
        }
    }
}

$search_phone = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_phone'])) {
    $search_phone = $conn->real_escape_string($_POST['search_phone']);
    $sql = "SELECT id, name, phone, gender, department, doctor FROM general_appointment WHERE status = 'pending' AND phone = '$search_phone' UNION SELECT id, name, phone, gender, department, doctor FROM general_appointment WHERE status = 'pending' AND phone != '$search_phone'";
} else {
    $sql = "SELECT id, name, phone, gender, department, doctor FROM general_appointment WHERE status = 'pending'";
}

$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidenav {
            width: 250px;
            height: 100%;
            position: fixed;
        }
        .sidenav a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }
        .sidenav a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="sidenav">
    <?php include("sidenav.php"); ?>
</div>
<div class="content">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="search_phone">Search by Phone Number</label>
            <input type="text" name="search_phone" class="form-control" id="search_phone" placeholder="Search by Phone Number" value="<?php echo htmlspecialchars($search_phone); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search Appointment</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Department</th>
                    <th>Doctor Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['gender']) . "</td>
                            <td>" . htmlspecialchars($row['department']) . "</td>
                            <td>" . htmlspecialchars($row['doctor']) . "</td>
                            <td>
                                <form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                    <button type='submit' name='approve' class='btn btn-success'>Accept</button>
                                    <button type='submit' name='deny' class='btn btn-danger'>Deny</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
