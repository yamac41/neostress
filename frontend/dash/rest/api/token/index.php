<?php

    if (!isset($_SERVER['HTTP_REFERER'])) {
        header('HTTP/1.0 404 Not Found');
        exit();
    }

    require '../../../../../backend/configuration/database.php';
    require '../../../../../backend/configuration/funcsinit.php';


    if (!$user->UserLoggedIn() || !$user->notBanned($odb)) {
        header('HTTP/1.0 404 Not Found');
        exit();
    }


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        

        $SelectAPI = $odb -> prepare("SELECT `apitoken` FROM `users` WHERE `username` = :username AND `id` = :id");
        $SelectAPI -> execute(array(':username' => $_SESSION['username'], ':id' => $_SESSION['id']));
        $apitoken = $SelectAPI -> fetchColumn(0);

        if($apitoken == '0'){
            $token = 'You do not have authorization token generated';
            echo json_encode(array('status'=>'error', 'message'=>$token));
            die();
        }else{
            $token = 'https://api.stresse.guru?token='.$apitoken.'&host=[host]&port=[port]&time=[time]&method=[method]'; 
            echo json_encode(array('status'=>'success', 'message'=>$token));
            die();
            
        }
    }
?>