<?php
require_once 'core.php';

// Query to get product data for CSV export
$sql = "SELECT product.product_name, product.rate, product.quantity, brands.brand_name, 
        categories.categories_name, product.date, product.purchase, product.supplier, product.serial_no 
        FROM product 
        INNER JOIN brands ON product.brand_id = brands.brand_id 
        INNER JOIN categories ON product.categories_id = categories.categories_id  
        WHERE product.status = 1";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Set headers for the CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=product_list.csv');
    
    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');
    
    // Add column headers to the CSV
    fputcsv($output, array('Product Name', 'Rate', 'Quantity', 'Brand', 'Category', 'Date', 'Purchase', 'Supplier', 'Serial No'));

    // Fetch each row and output it to CSV
    while ($row = $result->fetch_assoc()) {
        // Format date for CSV
        $row['date'] = date('d-m-Y', strtotime($row['date']));

        // Add row to CSV
        fputcsv($output, $row);
    }
    
    // Close the output stream
    fclose($output);
} else {
    echo "No records found.";
}

$connect->close();
?>
