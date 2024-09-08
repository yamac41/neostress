<?php
    header('Content-type: application/json');

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
        $MethodDB = $odb -> query("SELECT `apiname` as methodName, `usageStats` as count FROM methods ORDER BY usageStats DESC LIMIT 15");
       

        $data = array();

        while($row = $MethodDB -> fetch(PDO::FETCH_ASSOC)){
            
            $data[] = array(
                'method' => $row['methodName'],
                'value' => $row['count']
            );
        
        }
        
        echo json_encode($data);







	}




?>