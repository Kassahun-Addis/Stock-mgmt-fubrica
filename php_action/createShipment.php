<?php 
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {    
    // Use appropriate variables matching the fields in your Shipmen table
    $Assigned_Person = $_POST['assigned_person'];
    $Shipment_Date = $_POST['shipment_date'];
    $Carrier = $_POST['carrier'];
    $Tracking_Number = $_POST['tracking_number'];
    $Shipping_Address = $_POST['shipping_address'];
    $Shipping_Cost = $_POST['shipping_cost'];
    $Status = $_POST['status'];
   
    // Insert record into Shipmen table with matching fields
    $sql = "INSERT INTO shipment (Assigned_person, ShipmentDate, Carrier, TrackingNumber, ShippingAddress, ShippingCost, Status) 
            VALUES ('$Assigned_Person', '$Shipment_Date', '$Carrier', '$Tracking_Number', '$Shipping_Address', '$Shipping_Cost', '$Status')";

    if ($connect->query($sql) === TRUE) {
        $ItemID = $connect->insert_id; // Get the last inserted Shipmen ID                  
        $valid['success'] = true;
        $valid['messages'] = "Shipmen successfully added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the Shipmen";
    }
} 

$connect->close();

echo json_encode($valid);
?>
