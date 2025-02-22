<?php
include('header.php'); // Ensure this file connects to your database and starts the session
include 'connection.php'; // Ensure this file connects to your database

// Fetch complaints from the database
$query = "SELECT * FROM complaint";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Complaints</title>
    <style>

        .container-fluid {
            margin-top: 20px;
        }

        .table thead th {
            background-color: #78AEC6;
            color: #ffffff;
        }

        .btn-resolve {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-resolve:hover {
            background-color: #218838;
        }

        .btn-pending {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-pending:hover {
            background-color: #c82333;
        }

        .status-tick {
            color: green;
            font-size: 1.5rem;
            display: none;
        }

        .status {
            display: flex;
            align-items: center;
        }

        .status-tick {
            margin-left: 10px;
        }

        .row {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="col-md-2" style="margin-left:-230px;height: 48rem;">

                    <?php include("sidenav.php"); ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-10" style="margin-top: -47rem; left: -10rem;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Date Sent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr data-id="<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['message']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_send']); ?></td>
                                <td class='status'>
                                    <span class="status-text"><?php echo htmlspecialchars($row['status']); ?></span>
                                    <span class="status-tick">&#10003;</span>
                                </td>
                                <td>
                                    <a href="#" class='btn btn-resolve btn-sm' data-id='<?php echo $row['id']; ?>' style="display: <?php echo ($row['status'] == 'Pending') ? 'inline' : 'none'; ?>;">Resolve</a>
                                    <span class="status-tick" style="display: <?php echo ($row['status'] == 'Resolved') ? 'inline' : 'none'; ?>;">&#10003;</span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle the resolve button click
            $('.btn-resolve').click(function(e) {
                e.preventDefault();
                var complaintId = $(this).data('id');
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: { id: complaintId, status: 'Resolved' },
                    success: function(response) {
                        console.log('Resolve response:', response); // Debugging line
                        var row = $('tr[data-id="' + complaintId + '"]');
                        row.find('.status-text').text('Resolved');
                        row.find('.btn-resolve').hide();
                        row.find('.btn-pending').hide();
                        row.find('.status-tick').show();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Debugging line
                    }
                });
            });

            // Handle the pending button click
            $('.btn-pending').click(function(e) {
                e.preventDefault();
                var complaintId = $(this).data('id');
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: { id: complaintId, status: 'Pending' },
                    success: function(response) {
                        console.log('Pending response:', response); // Debugging line
                        var row = $('tr[data-id="' + complaintId + '"]');
                        row.find('.status-text').text('Pending');
                        row.find('.btn-resolve').show();
                        row.find('.btn-pending').show();
                        row.find('.status-tick').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error); // Debugging line
                    }
                });
            });
        });
    </script>
</body>
</html>
