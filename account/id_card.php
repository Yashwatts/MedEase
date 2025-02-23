<?php
include("header.php");
// session_start(); // Ensure session is started

// if (!isset($_SESSION['account_branch'])) {
//     header("Location: ../role.php");
//     exit();
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $full_name = isset($_POST['full_name']) ? $conn->real_escape_string($_POST['full_name']) : "";
    $dob = isset($_POST['dob']) ? $conn->real_escape_string($_POST['dob']) : "";
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : "";
    $refNumber = isset($_POST['refNumber']) ? $conn->real_escape_string($_POST['refNumber']) : "";

    // Image upload processing
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['image']['name']); // Make sure the form input name is correct
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image already exists in the database
    $checkExisting = "SELECT COUNT(*) AS count FROM patient_id WHERE image = '$targetFile'";
    $result = $conn->query($checkExisting);
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "<script>showMessage('Sorry, file already exists in the database.', 'alert-danger');</script>";
        $uploadOk = 0;
    }

    // Check if file is an image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists in the upload folder
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Limit file size to 500KB
    if ($_FILES['image']['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific image formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Try to upload the file if no errors occurred
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

            // Insert data into the database
            $sql = "INSERT INTO patient_id (full_name, dob, address, refNumber, image, date_reg)
                    VALUES ('$full_name', '$dob', '$address', '$refNumber', '$targetFile', NOW())";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                echo '<script>window.location.href = "view_cards.php";</script>'; // Redirect after success
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient ID</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <div class="container position-relative" style="margin-left:250px;">
                <form action="" method="post" enctype="multipart/form-data">
                    <h5 class="mb-4 text-center">Patient ID</h5>
                    <div class="form-group">
                        <label for="full_name">Patient Name:</label>
                        <input type="text" name="full_name" id="full_name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="dob">DOB:</label>
                        <input type="text" name="dob" id="dob" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" id="address" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="refNumber">UHID:</label>
                        <input type="text" name="refNumber" id="refNumber" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" name="image" id="image" accept="image/*" required class="form-control" style="width: 450px;">
                        <img src="" id="previewImage" style="max-width: 100px; max-height: 100px; display: none;" class="form-control">
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-id-card"></i> Get Patient ID</button>
                </form>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function showMessage(message, alertType) {
        var notification = document.createElement("div");
        notification.className = "alert " + alertType;
        notification.innerHTML = message;
        document.body.appendChild(notification);
        setTimeout(function() {
            notification.remove();
        }, 5000);
    }

    $(document).ready(function() {
        $('#image').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#previewImage').hide();
            }
        });
    });
    </script>
</body>
</html>
