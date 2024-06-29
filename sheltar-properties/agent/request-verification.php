<?php
session_start();
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'doc', 'docx');
$userId = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET["type"])) {
        $user_type = $_GET["type"];

        switch ($user_type) {
            case 'agent':
                $userType = 'Agent';
                $name = $_POST['agentName'];
                $number = $_POST['agentNumber'];
                $phone = $_POST['agentPhone'];
                break;
            case 'landloard':
                $userType = 'Landloard';
                $name = $_POST['landloardName'];
                $number = $_POST['plotNumber'];
                $phone = $_POST['landloardPhone'];
                break;
            default:
                $userType = null;
                $name = null;
                $number = null;
                $phone = null;
                break;
        }
    }


    $uploadSuccessful = true;
    $uploadsDir = '../uploads/documents/';

    if ($userType == 'Agent') {
        if (isset($_FILES['agentSupportDoc']) && $_FILES['agentSupportDoc']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['agentSupportDoc']['name'];
            $fileSize = $_FILES['agentSupportDoc']['size'];
            $fileTmpName = $_FILES['agentSupportDoc']['tmp_name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowTypes)) {
                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $filePath = $uploadsDir . $uniqueFileName;

                if (move_uploaded_file($fileTmpName, $filePath)) {
                    $documentPath = $filePath;
                } else {
                    $uploadSuccessful = false;
                }
            } else {
                $uploadSuccessful = false;
            }
        } else {
            $uploadSuccessful = false;
        }

    } elseif ($userType == 'Landloard') {
        if (isset($_FILES['landloardSupportDoc']) && $_FILES['landloardSupportDoc']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['landloardSupportDoc']['name'];
            $fileSize = $_FILES['landloardSupportDoc']['size'];
            $fileTmpName = $_FILES['landloardSupportDoc']['tmp_name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowTypes)) {
                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $filePath = $uploadsDir . $uniqueFileName;

                if (move_uploaded_file($fileTmpName, $filePath)) {
                    $documentPath = $filePath;
                } else {
                    $uploadSuccessful = false;
                }
            } else {
                $uploadSuccessful = false;
            }
        } else {
            $uploadSuccessful = false;
        }
    }

    if ($uploadSuccessful) {
        $sql = "INSERT INTO verification (user_id, name, number, contact, document, type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $userId, $name, $number, $phone, $uniqueFileName, $userType);
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Verification details submitted successfully, wait for approval.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'An error occurred while submitting details.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'The supporting document could not be uploaded. Please try again.'
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
