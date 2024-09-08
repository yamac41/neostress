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


   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `methods` ORDER BY `id` ASC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectServers = $odb -> prepare("SELECT * FROM `methods` ORDER BY `id` ASC LIMIT :limit,:offset");
		$SelectServers -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectServers -> rowCount();


		$data = array();

		while($row = $SelectServers -> fetch(PDO::FETCH_ASSOC)) {
		  	
		  	$timelimit = $row['timelimit'];
	    	$premium = $row['premium'];

	    	if($premium == '0'){
				$premiumtext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">No</span>';
			}else if($premium == '1'){
				$premiumtext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">Yes</span>';
			}

			if($timelimit == '0'){
				$limittext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">No</span>';
			}else if($timelimit > 0){
				$limittext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">'.$timelimit.'s</span>';
			}


	    	$data[] = array(
	    	 "apiname"=>$row['apiname'],
	         "publicname"=>$row['publicname'],
	         "type"=>'<span class="vnm-dark-badge px-2 py-1 rounded-lg">'.$row['type'].'</span>',
	         "premium"=>$premiumtext,
	         "timelimit"=>$limittext,
	         "action"=>'<button type="button" id="delete-method" onclick="DeleteMethod('.$row['id'].')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button> '
	    	);
	   	
	   	}
	   	$response = array(
	      "draw" => intval($draw),
	      "iTotalRecords" => $totalRecords,
	      "iTotalDisplayRecords" => $totalRecords,
	      "aaData" => $data
	    );

	    echo json_encode($response);
	}
	













?>