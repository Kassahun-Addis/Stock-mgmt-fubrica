<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once 'core.php';

// Query to fetch assets
$sql = "SELECT * FROM assets";
$result = $connect->query($sql);

if (!$result) {
    // Return error message in JSON format
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $connect->error]);
    exit(); // Stop further execution
}

$data = array();
while ($row = $result->fetch_assoc()) {
    // Update the delete button to trigger AJAX
    $button = '<div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a type="button" data-toggle="modal" data-target="#editAssetModal" onclick="editAsset('.$row['id'].')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                <li><a href="#" class="deleteAssetBtn" data-id="'.$row['id'].'"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>
            </ul>
        </div>';

    $data[] = [
        $row['id'],
        $row['asset_name'],
        $row['category'],
        $row['description'],
        $row['purchase_date'],
        $row['purchase_price'],
        $row['department'],
        $row['last_maintenance_date'],
        $row['status'],
        $row['assigned_to'],
        $row['remark'],
        $row['serial_no'],
        $button
    ];
}

// Return the data in JSON format
echo json_encode(['data' => $data]);

$connect->close();
?>
