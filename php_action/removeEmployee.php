<?php
require_once 'core.php'; // Ensure this file includes the database connection

if (isset($_POST['EmployeeID'])) {
    $EmployeeID = $_POST['EmployeeID']; // Get the EmployeeID from the AJAX request

    // SQL query to delete the employee
    $sql = "DELETE FROM employee WHERE EmployeeID = ?";
    $stmt = $connect->prepare($sql);

    if ($stmt) {
        // Bind the EmployeeID parameter and execute the statement
        $stmt->bind_param("i", $EmployeeID);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'messages' => 'Employee successfully deleted']);
        } else {
            echo json_encode(['success' => false, 'messages' => 'Error deleting employee']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'messages' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['success' => false, 'messages' => 'Invalid Employee ID']);
}

$connect->close(); // Close the database connection
?>
