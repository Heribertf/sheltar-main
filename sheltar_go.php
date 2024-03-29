<?php 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();
include("config.php");
///code								
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Meta Tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Homex template">
<meta name="keywords" content="">
<meta name="author" content="Unicoder">

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

<!--	Css Link
	========================================================-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/layerslider.css">
<link rel="stylesheet" type="text/css" href="css/color.css" id="color-change">
<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<!--	Title
	=========================================================-->
<title>Sheltar - Properties</title>
</head>

<body class="go_body">

    <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->


        <div class="jumbotron jumbotron-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-4 text-right">
                <h1 id = "h1_header">moving? we can help.</h1>
                <p>Get rates, availability and deals in your area.</p>
                <form class="form">
                    <div class="col-md-7 offset-md-5">
                        <div class="form-row mb-2">
                           <input class="form-control" type="search" placeholder="Pick up" aria-label="Search">
                        </div>
                        <div class="form-row mb-2">
                          <input class="form-control" type="search" placeholder="Drop off" aria-label="Search">
                        </div>
                        <div class="form-row mb-2">
                            <input class="form-control" type="search" placeholder="Date" aria-label="Search">
                        </div>
                        <div class="form-row mb-2">
                            <button class="btn btn-block btn-orange" type="submit">Search</button>
                        </div>
                         
                   </div>
                </form>
            </div>
        </div>
        </div>
        <!--row 3-->
        
            <div id="how" class="row">
            <div class="col-md-12">
                <h1 id = "h1_header">What we do! </h1>
            </div>
        
        <!--row 4-->
        <div class="row services">
            <div class="col-md-4">
                <img src="images/truck.png" style="width:300px;height:200px;"></img>
                <h2 id = "h2_header">Truck Rentals</h2>
                <p>If you are ready to set out on your own and have a few friends to help 
                with theheavy lifting, Sheltar GO can provide the wheels!</p>
            </div>
            <div class="col-md-4">
                <img src="images/suitcase.jpg" style="width:300px;height:200px;"></img>
                <h2 id = "h2_header">Full Equipment</h2>
                <p>If you are ready to set out on your own and have a few friends to help with theheavy lifting, 
                Sheltar GO can provide the wheels!</p>
            </div>
            <div class="col-md-4">
                <img src="images/packag.jpg" style="width:300px;height:200px;"></img>
                <h2 id = "h2_header">Packaging</h2>
                <p>Packing up an entire houseful of stuff is a big job! We will pack  from the treasured 
                stuffed animal collection to the fine antiques.</p>
            </div>
        </div> 
        </div>
        
        <!--row 5-->
        <div id="packag" class="row readmore1">
            <div class="col-md-5 ">
                <div class="orangebox">
                    <h1 id = "h1_header">Dont move, <br> Call Sheltar!</h1>
                    <p>Get our packaging service & have a rest! We will treat your things as if they were our own. </p>
                    <button class="btn btn-light justify" type="submit">Read more</button>
                </div>
            </div>
        </div>
        <!--row 6-->
        <div id="truck" class="row readmore2">
            
            <div class="col-md-5 offset-md-6">
                <div class="orangebox">
                    
                    <h1 id = "h1_header">Truck rentals</h1>
                    <p>Get out packaging service & have a rest! We will treat your things as if they were our own.  </p>
                    <button class="btn btn-light justify" type="submit">Read more</button>
                </div>
            </div>
            <img src="img/car.png"></img>
        </div>
        <!--row 7-->
        <div class="row white">
            <div class="col-md-12">     
                
            </div>
        </div>
        <!--row 8-->
        <div id="faq" class="row team">
            <div class="col-md-12">
              <h1 id = "h1_header">We are here to help! </h1>
              <p>We now how to move you fast and easy! Contact us.</p>
            </div>
   
           
        <!--row 9-->
        <div class="row team">
            <div class="col-md-12">  
        <form>
            <div class="form-row">
                <div class="col-md-3 mb-2 offset-md-1">
                    <input type="text" class="form-control" placeholder="Name">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" placeholder="E-mail">
                </div>
                <div class="col-md-4 mb-2">
                    <select class="form-control" placeholder="Category">
                        <option selected>Category</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="col-md-10 mb-2 offset-md-1">
                    <textarea class="form-control" rows="2"></textarea>
                </div>
                
            </div>
            <div class="col-md-3 mb-2 offset-md-4">
                <button class="btn btn-block btn-orange" type="submit">Send</button>
            </div>
        </form>
        </div>
        </div>     
        
        <div class="row white">
            <div class="col-md-12">
                
            </div>
        </div>
                
                                 
                
            
        </div>
    </div><!--conainer-->

         <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->



<!--	Js Link
============================================================--> 
<script src="js/jquery.min.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/greensock.js"></script> 
<script src="js/layerslider.transitions.js"></script> 
<script src="js/layerslider.kreaturamedia.jquery.js"></script> 
<!--jQuery Layer Slider --> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 

<script src="js/custom.js"></script>
</body>

</html>