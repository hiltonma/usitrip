<?php
if ($error404 === false) return;
//新结伴同游 主页 列表页

//详细页面
if(preg_match('/jiebantongyou-content/', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'new_bbs_travel_companion_content.php';
	$error404 = false;
	$req_array = explode('/',$req);
	$tmp_array = explode('-',$req_array[1]);
	for($i=0; $i<sizeof($tmp_array); ($i=$i+2)){
		if($tmp_array[$i]!="" && $tmp_array[($i+1)]!=""){
			if($tmp_array[$i]=="tci"){
				$HTTP_GET_VARS['t_companion_id'] = $_GET['t_companion_id'] = preg_replace('/\.html$/','',$tmp_array[($i+1)]);
			}
			if($tmp_array[$i]=="tcp"){
				$HTTP_GET_VARS['TcPath'] = $_GET['TcPath'] = preg_replace('/\.html$/','',$tmp_array[($i+1)]);
			}
		}
	}
	//unset($_GET['travel']);
	//unset($HTTP_GET_VARS['travel']);
}elseif(preg_match('/^jiebantongyou\//', $req)){ // 主页 列表页
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'new_travel_companion_index.php';
	$error404 = false;
	$req_array = explode('/',$req);
	$tmp_array = explode('-',$req_array[1]);
	for($i=0; $i<sizeof($tmp_array); ($i=$i+2)){
		if($tmp_array[$i]!="" && $tmp_array[($i+1)]!=""){
			$get_val = rawurldecode(preg_replace('/\.html$/','',$tmp_array[($i+1)]));
			$HTTP_GET_VARS[$tmp_array[$i]] = $_GET[$tmp_array[$i]] = $get_val;
		}
	}
	unset($_GET['travel']);
	unset($HTTP_GET_VARS['travel']);
}

?>