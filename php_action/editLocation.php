<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$bankId = $_POST['bankId'];
$bankName = $_POST['editBankName'];

if ($bankId && $bankName) {
    $sql = "UPDATE sells_location SET names = '$bankName' WHERE location_id = {$bankId}";

    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating the location";
    }

    $connect->close();

    echo json_encode($valid);
}
?>