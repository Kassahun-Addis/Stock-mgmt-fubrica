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
    <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> 
        <i class="glyphicon glyphicon-plus-sign"></i> Add Product 
    </button>
    <button class="btn btn-success" id="exportCsvBtn">
        <i class="glyphicon glyphicon-export"></i> Export to CSV
    </button>
</div> <!-- /div-action -->

				<table class="table" id="manageProductTable">
					<thead>
						
						<tr>
										
							<th>Product Name</th>
								<th>Category</th>
							<th>Selling Price</th>
						
							<th>Quantity</th>
							<th>Date</th>
							<th>Production Cost</th>
							<th>Detaild Specification</th>
							<th>Status</th>
							<th style="width:15%;">Options</th>
						</tr>
				
					</thead>
				</table>
				<!-- /table -->
				
				 <!-- Move Asset Value Calculation Panel Here -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="page-heading"> <i class="glyphicon glyphicon-stats"></i> Asset Value</div>
                    </div> <!-- /panel-heading -->
                    <div class="panel-body">
                        <?php
                            // Query to calculate the asset value
                            $sql = "SELECT SUM(quantity * purchase_cost) AS total_asset_value FROM finished_good";
                            $result = $connect->query($sql);
                            $row = $result->fetch_assoc();
                            $total_asset_value = $row['total_asset_value'];
                        ?>
                        <p><strong>Total Asset Value:</strong> <?php echo number_format($total_asset_value, 2); ?> </p>
                    </div> <!-- /panel-body -->
                </div> <!-- /panel -->
                <!-- End Asset Value Calculation Panel -->


			</div> <!-- /panel-body -->
		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<!-- add product -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content"><form class="form-horizontal" id="submitProductForm" action="php_action/createProduct.php" method="POST" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
    </div>

    <div class="modal-body" style="max-height:450px; overflow:auto;">

        <div id="add-product-messages"></div>


        <div class="form-group">
            <label for="productName" class="col-sm-3 control-label">Product Name: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="productName" placeholder="Product Name" name="productName" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

        <div class="form-group">
            <label for="quantity" class="col-sm-3 control-label">Quantity: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="quantity" placeholder="Quantity" name="quantity" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

        <div class="form-group">
            <label for="rate" class="col-sm-3 control-label">Selling Price: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="rate" placeholder="Rate" name="rate" autocomplete="off">
            </div>
        </div> <!-- /form-group-->



        <div class="form-group">
            <label for="categoryName" class="col-sm-3 control-label">Category Name: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <select type="text" class="form-control" id="categoryName" placeholder="Product Name" name="categoryName">
                    <option value="">~~SELECT~~</option>
                    <?php 
                    $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
                    $result = $connect->query($sql);

                    while($row = $result->fetch_array()) {
                        echo "<option value='".$row[0]."'>".$row[1]."</option>";
                    } // while
                    ?>
                </select>
            </div>
        </div> <!-- /form-group-->

        <div class="form-group">
            <label for="purchase" class="col-sm-3 control-label">Production Cost: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="purchase" placeholder="Purchasing Price" name="purchase" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

       

        <div class="form-group">
            <label for="serial_no" class="col-sm-3 control-label">Serial Number: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="serial_no" placeholder="Serial Number" name="serial_no" autocomplete="off">
            </div>
        </div> <!-- /form-group-->
           <div class="form-group">
    <label for="detailed_specification" class="col-sm-3 control-label">Detailed Specification: </label>
    <label class="col-sm-1 control-label">: </label>
    <div class="col-sm-8">
        <textarea class="form-control" id="detailed_specification" placeholder="Enter detailed specifications" name="detailed_specification" rows="3" autocomplete="off"></textarea>
    </div>
