<header id="header" class="transparent-header-modern fixed-header-bg-white w-100">
            <div class="top-header bg-secondary">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <ul class="top-contact list-text-white  d-table">
                                <li><a href="#"><i class="fas fa-phone-alt text-primary mr-1"></i>(+254) 795198192</a></li>
                                <li><a href="https://mail.google.com/mail/u/2/#inbox?compose=new"><i class="fas fa-envelope text-primary mr-1"></i>sheltarproperties@gmail.com</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="top-contact float-right">
                                <ul class="list-text-white d-table">
								<li><i class="fas fa-user text-primary mr-1"></i>
								<?php  if(isset($_SESSION['uemail']))
								{ ?>
								<a href="logout">Logout</a>&nbsp;&nbsp;<?php } else { ?>
								<a href="login">Login</a>&nbsp;&nbsp;
								<?php } ?>
								| </li>
								<li><i class="fas fa-user text-primary mr-1"></i><a href="register"> Register</li>
								</ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-nav secondary-nav hover-primary-nav py-2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <nav class="navbar navbar-expand-lg navbar-light p-0"> <a class="navbar-brand position-relative" href="#"><img class="nav-logo" src="images/logo/Sheltar scaled.png" alt=""></a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown"> <a class="nav-link" href="index" role="button" aria-haspopup="true" aria-expanded="false">Home</a></li>
										<li class="nav-item"> <a class="nav-link" href="property">Properties</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="sheltar_go">Sheltar Movers</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="about">About</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="contact">Contact</a> </li>
                                        
										
										<?php  if(isset($_SESSION['uemail']))
										{ ?>
										<li class="nav-item dropdown">
											<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Account</a>
											<ul class="dropdown-menu">
												<li class="nav-item"> <a class="nav-link" href="profile">Profile</a> </li>
												<li class="nav-item"> <a class="nav-link" href="logout">Logout</a> </li>	
											</ul>
                                        </li>
										<?php } else { ?>
										<li class="nav-item"> <a class="nav-link" href="login">Login/Register</a> </li>
										<?php } ?>
										
                                    </ul>
                                        <div><a class="btn btn-primary d-none d-xl-block m-3" href="request-property">Request Property</a></div>
                                        <div><a class="btn btn-primary d-none d-xl-block" href="./sheltar-properties/agent/add-listing">Submit Property</a></div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        