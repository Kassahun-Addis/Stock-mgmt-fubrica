<?php
require_once 'core.php';

$bankId = $_POST['bankId'];

$sql = "SELECT location_id, names FROM sells_location WHERE location_id = $bankId";
$result = $connect->query($sql);

if ($result->num_rows > 0) { 
    $row = $result->fetch_array();
}

$connect->close();

echo json_encode($row);
?>