<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
  <div class="col-md-12">

    <ol class="breadcrumb">
      <li><a href="dashboard.php">Home</a></li>
      <li class="active">Product</li>
    </ol>

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Product</div>
      </div> <!-- /panel-heading -->
      <div class="panel-body">

        <div class="remove-messages"></div>

        <div class="div-action pull pull-right" style="padding-bottom:20px;">
          <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addAssetModal"> 
            <i class="glyphicon glyphicon-plus-sign"></i> Add Product 
          </button>
          <button class="btn btn-success" id="exportCsvBtn">
            <i class="glyphicon glyphicon-export"></i> Export to CSV
          </button>
        </div> <!-- /div-action -->

        <table class="table" id="manageAssetTable">
          <thead>
            <tr>
              <th>Asset ID</th>
              <th>Asset Name</th>
              <th>Category</th>
              <th>Description</th>
              <th>Purchase Date</th>
              <th>Purchase Price</th>
              <th>Department</th>
              <th>Last Maintenance Date</th>
              <th>Status</th>
              <th>Assigned To</th>
              <th>Remark</th>
              <th>Serial No</th>
              <th style="width:15%;">Options</th>
            </tr>
          </thead>
        </table>
        <!-- /table -->

        <!-- Asset Value Calculation Panel -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="page-heading"> <i class="glyphicon glyphicon-stats"></i> Asset Value</div>
          </div> <!-- /panel-heading -->
          <div class="panel-body">
            <?php
              // Query to calculate the asset value
              $sql = "SELECT SUM(quantity * purchase) AS total_asset_value FROM product";
              $result = $connect->query($sql);
              $row = $result->fetch_assoc();
              $total_asset_value = $row['total_asset_value'];
            ?>
            <p><strong>Total Asset Value:</strong> <?php echo number_format($total_asset_value, 2); ?> </p>
          </div> <!-- /panel-body -->
        </div> <!-- /panel -->

      </div> <!-- /panel-body -->
    </div> <!-- /panel -->
  </div> <!-- /col-md-12 -->
</div> <!-- /row -->

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
           <div id="add-asset-messages"></div>
      <form class="form-horizontal" id="submitAssetForm" action="php_action/createAsset.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Asset</h4>
        </div>
        <div class="modal-body" style="max-height:450px; overflow:auto;">
          <div id="add-asset-messages"></div>

          <!-- Asset Name -->
          <div class="form-group">
            <label for="assetName" class="col-sm-3 control-label">Asset Name:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="assetName" name="assetName" placeholder="Asset Name" autocomplete="off" required>
            </div>
          </div>

          <!-- Category -->
          <div class="form-group">
            <label for="category" class="col-sm-3 control-label">Category:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="category" name="category" placeholder="Category" autocomplete="off" required>
            </div>
          </div>

          <!-- Description -->
          <div class="form-group">
            <label for="description" class="col-sm-3 control-label">Description:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <textarea class="form-control" id="description" name="description" placeholder="Description" autocomplete="off"></textarea>
            </div>
          </div>

          <!-- Purchase Date -->
          <div class="form-group">
            <label for="purchaseDate" class="col-sm-3 control-label">Purchase Date:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="purchaseDate" name="purchaseDate" required>
            </div>
          </div>

          <!-- Purchase Price -->
          <div class="form-group">
            <label for="purchasePrice" class="col-sm-3 control-label">Purchase Price:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="number" step="0.01" class="form-control" id="purchasePrice" name="purchasePrice" placeholder="Purchase Price" autocomplete="off" required>
            </div>
          </div>

          <!-- Department -->
          <div class="form-group">
            <label for="department" class="col-sm-3 control-label">Department:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="department" name="department" placeholder="Department" autocomplete="off" required>
            </div>
          </div>

          <!-- Last Maintenance Date -->
          <div class="form-group">
            <label for="lastMaintenanceDate" class="col-sm-3 control-label">Last Maintenance Date:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="lastMaintenanceDate" name="lastMaintenanceDate">
            </div>
          </div>

          <!-- Status -->
          <div class="form-group">
            <label for="status" class="col-sm-3 control-label">Status:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <select class="form-control" id="status" name="status" required>
                <option value="">~~SELECT~~</option>
                <option value="Operational">Operational</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Retired">Retired</option>
              </select>
            </div>
          </div>

          <!-- Assigned To -->
          <div class="form-group">
            <label for="assignedTo" class="col-sm-3 control-label">Assigned To:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="assignedTo" name="assignedTo" placeholder="Assigned To" autocomplete="off">
            </div>
          </div>

          <!-- Remark -->
          <div class="form-group">
            <label for="remark" class="col-sm-3 control-label">Remark:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <textarea class="form-control" id="remark" name="remark" placeholder="Remark" autocomplete="off"></textarea>
            </div>
          </div>

          <!-- Serial No -->
          <div class="form-group">
            <label for="serialNo" class="col-sm-3 control-label">Serial No:</label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="serialNo" name="serialNo" placeholder="Serial No" autocomplete="off" required>
            </div>
          </div>

        </div> <!-- /modal-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
          <button type="submit" class="btn btn-primary" id="createAssetBtn" data-loading-text="Loading..."><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
        </div> <!-- /modal-footer -->
      </form>
    </div>
  </div>
