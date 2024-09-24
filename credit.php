<?php require_once 'php_action/db_connect.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>		  
            <li class="active">Credit</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-credit-card"></i> Manage Credit</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addCreditModalBtn" data-target="#addCreditModal"> <i class="glyphicon glyphicon-plus-sign"></i>pay Credit </button>
                </div> <!-- /div-action -->				
				
                <table class="table" id="manageCreditTable">
                    <thead>
                        <tr>
                            <th>Credit_id</th>
                          
                            <th>product Name</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Purchase</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Transaction Number</th>
                            <th>Date</th>
                            <th style="width:15%;">Options</th>
                        </tr>
                    </thead>
                </table>
                <!-- /table -->
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading"> <i class="glyphicon glyphicon-stats"></i> Total Credit</div>
                    </div> <!-- /panel-heading -->
                    <div class="panel-body">
                        <?php
                            // Query to calculate the asset value
                            $sql = "SELECT SUM(remaining_due_amount) AS total_asset_value FROM credit WHERE status = 'active'";
                            $result = $connect->query($sql);
                            $row = $result->fetch_assoc();
                            $total_asset_value = $row['total_asset_value'];
                        ?>
                        <p><strong>Total Credit Value:</strong> <?php echo number_format($total_asset_value, 2); ?> </p>
                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
                <!-- End Asset Value Calculation Panel -->
                
            </div> <!-- /panel-body -->
        </div> <!-- /panel -->		
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->


<!-- Pay Credit Modal -->
<div class="modal fade" id="addCreditModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" id="submitCreditForm" action="php_action/creditPayment.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-plus"></i> Pay Credit</h4>
        </div>
        <div class="modal-body" style="max-height:450px; overflow:auto;">
          <div id="add-credit-messages"></div>
          <div class="form-group">
            <label for="creditId" class="col-sm-3 control-label">Credit ID: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="creditId" placeholder="Credit ID" name="creditId" autocomplete="off">
            </div>
          </div> 
          <div class="form-group">
            <label for="supplier" class="col-sm-3 control-label">Supplier: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="supplier" placeholder="Supplier" name="supplier" autocomplete="off" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="paidAmount" class="col-sm-3 control-label">Paid Amount: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="paidAmount" placeholder="Paid Amount" name="paidAmount" autocomplete="off">
            </div>
          </div> 
          <div class="form-group">
            <label for="transactionNumber" class="col-sm-3 control-label">Transaction Number: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="transactionNumber" placeholder="Transaction Number" name="transactionNumber" autocomplete="off">
            </div>
          </div> 
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="glyphicon glyphicon-remove-sign"></i> Close
          </button>
          <button type="submit" class="btn btn-primary" id="createCreditBtn" data-loading-text="Loading..." autocomplete="off">
            <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- edit credit -->
<div class="modal fade" id="editCreditModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" id="editCreditForm" action="php_action/editCredit.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Credit</h4>
        </div>
        <div class="modal-body" style="max-height:450px; overflow:auto;">
          <div id="edit-credit-messages"></div>
          
          <div class="form-group">
            <label for="editSupplier" class="col-sm-3 control-label">Supplier: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editSupplier" name="editSupplier" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editProductName" class="col-sm-3 control-label">Product Name: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editProductName" name="editProductName" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editQuantity" class="col-sm-3 control-label">Quantity: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editQuantity" name="editQuantity" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editPurchase" class="col-sm-3 control-label">Purchase: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editPurchase" name="editPurchase" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editPaidAmount" class="col-sm-3 control-label">Paid Amount: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editPaidAmount" name="editPaidAmount" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editDueAmount" class="col-sm-3 control-label">Due Amount: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editDueAmount" name="editDueAmount" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <div class="form-group">
            <label for="editTransactionNumber" class="col-sm-3 control-label">Transaction Number: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="editTransactionNumber" name="editTransactionNumber" autocomplete="off">
            </div>
          </div> <!-- /form-group-->

          <input type="hidden" id="editId" name="editId">

        </div> <!-- /modal-body -->

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
          <button type="submit" class="btn btn-primary" id="editCreditBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save changes</button>
        </div> <!-- /modal-footer -->
      </form>
    </div> <!-- /modal-content -->
  </div> <!-- /modal-dialog -->
</div> <!-- /modal -->

<!-- /edit credit -->


<!-- Remove Bank Modal -->
<div class="modal fade" id="removeCreditModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Credit</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to remove?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeCreditBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script src="custom/js/credit.js"></script>

