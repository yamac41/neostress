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


	$SelectAttacks = $odb -> prepare("SELECT * FROM `attacklogs` WHERE `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0 ORDER BY `id`, `user`");
	$SelectAttacks -> execute();
	$count = $SelectAttacks -> rowCount();

	if($count == 0){
		echo '
			<tr class="text-center">
				<td colspan="7">No running attacks</td>
			</tr>



		';
	}else{
		while($attack = $SelectAttacks -> fetch(PDO::FETCH_ASSOC)) {
			$attackmethod = $odb->query("SELECT `publicname` FROM `methods` WHERE `apiname` = '{$attack['method']}' LIMIT 1")->fetchColumn(0);
			$attackdiff = $attack['date'] + $attack['time'] - time();
			if($attack['apiattack'] == '0'){
				$attacks = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">Regular</span>';
			}else if($attack['apiattack'] == '1'){
				$attacks = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">API</span>';	
			}else{
				$attacks = '<span class="vnm-dark-badge py-1 px-2 rounded-lg">Scheduled</span>';	
			}
			
			$countdown = '<span id="expires-'.$attack['id'].'"></span>
						<script>
	                        CountAttackTime('.$attack['id'].', '.$attackdiff.');
	                    </script>';
			echo '
				<tr>
					<td class="text-center">'.$attack['user'].'</td>
					<td class="text-center">'.$attack['target'].':'.$attack['port'].'</td>
					<td class="text-center">'.$attackmethod.'</td>
					<td class="text-center">'.$attack['servers'].'</td>
					<td class="text-center">'.$attacks.'</td>
					<td class="text-center">'.$countdown.'</td>
					<td class="text-center"><button type="button" id="stopbtn" onclick="StopAttack('.$attack['id'].')" class="btn btn-danger btn-sm"><span id="stop_def"><i class="fa-solid fa-power-off"></i></span><span id="stop_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></span></button></td>
				</tr>
			';

		}
	}





?>
