<?php 
require_once 'core.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    // Get POST values
    $productName = $_POST['productName'];
    $quantity = $_POST['quantity'];
    $sellingPrice = $_POST['rate']; // Adjusted based on table column
    $categoryName = $_POST['categoryName']; // This should be category_id
    $purchase = $_POST['purchase'];
    $serial_no = $_POST['serial_no'];
    $detailed_specification = $_POST['detailed_specification'];  // New field
    $alert_quantity = $_POST['alert_quantity'];
    $productStatus = $_POST['productStatus'];

    // Validation (optional)
    if (empty($productName) || empty($categoryName) || empty($purchase)) {
        echo json_encode(array('success' => false, 'messages' => 'Please fill all required fields'));
        exit();
    }

    // SQL query to insert the product into the table
    $sql = "INSERT INTO finished_good (product_name, quantity, selling_price, purchase_cost, serial_no, detailed_specification, alert_quantity, product_status, category_id)
            VALUES ('$productName', '$quantity', '$sellingPrice', '$purchase', '$serial_no', '$detailed_specification', '$alert_quantity', '$productStatus', '$categoryName')";

    // Log SQL query for debugging instead of echoing
    error_log("SQL Query: " . $sql);

    // Execute the query and check for errors
    if ($connect->query($sql) === TRUE) {
        echo json_encode(array('success' => true, 'messages' => 'Product successfully added'));
    } else {
        // Output SQL error for debugging
        echo json_encode(array('success' => false, 'messages' => 'Error while adding product: ' . $connect->error));
    }

    // Close the connection
    $connect->close();
}
?>
