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

	$SelectAttacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :user ORDER BY `id` DESC LIMIT :limit,:offset");
	$SelectAttacks -> execute(array(':user' => $_SESSION['username'], ':limit' => $row, ':offset' => $rowperpage));
	
	$totalRecords = $SelectAttacks -> rowCount();
	$totalRecordwithFilter = $SelectAttacks -> rowCount();

	
	
	$data = array();

	while($attack = $SelectAttacks -> fetch(PDO::FETCH_ASSOC)) {
	  $attackmethod = $odb->query("SELECT `apiname` FROM `methods` WHERE `apiname` = '{$attack['method']}' LIMIT 1")->fetchColumn(0);
	  $attackdiff = $attack['date'] + $attack['time'] - time();
	  $countdown = '<span id="expires-'.$attack['id'].'"></span>
					<script>
                        CountAttackTime('.$attack['id'].', '.$attackdiff.');
                    </script>';
      $data[] = array(
         "id"=>$attack['id'],
         "target"=>''.$attack['target'].':'.$attack['port'].'',
         "method"=>$attackmethod,
         "expire"=>$countdown,
         "action"=>'<button type="button" id="stopbtn" onclick="StopAttack('.$attack['id'].')" class="btn btn-r1">STOP<span id="stop_def"><i class="fa-solid fa-power-off ml-1"></i></span><span id="stop_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></span></button>'
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
