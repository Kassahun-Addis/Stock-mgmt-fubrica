$(document).ready(function() {
    // Initialize DataTable
    var manageAssetTable = $('#manageAssetTable').DataTable({
        'ajax': 'php_action/fetchAssets.php', // PHP script to fetch asset data
        'order': [] // Disable initial ordering
    });

   // Initialize the Add Asset Modal
$('#addProductModalBtn').on('click', function() {
    // Reset the form when modal is triggered
    $('#submitAssetForm')[0].reset();
    $(".text-danger").remove();
    $(".form-group").removeClass('has-error').removeClass('has-success');
});

// Submit Asset Form - AJAX Request
$("#submitAssetForm").unbind('submit').bind('submit', function(e) {
    e.preventDefault();

    var form = $(this);

    // Form validation (customize based on your form fields)
    var assetName = $("#assetName").val();
    var category = $("#category").val();
    var purchasePrice = $("#purchasePrice").val();
    var valid = true;

    // Simple validation to check for empty fields
    if (assetName == "") {
        $("#assetName").after('<p class="text-danger">Asset Name is required</p>');
        $('#assetName').closest('.form-group').addClass('has-error');
        valid = false;
    } else {
        $("#assetName").find('.text-danger').remove();
        $("#assetName").closest('.form-group').addClass('has-success');
    }

    if (category == "") {
        $("#category").after('<p class="text-danger">Category is required</p>');
        $('#category').closest('.form-group').addClass('has-error');
        valid = false;
    } else {
        $("#category").find('.text-danger').remove();
        $("#category").closest('.form-group').addClass('has-success');
    }

    if (purchasePrice == "") {
        $("#purchasePrice").after('<p class="text-danger">Purchase Price is required</p>');
        $('#purchasePrice').closest('.form-group').addClass('has-error');
        valid = false;
    } else {
        $("#purchasePrice").find('.text-danger').remove();
        $("#purchasePrice").closest('.form-group').addClass('has-success');
    }

    // If form is valid, submit it
    if (valid) {
        // Show loading text while submitting
        $("#createAssetBtn").button('loading');

        // AJAX Request to submit the form
        $.ajax({
            url: form.attr('action'), // URL from form action
            type: form.attr('method'), // POST method
            data: form.serialize(), // Serialized form data
            dataType: 'json',
            success: function(response) {
                // Reset the button after submission
                $("#createAssetBtn").button('reset');

                // Clear previous messages
                $('#add-asset-messages').html('');

                if (response.status === 'success') {
                    // Close the modal
                    $("#addAssetModal").modal('hide');

                    // Reset the form fields
                    $("#submitAssetForm")[0].reset();

                    // Reload the manage asset table
                    manageAssetTable.ajax.reload(null, false);

                    // Show success messages
                    $('#add-asset-messages').html('<div class="alert alert-success">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.message +
                    '</div>');

                } else {
                    // Show error messages
                    $('#add-asset-messages').html('<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.message +
                    '</div>');
                }

                // Remove the alert after 5 seconds
                $(".alert-success, .alert-danger").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                    });
                });
            },
            error: function(xhr, status, error) {
                // Handle errors here
                $("#createAssetBtn").button('reset');
                $('#add-asset-messages').html('<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + error +
                '</div>');

                $(".alert-danger").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                    });
                });
            }
        });
    }
    return false; // Prevent form from submitting the normal way
});

   // Edit Asset Functionality
// Edit Asset Functionality
window.editAsset = function(assetId) {
    // Clear previous messages
    $('#edit-asset-messages').html('');

    // Fetch the asset details
    $.ajax({
        url: 'php_action/fetchSelectedAsset.php',
        type: 'post',
        data: { assetId: assetId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Populate modal fields with the asset data
                $('#assetId').val(response.data.id);
                $('#editAssetName').val(response.data.asset_name);
                $('#editCategory').val(response.data.category);
                $('#editDescription').val(response.data.description);
                $('#editPurchaseDate').val(response.data.purchase_date);
                $('#editPurchasePrice').val(response.data.purchase_price);
                $('#editDepartment').val(response.data.department);
                $('#editLastMaintenanceDate').val(response.data.last_maintenance_date);
                $('#editStatus').val(response.data.status);
                $('#editAssignedTo').val(response.data.assigned_to);
                $('#editRemark').val(response.data.remark);
                $('#editSerialNo').val(response.data.serial_no);

                // Show the modal
                $('#editAssetModal').modal('show');
            } else {
                $('#edit-asset-messages').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('#edit-asset-messages').html('<div class="alert alert-danger">Error: ' + error + '</div>');
        }
    });
};

// Handle Update Asset Form Submission
$("#editAssetForm").unbind('submit').bind('submit', function(e) {
    e.preventDefault();

    // Clear previous messages
    $('#edit-asset-messages').html('');

    // AJAX Request to submit the form
    $.ajax({
        url: 'php_action/editAsset.php', // Ensure this is correct
        type: 'POST',
        data: $(this).serialize(), // Serialized form data
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Close the modal
                $("#editAssetModal").modal('hide');

                // Reload the manage asset table
                manageAssetTable.ajax.reload(null, false); // Make sure manageAssetTable is defined

                // Show success message
                $('#edit-asset-messages').html('<div class="alert alert-success">' + response.message + '</div>');
            } else {
                // Show error message
                $('#edit-asset-messages').html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('#edit-asset-messages').html('<div class="alert alert-danger">Error: ' + error + '</div>');
        }
    });
});


  // Delete Asset Functionality
$("body").on("click", ".deleteAssetBtn", function(e) {
    e.preventDefault();
    var assetId = $(this).data('id');

    // Show confirmation dialog before deletion
    if (confirm("Are you sure you want to delete this asset?")) {
        $.ajax({
            url: 'php_action/removeAsset.php', // Adjust this path if necessary
            type: 'post',
            data: { assetId: assetId },
            dataType: 'json',
            success: function(response) {
                // Clear previous messages
                $('.remove-messages').html('');

                if (response.success) { // Check for 'success' instead of 'status'
                    manageAssetTable.ajax.reload(null, false); // Refresh the DataTable
                    $('.remove-messages').html('<div class="alert alert-success">' + response.messages + '</div>'); // Show success message
                } else {
                    $('.remove-messages').html('<div class="alert alert-danger">Error: ' + response.messages + '</div>'); // Show error message
                }
            },
            error: function(xhr, status, error) {
                $('.remove-messages').html('<div class="alert alert-danger">Error: ' + error + '</div>'); // Show error message
            }
        });
    }
});


    /* =========================================
       Export to CSV Functionality
    ============================================ */

    // Handle Export to CSV button click
    $('#exportCsvBtn').on('click', function() {
        window.location.href = 'php_action/exportAssetToCsv.php'; // Direct the button click to the PHP script
    });

});
