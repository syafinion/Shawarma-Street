
@include('components.header')

    
        
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <h3>Restaurant Menu</h3>
                        <ul>
                            <li><a href="/">home</a></li>
                            <li>Restaurant Menu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
    
    <!--shop  area start-->
    <div class="shop_area shop_fullwidth mt-70 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!--shop wrapper start-->
                    <!--shop toolbar start-->
                    <div class="shop_toolbar_wrapper">
                        <div class="shop_toolbar_btn">

                            <button data-role="grid_3" type="button" class=" btn-grid-3" data-toggle="tooltip" title="3"></button>

                            <button data-role="grid_4" type="button"  class=" btn-grid-4" data-toggle="tooltip" title="4"></button>

                            <button data-role="grid_list" type="button"  class="active btn-list" data-toggle="tooltip" title="List"></button>
                        </div>
                        <div class="page_amount">
                            <p>Showing 1–9 of 21 results</p>
                        </div>
                    </div>
                     <!--shop toolbar end-->
                     
                     <div class="row shop_wrapper grid_list">
                     @foreach($items as $item)
                            <div class="col-12">
                                <div class="single_product">
                                    <div class="product_thumb">
                                        <!-- Assuming $item->image_url and $item->secondary_image_url are set -->
                                        <a class="primary_img" href="product-details.html"><img src="{{ asset($item->image_url) }}" alt=""></a>
                                        <!-- <div class="label_product">
                                            <span class="label_sale">Sale</span>
                                            <span class="label_new">New</span>
                                        </div> -->
                                        <div class="action_links">
                                            <ul>
                                            <li class="add_to_cart">
                                            <a href="javascript:void(0);" onclick="addToCart({ id: '{{ $item->item_id }}', name: '{{ $item->name }}', price: '{{ $item->price }}', image: '{{ $item->image_url }}' })" title="Add to cart">
    <span class="lnr lnr-cart"></span>
</a>

                                            </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product_content grid_content">
                                        <h4 class="product_name"><a href="product-details.html">{{ $item->name }}</a></h4>
                                        <p><a href="#">{{ $item->category_name }}</a></p> <!-- Assuming the category is static; replace with dynamic if needed -->
                                        <div class="price_box">
                                            <span class="current_price">${{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="product_content list_content">
                                        <h4 class="product_name"><a href="product-details.html">{{ $item->name }}</a></h4>
                                        <p><a href="#">{{ $item->category_name }}</a></p>
                                        <div class="price_box">
                                            <span class="current_price">RM{{ number_format($item->price, 2) }}</span>
                                        </div>
                                        <div class="product_desc">
                                            <p>{{ $item->description }}</p>
                                        </div>
                                        <div class="action_links list_action_right">
                                            <ul>
                                            <li class="add_to_cart">
                                            <a href="javascript:void(0);" onclick="addToCart({ id: '{{ $item->item_id }}', name: '{{ $item->name }}', price: '{{ $item->price }}', image: '{{ $item->image_url }}' })" title="Add to cart">
    <span class="lnr lnr-cart"></span>
</a>
<input type="hidden" id="csrfToken" value="{{ csrf_token() }}">
                                            </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
</div>




                        </div>
                    </div>

                    <div class="shop_toolbar t_bottom">
                        <div class="pagination">
                            <ul>
                                <li class="current">1</li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li class="next"><a href="#">next</a></li>
                                <li><a href="#">>></a></li>
                            </ul>
                        </div>
                    </div>
                    <!--shop toolbar end-->
                    <!--shop wrapper end-->
                </div>
            </div>
        </div>
    </div>
    <!--shop  area end-->
    
    @include('components.footer')
    <!--footer area end-->
    
    


<!-- JS
============================================ -->
<!--jquery min js-->
<script src="assets/js/vendor/jquery-3.4.1.min.js"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>

<script src="assets/js/cart.js"></script>

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