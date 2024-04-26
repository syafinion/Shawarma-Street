// Utility function to access the cart from local storage
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function updateCart(response) {
    if (response && response.cartItems) {
        saveCart(response.cartItems);
        updateCartUI();
    } else {
        // If the response does not include 'cartItems', log it out and check what's coming back from the server
        console.log('Unexpected response format:', response);
    }
}




function updateCartUI() {
    const cart = getCart();  // Get the current state of the cart from local storage
    console.log('Cart:', cart);  // Debugging line to see what's in the cart

    const miniCart = document.querySelector('.mini_cart');  // The container for the mini cart in the HTML
    const itemCountElement = document.querySelector('.item_count');  // Element to display the total number of items

    let html = '';
    let subtotal = 0;
    let itemCount = 0;

    // Iterate over each item in the cart to build the HTML
    cart.forEach(item => {
        itemCount += item.quantity;  // Increment the total item count
        subtotal += item.quantity * item.price;  // Calculate subtotal

        // Create HTML for each item in the cart
        html += `
            <div class="cart_item">
                <div class="cart_img">
                    <a href="#"><img src="${item.image}" alt="${item.name}"></a>
                </div>
                <div class="cart_info">
                    <a href="#">${item.name}</a>
                    <div>
                        <button onclick="updateItemQuantity('${item.id}', -1)">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateItemQuantity('${item.id}', 1)">+</button>
                    </div>
                    <p>$${item.price.toFixed(2)}</p>
                </div>
                <div class="cart_remove">
                    <a href="javascript:void(0);" onclick="removeFromCart('${item.id}')"><i class="icon-x"></i></a>
                </div>
            </div>
        `;
    });

    // Update the inner HTML of the mini cart with the items and additional cart info
    miniCart.innerHTML = html + `
        <div class="mini_cart_table">
            <div class="cart_total">
                <span>Sub total:</span>
                <span class="price">$${subtotal.toFixed(2)}</span>  // Display the subtotal
            </div>
        </div>
        <div class="mini_cart_footer">
            <div class="cart_button">
                <a href="cart.html"><i class="fa fa-shopping-cart"></i> View cart</a>
            </div>
            <div class="cart_button">
                <a href="checkout.html"><i class="fa fa-sign-in"></i> Checkout</a>
            </div>
        </div>
    `;

    // Update the display of the total item count
    if (itemCountElement) {
        itemCountElement.textContent = itemCount;  // Set the text content to the total item count
        itemCountElement.style.display = itemCount > 0 ? 'block' : 'none';  // Only display the element if there are items
    }

    console.log('Updated cart UI');  // Debugging line to confirm the UI has been updated
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
    $.ajax({
        url: '/cart/remove',
        type: 'POST',
        data: {
            item_id: itemId,
            _token: csrfToken
        },
        success: function(response) {
            updateCart(response);
        },
        error: function(error) {
            console.error('Error removing from cart:', error);
        }
    });
}


function updateItemQuantity(itemId, delta) {
    const csrfToken = document.getElementById('csrfToken').value;
    $.ajax({
        url: '/cart/update',
        type: 'POST',
        data: {
            item_id: itemId,
            quantity: delta,
            _token: csrfToken
        },
        success: function(response) {
            updateCart(response);
        },
        error: function(error) {
            console.error('Error updating cart:', error);
        }
    });
}


// Call this function on page load and after any cart operations
document.addEventListener('DOMContentLoaded', updateCartUI);
