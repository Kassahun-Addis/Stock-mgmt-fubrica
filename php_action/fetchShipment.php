<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db_connect.php'; // Ensure this file contains the correct database connection

$output = array('data' => array());

try {
    $sql = "SELECT ShipmentID, Assigned_person, ShipmentDate, Carrier, TrackingNumber, ShippingAddress, ShippingCost, Status FROM shipment"; 

    // Update table name and column names
    $query = $connect->query($sql); // $connect should be your DB connection variable

    if ($query === false) {
        throw new Exception("Database query failed: " . $connect->error);
    }

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $Shipment_ID = $row['ShipmentID']; // Replace with your actual ShipmentID column
            $Assigned_Person = isset($row['Assigned_person']) ? $row['Assigned_person'] : 'N/A'; // Fallback value if column is missing
            $Shipment_Date = isset($row['ShipmentDate']) ? $row['ShipmentDate'] : '2024-01-01'; // Fallback value if column is missing
            $Carrier = isset($row['Carrier']) ? $row['Carrier'] : '0.00'; // Fallback value if column is missing
            $Tracking_Number = isset($row['TrackingNumber']) ? $row['TrackingNumber'] : 'N/A'; // Fallback value if column is missing
            $Shipping_Address = isset($row['ShippingAddress']) ? $row['ShippingAddress'] : '0.00'; // Fallback value if column is missing
            $Shipping_Cost = isset($row['ShippingCost']) ? $row['ShippingCost'] : '0.00'; // Fallback value if column is missing
            $Status = isset($row['Status']) ? $row['Status'] : 'N/A'; // Fallback value if column is missing


            $actions = '
    <button type="button" class="btn btn-warning" onclick="editExpense('.$Shipment_ID.')">Edit</button>
    <button type="button" class="btn btn-danger" onclick="removeExpense('.$Shipment_ID.')">Delete</button>
';

            $output['data'][] = array(
                $Assigned_Person,
                $Shipment_Date,
                $Carrier,
                $Tracking_Number,
                $Shipping_Address,
                $Shipping_Cost,
                $Status,
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


