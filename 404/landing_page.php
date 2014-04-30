<?php 
if ($error404 === false) return;
if(preg_match('/landingpage/', $req)){
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if($i==1){
			$HTTP_GET_VARS['landingpagename'] = $_GET['landingpagename'] = $req_array[$i];
			$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'landing-page.php';
			$error404 = false;
			break;
		}
	}
}
?>