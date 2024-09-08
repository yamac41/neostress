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

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `plans` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectPlans = $odb -> prepare("SELECT * FROM `plans` ORDER BY `id` ASC LIMIT :limit,:offset");
		$SelectPlans -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectPlans -> rowCount();


		$data = array();

		while($row = $SelectPlans -> fetch(PDO::FETCH_ASSOC)) {
		  	
	    	if($row['premium'] == '0'){
				$premium = 'No';
			}else if($row['premium'] == '1'){
				$premium = 'Yes';
			}

			if($row['apiaccess'] == '0'){
				$api = 'No';
			}else if($row['apiaccess'] == '1'){
				$api = 'Yes';
			}
			$users = $odb->query("SELECT COUNT(*) FROM `users` WHERE `plan` = ".$row['id']."")->fetchColumn(0);
			


	    	$data[] = array(
	    	 "name"=>$row['name'],
	         "price"=>''.$row['price'].'$',
	         "time"=>$row['time'],
	         "concs"=>$row['concs'],
	         "length"=>''.$row['length'].' '.$row['lengthtype'].'',
	         "premium"=>$premium,
	         "api"=>$api,
	         "private"=>ucfirst($row['private']),
	         "users"=>$users,
	         "action"=>'<button type="button" id="delete-plan" onclick="DeletePlan('.$row['id'].')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>'
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