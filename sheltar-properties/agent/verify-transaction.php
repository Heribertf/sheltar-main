<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
header('Content-Type: application/json');

include_once '../config.php';
include_once '../connection.php';

$userId = $_SESSION["id"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $reference = $input['reference'];
    $planId = $input['plan_id'];


    if (empty($reference) || empty($planId)) {
        echo json_encode(['success' => false, 'message' => 'Cannot validate request.']);
        exit;
    }

    // Verify the transaction with Paystack
    $url = "https://api.paystack.co/transaction/verify/{$reference}";
    $headers = [
        "Authorization: Bearer " . $config['paystack']['secretKey'],
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        error_log('Curl error: ' . $curlError);

        echo json_encode(['success' => false, 'message' => 'An error occurred while verifying the transaction. Please try again later.']);
        exit;
    }

    $result = json_decode($response, true);

    if ($result && $result['status'] && $result['data']['status'] === 'success') {
        $status = $result['data']['status'];
        $amount = $result['data']['amount'];
        $isoDate = $result['data']['paid_at'];
        $txn_ref = $result['data']['reference'];

        if ($status == 'success') {
            $subStatus = 1;
        } else {
            $subStatus = 2;
        }

        $paid_at = new DateTime($isoDate);

        $paidAt = $paid_at->format('Y-m-d H:i:s');

        $expFormat = mktime(
            date("H"),
            date("i"),
            date("s"),
            date("m"),
            date("d") + 30,
            date("Y")
        );

        $expDate = date("Y-m-d H:i:s", $expFormat);

        $checkQuery = "SELECT sub_id FROM subscriptions WHERE user_id = ? LIMIT 1";
        $check_stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($check_stmt, "i", $userId);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            mysqli_stmt_bind_result($check_stmt, $subscription_id);

            if (mysqli_stmt_fetch($check_stmt)) {
                $updateQuery = "UPDATE subscriptions SET plan = ?, sub_date = ?, sub_expiry = ?, transaction_ref = ?, amount = ?, status = ? WHERE sub_id = ?";
                $stmt2 = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmt2, "isssdii", $planId, $paidAt, $expDate, $txn_ref, $param_amt, $subStatus, $subscription_id);
                $param_amt = $amount / 100;

                if (mysqli_stmt_execute($stmt2)) {
                    $sub_query = "SELECT DATE_FORMAT(s.sub_expiry, '%d-%b-%Y') AS expiryDate, s.status, p.plan_name
                                            FROM subscriptions s 
                                            JOIN plans p ON s.plan = p.plan_id
                                            WHERE s.user_id = ?";

                    if ($stmt_sub = mysqli_prepare($conn, $sub_query)) {
                        mysqli_stmt_bind_param($stmt_sub, "i", $userId);

                        if (mysqli_stmt_execute($stmt_sub)) {
                            mysqli_stmt_store_result($stmt_sub);

                            if (mysqli_stmt_num_rows($stmt_sub) == 1) {
                                mysqli_stmt_bind_result($stmt_sub, $sub_expiry, $subscription_status, $plan_name);

                                if (mysqli_stmt_fetch($stmt_sub)) {
                                    $_SESSION["subscription"] = $plan_name;
                                    $_SESSION["sub_expiry"] = $sub_expiry;
                                    $_SESSION["sub_status"] = $subscription_status;
                                }
                            }
                        }
                    }
                    echo json_encode(['success' => true, 'message' => 'Payment transaction has been verified successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Could not complete request. Please try again later or contact support for assistance.']);
                }
                mysqli_stmt_close($stmt2);
            }
        } else {
            $query = "INSERT INTO subscriptions (user_id, plan, sub_date, sub_expiry, transaction_ref, amount, status) VALUES(?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($conn, $query)) {
                mysqli_stmt_bind_param($stmt, 'iisssdi', $param_user_id, $param_plan, $param_sub_date, $param_expiry, $param_txn, $param_amt, $param_status);

                $param_user_id = $userId;
                $param_plan = $planId;
                $param_sub_date = $paidAt;
                $param_expiry = $expDate;
                $param_txn = $txn_ref;
                $param_amt = $amount / 100;
                $param_status = $subStatus;

                if (mysqli_stmt_execute($stmt)) {
                    $sub_query = "SELECT DATE_FORMAT(s.sub_expiry, '%d-%b-%Y') AS expiryDate, s.status, p.plan_name
                                            FROM subscriptions s 
                                            JOIN plans p ON s.plan = p.plan_id
                                            WHERE s.user_id = ?";

                    if ($stmt_sub = mysqli_prepare($conn, $sub_query)) {
                        mysqli_stmt_bind_param($stmt_sub, "i", $userId);

                        if (mysqli_stmt_execute($stmt_sub)) {
                            mysqli_stmt_store_result($stmt_sub);

                            if (mysqli_stmt_num_rows($stmt_sub) == 1) {
                                mysqli_stmt_bind_result($stmt_sub, $sub_expiry, $subscription_status, $plan_name);

                                if (mysqli_stmt_fetch($stmt_sub)) {
                                    $_SESSION["subscription"] = $plan_name;
                                    $_SESSION["sub_expiry"] = $sub_expiry;
                                    $_SESSION["sub_status"] = $subscription_status;
                                }
                            }
                        }
                    }
                    echo json_encode(['success' => true, 'message' => 'Payment transaction has been verified successfully.']);

                } else {
                    echo json_encode(['success' => false, 'message' => 'Could not complete request. Please try again later or contact support for assistance.']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'An error unexpected occurred.']);
            }
        }
        mysqli_stmt_close($check_stmt);

    } else {
        echo json_encode(['success' => false, 'message' => 'Transaction verification failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}



// include '../config.php';
// if (isset($_GET['reference'])) {
//     $referenceId = $_GET['reference'];
//     if ($referenceId == '') {
//         header("Location: ./plans");
//     } else {
//         $curl = curl_init();
//         curl_setopt_array(
//             $curl,
//             array(
//                 CURLOPT_URL => "https://api.paystack.co/transaction/verify/$referenceId",
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => "",
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 30,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => "GET",
//                 CURLOPT_HTTPHEADER => array(
//                     "Authorization: Bearer {$config['paystack']['secretKey']}",
//                     "Cache-Control: no-cache",
//                 ),
//             )
//         );

//         $response = curl_exec($curl);
//         $err = curl_error($curl);

//         curl_close($curl);

//         if ($err) {
//             echo "cURL Error #:" . $err;
//         } else {
//             $data = json_decode($response);
//             if ($data->status == true) {
//                 echo $transaction_message = $data->message;
//                 echo "<br>";
//                 echo $paid_reference = $data->data->reference;
//                 echo "<br>";
//                 echo $message = $data->data->message;
//                 echo "<br>";
//                 echo $gateway_response = $data->data->gateway_response;
//                 echo "<br>";
//                 echo $receipt_number = $data->data->receipt_number;
//                 echo "<br>";
//             } else {
//                 // echo $response;
//                 echo $transaction_message = $data->message;
//             }

//         }
//     }
// } else {
//     header("Location: ./plans");
// }

