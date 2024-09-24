<?php
require_once 'core.php';

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch raw materials data from the database
$sql = "SELECT * FROM raw_materials";
$result = $connect->query($sql);

// Check if there are records
if ($result->num_rows > 0) {
    // Create CSV file and open it for writing
    $filename = "raw_materials_export_" . date('Ymd') . ".csv";
    $file = fopen($filename, 'w');

    // Add CSV column headers
    $headers = array('Material ID', 'Item Name', 'Purchase Quantity', 'Unit', 'Alert Quantity', 'Purchase Price', 'Paid Amount', 'Transaction Number', 'Tin Number', 'Purchase Date', 'Purchased By', 'Supplier', 'Created At');
    fputcsv($file, $headers);

    // Write data rows
    while ($row = $result->fetch_assoc()) {
        $csvRow = array(
            $row['material_id'],
            $row['item_name'],
            $row['purchase_quantity'],
            $row['unit'],
            $row['alert_quantity'],
            $row['purchase_price'],
            $row['paid_amount'],
            $row['transaction_number'],
            $row['TinNumber'],
            $row['purchase_date'],
            $row['purchased_by'],
            $row['supplier'],
            $row['created_at']
        );
        fputcsv($file, $csvRow);
    }

    // Close the file after writing
    fclose($file);

    // Set headers to download file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Remove file from server after download
    unlink($filename);

} else {
    echo "No records found.";
}

// Close the database connection
$connect->close();
?>