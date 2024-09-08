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

	

	$SelectAttacks = $odb -> prepare("SELECT * FROM `attacklogs` ORDER BY `id` DESC LIMIT 35");
	$SelectAttacks -> execute();
	$count = $SelectAttacks -> rowCount();

	if($count == 0){
		echo '
			<tr class="text-center">
				<td colspan="5">No data in database</td>
			</tr>



		';
	}else{
		while($log = $SelectAttacks -> fetch(PDO::FETCH_ASSOC)) {
			$attackmethod = $odb->query("SELECT `publicname` FROM `methods` WHERE `apiname` = '{$log['method']}' LIMIT 1")->fetchColumn(0);
			
			$started = date('m-d-Y H:i:s', $log['date']);
			echo '
				<tr>
					<td class="text-center">'.$log['user'].'</td>
					<td class="text-center">'.$log['target'].' <span class="vnm-dark-badge py-1 px-2 rounded-lg">'.$attackmethod.'</span></td>
					<td class="text-center">'.$log['time'].'s</td>
					<td class="text-center">'.$started.'</td>
					<td class="text-center">'.$log['servers'].'</td>
				</tr>
			';

		}
	}





?>
