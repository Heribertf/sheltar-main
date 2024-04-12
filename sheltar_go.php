<?php
include("config.php");

$error = "";
$msg = "";

if(isset($_POST['send'])) {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $moving_date = $_POST['moving_date'];
    $vehicle_type = $_POST['vehicle_type'];
    
    
    if(!empty($origin) && !empty($destination) && !empty($moving_date) && !empty($vehicle_type)){
        // Prepare the SQL statement
        $sql = "INSERT INTO moving_quotes (origin, destination, moving_date,vehicle_type) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);

        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssss", $origin, $destination, $moving_date, $vehicle_type);

        // Execute the prepared statement
        if(mysqli_stmt_execute($stmt)) {
           // Redirect to Quoatation Page
           header("location:quotation.php");
        }
         else{
            $error = "<p class='alert alert-warning'>Quote Request Not Sent Successfully</p>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $error = "<p class='alert alert-warning'>Please fill in all the fields</p>";
    }
}
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

<!--	Fonts
	========================================================-->
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&display=swap" rel="stylesheet">
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">

<!--	Title
	=========================================================-->
<title>Sheltar-Movers</title>
</head>
<body>

<!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>
--> 

<div id="page-wrapper">
    <div class="row"> 
        <!--	Header start  -->
		<?php include("include/header.php");?>
        <!--	Header end  -->
        
        <!--	Banner -->
        <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Sheltar Movers</b></h2>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="breadcrumb" class="float-left float-md-right">
                            <ol class="breadcrumb bg-transparent m-0 p-0">
                                <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Sheltar Movers</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!--	Banner -->
		
        <!--	Quote Inforamtion -->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-5 bg-primary">
                        <div class="contact-info">
                            <h3 class="mb-4 mt-4 text-white">Our Services</h3>
							
                            <ul>
                                <li class="d-flex mb-4">  <i class="fas fa-home text-white mr-2 font-13 mt-1"></i>
                                    <div class="contact-address">
                                        <h5 class="text-white">Residential Move</h5>
                                        <span class="text-secondary">"Moving homes with care and precision."</span>
										</div>
                                </li>
                                <li class="d-flex mb-4"> <i class="fas fa-building text-white mr-2 font-13 mt-1"></i>
                                    <div class="contact-address">
                                        <h5 class="text-white">Commercial Move</h5>
                                        <span class="d-table text-secondary">"Seamless transitions for your business."</span>
									</div>
                                </li>
                                <li class="d-flex mb-4">  <i class="fas fa-box text-white mr-2 font-13 mt-1"></i>
                                    <div class="contact-address">
                                        <h5 class="text-white">Packing & Unpacking</h5>
										<span class="d-table text-secondary">"Effortless packing, stress-free unpacking."</span>
										</div>
                                </li>
                            </ul>
                        </div>
                    </div>
					<div class="col-lg-1"></div>
                    <div class="col-md-12 col-lg-7">
						<div class="container">
                        <div class="row">
							<div class="col-lg-12">
								<h2 class="text-secondary double-down-line text-center mb-5">Looking to move? Move with Us</h2>
								<?php echo $msg; ?><?php echo $error; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<form class="w-100" action="#" method="post">
									<div class="row">
										<div class="row mb-4">
											<div class="form-group col-lg-6">
												<input type="text"  name="origin" class="form-control" placeholder="From...">
											</div>
											<div class="form-group col-lg-6">
												<input type="text"  name="destination" class="form-control" placeholder="Destination">
											</div>
											<div class="form-group col-lg-6">
                                                <input type="text" id="datepicker" name="moving_date" class="form-control" placeholder="Date of moving">
                                            </div>

											<div class="form-group col-lg-6">
                                            <select name="vehicle_type" class="form-control">
                                            <option value="" disabled selected>Select Vehicle Type </option>
                                                <option value="Pickup">Pickup</option>
                                                <option value="Truck">Trailer</option>
                                                <option value="Trailer">Truck</option>
                                                <option value="Flight">Flight</option>
                                            </select>
											</div>
											
										</div>
										<button type="submit" value="send message" name="send" class="btn btn-primary">Get a Quote</button>
									</div>
								</form>
							</div>
						</div>
						</div>
					</div>
                </div>
            </div>
        </div>
        <!--	Quote Inforamtion -->
        
        <!--	Footer   start-->
		<?php include("include/footer.php");?>
		<!--	Footer   start-->
        
        <!-- Scroll to top --> 
        <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i class="fas fa-angle-up"></i></a> 
        <!-- End Scroll To top --> 
    </div>
</div>
<!-- Wrapper End --> 

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
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="js/owl.carousel.min.js"></script> 
<script src="js/tmpl.js"></script> 
<script src="js/jquery.dependClass-0.1.js"></script> 
<script src="js/draggable-0.1.js"></script> 
<script src="js/jquery.slider.js"></script> 
<script src="js/wow.js"></script> 
<script src="js/jquery.cookie.js"></script> 
<script src="js/custom.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
  } );
  </script>
</body>
</html>