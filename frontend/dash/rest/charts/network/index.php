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

		$AttacksDB = $odb -> query("SELECT COUNT(*) AS count FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0");
		$rowAttacks = $AttacksDB -> fetch(PDO::FETCH_ASSOC);

        $serversSlots = $odb -> query("SELECT SUM(slots) as `allSlots` FROM `servers` WHERE `status` = 'online'");
		$rowSlots = $serversSlots -> fetch(PDO::FETCH_ASSOC);

        $data = array(
            "usage" => round(($rowAttacks['count'] / $rowSlots['allSlots']) * 100, 2),
        );


        echo json_encode($data);

	}



?>