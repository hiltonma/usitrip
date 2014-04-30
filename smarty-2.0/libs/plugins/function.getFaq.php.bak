<?php 
function smarty_function_getFaq($params, &$smarty)
{
	$mod = $params['mod'];
	$uid = intval($params['uid']);
	$name = $params['name'];
	!$name && $name='tempData';
	if($mod == 'answersnum'){
		//$query = tep_db_get_one("SELECT count(`a_id`) as rows FROM `experts_answers` WHERE `uid` ='".$uid."' ");
		$query = tep_db_get_one("SELECT count(`q_id`) as rows FROM `experts_question` WHERE uid ='".$uid."' and q_answered='1' ");
		$$name = (int)$query['rows'];
	}elseif($mod == 'noanswersnum'){
		$query = tep_db_get_one("SELECT count(`q_id`) as rows FROM `experts_question` WHERE uid ='".$uid."' and q_answered='0' ");
		$$name = (int)$query['rows'];
	}
	$smarty->assign($name,$$name);
	return '';
}
?>