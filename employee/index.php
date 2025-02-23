<?php include("header.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lab Dashboard</title>

    <style>
    .dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    padding: 20px;
}

.card {
    position: relative;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1,0,0,1.0);
    flex: 1 1 calc(33.333% - 20px);
    padding: 15px;
    overflow: hidden;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 8px 16px rgba(0, 0, 0, 0.1);
}

.card-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.75rem;
    color: #007bff;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 15px;
    color: #333333;
}

.card-text {
    font-size: 0.9rem;
    color: #666666;
    margin-top: 8px;
}

.card:nth-child(1) .card-icon {
    color: #28a745;
}

.card:nth-child(2) .card-icon {
    color: #ffc107;
}

.card:nth-child(3) .card-icon {
    color: #17a2b8;
}

.card:nth-child(4) .card-icon {
    color: #dc3545;
}

.card:nth-child(5) .card-icon {
    color: #6f42c1;
}

.card:nth-child(6) .card-icon {
    color: #fd7e14;
}


    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" style="margin-left: -5px;">
                    <?php include("sidenav.php"); ?>

                </div>
                <div class="col-md-10">
                    <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">

                            <div class="dashboard">
                                <div class="card">
                                    <a href="profile.php" class="card-link">
                                    <i class="fas fa-user-md card-icon text-white"></i>
                                    <h3 class="card-title">Profile</h3>
                                    <p class="card-text">Manage Your Profile</p>
                                    </a>
                                </div>
                                <div class="card" style="background-color:#16398a;">
                                    <a href="upload_report.php" class="card-link">
                                    <i class="fas fa-file-upload card-icon text-warning"></i>
                                    <h3 class="card-title text-white">Upload Report</h3>
                                    <p class="card-text text-white">Upload Patient Report</p>
                                    </a>
                                </div>
                                <div class="card" style="background-color:#b9b64e;">
                                    <a href="#" class="card-link">
                                    <i class="fas fa-user-md card-icon"></i>
                                    <h3 class="card-title">Total Report</h3>
                                    <p class="card-text">Manage Total Report</p>
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
