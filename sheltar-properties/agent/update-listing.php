<?php
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listingId = trim($_POST['edit_listing_id']);
    $propertyName = trim($_POST['edit_property_name']);
    $unitPrice = trim($_POST['edit_unit_price']);

    if (!empty($listingId) && !empty($propertyName) && !empty($unitPrice)) {
        $update_query = "UPDATE property_listing SET property_name = ?, unit_price = ? WHERE listing_id = ?";
        if ($stmt = mysqli_prepare($conn, $update_query)) {
            mysqli_stmt_bind_param($stmt, "sdi", $propertyName, $unitPrice, $listingId);

            if (mysqli_stmt_execute($stmt)) {
                $response = array('success' => true, 'message' => 'Prooerty details updated successfully');
                echo json_encode($response);
            } else {
                $response = array('success' => false, 'message' => 'Failed to update property details');
                echo json_encode($response);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = array('success' => false, 'message' => 'An error occurred while trying to update property details');
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Please provide all the details');
        echo json_encode($response);
    }

}
mysqli_close($conn);