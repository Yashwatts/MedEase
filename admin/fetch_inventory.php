<?php
$connection = new mysqli('localhost', 'root', '', 'hospital');

$query = "SELECT * FROM inventory";
$result = $connection->query($query);

$inventory = [];

while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}

echo json_encode($inventory);

$connection->close();
?>
