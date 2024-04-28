@include('components.header')
    
    <!-- customer login start -->
<div class="customer_login">
    <div class="container">
        <div class="row">
            <!-- register area start -->
            <div class="col-lg-6 col-md-6">
                <div class="account_form register">
                    <h2>Register</h2>
                    <!-- Note the action points to the /register route and method is POST -->
                    <form action="/register" method="POST">
                        @csrf <!-- CSRF Token for Laravel form submission security -->
                        <p>
                            <label>Name <span>*</span></label>
                            <input type="text" name="name" required>
                        </p>
                        <p>
                            <label>Email address <span>*</span></label>
                            <input type="email" name="email" required>
                        </p>
                        <p>
                            <label>Password <span>*</span></label>
                            <input type="password" name="password" required>
                        </p>
                        <p>
                            <label>Phone Number</label>
                            <input type="text" name="phone_number">
                        </p>
                        <div class="login_submit">
                            <button type="submit">Register</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- register area end -->
        </div>
    </div>
</div>
    <!-- customer login end -->
    @include('components.footer')
    <!--footer area end-->
    
    
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


</body>

</html>