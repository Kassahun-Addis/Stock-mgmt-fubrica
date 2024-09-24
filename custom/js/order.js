let previousRateValue = {};

function capturePreviousRateValue(row) {
    previousRateValue[row] = Number($("#rate" + row).val());
}

function restrictRateChange(row) {
    let currentRateValue = Number($("#rate" + row).val());
    if (currentRateValue < previousRateValue[row]) {
        alert("Rate cannot be decreased!");
        $("#rate" + row).val(previousRateValue[row]);
    } else {
        previousRateValue[row] = currentRateValue;
        getTotal(row);
    }
}

function getTotal(row = null) {
    if (row) {
        var productId = $("#productName" + row).val();
        var quantity = Number($("#quantity" + row).val());

        $.ajax({
            url: 'php_action/getAvailableQuantity.php',
            type: 'post',
            data: { productId: productId },
            dataType: 'json',
            success: function(response) {
                var availableQuantity = Number(response.quantity);

                if (quantity > availableQuantity) {
                    // Set border color to red using inline CSS
                    $("#quantity" + row).css('border', '2px solid red');
                    
                    // Display the error message directly above the field
                    $("#quantity-error-" + row).html('Quantity not available! Available quantity is ' + availableQuantity);
                    
                    // Set quantity to available quantity
                    $("#quantity" + row).val(availableQuantity);
                    quantity = availableQuantity;
                } else {
                    // Remove the red border if the quantity is valid
                    $("#quantity" + row).css('border', '1px solid #ccc'); // Reset to default border
                    
                    // Clear the error message
                    $("#quantity-error-" + row).html('');
                }

                var rate = Number($("#rate" + row).val());
                var total = quantity * rate;
                total = total.toFixed(2);
                $("#total" + row).val(total);
                $("#totalValue" + row).val(total);

                subAmount();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching available quantity:", status, error);
                // Optional: Show a generic error message if fetching the available quantity fails
                $("#quantity-error-" + row).html('Error fetching available quantity.');
            }
        });
    } else {
        console.error('No row!! Please refresh the page.');
    }
}



// function subAmount() {
//     var tableProductLength = $("#productTable tbody tr").length;
//     var totalSubAmount = 0;
//     for (var x = 0; x < tableProductLength; x++) {
//         var tr = $("#productTable tbody tr")[x];
//         var count = $(tr).attr('id').substring(3);

//         totalSubAmount += Number($("#total" + count).val());
//     }
//     totalSubAmount = totalSubAmount.toFixed(2);
//     $("#subTotal").val(totalSubAmount);
//     $("#subTotalValue").val(totalSubAmount);

//     var vat = (Number(totalSubAmount) / 100) * 10;
//     vat = vat.toFixed(2);
//     $("#vat").val(vat);
//     $("#vatValue").val(vat);

//     var totalAmount = (Number(totalSubAmount) + Number(vat)).toFixed(2);
//     $("#totalAmount").val(totalAmount);
//     $("#totalAmountValue").val(totalAmount);

//     var discount = $("#discount").val();
//     var grandTotal = discount ? (totalAmount - Number(discount)).toFixed(2) : totalAmount;
//     $("#grandTotal").val(grandTotal);
//     $("#grandTotalValue").val(grandTotal);

//     var paidAmount = $("#paid").val();
//     var dueAmount = paidAmount ? (grandTotal - Number(paidAmount)).toFixed(2) : grandTotal;
//     $("#due").val(dueAmount);
//     $("#dueValue").val(dueAmount);
// }




var manageOrderTable;

