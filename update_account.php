<?php
require_once 'core.php'; // Ensure this includes your database connection

$response = array();

// Check if required POST data is available
if (isset($_POST['sellsId']) && isset($_POST['editSellsName']) && isset($_POST['editPassword']) && isset($_POST['editPhoneNumber'])) {
    $sellsId = $_POST['sellsId'];
    $sellsName = $_POST['editSellsName'];
    $password = md5($_POST['editPassword']); // Hash the password using md5
    $phoneNumber = $_POST['editPhoneNumber'];

    // Prepare and execute the update query
    $sql = "UPDATE sells_user SET username = ?, password = ?, phone = ? WHERE user_id = ?";
    if ($stmt = $connect->prepare($sql)) {
        $stmt->bind_param("sssi", $sellsName, $password, $phoneNumber, $sellsId);

        if ($stmt->execute()) {
            // Successful update
            $response['success'] = true;
            $response['messages'] = 'Sells user updated successfully.';
        } else {
            // Failed to execute the query
            $response['success'] = false;
            $response['messages'] = 'Error updating the sells user. Please try again.';
        }

        $stmt->close();
    } else {
        // Failed to prepare the statement
        $response['success'] = false;
        $response['messages'] = 'Database error. Please try again.';
    }
} else {
    // Missing required POST data
    $response['success'] = false;
    $response['messages'] = 'Required data not provided.';
}

$connect->close(); // Close the database connection

// Return the JSON response
echo json_encode($response);
?>
