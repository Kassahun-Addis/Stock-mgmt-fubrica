var manageExpenseTable;

$(document).ready(function() {
    // Initialize data table for expenses
    manageExpenseTable = $('#manageExpenseTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchEmployee.php',
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
            {selector: "#FirstName", name: "First Name"},
            {selector: "#LastName", name: "Last Name"},
            {selector: "#Phone_no", name: "Phone No"},
            {selector: "#Email", name: "Email"},
            {selector: "#Position", name: "Position"},
            {selector: "#Department", name: "Department"},
            {selector: "#HireDate", name: "HireDate"}
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


function editExpense(EmployeeID) {
    $.ajax({
        url: 'php_action/fetchSelectedEmployee.php',
        type: 'post',
        data: { EmployeeID: EmployeeID },
        dataType: 'json',
        success: function(response) {
            // Check if response contains valid data
            if (response && response.EmployeeID) {
                // Populate the edit form fields with the response data
                $('#editExpenseModal #EmployeeID').val(response.EmployeeID);
                $('#editExpenseModal #FirstName').val(response.FirstName);
                $('#editExpenseModal #LastName').val(response.LastName);
                $('#editExpenseModal #Phone_no').val(response.Phone_no);
                $('#editExpenseModal #Email').val(response.Email);
                $('#editExpenseModal #Position').val(response.Position);
                $('#editExpenseModal #Department').val(response.Department);
                $('#editExpenseModal #HireDate').val(response.HireDate);
                
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
            url: 'php_action/editEmployee.php', // URL to the PHP file handling the edit
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
function removeExpense(EmployeeID) { // Changed parameter name to EmployeeID for consistency
    if (EmployeeID) { // Check if EmployeeID is provided
        console.log("Preparing to delete employee with ID:", EmployeeID); // Log EmployeeID to verify it's correct

        // Fetch the selected expense to delete
        $.ajax({
            url: 'php_action/fetchSelectedEmployee.php',
            type: 'post',
            data: { EmployeeID: EmployeeID }, // Changed key to match the parameter name in PHP script
            dataType: 'json',
            success: function(response) {
                console.log("Employee fetched successfully:", response); // Check if expense is fetched

                // Show confirmation modal
                $('#removeExpenseModal').modal('show');

                // Set up the confirmation button click event
                $('#removeExpenseBtn').off('click').on('click', function() {
                    console.log("Confirmed deletion for employee ID:", EmployeeID); // Confirming delete

                    // Perform delete request
                    $.ajax({
                        url: 'php_action/removeEmployee.php',
                        type: 'post',
                        data: { EmployeeID: EmployeeID }, // Changed key to match the parameter name in PHP script
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
                            console.error('Error deleting employee:', error);
                            alert('An error occurred while deleting the employee.');
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching employee data:', error);
                alert('An error occurred while fetching employee data.');
            }
        });
    } else {
        alert('Error: Please refresh the page and try again.');
    }
}


