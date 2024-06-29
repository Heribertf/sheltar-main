<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $planId = $_POST['planId'];

    $deleteQuery = "UPDATE plans SET delete_flag = 1 WHERE plan_id = ?";
    if ($stmt = mysqli_prepare($conn, $deleteQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $planId);
        if (mysqli_stmt_execute($stmt)) {
            $response = array('success' => true, 'message' => 'Plan deleted successfully');
            echo json_encode($response);
        } else {
            $response = array('success' => false, 'message' => 'Failed to delete plan');
            echo json_encode($response);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response = array('success' => false, 'message' => 'Database error: Could not update the database.');
        echo json_encode($response);
    }

    mysqli_close($conn);
}