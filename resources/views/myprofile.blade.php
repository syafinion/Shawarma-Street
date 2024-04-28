   
@include('components.header')
    <!--breadcrumbs area start-->
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                       <h3>My Account</h3>
                        <ul>
                            <li><a href="index.html">home</a></li>
                            <li>My account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    <!--breadcrumbs area end-->


    <!-- my account start  -->
    <section class="main_content_area">
        <div class="container">   
            <div class="account_dashboard">
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <!-- Nav tabs -->
                        <div class="dashboard_tab_button">
                            <ul role="tablist" class="nav flex-column dashboard-list" id="nav-tab">
                                <li><a href="#dashboard" data-toggle="tab" class="nav-link active">Dashboard</a></li>
                                <li> <a href="#orders" data-toggle="tab" class="nav-link">Orders</a></li>
                                <li><a href="#address" data-toggle="tab" class="nav-link">Addresses</a></li>
                                <li><a href="#account-details" data-toggle="tab" class="nav-link">Account details</a></li>
                                <li><a href="#" id="logout-link" class="nav-link">Logout</a></li>
                            </ul>
                        </div>    
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <!-- Tab panes -->
                        <div class="tab-content dashboard_content">
                        @if(Auth::check())
                        <div class="tab-pane fade show active" id="dashboard">
                            <h3>Welcome, {{ Auth::user()->name }}</h3>
                            <p>From your account dashboard, you can view your <a href="#orders" data-toggle="tab">recent orders</a>, manage your <a href="#address" data-toggle="tab">shipping and billing addresses</a> and <a href="#account-details" data-toggle="tab">edit your password and account details.</a></p>
                        </div>
                    @else
                        <div class="tab-pane fade show active" id="dashboard">
                            <h3>Welcome</h3>
                            <p>You are not logged in. Please <a href="/login">login</a> to view your account details.</p>
                        </div>
                    @endif

                             <div class="tab-pane fade" id="orders">
                             <h3>Orders</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Items</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                                    <td><span class="status">{{ $order->status }}</span></td>
                                                    <td>RM{{ number_format($order->total_price, 2) }}</td>
                                                    <td>{{ $order->item_count }}</td>
                                                    <td>
                                                    <a href="#" class="view" data-toggle="modal" data-target="#orderDetailsModal" data-order-id="{{ $order->order_id }}">View</a>

                                                        @if($order->status == 'pending')
                                                            <button class="cancel-order" data-order-id="{{ $order->order_id }}">Cancel</button>
                                                        @endif
                                                        <button class="review-order" data-order-id="{{ $order->order_id }}" data-toggle="modal" data-target="#reviewModal">Review</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6">No orders found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                        </div>

                        <div class="tab-pane" id="address">
                            <p>The following addresses will be used on the checkout page by default.</p>
                            @if (empty($addresses))
                                <!-- Form for adding a new address when no addresses exist -->
                                <div class="address-item">
                                    <div class="address-edit">
                                        <form method="POST" action="{{ route('add-address') }}">
                                            @csrf
                                            <input type="text" name="address_line1" placeholder="Address Line 1" required>
                                            <input type="text" name="address_line2" placeholder="Address Line 2">
                                            <input type="text" name="city" placeholder="City" required>
                                            <input type="text" name="state" placeholder="State" required>
                                            <input type="text" name="zip_code" placeholder="Zip Code" required>
                                            <input type="text" name="country" placeholder="Country" required>
                                            <button type="submit">Add Address</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                            @foreach ($addresses as $address)
                            <div class="address-item" data-address-id="{{ $address->address_id }}">
                                <!-- View mode -->
                                <div class="address-view">
                                    <h4 class="billing-address">Billing Address</h4>
                                    <p><strong>{{ $address->user_name }}</strong></p> <!-- Updated from $address->name to $address->user_name -->
                                    <address>
                                        {{ $address->address_line1 }}
                                        <br>
                                        {{ $address->address_line2 ? $address->address_line2 . '' : '' }} <br> 
                                        {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}
                                        <br>
                                        {{ $address->country }}
                                    </address>
                                    <button class="edit-address-btn">Edit</button>
                                </div>
                                <!-- Edit mode (hidden by default) -->
                                <div class="address-edit" style="display: none;">
                                    <form method="POST" action="{{ route('update-address', ['addressId' => $address->address_id]) }}">
                                        @csrf
                                        <input type="text" name="address_line1" value="{{ $address->address_line1 }}" required>
                                        <input type="text" name="address_line2" value="{{ $address->address_line2 }}">
                                        <input type="text" name="city" value="{{ $address->city }}" required>
                                        <input type="text" name="state" value="{{ $address->state }}" required>
                                        <input type="text" name="zip_code" value="{{ $address->zip_code }}" required>
                                        <input type="text" name="country" value="{{ $address->country }}" required>
                                        <button type="submit">Save</button>
                                        <button type="button" class="cancel-edit-btn">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                            @endif
                        </div>


                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                            
                        {{-- Account Details Tab --}}
                        <div class="tab-pane fade" id="account-details">
                            <h3>Account details</h3>
                            <div class="login">
                                <div class="login_form_container">
                                    <div class="account_login_form">
                                        <form action="{{ route('update-account') }}" method="POST">
                                            @csrf
                                            <label>Username</label>
                                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                            <div id="name-error"></div>
                                            @error('name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            <label>Email</label>
                                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
                                            <div id="email-error"></div>
                                            @error('email')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            {{-- Password Reset Section --}}
                                            <div class="password_section">
                                                <label>Current Password</label>
                                                <input type="password" id="current-password" name="current-password">
                                                <div id="current-password-error"></div>
                                                @error('current-password')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror

                                                <label>New Password</label>
                                                <input type="password" id="new-password" name="new-password">
                                                <div id="new-password-error"></div>
                                                @error('new-password')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror

                                                <label>Confirm New Password</label>
                                                <input type="password" id="new-password_confirmation" name="new-password_confirmation">
                                                <div id="new-password-confirmation-error"></div>
                                                @error('new-password_confirmation')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <label>Phone Number</label>
                                            <input type="text" id="phone-number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}">
                                            <div id="phone-number-error"></div>
                                            @error('phone_number')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror

                                            <label>Birthdate</label>
                                            <input type="date" id="birthday" name="birthday" value="{{ old('birthday', optional(Auth::user()->birthday)->toDateString()) }}">
                                            <div id="birthday-error"></div>
                                            @error('birthday')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            <br>
                                            <div class="save_button primary_btn default_button">
                                                <button type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>  
        </div>        	
    </section>			
    <!-- my account end   --> 

    @include('components.footer')



<!-- JS
============================================ -->
<!--jquery min js-->
<script src="assets/js/vendor/jquery-3.4.1.min.js"></script>
<!--popper min js-->
<script src="assets/js/popper.js"></script>
<!--bootstrap min js-->
<script src="assets/js/bootstrap.min.js"></script>
<!--owl carousel min js-->
<script src="assets/js/owl.carousel.min.js"></script>
<!--slick min js-->
<script src="assets/js/slick.min.js"></script>
<!--magnific popup min js-->
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<!--counterup min js-->
<script src="assets/js/jquery.counterup.min.js"></script>
<!--jquery countdown min js-->
<script src="assets/js/jquery.countdown.js"></script>
<!--jquery ui min js-->
<script src="assets/js/jquery.ui.js"></script>
<!--jquery elevatezoom min js-->
<script src="assets/js/jquery.elevatezoom.js"></script>
<!--isotope packaged min js-->
<script src="assets/js/isotope.pkgd.min.js"></script>
<!--slinky menu js-->
<script src="assets/js/slinky.menu.js"></script>
<!--instagramfeed menu js-->
<script src="assets/js/jquery.instagramFeed.min.js"></script>
<!-- tippy bundle umd js -->
<script src="assets/js/tippy-bundle.umd.js"></script>
<!-- Plugins JS -->
<script src="assets/js/plugins.js"></script>

<!-- Main JS -->
<script src="assets/js/main.js"></script>

<script src="assets/js/cart.js"></script>

<script src="assets/js/reviews.js"></script>

<script>

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const addresses = document.querySelectorAll('.address-item');

    addresses.forEach(address => {
        const editBtn = address.querySelector('.edit-address-btn');
        const cancelBtn = address.querySelector('.cancel-edit-btn');
        const viewDiv = address.querySelector('.address-view');
        const editDiv = address.querySelector('.address-edit');

        editBtn.addEventListener('click', function() {
            viewDiv.style.display = 'none';
            editDiv.style.display = 'block';
        });

        cancelBtn.addEventListener('click', function() {
            editDiv.style.display = 'none';
            viewDiv.style.display = 'block';
        });
    });
});




