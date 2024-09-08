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
            $errors[] = "Authorization token is already disabled.";
            echo json_encode(array('status'=>'error', 'message'=>'Authorization token is already disabled.'));
            die();
        }

        if(empty($errors)){
            $UpdateDB = $odb -> prepare("UPDATE `users` SET `apitoken` = :token WHERE `username` = :username AND `id` = :id");
            $UpdateDB -> execute(array(':token' => '0', ':username' => $_SESSION['username'], ':id' => $_SESSION['id']));
            echo json_encode(array('status'=>'success', 'message'=>'Authorization token successfully disabled.'));
            die();
        }


        


    }

?>