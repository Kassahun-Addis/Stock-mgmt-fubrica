var manageSellsTable;

$(document).ready(function() {
    $('#navCategories').addClass('active');	

    // Initialize DataTable for managing sells users
    manageSellsTable = $('#manageSellsTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchUser.php', // Fetch sells user data
            'type': 'GET',
            'error': function(xhr, error, thrown) {
                console.error('Error in AJAX request:', error); // Log the error
                console.error('Response:', xhr.responseText); // Log the full response
                alert('An error occurred while fetching data: ' + error); // Alert the user
            }
        },
        'order': [],
        'columns': [
            { 'data': 0 }, // Column 1: Username
            { 'data': 1 }, // Column 2: Phone Number
            { 'data': 2, 'orderable': false } // Column 3: Options (Edit/Remove)
        ]
    });

    // Handle form submission for adding a sells user
    $('#submitSellsForm').on('submit', function(e) {
        e.preventDefault();

        // Clear any previous messages
        $('#add-sells-messages').html('');

        // Remove the error class from form fields
        $(".form-group").removeClass('has-error').removeClass('has-success');

        // Button loading state
        var $button = $('#createSellsBtn');
        $button.button('loading');

        // Serialize the form data
        var formData = $(this).serialize();

        // AJAX request to process the form data
        $.ajax({
            url: $(this).attr('action'), // The action attribute of the form
            type: $(this).attr('method'), // The method attribute of the form (POST)
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Reset the button to its original state
                $button.button('reset');

                if (response.success === true) {
                    // If successfully added, display success message
                    $('#add-sells-messages').html('<div class="alert alert-success">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
                    '</div>');

                    // Reset the form fields
                    $('#submitSellsForm')[0].reset();

                    // Reload the DataTable
                    manageSellsTable.ajax.reload(null, false);

                    // Close the modal after a short delay
                    setTimeout(function() {
                        $('#addSellsModal').modal('hide');
                    }, 1500);
                } else {
                    // If there were validation errors, display them
                    $.each(response.messages, function(index, value) {
                        var id = $("#" + index);
                        id.closest('.form-group')
                        .removeClass('has-success')
                        .addClass('has-error');
                        id.after('<p class="text-danger">'+value+'</p>');
                    });
                }
            },
            error: function() {
                // Handle error scenario
                $('#add-sells-messages').html('<div class="alert alert-danger">'+
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> Something went wrong. Please try again.'+
                '</div>');
                $button.button('reset');
            }
        });

        return false; // Prevent default form submission
    });
});

function removeSells(sellsId = null) {
    if (sellsId) {
        $.ajax({
            url: 'php_action/fetchSelectedUser.php',
            type: 'POST',
            data: { sellsId: sellsId },
            dataType: 'json',
            success: function(response) {
                if (response.user_id) {
                    // Populate the modal with user details
                    $("#removeSellsModal").find('.modal-body').html(
                        '<p>Are you sure you want to delete the user <strong>' + response.username + '</strong>?</p>'
                    );

                    // Bind the click event to the button
                    $("#removeSellsBtn").unbind('click').bind('click', function() {
                        $("#removeSellsBtn").button('loading');

                        $.ajax({
                            url: 'php_action/removeSells.php',
                            type: 'POST',
                            data: { sellsId: sellsId },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success === true) {
                                    $("#removeSellsBtn").button('reset');
                                    $("#removeSellsModal").modal('hide');
                                    manageSellsTable.ajax.reload(null, false);

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
                            }
                        });
                    });

                    // Show the modal
                    $("#removeSellsModal").modal('show');
                } else {
                    alert('User details could not be fetched.');
                }
            }
        });
    } else {
        alert('Error: No user ID provided.');
    }
}

function editSells(sellsId = null) {
    if (sellsId) {
        $.ajax({
         url: 'php_action/fetchSelectedUser.php',
            type: 'post',
            data: { sellsId: sellsId },
            dataType: 'json',
            success: function(response) {
                // Check if response contains data
                if (response.user_id) {
                    $("#editSellsName").val(response.username);
                    $("#editPassword").val(response.password);
                    $("#editPhoneNumber").val(response.phone);

                    $("#editSellsForm").unbind('submit').bind('submit', function() {
                        var sellsName = $("#editSellsName").val();
                        var password = $("#editPassword").val();
                        var phoneNumber = $("#editPhoneNumber").val();

                        var hasError = false;

                        if (sellsName == "") {
                            $("#editSellsName").after('<p class="text-danger">Sells Name field is required</p>');
                            $('#editSellsName').closest('.form-group').addClass('has-error');
                            hasError = true;
                        } else {
                            $("#editSellsName").find('.text-danger').remove();
                            $("#editSellsName").closest('.form-group').addClass('has-success');
                        }

                        if (password == "") {
                            $("#editPassword").after('<p class="text-danger">Password field is required</p>');
                            $('#editPassword').closest('.form-group').addClass('has-error');
                            hasError = true;
                        } else {
                            $("#editPassword").find('.text-danger').remove();
                            $("#editPassword").closest('.form-group').addClass('has-success');
                        }

                        if (phoneNumber == "") {
                            $("#editPhoneNumber").after('<p class="text-danger">Phone Number field is required</p>');
                            $('#editPhoneNumber').closest('.form-group').addClass('has-error');
                            hasError = true;
                        } else {
                            $("#editPhoneNumber").find('.text-danger').remove();
                            $("#editPhoneNumber").closest('.form-group').addClass('has-success');
                        }

                        if (!hasError) {
                            var form = $(this);
                            $("#editSellsBtn").button('loading');

                            $.ajax({
                                url: form.attr('action'),
                                type: form.attr('method'),
                                data: form.serialize() + '&sellsId=' + sellsId,
                                dataType: 'json',
                                success: function(response) {
                                    $("#editSellsBtn").button('reset');

                                    if (response.success === true) {
                                        manageSellsTable.ajax.reload(null, false);
                                        $("#editSellsModal").modal('hide');

                                        $('#edit-sells-messages').html('<div class="alert alert-success">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                            '</div>');

                                        $(".alert-success").delay(500).show(10, function() {
                                            $(this).delay(3000).hide(10, function() {
                                                $(this).remove();
                                            });
                                        });
                                    } else {
                                        $('#edit-sells-messages').html('<div class="alert alert-danger">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                                            '</div>');

                                        $(".alert-danger").delay(500).show(10, function() {
                                            $(this).delay(3000).hide(10, function() {
                                                $(this).remove();
                                            });
                                        });
                                    }
                                }
                            });
                        }

                        return false;
                    });
                } else {
                    alert('Error: No data returned from server.');
                }
            },
            error: function(xhr, error, thrown) {
                console.error('Error in AJAX request:', error);
                console.error('Response:', xhr.responseText);
                alert('An error occurred while fetching the sells user data: ' + error);
            }
        });
    } else {
        alert('Error: Please refresh the page and try again.');
    }
}
