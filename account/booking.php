<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Bed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, grey, white);
            animation: backgroundAnimation 10s infinite alternate;
        }

        @keyframes backgroundAnimation {
            0% { background-color: #4e9bc4; }
            100% { background-color: #007bff; }
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 24px;
            animation: slideIn 1s ease-out;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book a Bed</h2>
        <form method="POST" action="booking_management.php">
            <label for="bed_id">Select Bed:</label>
            <select name="bed_id" id="bed_id" required>
                <option value="">Select a Bed</option>
                <?php
                include '../admin/beds_management.php';
                $beds = fetch_beds();
                foreach ($beds as $bed) {
                    echo "<option value=\"{$bed['id']}\">{$bed['bed_type']} - ID: {$bed['id']}</option>";
                }
                ?>
            </select><br><br>

            <input type="submit" value="Book Bed">
        </form>
    </div>
</body>
</html>
