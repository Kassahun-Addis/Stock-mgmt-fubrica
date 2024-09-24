<?php
require_once 'core.php';

// Query to get expense data for CSV export
$sql = "SELECT ShipmentID, Assigned_person, ShipmentDate, Carrier, TrackingNumber, ShippingAddress, ShippingCost, Status FROM shipment"; 

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Set headers for the CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=expense_list.csv');
    
    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');
    
    // Add column headers to the CSV
    fputcsv($output, array('Id', 'Assigned Person', 'Shipment Date', 'Carrrier','Tracking No', 'Shipment Address', 'Shipment Cost', 'Status'));

    // Fetch each row and output it to CSV
    while ($row = $result->fetch_assoc()) {
        // Format date for CSV
        $row['ShipmentDate'] = date('d-m-Y', strtotime($row['ShipmentDate']));

        // Add row to CSV
        fputcsv($output, $row);
    }
    
    // Close the output stream
    fclose($output);
} else {
    // If no records found, return a message
    echo "No records found.";
}

$connect->close();
?>