var manageBankTable;

$(document).ready(function() {
	$('#navCategories').addClass('active');	

	manageBankTable = $('#manageBankTable').DataTable({
		'ajax' : 'php_action/fetchBank.php', // The path to your PHP script that fetches the data
		'order': [],
		'columns': [
			{ 'data': 'bank_name' }, // Column 1: Bank Name
			{ 'data': 'options', 'orderable': false } // Column 2: Options (Edit/Remove)
		]
	}); 
	$('#addBankModalBtn').on('click', function() {
		$("#submitBankForm")[0].reset();
		$(".text-danger").remove();
		$('.form-group').removeClass('has-error').removeClass('has-success');

		$("#submitBankForm").unbind('submit').bind('submit', function() {
			var bankName = $("#bankName").val();

			if(bankName == "") {
				$("#bankName").after('<p class="text-danger">Bank Name field is required</p>');
				$('#bankName').closest('.form-group').addClass('has-error');
			} else {
				$("#bankName").find('.text-danger').remove();
				$("#bankName").closest('.form-group').addClass('has-success');
			}

			if(bankName) {
				var form = $(this);
				$("#createBankBtn").button('loading');

				$.ajax({
					url : form.attr('action'),
					type: form.attr('method'),
					data: form.serialize(),
					dataType: 'json',
					success:function(response) {
						$("#createBankBtn").button('reset');

						if(response.success == true) {
							manageBankTable.ajax.reload(null, false);
							$("#submitBankForm")[0].reset();
							$('.form-group').removeClass('has-error').removeClass('has-success');

							$('#add-bank-messages').html('<div class="alert alert-success">'+
		            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
		            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
			          '</div>');

							$(".alert-success").delay(500).show(10, function() {
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
	});

 // Edit and Remove functionalities
 
 
 })
 
 function removeBank(bankId = null) {
    $.ajax({
        url: 'php_action/fetchSelectedBank.php',
        type: 'post',
        data: {bankId: bankId},
        dataType: 'json',
        success: function(response) {
            // remove bank button clicked to trigger the removal
            $("#removeBankBtn").unbind('click').bind('click', function() {
                // show loading state
                $("#removeBankBtn").button('loading');

                $.ajax({
                    url: 'php_action/removeBank.php',
                    type: 'post',
                    data: {bankId: bankId},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success === true) {
                            // reset the button state
                            $("#removeBankBtn").button('reset');
                            // close the modal
                            $("#removeBankModal").modal('hide');
                            // reload the bank table data
                            manageBankTable.ajax.reload(null, false);
                            // display success message
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
                            // display error message
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
function editBank(bankId = null) {
    if(bankId) {
        // Fetch selected bank data
        $.ajax({
            url: 'php_action/fetchSelectedBank.php',
            type: 'post',
            data: {bankId: bankId},
            dataType: 'json',
            success: function(response) {
                // Show the current bank name in the edit modal
                $("#editBankName").val(response.bank_name);

                // Update the bank when the form is submitted
                $("#editBankForm").unbind('submit').bind('submit', function() {
                    var bankName = $("#editBankName").val();

                    if(bankName == "") {
                        $("#editBankName").after('<p class="text-danger">Bank Name field is required</p>');
                        $('#editBankName').closest('.form-group').addClass('has-error');
                    } else {
                        $("#editBankName").find('.text-danger').remove();
                        $("#editBankName").closest('.form-group').addClass('has-success');
                    }

                    if(bankName) {
                        var form = $(this);
                        $("#editBankBtn").button('loading');

                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: form.serialize() + '&bankId=' + bankId,
                            dataType: 'json',
                            success: function(response) {
                                $("#editBankBtn").button('reset');

                                if(response.success === true) {
                                    manageBankTable.ajax.reload(null, false);
                                    $("#editBankModal").modal('hide');

                                    // Show success message
                                    $('#edit-bank-messages').html('<div class="alert alert-success">'+
                                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                        '</div>');

                                    $(".alert-success").delay(500).show(10, function() {
                                        $(this).delay(3000).hide(10, function() {
                                            $(this).remove();
                                        });
                                    });
                                } else {
                                    // Show error message
                                    $('#edit-bank-messages').html('<div class="alert alert-danger">'+
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
                    }

                    return false;
                });
            }
        });
    } else {
        alert('Error: Please refresh the page and try again.');
    }
}
