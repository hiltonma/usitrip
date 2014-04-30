<?php
//นฅยิ
if ($error404 === false)
	return;
if (preg_match('/raiders(.*)\.html/', $req)) {
	$error404 = false;
	$arr_tmp = explode('__', $req);
	if (count($arr_tmp) > 1) {
		$page_name = $arr_tmp[0];
		$parameters = $arr_tmp[1];
		$parameters = str_replace('_usi_', '=', $parameters);
		$parameters = str_replace('_trip_', '&', $parameters);
		$parameters = str_replace('.html', '', $parameters);
		$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = $page_name . '.php';
		parse_str($parameters, $_GET);
		parse_str($parameters, $HTTP_GET_VARS);
	} else {
		$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = str_replace('.html', '.php', $req);
	}
}
if ($error404 === false)
	return;
if (preg_match('/rl(.*)\.html/', $req) || preg_match('/ri(.*)\.html/', $req)) {
	$error404 = false;
	$arr_tmp = explode('_z', $req);
	if (count($arr_tmp) > 1) {
		$page_name = $arr_tmp[0];
		$parameters = $arr_tmp[1];
		$parameters = str_replace('.html', '', $parameters);
		$parameters = str_replace('_u', 'parent_id=', $parameters);
		$parameters = str_replace('_s', 'type_id=', $parameters);
		$parameters = str_replace('_r', 'article_id=', $parameters);
		$parameters = str_replace('_w', 'page=', $parameters);
		$parameters = str_replace('_t', '&', $parameters);
		$page_name = str_replace('rl', 'raiders_list', $page_name);
		$page_name = str_replace('ri', 'raiders_info', $page_name);
		$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = $page_name . '.php';
		parse_str($parameters, $_GET);
		parse_str($parameters, $HTTP_GET_VARS);
	} else {
		$req = str_replace('rl', 'raiders_list', $req);
		$req = str_replace('ri', 'raiders_info', $req);
		$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = str_replace('.html', '.php', $req);
	}
}
?>