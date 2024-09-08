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

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `news` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectNews = $odb -> prepare("SELECT * FROM `news` ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
		$SelectNews -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectNews -> rowCount();


		$data = array();

		while($row = $SelectNews -> fetch(PDO::FETCH_ASSOC)) {
		  	
	    	$date = strtotime($row['date']);


	    	$data[] = array(
	    	 "title"=>$row['title'],
	         "icon"=>ucwords($row['icon']),
	         "created_at"=>date("Y-m-d H:i", $date),
	         "action"=>'<button type="button" id="delete-plan" onclick="DeleteNews('.$row['id'].')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></button>'
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