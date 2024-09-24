<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    // Retrieve all the necessary fields
    $itemNames = $_POST['ItemName'];
    $requestedBy = $_POST['RequestedBy'];
    $issuedBy = $_POST['IssuedBy'];
    $approvedBy = $_POST['ApprovedBy'];
    $quantities = $_POST['Quantity'];
    $units = $_POST['Unit'];
    $unitPrices = $_POST['UnitPrice'];
    $totalPrices = $_POST['TotalPrice'];
    $remarks = $_POST['Remark']; // Array of remarks
    $orderDate = $_POST['OrderDate'];

    // Begin transaction
    $connect->begin_transaction();

    try {
        foreach ($itemNames as $index => $itemName) {
            $quantity = $quantities[$index];
            $unit = $units[$index];
            $unitPrice = $unitPrices[$index];
            $totalPrice = $totalPrices[$index];
            $remark = $remarks[$index]; // Extract the corresponding remark
            $quantityAvailable = $quantityAvailables[$index]; // Ensure this is initialized correctly

            // Check available quantity in raw_materials
            $sqlCheck = "SELECT purchase_quantity FROM raw_materials WHERE item_name = ?";
            $stmtCheck = $connect->prepare($sqlCheck);
            $stmtCheck->bind_param("s", $itemName);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                $rowCheck = $resultCheck->fetch_assoc();
                $availableQuantity = $rowCheck['purchase_quantity'];

                // Check if enough quantity is available
                if ($availableQuantity >= $quantity) {
                    // Insert record into request_order table
                    $sqlInsert = "INSERT INTO request_order (ItemName, RequestedBy, IssuedBy, ApprovedBy, Quantity, Unit, UnitPrice, TotalPrice, Remark, OrderDate) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert = $connect->prepare($sqlInsert);
                    $stmtInsert->bind_param("ssssisddss", $itemName, $requestedBy, $issuedBy, $approvedBy, $quantity, $unit, $unitPrice, $totalPrice, $remark, $orderDate);

                    if (!$stmtInsert->execute()) {
                        throw new Exception("Error inserting order for item: $itemName. " . $stmtInsert->error);
                    }

                    // Update the raw_materials table
                    $newQuantity = $availableQuantity - $quantity;
                    $sqlUpdate = "UPDATE raw_materials SET purchase_quantity = ? WHERE item_name = ?";
                    $stmtUpdate = $connect->prepare($sqlUpdate);
                    $stmtUpdate->bind_param("is", $newQuantity, $itemName);

                    if (!$stmtUpdate->execute()) {
                        throw new Exception("Error updating quantity for item: $itemName. " . $stmtUpdate->error);
                    }
                } else {
                    // Not enough quantity available
                    throw new Exception("Not enough inventory available for item: $itemName.");
                }
            } else {
                // Item not found
                throw new Exception("Item not found: $itemName.");
            }
        }

        // Commit the transaction
        $connect->commit();
        $valid['success'] = true;
        $valid['messages'][] = "Order placed successfully.";
        
        session_start();
        $_SESSION['success_message'] = "Order placed successfully.";
        header("Location: /wampme2/stock/stock/request_orders.php?o=manord");
        exit();

    } catch (Exception $e) {
        // Rollback the transaction on error
        $connect->rollback();
        $valid['success'] = false;
        $valid['messages'][] = "Error while placing the orders: " . $e->getMessage();
    }
} 

$connect->close();
echo json_encode($valid);
?>
