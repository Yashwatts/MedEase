<?php
include("header.php");
include("connection.php"); // Ensure this file sets up $connect

// Check if the session variable is set
if (!isset($_SESSION['doctor'])) {
    die("You are not logged in as a doctor.");
}

// Set the full name from the session
$doctor_full_name = $_SESSION['doctor'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $slot_start = $_POST['slot_start'];
    $slot_end = $_POST['slot_end'];
    $date = $_POST['date'];

    // Check if the full name exists in the doctors table
    $check_query = "SELECT full_name FROM doctors WHERE full_name = ?";
    $stmt = $connect->prepare($check_query);
    $stmt->bind_param("s", $doctor_full_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Prepare the SQL statement
        $stmt = $connect->prepare("INSERT INTO doctor_timeslots (doctor_full_name, slot_start, slot_end, date) VALUES (?, ?, ?, ?)");

        // Check if prepare() was successful
        if (!$stmt) {
            die("Prepare failed: " . $connect->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("ssss", $doctor_full_name, $slot_start, $slot_end, $date);

        if ($stmt->execute()) {
            echo "Timeslot added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Doctor's full name does not exist in the database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Timeslots</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Set Your Available Timeslots</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" id="date" required>
        </div>
        <div class="form-group">
            <label for="slot_start">Start Time</label>
            <input type="time" name="slot_start" class="form-control" id="slot_start" required>
        </div>
        <div class="form-group">
            <label for="slot_end">End Time</label>
            <input type="time" name="slot_end" class="form-control" id="slot_end" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Timeslot</button>
    </form>
</div>
<div class="sidenav" style="margin-top: -344px; margin-left: -5px;">
        <?php include("sidenav.php"); ?>
    </div>
</body>
</html>
