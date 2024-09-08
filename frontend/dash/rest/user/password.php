<?php
	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';


	if (!$user->UserLoggedIn() || !$user->notBanned($odb)) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}


	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$currentpass = SHA1(md5(htmlentities($user -> CheckInput($_POST['currpass']))));
		$newpass = SHA1(md5(htmlentities($user -> CheckInput($_POST['newpass']))));
		
		if($user -> SecureText($currentpass) || $user -> SecureText($newpass)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(empty($currentpass) || empty($newpass)){
			$errors[] = "Please fill all required fields";
        	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields'));
        	die();
		}

		$CheckPass = $odb -> prepare("SELECT `password` FROM `users` WHERE `password` = :pass AND `id` = :id");
		$CheckPass -> execute(array(':pass' => $currentpass, ':id' => $_SESSION['id']));

		$count = $CheckPass -> rowCount();

		if($count == 0){
			$errors[] = "Current password is incorrect";
        	echo json_encode(array('status'=>'error', 'message'=>'Current password is incorrect'));
        	die();
		}


		if(empty($errors)){
			$UpdatePass = $odb -> prepare("UPDATE `users` SET `password` = :pass WHERE `id` = :id");
			$UpdatePass -> execute(array(':pass' => $newpass, ':id' => $_SESSION['id']));
			echo json_encode(array('status'=>'success', 'message'=>'Your password successfully changed'));
		}

	}
















?>