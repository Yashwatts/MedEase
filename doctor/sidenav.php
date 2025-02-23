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
    <div class="list-group bg-dark" style="height: 110vh; width: 200px;">
        <a href="index.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fas fa-tachometer-alt"></i></span>Dashboard
        </a>
        <a href="set_timeslots.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-clock"></i></span>Set Timeslots
        </a>
        <a href="patient.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-hospital-user"></i></span>Patients
        </a>
        <a href="appointment.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-file-invoice"></i></span>Appointment
        </a>
        <a href="general_appointment.php" class="list-group-item list-group-item-action bg-dark text-center text-white">
            <span class="animate_animated animate_heartBeat"><i class="fa-solid fa-file-waveform"></i></span>Hospital Appointment
        </a>
    </div>
</body>
</html>
