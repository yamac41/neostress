<?php
    require '../../backend/configuration/database.php';
    require '../../backend/configuration/funcsinit.php';

    if (!$user -> UserLoggedIn()){
        header('Location: ../login');
        exit;
    }else{
      header('Location: /dash/home');
    }



?>
