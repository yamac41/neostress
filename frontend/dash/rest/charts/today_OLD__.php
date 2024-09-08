<?php
    header('Content-type: application/json');

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
		$data = array();

		$Attacks_Cache = getCache($memoryCache, "__today_atk_V2");

		if($Attacks_Cache) {
			$data = json_decode($Attacks_Cache);
		} else {
			$beginOfDay = strtotime("today", time());
			$AttacksDB = $odb -> query("SELECT COUNT(*) AS count FROM `attacklogs` WHERE `date` > " . $beginOfDay);
			$row = $AttacksDB -> fetch(PDO::FETCH_ASSOC);

			$data = array(
				'attacks' => $row['count']
			);
			
			setCache($memoryCache, "__today_atk_V2", json_encode($data));
		}
			

        echo json_encode($data);

	}



?>