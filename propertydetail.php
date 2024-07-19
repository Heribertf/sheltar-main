<?php
ini_set('session.cache_limiter', 'public');
session_cache_limiter(false);
session_start();
include_once './connection1.php';
include_once './connection2.php';
include_once "./configuration.php";

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
    <link rel="shortcut icon" href="images/favicon.ico">

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <title>Property Details - Sheltar Properties</title>

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

        #description-container {
            /* position: relative; */
            max-height: 150px;
            /* Set a height that shows only part of the description */
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        #description-container.expanded {
            max-height: none;
        }

        #read-more {
            /* display: inline-block; */
            margin-top: 10px;
            color: var(--theme-primary-color);
            cursor: pointer;
        }

        #read-more:active,
        #read-more:focus {
            outline: none;
            text-decoration: none;
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
                            <h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Property Detail</b>
                            </h2>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="breadcrumb" class="float-left float-md-right">
                                <ol class="breadcrumb bg-transparent m-0 p-0">
                                    <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Property Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--	Banner   --->


            <div class="full-row">
                <div class="container">
                    <div class="row">

                        <div id="loader">
                            <div class="loader"></div>
                        </div>

                        <?php
                        $listing_id = $_GET['pid'];
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
                                            END AS property_type, pl.bedroom_count, pl.unit_price, pl.property_description, pl.features, pl.image_paths, pl.city, pl.address, pl.latitude, pl.longitude, DATE_FORMAT(pl.added, '%d-%b-%Y') AS dateAdded,
                                        CONCAT(u.first_name, ' ', u.last_name) AS agentName, u.phone, u.email, u.profileImage
                                        FROM 
                                            property_listing pl
                                        LEFT JOIN
                                            users u ON pl.user_id = u.user_id
                                        WHERE pl.listing_id = ?
                                        ORDER BY pl.added DESC";

                        if ($result = mysqli_prepare($conn, $query)) {
                            mysqli_stmt_bind_param($result, "i", $listing_id);
                            mysqli_stmt_execute($result);

                            mysqli_stmt_bind_result($result, $listingId, $propertyName, $listingType, $proertyUse, $propertyStatus, $propertyType, $bedrooms, $price, $description, $features, $images, $city, $address, $latitude, $longitude, $dateAdded, $agentName, $agentPhone, $agentEmail, $agentProfile);


                            if (mysqli_stmt_fetch($result)) {
                                $customMessage = "Hi there, I am interested in your property " . $propertyName . ", listed on http://localhost/sheltar-main/propertydetail.php?pid=" . $listingId;
                                do {

                                    $duration = '';
                                    $propertyFeatures = preg_split('/\r\n|\r|\n|,/', $features);
                                    $imagePaths = preg_split('/\s*,\s*/', $images);
                                    if ($listingType == 'For Rent') {
                                        $duration = ' / month';
                                    }

                                    echo '
                                            <div class="col-lg-8">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="single-property" style="width:1200px; height:700px; margin:30px auto 50px;">';

                                    foreach ($imagePaths as $imagePath) {
                                        echo '<div class="ls-slide" data-ls="duration:7500; transition2d:5; kenburnszoom:in; kenburnsscale:1.2;"> <img width="1920" height="1080" src="sheltar-properties/uploads/property-images/' . htmlspecialchars($imagePath) . '" class="ls-bg" alt="" /> </div>';
                                    }

                                    echo '</div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="bg-primary d-table px-3 py-2 rounded text-white text-capitalize">' . htmlspecialchars($listingType) . '</div>
                                                    <h5 class="mt-2 text-secondary text-capitalize">' . htmlspecialchars($propertyName) . '</h5>
                                                    <span class="mb-sm-20 d-block text-capitalize"><i class="fas fa-map-marker-alt text-primary font-12"></i> &nbsp;' . htmlspecialchars($address) . ', ' . htmlspecialchars($city) . '</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-primary text-left h5 my-2 text-md-right">Ksh
                                                    ' . number_format($price) . '<span class="text-sm">' . htmlspecialchars($duration) . '</span></div>
                                                    <div class="text-left text-md-right">Price</div>
                                                </div>
                                            </div>
                                            <div class="property-details">
                                                 

                                            <h4 class="text-secondary my-4">Description</h4>
                                            <div id="description-container">
                                                <p id="description-text">' . nl2br($description) . '</p>
                                            </div>
                                            
                                            <a href="javascript:void(0)" id="read-more">Read More</a>

                                                
                                                <h5 class="mt-5 mb-4 text-secondary">Property Summary</h5>
                                                <div  class="table-striped font-14 pb-2">
                                                    <table class="w-100">
                                                        <tbody>
                                                            <tr>
                                                                <td>Type:</td>
                                                                <td class="text-capitalize">' . htmlspecialchars($listingType) . '</td>
                                                                <td>Status :</td>
                                                                <td class="text-capitalize">Available</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Beds :</td>
                                                                <td class="text-capitalize">' . htmlspecialchars($bedrooms) . '</td>
                                                                <td>Baths :</td>
                                                                <td class="text-capitalize"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>City :</td>
                                                                <td class="text-capitalize">' . htmlspecialchars($city) . '</td>
                                                                <td>Address :</td>
                                                                <td class="text-capitalize">' . htmlspecialchars($address) . '</td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <h5 class="mt-5 mb-4 text-secondary">Features</h5>
                                                <div class="row">
                                                    <ul>';

                                    foreach ($propertyFeatures as $propertyFeature) {
                                        echo '    <li>• ' . htmlspecialchars($propertyFeature) . '</li>';
                                    }

                                    echo '</ul>
                                                    
                                                </div>
                    
                                                <h5 class="mt-5 mb-4 text-secondary double-down-line-left position-relative">Contact Agent</h5>
                                                <div class="agent-contact pt-60">
                                                    <div class="row">
                                                        <div class="col-sm-4 col-lg-3"> <img src="sheltar-properties/uploads/profile-images/' . htmlspecialchars($agentProfile) . '" alt="agent profile" style="object-fit: cover;" height="200" width="170"> </div>
                                                        <div class="col-sm-8 col-lg-9">
                                                            <div class="agent-data text-ordinary mt-sm-20">
                                                                <h6 class="text-primary text-capitalize">
                                                                ' . htmlspecialchars($agentName) . ' 
                                                                <img src="images/verified-badge.png" alt="verified badge" style="width: 20px; height: 20px; margin-left: 5px;">
                                                                </h6>
                                                                <ul class="mb-3">
                                                                    <li>' . htmlspecialchars($agentEmail) . '</li>
                                                                </ul>
                                                        
                                                            </div>
                                                            <div>
                                                            <a href="https://wa.me/' . htmlspecialchars($agentPhone) . '?text=' . htmlspecialchars($customMessage) . '" class="btn btn-primary mt-4"> <i class="fab fa-whatsapp"></i> Whatsapp Agent</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-12">
                                                            
                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                            <h4 class="text-secondary my-4">Property Location</h4>
                                            <div id="map" style="width: 100%; height: 400px;"></div>
                                        </div>

                                        </div>
                                                ';

                                } while (mysqli_stmt_fetch($result));

                                mysqli_stmt_close($result);
                            } else {
                                echo '<div class="col-md-12" >
                                                <p class="text-secondary" >Property not found!</p>
                                            </div>';
                            }
                        } else {
                            error_log("Error in prepared statement: " . mysqli_error($conn));
                        }

                        ?>

                        <!-- Google Maps JavaScript API -->
                        <script async defer
                            src="https://maps.googleapis.com/maps/api/js?key=<?php echo $config["google"]["apiKey"]; ?>&callback=initMap"></script>

                        <script>
                            function initMap() {

                                var defaultLocation = { lat: -1.2921, lng: 36.8219 };
                                var propertyLocation = {
                                    lat: <?php echo isset($latitude) && !empty($latitude) ? $latitude : 'null'; ?>,
                                    lng: <?php echo isset($longitude) && !empty($longitude) ? $longitude : 'null'; ?>
                                };

                                // Check if the propertyLocation is valid, otherwise use defaultLocation
                                if (propertyLocation.lat === null || propertyLocation.lng === null) {
                                    propertyLocation = defaultLocation;
                                }

                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 15,
                                    center: propertyLocation
                                });

                                var marker = new google.maps.Marker({
                                    position: propertyLocation,
                                    map: map
                                });
                            }
                        </script>

                        <!-- <script>
                            function initMap() {
                                var address = "Roysambu, Nairobi, Kenya";

                                var geocoder = new google.maps.Geocoder();
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    zoom: 15,
                                    center: { lat: -34.397, lng: 150.644 }  // Default center
                                });

                                geocoder.geocode({ 'address': address }, function (results, status) {
                                    if (status === 'OK') {
                                        map.setCenter(results[0].geometry.location);
                                        var marker = new google.maps.Marker({
                                            map: map,
                                            position: results[0].geometry.location
                                        });
                                    } else {
                                        alert('Geocode was not successful for the following reason: ' + status);
                                    }
                                });
                            }
                        </script> -->



                        <div class="col-lg-4">
                            <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4 mt-md-50">Send
                                Message to Agent</h4>
                            <form method="post" enctype="multipart/form-data" id="contact-agent">
                                <div class="row">
                                    <div>
                                        <input type="hidden" name="agent-name" value="<?php echo $agentName ?>"
                                            readonly>
                                        <input type="hidden" name="agent-email" value="<?php echo $agentEmail ?>"
                                            readonly>
                                        <input type="hidden" name="property-name" value="<?php echo $propertyName ?>"
                                            readonly>
                                        <input type="hidden" name="property-id" value="<?php echo $listingId ?>"
                                            readonly>


                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="client-name"
                                                placeholder="Enter Your Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="client-email"
                                                placeholder="Enter Your Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="client-phone"
                                                placeholder="Enter Your Phone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="contact-message"
                                                placeholder="Enter Message"
                                                required>Hi there, I'd like to know more about this property. Please get in touch with me.</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <h4 class="double-down-line-left text-secondary position-relative pb-4 my-4">Message Agent
                                on Whatsapp</h4>

                            <a href="https://wa.me/<?php echo $agentPhone ?>?text=<?php echo $customMessage ?>"
                                class="btn btn-primary mt-4"> <i class="fab fa-whatsapp"></i> Whatsapp</a>


                            <div class="sidebar-widget mt-5">
                                <h4 class="double-down-line-left text-secondary position-relative pb-4 mb-4">Recently
                                    Added</h4>
                                <ul class="property_list_widget">

                                    <?php
                                    $recentQuery = mysqli_query($conn, "SELECT listing_id, property_name, image_paths, city, address
                                        FROM 
                                            property_listing
                                        WHERE listing_id != $listing_id
                                        ORDER BY added DESC LIMIT 6");

                                    while ($row = mysqli_fetch_array($recentQuery)) {
                                        $imagePaths = preg_split('/\s*,\s*/', $row['image_paths']);

                                        ?>
                                        <li>
                                            <a href="propertydetail.php?pid=<?php echo $row['listing_id']; ?>"><img
                                                    src="sheltar-properties/uploads/property-images/<?php echo $imagePaths[0]; ?>"
                                                    alt="property image"></a>
                                            <h6 class="text-secondary hover-text-primary text-capitalize"><a
                                                    href="propertydetail.php?pid=<?php echo $row['listing_id']; ?>">
                                                    <?php echo $row['property_name']; ?>
                                                </a></h6>
                                            <span class="font-14"><i
                                                    class="fas fa-map-marker-alt icon-primary icon-small"></i>
                                                <?php echo $row['address'] . ', ' . $row['city']; ?>
                                            </span>

                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/custom.js"></script>
    <script src="js/contact.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var readMoreBtn = document.getElementById('read-more');
            var descriptionContainer = document.getElementById('description-container');

            readMoreBtn.addEventListener('click', function () {
                descriptionContainer.classList.toggle('expanded');
                if (descriptionContainer.classList.contains('expanded')) {
                    readMoreBtn.textContent = 'Read Less';
                } else {
                    readMoreBtn.textContent = 'Read More';
                }
            });
        });
    </script>



</body>

</html>