<?php 
require_once 'php_action/db_connect.php';

session_start();

if(isset($_SESSION['userId'])) {
	header('location: https://ethiosuggestion.com/stock/stock/dashboard.php');	
	exit();
}

$errors = array();

if($_POST) {		
	$username = $_POST['username'];
	$password = $_POST['password'];
	$phone = $_POST['phone'];

	// Check if fields are empty
	if(empty($username) || empty($password) || empty($phone)) {
		if($username == "") {
			$errors[] = "Username is required";
		} 

		if($password == "") {
			$errors[] = "Password is required";
		}
		
		if($phone == "") {
			$errors[] = "Phone is required";
		}
	} else {
		// Check if username already exists
		$sql = "SELECT * FROM sells_user WHERE username = '$username'";
		$result = $connect->query($sql);

		if($result->num_rows == 0) {
			// Username doesn't exist, so insert new user
			$password = md5($password); // Hash the password
			$insertSql = "INSERT INTO sells_user (username, password, phone) VALUES ('$username', '$password', '$phone')";
			
			if($connect->query($insertSql) === TRUE) {
				$_SESSION['userId'] = $connect->insert_id; // Get the ID of the newly inserted user
				header('location: https://ethiosuggestion.com/stock/stock/dashboard.php');	
				exit();
			} else {
				$errors[] = "Error: " . $connect->error;
			}
		} else {
			$errors[] = "Username already exists";		
		}
	} 
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register - Stock Management System</title>

	<!-- bootstrap -->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
	<!-- bootstrap theme-->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">

  	<!-- custom css -->
  	<link rel="stylesheet" href="custom/css/custom.css">	

  	<!-- jquery -->
	<script src="assests/jquery/jquery.min.js"></script>
  	<!-- jquery ui -->  
  	<link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
  	<script src="assests/jquery-ui/jquery-ui.min.js"></script>

  	<!-- bootstrap js -->
	<script src="assests/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row vertical">
			<div class="col-md-5 col-md-offset-4">

				<!-- Add logo at the top -->
				<div class="text-center">
					 <img src="yoyologo.png" alt="Company Logo" class="img-responsive" style="width: 100%; max-width: 100%; height: auto;">
				</div>

				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Register</h3>
					</div>
					<div class="panel-body">

						<div class="messages">
							<?php if($errors) {
								foreach ($errors as $key => $value) {
									echo '<div class="alert alert-warning" role="alert">
									<i class="glyphicon glyphicon-exclamation-sign"></i>
									'.$value.'</div>';										
									}
								} ?>
						</div>

						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="registerForm">
							<fieldset>
								<div class="form-group">
									<label for="username" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-10">
									  <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" required />
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-10">
									  <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" required />
									</div>
								</div>	
								<div class="form-group">
									<label for="phone" class="col-sm-2 control-label">Phone</label>
									<div class="col-sm-10">
									  <input type="text" class="form-control"
									  									  id="phone" name="phone" placeholder="Phone" autocomplete="off" required />
									</div>
								</div>								
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
									  <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-log-in"></i> Register</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<!-- panel-body -->
				</div>
				<!-- /panel -->
			</div>
			<!-- /col-md-4 -->
		</div>
		<!-- /row -->
	</div>
	<!-- container -->	
</body>
</html>

