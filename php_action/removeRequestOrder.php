<?php
// Database connection
require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the ItemID from the POST request
    $itemId = $_POST['ItemID'];

    $response = array();

    try {
        // Use the retrieved ItemID in the SQL query
        $sql = "DELETE FROM request_order WHERE ItemID = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $itemId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['messages'] = "Request Order removed successfully.";
        } else {
            throw new Exception("Error removing request order: " . $stmt->error);
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['messages'] = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}



