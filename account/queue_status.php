<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to estimate checkout time based on patient condition and age
function estimateCheckoutTime($condition, $age) {
    if ($condition == 'Critical') {
        if ($age <= 18) {
            return 24; // 24 hours
        } elseif ($age <= 60) {
            return 48; // 48 hours
        } else {
            return 72; // 72 hours
        }
    } elseif ($condition == 'Normal') {
        if ($age <= 18) {
            return 6; // 6 hours
        } elseif ($age <= 60) {
            return 12; // 12 hours
        } else {
            return 24; // 24 hours
        }
    }
    return 0; // Default case
}

// Updated SQL query to join hospital_bookings and patient tables with a status column
$sql = "
    SELECT hospital_bookings.full_name, hospital_bookings.age, hospital_bookings.phone_no AS phone, hospital_bookings.date_reg, 'Critical' AS status
    FROM hospital_bookings 
    INNER JOIN account_branch ON hospital_bookings.hospital_name = account_branch.hospital_name 
        AND hospital_bookings.location = account_branch.hospital_location

    UNION ALL
    
    SELECT patient.full_name, patient.age, patient.phone, patient.date_reg, 'Normal' AS status
    FROM patient 
    INNER JOIN account_branch ON patient.hospital_name = account_branch.hospital_name 
        AND patient.hospital_location = account_branch.hospital_location 
    WHERE patient.status = 'approved'
";

// Execute the query
$result = $conn->query($sql);

if ($result === false) {
    die("Query failed: " . $conn->error);
}

// Fetch results
$results = $result->fetch_all(MYSQLI_ASSOC);

// Fetch number of approved patients
$ad = $conn->query("SELECT * FROM patient WHERE status = 'approved'");
if ($ad === false) {
    die("Query failed: " . $conn->error);
}
$num = $ad->num_rows;

// Close the connection after all queries
$conn->close();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" style="margin-left:-30px; min-height: 42.8rem;">
                    <?php include("sidenav.php"); ?>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-10">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Queue Number</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Phone</th>
                                            <th>Registration Date</th>
                                            <th>Patient Condition</th>
                                            <th>Estimated Checkout Time (hours)</th>
                                            <th id="details">Details         </th>
                                            <th>Submit</th> <!-- New Submit column -->
                                            <th>Edit</th>   <!-- New Edit column -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $queueNumber = 1; // Initialize queue number
                                        foreach ($results as $row) { 
                                            $estimatedTime = estimateCheckoutTime($row['status'], $row['age']); // Calculate estimated time
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($queueNumber++); ?></td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($row['date_reg']); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                            <td><?php echo htmlspecialchars($estimatedTime); ?></td> <!-- Display estimated time -->
                                            <td>
                                                <textarea class="form-control" rows="2" placeholder="Enter details here..."></textarea>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary submit-btn">Submit</button> <!-- Submit button -->
                                            </td>
                                            <td>
                                                <button class="btn btn-secondary edit-btn hidden">Edit</button> <!-- Edit button -->
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle submit button click
            $('.submit-btn').on('click', function() {
                var row = $(this).closest('tr');
                var details = row.find('textarea').val();

                // Here you can send an AJAX request to save the details
                console.log("Submitting details:", details);
                row.find('textarea').prop('disabled', true); // Disable the textarea
                $(this).addClass('hidden'); // Hide submit button
                row.find('.edit-btn').removeClass('hidden'); // Show edit button
            });

            // Handle edit button click
            $('.edit-btn').on('click', function() {
                var row = $(this).closest('tr');
                row.find('textarea').prop('disabled', false); // Enable the textarea
                $(this).addClass('hidden'); // Hide edit button
                row.find('.submit-btn').removeClass('hidden'); // Show submit button
            });
        });
    </script>
</body>
</html>
