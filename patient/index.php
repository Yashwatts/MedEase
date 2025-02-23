<?php
// Include database connection
include ('header.php');
include 'connection.php'; // Make sure to connect to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $title = mysqli_real_escape_string($connect, $_POST['title']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    $date_send = date("Y-m-d H:i:s"); // Current date and time

    // Insert query
    $sql = "INSERT INTO complaint (name, title, message, date_send) VALUES ('$name', '$title', '$message', '$date_send')";

    // Execute query
    if (mysqli_query($connect, $sql)) {
        echo "Complaint submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    }

    // Close connection
    mysqli_close($connect);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            padding: 20px;
        }

        .card {
            position: relative;
            background-color: #78AEC6;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .bg-info, .bg-warning, .bg-success {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form Container Styling */
        .form-container {
            margin: 40px;
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-left: 300px;
            border: 1px solid black;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-container label {
            font-weight: bold;
            color: #555;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #78AEC6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #5c94a7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .dashboard .card {
                flex: 1 1 100%;
            }
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2" style="margin-left: -20px;">
                <?php include("sidenav.php"); ?>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard">
                            <div class="card" style="background-color: #78AEC6;">
                                <h5 class="text-white">My Profile</h5>
                                <a href="profile.php">
                                    <i class="fa fa-user-circle card-icon text-white"></i>
                                </a>
                            </div>
                            <div class="card" style="background-color: #78AEC6;">
                                <h5 class="text-white">Book Appointment</h5>
                                <a href="appointment.php">
                                    <i class="fa fa-calendar-plus card-icon text-white"></i>
                                </a>
                            </div>
                            <div class="card" style="background-color: #78AEC6;">
                                <h5 class="text-white">Invoice</h5>
                                <a href="invoice.php">
                                    <i class="fas fa-file-invoice card-icon text-white"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Complaint Form -->
                        <div class="form-container">
                            <h2>Submit a Complaint</h2>
                            <form action="submit_complaint.php" method="POST">
                                <div class="form-group">
                                    <label for="name">Enter Your Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="title">Enter Title</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Enter Your Message</label>
                                    <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                                </div>
                                
                                <input type="submit" value="Submit Complaint" class="btn btn-primary">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
