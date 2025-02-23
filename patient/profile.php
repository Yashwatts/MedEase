<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "header.php";
include "connection.php";

if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the patient session is active
if (!isset($_SESSION["patient"]) || empty($_SESSION["patient"])) {
    die("No patient session found.");
}

$ad = $_SESSION["patient"];

// Fetch user data
$query = "SELECT * FROM patient WHERE username = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "s", $ad);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    die("Query failed: " . mysqli_error($connect));
}

$row = mysqli_fetch_array($res);
if (!$row) {
    die("User not found.");
}

$username = $row["username"];
$img = $row["img"];
$updateMessage = "";

// Handle profile image update
if (isset($_POST["update"])) {
    if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] == UPLOAD_ERR_OK) {
        $profileFile = $_FILES["profile"];
        $profileName = basename($profileFile["name"]);
        $profileTmp = $profileFile["tmp_name"];

        // Define the upload path
        $uploadPath = "uploads/" . $profileName;

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($profileTmp, $uploadPath)) {
            $updateQuery = "UPDATE patient SET img = ? WHERE username = ?";
            $stmt = mysqli_prepare($connect, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $profileName, $ad);
            $updateResult = mysqli_stmt_execute($stmt);

            if ($updateResult) {
                $updateMessage = "<div class='alert alert-success'>Profile updated successfully</div>";
            } else {
                $updateMessage = "<div class='alert alert-danger'>Failed to update profile</div>";
            }
        } else {
            $updateMessage = "<div class='alert alert-danger'>Failed to move uploaded file</div>";
        }
    } else {
        $updateMessage = "<div class='alert alert-danger'>No file selected or upload error occurred</div>";
    }
}

// Handle username change
if (isset($_POST["change"])) {
    $uname = $_POST["uname"];
    if (!empty($uname)) {
        $query = "UPDATE patient SET username = ? WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ss", $uname, $ad);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $_SESSION["patient"] = $uname; // Update session
            $updateMessage = "<div class='alert alert-success'>Username updated successfully</div>";
        } else {
            $updateMessage = "<div class='alert alert-danger'>Failed to update username</div>";
        }
    } else {
        $updateMessage = "<div class='alert alert-danger'>Username cannot be empty</div>";
    }
}

// Handle password change
if (isset($_POST["update_pass"])) {
    $old_pass = $_POST["old_pass"];
    $new_pass = $_POST["new_pass"];
    $con_pass = $_POST["con_pass"];
    $error = [];

    $query = "SELECT password FROM patient WHERE username = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $ad);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($res);
    $pass = $row["password"];

    if (empty($old_pass)) {
        $error["p"] = "Enter old password";
    } elseif (empty($new_pass)) {
        $error["p"] = "Enter new password";
    } elseif (empty($con_pass)) {
        $error["p"] = "Confirm password";
    } elseif (!password_verify($old_pass, $pass)) {
        $error["p"] = "Incorrect old password";
    } elseif ($new_pass != $con_pass) {
        $error["p"] = "Passwords do not match";
    } else {
        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $query = "UPDATE patient SET password = ? WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_new_pass, $ad);
        $updateResult = mysqli_stmt_execute($stmt);

        if ($updateResult) {
            $updateMessage = "<div class='alert alert-success'>Password updated successfully</div>";
        } else {
            $updateMessage = "<div class='alert alert-danger'>Failed to update password</div>";
        }
    }

    if (isset($error["p"])) {
        $updateMessage = "<div class='alert alert-danger text-center'>" . htmlspecialchars($error["p"]) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center"><?php echo htmlspecialchars($username); ?>'s Profile</h4>
                    <div class="text-center">
                        <img src="uploads/<?php echo htmlspecialchars($img ?? 'default.png'); ?>" class="rounded-circle" width="150" alt="Profile Picture">
                    </div>
                    <form method="post" enctype="multipart/form-data" class="mt-4 text-center">
                        <input type="file" name="profile" class="form-control mb-3" style="max-width: 300px; margin: auto;">
                        <button type="submit" name="update" class="btn btn-success">Update Profile</button>
                    </form>

                    <hr>

                    <form method="post" class="mt-4">
                        <h5>Change Username</h5>
                        <input type="text" name="uname" class="form-control mb-3" placeholder="Enter New Username" required>
                        <button type="submit" name="change" class="btn btn-success">Submit</button>
                    </form>

                    <hr>

                    <form method="post" class="mt-4">
                        <h5>Change Password</h5>
                        <input type="password" name="old_pass" class="form-control mb-2" placeholder="Old Password">
                        <input type="password" name="new_pass" class="form-control mb-2" placeholder="New Password">
                        <input type="password" name="con_pass" class="form-control mb-2" placeholder="Confirm Password">
                        <button type="submit" name="update_pass" class="btn btn-success">Update Password</button>
                    </form>

                    <?php if (!empty($updateMessage)) { echo $updateMessage; } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sidenav" style="margin-top: -807px; margin-left: -5px;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>
