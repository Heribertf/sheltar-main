<?php
session_start();
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$targetDir = "../uploads/profile-images/";
$userId = $_SESSION["id"];

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES["profileImage"]["name"])) {
        $fileName = $_FILES["profileImage"]["name"];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'png', 'jpeg');


        if (in_array($fileType, $allowTypes)) {
            $uniqueFileName = uniqid() . '.' . $fileType;
            $targetFilePath = $targetDir . $uniqueFileName;

            if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFilePath)) {
                $sql = "UPDATE users SET profileImage = ? WHERE user_id = ?";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "si", $uniqueFileName, $userId);
                    if (mysqli_stmt_execute($stmt)) {
                        $response = [
                            'success' => true,
                            'message' => 'Profile image updated successfully.'
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Update failed, please try again.'
                        ];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'An unexpected error occurred.'
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Sorry, there was an error uploading your profile image.'
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Sorry, only jpg, jpeg & png files are allowed to upload.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Please select a file to upload.'
        ];
    }
}

echo json_encode($response);
