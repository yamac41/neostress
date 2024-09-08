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


   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `servers` ORDER BY `id` ASC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectServers = $odb -> prepare("SELECT * FROM `servers` ORDER BY `id` ASC LIMIT :limit,:offset");
		$SelectServers -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectServers -> rowCount();


		$data = array();

		while($row = $SelectServers -> fetch(PDO::FETCH_ASSOC)) {
		  	
		  	$status = $row['status'];
	    	$premium = $row['premium'];

	    	if($premium == 'no'){
				$premiumtext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">Basic</span>';
			}else if($premium == 'yes'){
				$premiumtext = '<span class="vnm-dark-badge px-2 py-1 rounded-lg">Premium</span>';
			}

			if($status == 'online'){
				$statustext = '<i class="fa-solid fa-check"></i> Online';
			}else if($status == 'offline'){
				$statustext = '<i class="fa-solid fa-xmark"></i> Offline';
			}else if($status == 'maintaince'){
				$statustext = '<i class="fa-solid fa-triangle-exclamation"></i> Maintenance';
			}


	    	$data[] = array(
	    	 "id"=>$row['id'],
	         "name"=>$row['name'],
	         "apiurl"=>$row['apiurl'],
	         "slots"=>$row['slots'],
	         "type"=>'<span class="vnm-dark-badge px-2 py-1 rounded-lg">'.ucwords($row['type']).'</span>',
	         "network"=>$premiumtext,
	         "methods"=>$row['methods'],
	         "status"=>$statustext,
	         "actions"=>'<button type="button" id="delete-server" onclick="DeleteServer('.$row['id'].')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button> <button type="button" id="edit-server" onclick="EditServer('.$row['id'].')" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>'
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