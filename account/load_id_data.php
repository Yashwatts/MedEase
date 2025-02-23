<?php
$host = "localhost";
$username = "root";
$password = " ";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

$sql = " SELECT full_name, dob, address, district, refNumber FROM patient ORDER BY id DESC LIMIT 1";
$result = $conn-> query($sql);
$response = array();

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$preFilledData = $row;
} else{
	$response['success'] = false;
	$response['message'] = "No patient records found.";

	$preFilledData = array(
		'firstname' => '',
		'dob' => '',
		'district' => '',
		'refNumber' => ''
	);
}

$conn->close();
echo json_encode($response);

?>