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


	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		$userid = intval($_POST['userid']);
		$username = htmlentities($_POST['username']);
		$email = $_POST['email'];
		$rank = htmlentities($_POST['rank']);
		$plan = intval($_POST['plan']);
		$balance = intval($_POST['balance']);
		$premium = intval($_POST['premium']);
		$apiaccess = intval($_POST['apiaccess']);
		$apikey = htmlentities($_POST['apikey']);
		$aconcs = intval($_POST['aconcs']);
		$atime = intval($_POST['atime']);
		$blacklist = intval($_POST['blacklist']);
		$secret = htmlentities($_POST['secret']);

		$expire = $_POST['expire'];

		$UserInfo = $odb -> prepare("SELECT `plan` FROM `users` WHERE `id` = :id");
		$UserInfo -> execute(array(':id' => $userid));
		$userplan = $UserInfo -> fetchColumn(0);

		if(empty($expire) && $plan != $userplan){
			$newexpire = strtotime("+30 Days", time());

			$UpdateUser = $odb -> prepare("UPDATE `users` SET `username` = :username, `email` = :email, `rank` = :rank, `plan` = :plan, `planexpire` = :expire, `balance` = :balance, `premium` = :premium, `apiaccess` = :apiaccess, `addon_concs` = :concs, `addon_time` = :time, `addon_blacklist` = :blacklist, `secretkey` = :secret WHERE `id` = :id");
			$UpdateUser -> execute(array(':username' => $username, ':email' => $email, ':rank' => $rank, ':plan' => $plan, ':expire' => $newexpire, ':balance' => $balance, ':premium' => $premium, ':apiaccess' => $apiaccess, ':concs' => $aconcs, ':time' => $atime, ':blacklist' => $blacklist, ':secret' => $secret, ':id' => $userid));

			echo json_encode(array('status'=>'success', 'message'=>'User successfully updated.'));

		}else if(empty($expire) && $plan == $userplan){
			$UpdateUser = $odb -> prepare("UPDATE `users` SET `username` = :username, `email` = :email, `rank` = :rank, `plan` = :plan, `balance` = :balance, `premium` = :premium, `apiaccess` = :apiaccess, `addon_concs` = :concs, `addon_time` = :time, `addon_blacklist` = :blacklist, `secretkey` = :secret WHERE `id` = :id");
			$UpdateUser -> execute(array(':username' => $username, ':email' => $email, ':rank' => $rank, ':plan' => $plan, ':balance' => $balance, ':premium' => $premium, ':apiaccess' => $apiaccess, ':concs' => $aconcs, ':time' => $atime, ':blacklist' => $blacklist, ':secret' => $secret, ':id' => $userid));

			echo json_encode(array('status'=>'success', 'message'=>'User successfully updated.'));
		
		}else{

			$setexpire = strtotime($expire);


			$UpdateUser = $odb -> prepare("UPDATE `users` SET `username` = :username, `email` = :email, `rank` = :rank, `plan` = :plan, `planexpire` = :expire, `balance` = :balance, `premium` = :premium, `apiaccess` = :apiaccess, `addon_concs` = :concs, `addon_time` = :time, `addon_blacklist` = :blacklist, `secretkey` = :secret WHERE `id` = :id");
			$UpdateUser -> execute(array(':username' => $username, ':email' => $email, ':rank' => $rank, ':plan' => $plan, ':expire' => $setexpire, ':balance' => $balance, ':premium' => $premium, ':apiaccess' => $apiaccess, ':concs' => $aconcs, ':time' => $atime, ':blacklist' => $blacklist, ':secret' => $secret, ':id' => $userid));

			echo json_encode(array('status'=>'success', 'message'=>'User successfully updated.'));
			}

	}


?>