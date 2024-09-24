<?php
require_once 'core.php';

if ($_POST) {
    // Collect form data
    $item_name = $_POST['item_name'];
    $purchase_quantity = $_POST['purchase_quantity'];
    $unit = $_POST['unit'];
    $alert_quantity = $_POST['alert_quantity'];
    $purchase_price = $_POST['purchase_price'];
    $purchase_date = $_POST['purchase_date'];
    $purchased_by = $_POST['purchased_by'];
    $supplier = $_POST['supplier'];
    $paidAmount = $_POST['paidAmount'];
    $tinNumber = $_POST['tinNumber'];
    $transactionNumber = $_POST['transactionNumber'];

    // Initialize the response array
    $response = array();

    // Calculate the due amount
    $dueAmount = ($purchase_price * $purchase_quantity) - $paidAmount;

    // Prepare the SQL statement for inserting into raw_materials
    $sql = "INSERT INTO raw_materials (item_name, purchase_quantity, unit, alert_quantity, purchase_price, purchase_date, purchased_by, supplier, paid_amount, transaction_number, TinNumber)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        // Prepare the statement for raw_materials
        if ($stmt = $connect->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sssssssssss", $item_name, $purchase_quantity, $unit, $alert_quantity, $purchase_price, $purchase_date, $purchased_by, $supplier, $paidAmount, $transactionNumber, $tinNumber);

            // Execute the statement
            if ($stmt->execute()) {
                $materialId = $connect->insert_id; // Get the last inserted material ID from raw_materials

                // Check if due amount is greater than 0
                if ($dueAmount > 0) {
                    // Prepare the SQL statement for inserting into credit
                    $creditSql = "INSERT INTO credit (product_id, product_name, supplier, quantity, purchase, paid_amount, total_due_amount, remaining_due_amount, transaction_number)
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    if ($creditStmt = $connect->prepare($creditSql)) {
                        // Bind parameters, use $materialId for product_id
                        $creditStmt->bind_param("issidddds", $materialId, $item_name, $supplier, $purchase_quantity, $purchase_price, $paidAmount, $purchase_price, $dueAmount, $transactionNumber);
                        
                        // Execute the credit statement
                        $creditStmt->execute();
                        $creditStmt->close();
                    } else {
                        $response['success'] = false;
                        $response['messages'] = "Error preparing credit statement: " . $connect->error;
                        echo json_encode($response);
                        exit();
                    }
                }

                $response['success'] = true;
                $response['messages'] = "Successfully Added";
            } else {
                $response['success'] = false;
                $response['messages'] = "Error while adding the raw material: " . $stmt->error;
            }

            // Close the raw material statement
            $stmt->close();
        } else {
            $response['success'] = false;
            $response['messages'] = "Error preparing raw material statement: " . $connect->error;
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['messages'] = "An error occurred: " . $e->getMessage();
    }

    // Close the database connection
    $connect->close();

    // Return the response as JSON
    echo json_encode($response);
}
?>
