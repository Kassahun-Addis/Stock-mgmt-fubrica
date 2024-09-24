<?php
require_once 'core.php';

$creditId = $_POST['creditId'];

$sql = "SELECT credit_id, product_name, supplier, quantity, purchase, paid_amount, remaining_due_amount, transaction_number FROM credit WHERE credit_id = $creditId";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_array();
}

$connect->close();

echo json_encode($row);
?>
