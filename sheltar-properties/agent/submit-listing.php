<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$targetDir = "../uploads/property-images/";

$response = [
    'success' => false,
    'message' => 'Cannot complete request.'
];

$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'JPG', 'PNG', 'JPEG', 'GIF');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $propertyName = $_POST['property-name'];
    $listingType = $_POST['listing-type'];
    $propertyUse = $_POST['property-use'];
    $propertyStatus = $_POST['property-status'];
    $propertyType = $_POST['property-type'];
    $bedroomCount = $_POST['bedroom-count'];
    $unitPrice = $_POST['unit-price'];
    // $availableUnits = $_POST['available-units'];
    // $totalUnits = $_POST['total-units'];
    $propertyDescription = $_POST['property-desc'];
    $features = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $city = $_POST['city'];
    $address = $_POST['address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $userId = $_SESSION["id"];

    // Check if the agent is verified
    $verifiedQuery = "SELECT COUNT(*) FROM verified_agents WHERE user_id = ?";
    $stmt1 = $conn->prepare($verifiedQuery);
    $stmt1->bind_param("i", $userId);
    $stmt1->execute();
    $stmt1->bind_result($isVerifiedCount);
    $stmt1->fetch();
    $stmt1->close();

    if ($isVerifiedCount == 0) {
        $response = [
            'success' => false,
            'message' => 'Your agent status is not verified. Please request a verification to be able to submit listings.',
            'redirect' => './agent-verification'
        ];
        echo json_encode($response);
        exit();
    }

    // Check if agent has an active subscription
    $subscriptionQuery = "SELECT status FROM subscriptions WHERE user_id = ?";
    $stmt2 = $conn->prepare($subscriptionQuery);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $stmt2->bind_result($subscriptionStatus);

    if ($stmt2->fetch() === false || $subscriptionStatus != 1) {
        $response = [
            'success' => false,
            'message' => 'You do not have an active subscription. Please choose a plan to continue.',
            'redirect' => './plans'
        ];
        echo json_encode($response);
        exit();
    }

    $stmt2->close();


    // Check if agent has reached the listing limit
    $listingLimitQuery = "SELECT 
                        CASE 
                            WHEN s.plan = 1 THEN 'basic'
                            WHEN s.plan = 2 THEN 'professional'
                            WHEN s.plan = 3 THEN 'premium'
                        END AS sub_plan, s.sub_date, COUNT(pl.listing_id) AS listing_count
                        FROM subscriptions s
                        LEFT JOIN property_listing pl ON s.user_id = pl.user_id 
                            AND pl.verification = 1 
                            AND pl.delete_flag = 0 
                            AND pl.added >= s.sub_date
                        WHERE s.user_id = ?
                        GROUP BY s.plan, s.sub_date";
    $stmt = $conn->prepare($listingLimitQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($subscriptionPlan, $subscriptionStartDate, $listingCount);
    $stmt->fetch();
    $stmt->close();

    $listingLimits = [
        'basic' => 5,
        'professional' => 10
    ];

    if (isset($listingLimits[$subscriptionPlan]) && $listingCount >= $listingLimits[$subscriptionPlan]) {
        $response = [
            'success' => false,
            'message' => 'You have reached the listing limit for your subscription plan. You can upgrade your plan to continue submitting more listings.',
        ];
        echo json_encode($response);
        exit();
    }

    // Handle image uploads
    $uploadedImages = array();
    $uploadsDir = '../uploads/property-images/';
    $uploadSuccessful = true;

    foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmp_name) {
        $originalFileName = $_FILES['propertyImages']['name'][$key];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $allowTypes)) {
            $uploadSuccessful = false;
            continue;
        }

        // Generate a unique file name
        $uniqueFileName = uniqid() . '.' . $fileExtension;

        $filePath = $uploadsDir . $uniqueFileName;
        if (move_uploaded_file($tmp_name, $filePath)) {
            $uploadedImages[] = $uniqueFileName;
        } else {
            $uploadSuccessful = false;
        }
    }

    if ($uploadSuccessful) {
        $imagePaths = implode(',', $uploadedImages);

        $sql = "INSERT INTO property_listing (user_id, property_name, listing_type, property_use, property_status, property_type, bedroom_count, unit_price, property_description, features, image_paths, city, address, latitude, longitude)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiiiiidsssssss", $userId, $propertyName, $listingType, $propertyUse, $propertyStatus, $propertyType, $bedroomCount, $unitPrice, $propertyDescription, $features, $imagePaths, $city, $address, $latitude, $longitude);
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Submission successful. Your property listing is currently under review.'
            ];
        }

    } else {
        $response = [
            'success' => false,
            'message' => 'One or more files could not be uploaded. Please check the file types and try again.'
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
}

$conn->close();
echo json_encode($response);