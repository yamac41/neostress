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


		$apiname = $_POST['apiname'];
		$publicname = $_POST['publicname'];
		$type = $_POST['type'];
		$premium = intval($_POST['premium']);
		$timelimit = intval($_POST['timelimit']);
		

		$CheckMethod = $odb -> prepare("SELECT COUNT(*) FROM `methods` WHERE `apiname` = :apiname");
		$CheckMethod -> execute(array(':apiname' => $apiname));
		$count = $CheckMethod -> fetchColumn(0);

		if($count > 0){
			$errors[] = "There is already method with this API Name";
          	echo json_encode(array('status'=>'error', 'message'=>'There is already method with this API Name'));
          	die();
		}

		if(empty($errors)){
			$AddMethod = $odb -> prepare("INSERT INTO `methods` (`id`, `apiname`, `publicname`, `type`, `premium`, `timelimit`) VALUES (NULL, :apiname, :publicname, :type, :premium, :timelimit)");
			$AddMethod -> execute(array(':apiname' => $apiname, ':publicname' => $publicname, ':type' => $type, ':premium' => $premium, ':timelimit' => $timelimit));

			echo json_encode(array('status'=>'success', 'message'=>'Method successfully added.'));
		}

	}


?>