</div>
<!-- /Add Asset Modal -->

<!-- Edit Asset Modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div id="edit-asset-messages"></div>
      <form class="form-horizontal" id="editAssetForm" action="php_action/editAsset.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Asset</h4>
        </div>
        <div class="modal-body" style="max-height:450px; overflow:auto;">
          <div class="div-loading">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
          </div>

          <div class="div-result">
            <div id="edit-asset-messages"></div>

            <!-- Asset ID (Hidden) -->
            <input type="hidden" id="assetId" name="assetId">

            <!-- Asset Name -->
            <div class="form-group">
              <label for="editAssetName" class="col-sm-3 control-label">Asset Name:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="editAssetName" name="editAssetName" required>
              </div>
            </div>

            <!-- Category -->
            <div class="form-group">
              <label for="editCategory" class="col-sm-3 control-label">Category:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="editCategory" name="editCategory" required>
              </div>
            </div>

            <!-- Description -->
            <div class="form-group">
              <label for="editDescription" class="col-sm-3 control-label">Description:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <textarea class="form-control" id="editDescription" name="editDescription"></textarea>
              </div>
            </div>

            <!-- Purchase Date -->
            <div class="form-group">
              <label for="editPurchaseDate" class="col-sm-3 control-label">Purchase Date:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="date" class="form-control" id="editPurchaseDate" name="editPurchaseDate" required>
              </div>
            </div>

            <!-- Purchase Price -->
            <div class="form-group">
              <label for="editPurchasePrice" class="col-sm-3 control-label">Purchase Price:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="number" step="0.01" class="form-control" id="editPurchasePrice" name="editPurchasePrice" required>
              </div>
            </div>

            <!-- Department -->
            <div class="form-group">
              <label for="editDepartment" class="col-sm-3 control-label">Department:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="editDepartment" name="editDepartment" required>
              </div>
            </div>

            <!-- Last Maintenance Date -->
            <div class="form-group">
              <label for="editLastMaintenanceDate" class="col-sm-3 control-label">Last Maintenance Date:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="date" class="form-control" id="editLastMaintenanceDate" name="editLastMaintenanceDate">
              </div>
            </div>

            <!-- Status -->
            <div class="form-group">
              <label for="editStatus" class="col-sm-3 control-label">Status:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <select class="form-control" id="editStatus" name="editStatus" required>
                  <option value="">~~SELECT~~</option>
                  <option value="Operational">Operational</option>
                  <option value="Under Maintenance">Under Maintenance</option>
                  <option value="Retired">Retired</option>
                </select>
              </div>
            </div>

            <!-- Assigned To -->
            <div class="form-group">
              <label for="editAssignedTo" class="col-sm-3 control-label">Assigned To:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="editAssignedTo" name="editAssignedTo">
              </div>
            </div>

            <!-- Remark -->
            <div class="form-group">
              <label for="editRemark" class="col-sm-3 control-label">Remark:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <textarea class="form-control" id="editRemark" name="editRemark"></textarea>
              </div>
            </div>

            <!-- Serial No -->
            <div class="form-group">
              <label for="editSerialNo" class="col-sm-3 control-label">Serial No:</label>
              <label class="col-sm-1 control-label">: </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="editSerialNo" name="editSerialNo" required>
              </div>
            </div>

          </div> <!-- /div-result -->
        </div> <!-- /modal-body -->
        <div class="modal-footer editAssetFooter">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
          <button type="submit" class="btn btn-success" id="editAssetBtn" data-loading-text="Loading..."><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /Edit Asset Modal -->


<!-- Remove Asset Modal -->
<div class="modal fade" id="removeAssetModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this asset?</p>
                <div class="removeProductMessages"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="removeProductBtn" class="btn btn-danger">Remove Asset</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportProductToCsv.php';  // Direct the button click to the PHP script
    });
</script>



<script src="custom/js/asset.js"></script>
<?php require_once 'includes/footer.php'; ?>
					