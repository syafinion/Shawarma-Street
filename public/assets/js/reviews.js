function clearModalState() {
    $('body').removeClass('modal-open'); // This removes the class that disables scrolling
    $('.modal-backdrop').remove(); // This removes any backdrop elements from the DOM
    $('body').css('padding-right', ''); // Removes any padding that might have been added
    $('body').css('overflow', ''); // Reset any overflow styles set by the modal
}

$(document).ready(function() {
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.post('/submit-review', data, function(response) {
            // Hide the review modal and clear its state
            $('#reviewModal').modal('hide');
            setTimeout(function() { 
                clearModalState(); // Ensure the state is cleared
                // Show the success modal after a short delay
                $('#successModal').modal('show');
            }, 500); // Adjust the delay if needed to ensure animations complete
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert('Failed to submit review: ' + errorThrown);
        });
    });

    // Set the orderId when the review button is clicked
    $('.review-order').click(function() {
        var orderId = $(this).data('order-id');
        $('#orderId').val(orderId);
        $('#reviewModal').modal('show');
    });

    // Submit the cancel order request
$('.cancel-order').click(function() {
    var orderId = $(this).data('order-id');
    $.ajax({
        url: '/cancel-order',
        type: 'POST',
        data: {
            orderId: orderId,
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function(response) {
            alert('Order cancelled successfully!');
            location.reload(); // Refresh the page to reflect changes
        },
        error: function(xhr, status, error) {
            alert('Failed to cancel order: ' + xhr.responseText);
        }
    });
});

$('#logout-link').click(function(event) {
    event.preventDefault();
    
    // Create a form dynamically and submit it for logout
    var logoutForm = $('<form>', {
        'method': 'POST',
        'action': '/logout'  // Direct URL to your logout route
    }).append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content') // CSRF token, necessary for Laravel POST requests
    }));
    
    // Append the form to the body and submit it
    $('body').append(logoutForm);
    logoutForm.submit();
});



    // Clear modal state when any close button is clicked
    $('#reviewModal .close, #successModal .close, #successModal button').click(function() {
        // No need to call clearModalState() here since the 'hidden.bs.modal' event will handle it
    });

    // Clear modal state when modals are closed
    $('#reviewModal, #successModal').on('hidden.bs.modal', function() {
        clearModalState();
    });
});


$(document).ready(function() {
    $('.view').click(function(e) {
        e.preventDefault();
        var orderId = $(this).data('order-id'); // Make sure your view button has data-order-id attribute

        // Fetch order details via AJAX
        $.ajax({
            url: '/get-order-details/' + orderId,
            type: 'GET',
            success: function(response) {
                console.log(response);
                var orderData = response.order;
                var itemsData = response.items;
            
                // Now use orderData and itemsData to populate your modal
                $('#modalOrderId').text(orderData.order_id);
                $('#modalOrderDate').text(orderData.created_at);
                $('#modalOrderStatus').text(orderData.status);
                $('#modalOrderTotal').text('RM' + parseFloat(orderData.total_price).toFixed(2));
                $('#modalCustomerName').text(orderData.customer_name);
                $('#modalPhoneNumber').text(orderData.phone_number);
                $('#modalDeliveryAddress').text(orderData.delivery_address_line1 + ', ' + 
                                                 orderData.delivery_address_line2 + ', ' + 
                                                 orderData.delivery_city + ', ' + 
                                                 orderData.delivery_state + ', ' + 
                                                 orderData.delivery_zip_code + ', ' + 
                                                 orderData.delivery_country);
                
                $('#modalPaymentMethod').text(orderData.payment_method);
                $('#modalPaymentStatus').text(orderData.payment_status);
                $('#modalProcessedAt').text(orderData.processed_at);
            
                // Clear previous items
                $('#modalOrderItems').empty();
            
                $.each(itemsData, function(index, item) {
                    var itemPrice = parseFloat(item.price) || 0;
                    var itemSubtotal = parseFloat(item.subtotal) || 0;
                    
                    $('#modalOrderItems').append('<tr><td>' + (item.name || 'N/A') + '</td><td>' + 
                                                  (item.description || 'N/A') + '</td><td>' + 
                                                  (item.quantity || '0') + '</td><td>' + 
                                                  itemPrice.toFixed(2) + '</td><td>' + 
                                                  itemSubtotal.toFixed(2) + '</td></tr>');
                });
            
                // Show the modal
                $('#orderDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                alert('Error fetching order details: ' + xhr.responseText);
            }
        });
    });

    // Event handler for closing the modal
    $('#orderDetailsModal .close, #orderDetailsModal button').click(function() {
        $('#orderDetailsModal').modal('hide');
    });

    // Ensure the modal state is cleared when the modal has finished being hidden
    $('#orderDetailsModal').on('hidden.bs.modal', function() {
        clearModalState();
    });


});
