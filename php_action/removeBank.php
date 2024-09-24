<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$assetId= $_POST['assetId'];

if ($bankId) {
    // Delete the bank entry entirely
    $sql = "DELETE FROM Bank WHERE id = {$bankId}";

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
