<?php
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paybillNo = $_POST['paybill'];
    $accountNo = $_POST['account'];
    $businessName = $_POST['business'];

    if (!empty($paybillNo) && !empty($accountNo)) {
        $query = "UPDATE payment SET paybill_number = ?, account_number = ?, business_name = ? WHERE id = 1";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'iis', $paybillNo, $accountNo, $businessName);

            if (mysqli_stmt_execute($stmt)) {
                $response = [
                    'success' => true,
                    'message' => 'Payment details updated successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ];
            }

            mysqli_stmt_close($stmt);
        } else {
            $response = [
                'success' => false,
                'message' => 'An error occurred.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'An error occurred.'
        ];
    }
}

mysqli_close($conn);
echo json_encode($response);