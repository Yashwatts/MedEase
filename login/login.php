<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Log the role and username for debugging
    error_log("Role: $role, Username: $username");

    // Prepare the query based on the role
    switch ($role) {
        case 'admin':
            $query = $conn->prepare("SELECT * FROM admin WHERE username=?");
            break;
        case 'doctor':
            $query = $conn->prepare("SELECT * FROM doctors WHERE username=?");
            break;
        case 'patient':
            $query = $conn->prepare("SELECT * FROM patient WHERE username=?");
            break;
        case 'employee':
            $query = $conn->prepare("SELECT * FROM employee WHERE username=?");
            break;
        case 'account_branch':
            $query = $conn->prepare("SELECT * FROM account_branch WHERE username=?");
            break;
        default:
            $response = ['success' => false, 'message' => 'Role not recognized'];
            echo json_encode($response);
            exit();
    }

    // Bind parameters and execute the query
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // Log the stored hash for debugging
    error_log("Stored Hash: " . $user['password']);
    error_log("Entered Password: " . $password);

    if ($role == 'account_branch' || $role == 'doctor' || $role == 'employee') {
        // For account_branch, compare the password directly (not secure for production)
        if ($password === $user['password']) {
            $_SESSION[$role] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            $_SESSION['full_name'] = $user['full_name'];

            $response = ['success' => true, 'role' => $role];
        } else {
            error_log("Password verification failed.");
            $response = ['success' => false, 'message' => 'Invalid Login Details'];
        }
    } else {
        // For all other roles, use password_verify()
        if (password_verify($password, $user['password'])) {
            $_SESSION[$role] = $username;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            $_SESSION['full_name'] = $user['full_name'];

            $response = ['success' => true, 'role' => $role];
        } else {
            error_log("Password verification failed.");
            $response = ['success' => false, 'message' => 'Invalid Login Details'];
        }
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid Login Details'];
}


    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>





<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Login Form</title>
    <style>
        .hidden-content {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form id="loginForm" method="post">
                <h4>Sign In</h4>
                <div class="icons">
                    <a href="" class="icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="" class="icon"><i class="fab fa-facebook"></i></a>
                    <a href="" class="icon"><i class="fab fa-github"></i></a>
                    <a href="" class="icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="" class="icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                    <option value="employee">Employee</option>
                    <option value="account_branch">Account Branch</option>
                </select>
                <input type="text" name="username" id="username" placeholder="Enter Your Username" required>
                <input type="password" name="password" placeholder="Enter Your Password" required>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            <?php
            // Display errors if any
            if ($errors) {
                echo "<h5 class='text-center alert alert-danger'>$errors</h5>";
            }
            ?>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                
                <button id="sign-up23" style="margin-top: 12rem;margin-left:8rem" onclick="loadSignUp()">Sign Up</button>

                <div class="icons" style="margin-left:9.2rem; margin-top:-4.4rem;">
                    <a href="" class="icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="" class="icon"><i class="fab fa-facebook"></i></a>
                    <a href="" class="icon"><i class="fab fa-github"></i></a>
                    <a href="" class="icon"><i class="fab fa-google-plus-g"></i></a>
                    <a href="" class="icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width:800px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="width:500px;">
                    <!-- Content from signup.php will load here -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>