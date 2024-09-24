<?php 
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {    
    // Use appropriate variables matching the fields in your employee table
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $Phone_no = $_POST['Phone_no'];
    $Email = $_POST['Email'];
    $Position = $_POST['Position'];
    $Department = $_POST['Department'];
    $HireDate = $_POST['HireDate'];


    // Insert record into employee table with matching fields
    $sql = "INSERT INTO employee (FirstName, LastName, Phone_no, Email, Position, Department, HireDate) 
            VALUES ('$FirstName', '$LastName', '$Phone_no', '$Email', '$Position', '$Department', '$HireDate')";

    if ($connect->query($sql) === TRUE) {
        $EmployeeID = $connect->insert_id; // Get the last inserted employee ID                  
        $valid['success'] = true;
        $valid['messages'] = "Employee successfully added";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while adding the employee";
    }
} 

$connect->close();

echo json_encode($valid);
?>
