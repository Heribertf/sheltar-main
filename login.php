<?php
session_start();
include ("config.php");

require_once './sheltar-properties/new-config.php';

$login_url = $client->createAuthUrl();

$error = "";
$msg = "";
if (isset($_REQUEST['login'])) {
	$email = $_REQUEST['email'];
	$pass = $_REQUEST['pass'];


	if (!empty($email) && !empty($pass)) {
		$sql = "SELECT * FROM user where uemail='$email' && upass='$pass'";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		if ($row) {

			$_SESSION['uid'] = $row['uid'];
			$_SESSION['uemail'] = $email;
			$_SESSION['uname'] = $row['uname'];
			header("location:index.php");

		} else {
			$error = "<p class='alert alert-warning'>Login Not Successful</p> ";
		}
	} else {
		$error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Meta Tags -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="images/favicon.ico">

	<!--	Fonts
	========================================================-->
	<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">

	<!--	Css Link
	========================================================-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="css/layerslider.css">
	<link rel="stylesheet" type="text/css" href="css/color.css">
	<link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/flaticon/flaticon.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">

	<!--	Title
	=========================================================-->
	<title>Sheltar Login</title>
</head>

<body>

	<!--	Page Loader
=============================================================
<div class="page-loader position-fixed z-index-9999 w-100 bg-white vh-100">
	<div class="d-flex justify-content-center y-middle position-relative">
	  <div class="spinner-border" role="status">
		<span class="sr-only">Loading...</span>
	  </div>
	</div>
</div>
-->


	<div id="page-wrapper">
		<div class="row">
			<!--	Header start  -->
			<?php include ("include/header.php"); ?>
			<!--	Header end  -->

			<!--	Banner   --->
			<div class="banner-full-row page-banner" style="background-image:url('images/breadcromb.jpg');">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<h2 class="page-name float-left text-white text-uppercase mt-1 mb-0"><b>Login</b></h2>
						</div>
						<div class="col-md-6">
							<nav aria-label="breadcrumb" class="float-left float-md-right">
								<ol class="breadcrumb bg-transparent m-0 p-0">
									<li class="breadcrumb-item text-white"><a href="index.php">Home</a></li>
									<li class="breadcrumb-item active">Login</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
			</div>
			<!--	Banner   --->



			<div class="page-wrappers login-body full-row bg-gray">
				<div class="login-wrapper">
					<div class="container">
						<div class="loginbox">
							<div class="login-right">
								<div class="login-right-wrap">
									<h1>Login</h1>
									<p class="account-subtitle">Access to our dashboard</p>
									<?php echo $error; ?>
									<?php echo $msg; ?>
									<!-- Form -->
									<form method="post">
										<div class="form-group">
											<input type="email" name="email" class="form-control"
												placeholder="Your Email*">
										</div>
										<div class="form-group">
											<input type="password" name="pass" class="form-control"
												placeholder="Your Password">
										</div>

										<button class="btn btn-primary" name="login" value="Login"
											type="submit">Login</button>
										<a href="./sheltar-properties/agent/sign-in" class="btn btn-primary ml-3">Login
											as
											Agent</a>

									</form>

									<div class="login-or">
										<span class="or-line"></span>
										<span class="span-or">or</span>
									</div>

									<!-- Social Login -->
									<div class="social-login" style="">
										<span style="font-size: 16px; color: #333;">Login with</span>
										<a href="<?php echo $login_url; ?>" class="">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3"
												style="height: 24px;">
												<path
													d="M533.5 278.4c0-17.3-1.6-33.8-4.6-50H273v95.2h146.9c-6.4 34.7-25 63.9-52.6 83.5v69.2h84.7c49.5-45.6 78.5-112.9 78.5-188.9z"
													fill="#4285f4" />
												<path
													d="M273 544.3c71.1 0 130.7-23.8 174.3-64.7l-84.7-69.2c-23.6 15.8-53.4 25.2-89.5 25.2-68.9 0-127.3-46.6-148.2-109.2H35.1v68.8c43.5 85.8 131.4 149.1 237.9 149.1z"
													fill="#34a853" />
												<path
													d="M124.8 326.4c-6.2-18.3-9.7-37.9-9.7-58s3.5-39.7 9.7-58l-.2-.1L35 142.6C12.5 185 0 235 0 278.4s12.5 93.4 35 135.8l89.8-70.2z"
													fill="#fbbc05" />
												<path
													d="M273 109.2c37.3 0 70.7 12.9 97 38.1l72.5-72.5C403.7 26.3 344.1 0 273 0 166.5 0 78.5 63.3 35 149.1l89.7 70.2c20.9-62.6 79.3-109.2 148.3-109.2z"
													fill="#ea4335" />
											</svg>
										</a>
										<a href="./twitter-login.php" class="">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
												style="height: 24px;">
												<path
													d="M23.444 4.834c-.835.37-1.73.621-2.675.733a4.685 4.685 0 0 0 2.048-2.57c-.911.54-1.923.926-3.002 1.14A4.667 4.667 0 0 0 16.29 3c-2.575 0-4.66 2.086-4.66 4.662 0 .365.041.719.119 1.06-3.874-.195-7.31-2.051-9.61-4.874-.402.689-.63 1.49-.63 2.342 0 1.615.823 3.04 2.075 3.874-.765-.025-1.485-.234-2.114-.583v.059c0 2.255 1.604 4.137 3.732 4.562a4.69 4.69 0 0 1-2.108.081c.595 1.86 2.323 3.215 4.367 3.25a9.375 9.375 0 0 1-5.8 2.003c-.377 0-.748-.022-1.115-.065a13.255 13.255 0 0 0 7.175 2.104c8.61 0 13.313-7.13 13.313-13.31 0-.203-.005-.405-.014-.606a9.485 9.485 0 0 0 2.344-2.415z"
													fill="#1DA1F2" />
											</svg>
										</a>
									</div>

									<!-- /Social Login -->

									<div class="text-center dont-have">Don't have an account? <a
											href="register.php">Register</a></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--	login  -->


			<!--	Footer   start-->
			<?php include ("include/footer.php"); ?>
			<!--	Footer   start-->

			<!-- Scroll to top -->
			<a href="#" class="bg-secondary text-white hover-text-secondary" id="scroll"><i
					class="fas fa-angle-up"></i></a>
			<!-- End Scroll To top -->
		</div>
	</div>
	<!-- Wrapper End -->

	<!--	Js Link
============================================================-->
	<script src="js/jquery.min.js"></script>
	<!--jQuery Layer Slider -->
	<script src="js/greensock.js"></script>
	<script src="js/layerslider.transitions.js"></script>
	<script src="js/layerslider.kreaturamedia.jquery.js"></script>
	<!--jQuery Layer Slider -->
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/tmpl.js"></script>
	<script src="js/jquery.dependClass-0.1.js"></script>
	<script src="js/draggable-0.1.js"></script>
	<script src="js/jquery.slider.js"></script>
	<script src="js/wow.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>