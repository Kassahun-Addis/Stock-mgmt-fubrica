

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'php_action/db_connect.php';
require_once 'includes/header.php';

if (isset($_SESSION['success_message'])) {
    echo '<div id="success-message" class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Clear the message after displaying
}
?>

<script>
    window.onload = function() {
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    };
</script>

<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addExpenseModalLabel"><i class="fa fa-plus"></i> Add Request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form class="form-horizontal" id="submitExpenseForm" action="php_action/createRequestOrder.php" method="POST">
        <div id="add-expense-messages"></div>    
        
        <div class="form-group">
            <label for="RequestedBy" class="col-sm-3 control-label">Requested By: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="RequestedBy" name="RequestedBy" placeholder="Requested By" autocomplete="off">
            </div>
        </div>  

        <div class="form-group">
            <label for="IssuedBy" class="col-sm-3 control-label">Issued By: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="IssuedBy" name="IssuedBy" placeholder="Issued By" autocomplete="off">
            </div>
        </div> 
        
        <div class="form-group">
            <label for="ApprovedBy" class="col-sm-3 control-label">Approved By: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="ApprovedBy" name="ApprovedBy" placeholder="Approved By" autocomplete="off">
            </div>
        </div> 

        <div class="form-group">
            <label for="Remark" class="col-sm-3 control-label">Remark: </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="Remark"  name="Remark[]" placeholder="Remark" autocomplete="off">
            </div>
        </div> 
        
        <div class="form-group">
            <label for="OrderDate" class="col-sm-3 control-label">Requested Date: </label>
            <div class="col-sm-8">
                <input type="date" class="form-control" id="OrderDate" name="OrderDate" autocomplete="off">
            </div>
        </div>


         <div id="itemContainer">
            <div class="item-row">
                <div>
                    <label for="ItemName">Item Name</label>
                    <select class="form-control" name="ItemName[]" required>
                        <option value="">Select items</option>
                        <?php
                        $sql = "SELECT * FROM raw_materials";
                        $result = $connect->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['item_name'] . '" data-price="' . $row['purchase_price'] . '">' . $row['item_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No items available</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="UnitPrice">Unit Price</label>
                    <input type="text" class="form-control" name="UnitPrice[]" readonly>
                </div>
                <div>
                    <label for="Quantity">Quantity</label>
                    <input type="number" class="form-control" name="Quantity[]" placeholder="Quantity" required>
                </div>
                <div>
                    <label for="TotalPrice">Total Price</label>
                    <input type="number" class="form-control" name="TotalPrice[]" placeholder="Total Price" readonly>
                </div>
                <div>
                    <label for="Unit">Unit</label>
                    <input type="text" class="form-control" name="Unit[]" placeholder="Unit" required>
                </div>
                
           <div>
            <label for="QuantityAvailable">Quantity Available: </label>
            <input type="number" class="form-control" id="QuantityAvailable" name="QuantityAvailable" placeholder="Quantity Available" autocomplete="off" readonly>
          </div>
                <div>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" id="addItemButton">Add Another Item</button>

        

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-8">
                <button type="button" class="btn btn-default" onclick="window.history.back();"> 
                    <i class="glyphicon glyphicon-remove-sign"></i> Close
                </button>
                <button type="submit" class="btn btn-primary" id="createExpenseBtn"> 
                    <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
    </div>
    </div>
  </div>
</div>



<div class="row">
    <div class="col-md-12">

        <!-- <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>          
            <li class="active">Request</li>
        </ol> -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Request</div>
            </div> <!-- /panel-heading -->
            <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull-right" style="padding-bottom:20px;">
                <button class="btn btn-default button1" data-toggle="modal" id="addExpenseModalBtn" data-target="#addExpenseModal"> 
    <i class="glyphicon glyphicon-plus-sign"></i> Add Request 
</button>
                    <button class="btn btn-success" id="exportCsvBtn">
                        <i class="glyphicon glyphicon-export"></i> Export to CSV
                    </button>
                </div> <!-- /div-action -->
 <table class="table" id="manageExpenseTable">
        <thead>
            <tr>
            <th>Item Name</th>
            <th>Requested By</th>							
            <th>Issued By</th>
            <th>Approved By</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>UnitPrice</th>
            <th>TotalPrice</th>
       
            <th>Remark</th>
            <th>OrderDate</th>
            <th style="width:15%;">Options</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
            </div> <!-- /panel-body -->
        </div> <!-- /panel -->      
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->



<!-- Edit Expense Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="editExpenseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editExpenseForm" action="php_action/editRequestOrder.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Expense</h4>
                </div>
                <div class="modal-body" style="max-height:850px; overflow:auto;">
                    <!-- Hidden field to store the item ID -->
                    <input type="hidden" name="ItemID" id="ItemID">

                    <!-- Expense Details Input Fields -->
                    <div class="form-group row">
                        <label for="ItemName" class="col-sm-3 control-label">Item Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ItemName" name="ItemName" placeholder="Item Name" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="UnitPrice" class="col-sm-3 control-label">Unit Price</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="UnitPrice" name="UnitPrice" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="Quantity" class="col-sm-3 control-label">Quantity</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="Quantity" name="Quantity" placeholder="Quantity" autocomplete="off">
                        </div>
                    </div> 

                    <div id="errorMessage" class="text-danger"></div>

                    <div class="form-group row">
                        <label for="TotalPrice" class="col-sm-3 control-label">Total Price</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="TotalPrice" name="TotalPrice" placeholder="Total Price" autocomplete="off" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="RequestedBy" class="col-sm-3 control-label">Requested By</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="RequestedBy" name="RequestedBy" placeholder="Requested By" autocomplete="off">
                        </div>
                    </div>  

                    <div class="form-group row">
                        <label for="IssuedBy" class="col-sm-3 control-label">Issued By</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="IssuedBy" name="IssuedBy" placeholder="Issued By" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="ApprovedBy" class="col-sm-3 control-label">Approved By</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ApprovedBy" name="ApprovedBy" placeholder="Approved By" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="Unit" class="col-sm-3 control-label">Unit</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="Unit" name="Unit" placeholder="Unit" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="Remark" class="col-sm-3 control-label">Remark</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="Remark" name="Remark" placeholder="Remark" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="OrderDate" class="col-sm-3 control-label">Requested Date</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="OrderDate" name="OrderDate" autocomplete="off">
                        </div>
                    </div>
                </div> <!-- /Modal body-->
                
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
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Request</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to remove this request order?</p>
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


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Add new item row
    $('#addItemButton').click(function() {
        var newItemRow = `
            <div class="item-row row">
    <div class="col-md-2">
        <label for="ItemName">Item Name</label>
        <select class="form-control" name="ItemName[]" required>
            <option value="">Select items</option>
            <?php
            $sql = "SELECT * FROM raw_materials";
            $result = $connect->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['item_name'] . '" data-price="' . $row['purchase_price'] . '">' . $row['item_name'] . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <label for="UnitPrice">Unit Price</label>
        <input type="text" class="form-control" name="UnitPrice[]" readonly>
    </div>
    <div class="col-md-2">
        <label for="Quantity">Quantity</label>
        <input type="number" class="form-control" name="Quantity[]" placeholder="Quantity" required>
    </div>
    <div class="col-md-2">
        <label for="TotalPrice">Total Price</label>
        <input type="number" class="form-control" name="TotalPrice[]" placeholder="Total Price" readonly>
    </div>
    <div class="col-md-1">
        <label for="Unit">Unit</label>
        <input type="text" class="form-control" name="Unit[]" placeholder="Unit" required>
    </div>
    <div class="col-md-2">
        <label for="QuantityAvailable">Quantity Available</label>
        <input type="number" class="form-control" id="QuantityAvailable" name="QuantityAvailable" placeholder="Quantity Available" autocomplete="off">
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger remove-item">Remove</button>
    </div>
</div>
`;
        $('#itemContainer').append(newItemRow);
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
    });

    // Event delegation for ItemName dropdown
$(document).on('change', 'select[name="ItemName[]"]', function() {
    var selectedOption = $(this).find('option:selected');
    var unitPrice = selectedOption.data('price');
    var $row = $(this).closest('.item-row');

    // Set the Unit Price field
    $row.find('input[name="UnitPrice[]"]').val(unitPrice);
    $row.find('input[name="TotalPrice[]"]').val('');
    $('#errorMessage').text('');

    var selectedItem = $(this).val();
    if (selectedItem) {
        $.ajax({
            url: 'php_action/AvailableQuantity.php',
            method: 'POST',
            data: { itemName: selectedItem },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var availableQuantity = response.quantity;
                    // Set the available quantity in both input value and data attribute
                    $row.find('input[name="QuantityAvailable"]').val(availableQuantity);
                    $row.find('input[name="QuantityAvailable"]').attr('data-original-quantity', availableQuantity);
                } else {
                    $row.find('input[name="QuantityAvailable"]').val(0);
                    $row.find('input[name="QuantityAvailable"]').attr('data-original-quantity', 0);
                }
            },
            error: function() {
                $row.find('input[name="QuantityAvailable"]').val(0);
                $row.find('input[name="QuantityAvailable"]').attr('data-original-quantity', 0);
            }
        });
    } else {
        $row.find('input[name="QuantityAvailable"]').val(0);
        $row.find('input[name="QuantityAvailable"]').attr('data-original-quantity', 0);
    }
});

