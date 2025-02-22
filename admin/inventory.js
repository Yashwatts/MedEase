// Fetch and display inventory items
window.onload = function() {
    loadInventory();
};

// Function to load inventory from the database
function loadInventory() {
    fetch('fetch_inventory.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#inventoryTable tbody');
            tableBody.innerHTML = '';  // Clear previous data
            data.forEach(item => {
                const row = `<tr>
                    <td>${item.id}</td>
                    <td>${item.item_name}</td>
                    <td>${item.item_type}</td>
                    <td>${item.quantity}</td>
                    <td>${item.description}</td>
                    <td>
                        <button onclick="editItem(${item.id})">Edit</button>
                        <button onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        });
}

// Function to add a new item
function addItem() {
    const item_name = document.getElementById('item_name').value;
    const item_type = document.getElementById('item_type').value;
    const quantity = document.getElementById('quantity').value;
    const description = document.getElementById('description').value;

    fetch('add_inventory.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_name, item_type, quantity, description })
    }).then(response => response.text())
      .then(() => {
          loadInventory();  // Reload inventory
      });
}

// Function to delete an item
function deleteItem(id) {
    fetch(delete_inventory.php?id=${id})
        .then(() => loadInventory());
}

// Function to edit an item
function editItem(id) {
    // You can implement this in a similar manner to update the item.
}