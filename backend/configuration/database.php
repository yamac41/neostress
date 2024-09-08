<?php

date_default_timezone_set('Europe/Amsterdam');
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'king');
define('DB_USERNAME', 'dev');
define('DB_PASSWORD', 'w[vHQ/6Z6');
define('ERROR_MESSAGE', '<title>Problem while connecting to database</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<style>
  html, body { padding: 0; margin: 0; width: 100%; height: 100%; }
  * {box-sizing: border-box;}
  body { text-align: center; padding: 0; background: #18191C; color: #fff; font-family: Open Sans; }
  h1 { font-size: 50px; font-weight: 100; text-align: center;}
  body { font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center; -webkit-box-align: center; -ms-flex-align: center; align-items: center;}
  article { display: block; width: 700px; padding: 50px; margin: 0 auto; }
  a { color: #fff; font-weight: bold;}
  a:hover { text-decoration: none; }
  img { height: 150px; margin-top: 1em; }
</style>

<article>
   	<img src="" />
    <h1>Problem while connecting to database</h1>
    <div>
        <p>Verify that the database is configured correctly!</p>
        <p>&mdash; change.it</p>
    </div>
</article>');

	try {
		global $odb;
		$odb = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME.';charset=utf8', DB_USERNAME, DB_PASSWORD);
		$odb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch( PDOException $Exception ) {
		//error_log('ERROR: '.$Exception->getMessage().' - '.$_SERVER['REQUEST_URI'].' u '.date('l jS \of F, Y, h:i:s A')."\n", 3, 'errors.log');
		//die(ERROR_MESSAGE);
		die('<title>Problem while connecting to database</title>
			<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
			<style>
			  html, body { padding: 0; margin: 0; width: 100%; height: 100%; }
			  * {box-sizing: border-box;}
			  body { text-align: center; padding: 0; background: #18191C; color: #fff; font-family: Open Sans; }
			  h1 { font-size: 50px; font-weight: 100; text-align: center;}
			  body { font-family: Open Sans; font-weight: 100; font-size: 20px; color: #fff; text-align: center; display: -webkit-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center; -webkit-box-align: center; -ms-flex-align: center; align-items: center;}
			  article { display: block; width: 700px; padding: 50px; margin: 0 auto; }
			  a { color: #fff; font-weight: bold;}
			  a:hover { text-decoration: none; }
			  img { height: 150px; margin-top: 1em; }
			</style>

			<article>
			   	<img src="https://i.ibb.co/tBPPmfK/vnmcropped.png" />
			    <h1>Problem while connecting to database</h1>
			    <div>
			        <p>'.$Exception->getMessage().'</p>
			        <p>&mdash; change.it</p>
			    </div>
			</article>');
		echo $Exception->getMessage();
	}
	function error($string){  
		return '<div id="alert-2" x-data="{ show: true }" x-show="show" class="flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200" role="alert"> <svg class="flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">'.$string.'</div> <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300" data-dismiss-target="#alert-box" aria-label="Close" @click="show = false"> <span class="sr-only">Close</span> <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> </button> </div>';
	}

	function success($string) {
		return '<div id="alert-3" x-data="{ show: true }" x-show="show" class="flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200" role="alert"> <svg class="flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">'.$string.'</div> <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300" data-dismiss-target="#alert-3" aria-label="Close" @click="show = false"> <span class="sr-only">Close</span> <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> </button></div>';
	}
	
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// ini_set( 'session.use_only_cookies', 1);                
// ini_set( 'session.use_trans_sid', 0);
// error_reporting(E_ALL);

$DBSettings = $odb -> query("SELECT * FROM `settings`");
$setting = $DBSettings -> fetch(PDO::FETCH_ASSOC);

require('waf.php');
$aWAF = new aWAF();

$aWAF->useCloudflare();
$aWAF->antiCookieSteal('username');

$aWAF->checkGET();
$aWAF->checkPOST();
$aWAF->checkCOOKIE();

$aWAF->start();

?>
