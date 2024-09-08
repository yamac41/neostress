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

		if(empty($serverid)){
			$errors[] = "ServerID is empty";
			echo json_encode(array('status'=>'error', 'message'=>'ServerID is empty!'));
			die();
		}
		
		if(empty($errors)) {
			$DeleteServer= $odb -> prepare("DELETE FROM `servers` WHERE `id` = :serverid");
			$DeleteServer -> execute(array(':serverid' => $serverid));

			echo json_encode(array('status'=>'success', 'message'=>'Server successfully deleted.'));
		}

	}


?>