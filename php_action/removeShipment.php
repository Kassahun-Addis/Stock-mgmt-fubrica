<?php
// Database connection
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the ShipmentID from the POST request
    $Shipment_ID = $_POST['shipment_id'];

    $response = array();

    try {
        // Use the retrieved ShipmentID in the SQL query
        $sql = "DELETE FROM shipment WHERE ShipmentID = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $Shipment_ID);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['messages'] = "Shipment removed successfully.";
        } else {
            throw new Exception("Error removing Shipment: " . $stmt->error);
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['messages'] = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}



