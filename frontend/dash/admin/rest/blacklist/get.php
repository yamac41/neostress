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

	

	$SelectBlacklist = $odb -> prepare("SELECT * FROM `blacklist` ORDER BY `id` ASC");
	$SelectBlacklist -> execute();
	$count = $SelectBlacklist -> rowCount();

	if($count == 0){
		echo '
			<tr class="text-center">
				<td colspan="4">No data in database</td>
			</tr>



		';
	}else{
		while($bl = $SelectBlacklist -> fetch(PDO::FETCH_ASSOC)) {
			
			echo '
				<tr>
					<td class="text-center">'.$bl['target'].'</td>
					<td class="text-center"><span class="vnm-dark-badge py-1 px-2 rounded-lg">'.$bl['type'].'</span></td>
					<td class="text-center">'.$bl['user'].'</td>
					<td class="text-center"><button type="button" onclick="UnBlackList('.$bl['id'].')" class="btn btn-danger btn-sm" title="UnBlackList"><i class="fa-solid fa-circle-minus"></i></button></td>
				</tr>
			';

		}
	}





?>
