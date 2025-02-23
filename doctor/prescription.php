<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doctorsQuery = "SELECT full_name FROM doctors";
$doctorResult = $conn->query($doctorsQuery);

if (!$doctorResult) {
    die("Error fetching doctors: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $refNumber = $_POST['refNumber'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $doctor_name = $_POST['doctor_name'];

    $stmt = $conn->prepare("INSERT INTO prescription_data (refNumber, full_name, gender, age, doctor_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $refNumber, $full_name, $gender, $age, $doctor_name);

    if ($stmt->execute()) {
        header("Location: fetch_patient_data.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Print Prescription</title>
<style>
    form{
        margin-left: 120px;
    }
    form .form-control{
        width: 300px;
    }
</style>
</head>
<body>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2" style="margin-left:-30px;">
                <?php include("sidenav.php"); ?>
            </div>
            <div class="col-md-10">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>UHID:</label>
                                    <input type="text" name="refNumber" id="refNumber" required>
                                </div>
                                <div class="form-group">
                                    <label>Patient Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name">
                                </div>
                                <div class="form-group">
                                    <label>Patient Age</label>
                                    <input type="text" name="age" class="form-control" id="age" required>
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <input type="text" name="gender" class="form-control" id="gender" required>
                                </div>
                                <div class="form-group">
                                    <label>Doctor Name</label>
                                    <select name="doctor_name" class="form-control" id="doctor_name">
                                        <?php
                                        if ($doctorResult->num_rows > 0) {
                                            while($row = $doctorResult->fetch_assoc()){
                                                echo '<option value="' . htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8') . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No doctors available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="alert" id="notification" role="alert">
                                    <button type="submit" class="btn btn-primary">Generate Prescription</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="script2.js"></script>
</div>
</body>
</html>
