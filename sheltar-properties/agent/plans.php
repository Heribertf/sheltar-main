<?php
session_start();
include("./includes/header.php");
include("./includes/sidebar.php");
include("./includes/navbar.php");

include("../connection.php");
$session_user = $_SESSION["id"];
$currrentSubStatus = $_SESSION["sub_status"];
?>

<style>
    .open-modal-btn {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #00a54f;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .my-modal {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        width: 400px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: scale(0.8);
        opacity: 1;
        transition: transform 0.5s ease, opacity 0.5s ease;
    }

    .modal-overlay.active .modal {
        transform: scale(1);
        opacity: 1;
    }

    .card-info {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .mpesa-logo {
        width: 80px;
        height: auto;
        margin-right: 10px;
    }

    .card-details {
        font-size: 14px;
    }

    .amount-option {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .amount-option.selected {
        border-color: #00a54f;
        background-color: #e6f7ef;
    }

    .amount-option input[type="radio"] {
        margin-right: 10px;
    }

    .other-amount {
        display: flex;
        align-items: center;
    }

    .other-amount input[type="text"] {
        width: 100%;
        padding: 5px;
        margin-left: 5px;
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .go-back {
        color: #00a54f;
        text-decoration: none;
        cursor: pointer;
    }

    .pay-button {
        background-color: #00a54f;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .phone-number {
        margin-bottom: 15px;
    }

    .phone-number label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #333;
    }

    .phone-number-input {
        display: flex;
        align-items: center;
    }

    #phonePrefix {
        width: 40px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px 0 0 4px;
        background-color: #f0f0f0;
        text-align: center;
        font-size: 14px;
    }

    #phoneNumber {
        flex-grow: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-left: none;
        border-radius: 0 4px 4px 0;
        font-size: 14px;
    }

    #phoneNumber:focus,
    #phonePrefix:focus {
        outline: none;
        border-color: #00a54f;
    }
</style>

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
                                    <div class="col-md-6 stretch-card grid-margin grid-margin-md-0">
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
                                                    <button class="btn btn-' . htmlspecialchars($textType) . ' mt-4 payment-btn" data-plan-id="' . htmlspecialchars($planId) . '" data-key="' . htmlspecialchars($config["paystack"]["publicKey"]) . '" data-email="' . htmlspecialchars($_SESSION["email"]) . '" data-amount="' . htmlspecialchars($planPrice) . '" onclick="payWithMpesa(event, this)"' . ($currrentSubStatus == 1 ? ' disabled' : '') . '>Choose Plan</button>
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

    <div class="modal-overlay">
        <div class="my-modal">
            <div class="card-info">
                <img src="../assets/images/mpesalogo.png" alt="M-Pesa Logo" class="mpesa-logo">
                <div class="card-details">
                    <div>Sheltar Properties</div>
                    <div>+254 712 345678</div>
                </div>
            </div>

            <div class="alert alert-secondary" role="alert" id="alert-section" style="display: none">
                <h4 class="alert-heading" id="payment-status">Processing...</h4>
                <hr>
                <p class="mb-0" id="payment-message">Payment initiated. Please check your phone to complete the
                    transaction.</p>
            </div>

            <div class="amount-option selected">
                <label for="total-amount">Total amount due</label>
                <div id="plan-price">KSh 0</div>
            </div>
            <div class="phone-number">
                <label for="phoneNumber">Enter M-Pesa Phone Number</label>
                <div class="phone-number-input">
                    <input type="text" id="phonePrefix" value="254" readonly>
                    <input type="text" id="phoneNumber" maxlength="9" placeholder="7xxxxxxxx">
                </div>
            </div>
            <div class="actions">
                <a class="go-back">Cancel</a>
                <button class="pay-button" id="confirm-payment">PROCEED TO PAY</button>
            </div>
        </div>
    </div>

</div>


<?php
include("./includes/footer.php");
?>


<?php
include '../config.php';
?>
<script src="https://js.paystack.co/v1/inline.js"></script>

<script src="./assets/js/custom.js"></script>

<?php
include("./includes/footer_end.php");
?>