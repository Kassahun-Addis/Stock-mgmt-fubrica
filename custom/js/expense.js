var manageExpenseTable;

$(document).ready(function() {
    // Initialize data table for expenses
    manageExpenseTable = $('#manageExpenseTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchExpense.php',
            'type': 'GET',
            'error': function(xhr, error, thrown) {
                console.error('Error loading expenses:', error, thrown);
            }
        },
        'order': []
    });

    // Add expense modal button clicked
    $("#addExpenseModalBtn").on('click', function() {
        // Reset the form and remove any previous messages
        $("#submitExpenseForm")[0].reset();
        $(".text-danger").remove();
        $(".form-group").removeClass('has-error has-success');
        $('#add-expense-messages').html('');
    });

    // Submit expense form
    $("#submitExpenseForm").on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        // Form validation
        var valid = true;
        $(".text-danger").remove(); // Clear error messages
        $(".form-group").removeClass('has-error has-success'); // Clear classes

        var fields = [
            {selector: "#expense_for", name: "Expense For"},
            {selector: "#ex_description", name: "Description"},
            {selector: "#amount", name: "Amount"},
            {selector: "#expense_date", name: "Expense Date"}
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


function editExpense(expenseId) {
    $.ajax({
        url: 'php_action/fetchSelectedExpense.php',
        type: 'post',
        data: { expenseId: expenseId },
        dataType: 'json',
        success: function(response) {
            // Check if response is valid
            if (response && response.id) {
                // Populate the edit form fields with the response data
                $('#editExpenseModal #expense_id').val(response.id);
                $('#editExpenseModal #expense_for').val(response.expense_for);
                $('#editExpenseModal #ex_description').val(response.ex_description);
                $('#editExpenseModal #amount').val(response.amount);
                $('#editExpenseModal #expense_date').val(response.expense_date);
                
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

    $.ajax({
        url: 'php_action/editExpense.php', // Update this to the correct URL
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success === true) {
                // Hide the modal after successful update
                $('#editExpenseModal').modal('hide');

                // Reload the DataTable
                manageExpenseTable.ajax.reload(null, false);

                // Display success message
                $('.edit-messages').html('<div class="alert alert-success">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                    '</div>');

                // Auto-hide success message
                $(".alert-success").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                    });
                });
            } else {
                // Display error message
                $('.edit-messages').html('<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
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
function removeExpense(expenseId) {
    if (expenseId) {
        // Fetch the selected expense to delete
        $.ajax({
            url: 'php_action/fetchSelectedExpense.php',
            type: 'post',
            data: { expenseId: expenseId },
            dataType: 'json',
            success: function(response) {
                // Show confirmation modal
                $('#removeExpenseModal').modal('show');

                // Set up the confirmation button click event
                $('#removeExpenseBtn').off('click').on('click', function() {
                    // Perform delete request
                    $.ajax({
                        url: 'php_action/removeExpense.php',
                        type: 'post',
                        data: { expenseId: expenseId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success === true) {
                                // Hide the modal and reload the table
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
