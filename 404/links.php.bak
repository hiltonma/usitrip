<?php 
if ($error404 === false) return;
//ำัว้มฌฝำ 404
if(preg_match('/hezuo(\d+).html/', $req, $m)){
	$HTTP_GET_VARS['cId'] = $_GET['cId'] = $m[1];
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'links.php';
	$error404 = false;
}else if(preg_match('/links.html/', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'links.php';
	$error404 = false;
}
?>