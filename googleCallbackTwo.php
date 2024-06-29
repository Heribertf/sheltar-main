<?php
session_start();

require_once 'sheltar-properties/new-config.php';
require_once 'connection2.php';

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
        $fullname = $firstname . ' ' . $lastname;
        $usrType = "user";

        $stmt = $conn->prepare("SELECT uid, uname, uemail, uphone, upass, utype, uimage FROM user WHERE oauth_uid = ?");
        $stmt->bind_param("s", $google_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user['uid'];
            $userEmail = $user['uemail'];
            $fullname = $user['uname'];
            $phone = $user['uphone'];
            $profile = $user['uimage'];
            $userType = $user['utype'];

            $_SESSION['uid'] = $userId;
            $_SESSION['uemail'] = $userEmail;
            $_SESSION['uname'] = $fullname;

            header("location: index.php");
            exit();
        } else {
            $stmt = $conn->prepare("INSERT INTO user (uname, uemail, utype, oauth_uid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $email, $usrType, $google_id);
            $stmt->execute();
            $userId = $stmt->insert_id;

            $_SESSION['uid'] = $userId;
            $_SESSION['uemail'] = $email;
            $_SESSION['uname'] = $fullname;

            header("location: index.php");
            exit();
        }

    } else {
        echo 'Authentication failed: ' . $token['error'];
    }
}