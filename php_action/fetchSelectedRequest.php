<?php
require_once 'db_connect.php'; // Ensure this includes database connection

// Retrieve the ItemID from the POST request
$itemId = $_POST['ItemID'];

// Use parameterized query to prevent SQL injection
$sql = "SELECT ItemID, ItemName, RequestedBy, IssuedBy, ApprovedBy, Quantity, Unit, UnitPrice, TotalPrice, QuantityAvailable, Remark, OrderDate FROM request_order WHERE ItemID = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $itemId);

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
