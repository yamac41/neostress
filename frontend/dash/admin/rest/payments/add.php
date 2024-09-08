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
		

		$user = htmlentities($_POST['user']);
		$amount = intval($_POST['amount']);
		$type = htmlentities($_POST['type']);
		$date = strtotime($_POST['date']);
		
		function generateRandom($digits) { 
		    $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $rand_str = '';
		    while(strlen($rand_str) < $digits)
		    $rand_str .= substr(str_shuffle($charset), 0, 1);
		    return $rand_str; 
		}


		if(empty($user) || empty($amount) || empty($type) || empty($date)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
          	die();
		}

		$coins = array('BITCOIN', 'PAYPAL', 'LITECOIN', 'BITCOINCASH', 'ETHEREUM', 'MONERO', 'NANO', 'SOLANA', 'GIFTCARD');
  		if(!in_array($type, $coins)){
      		$errors[] = "Invalid length type!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid coin.'));
          	die();
    	}


    	if(empty($errors)){
    		$SQLInsert = $odb -> prepare("INSERT INTO `payments`(`id`, `uniqid`, `user`, `type`, `amount`, `crypto_address`, `crypto_amount`, `amount_paid`, `crypto_uri`, `gateway`, `confirmations`, `hash`, `created_at`, `status`) VALUES (NULL, :uniqid, :user, :type, :amount, :crypto_address, :crypto_amount, :amount_paid, :crypto_uri, :gateway, :confirmations, :hash, :created_at, :status )");
    		$SQLInsert -> execute(array(':uniqid' => generateRandom(24), ':user' => $user, ':type' => 'Custom', ':amount' => $amount, ':crypto_address' => generateRandom(34), ':crypto_amount' => '0', ':amount_paid' => '0', ':crypto_uri' => 'none', ':gateway' => $type, ':confirmations' => '2', ':hash' => 'none', ':created_at' => $date, ':status' => 'COMPLETED'));
    		echo json_encode(array('status'=>'success', 'message'=>'Invoice successfully added.'));
          	die();

    	}











	}

?>