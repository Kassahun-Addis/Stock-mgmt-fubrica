<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

// Function to check if a variable is set and not empty
function checkField($field) {
    return isset($field) && !empty($field);
}

if ($_POST) {
    $creditId = $_POST['editId'] ?? null;
    $supplier = $_POST['editSupplier'] ?? null;
    $productName = $_POST['editProductName'] ?? null;
    $quantity = $_POST['editQuantity'] ?? null;
    $purchase = $_POST['editPurchase'] ?? null;
    $paidAmount = $_POST['editPaidAmount'] ?? null;
    $remainingDueAmount = $_POST['editDueAmount'] ?? null;
    $transactionNumber = $_POST['editTransactionNumber'] ?? null;

    // Debugging output
    $missingFields = [];

    if (!checkField($creditId)) $missingFields[] = 'creditId';
    if (!checkField($supplier)) $missingFields[] = 'supplier';
    if (!checkField($productName)) $missingFields[] = 'productName';
    if (!checkField($quantity)) $missingFields[] = 'quantity';
    if (!checkField($purchase)) $missingFields[] = 'purchase';
    if (!checkField($paidAmount)) $missingFields[] = 'paidAmount';
    if (!checkField($remainingDueAmount)) $missingFields[] = 'remainingDueAmount';
    if (!checkField($transactionNumber)) $missingFields[] = 'transactionNumber';

    if (empty($missingFields)) {
        $sql = "UPDATE credit SET 
                    supplier = '$supplier', 
                    product_name = '$productName', 
                    quantity = '$quantity', 
                    purchase = '$purchase', 
                    paid_amount = '$paidAmount', 
                    remaining_due_amount = '$remainingDueAmount', 
                    transaction_number = '$transactionNumber'
                WHERE credit_id = {$creditId}";

        if ($connect->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Updated";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while updating the credit: " . $connect->error;
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "All fields are required: " . implode(", ", $missingFields);
    }

    $connect->close();

    echo json_encode($valid);
}
?>
