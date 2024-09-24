<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'core.php';

// Fetch order_id from URL
$orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : 0;

$sql = "SELECT order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, tinNumber, paid, due FROM orders WHERE order_id = $orderId";
$orderResult = $connect->query($sql);

if ($orderResult->num_rows > 0) {
    $orderData = $orderResult->fetch_array();
    $orderDate = $orderData[0];
    $clientName = $orderData[1];
    $clientContact = $orderData[2];
    $subTotal = $orderData[3];
    $vat = $orderData[4];
    $totalAmount = $orderData[5];
    $discount = $orderData[6];
    $grandTotal = $orderData[7];
    $tinNumber = $orderData[8];  // Added TIN number variable
    $paid = $orderData[9];
    $due = $orderData[10];
} else {
    $orderDate = $clientName = $clientContact = $subTotal = $vat = $totalAmount = $discount = $grandTotal = $tinNumber = $paid = $due = "N/A";
}

$orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total, product.product_name 
                 FROM order_item
                 INNER JOIN product ON order_item.product_id = product.product_id 
                 WHERE order_item.order_id = $orderId";
$orderItemResult = $connect->query($orderItemSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .invoice-header img {
            max-width: 150px;
            height: auto;
        }

        .invoice-header h1 {
            font-size: 24px;
            color: #333333;
            margin: 0;
        }

        .invoice-details, .order-details {
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-details table, .order-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-details th, .order-details th {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            text-align: left;
        }

        .invoice-details td, .order-details td {
            padding: 10px;
            border-bottom: 1px solid #dddddd;
        }

        .order-details th, .order-details td {
            text-align: center;
        }

        .total-summary {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .total-summary table {
            width: 300px;
            border-collapse: collapse;
        }

        .total-summary th, .total-summary td {
            padding: 10px;
            border-bottom: 1px solid #dddddd;
        }

        .total-summary th {
            background-color: #f4f4f4;
            color: #333333;
            text-align: left;
        }

        .total-summary td {
            text-align: right;
            font-weight: bold;
        }

        .button-container {
            display: flex;
            gap: 10px;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            border: none;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        @media print {
            .button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="yoyologo.png" alt="Company Logo">
            <h1>YoyoTwo7 Leather Product</h1>
        </div>

        <div class="button-container">
            <a href="fetchOrder.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Print
            </button>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <th>Order Date:</th>
                    <td><?php echo htmlspecialchars($orderDate); ?></td>
                </tr>
                <tr>
                    <th>Client Name:</th>
                    <td><?php echo htmlspecialchars($clientName); ?></td>
                </tr>
                <tr>
                    <th>Contact:</th>
                    <td><?php echo htmlspecialchars($clientContact); ?></td>
                </tr>
                <tr>
                    <th>TIN Number:</th>
                    <td><?php echo htmlspecialchars($tinNumber); ?></td>
                </tr>
            </table>
        </div>

        <div class="order-details">
            <table>
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Product</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    if ($orderItemResult->num_rows > 0) {
                        while ($row = $orderItemResult->fetch_array()) {
                            echo '<tr>
                                <td>' . $x . '</td>
                                <td>' . htmlspecialchars($row['product_name']) . '</td>
                                <td>' . htmlspecialchars($row['rate']) . '</td>
                                <td>' . htmlspecialchars($row['quantity']) . '</td>
                                <td>' . htmlspecialchars($row['total']) . '</td>
                            </tr>';
                            $x++;
                        }
                    } else {
                        echo '<tr><td colspan="5">No items found for this order.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="total-summary">
            <table>
                <tr>
                    <th>Sub Total:</th>
                    <td><?php echo htmlspecialchars($subTotal); ?></td>
                </tr>
                <tr>
                    <th>VAT:</th>
                    <td><?php echo htmlspecialchars($vat); ?></td>
                </tr>
                <tr>
                    <th>Total Amount:</th>
                    <td><?php echo htmlspecialchars($totalAmount); ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$connect->close();
?>
