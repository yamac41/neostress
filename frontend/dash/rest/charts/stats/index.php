<?php
    header('Content-type: application/json');

	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../../backend/configuration/database.php';
	require '../../../../../backend/configuration/funcsinit.php';


	if (!$user->UserLoggedIn() || !$user->notBanned($odb)) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$data = array();

		$Attacks_Cache = getCache($memoryCache, "__stats_all");

		if($Attacks_Cache) {
			$data = json_decode($Attacks_Cache);
		} else {
			$beginOfDay = strtotime("today", time());
			$AttacksDB = $odb -> query("SELECT COUNT(*) AS count FROM `attacklogs` WHERE `date` > " . $beginOfDay);
			$rowAttacks = $AttacksDB -> fetch(PDO::FETCH_ASSOC);

			$beginOfDay = strtotime("today", time());
			$AttacksDB_running = $odb -> query("SELECT COUNT(*) AS count FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP()");
			$rowAttacks_running = $AttacksDB_running-> fetch(PDO::FETCH_ASSOC);

            $UsersDB = $odb -> query("SELECT COUNT(*) as count FROM users");
			$rowUsers = $UsersDB -> fetch(PDO::FETCH_ASSOC);

            //$TotalAttacksDB = $odb -> query("SELECT COUNT(*) as count FROM attacklogs");
			//$rowTotalAttacks = $TotalAttacksDB -> fetch(PDO::FETCH_ASSOC);

			$data = array(
                'todayattacks' => $rowAttacks['count'],
				'runningattacks' => $rowAttacks_running['count'],
                'users' => $rowUsers['count'],
			);
			
			setCache($memoryCache, "__stats_all", json_encode($data));
		}
			

        echo json_encode($data);

	}



?>