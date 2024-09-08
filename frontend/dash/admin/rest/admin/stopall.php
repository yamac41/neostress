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
		
		$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 ORDER BY `id` DESC");
		$SelectAttack -> execute();
    	$info = $SelectAttack -> fetch(PDO::FETCH_ASSOC);

    	$countattacks = $SelectAttack -> rowCount();
		
		if($countattacks == 0){
    		$errors[] = "There is no running attacks.";
			echo json_encode(array('status'=>'error', 'message'=>'There is no running attacks.'));
			die();
    	}
    	

    	if(empty($errors)){
    		$handler = $info['servers'];
    		$handlers = explode(",", $handler);

    		foreach ($handlers as $handler){
		    	$SQLSelectAPI = $odb->prepare("SELECT `apiurl` FROM `servers` WHERE `name` = :handler ORDER BY `id` DESC");
		    	$SQLSelectAPI -> execute(array(':handler' => $handler));
		    	$api = $SQLSelectAPI -> fetch(PDO::FETCH_ASSOC);
		    	$SelectAttackk = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
    			$SelectAttackk -> execute();
		    	while($infoo = $SelectAttackk -> fetch(PDO::FETCH_ASSOC)) {
		    		$target = $infoo['target'];
		    		$port = $infoo['port'];
		    		$time = $infoo['time'];
		    		
		    		$stopcommand = "stop";
			    	$FindInApi = array('[host]', '[port]', '[time]', '[method]');
					$ApiReplace = array($info['target'], $info['port'], $info['time'], $stopcommand);
					
			        $APIReplaced = str_replace($FindInApi, $ApiReplace, $api['apiurl']);
			        
			        
			        $stopapi = $APIReplaced;
			        $ch = curl_init();
			        curl_setopt($ch, CURLOPT_URL, $stopapi);
			        curl_setopt($ch, CURLOPT_HEADER, 0);
			        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
			        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);    
			        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			        $result = curl_exec($ch);
			        curl_close($ch);
			       
		    	}
	    	}	
	        
			$UpdateAttack = $odb -> prepare("UPDATE `attacklogs` SET `stopped` = 1 WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
			$UpdateAttack -> execute();
			echo json_encode(array('status'=>'success', 'message'=>'All running attacks successfully stopped!'));

    	}

    }






?>