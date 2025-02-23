<?php
session_start();
include("connection.php"); // Ensure this file sets up $connect

// Fetch user details if logged in
if (isset($_SESSION['doctor'])) {
    $user = $_SESSION['doctor'];

    // Prepare and execute the query to get doctor details
    $stmt = $connect->prepare("SELECT * FROM doctors WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $doctor_data = $result->fetch_assoc();
        $doctor_image = $doctor_data['profile']; // Use 'profile' column for the profile image
        $doctor_username = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    } else {
        $doctor_image = 'default-profile.png'; // Provide a default image if none is found
        $doctor_username = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    }
    $stmt->close();
} else {
    $doctor_image = 'default-profile.png'; // Provide a default image if not logged in
    $doctor_username = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-info bg-dark" style="height:60px;">
    <h5 class="text-white">Doctor Dashboard</h5>
    <div class="mr-auto"></div>
    <ul class="navbar-nav">
        <?php if (isset($_SESSION['doctor'])): ?>
            <li class="nav-item d-flex align-items-center">
                <a href="profile.php">
                    <img src="img/<?php echo htmlspecialchars($doctor_image, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Image" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                </a>
                <a href="profile.php" class="nav-link text-white"><?php echo $doctor_username; ?></a>
            </li>
            <li class="nav-item"><a href="logout.php" class="nav-link text-white">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a href="../login/login.php" class="nav-link">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
</body>
</html>
