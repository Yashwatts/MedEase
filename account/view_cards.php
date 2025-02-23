<?php
include("header.php");
?>
<!DOCTYPE html>
<html>
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>Patient ID Card</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .container {
            display: flex;
        }
        .sidenav {
            margin-left: -207px;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="sidenav">
        <?php include("sidenav.php"); ?>
    </nav>
    <main>
        <div class="id_cardfor_patient" style="margin-left: 300px;">
            <img src="cover.png" alt="Additional Image" style="width: 700px; height: 420px; margin-top: 100px; margin-left: 115px;">
            
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hospital";
            
            // Create a database connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);   
            }

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $full_name = $_POST['full_name'];
                $dob = $_POST['dob'];
                $date_reg = $_POST['date_reg'];
                $refNumber = $_POST['refNumber'];
                $address = $_POST['address'];
                $image = $_POST['image']; // Corrected typo

                // Insert data into the patient_id table
                $insert_sql = "INSERT INTO patient_id (full_name, dob, date_reg, refNumber, address, image) 
                               VALUES ('$full_name', '$dob', '$date_reg', '$refNumber', '$address', '$image')";
                $conn->query($insert_sql);
            }

            // Retrieve the most recent patient data
            $sql = "SELECT * FROM patient_id ORDER BY id DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            ?>
            
            <div class="container">
                <!-- Patient Image -->
                <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Profile Image" style="width: 110px; height: 110px; margin-top: -300px; margin-left: 140px; margin-right: auto;">
                
                <!-- Patient Details Table -->
                <table style="margin-top: -300px; margin-left: -160px; height: 150px;">
                    <tr>
                        <td class="table-label">Patient Name:</td>
                        <td style="width:200px;"><?php echo $row['full_name']; ?></td> 
                    </tr>
                    <tr>
                        <td class="table-label">Patient DOB:</td>
                        <td><?php echo $row['dob']; ?></td> 
                    </tr>
                    <tr> 
                        <td class="table-label">Date of Reg:</td>
                        <td><b><?php echo $row['date_reg']; ?></td> 
                    </tr>
                    <tr>
                        <td class="table-label">UHID Number:</td>
                        <td><b><?php echo $row['refNumber']; ?></b></td>
                    </tr>
                    <tr>
                        <td class="table-label">Address:</td>
                        <td style="width:150px;"><?php echo $row['address']; ?></td> 
                    </tr>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
