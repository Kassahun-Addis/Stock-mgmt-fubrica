

function editExpense(itemId) {
    $.ajax({
        url: 'php_action/fetchSelectedRequest.php',
        type: 'post',
        data: { ItemID: itemId },
        dataType: 'json',
        success: function(response) {
            // Check if response contains valid data
            if (response && response.ItemID) {
                // Populate the edit form fields with the response data
                $('#editExpenseModal #ItemID').val(response.ItemID);
                $('#editExpenseModal #ItemName').val(response.ItemName);
                $('#editExpenseModal #RequestedBy').val(response.RequestedBy);
                $('#editExpenseModal #IssuedBy').val(response.IssuedBy);
                $('#editExpenseModal #ApprovedBy').val(response.ApprovedBy);
                $('#editExpenseModal #Quantity').val(response.Quantity);
                $('#editExpenseModal #Unit').val(response.Unit);
                $('#editExpenseModal #UnitPrice').val(response.UnitPrice);
                $('#editExpenseModal #TotalPrice').val(response.TotalPrice);
                $('#editExpenseModal #QuantityAvailable').val(response.QuantityAvailable);
                $('#editExpenseModal #Remark').val(response.Remark);
                $('#editExpenseModal #OrderDate').val(response.OrderDate);

                // Show the modal
                $('#editExpenseModal').modal('show');
            } else {
                alert('Error: Expense data not found.');
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
            url: 'php_action/editRequestOrder.php', // URL to the PHP file handling the edit
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
function removeExpense(itemId) { // Changed parameter name to itemId for consistency
    if (itemId) { // Check if itemId is provided
        console.log("Preparing to delete item with ID:", itemId); // Log itemId to verify it's correct

        // Fetch the selected expense to delete
        $.ajax({
            url: 'php_action/fetchSelectedRequest.php',
            type: 'post',
            data: { ItemID: itemId }, // Changed key to match the parameter name in PHP script
            dataType: 'json',
            success: function(response) {
                console.log("Expense fetched successfully:", response); // Check if expense is fetched

                // Show confirmation modal
                $('#removeExpenseModal').modal('show');

                // Set up the confirmation button click event
                $('#removeExpenseBtn').off('click').on('click', function() {
                    console.log("Confirmed deletion for item ID:", itemId); // Confirming delete

                    // Perform delete request
                    $.ajax({
                        url: 'php_action/removeRequestOrder.php',
                        type: 'post',
                        data: { ItemID: itemId }, // Changed key to match the parameter name in PHP script
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


