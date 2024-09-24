<?php

require_once 'core.php';

$response = array('success' => false, 'messages' => array());

if ($_POST) {
    $username = $_POST['sellsName'];
    $password = $_POST['password'];
    $phone = $_POST['phoneNumber'];

    // Validation checks
    if (empty($username)) {
        $response['messages']['sellsName'] = "Username is required";
    }
    if (empty($password)) {
        $response['messages']['password'] = "Password is required";
    }
    if (empty($phone)) {
        $response['messages']['phoneNumber'] = "Phone number is required";
    }

    if (empty($response['messages'])) {
        // Check if username already exists
        $sql = "SELECT * FROM sells_user WHERE username = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Hash the password
            $hashedPassword = md5($password);

            // Insert new user into the database
            $insertSql = "INSERT INTO sells_user (username, password, phone) VALUES (?, ?, ?)";
            $stmt = $connect->prepare($insertSql);
            $stmt->bind_param('sss', $username, $hashedPassword, $phone);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['messages'] = "Sells user added successfully.";
            } else {
                $response['messages'] = "Error: " . $stmt->error;
            }
        } else {
            $response['messages']['sellsName'] = "Username already exists";
        }
    }
}

$connect->close(); // Close the database connection

// Return the JSON response
echo json_encode($response);
?>