// Disable the Save button by default
document.querySelector('button[type="submit"]').disabled = true;

// Function to enable/disable Save button based on validation status
function toggleSaveButton() {
    var errors = document.querySelectorAll('.alert-danger, .invalid');
    var saveButton = document.querySelector('button[type="submit"]');
    
    // Enable the Save button only if there are no errors displayed
    saveButton.disabled = errors.length !== 0;
}



document.addEventListener('DOMContentLoaded', function () {
    var currentPasswordInput = document.getElementById('current-password');

    currentPasswordInput.addEventListener('input', function() {
        var password = currentPasswordInput.value;
        if (password.length > 0) {
            fetch('/check-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // Make sure the server knows to send back JSON
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ current_password: password })
            })
            .then(response => {
                if (!response.ok) throw new Error(`Network response was not ok: ${response.statusText}`);
                return response.json();
            })
            .then(data => {
                if (!data.valid) {
                    document.getElementById('current-password-error').textContent = data.message;
                    document.getElementById('current-password-error').style.color = 'red';
                } else {
                    document.getElementById('current-password-error').textContent = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('current-password-error').textContent = 'Error checking password.';
                document.getElementById('current-password-error').style.color = 'red';
            });
        } else {
            document.getElementById('current-password-error').textContent = 'Current password is required.';
            document.getElementById('current-password-error').style.color = 'red';
        }
    });

    // New Password validation - check minimum length
    document.getElementById('new-password').addEventListener('input', function(event) {
        if (event.target.value.length < 6) {
            document.getElementById('new-password-error').textContent = 'Password must be at least 6 characters long.';
            document.getElementById('new-password-error').style.color = 'red';
        } else {
            document.getElementById('new-password-error').textContent = '';
            // Also validate confirmation password every time the new password changes
            validatePasswordConfirmation();
        }

        toggleSaveButton(); 

    });

    // Confirm New Password validation - check if passwords match
    document.getElementById('new-password_confirmation').addEventListener('input', validatePasswordConfirmation);

    function validatePasswordConfirmation() {
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('new-password_confirmation').value;
        if (newPassword !== confirmPassword) {
            document.getElementById('new-password-confirmation-error').textContent = 'Passwords do not match.';
            document.getElementById('new-password-confirmation-error').style.color = 'red';
        } else {
            document.getElementById('new-password-confirmation-error').textContent = '';
        }
    }

    toggleSaveButton(); 

});



