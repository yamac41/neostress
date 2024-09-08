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
		
		$id = intval($_POST['id']);

		if(empty($id)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Plan ID is empty.'));
          	die();
		}

		if(!filter_var($id, FILTER_VALIDATE_INT)){
			$errors[] = "Invalid Plan ID format.";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Plan ID format.'));
          	die();
		}

    	if(empty($errors)){
    		$SQLDelete = $odb -> prepare("DELETE FROM `blacklist` WHERE `id` = :id");
    		$SQLDelete -> execute(array(':id' => $id));

    		echo json_encode(array('status'=>'success', 'message'=>'Target '.$id.' successfully removed from blacklist.'));
          	die();

    	}











	}

?>