<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true || $_SESSION["user_type"] == 3) {
    header("location: ./dashboard");
    exit;
}

require_once '../config.php';

$login_url = $client->createAuthUrl();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Sign In - Sheltar Properties</title>


    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/vendors/core/core.css">

    <link rel="stylesheet" href="../assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/sweetalert2/sweetalert2.min.css">

    <link rel="stylesheet" href="../assets/css/demo1/style.min.css">

    <style>
        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #075C52;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body class="sidebar-dark">
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pe-md-0">
                                    <div class="auth-side-wrapper">

                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">

                                        <div id="loader">
                                            <div class="loader"></div>
                                        </div>

                                        <a href="#"
                                            class="noble-ui-logo d-block mb-2">Sheltar<span>Properties</span></a>
                                        <h5 class="text-muted fw-normal mb-4">Paasword Reset.</h5>
                                        <form class="forms-sample" method="post" enctype="multipart/form-data"
                                            id="reset-form">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email address</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter your registered email" required>
                                            </div>
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-primary text-white me-2 mb-2 mb-md-0">Recover Password</button>

                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-md-6">
                                                    <a href="./sign-in" class="d-block mt-3 text-muted">Back to login</a>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end">
                                                    <a href="../../index.php"
                                                        class="btn btn-primary text-white me-2 mb-2 mb-md-0">Go back
                                                        Home</a>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../assets/vendors/core/core.js"></script>

    <script src="../assets/vendors/feather-icons/feather.min.js"></script>
    <script src="../assets/vendors/sweetalert2/sweetalert2.min.js"></script>

    <script src="../assets/js/template.js"></script>
    <script src="./assets/js/auth.js"></script>

</body>

</html>