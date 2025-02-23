<?php
include("header.php");

$records_per_page = 15;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Get the patient_id from URL
$patient_id = $_GET['patient_id'] ?? null; 

if (!$patient_id) {
    die("Patient ID is required.");
}

// Establish connection to the database
$connect = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch products for the given patient_id with pagination
$sql = "SELECT * FROM products WHERE patient_id = $patient_id LIMIT $start_from, $records_per_page";
$result = $connect->query($sql);

// Calculate the total amount for the given patient_id
$sql_total_amount = "SELECT SUM(price) AS total_amount FROM products WHERE patient_id = $patient_id";
$result_total_amount = $connect->query($sql_total_amount);
$row_total_amount = $result_total_amount->fetch_assoc();
$total_amount = $row_total_amount['total_amount'];
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
                                        <tr class="bg-dark text-white">
                                            <th>ID</th>
                                            <th>Patient Name</th>
                                            <th>UHID</th>
                                            <th>Product Name</th>
                                            <th>Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Initialize total amount
                                        $total_amount = 0;
                                        if ($result->num_rows > 0) {
                                            // Fetch and display data
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row['patient_id'] . "</td>";
                                                echo "<td>" . $row['full_name'] . "</td>";
                                                echo "<td>" . $row['refNumber'] . "</td>";
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td style='text-align: right;'>" . $row['price'] . "</td>";
                                                echo "</tr>";

                                                $total_amount += $row['price'];
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No data found</td></tr>";
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4" style="font-size:16px; text-align:right;">Total Amount:</td>
                                            <td style="text-align:right;"><b><?php echo number_format($total_amount, 2); ?>$</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                            // Pagination
                            $sql_count = "SELECT COUNT(*) AS total_records FROM products WHERE patient_id = $patient_id";
                            $result_count = $connect->query($sql_count);
                            $row_count = $result_count->fetch_assoc();
                            $total_pages = ceil($row_count["total_records"] / $records_per_page);

                            echo "<ul class='pagination justify-content-center'>";
                            for ($i = 1; $i <= $total_pages; $i++) {
                                echo "<li class='page-item'><a class='page-link' href='view_bill.php?page=".$i."&patient_id=".$patient_id."'>".$i . "</a></li>";
                            }
                            echo "</ul>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
