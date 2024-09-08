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
		$code = htmlentities($_POST['code']);
		$percent = intval($_POST['percent']);
		$type = $_POST['type'];
		$expire = strtotime($_POST['expire']);
		

		if(empty($code) || empty($percent) || empty($type) || empty($expire)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
          	die();
		}

		


    	if(empty($errors)){
    		if($type == 'plans'){
    			$Insert = $odb -> prepare("INSERT INTO `coupons`(`id`, `code`, `percent`, `created_at`, `expire_at`) VALUES (NULL, :code, :percent, NOW(), :expire)");
    			$Insert -> execute(array(':code' => $code, ':percent' => $percent, ':expire' => $expire));
    		}else if($type == 'addons'){
    			$Insert = $odb -> prepare("INSERT INTO `addon_coupons`(`id`, `code`, `percent`, `created_at`, `expire_at`) VALUES (NULL, :code, :percent, NOW(), :expire)");
    			$Insert -> execute(array(':code' => $code, ':percent' => $percent, ':expire' => $expire));
    		}
    		echo json_encode(array('status'=>'success', 'message'=>'Coupon successfully created.'));
          	die();

    	}











	}

?>