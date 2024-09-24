<?php 	
require_once 'core.php';

$output = array('data' => array());

$sql = "SELECT user_id, username, phone FROM sells_user";
$result = $connect->query($sql);

// Check if the query was successful
if ($result) {
    if ($result->num_rows > 0) { 
        while ($row = $result->fetch_array()) {
            $user_id = $row['user_id']; // Use the correct field name

        $button = '<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a type="button" data-toggle="modal" data-target="#editSellsModal" onclick="editSells('.$user_id.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
        <li><a type="button" data-toggle="modal" data-target="#removeSellsModal" onclick="removeSells('.$user_id.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
    </ul>
</div>';


            $output['data'][] = array(
                $row['username'], // Username
                $row['phone'],    // Phone number
                $button           // Action buttons
            ); 	
        }
    }
} else {
    // Log the error to a file or display it
    error_log("SQL Error: " . $connect->error);
    $output['error'] = "Error in the SQL query: " . $connect->error;
}

$connect->close();

echo json_encode($output);
?>
