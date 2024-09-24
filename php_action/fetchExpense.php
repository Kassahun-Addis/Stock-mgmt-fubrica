<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

$output = array('data' => array());

try {
    $sql = "SELECT id, expense_for, ex_description, amount, expense_date FROM expense"; // Update table name and column names
    $query = $connect->query($sql); // $connect should be your DB connection variable

    if ($query === false) {
        throw new Exception("Database query failed: " . $connect->error);
    }

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $expenseId = $row['id']; // Replace with your actual expense ID column
            $expenseFor = isset($row['expense_for']) ? $row['expense_for'] : 'N/A'; // Fallback value if column is missing
            $description = isset($row['ex_description']) ? $row['ex_description'] : 'N/A'; // Fallback value if column is missing
            $amount = isset($row['amount']) ? $row['amount'] : '0.00'; // Fallback value if column is missing
            $expenseDate = isset($row['expense_date']) ? $row['expense_date'] : '1970-01-01'; // Fallback value if column is missing

           $actions = '
    <button type="button" class="btn btn-warning" onclick="editExpense('.$expenseId.')">Edit</button>
    <button type="button" class="btn btn-danger" onclick="removeExpense('.$expenseId.')">Delete</button>
';

            $output['data'][] = array(
                $expenseFor,
                $description,
                $amount,
                $expenseDate,
                $actions
            );
        }
    }
} catch (Exception $e) {
    // Log the error
    error_log($e->getMessage());
    $output['error'] = $e->getMessage();
}

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($output);
