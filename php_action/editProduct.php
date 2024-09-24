<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    // Get POST values
    $productId = $_POST['productId']; // The product ID to be updated
    $productName = $_POST['editProductName'];
    $quantity = $_POST['editQuantity'];
    $sellingPrice = $_POST['editRate']; // Adjusted based on table column
    $categoryId = $_POST['editCategoryName']; // Category ID selected in the form
    $purchaseCost = $_POST['editPurchase'];
    $serialNo = $_POST['editSerial_no'];
    $detailedSpecification = $_POST['detailed_specification']; // New field
    $alertQuantity = $_POST['editAlert_quantity'];
    $productStatus = $_POST['editProductStatus'];

    // Validation (optional)
    if (empty($productId) || empty($productName) || empty($quantity) || empty($sellingPrice) || empty($purchaseCost) || empty($categoryId)) {
        echo json_encode(array('success' => false, 'messages' => 'Please fill all required fields'));
        exit();
    }

    // SQL query to update the product in the table
    $sql = "UPDATE finished_good 
            SET product_name = '$productName', 
                quantity = '$quantity', 
                selling_price = '$sellingPrice', 
                purchase_cost = '$purchaseCost', 
                serial_no = '$serialNo', 
                detailed_specification = '$detailedSpecification', 
                alert_quantity = '$alertQuantity', 
                product_status = '$productStatus', 
                category_id = '$categoryId' 
            WHERE product_id = '$productId'";

    // Execute the query and check for errors
    if ($connect->query($sql) === TRUE) {
        echo json_encode(array('success' => true, 'messages' => 'Product successfully updated'));
    } else {
        // Output SQL error for debugging
        echo json_encode(array('success' => false, 'messages' => 'Error while updating product: ' . $connect->error));
    }

    // Close the connection
    $connect->close();
}
?>
