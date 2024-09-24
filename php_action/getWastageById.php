<?php

require_once 'db_connect.php';

// Default response
$response = ['success' => false, 'data' => []];

// Check if wastage ID is passed
if (isset($_POST['wastageId'])) {
    $wastageId = $_POST['wastageId'];

    // Prepare SQL query to fetch the wastage record by ID
    if ($stmt = $connect->prepare("SELECT id, product_name, quantity, unit, wastage_date, reason FROM wastage WHERE id = ?")) {
        // Bind parameters
        $stmt->bind_param("i", $wastageId);

        // Execute query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Check if record exists
        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['data'] = $result->fetch_assoc(); // Fetch the row data
        } else {
            $response['messages'] = 'Wastage record not found';
        }

        // Close the statement
        $stmt->close();
    } else {
        $response['messages'] = 'Error preparing statement';
    }

    // Close the database connection
    $connect->close();
} else {
    $response['messages'] = 'No wastage ID provided';
}

// Return JSON response
echo json_encode($response);

?>
