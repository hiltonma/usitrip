<?php
/**
 * 国际短信hi8d的数据返回页
 */
//引用我们的数据库操作函数
require('includes/application_top.php');
// 引用发送信类
require('includes/classes/ensms.php');
try {
// 直实的发送地址
	$a = new ensms('usitrip','a63b4be2106e3128057cae3ab7a6e2e4','http://www.sms01.com/ensms/servlet/WebSend','http://www.sms01.com/ensms/servlet/BalanceService',true);	
	// 如果是接收发送状态的页面，则判断是否有返回数据
	if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
		// 记录返回的发送状态
		$a->checkMsg($GLOBALS['HTTP_RAW_POST_DATA']);
	}
}catch (Exception $e) {
	echo $e->getMessage();
}
echo 1;
?>