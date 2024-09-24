<?php 	

require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());


// Get the product ID from the POST request


// Check if the productId is provided
if (isset($_POST['productId'])) {
    $productId = intval($_POST['productId']);

    // Prepare the SQL query to delete the product
    $sql = "DELETE FROM finished_good WHERE product_id = ?";

    // Initialize the response array
    $response = array();

    try {
        // Prepare the statement
        if ($stmt = $connect->prepare($sql)) {
            // Bind the parameter
            $stmt->bind_param("i", $productId);

            // Execute the statement
            if ($stmt->execute()) {
                // Check if any row was affected
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['messages'] = "Product successfully removed.";
                } else {
                    $response['success'] = false;
                    $response['messages'] = "Product not found or already removed.";
                }
            } else {
                $response['success'] = false;
                $response['messages'] = "Error executing query: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $response['success'] = false;
            $response['messages'] = "Error preparing statement: " . $connect->error;
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['messages'] = "An error occurred: " . $e->getMessage();
    }

    // Close the database connection
    $connect->close();

    // Return the response as JSON
    echo json_encode($response);
} else {
    $response = array(
        'success' => false,
        'messages' => 'No product ID provided.'
    );

    // Return the response as JSON
    echo json_encode($response);
}
?>
