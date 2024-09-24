$(document).ready(function() {
    // Initialize the table to display wastage data
    var manageWastageTable = $('#manageWastageTable').DataTable({
        'ajax': 'php_action/fetchWastage.php',  // Fetch the wastage data from the server
        'order': []  // Disable initial sorting
    });

    // Add wastage modal - submit form
    $('#submitWastageForm').unbind('submit').bind('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission

        // Form validation
        var productName = $('#product_name').val();
        var quantity = $('#quantity').val();
        var unit = $('#unit').val();
        var wastageDate = $('#wastage_date').val();
        var reason = $('#reason').val();

        if (productName && quantity && unit && wastageDate && reason) {
            // Submit the form via AJAX
            $.ajax({
                url: 'php_action/createWastage.php',
                type: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Display success message
                        $('.remove-messages').html('<div class="alert alert-success">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                        '</div>');

                        // Refresh the table
                        manageWastageTable.ajax.reload(null, false);

                        // Close the modal and reset the form
                        $('#addWastageModal').modal('hide');
                        $('#submitWastageForm')[0].reset();
                    } else {
                        alert(response.messages);  // Display error message
                    }
                }
            });
        } else {
            alert('Please fill in all fields');
        }
    });

    // Edit wastage modal
    $(document).on('click', '.btn-warning', function() {
        var wastageId = $(this).data('id');
        $.ajax({
            url: 'php_action/getWastageById.php',
            type: 'POST',
            data: { wastageId: wastageId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Populate the edit modal with the fetched data
                    $('#wastage_id').val(response.data.id);
                    $('#editWastageModal #product_name').val(response.data.product_name);
                    $('#editWastageModal #quantity').val(response.data.quantity);
                    $('#editWastageModal #unit').val(response.data.unit);
                    $('#editWastageModal #wastage_date').val(response.data.wastage_date);
                    $('#editWastageModal #reason').val(response.data.reason);

                    // Show the edit modal
                    $('#editWastageModal').modal('show');
                } else {
                    alert('Failed to fetch wastage details.');
                }
            }
        });
    });

    // Handle form submission for editing wastage
  // Handle form submission for editing wastage
$('#editWastageForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),  // URL for handling the form submission
        type: 'POST',
        data: $(this).serialize(),  // Serialize the form data
        dataType: 'json',  // Expect JSON response
        success: function(response) {
            if (response.success === true) {
                // Display success message in edit-wastage-messages div
                $('#edit-wastage-messages').html('<div class="alert alert-success">'+
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                '</div>');

                // Reload the table data to reflect the changes
                manageWastageTable.ajax.reload(null, false);

                // Close the modal after a slight delay to allow the message to show
                setTimeout(function() {
                    $('#editWastageModal').modal('hide');
                }, 2000); // Delay of 2 seconds
            } else {
                // Display error message in edit-wastage-messages div
                $('#edit-wastage-messages').html('<div class="alert alert-danger">'+
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                '</div>');
            }
        }
    });
});

    // Handle delete button click
    $(document).on('click', '.btn-danger', function() {
        var wastageId = $(this).data('id');
        var wastageRow = $(this).closest('tr');  // Store the row to be deleted

        // Show delete confirmation modal
        $('#deleteWastageModal').modal('show');

        // Confirm delete button click
        $('#confirmDeleteBtn').off('click').on('click', function() {
            $.ajax({
                url: 'php_action/deleteWastage.php',
                type: 'POST',
                data: { wastageId: wastageId },
                dataType: 'json',  // Ensure the response is JSON
                success: function(response) {
                    if (response.success) {
                        // Remove the row from the table
                        manageWastageTable.row(wastageRow).remove().draw();
                        $('#deleteWastageModal').modal('hide');
                    } else {
                        alert('Failed to delete wastage record.');
                    }
                }
            });
        });
    });

    // Export wastage records to CSV
    $('#exportCsvBtn').on('click', function() {
        window.location.href = 'php_action/exportWastageToCsv.php';  // Adjust the PHP script for wastage export
    });
});
