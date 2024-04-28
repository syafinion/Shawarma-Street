@include('components.header')
    
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                       <h3>Cart</h3>
                        <ul>
                            <li><a href="index.html">home</a></li>
                            <li>Shopping Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
<!--shopping cart area start -->
<div class="shopping_cart_area mt-70">
    <div class="container">
        <form action="#">
            <div class="row">
                <div class="col-12">
                    <div class="table_desc">
                        <div class="cart_page">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product_remove">Delete</th>
                                        <th class="product_thumb">Image</th>
                                        <th class="product_name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product_quantity">Quantity</th>
                                        <th class="product_total">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="cart_items">
                                    <!-- Cart items will be loaded here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--coupon code area start-->
            <div class="coupon_area">
                <div class="row">
                    <div class="coupon_code right">
                        <h3>Cart Totals</h3>
                        <div class="order_type_selection">
                                <h4>Choose Order Type:</h4>
                                <label><input type="radio" name="order_type" value="dine_in" checked> Dine In</label>
                                <label><input type="radio" name="order_type" value="takeaway"> Takeaway</label>
                                <label><input type="radio" name="order_type" value="delivery"> Delivery (Extra RM5)</label>
                            </div>
                        <div class="coupon_inner">
                            <!-- Order Type Selection Area -->
                            <div class="cart_subtotal">
                                <p>Subtotal</p>
                                <p class="cart_amount" id="cart_subtotal">RM0.00</p>
                            </div>
                            <div class="cart_subtotal shipping">
                                <p>Shipping</p>
                                <p class="cart_amount"><span>Flat Rate:</span> RM5.00</p>
                            </div>
                            <a href="#">Calculate shipping</a>
                            <div class="cart_subtotal">
                                <p>Total</p>
                                <p class="cart_amount" id="cart_total">RM0.00</p>
                            </div>
                            <div class="checkout_btn">
                                <a href="/checkout">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--shopping cart area end -->
    
    <!--footer area start-->
    @include('components.footer')
    <!--footer area end-->

<!-- JS
============================================ -->
<!--jquery min js-->
<script src="assets/js/vendor/jquery-3.4.1.min.js"></script>

<script src="assets/js/cartPage.js"></script>



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