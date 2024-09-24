<?php 

require_once 'core.php';

$response = array('success' => false, 'messages' => '');

if($_POST) {  
  $bankName = $_POST['bankName'];

  // Check if the bank name is provided
  if(empty($bankName)) {
    $response['messages'] = 'Location name is required';
  } else {
    $sql = "INSERT INTO sells_location (names) VALUES ('$bankName')";

    // Execute the query and check for success
    if($connect->query($sql) === TRUE) {
      $response['success'] = true;
      $response['messages'] = 'Location successfully created';
    } else {
      $response['messages'] = 'Error while creating location';
    }
  }

  $connect->close();

  // Return the JSON response
  echo json_encode($response);
}