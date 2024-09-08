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
		$secretkey = htmlentities($user -> CheckInput($_POST['secretkey']));
		
		if($user -> SecureText($secretkey)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(empty($secretkey)){
			$errors[] = "Please fill all required fields";
        	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields'));
        	die();
		}

		$CheckKey = $odb -> prepare("SELECT `secretkey` FROM `users` WHERE `secretkey` = :secret AND `id` = :id");
		$CheckKey -> execute(array(':secret' => $secretkey, ':id' => $_SESSION['id']));

		$count = $CheckKey -> rowCount();

		if($count == 0){
			$errors[] = "Secret key is incorrect";
        	echo json_encode(array('status'=>'error', 'message'=>'Secret key is incorrect'));
        	die();
		}


		if(empty($errors)){
			$DeleteUser = $odb -> prepare("DELETE FROM `users` WHERE `id` = :id AND `secretkey` = :secret");
			$DeleteUser -> execute(array(':id' => $_SESSION['id'], ':secret' => $secretkey));

			session_start();
			unset($_SESSION['loggedin']);
			unset($_SESSION['username']);
			unset($_SESSION['id']);
			unset($_SESSION['email']);
			unset($_SESSION['rank']);

			session_destroy();

			echo json_encode(array('status'=>'success', 'message'=>'Your account successfully deleted'));
		}

	}else{
		header('HTTP/1.0 400 Bad Request');
			exit();
	}





?>