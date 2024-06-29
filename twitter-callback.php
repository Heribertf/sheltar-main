<?php
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

require 'connection2.php';
require_once './sheltar-properties/new-config.php';


session_start();

if (!isset($_SESSION['oauth_token']) || !isset($_GET['oauth_verifier'])) {
    header('Location: twitter-login.php');
    exit();
}

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $connection->oauth('oauth/access_token', ['oauth_verifier' => $_GET['oauth_verifier']]);

$_SESSION['access_token'] = $access_token;

$user = $connection->get('account/verify_credentials', ['include_email' => 'true']);

$twitter_id = $user->id_str;
$username = $user->screen_name;
$profile_image = $user->profile_image_url_https;

// $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// if ($con->connect_error) {
//     die("Connection failed: " . $con->connect_error);
// }

$stmt = $con->prepare("INSERT INTO user (oauth_uid, uname) VALUES (?, ?) ON DUPLICATE KEY UPDATE uname=?");
$stmt->bind_param("sss", $twitter_id, $username, $username);
$stmt->execute();

// Check if an insert or update occurred
if ($stmt->affected_rows > 0) {
    if ($stmt->insert_id > 0) {
        // Insert occurred
        $user_id = $stmt->insert_id;
    } else {
        // Update occurred, retrieve the ID using the twitter_id
        $stmt2 = $conn->prepare("SELECT uid FROM user WHERE oauth_uid = ?");
        $stmt2->bind_param("s", $twitter_id);
        $stmt2->execute();
        $stmt2->bind_result($user_id);
        $stmt2->fetch();
        $stmt2->close();
    }
}

$stmt->close();
$con->close();

$_SESSION['uid'] = $user_id;
$_SESSION['uemail'] = '';
$_SESSION['uname'] = $username;

header('Location: index.php');