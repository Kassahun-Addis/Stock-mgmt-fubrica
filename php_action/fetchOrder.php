<?php 

require_once 'core.php';

$sql = "SELECT order_id, order_date, client_name, client_contact, bank_name, transaction_number, tinNumber, user_name, user_phone,sells_location, payment_status FROM orders WHERE order_status = 1";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 

    $paymentStatus = ""; 
    $x = 1;

    while($row = $result->fetch_array()) {

        $orderId = $row[0];

        $countOrderItemSql = "SELECT count(*) FROM order_item WHERE order_id = $orderId";
        $itemCountResult = $connect->query($countOrderItemSql);
        $itemCountRow = $itemCountResult->fetch_row();

        // Determine payment status
      // Determine payment status
if($row[10] == 1) {        
    $paymentStatus = "<label class='label label-success'>Full Payment</label>";
} else if($row[10] == 2) {        
    $paymentStatus = "<label class='label label-info'>Advance Payment</label>";
} else {        
    $paymentStatus = "<label class='label label-warning'>No Payment</label>";
}


        // Prepare action buttons
        $button = '<!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="orders.php?o=editOrd&i='.$orderId.'" id="editOrderModalBtn"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
                <li><a type="button" data-toggle="modal" id="paymentOrderModalBtn" data-target="#paymentOrderModal" onclick="paymentOrder('.$orderId.')"> <i class="glyphicon glyphicon-save"></i> Payment</a></li>
                <li><a type="button" onclick="printOrder('.$orderId.')"> <i class="glyphicon glyphicon-print"></i> Print </a></li>
                <li><a type="button" data-toggle="modal" data-target="#removeOrderModal" id="removeOrderModalBtn" onclick="removeOrder('.$orderId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>
            </ul>
        </div>';

        // Add data to output array
        $output['data'][] = array(
            $x,
            $row[1], // order date
            $row[2], // client name
            $row[3], // client contact         
            $itemCountRow[0], // total order item count
            $paymentStatus, // payment status
            $row[4] ? $row[4] : 'NULL', // bank_name
            $row[5] ? $row[5] : 'NULL', // transaction_number
            $row[6] ? $row[6] : 'NULL', // tinNumber
            $row[7] ? $row[7] : 'NULL', // user_name
            $row[8] ? $row[8] : 'NULL', // user_phone
            $row[9] ? $row[9] : 'NULL', // sells_location
            $button // action buttons
        ); 

        $x++;
    } // /while 

} // if num_rows

$connect->close();

echo json_encode($output); 
?>
