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

		if($draw < 0 || $row < 0 || $rowperpage < 0){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['draw'])) || !(is_numeric($_POST['start'])) || !(is_numeric($_POST['length']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		$SelectBillings = $odb -> prepare("SELECT * FROM `payments` WHERE `user` = :user ORDER BY `id` DESC LIMIT :limit,:offset");
		$SelectBillings -> execute(array(':user' => $_SESSION['username'], ':limit' => $row, ':offset' => $rowperpage));
	
		$totalRecords = $SelectBillings -> rowCount();
		$totalRecordwithFilter = $SelectBillings -> rowCount();

		$data = array();


		while($bill = $SelectBillings -> fetch(PDO::FETCH_ASSOC)) {
			if($bill['status'] == 'PENDING'){
				$status = '<i class="fa-solid fa-clock-rotate-left" style="color:#B4C6FC;"></i> Pending';
			}else if($bill['status'] == 'VOIDED'){
				$status = '<i class="fa-solid fa-xmark"></i> Expired';
			}else if($bill['status'] == 'PARTIAL'){
				$status = '<i class="fa-solid fa-clock-rotate-left" style="color:#B4C6FC;"></i> Partially Paid';
			}else if($bill['status'] == 'PROCESSING'){
				$status = '<i class="fa-solid fa-spinner" style="color:#B4C6FC;"></i> Processing';
			}else if($bill['status'] == 'WAITING_FOR_CONFIRMATIONS'){
				$status = '<i class="fa-solid fa-list-check" style="color:#B4C6FC;"></i> Confirming';
			}else if($bill['status'] == 'COMPLETED'){
				$status = '<i class="fa-solid fa-check"></i> Completed';
			}

			if($bill['status'] == 'PENDING'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye bill-info-ic"></i></button>';
			}else if($bill['status'] == 'VOIDED'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye-slash bill-info-ic"></i></button>';
			}else if($bill['status'] == 'COMPLETED'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye-slash bill-info-ic"></i></button>';
			}else if($bill['status'] == 'PARTIAL'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye bill-info-ic"></i></button>';
			}else if($bill['status'] == 'PROCESSING'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye bill-info-ic"></i></button>';
			}else if($bill['status'] == 'WAITING_FOR_CONFIRMATIONS'){
				$action = '<button type="button" id="billinfobtn" onclick="OpenPayment('.$bill['id'].')"  class="btn btn-warning btn-sm"><i class="fa-solid fa-eye bill-info-ic"></i></button>';
			}
			
			$created_at = date ('Y-m-d H:i:s', $bill['created_at']);

			$data[] = array(
				"id"=>$bill['id'],
		        "type"=>$bill['type'],
		        "amount"=>''.$bill['amount'].'$',
		        "status"=>$status,
		       	"date"=>$created_at,
		        "action"=>$action
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