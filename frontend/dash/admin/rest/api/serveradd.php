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


		$name = htmlentities($_POST['name']);
		$methods = $_POST['methods'];
		$premium = htmlentities($_POST['premium']);
		$slots = intval($_POST['slots']);
		$type = htmlentities($_POST['type']);
		$status = htmlentities($_POST['status']);
		$apiurl = $_POST['apiurl'];

		$AddServer= $odb -> prepare("INSERT INTO `servers`(`id`, `name`, `apiurl`, `methods`, `type`, `premium`, `slots`, `status`, `lastused`) VALUES (NULL, :name, :apiurl, :methods, :type, :premium, :slots, :status, NOW())");
		$AddServer -> execute(array(':name' => $name, ':apiurl' => $apiurl, ':methods' => $methods, ':type' => $type, ':premium' => $premium, ':slots' => $slots, ':status' => $status));

		echo json_encode(array('status'=>'success', 'message'=>'Server successfully added.'));


	}


?>