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
        
        if(!(is_numeric($_POST['ticketid']))){
            header('HTTP/1.0 400 Bad Request');
            exit();
        }

		if(!filter_var($ticketid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid ID value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid Ticket ID value!'));
          	die();
    	}

        $Check = $odb -> prepare("SELECT COUNT(*) FROM `tickets` WHERE `id` = :id AND `user` = :user");
        $Check -> execute(array(':id' => $ticketid, ':user' => $_SESSION['username']));
        $count = $Check -> fetchColumn(0);

        if($count == 0){
            $errors[] = "This is not your ticket.";
            echo json_encode(array('status'=>'error', 'message'=>'This is not your ticket!'));
            die();
        }

    	

    	if(empty($errors)){
    		$Ticket = $odb -> prepare("SELECT * FROM `tickets` WHERE `id` = :ticketid AND `user` = :user");
            $Ticket -> execute(array(':ticketid' => $ticketid, ':user' => $_SESSION['username']));
            $tickets = $Ticket -> fetch(PDO::FETCH_ASSOC);

            $Replies = $odb -> prepare("SELECT * FROM `tickets_replies` WHERE `ticket_id` = :ticketid ORDER BY created ASC");
            $Replies -> execute(array(':ticketid' => $ticketid));
            $replies = $Replies -> fetchAll(PDO::FETCH_ASSOC);



            $createddb = strtotime($tickets['created']);
            $created = date('m/d/Y H:i', $createddb);
    		
            echo '
                <div class="card ticketreply-card ticketreply-card-1">
                    <div class="card-header">
                        Posted by <b>Customer</b> on '.$created.'
                    </div>
                    <div class="card-body">
                        '.$tickets['msg'].'
                    </div>
                </div>
            ';
            
            foreach($replies as $reply){
                if($reply['sender'] == 'admin'){
                    $sender = 'Admin';
                }else if($reply['sender'] == 'support'){
                    $sender = 'Support';
                }else if($reply['sender'] == 'customer'){
                    $sender = 'Customer';
                }

                $datedb = strtotime($reply['created']);
                $date = date('m/d/Y H:i', $datedb);
                echo '
                    <div class="card ticketreply-card">
                        <div class="card-header">
                            Posted by <b>'.$sender.'</b> on '.$date.'
                        </div>
                        <div class="card-body">
                            '.$reply['msg'].'
                        </div>
                    </div>
                ';
            }


    	}








	}




?>