<?php
	
	require 'database.php';
	require 'funcsinit.php';

	$CheckExpire = $odb -> prepare("SELECT * FROM `users` WHERE `planexpire` < UNIX_TIMESTAMP()");
	$CheckExpire -> execute();

	$count = $CheckExpire -> rowCount();
	while($user = $CheckExpire -> fetch(PDO::FETCH_ASSOC)){
		$UpdateDB = $odb -> prepare("UPDATE `users` SET `plan` = 0, `planexpire` = 1767283020, `addon_concs` = 0, `addon_time` = 0, `addon_blacklist` = 0, `apiaccess` = 0, `apitoken` = 0 WHERE `id` = :id");
		$UpdateDB -> execute(array(':id' => $user['id']));

	}
	echo $count;




	













?>