<?php

require_once 'core.php';

// Default response
$response = ['success' => false, 'messages' => ''];

// Check if wastage ID is passed
if (isset($_POST['wastageId'])) {
    $wastageId = $_POST['wastageId'];

    // Prepare SQL query to delete the wastage record
    $sql = "DELETE FROM wastage WHERE id = ?";

    if ($stmt = $connect->prepare($sql)) {
        // Bind the wastage ID parameter
        $stmt->bind_param("i", $wastageId);

        // Execute the query
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['messages'] = 'Wastage record deleted successfully.';
        } else {
            $response['messages'] = 'Failed to delete wastage record.';
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        $response['messages'] = 'Failed to prepare SQL statement.';
    }

    // Close the database connection
    $connect->close();
} else {
    $response['messages'] = 'No wastage ID provided.';
}

// Return JSON response
echo json_encode($response);

?>
