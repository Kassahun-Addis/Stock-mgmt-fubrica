<?php 

require_once 'core.php';

$response = array('success' => false, 'messages' => '');

if($_POST) {	
	$bankName = $_POST['bankName'];

	// Check if the bank name is provided
	if(empty($bankName)) {
		$response['messages'] = 'Bank name is required';
	} else {
		$sql = "INSERT INTO Bank (bank_name) VALUES ('$bankName')";

		// Execute the query and check for success
		if($connect->query($sql) === TRUE) {
			$response['success'] = true;
			$response['messages'] = 'Bank successfully created';
		} else {
			$response['messages'] = 'Error while creating bank';
		}
	}

	$connect->close();

	// Return the JSON response
	echo json_encode($response);
}
