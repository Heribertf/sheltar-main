<?php
include_once 'mysqli_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = trim($_POST['editAdminId']);

    if (empty(trim($_POST["editAdminUsername"]))) {
        $response = array('success' => false, 'message' => 'Username cannot be empty!');
        echo json_encode($response);
    } elseif (empty(trim($_POST["editAdminEmail"]))) {
        $response = array('success' => false, 'message' => 'Email cannot be empty!');
        echo json_encode($response);
    } else {
        $adminUsername = trim($_POST['editAdminUsername']);
        $adminEmail = trim($_POST['editAdminEmail']);

        $updateAdmQuery = "UPDATE admin SET username = ?, email = ? WHERE adminId = ?";
        if ($stmt = mysqli_prepare($conn, $updateAdmQuery)) {
            mysqli_stmt_bind_param($stmt, "ssi", $adminUsername, $adminEmail, $adminId);
            if (mysqli_stmt_execute($stmt)) {
                $response = array('success' => true, 'message' => 'Administrator details updated successfully');
                echo json_encode($response);
            } else {
                $response = array('success' => false, 'message' => 'Failed to update Administrator details');
                echo json_encode($response);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = array('success' => false, 'message' => 'Database error: Could not update the database.');
        }
    }
}
mysqli_close($conn);