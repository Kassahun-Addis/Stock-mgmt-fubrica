<?php
require_once 'db_connect.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {    
    // Retrieve POST data
    $EmployeeID = $_POST['EmployeeID'];
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $Phone_no = $_POST['Phone_no'];
    $Email = $_POST['Email'];
    $Position = $_POST['Position'];
    $Department = $_POST['Department'];
    $HireDate = $_POST['HireDate'];

    // Debugging output
    error_log('Received POST data: ' . print_r($_POST, true));

    // SQL update statement
    $sql = "UPDATE employee SET FirstName = ?, LastName = ?, Phone_no = ?, Email = ?, Position = ?, Department = ?, HireDate = ? WHERE EmployeeID = ?";
    
    // Prepare statement
    $stmt = $connect->prepare($sql);
    
    // Check if prepare was successful
    if ($stmt === false) {
        error_log('Prepare failed: ' . $connect->error);
        echo json_encode(['success' => false, 'message' => 'SQL Prepare Error']);
        exit;
    }

    // Bind parameters
    $stmt->bind_param('ssissssi', 
        $FirstName, $LastName, $Phone_no, $Email, $Position, $Department, $HireDate, $EmployeeID);

    // Execute statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'messages' => 'Employee updated successfully.']);
    } else {
        error_log('Execute failed: ' . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error updating employee: ' . $stmt->error]);
    }

    $stmt->close();
    $connect->close();
}
?>