$(document).ready(function() {

	var divRequest = $(".div-request").text();

	// top nav bar 
	$("#navOrder").addClass('active');

	if(divRequest == 'add')  {
		// add order	
		// top nav child bar 
		$('#topNavAddOrder').addClass('active');	

		// order date picker
		$("#orderDate").datepicker();

		// create order form function
		$("#createOrderForm").unbind('submit').bind('submit', function() {
			var form = $(this);

			$('.form-group').removeClass('has-error').removeClass('has-success');
			$('.text-danger').remove();
				
			var orderDate = $("#orderDate").val();
			var clientName = $("#clientName").val();
			var clientContact = $("#clientContact").val();
			var clientContact = $("#fsNumber").val();
			var paid = $("#paid").val();
			var discount = $("#discount").val();
			var paymentType = $("#paymentType").val();
			var paymentStatus = $("#paymentStatus").val();		

			// form validation 
			if(orderDate == "") {
				$("#orderDate").after('<p class="text-danger"> The Order Date field is required </p>');
				$('#orderDate').closest('.form-group').addClass('has-error');
			} else {
				$('#orderDate').closest('.form-group').addClass('has-success');
			} // /else

			if(clientName == "") {
				$("#clientName").after('<p class="text-danger"> The Client Name field is required </p>');
				$('#clientName').closest('.form-group').addClass('has-error');
			} else {
				$('#clientName').closest('.form-group').addClass('has-success');
			} // /else

			if(clientContact == "") {
				$("#clientContact").after('<p class="text-danger"> The Contact field is required </p>');
				$('#clientContact').closest('.form-group').addClass('has-error');
			} else {
				$('#clientContact').closest('.form-group').addClass('has-success');
			} // /else

			if(paid == "") {
				$("#paid").after('<p class="text-danger"> The Paid field is required </p>');
				$('#paid').closest('.form-group').addClass('has-error');
			} else {
				$('#paid').closest('.form-group').addClass('has-success');
			} // /else

			if(discount == "") {
				$("#discount").after('<p class="text-danger"> The Discount field is required </p>');
				$('#discount').closest('.form-group').addClass('has-error');
			} else {
				$('#discount').closest('.form-group').addClass('has-success');
			} // /else

			if(paymentType == "") {
				$("#paymentType").after('<p class="text-danger"> The Payment Type field is required </p>');
				$('#paymentType').closest('.form-group').addClass('has-error');
			} else {
				$('#paymentType').closest('.form-group').addClass('has-success');
			} // /else

			if(paymentStatus == "") {
				$("#paymentStatus").after('<p class="text-danger"> The Payment Status field is required </p>');
				$('#paymentStatus').closest('.form-group').addClass('has-error');
			} else {
				$('#paymentStatus').closest('.form-group').addClass('has-success');
			} // /else


			// array validation
			var productName = document.getElementsByName('productName[]');				
			var validateProduct;
			for (var x = 0; x < productName.length; x++) {       			
				var productNameId = productName[x].id;	    	
		    if(productName[x].value == ''){	    		    	
		    	$("#"+productNameId+"").after('<p class="text-danger"> Product Name Field is required!! </p>');
		    	$("#"+productNameId+"").closest('.form-group').addClass('has-error');	    		    	    	
	      } else {      	
		    	$("#"+productNameId+"").closest('.form-group').addClass('has-success');	    		    		    	
	      }          
	   	} // for

	   	for (var x = 0; x < productName.length; x++) {       						
		    if(productName[x].value){	    		    		    	
		    	validateProduct = true;
	      } else {      	
		    	validateProduct = false;
	      }          
	   	} // for       		   	
	   	
	   	var quantity = document.getElementsByName('quantity[]');		   	
	   	var validateQuantity;
	   	for (var x = 0; x < quantity.length; x++) {       
	 			var quantityId = quantity[x].id;
		    if(quantity[x].value == ''){	    	
		    	$("#"+quantityId+"").after('<p class="text-danger"> Product Name Field is required!! </p>');
		    	$("#"+quantityId+"").closest('.form-group').addClass('has-error');	    		    		    	
	      } else {      	
		    	$("#"+quantityId+"").closest('.form-group').addClass('has-success');	    		    		    		    	
	      } 
	   	}  // for

	   	for (var x = 0; x < quantity.length; x++) {       						
		    if(quantity[x].value){	    		    		    	
		    	validateQuantity = true;
	      } else {      	
		    	validateQuantity = false;
	      }          
	   	} // for       	
	   	

			if(orderDate && clientName && clientContact && paid && discount && paymentType && paymentStatus) {
				if(validateProduct == true && validateQuantity == true) {
					// create order button
					// $("#createOrderBtn").button('loading');

					$.ajax({
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),					
						dataType: 'json',
						success:function(response) {
							console.log(response);
							// reset button
							$("#createOrderBtn").button('reset');
							
							$(".text-danger").remove();
							$('.form-group').removeClass('has-error').removeClass('has-success');

							if(response.success == true) {
								
								// create order button
								$(".success-messages").html('<div class="alert alert-success">'+
	            	'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	            	'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
	            	' <br /> <br /> <a type="button" onclick="printOrder('+response.order_id+')" class="btn btn-primary"> <i class="glyphicon glyphicon-print"></i> Print </a>'+
	            	'<a href="orders.php?o=add" class="btn btn-default" style="margin-left:10px;"> <i class="glyphicon glyphicon-plus-sign"></i> Add New Order </a>'+
	            	
	   		       '</div>');
								
							$("html, body, div.panel, div.pane-body").animate({scrollTop: '0px'}, 100);

							// disabled te modal footer button
							$(".submitButtonFooter").addClass('div-hide');
							// remove the product row
							$(".removeProductRowBtn").addClass('div-hide');
								
							} else {
								alert(response.messages);								
							}
						} // /response
					}); // /ajax
				} // if array validate is true
			} // /if field validate is true
			

			return false;
		}); // /create order form function	
	
	} 
	else if(divRequest == 'manord') {
		// top nav child bar 
		$('#topNavManageOrder').addClass('active');

		manageOrderTable = $("#manageOrderTable").DataTable({
			'ajax': 'php_action/fetchOrder.php',
			'order': []
		});		
					
	} 
	else if(divRequest == 'editOrd') {
		$("#orderDate").datepicker();

		// edit order form function
		$("#editOrderForm").unbind('submit').bind('submit', function() {
			// alert('ok');
			var form = $(this);

			$('.form-group').removeClass('has-error').removeClass('has-success');
			$('.text-danger').remove();
				
			var orderDate = $("#orderDate").val();
			var clientName = $("#clientName").val();
			var clientContact = $("#clientContact").val();
			var paid = $("#paid").val();
			var discount = $("#discount").val();
			var paymentType = $("#paymentType").val();
			var paymentStatus = $("#paymentStatus").val();		
			var fsNumber = $("#fsNumber").val();		

			// form validation 
			if(orderDate == "") {
				$("#orderDate").after('<p class="text-danger"> The Order Date field is required </p>');
				$('#orderDate').closest('.form-group').addClass('has-error');
			} else {
				$('#orderDate').closest('.form-group').addClass('has-success');
			} // /else

			if(clientName == "") {
				$("#clientName").after('<p class="text-danger"> The Client Name field is required </p>');
				$('#clientName').closest('.form-group').addClass('has-error');
			} else {
				$('#clientName').closest('.form-group').addClass('has-success');
			} // /else

			if(clientContact == "") {
				$("#clientContact").after('<p class="text-danger"> The Contact field is required </p>');
				$('#clientContact').closest('.form-group').addClass('has-error');
			} else {
				$('#clientContact').closest('.form-group').addClass('has-success');
			} // /else

			if(paid == "") {
				$("#paid").after('<p class="text-danger"> The Paid field is required </p>');
				$('#paid').closest('.form-group').addClass('has-error');
			} else {
				$('#paid').closest('.form-group').addClass('has-success');
			} // /else

			if(discount == "") {
				$("#discount").after('<p class="text-danger"> The Discount field is required </p>');
				$('#discount').closest('.form-group').addClass('has-error');
			} else {
				$('#discount').closest('.form-group').addClass('has-success');
			} // /else

			if(paymentType == "") {
				$("#paymentType").after('<p class="text-danger"> The Payment Type field is required </p>');
				$('#paymentType').closest('.form-group').addClass('has-error');
			} else {
				$('#paymentType').closest('.form-group').addClass('has-success');
			} // /else

			if(paymentStatus == "") {
				$("#paymentStatus").after('<p class="text-danger"> The Payment Status field is required </p>');
				$('#paymentStatus').closest('.form-group').addClass('has-error');
			} else {
				$('#paymentStatus').closest('.form-group').addClass('has-success');
			} // /else


			// array validation
			var productName = document.getElementsByName('productName[]');				
			var validateProduct;
			for (var x = 0; x < productName.length; x++) {       			
				var productNameId = productName[x].id;	    	
		    if(productName[x].value == ''){	    		    	
		    	$("#"+productNameId+"").after('<p class="text-danger"> Product Name Field is required!! </p>');
		    	$("#"+productNameId+"").closest('.form-group').addClass('has-error');	    		    	    	
	      } else {      	
		    	$("#"+productNameId+"").closest('.form-group').addClass('has-success');	    		    		    	
	      }          
	   	} // for

	   	for (var x = 0; x < productName.length; x++) {       						
		    if(productName[x].value){	    		    		    	
		    	validateProduct = true;
	      } else {      	
		    	validateProduct = false;
	      }          
	   	} // for       		   	
	   	
	   	var quantity = document.getElementsByName('quantity[]');		   	
	   	var validateQuantity;
	   	for (var x = 0; x < quantity.length; x++) {       
	 			var quantityId = quantity[x].id;
		    if(quantity[x].value == ''){	    	
		    	$("#"+quantityId+"").after('<p class="text-danger"> Product Name Field is required!! </p>');
		    	$("#"+quantityId+"").closest('.form-group').addClass('has-error');	    		    		    	
	      } else {      	
		    	$("#"+quantityId+"").closest('.form-group').addClass('has-success');	    		    		    		    	
	      } 
	   	}  // for

	   	for (var x = 0; x < quantity.length; x++) {       						
		    if(quantity[x].value){	    		    		    	
		    	validateQuantity = true;
	      } else {      	
		    	validateQuantity = false;
	      }          
	   	} // for       	
	   	

			if(orderDate && clientName && clientContact && paid && discount && paymentType && paymentStatus) {
				if(validateProduct == true && validateQuantity == true) {
					// create order button
					// $("#createOrderBtn").button('loading');

					$.ajax({
						url : form.attr('action'),
						type: form.attr('method'),
						data: form.serialize(),					
						dataType: 'json',
						success:function(response) {
							console.log(response);
							// reset button
							$("#editOrderBtn").button('reset');
							
							$(".text-danger").remove();
							$('.form-group').removeClass('has-error').removeClass('has-success');

							if(response.success == true) {
								
								// create order button
								$(".success-messages").html('<div class="alert alert-success">'+
	            	'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	            	'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +	            		            		            	
	   		       '</div>');
								
							$("html, body, div.panel, div.pane-body").animate({scrollTop: '0px'}, 100);

							// disabled te modal footer button
							$(".editButtonFooter").addClass('div-hide');
							// remove the product row
							$(".removeProductRowBtn").addClass('div-hide');
								
							} else {
								alert(response.messages);								
							}
						} // /response
					}); // /ajax
				} // if array validate is true
			} // /if field validate is true
			

			return false;
		}); // /edit order form function	
	} 	

}); // /documernt


