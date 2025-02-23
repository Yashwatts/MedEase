<?php
include("header.php");

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

// Handle form submission for approving, denying, or marking payment as completed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'approve':
            $sql = "UPDATE appointment SET status = 'Accepted' WHERE id = '$appointment_id'";
            break;
        case 'deny':
            $sql = "UPDATE appointment SET status = 'Denied' WHERE id = '$appointment_id'";
            break;
        case 'payment_completed':
            $sql = "UPDATE appointment SET payment_status = 'Completed' 
                    WHERE id = '$appointment_id' AND status = 'Accepted'";
            break;
        default:
            echo "Invalid action.";
            exit();
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: appointment.php"); // Redirect to the same page after action
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Search appointments by phone number
$search_phone = "";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_phone'])) {
    $search_phone = $conn->real_escape_string($_POST['search_phone']);
    $sql = "SELECT id, name, phone, gender, department, doctor, status, payment_status 
            FROM appointment 
            WHERE doctor = '$doctor_name'
            AND (phone = '$search_phone' OR status = 'pending')";
} else {
    $sql = "SELECT id, name, phone, gender, department, doctor, status, payment_status 
            FROM appointment WHERE doctor = '$doctor_name'";
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }

        .sidenav {
            height: 100%;
            position: fixed;
            width: 250px;
            color: white;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .table {
            background-color: #fff;
            border-radius: 8px;
        }

        th {
            background-color: #78AEC6;
            color: white;
            padding: 15px;
        }

        td {
            padding: 15px;
        }

        .btn {
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .checkmark {
            color: green;
            font-size: 20px;
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
            <input type="text" name="search_phone" class="form-control" id="search_phone" 
                   placeholder="Search by Phone Number" value="<?php echo htmlspecialchars($search_phone); ?>" 
                   style="width:500px;">
        </div>
        <button type="submit" class="btn btn-primary">Search Appointment</button>
    </form>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Department</th>
                    <th>Doctor</th>
                    <th>Status</th>
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
                            <td>";

                        if ($row['status'] == 'Accepted' && $row['payment_status'] == 'Completed') {
                            echo "Payment Completed <span class='checkmark'>✔️</span>";
                        } else {
                            echo htmlspecialchars($row['status']);
                        }

                        echo "</td>
                            <td>
                                <form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
                                    <input type='hidden' name='appointment_id' value='" . $row['id'] . "'>";

                        if ($row['status'] == 'pending') {
                            echo "<button type='submit' name='action' value='approve' class='btn btn-success'>Approve</button>
                                  <button type='submit' name='action' value='deny' class='btn btn-danger'>Deny</button>";
                        } elseif ($row['status'] == 'Accepted' && $row['payment_status'] != 'Completed') {
                            echo "<button type='submit' name='action' value='payment_completed' class='btn btn-primary'>Payment Completed</button>";
                        } elseif ($row['status'] == 'Denied') {
                            echo "Denied";
                        }

                        echo "</form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
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