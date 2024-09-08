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
		
		if(!(is_numeric($_POST['draw'])) || !(is_numeric($_POST['start'])) || !(is_numeric($_POST['length']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		$SelectAttacks = $odb -> prepare("SELECT * FROM `sheduledattacks` WHERE `datetime` > UNIX_TIMESTAMP() AND `started` = 0 AND `user` = :user ORDER BY `id` DESC LIMIT :limit,:offset");
		$SelectAttacks -> execute(array(':user' => $_SESSION['username'], ':limit' => $row, ':offset' => $rowperpage));
		
		$totalRecords = $SelectAttacks -> rowCount();
		$totalRecordwithFilter = $SelectAttacks -> rowCount();

		
		
		$data = array();

		while($attack = $SelectAttacks -> fetch(PDO::FETCH_ASSOC)) {
		  
		  if($attack['method'] == 'HTTPGET' || $attack['method'] == 'HTTPPOST' || $attack['method'] == 'HTTPHEAD'){
		  		$attackmethod = 'HTTP-ARIA';
		  }else{
		  		$attackmethod = $odb->query("SELECT `publicname` FROM `methods` WHERE `apiname` = '{$attack['method']}' LIMIT 1")->fetchColumn(0);
		  }
		 
	      $data[] = array(
	         "id"=>$attack['id'],
	         "target"=>''.$attack['target'].':'.$attack['port'].'',
	         "method"=>$attackmethod,
	         "created"=>date('m-d-Y H:i:s', strtotime($attack['created'])),
	         "scheduled"=>date('m-d-Y H:i:s', $attack['datetime']),
	         "action"=>'<button type="button" id="deleteschbtn" onclick="DeleteSchedule('.$attack['id'].')" class="btn btn-danger btn-sm"><span id="deletesch_def"><i class="fa-solid fa-trash-can"></i></span><span id="deletesch_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></span></button>'
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
