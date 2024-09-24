<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
    <!-- bootstrap theme-->
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="custom/css/custom.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">
    <!-- file input -->
    <link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">
    <!-- jquery -->
    <script src="assests/jquery/jquery.min.js"></script>
    <!-- jquery ui -->  
    <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
    <script src="assests/jquery-ui/jquery-ui.min.js"></script>
    <!-- bootstrap js -->
    <script src="assests/bootstrap/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #dedede">

<nav class="navbar navbar-static-top" style="background-color: #126fcd">
    <div class="container" style="display: flex; align-items: center;">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header" style="display: flex; align-items: center;">
            <a class="navbar-brand" href="index.php" style="margin-right: 15px;">
                <img src="yoyologo.png" alt="Company Logo" style="height: 40px;">
            </a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="margin-top: 0;">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="flex-grow: 1;">      
            <ul class="nav navbar-nav navbar-right">        
                <li id="navDashboard"><a href="index.php"><i class="glyphicon glyphicon-list-alt"></i> Dashboard</a></li>        
                   
                <li id="navCategories" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                        <i class="glyphicon glyphicon-th-list"></i> Category <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">   
                      <li id="topNavBank"><a href="brand.php"> <i class="glyphicon glyphicon-piggy-bank"></i> brand</a></li>
                        <li id="topNavBank"><a href="bank.php"> <i class="glyphicon glyphicon-piggy-bank"></i> Bank</a></li>
                        <li id="topNavBank"><a href="categories.php"> <i class="glyphicon glyphicon-piggy-bank"></i> product category</a></li>
                         <li id="topNavBank"><a href="  sellsLocation.php"> <i class="glyphicon glyphicon-piggy-bank"></i>   sellsLocation</a></li>
                    </ul>
                </li>
                <li id="navProduct"><a href="product.php"> <i class="glyphicon glyphicon-ruble"></i> Product </a></li>  
                
                   <li id="navProduct"><a href="stock.php"> <i class="glyphicon glyphicon-ruble"></i> Stock </a></li> 
                   
                    <li id="navProduct"><a href="wastage.php"> <i class="glyphicon glyphicon-ruble"></i> Wastage </a></li> 
                    
                   
                <li id="navProduct"><a href="expense.php"> <i class="glyphicon glyphicon-ruble"></i> Expense </a></li>
                <li id="navCredit"><a href="credit.php"> <i class="glyphicon glyphicon-ruble"></i> Credit </a></li>  
                <li class="dropdown" id="navOrder">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="glyphicon glyphicon-shopping-cart"></i> Orders <span class="caret"></span></a>
                    <ul class="dropdown-menu">   
                     <li id="topNavAddOrder"><a href="qr.html"> <i class="glyphicon glyphicon-plus"></i> qr code</a></li>  
                        <li id="topNavAddOrder"><a href="orders.php?o=add"> <i class="glyphicon glyphicon-plus"></i> Add Orders</a></li> 
                        <li id="topNavAddOrder"><a href="request_orders.php"> <i class="glyphicon glyphicon-plus"></i> Add Request Orders</a></li>            
                        <li id="topNavManageOrder"><a href="orders.php?o=manord"> <i class="glyphicon glyphicon-edit"></i> Manage Orders</a></li>            
                    </ul>
                </li> 
                  
                  <li id="navReport"><a href="asset.php"> <i class="glyphicon glyphicon-user"></i> Asset</a></li>
                   
                <li id="navReport"><a href="report.php"> <i class="glyphicon glyphicon-check"></i> Report </a></li>
                <li class="dropdown" id="navSetting">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="glyphicon glyphicon-user"></i> <span class="caret"></span></a>
                    <ul class="dropdown-menu"> 
                         <li id="navReport"><a href="sellsUser.php"> <i class="glyphicon glyphicon-user"></i> Sells User</a></li>
                      <li id="navReport"><a href="employee.php"> <i class="glyphicon glyphicon-user"></i> Employee</a></li>
                    <li id="topNavSetting"><a href="register.php"> <i class="glyphicon glyphicon-plus"></i> registerSells</a></li>
                        <li id="topNavSetting"><a href=" update_admin.php"> <i class="glyphicon glyphicon-wrench"></i> update_account</a></li>            
                        <li id="topNavLogout"><a href="logout.php"> <i class="glyphicon glyphicon-log-out"></i> Logout</a></li>            
                    </ul>
                </li>        
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">
    <!-- Your content here -->
</div>

</body>
</html>
