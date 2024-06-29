<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

require 'connection2.php';
require_once './sheltar-properties/new-config.php';


session_start();

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK]);

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]);
header('Location: ' . $url);