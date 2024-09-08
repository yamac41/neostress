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

		$draw = intval($_POST['draw']);
		$row = intval($_POST['start']);
		$rowperpage = intval($_POST['length']);

		if($draw < 0 || $row < 0 || $rowperpage < 0){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['draw'])) || !(is_numeric($_POST['start'])) || !(is_numeric($_POST['length']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		$GetTickets = $odb -> prepare("SELECT * FROM `tickets` WHERE `user` = :user ORDER BY `id` DESC LIMIT :limit,:offset");
		$GetTickets -> execute(array(':user' => $_SESSION['username'], ':limit' => $row, ':offset' => $rowperpage));
	
		$totalRecords = $GetTickets -> rowCount();
		$totalRecordwithFilter = $GetTickets -> rowCount();

		$data = array();


		while($ticket = $GetTickets -> fetch(PDO::FETCH_ASSOC)) {
			
			$dbcreated = strtotime($ticket['created']);
    		$created = date('m/d/Y H:i', $dbcreated);
			
			$data[] = array(
				"subject"=>'<a onclick="ViewTicket('.$ticket['id'].')">#'.$ticket['id'].' '.$ticket['subject'].'</a>',
		        "status"=>'<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.ucfirst($ticket['status']).'</span>',
		        "created"=>$created,
		        
		    );
		}
	
		$response = array(
	      "draw" => intval($draw),
	      "iTotalRecords" => $totalRecords,
	      "iTotalDisplayRecords" => $totalRecordwithFilter,
	      "aaData" => $data
		);

		echo json_encode($response);

	}else{
		header('HTTP/1.0 400 Bad Request');
		exit();
	}



?>