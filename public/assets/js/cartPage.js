$(document).ready(function() {
    // Fetch and display cart on page load
    fetchAndDisplayCart();

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle order type change and update the cart
    $('input[name="order_type"]').change(function() {
        const orderType = $(this).val();
        updateOrderType(orderType);
        updateCartTotal();
    });

    // Delegate click event for removing items from the cart
    $(document).on('click', '.product_remove a', function() {
        const itemId = $(this).data('item-id');
        removeFromCart(itemId);
    });

    // Delegate change event for updating item quantity
    $(document).on('change', '.product_quantity input[type="number"]', function() {
        const itemId = $(this).data('item-id');
        const newQuantity = parseInt($(this).val());
        updateItemQuantity(itemId, newQuantity);
    });
});


function updateOrderType(orderType) {
    $.ajax({
        url: '/cart/update-order-type',
        type: 'POST',
        data: {
            order_type: orderType
        },
        success: function(response) {
            console.log("Order type updated:", response);
        },
        error: function(error) {
            console.error('Error updating order type:', error);
        }
    });
}


function updateCartTotal() {
    const orderType = $('input[name="order_type"]:checked').val();
    const shippingCost = orderType === 'delivery' ? 5.00 : 0;
    const subtotal = parseFloat($('#cart_subtotal').text().replace('RM', ''));

    $('#cart_total').text(`RM${(subtotal + shippingCost).toFixed(2)}`);

    // Update the displayed shipping cost
    if (orderType === 'delivery') {
        $('.cart_subtotal.shipping').show();
        $('.shipping_cost').text(`Flat Rate: RM${shippingCost.toFixed(2)}`);
    } else {
        $('.cart_subtotal.shipping').hide(); // Hide the shipping cost line
    }
}

$(document).ready(function() {
    // Handle order type change
    $('input[name="order_type"]').change(function() {
        updateCartTotal();
    });

    fetchAndDisplayCart();
});



function fetchAndDisplayCart() {
    $.ajax({
        url: '/cart/show',
        type: 'GET',
        success: function(response) {
            if (response && response.cartItems) {
                updateServerCartUI(response.cartItems);
            }
        },
        error: function(error) {
            console.error('Error fetching cart:', error);
            alert('Failed to fetch cart items.');
        }
    });
}

function updateServerCartUI(cartItems) {
    let subtotal = 0;
    const cartBody = $('#cart_items');
    cartBody.empty(); // Clear existing cart items

    cartItems.forEach(item => {
        const total = item.price * item.quantity;
        subtotal += total;
        cartBody.append(`
            <tr>
                <td class="product_remove"><a href="javascript:void(0);" data-item-id="${item.item_id}"><i class="fa fa-trash-o"></i></a></td>
                <td class="product_thumb"><a href="#"><img src="${item.image}" alt="${item.name}"></a></td>
                <td class="product_name"><a href="#">${item.name}</a></td>
                <td class="product-price">RM${item.price}</td>
                <td class="product_quantity">
                    <input min="1" max="100" value="${item.quantity}" type="number" data-item-id="${item.item_id}">
                </td>
                <td class="product_total">RM${total.toFixed(2)}</td>
            </tr>
        `);
    });

    $('#cart_subtotal').text(`RM${subtotal.toFixed(2)}`);
    updateCartTotal(); // Recalculate total based on the initial order type
}

function removeFromCart(itemId) {
    $.ajax({
        url: '/cart/remove',
        type: 'POST',
        data: {
            item_id: itemId
        },
        success: function(response) {
            console.log("Removed from cart:", response);
            fetchAndDisplayCart(); // Refresh the cart after removing an item
        },
        error: function(error) {
            console.error('Error removing from cart:', error);
            alert('Failed to remove item from cart.');
        }
    });
}

function updateItemQuantity(itemId, newQuantity) {
    if (newQuantity < 0) {
        alert("Quantity cannot be less than zero.");
        return;
    }

    $.ajax({
        url: '/cart/update',
        type: 'POST',
        data: {
            item_id: itemId,
            quantity: newQuantity
        },
        success: function(response) {
            console.log("Quantity updated:", response);
            fetchAndDisplayCart(); // Refresh the cart after updating quantity
        },
        error: function(error) {
            console.error('Failed to update quantity:', error);
            alert('Failed to update item quantity.');
        }
    });
}

