<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Bank Category</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Bank Categories</div>
			</div>
			<div class="panel-body">
				<div class="remove-messages"></div>
				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" id="addBankModalBtn" data-target="#addBankModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add Bank </button>
				</div>				
				
				<table class="table" id="manageBankTable">
					<thead>
						<tr>							
							<th>Bank Name</th>
							<th style="width:15%;">Options</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="submitBankForm" action="php_action/createBank.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Bank</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="add-bank-messages"></div>
	        <div class="form-group">
	        	<label for="bankName" class="col-sm-4 control-label">Bank Name: </label>
	        	<div class="col-sm-7">
				      <input type="text" class="form-control" id="bankName" name="bankName" autocomplete="off">
				    </div>
	        </div>	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        <button type="submit" class="btn btn-primary" id="createBankBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>      
     	</form>
    </div>    
  </div>
</div>

<!-- Edit Bank Modal -->
<div class="modal fade" id="editBankModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="editBankForm" action="php_action/editBank.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Bank</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="edit-bank-messages"></div>
		      <div class="edit-bank-result">
		      	<div class="form-group">
		        	<label for="editBankName" class="col-sm-4 control-label">Bank Name: </label>
		        	<div class="col-sm-7">
					      <input type="text" class="form-control" id="editBankName" name="editBankName" autocomplete="off">
					    </div>
		        </div> 
		      </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        <button type="submit" class="btn btn-success" id="editBankBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>
     	</form>
    </div>
  </div>
</div>

<!-- Remove Bank Modal -->
<div class="modal fade" id="removeBankModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Bank</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to remove?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeBankBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script src="custom/js/bank.js"></script>

<?php require_once 'includes/footer.php'; ?>
