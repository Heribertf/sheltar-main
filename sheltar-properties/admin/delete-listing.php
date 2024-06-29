<?php
header('Content-Type: application/json');

if (isset($_POST['listingId'])) {

    include_once './includes/config.php';
    include_once './includes/mysqli_connection.php';

    $listingId = $_POST['listingId'];

    $deleteQuery = "UPDATE property_listing SET delete_flag = 1 WHERE listing_id = ?";
    if ($stmt = mysqli_prepare($conn, $deleteQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $listingId);
        if (mysqli_stmt_execute($stmt)) {
            $response = array('success' => true, 'message' => 'Listing deleted successfully');
            echo json_encode($response);
        } else {
            $response = array('success' => false, 'message' => 'Failed to delete listing');
            echo json_encode($response);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response = array('success' => false, 'message' => 'Cannot complete request.');
        echo json_encode($response);
    }

    mysqli_close($conn);
}