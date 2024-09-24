<?php
require_once 'db_connect.php';

if (isset($_POST['material_id'])) {
    $material_id = $_POST['material_id'];

    // Query to fetch material data by ID
    $sql = "SELECT * FROM raw_materials WHERE material_id = $material_id";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();

    // Return the fetched data as JSON
    echo json_encode($row);
}
?>
