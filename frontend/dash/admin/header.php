<?php
	
	require_once '../../../backend/configuration/database.php';
    require_once '../../../backend/configuration/funcsinit.php';

    if (!$user -> UserLoggedIn()){
        header('Location: ../../login');
        exit;
    }

    if (!$user -> isUserAdmin($odb)){
        header('Location: ../home');
        exit;
    }
	
    $AdminInfo = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
    $AdminInfo -> execute(array(':id' => $_SESSION['id']));
    $admin = $AdminInfo -> fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    

    <title>CatStresse &mdash; <?php echo $pagename; ?></title>
    <link rel="icon" href="../assets/img/logo2.webp" type="image/x-icon">

   	<script src="assets/vendor/js/moment.min.js"></script>
    <script src="assets/vendor/js/chart.js"></script>

  	
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css?v=<?php echo time(); ?>">


	<link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/css/toastify.min.css">


	
    

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">



    <link rel="stylesheet" type="text/css" href="assets/css/datatables.css?v=<?php echo time(); ?>">
    <script src="assets/vendor/js/wNumb.min.js"></script>


</head>
<body class="bg-gray-900">
	<div id="preloader">
    	<span class="loader-animated"></span>
  	</div>
	<nav class="navbar navbar-dark bg-gray-800 border-gray-nav">
	  	<div class="container">
		    <a class="navbar-brand">
	        	<span class="self-center logo-text">Cat<span style="color: #B4C6FC;">Stresse</span></span>
	        </a>
		    <div class="d-flex align-items-center">
		    	<div class="flex-shrink-0 dropdown">
		      		<a href="#" class="nav-item-vnm me-2" id="notifdrop" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-bell"></i></a>
		      		
		      		<ul class="dropdown-menu bg-gray-700 dropdown-menu-end rounded-0 mt-2 pt-0 text-small shadow" aria-labelledby="notifdrop">
			          	
			            
			        </ul>
		      	</div>
		      	<div class="flex-shrink-0 dropdown">
		          <a href="#" class="d-block link-dark text-decoration-none ml-3" id="profiledrop" data-bs-toggle="dropdown" aria-expanded="false">
		            <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=8DA2FB&color=fff&rounded=true" alt="mdo" class="rounded-circle profile-img">
		          </a>
		          <ul class="dropdown-menu profildrop bg-gray-700 dropdown-menu-end rounded-0 mt-2 pt-0 text-small shadow" aria-labelledby="profiledrop">
		          	<div class="">
		          		<div class="px-4 py-2 mt-2">
                        	<h6 class="mb-0 text-white"><?php echo $_SESSION['username']; ?></h6>
                        	<p class="mb-0 font-size-11 text-gray-400 fw-semibold"><?php echo $_SESSION['rank']; ?></p>
                        </div>
                    </div>
		            <li><hr class="dropdown-divider"></li>
		            
		            <li><a class="dropdown-item" href="../home">Back to Client</a></li>
		            <li><a class="dropdown-item pointer" onclick="SignOut()">Sign out</a></li>
		          </ul>
		        </div>
		    </div>
	  	</div>
	</nav>
	<nav class="navbar navbar-expand-lg navbar-dark bg-gray-800">
	  <div class="container">
	    
	    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarunder" aria-controls="navbarunder" aria-expanded="false" aria-label="Toggle navigation">
	      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
	    </button>
	    <div class="navbar-collapse justify-content-md-center collapse" id="navbarunder">
	      <ul class="navbar-nav under-nav">
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='Admin Dashboard' ? 'active' : '' ?>" aria-current="page" href="home">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> Home
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='Running Attacks' ? 'active' : '' ?>" href="attacks">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg> Running Attacks
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='Plans Manager' ? 'active' : '' ?>" href="plans">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Plans Manager
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='News Manager' ? 'active' : '' ?>" href="news">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg> News Manager
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='API Manager' ? 'active' : '' ?>" href="manager">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> API Manager
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='Support Tickets' ? 'active' : '' ?>" href="support">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg> Support Tickets
	          </a>
	        </li>
	        <li class="nav-item spacing">
	          <a class="nav-link d-inline-flex <?=$pagename=='Users Manager' ? 'active' : '' ?>" href="users">
	          	<svg class="me-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Users Manager
	          </a>
	        </li>
	      </ul>
	    </div>
	  </div>
	</nav>