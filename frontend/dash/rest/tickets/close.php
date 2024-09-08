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

		$ticketid = intval($_POST['ticketid']);
        
        if($ticketid < 0){
            $errors[] = "Invalid ID value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Ticket ID value!'));
          	die();
        }

		if(!filter_var($ticketid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid ID value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Ticket ID value!'));
          	die();
    	}

        if(!(is_numeric($_POST['ticketid']))){
            header('HTTP/1.0 400 Bad Request');
            exit();
        }

        $Check = $odb -> prepare("SELECT COUNT(*) FROM `tickets` WHERE `id` = :id AND `user` = :user");
        $Check -> execute(array(':id' => $ticketid, ':user' => $_SESSION['username']));
        $count = $Check -> fetchColumn(0);

        if($count == 0){
            $errors[] = "This is not your ticket.";
            echo json_encode(array('status'=>'error', 'message'=>'This is not your ticket!'));
            die();
        }

        $CheckStatus = $odb -> prepare("SELECT `status` FROM `tickets` WHERE `id` = :id");
        $CheckStatus -> execute(array(':id' => $ticketid));
        $status = $CheckStatus -> fetchColumn(0);

        if($status == 'closed'){
            $errors[] = "This ticket is already closed.";
            echo json_encode(array('status'=>'error', 'message'=>'This ticket is already closed.'));
            die();
        }


        if(empty($errors)){
    		
            
            $SQLUpdate = $odb -> prepare("UPDATE `tickets` SET `status` = :status WHERE `id` = :id AND `user` = :user");
            $SQLUpdate -> execute(array(':status' => 'closed', ':id' => $ticketid, ':user' => $_SESSION['username']));



            echo json_encode(array('status'=>'success', 'message'=>'Ticket successfully closed.'));
            die();
            


    	}








	}




?>