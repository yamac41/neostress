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

		$subject = htmlentities($user -> CheckInput($_POST['subject']));
        $priority = htmlentities($user -> CheckInput($_POST['priority']));
        $msgg = htmlspecialchars($user -> CheckInput($_POST['msg']));
        $msg = nl2br($msgg);

        if($user -> SecureText($subject) || $user -> SecureText($priority) || $user -> SecureText($msgg)){
            header('HTTP/1.0 400 Bad Request');
            exit();
        }

        if(empty($subject) || empty($priority) || empty($msg)){
            $errors[] = "Please fill all required fields.";
            echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
            die();
        }

		if(!filter_var($subject, FILTER_SANITIZE_STRING)){
    		$errors[] = "Invalid subject form.";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid subject format.'));
          	die();
    	}
        $priorities = array('low', 'normal', 'high');
        if(!in_array($priority, $priorities)){
            $errors[] = "Invalid priority!";
            echo json_encode(array('status'=>'error', 'message'=>'Invalid priority!'));
            die();
        }

        $CheckStatus = $odb -> prepare("SELECT COUNT(*) FROM `tickets` WHERE `user` = :user AND `status` IN ('open', 'customer-reply', 'answered')");
        $CheckStatus -> execute(array(':user' => $_SESSION['username']));
        $ticketsnumber = $CheckStatus -> fetchColumn(0);

        if($ticketsnumber >= 2){
            $errors[] = "You already have opened 2 tickets.";
            echo json_encode(array('status'=>'error', 'message'=>'You already have opened 2 tickets.'));
            die();
        }

        if(empty($errors)){
    		
            $InsertTicket = $odb -> prepare("INSERT INTO `tickets`(`id`, `subject`, `msg`, `priority`, `user`, `created`, `status`) VALUES (NULL, :subject, :msg, :priority, :user, NOW(), 'open')");
            $InsertTicket -> execute(array(':subject' => $subject, ':msg' => $msg, ':priority' => $priority, ':user' => $_SESSION['username']));

            echo json_encode(array('status'=>'success', 'message'=>'Ticket successfully opened, please wait for admin/support response.'));
            die();
            


    	}








	}




?>