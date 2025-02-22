<?php
// Initialize variables for displaying results
$result = '';
$hospital = '';
$location = '';
$bookingType = '';
$amount = 0; // Variable to hold the amount
$showProceedToPaymentButton = false; // Control visibility of proceed to payment button

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $hospital = filter_input(INPUT_POST, 'hospital', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $bookingType = filter_input(INPUT_POST, 'bookingType', FILTER_SANITIZE_STRING);

    // Validate input and prepare the result message
    if (!empty($hospital) && !empty($location) && !empty($bookingType)) {
        // Define booking amounts
        switch ($bookingType) {
            case 'Bed':
                $amount = 1000; // Amount for booking a bed
                break;
            case 'Ambulance':
                $amount = 500; // Amount for booking an ambulance
                break;
            case 'Ventilator':
                $amount = 1500; // Amount for booking a ventilator
                break;
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO hospital_bookings (hospital_name, location, booking_type, amount) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssd", $hospital, $location, $bookingType, $amount);

        if ($stmt->execute()) {
            $result = "Booking successful!<br>" .
                      "Selected Location: " . htmlspecialchars($location) . "<br>" .
                      "Selected Hospital: " . htmlspecialchars($hospital) . "<br>" .
                      "Booking Type: " . htmlspecialchars($bookingType) . "<br>" .
                      "Amount: ₹" . number_format($amount, 2);
            $showProceedToPaymentButton = true; // Show proceed to payment button
        } else {
            $result = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $result = "Please select a location, hospital, and booking type.";
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px;
            display: flex;
            flex-direction: row;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Prevent overflow */
        }
        .form-container {
            padding: 20px;
            flex: 1; /* Allow this to take available space */
            background-color: #ffffff;
        }
        .result-container {
            padding: 20px;
            border-left: 2px solid #007bff;
            width: 300px; /* Fixed width for result display */
            background-color: #e7f3fe;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #333;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #007bff;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        select:focus {
            border-color: #0056b3;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        button:hover {
            background-color: #218838;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #f7faff;
            color: #31708f;
        }
        .proceed-button {
            display: none; /* Initially hidden */
            margin-top: 20px;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            width: 100%;
        }
        .proceed-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Select Location</h1>
            <form id="hospitalForm" method="POST" onsubmit="return validateForm();">
                <label for="location">Select Location:</label>
                <select id="location" name="location" onchange="populateHospitals(); updateUserSelection();">
                    <option value="">--Select a Location--</option>
                    <option value="Location A1">Location A1</option>
                    <option value="Location A2">Location A2</option>
                    <option value="Location B1">Location B1</option>
                    <option value="Location B2">Location B2</option>
                    <option value="Location C1">Location C1</option>
                    <option value="Location C2">Location C2</option>
                </select>

                <label for="hospital">Select Hospital:</label>
                <select id="hospital" name="hospital" style="display:none;" onchange="enableBookingType(); updateUserSelection();">
                    <option value="">--Select a Hospital--</option>
                </select>

                <label for="bookingType" id="bookingTypeLabel" style="display:none;">Select Booking Type:</label>
                <select id="bookingType" name="bookingType" style="display:none;" onchange="updateUserSelection();">
                    <option value="">--Select Booking Type--</option>
                    <option value="Bed">Book Bed (₹1000)</option>
                    <option value="Ambulance">Book Ambulance (₹500)</option>
                    <option value="Ventilator">Book Ventilator (₹1500)</option>
                </select>

                <button type="submit" style="display:none;" id="submitButton">Submit</button>
            </form>
        </div>

        <div class="result-container">
            <h2>Your Selection</h2>
            <div id="userSelection" class="result">
                <?php if ($result): ?>
                    <?php echo $result; ?>
                <?php else: ?>
                    No selection made yet.
                <?php endif; ?>
            </div>

            <!-- Show the proceed to payment button if the form has been submitted -->
            <?php if ($showProceedToPaymentButton): ?>
                <button class="proceed-button" id="proceedButton" style="display: block;" onclick="proceedToPayment()">Proceed to Payment</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function populateHospitals() {
            const location = document.getElementById('location').value;
            const hospitalSelect = document.getElementById('hospital');

            // Clear previous hospitals
            hospitalSelect.innerHTML = '<option value="">--Select a Hospital--</option>';
            hospitalSelect.style.display = 'none'; // Hide initially

            let hospitals = [];

            // Define hospitals based on the selected location
            if (location === 'Location A1') {
                hospitals = ['Hospital A1', 'Hospital A2'];
            } else if (location === 'Location A2') {
                hospitals = ['Hospital A3', 'Hospital A4'];
            } else if (location === 'Location B1') {
                hospitals = ['Hospital B1', 'Hospital B2'];
            } else if (location === 'Location B2') {
                hospitals = ['Hospital B3', 'Hospital B4'];
            } else if (location === 'Location C1') {
                hospitals = ['Hospital C1', 'Hospital C2'];
            } else if (location === 'Location C2') {
                hospitals = ['Hospital C3', 'Hospital C4'];
            }

            // Populate hospital select
            hospitals.forEach(function(hospital) {
                const option = document.createElement('option');
                option.value = hospital;
                option.textContent = hospital;
                hospitalSelect.appendChild(option);
            });

            if (hospitals.length > 0) {
                hospitalSelect.style.display = 'block'; // Show hospital select
            }

            // Hide other selects initially
            document.getElementById('bookingType').style.display = 'none';
            document.getElementById('bookingTypeLabel').style.display = 'none';
        }

        function enableBookingType() {
            const bookingTypeSelect = document.getElementById('bookingType');
            const bookingTypeLabel = document.getElementById('bookingTypeLabel');
            bookingTypeSelect.style.display = 'block';
            bookingTypeLabel.style.display = 'block';
            document.getElementById('submitButton').style.display = 'block'; // Show submit button
        }

        function updateUserSelection() {
            const hospital = document.getElementById('hospital').value;
            const location = document.getElementById('location').value;
            const bookingType = document.getElementById('bookingType').value;
            const userSelectionDiv = document.getElementById('userSelection');

            // Update the displayed selection
            userSelectionDiv.innerHTML = `Selected Location: ${location}<br>Selected Hospital: ${hospital}<br>Booking Type: ${bookingType}`;
        }

        function proceedToPayment() {
            alert('Proceeding to payment...');
            // Here, you can redirect to the payment page or process payment
        }

        function validateForm() {
            // Implement form validation if necessary
            return true; // Allow submission
        }
    </script>
</body>
</html>
