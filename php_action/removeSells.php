<?php
require_once 'core.php'; // Ensure this includes your database connection

$response = array();

// Check if 'sellsId' is provided in the POST request
if (isset($_POST['sellsId'])) {
    $sellsId = $_POST['sellsId'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM sells_user WHERE user_id = ?";
    if ($stmt = $connect->prepare($sql)) {
        $stmt->bind_param("i", $sellsId);

        if ($stmt->execute()) {
            // Successful deletion
            $response['success'] = true;
            $response['messages'] = 'User removed successfully.';
        } else {
            // Failed to execute the query
            $response['success'] = false;
            $response['messages'] = 'Error removing the user. Please try again.';
        }

        $stmt->close();
    } else {
        // Failed to prepare the statement
        $response['success'] = false;
        $response['messages'] = 'Database error. Please try again.';
    }
} else {
    // No user ID provided
    $response['success'] = false;
    $response['messages'] = 'No user ID provided.';
}

$connect->close(); // Close the database connection

// Return the JSON response
echo json_encode($response);
?>
