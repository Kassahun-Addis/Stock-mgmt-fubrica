<?php 
require_once 'db_connect.php';

// SQL query to fetch product data
$sql = "SELECT fg.product_name, fg.selling_price, fg.quantity, c.categories_name, fg.date_added, fg.purchase_cost, fg.serial_no, 
        CASE 
            WHEN fg.product_status = 1 THEN 'Available'
            WHEN fg.product_status = 2 THEN 'Not Available'
        END AS status 
        FROM finished_good fg 
        JOIN categories c ON fg.category_id = c.categories_id";

$result = $connect->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
    // Fetching data row by row
    while ($row = $result->fetch_assoc()) {
        $output['data'][] = array(
            $row['product_name'],
            number_format($row['selling_price'], 2), // Formatting selling price
            $row['quantity'],
            $row['categories_name'], // Category name
            date('Y-m-d', strtotime($row['date_added'])), // Formatting date
            number_format($row['purchase_cost'], 2), // Formatting production cost
            $row['serial_no'], // Detailed specification (serial number)
            $row['status'], // Product status
        );
    }
}

// Close database connection
$connect->close();

// Return data in JSON format
echo json_encode($output);

?>
