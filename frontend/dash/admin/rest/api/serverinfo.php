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
		
		$serverid = intval($_POST['serverid']);

		if(!filter_var($serverid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid serverID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid serverID!'));
          	die();
    	}

    	if(empty($errors)){
    		$Info = $odb -> prepare("SELECT * FROM `servers` WHERE `id` = :id");
    		$Info -> execute(array(':id' => $serverid));

    		$server = $Info -> fetch(PDO::FETCH_ASSOC);

    		echo json_encode(array(
				'status'=>'success', 
				'name'=>$server['name'],
				'status'=>$server['status'],
				'slots'=>$server['slots'],
				'premium'=>$server['premium'],
				'type'=>$server['type'],
				'apiurl'=>$server['apiurl'],
				'methods'=>$server['methods'],

			));

    	}

	}
	













?>