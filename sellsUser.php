<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Sells User</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Sells User</div>
			</div>
			<div class="panel-body">
				<div class="remove-messages"></div>
				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" id="addSellsModalBtn" data-target="#addSellsModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add Sells </button>
				</div>				
				
				<table class="table" id="manageSellsTable">
					<thead>
						<tr>							
							<th>Sells Name</th>
							<th>Phone Number</th>
							<th style="width:15%;">Options</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Add Sells Modal -->
<div class="modal fade" id="addSellsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="submitSellsForm" action="php_action/createSells.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Sells</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="add-sells-messages"></div>
	        <div class="form-group">
	        	<label for="sellsName" class="col-sm-4 control-label">Sells Name: </label>
	        	<div class="col-sm-7">
				      <input type="text" class="form-control" id="sellsName" name="sellsName" autocomplete="off">
				    </div>
	        </div>	 
	        <div class="form-group">
	        	<label for="password" class="col-sm-4 control-label">Password: </label>
	        	<div class="col-sm-7">
				      <input type="password" class="form-control" id="password" name="password" autocomplete="off">
				    </div>
	        </div>
	        <div class="form-group">
	        	<label for="phoneNumber" class="col-sm-4 control-label">Phone Number: </label>
	        	<div class="col-sm-7">
				      <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" autocomplete="off">
				    </div>
	        </div>
	      </div>
	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        <button type="submit" class="btn btn-primary" id="createSellsBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>      
     	</form>
    </div>    
  </div>
</div>

<!-- Edit Sells Modal -->
<!-- Edit Sells Modal -->
<div class="modal fade" id="editSellsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="editSellsForm" action="php_action/editSells.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Sells User</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="edit-sells-messages"></div>
		      <div class="edit-sells-result">
		      	<div class="form-group">
		        	<label for="editSellsName" class="col-sm-4 control-label">Sells Name: </label>
		        	<div class="col-sm-7">
					      <input type="text" class="form-control" id="editSellsName" name="editSellsName" autocomplete="off">
					    </div>
		        </div> 
		        <div class="form-group">
		        	<label for="editPassword" class="col-sm-4 control-label">Password: </label>
		        	<div class="col-sm-7">
					      <input type="password" class="form-control" id="editPassword" name="editPassword" autocomplete="off">
					    </div>
		        </div>
		        <div class="form-group">
		        	<label for="editPhoneNumber" class="col-sm-4 control-label">Phone Number: </label>
		        	<div class="col-sm-7">
					      <input type="text" class="form-control" id="editPhoneNumber" name="editPhoneNumber" autocomplete="off">
					    </div>
		        </div>
		      </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        <button type="submit" class="btn btn-success" id="editSellsBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>
     	</form>
    </div>
  </div>
</div>


<!-- Remove Sells Modal -->
<div class="modal fade" id="removeSellsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Sells</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to remove this sells user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeSellsBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/sellsuser.js"></script>

<?php require_once 'includes/footer.php'; ?>
