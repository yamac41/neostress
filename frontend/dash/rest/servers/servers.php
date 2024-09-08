<?php
    $ServerDB_Cache = getCache($memoryCache, "__serversList");
    if($ServerDB_Cache) {
        echo $ServerDB_Cache;
        return;
    }

	$ServerDB = $odb -> query("SELECT ds.premium as ServerPremium, ds.status as ServerStatus, ds.name as ServerName, ds.type as ServerType, ds.slots as MaxSlots, count(com.id) AS AttacksCount
	FROM servers ds
	LEFT JOIN attacklogs com ON (com.servers = ds.name) AND com.enddate > UNIX_TIMESTAMP() AND com.stopped = 0
	GROUP by ds.id
	ORDER BY ds.id ASC");

	$BuildOutput = "";

	while($serverinfo = $ServerDB ->fetch(PDO::FETCH_ASSOC)){
		$name = $serverinfo['ServerName'];
		$type = $serverinfo['ServerType'];
		$serverslots = $serverinfo['MaxSlots'];
		$status = $serverinfo['ServerStatus'];
		$premium = $serverinfo['ServerPremium'];

		if($premium == 'no'){
			$premiumtext = '<span>Basic</span>';
		}else if($premium == 'yes'){
			$premiumtext = '<span style="color: #AC8EFF; font-weight: 500;">Premium</span>';
		}

		if($status == 'online'){
			$statustext = '<span style="color: #1EDE8D;">Online</span>';
		}else if($status == 'offline'){
			$statustext = '<span style="color: #FF4343;">Offline</span>';
		}else if($status == 'maintaince'){
			$statustext = '<span style="color: #FF4343;">Offline</span>';
		}
		

		$runningattacks = $serverinfo['AttacksCount'];

	 	$serverload = $serverslots - $runningattacks;
		
		$percentagefull = ($runningattacks / $serverslots) * 100;
		$percentage = round($percentagefull, 2);

		if ($percentage >= 0 AND $percentage <= 35 ){
			$loadbar = '
				<div class="progress mt-1 mx-md-5 mx-lg-5 mx-xl-5 mx-xxl-5" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-custom-class="custom-tooltip" title="'.$runningattacks.'/'.$serverslots.'">
	                <div class="progress-bar bg-green" style="width: '.$percentage.'%;" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100"></div>
	            </div>';
		}else if($percentage >= 36 AND $percentage <= 74){
			$loadbar = '
				<div class="progress mt-1 mx-md-5 mx-lg-5 mx-xl-5 mx-xxl-5" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-custom-class="custom-tooltip" title="'.$runningattacks.'/'.$serverslots.'">
	                <div class="progress-bar bg-orange" style="width: '.$percentage.'%;" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100"></div>
	            </div>';
		}else if($percentage >= 75 AND $percentage <= 100){
			$loadbar = '
				<div class="progress mt-1 mx-md-5 mx-lg-5 mx-xl-5 mx-xxl-5" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-custom-class="custom-tooltip" title="'.$runningattacks.'/'.$serverslots.'">
	                <div class="progress-bar bg-red" style="width: '.$percentage.'%;" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100"></div>
	            </div>';
		}else if($percentage > 100){
			$loadbar = '
				<div class="progress mt-1 mx-md-5 mx-lg-5 mx-xl-5 mx-xxl-5" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-custom-class="custom-tooltip" title="'.$runningattacks.'/'.$serverslots.'">
	                <div class="progress-bar bg-red" style="width: '.$percentage.'%;" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100"></div>
	            </div>';
		}
		$BuildOutput = $BuildOutput . '
			<tr>
	            <td>'.$name.'</td>
	            <td>'.$premiumtext.'</td>
	            <td>'.$statustext.'</td>
				<td>
				'.$loadbar.'
			  	</td>
	        </tr>
		';
	}

	setCache($memoryCache, "__serversList", $BuildOutput);
	echo $BuildOutput;
?>