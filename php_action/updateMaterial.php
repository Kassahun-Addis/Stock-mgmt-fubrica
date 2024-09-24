<?php
require_once 'db_connect.php';

if ($_POST) {
    $material_id = $_POST['material_id'];
    $item_name = $_POST['item_name'];
    $purchase_quantity = $_POST['purchase_quantity'];
    $unit = $_POST['unit'];
    $alert_quantity = $_POST['alert_quantity'];
    $purchase_price = $_POST['purchase_price'];
    $purchase_date = $_POST['purchase_date'];
    $purchased_by = $_POST['purchased_by'];
    $supplier = $_POST['supplier'];


    $sql = "UPDATE raw_materials
            SET item_name = ?, 
                purchase_quantity = ?, 
                unit = ?, 
                alert_quantity = ?, 
                purchase_price = ?, 
                purchase_date = ?, 
                purchased_by = ?, 
                supplier = ?
            WHERE material_id = ?";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param('ssssssssi', $item_name, $purchase_quantity, $unit, $alert_quantity, $purchase_price, $purchase_date, $purchased_by, $supplier, $material_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'messages' => 'Material updated successfully']);
    } else {
        echo json_encode(['success' => false, 'messages' => 'Error updating material']);
    }
    $stmt->close();
}
?>
