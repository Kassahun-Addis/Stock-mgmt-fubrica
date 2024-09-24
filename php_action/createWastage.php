<?php

require_once 'core.php';

$response = ['success' => false, 'messages' => ''];

// Validate if form is submitted
if ($_POST) {
    // Capture form data
    $productName = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $wastageDate = $_POST['wastage_date'];
    $reason = $_POST['reason'];

    // Basic validation
    if (empty($productName) || empty($quantity) || empty($unit) || empty($wastageDate) || empty($reason)) {
        $response['messages'] = 'All fields are required';
    } else {
        // SQL Insert Query
        $sql = "INSERT INTO wastage (product_name, quantity, unit, wastage_date, reason) 
                VALUES ('$productName', '$quantity', '$unit', '$wastageDate', '$reason')";

        // Insert data into the database
        if ($connect->query($sql) === TRUE) {
            $response['success'] = true;
            $response['messages'] = 'Wastage record successfully added!';
        } else {
            $response['success'] = false;
            $response['messages'] = 'Error while adding the wastage record';
        }
    }

    // Close the database connection
    $connect->close();
}

// Return the JSON response
echo json_encode($response);
?>
