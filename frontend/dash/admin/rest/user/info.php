<?php

	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../../backend/configuration/database.php';
	require '../../../../../backend/configuration/funcsinit.php';


	if (!$user->UserLoggedIn() || !$user->notBanned($odb) || !$user->isUserAdmin($odb)) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		
		$userid = intval($_POST['userid']);

		if(!filter_var($userid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid userID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid userID!'));
          	die();
    	}

    	if(empty($errors)){
    		$UserInfo = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
    		$UserInfo -> execute(array(':id' => $userid));

    		$user = $UserInfo -> fetch(PDO::FETCH_ASSOC);

    		$expire = date('m-d-Y H:i', $user['planexpire']);
			$apikey = $odb -> query("SELECT `api_key` FROM `apikeys` WHERE `user` = '{$user['username']}' LIMIT 1")->fetchColumn(0);
    		
    		$createddb = strtotime($user['created']);
    		$created = date('m-d-Y H:i', $createddb);

    		$lastlogindb = strtotime($user['lastlogin']);
    		$lastlogin = date('m-d-Y H:i', $lastlogindb);

    		echo json_encode(array(
				'status'=>'success', 
				'username'=>$user['username'],
				'email'=>$user['email'],
				'rank'=>$user['rank'],
				'plan'=>$user['plan'],
				'expire'=>$expire,
				'balance'=>$user['balance'],
				'premium'=>$user['premium'],
				'apiaccess'=>$user['apiaccess'],
				'apikey'=>$apikey,
				'apiaccess'=>$user['apiaccess'],
				'aconcs'=>$user['addon_concs'],
				'atime'=>$user['addon_time'],
				'ablacklist'=>$user['addon_blacklist'],
				'secret'=>$user['secretkey'],
				'created'=>$created,
				'lastlogin'=>$lastlogin

			));

    	}

	}
	













?>