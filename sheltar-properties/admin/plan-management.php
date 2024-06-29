<?php
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $planId = trim($_POST['edit-plan-id']);
    $planName = trim($_POST['edit_plan_name']);
    $planPrice = trim($_POST['edit_plan_price']);
    $planStatus = trim($_POST['edit_plan_status']);

    if (!empty($planId) && !empty($planName) && !empty($planPrice) && !empty($planStatus)) {
        $update_query = "UPDATE plans SET plan_name = ?, plan_price = ?, plan_status = ? WHERE plan_id = ?";
        if ($stmt = mysqli_prepare($conn, $update_query)) {
            mysqli_stmt_bind_param($stmt, "sdsi", $planName, $planPrice, $planStatus, $planId);

            if (mysqli_stmt_execute($stmt)) {
                $response = array('success' => true, 'message' => 'Plan details updated successfully');
                echo json_encode($response);
            } else {
                $response = array('success' => false, 'message' => 'Failed to update plan details');
                echo json_encode($response);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = array('success' => false, 'message' => 'An error occurred while trying to update plan details');
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Please provide all the details');
        echo json_encode($response);
    }

}
mysqli_close($conn);