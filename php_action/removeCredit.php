<?php
require_once 'core.php';

$response = array('success' => false, 'messages' => '');

if ($_POST) {
    $creditId = $_POST['creditId'];

    $sql = $sql = "UPDATE credit SET status = 'inactive' WHERE credit_id = {$creditId}";
    if ($connect->query($sql) === TRUE) {
        $response['success'] = true;
        $response['messages'] = "Credit successfully removed";
    } else {
        $response['success'] = false;
        $response['messages'] = "Error while removing credit";
    }

    $connect->close();

    echo json_encode($response);
}
?>
