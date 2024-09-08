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
		
		

		$SQLCheck = $odb -> prepare("SELECT * FROM `attacklogs` LIMIT 5");
		$SQLCheck -> execute();

		$count = $SQLCheck -> rowCount();

		if($count == 0){
			$errors[] = "Logs are already cleared.";
			echo json_encode(array('status'=>'error', 'message'=>'Logs are already cleared!'));
          	die();
		}

		


    	if(empty($errors)){
    		
    		$SQLDelete = $odb -> prepare("DELETE FROM `attacklogs`");
			$SQLDelete -> execute();

    		echo json_encode(array('status'=>'success', 'message'=>'Logs successfully cleared.'));
          	die();

    	}











	}

?>