document.getElementById('name').addEventListener('input', function(event) {
    if (event.target.value.length < 3) {
        document.getElementById('name-error').textContent = 'Username must be at least 3 characters long.';
        document.getElementById('name-error').style.color = 'red';
    } else {
        document.getElementById('name-error').textContent = '';
    }
    toggleSaveButton(); 

});

document.getElementById('email').addEventListener('input', function(event) {
    const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/; // Simple email validation regex
    if (!emailRegex.test(event.target.value)) {
        document.getElementById('email-error').textContent = 'Enter a valid email address.';
        document.getElementById('email-error').style.color = 'red';
    } else {
        document.getElementById('email-error').textContent = '';
    }
    toggleSaveButton(); 
});

// If Birthdate validation is needed
document.getElementById('birthday').addEventListener('input', function(event) {
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD
    if (!dateRegex.test(event.target.value)) {
        document.getElementById('birthday-error').textContent = 'Enter a valid date in YYYY-MM-DD format.';
        document.getElementById('birthday-error').style.color = 'red';
    } else {
        document.getElementById('birthday-error').textContent = '';
    }
    toggleSaveButton(); 
});

document.getElementById('phone-number').addEventListener('input', function(event) {
    var phoneNumber = event.target.value;
    // Check for non-numeric characters and replace them
    phoneNumber = phoneNumber.replace(/[^0-9]/g, '');
    event.target.value = phoneNumber; // Update the input field with cleaned number

    // Validation for length after cleaning the input
    if (phoneNumber.length > 0 && (phoneNumber.length < 7 || phoneNumber.length > 15)) {
        document.getElementById('phone-number-error').textContent = 'Phone number must be between 7 and 15 digits long.';
        document.getElementById('phone-number-error').style.color = 'red';
    } else {
        document.getElementById('phone-number-error').textContent = '';
    }
    toggleSaveButton();
});


document.addEventListener('DOMContentLoaded', function() {
    // Check for the order success message from Laravel's session, correctly outputted in JavaScript
    var orderSuccess = @json(session('orderSuccess'));

    if (orderSuccess) {
        localStorage.removeItem('cart');  // Clear the cart from local storage
        console.log("Cart cleared due to successful order.");

        // Assuming you have a function to update the cart display/UI
        if (typeof updateCartUI === "function") {
            updateCartUI();  // Update the UI to reflect an empty cart
        }
    }
});


</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

   <!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Submit Your Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reviewForm">
                <div class="modal-body">
                    <input type="hidden" id="orderId" name="orderId">
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <select class="form-control" id="rating" name="rating">
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Review Submitted</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                Thank you! Your review has been submitted successfully.
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Order Information -->
                <h6>Order Information</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Order ID:</strong> <span id="modalOrderId"></span><br>
                        <strong>Date:</strong> <span id="modalOrderDate"></span><br>
                        <strong>Status:</strong> <span id="modalOrderStatus"></span><br>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Price:</strong><span id="modalOrderTotal"></span><br>
                        <strong>Customer Name:</strong> <span id="modalCustomerName"></span><br>
                        <strong>Phone Number:</strong> <span id="modalPhoneNumber"></span><br>
                    </div>
                </div>

                <!-- Delivery Address -->
                <h6>Delivery Address</h6>
                <p id="modalDeliveryAddress"></p>

                <!-- Order Items -->
                <h6>Items Ordered</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalOrderItems">
                            <!-- Order items will be populated here -->
                        </tbody>
                    </table>
                </div>

                <!-- Payment Information -->
                <h6>Payment Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Payment Method:</strong> <span id="modalPaymentMethod"></span><br>
                        <strong>Payment Status:</strong> <span id="modalPaymentStatus"></span><br>
                    </div>
                    <div class="col-md-6">
                        <strong>Processed At:</strong> <span id="modalProcessedAt"></span><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


</body>

</html>