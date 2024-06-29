<?php
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");

include ("./upload.php");
?>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Verification</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="col-md-6 grid-margin stretch-card" id="first-form">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Request Verification</h6>
                        <div class="card text-white bg-info">
                            <!-- <div class="card-header">Header</div> -->
                            <div class="card-body">
                                <h5 class="card-title">Become verified as an agent/landloard</h5>
                                <p class="card-text">For your listings to be approved to go live, you are required to
                                    submit any legal documents proving ownership or lease agreement.</p>
                                <p class="card-text">If you wish to upload more than one document, combine all into a
                                    single document and upload as a pdf.</p>
                            </div>
                        </div>


                        <div class="mt-2">
                            <label for="exampleFormControlTextarea1" class="form-label">Are you an agent or
                                landloard?</label>

                        </div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="userType" id="agent">
                                <label class="form-check-label" for="agent">
                                    Agent
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="userType" id="landloard">
                                <label class="form-check-label" for="landloard">
                                    Landloard
                                </label>
                            </div>
                        </div>

                        <form class="forms-sample" enctype="multipart/form-data" method="post" id="agent-form">
                            <div id="agent-details" style="display: none;">
                                <div class="mb-3">
                                    <label for="agentName" class="form-label">Agent Name</label>
                                    <input type="text" class="form-control" id="agentName" name="agentName"
                                        placeholder="Enter your registered agent name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="agentNumber" class="form-label">Agent Registration Number</label>
                                    <input type="text" class="form-control" id="agentNumber" name="agentNumber"
                                        placeholder="Enter your agent registration number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="agentPhone" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" id="agentPhone" name="agentPhone"
                                        placeholder="Enter your contact mobile number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="agentSupportDoc" class="form-label">Supporting Document</label>
                                    <input type="file" class="form-control" name="agentSupportDoc" id="agentSupportDoc"
                                        required>
                                </div>
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary me-2">Submit</button>
                            </div>
                        </form>
                        <form class="forms-sample" enctype="multipart/form-data" method="post" id="landloard-form">
                            <div id="landloard-details" style="display: none;">
                                <div class="mb-3">
                                    <label for="landloardName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="landloardName" name="landloardName"
                                        placeholder="Enter your legal name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="plotNumber" class="form-label">Plot Number</label>
                                    <input type="text" class="form-control" id="plotNumber" name="plotNumber"
                                        placeholder="Enter your property plot number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="landloardPhone" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" id="landloardPhone" name="landloardPhone"
                                        placeholder="Enter your contact mobile number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="landloardSupportDoc" class="form-label">Supporting Document</label>
                                    <input type="file" class="form-control" name="landloardSupportDoc"
                                        id="landloardSupportDoc" required>
                                </div>
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary me-2">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Submitted Requests</h6>
                    <p class="text-muted mb-3">List of all submitted requests</p>
                    <div class="table-responsive">
                        <table id="dataTableImages" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                    <th>Comments</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once './includes/config.php';
                                include_once './includes/mysqli_connection.php';
                                $i = 1;

                                $session_user = $_SESSION["id"];
                                $sql = "SELECT id, DATE_FORMAT(submitted, '%d-%b-%Y') AS formattedDate, comment,
                                        CASE 
                                            WHEN status = 1 THEN 'Verified'
                                            WHEN status = 2 THEN 'Pending'
                                            WHEN status = 0 THEN 'Rejected'
                                            ELSE 'Pending' 
                                        END AS verificationStatus
                                        FROM verification 
                                        WHERE user_id = $session_user AND delete_flag = 0 ";

                                if ($stmt = mysqli_prepare($conn, $sql)) {
                                    mysqli_stmt_execute($stmt);

                                    mysqli_stmt_bind_result($stmt, $submissionId, $submissionDate, $comments, $verificationStatus);

                                    while (mysqli_stmt_fetch($stmt)) {
                                        echo "<tr>";
                                        echo "<td>" . $i++ . "</td>";
                                        echo "<td>" . htmlspecialchars($submissionDate) . "</td>";

                                        if ($verificationStatus === 'Verified') {
                                            echo "<td><span class='badge border border-success text-success'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        } elseif ($verificationStatus === 'Pending') {
                                            echo "<td><span class='badge border border-secondary text-secondary'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        } elseif ($verificationStatus === 'Rejected') {
                                            echo "<td><span class='badge border border-danger text-danger'>" . htmlspecialchars($verificationStatus) . "</span></td>";
                                        }

                                        echo "<td>" . htmlspecialchars($comments) . "</td>";

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
                        <h5 class="modal-title" id="exampleModalCenterTitle">View Image Detail</h5>
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
        var agentRadio = document.getElementById("agent");
        var landloardRadio = document.getElementById("landloard");
        var agentDetails = document.getElementById("agent-details");
        var landloardDetails = document.getElementById("landloard-details");

        function toggleDetails() {
            if (agentRadio.checked) {
                agentDetails.style.display = "block";
                landloardDetails.style.display = "none";
            } else if (landloardRadio.checked) {
                agentDetails.style.display = "none";
                landloardDetails.style.display = "block";
            }
        }

        agentRadio.addEventListener("change", toggleDetails);
        landloardRadio.addEventListener("change", toggleDetails);

        toggleDetails();

        $('#agent-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData($(this)[0]);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: './request-verification?type=agent',
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
                            // showConfirmButton: false,
                            // timer: 2000
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
                    })
                }
            });
        });

        $('#landloard-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData($(this)[0]);
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: './request-verification?type=landloard',
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
                            // showConfirmButton: false,
                            // timer: 2000
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
                    })
                }
            });
        });

    });

</script>


<?php
include ("./includes/footer_end.php");
?>