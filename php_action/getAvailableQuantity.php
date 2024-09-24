<?php
require_once 'db_connect.php';

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    $sql = "SELECT quantity FROM finished_good WHERE product_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_assoc();
    echo json_encode($data);

    // Debugging output
    error_log("Fetched data: " . print_r($data, true));
} else {
    error_log("No productId provided");
}
?>
