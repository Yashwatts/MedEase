<?php
// view_complaints.php
include 'header.php';
// Database connection parameters
$servername = "localhost"; // Change if needed
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "hospital"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search variable
$searchName = '';
if (isset($_POST['search'])) {
    $searchName = $_POST['search'];
}

// SQL query to fetch details from the complaint table with optional search
$sql = "SELECT name, title, message, date_send, status FROM complaint";
if ($searchName) {
    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($searchName) . "%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 1100px;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 300px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .search-container {
            margin-left: 300px;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 8px;
            width: 300px;
        }
        .search-container input[type="submit"] {
            padding: 8px 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 style="margin-left: 690px;">Complaints List</h1>
    
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($searchName); ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Title</th>
                <th>Message</th>
                <th>Date Sent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>" . htmlspecialchars($row["title"]) . "</td>
                            <td>" . htmlspecialchars($row["message"]) . "</td>
                            <td>" . htmlspecialchars($row["date_send"]) . "</td>
                            <td>" . htmlspecialchars($row["status"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No complaints found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    $conn->close(); // Close the database connection
    ?>
    <div class="sidenav" style="margin-top: -693px; margin-left: -5px; position: absolute;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>
