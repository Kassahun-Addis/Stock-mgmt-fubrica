<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-check"></i> Report Generator
            </div>
            <!-- /panel-heading -->
            <div class="panel-body">
                
                <form class="form-horizontal" action="php_action/getOrderReport.php" method="post" id="getReportForm">
                    <div class="form-group">
                        <label for="startDate" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endDate" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reportType" class="col-sm-2 control-label">Report Type</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="reportType" name="reportType">
                                <option value="orders">Order Report</option>
                                <option value="products">Product Report</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success" id="generateReportBtn"> 
                                <i class="glyphicon glyphicon-ok-sign"></i> Generate Report
                            </button>
                        </div>
                    </div>
                </form>

    
            </div>
            <!-- /panel-body -->
            
     <div class="panel-body">   
    <!-- New Form for Credit Report -->
    <form class="form-horizontal" action="php_action/getCreditReport.php" method="post" id="getCreditReportForm">
        <div class="form-group">
            <label for="searchField" class="col-sm-2 control-label">Credit ID or Supplier</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="searchField" name="searchField" placeholder="Credit ID or Supplier" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success" id="generateCreditReportBtn"> 
                    <i class="glyphicon glyphicon-ok-sign"></i> Generate Credit Report
                </button>
            </div>
        </div>
    </form>
</div>

            
        </div>
    </div>
    <!-- /col-dm-12 -->
</div>
<!-- /row -->

<script src="custom/js/report.js"></script>

<?php require_once 'includes/footer.php'; ?>
