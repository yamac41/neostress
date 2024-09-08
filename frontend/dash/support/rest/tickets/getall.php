<?php

	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../../backend/configuration/database.php';
	require '../../../../../backend/configuration/funcsinit.php';


	if (!$user->UserLoggedIn() || !$user->notBanned($odb) || !$user->isUserSupport($odb)) {
        header('HTTP/1.0 404 Not Found');
        exit();
    }

	$draw = intval($_POST['draw']);
	$row = intval($_POST['start']);
	$rowperpage = intval($_POST['length']);

	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		$GetTickets = $odb -> prepare("SELECT * FROM `tickets` ORDER BY `id` DESC LIMIT :limit,:offset");
		$GetTickets -> execute(array(':limit' => $row, ':offset' => $rowperpage));
	
		$totalRecords = $GetTickets -> rowCount();
		$totalRecordwithFilter = $GetTickets -> rowCount();

		$data = array();


		while($ticket = $GetTickets -> fetch(PDO::FETCH_ASSOC)) {
			
			$dbcreated = strtotime($ticket['created']);
    		$created = date('m/d/Y H:i', $dbcreated);
			
			$data[] = array(
				"user"=>$ticket['user'],
				"subject"=>'#'.$ticket['id'].' '.$ticket['subject'].'',
		        "status"=>'<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.ucfirst($ticket['status']).'</span>',
		        "priority"=>'<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.ucfirst($ticket['priority']).'</span>',
		        "created"=>$created,
		        "action"=>'<button type="button" id="ticket-details" style="color:#fff;font-size:.6rem;" onclick="ViewTicket('.$ticket['id'].')" class="btn btn-warning btn-sm"><i class="fa-solid fa-up-right-from-square"></i></button>'
		        
		    );
		}
	
		$response = array(
	      "draw" => intval($draw),
	      "iTotalRecords" => $totalRecords,
	      "iTotalDisplayRecords" => $totalRecordwithFilter,
	      "aaData" => $data
		);

		echo json_encode($response);

	}



?>