function printOrder(orderId = null) {
  if(orderId) {    
    window.location.href = 'php_action/printOrder.php?orderId=' + orderId;
  } // /if orderId
} // /print order function
function addRow() {
	$("#addRowBtn").button("loading");

	var tableLength = $("#productTable tbody tr").length;

	var tableRow;
	var arrayNumber;
	var count;

	if(tableLength > 0) {		
		tableRow = $("#productTable tbody tr:last").attr('id');
		arrayNumber = $("#productTable tbody tr:last").attr('class');
		count = tableRow.substring(3);	
		count = Number(count) + 1;
		arrayNumber = Number(arrayNumber) + 1;					
	} else {
		// no table row
		count = 1;
		arrayNumber = 0;
	}

	$.ajax({
		url: 'php_action/fetchProductData.php',
		type: 'post',
		dataType: 'json',
		success:function(response) {
			$("#addRowBtn").button("reset");			

			var tr = '<tr id="row'+count+'" class="'+arrayNumber+'">'+			  				
				'<td>'+
					'<div class="form-group">'+

					'<select class="form-control" name="productName[]" id="productName'+count+'" onchange="getProductData('+count+')" >'+
						'<option value="">~~SELECT~~</option>';
						// console.log(response);
						$.each(response, function(index, value) {
							tr += '<option value="'+value[0]+'">'+value[1]+'</option>';							
						});
													
					tr += '</select>'+
					'</div>'+
				'</td>'+
				'<td style="padding-left:20px;"">'+
					'<input type="text" name="serialNo[]" id="serialNo'+count+'" autocomplete="off" disabled="true" class="form-control" />'+
					'<input type="hidden" name="serialNoValue[]" id="serialNoValue'+count+'" autocomplete="off" class="form-control" />'+
				'</td style="padding-left:20px;">'+
				
				'<td style="padding-left:20px;"">'+
					'<input type="text" name="rate[]" id="rate'+count+'" autocomplete="off" disabled="true" class="form-control" />'+
					'<input type="hidden" name="rateValue[]" id="rateValue'+count+'" autocomplete="off" class="form-control" />'+
				'</td style="padding-left:20px;">'+

				'<td style="padding-left:20px;"">'+
					'<input type="text" name="purchase[]" id="purchase'+count+'" autocomplete="off" disabled="true" class="form-control" />'+
					'<input type="hidden" name="purchaseValue[]" id="purchaseValue'+count+'" autocomplete="off" class="form-control" />'+
				'</td style="padding-left:20px;">'+

				'<td style="padding-left:20px;">'+
					'<div class="form-group">'+
					'<input type="number" name="quantity[]" id="quantity'+count+'" onkeyup="getTotal('+count+')" onChange="getTotal('+count+')" autocomplete="off" class="form-control" min="1" />'+
					'</div>'+
				'</td>'+
				'<td style="padding-left:20px;">'+
					'<input type="text" name="total[]" id="total'+count+'" autocomplete="off" class="form-control" disabled="true" />'+
					'<input type="hidden" name="totalValue[]" id="totalValue'+count+'" autocomplete="off" class="form-control" />'+
				'</td>'+
				'<td>'+
					'<button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow('+count+')"><i class="glyphicon glyphicon-trash"></i></button>'+
				'</td>'+
			'</tr>';
			if(tableLength > 0) {							
				$("#productTable tbody tr:last").after(tr);
			} else {				
				$("#productTable tbody").append(tr);
			}		

		} // /success
	});	// get the product data

} // /add row

