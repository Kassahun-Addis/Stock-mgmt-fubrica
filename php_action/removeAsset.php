<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if (isset($_POST['assetId'])) {
    $assetId = $_POST['assetId'];
    $assetId = intval($assetId); // Ensure it's an integer for safety

    // Debugging: Check if assetId is being received
    error_log("Asset ID received: " . $assetId);

    $sql = "DELETE FROM assets WHERE id = {$assetId}";
    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Removed";        
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while removing the asset";
    }
} else {
    $valid['success'] = false;
    $valid['messages'] = "Invalid Asset ID";
}

echo json_encode($valid);

?>
