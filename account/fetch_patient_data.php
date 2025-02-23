<?php
require_once('path/to/tcpdf/tcpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch most recent prescription data from the database
$sql = "SELECT * FROM prescription_data ORDER BY timestamp_column DESC LIMIT 1";
$result = $conn->query($sql);

// Initialize TCPDF
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Prescription Details');

// Add a page to the PDF
$pdf->AddPage();

// Add a side navigation bar with a width of 50px and height same as the page
$pdf->SetFillColor(65, 105, 225); // Blue color
$pdf->Rect(0, 0, 12, $pdf->getPageHeight(), 'F');

// Add a header with a blue background color and a height of 50px
$pdf->SetFillColor(65, 105, 225); // Blue color
$pdf->Rect(0, -35, $pdf->getPageWidth(), 50, 'F');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $refNumber = str_replace('.', '<br>', $row["refNumber"]);
        $full_name = str_replace('.', '<br>', $row["full_name"]);
        $gender = str_replace('.', '<br>', $row["gender"]);
        $age = str_replace('.', '<br>', $row["age"]);
        $doctor_name = $row["doctor_name"];
        $medications = $row["medications"];

        // Add prescription details to the PDF
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(12, 20); // Set position with margin right 20px and margin top 10px
        $pdf->Write(0, 'Ref.Number: ' . $refNumber, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(12, 30); // Set position with margin right 20px and margin top 10px
        $pdf->Write(0, 'Patient Name: ' . $full_name, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(12, 40); // Set position with margin right 20px and margin top 10px
        $pdf->Write(0, 'Patient Gender: ' . $gender, '', 0, 'L', true, 0, false, false, 0);

        $pdf->SetXY(12, 50); // Set position with margin right 20px and margin top 10px
        $pdf->Write(0, 'Patient Age: ' . $age, '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetXY(160, 12);
        $pdf->Write(12, 'Specialist : Dr. ' . $doctor_name, '', 0, 'L', true, 0, false, false, 0);
       

        // Add vertical line spanning the entire height

       $pdf->Line(70, 15, 70, $pdf->getPageHeight() - 0, array('width' => 0.5, 'color' => array(0, 0, 0)));


        // Add horizontal line with a top margin of -250 units from the bottom
        $pdf->Line(12, $pdf->getPageHeight() - 239, $pdf->getPageWidth() - 0, $pdf->getPageHeight() - 239, array('width' => 0.5, 'color' => array(0, 0, 0)));
       
    }
} else {
    // Add a warning message to the PDF
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, 'No prescriptions available.', '', 0, 'L', true, 0, false, false, 0);
}

// Output the PDF to the browser
$pdf->Output('prescription_details.pdf', 'I');

$conn->close();
?>