function removeProductRow(row = null) {
	if(row) {
		$("#row"+row).remove();


		subAmount();
	} else {
		alert('error! Refresh the page again');
	}
}

// select on product data
function getProductData(row = null) {
    if (row) {
        var productId = $("#productName" + row).val();

        if (productId == "") {
            $("#rate" + row).val("");
            $("#serialNo" + row).val("");
            $("#purchase" + row).val("");
            $("#quantity" + row).val("");
            $("#total" + row).val("");
        } else {
            $.ajax({
                url: 'php_action/fetchSelectedProduct.php',
                type: 'post',
                data: { productId: productId },
                dataType: 'json',
                success: function(response) {
                    // setting the rate value into the rate input field
                    $("#serialNo" + row).val(response.serial_no);
                    $("#serialNoValue" + row).val(response.serial_no);

                    $("#purchase" + row).val(response.purchase);
                    $("#purchaseValue" + row).val(response.purchase);

                    $("#rate" + row).val(response.rate);
                    $("#rateValue" + row).val(response.rate);

                    // Set the available quantity in a hidden field
                    $("#availableQuantity" + row).val(response.quantity);  // assuming response contains available quantity

                    $("#quantity" + row).val(1);

                    var total = Number(response.rate) * 1;
                    total = total.toFixed(2);
                    $("#total" + row).val(total);
                    $("#totalValue" + row).val(total);

                    subAmount();
                } // /success
            }); // /ajax function to fetch the product data
        }
    } else {
        alert('no row! please refresh the page');
    }
}


