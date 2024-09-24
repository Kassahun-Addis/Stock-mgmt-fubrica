<?php
require_once 'db_connect.php';

if($_POST) {
  $creditId = $_POST['creditId'];

  $sql = "SELECT supplier FROM credit WHERE credit_id = ?";
  $stmt = $connect->prepare($sql);
  $stmt->bind_param('i', $creditId);

  if($stmt->execute()) {
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      echo json_encode(['success' => true, 'supplier' => $row['supplier']]);
    } else {
      echo json_encode(['success' => false, 'messages' => 'Supplier not found.']);
    }
  } else {
    echo json_encode(['success' => false, 'messages' => 'Error executing query.']);
  }
}
?>
