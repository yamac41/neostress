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
        $reply = nl2br(htmlspecialchars($_POST['reply']));

		if(!filter_var($ticketid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid ID value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Ticket ID value!'));
          	die();
    	}

        if(empty($reply)){
            $errors[] = "Please fill all required fields.";
            echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
            die();
        }

        $Check = $odb -> prepare("SELECT COUNT(*) FROM `tickets` WHERE `id` = :id");
        $Check -> execute(array(':id' => $ticketid));
        $count = $Check -> fetchColumn(0);

        if($count == 0){
            $errors[] = "This is not your ticket.";
            echo json_encode(array('status'=>'error', 'message'=>'This ticket is not available!'));
            die();
        }

        $CheckStatus = $odb -> prepare("SELECT `status` FROM `tickets` WHERE `id` = :id");
        $CheckStatus -> execute(array(':id' => $ticketid));
        $status = $CheckStatus -> fetchColumn(0);

        if($status == 'closed'){
            $errors[] = "This ticket is closed.";
            echo json_encode(array('status'=>'error', 'message'=>'You cannot reply to closed ticket.'));
            die();
        }


        if(empty($errors)){
    		$Insert = $odb -> prepare("INSERT INTO `tickets_replies`(`id`, `ticket_id`, `msg`, `sender`, `created`) VALUES (NULL, :ticketid, :msg, 'admin', NOW())");
            $Insert -> execute(array(':ticketid' => $ticketid, ':msg' => $reply));
            
            $SQLUpdate = $odb -> prepare("UPDATE `tickets` SET `status` = :status WHERE `id` = :id");
            $SQLUpdate -> execute(array(':status' => 'answered', ':id' => $ticketid));



            echo json_encode(array('status'=>'success', 'message'=>'Ticket successfully replied.'));
            die();
            


    	}








	}




?>