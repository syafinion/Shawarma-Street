
@include('components.homeheader')

    
    <!--faq area start-->
    <div class="faq_content_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="faq_content_wrapper">
                        <h4>Below are frequently asked questions and their answers.</h4>
                    </div>
                </div>
            </div> 
        </div>    
    </div>
     <!--Accordion area-->
  

    <div class="accordion_area">
        <div class="container">
            <div class="row">
            <div class="col-12"> 
                <div id="accordionExample" class="card__accordion accordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        What is shawarma?
                        </button>
                      </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>Shawarma is a traditional Middle Eastern dish made with thinly sliced, marinated meat (usually chicken, beef, or lamb) that is stacked on a vertical rotisserie and slowly roasted to perfection. The tender, juicy meat is typically served in a wrap or on a plate with various accompaniments such as fresh vegetables, pickles, and sauces.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Are your menu items suitable for vegetarians and vegans?
                        </button>
                      </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>While our menu primarily features meat-based shawarma wraps, we also offer delicious vegetarian and vegan options. </p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                            Do you offer gluten-free options?
                        </button>
                      </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>Yes, we understand the importance of catering to various dietary needs and preferences. While our traditional shawarma wraps are made with flatbread that contains gluten, we can accommodate gluten-free diets by serving the shawarma fillings on a bed of rice or in a salad bowl instead.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            Do you accommodate dietary restrictions or food allergies?
                        </button>
                      </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p> Absolutely! At Shawarma Street, we understand the importance of catering to dietary restrictions and food allergies. Our menu includes options for vegetarian, vegan, and gluten-free diets, and our staff is trained to assist customers with specific dietary needs. Please inform us of any allergies or dietary restrictions when placing your order, and we'll do our best to accommodate your requirements.</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                            Do you offer special promotions or discounts?
                        </button>
                      </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>Yes, Shawarma Street regularly runs special promotions and discounts to reward our valued customers. These may include limited-time offers, combo deals, loyalty rewards, and seasonal discounts. Be sure to sign up for our newsletter or follow us on social media to stay updated on the latest promotions and save on your next meal!</p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                            What are your operating hours?
                        </button>
                      </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>Our operating hours may vary depending on the location, but we typically open from 10.30 till 11.00, seven days a week. </p>
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                            How can I provide feedback or share my dining experience with Shawarma Street?
                        </button>
                      </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                      <div class="card-body">
                           <p>We love hearing from our customers! Whether you have feedback, suggestions, or simply want to share your dining experience with us, there are several ways to get in touch. You can leave a review on our website by clicking here or speak to a member of our team during your next visit. Your feedback is valuable to us as we strive to continuously improve and exceed your expectations.</p>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!--Accordion area end-->

    @include('components.footer')
    <!--faq area end-->

    
    
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