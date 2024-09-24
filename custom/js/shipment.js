var manageExpenseTable;

$(document).ready(function() {
    // Initialize data table for shipment
    manageExpenseTable = $('#manageExpenseTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchShipment.php',
            'type': 'GET',
            'error': function(xhr, error, thrown) {
                console.error('Error loading shipments:', error, thrown);
            }
        },
        'order': []
    });

    // Add shipment modal button clicked
    $("#addExpenseModalBtn").on('click', function() {
        // Reset the form and remove any previous messages
        $("#submitExpenseForm")[0].reset();
        $(".text-danger").remove();
        $(".form-group").removeClass('has-error has-success');
        $('#add-expense-messages').html('');
    });

    // Submit shipment form
    $("#submitExpenseForm").on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        // Form validation
        var valid = true;
        $(".text-danger").remove(); // Clear error messages
        $(".form-group").removeClass('has-error has-success'); // Clear classes

        var fields = [
            {selector: "#assigned_person", name: "Assigned Person"},
            {selector: "#shipment_date", name: "Shipping Date"},
            {selector: "#carrier", name: "Carrier"},
            {selector: "#tracking_number", name: "Tracking No"},
            {selector: "#shipping_address", name: "Shipping Address"},
            {selector: "#shipping_cost", name: "Shipping Cost"},
            {selector: "#status", name: "Status"},
           
        ];
        // Validate each field
        fields.forEach(function(field) {
            if (!$(field.selector).val().trim()) {
                $(field.selector).after('<p class="text-danger">' + field.name + ' is required</p>')
                    .closest('.form-group').addClass('has-error');
                valid = false;
            } else {
                $(field.selector).closest('.form-group').addClass('has-success');
            }
        });

        if (valid) {
            $("#createExpenseBtn").prop('disabled', true); // Disable the button while processing

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#createExpenseBtn").prop('disabled', false); // Enable the button

                    if (response.success === true) {
                        // Reset the form and hide the modal
                        $("#submitExpenseForm")[0].reset();
                        $('#add-expense-messages').html('<div class="alert alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                            '</div>');

                        manageExpenseTable.ajax.reload(null, true);

                        $(".alert-success").delay(500).show(10, function() {
                            $(this).delay(3000).hide(10, function() {
                                $(this).remove();
                            });
                        });

                        $(".form-group").removeClass('has-error has-success');
                    } else {
                        $('#add-expense-messages').html('<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                            '</div>');
                    }
                }
            });
        }
    });
});


function editExpense(Shipment_ID) {
    console.log("response",shipment_id)
    $.ajax({
        url: 'php_action/fetchSelectedShipment.php',
        type: 'post',
        data: { shipment_id: Shipment_ID },
        dataType: 'json',
      
        success: function(response) {
           
            // Check if response contains valid data
            if (response && response.ShipmentID) {
                
                // Populate the edit form fields with the response data
                $('#editExpenseModal #shipment_id').val(response.ShipmentID);
                $('#editExpenseModal #assigned_person').val(response.Assigned_person);
                $('#editExpenseModal #shipment_date').val(response.ShipmentDate);
                $('#editExpenseModal #carrier').val(response.Carrier);
                $('#editExpenseModal #tracking_number').val(response.TrackingNumber);
                $('#editExpenseModal #shipping_address').val(response.ShippingAddress);
                $('#editExpenseModal #shipping_cost').val(response.ShippingCost);
                $('#editExpenseModal #status').val(response.Status);
               
                // Show the modal
                $('#editExpenseModal').modal('show');
            } else {
                alert('Error: shipment data not found.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching expense data:', error);
            alert('An error occurred while fetching expense data.');
        }
    });
}

    
    // Submit Edit Form via AJAX
    $("#editExpenseForm").on('submit', function(event) {
        event.preventDefault();
        var formData = $(this).serialize(); // Serialize form data
        console.log('Form Data:', formData); // Debugging statement
    
        $.ajax({
            url: 'php_action/editShipment.php', // URL to the PHP file handling the edit
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debugging statement
                if (response.success === true) {
                    $('#editExpenseModal').modal('hide');
                    manageExpenseTable.ajax.reload(null, false);
                    $('.edit-messages').html('<div class="alert alert-success">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                        '</div>');
                    $(".alert-success").delay(500).show(10, function() {
                        $(this).delay(3000).hide(10, function() {
                            $(this).remove();
                        });
                    });
                } else {
                    $('.edit-messages').html('<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.message +
                        '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error editing expense:', error);
                alert('An error occurred while editing the expense.');
            }
        });
    });
    
    

// Remove expense
function removeExpense(Shipment_ID) { // Changed parameter name to Shipment_ID for consistency
    if (Shipment_ID) { // Check if Shipment_ID is provided
        console.log("Preparing to delete item with ID:", Shipment_ID); // Log Shipment_ID to verify it's correct

        // Fetch the selected shipment to delete
        $.ajax({
            url: 'php_action/fetchSelectedShipment.php',
            type: 'post',
            data: { shipment_id: Shipment_ID }, // Changed key to match the parameter name in PHP script
            dataType: 'json',
            success: function(response) {
                console.log("Expense fetched successfully:", response); // Check if shipment is fetched

                // Show confirmation modal
                $('#removeExpenseModal').modal('show');

                // Set up the confirmation button click event
                $('#removeExpenseBtn').off('click').on('click', function() {
                    console.log("Confirmed deletion for shipment id:", Shipment_ID); // Confirming delete

                    // Perform delete request
                    $.ajax({
                        url: 'php_action/removeShipment.php',
                        type: 'post',
                        data: { shipment_id: Shipment_ID }, // Changed key to match the parameter name in PHP script
                        dataType: 'json',
                        success: function(response) {
                            console.log("Response from server:", response); // Log server response
                            if (response.success === true) {
                                $('#removeExpenseModal').modal('hide');
                                manageExpenseTable.ajax.reload(null, false);

                                $('.remove-messages').html('<div class="alert alert-success">' +
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                    '</div>');

                                $(".alert-success").delay(500).show(10, function() {
                                    $(this).delay(3000).hide(10, function() {
                                        $(this).remove();
                                    });
                                });
                            } else {
                                $('.remove-messages').html('<div class="alert alert-danger">' +
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                                    '</div>');

                                $(".alert-danger").delay(500).show(10, function() {
                                    $(this).delay(3000).hide(10, function() {
                                        $(this).remove();
                                    });
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting expense:', error);
                            alert('An error occurred while deleting the expense.');
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching expense data:', error);
                alert('An error occurred while fetching expense data.');
            }
        });
    } else {
        alert('Error: Please refresh the page and try again.');
    }
}


