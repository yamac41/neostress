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
        if(!isset($_POST['addon'])) {
            die(json_encode(array("error" => "Invalid addon entered!")));
            return;
        }

        $addonID = intval($_POST['addon']);
      
        if($addonID < 0 || $addonID > 3) {
            die(json_encode(array("error" => "Invalid addon entered!")));
            return;
        }
      
        $addonsPrices = array(10, 10, 15, 15);
        $price = $addonsPrices[$addonID];

        $UserAccount = $odb -> prepare("SELECT * FROM `users` WHERE `username` = :user AND `id` = :id");
        $UserAccount -> execute(array(':user' => $_SESSION['username'], ':id' => $_SESSION['id']));
        $account = $UserAccount->fetch(PDO::FETCH_ASSOC);

        if(intval($account['balance']) < intval($price)) {
            $__price = intval($price);
            $__userAccountBalance = intval($account['balance']);
            $__missing = ($__price - $__userAccountBalance);
            return die(json_encode(array("error" => "You don't have sufficient money! (missing $".$__missing.")")));
        }

        $currentAddons = json_decode($account['paidAddons'], true);
        array_push($currentAddons, array("type" => $addonID, "expire" => (time() + (86400 * 30))));

        $updateUser = $odb -> prepare("UPDATE `users` SET `balance` = `balance` - :price, `paidAddons` = :newAddons WHERE id = :userID");
        $updateUser->execute(array(':price' => $price, ':newAddons' => json_encode($currentAddons), ':userID' => $account["id"]));

        die(json_encode(array("message" => "Addon has been activated!")));
    } else {
    	header('HTTP/1.0 400 Bad Request');
		exit();
    }
?>
