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
		$name = htmlentities($_POST['name']);
		$methods = $_POST['methods'];
		$premium = htmlentities($_POST['premium']);
		$slots = intval($_POST['slots']);
		$type = htmlentities($_POST['type']);
		$status = htmlentities($_POST['status']);
		$apiurl = $_POST['apiurl'];

		$UpdateServer= $odb -> prepare("UPDATE `servers` SET `name`= :name,`apiurl`= :apiurl,`methods`= :methods,`type`= :type,`premium`= :premium,`slots`= :slots,`status`= :status WHERE `id` = :serverid");
		$UpdateServer -> execute(array(':name' => $name, ':apiurl' => $apiurl, ':methods' => $methods, ':type' => $type, ':premium' => $premium, ':slots' => $slots, ':status' => $status, ':serverid' => $serverid));

		echo json_encode(array('status'=>'success', 'message'=>'Server successfully updated.'));


	}


?>