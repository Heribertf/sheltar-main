<?php
require '/opt/lampp/htdocs/sheltar-main/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendConfirmationEmail($client_email, $quote, $client_name)
{
    date_default_timezone_set('Africa/Nairobi');
    include_once './configuration.php';

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config["smtp"]["smtpUsername"];
        $mail->Password = $config["smtp"]["smtpPass"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        //$mail->Port = 587; // Port for TLS
        $mail->Port = 465; // Port for SMTPS
        $mail->setFrom($config["smtp"]["smtpUsername"], 'Sheltar Properties');
        $mail->addAddress($client_email, $client_name);
        $mail->addReplyTo($config["smtp"]["smtpUsername"], 'Sheltar Properties');

        $mail->isHTML(true);
        $mail->Subject = 'Moving Quote Request';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Moving Quote - Sheltar Properties</title>
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
                            <h3>Quote Request Received</h3>
                            <p><strong>Dear ' . $client_name . ',</strong></p>
                            <p>Thank you for trusting Sheltar Movers. We have received your quote request which is estimated at <strong>Ksh ' . number_format($quote, 2) . '</strong></p>
                            <p>Please note that the amount quoted is provisional and is subject to change upon review. We will get back to you soon.</p>
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

function notifyMover($client_name, $client_email, $client_phone, $mover_email, $mover_name, $current_address, $destination_address, $moving_date, $rooms, $additional_services, $quote, $distance)
{
    date_default_timezone_set('Africa/Nairobi');
    include_once './configuration.php';

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config["smtp"]["smtpUsername"];
        $mail->Password = $config["smtp"]["smtpPass"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        //$mail->Port = 587; // Port for TLS
        $mail->Port = 465; // Port for SMTPS
        $mail->setFrom($config["smtp"]["smtpUsername"], 'Sheltar Properties');
        $mail->addAddress($mover_email, $mover_name);
        $mail->addReplyTo($client_email, $client_name);
        //$mail->addCC('xxxx@gmail.com', 'xxx xxx');

        $mail->isHTML(true);
        $mail->Subject = 'Moving Quote Request';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Moving Quote - Sheltar Properties</title>
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
                            <h3>New Quote Request</h3>
                            <p><strong>Dear ' . $mover_name . ',</strong></p>
                            <p>You have received a new moving request from <strong>' . $client_name . '.</strong> Below are the details:</p>
                            <p>Client Name: ' . $client_name . '<br>Client Email: ' . $client_email . '<br>Client Phone: ' . $client_phone . '<br>
                            Current Location: ' . $current_address . '<br>Destination: ' . $destination_address . '<br>Moving Date: ' . $moving_date . '<br>
                            Number of rooms: ' . $rooms . '<br>Additional Services: ' . $additional_services . '<br>Estimated quote: Ksh ' . $quote . '<br>Estimated distance: ' . $distance . 'km</p>
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

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('moving_email_queue', false, true, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function ($msg) {
    $data = json_decode($msg->body, true);
    $clientName = $data['clientName'];
    $clientEmail = $data['clientEmail'];
    $quote = $data['quote'];
    $clientPhone = $data['clientPhone'];
    $currentAddress = $data['currentAddress'];
    $destinationAddress = $data['destination'];
    $movingDate = $data['movingDate'];
    $rooms = $data['rooms'];
    $additionalServices = $data['addons'];
    $distance = $data['distance'];
    $moverEmail = $data['moverEmail'];
    $moverName = $data['moverName'];


    $confirmationEmail = sendConfirmationEmail($clientEmail, $quote, $clientName);
    $notifyMover = notifyMover($clientName, $clientEmail, $clientPhone, $moverEmail, $moverName, $currentAddress, $destinationAddress, $movingDate, $rooms, $additionalServices, $quote, $distance);

    if ($confirmationEmail && $notifyMover) {
        echo " [x] Emails sent successfully\n";
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    } else {
        echo " [x] Failed to send emails\n";
        $msg->delivery_info['channel']->basic_reject($msg->delivery_info['delivery_tag'], false);
    }
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('moving_email_queue', '', false, false, false, false, $callback);

function checkQueueStatus($channel, $queueName)
{
    list($queue, $messageCount, $consumerCount) = $channel->queue_declare($queueName, true);
    return $messageCount;
}

while (true) {
    $channel->wait(null, false, 1); // Timeout set to 1 second
    $messageCount = checkQueueStatus($channel, 'moving_email_queue');
    if ($messageCount == 0 && empty($channel->callbacks)) {
        break;
    }
}

$channel->close();
$connection->close();
