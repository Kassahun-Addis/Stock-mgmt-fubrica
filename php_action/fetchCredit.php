<?php
require_once 'core.php';

// Adjusted SQL query to match the columns in the credit table
$sql = "SELECT credit.credit_id, credit.product_id, credit.supplier, credit.product_name, 
        credit.quantity, credit.purchase, credit.paid_amount, credit.remaining_due_amount, 
        credit.transaction_number, credit.created_at
        FROM credit
        INNER JOIN raw_materials ON credit.product_id = raw_materials.material_id
        WHERE credit.status = 'active'";

$result = $connect->query($sql);

if (!$result) {
    // Output detailed error message
    $error = $connect->error;
    $response = array('success' => false, 'message' => "SQL Error: $error");
    echo json_encode($response);
    exit();
}

$output = array('data' => array());

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $creditId = $row[0];

        // Format date with numeric month
        $formattedDate = date('d-m-Y', strtotime($row[9]));

   

        $button =  '<div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a type="button" data-toggle="modal" id="editCreditModalBtn" data-target="#editCreditModal" onclick="editCredit('.$creditId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                <li><a type="button" data-toggle="modal" data-target="#removeCreditModal" id="removeCreditModalBtn" onclick="removeCredit('.$creditId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>
            </ul>
        </div>';

        $output['data'][] = array(
            // Credit ID
            $creditId,
            // Product Name
            $row[3],
            // Supplier
            $row[2],
            // Quantity
            $row[4],
            // Purchase
            $row[5],
            // Paid Amount
            $row[6],
            // Due Amount
            $row[7],
            // Transaction Number
            $row[8],
            // Date
            $formattedDate,
            // Options
            $button
        );
    }
}

$connect->close();

echo json_encode($output);
?>
