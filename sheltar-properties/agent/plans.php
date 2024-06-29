<?php
session_start();
include ("./includes/header.php");
include ("./includes/sidebar.php");
include ("./includes/navbar.php");

include ("../connection.php");
$session_user = $_SESSION["id"];
$currrentSubStatus = $_SESSION["sub_status"];
?>

<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Plans</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">My Plan:
                                    <span
                                        class="text-primary"><?php echo htmlspecialchars($_SESSION["subscription"]); ?></span>
                                </h6>

                            </div>
                            <div class="row mt-3">
                                <div class="col-12 col-md-12 col-xl-6">
                                    <h3 class="mb-2"> Ends in:
                                        <span
                                            class="text-secondary"><?php echo htmlspecialchars($_SESSION["sub_expiry"]); ?></span>
                                    </h3>
                                </div>
                                <!-- <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-end">
                                    <a href="./add-listing" class="btn btn-primary btn-icon-text" role="button">Add
                                        Listing <i class="btn-icon-append" data-feather="plus-circle"></i></a>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- row -->

    <div class="row">
        <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h2 class="text-center mb-3 mt-4">Choose a plan</h2>
                    <p class="text-muted text-center mb-4 pb-2">Choose the features and functionality your team
                        need today.
                        Easily upgrade as your company grows.</p>
                    <div class="container">
                        <div class="row">

                            <?php
                            $sql = "SELECT feature_id, feature_name FROM features";
                            $result1 = $conn->query($sql);
                            $allFeatures = [];
                            $featureIds = [];
                            if ($result1->num_rows > 0) {
                                while ($row = $result1->fetch_assoc()) {
                                    $featureIds[] = $row['feature_id'];
                                    $allFeatures[] = $row['feature_name'];
                                }
                            }


                            $query = "SELECT plan_id, plan_name, plan_price, plan_features FROM plans WHERE plan_status = 'Active' AND delete_flag = 0 ORDER BY plan_price ASC";
                            $result2 = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result2) > 0) {
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $planId = $row["plan_id"];
                                    $planName = $row['plan_name'];
                                    $planPrice = $row['plan_price'];
                                    $planFeatures = $row['plan_features'];

                                    $splitFeatures = preg_split('/\r\n|\r|\n|,/', $planFeatures);
                                    $features = array_unique(array_map('trim', $splitFeatures)); // Trim spaces and remove duplicates
                            
                                    if ($planName == "Free") {
                                        $iconType = "gift";
                                        $textType = "primary";
                                    } elseif ($planName == "Professional") {
                                        $iconType = "package";
                                        $textType = "success";
                                    } elseif ($planName == "Basic") {
                                        $iconType = "award";
                                        $textType = "primary";
                                    } elseif ($planName == "Premium") {
                                        $iconType = "briefcase";
                                        $textType = "primary";
                                    }

                                    $i = 0;

                                    switch ($planId) {
                                        case 1:
                                            $specificFeature = ["10 per month", "Email support", "Standard", "Standard", "Basic", "Basic", "-"];
                                            $listingLimit = "Up to 10 listings";
                                            break;
                                        case 2:
                                            $specificFeature = ["50 per month", "Priority email and chat", "Enhanced", "Enhanced", "Advanced", "Detailed", "Basic"];
                                            $listingLimit = "Up to 50 listings";
                                            break;
                                        case 3:
                                            $specificFeature = ["Unlimited", "24/7 phone, email, chat", "Premium", "Top-tier", "Full suite", "Comprehensive", "Advanced"];
                                            $listingLimit = "Ulimited listings";
                                            break;
                                    }

                                    echo '
                                    <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center mt-3 mb-4">' . htmlspecialchars($planName) . '</h4>
                                            <i data-feather="' . htmlspecialchars($iconType) . '" class="text-' . htmlspecialchars($textType) . ' icon-xxl d-block mx-auto my-3"></i>
                                            <h1 class="text-center">Ksh ' . number_format($planPrice) . '</h1>
                                            <p class="text-muted text-center mb-4 fw-light">per month</p>
                                            <h5 class="text-' . htmlspecialchars($textType) . ' text-center mb-4">' . htmlspecialchars($listingLimit) . '</h5>
                                    ';

                                    echo '<table class="mx-auto">';

                                    foreach ($allFeatures as $index => $feature) {
                                        if (in_array($featureIds[$index], $features)) {
                                            $dataFeather = "check";
                                            $textTypeOne = "text-primary";
                                            $textTypeTwo = "no-specified-class";
                                        } else {
                                            $dataFeather = "x";
                                            $textTypeOne = "text-danger";
                                            $textTypeTwo = "text-muted";
                                        }
                                        echo '
                                        <tr>
                                            <td><i data-feather="' . htmlspecialchars($dataFeather) . '" class="icon-md ' . htmlspecialchars($textTypeOne) . ' me-2"></i>
                                            </td>
                                            <td>
                                                <p class=""> <span class="text-primary">' . htmlspecialchars($feature) . ': </span>' . htmlspecialchars($specificFeature[$i++]) . '</p>
                                            </td>
                                        </tr>
                                        ';
                                    }
                                    echo '</table>';

                                    echo '
                                                    <div class="d-grid">
                                                    <button class="btn btn-' . htmlspecialchars($textType) . ' mt-4 payment-btn" data-plan-id="' . htmlspecialchars($planId) . '" data-key="' . htmlspecialchars($config["paystack"]["publicKey"]) . '" data-email="' . htmlspecialchars($_SESSION["email"]) . '" data-amount="' . htmlspecialchars($planPrice) . '" onclick="payWithPaystack(event, this)"' . ($currrentSubStatus == 1 ? ' disabled' : '') . '>Choose Plan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }
                            } else {
                                echo 'No plans available';
                            }
                            mysqli_close($conn);
                            ?>
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


<?php
include '../config.php';
?>
<script src="https://js.paystack.co/v1/inline.js"></script>

<script src="./assets/js/custom.js"></script>

<?php
include ("./includes/footer_end.php");
?>