<?php 
function smarty_function_getAffiliate($params, &$smarty)
{
	$uid = intval($params['uid']);
	$name = $params['name'];
	!$name && $name='tempData';
	
	$orderNum = getProducts_OrderNum($uid);
	$payment = getProducts_OrderPayment($uid);
	
	$$name = array('orderNum'=>$orderNum,'payment'=>$payment);
	
	$smarty->assign($name,$$name);
	return '';
}
?>