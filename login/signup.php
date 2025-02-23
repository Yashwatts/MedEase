<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connect = new mysqli("localhost", "root", "", "hospital");

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $full_name = $connect->real_escape_string($_POST['full_name']);
    $email = $connect->real_escape_string($_POST['email']);
    $phone = $connect->real_escape_string($_POST['phone']);
    $username = $connect->real_escape_string($_POST['username']);
    $gender = $connect->real_escape_string($_POST['gender']);
    $password = password_hash($connect->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $profile = $connect->real_escape_string($_POST['profile']);
    $hospital_name = $connect->real_escape_string($_POST['hospital_name']);
    $hospital_location = $connect->real_escape_string($_POST['hospital_location']);

    // Check for validation errors
    $errors = [];
    if (empty($full_name)) {
        $errors[] = "Please enter your full name.";
    }
    if (empty($phone)) {
        $errors[] = "Please enter your phone number.";
    }
    if (empty($username)) {
        $errors[] = "Please enter a username.";
    }
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    }
    if (empty($gender)) {
        $errors[] = "Please select a gender.";
    }
    if (empty($_POST['password'])) {
        $errors[] = "Please enter a password.";
    }
    if (empty($hospital_name)) {
        $errors[] = "Please enter the hospital name.";
    }
    if (empty($hospital_location)) {
        $errors[] = "Please enter the hospital location.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo 'Error: ' . htmlspecialchars($error) . '<br>';
        }
    } else {
        // Check if the email already exists
        $check_query = "SELECT * FROM admin WHERE email=?";
        $stmt = $connect->prepare($check_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo 'You have already registered with this email.';
        } else {
            // Prepare and execute the INSERT statement
            $insert_query = "INSERT INTO admin (full_name, email, phone, username, gender, password, profile, hospital_name, hospital_location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $connect->prepare($insert_query);
            $stmt->bind_param("sssssssss", $full_name, $email, $phone, $username, $gender, $password, $profile, $hospital_name, $hospital_location);

            if ($stmt->execute()) {
                echo 'Registration Successful.';
            } else {
                echo 'Error: ' . $stmt->error; // Print the error directly for debugging
            }
        }
        $stmt->close();
    }
}

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Admin Registration Form</title>
</head>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
/* Your CSS here */
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
}

.container {
    max-width: 600px;
    margin-top: 50px;
}

.form-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h4 {
    margin-bottom: 20px;
    color: #343a40;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    border-radius: 4px;
    border: 1px solid #ced4da;
    padding: 10px;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    padding: 10px;
    color: #ffffff;
    font-size: 16px;
    font-weight: bold;
}

.btn-primary:hover {
    background-color: #0056b3;
    color: #ffffff;
}

.col {
    margin-top: 20px;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%;
}

@media (max-width: 767px) {
    .form-container {
        padding: 20px;
    }
    
    .btn-primary {
        width: 100%;
    }
}
</style>
<body>
    <div class="container">
        <div class="form-container">
            <form action="" method="post" id="signupForm">
                <h4 style="text-align: center;">Admin Registration Form</h4>
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="full_name" class="form-control" placeholder="Enter Your Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" class="form-control" placeholder="Enter Your Phone Number" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="hospital_name" class="form-control" placeholder="Enter Hospital Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="hospital_location" class="form-control" placeholder="Enter Hospital Location" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Enter Your Username" required>
                    </div>
                    <div class="form-group">
                        <select name="gender" class="form-control" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Enter Your Password" style="width:300px;" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="register" class="btn btn-primary" style="width:300px; margin-left: -30px;">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
