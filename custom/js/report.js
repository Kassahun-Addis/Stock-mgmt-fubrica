$(document).ready(function() {
    // Initialize datepickers
    $("#startDate").datepicker({
        dateFormat: 'mm/dd/yy' // Ensure the format matches your backend expectations
    });
    $("#endDate").datepicker({
        dateFormat: 'mm/dd/yy' // Ensure the format matches your backend expectations
    });

    // Handle order/product report form submission
    $("#getReportForm").unbind('submit').bind('submit', function() {
        var reportType = $("#reportType").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        // Clear previous errors
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $(".text-danger").remove();

        if (reportType === 'orders' || reportType === 'products') {
            // Date-based reports validation
            if (startDate === "" || endDate === "") {
                if (startDate === "") {
                    $("#startDate").closest('.form-group').addClass('has-error');
                    $("#startDate").after('<p class="text-danger">The Start Date is required</p>');
                }
                if (endDate === "") {
                    $("#endDate").closest('.form-group').addClass('has-error');
                    $("#endDate").after('<p class="text-danger">The End Date is required</p>');
                }
            } else {
                // Clear previous errors
                $(".form-group").removeClass('has-error').removeClass('has-success');
                $(".text-danger").remove();

                var form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'text',
                    success: function(response) {
                        // Open a new window and display the report content
                        var mywindow = window.open('', 'Report Slip', 'height=400,width=600');
                        mywindow.document.write('<html><head><title>Report Slip</title>');
                        mywindow.document.write('</head><body>');
                        mywindow.document.write(response); // Display the HTML report
                        mywindow.document.write('</body></html>');

                        mywindow.document.close(); // necessary for IE >= 10
                        mywindow.focus(); // necessary for IE >= 10

                        mywindow.print();
                        mywindow.close();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' ' + error);
                    }
                });
            }
        } else {
            alert('Please select a valid report type.');
        }

        return false;
    });

    // Handle credit report form submission
    $("#getCreditReportForm").unbind('submit').bind('submit', function() {
        var creditId = $("#creditId").val();

        // Clear previous errors
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $(".text-danger").remove();

        // Validate creditId
        if (creditId === "") {
            $("#creditId").closest('.form-group').addClass('has-error');
            $("#creditId").after('<p class="text-danger">The Credit ID is required for Credit Report</p>');
        } else {
            // Clear previous errors
            $(".form-group").removeClass('has-error').removeClass('has-success');
            $(".text-danger").remove();

            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'text',
                success: function(response) {
                    // Open a new window and display the credit report content
                    var mywindow = window.open('', 'Credit Report Slip', 'height=400,width=600');
                    mywindow.document.write('<html><head><title>Credit Report Slip</title>');
                    mywindow.document.write('</head><body>');
                    mywindow.document.write(response); // Display the HTML report
                    mywindow.document.write('</body></html>');

                    mywindow.document.close(); // necessary for IE >= 10
                    mywindow.focus(); // necessary for IE >= 10

                    mywindow.print();
                    mywindow.close();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' ' + error);
                }
            });
        }

        return false;
    });
});
