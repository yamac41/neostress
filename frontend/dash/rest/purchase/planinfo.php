<?php

	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';


	if (!$user->UserLoggedIn() || !$user->notBanned($odb)) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}


	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		$planid = intval($_POST['planid']);
		if(!(is_numeric($_POST['planid']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if(!filter_var($planid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid planID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid planID!'));
          	die();
    	}

		if($planid < 0){
			$errors[] = "Invalid planID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid planID!'));
          	die();
		}
		
    	if(empty($errors)){
			
			$PlanInfo = $odb -> prepare("SELECT `concs`, `time`, `length`, `premium`, `apiaccess`, `price` FROM `plans` WHERE `id` = :id AND `private` = 'no'");
			$PlanInfo -> execute(array(':id' => $planid));

			$plan = $PlanInfo -> fetch(PDO::FETCH_ASSOC);

			if($plan['premium'] == 0){
				$premium = 'no';
			}else{
				$premium = 'yes';
			}

			if($plan['apiaccess'] == 0){
				$api = 'no';
			}else{
				$api = 'yes';
			}

			echo json_encode(array(
				'status'=>'success', 
				'concs'=>$plan['concs'],
				'time'=>''.$plan['time'].'s',
				'length'=>''.$plan['length'].' days',
				'premium'=>$premium,
				'api'=>$api,
				'price'=>''.$plan['price'].'$'

			));

		}else{
			header('HTTP/1.0 400 Bad Request');
			exit();
		}















	}


?>