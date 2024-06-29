<?php
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
    $availableUnits = $_POST['available-units'];
    $totalUnits = $_POST['total-units'];
    $propertyDescription = $_POST['property-desc'];
    $features = isset($_POST['features']) ? implode(',', $_POST['features']) : '';
    $city = $_POST['city'];
    $address = $_POST['address'];
    $userId = $_SESSION["id"];

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

        $sql = "INSERT INTO property_listing (user_id, property_name, listing_type, property_use, property_status, property_type, bedroom_count, unit_price, available_units, total_units, property_description, features, image_paths, city, address)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiiiiidiisssss", $userId, $propertyName, $listingType, $propertyUse, $propertyStatus, $propertyType, $bedroomCount, $unitPrice, $availableUnits, $totalUnits, $propertyDescription, $features, $imagePaths, $city, $address);
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Submission successful. You property listing is currently under review.'
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
        'success' => true,
        'message' => 'Invalid request method'
    ];
}

$conn->close();
echo json_encode($response);
