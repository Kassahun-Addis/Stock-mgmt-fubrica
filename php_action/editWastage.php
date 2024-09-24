<?php

require_once 'core.php';

$response = ['success' => false, 'messages' => ''];

// Check if the form is submitted
if ($_POST) {
    $wastageId = $_POST['wastageId']; // Hidden field containing the wastage ID
    $productName = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $wastageDate = $_POST['wastage_date'];
    $reason = $_POST['reason'];

    // Basic validation
    if (empty($productName) || empty($quantity) || empty($unit) || empty($wastageDate) || empty($reason)) {
        $response['messages'] = 'All fields are required';
    } else {
        // SQL Update Query
        $sql = "UPDATE wastage 
                SET product_name = '$productName', quantity = '$quantity', unit = '$unit', wastage_date = '$wastageDate', reason = '$reason' 
                WHERE id = $wastageId";

        // Execute the query
        if ($connect->query($sql) === TRUE) {
            $response['success'] = true;
            $response['messages'] = 'Wastage record successfully updated!';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error while updating the wastage record';
        }
    }

    // Close the database connection
    $connect->close();
}

// Return the JSON response
echo json_encode($response);

?>
