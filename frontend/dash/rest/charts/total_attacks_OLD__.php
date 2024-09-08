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
		
			
        /*$AttacksDB = $odb -> query("SELECT day(`datetime`) AS day, month(`datetime`) AS month, COUNT(*) AS count FROM `attacklogs` GROUP BY day(`datetime`), month(`datetime`) ORDER BY day(`datetime`), month(`datetime`)");
       

        $data = array();

        while($row = $AttacksDB -> fetch(PDO::FETCH_ASSOC)){
            
            $day = $row['day'];
            $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));
            $data[] = array(
                'date' => ''.$day.' '.$monthName.'',
                'attacks' => $row['count']
            );
        
        }
        
        echo json_encode($data);*/

        $data = array();
		$Attacks_Cache = getCache($memoryCache, "__today_atk_V3");

		if($Attacks_Cache) {
			$data = json_decode($Attacks_Cache);
		} else {
			$AttacksDB = $odb -> query("SELECT day(`datetime`) AS day, month(`datetime`) AS month, COUNT(*) AS count FROM `attacklogs` GROUP BY day(`datetime`), month(`datetime`) ORDER BY day(`datetime`), month(`datetime`)");

			while($row = $AttacksDB -> fetch(PDO::FETCH_ASSOC)){
                $day = $row['day'];
                $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));
                $data[] = array(
                    'date' => ''.$day.' '.$monthName.'',
                    'attacks' => $row['count']
                ); 
			}
			
			setCache($memoryCache, "__today_atk_V3", json_encode($data));
		}
			

        echo json_encode($data);
	}



?>