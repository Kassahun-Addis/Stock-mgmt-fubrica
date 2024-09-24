<?php
require_once 'core.php';

// Query to fetch order data for CSV export
$sql = "SELECT orders.order_date, orders.client_name, orders.client_contact, 
        (SELECT count(*) FROM order_item WHERE order_item.order_id = orders.order_id) AS total_items,
        CASE 
            WHEN orders.payment_status = 1 THEN 'Full Payment'
            WHEN orders.payment_status = 2 THEN 'Advance Payment'
            ELSE 'No Payment' 
        END AS payment_status,
        orders.bank_name, 
        orders.transaction_number, 
        orders.tinNumber, 
        orders.user_name, 
        orders.user_phone
        FROM orders 
        WHERE orders.order_status = 1";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Set headers to force download the CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=order_list.csv');

    // Open output stream for the CSV file
    $output = fopen('php://output', 'w');

    // Add column headers to the CSV file
    fputcsv($output, array('Order Date', 'Client Name', 'Client Contact', 'Total Items', 'Payment Status', 'Bank Name', 'Transaction Number', 'TIN Number', 'User Name', 'User Phone'));

    // Fetch and output rows one by one
    while ($row = $result->fetch_assoc()) {
        // If bank_name, transaction_number, tinNumber, user_name, or user_phone are NULL, replace with 'NULL'
        $row['bank_name'] = $row['bank_name'] ? $row['bank_name'] : 'NULL';
        $row['transaction_number'] = $row['transaction_number'] ? $row['transaction_number'] : 'NULL';
        $row['tinNumber'] = $row['tinNumber'] ? $row['tinNumber'] : 'NULL';
        $row['user_name'] = $row['user_name'] ? $row['user_name'] : 'NULL';
        $row['user_phone'] = $row['user_phone'] ? $row['user_phone'] : 'NULL';

        // Add the row to the CSV file
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
} else {
    echo "No orders found.";
}

$connect->close();
?>
