<?php
session_start();
header('Content-Type: application/json');

include_once './includes/config.php';
include_once './includes/mysqli_connection.php';

$response = [
    'success' => false,
    'message' => 'An unexpected error occurred.'
];

$currentUser = $_SESSION["id"];
$confirm_password_err = $password_err = $password = $confirm_password = $currentPassword = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["currentPassword"]))) {
        $password_err = "Please enter your current password.";
        $response = [
            'success' => false,
            'message' => 'Please enter your current password.'
        ];
    } else {
        $currentPassword = trim($_POST["currentPassword"]);
    }

    if (empty(trim($_POST["newPassword"]))) {
        $password_err = "Please enter a password.";
        $response = [
            'success' => false,
            'message' => 'Please enter a password.'
        ];
    } else {
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).*$/', trim($_POST["newPassword"]))) {
            $password_err = "Password must contain at least one digit, one lowercase letter, one uppercase letter, and one special character.";
            $response = [
                'success' => false,
                'message' => 'Password must contain at least one digit, one lowercase letter, one uppercase letter, and one special character.'
            ];
            echo json_encode($response);
            exit();
        } elseif (strlen(trim($_POST["newPassword"])) < 8) {
            $password_err = "Password must have at least 8 characters.";
            $response = [
                'success' => false,
                'message' => 'Password must have at least 8 characters.'
            ];
            echo json_encode($response);
            exit();
        } else {
            $password = trim($_POST['newPassword']);
        }
    }


    if (empty(trim($_POST["confirmNewPassword"]))) {
        $confirm_password_err = "Please confirm password.";
        $response = [
            'success' => false,
            'message' => 'Please confirm password.'
        ];
    } else {
        $confirm_password = trim($_POST['confirmNewPassword']);
        if ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
            $response = [
                'success' => false,
                'message' => 'Password did not match.'
            ];
        }
    }

    if (empty($password_err) && empty($confirm_password_err)) {
        $passQuery = "SELECT user_id, password FROM users WHERE user_id = ?";
        $pass_stmt = mysqli_prepare($conn, $passQuery);

        if ($pass_stmt) {
            mysqli_stmt_bind_param($pass_stmt, 'i', $currentUser);
            mysqli_stmt_execute($pass_stmt);
            $result = mysqli_stmt_get_result($pass_stmt);

            if ($result && mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $userId = $row['user_id'];
                $hashed_password = $row['password'];

                if (password_verify($currentPassword, $hashed_password)) {
                    if (password_verify($password, $hashed_password)) {
                        $response = [
                            'success' => false,
                            'message' => 'New password cannot be same as current password.'
                        ];
                        echo json_encode($response);
                        exit();
                    }
                    $query = "UPDATE users SET password = ? WHERE user_id = ?";
                    $stmt = mysqli_prepare($conn, $query);

                    if ($stmt) {
                        $param_password = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'si', $param_password, $currentUser);

                        if (mysqli_stmt_execute($stmt)) {
                            $response = [
                                'success' => true,
                                'message' => 'Password updated successfully'
                            ];

                            // Renew the session after a successful password change
                            session_regenerate_id(true);
                        } else {
                            $response = [
                                'success' => false,
                                'message' => 'Something went wrong. Please try again later.'
                            ];
                        }

                        mysqli_stmt_close($stmt);
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'An error occurred.'
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Current password is wrong.'
                    ];
                }

                mysqli_free_result($result);
            } else {
                $response = [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            }

            mysqli_stmt_close($pass_stmt);
        } else {
            $response = [
                'success' => false,
                'message' => 'An error occurred.'
            ];
        }
    }
}

mysqli_close($conn);
echo json_encode($response);