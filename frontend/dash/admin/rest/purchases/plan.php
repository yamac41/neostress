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

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `plan_purchases` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectPayments = $odb -> prepare("SELECT * FROM `plan_purchases` ORDER BY id DESC LIMIT :limit,:offset");
		$SelectPayments -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectPayments -> rowCount();


		$data = array();

		while($row = $SelectPayments -> fetch(PDO::FETCH_ASSOC)) {
		  	$date = strtotime($row['date']);
	    	$data[] = array(
	    	 "user"=>$row['user'],
	    	 "plan"=>$row['plan'],
	         "amount"=>''.$row['amount'].'$',
	         "date"=>date("Y-m-d H:i", $date),

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