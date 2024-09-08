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
		
		$paymentid = intval($_POST['paymentid']);

		if(!filter_var($paymentid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid paymentID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid paymentID!'));
          	die();
    	}

		

		
		if(empty($errors)){
    		
    		$SQLCheck = $odb -> prepare("SELECT * FROM `payments` WHERE `id` = :id");
			$SQLCheck -> execute(array(':id' => $paymentid));
			$row = $SQLCheck -> fetch(PDO::FETCH_ASSOC);
    		
			echo json_encode(array(
				'status'=>'success', 
				'uniqid'=>$row['uniqid'],
				'gateway'=>$row['gateway'],
				'confirmations'=>$row['confirmations'],
				'hash'=>$row['hash'],
				'created'=>date('m-d-Y H:i:s', $row['created_at']),
				'status'=>$row['status']
				

			));
			die();

    	}











	}

?>