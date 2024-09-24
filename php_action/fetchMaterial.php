<?php

require_once 'db_connect.php';

$sql = "SELECT * FROM raw_materials";
$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materialId = $row['material_id'];

        // Determine the product status based on purchase_quantity and alert_quantity
        if ($row['purchase_quantity'] == 0) {
            $status = "<label class='label label-danger'>Not Available</label>";
        } else if ($row['purchase_quantity'] < $row['alert_quantity']) {
            $status = "<label class='label label-warning'>Low Quantity</label>";
        } else {
            $status = "<label class='label label-success'>Sufficient Quantity</label>";
        }

        // Action buttons for editing and removing material
        $actionButton = '
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editMaterialModal" onclick="editMaterial(' . $materialId . ')">Edit</button>
            <button type="button" class="btn btn-danger" onclick="removeMaterial(' . $materialId . ')">Remove</button>
        ';

        // Add data to the output array, including the status
        $output['data'][] = array(
            $row['item_name'],
            $row['purchase_quantity'],
            $row['unit'], 
            $row['purchase_price'],
            $row['purchase_date'],
            $row['purchased_by'],
            $row['supplier'],
            $status,  // Add the status column here
            $actionButton
        );
    }
}

// Close the database connection
$connect->close();

// Return the JSON encoded output
echo json_encode($output);

?>
