<?php

	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';

	if (!function_exists('str_contains')) {
		function str_contains($haystack, $needle) {
			return $needle !== '' && mb_strpos($haystack, $needle) !== false;
		}
	}

	if (!$user->UserLoggedIn() || !$user->notBanned($odb)) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		$paymentid = intval($_POST['payid']);
		$sellixapikey = $setting['sellixapikey'];

		if(!filter_var($paymentid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid ID value!";
      	echo json_encode(array('status'=>'error', 'message'=>'Invalid payment ID value!'));
      	die();
    	}
    	if(!(is_numeric($_POST['payid']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

    	$SelectPayment = $odb -> prepare("SELECT * FROM `payments` WHERE `id` = :payid AND `user` = :user");
    	$SelectPayment -> execute(array(':payid' => $paymentid, ':user' => $_SESSION['username']));
    	
    	$count = $SelectPayment -> rowCount();
    	$payment = $SelectPayment -> fetch(PDO::FETCH_ASSOC);
    	
    	if($payment['status'] == 'COMPLETED'){
    		$errors[] = "You cannot cancel completed payment.";
          	echo json_encode(array('status'=>'error', 'message'=>'You cannot cancel completed payment.'));
          	die();
    	}
    	if($count == 0){
    		$errors[] = "This is not your payment!";
          	echo json_encode(array('status'=>'error', 'message'=>'This is not your payment!'));
          	die();
    	}

    	if(empty($errors)){

    		$UpdateDB = $odb -> prepare("UPDATE `payments` SET `status` = 'VOIDED' WHERE `id` = :payid");
    		$UpdateDB -> execute(array(':payid' => $paymentid));

    		$curlpay = curl_init('https://dev.sellix.io/v1/payments/'.$payment['uniqid'].'');
    		curl_setopt($curlpay, CURLOPT_CUSTOMREQUEST, "DELETE");
	        curl_setopt($curlpay, CURLOPT_USERAGENT, 'Sellix (PHP ' . PHP_VERSION . ')');
	        curl_setopt($curlpay, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $sellixapikey]);
	        curl_setopt($curlpay, CURLOPT_TIMEOUT, 10);
	        curl_setopt($curlpay, CURLOPT_RETURNTRANSFER, true);
	        $response = curl_exec($curlpay);
	        curl_close($curlpay);

	        //$res = json_decode($response, true);

	        if (str_contains($response, 'Payment Voided Successfully')) {
	        	echo json_encode(array('status'=>'success', 'message'=>'Payment successfully canceled.'));
			}
    	}
    }
?>