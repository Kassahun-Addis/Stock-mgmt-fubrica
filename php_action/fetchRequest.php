<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

$output = array('data' => array());

try {
    $sql = "SELECT ItemID, ItemName, RequestedBy, IssuedBy, ApprovedBy, Quantity, Unit, UnitPrice, TotalPrice, Remark, OrderDate FROM request_order"; 
    // Update table name and column names
    $query = $connect->query($sql); // $connect should be your DB connection variable

    if ($query === false) {
        throw new Exception("Database query failed: " . $connect->error);
    }

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $itemId = $row['ItemID']; // Replace with your actual expense ID column
            $itemName = isset($row['ItemName']) ? $row['ItemName'] : 'N/A'; // Fallback value if column is missing
            $requestedBy = isset($row['RequestedBy']) ? $row['RequestedBy'] : 'N/A'; // Fallback value if column is missing
            $issuedBy = isset($row['IssuedBy']) ? $row['IssuedBy'] : '0.00'; // Fallback value if column is missing
            $approvedBy = isset($row['ApprovedBy']) ? $row['ApprovedBy'] : 'N/A'; // Fallback value if column is missing
            $quantity = isset($row['Quantity']) ? $row['Quantity'] : '0.00'; // Fallback value if column is missing
            $unit = isset($row['Unit']) ? $row['Unit'] : 'N/A'; // Fallback value if column is missing
            $unitPrice = isset($row['UnitPrice']) ? $row['UnitPrice'] : '0.00'; // Fallback value if column is missing
            $totalPrice = isset($row['TotalPrice']) ? $row['TotalPrice'] : '0.00'; // Fallback value if column is missing
            
            $remark = isset($row['Remark']) ? $row['Remark'] : 'N/A'; // Fallback value if column is missing
            $orderDate = isset($row['OrderDate']) ? $row['OrderDate'] : '2024-01-01'; // Fallback value if column is missing
            
            $actions = '
    <button type="button" class="btn btn-warning" onclick="editExpense('.$itemId.')">Edit</button>
    <button type="button" class="btn btn-danger" onclick="removeExpense('.$itemId.')">Delete</button>
';

            $output['data'][] = array(
                $itemName,
                $requestedBy,
                $issuedBy,
                $approvedBy,
                $quantity,
                $unit,
                $unitPrice,
                $totalPrice,
                $remark,
                $orderDate,
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


