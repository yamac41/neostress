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

		$banid = intval($_POST['banid']);
		
		$BanDB = $odb -> prepare("DELETE FROM `bans` WHERE `id` = :id");
		$BanDB -> execute(array(':id' => $banid));
		

		echo json_encode(array('status'=>'success', 'message'=>'User successfully unbanned.'));


	}


?>