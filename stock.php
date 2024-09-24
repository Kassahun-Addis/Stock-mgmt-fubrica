<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Raw Material</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Material</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addMaterialModalBtn" data-target="#addMaterialModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Material 
                    </button>
                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div> <!-- /div-action -->

                <table class="table" id="manageMaterialTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Purchase Quantity</th>
                            <th>Unit</th> <!-- Added Unit Field -->
                            <th>Purchase Price</th>
                            <th>Purchase Date</th>
                            <th>Purchase By</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th style="width:15%;">Options</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div> <!-- /panel-body -->
        </div> <!-- /panel -->      
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->

<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitMaterialForm" action="php_action/createMaterial.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Material</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-material-messages"></div>

                    <div class="form-group">
                        <label for="item_name" class="col-sm-3 control-label">Item Name: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="purchase_quantity" class="col-sm-3 control-label">Purchase Quantity: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="purchase_quantity" name="purchase_quantity" placeholder="Purchase Quantity" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="unit" class="col-sm-3 control-label">Unit: </label> <!-- Unit Field -->
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="unit" name="unit" placeholder="Unit (e.g., kg, liters)" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alert_quantity" class="col-sm-3 control-label">Alert Quantity: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="alert_quantity" name="alert_quantity" placeholder="Alert Quantity" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="purchase_price" class="col-sm-3 control-label">Purchase Price: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="purchase_price" name="purchase_price" placeholder="Purchase Price" autocomplete="off">
                        </div>
                    </div>  
                    
                     <div class="form-group">
            <label for="paidAmount" class="col-sm-3 control-label">Paid Amount: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="paidAmount" placeholder="Paid Amount" name="paidAmount" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

        <div class="form-group">
            <label for="transactionNumber" class="col-sm-3 control-label">Transaction Number: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="transactionNumber" placeholder="Transaction Number" name="transactionNumber" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

    
        <!-- New field for TIN Number -->
        <div class="form-group">
            <label for="tinNumber" class="col-sm-3 control-label">TIN Number: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="tinNumber" placeholder="TIN Number" name="tinNumber" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

                    <div class="form-group">
                        <label for="purchased_by" class="col-sm-3 control-label">Purchased By: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="purchased_by" name="purchased_by" placeholder="Purchased By" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="supplier" class="col-sm-3 control-label">Supplier: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Supplier" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="purchase_date" class="col-sm-3 control-label">Purchase Date: </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" autocomplete="off">
                        </div>
                    </div>

                </div> <!-- /modal-body -->
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="createMaterialBtn"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                    </button>
                </div>      
            </form>
        </div>
    </div>
</div> 
<!-- /Add Material Modal -->
<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="editMaterialForm" action="php_action/updateMaterial.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Material</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="edit-material-messages"></div>

                    <!-- Hidden input to store material ID -->
                    <input type="hidden" id="material_id" name="material_id">

                    <!-- Item Name -->
                    <div class="form-group">
                        <label for="editItemName" class="col-sm-3 control-label">Item Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editItemName" name="item_name" placeholder="Item Name" autocomplete="off">
                        </div>
                    </div>

                    <!-- Purchase Quantity -->
                    <div class="form-group">
                        <label for="editPurchaseQuantity" class="col-sm-3 control-label">Purchase Quantity</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editPurchaseQuantity" name="purchase_quantity" placeholder="Purchase Quantity" autocomplete="off">
                        </div>
                    </div>

                    <!-- Unit -->
                    <div class="form-group">
                        <label for="editUnit" class="col-sm-3 control-label">Unit</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editUnit" name="unit" placeholder="Unit (e.g., kg, liters)" autocomplete="off">
                        </div>
                    </div>

                    <!-- Alert Quantity -->
                    <div class="form-group">
                        <label for="editAlertQuantity" class="col-sm-3 control-label">Alert Quantity</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editAlertQuantity" name="alert_quantity" placeholder="Alert Quantity" autocomplete="off">
                        </div>
                    </div>

                    <!-- Purchase Price -->
                    <div class="form-group">
                        <label for="editPurchasePrice" class="col-sm-3 control-label">Purchase Price</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editPurchasePrice" name="purchase_price" placeholder="Purchase Price" autocomplete="off">
                        </div>
                    </div>

                    <!-- Paid Amount -->
                    <div class="form-group">
                        <label for="editPaidAmount" class="col-sm-3 control-label">Paid Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editPaidAmount" name="paidAmount" placeholder="Paid Amount" autocomplete="off">
                        </div>
                    </div>

                    <!-- Transaction Number -->
                    <div class="form-group">
                        <label for="editTransactionNumber" class="col-sm-3 control-label">Transaction Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editTransactionNumber" name="transactionNumber" placeholder="Transaction Number" autocomplete="off">
                        </div>
                    </div>

                    <!-- TIN Number -->
                    <div class="form-group">
                        <label for="editTinNumber" class="col-sm-3 control-label">TIN Number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editTinNumber" name="tinNumber" placeholder="TIN Number" autocomplete="off">
                        </div>
                    </div>

                    <!-- Purchased By -->
                    <div class="form-group">
                        <label for="editPurchasedBy" class="col-sm-3 control-label">Purchased By</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editPurchasedBy" name="purchased_by" placeholder="Purchased By" autocomplete="off">
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="form-group">
                        <label for="editSupplier" class="col-sm-3 control-label">Supplier</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editSupplier" name="supplier" placeholder="Supplier" autocomplete="off">
                        </div>
                    </div>

                    <!-- Purchase Date -->
                    <div class="form-group">
                        <label for="editPurchaseDate" class="col-sm-3 control-label">Purchase Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="editPurchaseDate" name="purchase_date" autocomplete="off">
                        </div>
                    </div>

                </div> <!-- /modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> 
                        <i class="glyphicon glyphicon-remove-sign"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary"> 
                        <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                    </button>
                </div>      
            </form>
        </div>
    </div>
</div>
<!-- /Edit Material Modal -->



<!-- Remove Material Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeMaterialModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Material</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to remove this material?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"> 
                    <i class="glyphicon glyphicon-remove-sign"></i> Close
                </button>
                <button type="button" class="btn btn-danger" id="removeMaterialBtn"> 
                    <i class="glyphicon glyphicon-ok-sign"></i> Remove
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportMaterialToCsv.php';  // Direct the button click to the PHP script
    });
</script>

<script src="custom/js/material.js"></script>

<?php require_once 'includes/footer.php'; ?>
