<?php
require_once 'db_connect.php';

$valid['success'] = array('success' => false, 'messages' => array());


if ($_POST) {
    $expenseId = $_POST['expenseId'];
    $expense_for = $_POST['expense_for'];
    $ex_description = $_POST['ex_description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];

    $sql = "UPDATE expense SET expense_for = ?, ex_description = ?, amount = ?, expense_date = ? WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param('ssdsi', $expense_for, $ex_description, $amount, $expense_date, $expenseId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating expense']);
    }
}
?>