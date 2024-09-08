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
		
		$ticketid = intval($_POST['ticketid']);

		if(empty($ticketid)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Ticket ID is empty.'));
          	die();
		}

		if(!filter_var($ticketid, FILTER_VALIDATE_INT)){
			$errors[] = "Invalid Plan ID format.";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Ticket ID format.'));
          	die();
		}

    	if(empty($errors)){
    		$SQLDelete = $odb -> prepare("DELETE FROM `tickets` WHERE `id` = :id");
    		$SQLDelete -> execute(array(':id' => $ticketid));

    		$SQLDeleteR = $odb -> prepare("DELETE FROM `tickets_replies` WHERE `ticket_id` = :id");
    		$SQLDeleteR -> execute(array(':id' => $ticketid));
    		
    		echo json_encode(array('status'=>'success', 'message'=>'Ticket '.$ticketid.' successfully deleted.'));
          	die();

    	}











	}

?>