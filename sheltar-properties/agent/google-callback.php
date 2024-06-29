<?php
session_start();

require_once '../config.php';
require_once '../connection.php';

$phone = $profile = $verified = $userType = NULL;

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google_Service_Oauth2($client);
        $google_user = $oauth2->userinfo->get();

        $authProvider = "google";
        $google_id = $google_user['id'];
        $firstname = $google_user['given_name'];
        $lastname = $google_user['family_name'];
        $email = $google_user['email'];

        $stmt = $conn->prepare("SELECT user_id, CONCAT(first_name, ' ', last_name) AS fullname, phone, email, profileImage, verified, password, type FROM users WHERE oauth_uid = ?");
        $stmt->bind_param("s", $google_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user['user_id'];
            $userEmail = $user['email'];
            $fullname = $user['fullname'];
            $phone = $user['phone'];
            $profile = $user['profileImage'];
            $verified = $user['verified'];
            $userType = $user['type'];
        } else {
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, oauth_provider, oauth_uid) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $authProvider, $google_id);
            $stmt->execute();
            $userId = $stmt->insert_id;
        }

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
                        date_default_timezone_set('Africa/Nairobi');
                        $todayDate = date('Y-m-d');

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $userId;
                        $_SESSION["email"] = $email;
                        $_SESSION["fullname"] = $firstname . ' ' . $lastname;
                        $_SESSION["phone"] = $phone;
                        $_SESSION["profile"] = $profile;
                        $_SESSION["user_type"] = 3;
                        $_SESSION["subscription"] = $plan_name;
                        $_SESSION["sub_expiry"] = $sub_expiry;
                        $_SESSION["sub_status"] = $subscription_status;

                        header('Location: ./dashboard');
                        exit();

                    }
                } else {

                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $userId;
                    $_SESSION["email"] = $email;
                    $_SESSION["fullname"] = $firstname . ' ' . $lastname;
                    $_SESSION["phone"] = $phone;
                    $_SESSION["profile"] = $profile;
                    $_SESSION["user_type"] = 3;
                    $_SESSION["subscription"] = 'No active plan';
                    $_SESSION["sub_expiry"] = '';
                    $_SESSION["sub_status"] = '';

                    header('Location: ./dashboard');
                    exit();
                }
            }
        }

    } else {
        echo 'Authentication failed: ' . $token['error'];
    }
}