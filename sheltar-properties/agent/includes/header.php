<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== 3) {
    header("location: ./sign-in");
    exit;
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Agent Dashboard - Sheltarn Developers</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="../assets/vendors/core/core.css">
    <!-- endinject -->

    <link rel="stylesheet" href="../assets/vendors/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl.carousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="../assets/vendors/animate.css/animate.min.css">
    <link rel="stylesheet" href="../assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="../assets/vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/vendors/prismjs/themes/prism.css">

    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/7.2.1/css/flag-icons.min.css"
        integrity="sha512-bZBu2H0+FGFz/stDN/L0k8J0G8qVsAL0ht1qg5kTwtAheiXwiRKyCq1frwfbSFSJN3jooR5kauE0YjtPzhZtJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/demo1/style.min.css">
    <!-- End layout styles -->

</head>

<body class="sidebar-dark">
    <div class="main-wrapper">