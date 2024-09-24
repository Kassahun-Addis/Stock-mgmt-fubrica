<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Employee</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Employee</div>
            </div>
            <div class="panel-body">
                <div class="remove-messages"></div>
                <div class="div-action pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addExpenseModalBtn" data-target="#addExpenseModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Employee 
                    </button>
                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div>

                <table class="table" id="manageExpenseTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone No</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Hire Date</th>
                            <th style="width:15%;">Options</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitExpenseForm" action="php_action/createEmployee.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Employee</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-expense-messages"></div>

                    <div class="form-group">
                        <label for="FirstName" class="col-sm-3 control-label">First Name: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="LastName" class="col-sm-3 control-label">Last Name: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="Phone_no" class="col-sm-3 control-label">Phone No: </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="Phone_no" name="Phone_no" placeholder="Phone No" autocomplete="off">
                        </div>
                    </div> 
                    
                    <div class="form-group">
                        <label for="Email" class="col-sm-3 control-label">Email: </label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="Email" name="Email" placeholder="Email" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="Position" class="col-sm-3 control-label">Position: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Position" name="Position" placeholder="Position" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="Department" class="col-sm-3 control-label">Department: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="Department" name="Department" placeholder="Department" autocomplete="off">
                        </div>
                    </div> 
                    
                    <div class="form-group">
                        <label for="HireDate" class="col-sm-3 control-label">Hire Date: </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="HireDate" name="HireDate" autocomplete="off">
                        </div>
                    </div>

</div> <!-- /modal-body -->
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="createExpenseBtn"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                    </button>
                </div>      
            </form>
        </div>
    </div>
</div> 
<!-- /Add Expense Modal -->


<!-- Edit Expense Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Employee</h4>
            </div>
            <form id="editExpenseForm" action="php_action/editEmployee.php" method="post">
                <div class="modal-body">
                    <!-- Hidden field to store the item ID -->
                    <input type="hidden" name="EmployeeID" id="EmployeeID"> <!-- Changed name to EmployeeID -->

                    <!-- Expense Details Input Fields -->
                    <div class="form-group">
                        <label for="FirstName">First Name</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="Enter first name" required> <!-- Changed name to ItemName -->
                    </div>

                    <div class="form-group">
                        <label for="LastName">Last Name</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Enter last name" required> <!-- Changed name to RequestedBy -->
                    </div>

                    <div class="form-group">
                        <label for="Phone_no">Phone No</label> <!-- Changed label to match PHP field -->
                        <input type="number" class="form-control" id="Phone_no" name="Phone_no" placeholder="Enter phone number" required> <!-- Changed name to IssuedBy -->
                    </div>

                    <div class="form-group">
                        <label for="Email">Email</label> <!-- Changed label to match PHP field -->
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Enter email" required> <!-- Changed name to ApprovedBy -->
                    </div>

                    <div class="form-group">
                        <label for="Position">Position</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="Position" name="Position" placeholder="Enter position" required> <!-- Changed name to Quantity -->
                    </div>

                    <div class="form-group">
                        <label for="Department">Department</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="Department" name="Department" placeholder="Enter department" required> <!-- Changed name to Unit -->
                    </div>

                    <div class="form-group">
                        <label for="HireDate">Hire Date</label> <!-- Changed label to match PHP field -->
                        <input type="date" class="form-control" id="HireDate" name="HireDate" required> <!-- Changed name to OrderDate -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                    <button type="submit" class="btn btn-primary" id="editExpenseBtn"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Expense Modal -->





<!-- Remove Expense Modal -->
<!-- Remove Expense Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Employee</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to remove this employee order?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove-sign"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="removeExpenseBtn">
                    <i class="glyphicon glyphicon-ok-sign"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /Remove Expense Modal -->

<!-- /Remove Expense Modal -->


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportEmployeeToCsv.php';  // Direct the button click to the PHP script
    });
</script>

<script src="custom/js/employee.js"></script>

<?php require_once 'includes/footer.php'; ?>