<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hospital';

// Function to connect to the database
function db_connect() {
    global $host, $user, $password, $dbname;
    $conn = new mysqli($host, $user, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Handle bed booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bed_id'])) {
    $bed_id = $_POST['bed_id'];

    $conn = db_connect();

    // Start transaction
    $conn->begin_transaction();

    try {
        // Check if a bed is available
        $stmt = $conn->prepare("SELECT available_beds FROM beds WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $bed_id);
        $stmt->execute();
        $stmt->bind_result($available_beds);
        $stmt->fetch();
        $stmt->close();

        if ($available_beds > 0) {
            // Decrement the number of available beds
            $new_available_beds = $available_beds - 1;
            $stmt = $conn->prepare("UPDATE beds SET available_beds = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_available_beds, $bed_id);
            if ($stmt->execute()) {
                $conn->commit();
                echo "Bed booked successfully.";
            } else {
                $conn->rollback();
                echo "Error updating bed availability: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "No beds available to book.";
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
