<?php 
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {    
    // Use appropriate variables matching the fields in your expense table
    $expense_for     = $_POST['expense_for'];         // expense_for field
    $ex_description  = $_POST['ex_description'];      // ex_description field
    $amount          = $_POST['amount'];              // amount field
    $expense_date    = $_POST['expense_date'];        // expense_date field

    // Insert record into expense table with matching fields
    $sql = "INSERT INTO expense (expense_for, ex_description, amount, expense_date) 
            VALUES ('$expense_for', '$ex_description', '$amount', '$expense_date')";

    if ($connect->query($sql) === TRUE) {
        $expenseId = $connect->insert_id; // Get the last inserted expense ID                  
        $valid['success'] = true;
        $valid['messages'] = "Expense successfully added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the expense";
    }
} 

$connect->close();

echo json_encode($valid);
?>