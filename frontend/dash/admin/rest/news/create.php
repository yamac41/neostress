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
		
		$title = htmlentities($_POST['title']);
		$desc = nl2br($_POST['desc']);
		$icon = htmlentities($_POST['icon']);

		if(empty($title) || empty($desc) || empty($icon)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
          	die();
		}

		$date = date('Y-m-d H:i:s');

		if(empty($errors)){
			
			$InsertDB = $odb -> prepare("INSERT INTO `news`(`id`, `title`, `description`, `icon`, `date`) VALUES (NULL, :title, :desc, :icon, :date)");
			$InsertDB -> execute(array(':title' => $title, ':desc' => $desc, ':icon' => $icon, ':date' => $date));
			echo json_encode(array('status'=>'success', 'message'=>'News successfully added.'));

		}











	}

?>