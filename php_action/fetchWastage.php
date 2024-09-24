<?php

require_once 'db_connect.php';

$output = ['data' => []];

$sql = "SELECT id, product_name, quantity, unit, wastage_date, reason FROM wastage";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Loop through each wastage record
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];

        // Edit and Delete buttons with data attributes
        $button = '
        <div class="btn-group">
            <button class="btn btn-warning btn-sm editWastageBtn me-1" data-id="' . $id . '">
                <i class="glyphicon glyphicon-edit"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm deleteWastageBtn" data-id="' . $id . '">
                <i class="glyphicon glyphicon-trash"></i> Delete
            </button>
        </div>';

        // Add the row data to the output array
        $output['data'][] = [
            $row['product_name'],
            $row['quantity'],
            $row['unit'],
            $row['wastage_date'],
            $row['reason'],
            $button
        ];
    }
}

// Close the database connection
$connect->close();

// Return the JSON response
echo json_encode($output);

?>
