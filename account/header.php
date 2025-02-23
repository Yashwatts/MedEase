<?php
session_start(); // Ensure session is started
include("connection.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Add your custom CSS and JS links here -->
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-info bg-dark">
    <h5 class="text-white">Account Branch Dashboard</h5>
    <div class="mr-auto"></div>
    <ul class="navbar-nav">
        <?php
        if (isset($_SESSION['account_branch'])) {
            $user = $_SESSION['account_branch'];

            // Fetch admin details including the profile image
            $query = "SELECT * FROM account_branch WHERE username='$user'";
            $result = mysqli_query($connect, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $admin_data = mysqli_fetch_assoc($result);
                $admin_image = $admin_data['profile']; // Assuming 'profile' is the field in your database containing the admin's profile image filename

                // Display admin image and username
                echo '<li class="nav-item d-flex align-items-center">
                    <a href="profile.php">
                        <img src="img/' . htmlspecialchars($admin_image) . '" alt="Admin Image" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    </a>
                    <a href="profile.php" class="nav-link text-white">' . htmlspecialchars($user) . '</a>
                </li>';
            }
            // Display logout button
            echo '<li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>';
        } else {
            // If not logged in, you might want to show different links or login options
            echo '<li class="nav-item"><a href="../login/login.php" class="nav-link text-white">Login</a></li>';
        }
        ?>
    </ul>
</nav>
</body>
</html>