</div>


        <div class="form-group">
            <label for="alert_quantity" class="col-sm-3 control-label">Alert Quantity: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="alert_quantity" placeholder="Alert Quantity" name="alert_quantity" autocomplete="off">
            </div>
        </div> <!-- /form-group-->

        <div class="form-group">
            <label for="productStatus" class="col-sm-3 control-label">Status: </label>
            <label class="col-sm-1 control-label">: </label>
            <div class="col-sm-8">
                <select class="form-control" id="productStatus" name="productStatus">
                    <option value="">~~SELECT~~</option>
                    <option value="1">Available</option>
                    <option value="2">Not Available</option>
                </select>
            </div>
        </div> <!-- /form-group-->

    </div> <!-- /modal-body -->

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
    </div> <!-- /modal-footer -->
</form> <!-- /.form -->
    
    </div> <!-- /modal-content -->    
  </div> <!-- /modal-dailog -->
</div> 
<!-- /add categories -->


<!-- edit product -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Product</h4>
      </div>
      <div class="modal-body" style="max-height:450px; overflow:auto;">

        <div class="div-loading">
          <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
          <span class="sr-only">Loading...</span>
        </div>

        <div class="div-result">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab">Product Info</a></li>    
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">

            <!-- Product Info Tab -->
            <div role="tabpanel" class="tab-pane active" id="productInfo">
              <form class="form-horizontal" id="editProductForm" action="php_action/editProduct.php" method="POST">            
                <br />

                <div id="edit-product-messages"></div>

                <div class="form-group">
                  <label for="editProductName" class="col-sm-3 control-label">Product Name: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editProductName" placeholder="Product Name" name="editProductName" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->    

                <div class="form-group">
                  <label for="editQuantity" class="col-sm-3 control-label">Quantity: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editQuantity" placeholder="Quantity" name="editQuantity" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->    

                <div class="form-group">
                  <label for="editRate" class="col-sm-3 control-label">Selling Price: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editRate" placeholder="Selling Price" name="editRate" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->   

                <div class="form-group">
                  <label for="editCategoryName" class="col-sm-3 control-label">Category Name: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <select class="form-control" id="editCategoryName" name="editCategoryName">
                      <option value="">~~SELECT~~</option>
                      <?php 
                      $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
                      $result = $connect->query($sql);

                      while($row = $result->fetch_array()) {
                        echo "<option value='".$row[0]."'>".$row[1]."</option>";
                      } // while
                      ?>
                    </select>
                  </div>
                </div> <!-- /form-group--> 

                <div class="form-group">
                  <label for="editPurchase" class="col-sm-3 control-label">Purchase Cost: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editPurchase" placeholder="Purchase Cost" name="editPurchase" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->       

                <div class="form-group">
                  <label for="editSerial_no" class="col-sm-3 control-label">Serial Number: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editSerial_no" placeholder="Serial Number" name="editSerial_no" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->   

                <div class="form-group">
                  <label for="editAlert_quantity" class="col-sm-3 control-label">Alert Quantity: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="editAlert_quantity" placeholder="Alert Quantity" name="editAlert_quantity" autocomplete="off">
                  </div>
                </div> <!-- /form-group-->   

                <div class="form-group">
                  <label for="editProductStatus" class="col-sm-3 control-label">Status: </label>
                  <label class="col-sm-1 control-label">: </label>
                  <div class="col-sm-8">
                    <select class="form-control" id="editProductStatus" name="editProductStatus">
                      <option value="">~~SELECT~~</option>
                      <option value="1">Available</option>
                      <option value="2">Not Available</option>
                    </select>
                  </div>
                </div> <!-- /form-group-->        

                <div class="modal-footer editProductFooter">
                  <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                  <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                </div> <!-- /modal-footer -->                 
              </form> <!-- /.form -->     
            </div>    
            <!-- /product info -->
          </div>

        </div>
        
      </div> <!-- /modal-body -->
      
    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dialog -->
</div>
<!-- /edit product -->

<!-- categories brand -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Product</h4>
      </div>
      <div class="modal-body">

      	<div class="removeProductMessages"></div>

        <p>Do you really want to remove ?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /categories brand -->

<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportProductToCsv.php';  // Direct the button click to the PHP script
    });
</script>


<script src="custom/js/product.js"></script>

<?php require_once 'includes/footer.php'; ?>
					