// Event delegation for Quantity input
$(document).on('input', 'input[name="Quantity[]"]', function() {
    var $row = $(this).closest('.item-row');
    var quantity = Number($(this).val());
    var unitPrice = Number($row.find('input[name="UnitPrice[]"]').val());
    // Get the original available quantity from the data attribute
    var originalAvailableQuantity = Number($row.find('input[name="QuantityAvailable"]').attr('data-original-quantity'));

    if (quantity > originalAvailableQuantity) {
        $(this).css('border', '2px solid red');
        $('#errorMessage').html('Error: Quantity exceeds available stock! Available quantity is ' + originalAvailableQuantity);
        $(this).val(originalAvailableQuantity); // Set quantity to available if exceeded
        quantity = originalAvailableQuantity;
    } else {
        $(this).css('border', '1px solid #ccc');
        $('#errorMessage').text('');
    }

    // Calculate total price
    var totalPrice = quantity * unitPrice;
    $row.find('input[name="TotalPrice[]"]').val(totalPrice.toFixed(2));

    // Update the displayed available quantity but keep original intact
    var remainingQuantity = originalAvailableQuantity - quantity;
    $row.find('input[name="QuantityAvailable"]').val(remainingQuantity);
});
    // Initialize data table for expenses
    manageExpenseTable = $('#manageExpenseTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchRequest.php',
            'type': 'GET',
            'error': function(xhr, error, thrown) {
                console.error('Error loading expenses:', error, thrown);
            }
        },
        'order': []
    });
});
</script>


<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function() {
        window.location.href = 'php_action/exportRequestOrderToCsv.php';  // Direct the button click to the PHP script
    });
</script>

<script src="custom/js/request_order.js"></script>

<?php require_once 'includes/footer.php'; ?>