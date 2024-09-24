<?php
// Enable error reporting for debugging
require_once 'core.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if connection is established
    if (!$connect) {
        $response['status'] = 'error';
        $response['message'] = 'Database connection failed.';
        echo json_encode($response);
        exit;
    }

    // Get the asset data from POST request
    $assetId = $_POST['assetId'];
    $assetName = $_POST['editAssetName'];
    $category = $_POST['editCategory'];
    $description = $_POST['editDescription'];
    $purchaseDate = $_POST['editPurchaseDate'];
    $purchasePrice = $_POST['editPurchasePrice'];
    $department = $_POST['editDepartment'];
    $lastMaintenanceDate = $_POST['editLastMaintenanceDate'];
    $status = $_POST['editStatus'];
    $assignedTo = $_POST['editAssignedTo'];
    $remark = $_POST['editRemark'];
    $serialNo = $_POST['editSerialNo'];

    // Prepare SQL statement to update asset details
    $sql = "UPDATE assets SET 
                asset_name = ?, 
                category = ?, 
                description = ?, 
                purchase_date = ?, 
                purchase_price = ?, 
                department = ?, 
                last_maintenance_date = ?, 
                status = ?, 
                assigned_to = ?, 
                remark = ?, 
                serial_no = ? 
            WHERE id = ?";

    $stmt = $connect->prepare($sql);

    if ($stmt === false) {
        $response['status'] = 'error';
        $response['message'] = 'SQL preparation error: ' . $conn->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("ssssdsdssssi", $assetName, $category, $description, $purchaseDate, $purchasePrice, $department, $lastMaintenanceDate, $status, $assignedTo, $remark, $serialNo, $assetId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Asset updated successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No changes made. Please check if the asset ID exists or if the data is the same.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update asset: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Please use POST.';
}

header('Content-Type: application/json');
echo json_encode($response);

$connect->close();
?>
