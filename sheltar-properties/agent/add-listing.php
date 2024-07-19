<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");
include_once "../../configuration.php";

?>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Listing</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Property Details</h6>
                    <form id="property-listing-form" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Listing Title</label>
                                    <input type="text" class="form-control" id="property-name" name="property-name"
                                        placeholder="Enter the title that will appear on the listing or the name identified with your property"
                                        required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Listing Type</label>
                                    <select class="form-select" id="listing-type" name="listing-type" required>
                                        <option value="1">For Rent</option>
                                        <option value="2">For Sale</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Property Use</label>
                                    <select class="form-select" id="property-use" name="property-use" required>
                                        <option value="1">Residential</option>
                                        <option value="2">Commercial</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Property Status</label>
                                    <select class="form-select" id="property-status" name="property-status" required>
                                        <option value="1">Unfurnished</option>
                                        <option value="2">Furnished</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Property Type</label>
                                    <select class="form-select" id="property-type" name="property-type" required>
                                        <option value="1">Apartment</option>
                                        <option value="2">Bungalow</option>
                                        <option value="3">Mansionette</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Number of Bedrooms</label>
                                    <input type="number" class="form-control" id="bedroom-count" name="bedroom-count"
                                        placeholder="Enter number of bedrooms per unit" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Monthly Rent (KSH)</label>
                                    <input type="text" class="form-control" id="unit-price" name="unit-price"
                                        placeholder="Enter the price per unit" required>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Available Units</label>
                                    <input type="number" class="form-control" id="available-units"
                                        name="available-units" placeholder="Enter number of vacant units" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Units</label>
                                    <input type="number" class="form-control" id="total-units" name="total-units"
                                        placeholder="Enter number of total units for the property" required>
                                </div>
                            </div> -->

                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Enter Property Description</label>
                                    <textarea class="form-control" name="property-desc" id="property-desc"
                                        rows="5"></textarea>

                                </div>
                            </div>

                        </div><!-- Row -->

                        <div class="mb-4">
                            <h6 class="card-title">Features</h6>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Master Ensuite">
                                <label class="form-check-label" for="checkInline">
                                    Master Ensuite
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Parking">
                                <label class="form-check-label" for="checkInline">
                                    Parking
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="DSQ">
                                <label class="form-check-label" for="checkInline">
                                    DSQ
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Open Kitchen">
                                <label class="form-check-label" for="checkInline">
                                    Open Kitchen
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Swimming">
                                <label class="form-check-label" for="checkInline">
                                    Swimming Pool
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Internet">
                                <label class="form-check-label" for="checkInline">
                                    Internet
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Gym">
                                <label class="form-check-label" for="checkInline">
                                    Gym
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Security">
                                <label class="form-check-label" for="checkInline">
                                    Security
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Elevator">
                                <label class="form-check-label" for="checkInline">
                                    Elevator
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="checkInline" name="features[]"
                                    value="Own Compound">
                                <label class="form-check-label" for="checkInline">
                                    Own Compound
                                </label>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <h4 class="card-title">Location</h4>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" placeholder="Enter City/Town e.g Kasarani"
                                        id="city" name="city" required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" placeholder="Enter address e.g Seasons"
                                        id="address" name="address" required>
                                </div>
                            </div><!-- Col -->

                            <!-- Map for selecting coordinates -->
                            <div class="col-sm-12">
                                <div class="mb-2">
                                    <label class="form-label">Select Location on Map to be Displayed on Website:</label>
                                    <div id="map" style="height: 400px; width: 100%;"></div>

                                </div>

                            </div>
                            <div class="col-sm-6 mb-3">
                                <input type="text" class="form-control" id="latitude" name="latitude" required readonly>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <input type="text" class="form-control" id="longitude" name="longitude" required
                                    readonly>
                            </div>

                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Upload Property Images</label>
                                    <input type="file" class="form-control" id="propertyImages" name="propertyImages[]"
                                        accept="image/*" multiple onchange="previewImages()" required>
                                </div>
                                <div id="imagePreview"></div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <button type="submit" class="mt-3 btn btn-primary submit">Submit Listing</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include ("./includes/footer.php");
?>
<script>
    function initMap() {
        var defaultLocation = { lat: -1.2921, lng: 36.8219 }; // Default location (Nairobi)
        var map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 13
        });
        var marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        // Update lat/long values in the form fields
        function updateLatLongFields(latLng) {
            document.getElementById('latitude').value = latLng.lat();
            document.getElementById('longitude').value = latLng.lng();
        }

        // Add event listener to update lat/long when the marker is moved
        marker.addListener('dragend', function () {
            updateLatLongFields(marker.getPosition());
        });

        // Add event listener to update lat/long when the map is clicked
        map.addListener('click', function (event) {
            marker.setPosition(event.latLng);
            updateLatLongFields(event.latLng);
        });

        // Initialize the lat/long values in the form fields
        updateLatLongFields(defaultLocation);
    }

    // Load the Google Maps API and initialize the map
    function loadGoogleMapsApi() {
        var script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=<?php echo $config["google"]["apiKey"]; ?>&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadGoogleMapsApi();
    });

    function previewImages() {
        var preview = document.getElementById('imagePreview');
        var files = document.getElementById('propertyImages').files;
        var maxImages = 6;

        // Clear previous previews
        preview.innerHTML = '';

        if (files.length > maxImages) {
            preview.innerHTML = 'Maximum ' + maxImages + ' images allowed';
            return;
        }

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = (function (file) {
                    return function (e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.maxHeight = '200px';
                        preview.appendChild(img);
                    };
                })(file);
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = 'Invalid file format. Please select image files.';
            }
        }
    }

    $(document).ready(function () {
        $('#property-listing-form').submit(function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            console.log(formData);
            $.ajax({
                type: 'POST',
                url: './submit-listing',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message
                        }).then((result) => {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.'
                    })
                }
            });
        });
    });
</script>

<?php
include ("./includes/footer_end.php");
?>