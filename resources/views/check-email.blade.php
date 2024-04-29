@include('components.homeheader')


            <div class="container">   
            <div class="row">
                <div class="col-12">
                                    </div>
            </div>
        </div>         
  
    
    <!-- customer login start -->
    <div class="customer_login">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6" style="margin-left: auto; margin-right: auto;">
                <div class="account_form">
                    <form action="{{ route('check-email') }}" method="POST">
                        @csrf
                        <h2>Forgot Password</h2>
                        <p>Please enter your registered email address. We'll check if it exists in our system.</p>
                        <p>   
                            <label>Email <span>*</span></label>
                            <input type="email" name="email" required>
                        </p>
                        <div class="login_submit">
                            <button type="submit">Check Email</button> 
                        </div>
                    </form>
                </div>    
            </div>
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

</body>

</html>