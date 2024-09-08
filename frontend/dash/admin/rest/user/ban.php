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
		$reason = htmlentities($_POST['reason']);
		$expirepost = $_POST['expire'];

		
		$expire = date('Y-m-d H:i', strtotime($expirepost));
		$date = date('Y-m-d H:i:s');


		$UserDB = $odb -> prepare("SELECT `username` FROM `users` WHERE `id` = :id");
		$UserDB -> execute(array(':id' => $userid));
		$username = $UserDB -> fetchColumn(0);


		$CheckBan = $odb -> prepare("SELECT * FROM `bans` WHERE `userid` = :userid");
		$CheckBan -> execute(array(':userid' => $userid));

		$count = $CheckBan -> rowCount();

		if($count > 0){
			$errors[] = "This user is already banned";
        	echo json_encode(array('status'=>'error', 'message'=>'This user is already banned!'));
        	die();
		}

		if(empty($errors)){
			$AddBan = $odb -> prepare("INSERT INTO `bans`(`id`, `userid`, `username`, `reason`, `date`, `expire`) VALUES (NULL, :userid, :username, :reason, :date, :expire)");

			$AddBan -> execute(array(':userid' => $userid, ':username' => $username, ':reason' => $reason, ':date' => $date, ':expire' => $expire));

			echo json_encode(array('status'=>'success', 'message'=>'User successfully banned.'));
		}

	}


?>