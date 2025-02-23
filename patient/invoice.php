<?php
include("header.php");
include("connection.php");

$limit = 13;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Invoices</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container_fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-mod-2" style="margin-left:-30px;">
                    <?php include("sidenav.php") ?>
                </div>
                <div class="col-md-10">
                    <div class="col-mod-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Patient Invoice</h6>
                                <?php
                                $query = "SELECT * FROM income WHERE user_id = ? LIMIT $start, $limit";
                                $stmt = mysqli_prepare($connect, $query);
                                mysqli_stmt_bind_param($stmt, "i", $_SESSION['patient_id']);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    echo '<table>';
                                    echo '<tr style="background-color:skyblue;">';
                                    echo '<th style="background-color:skyblue;">Billing ID</th>';
                                    echo '<th style="background-color: skyblue;">Doctor Name</th>';
                                    echo '<th style="background-color:skyblue;">Patient Name</th>';
                                    echo '<th style="background-color:skyblue;">Date Of Bill Pay</th>';
                                    echo '<th style="background-color:skyblue;">Description</th>';
                                    echo '<th style="background-color:skyblue;">Amount Paid</th>';
                                    echo '</tr>';

                                    $totalAmount = 0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td>' . $row["id"] . '</td>';
                                        echo '<td>' . $row["doctor"] . '</td>';
                                        echo '<td>' . $row["patient"] . '</td>';
                                        echo '<td>' . $row["date_discharge"] . '</td>';
                                        echo '<td>' . $row["description"] . '</td>';
                                        echo '<td style="text-align: right;">' . $row["amount_paid"] . '</td>';
                                        echo '</tr>';

                                        $totalAmount += $row["amount_paid"];
                                    }

                                    // Total amount row should be outside the loop
                                    echo '<tr>';
                                    echo '<td colspan="5" style="text-align:right;"><b>Total Amount:</b></td>';
                                    echo '<td style="text-align:right;"><b>' . $totalAmount . '</b></td>';
                                    echo '</tr>';
                                    echo '</table>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
