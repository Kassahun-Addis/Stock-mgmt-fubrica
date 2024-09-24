<?php
require_once 'core.php';

$user_id = $_POST['sellsId']; // Ensure this matches the key sent from the JavaScript

$sql = "SELECT user_id, username, password, phone FROM sells_user WHERE user_id = $user_id";
$result = $connect->query($sql);

$row = array();

if ($result->num_rows > 0) { 
    $row = $result->fetch_array(); // Fetch the data as an associative array
}

$connect->close();

echo json_encode($row); // Return the fetched data as a JSON object
?>
