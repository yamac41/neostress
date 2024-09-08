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

   		$AllRecords = $odb -> query("SELECT COUNT(*) FROM `payments` ORDER BY `id` DESC");
   		$totalRecords = $AllRecords -> fetchColumn(0);
		
		$SelectPayments = $odb -> prepare("SELECT * FROM `payments` ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");
		$SelectPayments -> execute(array(':limit' => $row, ':offset' => $rowperpage));

		
		$totalRecordwithFilter = $SelectPayments -> rowCount();


		$data = array();

		while($row = $SelectPayments -> fetch(PDO::FETCH_ASSOC)) {
		  	
	    	if($row['status'] == 'PENDING'){
				$status = 'Pending';
			}else if($row['status'] == 'VOIDED'){
				$status = 'Expired';
			}else if($row['status'] == 'PARTIAL'){
				$status = 'Partially Paid';
			}else if($row['status'] == 'PROCESSING'){
				$status = 'Processing';
			}else if($row['status'] == 'WAITING_FOR_CONFIRMATIONS'){
				$status = 'Confirming';
			}else if($row['status'] == 'COMPLETED'){
				$status = 'Completed';
			}


	    	$data[] = array(
	    	 "id"=>$row['id'],
	         "user"=>$row['user'],
	         "amount"=>''.$row['amount'].'$',
	         "date"=>date("Y-m-d H:i", $row['created_at']),
	         "status"=>$status,
	         "action"=>'<button type="button" id="payment-details" onclick="ViewPayment('.$row['id'].')" class="btn btn-warning btn-sm"><i class="fa-solid fa-up-right-from-square"></i></button>'
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