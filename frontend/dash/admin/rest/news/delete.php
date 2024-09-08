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
		
		$newsid = intval($_POST['newsid']);

		if(empty($newsid)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'News ID is empty.'));
          	die();
		}

		if(!filter_var($newsid, FILTER_VALIDATE_INT)){
			$errors[] = "Invalid Plan ID format.";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid News ID format.'));
          	die();
		}

    	if(empty($errors)){
    		$SQLDelete = $odb -> prepare("DELETE FROM `news` WHERE `id` = :id");
    		$SQLDelete -> execute(array(':id' => $newsid));

    		echo json_encode(array('status'=>'success', 'message'=>'News '.$newsid.' successfully deleted.'));
          	die();

    	}
	}

?>