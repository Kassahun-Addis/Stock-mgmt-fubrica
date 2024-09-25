<?php
require_once 'php_action/core.php'; // Include your database connection logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the JSON data sent by the fetch request
    $data = json_decode(file_get_contents("php://input"), true);
    $productName = mysqli_real_escape_string($connect, $data['product_name']);

    // Check if the product name exists
    if (!empty($productName)) {
        // Update the 'is_read' field in the product table to '1' for this product
        $query = "UPDATE product SET is_read = 1 WHERE product_name = '$productName' AND is_read = 0";

        // Execute the query and check if the operation was successful
        if (mysqli_query($connect, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product name is empty.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
