<?php
// Ensure the session is started and the patient is logged in
include("header.php");
include("connection.php");


// Check if the patient's username is stored in the session
if (!isset($_SESSION['patient'])) {
    die("Please log in to book an appointment.");
}

$patient_username = $_SESSION['patient'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch patient details from the 'patient' table using the logged-in patient's username
$patient_query = mysqli_query($conn, 
    "SELECT full_name, email, division, district, gender FROM patient WHERE username = '$patient_username'"
);

if (!$patient_query) {
    die("Error: " . mysqli_error($conn));
}

$patient_data = mysqli_fetch_assoc($patient_query);
if (!$patient_data) {
    die("No patient data found.");
}

// Extract patient details
$name = $patient_data['full_name'];
$email = $patient_data['email'];
$division = $patient_data['division'];
$district = $patient_data['district'];
$gender = $patient_data['gender'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $phone = $_POST['phone'];
    $appointment_date = $_POST['date'];
    $department = $_POST['department'];
    $doctor = $_POST['doctor'];
    $symptoms = $_POST['sym'];

    // Insert data into the 'general_appointment' table
    $insert = mysqli_query($conn, 
        "INSERT INTO general_appointment 
        (phone, date, department, doctor, symptoms, status, date_reg, gender, name, email, division, district) 
        VALUES 
        ('$phone', '$appointment_date', '$department', '$doctor', '$symptoms', 'pending', NOW(), 
        '$gender', '$name', '$email', '$division', '$district')"
    );

    if ($insert) {
        echo "<script>alert('You have successfully booked an appointment.');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Appointment Form</title>
    <style>
        .jumbotron {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 20px auto;
            margin-left: 400px;
            margin-top: -700px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        form input[type="text"], 
        form input[type="date"], 
        form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="text-dark my-2">
                <div class="row">
                    <div class="col-md-3" style="margin-left:-30px; margin-top:-10px;">
                        <?php include("sidenav.php"); ?>
                    </div>
                </div>
                <div class="col-md-6 jumbotron">
                    <form action="" method="post">
                        <label>Phone</label>
                        <input type="text" name="phone" required>

                        <label>Date</label>
                        <input type="date" name="date" required>

                        <label>Select Department</label>
                        <select id="department" name="department" required onchange="populateDoctors()" class="form-control">
                            <option value="" disabled selected>Select Department</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="dermatology">Dermatology</option>
                            <option value="orthopedics">Orthopedics</option>
                            <option value="neurology">Neurology</option>
                            <option value="oncology">Oncology</option>
                            <option value="pediatrics">Pediatrics</option>
                            <option value="gastroenterology">Gastroenterology</option>
                            <option value="opthamology">Opthamology</option>
                            <option value="urology">Urology</option>
                        </select>

                        <label>Select Doctor</label>
                        <select id="doctor" name="doctor" required class="form-control">
                            <option value="" disabled selected>Select Department first</option>
                        </select>

                        <label>Symptoms</label>
                        <input type="text" name="sym" required class="form-control">

                        <input type="submit" name="book" class="btn btn-info my-2" value="Book Appointment">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
