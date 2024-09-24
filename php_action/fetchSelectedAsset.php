<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'core.php'; // Ensure this file contains your database connection

// Check if asset ID is set
if (isset($_POST['assetId'])) {
    $assetId = intval($_POST['assetId']); // Sanitize input to prevent SQL injection

    // Prepare and execute query to fetch the asset
    $sql = "SELECT * FROM assets WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $assetId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the asset details
        $asset = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $asset]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Asset not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No asset ID provided.']);
}

$connect->close();
?>
