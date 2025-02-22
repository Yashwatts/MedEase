<?php
$connection = new mysqli('localhost', 'root', '', 'hospital');

$data = json_decode(file_get_contents('php://input'), true);

$item_name = $data['item_name'];
$item_type = $data['item_type'];
$quantity = $data['quantity'];
$description = $data['description'];

$query = "INSERT INTO inventory (item_name, item_type, quantity, description) VALUES ('$item_name', '$item_type', $quantity, '$description')";

if ($connection->query($query)) {
    echo "Item added successfully";
} else {
    echo "Error: " . $connection->error;
}

$connection->close();
?>
