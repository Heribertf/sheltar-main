<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
header('Content-Type: application/json');

require './vendor/autoload.php';

include_once "./configuration.php";
include_once "./connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$smtpUser = $config["smtp"]["smtpUsername"];
$smtpPass = $config["smtp"]["smtpPass"];

function sendMessage($agentName, $agentEmail, $propertyName, $listingId, $clientName, $clientEmail, $clientPhone, $message, $smtpUser, $smtpPass)
{
    date_default_timezone_set('Africa/Nairobi');

    $mail = new PHPMailer(true);


    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        //$mail->Port = 587; // Port for TLS
        $mail->Port = 465; // Port for SMTPS
        $mail->setFrom($smtpUser, 'Sheltar Properties');
        $mail->addAddress($agentEmail, $agentName);
        $mail->addReplyTo($clientEmail, $clientName);

        $mail->isHTML(true);
        $mail->Subject = 'Property Inquiry';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Property Inquiry - Sheltar Properties</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }

                        .container {
                            max-width: 576px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #ffffff;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                
                        .card {
                            border: 1px solid #e1e1e1;
                            border-radius: 5px;
                            padding: 20px;
                            background-color: #ffffff;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            max-width: 550px;
                            margin: 0 auto;
                        }

                        h3 {
                            color: #333333;
                            font-size: 1.5rem;
                            text-align: center;
                        }

                        p {
                            color: #555555;
                            font-size: 1rem;
                            line-height: 1.5;
                        }

                        a.button {
                            display: inline-block;
                            padding: 1rem 2rem;
                            background-color: #4834d4;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 5px;
                            font-size: 0.875rem;
                            text-align: center;
                        }

                        img {
                            max-width: 100%;
                            height: auto;
                            display: block;
                            margin: 0 auto;
                        }

                        footer {
                            margin-top: 20px;
                            text-align: center;
                            color: #ffffff;
                            font-size: 0.875rem; /* 14px */
                            background: linear-gradient(to right, #075C52, #0C635A, #126B61, #177269, #1D796F);
                            padding: 10px;
                            max-width: 550px;
                        }

                        @media only screen and (max-width: 600px) {
                            .container {
                                padding: 10px;
                            }

                            .card {
                                padding: 15px;
                            }

                            h3 {
                                font-size: 1.2rem;
                            }

                            p {
                                font-size: 0.9rem;
                            }

                            footer {
                                padding: 8px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="card">
                            <h3>Property Inquiry</h3>
                            <p><strong>Dear ' . $agentName . ',</strong></p>
                            <p>' . $clientName . ' is interested to know more about your property <a href="http://localhost/sheltar-main/propertydetail.php?pid=' . $listingId . '">' . $propertyName . '</a> listed on Sheltar Properties. Below are the details:</p>
                            <p>Client Name: ' . $clientName . '<br>Client Email: ' . $clientEmail . '<br>Client Phone: ' . $clientPhone . '<br>
                            Message: ' . $message . '</p>
                        </div>
                        <footer>Sheltar Properties</footer>
                    </div>
                </body>
                </html>
            ';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function requestCallback($agentName, $agentEmail, $propertyName, $listingId, $clientName, $clientPhone, $smtpUser, $smtpPass)
{
    date_default_timezone_set('Africa/Nairobi');

    $mail = new PHPMailer(true);


    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        //$mail->Port = 587; // Port for TLS
        $mail->Port = 465; // Port for SMTPS
        $mail->setFrom($smtpUser, 'Sheltar Properties');
        $mail->addAddress($agentEmail, $agentName);
        $mail->isHTML(true);
        $mail->Subject = 'Property Inquiry';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Property Inquiry - Sheltar Properties</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }

                        .container {
                            max-width: 576px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #ffffff;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                
                        .card {
                            border: 1px solid #e1e1e1;
                            border-radius: 5px;
                            padding: 20px;
                            background-color: #ffffff;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            max-width: 550px;
                            margin: 0 auto;
                        }

                        h3 {
                            color: #333333;
                            font-size: 1.5rem;
                            text-align: center;
                        }

                        p {
                            color: #555555;
                            font-size: 1rem;
                            line-height: 1.5;
                        }

                        a.button {
                            display: inline-block;
                            padding: 1rem 2rem;
                            background-color: #4834d4;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 5px;
                            font-size: 0.875rem;
                            text-align: center;
                        }

                        img {
                            max-width: 100%;
                            height: auto;
                            display: block;
                            margin: 0 auto;
                        }

                        footer {
                            margin-top: 20px;
                            text-align: center;
                            color: #ffffff;
                            font-size: 0.875rem; /* 14px */
                            background: linear-gradient(to right, #075C52, #0C635A, #126B61, #177269, #1D796F);
                            padding: 10px;
                            max-width: 550px;
                        }

                        @media only screen and (max-width: 600px) {
                            .container {
                                padding: 10px;
                            }

                            .card {
                                padding: 15px;
                            }

                            h3 {
                                font-size: 1.2rem;
                            }

                            p {
                                font-size: 0.9rem;
                            }

                            footer {
                                padding: 8px;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="card">
                            <h3>Property Inquiry</h3>
                            <p><strong>Hello ' . $agentName . ',</strong></p>
                            <p>My name is ' . $clientName . ' and I am interested to know more about your property <a href="http://localhost/sheltar-main/propertydetail.php?pid=' . $listingId . '">' . $propertyName . '</a> listed on Sheltar Properties.</p>
                            <p>Kindly call be at <strong>' . $clientPhone . '</strong> as soon as you receive this message so that we can talk more. Thank you.</p>
                        </div>
                        <footer>Sheltar Properties</footer>
                    </div>
                </body>
                </html>
            ';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}


function handleRequestOne()
{
    global $smtpUser, $smtpPass;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $agentName = trim($_POST['agent-name']);
        $agentEmail = trim($_POST['agent-email']);
        $propertyName = trim($_POST['property-name']);
        $listingId = trim($_POST['property-id']);
        $clientName = trim($_POST['client-name']);
        $clientEmail = trim($_POST['client-email']);
        $clientPhone = trim($_POST['client-phone']);
        $message = trim($_POST['contact-message']);


        $response = sendMessage($agentName, $agentEmail, $propertyName, $listingId, $clientName, $clientEmail, $clientPhone, $message, $smtpUser, $smtpPass);

        if ($response) {
            response_json(true, 'Your message has been received successfully. The agent will get back to you the soonest.');
        } else {
            response_json(false, 'Your message colud not be send. Please try again later or contact support for assistance.');
        }
    }
}


function handleRequestTwo()
{
    global $smtpUser, $smtpPass;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $agentName = trim($_POST['agent-name']);
        $agentEmail = trim($_POST['agent-email']);
        $propertyName = trim($_POST['property-name']);
        $listingId = trim($_POST['property-id']);
        $clientName = trim($_POST['clientname']);
        $clientPhone = trim($_POST['clientphone']);


        $response = requestCallback($agentName, $agentEmail, $propertyName, $listingId, $clientName, $clientPhone, $smtpUser, $smtpPass);

        if ($response) {
            response_json(true, 'Callback request has been received successfully. The agent will get back to you the soonest.');
        } else {
            response_json(false, 'Your callback request could not be processed. Please try again later or contact support for assistance.');
        }
    }
}


function response_json($status, $message)
{
    ob_end_clean();
    echo json_encode(['success' => $status, 'message' => $message]);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['request'])) {
        $request = $_GET['request'];
        switch ($request) {
            case 'message':
                handleRequestOne();
                break;
            case 'callback':
                handleRequestTwo();
                break;
        }
    } else {
        response_json(false, 'Action cannot be determined');
    }
}