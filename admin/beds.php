<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    die("Access denied. Please log in.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch hospital name and location for the logged-in admin
$admin_username = $_SESSION['admin'];
$adminQuery = $conn->prepare("SELECT hospital_name, hospital_location FROM admin WHERE username = ?");
$adminQuery->bind_param("s", $admin_username);
$adminQuery->execute();
$adminResult = $adminQuery->get_result();

// Check if the query returned any rows
if ($adminResult->num_rows > 0) {
    $adminData = $adminResult->fetch_assoc();
} else {
    die("Admin data not found.");
}

// Check if form is submitted for adding a new bed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_bed'])) {
        // Edit bed logic
        $bed_id = $_POST['id'];
        $bed_type = $_POST['bed_type'];
        $total_beds = $_POST['total_beds'];
        $available_beds = $_POST['available_beds'];
        
        // Update bed information
        $stmt = $conn->prepare("UPDATE beds SET bed_type = ?, total_beds = ?, available_beds = ? WHERE id = ?");
        $stmt->bind_param("siii", $bed_type, $total_beds, $available_beds, $bed_id);

        if ($stmt->execute()) {
            echo "Bed updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        // Delete bed logic
        $bed_id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM beds WHERE id = ?");
        $stmt->bind_param("i", $bed_id);

        if ($stmt->execute()) {
            echo "Bed deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Add new bed logic
        $bed_type = $_POST['bed_type'];
        $total_beds = $_POST['total_beds'];
        $available_beds = $_POST['available_beds'];
        
        // Use the fetched hospital name and location from the admin table
        $hospital_name = isset($adminData['hospital_name']) ? $adminData['hospital_name'] : 'Unknown';
        $hospital_location = isset($adminData['hospital_location']) ? $adminData['hospital_location'] : 'Unknown';

        // Insert new bed information
        $stmt = $conn->prepare("INSERT INTO beds (bed_type, total_beds, available_beds, hospital_name, hospital_location) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiss", $bed_type, $total_beds, $available_beds, $hospital_name, $hospital_location);

        if ($stmt->execute()) {
         
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch existing beds for display
function fetch_beds() {
    global $conn;
    $sql = "SELECT * FROM beds";
    $result = $conn->query($sql);
    $beds = [];
    while ($row = $result->fetch_assoc()) {
        $beds[] = $row;
    }
    return $beds;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Availability Management</title>
    <style>
        /* Your existing styles */
         body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, grey, white);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        h2 {
            margin-top: 20px;
            color: #333;
            font-size: 48px;
            animation: fadeIn 2s ease-in-out;
            font-weight: bolder;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .form-container, .table-container {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
            transition: transform 0.3s;
            animation: fadeIn 1.5s ease-in-out;
        }

        .form-container:hover, .table-container:hover {
            transform: scale(1.02);
        }

        .form-container h3, .table-container h3 {
            color: #007bff;
            margin-top: 0;
            font-size: 24px;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"], button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .deleteBtn {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 15px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .deleteBtn:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        table thead {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        table th {
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table td button, table td .deleteBtn {
            border: none;
            background-color: #007bff;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 14px;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom: 5px;
        }

        table td button:hover {
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        table td .deleteBtn {
            background-color: #dc3545;
            color: #fff;
            max-width: 65px;
        }

        table td .deleteBtn:hover {
            background-color: #c82333;
        }

        .table-container {
            max-height: 400px; /* Adjust height as needed */
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <h2>Beds Management</h2>

    <div class="container">
        <!-- Add New Bed Form -->
        <div class="form-container">
            <form id="addBedForm" method="POST" action="beds.php" onsubmit="return validateForm()">
                <h3>Add Bed</h3>
                <label for="bed_type">Bed Type:</label>
                <select name="bed_type" id="bed_type" required>
                    <option value="">Select Bed Type</option>
                    <option value="ICU">ICU</option>
                    <option value="General">General</option>
                    <option value="Private">Private</option>
                </select>

                <label for="total_beds">Total Beds:</label>
                <input type="number" id="total_beds" name="total_beds" required>

                <label for="available_beds">Available Beds:</label>
                <input type="number" id="available_beds" name="available_beds" required>

                <input type="submit" value="Add Bed">
            </form>
        </div>

        <!-- Beds Table -->
        <div class="table-container">
            <h3>Existing Beds</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bed Type</th>
                        <th>Total Beds</th>
                        <th>Available Beds</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $beds = fetch_beds();
                    foreach ($beds as $bed) {
                        echo "<tr>
                            <td>" . htmlspecialchars($bed['id']) . "</td>
                            <td>" . htmlspecialchars($bed['bed_type']) . "</td>
                            <td>" . htmlspecialchars($bed['total_beds']) . "</td>
                            <td>" . htmlspecialchars($bed['available_beds']) . "</td>
                            <td>
                                <form method='POST' action='beds.php' style='display:inline;'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($bed['id']) . "'>
                                    <input type='submit' name='delete' value='Delete' class='deleteBtn'>
                                </form>
                                <button onclick='editBed(" . htmlspecialchars($bed['id']) . ")'>Edit</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editBed(id) {
            // Function to fill the form with existing bed details for editing
            // You may need to implement this based on your requirements
            alert('Edit functionality for bed ID: ' + id);
        }

        function validateForm() {
            // Validate form input before submission
            var bedType = document.getElementById('bed_type').value;
            var totalBeds = document.getElementById('total_beds').value;
            var availableBeds = document.getElementById('available_beds').value;

            if (bedType === "" || totalBeds === "" || availableBeds === "") {
                alert("Please fill out all fields.");
                return false;
            }

            if (parseInt(availableBeds) > parseInt(totalBeds)) {
                alert("Available beds cannot be more than total beds.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
