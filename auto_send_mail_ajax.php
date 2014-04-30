<?php
require_once('includes/application_top.php');
ini_set("max_execution_time", 7200);
set_time_limit(0);

//echo '<pre>';
//print_r($_SESSION['need_send_email']); exit;
//echo '</pre>';
//exit;

if(!tep_not_null($_SESSION['need_send_email'])){ die();}

for($i=0; $i<count($_SESSION['need_send_email']); $i++){

	$to_name[$i] = $_SESSION['need_send_email'][$i]['to_name'] ;
	$to_email_address[$i] = $_SESSION['need_send_email'][$i]['to_email_address'] ;
	$email_subject[$i] = $_SESSION['need_send_email'][$i]['email_subject'] ;
	$email_text[$i] = $_SESSION['need_send_email'][$i]['email_text'] ;
	$from_email_name[$i] = $_SESSION['need_send_email'][$i]['from_email_name'] ;
	$from_email_address[$i] = $_SESSION['need_send_email'][$i]['from_email_address'] ;
	$action_type[$i] = $_SESSION['need_send_email'][$i]['action_type'] ;
	$email_charset[$i] = $_SESSION['need_send_email'][$i]['email_charset'] ;
}
$_SESSION['need_send_email'] = array();

//为了避免用户同时点击不同网页而出现重复发送邮件的bug才决定先去掉session值才发邮件。

$_SESSION['need_send_email_confirmed'] = array();
for($i=0; $i<count($to_email_address); $i++){
	$_SESSION['need_send_email_confirmed'][$i]['to_name'] = $to_name[$i];
	$_SESSION['need_send_email_confirmed'][$i]['to_email_address'] = $to_email_address[$i];
	$_SESSION['need_send_email_confirmed'][$i]['email_subject'] = $email_subject[$i];
	$_SESSION['need_send_email_confirmed'][$i]['email_text'] = $email_text[$i];
	$_SESSION['need_send_email_confirmed'][$i]['from_email_name'] = $from_email_name[$i];
	$_SESSION['need_send_email_confirmed'][$i]['from_email_address'] = $from_email_address[$i];
	$_SESSION['need_send_email_confirmed'][$i]['action_type'] = $action_type[$i];
	$_SESSION['need_send_email_confirmed'][$i]['email_charset'] = $email_charset[$i];
	
	echo '[ACTION]SUCCESS[/ACTION]'.$to_email_address[$i];
}
echo '[ACTION]SUCCESS[/ACTION]';
//tep_redirect(tep_href_link('auto_send_mail_ajax_confirmed.php', '', 'SSL'));
?>