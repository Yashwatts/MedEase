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

$sql = "SELECT * FROM doctors WHERE status ='approved'";
$result = $conn->query($sql);

$results = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $deleted = $_POST['delete_id'];  // Corrected from intdiv()
    $stmt = $conn->prepare("UPDATE doctors SET status = 'deleted' WHERE id = ?");
    $stmt->bind_param("i", $deleted);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo 'Error updating record: ' . $conn->error;
    }
}

// Move this section above `conn->close()`
$ad = mysqli_query($conn, "SELECT * FROM doctors WHERE status = 'approved'");
$num = mysqli_num_rows($ad);

// Close the connection after all queries
$conn->close();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
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
                                            <th>Doctor Name</th>
                                            <th>Department</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $row) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td>
                                                <a href="#" data-id="<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-view"> <i class="fa-solid fa-eye"></i></a>

                                                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" style="display: inline;">
                                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                    <button type="submit" class="btn btn-danger"> <i class="fa-solid fa-trash"></i></button>
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

    <!-- Modal for viewing doctor details -->
    <div id="doctorModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Doctor Details</h5>
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
                
                var doctorId = $(this).data('id');
                
                $.ajax({
                    url: 'view_doctor.php',
                    type: 'GET',
                    data: {id: doctorId},
                    success: function(response) {
                        // Load the content into the modal body
                        $('#doctorModal .modal-body').html(response);
                        // Show the modal
                        $('#doctorModal').modal('show');
                    },
                    error: function() {
                        alert('An error occurred while fetching the doctor details.');
                    }
                });
            });
        });
    </script>
</body>
</html>
