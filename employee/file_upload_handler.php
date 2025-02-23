<?php
include("connection.php");

if (isset($_SESSION['selected_patient_id'])) {
    $selected_patient_id = $_SESSION['selected_patient_id'];

    if (isset($_POST['upload'])) {
        $file_type = $_POST['file_type'];
        $file_upload_dir = '../patient/uploads/';
        $file_uploaded = false;

        if ($_FILES['file']['error'] == 0) {
            $file_name = $_FILES['file']['name'];
            $file_temp = $_FILES['file']['tmp_name'];
            $file_path = $file_upload_dir . $file_name;

            if (move_uploaded_file($file_temp, $file_path)) {
                $file_uploaded = true;

                // code...
            } else {
                echo "error: file upload failed";
            }
        } else {
            echo "Error: " . $_FILES['file']['error'];
        }

        if ($file_uploaded) {
            $insertFileQuery = "INSERT INTO reports (patient_id, file_name, file_type, file_path, upload_date) VALUES ('$selected_patient_id', '$file_name', '$file_type', '$file_path', NOW())";
              
if (mysqli_query($connect, $insertFileQuery)) {
    echo '<script>alert("File Uploaded successfully");</script>';
    header('Location: file_upload.php');
    exit();
}
// code...
}
}
}
?>