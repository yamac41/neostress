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

        $CheckAPI = $odb -> prepare("SELECT `plans`.`apiaccess` + `users`.`apiaccess` FROM `plans`,`users` WHERE `users`.`plan` = `plans`.`id` AND `users`.`id` = :userid AND `users`.`username` = :username");
        $CheckAPI -> execute(array(':userid' => $_SESSION['id'], ':username' => $_SESSION['username']));
        $apiaccess = $CheckAPI -> fetchColumn(0);

        if($apiaccess == '0'){
          $errors[] = "You do not have access to use API.";
          echo json_encode(array('status'=>'error', 'message'=>'You do not have API access!'));
          die(); 
        }


        function generateRandom($digits) { 
            $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $rand_str = '';
            while(strlen($rand_str) < $digits)
            $rand_str .= substr(str_shuffle($charset), 0, 1);
            return $rand_str; 
        }

        if(empty($errors)){
            $UpdateDB = $odb -> prepare("UPDATE `users` SET `apitoken` = :token WHERE `username` = :username AND `id` = :id");
            $UpdateDB -> execute(array(':token' => generateRandom(22), ':username' => $_SESSION['username'], ':id' => $_SESSION['id']));
            echo json_encode(array('status'=>'success', 'message'=>'Authorization token successfully generated.'));
            die();
        }


        


    }else{
        header('HTTP/1.0 404 Not Found');
        exit();
    }

?>