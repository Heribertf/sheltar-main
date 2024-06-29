<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include_once '../connection.php';

$response = [
    'success' => false,
    'message' => 'Unexpected error occurred.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $validData = true;

    $planName = trim($_POST['plan_name']);
    $planPrice = trim($_POST['plan_price']);
    $planStatus = trim($_POST['plan_status']);

    if (empty($planName)) {
        $validData = false;
        $response = [
            'success' => false,
            'message' => 'Provide the plan name.'
        ];
    } elseif (empty($planStatus)) {
        $validData = false;
        $response = [
            'success' => false,
            'message' => 'Choose the plan status.'
        ];
    }

    $name_query = "SELECT plan_id FROM plans WHERE plan_name = ?";
    if ($statement = mysqli_prepare($conn, $name_query)) {
        mysqli_stmt_bind_param($statement, "s", $planName);

        if (mysqli_stmt_execute($statement)) {
            mysqli_stmt_store_result($statement);

            if (mysqli_stmt_num_rows($statement) > 0) {
                $validData = false;
                $response = [
                    'success' => false,
                    'message' => 'Plan name already exists. Choose a different one.'
                ];
            }
        } else {
            $validData = false;
            $response = [
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ];
        }

        mysqli_stmt_close($statement);
    } else {
        $validData = false;
        $response = [
            'success' => false,
            'message' => 'Database error.'
        ];
    }


    if ($validData) {
        $insertQuery = "INSERT INTO plans (plan_name, plan_price, plan_status) VALUES (?,?,?)";

        if ($stmt = mysqli_prepare($conn, $insertQuery)) {
            mysqli_stmt_bind_param($stmt, "sds", $planName, $planPrice, $planStatus);

            if (mysqli_stmt_execute($stmt)) {
                $response = [
                    'success' => true,
                    'message' => 'The plan has been added successfully.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Plan submission failed, please try again.'
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = [
                'success' => false,
                'message' => 'Database error: Could not insert into database.'
            ];
        }
    }
}

echo json_encode($response);
