@include('components.header')

    <!--breadcrumbs area start-->
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                       <h3>Checkout</h3>
                        <ul>
                            <li><a href="/">home</a></li>
                            <li>Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    <!--breadcrumbs area end-->
    
   <!--Checkout page section-->
<div class="Checkout_section mt-70">
    <div class="container">
        <div class="checkout_form">
            <form action="{{ route('submitOrder') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                            <h3>Billing Details</h3>
                            <div class="row">

                            <div class="col-lg-6 mb-20">
                                <label>Name <span>*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>


                            <!-- Street Address -->
                            <div class="col-12 mb-20">
                                <label>Street Address <span>*</span></label>
                                <input type="text" name="street_address" id="street_address" class="form-control" value="{{ old('street_address', $address->address_line1 ?? '') }}" placeholder="House number and street name" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Apartment -->
                            <div class="col-12 mb-20">
                                <input type="text" name="apartment" id="apartment" class="form-control" value="{{ $address->address_line2 ?? '' }}" placeholder="Apartment, suite, unit etc. (optional)">
                            </div>

                            <!-- Town / City -->
                            <div class="col-12 mb-20">
                                <label>Town / City <span>*</span></label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ $address->city ?? '' }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- State / County -->
                            <div class="col-12 mb-20">
                                <label>State / County <span>*</span></label>
                                <input type="text" name="state" id="state" class="form-control" value="{{ $address->state ?? '' }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Zip Code -->
                            <div class="col-12 mb-20">
                                <label>Zip Code <span>*</span></label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ $address->zip_code ?? '' }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Country -->
                            <div class="col-12 mb-20">
                                <label>Country <span>*</span></label>
                                <input type="text" name="country" id="country" class="form-control" value="{{ $address->country ?? '' }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-lg-6 mb-20">
                                <label>Phone <span>*</span></label>
                                <input type="number" name="phone_number" id="phone_number" class="form-control" value="{{ $user->phone_number ?? '' }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-lg-6 mb-20">
                                <label>Email Address <span>*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                                <div class="invalid-feedback" style="display:none;"></div>
                            </div>

                            <!-- Order Notes -->
                            <div class="col-12">
                                <div class="order-notes">
                                    <label for="order_note">Order Notes</label>
                                    <textarea id="order_note" name="order_notes" class="form-control" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <h3>Your order</h3> 
                        <div class="order_table table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($cartItems) > 0)
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>{{ $item->name }} <strong> × {{ $item->quantity }}</strong></td>
                                            <td>RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="2">No items in your cart.</td></tr>
                                @endif
                                                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Cart Subtotal</th>
                                        <td id="subtotal_amount">RM {{ number_format(array_reduce($cartItems, function($carry, $item) { return $carry + ($item->price * $item->quantity); }, 0), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Type</th>
                                        <td><strong id="display_order_type">{{ $orderType }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Shipping</th>
                                        <td><strong class="shipping_info">RM5.00</strong></td>
                                    </tr>
                                    <tr class="order_total">
                                        <th>Order Total</th>
                                        <td><strong id="order_total">RM {{ number_format(array_reduce($cartItems, function($carry, $item) { return $carry + ($item->price * $item->quantity); }, 0), 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>     
                        </div>
                        <div class="payment_method">
                            <label>Payment Method:</label>
                            <div class="panel-default">
                                <input id="payment_counter" name="payment_method" type="radio" value="pay_at_counter" checked />
                                <label for="payment_counter">Pay at Counter</label>
                            </div>
                            <div class="panel-default">
                                <input id="payment_online" name="payment_method" type="radio" value="online_bank" />
                                <label for="payment_online">Online Bank</label>
                            </div>
                            <div class="panel-default">
                                <input id="payment_touchngo" name="payment_method" type="radio" value="touchngo" />
                                <label for="payment_touchngo">Touch N Go</label>
                            </div>
                            <div class="panel-default">
                                <input id="payment_affin" name="payment_method" type="radio" value="affin_bank" />
                                <label for="payment_affin">Affin Bank</label>
                            </div>
                            <div class="panel-default">
                                <input id="payment_maybank" name="payment_method" type="radio" value="maybank" />
                                <label for="payment_maybank">Maybank</label>
                            </div>
                    </div>
                        <div class="order_button">
                            <button type="submit">Proceed to Payment</button> 
                        </div>    
                    </div> 
                </div> 
            </form>
        </div>
    </div>       
</div>
<!--Checkout page section end-->

    
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

<script>
$(document).ready(function() {
    // Initial setup and CSRF token configuration
    adjustShippingDisplay();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Ensure labels are shown when payment method changes
    $('input[name="payment_method"]').on('change', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var form = $('#checkoutForm');
        form.attr('action', "{{ route('submitOrder') }}");
        form.attr('method', "POST");

        // Explicitly show all labels in case they are hidden
        $('label[for^="payment_"]').css('display', 'inline');
    });
});

function adjustShippingDisplay() {
    var orderType = $('#display_order_type').text().trim();
    var subtotal = parseFloat($('#subtotal_amount').text().trim().replace('RM', ''));
    var shippingCost = 0;

    if (orderType === 'delivery') {
        $('.shipping_info').show();
        shippingCost = 5.00;
    } else {
        $('.shipping_info').hide();
    }

    var total = subtotal + shippingCost;
    $('#order_total').text(`RM${total.toFixed(2)}`);
}




// Input validation on blur
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('blur', function() {
            validateInput(input);
        });
    });
});

    function validateInput(input) {
        const type = input.type;
        const value = input.value.trim();
        const feedbackElement = input.nextElementSibling; // Assumes error display div follows input

        if (!value) {
            feedbackElement.textContent = 'This field is required.';
            feedbackElement.style.display = 'block';
            input.classList.add('is-invalid');
        } else if (type === 'email' && !validateEmail(value)) {
            feedbackElement.textContent = 'Please enter a valid email address.';
            feedbackElement.style.display = 'block';
            input.classList.add('is-invalid');
        } else {
            feedbackElement.style.display = 'none';
            input.classList.remove('is-invalid');
        }
    }

    function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email.toLowerCase());
}


</script>




</body>

</html>