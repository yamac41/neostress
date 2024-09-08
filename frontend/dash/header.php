<?php
    require_once '../../../backend/configuration/database.php';
    require_once '../../../backend/configuration/funcsinit.php';

    if (!$user -> UserLoggedIn()){
        header('Location: ../login');
        exit;
    }

	$isAdmin = $user->isUserAdmin($odb);

	//if ($user -> isUserSupport($odb)){
    //	$supportli = '<li><a class="dropdown-item" href="support/home">Support Dash</a></li>';
	//}else{
	//	$supportli = '';
	//}

    $UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
    $UserInfoDB -> execute(array(':id' => $_SESSION['id']));
    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
    $planid = $user['plan'];


    $PlanDB = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :id");
    $PlanDB -> execute(array(':id' => $planid));
    $plan = $PlanDB -> fetch(PDO::FETCH_ASSOC);

    $concs = $plan['concs'];
    
    $dbcreated = strtotime($user['created']);
    $created = date('m/d/Y H:i', $dbcreated);


   	$dexp = $user['planexpire'];
    $expire = date('m/d/Y H:i', $dexp);

    if($setting['maintenance'] == 'yes'){
    	header('Location: maintenance');
    	die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  	<title>stresse.guru &mdash; <?php echo $pagename; ?></title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="/assets/js/tailwind.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel="stylesheet" type="text/css" href="/dash/assets/vendor/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="/dash/assets/vendor/css/toastify.min.css">
	<link rel="stylesheet" type="text/css" href="/dash/assets/css/datatables.css?v=1690167653">

	<link rel="stylesheet" href="/dash/assets/css/new_style.css"/>
	<link rel="icon" href="/assets/favicon.ico" type="image/x-icon" />
	<style>
		@media screen and (max-height: 900px) {
			.promo-container {
				display: none;
			}
		}
	</style>
</head>
<body>
	
	<div id="preloader">
    	<span class="loader-animated"></span>
  	</div>
	

	  <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto sidebar-bg">
      <a href="/dash/home" class="flex items-center p-2.5 mb-5">
		<img src="/assets/images/logo.png"/>
      </a>
      <ul class="space-y-2 font-medium">
         <li>
            <a href="/dash/home" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Dashboard' ? 'active' : '' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M12.5227 1.33611C12.1804 1.24343 11.8196 1.24343 11.4773 1.33611C11.08 1.4437 10.7454 1.70635 10.4784 1.91598L10.4038 1.9744L3.54376 7.30988C3.16713 7.60216 2.83532 7.85966 2.58806 8.19396C2.37107 8.48735 2.20942 8.81785 2.11106 9.16926C1.99898 9.56967 1.99943 9.98968 1.99995 10.4664L2.00002 17.8383C2 18.3654 1.99998 18.8202 2.03059 19.1948C2.06289 19.5901 2.1342 19.9833 2.327 20.3617C2.61462 20.9262 3.07356 21.3851 3.63805 21.6728C4.01643 21.8656 4.40964 21.9369 4.80499 21.9692C5.17956 21.9998 5.63431 21.9998 6.16145 21.9997H17.8386C18.3657 21.9998 18.8205 21.9998 19.195 21.9692C19.5904 21.9369 19.9836 21.8656 20.362 21.6728C20.9265 21.3851 21.3854 20.9262 21.673 20.3617C21.8658 19.9833 21.9371 19.5901 21.9694 19.1948C22.0001 18.8202 22 18.3654 22 17.8383L22.0001 10.4664C22.0006 9.98969 22.0011 9.56967 21.889 9.16926C21.7906 8.81785 21.629 8.48735 21.412 8.19396C21.1647 7.85966 20.8329 7.60216 20.4563 7.30987L13.5963 1.9744L13.5216 1.91599C13.2546 1.70636 12.9201 1.4437 12.5227 1.33611ZM8.00003 15.9998C7.44775 15.9998 7.00003 16.4475 7.00003 16.9998C7.00003 17.552 7.44775 17.9998 8.00003 17.9998H16C16.5523 17.9998 17 17.552 17 16.9998C17 16.4475 16.5523 15.9998 16 15.9998H8.00003Z" <?=$pagename=='Dashboard' ? 'fill="white"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?>/>
				</svg>
               <span class="ml-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="/dash/stress" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Stress Panel' ? 'active' : '' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M12 19.5H12.01M22.8064 8.70076C19.9595 6.09199 16.1656 4.5 11.9999 4.5C7.83414 4.5 4.04023 6.09199 1.19336 8.70076M4.73193 12.243C6.67006 10.5357 9.21407 9.5 12 9.5C14.7859 9.5 17.3299 10.5357 19.268 12.243M15.6983 15.7751C14.6792 14.9763 13.3952 14.5 11.9999 14.5C10.5835 14.5 9.28172 14.9908 8.25537 15.8116" <?=$pagename=='Stress Panel' ? 'stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?>/>
				</svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Stress Panel</span>
            </a>
         </li>
         <li>
            <a href="/dash/purchase" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Purchase' ? 'active' : '' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M6.50014 17H17.3294C18.2793 17 18.7543 17 19.1414 16.8284C19.4827 16.6771 19.7748 16.4333 19.9847 16.1246C20.2228 15.7744 20.3078 15.3071 20.4777 14.3724L21.8285 6.94311C21.8874 6.61918 21.9169 6.45721 21.8714 6.33074C21.8315 6.21979 21.7536 6.12651 21.6516 6.06739C21.5353 6 21.3707 6 21.0414 6H5.00014M2 2H3.3164C3.55909 2 3.68044 2 3.77858 2.04433C3.86507 2.0834 3.93867 2.14628 3.99075 2.22563C4.04984 2.31565 4.06876 2.43551 4.10662 2.67523L6.89338 20.3248C6.93124 20.5645 6.95016 20.6843 7.00925 20.7744C7.06133 20.8537 7.13493 20.9166 7.22142 20.9557C7.31956 21 7.44091 21 7.6836 21H19" <?=$pagename=='Purchase' ? 'stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?>/>
				</svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Purchase</span>
            </a>
         </li>
		 <li>
            <a href="/dash/deposit" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Deposit' ? 'active' : '' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" <?=$pagename=='Deposit' ? 'stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?>></path>
				</svg>
				
               <span class="flex-1 ml-3 whitespace-nowrap">Deposit</span>
            </a>
         </li>
         <li>
            <a href="/dash/manager" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='API Manager' ? 'active' : '' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M14 17L17 14L14 11M10 7L7 10L10 13M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" <?=$pagename=='API Manager' ? 'stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?> stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
               <span class="flex-1 ml-3 whitespace-nowrap">API Manager</span>
            </a>
         </li>
		 <!-- <li>
            <a href="/dash/tools" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Tools' ? 'active' : '' ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						<path fill="#FFF" d="M19.89,9.55A1,1,0,0,0,19,9H14V3a1,1,0,0,0-.69-1,1,1,0,0,0-1.12.36l-8,11a1,1,0,0,0-.08,1A1,1,0,0,0,5,15h5v6a1,1,0,0,0,.69.95A1.12,1.12,0,0,0,11,22a1,1,0,0,0,.81-.41l8-11A1,1,0,0,0,19.89,9.55ZM12,17.92V14a1,1,0,0,0-1-1H7l5-6.92V10a1,1,0,0,0,1,1h4Z"/>
					</svg>
				<span class="flex-1 ml-3 whitespace-nowrap">Tools</span>
            </a>
         </li> -->
         <li>
      </ul>
   </div>
   <div class="absolute bottom-0 left-0 hidden w-full p-4 space-x-4 sidebar-bg text-white" sidebar-bottom-menu="" style="display: block;">
   		<div class="contents">
		   	<ul class="space-y-2 font-medium">
				<li>
					<a href="https://t.me/hitemwhere" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group <?=$pagename=='Support' ? 'active' : '' ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" <?=$pagename=='Support' ? 'stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' : 'stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' ?>/>
						</svg>
						<span class="ml-3">Support</span>
					</a>
				</li>
				<li>
					<a href="https://t.me/stresseguru" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M21.9841 5.38149C22.132 4.46094 21.2226 3.73434 20.3722 4.09371L3.43322 11.2512C2.82333 11.5089 2.86795 12.3981 3.50049 12.5919L6.99367 13.6625C7.66045 13.8669 8.38233 13.7611 8.96457 13.3741L16.8403 8.13749C17.0778 7.97954 17.3367 8.30457 17.1337 8.50583L11.4646 14.131C10.9147 14.6767 11.0238 15.6014 11.6854 16.0006L18.0325 19.8312C18.7444 20.2608 19.6602 19.8293 19.7934 19.0013L21.9841 5.38149Z" fill="#96A6C6"/>
						</svg>
						<span class="ml-3">Telegram Channel</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="contents">
			<div class="promo-container mb-4 p-3 py-8 mt-20">
				<div class="promo-image relative">
					<div class="absolute" style="top: -70px;">
						<img src="/assets/images/gift.png">
					</div>
				</div>
				<div class="promo-code">
					‘SUMMER20’
				</div>
				<div class="promo-desc">
					Get your -20% promocode for next purchase
				</div>
			</div>
		</div>
		<div class="contents">
			<div class="flex items-center space-x-4 mt-6">
				<div class="flex-shrink-0">
					<button type="button" class="flex text-sm bg-gray-800 rounded-full gradient-avatar focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
						<div class="w-8 h-8 rounded-full user-pfp" alt="user photo">
							<?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
						</div>
					</button>
				</div>
				<div class="flex-1 min-w-0">
					<a href="/dash/profile">
						<p class="font-sm dark:text-white">
							<?= $_SESSION['username'] ?>
						</p>
						<p class="text-sm dark:text-gray-400">
							View Profile
						</p>
					</a>
				</div>
			</div>
		</div>
   </div>
</aside>
<div class="p-4 lg:px-24 sm:ml-64 text-white">
	<div class="lg:py-3 lg:pl-3">
	    <div class="flex items-center justify-between">
	      <div class="flex items-center justify-start">
	      </div>
	      <div class="flex items-center">
	          <div class="hidden mr-3 -mb-1 sm:block">
	            <span></span>
	          </div>
	
	          <button type="button" data-dropdown-toggle="notification-dropdown" class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
	            <span class="sr-only">View notifications</span>
	
	            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M9.35419 21C10.0593 21.6224 10.9856 22 12 22C13.0145 22 13.9407 21.6224 14.6458 21M18 8C18 6.4087 17.3679 4.88258 16.2427 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.8826 2.63214 7.75738 3.75736C6.63216 4.88258 6.00002 6.4087 6.00002 8C6.00002 11.0902 5.22049 13.206 4.34968 14.6054C3.61515 15.7859 3.24788 16.3761 3.26134 16.5408C3.27626 16.7231 3.31488 16.7926 3.46179 16.9016C3.59448 17 4.19261 17 5.38887 17H18.6112C19.8074 17 20.4056 17 20.5382 16.9016C20.6852 16.7926 20.7238 16.7231 20.7387 16.5408C20.7522 16.3761 20.3849 15.7859 19.6504 14.6054C18.7795 13.206 18 11.0902 18 8Z" stroke="#96A6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
	          </button>
	
	          <div class="z-20 z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:divide-gray-600 dark:bg-gray-700" id="notification-dropdown" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1700px, 65px);" data-popper-placement="bottom">
	            <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
	                Notifications
	            </div>
	            <div>
					<div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400 text-center">No notifications</div>
	            </div>
	          </div>
	
	          <div class="flex items-center ml-3">
	            <div>
	              <button type="button" class="flex text-sm bg-gray-800 rounded-full gradient-avatar focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
	                <span class="sr-only">Open user menu</span>
	                <div class="w-8 h-8 rounded-full user-pfp" alt="user photo">
						<?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
					</div>
	              </button>
	            </div>
	
	            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-2" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1828px, 61px);" data-popper-placement="bottom">
	              <div class="px-4 py-3" role="none">
	                <p class="text-sm text-gray-900 dark:text-white" role="none">
	                  <?= $_SESSION['username'] ?>
	                </p>
	                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
	                  <?= $user['email'] ?>
	                </p>
	              </div>
	              <ul class="py-1" role="none">
	                <li>
	                  <a href="/dash/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profile</a>
	                </li>
					<?php if($isAdmin): ?>
						<li>
	                  		<a href="/dash/admin/home" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Admin dashboard</a>
	                	</li>
					<?php endif; ?>
	                <li>
	                  <a href="#" onclick="SignOut()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
	                </li>
	              </ul>
	            </div>
	          </div>
	        </div>
	    </div>
	</div>
</div>

<div class="site-container">
	<div class="site-child" style="justify-content: center;display: flex; position: relative;">