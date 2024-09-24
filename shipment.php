<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Shipment</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Shipment</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addExpenseModalBtn" data-target="#addExpenseModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Shipment 
                    </button>
                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div> <!-- /div-action -->
 <table class="table" id="manageExpenseTable">
        <thead>
            <tr>
            <th>Assigned Person</th>
            <th>Shipment Date</th>							
            <th>Carrier</th>
            <th>Tracking No</th>
            <th>Shipping Address</th>
            <th>Shipping Cost</th>
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

<!-- Add Shipment Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitExpenseForm" action="php_action/createShipment.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Shipment</h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-expense-messages"></div>

                    <div class="form-group">
                        <label for="assigned_person" class="col-sm-3 control-label">Assigned Person: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="assigned_person" name="assigned_person" placeholder="Enter Assigned Person" autocomplete="off">
                        </div>
                    </div>    

                    <div class="form-group">
                        <label for="shipment_date" class="col-sm-3 control-label">Shipment Date: </label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="shipment_date" name="shipment_date" placeholder="Enter Shipment Date" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group">
                        <label for="carrier" class="col-sm-3 control-label">Carrier: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="carrier" name="carrier" placeholder="Enter carrier" autocomplete="off">
                        </div>
                    </div> 
                    
                    <div class="form-group">
                        <label for="tracking_number" class="col-sm-3 control-label">Tracking No: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Enter Tracking No" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="shipping_address" class="col-sm-3 control-label">Shipping Address: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address" placeholder="Enter shipping address" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="shipping_cost" class="col-sm-3 control-label">Shipping Cost: </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" placeholder="Enter Shipping Cost" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">Status: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="status" name="status" placeholder="Enter status" autocomplete="off">
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
<!-- /Add Shipment Modal -->


<!-- Edit Shipment Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Shipment</h4>
            </div>
            <form id="editExpenseForm" action="php_action/editShipment.php" method="post">
                <div class="modal-body">
                    <!-- Hidden field to store the shipment_id -->
                    <input type="hidden" name="shipment_id" id="shipment_id"> <!-- Changed name to shipment_id -->

                    <!-- Expense Details Input Fields -->
                    <div class="form-group">
                        <label for="assigned_person">Assigned Person</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="assigned_person" name="assigned_person" placeholder="Enter Assigned Person" required> <!-- Changed name to ItemName -->
                    </div>

                    <div class="form-group">
                        <label for="shipment_date">Shipment Date</label> <!-- Changed label to match PHP field -->
                        <input type="date" class="form-control" id="shipment_date" name="shipment_date" placeholder="Enter shipment date" required> <!-- Changed name to RequestedBy -->
                    </div>

                    <div class="form-group">
                        <label for="carrier">Carrier</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="carrier" name="carrier" placeholder="Enter Carrier" required> <!-- Changed name to IssuedBy -->
                    </div>

                    <div class="form-group">
                        <label for="tracking_number">Tracking Number</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Enter tracking number" required> <!-- Changed name to ApprovedBy -->
                    </div>

                    <div class="form-group">
                        <label for="shipping_address">Shipping Address</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="shipping_address" name="shipping_address" placeholder="Enter shipping address" required> <!-- Changed name to Quantity -->
                    </div>

                    <div class="form-group">
                        <label for="shipping_cost">Shipping Cost</label> <!-- Changed label to match PHP field -->
                        <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" placeholder="Enter shipping cost" required> <!-- Changed name to Unit -->
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label> <!-- Changed label to match PHP field -->
                        <input type="text" class="form-control" id="status" name="status" placeholder="Enter status" step="0.01" required> <!-- Changed name to UnitPrice -->
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



<!-- Remove Shipment Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Shipment</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to remove this shipment?</p>
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
<!-- /Remove Shipment Modal -->


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportShipmentToCsv.php';  // Direct the button click to the PHP script
    });
</script>

<script src="custom/js/shipment.js"></script>

<?php require_once 'includes/footer.php'; ?>