var manageProductTable;

$(document).ready(function() {

	var isLowStock = false;
	var currentUrl = window.location.href;
	function isLowParameterSet(url) { 
		var urlObject = new URL(url);
		var lowParameterValue = urlObject.searchParams.get("low");
		if (lowParameterValue !== null && lowParameterValue.toLowerCase() === "true") {
			return true;
		} else {
			return false;
		}
	}
	
	if (isLowParameterSet(currentUrl)) {
		isLowStock = true; 
	} else {
		isLowStock = false; 
	}

	// top nav bar 
	$('#navProduct').addClass('active');
	// manage product data table

    console.log(isLowStock);
	manageProductTable = $('#manageProductTable').DataTable({
		'ajax': {
			'url': 'php_action/fetchProduct.php',
			'method': 'POST',
			'data': function(data) { 
				data.whereParam = isLowStock;
				return data;
			}
		},
		'order': []
	});

// Add product modal btn clicked
$("#addProductModalBtn").unbind('click').bind('click', function() {
    // product form reset
    $("#submitProductForm")[0].reset();        

    // remove text-error 
    $(".text-danger").remove();
    // remove form-group error
    $(".form-group").removeClass('has-error').removeClass('has-success');

    $("#productImage").fileinput({
        overwriteInitial: true,
        maxFileSize: 2500,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#kv-avatar-errors-1',
        msgErrorClass: 'alert alert-block alert-danger',
        defaultPreviewContent: '<img src="assests/images/photo_default.png" alt="Profile Image" style="width:100%;">',
        layoutTemplates: {main2: '{preview} {remove} {browse}'},                                    
        allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]
    });   

    // submit product form
    $("#submitProductForm").unbind('submit').bind('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Form validation
        var productImage = $("#productImage").val();
        var productName = $("#productName").val();
        var quantity = $("#quantity").val();
        var sellingPrice = $("#rate").val();
        var categoryName = $("#categoryName").val();
        var productStatus = $("#productStatus").val();
        var purchase = $("#purchase").val();
        var serial_no = $("#serial_no").val();

        // Reset error messages
        $(".text-danger").remove();
        $(".form-group").removeClass('has-error').removeClass('has-success');

        // Form validation logic (simplified for brevity)
        var isValid = true;
        if(productImage == "") {
            $("#productImage").closest('.form-group').addClass('has-error').after('<p class="text-danger">Product Image field is required</p>');
            isValid = false;
        }
        if(productName == "") {
            $("#productName").closest('.form-group').addClass('has-error').after('<p class="text-danger">Product Name field is required</p>');
            isValid = false;
        }
        if(quantity == "") {
            $("#quantity").closest('.form-group').addClass('has-error').after('<p class="text-danger">Quantity field is required</p>');
            isValid = false;
        }
        if(sellingPrice == "") {
            $("#rate").closest('.form-group').addClass('has-error').after('<p class="text-danger">Selling Price field is required</p>');
            isValid = false;
        }
        if(categoryName == "") {
            $("#categoryName").closest('.form-group').addClass('has-error').after('<p class="text-danger">Category Name field is required</p>');
            isValid = false;
        }
        if(productStatus == "") {
            $("#productStatus").closest('.form-group').addClass('has-error').after('<p class="text-danger">Product Status field is required</p>');
            isValid = false;
        }

        if(isValid) {
            // Submit form via AJAX
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Reset the submit button
                    $("#createProductBtn").button('reset');

                    // Display message in #add-product-messages div
                    var messageType = response.success ? 'alert-success' : 'alert-danger';
                    var messageIcon = response.success ? 'glyphicon-ok-sign' : 'glyphicon-remove-sign';
                    $('#add-product-messages').html('<div class="alert ' + messageType + '">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon ' + messageIcon + '"></i></strong> ' + response.messages +
                        '</div>');

                    // Scroll to the top of the modal to show the message
                    $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);

                    // Clear form fields if product is successfully added
                    if(response.success) {
                        $("#submitProductForm")[0].reset();
                        // Reload the product table (or do other post-success tasks)
                        manageProductTable.ajax.reload(null, true);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    $('#add-product-messages').html('<div class="alert alert-danger">'+
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                        '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + 'Error: ' + error +
                        '</div>');
                }
            });
        }
        return false;
    });
});

	

	// remove product 	

}); // document.ready fucntion

