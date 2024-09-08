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


		$methodid = intval($_POST['methodid']);

		if(empty($methodid)){
			$errors[] = "methodID is empty";
			echo json_encode(array('status'=>'error', 'message'=>'MethodID is empty!'));
			die();
		}
		
		if(empty($errors)) {
			$DeleteMethod = $odb -> prepare("DELETE FROM `methods` WHERE `id` = :methodid");
			$DeleteMethod -> execute(array(':methodid' => $methodid));

			echo json_encode(array('status'=>'success', 'message'=>'Method successfully deleted.'));
		}

	}


?>