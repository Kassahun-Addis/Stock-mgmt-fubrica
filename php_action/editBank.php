<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$bankId = $_POST['bankId'];
$bankName = $_POST['editBankName'];

if ($bankId && $bankName) {
    $sql = "UPDATE Bank SET bank_name = '$bankName' WHERE id = {$bankId}";

    if ($connect->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating the bank";
    }

    $connect->close();

    echo json_encode($valid);
}
?>
