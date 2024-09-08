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

		$paymentid = intval($_POST['payid']);


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
    	
    	if($count == 0){
    		$errors[] = "This is not your payment!";
          	echo json_encode(array('status'=>'error', 'message'=>'This is not your payment!'));
          	die();
    	}

    	if($payment['status'] == 'VOIDED'){
    		$errors[] = "Your payment has expired.";
          	echo json_encode(array('status'=>'error', 'message'=>'Your payment has expired, please generate new.'));
          	die();
    	}
        if($payment['status'] == 'COMPLETED'){
            $errors[] = "Your payment has completed.";
            echo json_encode(array('status'=>'error', 'message'=>'Your payment has completed.'));
            die();
        }



    	if(empty($errors)){
    		$expiredb = $payment['created_at'];
    		
    		$expires = date ('Y-m-d H:i:s', $expiredb + 1500);
    		$qr = 'https://api.qrserver.com/v1/create-qr-code/?size=123x123&data='.$payment['crypto_uri'].'';
    		
    		if($payment['status'] == 'PENDING'){
				$status = 'We are looking for your payment..';
			}else if($payment['status'] == 'VOIDED'){
				$status = 'Invoice is expired!';
			}else if($payment['status'] == 'PARTIAL'){
				$status = 'Partially paid..';
			}else if($payment['status'] == 'PROCESSING'){
				$status = 'We are processing your payment..';
			}else if($payment['status'] == 'WAITING_FOR_CONFIRMATIONS'){
				$status = 'We are confirming your payment..';
			}else if($payment['status'] == 'COMPLETED'){
				$status = 'Payment completed!';
			}

    		echo json_encode(array(
    			'status'=>'success', 
    			'message'=>'Processing..', 
    			'id'=>$payment['id'],
    			'coin'=>$payment['gateway'],
    			'address'=>$payment['crypto_address'],
    			'amount'=>$payment['crypto_amount'],
    			'qr'=>''.$qr.'',
    			'amount_paid'=>$payment['amount_paid'],
    			'expires'=>$expires,
    			'pstatus'=>$status,
    			'confirmations'=>''.$payment['confirmations'].' of 2',
    			'hash'=>$payment['hash']
    		));
    	}
	}
?>