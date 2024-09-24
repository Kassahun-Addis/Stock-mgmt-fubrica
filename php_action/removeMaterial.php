<?php
require_once 'db_connect.php';

// Initialize response array
$response = array('success' => false, 'messages' => 'Error occurred.');

// Check if material_id is set
if (isset($_POST['material_id'])) {
    // Sanitize the material_id
    $material_id = intval($_POST['material_id']);
    
    // Prepare the SQL statement
    $sql = "DELETE FROM raw_materials WHERE material_id = ?";
    
    // Initialize prepared statement
    if ($stmt = $connect->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("i", $material_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['messages'] = 'Material deleted successfully.';
            } else {
                $response['messages'] = 'No material found with the provided ID.';
            }
        } else {
            $response['messages'] = 'Error executing the query.';
        }
        
        // Close the statement
        $stmt->close();
    } else {
        $response['messages'] = 'Error preparing the statement.';
    }
} else {
    $response['messages'] = 'Material ID not provided.';
}

// Close the database connection
$connect->close();

// Return the JSON response
echo json_encode($response);
?>
