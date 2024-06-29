<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
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
                                        <a href="#"
                                            class="noble-ui-logo d-block mb-2">Sheltar<span>Properties</span></a>
                                        <h5 class="text-muted fw-normal mb-4">Login to your account.</h5>
                                        <form class="forms-sample" method="post" enctype="multipart/form-data"
                                            id="login-form">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email address</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter your registered email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Enter your password" required>
                                            </div>
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-primary text-white me-2 mb-2 mb-md-0">Sign
                                                    in</button>

                                                <a href="<?php echo $login_url; ?>" type="button"
                                                    class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                                    <svg class="btn-icon-prepend" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24" width="24" height="24"
                                                        class="main-grid-item-icon" fill="none">
                                                        <path
                                                            d="M24 12.276c0-.816-.067-1.636-.211-2.438H12.242v4.62h6.612a5.549 5.549 0 0 1-2.447 3.647v2.998h3.945C22.669 19.013 24 15.927 24 12.276Z"
                                                            fill="#4285F4" />
                                                        <path
                                                            d="M12.241 24c3.302 0 6.086-1.063 8.115-2.897l-3.945-2.998c-1.097.732-2.514 1.146-4.165 1.146-3.194 0-5.902-2.112-6.873-4.951H1.302v3.09C3.38 21.444 7.612 24 12.242 24Z"
                                                            fill="#34A853" />
                                                        <path
                                                            d="M5.369 14.3a7.053 7.053 0 0 1 0-4.595v-3.09H1.302a11.798 11.798 0 0 0 0 10.776L5.369 14.3Z"
                                                            fill="#FBBC04" />
                                                        <path
                                                            d="M12.241 4.75a6.727 6.727 0 0 1 4.696 1.798l3.495-3.425A11.898 11.898 0 0 0 12.243 0C7.611 0 3.38 2.558 1.301 6.615l4.067 3.09C6.336 6.862 9.048 4.75 12.24 4.75Z"
                                                            fill="#EA4335" />
                                                    </svg>Sign in with Google</a>

                                                <!-- Sign in with google -->

                                            </div>
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-md-6">
                                                    <a href="./sign-up" class="d-block mt-3 text-muted">Don't have an
                                                        account? Sign up</a>
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