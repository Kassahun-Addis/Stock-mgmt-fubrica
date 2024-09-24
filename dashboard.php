<?php require_once 'php_action/core.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Stock Management System</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="custom/css/custom.css">

<!-- DataTables -->
<link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">

<!-- File input -->
<link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">

<!-- jQuery -->
<script src="assests/jquery/jquery.min.js"></script>
<!-- jQuery UI -->
<link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
<script src="assests/jquery-ui/jquery-ui.min.js"></script>

<!-- Bootstrap JS -->
<script src="assests/bootstrap/js/bootstrap.min.js"></script>

</head>
<body style="background-color: #dedede">

<nav class="navbar navbar-static-top" style="background-color: #126fcd">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

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
                        <li id="topNavManageOrder"><a href="orders.php?o=manord"> <i class="glyphicon glyphicon-edit"></i> Manage Orders</a></li>            
						<li id="topNavManageOrder"><a href="request_orders.php?o=manord"> <i class="glyphicon glyphicon-edit"></i> Add Requests</a></li>            
					</ul>
                </li> 
                  
                  <li id="navReport"><a href="asset.php"> <i class="glyphicon glyphicon-user"></i> Asset</a></li>
				  <li id="navReport"><a href="shipment.php"> <i class="glyphicon glyphicon-user"></i> Shipment</a></li>

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
	<?php 
    $sql = "SELECT * FROM raw_materials";  // Modify based on your logic for active materials if needed
 $query = $connect->query($sql);
$countProduct = $query->num_rows;

 $sql = "SELECT * FROM finished_good";
  // Modify based on your logic for active materials if needed
 $query = $connect->query($sql);
 $finished_good = $query->num_rows;


	$date30DaysAgo = date('Y-m-d', strtotime('-10 days'));

	$orderSql = "SELECT order_date, SUM(total_amount) AS total_amount_sum 
	             FROM orders 
	             WHERE order_date >= '$date30DaysAgo' 
	               AND order_status = 1 
	             GROUP BY order_date";
	$orderQuery = $connect->query($orderSql);

	$totalRevenue = 0;
	$orderDates = [];
	$totalAmounts = [];

	while ($orderResult = $orderQuery->fetch_assoc()) {
	    $orderDates[] = $orderResult['order_date'];
	    $totalAmounts[] = (float)$orderResult['total_amount_sum'];
	    $totalRevenue += (float)$orderResult['total_amount_sum'];
	}

	$profitSql = "SELECT * FROM orders";
	$profitQuery = $connect->query($profitSql);

	$totalProfit = 0;
	while ($profitResult = $profitQuery->fetch_assoc()) {
	    $x = "SELECT * FROM order_item WHERE order_id = '".$profitResult['order_id']."'";
	    $y = $connect->query($x);

	    $purchaseTotal = 0;
	    while ($z = $y->fetch_assoc()) { 
	        $purchaseTotal +=  ((float)$z['purchase'] * (int)$z['quantity']);
	    }

	    $totalProfit += ((float)$profitResult['paid'] - (float)$purchaseTotal);
	}

 $lowProductSql = "SELECT * FROM finished_good WHERE quantity < alert_quantity";
    $lowProductQuery = $connect->query($lowProductSql);
    $countLowProduct = $lowProductQuery->num_rows;



 $lowStockSql = "SELECT * FROM raw_materials WHERE purchase_quantity < alert_quantity";
    $lowStockQuery = $connect->query($lowStockSql);
    $countLowStock = $lowStockQuery->num_rows;
	$connect->close();
	?>



	<div class="row mt-4">
		<div class="col-md-3">
			<div class="panel panel-primary text-center">
				<div class="panel-heading">
					Total raw materials
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo $countProduct; ?></h5>
					<p class="panel-text">currently available raw matterials.</p>
					<a href="stock.php" class="btn btn-primary">View available materials</a>
				</div>
			</div>
		</div>
		
			<div class="col-md-3">
			<div class="panel panel-primary text-center">
				<div class="panel-heading">
					Total Product
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo  $finished_good ; ?></h5>
					<p class="panel-text">currently available Products.</p>
					<a href="product.php" class="btn btn-primary">View Products</a>
				</div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="panel panel-info text-center">
				<div class="panel-heading">
					Total Orders
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo $orderQuery->num_rows; ?></h5>
					<p class="panel-text">Total orders received.</p>
					<a href="orders.php?o=manord" class="btn btn-info">View Orders</a>
				</div>
			</div>
		</div>
	
			<div class="col-md-3">
			<div class="panel panel-danger text-center">
				<div class="panel-heading">
					Low Product
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo $countLowProduct; ?></h5>
					<p class="panel-text">Products running low on stock.</p>
					<a href="stock.php?low=true" class="btn btn-danger">View Low Quantity Product</a>
				</div>
			</div>
			
		</div>
		
		
			<div class="col-md-3">
			<div class="panel panel-danger text-center">
				<div class="panel-heading">
					Low Matterial
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo $countLowStock; ?></h5>
					<p class="panel-text">Raw material running low on stock.</p>
					<a href="stock.php?low=true" class="btn btn-danger">View Low Quantity material</a>
				</div>
			</div>
			
		</div>
		
		<div class="col-md-3">
			<div class="panel panel-success text-center">
				<div class="panel-heading">
					Date
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><?php echo date('d'); ?></h5>
					<p class="panel-text"><?php echo date('l') .' '.date('d').', '.date('Y'); ?></p>
				</div>
			</div>
		</div>
			<div class="col-md-3">
			<div class="panel panel-default text-center">
				<div class="panel-heading">
					Total Revenue
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><i class="glyphicon glyphicon-usd"></i> <?php echo number_format($totalRevenue, 2); ?></h5>
					<p class="panel-text">Total revenue generated from orders.</p>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="panel panel-default text-center">
				<div class="panel-heading">
					Total Profit
				</div>
				<div class="panel-body">
					<h5 class="panel-title"><i class="glyphicon glyphicon-usd"></i> <?php echo number_format($totalProfit, 2); ?></h5>
					<p class="panel-text">Total profit after deducting costs.</p>
				</div>
			</div>
		</div>
		</div>
	</div>
		
	

	
	<div class="row mt-4">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					Sales Report (Last 30 Days)
				</div>
				<div class="panel-body">
					<canvas id="salesChart" height="150"></canvas>
				</div>
			</div>
		</div>
		
		 <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <img src="right.jpg" alt="Company Logo" class="img-responsive">
                </div>
            </div>
        </div>
	</div>

</div> <!-- container -->

<!-- Include Chart.js version 2.9.4 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<!-- Include Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Include the correct version of Chart.js adapter for Moment.js -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@0.1.2/dist/chartjs-adapter-moment.min.js"></script>

<script>
var ctx = document.getElementById('salesChart').getContext('2d');
var salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($orderDates); ?>,
        datasets: [{
            label: 'Total Amount',
            data: <?php echo json_encode($totalAmounts); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            xAxes: [{
                type: 'time',
                time: {
                    unit: 'day',
                    displayFormats: {
                        day: 'MMM D'
                    }
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

</body>
</html>
