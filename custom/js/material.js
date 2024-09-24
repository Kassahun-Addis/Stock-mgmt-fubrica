$(document).ready(function() {
    // Initialize the material table
    var manageMaterialTable = $('#manageMaterialTable').DataTable({
        'ajax': 'php_action/fetchMaterial.php', // Fetch data from this script
        'order': [] // No initial ordering
    });

    // Add material
    $('#submitMaterialForm').unbind('submit').bind('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var form = $(this); // Reference to the form
        $.ajax({
            url: form.attr('action'), // Get form action (URL)
            type: form.attr('method'), // Get form method (POST)
            data: form.serialize(), // Serialize the form data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.success === true) {
                    // Reload the table data without refreshing the page
                    manageMaterialTable.ajax.reload(null, false); 
                    // Hide the add material modal
                    $('#addMaterialModal').modal('hide'); 
                    // Reset the form for future use
                    form[0].reset(); 
                    // Show a success message
                    $('.remove-messages').html('<div class="alert alert-success">' + response.messages + '</div>');
                } else {
                    // Show an error message
                    $('.remove-messages').html('<div class="alert alert-danger">' + response.messages + '</div>');
                }
            }
        });
    });

   // Edit material function
window.editMaterial = function(materialId) {
    $.ajax({
        url: 'php_action/getMaterial.php', // Fetch material details
        type: 'post',
        data: { material_id: materialId }, // Send the material ID
        dataType: 'json', // Expect JSON response
        success: function(response) {
            // Populate the edit form with the fetched data
            $("#material_id").val(response.material_id);
            $("#editItemName").val(response.item_name);
            $("#editPurchaseQuantity").val(response.purchase_quantity);
            $("#editUnit").val(response.unit);
            $("#editAlertQuantity").val(response.alert_quantity);
            $("#editPurchasePrice").val(response.purchase_price);
            $("#editPurchaseDate").val(response.purchase_date);
            $("#editPurchasedBy").val(response.purchased_by);
            $("#editSupplier").val(response.supplier);

            // Show the edit modal
            $("#editMaterialModal").modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching material data:', error);
            alert('An error occurred while fetching the material data.');
        }
    });
};

// Function to handle form submission via AJAX
$("#editMaterialForm").on("submit", function(event) {
    event.preventDefault(); // Prevent form from submitting the default way

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: 'php_action/updateMaterial.php', // URL to handle the update
        type: 'post',
        data: formData, // Send the form data
        dataType: 'json', // Expect JSON response
        success: function(response) {
            if (response.success) {
                // Display the success message in the div
                $("#edit-material-messages").html('<div class="alert alert-success">' + response.message + '</div>');

                // Close the modal
                $("#editMaterialModal").modal('hide');

                // Refresh the page after a short delay (e.g., 2 seconds)
                setTimeout(function() {
                    location.reload();
                }, 2000); // 2-second delay
            } else {
                // Display the error message
                $("#edit-material-messages").html('<div class="alert alert-danger">' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating material data:', error);
            $("#edit-material-messages").html('<div class="alert alert-danger">An error occurred while updating the material.</div>');
        }
    });
});


    // Remove material function
    window.removeMaterial = function(materialId) {
        if (materialId) {
            console.log(materialId);
            $.ajax({
                url: 'php_action/removeMaterial.php',
                type: 'post',
                data: { material_id: materialId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Hide the modal and reload the table
                        $('#removeMaterialModal').modal('hide');
                        manageMaterialTable.ajax.reload(null, false);

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
                    console.error('Error deleting material:', error);
                    alert('An error occurred while deleting the material.');
                }
            });
        } else {
            alert('Error: Please refresh the page and try again.');
        }
    };
});
