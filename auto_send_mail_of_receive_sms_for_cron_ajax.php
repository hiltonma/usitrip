<?php
/**
 * @author yichi.sun@usitrip.com
 * @time 2011-08-18
 */
require('includes/application_top.php');
ini_set("max_execution_time", 7200);
set_time_limit(0);

if(!IS_LIVE_SITES){
	echo "非生产站，不获取上行短信！";
	exit;
}

echo "把用户回复的短信发送到客服部邮箱service@usitrip.com"."\n\n";

!isset($GLOBALS['tmp_sms']) && $GLOBALS['tmp_sms'] = new cpunc_SMS;
$sms = $GLOBALS['tmp_sms'];
$moResult = $sms->getMO();
foreach($moResult as $mo){
	//$mo 是位于 cpunc_sms.php 里的 Mo 对象 	
 	$to_name = STORE_OWNER;
 	$to_email_address = STORE_OWNER_EMAIL_ADDRESS;
 	$sms_phone = trim($mo->getMobileNumber());
 	$email_subject = '用户'.$sms_phone.'回复的短信息 ';
 	$sms_content = iconv("UTF-8","GB2312",$mo->getSmsContent());
 	$sms_time = $mo->getSentTime(); //格式如：20110819094119
	$sms_time = date('Y-m-d H:i:s', strtotime($sms_time));
 	$email_text = '';
 	$email_text .= 'Dear ' . STORE_OWNER . "\n\n";
	$email_text .= '&nbsp;&nbsp;&nbsp;&nbsp;用户回复的短信信息如下：' . "\n" .
				   '&nbsp;&nbsp;&nbsp;&nbsp;发送人手机号：' . $sms_phone . "\n" .
				   '&nbsp;&nbsp;&nbsp;&nbsp;发送时间：' . $sms_time . "\n" .
				   '&nbsp;&nbsp;&nbsp;&nbsp;发送内容：' . $sms_content . "\n\n";
	
	$finance_phone = array('18982065453', '13808031192');
 	if(in_array($sms_phone, $finance_phone)){
 		$email_subject = 'From会计：国内银行'.$sms_time.'到账通知'.' ';
 		$email_text = $sms_content.' ';
 	}
 	
 	$from_email_name = STORE_OWNER;
 	$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
 	$mail_isNoReplay = true;
 	if(function_exists('tep_mail')){
 		tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', 'gb2312');
 	}
 	else{
 		echo "邮件没有发送成功！"."\n\n";
 	}
 	$sms->saveSMS($sms_phone, $sms_content, 0, 'b2m.cn-receive', $sms_time);
 	echo "用户 ".$sms_phone." 回复的短信：".$sms_content."\n\n";
 	//$sms->saveSMS('13880695761', '测试一下发送邮件', 0, 'b2m.cn-receive', date('Y-m-d H:i:s'));
}

echo "Done";
?>