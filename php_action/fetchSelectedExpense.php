<?php   

require_once 'core.php';

$productId = $_POST['expenseId'];

$sql = "SELECT id, expense_for, ex_description, amount, expense_date FROM expense WHERE id = $productId";
$result = $connect->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$connect->close();

echo json_encode($row);