<?php
//�����б�
if ($error404 === false) return;
if(preg_match('/previews(\/|\.html)/', $req) ){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'reviews.php';
	$error404 = false;
	$req_array = explode('/',$req);
	$tmp_array = explode('-',$req_array[1]);
	for($i=0; $i<sizeof($tmp_array); ($i=$i+2)){
		if($tmp_array[$i]!="" && $tmp_array[($i+1)]!=""){
			$HTTP_GET_VARS[$tmp_array[$i]] = $_GET[$tmp_array[$i]] = preg_replace('/\.html$/','',$tmp_array[($i+1)]);
		}
	}
}
?>