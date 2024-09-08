<?php
	if (!isset($_SERVER['HTTP_REFERER'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}

	require '../../../../../backend/configuration/database.php';
	require '../../../../../backend/configuration/funcsinit.php';

	if (!$user -> UserLoggedIn()){
        echo json_encode(array('status'=>'error', 'message'=>'Your session has timed out, please log in again.'));
        die();
    }

    if (!isset($_SESSION['username']) || !isset($_SESSION['id'])){
    	echo json_encode(array('status'=>'error', 'message'=>'Your session has timed out, please log in again.'));
		die();
	}else{
		echo json_encode(array('status'=>'success', 'message'=>'Your session is active.'));
	}













?>