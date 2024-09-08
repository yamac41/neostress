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

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `addons` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectPayments = $odb -> prepare("SELECT * FROM `addons` ORDER BY id DESC LIMIT :limit,:offset");
		$SelectPayments -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectPayments -> rowCount();


		$data = array();

		while($row = $SelectPayments -> fetch(PDO::FETCH_ASSOC)) {
		  	
			if($row['concs'] == '0'){
				$concs = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">No</span>';
			}else if($row['concs'] > '0'){
				$concs = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.$row['concs'].'</span>';
			}
			if($row['attacktime'] == '0'){
				$time = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">No</span>';
			}else if($row['attacktime'] > '0'){
				$time = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">'.$row['attacktime'].'s</span>';
			}
			if($row['blacklist'] == '0'){
				$blacklist = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">No</span>';
			}else if($row['blacklist'] > '0'){
				$blacklist = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">Yes</span>';
			}
			if($row['apiaccess'] == '0'){
				$api = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">No</span>';
			}else if($row['apiaccess'] > '0'){
				$api = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">Yes</span>';
			}

	    	$data[] = array(
	    	 "user"=>$row['user'],
	    	 "concs"=>$concs,
	         "time"=>$time,
	         "blacklist"=>$blacklist,
	         "apiaccess"=>$api,
	         "date"=>date("Y-m-d H:i", strtotime($row['created_at'])),

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