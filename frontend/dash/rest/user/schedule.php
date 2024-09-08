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
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	if(!($user->isUserPremium($odb))){
		$errors[] = "Only premium users can schedule attacks.";
    	echo json_encode(array('status'=>'error', 'message'=>'Only premium users can schedule attacks.'));
    	die();
	}

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
		$datetime = strtotime($_POST['datetime']);
		$concs = 1;

		if($time < 0 || $port < 0)

		if($user -> SecureText($method)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['time'])) || !(is_numeric($_POST['port']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}


		if (!($user->activeMembership($odb))) {
			$errors[] = "Please fill all required fields to create account!";
        	echo json_encode(array('status'=>'error', 'message'=>'You do not have active membership.'));
        	die();

		}

		if(empty($host) || empty($time) || empty($port) || empty($method) || empty($datetime) || $port < 0 || $port > 65535){
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


		$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	    $UserInfoDB -> execute(array(':id' => $_SESSION['id']));
	    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
	    $planid = $user['plan'];

		$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
		$PlanInfo -> execute(array(':planid' => $planid));
		$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

		$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
		$Addons -> execute(array(':username' => $_SESSION['username']));

		$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

		$totalconcs = ($plan['concs'] + $addon['addon_concs']);
		$totalattacktime = ($plan['time'] + $addon['addon_time']);

		$Sheduled = $odb -> prepare("SELECT COUNT(*) FROM `sheduledattacks` WHERE `datetime` > UNIX_TIMESTAMP() AND `user` = :user");
		$Sheduled -> execute(array(':user' => $_SESSION['username']));

		$countsheduled = $Sheduled -> fetchColumn(0);

		if($countsheduled >= $totalconcs){
			$errors[] = "You cannot schedule more then attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You cannot schedule more then '.$totalconcs.' attacks with this plan!'));
        	die();
		}
		if($countsheduled >= '5'){
			$errors[] = "You cannot schedule more then 5 attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You cannot schedule more then 5 attacks!'));
        	die();
		}
		

		$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
		$MethodDB -> execute(array(':method' => $method));
		$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
		$premiummethod = $methodinfo['premium'];
		$timelimit = $methodinfo['timelimit'];

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
		$Attacks -> execute(array(':user' => $_SESSION['username']));
		$runningattacks = $Attacks -> rowCount();

		if(($runningattacks + $countsheduled + 1) > $totalconcs){
			$errors[] = "You can’t start as many attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You can’t start as many attacks!'));
        	die();
		}

		if($datetime < time()){
			$errors[] = "Invalid datetime!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid datetime!'));
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

		            		$datenow = date('Y-m-d H:i:s');
		            		$InsertShedule = $odb -> prepare("INSERT INTO `sheduledattacks`(`id`, `user`, `target`, `port`, `time`, `method`, `started`, `datetime`, `created`) VALUES (NULL, :user, :target, :port, :time, :method, '0', :datetime, NOW())");

							$InsertShedule -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':datetime' => $datetime));
						}
						if($atck == 0) {
			        		$errors[] = "No available slots!";
			    			echo json_encode(array('status'=>'error', 'message'=>'No available slots for your attack!'));
			    			die();	
			        	}
						echo json_encode(array('status'=>'success', 'message'=>'Attack successfully scheduled!'));
						die();
			     
			    }
			}
		}
	
	}else if($type == 'l7'){

	
		$host = $_POST['host'];
		$time = intval($_POST['time']);
		$port = 80;
		$method = htmlentities($user -> CheckInput($_POST['method']));
		$concs = 1;
		$datetime = strtotime($_POST['datetime']);
		$reqmethod = $user -> CheckInput($_POST['reqmethod']);
		$reqs = intval($_POST['reqs']);

		

		if($user -> SecureText($method) || $user -> SecureText($reqmethod)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!(is_numeric($_POST['time'])) || !(is_numeric($_POST['reqs']))){
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
		
		$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
	    $UserInfoDB -> execute(array(':id' => $_SESSION['id']));
	    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
	    $planid = $user['plan'];

		$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
		$PlanInfo -> execute(array(':planid' => $planid));
		$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

		$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
		$Addons -> execute(array(':username' => $_SESSION['username']));

		$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

		$totalconcs = ($plan['concs'] + $addon['addon_concs']);
		$totalattacktime = ($plan['time'] + $addon['addon_time']);

		$SheduledDB = $odb -> prepare("SELECT COUNT(*) FROM `sheduledattacks` WHERE `datetime` > UNIX_TIMESTAMP() AND `user` = :user");
		$SheduledDB -> execute(array(':user' => $_SESSION['username']));

		$countsheduled = $SheduledDB -> fetchColumn(0);

		$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
		$Attacks -> execute(array(':user' => $_SESSION['username']));
		$runningattacks = $Attacks -> rowCount();

		

		if(($runningattacks + $countsheduled + 1) > $totalconcs){
			$errors[] = "You can’t start as many attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You can’t start as many attacks!'));
        	die();
		}


		if($countsheduled >= $totalconcs){
			$errors[] = "You cannot schedule more then attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You cannot schedule more then '.$totalconcs.' attacks with this plan!'));
        	die();
		}
		if($countsheduled >= '5'){
			$errors[] = "You cannot schedule more then 5 attacks!";
        	echo json_encode(array('status'=>'error', 'message'=>'You cannot schedule more then 5 attacks!'));
        	die();
		}
		
		
		$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
		$MethodDB -> execute(array(':method' => $method));
		$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
		$premiummethod = $methodinfo['premium'];
		$timelimit = $methodinfo['timelimit'];

		if(empty($host) || empty($time) || empty($method) || empty($_POST['datetime']) || $port != 80 || empty($reqs)){
			$errors[] = "Please fill all required fields";
        	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
        	die();
		}
		if($datetime < time()){
			$errors[] = "Invalid datetime!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid datetime!'));
        	die();
		}
		if($reqs > 64 || $reqs < 0){
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
		if(!(filter_var($time, FILTER_SANITIZE_NUMBER_INT))){
			$errors[] = "Invalid port or time value!";
        	echo json_encode(array('status'=>'error', 'message'=>'Invalid port or time value!'));
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
								
								$InsertSchedule = $odb -> prepare("INSERT INTO `sheduledattacks`(`id`, `user`, `target`, `port`, `time`, `method`, `started`, `datetime`, `created`) VALUES (NULL, :user, :target, :port, :time, :method, '0', :datetime, NOW())");

								$InsertSchedule -> execute(array(':user' => $_SESSION['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $methodupdated, ':datetime' => $datetime));
								
			            		
							}



						if($atck == 0) {
			        		$errors[] = "No available slots for your attack!";
			    			echo json_encode(array('status'=>'error', 'message'=>'No available slots for your attack!'));
			    			die();	
			        	}
						echo json_encode(array('status'=>'success', 'message'=>'Attack successfully scheduled!'));
						die();
			     
			    }
			}
			
		}




	}else if($type == 'delete'){
		$shedid = intval($_POST['shedid']);

		$SelectAttack = $odb -> prepare("SELECT COUNT(*) FROM `sheduledattacks` WHERE `id` = :id AND `user` = :user");
		$SelectAttack -> execute(array(':id' => $shedid, ':user' => $_SESSION['username']));

		$count = $SelectAttack -> fetchColumn(0);

		if($count == '0'){
			$errors[] = "This is not your attack!";
			echo json_encode(array('status'=>'error', 'message'=>'This is not your attack!'));
			die();	
		}


		if(empty($errors)){
			$UpdateSQL = $odb -> prepare("DELETE FROM `sheduledattacks` WHERE `id` = :id");
			$UpdateSQL -> execute(array(':id' => $shedid));

			echo json_encode(array('status'=>'success', 'message'=>'Scheduled attack successfully deleted.'));
			die();
		}

	}


?>