<?php

	

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';

	if(!(isset($_SERVER['HTTP_REFERER']))){
	    die(json_encode(array('status'=>'error', 'message'=>'Authorization error!')));
	}

	$turnstileKeys = array(
		"king.cfxsecurity.ru" => "0x4AAAAAAAKOu3In3LyyZdDQFB6zpUG9pAE",
	);


	$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
	if (!isset($ip)) {
	  $ip = $_SERVER['REMOTE_ADDR'];
	}
	if(!(filter_var($ip, FILTER_VALIDATE_IP))){
		$errors[] = "Problem with your IP Address.";
        echo json_encode(array('status'=>'error', 'message'=>'Problem with your IP Address.'));
        die();

	}

	$date = date('Y-m-d H:i:s');

	$username = stripslashes(htmlentities($user -> CheckInput($_POST['username'])));
	$password = htmlspecialchars($user -> CheckInput($_POST['password']));
	$csrf = htmlspecialchars($user -> CheckInput($_POST['csrf']));
	//$token = htmlspecialchars($user -> CheckInput($_POST['token']));
	$captcha = htmlspecialchars($user -> CheckInput($_POST['captcha']));
	$hashed = SHA1(md5($password));
	
	

	if($user -> SecureText($username) || $user -> SecureText($password) || $user -> SecureText($hashed) || $user -> SecureText($csrf) || $user -> SecureText($captcha)){
		$errors[] = "Unsafe characters in your input.";
        echo json_encode(array('status'=>'error', 'message'=>'Unsafe characters in your input.'));
        die();
	}

	$SelectBan = $odb -> prepare("SELECT * FROM bans WHERE username = :username");
	$SelectBan -> execute(array(":username" => $username));
	$ban = $SelectBan -> fetch(PDO::FETCH_ASSOC);
	$countban = $SelectBan -> rowCount();

	if($countban > 0){
		$dexp = strtotime($ban['expire']);
    	$expire = date('m/d/Y H:i', $dexp);
		$errors[] = "Please fill all required fields to create account!";
        echo json_encode(array('status'=>'error', 'message'=>'You are banned. Reason: '.$ban['reason'].' expires: '.$expire.', if you think this is a mistake contact our support!'));
        die();
	}

	if($setting['login'] == 'off'){
		$errors[] = "Login is currently disabled, please try again later.";
        echo json_encode(array('status'=>'error', 'message'=>'Login is currently disabled, please try again later.'));
        die();
	}

	if(empty($username) || empty($password)){
		$errors[] = "Please fill all required fields to sign in.";
        echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields to sign in.'));
        die();
	}
	
	// if(empty($csrf)){
	// 	$errors[] = "There is problem with your request, please try again!";
    //     echo json_encode(array('status'=>'error', 'message'=>'There is problem with your request, please try again!'));
    //     die();
	// }

	// if ($aWAF->verifyCSRF($csrf)){
	// 	if(empty($captcha)){
	// 		$errors[] = "Please enter the captcha.";
	// 		echo json_encode(array('status'=>'error', 'message'=>'Please enter the captcha.'));
	// 		die();
	// 	} else {
	// 		$secretKey = $turnstileKeys[$_SERVER['HTTP_HOST']];
	// 		$ip = $_SERVER['REMOTE_ADDR'];
		 
	// 		$url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
	// 		$data = array('secret' => $secretKey, 'response' => $captcha, 'remoteip' => $ip);
			 
	// 		$options = array(
	// 			'http' => array(
	// 			'method' => 'POST',
	// 			'content' => http_build_query($data))
	// 		);
			 
	// 		$stream = stream_context_create($options);
	// 		$result = file_get_contents($url_path, false, $stream);
	// 		$response = $result;
	// 		$responseKeys = json_decode($response,true);

	// 		if(intval($responseKeys["success"]) !== 1) {
	// 			echo json_encode(array('status'=>'error', 'message'=>'Invalid captcha (#1)'));
	// 			die();
	// 		} else {
	// 			if(empty($errors)){
					$DBCheck = $odb -> prepare("SELECT `id`, `username`, `email`, `rank` FROM `users` WHERE `username` = :username AND `password` = :password");
					$DBCheck -> execute(array(':username' => $username, ':password' => $hashed));
	
					$userinfo = $DBCheck -> fetch(PDO::FETCH_ASSOC);
					$countaccs = $DBCheck -> rowCount();
	
					if($countaccs > 0){
	
						session_regenerate_id();
						$_SESSION['loggedin'] = true;
						$_SESSION['id'] = $userinfo['id'];
						$_SESSION['username'] = $userinfo['username'];
						$_SESSION['email'] = $userinfo['email'];
						$_SESSION['rank'] = $userinfo['rank'];
	
						$UpdateDB = $odb -> prepare("UPDATE `users` SET `lastlogin` = :lastlogin WHERE `id` = :id AND `username` = :username");
						$UpdateDB -> execute(array(':lastlogin' => $date, ':id' => $userinfo['id'], ':username' => $userinfo['username']));
						

						echo json_encode(array('status'=>'success', 'message'=>'You have successfully logged in, you will be redirected in 3 seconds..'));
					
					}else{
				
						echo json_encode(array('status'=>'error', 'message'=>'Wrong password or username!'));
				
					}	
// 				}
// 			}
// 		}
// 	} else {
// 		$errors[] = "There is problem with your request, please try again!";
//         echo json_encode(array('status'=>'error', 'message'=>'There is problem with your request, please try again!'));
//         die();
// 	}
?>