<?php
session_start();
include("connection.php"); // Ensure this file sets up $connect

// Fetch user details if logged in
if (isset($_SESSION['patient'])) {
    $user = $_SESSION['patient'];

    // Prepare and execute the query to get patient details
    $stmt = $connect->prepare("SELECT * FROM patient WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $patient_data = $result->fetch_assoc();
        $patient_image = $patient_data['img']; // Assuming 'img' is the field for profile image filename
        $patient_username = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    } else {
        $patient_image = 'default-profile.png'; // Provide a default image if none is found
        $patient_username = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
    }
    $stmt->close();
} else {
    $patient_image = 'default-profile.png'; // Provide a default image if not logged in
    $patient_username = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .navbar {
            height: 60px;
        }
        .navbar-brand {
            font-size: 1.5rem;
        }
        .navbar-nav img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-info bg-dark">
    <a class="navbar-brand text-white" href="#">Patient Dashboard</a>
    <div class="mr-auto"></div>
    <ul class="navbar-nav">
        <?php if (isset($_SESSION['patient'])): ?>
            <li class="nav-item d-flex align-items-center">
                <a href="profile.php">
                    <img src="uploads/<?php echo htmlspecialchars($patient_image, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Image">
                </a>
                <a href="profile.php" class="nav-link"><?php echo $patient_username; ?></a>
            </li>
            <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a href="#" class="nav-link">Admin</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Doctor</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Patient</a></li>
        <?php endif; ?>
    </ul>
</nav>
</body>
</html>
