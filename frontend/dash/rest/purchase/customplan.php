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

        $premium = intval($_POST['premium']);
        $api = intval($_POST['api']);
        $time = intval($_POST['time']);
        $concs = intval($_POST['concs']);
        $period = intval($_POST['period']);

        $updatedtime = $time * 300;

        if(empty($time) || empty($concs) || empty($period)){
            $errors[] = "There is problem with ordering custom plan!";
            echo json_encode(array('status'=>'error', 'message'=>'There is problem with ordering custom plan!'));
            die(); 
        }

        if($premium < 0 || $api < 0 || $time < 0 || $concs < 0 || $period < 0 || $period > 12 || $concs > 30 || $time > 12 || $premium > 1 || $api > 1){
            $errors[] = "Invalid value!";
            echo json_encode(array('status'=>'error', 'message'=>'Invalid value!'));
            die();
            return;
        }

        if(!isset($premium) || !isset($api)){
            $errors[] = "There is problem with ordering custom plan!";
            echo json_encode(array('status'=>'error', 'message'=>'There is problem with ordering custom plan!'));
            die(); 
        }

        if(empty($errors)){

            $UserBalance = $odb -> prepare("SELECT `balance` FROM `users` WHERE `username` = :user AND `id` = :id");
            $UserBalance -> execute(array(':user' => $_SESSION['username'], ':id' => $_SESSION['id']));
            $balance = $UserBalance -> fetchColumn(0);

            $calculatedprice = ((($time * 6))+(($concs * 11))+($premium * 15)+($api * 20)) * $period;

            if($calculatedprice > $balance){
                $errors[] = "You do not have enought balance";
                echo json_encode(array('status'=>'error', 'message'=>'You do not have enought balance!'));
                die(); 
            }else if($balance >= $calculatedprice){

                $expiration = strtotime("+{$period} month", time());
                $date = date('Y-m-d H:i:s');
                
                $CheckPlan = $odb -> prepare("SELECT COUNT(*) FROM `plans` WHERE `name` = :username");
                $CheckPlan -> execute(array(':username' => ''.$_SESSION['username'].' Custom'));
                $countplan = $CheckPlan -> fetchColumn(0);

                if($countplan > 0){
                    $DeletePlan = $odb -> prepare("DELETE FROM `plans` WHERE `name` = :username");
                    $DeletePlan -> execute(array(':username' => ''.$_SESSION['username'].' Custom'));
                }
                
                $CreateCustomPlan = $odb -> prepare("INSERT INTO `plans`(`id`, `name`, `premium`, `time`, `concs`, `length`, `lengthtype`, `pagelength`, `private`, `apiaccess`, `supportprio`, `price`, `custom`) VALUES (NULL, :username, :premium, :time, :concs, :length, 'Months', 'month', 'yes', :apiaccess, '1', :price, 'yes')");
                $CreateCustomPlan -> execute(array(':username' => ''.$_SESSION['username'].' Custom', ':premium' => $premium, ':time' => $updatedtime, ':concs' => $concs, ':length' => $period, ':apiaccess' => $api, ':price' => $calculatedprice));

                $PlanIDDB = $odb -> prepare("SELECT `id` FROM `plans` WHERE `name` = :username");
                $PlanIDDB -> execute(array(':username' => ''.$_SESSION['username'].' Custom'));
                $planid = $PlanIDDB -> fetchColumn(0);

                $SetUserPlan = $odb -> prepare("UPDATE `users` SET `plan` = :planid, `planexpire` = :expiration, `balance` = `balance` - :price WHERE `id` = :userID");
                $SetUserPlan -> execute(array('userID' => $_SESSION['id'], ':planid' => $planid, ':expiration' => $expiration, ':price' => $calculatedprice)); 

                $InsertLog = $odb -> prepare("INSERT INTO `plan_purchases`(`id`, `user`, `plan`, `amount`, `date`) VALUES (NULL, :user, :plan, :amount, :date)");
    			$InsertLog -> execute(array(':user' => $_SESSION['username'], ':plan' => ''.$_SESSION['username'].' Custom', ':amount' => $calculatedprice,':date' => $date));
                
                echo json_encode(array('status'=>'success', 'message'=>'You have successfully purchased custom plan for '.$calculatedprice.'$'));
                die(); 
            }
        }
    } else {
    	header('HTTP/1.0 400 Bad Request');
		exit();
    }
?>
