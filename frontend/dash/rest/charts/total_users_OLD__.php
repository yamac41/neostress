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
		
			
        /*$UsersDB = $odb -> query("SELECT day(`created`) AS day, month(`created`) AS month, COUNT(*) AS count FROM `users` GROUP BY day(`created`), month(`created`) ORDER BY day(`created`), month(`created`)");
       

        $data = array();

        while($row = $UsersDB -> fetch(PDO::FETCH_ASSOC)){
            
            $day = $row['day'];
            $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));
            $data[] = array(
                'date' => ''.$day.' '.$monthName.'',
                'users' => $row['count']
            );
        
        }
        
        echo json_encode($data);*/
	    


        $data = array();
		$Attacks_Cache = getCache($memoryCache, "__today_atk_V4");

		if($Attacks_Cache) {
			$data = json_decode($Attacks_Cache);
		} else {
            $UsersDB = $odb -> query("SELECT day(`created`) AS day, month(`created`) AS month, COUNT(*) AS count FROM `users` GROUP BY day(`created`), month(`created`) ORDER BY day(`created`), month(`created`)");
       
            while($row = $UsersDB -> fetch(PDO::FETCH_ASSOC)){
            
                $day = $row['day'];
                $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));
                $data[] = array(
                    'date' => ''.$day.' '.$monthName.'',
                    'users' => $row['count']
                );
            
            }
			
			setCache($memoryCache, "__today_atk_V4", json_encode($data));
		}
			
        echo json_encode($data);



	}



?>