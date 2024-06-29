<?php
ob_start();

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Function to send verification email
function sendVerificationEmail($user_email, $otp, $firstname, $lastname)
{
    date_default_timezone_set('Africa/Nairobi');
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'felixprogrammer76@gmail.com';
        $mail->Password = 'yodxzwuqsjprfqde';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
        //$mail->Port = 587; // Port for TLS
        $mail->Port = 465; // Port for SMTPS
        $mail->setFrom('felixprogrammer76@gmail.com', 'Sheltarn Developers');
        $mail->addAddress($user_email, $firstname . ' ' . $lastname);
        $mail->addReplyTo('felixprogrammer76@gmail.com', 'Sheltarn Developers');

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = '
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Email Verification - Sheltarn Developers</title>
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
                            background: linear-gradient(to right, #075C52, #0C635A, #126B61, #177269, #1D796F); /* Shades of blue */
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
                            <h3>Verify Your Account Email</h3>
                            <p><strong>Dear ' . $firstname . ',</strong></p>
                            <p>Please use the following code to verify your email: <strong>' . $otp . '</strong></p>
                            <p>The code is only valid for one day. After which you will need to request another one if you will not have verified your email.</p>
                            <p>Thank you.</p>
                        </div>
                        <footer>Sheltarn Developers</footer>
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

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST['userEmail']);

    if (!empty($user_email) && filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT user_id, first_name, last_name FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $user_email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $user_id, $firstname, $lastname);
                    mysqli_stmt_fetch($stmt);

                    $otp = mt_rand(100000, 999999);
                    $expFormat = mktime(
                        date("H"),
                        date("i"),
                        date("s"),
                        date("m"),
                        date("d") + 1,
                        date("Y")
                    );

                    $expDate = date("Y-m-d H:i:s", $expFormat);

                    $updateQuery = "UPDATE users SET code = ?, code_expiry = ? WHERE email = ?";

                    if ($statement = mysqli_prepare($conn, $updateQuery)) {
                        mysqli_stmt_bind_param($statement, "iss", $paramCode, $paramExpiry, $paramEmail);

                        $paramCode = $otp;
                        $paramExpiry = $expDate;
                        $paramEmail = $user_email;

                        if (mysqli_stmt_execute($statement)) {
                            $send = sendVerificationEmail($user_email, $otp, $firstname, $lastname);

                            if ($send) {

                                $response = [
                                    'success' => true,
                                    'message' => 'Verification code send.',
                                ];
                            } else {
                                $response = [
                                    'success' => false,
                                    'message' => 'Failed to send verification code.',
                                ];
                            }
                        } else {
                            $response = [
                                'success' => false,
                                'message' => 'Something went wrong. Please try again later.'
                            ];
                        }
                        mysqli_stmt_close($statement);
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'No account associated with that email.'
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ];
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Could not validate your email address.'
        ];
    }
    mysqli_close($conn);
}
ob_end_clean();

echo json_encode($response);