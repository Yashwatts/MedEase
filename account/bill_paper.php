<?php
include('header.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $refNumber = $_POST['refNumber'];
        $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
        $product_price = isset($_POST['product_price']) ? (float)$_POST['product_price'] : 0;  // Ensure price is cast to float

        $patientQuery = "SELECT * FROM patient WHERE refNumber = '$refNumber'";
        $result = $conn->query($patientQuery);

        if ($result && $result->num_rows > 0) {
            $patientInfo = $result->fetch_assoc();
            $patient_id = $patientInfo['id'];
            $patient_full_name = $patientInfo['full_name'];

            $_SESSION['products'][] = [
                'name' => $product_name,
                'price' => $product_price,
                'patient_id' => $patient_id
            ];

            $sql = "INSERT INTO products (name, price, date_bill, bill_time, refNumber, patient_id, full_name) 
                    VALUES ('$product_name', '$product_price', CURDATE(), NOW(), '$refNumber', '$patient_id', '$patient_full_name')";

            $conn->query($sql);
        } else {
            echo "Patient not found with this UHID";
        }
    } elseif (isset($_POST['clear_invoice'])) {
        $_SESSION['products'] = [];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['generate_pdf'])) {
        $refNumber = $_POST['refNumber'];
        $patientQuery = "SELECT * FROM patient WHERE refNumber = '$refNumber'";
        $result = $conn->query($patientQuery);

        if ($result && $result->num_rows > 0) {
            $patientInfo = $result->fetch_assoc();
            require_once('tcpdf/tcpdf.php');
            $pdf = new TCPDF();

            $pdf->SetCreator('Invoice System');
            $pdf->SetAuthor('Your Name');
            $pdf->SetTitle('Invoice');

            $pdf->SetAutoPageBreak(false, 0);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);

            $backgroundImagePath = 'image/invoice_bg2.jpg';
            $pdf->Image($backgroundImagePath, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), '', '', '', false, 300);

            $pdf->SetLeftMargin(28);
            $pdf->SetY(40 - 38);
            $pdf->setLineWidth(0.01);

            $pdf->Cell(0, 7, 'UHID: ' . $patientInfo['refNumber'], 0, 1, 'L');
            $pdf->Cell(0, 7, 'Patient Name: ' . $patientInfo['full_name'], 0, 1, 'L');
            $pdf->Cell(0, 7, 'Age: ' . $patientInfo['age'], 0, 1, 'L');
            $pdf->Cell(0, 7, 'Gender: ' . $patientInfo['gender'], 0, 1, 'L');
            $pdf->Cell(0, 7, 'Address: ' . $patientInfo['district'], 0, 1, 'L');

            $pdf->SetXY(28, 80);
            foreach ($_SESSION['products'] as $product) {
                $pdf->Cell(0, 0, $product['name'], 0, 0, '');
                $pdf->Cell(0, 0, $product['price'], 0, 1, 'R');
            }
            $pdf->SetY(241);

            $total_amount = array_sum(array_column($_SESSION['products'], 'price'));
            $pdf->Cell(0, 10, 'Total Amount: $' . number_format($total_amount, 2), 0, 1, 'R');

            $pdf->Output('invoice.pdf', 'I');
        } else {
            echo "Patient not found with this UHID";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Section</title>
    <style>
        form{
            margin-top: 30px;
        }
        form .form-control{
            width: 350px;
        }
        .table-bordered{
            margin-left: 500px;
            margin-top: -300px;
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
                                    <label>Ref.Number</label>
                                    <input type="text" name="refNumber" class="form-control" id="refNumber" required>
                                </div>

                                <div class="form-group">
                                    <label>Product Name</label>
                                    <select name="product_name" class="form-control" aria-label="Select Report" onchange="updateTestCost(this)">
                                        <option value="" disabled selected>Select Report</option>
                                        <option value="CBC">CBC</option>
                                        <option value="X-ray">X-Ray</option>
                                        <option value="ECG">ECG</option>
                                        <option value="Echo">Echo</option>
                                        <option value="Lipid_profile">Lipid Profile</option>
                                        <option value="Ultrasound">Ultrasound</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="product_price">Test Cost</label>
                                    <input type="number" name="product_price" id="product_price" step="0.01" class="form-control">
                                </div>

                                <button type="submit" name="add_product" class="btn btn-primary"><i class="fas fa-plus"></i>Add</button>
                                <button type="submit" name="clear_invoice" class="btn btn-danger"><i class="fas fa-trash-alt"></i>Clear</button>
                                <button type="submit" name="generate_pdf" class="btn btn-success"><i class="fas fa-file-pdf"></i>Print</button>
                            </form>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Test Name</th>
                                        <th style="text-align:right;">Test cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_amount = 0;
                                    foreach ($_SESSION['products'] as $product): 
                                        $total_amount += (float)$product['price'];  // Cast price to float for summing
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td style="text-align: right;"><?php echo htmlspecialchars($product['price']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td>Total Amount</td>
                                        <td style="text-align:right;"><b><?php echo number_format($total_amount, 2); ?> $</b></td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var refNumberInput = document.getElementById('refNumber');
    var storedRefNumber = localStorage.getItem('refNumber');
    if (storedRefNumber) {
        refNumberInput.value = storedRefNumber;
    }
    refNumberInput.addEventListener('input', function() {
        localStorage.setItem('refNumber', refNumberInput.value);
    });
});

function updateTestCost(selectElement) {
    var testCostInput = document.getElementById("product_price");
    var costs = {
        "CBC": 600,
        "ECG": 200,
        "Echo": 1500,
        "Ultrasound": 1500,
        "X-ray": 550,
        "Lipid_profile": 1150
    };
    testCostInput.value = costs[selectElement.value] || 0;
}
</script>
</body>
</html>
