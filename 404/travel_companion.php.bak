<?php
if ($error404 === false) return;
//结伴同游 主页
if(preg_match('/bbs_travel_companion.html/', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'bbs_travel_companion.php';
	$error404 = false;
}elseif(preg_match('/bbs_travel_companion\//', $req)){
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/^tp_([0-9_]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['TcPath'] = $_GET['TcPath'] = $m[1];
		}
		if(preg_match('/^pid_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['products_id'] = $_GET['products_id'] = $m[1];
		}
	}
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'bbs_travel_companion.php';
	$error404 = false;
	
}

//结伴同游右页面

if(preg_match('/bbs_travel_companion_rightindex.html/', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'bbs_travel_companion_rightindex.php';
	$error404 = false;
}elseif(preg_match('/bbs_travel_companion_rightindex\//', $req)){
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/^tp_([0-9_]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['TcPath'] = $_GET['TcPath'] = $m[1];
		}
		if(preg_match('/^pid_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['products_id'] = $_GET['products_id'] = $m[1];
		}
		if(preg_match('/^page_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['page'] = $_GET['page'] = $m[1];
		}
		if(preg_match('/^sort_name_(\S+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['sort_name'] = $_GET['sort_name'] = $m[1];
		}
		if(preg_match('/^cus_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['customers_id'] = $_GET['customers_id'] = $m[1];
		}
	}
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'bbs_travel_companion_rightindex.php';
	$error404 = false;
	
}

//结伴同游详细页面
if(preg_match('/bbs\//', $req)){
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/^tp_([0-9_]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['TcPath'] = $_GET['TcPath'] = $m[1];
		}
		if(preg_match('/^pid_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['products_id'] = $_GET['products_id'] = $m[1];
		}
		if(preg_match('/^page_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['page'] = $_GET['page'] = $m[1];
		}
		if(preg_match('/^cus_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['customers_id'] = $_GET['customers_id'] = $m[1];
		}
		if(preg_match('/^tid_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['t_companion_id'] = $_GET['t_companion_id'] = $m[1];
		}
	}
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'bbs_travel_companion_content.php';
	$error404 = false;
	
}

?>