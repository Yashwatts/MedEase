<?php
include("header.php");
include("connection.php");

$selected_patient_id = null;
$search_refNumber = "";
$patientDetails = null;

if (isset($_POST['search_patient'])) {
    $search_refNumber = mysqli_real_escape_string($connect, $_POST['search_refNumber']);
    $query = "SELECT id, refNumber, full_name FROM patient WHERE refNumber = '$search_refNumber'";
    $result = mysqli_query($connect, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $selected_patient_id = $row['id'];
        $patientDetails = $row;
    } else {
        $patientDetails = 'No patient found with this UHID';
    }
}

if (isset($_POST['select_patient'])) {
    $selected_patient_id = $_POST['patient_id'];
    $_SESSION['selected_patient_id'] = $selected_patient_id;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Patient Report Upload</title>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" style="margin-left:-f30px;">
                    <?php include("sidenav.php"); ?>
                </div>
                <div class="container col-md-4 my-4" style="margin-left:-10px;">
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Search by UHID</label>
                            <input type="text" name="search_refNumber" id="search_refNumber" class="form-control" placeholder="Patient UHID" value="<?php echo htmlspecialchars($search_refNumber); ?>">
                        </div>
                        <button type="submit" name="search_patient" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass" title="Search"></i>
                        </button>
                    </form>
                </div>

                <?php if ($patientDetails && is_array($patientDetails)) { ?>
                    <div class="card-body">
                        <p><strong>UHID:</strong> <?php echo htmlspecialchars($patientDetails['refNumber']); ?></p>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($patientDetails['full_name']); ?></p>
                        
                        <form action="" method="post">
                            <input type="hidden" name="patient_id" value="<?php echo $selected_patient_id; ?>">
                            <button type="submit" name="select_patient" class="btn btn-primary">Select Patient</button>
                        </form>
                    </div>
                <?php } elseif ($patientDetails) { ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($patientDetails); ?></div>
                <?php } ?>
            </div>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select Document Type</label>
                <select name="file_type" id="file_type" class="form-control" required>
                    <option value="" disabled selected>Select Report</option>
                    <option value="Echo_Report">Echo Report</option>
                    <option value="Ultra_Sound">Ultra Sound</option>
                    <option value="MRI">MRI</option>
                    <option value="CT_SCAN">CT Scan</option>
                    <option value="SGPT">SGPT</option>
                    <option value="Invoice">Invoice</option>
                </select>
            </div>

            <div class="form-group">
                <label>Choose File</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <input type="hidden" name="patient_id" value="<?php echo $selected_patient_id; ?>">
            <button type="submit" name="upload" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
