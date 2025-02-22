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

// Fetch all beds from the database
function fetch_beds() {
    $conn = db_connect();
    $sql = "SELECT * FROM beds";
    $result = $conn->query($sql);
    
    $beds = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $beds[] = $row;
        }
    }

    $conn->close();
    return $beds;
}

// Add a new bed
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = db_connect();

    // Add Bed
    if (isset($_POST['bed_type']) && isset($_POST['total_beds']) && isset($_POST['available_beds']) && !isset($_POST['edit_bed'])) {
        $bed_type = $_POST['bed_type'];
        $total_beds = $_POST['total_beds'];
        $available_beds = $_POST['available_beds'];

        $stmt = $conn->prepare("INSERT INTO beds (bed_type, total_beds, available_beds) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $bed_type, $total_beds, $available_beds);
        
        if ($stmt->execute()) {
            header('Location: beds.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Edit Bed
    if (isset($_POST['edit_bed']) && isset($_POST['id']) && isset($_POST['bed_type']) && isset($_POST['total_beds']) && isset($_POST['available_beds'])) {
        $id = $_POST['id'];
        $bed_type = $_POST['bed_type'];
        $total_beds = $_POST['total_beds'];
        $available_beds = $_POST['available_beds'];

        $stmt = $conn->prepare("UPDATE beds SET bed_type=?, total_beds=?, available_beds=? WHERE id=?");
        $stmt->bind_param("siii", $bed_type, $total_beds, $available_beds, $id);
        
        if ($stmt->execute()) {
            header('Location: beds.php');
            exit();
        } else {
            echo "Error updating bed: " . $stmt->error;
        }
        $stmt->close();
    }

    // Delete Bed
    if (isset($_POST['delete']) && isset($_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM beds WHERE id=?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header('Location: beds.php');
            exit();
        } else {
            echo "Error deleting bed: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}
?>
