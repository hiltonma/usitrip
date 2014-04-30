<?php 
function smarty_function_getWritings($params, &$smarty)
{
	$mod = $params['mod'];
	$uid = intval($params['uid']);
	$name = $params['name'];
	!$name && $name='tempData';
	
	$expertself = $params['expertself']===true?true:false;
	
	if($mod == 'type'){
		if($uid){
			$$name = getWritingsType($uid);
		}
	}elseif($mod == 'writingsnum'){
		$sql = "SELECT count(`aid`) as rows FROM `experts_writings` WHERE `uid` ='".$uid."' and is_draft!='1'";
		if(!$expertself){
			$sql .= " and `is_draft`='0'";	
		}
		$query = tep_db_get_one($sql);
		$$name = $query['rows'];
	}
	$smarty->assign($name,$$name);
	return '';
}
?>