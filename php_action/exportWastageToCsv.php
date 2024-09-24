<?php
// db connection
require_once 'db_connect.php';

// Set the headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=wastage.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Set column headers
fputcsv($output, array('Product Name', 'Quantity', 'Unit', 'Wastage Date', 'Reason'));

// Fetch data from wastage table
$sql = "SELECT product_name, quantity, unit, wastage_date, reason FROM wastage";
$result = $connect->query($sql);

// If data exists, fetch and write to CSV
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    echo "No data available";
}

// Close the file pointer
fclose($output);
?>
