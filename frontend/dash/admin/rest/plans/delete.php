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
		
		$plan = intval($_POST['plan']);

		if(empty($plan)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Plan ID is empty.'));
          	die();
		}

		if(!filter_var($plan, FILTER_VALIDATE_INT)){
			$errors[] = "Invalid Plan ID format.";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Plan ID format.'));
          	die();
		}

    	if(empty($errors)){
    		$SQLDelete = $odb -> prepare("DELETE FROM `plans` WHERE `id` = :id");
    		$SQLDelete -> execute(array(':id' => $plan));

    		echo json_encode(array('status'=>'success', 'message'=>'Plan '.$plan.' successfully deleted.'));
          	die();

    	}











	}

?>