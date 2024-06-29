<?php
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';


$response = [
    'success' => false,
    'message' => 'unexpected error occurred.'
];

$email = $password = "";
$email_err = $password_err = $verification_err = "";
$redirect = './verification';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
        $response = [
            'success' => false,
            'message' => 'Please enter your email.'
        ];
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
        $response = [
            'success' => false,
            'message' => 'Please enter your password.'
        ];
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS fullname, phone, email, profileImage, verified, password, type FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $userId, $fullname, $phone, $user_email, $profileImage, $verification_status, $hashed_password, $userType);

                    if (mysqli_stmt_fetch($stmt)) {
                        if ($userType !== 3) {
                            $response = [
                                'success' => false,
                                'message' => 'Invalid login credentials. Please check your details again'
                            ];
                            echo json_encode($response);
                            exit();
                        }
                        if ($verification_status == 0) {
                            $verification_err = "Please verify your email.";
                            $response = [
                                'success' => false,
                                'message' => 'Please verify your email.',
                                'redirect' => $redirect,
                                'email' => $user_email
                            ];
                        } else {
                            if (password_verify($password, $hashed_password)) {

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
                                                session_start();

                                                $_SESSION["loggedin"] = true;
                                                $_SESSION["id"] = $userId;
                                                $_SESSION["email"] = $user_email;
                                                $_SESSION["fullname"] = $fullname;
                                                $_SESSION["phone"] = $phone;
                                                $_SESSION["profile"] = $profileImage;
                                                $_SESSION["user_type"] = 3;
                                                $_SESSION["subscription"] = $plan_name;
                                                $_SESSION["sub_expiry"] = $sub_expiry;
                                                $_SESSION["sub_status"] = $subscription_status;

                                                $response = [
                                                    'success' => true,
                                                    'message' => 'Successfully logged in.'
                                                ];
                                            }
                                        } else {
                                            session_start();

                                            $_SESSION["loggedin"] = true;
                                            $_SESSION["id"] = $userId;
                                            $_SESSION["email"] = $user_email;
                                            $_SESSION["fullname"] = $fullname;
                                            $_SESSION["phone"] = $phone;
                                            $_SESSION["profile"] = $profileImage;
                                            $_SESSION["user_type"] = 3;
                                            $_SESSION["subscription"] = 'No active plan';
                                            $_SESSION["sub_expiry"] = '';
                                            $_SESSION["sub_status"] = '';

                                            $response = [
                                                'success' => true,
                                                'message' => 'Successfully logged in.'
                                            ];
                                        }
                                    }
                                }


                            } else {
                                $password_err = "Invalid login credentials.";
                                $response = [
                                    'success' => false,
                                    'message' => 'Invalid login credentials.'
                                ];
                            }
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                    $response = [
                        'success' => false,
                        'message' => 'No account found with that email.'
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
    }

    mysqli_close($conn);
}

echo json_encode($response);