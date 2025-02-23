<?php 
include("header.php"); 
// Check if the doctor is logged in
if (!isset($_SESSION['doctor'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Doctor Dashboard</title>

    <style>
        body { background-color: #f8f9fa; }
        .sidenav { margin-top: 0; margin-left: -5px; }
        .dashboard-title { margin-top: -50rem; margin-left: 210px; font-weight: 600; }
        .dashboard-card {
            border-radius: 15px; height: 140px; width: 280px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out; margin-left: 200px;
            background-color: #78AEC6;
        }
        .dashboard-card:hover { transform: translateY(-5px) scale(1.02); }
        .card-title { font-size: 18px; margin-top: 20px; font-weight: 500; }
        .icon { font-size: 45px; color: white; }
        .content-row { justify-content: space-evenly; margin-top: 40px; }
    </style>
</head>

<body>
    <div class="sidenav">
        <?php include("sidenav.php"); ?>
    </div>

    <div class="container-fluid">
        <h5 class="dashboard-title">Doctor Dashboard</h5>

        <div class="row content-row animate__animated animate__fadeIn">
            <!-- My Profile Card -->
            <div class="text-white dashboard-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <h5 class="card-title">My Profile</h5>
                </div>
                <a href="profile.php" class="text-white">
                    <i class="fa fa-user-circle icon"></i>
                </a>
            </div>

            <!-- Total Appointments Card -->
            <div class="text-white dashboard-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <h5 class="card-title">
                        <?php
                        $doctor_username = $_SESSION['doctor'];

                        // Fetch the doctor's full name using prepared statement
                        $stmt = $connect->prepare("SELECT full_name FROM doctors WHERE username = ?");
                        $stmt->bind_param("s", $doctor_username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $doctor = $result->fetch_assoc();
                            $doctor_name = $doctor['full_name'];

                            // Fetch the appointments for this doctor
                            $appointment_stmt = $connect->prepare(
                                "SELECT COUNT(*) AS total FROM appointment WHERE doctor = ? AND status = 'pending'"
                            );
                            $appointment_stmt->bind_param("s", $doctor_name);
                            $appointment_stmt->execute();
                            $appointment_result = $appointment_stmt->get_result();
                            $appointment_data = $appointment_result->fetch_assoc();

                            echo $appointment_data['total'];
                        } else {
                            echo "0"; // No doctor found
                        }
                        ?>
                        <br>Total Appointments
                    </h5>
                </div>
                <a href="appointment.php" class="text-white">
                    <i class="fa-solid fa-calendar-check icon"></i>
                </a>
            </div>

            <!-- Total Patients with Completed Payments Card -->
            <div class="text-white dashboard-card d-flex align-items-center justify-content-between p-4">
                <div>
                    <h5 class="card-title">
                        <?php
                        // Query to count distinct patients with completed payments for the logged-in doctor
                        $completed_payments_query = "
                            SELECT COUNT(DISTINCT name) AS total 
                            FROM appointment 
                            WHERE payment_status = 'completed' 
                            AND doctor = ?
                        ";

                        // Prepare and execute the statement
                        $completed_payments_stmt = $connect->prepare($completed_payments_query);
                        $completed_payments_stmt->bind_param("s", $doctor_name);
                        $completed_payments_stmt->execute();
                        $completed_payments_result = $completed_payments_stmt->get_result();

                        // Check if the query was successful
                        if ($completed_payments_result) {
                            $completed_payments_data = $completed_payments_result->fetch_assoc();
                            echo $completed_payments_data['total'];
                        } else {
                            // Output the error message if the query fails
                            echo "Error: " . mysqli_error($connect);
                        }
                        ?>
                        <br>Total Patients
                    </h5>
                </div>
                <a href="patient.php" class="text-white">
                    <i class="fa-solid fa-bed-pulse icon"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
