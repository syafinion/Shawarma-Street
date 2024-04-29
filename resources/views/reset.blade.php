
@include('components.homeheader')


            <div class="container">   
            <div class="row">
                <div class="col-12">
                                    </div>
            </div>
        </div>         
  
    
 <!-- Reset Password Form -->
<div class="customer_login">
    <div class="container"  style="background-color: #ae2108;">
        <div class="row">
            <div class="col-lg-6 col-md-6" style="margin-left: auto; margin-right: auto;">
                <div class="account_form">
                    <form action="{{ route('reset') }}" method="POST" id="resetPasswordForm">
                        @csrf
                        <h2>Change Your Password</h2>
                        <p>Please enter your new password:</p>
                        <p>
                            <label>New Password <span>*</span></label>
                            <input type="password" name="password" id="password" required>
                            <div class="error" id="passwordError"></div>
                        </p>
                        <p>
                            <label>Confirm New Password <span>*</span></label>
                            <input type="password" name="password_confirmation" id="confirmPassword" required>
                            <div class="error" id="confirmPasswordError"></div>
                        </p>
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        <div class="login_submit">
                            <button type="submit">Reset Password</button>
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    function validatePasswords() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordError.textContent = 'Passwords do not match.';
            confirmPasswordError.style.color = 'red';
            return false;
        } else {
            confirmPasswordError.textContent = '';
            return true;
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    const form = document.getElementById('resetPasswordForm');
    form.addEventListener('submit', function (event) {
        if (!validatePasswords()) {
            event.preventDefault(); // Stop form submission
        }
    });
});
</script>




</body>

</html>