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
		
		$length = intval($_POST['length']);
		$unit = htmlentities($_POST['unit']);

		$increase = (strtotime("+".$length." ".$unit) - time());
		
		$SQLUpdate = $odb -> prepare("UPDATE `users` SET `planexpire` = `planexpire` + :increase");
		$SQLUpdate -> execute(array(':increase' => $increase));

		echo json_encode(array('status'=>'success', 'message'=>'Plan expiration successfully increased by '.$length.' '.$unit.' to all users.'));
        die();












	}

?>