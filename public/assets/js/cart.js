// Utility function to access the cart from local storage
function getCart() {
    const cart = localStorage.getItem('cart'); // Retrieve the cart as a string
    const parsedCart = cart ? JSON.parse(cart) : []; // Parse the string into a JSON object or return an empty array
    console.log('Retrieved cart from storage:', parsedCart);
    return parsedCart; // Return the parsed cart
}

// Utility function to save the cart to local storage
function saveCart(cart) {
    console.log('Saving to local storage:', cart);
    localStorage.setItem('cart', JSON.stringify(cart)); // Convert the cart to a string and store it
}

// Function to update the cart with a server response
function updateCart(response) {
    if (response && response.cartItems) {
        saveCart(response.cartItems); // Save the updated cart to local storage
        updateCartUI(); // Refresh the cart UI to reflect the new cart state
    } else {
        console.error('Unexpected response format or error:', response);
        if (response.error) {
            alert('Error: ' + response.error); // Display an error alert
        }
    }
}


// Function to update the cart's UI
function updateCartUI() {
    const cart = getCart(); // Retrieve the cart from local storage
    console.log('Updating UI with cart:', cart);

    const miniCart = document.querySelector('.mini_cart'); // Find the mini cart DOM element
    const itemCountElement = document.querySelector('.item_count'); // Find the item count DOM element

    let html = ''; // Initialize HTML content for the mini cart
    let subtotal = 0;  // Initialize subtotal to zero
    let itemCount = 0; // Initialize item count to zero

    cart.forEach(item => {
        console.log('Processing item:', item);
        itemCount += item.quantity; // Increment item count by the quantity of this item
        const itemPrice = parseFloat(item.price); // Parse the item price as a float
        subtotal += item.quantity * itemPrice; // Add to the subtotal
// Append HTML for each item in the mini cart
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
// Append subtotal and cart footer
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
        </div>
    `;

    // Update item count display
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

// Function to clear the cart
function clearCart() {
    localStorage.removeItem('cart'); // Clear the cart from local storage
    updateCartUI(); // Update the cart UI to reflect the empty cart
}

// Function to add an item to the cart via an AJAX request
function addToCart(item) {
    const csrfToken = document.getElementById('csrfToken').value;
    $.ajax({
        url: '/cart/add',
        type: 'POST',
        data: {
            _token: csrfToken,  // CSRF token for security
            item_id: item.id,
            name: item.name,
            price: item.price,
            image: item.image
        },
        success: function(response) {
            console.log("Added to cart:", response);
            updateCart(response);  // Update the cart with the server response
        },
        error: function(error) {
            console.error('Error adding to cart:', error);  // Log errors if any
        }
    });
}

// Function to remove an item from the cart
function removeFromCart(itemId) {
    const csrfToken = document.getElementById('csrfToken').value;  // Retrieve the CSRF token
    console.log("Attempting to remove item with ID:", itemId);  // Debug: Log the item ID being passed
    $.ajax({
        url: '/cart/remove',
        type: 'POST',
        data: {
            _token: csrfToken, // CSRF token for security
            item_id: itemId
        },
        success: function(response) {
            console.log("Removed from cart:", response);
            updateCart(response); // Update the cart with the server response
        },
        error: function(error) {
            console.error('Error removing from cart:', error);
        }
    });
}
// Function to update the quantity of an item in the cart
function updateItemQuantity(itemId, delta) {
    const cart = getCart(); // Retrieve the cart from local storage
    const item = cart.find(item => item.item_id.toString() === itemId.toString());
    if (!item) {
        console.error("Item not found in cart:", itemId);
        return;
    }

    const newQuantity = item.quantity + delta; // Calculate the new quantity
    if (newQuantity < 0) {
        console.warn("Cannot reduce quantity below zero.");
        return;
    }

    const csrfToken = document.getElementById('csrfToken').value;

    $.ajax({
        url: '/cart/update',
        type: 'POST',
        data: {
            _token: csrfToken, // CSRF token for security
            item_id: itemId,
            quantity: newQuantity  // Send the new total quantity
        },
        success: function(response) {
            console.log("Quantity updated:", response);
            updateCart(response); // Update the cart with the server response
        },
        error: function(error) {
            console.error('Failed to update quantity:', error); // Log errors if any
        }
    });
}








// Call this function on page load and after any cart operations
document.addEventListener('DOMContentLoaded', updateCartUI);
