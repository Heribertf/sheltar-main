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
    <link rel="stylesheet" type="text/css" href="css/color.css">
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- <link rel="stylesheet" type="text/css" href="css/login.css"> -->
    <link rel="stylesheet" type="text/css" href="css/request-property.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <title>Request Property - Sheltar Properties</title>

    <style>
        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #075C52;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>



    <div id="page-wrapper">
        <div class="row">
            <!--	Header start  -->
            <?php include ("include/header.php"); ?>
            <!--	Header end  -->

            <!--	Banner   --->
            <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Submit Property
                                    Request</b>
                            </h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-left float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Request Property</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="full-row">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-secondary double-down-line text-center">Request Property</h2>
                        </div>
                    </div>
                    <div class=" p-5 bg-white" style="text-align: center;">
                        <div id="loader">
                            <div class="loader"></div>
                        </div>
                        <div id="svg_wrap"></div>
                        <form id="request-property-form" method="post" enctype="multipart/form-data">
                            <div id="warning-message" class="alert alert-danger"
                                style="display: none; max-width: 412px; margin: 0 auto;"></div>

                            <section>
                                <h5>Property details</h5>
                                <p>Property Category:</p>
                                <select class="form-select" id="property-category" name="property-category" required>
                                    <option value="Rent">For Rent</option>
                                    <option value="Buy">For Buy</option>
                                </select>
                                <p>Type of property:</p>
                                <select class="form-select" id="property-type" name="property-type" required>
                                    <option value="House">House</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Bedsitter">Bedsitter</option>
                                </select>
                                <p>No of bedrooms:</p>
                                <input type="number" id="rooms" name="rooms">
                            </section>

                            <section>
                                <h5>Budget</h5>
                                <p>Min price:</p>
                                <input type="text" id="min-price" name="min-price" required>
                                <p>Max price:</p>
                                <input type="text" id="max-price" name="max-price" required>
                                <p>Location:</p>
                                <input type="text" id="location" name="location" required>
                            </section>

                            <section>
                                <h5>More details</h5>
                                <p>Tell us more about your ideal property:</p>
                                <textarea class="form-control" name="property-details" id="property-details"
                                    rows="5"></textarea>
                            </section>

                            <section>
                                <h5>Contact details</h5>
                                <p>What is your preferred contact phone:</p>
                                <input type="tel" id="contact-phone" name="contact-phone" required>
                            </section>

                            <div class="button" id="prev">&larr; Previous</div>
                            <div class="button" id="next">Next &rarr;</div>
                            <button class="button" id="submit" type="submit">Submit Request</button>
                        </form>

                    </div>
                </div>
            </div>


            <?php include ("include/footer.php"); ?>

            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i
                    class="fas fa-angle-up"></i></a>
        </div>
    </div>
    <!-- Wrapper End -->

    <!--	Js Link
============================================================-->
    <script src="js/jquery.min.js"></script>
    <script src="js/tinymce/tinymce.min.js"></script>
    <script src="js/tinymce/init-tinymce.min.js"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/custom.js"></script>
    <script src="js/request-property.js"></script>

</body>

</html>