<?php 

require_once 'core.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$reportType = $_POST['reportType'];

// Convert dates to the appropriate format
$startDate = DateTime::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
$endDate = DateTime::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');

// Header HTML
$header = '
    <div style="display: flex; align-items: center; margin-bottom: 20px;">
        <img src="yoyologo.png" alt="Company Logo" style="height: 50px; margin-right: 10px;">
        <div>
            <h1>YoyoTwo7 Leather product</h1>
        </div>
    </div>
';

if ($reportType == 'orders') {
    // SQL query for order report
    $sql = "
    SELECT 
        o.order_date,
        o.client_name,
        o.client_contact,
        o.grand_total,
        oi.quantity,
        p.product_name
    FROM 
        orders o
    INNER JOIN 
        order_item oi ON o.order_id = oi.order_id
    INNER JOIN 
        product p ON oi.product_id = p.product_id
    WHERE 
        o.order_date BETWEEN '$startDate' AND '$endDate' 
        AND o.order_status = 1
    ";

    $query = $connect->query($sql);

    if (!$query) {
        die("Query failed: " . mysqli_error($conn));
    }

    $table = $header . '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Order Date</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Client Name</th>
            <th>Contact</th>
            <th>Grand Total</th>
        </tr>';

    $totalAmount = 0; // Initialize totalAmount to zero
    while ($result = mysqli_fetch_assoc($query)) {
        $totalAmount += (float)$result['grand_total']; // Accumulate grand_total
        $table .= '<tr>
            <td><center>'.$result['order_date'].'</center></td>
            <td><center>'.$result['product_name'].'</center></td>
            <td><center>'.$result['quantity'].'</center></td>
            <td><center>'.$result['client_name'].'</center></td>
            <td><center>'.$result['client_contact'].'</center></td>
            <td><center>'.$result['grand_total'].'</center></td>
        </tr>';  
    }

    $table .= '
        <tr>
            <td colspan="5"><center>Total Amount</center></td>
            <td><center>'.number_format($totalAmount, 2).'</center></td>
        </tr>
    </table>';

} elseif ($reportType == 'products') {
    // SQL query for product report
    $sql = "SELECT product.product_id, product.product_name, product.product_image, product.brand_id,
        product.categories_id, product.quantity, product.rate, product.active,
        product.status, 
        brands.brand_name, 
        categories.categories_name, 
        product.purchase, 
        product.supplier,
        product.serial_no,
        product.alert_quantity,
        product.date
        FROM product 
        INNER JOIN brands ON product.brand_id = brands.brand_id 
        INNER JOIN categories ON product.categories_id = categories.categories_id  
        WHERE product.status = 1";

    $query = $connect->query($sql);

    if (!$query) {
        die("Query failed: " . mysqli_error($conn));
    }
$table = $header . '
    <table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>purchase</th>
            <th>rate</th>
            <th>Serial No</th>
        </tr>';

    while ($result = mysqli_fetch_assoc($query)) {
        $table .= '<tr>
            <td><center>'.$result['product_name'].'</center></td>
            <td><center>'.$result['quantity'].'</center></td>
            <td><center>'.$result['supplier'].'</center></td>
            <td><center>'.$result['date'].'</center></td>
              <td><center>'.$result['purchase'].'</center></td>
                <td><center>'.$result['rate'].'</center></td>
            <td><center>'.$result['serial_no'].'</center></td>
        </tr>';  
    }

    $table .= '</table>';
}

echo $table;

?>
