<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'core.php';
  
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
   $asset_name = $_POST['assetName'] ?? '';
$category = $_POST['category'] ?? '';
$description = $_POST['description'] ?? '';
$purchase_date = $_POST['purchaseDate'] ?? ''; // Updated field name
$purchase_price = $_POST['purchasePrice'] ?? 0; // Updated field name
$department = $_POST['department'] ?? '';
$last_maintenance_date = $_POST['lastMaintenanceDate'] ?? null; // Updated field name
$status = $_POST['status'] ?? '';
$assigned_to = $_POST['assignedTo'] ?? ''; // Updated field name
$remark = $_POST['remark'] ?? '';
$serial_no = $_POST['serialNo'] ?? ''; // Updated field name



    // Validate required fields
    if (empty($asset_name) || empty($category) || empty($purchase_date) || empty($purchase_price) || empty($department)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit();
    }


    // Prepare the SQL statement
    $stmt = $connect->prepare("INSERT INTO assets (asset_name, category, description, purchase_date, purchase_price, department, last_maintenance_date, status, assigned_to, remark, serial_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdsissss", $asset_name, $category, $description, $purchase_date, $purchase_price, $department, $last_maintenance_date, $status, $assigned_to, $remark, $serial_no);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Asset created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $connect->close();
} else {
    // Handle invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
