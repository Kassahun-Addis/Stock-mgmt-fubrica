<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

$output = array('data' => array());

try {
    $sql = "SELECT EmployeeID, FirstName, LastName, Phone_no, Email, Position, Department, HireDate FROM employee"; 
    // Update table name and column names
    $query = $connect->query($sql); // $connect should be your DB connection variable

    if ($query === false) {
        throw new Exception("Database query failed: " . $connect->error);
    }

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $EmployeeID = $row['EmployeeID']; // Replace with your actual expense ID column
            $FirstName = isset($row['FirstName']) ? $row['FirstName'] : 'N/A'; // Fallback value if column is missing
            $LastName = isset($row['LastName']) ? $row['LastName'] : 'N/A'; // Fallback value if column is missing
            $Phone_no = isset($row['Phone_no']) ? $row['Phone_no'] : 'N/A'; // Fallback value if column is missing
            $Email = isset($row['Email']) ? $row['Email'] : '0.00'; // Fallback value if column is missing
            $Position = isset($row['Position']) ? $row['Position'] : 'N/A'; // Fallback value if column is missing
            $Department = isset($row['Department']) ? $row['Department'] : '0.00'; // Fallback value if column is missing
            $HireDate = isset($row['HireDate']) ? $row['HireDate'] : 'N/A'; // Fallback value if column is missing
            $actions = '
    <button type="button" class="btn btn-warning" onclick="editExpense('.$EmployeeID.')">Edit</button>
    <button type="button" class="btn btn-danger" onclick="removeExpense('.$EmployeeID.')">Delete</button>
';

            $output['data'][] = array(
                $FirstName,
                $LastName,
                $Phone_no,
                $Email,
                $Position,
                $Department,
                $HireDate,
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


