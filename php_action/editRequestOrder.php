<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    // Collect and sanitize the input
  $itemId = $_POST['ItemID']; // Replace with your actual expense ID column
    $itemName = $_POST['ItemName'];
    $requestedBy = $_POST['RequestedBy'];
    $issuedBy = $_POST['IssuedBy'];
    $approvedBy = $_POST['ApprovedBy'];
    $quantity = $_POST['Quantity'];
    $unit = $_POST['Unit'];
    $unitPrice = $_POST['UnitPrice'];
    $totalPrice = $_POST['TotalPrice'];
    $quantityAvailable = $_POST['QuantityAvailable'];
    $remark = $_POST['Remark'];
    $orderDate = $_POST['OrderDate'];

    // Prepare the SQL query with placeholders
    $sql = "UPDATE request_order 
            SET ItemName = ?, RequestedBy = ?, IssuedBy = ?, ApprovedBy = ?, Quantity = ?, Unit = ?, UnitPrice = ?, TotalPrice = ?, QuantityAvailable = ?, Remark = ?, OrderDate = ? 
            WHERE ItemID = ?";

    // Initialize the database connection (assuming $connect is your connection variable)
    if ($stmt = $connect->prepare($sql)) {
        // Bind the variables to the prepared statement
        $stmt->bind_param("ssssisddissi", $itemName, $requestedBy, $issuedBy, $approvedBy, $quantity, $unit, $unitPrice, $totalPrice, $quantityAvailable, $remark, $orderDate, $itemId);

        // Execute the statement
        if ($stmt->execute()) {
            $valid['success'] = true;
            $valid['messages'] = "Record updated successfully!";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error updating record: " . $connect->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error preparing query: " . $connect->error;
    }

    // Close the connection
    $connect->close();

    // Return the response as JSON
    echo json_encode($valid);
}
?>