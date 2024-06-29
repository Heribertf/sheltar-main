<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");
?>

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Property Listings</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Rejected Listings</h6>
                    <p class="text-muted mb-3">List of all rejected property submissions</p>
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
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once './includes/config.php';
                                include_once './includes/mysqli_connection.php';
                                $i = 1;

                                $sql = "SELECT pl.listing_id, pl.user_id, pl.property_name, pl.bedroom_count, pl.unit_price, pl.city, pl.address, pl.comment, CONCAT(u.first_name, ' ', u.last_name) AS agent_name, u.phone, u.email,
                                        CASE 
                                            WHEN pl.verification = 1 THEN 'Approved'
                                            WHEN pl.verification = 2 THEN 'Pending'
                                            WHEN pl.verification = 0 THEN 'Rejected'
                                            ELSE 'Pending' 
                                        END AS listingStatus,
                                        CASE 
                                            WHEN pl.listing_type = 1 THEN 'For Rent'
                                            WHEN pl.listing_type = 2 THEN 'For Sale'
                                            ELSE 'For Rent' 
                                        END AS listing_type,
                                        CASE 
                                            WHEN pl.property_use = 1 THEN 'Residential'
                                            WHEN pl.property_use = 2 THEN 'Commercial'
                                            ELSE 'Rensidential'
                                        END AS property_use,
                                        CASE 
                                            WHEN pl.property_type = 1 THEN 'Apartment'
                                            WHEN pl.property_type = 2 THEN 'Bungalow'
                                            WHEN pl.property_type = 3 THEN 'Mansionette'
                                            ELSE 'Apartment' 
                                        END AS property_type, va.user_id AS agentVerification
                                        FROM property_listing pl
                                        LEFT JOIN users u ON pl.user_id = u.user_id
                                        LEFT JOIN verified_agents va ON pl.user_id = va.user_id
                                        WHERE pl.verification = 0 AND pl.delete_flag = 0";

                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    mysqli_stmt_execute($stmt);

                                    mysqli_stmt_bind_result($stmt, $listingId, $userId, $propertyName, $bedroomCount, $unitPrice, $city, $address, $comment, $agentName, $agentPhone, $agentEmail, $listingStatus, $listingType, $propertyUse, $propertyType, $agentVerification);

                                    while (mysqli_stmt_fetch($stmt)) {
                                        if ($agentVerification == NULL) {
                                            $agentVerification = 'Not Verified';
                                            $statusClass = 'secondary';
                                        } else {
                                            $agentVerification = 'Verified';
                                            $statusClass = 'success';
                                        }
                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyName) . "</td>";
                                        echo "<td>" . htmlspecialchars($listingType) . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyUse) . "</td>";
                                        echo "<td>" . htmlspecialchars($propertyType) . "</td>";
                                        echo "<td>" . htmlspecialchars($bedroomCount) . "</td>";
                                        echo "<td>" . htmlspecialchars($city) . " - " . htmlspecialchars($address) . "</td>";

                                        if ($listingStatus === 'Active') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($listingStatus) . "</span></td>";
                                        } else {
                                            echo "<td><span class='badge border border-danger text-danger'>" . htmlspecialchars($listingStatus) . "</span></td>";
                                        }
                                        echo "<td>" . number_format($unitPrice, 2) . "</td>";
                                        echo "<td>" . htmlspecialchars($comment) . "</td>";

                                        echo "<td>
                                                <div class='dropdown'>
                                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                                        Actions
                                                    </button>
                                                    <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                        <li><a class='dropdown-item view-agent' href='#' data-bs-toggle='modal' data-bs-target='#exampleModalCenter' data-agent-name='" . htmlspecialchars($agentName) . "'
                                                        data-agent-contact='" . htmlspecialchars($agentPhone) . "' data-agent-email='" . htmlspecialchars($agentEmail) . "' data-agent-status='" . htmlspecialchars($agentVerification) . "' data-class='" . htmlspecialchars($statusClass) . "'>View Agent</a></li>
                                                    </ul>
                                                </div>
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
        const viewLinks = document.querySelectorAll('.view-agent');

        viewLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const agentName = this.getAttribute('data-agent-name');
                const agentContact = this.getAttribute('data-agent-contact');
                const agentEmail = this.getAttribute('data-agent-email');
                const agentStatus = this.getAttribute('data-agent-status');
                const statusClass = this.getAttribute('data-class');

                const editModalTitle = document.getElementById('exampleModalCenterTitle');
                const editModalBody = document.querySelector('.modal-body');

                editModalTitle.textContent = "View Agent Details";
                editModalBody.innerHTML = `
                <div class="card">
                    <div class="card-body">
                            <div class="mb-3">
                                <p class="card-text mb-3"><span class="card-title">Agent Name: </span>${agentName}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Contact: </span>${agentContact}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Email: </span>${agentEmail}</p>
                                <p class="card-text mb-3"><span class="card-title">Agent Status: </span><span class="badge rounded-pill border border-${statusClass} text-${statusClass}">${agentStatus}</span></p>

                            </div>
                    </div>
                </div>`;
            });
        });
    });

</script>

<?php
include ("./includes/footer_end.php");
?>