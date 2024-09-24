<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Expense</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Expense</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addExpenseModalBtn" data-target="#addExpenseModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Expense 
                    </button>
                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div> <!-- /div-action -->
 <table class="table" id="manageExpenseTable">
        <thead>
            <tr>
                <th>Expense for</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                <th style="width:15%;">Options</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
            </div> <!-- /panel-body -->
        </div> <!-- /panel -->      
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitExpenseForm" action="php_action/createExpense.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Expense</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-expense-messages"></div>

                    <div class="form-group">
                        <label for="expense_for" class="col-sm-3 control-label">Expense for: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="expense_for" name="expense_for" placeholder="Expense for" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="ex_description" class="col-sm-3 control-label">Description: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="ex_description" name="ex_description" placeholder="Description" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="amount" class="col-sm-3 control-label">Amount: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" autocomplete="off">
                        </div>
                    </div>      
                    
                    <div class="form-group">
                        <label for="expense_date" class="col-sm-3 control-label">Date: </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="expense_date" name="expense_date" autocomplete="off">
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
                <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Expense</h4>
            </div>
            <form id="editExpenseForm" action="php_action/editExpense.php" method="post">
                <div class="modal-body">
                    <!-- Hidden field to store the expense ID -->
                    <input type="hidden" name="expenseId" id="expense_id">

                    <!-- Expense Details Input Fields -->
                    <div class="form-group">
                        <label for="expense_for">Expense For</label>
                        <input type="text" class="form-control" id="expense_for" name="expense_for" placeholder="Enter expense for" required>
                    </div>

                    <div class="form-group">
                        <label for="ex_description">Description</label>
                        <input type="text" class="form-control" id="ex_description" name="ex_description" placeholder="Enter description" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
                    </div>

                    <div class="form-group">
                        <label for="expense_date">Date</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" required>
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
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Expense</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to remove this expense?</p>
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

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportExpenseToCsv.php';  // Direct the button click to the PHP script
    });
</script>

<script src="custom/js/expense.js"></script>

<?php require_once 'includes/footer.php'; ?>