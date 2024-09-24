<?php 
require_once 'php_action/db_connect.php';

session_start();

if(!isset($_SESSION['userId'])) {
    header('location: login.php');
    exit();
}

$errors = array();
$success = '';

if($_POST) {		
    $userId = $_SESSION['userId'];
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];

    if(empty($newUsername) || empty($newPassword)) {
        if($newUsername == "") {
            $errors[] = "Username is required";
        } 

        if($newPassword == "") {
            $errors[] = "Password is required";
        }
    } else {
        // Hash the new password using md5
        $hashedPassword = md5($newPassword);

        // Update the username and password in the database
        $sql = "UPDATE users SET username = '$newUsername', password = '$hashedPassword' WHERE user_id = '$userId'";
        if($connect->query($sql) === TRUE) {
            $success = "Account updated successfully!";
            header('location: https://ethiosuggestion.com/stock/stock/dashboard.php');	
            exit(); // Ensure no further code is executed after redirection
        } else {
            $errors[] = "Error updating account: " . $connect->error;
        }
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Account</title>
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
                        <h3 class="panel-title">Update Account</h3>
                    </div>
                    <div class="panel-body">

                        <!-- Display success message -->
                        <?php if($success) {
                            echo '<div class="alert alert-success" role="alert">
                            <i class="glyphicon glyphicon-ok-sign"></i>
                            '.$success.'</div>';
                        } ?>

                        <!-- Display error messages -->
                        <div class="messages">
                            <?php if($errors) {
                                foreach ($errors as $key => $value) {
                                    echo '<div class="alert alert-warning" role="alert">
                                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                                    '.$value.'</div>';										
                                }
                            } ?>
                        </div>

                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="updateForm">
                            <fieldset>
                              <div class="form-group">
                                    <label for="newUsername" class="col-sm-4 control-label">New Username</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" id="newUsername" name="newUsername" placeholder="New Username" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword" class="col-sm-4 control-label">New Password</label>
                                    <div class="col-sm-8">
                                      <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password" autocomplete="off" />
                                    </div>
                                </div>								
                                <div class="form-group">
                                    <div class="col-sm-offset-4 col-sm-8">
                                      <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-save"></i> Update</button>
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
