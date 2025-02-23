<?php
include("header.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Updated SQL query to join patient and account_branch tables
$sql = "
    SELECT patient.* 
    FROM patient 
    INNER JOIN account_branch ON patient.hospital_name = account_branch.hospital_name 
        AND patient.hospital_location = account_branch.hospital_location 
    WHERE patient.status = 'approved'
";
$result = $conn->query($sql);

if ($result === false) {
    // Output error details if the query failed
    die("Query failed: " . $conn->error);
}

// Fetch results
$results = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $deleted = $_POST['delete_id'];
    $stmt = $conn->prepare("UPDATE patient SET status = 'deleted' WHERE id = ?");
    $stmt->bind_param("i", $deleted);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo 'Error updating record: ' . $conn->error;
    }
}

// Fetch number of approved patients
$ad = $conn->query("SELECT * FROM patient WHERE status = 'approved'");
if ($ad === false) {
    die("Query failed: " . $conn->error);
}
$num = $ad->num_rows;

// Close the connection after all queries
$conn->close();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2" style="margin-left:-30px; min-height: 42.8rem;">
                    <?php include("sidenav.php"); ?>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-10">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $row) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td>
                                                <a href="#" data-id="<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-view"> <i class="fa-solid fa-eye"></i></a>

                                                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" style="display: inline;">
                                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for viewing patient details -->
    <div id="patientModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Patient Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded here by AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle the "View" button click
            $('.btn-view').click(function(event) {
                event.preventDefault();
                
                var patientId = $(this).data('id');
                
                $.ajax({
                    url: 'view_patient.php',
                    type: 'GET',
                    data: {id: patientId},
                    success: function(response) {
                        // Load the content into the modal body
                        $('#patientModal .modal-body').html(response);
                        // Show the modal
                        $('#patientModal').modal('show');
                    },
                    error: function() {
                        alert('An error occurred while fetching the patient details.');
                    }
                });
            });
        });
    </script>
</body>
</html>
