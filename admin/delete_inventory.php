<?php
$connection = new mysqli('localhost', 'root', '', 'hospital');

$id = $_GET['id'];

$query = "DELETE FROM inventory WHERE id=$id";

if ($connection->query($query)) {
    echo "Item deleted successfully";
} else {
    echo "Error: " . $connection->error;
}

$connection->close();
?>
