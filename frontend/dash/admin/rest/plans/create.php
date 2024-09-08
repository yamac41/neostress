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
		
		$name = htmlentities($_POST['name']);
		$price = intval($_POST['price']);
		$length = intval($_POST['length']);
		$lengthtype = htmlentities($_POST['lengthtype']);
		$pagelength = htmlentities($_POST['pagelength']);
		$concs = intval($_POST['concs']);
		$time = intval($_POST['time']);
		$premium = intval($_POST['premium']);
		$apiaccess = intval($_POST['api']);
		$private = htmlentities($_POST['private']);
		$custom = htmlentities($_POST['custom']);
		$priority = intval($_POST['priority']);

		if(empty($name) || empty($price) || empty($length) || empty($lengthtype) || empty($pagelength) || empty($concs) || empty($time) || empty($private) || empty($custom)){
			$errors[] = "Please fill all required fields.";
          	echo json_encode(array('status'=>'error', 'message'=>'Please fill all required fields.'));
          	die();
		}

		$lengthtypes = array('Days', 'Weeks', 'Months', 'Years');
  		if(!in_array($lengthtype, $lengthtypes)){
      		$errors[] = "Invalid length type!";
          	echo json_encode(array('status'=>'error', 'message'=>'Invalid length type.'));
          	die();
    	}


    	if(empty($errors)){
    		$SQLInsert = $odb -> prepare("INSERT INTO `plans`(`id`, `name`, `premium`, `time`, `concs`, `length`, `lengthtype`, `pagelength`, `private`, `apiaccess`, `supportprio`, `price`, `custom`) VALUES (NULL, :name, :premium, :time, :concs, :length, :lengthtype, :pagelength, :private, :apiaccess, :supportprio, :price, :custom)");
    		$SQLInsert -> execute(array(':name' => $name, ':premium' => $premium, ':time' => $time, ':concs' => $concs, ':length' => $length, ':lengthtype' => $lengthtype, ':pagelength' => $pagelength, ':private' => $private, ':apiaccess' => $apiaccess, ':supportprio' => $priority, ':price' => $price, ':custom' => $custom));
    		echo json_encode(array('status'=>'success', 'message'=>'Plan successfully created.'));
          	die();

    	}











	}

?>