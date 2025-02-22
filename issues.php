<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "city_issues");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Handle Photo Upload
    $target_dir = "uploads/";
    $photo_name = basename($_FILES["photo"]["name"]);
    $photo_path = $target_dir . $photo_name;
    
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path)) {
        // Insert Issue into Database
        $stmt = $conn->prepare("INSERT INTO issues (category, description, photo_url, latitude, longitude, status) 
                                VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssssd", $category, $description, $photo_path, $latitude, $longitude);

        if ($stmt->execute()) {
            echo "<script>alert('Issue submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error submitting issue.');</script>";
        }
    } else {
        echo "<script>alert('Error uploading photo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Issue</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input, select, textarea, button { width: 100%; margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        #map { height: 300px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Report an Issue</h2>

<form id="issueForm" method="POST" enctype="multipart/form-data">
    <label for="category">Category:</label>
    <select id="category" name="category" required>
        <option value="pothole">Pothole</option>
        <option value="infrastructure">Broken Infrastructure</option>
        <option value="unsafe">Unsafe Area</option>
    </select>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" required></textarea>

    <label for="photo">Upload Photo:</label>
    <input type="file" id="photo" name="photo" accept="image/*" required>

    <div id="map"></div>

    <input type="hidden" id="latitude" name="latitude" required>
    <input type="hidden" id="longitude" name="longitude" required>

    <button type="submit">Submit Issue</button>
</form>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAXZ_durXLvZu2rm9sl4OUSzoY-nwPFGk"></script>
<script>
    let map, marker;

    function initMap() {
        // Initialize map with a default location
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -34.397, lng: 150.644 },
            zoom: 8,
        });

        // Place marker on map click
        map.addListener("click", (event) => {
            placeMarker(event.latLng);
        });

        // Get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                map.setCenter(userLocation);
                placeMarker(userLocation);
            });
        }
    }

    function placeMarker(location) {
        if (marker) marker.setMap(null); // Remove existing marker

        marker = new google.maps.Marker({
            position: location,
            map: map,
        });

        // Update hidden inputs with latitude and longitude
        document.getElementById("latitude").value = location.lat;
        document.getElementById("longitude").value = location.lng;
    }

    window.onload = initMap;
</script>

</body>
</html>
