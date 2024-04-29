<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Payment Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- CSS 
    ========================= -->
    <!--bootstrap min css-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!--owl carousel min css-->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!--slick min css-->
    <link rel="stylesheet" href="assets/css/slick.css">
    <!--magnific popup min css-->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!--font awesome css-->
    <link rel="stylesheet" href="assets/css/font.awesome.css">
    <!--ionicons css-->
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <!--linearicons css-->
    <link rel="stylesheet" href="assets/css/linearicons.css">
    <!--animate css-->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!--jquery ui min css-->
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <!--slinky menu css-->
    <link rel="stylesheet" href="assets/css/slinky.menu.css">
    <!--plugins css-->
    <link rel="stylesheet" href="assets/css/plugins.css">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--modernizr min js here-->
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>
</head>

<body>
<div class="container">
    <div class="payment-container my-5 p-4 shadow">
        <h2 class="text-center mb-4">Enter Payment Details</h2>

        <!-- Order Details -->
        @if(isset($order))
        <div class="order-details mb-4 p-3 border rounded">
            <h4>Order Details:</h4>
            <p>Order ID: {{ $order->order_id }}</p>
            <p>Total Price: RM{{ number_format($order->total_price, 2) }}</p>
        </div>
        @endif

        <!-- Payment Method -->
        <h3 class="mb-3">Payment Method: {{ $paymentMethod }}</h3>

        <!-- Payment Form -->
        <form action="{{ route('processPayment') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
            <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
            <div class="mb-3">
                <label for="cardNumber" class="form-label">Card Number</label>
                <input type="text" class="form-control" id="cardNumber" name="card_number" required>
                <div class="invalid-feedback">Please enter a valid 16-digit card number.</div>
            </div>
            <div class="mb-3">
                <label for="expiryDate" class="form-label">Expiry Date</label>
                <input type="month" class="form-control" id="expiryDate" name="expiry_date" required>
                <div class="invalid-feedback">Expiry date cannot be in the past.</div>
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
                <div class="invalid-feedback">Enter a 3 or 4 digit CVV.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Process Payment</button>
        </form>

    </div>
</div>

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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('.needs-validation');
    const submitButton = form.querySelector('button[type="submit"]'); // Select the submit button

    // Define the input elements
    const cardNumberInput = document.getElementById('cardNumber');
    const cvvInput = document.getElementById('cvv');
    const expiryDateInput = document.getElementById('expiryDate');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            submitButton.disabled = true; // Disable submit button if form is invalid
        } else {
            submitButton.disabled = false; // Ensure it's enabled if form is valid
        }
        form.classList.add('was-validated');
    }, false);

    validateInput(cardNumberInput, function(value) {
        return value.length === 16 && !isNaN(parseInt(value));
    }, 'Enter a valid 16-digit card number');

    validateInput(cvvInput, function(value) {
        return value.length >= 3 && value.length <= 4 && !isNaN(parseInt(value));
    }, 'Enter a 3 or 4 digit CVV');

    validateInput(expiryDateInput, function(value) {
        const expiryDate = new Date(value);
        const now = new Date();
        expiryDate.setMonth(expiryDate.getMonth() + 1);
        expiryDate.setDate(0); // Last day of the expiry month
        return expiryDate >= now;
    }, 'Expiry date cannot be in the past');
});

function validateInput(inputElement, isValidFunction, invalidMessage) {
    inputElement.addEventListener('input', function() {
        const valid = isValidFunction(this.value.replace(/\s+/g, ''));
        if (valid) {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.form.querySelector('button[type="submit"]').disabled = false; // Enable submit button if valid
        } else {
            this.setCustomValidity(invalidMessage);
            this.classList.add('is-invalid');
            this.form.querySelector('button[type="submit"]').disabled = true; // Disable submit button if invalid
        }
    });
}
</script>



</body>
</html>