// table total
function getTotal(row = null) {
    if (row) {
        var productId = $("#productName" + row).val();
        var quantity = Number($("#quantity" + row).val());

        $.ajax({
            url: 'php_action/getAvailableQuantity.php',
            type: 'post',
            data: { productId: productId },
            dataType: 'json',
            success: function(response) {
                var availableQuantity = Number(response.quantity);

                if (quantity > availableQuantity) {
                    // Set border color to red using inline CSS
                    $("#quantity" + row).css('border', '2px solid red');
                    
                    // Display the error message directly above the field
                    $("#quantity-error-" + row).html('Quantity not available! Max available is ' + availableQuantity);
                    
                    // Clear the quantity field
                    $("#quantity" + row).val('');
                } else {
                    // Remove the red border if the quantity is valid
                    $("#quantity" + row).css('border', '1px solid #ccc'); // Reset to default border
                    
                    // Clear the error message
                    $("#quantity-error-" + row).html('');
                }

                var rate = Number($("#rate" + row).val());
                var total = quantity * rate;
                total = total.toFixed(2);
                $("#total" + row).val(total);
                $("#totalValue" + row).val(total);

                subAmount();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching available quantity:", status, error);
                // Optional: Show a generic error message if fetching the available quantity fails
                $("#quantity-error-" + row).html('Error fetching available quantity.');
            }
        });
    } else {
        console.error('No row!! Please refresh the page.');
    }
}



