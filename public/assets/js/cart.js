// Utility function to access the cart from local storage
function getCart() {
    const cart = localStorage.getItem('cart');
    const parsedCart = cart ? JSON.parse(cart) : [];
    console.log('Retrieved cart from storage:', parsedCart);
    return parsedCart;
}

function saveCart(cart) {
    console.log('Saving to local storage:', cart);
    localStorage.setItem('cart', JSON.stringify(cart));
}


function updateCart(response) {
    if (response && response.cartItems) {
        saveCart(response.cartItems); // Save the updated cart to local storage
        updateCartUI(); // Refresh the cart UI to reflect the new cart state
    } else {
        console.error('Unexpected response format or error:', response);
        if (response.error) {
            alert('Error: ' + response.error);
        }
    }
}



function updateCartUI() {
    const cart = getCart();
    console.log('Updating UI with cart:', cart);

    const miniCart = document.querySelector('.mini_cart');
    const itemCountElement = document.querySelector('.item_count');

    let html = '';
    let subtotal = 0;
    let itemCount = 0;

    cart.forEach(item => {
        console.log('Processing item:', item);
        itemCount += item.quantity;
        const itemPrice = parseFloat(item.price);
        subtotal += item.quantity * itemPrice;

        html += `
            <div class="cart_item">
                <div class="cart_img">
                    <a href="#"><img src="${item.image}" alt="${item.name}"></a>
                </div>
                <div class="cart_info">
                    <a href="#">${item.name}</a>
                    <div>
                    <button onclick="updateItemQuantity('${item.item_id.toString()}', -1)">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateItemQuantity('${item.item_id.toString()}', 1)">+</button>
                </div>
                    <p>RM${itemPrice.toFixed(2)}</p>
                </div>
                <div class="cart_remove">
                    <a href="javascript:void(0);" onclick="removeFromCart('${item.item_id}')"><i class="icon-x"></i></a>
                </div>
            </div>
        `;
    });

    miniCart.innerHTML = html + `
        <div class="mini_cart_table">
            <div class="cart_total">
                <span>Sub total:</span>
                <span class="price">RM${subtotal.toFixed(2)}</span>
            </div>
        </div>
        <div class="mini_cart_footer">
            <div class="cart_button">
                <a href="/cart"><i class="fa fa-shopping-cart"></i> View cart</a>
            </div>
            <div class="cart_button">
                <a href="/checkout"><i class="fa fa-sign-in"></i> Checkout</a>
            </div>
        </div>
    `;

    if (itemCountElement) {
        itemCountElement.textContent = itemCount;
        itemCountElement.style.display = itemCount > 0 ? 'block' : 'none';
    }

    console.log('Updated cart UI');

    // Optional: force reflow/repaint
    // miniCart.style.display = 'none';
    // setTimeout(() => {
    //     miniCart.style.display = 'block';
    // }, 10);
}



function addToCart(item) {
    const csrfToken = document.getElementById('csrfToken').value;
    $.ajax({
        url: '/cart/add',
        type: 'POST',
        data: {
            _token: csrfToken,
            item_id: item.id,
            name: item.name,
            price: item.price,
            image: item.image
        },
        success: function(response) {
            console.log("Added to cart:", response);
            updateCart(response);
        },
        error: function(error) {
            console.error('Error adding to cart:', error);
        }
    });
}

function removeFromCart(itemId) {
    const csrfToken = document.getElementById('csrfToken').value;
    console.log("Attempting to remove item with ID:", itemId);  // Debug: Log the item ID being passed
    $.ajax({
        url: '/cart/remove',
        type: 'POST',
        data: {
            _token: csrfToken,
            item_id: itemId
        },
        success: function(response) {
            console.log("Removed from cart:", response);
            updateCart(response);
        },
        error: function(error) {
            console.error('Error removing from cart:', error);
        }
    });
}

function updateItemQuantity(itemId, delta) {
    const cart = getCart();
    const item = cart.find(item => item.item_id.toString() === itemId.toString());
    if (!item) {
        console.error("Item not found in cart:", itemId);
        return;
    }

    const newQuantity = item.quantity + delta;
    if (newQuantity < 0) {
        console.warn("Cannot reduce quantity below zero.");
        return;
    }

    const csrfToken = document.getElementById('csrfToken').value;

    $.ajax({
        url: '/cart/update',
        type: 'POST',
        data: {
            _token: csrfToken,
            item_id: itemId,
            quantity: newQuantity  // Send the new total quantity
        },
        success: function(response) {
            console.log("Quantity updated:", response);
            updateCart(response);
        },
        error: function(error) {
            console.error('Failed to update quantity:', error);
        }
    });
}








// Call this function on page load and after any cart operations
document.addEventListener('DOMContentLoaded', updateCartUI);
