<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    $creditId = $_POST['creditId'];
    $supplier = $_POST['supplier'];
    $paidAmount = $_POST['paidAmount'];
    $transactionNumber = $_POST['transactionNumber'];

    // Check if all required POST fields are present
    if (!isset($creditId, $supplier, $paidAmount, $transactionNumber)) {
        $valid['messages'] = "Missing required fields.";
        echo json_encode($valid);
        exit;
    }

    // Retrieve the credit record based on creditId and supplier
    $creditQuery = "SELECT * FROM credit WHERE credit_id = '$creditId' AND supplier = '$supplier' LIMIT 1";
    $result = $connect->query($creditQuery);

    if (!$result) {
        $valid['messages'] = "Error executing query: " . $connect->error;
        echo json_encode($valid);
        exit;
    }

    if ($result->num_rows > 0) {
        $creditData = $result->fetch_assoc();
        $remainingDueAmount = $creditData['remaining_due_amount'];
        $totalPaidAmount = $creditData['paid_amount'];

        // Calculate the new remaining due amount
        $newRemainingDueAmount = $remainingDueAmount - $paidAmount;
        if ($newRemainingDueAmount < 0) {
            $valid['messages'] = "Paid amount exceeds the remaining due amount.";
            echo json_encode($valid);
            exit;
        }

        // Insert the new payment record
        $newPaymentQuery = "INSERT INTO credit_payments (credit_id, paid_amount,dueAmount, transaction_number, payment_date) 
                            VALUES ('$creditId', '$paidAmount','$newRemainingDueAmount', '$transactionNumber', NOW())";
        if ($connect->query($newPaymentQuery) === TRUE) {
            // Update the remaining due amount
            $newTotalPaidAmount = $totalPaidAmount + $paidAmount;
            $updateCreditQuery = "UPDATE credit 
                                  SET remaining_due_amount = '$newRemainingDueAmount', 
                                      paid_amount = '$newTotalPaidAmount'
                                  WHERE credit_id = '$creditId'";

            if ($connect->query($updateCreditQuery) === TRUE) {
                $valid['success'] = true;
                $valid['messages'] = "Payment recorded successfully.";
            } else {
                $valid['messages'] = "Error updating credit record: " . $connect->error;
            }
        } else {
            $valid['messages'] = "Error recording payment: " . $connect->error;
        }
    } else {
        $valid['messages'] = "No credit record found for this credit ID and supplier.";
    }

    $connect->close();

    echo json_encode($valid);
}
?>
