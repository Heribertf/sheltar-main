<?php
ob_start();
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function sendNotificationEmail($fullname, $email, $propertyName, $comment, $type)
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
        $mail->addAddress($email, $fullname);
        $mail->addReplyTo('felixprogrammer76@gmail.com', 'Sheltarn Developers');

        $mail->isHTML(true);
        $mail->Subject = 'Property Listing Approval';
        if ($type == 'approved') {
            $mail->Body = '
            <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Listing Approval - Sheltarn Developers</title>
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
                        <h3>' . $propertyName . ' is live now!</h3>
                        <p><strong>Hello ' . $fullname . ',</strong></p>
                        <p>We are pleased to inform you that your property listing: <strong>' . $propertyName . '</strong> has been approved and published.</p>
                        <p>Continue advertising with us for greater market reach.</p>
                        <p>Thank you.</p>
                    </div>
                    <footer>Sheltarn Developers</footer>
                </div>
            </body>
            </html>
        ';
        } elseif ($type == 'rejected') {
            $mail->Body = '
            <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Listing Approval - Sheltarn Developers</title>
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
                        <h3>' . $propertyName . ' has been rejected!</h3>
                        <p><strong>Hello ' . $fullname . ',</strong></p>
                        <p>We regrete to inform you that your property listing: <strong>' . $propertyName . '</strong> has not been approved.</p>
                        <p>' . $comment . '</p>
                    </div>
                    <footer>Sheltarn Developers</footer>
                </div>
            </body>
            </html>
        ';
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}


include_once './includes/config.php';
include_once './includes/mysqli_connection.php';
$comment = null;
$conditionOne = $conditionTwo = false;
$response = array('success' => false, 'message' => 'An unexpected error occurred.');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listingId = trim($_POST['update_listing_id']);
    $listingStatus = trim($_POST['listingStatus']);
    $comment = trim($_POST['rejectionComment']);
    if (!empty($listingId) && !empty($listingStatus) && empty($comment)) {
        $update_query = "UPDATE property_listing SET verification = ? WHERE listing_id = ?";
        if ($stmt = mysqli_prepare($conn, $update_query)) {
            mysqli_stmt_bind_param($stmt, "ii", $listingStatus, $listingId);

            if (mysqli_stmt_execute($stmt)) {
                $conditionOne = true;
                $response = array('success' => true, 'message' => 'Listing status updated successfully');
            } else {
                $response = array('success' => false, 'message' => 'Failed to update listing status');
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = array('success' => false, 'message' => 'An error occurred while trying to update listing status');
        }
    } elseif (!empty($comment)) {
        $update_query = "UPDATE property_listing SET verification = ?, comment = ? WHERE listing_id = ?";
        if ($stmt = mysqli_prepare($conn, $update_query)) {
            mysqli_stmt_bind_param($stmt, "isi", $listingStatus, $comment, $listingId);

            if (mysqli_stmt_execute($stmt)) {
                $conditionTwo = true;
                $response = array('success' => true, 'message' => 'Listing status updated successfully');
            } else {
                $response = array('success' => false, 'message' => 'Failed to update listing status');
            }
            mysqli_stmt_close($stmt);
        } else {
            $response = array('success' => false, 'message' => 'An error occurred while trying to update listing status');
        }
    }
    $agentQuery = "SELECT pl.user_id, pl.property_name, CONCAT(u.first_name, ' ', u.last_name) AS agent_name, u.email
                                FROM property_listing pl
                                JOIN users u ON pl.user_id = u.user_id
                                WHERE pl.listing_id = ?";
    $statement5 = mysqli_prepare($conn, $agentQuery);
    mysqli_stmt_bind_param($statement5, "i", $listingId);
    mysqli_stmt_execute($statement5);
    mysqli_stmt_store_result($statement5);

    if (mysqli_stmt_num_rows($statement5) > 0) {
        mysqli_stmt_bind_result($statement5, $userId, $property_name, $fullName, $email);
        mysqli_stmt_fetch($statement5);
    }
    mysqli_stmt_close($statement5);

    if ($conditionOne || $conditionTwo) {
        if ($listingStatus == 1) {
            $type = "approved";
            sendNotificationEmail($fullName, $email, $property_name, $comment, $type);
        } elseif ($listingStatus == 0) {
            $type = "rejected";
            sendNotificationEmail($fullName, $email, $property_name, $comment, $type);
        }
    }
}
mysqli_close($conn);
ob_end_clean();
echo json_encode($response);
