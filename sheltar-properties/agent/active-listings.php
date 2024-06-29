<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");
?>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Listings</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Active Listings</h6>
                    <p class="text-muted mb-3">List of all active property listings</p>
                    <div class="table-responsive">
                        <table id="dataTableUser" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Use</th>
                                    <th>Type</th>
                                    <th>Bedrooms</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once './includes/config.php';
                                include_once './includes/mysqli_connection.php';
                                $i = 1;
                                $session_user = $_SESSION["id"];

                                $sql = "SELECT listing_id, property_name, bedroom_count, unit_price, city, address ,IF(verification = 1, 'Active', 'Pending') AS verificationStatus,
                                        CASE 
                                            WHEN listing_type = 1 THEN 'For Rent'
                                            WHEN listing_type = 2 THEN 'For Sale'
                                            ELSE 'For Rent' 
                                        END AS listing_type,
                                        CASE 
                                            WHEN property_use = 1 THEN 'Residential'
                                            WHEN property_use = 2 THEN 'Commercial'
                                            ELSE 'Rensidential'
                                        END AS property_use,
                                        CASE 
                                            WHEN property_type = 1 THEN 'Apartment'
                                            WHEN property_type = 2 THEN 'Bungalow'
                                            WHEN property_type = 3 THEN 'Mansionette'
                                            ELSE 'Apartment' 
                                        END AS property_type
                                        FROM property_listing 
                                        WHERE verification = 1 AND user_id = $session_user AND delete_flag = 0 ";

                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    mysqli_stmt_execute($stmt);

                                    mysqli_stmt_bind_result($stmt, $listingId, $propertyName, $bedroomCount, $unitPrice, $city, $address, $verificationStatus, $listingType, $propertyUse, $propertyType);

                                    while (mysqli_stmt_fetch($stmt)) {
                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyName) . "</td>";
                                        echo "<td>" . htmlspecialchars($listingType) . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyUse) . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyType) . "</td>";
                                        echo "<td>" . htmlspecialchars($bedroomCount) . "</td>";
                                        echo "<td>" . htmlspecialchars($city) . " - " . htmlspecialchars($address) . "</td>";

                                        if ($verificationStatus === 'Active') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        } else {
                                            echo "<td><span class='badge border border-secodary text-secondary'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        }
                                        echo "<td>" . number_format($unitPrice, 2) . "</td>";

                                        echo "<td>
                                                <a href='#' class='btn btn-primary edit-listing' data-bs-toggle='modal' data-bs-target='#exampleModalCenter' data-listing-id='" . htmlspecialchars($listingId) . "' 
                                                data-property-name='" . htmlspecialchars($propertyName) . "' data-property-price='" . htmlspecialchars($unitPrice) . "'>
                                                    <h5>Update</h5>
                                                </a>
                                                <a href='#' class='btn btn-danger delete-listing'  dataListingId='" . htmlspecialchars($listingId) . "'>
                                                    <h5>Delete</h5>
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }

                                    mysqli_stmt_close($stmt);
                                } else {
                                    echo "Error in prepared statement: " . mysqli_error($conn);
                                }

                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Update Listing Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
include ("./includes/footer.php");
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editLinks = document.querySelectorAll('.edit-listing');

        editLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const listingId = this.getAttribute('data-listing-id');
                const propertyName = this.getAttribute('data-property-name');
                const unitPrice = this.getAttribute('data-property-price');

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "Update Listing Details";
                editModalBody.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Updating : ${propertyName}</h6>
                        <form class="forms-sample" enctype="multipart/form-data" method="post" id="listing-edit-form">
                            <div class="mb-3">
                                    <input type="hidden" name="edit_listing_id" value="${listingId}" required>
                                </div>
                            <div class="mb-3">
                                <label for="">Property Name:</label>
                                <input class="form-control" type="text" id="edit_property_name" name="edit_property_name" value="${propertyName}" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Unit Price (Ksh):</label>
                                <input class="form-control" type="number" id="edit_unit_price" name="edit_unit_price" value="${unitPrice}" required>
                            </div>
                            <button type="submit" name="submit" value="submit"
                                class="btn btn-primary me-2">Update</button>
                    </form>
                    </div>
                </div>`;


                const editForm = document.getElementById('listing-edit-form');

                editForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(editForm)

                    $.ajax({
                        type: 'POST',
                        url: './update-listing',
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
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.'
                            });
                        }
                    });
                });
            });
        });

    });
    $(document).ready(function () {
        $('.delete-listing').on('click', function (e) {
            e.preventDefault();

            const listingId = this.getAttribute('dataListingId');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger me-2'
                },
                buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
                title: 'Warning!',
                text: "Are you sure you want to delete this listing?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'me-2',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: './delete-listing',
                        data: {
                            listingId: listingId
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Listing Deleted',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.'
                            });
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Deletion aborted',
                        'error'
                    )
                }
            });
        });
    })
</script>

<?php
include ("./includes/footer_end.php");
?>