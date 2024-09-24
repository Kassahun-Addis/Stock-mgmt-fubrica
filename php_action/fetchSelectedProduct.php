<?php 

require_once 'core.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sanitize input
$productId = intval($_POST['productId']);

if ($productId <= 0) {
    echo json_encode(array('success' => false, 'message' => 'Invalid Product ID'));
    exit();
}

// Prepare SQL query
$sql = "SELECT 
            product_id, 
            product_name, 
            quantity, 
            selling_price AS rate, 
            purchase_cost AS purchase, 
            serial_no, 
            detailed_specification, 
            alert_quantity, 
            product_status AS status, 
            category_id 
        FROM finished_good 
        WHERE product_id = $productId";

// Execute SQL query
$result = $connect->query($sql);

if (!$result) {
    echo json_encode(array('success' => false, 'message' => 'SQL Error: ' . $connect->error));
    $connect->close();
    exit();
}

// Fetch the result
if ($result->num_rows > 0) { 
    $row = $result->fetch_assoc();
} else {
    $row = array(); // If no results, return an empty array
}

// Close the connection
$connect->close();

// Output the result as JSON
echo json_encode($row);

?>
