<?php
if (isset($_POST['adminId'])) {
    include_once 'mysqli_connection.php';

    $adminId = $_POST['adminId'];

    $deleteQuery = "UPDATE admin SET delete_flag = 1 WHERE adminId = ?";
    if ($stmt = mysqli_prepare($conn, $deleteQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $adminId);
        if (mysqli_stmt_execute($stmt)) {
            $response = array('success' => true, 'message' => 'Administrator deleted successfully');
            echo json_encode($response);
        } else {
            $response = array('success' => false, 'message' => 'Failed to delete administrator');
            echo json_encode($response);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response = array('success' => false, 'message' => 'Database error: Could not update the database.');
        echo json_encode($response);
    }

    mysqli_close($conn);
}
