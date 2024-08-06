<?php
session_start();
require_once '../../vendor/autoload.php';

use IntaSend\IntaSendPHP\Collection;

class SubscriptionHandler
{
    private $collection;
    private $db;
    private $userId;

    public function __construct()
    {
        include_once '../config.php';
        require_once '../connection.php';

        $this->collection = new Collection();

        $credentials = [
            'token' => $config["intasend"]["secretKey"],
            'publishable_key' => $config["intasend"]["publicKey"],
            'test' => true,
        ];

        $this->collection->init($credentials);

        // global $conn;
        // $this->db = $conn;
        $this->userId = $_SESSION["id"];

        $this->db = new mysqli('localhost', 'root', '', 'sheltarn_db');
        if ($this->db->connect_error) {
            die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $this->db->connect_error]));
        }
    }

    public function initiatePayment($amount, $currency, $plan, $phone_number, $email, $name)
    {
        try {
            if (empty($amount) || empty($currency) || empty($plan) || empty($phone_number) || empty($email) || empty($name)) {
                return json_encode(['success' => false, 'message' => 'Invalid input parameters']);
            }

            $response = $this->collection->create(
                $amount,
                $phone_number,
                $currency,
                "MPESA_STK_PUSH",
                $plan,
                $email,
                null
            );

            return json_encode(['success' => true, 'message' => 'Payment initiated', 'data' => $response]);

        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Exception occurred: ' . $e->getMessage()]);
        }
    }

    public function verifyPayment($transactionId)
    {
        try {
            $response = $this->collection->status($transactionId);
            $txn_state = $response->invoice->state;
            $amountRecorded = $response->invoice->value;
            $mpesaNo = $response->invoice->account;
            $mpesaRef = $response->invoice->mpesa_reference;
            $paidAt = $response->invoice->updated_at;
            $planId = $response->invoice->api_ref;
            $currency = $response->invoice->currency;

            $paid_at = new DateTime($paidAt);
            $datePaid = $paid_at->format('Y-m-d H:i:s');

            $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 30, date("Y"));
            $expDate = date("Y-m-d H:i:s", $expFormat);

            // Only insert the record if the transaction is completed
            if ($txn_state === 'COMPLETE') {
                $subStatus = 1;
                $checkQuery = "SELECT sub_id FROM subscriptions WHERE user_id = ? LIMIT 1";
                $check_stmt = $this->db->prepare($checkQuery);
                if (!$check_stmt) {
                    throw new Exception("Failed to prepare check statement: " . $this->db->error);
                }
                $check_stmt->bind_param("i", $this->userId);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    $check_stmt->bind_result($subscriptionId);
                    $check_stmt->fetch();
                    $check_stmt->close();

                    $updateQuery = "UPDATE subscriptions SET plan = ?, sub_date = ?, sub_expiry = ?, transaction_ref = ?, amount = ?, status = ? WHERE sub_id = ?";
                    $stmt2 = $this->db->prepare($updateQuery);
                    if (!$stmt2) {
                        throw new Exception("Failed to prepare update statement: " . $this->db->error);
                    }
                    $stmt2->bind_param("isssdii", $planId, $datePaid, $expDate, $mpesaRef, $amountRecorded, $subStatus, $subscriptionId);

                    if (!$stmt2->execute()) {
                        throw new Exception("Failed to execute update statement: " . $stmt2->error);
                    }
                    $stmt2->close();
                } else {
                    $check_stmt->close();
                    $query = "INSERT INTO subscriptions (user_id, plan, sub_date, sub_expiry, transaction_ref, amount, status) VALUES(?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);
                    if (!$stmt) {
                        throw new Exception("Failed to prepare insert statement: " . $this->db->error);
                    }
                    $stmt->bind_param("iisssdi", $this->userId, $planId, $datePaid, $expDate, $mpesaRef, $amountRecorded, $subStatus);
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to execute insert statement: " . $stmt->error);
                    }
                    $stmt->close();
                }

                $sub_query = "SELECT DATE_FORMAT(s.sub_expiry, '%d-%b-%Y') AS expiryDate, s.status, p.plan_name
                              FROM subscriptions s 
                              JOIN plans p ON s.plan = p.plan_id
                              WHERE s.user_id = ?";
                $stmt_sub = $this->db->prepare($sub_query);
                if (!$stmt_sub) {
                    throw new Exception("Failed to prepare subscription query: " . $this->db->error);
                }
                $stmt_sub->bind_param("i", $this->userId);
                $stmt_sub->execute();
                $stmt_sub->store_result();

                if ($stmt_sub->num_rows == 1) {
                    $stmt_sub->bind_result($sub_expiry, $subscription_status, $plan_name);
                    $stmt_sub->fetch();

                    $_SESSION["subscription"] = $plan_name;
                    $_SESSION["sub_expiry"] = $sub_expiry;
                    $_SESSION["sub_status"] = $subscription_status;
                }
                $stmt_sub->close();

            }
            // var_dump($datePaid);
            return json_encode(['success' => true, 'message' => 'Payment verified', 'data' => $response]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Exception occurred: ' . $e->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $handler = new SubscriptionHandler();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'initiate':
                echo $handler->initiatePayment(
                    $_POST['amount'],
                    $_POST['currency'],
                    $_POST['plan'],
                    $_POST['phone_number'],
                    $_POST['email'],
                    $_POST['name']
                );
                break;
            case 'verify':
                echo $handler->verifyPayment($_POST['transactionId']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action not specified']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
