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
		$coupon = htmlentities($user -> CheckInput($_POST['coupon']));

		
		if(!(is_numeric($_POST['planid']))){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}
		if(!filter_var($planid, FILTER_SANITIZE_NUMBER_INT)){
    		$errors[] = "Invalid planID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid planID!'));
          	die();
    	}
		if($user -> SecureText($coupon)){
			header('HTTP/1.0 400 Bad Request');
			exit();
		}

		if($planid < 0){
			$errors[] = "Invalid planID!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid planID!'));
          	die();
		}

    	$SelectPlan = $odb -> prepare("SELECT `price`, `premium`, `name`, `id`, `length`, `lengthtype` FROM `plans` WHERE `id` = :id AND `private` = 'no'");
    	$SelectPlan -> execute(array(':id' => $planid));
    	$plan = $SelectPlan -> fetch(PDO::FETCH_ASSOC);
    	$price = $plan['price'];
    	$length = $plan['length'];
    	$lengthtype = $plan['lengthtype'];
    	$name = $plan['name'];

		$UserInfo = $odb -> prepare("SELECT `balance` FROM `users` WHERE `id` = :userid AND `username` = :username");
    	$UserInfo -> execute(array(':userid' => $_SESSION['id'], ':username' => $_SESSION['username']));
    	$balance = $UserInfo -> fetchColumn(0);

    	$expire = strtotime("+{$length} {$lengthtype}", time());
		
        if(empty($coupon)){
            if($price > $balance){
            
                $errors[] = "You do not have enought balance for this plan.";
                echo json_encode(array('status'=>'error', 'message'=>'You do not have enought balance for this plan.'));
                die();  
            
            }else if($balance >= $price){
                $date = date('Y-m-d H:i:s');
    			$UpdateBalance = $odb -> prepare("UPDATE `users` SET `balance` = balance -:price, `plan` = :planid, `planexpire` = :expire WHERE `username` = :username");
    			$UpdateBalance -> execute(array(':price' => $price, ':planid' => $planid, ':expire' => $expire, ':username' => $_SESSION['username']));
    			$InsertLog = $odb -> prepare("INSERT INTO `plan_purchases`(`id`, `user`, `plan`, `amount`, `date`) VALUES (NULL, :user, :plan, :amount, :date)");
    			$InsertLog -> execute(array(':user' => $_SESSION['username'], ':plan' => $name, ':amount' => $price,':date' => $date));
    			
          		echo json_encode(array('status'=>'success', 'message'=>'You have successfully purchased a '.$name.' plan for '.$price.'$'));
          		die();
            }
		
        }else{
			
            if(!filter_var($coupon, FILTER_SANITIZE_STRING)){
	    		$errors[] = "Invalid coupon format!";
	          	echo json_encode(array('status'=>'error', 'message'=>'Invalid coupon format!'));
	          	die();
	    	}
			$CheckCoupon = $odb -> prepare("SELECT `code`, `percent` FROM `coupons` WHERE `code` = :code AND `expire_at` > UNIX_TIMESTAMP()");
			$CheckCoupon -> execute(array(':code' => $coupon));
			$checkc = $CheckCoupon -> rowCount();
			$coupon = $CheckCoupon -> fetch(PDO::FETCH_ASSOC);
			
			
            if($checkc == 0){
				$errors[] = "Invalid coupon code.";
      			echo json_encode(array('status'=>'error', 'message'=>'Invalid coupon code.'));
      			die();	
			}else{
                $percent = $coupon['percent'];
				$discount = $price - (($price / 100) * $percent);

                if($discount > $balance){
                    $errors[] = "You do not have enought balance for this plan.";
                    echo json_encode(array('status'=>'error', 'message'=>'You do not have enought balance for this plan.'));
                    die(); 
                }else if($balance >= $discount){
                    $date = date('Y-m-d H:i:s');
    				$UpdateBalance = $odb -> prepare("UPDATE `users` SET `balance` = balance -:price, `plan` = :planid, `planexpire` = :expire WHERE `username` = :username");
        			$UpdateBalance -> execute(array(':price' => $discount, ':planid' => $planid, ':expire' => $expire, ':username' => $_SESSION['username']));
        			$InsertLog = $odb -> prepare("INSERT INTO `plan_purchases` (`id`, `user`, `plan`, `amount`, `date`) VALUES (NULL, :user, :plan, :amount, :date)");
        			$InsertLog -> execute(array(':user' => $_SESSION['username'], ':plan' => $name, ':amount' => $discount,':date' => $date));

					echo json_encode(array('status'=>'success', 'message'=>'You have successfully purchased a '.$name.' plan for '.$discount.'$'));
              		die();
                }
			}
		}
    }else{
    	header('HTTP/1.0 400 Bad Request');
			exit();
    }

?>