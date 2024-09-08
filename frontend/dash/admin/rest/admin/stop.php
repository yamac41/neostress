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


		$attackid = intval($_POST['attackid']);
		
		if(!(filter_var($attackid, FILTER_SANITIZE_NUMBER_INT))){
			$errors[] = "There is problem with your request!";
			echo json_encode(array('status'=>'error', 'message'=>'There is problem with your request!'));
			die();		
		}

		$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `id` = :attackid");
    	$SelectAttack -> execute(array(':attackid' => $attackid));
    	$info = $SelectAttack -> fetch(PDO::FETCH_ASSOC);

    	

    	

    	if(empty($errors)){
    		$handler = $info['servers'];
    		$handlers = explode(",", $handler);

    		foreach ($handlers as $handler){
		    	$SQLSelectAPI = $odb->prepare("SELECT `apiurl` FROM `servers` WHERE `name` = :handler ORDER BY `id` DESC");
		    	$SQLSelectAPI -> execute(array(':handler' => $handler));
		    	while($api = $SQLSelectAPI -> fetch(PDO::FETCH_ASSOC)){
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
			        //echo $result;
		    	}
	    	}	
	        if(!$result){
	        	$errors[] = "There is problem with API, please contact administrator!";
				echo json_encode(array('status'=>'error', 'message'=>'There is problem with API, please contact administrator!'));
				die();
	        }

			$UpdateAttack = $odb -> prepare("UPDATE `attacklogs` SET `stopped` = 1 WHERE `id` = :attackid");
			$UpdateAttack -> execute(array(':attackid' => $attackid));

    		echo json_encode(array('status'=>'success', 'message'=>'Attack '.$attackid.' successfully stopped!'));

    	}
		

    }
	
		













?>