function subAmount() {
    var tableProductLength = $("#productTable tbody tr").length;
    var totalSubAmount = 0;

    // Calculate total sub amount
    for (var x = 0; x < tableProductLength; x++) {
        var tr = $("#productTable tbody tr")[x];
        var count = $(tr).attr('id').substring(3);
        totalSubAmount += Number($("#total" + count).val());
    }

	totalSubAmount = totalSubAmount.toFixed(2);

	// sub total
	$("#subTotal").val(totalSubAmount);
	$("#subTotalValue").val(totalSubAmount);

	// vat
	var vat = (Number($("#subTotal").val())/100) * 15;
	vat = vat.toFixed(2);
	$("#vat").val(vat);
	$("#vatValue").val(vat);

	// // total amount
	// var totalAmount = (Number($("#subTotal").val()) + Number($("#vat").val()));
	// totalAmount = totalAmount.toFixed(2);
	// $("#totalAmount").val(totalAmount);
	// $("#totalAmountValue").val(totalAmount);

	// Calculate total amount
	var totalAmount = (Number(totalSubAmount) + Number(vat)).toFixed(2);
	$("#totalAmount").val(totalAmount);
	$("#totalAmountValue").val(totalAmount);
	


	// Calculate withhold if the checkbox is checked
    var withholdAmount = 0;
    if ($("#applyWithhold").is(':checked')) {
        withholdAmount = recalculateWithhold(); // Ensure this function returns the withhold amount
    }

// Retrieve discount value from input
var discount = Number($("#discount").val()) || 0; // Default to 0 if not a number

	// Calculate grand total considering withhold and discount
var grandTotal = (Number(totalAmount) - discount - withholdAmount).toFixed(2);
$("#grandTotal").val(grandTotal);
$("#grandTotalValue").val(grandTotal);

// Calculate due amount
var paidAmount = Number($("#paid").val()) || 0;
var dueAmount = (Number(grandTotal) - paidAmount).toFixed(2);
$("#due").val(dueAmount);
$("#dueValue").val(dueAmount);
}

// Function to recalculate withhold
function recalculateWithhold() {
var totalSubAmount = Number($("#subTotal").val());
var withhold = totalSubAmount > 3000 ? (totalSubAmount * 0.02).toFixed(2) : 0; // 2% if greater than 3000
$("#withhold").val(withhold);
$("#withholdValue").val(withhold);
return Number(withhold); // Return the withhold amount
}

// Initialize calculations on document ready
$(document).ready(function() {
// Handle checkbox change for withhold
$("#applyWithhold").change(function() {
	if ($(this).is(':checked')) {
		recalculateWithhold();
	} else {
		$("#withhold").val('0');
		$("#withholdValue").val('0');
	}
	subAmount(); // Recalculate amounts based on current state
});

// Handle discount input change
$("#discount").on('input', function() {
	subAmount(); // Recalculate whenever the discount changes
});

// Initial call to set values based on the current checkbox state on page load
if ($("#applyWithhold").is(':checked')) {
	recalculateWithhold();
} else {
	$("#withhold").val('0');
}

// Initial calculation
subAmount();
});
	// var discount = $("#discount").val();
	// if(discount) {
	// 	var grandTotal = Number($("#totalAmount").val()) - Number(discount);
	// 	grandTotal = grandTotal.toFixed(2);
	// 	$("#grandTotal").val(grandTotal);
	// 	$("#grandTotalValue").val(grandTotal);
	// } else {
	// 	$("#grandTotal").val(totalAmount);
	// 	$("#grandTotalValue").val(totalAmount);
	// } // /else discount	

	var paidAmount = $("#paid").val();
	if(paidAmount) {
		paidAmount =  Number($("#grandTotal").val()) - Number(paidAmount);
		paidAmount = paidAmount.toFixed(2);
		$("#due").val(paidAmount);
		$("#dueValue").val(paidAmount);
	} else {	
		$("#due").val($("#grandTotal").val());
		$("#dueValue").val($("#grandTotal").val());
	} // else

 // /sub total amount

// function discountFunc() {
// 	var discount = $("#discount").val();
//  	var totalAmount = Number($("#totalAmount").val());
//  	totalAmount = totalAmount.toFixed(2);

