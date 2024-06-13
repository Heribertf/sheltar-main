<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start();
header('Content-Type: application/json');

require './vendor/autoload.php';

include_once "./configuration.php";
include_once "./connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$userId = $_SESSION['uid'];
$userEmail = $_SESSION['uemail'];
$userName = $_SESSION['uname'];

$smtpUser = $config["smtp"]["smtpUsername"];
$smtpPass = $config["smtp"]["smtpPass"];

function notifyAdmin($userEmail, $userName, $category, $type, $rooms, $minPrice, $maxPrice, $location, $propertyDetails, $phone, $smtpUser, $smtpPass)
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
        $mail->addAddress('heribertfel20@gmail.com', 'Sheltar Admin');
        $mail->addReplyTo($userEmail, $userName);
        //$mail->addCC('xxxx@gmail.com', 'xxx xxx');

        $mail->isHTML(true);
        $mail->Subject = 'Property Request';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Property Request - Sheltar Properties</title>
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
                            <h3>New Property Request</h3>
                            <p><strong>Dear Sheltar Admin,</strong></p>
                            <p>You have received a new property request from <strong>' . $userName . '.</strong> Below are the details:</p>
                            <p>Client Name: ' . $userName . '<br>Client Email: ' . $userEmail . '<br>Client Phone: ' . $phone . '<br>
                            Property Category: ' . $category . '<br>Property Type: ' . $type . '<br>Number of Bedrooms: ' . $rooms . '<br>
                            Minimum price: ' . $minPrice . '<br>Maximum Price: ' . $maxPrice . '<br>Preferred Location: ' . $location . '<br>Ideal Property Preference: ' . $propertyDetails . '</p>
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = $_POST['property-category'];
    $type = $_POST['property-category'];
    $phone = $_POST['contact-phone'];
    $minPrice = $_POST['min-price'];
    $maxPrice = $_POST['max-price'];
    $location = $_POST['location'];
    $rooms = $_POST['rooms'];
    $propertyDetails = $_POST['property-details'];


    $query = "INSERT INTO property_request (user_id, property_category, property_type, rooms, min_price, max_price, location, property_details, contact) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("issiddsss", $userId, $category, $type, $rooms, $minPrice, $maxPrice, $location, $propertyDetails, $phone);

    if ($stmt->execute()) {
        $notifyAdmin = notifyAdmin($userEmail, $userName, $category, $type, $rooms, $minPrice, $maxPrice, $location, $propertyDetails, $phone, $smtpUser, $smtpPass);
        if ($notifyAdmin) {
            response_json(true, "Your request has been submitted successfully. We will get back to you soon");
        } else {
            response_json(false, 'An error occurred while processing your request. Please try again later.');
        }
    } else {
        response_json(false, 'Cannot complete request. Please try again later.');
    }

    $stmt->close();
    $conn->close();
}

function response_json($status, $message)
{
    ob_end_clean();
    echo json_encode(['success' => $status, 'message' => $message]);
}