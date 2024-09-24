<?php
require_once 'db_connect.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {    
    // Retrieve POST data
    $Shipment_ID = $_POST['shipment_id'];
    $Assigned_Person = $_POST['assigned_person'];
    $Shipment_Date = $_POST['shipment_date'];
    $Carrier = $_POST['carrier'];
    $Tracking_Number = $_POST['tracking_number'];
    $Shipping_Address = $_POST['shipping_address'];
    $Shipping_Cost = $_POST['shipping_cost'];
    $Status = $_POST['status'];

    // Debugging output
    error_log('Received POST data: ' . print_r($_POST, true));

    // SQL update statement
    $sql = "UPDATE shipment SET Assigned_person = ?, ShipmentDate = ?, Carrier = ?, TrackingNumber = ?, ShippingAddress = ?, ShippingCost = ?, Status = ? WHERE ShipmentID = ?";
    
    // Prepare statement
    $stmt = $connect->prepare($sql);
    
    // Check if prepare was successful
    if ($stmt === false) {
        error_log('Prepare failed: ' . $connect->error);
        echo json_encode(['success' => false, 'message' => 'SQL Prepare Error']);
        exit;
    }

    // Bind parameters
    $stmt->bind_param('sssisdsi', 
        $Assigned_Person, $Shipment_Date, $Carrier, $Tracking_Number, $Shipping_Address, $Shipping_Cost, $Status, $Shipment_ID);

    // Execute statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'messages' => 'Shipment updated successfully.']);
    } else {
        error_log('Execute failed: ' . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error updating Shipment: ' . $stmt->error]);
    }

    $stmt->close();
    $connect->close();
}
?>
