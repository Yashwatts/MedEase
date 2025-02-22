<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Animate.css link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sidenav</title>
    <style>
        .list-group {
            border: none;
            display: flex;
            align-items: center;
            justify-content: flex-start;

        }
        .list-group span {
            display: inline-block;
            margin-right: 8px;
        }
        

    </style>
</head>
<body>
    <div class="list-group bg-dark" style="height: 100%; width: 200px;">
        <a href="index.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fas fa-tachometer-alt"></i></span>Dashboard
        </a>
        <a href="doctor.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-user-doctor"></i></span>Doctors
        </a>
        <a href="patient.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-hospital-user"></i></span>Patient
        </a>
        <a href="beds.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-computer"></i></span>Manage Beds
        </a>
        <a href="inventory.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-computer"></i></span>Manage Inventory
        </a>
        <a href="chat.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-address-card"></i></span>Chat
        </a>
        <a href="emergency_patients.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-address-card"></i></span>Emergency patients
        </a>
    </div>
</body>
</html>
