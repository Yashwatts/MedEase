<?php
    include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Income from Test</title>
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
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>UHID</th>
                                            <th>Cost</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("connection.php");
                                            
                                            // Query to get the required data
                                            $query = "SELECT patient_id, MIN(date_bill) AS date_bill, SUM(price) AS total_amount, refNumber, MAX(full_name) AS full_name, MAX(name) AS name 
                                                      FROM products 
                                                      GROUP BY patient_id, DATE(date_bill) 
                                                      ORDER BY date_bill DESC";

                                            // Execute query
                                            $result = $connect->query($query);

                                            // Check if any data returned
                                            if ($result->num_rows > 0) {
                                                // Loop through each record
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>"; // Missing <tr> tag added
                                                    echo "<td>" . $row['patient_id'] . "</td>";
                                                    echo "<td>" . $row['full_name'] . "</td>";
                                                    echo "<td>" . $row['refNumber'] . "</td>";
                                                    echo "<td>" . $row['total_amount'] . "</td>";
                                                    echo "<td><a href='view_bill.php?patient_id=" . $row["patient_id"] . "&date_bill=" . $row['date_bill'] . "' class='btn btn-primary'>View Bill</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                // Handle case when no data is found
                                                echo "<tr><td colspan='6'>No Data Found</td></tr>";
                                            }

                                            // Close connection
                                            $connect->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
