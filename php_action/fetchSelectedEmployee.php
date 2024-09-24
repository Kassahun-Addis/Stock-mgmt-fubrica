<?php
require_once 'db_connect.php'; // Ensure this includes database connection

// Retrieve the EmployeeID from the POST request
$EmployeeID = $_POST['EmployeeID'];

// Use parameterized query to prevent SQL injection
$sql = "SELECT EmployeeID, FirstName, LastName, Phone_no, Email, Position, Department, HireDate FROM employee WHERE EmployeeID = ?";

$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $EmployeeID);

$result = $stmt->execute();

if ($result) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch the row as an associative array
        $row = $result->fetch_assoc();
    } else {
        $row = array(); // Return an empty array if no rows found
    }
} else {
    // Handle SQL execution error
    $row = array('error' => 'Error executing query');
}

// Close the statement and connection
$stmt->close();
$connect->close();

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($row);
