<?php
include("header.php");
include("connection.php");
$connect = mysqli_connect('localhost', 'root', '', 'hospital');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $refNumber = $_POST['refNumber'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $wbc = $_POST['wbc'];
    $rbc = $_POST['rbc'];
    $platelet = $_POST['platelet'];
    $haemoglobin = $_POST['haemoglobin'];
    $hbsAg = $_POST['hbsAg'];
    $bloodGroup = $_POST['bloodGroup'];
    $bloodSugar = $_POST['bloodSugar'];
    $uricAcid = $_POST['uricAcid'];
    $Leukocyte = $_POST['Leukocyte'];
    $Neutrophils = $_POST['Neutrophils'];
    $Basophil = $_POST['Basophil'];
    $Eosinophils = $_POST['Eosinophils'];

    $insert = mysqli_query($connect, "INSERT INTO cbc_report (refNumber, full_name, age, gender, wbc, rbc, platelet, haemoglobin, hbsAg, bloodGroup, bloodSugar, uricAcid, Leukocyte, Neutrophils, Basophil, Eosinophils, reportTitle, report_submit_date) VALUES ('$refNumber', '$full_name', '$age', '$gender', '$wbc', '$rbc', '$platelet', '$haemoglobin', '$hbsAg', '$bloodGroup', '$bloodSugar', '$uricAcid', '$Leukocyte', '$Neutrophils', '$Basophil', '$Eosinophils', 'CBC_Report', NOW())");

    if ($insert) {
        echo "<script>alert('Report Uploaded successfully'); location = 'cbc_test.php';</script>";
    } else {
        echo "Error: " . mysqli_error($connect);
    }


mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2" style="margin-left: -30px;">
                <!-- <?php include("sidenav.php"); ?> -->
            </div>
            <form id="cbcForm" action="" method="post" class="my-4 col-md-10">
                <div class="row">
                    <!-- Patient UHID -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                            </div>
                            <input type="text" name="refNumber" id="refNumber" class="form-control" autocomplete="off" placeholder="Enter Patient UHID" required>
                        </div>
                    </div>

                    <!-- Patient Full Name -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="full_name" id="full_name" class="form-control" autocomplete="off" placeholder="Enter Patient Full Name" required>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            </div>
                            <input type="text" name="gender" id="gender" class="form-control" autocomplete="off" placeholder="Enter Gender" required>
                        </div>
                    </div>

                    <!-- Age -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" name="age" id="age" class="form-control" autocomplete="off" placeholder="Enter Age" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Basophil -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Basophil" id="Basophil" class="form-control" autocomplete="off" placeholder="Enter Basophil Count" required>
                        </div>
                    </div>

                    <!-- WBC -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-flask"></i></span>
                            </div>
                            <input type="text" name="wbc" id="wbc" class="form-control" autocomplete="off" placeholder="Total WBC" required>
                        </div>
                    </div>

                    <!-- RBC -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-flask"></i></span>
                            </div>
                            <input type="text" name="rbc" id="rbc" class="form-control" autocomplete="off" placeholder="Total RBC" required>
                        </div>
                    </div>

                    <!-- Platelet -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-flask"></i></span>
                            </div>
                            <input type="text" name="platelet" id="platelet" class="form-control" autocomplete="off" placeholder="Total Platelet" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Haemoglobin -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="haemoglobin" id="haemoglobin" class="form-control" autocomplete="off" placeholder="Total Haemoglobin" required>
                        </div>
                    </div>

                    <!-- HbsAg -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-virus"></i></span>
                            </div>
                            <select name="hbsAg" id="hbsAg" class="form-control" required>
                                <option value="">Select Result</option>
                                <option value="hbsAg Positive">HbsAg Positive</option>
                                <option value="hbsAg Negative">HbsAg Negative</option>
                            </select>
                        </div>
                    </div>

                    <!-- Blood Group -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-tint"></i></span>
                            </div>
                            <select name="bloodGroup" id="bloodGroup" class="form-control" required>
                                <option value="A+">A Positive</option>
                                <option value="A-">A Negative</option>
                                <option value="O+">O Positive</option>
                                <option value="O-">O Negative</option>
                                <option value="B+">B Positive</option>
                                <option value="B-">B Negative</option>
                                <option value="AB+">AB Positive</option>
                                <option value="AB-">AB Negative</option>
                            </select>
                        </div>
                    </div>

                    <!-- Blood Sugar -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="bloodSugar" id="bloodSugar" class="form-control" autocomplete="off" placeholder="Sugar Level" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Uric Acid -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="uricAcid" id="uricAcid" class="form-control" autocomplete="off" placeholder="Uric Acid Level" required>
                        </div>
                    </div>

                    <!-- Leukocytes -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Leukocyte" id="Leukocyte" class="form-control" autocomplete="off" placeholder="Leukocytes Level" required>
                        </div>
                    </div>

                    <!-- Neutrophils -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Neutrophils" id="Neutrophils" class="form-control" autocomplete="off" placeholder="Neutrophils Level" required>
                        </div>
                    </div>

                    <!-- Eosinophils -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Eosinophils" id="Eosinophils" class="form-control" autocomplete="off" placeholder="Eosinophils Level" required>
                        </div>
                    </div>
                </div>

                <!--
                <div class="row">
                    Lymphocytes -->
                                   <div class="row">
                    <!-- Lymphocytes -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Lymphocytes" id="Lymphocytes" class="form-control" autocomplete="off" placeholder="Lymphocytes Level" required>
                        </div>
                    </div>

                    <!-- Monocytes -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                            </div>
                            <input type="text" name="Monocytes" id="Monocytes" class="form-control" autocomplete="off" placeholder="Monocytes Level" required>
                        </div>
                    </div>

                    <!-- Uric Acid -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tint"></i></span>
                            </div>
                            <input type="text" name="uricAcid" id="uricAcid" class="form-control" autocomplete="off" placeholder="Uric Acid Level" required>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-comments"></i></span>
                            </div>
                            <input type="text" name="remarks" id="remarks" class="form-control" autocomplete="off" placeholder="Remarks" required>
                        </div>
                    </div>
                </div>


                <!-- Submit Button -->
                <div class="row">
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit CBC Form</button>
                    </div>
                </div>
            </form>
            <div id="successNotification" class="alert alert-success" style="display: none; height: 50px; width: 300px;">
    Data Collect Successful
</div>
<div id="dangerNotification" class="alert alert-danger" style="display: none; height: 50px; width: 300px;">
    Error! Incorrect UHID
</div>


        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Font Awesome JS -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
  $('#refNumber').on('blur', function() {
    var refNumber = $(this).val();
    if (refNumber != '') {
      $.ajax({
        url: "load_data.php",
        method: "POST",
        data: { refNumber: refNumber },
        dataType: 'json',
        success: function(data) {
          if (data.error) {
            $('#dangerNotification').addClass('popup').fadeIn().delay(3000).fadeOut();
            $('#successNotification').hide();
            $('#full_name').val('');
            $('#age').val('');
            $('#gender').val('');
          } else {
            $('#successNotification').addClass('popup').fadeIn().delay(3000).fadeOut();
            $('#dangerNotification').hide();
            $('#full_name').val(data.full_name);
            $('#age').val(data.age);
            $('#gender').val(data.gender);
          }
        }
      })
    }
  })
</script>
<style>

    #successNotification, #dangerNotification {
    background-color: green;
    color: white;
    height: 25px;
    width: 300px;
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    border-radius: 5px;
    padding: 5px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    margin-top: -400px;
}
#dangerNotification {
    background-color: red;
}

.popup {
    animation: popupAnimation 0.5s forwards;
}

@keyframes popupAnimation {
    from {
        transform: translate(-50%, -60%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, -50%);
        opacity: 1;
    }
}


</style>

</body>
</html>
