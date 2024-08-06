<?php
session_start();
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");
include_once './includes/config.php';
include_once './includes/mysqli_connection.php';
?>

<div class="page-content">

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome <?php echo htmlspecialchars($_SESSION["fullname"]); ?> to admin dashboard
            </h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group flatpickr wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
                <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle><i
                        data-feather="calendar" class="text-primary"></i></span>
                <input type="text" class="form-control bg-transparent border-primary" placeholder="Select date"
                    data-input>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Active Listings</h6>

                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">
                                        <?php
                                        echo $conn->query("SELECT listing_id FROM `property_listing` WHERE verification = 1 AND delete_flag = 0 ")->num_rows;
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="./active-listings" class="btn btn-primary" role="button">View
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Pending Listings</h6>

                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">
                                        <?php
                                        echo $conn->query("SELECT listing_id FROM `property_listing` WHERE verification = 2 AND delete_flag = 0 ")->num_rows;
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="./pending-review" class="btn btn-primary" role="button">View
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Rejected Listings</h6>

                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">
                                        <?php
                                        echo $conn->query("SELECT listing_id FROM `property_listing` WHERE verification = 0 AND delete_flag = 0")->num_rows;
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="./rejected-listings" class="btn btn-primary" role="button">View
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Verified Agents</h6>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">
                                        <?php
                                        echo $conn->query("SELECT id FROM `verified_agents`")->num_rows;
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="./verified-agents" class="btn btn-primary" role="button">View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Un-Verified Agents</h6>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">
                                        <?php
                                        echo $conn->query("SELECT DISTINCT user_id FROM `verification` WHERE status = 0 AND delete_flag = 0")->num_rows;
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="#" class="btn btn-primary" role="button">View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- row -->


</div>

<?php
include ("./includes/footer.php");
?>