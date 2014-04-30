<?php 
require_once('includes/application_top.php');

function ajax_str($str){
	global $include;
	if($include!=true){
		return iconv(CHARSET,'utf-8'.'//IGNORE',$str);
	}else{
		return $str;
	}
}

if (!tep_session_is_registered('customer_id')) {
	echo ajax_str('您需要登录后才能添加好友！');
	exit;
}


if((int)$_GET['customers_id'] && !check_friend($customer_id,$_GET['customers_id'])){
	tep_db_query("INSERT INTO `friends_list` ( `f0_customers_id` , `f1_customers_id` , `f_date` )VALUES ($customer_id, {$_GET['customers_id']}, NOW());");
	echo ajax_str('好友添加成功。');
}else{
	echo ajax_str('他已经是您的好友了。');
}
?>