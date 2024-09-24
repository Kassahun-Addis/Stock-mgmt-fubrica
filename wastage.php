<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Wastage</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Wastage</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull-right" style="padding-bottom:20px;">
                   <button class="btn btn-default button1" data-toggle="modal" id="addWastageModalBtn" data-target="#addWastageModal"> 
    <i class="glyphicon glyphicon-plus-sign"></i> Add Wastage product
</button>

                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div> <!-- /div-action -->
                <table class="table" id="manageWastageTable">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Wastage Date</th>
            <th>Reason</th>
            <th style="width:15%;">Options</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!-- Add Wastage Modal -->
<div class="modal fade" id="addWastageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitWastageForm" action="php_action/createWastage.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Wastage</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-wastage-messages"></div>

                    <div class="form-group">
                        <label for="product_name" class="col-sm-3 control-label">Product Name: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label">Quantity: </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="unit" class="col-sm-3 control-label">Unit: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="unit" name="unit" placeholder="Unit (e.g., kg, liter)" autocomplete="off">
                        </div>
                    </div>      

                    <div class="form-group">
                        <label for="wastage_date" class="col-sm-3 control-label">Wastage Date: </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="wastage_date" name="wastage_date" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason" class="col-sm-3 control-label">Reason: </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="reason" name="reason" placeholder="Reason for wastage" rows="3"></textarea>
                        </div>
                    </div>
                </div> <!-- /modal-body -->
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="createWastageBtn"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                    </button>
                </div>      
            </form>
        </div>
    </div>
</div> 
<!-- /Add Wastage Modal -->
<!-- Edit Wastage Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editWastageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Wastage</h4>
            </div>
             <div id="edit-wastage-messages"></div>
            <form id="editWastageForm" action="php_action/editWastage.php" method="post">
                 
                <div class="modal-body">
                    <!-- Hidden field to store the wastage ID -->
                    <input type="hidden" name="wastageId" id="wastage_id">

                    <!-- Wastage Details Input Fields -->
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" placeholder="Enter unit (e.g., kg, liter)" required>
                    </div>

                    <div class="form-group">
                        <label for="wastage_date">Wastage Date</label>
                        <input type="date" class="form-control" id="wastage_date" name="wastage_date" required>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" placeholder="Enter reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                    <button type="submit" class="btn btn-primary" id="editWastageBtn"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Wastage Modal -->
<!-- Delete Wastage Modal -->
<div class="modal fade" id="deleteWastageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Delete Wastage</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this wastage record?</p>
                <p id="delete-wastage-info"></p> <!-- To display the record information -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> 
                    <i class="glyphicon glyphicon-remove-sign"></i> Close
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"> 
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Wastage Modal -->

<script>
   document.getElementById('exportCsvBtn').addEventListener('click', function() {
    window.location.href = 'php_action/exportWastageToCsv.php';
});

</script>
<script src="custom/js/wastage.js"></script>

<?php require_once 'includes/footer.php'; ?>
