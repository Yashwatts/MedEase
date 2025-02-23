<?php
include("header.php");
include("sidenav.php");
$connect = new mysqli("localhost", "root", "", "hospital");

if (isset($_POST['create'])) {
    // Collect form data
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $emergency_contact = $_POST['emergency_contact'];
    $refNumber = $_POST['refNumber'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $religion = $_POST['religion'];
    $martial_status = $_POST['martial_status'];
    $bloodGroup = $_POST['bloodGroup'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $division = $_POST['division'];
    $district = $_POST['district'];
    $password = password_hash($connect->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

    // Initialize image variables
    $profile = 'default.jpg'; // Default profile image
    $uploadDirectory = "../patient/uploads/";
    
    // Handle image upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['img']['name'];
        $tmpName = $_FILES['img']['tmp_name'];
        $filePath = $uploadDirectory . basename($img);

        // Check if file is uploaded and move it to the upload directory
        if (move_uploaded_file($tmpName, $filePath)) {
            $profile = $img;
        } else {
            echo '<script>alert("Failed to upload image.");</script>';
        }
    }

    // Hash the password

    // Connect to the database
    $connect = mysqli_connect('localhost', 'root', '', 'hospital');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert data into the database
    $insert = mysqli_query($connect, "INSERT INTO patient (full_name, phone, email, emergency_contact, refNumber, age, dob, religion, martial_status, bloodGroup, username, gender, division, district, password, status, img) VALUES ('$full_name', '$phone', '$email', '$emergency_contact', '$refNumber', '$age', '$dob', '$religion', '$martial_status', '$bloodGroup', '$username', '$gender', '$division', '$district', '$password', 'pending', '$profile')");

    if ($insert) {
        echo '<script>alert("Form Submitted Successfully"); window.location.href = "id_card.php";</script>';
    } else {
        echo '<script>alert("Error: ' . mysqli_error($connect) . '");</script>';
    }

    mysqli_close($connect);
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient registration form</title>
    <style>
        .form-group input{
            width: 250px;
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        <div class="patient-form" style="margin-top: -750px;">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" class="my-2" style="margin-left: 230px;">
                <div class="form-group col-md-3">
                    <label>Patient Name:</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" autocomplete="off" placeholder="enter patient full name">
                </div>
                <div class="form-group col-md-3">
                    <label>Emergency Contact:</label>
                    <input type="text" name="emergency_contact" class="form-control" autocomplete="off" placeholder="enter patient em.contact" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Religion:</label>
                    <select name="religion" class="form-control" autocomplete="off" placeholder="select religion" required style="width:250px;" oninput="updatePhone()">
                        <option value="Select option">Select option</option>
                        <option value="Islam">Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Cristian">Cristian</option>
                        <option value="Sikh">Sikh</option>
                        <option value="Buddhist">Buddhist</option>
                    </select>

</div>
<div class="form-group col-md-3">
    <label for="gender">Gender:</label>
    <select name="gender" id="gender" class="form-control" required autocomplete="off" placeholder="Select Gender">
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Others">Others</option>
    </select>
    </div>
<div class="form-row">
    <div class="form-group col-md-3" style="margin-right: 0px;">
        <label>Email:</label>
        <input type="email" name="email" id="email" class="form-control" autocomplete="off" placeholder="Enter Your Email">
    </div>
    
    <div class="form-group col-md-3" >
        <label>UHID:</label>
        <input type="text" name="refNumber" id="refNumber" class="form-control" autocomplete="off" placeholder="Enter Ref. Number">
    </div>
    
    <div class="form-group col-md-3">
        <label>Martial Status:</label>
        <select name="martial_status" class="form-group">
            <option value="Married">Married</option>
            <option value="Unmarried">Unmarried</option>
        </select>
    </div>
    
    <div class="form-group col-md-3">
        <label>Select Division:</label>
        <select name="division" id="division" onchange="populateDistricts()">
    <option value="dhaka">Dhaka</option>
    <option value="barisal">Barisal</option>
    <option value="chittagong">Chittagong</option>
    <option value="khulna">Khulna</option>
    <option value="rajshahi">Rajshahi</option>
    <option value="sylhet">Sylhet</option>
    <option value="rangpur">Rangpur</option>
    <option value="mymensingh">Mymensingh</option>
</select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-3">
        <label>Phone</label>
        <input type="text" name="phone" autocomplete="off" placeholder="Enter Patient Phone Number" required class="form-control">
    </div>
    <div class="form-group col-md-3" style="margin-left: 0px;">
        <label>Age</label>
        <input type="text" name="age" class="form-control" autocomplete="off" placeholder="Enter Patient Age" required>
        </div>
        <div class="form-group col-md-3">
            <label>Blood Group</label>
            <select name="bloodGroup" class="form-control" style="width: 250px; height: 35px;">
                <option value="" style="width:250px; height: 35px;">Select Blood Group</option>
                <option value="A+">A+ Positive</option>
                <option value="A-">A- Negative</option>
                <option value="B+">B+ Positive</option>
                <option value="B-">B- Negitive</option>
                <option value="AB+">AB+ Positive</option>
                <option value="AB-">AB- Negative</option>
                <option value="O+">O+ Positive</option>
                <option value="O-">O- Negative</option>

            </select>



        </div>
    <div class="form-group col-md-3">
    <label>District:</label>
    <select name="district" id="district" required style="height:40px; width:300px;">
    <option value="" disabled selected>Select District</option>
</select>
</div>

<div class="form-row">
    <div class="form-group col-md-3" style="margin-right: 10px;">
        <label>Upload Image:</label>
        <input type="file" name="img" id="img" class="form-control" autocomplete="off" placeholder="Enter Your Email">
    </div>
    
    <div class="form-group col-md-3">
        <label>Date of Birth:</label>
        <input type="date" name="dob" id="dob" class="form-control" autocomplete="off" placeholder="Input Patient DOB">
    </div>
    
    <div class="form-group col-md-3">
        <label>Username:</label>
        <input type="text" name="username" id="username" class="form-control" autocomplete="off" placeholder="Enter Username">
    </div>
    
    <div class="form-group col-md-3">
        <label>Password:</label>
        <input type="password" name="password" id="password" class="form-control" autocomplete="off" placeholder="Enter Password">
    </div>
</div>
</div>

<div class="card" style="width:250px;">
    <input type="submit" class="btn btn-success" name="create" value="Submit">
    </form>
</div>
        </div>
    </div>
</form>

        </div>

    </div>

</div>

</div>    
</body>
<script src="script.js"></script>
</html>