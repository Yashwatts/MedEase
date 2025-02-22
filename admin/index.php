<?php
// session_start();
include("header.php");
if (isset($_SESSION['admin'])) {
    // Session is set for admin
} else {
    echo "Session not set. Redirecting...";
    header("Location: ../role.php");
    exit();
}



// Database connection
$connect = mysqli_connect("localhost", "root", "", "hospital");

// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        .dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    padding: 20px;
}

.card {
    position: relative;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1, 0, 0, 1.0);
    flex: 1 1 calc(33.333% - 20px);
    padding: 15px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    max-width: 315px;
    min-height: 11rem;
    max-height: 11rem;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 8px 16px rgba(0, 0, 0, 0.1);
}

.card-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.75rem;
    color: #007bff;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 15px;
    color: #333333;
}

.card-text {
    font-size: 0.9rem;
    color: #666666;
    margin-top: 8px;
}

.card:nth-child(1) .card-icon {
    color: #28a745;
}

.card:nth-child(2) .card-icon {
    color: #ffc107;
}

.card:nth-child(3) .card-icon {
    color: #17a2b8;
}

.card:nth-child(4) .card-icon {
    color: #dc3545;
}

.card:nth-child(5) .card-icon {
    color: #6f42c1;
}

.card:nth-child(6) .card-icon {
    color: #fd7e14;
}

.custom-height {
    height: 100%; /* Set the desired height */
}

</style>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-13" style="margin-left: -20px;">
        <div class="row">
            <div class="col-md-2">
                <?php include("sidenav.php"); ?>
            </div>
            <div class="col-md-10">
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10">

                        <div class="dashboard">
                                <div class="card" style="background-color: #78AEC6;">
                                <?php
                                $ad = mysqli_query($connect, "SELECT * FROM admin");
                                if ($ad) {
                                    $num = mysqli_num_rows($ad);
                                    echo "<h5 class='my-2 text-black' style='font-size: 30px;'>$num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-black' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-black'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                 <a href="admin.php" class="card-link">
                                    <i class="fa-solid fa-users card-icon text-white"></i>
                                     <h3 class="card-title">Total Admin</h3>
                            </a>
                        </div>
                    <!-- Doctor count block -->
                        <div class="card" style="background-color: #78AEC6;">
                                <?php
                                $doc = mysqli_query($connect, "SELECT * FROM doctors");
                                if ($doc) {
                                    $doc_num = mysqli_num_rows($doc);
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>$doc_num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-white'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                <a href="doctor.php" class="card-link">
                                <i class="fa-solid fa-user-doctor card-icon" style="color: hotpink;"></i>
                                <h3 class="card-title">Total Doctors</h3>
                            </a>
                            </div>
                    <!-- Patient count block -->
                      <div class="card" style="background-color:#78AEC6;">
                                <?php
                                $pat = mysqli_query($connect, "SELECT * FROM patient");
                                if ($pat) {
                                    $pat_num = mysqli_num_rows($pat);
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>$pat_num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-white'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                <a href="patient.php" class="card-link">
                                    <i class="fa-solid fa-bed fa-2x card-icon text-warning"></i>
                                    <h3 class="card-title text-white">Total Patients</h3>
                                </a>
                            </div>
                    <!-- Complain count block -->
                    <div class="card" style="background-color:#78AEC6;">
                                <?php
                                $complaint = mysqli_query($connect, "SELECT * FROM complaint");
                                if ($complaint) {
                                    $complaint_num = mysqli_num_rows($complaint);
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>$complaint_num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-white'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                <a href="view_complaint.php" class="card-link">
                                    <i class="fa-solid fa-stethoscope fa-2x card-icon"></i>
                                    <h3 class="card-title">Total Complaints</h3>
                                </a>
                            </div>
                    <!-- Job Request count block -->
                    <div class="card" style="background-color:#78AEC6;">
                                <?php
                                $job_request = mysqli_query($connect, "SELECT * FROM beds");
                                if ($job_request) {
                                    $job_request_num = mysqli_num_rows($job_request);
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>$job_request_num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-white'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                <a href="beds.php" class="card-link">
                                    <i class="fa-solid fa-person-circle-question fa-2x card-icon"></i>
                                    <h3 class="card-title">Bed Count</h3>
                                </a>
                            </div>
                    <!-- Income count block -->
                   
                            <!-- Inventory block -->
                    <div class="card" style="background-color:#78AEC6;">
                                <?php
                                $income = mysqli_query($connect, "SELECT * FROM inventory");
                                if ($income) {
                                    $income_num = mysqli_num_rows($income);
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>$income_num</h5>";
                                } else {
                                    echo "<h5 class='my-2 text-white' style='font-size: 30px;'>Error</h5>";
                                    echo "<p class='text-white'>Error: " . mysqli_error($connect) . "</p>";
                                }
                                ?>
                                <a href="inventory.php" class="card-link">
                                    <i class="fa-solid fa-money-bill-1-wave fa-2x card-icon"></i>
                                    <h3 class="card-title">Manage Inventory</h3>
                                </a>
                            </div>
                           

                </div> <!-- End row -->
            </div> <!-- End col-md-10 -->
        </div> <!-- End row -->
    </div> <!-- End container-fluid -->

    <div class="col-md-8" style="margin-left:20px;">
        <br>
        <h6>Add Staff</h6>
        <form method="POST" id="registrationForm">
            <div class="form-group">
                <div class="input-container">
                    <i class="fa-solid fa-pen icon"></i>    
                    <input type="text" name="full_name" class="form-control" placeholder="Staff Name">
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <i class="fa-solid fa-envelope icon"></i>
                    <input type="text" name="email" class="form-control" placeholder="Staff Email">
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <i class="fa-solid fa-user icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Staff Username">
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <i class="fa-solid fa-key icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Staff Password">
                </div>
            </div>

            <div class="form-group">
                <div class="input-container">
                    <i class="fa-solid fa-user-tie icon"></i>
                    <select class="form-control" name="role">
                        <option value="doctor">Doctor</option>
                        <option value="staff">Staff</option>
                        <option value="account branch">Account Branch</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" name="add" value="Add Staff" class="btn btn-primary">
            </div>
        </form>

        <?php
        if (isset($_POST['add'])) {
            $full_name = mysqli_real_escape_string($connect, $_POST['full_name']);
            $email = mysqli_real_escape_string($connect, $_POST['email']);
            $username = mysqli_real_escape_string($connect, $_POST['username']);
            $password = mysqli_real_escape_string($connect, $_POST['password']);
            $role = mysqli_real_escape_string($connect, $_POST['role']);

            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Determine the table based on the role
            $table = '';
            if ($role === 'doctor') {
                $table = 'doctors';
            } elseif ($role === 'staff') {
                $table = 'employee';
            } elseif ($role === 'account branch') {
                $table = 'account_branch';
            }

            if ($table) {
                // Prepare and execute the SQL statement
                $sql = "INSERT INTO $table (full_name, email, username, password) VALUES (?, ?, ?, ?)";
                $stmt = $connect->prepare($sql);
                if ($stmt === false) {
                    die("Prepare failed: " . $connect->error);
                }
                // Bind parameters (s for string)
                $stmt->bind_param("ssss", $full_name, $email, $username, $hashed_password);

                if ($stmt->execute()) {
                    echo "<p>Staff added successfully.</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p>Invalid role selected.</p>";
            }
        }
        ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
