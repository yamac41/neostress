<?php
	$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
	if (!isset($ip)) {
	  $ip = $_SERVER['REMOTE_ADDR'];
	}
	if(!(filter_var($ip, FILTER_VALIDATE_IP))){
		$errors[] = "Problem with your IP Address.";
        echo json_encode(array('status'=>'error', 'message'=>'Problem with your IP Address.'));
        die();

	}
	$whitelist = array('99.81.24.41');
	if (!(in_array($ip, $whitelist))) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../backend/configuration/database.php';
	require '../../../../backend/configuration/funcsinit.php';


	$data = json_decode(file_get_contents('php://input'), true);
	
	$event = $data['event'];
	$sellix_uniqid = $data['data']['uniqid'];
	$sellix_amount = $data['data']['total'];
	$sellix_email = $data['data']['customer_email'];
	$crypto_received = $data['data']['crypto_received'];
	$status = $data['data']['status'];

	echo 'https://sellix.io/payment/'.$sellix_uniqid.'';

	function validate_order($order_uniqid){
		
		$sellixapikey = 'SELLIX API KEY HERE!!';
		
		$curl = curl_init('https://dev.sellix.io/v1/orders/' . $order_uniqid);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sellix (PHP ' . PHP_VERSION . ')');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $sellixapikey]);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
		curl_close($curl);
        
        $res = json_decode($result, true);

        if ($res['error']) {
        	echo $res['error'];
        }else{
        	return $res['data']['order'];
        }

	}
	$valid_order = validate_order($sellix_uniqid);

	if($valid_order) {

		if($status == 'PARTIAL'){
			
			$SQLUpdate = $odb -> prepare("UPDATE `payments` SET `status`= 'PARTIAL' WHERE `uniqid` = :uniqid AND `amount_paid` = :paid");
      		$SQLUpdate->execute(array(":uniqid" => $valid_order['uniqid'], ':paid' => $data['data']['crypto_transactions'][0]['crypto_amount']));
		
		}else if($status == 'WAITING_FOR_CONFIRMATIONS'){
			
			$SQLUpdate = $odb -> prepare("UPDATE `payments` SET `status`= 'WAITING_FOR_CONFIRMATIONS', `amount_paid` = :paid, `confirmations` = :confirms, `hash` = :hash WHERE `uniqid` = :uniqid ");
      		$SQLUpdate->execute(array(":uniqid" => $valid_order['uniqid'], ":paid" => $data['data']['crypto_transactions'][0]['crypto_amount'], ":confirms" => $data['data']['crypto_transactions'][0]['confirmations'], ":hash" => $data['data']['crypto_transactions'][0]['hash']));
		
		}else if($status == 'PROCESSING'){

			$SQLUpdate = $odb -> prepare("UPDATE `payments` SET `status`= 'PROCESSING' WHERE `uniqid` = :uniqid");
      		$SQLUpdate->execute(array(":uniqid" => $valid_order['uniqid']));

		}else if($status == 'VOIDED'){

			$SQLUpdate = $odb -> prepare("UPDATE `payments` SET `status`= 'VOIDED' WHERE `uniqid` = :uniqid");
      		$SQLUpdate->execute(array(":uniqid" => $valid_order['uniqid']));

		}else if($status == 'COMPLETED'){
			$SQLUpdate = $odb -> prepare("UPDATE `payments` SET `status`= 'COMPLETED' WHERE `uniqid` = :uniqid");
      		$SQLUpdate->execute(array(":uniqid" => $valid_order['uniqid']));

			$SelectPayment = $odb -> prepare("SELECT * FROM `payments` WHERE `uniqid` = :uniqid");
			$SelectPayment -> execute(array(':uniqid' => $valid_order['uniqid']));
			$payment = $SelectPayment -> fetch(PDO::FETCH_ASSOC);

      		$SQLUpdatesec2 = $odb -> prepare("UPDATE `users` SET `balance` = `balance` + :bal WHERE `username` = :usernameuwu");
      		$SQLUpdatesec2 -> execute(array(':usernameuwu' => $payment['user'], ':bal' => $valid_order['total']));
		}
	}

?>