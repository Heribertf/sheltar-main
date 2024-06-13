<?php
include ("config.php");
include_once "./configuration.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $config["google"]["apiKey"]; ?>&libraries=places"></script>

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <link rel="stylesheet" href="/resources/demos/style.css">

    <!--	Title
    =========================================================-->
    <title>Sheltar-Movers</title>

    <style>
        /* Hide all form steps by default */
        .form-step {
            display: none;
        }

        /* Display the active form step */
        .form-step-active {
            display: block;
        }

        /* Style for progress bar */
        .progress {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            height: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #17c788;
            transition: width 0.3s ease;
            border-radius: 5px;
            /* width: 10%; */
        }

        /* .progress-step-container {
            display: flex;
            justify-content: space-between;
        }

        .progress-step {
            width: 20px;
            height: 20px;
            background-color: #ccc;
            border-radius: 50%;
        }

        .progress-step.active {
            background-color: #007bff;
        } */



        /* Additional styling for form inputs */
        .form-control {
            margin-bottom: 15px;
            /* Add some spacing between form inputs */
        }

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
            <?php include ("include/header.php"); ?>
            <!--	Header end  -->

            <!--	Banner -->
            <div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Sheltar Movers</b>
                            </h2>
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
                                    <li class="d-flex mb-4"> <i class="fas fa-home text-white mr-2 font-13 mt-1"></i>
                                        <div class="contact-address">
                                            <h5 class="text-white">Residential Move</h5>
                                            <span class="text-secondary">"Moving homes with care and precision."</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4"> <i
                                            class="fas fa-building text-white mr-2 font-13 mt-1"></i>
                                        <div class="contact-address">
                                            <h5 class="text-white">Commercial Move</h5>
                                            <span class="d-table text-secondary">"Seamless transitions for your
                                                business."</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4"> <i class="fas fa-box text-white mr-2 font-13 mt-1"></i>
                                        <div class="contact-address">
                                            <h5 class="text-white">Packing & Unpacking</h5>
                                            <span class="d-table text-secondary">"Effortless packing, stress-free
                                                unpacking."</span>
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
                                        <h2 class="text-secondary double-down-line text-center mb-5">Looking to move?
                                            Move with Us</h2>
                                        <?php echo $msg; ?><?php echo $error; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="progress mt-4 mb-4">
                                            <div class="progress-bar" id="progress" role="progressbar" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-step-container">
                                                <!-- <div class="progress-step"></div>
                                <div class="progress-step"></div>
                                <div class="progress-step"></div> -->
                                            </div>
                                        </div>

                                        <div id="loader">
                                            <div class="loader"></div>
                                        </div>

                                        <form id="quoteForm" class="container" method="post"
                                            enctype="multipart/form-data">

                                            <div id="warning-message" class="alert alert-danger" style="display: none;">
                                            </div>

                                            <!-- Step 1 -->
                                            <div class="form-step form-step-active">
                                                <div class="form-group">
                                                    <label for="name">Name:</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone:</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone"
                                                        required>
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                                </div>
                                            </div>
                                            <!-- Step 2 -->
                                            <div class="form-step">
                                                <div class="form-group">
                                                    <label for="current_address">Current Address:</label>
                                                    <input type="text" class="form-control autocomplete"
                                                        id="current_address" name="current_address" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="destination_address">Destination Address:</label>
                                                    <input type="text" class="form-control autocomplete"
                                                        id="destination_address" name="destination_address" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="moving_date">Moving Date:</label>
                                                    <input type="date" class="form-control" id="moving_date"
                                                        name="moving_date" required>
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="button"
                                                        class="btn btn-secondary prev-btn mr-2">Previous</button>
                                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                                </div>
                                            </div>
                                            <!-- Step 3 -->
                                            <div class="form-step">
                                                <div class="form-group">
                                                    <label for="rooms">Number of Rooms:</label>
                                                    <input type="number" class="form-control" id="rooms" name="rooms"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="additional_services">Additional Services:</label><br>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="packing"
                                                            name="additional_services[]" value="packing">
                                                        <label class="form-check-label" for="packing">Packing</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="unpacking"
                                                            name="additional_services[]" value="unpacking">
                                                        <label class="form-check-label"
                                                            for="unpacking">Unpacking</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="storage"
                                                            name="additional_services[]" value="storage">
                                                        <label class="form-check-label" for="storage">Storage</label>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="button"
                                                        class="btn btn-secondary prev-btn mr-2">Previous</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="message" class="container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!--	Quote Inforamtion -->

            <!--	Footer   start-->
            <?php include ("include/footer.php"); ?>
            <!--	Footer   start-->

            <!-- Scroll to top -->
            <a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i
                    class="fas fa-angle-up"></i></a>
            <!-- End Scroll To top -->
        </div>
    </div>
    <!-- Wrapper End -->

    <!--	Js Link
