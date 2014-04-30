<?php
if ($error404 === false) return;
//客户咨询页列表
if(preg_match('/all_question_answers/', $req)){
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/^page_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['page'] = $_GET['page'] = $m[1];
		}
		if(preg_match('/^tabid_([0-9]+)/',$req_array[$i], $m)){
			$HTTP_GET_VARS['tabid'] = $_GET['tabid'] = $m[1];
		}
	}
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'all_question_answers.php';
	$error404 = false;
}

?>