
<?php
require_once 'db_connect.php'; // Include your database connection

$response = array('success' => false, 'quantity' => 0);

if (isset($_POST['itemName'])) {
    $itemName = $_POST['itemName'];

    // Prepare and execute the query to get the available quantity
    $sql = "SELECT purchase_quantity FROM raw_materials WHERE item_name = ?";
    $stmt = $connect->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response['success'] = true;
            $response['quantity'] = $row['purchase_quantity'];
        }
    }
}

$connect->close();
echo json_encode($response);
?>