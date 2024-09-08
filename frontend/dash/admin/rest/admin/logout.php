<?php
	session_start();
	unset($_SESSION['loggedin']);
	unset($_SESSION['username']);
	unset($_SESSION['id']);
	unset($_SESSION['email']);
	unset($_SESSION['rank']);

	session_destroy();

	header('location: ../../../../login');
	
?>