//  	var grandTotal;
//  	if(totalAmount) { 	
//  		grandTotal = Number($("#totalAmount").val()) - Number($("#discount").val());
//  		grandTotal = grandTotal.toFixed(2);

//  		$("#grandTotal").val(grandTotal);
//  		$("#grandTotalValue").val(grandTotal);
//  	} else {
//  	}

//  	var paid = $("#paid").val();

//  	var dueAmount; 	
//  	if(paid) {
//  		dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
//  		dueAmount = dueAmount.toFixed(2);

//  		$("#due").val(dueAmount);
//  		$("#dueValue").val(dueAmount);
//  	} else {
//  		$("#due").val($("#grandTotal").val());
//  		$("#dueValue").val($("#grandTotal").val());
//  	}

// } // /discount function

function paidAmount() {
	var grandTotal = $("#grandTotal").val();

	if(grandTotal) {
		var dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
		dueAmount = dueAmount.toFixed(2);
		$("#due").val(dueAmount);
		$("#dueValue").val(dueAmount);
	} // /if
} // /paid amoutn function


function resetOrderForm() {
	// reset the input field
	$("#createOrderForm")[0].reset();
	// remove remove text danger
	$(".text-danger").remove();
	// remove form group error 
	$(".form-group").removeClass('has-success').removeClass('has-error');
} // /reset order form


