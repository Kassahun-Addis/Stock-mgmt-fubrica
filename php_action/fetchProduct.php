<?php
require_once 'core.php';

// SQL query to fetch data from the 'finished_good' and 'categories' tables
$sql = "SELECT finished_good.product_id, 
               finished_good.product_name, 
               finished_good.quantity, 
               finished_good.selling_price, 
               finished_good.purchase_cost, 
               finished_good.serial_no, 
               finished_good.detailed_specification, 
               finished_good.alert_quantity, 
               finished_good.product_status, 
               finished_good.date_added, 
               categories.categories_name 
        FROM finished_good 
        INNER JOIN categories ON finished_good.category_id = categories.categories_id ";

$result = $connect->query($sql);

// Array to hold the output data
$output = array('data' => array());

if ($result->num_rows > 0) {
    // Fetch each row from the result set
    while ($row = $result->fetch_array()) {
        $productId = $row[0];

        // Determine the product status based on quantity and alert_quantity
        if ($row[2] == 0) {
            $status = "<label class='label label-danger'>Not Available</label>";
        } else if ($row[2] < $row[7]) {
            $status = "<label class='label label-warning'>Low Quantity</label>";
        } else {
            $status = "<label class='label label-success'>Sufficient Quantity</label>";
        }

        // Action buttons for Edit/Remove
        $button = '<div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct(' . $productId . ')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct(' . $productId . ')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>
            </ul>
        </div>';

        // Format the date to 'dd-mm-yyyy'
        $formattedDate = date('d-m-Y', strtotime($row[9]));

        // Prepare data to be displayed in DataTables
        $output['data'][] = array(
            $row[1],                // Product Name
            $row[10],               // Category (Category Name)
            $row[3],                // Selling Price
            $row[2],                // Quantity
            $formattedDate,         // Date Added (Formatted)
            $row[4],                // Production Cost (Purchase Cost)
            $row[6],                // Detailed Specification
            $status,                // Status (based on Quantity)
            $button                 // Options (Edit/Remove buttons)
        );
    }
}

// Close the database connection
$connect->close();

// Return the JSON-encoded data
echo json_encode($output);
?>
