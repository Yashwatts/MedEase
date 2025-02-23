<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "header.php";
include "connection.php";

if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION["employee"]) || empty($_SESSION["employee"])) {
    die("No employee session found.");
}

$ad = $_SESSION["employee"];

// Fetch user data
$query = "SELECT * FROM employee WHERE username = ?";
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
$profile = $row["profile"];

$updateMessage = "";

// Handle profile update
if (isset($_POST["update"])) {
    if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] == UPLOAD_ERR_OK) {
        $profileFile = $_FILES["profile"];
        $profileName = basename($profileFile["name"]);
        $profileTmp = $profileFile["tmp_name"];
        
        // Move the uploaded file to the desired directory
        $uploadPath = "img/" . $profileName;
        if (move_uploaded_file($profileTmp, $uploadPath)) {
            $updateQuery = "UPDATE employee SET profile = ? WHERE username = ?";
            $stmt = mysqli_prepare($connect, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $profileName, $ad);
            $updateResult = mysqli_stmt_execute($stmt);
            
            if (!$updateResult) {
                die("Query failed: " . mysqli_error($connect));
            }

            $updateMessage = "Profile updated successfully";
        } else {
            $updateMessage = "Failed to move uploaded file.";
        }
    } else {
        $updateMessage = "Failed to upload profile picture.";
    }
}

// Handle username change
if (isset($_POST["change"])) {
    $uname = $_POST["uname"];
    if (!empty($uname)) {
        $query = "UPDATE employee SET username = ? WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ss", $uname, $ad);
        $res = mysqli_stmt_execute($stmt);
        
        if (!$res) {
            die("Query failed: " . mysqli_error($connect));
        }

        $_SESSION["employee"] = $uname;
        $updateMessage = "Username updated successfully";
    } else {
        $updateMessage = "Username cannot be empty.";
    }
}

// Handle password change
if (isset($_POST["update_pass"])) {
    $old_pass = $_POST["old_pass"];
    $new_pass = $_POST["new_pass"];
    $con_pass = $_POST["con_pass"];
    $error = [];

    $query = "SELECT password FROM employee WHERE username = ?";
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
    } elseif ($old_pass != $pass) {
        $error["p"] = "Incorrect old password";
    } elseif ($new_pass != $con_pass) {
        $error["p"] = "Passwords do not match";
    } else {
        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $query = "UPDATE employee SET password = ? WHERE username = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_new_pass, $ad);
        $updateResult = mysqli_stmt_execute($stmt);
        
        if (!$updateResult) {
            die("Query failed: " . mysqli_error($connect));
        }

        $updateMessage = "Password updated successfully";
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
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container { margin-top: 50px; }
        .profile-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }
        .green-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .red-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="profile-card bg-light">
                    <h4 class="text-center"><?php echo htmlspecialchars($username); ?>'s Profile</h4>
                    <div class="text-center">
                        <img src="<?php echo isset($profile) ? "img/" . htmlspecialchars($profile) : 'img/default.png'; ?>" 
                             class="profile-image" alt="Profile Picture">
                    </div>
                    <form method="post" enctype="multipart/form-data" class="text-center">
                        <input type="file" name="profile" class="form-control mb-3" style="max-width: 300px; margin: auto;">
                        <button type="submit" name="update" class="green-btn">Update Profile</button>
                    </form>

                    <hr>

                    <form method="post" class="mt-4">
                        <h5>Change Username</h5>
                        <input type="text" name="uname" class="form-control mb-3" placeholder="Enter New Username" required>
                        <button type="submit" name="change" class="green-btn">Submit</button>
                    </form>

                    <hr>

                    <form method="post" class="mt-4">
                        <h5>Change Password</h5>
                        <input type="password" name="old_pass" class="form-control mb-2" placeholder="Old Password">
                        <input type="password" name="new_pass" class="form-control mb-2" placeholder="New Password">
                        <input type="password" name="con_pass" class="form-control mb-2" placeholder="Confirm Password">
                        <button type="submit" name="update_pass" class="green-btn">Update Password</button>
                    </form>

                    <?php if (!empty($updateMessage)) { echo $updateMessage; } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="sidenav" style="margin-top: -786px; height: 50rem; margin-left: -2px;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>
