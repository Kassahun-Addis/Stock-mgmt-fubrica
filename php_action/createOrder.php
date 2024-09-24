<?php 

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'order_id' => '');

if ($_POST) {

    // Fetch the username based on session userId
    $userId = $_SESSION['userId'];
    $usernameQuery = "SELECT username FROM users WHERE user_id = '$userId'";
    $usernameResult = $connect->query($usernameQuery);
    $username = '';
    if ($usernameResult->num_rows == 1) {
        $userRow = $usernameResult->fetch_assoc();
        $username = $userRow['username'];  // Get the username
    }

    // Get the location name directly from the form submission
    $sellsLocation = $_POST['sellsLocation']; // This now holds the location name

    $orderDate            = date('Y-m-d', strtotime($_POST['orderDate']));
    $clientName           = $_POST['clientName'];
    $clientContact        = $_POST['clientContact'];
    $subTotalValue        = $_POST['subTotalValue'];
    $vatValue             = $_POST['vatValue'];
    $totalAmountValue     = $_POST['totalAmountValue'];
    $discount             = $_POST['discount'];
    $grandTotalValue      = $_POST['grandTotalValue'];
    $paid                 = $_POST['paid'];
    $dueValue             = $_POST['dueValue'];
    $paymentType          = $_POST['paymentType'];
    $paymentStatus        = $_POST['paymentStatus'];
    $fsNumber             = $_POST['fsNumber'];
    $tinNumber            = $_POST['tinNumber'];
    $bankName             = $_POST['bankName'];
    $transactionNumber    = $_POST['transactionNumber'];

    // SQL statement to insert the order with the location name included
    $sql = "INSERT INTO orders (
                order_date, 
                client_name, 
                client_contact, 
                sub_total, 
                vat, 
                total_amount, 
                discount, 
                grand_total, 
                paid, 
                due, 
                payment_type, 
                payment_status, 
                order_status, 
                fs_no, 
                tinNumber, 
                bank_name, 
                transaction_number, 
                sells_location, 
                user_name  
            ) VALUES (
                '$orderDate', 
                '$clientName', 
                '$clientContact', 
                '$subTotalValue', 
                '$vatValue', 
                '$totalAmountValue', 
                '$discount', 
                '$grandTotalValue', 
                '$paid', 
                '$dueValue', 
                '$paymentType', 
                '$paymentStatus', 
                1, 
                '$fsNumber', 
                '$tinNumber', 
                '$bankName', 
                '$transactionNumber', 
                '$sellsLocation', 
                '$username'
            )";

    // Execute the query and check for errors
    if ($connect->query($sql) === TRUE) {
        $order_id = $connect->insert_id;
        $valid['order_id'] = $order_id;
        $valid['success'] = true;
        $valid['messages'] = "Successfully Added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error: " . $sql . "<br>" . $connect->error;
    }

    // Add products to the order
    $orderItemStatus = false;
    for ($x = 0; $x < count($_POST['productName']); $x++) {
        $updateProductQuantitySql = "SELECT finished_good.quantity FROM finished_good WHERE finished_good.product_id = ".$_POST['productName'][$x];
        $updateProductQuantityData = $connect->query($updateProductQuantitySql);

        while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
            $updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];

            // Update product table
            $updateProductTable = "UPDATE finished_good SET quantity = '".$updateQuantity[$x]."' WHERE product_id = ".$_POST['productName'][$x];
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
                                '$order_id', 
                                '".$_POST['productName'][$x]."', 
                                '".$_POST['quantity'][$x]."', 
                                '".$_POST['rateValue'][$x]."', 
                                '".$_POST['totalValue'][$x]."', 
                                1, 
                                '".$_POST['purchaseValue'][$x]."',  
                                '".$_POST['serialNoValue'][$x]."'
                            )";

            $connect->query($orderItemSql);

            if ($x == count($_POST['productName'])) {
                $orderItemStatus = true;
            }
        } // while
    } // /for quantity

    $valid['success'] = true;
    $valid['messages'] = "Successfully Added";

    $connect->close();

    echo json_encode($valid);
} // /if $_POST

?>
