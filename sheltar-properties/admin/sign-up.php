<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Sign Up - Sheltarn Developers</title>


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
                                            class="noble-ui-logo d-block mb-2">Sheltarn<span>Developers</span></a>
                                        <h5 class="text-muted fw-normal mb-4">Create your account.</h5>
                                        <form class="forms-sample" method="post" enctype="multipart/form-data"
                                            id="register-form">
                                            <div class="mb-3">
                                                <label for="firstName" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="firstName" name="firstName"
                                                    placeholder="Enter your first name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lastName" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="lastName" name="lastName"
                                                    placeholder="Enter your last name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter your phone number" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email address</label>
                                                <input type="email" class="form-control" id="userEmail" name="userEmail"
                                                    placeholder="Enter your email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Enter your password" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="confPassword" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" id="confPassword"
                                                    name="confPassword" placeholder="Repeat your password" required>
                                            </div>
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-primary text-white me-2 mb-2 mb-md-0">Sign
                                                    up</button>
                                                <button type="button"
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
                                                    </svg>

                                                    Sign up with google
                                                </button>
                                            </div>
                                            <a href="./sign-in" class="d-block mt-3 text-muted">Already have an
                                                account? Sign
                                                in</a>
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