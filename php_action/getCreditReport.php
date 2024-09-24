<?php
require_once 'core.php'; // Ensure this includes your database connection setup

header('Content-Type: text/html; charset=utf-8'); // Return HTML content

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchField = isset($_POST['searchField']) ? trim($_POST['searchField']) : '';

    // Validate and ensure searchField is not empty
    if (!empty($searchField)) {
        // Determine if searchField is a numeric ID or a text supplier
        $isNumeric = is_numeric($searchField);

        if ($isNumeric) {
            // Search by credit_id
            $sql = "SELECT cp.credit_id, cp.paid_amount, cp.transaction_number, cp.payment_date, 
                           c.product_name, c.supplier, cp.dueAmount 
                    FROM credit_payments cp
                    JOIN credit c ON cp.credit_id = c.credit_id
                    WHERE cp.credit_id = ?
                    ORDER BY cp.payment_date ASC";
        } else {
            // Search by supplier and fetch all related credit IDs
            $sql = "SELECT c.supplier, c.product_name, cp.credit_id, 
                           cp.paid_amount, cp.transaction_number, cp.payment_date, 
                           cp.dueAmount
                    FROM credit_payments cp
                    JOIN credit c ON cp.credit_id = c.credit_id
                    WHERE c.supplier LIKE ?
                    ORDER BY cp.credit_id, cp.payment_date ASC";
        }

        $stmt = $connect->prepare($sql);

        if ($stmt) {
            if ($isNumeric) {
                $stmt->bind_param('i', $searchField); // Assuming credit_id is an integer
            } else {
                $searchField = '%' . $searchField . '%'; // For partial matches
                $stmt->bind_param('s', $searchField); // Assuming supplier is a string
            }
            $stmt->execute();
            $result = $stmt->get_result();

            // Generate HTML report
            ob_start(); // Start output buffering

            echo '<html><head><title>Credit Report</title>';
            echo '<style>
                    body { font-family: Arial, sans-serif; }
                    .header { display: flex; justify-content: space-between; align-items: center; padding: 20px; }
                    .header img { height: 50px; } /* Adjust logo size as needed */
                    .header h3 { margin: 0; }
                    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                    table, th, td { border: 1px solid black; }
                    th, td { padding: 8px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    </style>';
            echo '</head><body>';
            
            // Header with logo and company name
            echo '<div class="header">';
            echo '<img src="yoyologo.png" alt="Company Logo">'; // Update with your logo path
            echo '<h3>YoyoTwo7 Leather product</h3>';
            echo '</div>';

            echo '<table>';
            echo '<tr><th>Supplier</th><th>Product Name</th><th>Credit ID</th><th>Paid Amount</th><th>Transaction Number</th><th>Payment Date</th><th>Remaining Due Amount</th></tr>';

            $totalPaidAmount = 0;
            $dueAmounts = [];
            $rowCount = 0;

            while ($row = $result->fetch_assoc()) {
                $rowCount++;
                $creditId = $row['credit_id'];
                $dueAmounts[$creditId] = $row['dueAmount']; // Store the dueAmount for each credit_id

                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['supplier']) . '</td>';
                echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['credit_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['paid_amount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['transaction_number']) . '</td>';
                echo '<td>' . htmlspecialchars($row['payment_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['dueAmount']) . '</td>';
                echo '</tr>';

                // Track total paid amount
                $totalPaidAmount += (float) $row['paid_amount'];
            }

            // Calculate total due amount as sum of last dueAmount for each unique credit_id
            $totalDueAmount = array_sum($dueAmounts);

            if ($rowCount === 0) {
                echo '<tr><td colspan="7">No records found for: ' . htmlspecialchars($searchField) . '</td></tr>';
            } else {
                echo '<tr>';
                echo '<td colspan="3">Total Paid Amount</td>';
                echo '<td>' . number_format($totalPaidAmount, 2) . '</td>';
                echo '<td colspan="3"></td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td colspan="3">Total Remaining Due Amount</td>';
                echo '<td>' . number_format($totalDueAmount, 2) . '</td>';
                echo '<td colspan="3"></td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</body></html>';

            // Output the HTML directly
            echo ob_get_clean(); // Get the buffer content and clean the buffer
        } else {
            echo 'Database prepare error: ' . $connect->error;
        }

        $stmt->close();
    } else {
        echo 'Search field is required.';
    }

    $connect->close();
} else {
    echo 'Invalid request method. POST required.';
}