// remove order from server
function removeOrder(orderId = null) {
	if(orderId) {
		$("#removeOrderBtn").unbind('click').bind('click', function() {
			$("#removeOrderBtn").button('loading');

			$.ajax({
				url: 'php_action/removeOrder.php',
				type: 'post',
				data: {orderId : orderId},
				dataType: 'json',
				success:function(response) {
					$("#removeOrderBtn").button('reset');

					if(response.success == true) {

						manageOrderTable.ajax.reload(null, false);
						// hide modal
						$("#removeOrderModal").modal('hide');
						// success messages
						$("#success-messages").html('<div class="alert alert-success">'+
	            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
	          '</div>');

						// remove the mesages
	          $(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert	          

					} else {
						// error messages
						$(".removeOrderMessages").html('<div class="alert alert-warning">'+
	            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
	            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
	          '</div>');

						// remove the mesages
	          $(".alert-success").delay(500).show(10, function() {
							$(this).delay(3000).hide(10, function() {
								$(this).remove();
							});
						}); // /.alert	          
					} // /else

				} // /success
			});  // /ajax function to remove the order

		}); // /remove order button clicked
		

	} else {
		alert('error! refresh the page again');
	}
}
// /remove order from server

// Payment ORDER
function paymentOrder(orderId = null) {
	if(orderId) {

		$("#orderDate").datepicker();

		$.ajax({
			url: 'php_action/fetchOrderData.php',
			type: 'post',
			data: {orderId: orderId},
			dataType: 'json',
			success:function(response) {				

				// due 
				$("#due").val(response.order[10]);				

				// pay amount 
				$("#payAmount").val(response.order[10]);

				var paidAmount = response.order[9] 
				var dueAmount = response.order[10];							
				var grandTotal = response.order[8];

				// update payment
				$("#updatePaymentOrderBtn").unbind('click').bind('click', function() {
					var payAmount = $("#payAmount").val();
					var paymentType = $("#paymentType").val();
					var paymentStatus = $("#paymentStatus").val();

					if(payAmount == "") {
						$("#payAmount").after('<p class="text-danger">The Pay Amount field is required</p>');
						$("#payAmount").closest('.form-group').addClass('has-error');
					} else {
						$("#payAmount").closest('.form-group').addClass('has-success');
					}

					if(paymentType == "") {
						$("#paymentType").after('<p class="text-danger">The Pay Amount field is required</p>');
						$("#paymentType").closest('.form-group').addClass('has-error');
					} else {
						$("#paymentType").closest('.form-group').addClass('has-success');
					}

					if(paymentStatus == "") {
						$("#paymentStatus").after('<p class="text-danger">The Pay Amount field is required</p>');
						$("#paymentStatus").closest('.form-group').addClass('has-error');
					} else {
						$("#paymentStatus").closest('.form-group').addClass('has-success');
					}

					if(payAmount && paymentType && paymentStatus) {
						$("#updatePaymentOrderBtn").button('loading');
						$.ajax({
							url: 'php_action/editPayment.php',
							type: 'post',
							data: {
								orderId: orderId,
								payAmount: payAmount,
								paymentType: paymentType,
								paymentStatus: paymentStatus,
								paidAmount: paidAmount,
								grandTotal: grandTotal
							},
							dataType: 'json',
							success:function(response) {
								$("#updatePaymentOrderBtn").button('loading');

								// remove error
								$('.text-danger').remove();
								$('.form-group').removeClass('has-error').removeClass('has-success');

								$("#paymentOrderModal").modal('hide');

								$("#success-messages").html('<div class="alert alert-success">'+
			            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
			            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
			          '</div>');

								// remove the mesages
			          $(".alert-success").delay(500).show(10, function() {
									$(this).delay(3000).hide(10, function() {
										$(this).remove();
									});
								}); // /.alert	

			          // refresh the manage order table
								manageOrderTable.ajax.reload(null, false);

							} //

						});
					} // /if
						
					return false;
				}); // /update payment			

			} // /success
		}); // fetch order data
	} else {
		alert('Error ! Refresh the page again');
	}
}


document.getElementById('paymentType').addEventListener('change', function() {
    var paymentType = this.value;
    var bankDetails = document.getElementById('bankDetails');
    var transactionDetails = document.getElementById('transactionDetails');

    // Reset fields visibility
    bankDetails.style.display = 'none';
    transactionDetails.style.display = 'none';

    if (paymentType === 'bank') {
        bankDetails.style.display = 'block';
        transactionDetails.style.display = 'block';
    } else if (paymentType === 'telebirr') {
        transactionDetails.style.display = 'block';
    }
});
let rowNumber = 1; // Initialize row number

function addProductRow() {
    rowNumber++; // Increment row number

    // Create a new row
    let newRow = `
        <tr id="row${rowNumber}">
            <td style="margin-left:20px;">
                <div class="form-group">
                    <select class="form-control" name="productName[]" id="productName${rowNumber}" onchange="getProductData(${rowNumber})">
                        <option value="">~~SELECT~~</option>
                        ${productOptions}  <!-- Use the preloaded product options -->
                    </select>
                </div>
            </td>
            <td style="padding-left:20px;">
                <input type="text" name="serialNo[]" id="serialNo${rowNumber}" autocomplete="off" disabled="true" class="form-control" />
                <input type="hidden" name="serialNoValue[]" id="serialNoValue${rowNumber}" autocomplete="off" class="form-control" />
            </td>
            <td style="padding-left:20px;">
                <input type="text" name="rate[]" id="rate${rowNumber}" autocomplete="off" onkeyup="restrictRateChange(${rowNumber})" class="form-control" />
                <input type="hidden" name="rateValue[]" id="rateValue${rowNumber}" autocomplete="off" class="form-control" />
                <!-- Hidden field for default rate -->
                <input type="hidden" id="defaultRate${rowNumber}" />
            </td>
            <td style="padding-left:20px;">
                <input type="text" name="purchase[]" id="purchase${rowNumber}" autocomplete="off" disabled="true" class="form-control" />
                <input type="hidden" name="purchaseValue[]" id="purchaseValue${rowNumber}" autocomplete="off" class="form-control" />
            </td>
            <td style="padding-left:20px;">
                <div class="form-group">
                    <input type="number" name="quantity[]" id="quantity${rowNumber}" onkeyup="getTotal(${rowNumber})" onChange="getTotal(${rowNumber})" autocomplete="off" class="form-control" min="1" />
                </div>
              <span id="quantity-error-${rowNumber}" style="color: red; font-weight: bold;"></span>
            </td>
            <td style="padding-left:20px;">
                <input type="text" name="total[]" id="total${rowNumber}" autocomplete="off" class="form-control" disabled="true" />
                <input type="hidden" name="totalValue[]" id="totalValue${rowNumber}" autocomplete="off" class="form-control" />
            </td>
            <td>
                <button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow(${rowNumber})"><i class="glyphicon glyphicon-trash"></i></button>
            </td>
        </tr>
    `;

    // Append the new row to the table
    document.querySelector('#productTable tbody').insertAdjacentHTML('beforeend', newRow);
}

function removeProductRow(rowId) {
    // Remove the row
    document.getElementById(`row${rowId}`).remove();
}
