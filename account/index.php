<?php
include("header.php");

// Establish database connection
$connect = new mysqli("localhost", "root", "", "hospital");

// Check connection
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

    // Query to fetch pending appointments
    $ad = mysqli_query($connect, "SELECT * FROM general_appointment WHERE status = 'pending'");
    $num = mysqli_num_rows($ad);

    // Query to calculate total income
    $in = mysqli_query($connect, "SELECT SUM(amount_paid) as profit FROM income");
    $row = mysqli_fetch_array($in);
    $inc = $row['profit'];

    // Query to fetch denied appointments
    $ad_denied = mysqli_query($connect, "SELECT * FROM general_appointment WHERE status = 'denied'");
    $num_denied = mysqli_num_rows($ad_denied);

    // Query to calculate product profit
    $in_product = mysqli_query($connect, "SELECT SUM(price) as profit FROM products");
    $row_product = mysqli_fetch_array($in_product);
    $inc_product = $row_product['profit'];

    // Query to count appointments from the 'general_appointment' table for the logged-in hospital
    $hospital_appointments = mysqli_query($connect, "SELECT COUNT(*) as count FROM general_appointment WHERE LOWER(TRIM(hospital_name)) = '$hospital_name' AND LOWER(TRIM(hospital_location)) = '$hospital_location'");
    $hospital_row = mysqli_fetch_array($hospital_appointments);
    $num_hospital_appointments = $hospital_row['count'];

    // Query to count appointments from the 'appointment' table for all outside appointments
    $outside_appointments = mysqli_query($connect, "SELECT COUNT(*) as count FROM appointment WHERE LOWER(TRIM(hospital_name)) = '$hospital_name' AND LOWER(TRIM(hospital_location)) = '$hospital_location'");
    $outside_row = mysqli_fetch_array($outside_appointments);
    $num_outside_appointments = $outside_row['count'];
} else {
    // Handle case where no branch details are found
    $num_hospital_appointments = 0;
    $num_outside_appointments = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Branch Dashboard</title>

    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: scale(1.05); /* Slightly zoom in */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }
    </style>
</head>
<body>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2" style="margin-left:-30px;"></div>
                <div class="col-md-10" style="margin-top:10px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Account Branch Dashboard</h5>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Appointments -->
                        <div class="col-md-2 mx-2 my-2 card-hover" style="height:130px; background-color: #78AEC6;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="my-2 text-white" style="font-size:30px;"><?php echo $num; ?></h5>
                                        <h5 class="text-white">Total Appointment</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="general_appointment.php">
                                            <i class="fa-solid fa-calendar-check fa-2x my-1 mx-4" style="color: white;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bill Received by Doctors -->
                        <div class="col-md-2 mx-2 my-2 card-hover" style="height:130px; background-color: #78AEC6;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="my-2 text-white" style="font-size:30px;"><?php echo number_format($inc, 2); ?>$</h5>
                                        <h5 class="text-white">Bill Received by Doctors</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="patient.php">
                                            <i class="fa-solid fa-file-invoice-dollar fa-2x mx-2 my-2" style="color: white;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Check Bed Count -->
                        <div class="col-md-2 mx-2 my-2 card-hover" style="height:130px; background-color: #78AEC6;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="text-white">Check Bed Count</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="beds.php">
                                            <i class="fa-solid fa-user-slash fa-2x my-1 mx-2" style="color: white;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment From Hospital -->
                        <div class="col-md-2 mx-2 my-2 card-hover" style="height:130px; background-color: #78AEC6;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="my-2 text-info" style="font-size:30px;"><?php echo $num_hospital_appointments; ?></h5>
                                        <h5 class="text-white">Appointment From Hospital</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="appointment_hospital.php">
                                            <i class="fa-solid fa-sack-dollar fa-2x my-1 mx-4" style="color:white;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment From Outside -->
                        <div class="col-md-2 mx-2 my-2 card-hover" style="height:130px; background-color: #78AEC6;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="my-2 text-info" style="font-size:30px;"><?php echo $num_outside_appointments; ?></h5>
                                        <h5 class="text-white">Appointment From Outside</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="appointment_outside.php">
                                            <i class="fa-solid fa-sack-dollar fa-2x my-1 mx-4" style="color:white;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidenav" style="margin-top: -190px;">
            <?php include("sidenav.php"); ?>
        </div>
    </main>
</body>
</html>

<?php
// Close the database connection
$connect->close();
?>
