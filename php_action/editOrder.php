<?php 

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
    $orderId = $_POST['orderId'];

    $orderDate = date('Y-m-d', strtotime($_POST['orderDate']));
    $clientName = $_POST['clientName'];
    $clientContact = $_POST['clientContact'];
    $subTotalValue = $_POST['subTotalValue'];
    $vatValue = $_POST['vatValue'];
    $totalAmountValue = $_POST['totalAmountValue'];
    $discount = $_POST['discount'];
    $grandTotalValue = $_POST['grandTotalValue'];
    $paid = $_POST['paid'];
    $dueValue = $_POST['dueValue'];
    $paymentType = $_POST['paymentType'];
    $paymentStatus = $_POST['paymentStatus'];
    $fsNumber = $_POST['fsNumber'];
    $bankName = isset($_POST['bankName']) ? $_POST['bankName'] : null; // Optional field
    $transactionNumber = isset($_POST['transactionNumber']) ? $_POST['transactionNumber'] : null; // Optional field

    // Update the order
    $sql = "UPDATE orders SET 
                order_date = '$orderDate', 
                client_name = '$clientName', 
                client_contact = '$clientContact', 
                sub_total = '$subTotalValue', 
                vat = '$vatValue', 
                total_amount = '$totalAmountValue', 
                discount = '$discount', 
                grand_total = '$grandTotalValue', 
                paid = '$paid', 
                due = '$dueValue', 
                payment_type = '$paymentType', 
                payment_status = '$paymentStatus', 
                fs_no = '$fsNumber', 
                bank_name = " . ($bankName ? "'$bankName'" : "NULL") . ", 
                transaction_number = " . ($transactionNumber ? "'$transactionNumber'" : "NULL") . ", 
                order_status = 1 
            WHERE order_id = {$orderId}";

    if ($connect->query($sql) === TRUE) {
        // Remove existing order items
        $removeOrderItemsSql = "DELETE FROM order_item WHERE order_id = {$orderId}";
        $connect->query($removeOrderItemsSql);

        // Update product quantities and add new order items
        for($x = 0; $x < count($_POST['productName']); $x++) {
            // Update product quantity
            $updateProductQuantitySql = "SELECT quantity FROM product WHERE product_id = " . $_POST['productName'][$x];
            $updateProductQuantityData = $connect->query($updateProductQuantitySql);

            while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
                $updateQuantity = $updateProductQuantityResult[0] - $_POST['quantity'][$x];

                // Update product table
                $updateProductTable = "UPDATE product SET quantity = '$updateQuantity' WHERE product_id = " . $_POST['productName'][$x];
                $connect->query($updateProductTable);

                // Add into order_item
                $orderItemSql = "INSERT INTO order_item (
                                    order_id, 
                                    product_id, 
                                    quantity, 
                                    rate, 
                                    total, 
                                    order_item_status, 
                                    purchase, 
                                    serial_no
                                ) VALUES (
                                    {$orderId}, 
                                    '" . $_POST['productName'][$x] . "', 
                                    '" . $_POST['quantity'][$x] . "', 
                                    '" . $_POST['rateValue'][$x] . "', 
                                    '" . $_POST['totalValue'][$x] . "', 
                                    1, 
                                    '" . $_POST['purchaseValue'][$x] . "',  
                                    '" . $_POST['serialNoValue'][$x] . "'
                                )";

                $connect->query($orderItemSql);
            } // while
        } // for

        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error: " . $sql . "<br>" . $connect->error;
    }

    $connect->close();
    echo json_encode($valid);
} // /if $_POST
?>