============================================================-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/custom.js"></script>
    <script>
        $(function () {
            $("#datepicker").datepicker();
            $("#datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
        });

        $(document).ready(function () {
            const steps = $('.form-step');
            const progress = $('.progress-bar');
            const progressSteps = $('.progress-step');

            let currentStep = 0;

            function initAutocomplete() {
                const currentAddressInput = document.getElementById('current_address');
                const destinationAddressInput = document.getElementById('destination_address');

                const currentAddressAutocomplete = new google.maps.places.Autocomplete(currentAddressInput);
                const destinationAddressAutocomplete = new google.maps.places.Autocomplete(destinationAddressInput);

                currentAddressAutocomplete.setFields(['formatted_address']);
                destinationAddressAutocomplete.setFields(['formatted_address']);

                currentAddressAutocomplete.addListener('place_changed', function () {
                    const place = currentAddressAutocomplete.getPlace();
                    currentAddressInput.value = place.formatted_address;
                });

                destinationAddressAutocomplete.addListener('place_changed', function () {
                    const place = destinationAddressAutocomplete.getPlace();
                    destinationAddressInput.value = place.formatted_address;
                });
            }


            function updateProgressBar() {
                const progressWidth = ((currentStep + 1) / steps.length) * 100 + '%';
                progress.css('width', progressWidth);

            }

            $('.next-btn').on('click', function () {
                const inputs = steps.eq(currentStep).find('input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="number"]');
                let isValid = true;
                inputs.each(function () {
                    if ($(this).val().trim() === '') {
                        isValid = false;
                        return false;
                    }
                });

                if (isValid) {
                    $('#warning-message').hide();

                    if (currentStep < steps.length - 1) {
                        steps.eq(currentStep).removeClass('form-step-active');
                        currentStep++;
                        steps.eq(currentStep).addClass('form-step-active');
                        updateProgressBar();
                    }
                } else {
                    $('#warning-message').text('Please fill in all fields before proceeding.').show();
                }
            });


            $('.prev-btn').on('click', function () {
                if (currentStep > 0) {
                    steps.eq(currentStep).removeClass('form-step-active');
                    currentStep--;
                    steps.eq(currentStep).addClass('form-step-active');
                    updateProgressBar();
                }
            });

            $('#quoteForm').submit(function (e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                console.log(formData);

                var loader = document.getElementById("loader");
                loader.style.display = "block";

                $.ajax({
                    type: 'POST',
                    url: './process-quote.php',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                        loader.style.display = "none";
                        if (response.success) {

                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                // showConfirmButton: false,
                                // timer: 2000
                            }).then((result) => {
                                $('#quoteForm')[0].reset();
                                steps.removeClass('form-step-active').eq(0).addClass('form-step-active');
                                currentStep = 0;
                                updateProgressBar();
                            });

                        } else {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "center",
                                stopOnFocus: true,
                                backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                            }).showToast();
                        }

                    },
                    error: function (xhr, status, error) {
                        loader.style.display = "none";
                        Toastify({
                            text: "An unexpected error occurred.",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            stopOnFocus: true,
                            backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                        }).showToast();
                    }
                });

            });

            initAutocomplete();
            updateProgressBar();
        });

    </script>
</body>

</html>