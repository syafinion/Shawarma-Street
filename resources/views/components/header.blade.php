<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shawarma Street</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

   <!--header area start-->
    
    <!--offcanvas menu area start-->
    <div class="off_canvars_overlay">
                
    </div>
    <!-- MOBILE -->
    <div class="offcanvas_menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="canvas_open">
                        <a href="javascript:void(0)"><i class="icon-menu"></i></a>
                    </div>
                    <div class="offcanvas_menu_wrapper">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="icon-x"></i></a>  
                        </div>
                        
                        <div class="call-support">
                            <p><a href="tel:(08)23456789">(08) 23 456 789</a> Customer Support</p>
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                            <li class="menu-item-has-children">
                                    <a href="/">Promotions</a>
                                </li>
                                <li class="menu-item-has-children active">
                                    <a href="#">Menu</a>
                                    <ul class="sub-menu">
                                        <li class="menu-item-has-children">
                                            <a href="#">Menu</a>
                                            <ul class="sub-menu">
                                                        <li><a href="index-2.html">Shawarma</a></li>
                                                        <li><a href="index-3.html">Rice</a></li>
                                                        <li><a href="index-4.html">Add ons</a></li>
                                                        <li><a href="index-5.html">Drinks & Desserts</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">About Us </a>
                                    <ul class="sub-menu">
                                            <li><a href="">History</a></li>
                                            <li><a href="">Contact Us</a></li>
                                            <li><a href="">FAQ</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="/myprofile">My Account</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--offcanvas menu area end-->
    <!-- MOBILE -->


    <header>
        <div class="main_header">
            <div class="header_top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_middle">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-3">
                            <div class="logo">
                                <a href="index.html"><img src="assets/img/logo/logo.png" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-6 col-sm-7 col-8">
                            <div class="header_right_info">

                            <!-- SEARCH FUNCTION DESKTOP-->
                            <div class="search_container mobail_s_none">
                                <form action="/search" method="GET">
                                    <div class="hover_category">
                                        <select class="select_option" name="category" id="categori1"> <!-- Note: Ensure unique IDs -->
                                            <option value="">Select a category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="search_box">
                                        <input name="query" placeholder="Search product..." type="text">
                                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                                    </div>
                                </form>
                            </div>
                            <!-- SEARCH FUNCTION DESKTOP-->


                                <div class="header_account_area">
                                    <div class="header_account_list register">
                                    <ul>
                                        @if(Auth::check())
                                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); clearCartAndLogout();">Logout</a></li>
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
            </div>


            <div class="header_bottom sticky-header">
                <div class="container">  
                    <div class="row align-items-center">

                       <!-- SEARCH FUNCTION MOBILE -->
                        <div class="col-12 col-md-6 mobail_s_block">
                            <div class="search_container">
                                <form action="/search" method="GET">
                                    <div class="hover_category">
                                        <select class="select_option" name="category" id="categori2">
                                            <option value="">Select a category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="search_box">
                                        <input name="query" placeholder="Search product..." type="text">
                                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- SEARCH FUNCTION MOBILE -->



                        <div class="col-lg-3 col-md-6">
                            <div class="categories_menu">
                                <div class="categories_title">
                                    <h2 class="categori_toggle">All Categories</h2>
                                </div>
                                <div class="categories_menu_toggle">
                                    <ul>
                                        <li>
                                            <!-- Link to show all items without any category filter -->
                                            <a href="{{ route('restaurant-menu') }}">All Categories</a>
                                        </li>
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="{{ route('restaurant-menu', ['category' => $category->category_id]) }}">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>





                        <div class="col-lg-6">
                            <!--main menu start-->
                            <div class="main_menu menu_position"> 
                                <nav>  
                                    <ul>
                                    <li class="menu-item-has-children">
                                    <a href="/">Promotions</a>
                                        </li>
                                        <li><a href="index.html">Menu<i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu home_sub_menu d-flex">
                                                <li><span>Menu</span>
                                                    <ul>
                                                        <li><a href="">Shawarma</a></li>
                                                        <li><a href="">Rice</a></li>
                                                        <li><a href="">Add ons</a></li>
                                                        <li><a href="">Drinks & Desserts</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                    <a href="/myprofile">My Account</a>
                                        </li>
                                        <li><a href="#">About Us <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                                        <li><a href="/history">History</a></li>
                                                        <li><a href="/contact">Contact Us</a></li>
                                                        <li><a href="/faq">FAQ</a></li>
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

