<?php 	
require_once 'core.php';

$sql = "SELECT id, bank_name FROM Bank";
$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 
	while($row = $result->fetch_array()) {
		$id = $row['id'];

		// Action buttons (Edit, Remove)
		$button = '<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Action <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a type="button" data-toggle="modal" data-target="#editBankModal" onclick="editBank('.$id.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
				<li><a type="button" data-toggle="modal" data-target="#removeBankModal" onclick="removeBank('.$id.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
			</ul>
		</div>';

		$output['data'][] = array(
			'bank_name' => $row['bank_name'], // Bank name
			'options' => $button // Action buttons
		); 	
	}
}

$connect->close();

echo json_encode($output);
