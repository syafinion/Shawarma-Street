<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shawarma Street</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../public/assets/img/favicon.png">
    
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
   
    <!--header area start-->
    
    <!--offcanvas menu area start-->
    <div class="off_canvars_overlay">
                
    </div>

    <header>
        <div class="main_header header_two">
            <div class="header_top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                        <div class="col-lg-6">
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_middle header_middle2">
                <div class="container">
                    <div class="row align-items-center">
                       <div class="col-lg-4 col_search">
                           <div class="search_container mobail_s_none">
                                <form action="/search" method="GET">
                                    <div class="search_box">
                                    <input name="query" placeholder="Search product..." type="text">
                                    <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                                    </div>
                                </form>
                            </div>
                       </div>

                        <div class="col-lg-4 col-md-3 col-sm-3 col-3">
                            <div class="logo">
                                <a href="/"><img src="assets/img/logo/logo.png" alt="" style="width: 100px;"></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-7 col-8">
                            <div class="header_account_area">
                                <div class="header_account_list register">
                                    <ul>
                                        <li><a href="/">Home</a></li>
                                        <li><span>/</span></li>

                                        @if(Auth::check())
                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @else
                                        <li><a href="{{ route('login') }}">Login</a></li>
                                        <li><span>/</span></li>
                                        <li><a href="{{ route('register') }}">Register</a></li>
                                    @endif
                                    </ul>
                                </div>

                                <div class="header_account_list  mini_cart_wrapper">
                                       <a href="javascript:void(0)"><span class="lnr lnr-cart"></span><span class="item_count"></span></a>
                                        
                                       <!--mini cart-->
                                        <div class="mini_cart">
                                            <div class="cart_gallery">
                                                <div class="cart_close">
                                                	<div class="cart_text">
                                                		<h3>cart</h3>
                                                	</div>
                                                	<div class="mini_cart_close">
                                                		<a href="javascript:void(0)"><i class="icon-x"></i></a>
                                                	</div>
                                                </div>
                                            </div>
                                            <div class="mini_cart_table">
                                            </div>
                                        </div>
                                        <!--mini cart end-->
                                   </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header_bottom sticky-header">
                <div class="container">  
                    <div class="row align-items-center">
                        <div class="col-lg-8 offset-lg-2">
                            <!--main menu start-->
                            <div class="main_menu  menu_two color_two menu_position"> 
                                <nav>  
                                    <ul>
                                        <li class="mega_items"><a href="/">Home</a> </li>
                                        <li><a class="active"  href="{{ route('restaurant-menu') }}">Menu<i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu home_sub_menu d-flex">
                                                <li><span>Menu</span>
                                                    <ul>
                                                    @foreach($categories as $category)
                                                    <li>
                                                        <a href="{{ route('restaurant-menu', ['category' => $category->category_id]) }}">
                                                            {{ $category->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                    <a href="/myprofile">My Account</a>
                                        </li>
                                        <li><a href="#">About Us <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu home_sub_menu d-flex">
                                                <li><span>About Us</span>
                                                    <ul>
                                                        <li><a href="/history">History</a></li>
                                                        <li><a href="/contact">Contact Us</a></li>
                                                        <li><a href="/faq">FAQ</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li> 
                                    </ul>  
                                </nav> 
                            </div>
                            <!--main menu end-->
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </header>
    <!--header area end-->
    