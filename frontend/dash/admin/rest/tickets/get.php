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
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length'];
		$columnIndex = $_POST['order'][0]['column'];
   		$columnName = $_POST['columns'][$columnIndex]['name']; 
   		$columnSortOrder = $_POST['order'][0]['dir'];

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `tickets` WHERE `status` = 'open' OR `status` = 'customer-reply' ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);

		$SelectTickets = $odb -> prepare("SELECT * FROM `tickets` WHERE `status` = 'open' OR `status` = 'customer-reply' ORDER BY `id` DESC LIMIT :limit,:offset");
		$SelectTickets -> execute(array(':limit' => $row, ':offset' => $rowperpage));
		$totalRecordwithFilter = $SelectTickets -> rowCount();

		$data = array();

		while($ticket = $SelectTickets -> fetch(PDO::FETCH_ASSOC)) {
		  	
	    	


	    	$data[] = array(
	         "id"=>$ticket['id'],
	         "title"=>$ticket['subject'],
	         "priority"=>'<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.ucfirst($ticket['priority']).'</span>',
	         "user"=>$ticket['user'],
	         "action"=>'<button type="button" id="ticket-details" onclick="ViewTicket('.$ticket['id'].')" class="btn btn-warning btn-sm"><i class="fa-solid fa-up-right-from-square"></i></button>'
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
		header('HTTP/1.0 404 Not Found');
		exit();
	}
	













?>