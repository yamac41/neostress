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
		
		$sellixapikey = $setting['sellixapikey'];


		$amount = intval($_POST['amount']);
		$coin = htmlentities($user -> CheckInput($_POST['gateway']));

		if(empty($amount) || empty($coin)){
			$errors[] = "Amount or gateway are empty!";
          	echo json_encode(array('status'=>'error', 'message'=>'Amount or gateway are empty!'));
          	die();
		}

		if(!(is_numeric($_POST['amount']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}
		if($user -> SecureText($coin)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		$coins = array('BITCOIN', 'BITCOIN_CASH', 'ETHEREUM', 'LITECOIN', 'SOLANA', 'USDT:ERC20', 'USDT:TRC20', 'TRON');
  		if(!in_array($coin, $coins)){
      		$errors[] = "Invalid gateway!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid gateway!'));
          	die();
    	}

    	if($amount < 2){
    		$errors[] = "Invalid amount value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid amount value, minimum value is 2$'));
          	die();
    	}
    	if($amount > 2000){
    		$errors[] = "Invalid amount value!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid amount value, maximum value is 2000$'));
          	die();
    	}
    	if(!filter_var($amount, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Amount must to be number!";
          	echo json_encode(array('status'=>'error', 'message'=>'The amount must be a number!'));
          	die();
    	}
    	if(!filter_var($coin, FILTER_SANITIZE_STRING)){
    		$errors[] = "Invalid gateway val!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid gateway value!'));
          	die();
    	}

    	$UserInfoDB = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :username");
    	$UserInfoDB -> execute(array(':username' => $_SESSION['username']));
    	$user = $UserInfoDB -> fetch(PDO::FETCH_ASSOC);

    	$CheckPayment = $odb -> prepare("SELECT * FROM `payments` WHERE `user` = :user AND `status` = 'PENDING'");
    	$CheckPayment -> execute(array(':user' => $_SESSION['username']));
    	
    	$runnpayment = $CheckPayment -> rowCount();
    	
    	if($runnpayment > 5){
    		$errors[] = "You have too many pending payments.";
          	echo json_encode(array('status'=>'error', 'message'=>'You have too many pending payments, please cancel one.'));
          	die();
    	}

    	if(empty($errors)){
	    	$params = [
	            'title' => "Deposit funds",
	            'currency' => "USD",
	            'return_url' => "https://catstresse.fun/dash/deposit",
	            'webhook' => "https://catstresse.fun/dash/rest/webhooks/sellixhook",
	            'email' => $user['email'],
	            'value' => $amount,
	            'gateway' => $coin,
	            'confirmations' => 2,
	            'white_label' => true
	        ];

	        $curlpay = curl_init('https://dev.sellix.io/v1/payments');
	        curl_setopt($curlpay, CURLOPT_POST, true);
	        curl_setopt($curlpay, CURLOPT_POSTFIELDS, json_encode($params));
	        curl_setopt($curlpay, CURLOPT_USERAGENT, 'Sellix (PHP ' . PHP_VERSION . ')');
	        curl_setopt($curlpay, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $sellixapikey]);
	        curl_setopt($curlpay, CURLOPT_TIMEOUT, 20);
	        curl_setopt($curlpay, CURLOPT_RETURNTRANSFER, true);
	        $response = curl_exec($curlpay);
	        curl_close($curlpay);

	        if(empty($response)){
	        	$errors[] = "There is problem with payment, please try again!";
	          	echo json_encode(array('status'=>'error', 'message'=>'There is problem with payment, please try again!'));
	          	die();
	        }

	    	$res = json_decode($response, true);

	    	$InsertDB = $odb -> prepare("INSERT INTO `payments` (`id`, `uniqid`, `user`, `type`, `amount`, `crypto_address`, `crypto_amount`, `crypto_uri`, `gateway`, `created_at`, `status`) VALUES (NULL, :uniqid, :user, 'Deposit', :amount, :cryptoaddr, :cryptoamount, :cryptouri, :gateway, :created, :status)");
	    	$InsertDB -> execute(array(':uniqid' => $res['data']['invoice']['uniqid'], ':user' => $_SESSION['username'], ':amount' => $amount, ':cryptoaddr' => $res['data']['invoice']['crypto_address'], ':cryptoamount' => $res['data']['invoice']['crypto_amount'], ':cryptouri' => $res['data']['invoice']['crypto_uri'], ':gateway' => $res['data']['invoice']['gateway'], ':created' => $res['data']['invoice']['created_at'], ':status' => $res['data']['invoice']['status']));

	    	$SelectPayment = $odb -> prepare("SELECT * FROM `payments` WHERE `uniqid` = :uniqid AND `user` = :user");
    		$SelectPayment -> execute(array(':uniqid' => $res['data']['invoice']['uniqid'], ':user' => $_SESSION['username']));
	    	$payment = $SelectPayment -> fetch(PDO::FETCH_ASSOC);
    	

	    	$expireapi = $res['data']['invoice']['created_at'];
    		$expires = date ('Y-m-d H:i:s', $expireapi + 1800);
    		$qr = 'https://api.qrserver.com/v1/create-qr-code/?size=123x123&data='.$res['data']['invoice']['crypto_uri'].'';
	    	
	    	echo json_encode(array(
	    		'status'=>'success', 
	    		'message'=>'Payment successfully generated, processing...', 
	    		'id'=>$payment['id'],
	    		'coin'=>$res['data']['invoice']['gateway'],
    			'address'=>$res['data']['invoice']['crypto_address'],
    			'amount'=>$res['data']['invoice']['crypto_amount'],
    			'qr'=>''.$qr.'',
    			'amount_paid'=>$res['data']['invoice']['crypto_received'],
    			'expires'=>$expires,
    			'pstatus'=>$res['data']['invoice']['status'],
    			'confirmations'=>''.$payment['confirmations'].' of '.$res['data']['invoice']['crypto_confirmations_needed'].'',
    			'hash'=>$payment['hash']
	    	));
	    }
	}
?>