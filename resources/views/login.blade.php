@include('components.header')

    
<body>
    <!-- customer login start -->
    <div class="customer_login" style="background-color: #ae2108;">
    <div class="container">
        <div class="row">
            <!-- login area start -->
            <div class="col-lg-6 col-md-6"  style="margin-left: auto; margin-right: auto;">
                <div class="account_form">
                    
                    <!-- Update the action to /login and method to POST -->
                    <form action="/login" method="POST" id="loginForm" style="background-color: white;">
                    <h2>Login</h2>        
                    @csrf
                            <div>
                                <label>Username or email <span>*</span></label>
                                <input type="text" name="email" id="login-email" required>
                                @error('email')
                                    <div id="login-email-error" class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label>Password <span>*</span></label>
                                <input type="password" name="password" id="login-password" required>
                                @error('password')
                                    <div id="login-password-error" class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="success-message">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                            </div>
                            <div class="login_submit">
                               <a href="/check-email">Lost your password?</a>
                                <button type="submit">login</button> 
                            </div>
                        </form>

                </div>
            </div>
            <!-- login area end -->
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
<script src="assets/js/cart.js"></script>

<script src="assets/js/loginValidation.js"></script>


</body>

</html>