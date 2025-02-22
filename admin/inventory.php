<?php
// session_start();
include("header.php");
include("sidenav.php");
// Database connection
$connection = new mysqli('localhost', 'root', '', 'hospital');

// Check if the connection was successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_item'])) {
        // Add new item
        $item_name = $connection->real_escape_string($_POST['item_name']);
        $item_type = $connection->real_escape_string($_POST['item_type']);
        $quantity = (int)$_POST['quantity'];  // Ensure quantity is an integer
        $description = $connection->real_escape_string($_POST['description']);

        $insert_query = "INSERT INTO inventory (item_name, item_type, quantity, description) 
                         VALUES ('$item_name', '$item_type', $quantity, '$description')";

        if ($connection->query($insert_query) === TRUE) {
            header("Location: inventory.php");
            exit();
        } else {
            echo "<p>Error: " . $connection->error . "</p>";
        }
    } elseif (isset($_POST['update_item'])) {
        // Update existing item
        $item_id = (int)$_POST['item_id'];
        $item_name = $connection->real_escape_string($_POST['item_name']);
        $item_type = $connection->real_escape_string($_POST['item_type']);
        $quantity = (int)$_POST['quantity'];
        $description = $connection->real_escape_string($_POST['description']);

        $update_query = "UPDATE inventory SET item_name='$item_name', item_type='$item_type', quantity=$quantity, description='$description' WHERE id=$item_id";

        if ($connection->query($update_query) === TRUE) {
            header("Location: inventory.php");
            exit();
        } else {
            echo "<p>Error: " . $connection->error . "</p>";
        }
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete_query = "DELETE FROM inventory WHERE id = $id";

    if ($connection->query($delete_query) === TRUE) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "<p>Error: " . $connection->error . "</p>";
    }
}

// Handle edit action
$item = [];
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $query = "SELECT * FROM inventory WHERE id = $id";
    $result = $connection->query($query);
    $item = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <h1>Track Inventory</h1>
<div class="container">
    <form method="POST" action="inventory.php">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" value="<?php echo isset($item['item_name']) ? htmlspecialchars($item['item_name']) : ''; ?>" required><br><br>

        <label for="item_type">Item Type:</label>
        <input type="text" id="item_type" name="item_type" value="<?php echo isset($item['item_type']) ? htmlspecialchars($item['item_type']) : ''; ?>" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo isset($item['quantity']) ? htmlspecialchars($item['quantity']) : ''; ?>" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo isset($item['description']) ? htmlspecialchars($item['description']) : ''; ?></textarea><br><br>

        <button type="submit" name="<?php echo isset($item['id']) ? 'update_item' : 'add_item'; ?>">
            <?php echo isset($item['id']) ? 'Update Item' : 'Add Item'; ?>
        </button>
        <?php if (isset($item['id'])): ?>
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
        <?php endif; ?>
    </form>
 <div class="inventory-list">
    <h2 style="text-align: center;">Inventory List</h2>
    <table id="inventoryTable">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Item Type</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM inventory";
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['item_name']}</td>";
                echo "<td>{$row['item_type']}</td>";
                echo "<td>{$row['quantity']}</td>";
                echo "<td>{$row['description']}</td>";
                echo "<td>
                        <a href='inventory.php?edit={$row['id']}' class='icon'><i class='fas fa-edit'></i></a>
                        <a href='inventory.php?delete={$row['id']}' class='icon delete-icon' onclick=\"return confirm('Are you sure you want to delete this item?')\"><i class='fas fa-trash-alt'></i></a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No items in the inventory.</td></tr>";
        }
        ?>
    </tbody>
</table>

</div>
</div>
</body>
</html>

<?php
$connection->close();
?>
