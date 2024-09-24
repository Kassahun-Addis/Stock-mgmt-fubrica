$(document).ready(function() {
    // Initialize credit management table
    manageCreditTable = $('#manageCreditTable').DataTable({
        'ajax': {
            'url': 'php_action/fetchCredit.php',
            'method': 'POST'
        },
        'order': []
    });
 // Event listener for creditId field
    $('#creditId').blur(function() {
        var creditId = $(this).val();
        if (creditId !== "") {
            // AJAX request to fetch supplier data
            $.ajax({
                url: 'php_action/fetchSupplierByCreditId.php',
                type: 'post',
                data: { creditId: creditId },
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        // Populate supplier field if supplier found
                        $('#supplier').val(response.supplier);
                    } else {
                        // Handle error: supplier not found
                        $('#supplier').val('');
                        alert(response.messages);
                    }
                }
            });
        }
    });
    // Pay credit modal button clicked
    $("#addCreditModalBtn").unbind('click').bind('click', function() {
        console.log('Form submitted');
        // Reset credit form
        $("#submitCreditForm")[0].reset();

        // Remove text-error
        $(".text-danger").remove();
        // Remove form-group error
        $(".form-group").removeClass('has-error').removeClass('has-success');

        // Submit credit payment form
        $("#submitCreditForm").unbind('submit').bind('submit', function() {
            // Form validation
            var creditId = $("#creditId").val();
            var supplier = $("#supplier").val();
            var paidAmount = $("#paidAmount").val();
            var transactionNumber = $("#transactionNumber").val();

            var isValid = true; // Track form validity

            if (creditId == "") {
                $("#creditId").after('<p class="text-danger">Credit ID field is required</p>');
                $('#creditId').closest('.form-group').addClass('has-error');
                isValid = false;
            } else {
                $("#creditId").find('.text-danger').remove();
                $("#creditId").closest('.form-group').addClass('has-success');
            }

            if (supplier == "") {
                $("#supplier").after('<p class="text-danger">Supplier field is required</p>');
                $('#supplier').closest('.form-group').addClass('has-error');
                isValid = false;
            } else {
                $("#supplier").find('.text-danger').remove();
                $("#supplier").closest('.form-group').addClass('has-success');
            }

            if (paidAmount == "") {
                $("#paidAmount").after('<p class="text-danger">Paid Amount field is required</p>');
                $('#paidAmount').closest('.form-group').addClass('has-error');
                isValid = false;
            } else {
                $("#paidAmount").find('.text-danger').remove();
                $("#paidAmount").closest('.form-group').addClass('has-success');
            }

            if (transactionNumber == "") {
                $("#transactionNumber").after('<p class="text-danger">Transaction Number field is required</p>');
                $('#transactionNumber').closest('.form-group').addClass('has-error');
                isValid = false;
            } else {
                $("#transactionNumber").find('.text-danger').remove();
                $("#transactionNumber").closest('.form-group').addClass('has-success');
            }

            if (isValid) {
                // Submit loading button
                $("#createCreditBtn").button('loading');

                var form = $(this);
                var formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        $("#createCreditBtn").button('reset');
                        
                        if (response.success === true) {
                            $("#submitCreditForm")[0].reset();

                            $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
                            
                            // Show success message
                            $('#add-credit-messages').html('<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
                              '</div>');

                            // Remove messages
                            $(".alert-success").delay(500).show(10, function() {
                                $(this).delay(3000).hide(10, function() {
                                    $(this).remove();
                                });
                            });

                            // Reload the credit table
                            manageCreditTable.ajax.reload(null, true);

                            // Remove text-error
                            $(".text-danger").remove();
                            // Remove form-group error
                            $(".form-group").removeClass('has-error').removeClass('has-success');

                            // Close the modal
                            $("#addCreditModal").modal('hide');
                        } else {
                            // Show error message
                            $('#add-credit-messages').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> '+ response.messages +
                              '</div>');
                        }
                    }, // /success function
                    error: function(xhr, status, error) {
                        $("#createCreditBtn").button('reset');
                        $('#add-credit-messages').html('<div class="alert alert-danger">'+
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                            '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> An error occurred. Please try again.' +
                          '</div>');
                    }
                }); // /ajax function
            } // /if validation is ok

            return false;
        }); // /submit credit payment form
    }); // /pay credit modal button clicked

   

});
function removeCredit(creditId = null) {
    $.ajax({
        url: 'php_action/fetchSelectedCredit.php',
        type: 'post',
        data: {creditId: creditId},
        dataType: 'json',
        success: function(response) {
            $("#removeCreditBtn").unbind('click').bind('click', function() {
                // Show loading state
                $("#removeCreditBtn").button('loading');

                $.ajax({
                    url: 'php_action/removeCredit.php',
                    type: 'post',
                    data: {creditId: creditId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success === true) {
                            // Reset the button state
                            $("#removeCreditBtn").button('reset');
                            // Close the modal
                            $("#removeCreditModal").modal('hide');
                            // Reload the credit table data
                            manageCreditTable.ajax.reload(null, false);
                            // Display success message
                            $('.remove-messages').html('<div class="alert alert-success">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                '</div>');

                            $(".alert-success").delay(500).show(10, function() {
                                $(this).delay(3000).hide(10, function() {
                                    $(this).remove();
                                });
                            });
                        } else {
                            // Display error message
                            $('.remove-messages').html('<div class="alert alert-danger">'+
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
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
        }
    });
}

function editCredit(creditId = null) {
    if (creditId) {
        // Fetch selected credit data
        $.ajax({
            url: 'php_action/fetchSelectedCredit.php', // Adjust the URL to your actual endpoint
            type: 'post',
            data: { creditId: creditId },
            dataType: 'json',
            success: function (response) {
                // Populate the form fields with the current credit data
                $("#editSupplier").val(response.supplier);
                $("#editProductName").val(response.product_name);
                $("#editQuantity").val(response.quantity);
                $("#editPurchase").val(response.purchase);
                $("#editPaidAmount").val(response.paid_amount);
                $("#editDueAmount").val(response.remaining_due_amount); // Corrected field name
                $("#editTransactionNumber").val(response.transaction_number);
                $("#editId").val(response.credit_id); // Add this line to populate the hidden field

                // Update the credit when the form is submitted
                $("#editCreditForm").unbind('submit').bind('submit', function () {
                    var form = $(this);
                    var formData = form.serialize() + '&editId=' + creditId;

                    // Additional validation can be added here if necessary
                    $("#editCreditBtn").button('loading');

                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            $("#editCreditBtn").button('reset');

                            if (response.success === true) {
                                manageCreditTable.ajax.reload(null, false);
                                $("#editCreditModal").modal('hide');

                                // Show success message
                                $('#edit-credit-messages').html('<div class="alert alert-success">' +
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                    '</div>');

                                $(".alert-success").delay(500).show(10, function () {
                                    $(this).delay(3000).hide(10, function () {
                                        $(this).remove();
                                    });
                                });
                            } else {
                                // Show error message
                                $('#edit-credit-messages').html('<div class="alert alert-danger">' +
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                    '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                                    '</div>');

                                $(".alert-danger").delay(500).show(10, function () {
                                    $(this).delay(3000).hide(10, function () {
                                        $(this).remove();
                                    });
                                });
                            }
                        }
                    });

                    return false; // Prevent form submission
                });
            }
        });
    } else {
        alert('Error: Please refresh the page and try again.');
    }
}
