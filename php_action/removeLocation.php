<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$bankId = $_POST['bankId'];

if ($bankId) {
    // Delete the bank entry entirely
    $sql = "DELETE FROM sells_location WHERE location_id = {$bankId}";

    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Removed";        
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while removing the bank";
    }

    $connect->close();

    echo json_encode($valid);
}
?>