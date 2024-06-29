<?php
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include_once './connection1.php';
include_once './connection2.php';

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
    <title>Sheltar Listing platform</title>
</head>

<body>


    <div id="page-wrapper">
        <div class="row">
            <!--	Header start  -->
            <?php include ("include/header.php"); ?>
            <!--	Header end  -->

            <!--	Banner Start   -->
            <div class="overlay-black w-100 slider-banner1 position-relative"
                style="background-image: url('images/banner/04.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-lg-12">
                            <div class="text-white">
                                <h1 class="mb-4"><span class="text-primary">Rentals</span><br>
                                    Near you</h1>
                                <form method="post" action="propertygrid.php">
                                    <div class="row">
                                        <div class="col--8 col-mdlg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="city"
                                                    placeholder="Enter Location" required>
                                            </div>
                                        </div>

                                        <div class="col--8 col-mdlg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="type"
                                                    placeholder="Enter Type of house" required>
                                            </div>
                                        </div>

                                        <div class="col--8 col-mdlg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="price"
                                                    placeholder="Max Price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-2">
                                            <div class="form-group">
                                                <select class="form-control" name="stype" required>
                                                    <option value="" disabled selected>For..</option>
                                                    <option value="rent">Rent</option>
                                                    <option value="sale">Sale</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-lg-2">
                                            <div class="form-group">
                                                <button type="submit" name="filter" class="btn btn-primary w-100">Search
                                                    Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Banner End  -->

            <!--	Recent Properties  -->
            <div class="full-row">

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-secondary double-down-line text-center mb-4">Recent Properties</h2>
                        </div>

                        <div class="col-md-12">
                            <div class="tab-content mt-4" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home">
                                    <div class="row">

                                        <?php
                                        $query = "SELECT pl.listing_id, pl.property_name, 
                                                CASE 
                                                    WHEN pl.listing_type = 1 THEN 'For Rent'
                                                    WHEN pl.listing_type = 2 THEN 'For Sale'
                                                END AS listing_type,
                                                CASE 
                                                    WHEN pl.property_use = 1 THEN 'Residential'
                                                    WHEN pl.property_use = 2 THEN 'Commercial'
                                                END AS property_use,
                                                CASE 
                                                    WHEN pl.property_status = 1 THEN 'Unfurnished'
                                                    WHEN pl.property_status = 2 THEN 'Furnished'
                                                END AS property_status,
                                                CASE 
                                                    WHEN pl.property_type = 1 THEN 'Apartment'
                                                    WHEN pl.property_type = 2 THEN 'Bungalow'
                                                    WHEN pl.property_type = 3 THEN 'Massionatte'
                                                END AS property_type, pl.bedroom_count, pl.unit_price, pl.property_description, pl.features, pl.image_paths, pl.city, pl.address, DATE_FORMAT(pl.added, '%d-%b-%Y') AS dateAdded,
                                            CONCAT(u.first_name, ' ', u.last_name) AS agentName, u.phone, u.email, u.profileImage
                                            FROM 
                                                property_listing pl
                                            LEFT JOIN
                                                users u ON pl.user_id = u.user_id
                                            ORDER BY pl.added DESC LIMIT 6";

                                        if ($result = mysqli_prepare($conn, $query)) {
                                            mysqli_stmt_execute($result);

                                            mysqli_stmt_bind_result($result, $listingId, $propertyName, $listingType, $proertyUse, $propertyStatus, $propertyType, $bedrooms, $price, $description, $features, $images, $city, $address, $dateAdded, $agentName, $agentPhone, $agentEmail, $agentProfile);


                                            if (mysqli_stmt_fetch($result)) {
                                                do {

                                                    $dateAddedDateTime = DateTime::createFromFormat('d-M-Y', $dateAdded);

                                                    $currentDateTime = new DateTime();

                                                    $interval = $currentDateTime->diff($dateAddedDateTime);

                                                    $daysDifference = $interval->days;


                                                    $duration = '';
                                                    $imagePaths = preg_split('/\s*,\s*/', $images);
                                                    if ($listingType == 'For Rent') {
                                                        $duration = ' / month';
                                                    }

                                                    echo '
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="featured-thumb hover-zoomer mb-4">
                                                                <a href="propertydetail.php?pid=' . htmlspecialchars($listingId) . '">
                                                                <div class="overlay-black overflow-hidden position-relative">
                                                                    <img src="sheltar-properties/uploads/property-images/' . htmlspecialchars($imagePaths[0]) . '" alt="pimage">
                                                                    <div class="featured bg-primary text-white text-capitalize">
                                                                    ' . htmlspecialchars($listingType) . '
                                                                    </div>
                                                                </div>
                                                                </a>
                                                                <div class="featured-thumb-data shadow-one">
                                                                    
                                                                    <div class="p-3">
                                                                        <div class="p-3">
                                                                            <h5 class="text-secondary hover-text-primary text-capitalize">
                                                                                <a
                                                                                    href="propertydetail.php?pid=' . htmlspecialchars($listingId) . '">' . htmlspecialchars($propertyName) . '</a>
                                                                            </h5>
                                                                        </div>
                                                                        <div class="p-3 text-primary"><b>Ksh.
                                                                        ' . number_format($price, 2) . '<span class="text-sm">' . htmlspecialchars($duration) . '</span>
                                                                            </b></div>
                                                                        <div class="px-3 text-green text-capitalize">
                                                                            <i class="fas fa-bed" style="color: #17c788;"></i>
                                                                            ' . htmlspecialchars($bedrooms) . ' Bedroom
                                                                        </div>
                                                                        <div class="bg-white quantity px-3 pt-3">
                                                                            <span class="location text-capitalize"><i
                                                                                    class="fas fa-map-marker-alt text-primary"></i>
                                                                                    ' . htmlspecialchars($address) . ', ' . htmlspecialchars($city) . '
                                                                            </span>
                                                                        </div>
                                                                        <div class="p-3 d-inline-block w-100">
                                                                            <div class="float-left text-capitalize">
                                                                                <i class="fas fa-user text-primary mr-1"></i> By:
                                                                                ' . htmlspecialchars($agentName) . '
                                                                            </div>
                                                                            <div class="float-right">
                                                                                <i class="far fa-calendar-alt text-primary mr-1"></i>' . $daysDifference . '
                                                                                Days Ago
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';

                                                } while (mysqli_stmt_fetch($result));

                                                mysqli_stmt_close($result);
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center">No records found.</td></tr>';
                                            }
                                        } else {
                                            error_log("Error in prepared statement: " . mysqli_error($conn));
                                        }

                                        mysqli_close($conn);

                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Recent Properties  -->

            <!--	Text Block One
        ======================================================-->
            <!-- Our Services -->
            <div class="full-row bg-gray">
                <div class="container">
                    <h2 class="text-secondary double-down-line text-center mb-5">Our Services</h2>
                    <div class="text-box-one">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 col-md-6 mb-4 mr-md-5">
                                <!-- Adjusted column classes to include left and right margin -->
                                <div class="p-4 text-center hover-bg-white hover-shadow rounded transation-3s">
                                    <i class="flaticon-rent text-primary flat-medium" aria-hidden="true"></i>
                                    <h5 class="text-secondary hover-text-primary py-3 m-0"><a href="#">Rental
                                            Services</a></h5>
                                    <p>Sheltar is an online listing platform that will enable individuals to search and
                                        secure affordable and quality housing.</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4 mr-md-5">
                                <!-- Adjusted column classes to include left and right margin -->
                                <div class="p-4 text-center hover-bg-white hover-shadow rounded transation-3s">
                                    <i class="flaticon-for-rent text-primary flat-medium" aria-hidden="true"></i>
                                    <h5 class="text-secondary hover-text-primary py-3 m-0"><a href="#">Moving
                                            Services</a></h5>
                                    <p>Additionally, we will also offer door to door moving services to our clients.</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4 mr-md-5">
                                <!-- Adjusted column classes to include left and right margin -->
                                <div class="p-4 text-center hover-bg-white hover-shadow rounded transation-3s">
                                    <i class="flaticon-list text-primary flat-medium" aria-hidden="true"></i>
                                    <h5 class="text-secondary hover-text-primary py-3 m-0"><a href="#">Property
                                            Listing</a></h5>
                                    <p>Sheltar is the best platform to use for listing your property. We guarantee more
                                        reach in the market</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services -->





            <!--	Why Choose Us -->
            <div class="full-row living bg-one overlay-secondary-half"
                style="background-image: url('images/haddyliving.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="living-list pr-4">
                                <h3 class="pb-4 mb-3 text-white">Why Choose Us</h3>
                                <ul>
                                    <li class="mb-4 text-white d-flex">
                                        <i class="flaticon-reward flat-medium float-left d-table mr-4 text-primary"
                                            aria-hidden="true"></i>
                                        <div class="pl-2">
                                            <h5 class="mb-3">Quality Assurance</h5>
                                            <p>Discover meticulously selected residences meticulously vetted by our team
                                                for unrivaled quality,ensuring you find a home that meets our stringent
                                                standards.</p>
                                        </div>
                                    </li>
                                    <li class="mb-4 text-white d-flex">
                                        <i class="flaticon-real-estate flat-medium float-left d-table mr-4 text-primary"
                                            aria-hidden="true"></i>
                                        <div class="pl-2">
                                            <h5 class="mb-3">Personalized Service</h5>
                                            <p>Experience a personalized journey to your dream home, where your unique
                                                preferences are understood and catered to with precision and care</p>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	why choose us -->

            <!--	How it work -->


            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-secondary double-down-line text-center mb-5">How It Works</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="icon-thumb-one text-center mb-5">
                                <div class="bg-primary text-white rounded-circle position-absolute z-index-9">1</div>
                                <div class="left-arrow"><i class="flaticon-investor flat-medium icon-primary"
                                        aria-hidden="true"></i></div>
                                <h5 class="text-secondary mt-5 mb-4"> Connect with Landlords</h5>
                                <p>We personally engage with landlords to enlist their properties on our platform,
                                    ensuring a diverse selection of properties.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="icon-thumb-one text-center mb-5">
                                <div class="bg-primary text-white rounded-circle position-absolute z-index-9">2</div>
                                <div class="left-arrow"><i class="flaticon-search flat-medium icon-primary"
                                        aria-hidden="true"></i></div>
                                <h5 class="text-secondary mt-5 mb-4">Explore and Inquire</h5>
                                <p>Simply log in to our system, browse through available properties, and send a message
                                    directly to the owner to express interest.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="icon-thumb-one text-center mb-5">
                                <div class="bg-primary text-white rounded-circle position-absolute z-index-9">3</div>
                                <div><i class="flaticon-handshake flat-medium icon-primary" aria-hidden="true"></i>
                                </div>
                                <h5 class="text-secondary mt-5 mb-4">Book a Visit</h5>
                                <p>Easily schedule a house visit through our platform, facilitating seamless
                                    communication between you and the property owner.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--	How It Work -->

            <!--	Achievement
        ============================================================-->
            <div class="full-row overlay-secondary"
                style="background-image: url('images/counterbg.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
                <div class="container">
                    <div class="fact-counter">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i
                                        class="flaticon-house flat-large text-white" aria-hidden="true"></i>
                                    <?php
                                    $query = mysqli_query($con, "SELECT count(pid) FROM property");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php
                                        $total = $row[0];
                                        echo $total; ?>">0</div>
                                    <?php } ?>
                                    <div class="text-white h5">Property Available</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i
                                        class="flaticon-house flat-large text-white" aria-hidden="true"></i>
                                    <?php
                                    $query = mysqli_query($con, "SELECT count(pid) FROM property where stype='sale'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php
                                        $total = $row[0];
                                        echo $total; ?>">0</div>
                                    <?php } ?>
                                    <div class="text-white h5">Sale Property Available</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i
                                        class="flaticon-house flat-large text-white" aria-hidden="true"></i>
                                    <?php
                                    $query = mysqli_query($con, "SELECT count(pid) FROM property where stype='rent'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php
                                        $total = $row[0];
                                        echo $total; ?>">0</div>
                                    <?php } ?>
                                    <div class="text-white h5">Rent Property Available</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="count wow text-center  mb-sm-50" data-wow-duration="300ms"> <i
                                        class="flaticon-man flat-large text-white" aria-hidden="true"></i>
                                    <?php
                                    $query = mysqli_query($con, "SELECT count(uid) FROM user");
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <div class="count-num text-primary my-4" data-speed="3000" data-stop="<?php
                                        $total = $row[0];
                                        echo $total; ?>">0</div>
                                    <?php } ?>
                                    <div class="text-white h5">Registered Users</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!--	Popular Places -->
            <div class="full-row bg-gray">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-secondary double-down-line text-center mb-5">Popular Places</h2>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6 col-lg-3 pb-1">
                                <div
                                    class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
                                    <img src="images/thumbnail4/1.jpg" alt="">
                                    <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                        <?php
                                        $query = mysqli_query($con, "SELECT count(state), property.* FROM property where state='Kitale'");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <h4 class="hover-text-primary text-capitalize"><a
                                                    href="stateproperty.php?id=<?php echo $row['state'] ?>"><?php echo $row['state']; ?></a>
                                            </h4>
                                            <span><?php
                                            $total = $row[0];
                                            echo $total; ?> Properties Listed</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 pb-1">
                                <div
                                    class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
                                    <img src="images/thumbnail4/2.jpg" alt="">
                                    <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                        <?php
                                        $query = mysqli_query($con, "SELECT count(state), property.* FROM property where state='Eldoret'");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <h4 class="hover-text-primary text-capitalize"><a
                                                    href="stateproperty.php?id=<?php echo $row['state'] ?>"><?php echo $row['state']; ?></a>
                                            </h4>
                                            <span><?php
                                            $total = $row[0];
                                            echo $total; ?> Properties Listed</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 pb-1">
                                <div
                                    class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
                                    <img src="images/thumbnail4/nai.jpg" alt="">
                                    <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                        <?php
                                        $query = mysqli_query($con, "SELECT count(state), property.* FROM property where state='Nairobi'");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <h4 class="hover-text-primary text-capitalize"><a
                                                    href="stateproperty.php?id=<?php echo $row['state'] ?>"><?php echo $row['state']; ?></a>
                                            </h4>
                                            <span><?php
                                            $total = $row[0];
                                            echo $total; ?> Properties Listed</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 pb-1">
                                <div
                                    class="overflow-hidden position-relative overlay-secondary hover-zoomer mx-n13 z-index-9">
                                    <img src="images/thumbnail4/4.jpg" alt="">
                                    <div class="text-white xy-center z-index-9 position-absolute text-center w-100">
                                        <?php
                                        $query = mysqli_query($con, "SELECT count(state), property.* FROM property where state='Thika'");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <h4 class="hover-text-primary text-capitalize"><a
                                                    href="stateproperty.php?id=<?php echo $row['state'] ?>"><?php echo $row['state']; ?></a>
                                            </h4>
                                            <span><?php
                                            $total = $row[0];
                                            echo $total; ?> Properties Listed</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Popular Places -->

            <!--	Testonomial -->
            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content-sidebar p-4">
                                <div class="mb-3 col-lg-12">
                                    <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">
                                        Testimonial</h4>
                                    <div class="recent-review owl-carousel owl-dots-gray owl-dots-hover-primary">

                                        <?php

                                        $query = mysqli_query($con, "select feedback.*, user.* from feedback,user where feedback.uid=user.uid and feedback.status='1'");
                                        while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                            <div class="item">
                                                <div class="p-4 bg-primary down-angle-white position-relative">
                                                    <p class="text-white"><i
                                                            class="fas fa-quote-left mr-2 text-white"></i><?php echo $row['2']; ?>.
                                                        <i class="fas fa-quote-right mr-2 text-white"></i>
                                                    </p>
                                                </div>
                                                <div class="p-2 mt-4">
                                                    <span
                                                        class="text-primary d-table text-capitalize"><?php echo $row['uname']; ?></span>
                                                    <span class="text-capitalize"><?php echo $row['utype']; ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Testonomial -->


            <!--	Footer   start-->
            <?php include ("include/footer.php"); ?>
            <!--	Footer   start-->


            <!-- Scroll to top -->
            <a href="#" class="bg-primary text-white hover-text-secondary" id="scroll"><i
                    class="fas fa-angle-up"></i></a>
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
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/tmpl.js"></script>
    <script src="js/jquery.dependClass-0.1.js"></script>
    <script src="js/draggable-0.1.js"></script>
    <script src="js/jquery.slider.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/YouTubePopUp.jquery.js"></script>
    <script src="js/validate.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>