<?php

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';

	// if(!(isset($_SERVER['HTTP_REFERER']))){
	//     die(json_encode(array('status'=>'error', 'message'=>'Authorization error!')));
	// }

	// $turnstileKeys = array(
	// 	"king.cfxsecurity.ru" => "0x4AAAAAAAKOu3In3LyyZdDQFB6zpUG9pAE",
	// );

	// $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
	// if (!isset($ip)) {
	//   $ip = $_SERVER['REMOTE_ADDR'];
	// }

	$date = date('Y-m-d H:i:s');

	$username = stripslashes(htmlentities($user -> CheckInput($_POST['username'])));
	$email = stripslashes($_POST['email']);
	$password = htmlspecialchars($user -> CheckInput($_POST['password']));
	$rpassword = htmlspecialchars($user -> CheckInput($_POST['rpassword']));
	$hashedpass = SHA1(md5($password));
	$csrf = htmlspecialchars($user -> CheckInput($_POST['csrf']));
	$captcha = htmlspecialchars($user -> CheckInput($_POST['captcha']));

	$random = md5(rand());
	$secretkey = substr($random, 0, 16);

	if($user -> SecureText($username) || $user -> SecureText($email) || $user -> SecureText($password) || $user -> SecureText($rpassword) || $user -> SecureText($hashedpass) || $user -> SecureText($csrf) || $user -> SecureText($captcha)){
		$errors[] = "Unsafe characters in your input.";
        echo json_encode(array('status'=>'error', 'message'=>'Unsafe characters in your input.'));
        die();
	}

	if($setting['registration'] == 'off'){
		$errors[] = "Registration is currently disabled, please try again later.";
        echo json_encode(array('status'=>'error', 'message'=>'Registration is currently disabled, please try again later.'));
        die();
	} 

	if(empty($username) || empty($email) || empty($password) || empty($rpassword)){
        $errors[] = "Please fill all required fields to create account!";
		echo json_encode(array('status'=>'error', 'message'=>$username.'  '.$email.'   '.$password.'   '.$rpassword)	);
        die();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    	$errors[] = "Invalid email format";
        echo json_encode(array('status'=>'error', 'message'=>'Invalid email format!'));
        die();
    }

    if (!ctype_alnum($username) || strlen($username) < 6 || strlen($username) > 20){
    	$errors[] = "Username must be between 6-20 characters!";
        echo json_encode(array('status'=>'error', 'message'=>'Username must be between 6-20 characters!'));
        die();
    }
    if ($password != $rpassword){
    	$errors[] = "The passwords you entered do not match!";
        echo json_encode(array('status'=>'error', 'message'=>'The passwords you entered do not match!'));
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
			// $secretKey = $turnstileKeys[$_SERVER['HTTP_HOST']];
			// $ip = $_SERVER['REMOTE_ADDR'];
		 
			// $url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
			// $data = array('secret' => $secretKey, 'response' => $captcha, 'remoteip' => $ip);
			 
			// $options = array(
			// 	'http' => array(
			// 	'method' => 'POST',
			// 	'content' => http_build_query($data))
			// );
			 
			// $stream = stream_context_create($options);
			// $result = file_get_contents($url_path, false, $stream);
			// $response = $result;
			// $responseKeys = json_decode($response,true);
			// if(intval($responseKeys["success"]) !== 1) {
			// 	echo json_encode(array('status'=>'error', 'message'=>'Invalid captcha (#1)'));
			// 	die();
			// } else {
			// 	if(empty($errors)){
					$DBUserName = $odb -> prepare("SELECT `username` FROM `users` WHERE `username` = :username");
					$DBUserName -> execute(array(':username' => $username));
					$checkusername = $DBUserName -> rowCount();

					$DBEmail = $odb -> prepare("SELECT `email` FROM `users` WHERE `email` = :email");
					$DBEmail -> execute(array(':email' => $email));
					$checkemail = $DBEmail -> rowCount();

					if($checkusername > 0){
						$errors[] = "This username already exists in the database!";
						echo json_encode(array('status'=>'error', 'message'=>'This username already exists in the database!'));
						die();
					}
					else if($checkemail > 0){
						$errors[] = "There is already registered account with this email address!";
						echo json_encode(array('status'=>'error', 'message'=>'There is already registered account with this email address!'));
						die();

					}else{
						$InsertDB = $odb -> prepare("INSERT INTO `users`(`id`, `username`, `password`, `email`, `secretkey`, `rank`, `plan`, `planexpire`, `premium`, `apiaccess`, `apitoken`, `addon_concs`, `addon_time`, `addon_blacklist`, `balance`, `created`, `lastlogin`) VALUES (NULL, :username, :password, :email, :secretkey, 'User', 0, 1767283020, 0, 0, 0, 0, 0, 0, 0, NOW(), NOW())");
						$InsertDB -> execute(array(':username' => $username, ':password' => $hashedpass, ':email' => $email, ':secretkey' => $secretkey));



						$SelectFromDB = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username AND `email` = :email AND `password` = :password");
						$SelectFromDB -> execute(array(':username' => $username, ':email' => $email, ':password' => $hashedpass));
						$userinfo = $SelectFromDB -> fetch(PDO::FETCH_ASSOC);
						session_regenerate_id();
						$_SESSION['loggedin'] = true;
						$_SESSION['id'] = $userinfo['id'];
						$_SESSION['username'] = $userinfo['username'];
						$_SESSION['email'] = $userinfo['email'];
						$_SESSION['rank'] = $userinfo['rank'];

						echo json_encode(array('status'=>'success', 'message'=>'You have successfully registered, you will be redirected in 3 seconds..'));

					}
				
		// 		}
		// 	}
		// }
		
    // }else{
    // 	$errors[] = "There is problem with your request, please try again!";
    //     echo json_encode(array('status'=>'error', 'message'=>'There is problem with your request, please try again!'));
    //     die();
    // }

?>