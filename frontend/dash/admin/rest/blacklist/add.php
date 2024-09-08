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
		
		$target = $_POST['target'];
		$type = $_POST['type'];

		if(empty($target) || empty($type)){
			$errors[] = "Fill all required fields.";
			echo json_encode(array('status'=>'error', 'message'=>'Fill all required fields.'));
          	die();
		}

		$SQLCheck = $odb -> prepare("SELECT * FROM `blacklist` WHERE `target` = :target");
		$SQLCheck -> execute(array(':target' => $target));

		$count = $SQLCheck -> rowCount();

		if($count > 0){
			$errors[] = "This target is already blacklisted!";
			echo json_encode(array('status'=>'error', 'message'=>'This target is already blacklisted!'));
          	die();
		}

		


    	if(empty($errors)){
    		
    		$SQLInsert = $odb -> prepare("INSERT INTO `blacklist`(`id`, `target`, `type`, `user`) VALUES (NULL, :target, :type, :user)");
			$SQLInsert -> execute(array(':target' => $target, ':type' => $type, ':user' => $_SESSION['username']));

    		echo json_encode(array('status'=>'success', 'message'=>'Target successfully blacklisted.'));
          	die();

    	}











	}

?>