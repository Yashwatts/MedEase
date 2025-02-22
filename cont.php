<?php
// Database connection settings
$host = "localhost";    // Database host (usually 'localhost')
$username = "root";      // Your MySQL username
$password = "";          // Your MySQL password
$dbname = "hospital";    // Name of your database

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs to prevent SQL injection
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $feedback = $conn->real_escape_string($_POST['feedback']);

    // Insert feedback into the reviews table
    $sql = "INSERT INTO reviews (name, email, feedback) VALUES ('$name', '$email', '$feedback')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thank you for your feedback!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!-- Contact Us Section Start -->
<div class="container-fluid py-5">
  <div class="container" style="background-color: #4e9bc4 !important;">
    <div class="text-center mx-auto mb-5" style="max-width: 600px;">
      <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4" 
          style="color: black !important; font-size: 2rem !important;">Contact Us</h5>
      <h1 class="display-4" style="color: black;">We Value Your Feedback</h1>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <form action="index.php" method="POST" class="p-4 bg-white shadow rounded">
          <div class="form-group mb-3">
            <label for="name" class="form-label" style="color: black !important;">Your Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
          </div>
          <div class="form-group mb-3">
            <label for="email" class="form-label" style="color: black !important;">Your Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
          </div>
          <div class="form-group mb-3">
            <label for="feedback" class="form-label" style="color: black !important;">Your Feedback</label>
            <textarea name="feedback" id="feedback" class="form-control" rows="5" 
                      placeholder="Let us know how we can improve..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100" style="color: black !important;">Submit Feedback</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Contact Us Section End -->