function editProduct(productId = null) {
    if (productId) {
        $("#productId").remove();
        // Remove existing error messages and styling
        $(".text-danger").remove();
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $('.div-loading').removeClass('div-hide');
        $('.div-result').addClass('div-hide');

        $.ajax({
            url: 'php_action/fetchSelectedProduct.php',
            type: 'post',
            data: { productId: productId },
            dataType: 'json',
            success: function(response) {
                // Hide loading and show result
                $('.div-loading').addClass('div-hide');
                $('.div-result').removeClass('div-hide');

                // Populate form fields
                $("#getProductImage").attr('src', 'stock/' + response.product_image);

                $("#editProductName").val(response.product_name);
                $("#editQuantity").val(response.quantity);
                $("#editRate").val(response.rate);
                $("#editCategoryName").val(response.categories_id);
                $("#editPurchase").val(response.purchase);
                $("#editSerial_no").val(response.serial_no);
                $("#editAlert_quantity").val(response.alert_quantity);
                $("#editProductStatus").val(response.active);

                // Remove and re-add hidden productId input
                $(".editProductFooter").append('<input type="hidden" name="productId" id="productId" value="' + response.product_id + '" />');

                // Update form submit handler
                $("#editProductForm").unbind('submit').bind('submit', function() {
                    // Remove existing error messages
                    $(".text-danger").remove();
                    $(".form-group").removeClass('has-error').removeClass('has-success');

                    // Validate form fields
                    var isValid = true;
                    var fields = ['#editProductName', '#editQuantity', '#editRate', '#editCategoryName', '#editProductStatus'];
                    fields.forEach(function(field) {
                        var value = $(field).val();
                        if (value === "") {
                            $(field).after('<p class="text-danger">This field is required</p>');
                            $(field).closest('.form-group').addClass('has-error');
                            isValid = false;
                        } else {
                            $(field).find('.text-danger').remove();
                            $(field).closest('.form-group').addClass('has-success');
                        }
                    });

                    if (isValid) {
                        $("#editProductBtn").button('loading');

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
                                $("#editProductBtn").button('reset');

                                $("html, body, div.modal, div.modal-content, div.modal-body").animate({ scrollTop: '0' }, 100);

                                $('#edit-product-messages').html('<div class="alert alert-success">' +
                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                    '</div>');

                                $(".alert-success").delay(500).show(10, function() {
                                    $(this).delay(3000).hide(10, function() {
                                        $(this).remove();
                                    });
                                });

                                manageProductTable.ajax.reload(null, true);
                                $(".text-danger").remove();
                                $(".form-group").removeClass('has-error').removeClass('has-success');
                            }
                        });
                    }

                    return false;
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching product data:', error);
                alert('An error occurred while fetching the product data.');
            }
        });
    } else {
        alert('Error: Please refresh the page.');
    }
}



// remove product 
function removeProduct(productId = null) {
    if (productId) {
        // Set up the click handler for the remove product button
        $("#removeProductBtn").unbind('click').bind('click', function() {
            // Show loading state for the remove button
            $("#removeProductBtn").button('loading');
            
            // Perform the AJAX request to remove the product
            $.ajax({
                url: 'php_action/removeProduct.php',
                type: 'post',
                data: { productId: productId },
                dataType: 'json',
                success: function(response) {
                    // Reset the remove button
                    $("#removeProductBtn").button('reset');

                    // Handle success response
                    if (response.success) {
                        // Hide the modal
                        $("#removeProductModal").modal('hide');

                        // Reload the product table
                        manageProductTable.ajax.reload(null, false);

                        // Display success message
                        $(".remove-messages").html('<div class="alert alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                            '</div>');

                        // Hide the success message after a delay
                        $(".alert-success").delay(500).show(10, function() {
                            $(this).delay(3000).hide(10, function() {
                                $(this).remove();
                            });
                        });
                    } else {
                        // Display error message if something went wrong
                        $(".removeProductMessages").html('<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> ' + response.messages +
                            '</div>');

                        // Hide the error message after a delay
                        $(".alert-danger").delay(500).show(10, function() {
                            $(this).delay(3000).hide(10, function() {
                                $(this).remove();
                            });
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Reset the remove button
                    $("#removeProductBtn").button('reset');

                    // Display a generic error message
                    $(".removeProductMessages").html('<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-remove-sign"></i></strong> An error occurred while removing the product. Please try again.' +
                        '</div>');

                    // Hide the error message after a delay
                    $(".alert-danger").delay(500).show(10, function() {
                        $(this).delay(3000).hide(10, function() {
                            $(this).remove();
                        });
                    });
                }
            });
            return false;
        }); // End of remove product button click handler
    } // End of if productId
} // End of removeProduct function

function clearForm(oForm) {
	// var frm_elements = oForm.elements;									
	// console.log(frm_elements);
	// 	for(i=0;i<frm_elements.length;i++) {
	// 		field_type = frm_elements[i].type.toLowerCase();									
	// 		switch (field_type) {
	// 	    case "text":
	// 	    case "password":
	// 	    case "textarea":
	// 	    case "hidden":
	// 	    case "select-one":	    
	// 	      frm_elements[i].value = "";
	// 	      break;
	// 	    case "radio":
	// 	    case "checkbox":	    
	// 	      if (frm_elements[i].checked)
	// 	      {
	// 	          frm_elements[i].checked = false;
	// 	      }
	// 	      break;
	// 	    case "file": 
	// 	    	if(frm_elements[i].options) {
	// 	    		frm_elements[i].options= false;
	// 	    	}
	// 	    default:
	// 	        break;
	//     } // /switch
	// 	} // for
}