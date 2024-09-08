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
   		$searchValue = htmlentities($_POST['search']['value']);

   		$searchQuery = " ";
		if($searchValue != ''){
		   $searchQuery = " AND (username like '%".$searchValue."%' or email like '%".$searchValue."%') ";
		}

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `users` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
   		$SelectFilter = $odb -> query("SELECT COUNT(*) FROM `users` WHERE 1 ".$searchQuery);
   		$totalRecordwithFilter = $SelectFilter -> fetchColumn(0);


		$SelectUsers = $odb -> prepare("SELECT * FROM `users` WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
		$SelectUsers -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		


		$data = array();

		while($row = $SelectUsers -> fetch(PDO::FETCH_ASSOC)) {
		  	
	    	$lastdate = strtotime($row['lastlogin']);
	    	$plan = $odb -> query("SELECT `name` FROM `plans` WHERE `id` = '{$row['plan']}' LIMIT 1")->fetchColumn(0);

	    	$data[] = array(
	    	 "id"=>$row['id'],
	         "username"=>$row['username'],
	         "email"=>$row['email'],
	         "plan"=>$plan,
	         "lastlogin"=>date("Y-m-d H:i", $lastdate),
	         "rank"=>$row['rank'],
	         "action"=>'<button type="button" id="edit-user" onclick="EditUser('.$row['id'].')" class="btn btn-warning btn-sm" title="Edit User"><i class="fa-solid fa-pen"></i></button> <button type="button" id="edit-user" onclick="BanUser('.$row['id'].')" class="btn btn-danger btn-sm" title="Ban User"><i class="fa-solid fa-ban"></i></button>'
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