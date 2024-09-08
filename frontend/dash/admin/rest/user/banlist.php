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


	$SelectBans = $odb -> prepare("SELECT * FROM `bans` ORDER BY `id` DESC");
	$SelectBans -> execute();
	$count = $SelectBans -> rowCount();

	if($count == 0){
		echo '
			<tr class="text-center">
				<td colspan="5">No data in database</td>
			</tr>



		';
	}else{
		while($ban = $SelectBans -> fetch(PDO::FETCH_ASSOC)) {
			$expire = date('m-d-Y H:i', strtotime($ban['expire']));
			$date = date('m-d-Y H:i', strtotime($ban['date']));
			echo '
				<tr>
					<td class="text-center">'.$ban['username'].'</td>
					<td class="text-center">'.$ban['reason'].'</td>
					<td class="text-center">'.$date.'</td>
					<td class="text-center">'.$expire.'</td>
					<td class="text-center"><button type="button" onclick="UnBanUser('.$ban['id'].')" class="btn btn-danger btn-sm" title="UnBan"><i class="fa-solid fa-unlock-keyhole"></i></button></td>
				</tr>
			';

		}
	}





?>
