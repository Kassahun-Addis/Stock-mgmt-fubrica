<?php
require_once 'core.php';

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch assets data from the database
$sql = "SELECT * FROM assets";
$result = $connect->query($sql);

// Check if there are records
if ($result->num_rows > 0) {
    // Create CSV file and open it for writing
    $filename = "assets_export_" . date('Ymd') . ".csv";
    $file = fopen($filename, 'w');

    // Add CSV column headers
    $headers = array('ID', 'Asset Name', 'Category', 'Description', 'Purchase Date', 'Purchase Price', 'Department', 'Last Maintenance Date', 'Status', 'Assigned To', 'Remark', 'Serial No');
    fputcsv($file, $headers);

    // Write data rows
    while ($row = $result->fetch_assoc()) {
        $csvRow = array(
            $row['id'],
            $row['asset_name'],
            $row['category'],
            $row['description'],
            $row['purchase_date'],
            $row['purchase_price'],
            $row['department'],
            $row['last_maintenance_date'],
            $row['status'],
            $row['assigned_to'],
            $row['remark'],
            $row['serial_no']
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
