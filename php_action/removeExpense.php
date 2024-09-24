<?php
// Database connection
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expenseId = $_POST['expenseId'];

    $response = array();

    try {
        $sql = "DELETE FROM expense WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $expenseId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['messages'] = "Expense removed successfully.";
        } else {
            throw new Exception("Error removing expense: " . $stmt->error);
        }
    } catch (Exception $e) {
        $response['success'] = false;
        $response['messages'] = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
