<?php
require_once('includes/application_top.php');
if(!tep_not_null($_SESSION['need_send_email_confirmed'])){ die();}
ini_set("max_execution_time", 7200);
set_time_limit(0);

header("HTTP/1.0 200 OK");
header("Status: 200 OK");

for($i=0; $i<count($_SESSION['need_send_email_confirmed']); $i++){

	if($_SESSION['need_send_email_confirmed'][$i]['email_charset']!=""){
		$email_charset = $_SESSION['need_send_email_confirmed'][$i]['email_charset'];
	}else{
		$email_charset = CHARSET;
		//unset($email_charset);
	}
	
	$to_name[$i] = $_SESSION['need_send_email_confirmed'][$i]['to_name'] ;
	$to_email_address[$i] = $_SESSION['need_send_email_confirmed'][$i]['to_email_address'] ;
	$email_subject[$i] = $_SESSION['need_send_email_confirmed'][$i]['email_subject'] ;
	$email_text[$i] = $_SESSION['need_send_email_confirmed'][$i]['email_text'] ;
	//$email_text[$i] .= ."\n tid:".date('YmdHis');
	
	$from_email_name[$i] = $_SESSION['need_send_email_confirmed'][$i]['from_email_name'] ;
	$from_email_address[$i] = $_SESSION['need_send_email_confirmed'][$i]['from_email_address'] ;
	$action_type[$i] = $_SESSION['need_send_email_confirmed'][$i]['action_type'] ;
	
	//tep_mail($to_name[$i], $to_email_address[$i], $email_subject[$i], $email_text[$i], $from_email_name[$i], $from_email_address[$i], $action_type[$i], $email_charset);
	//echo "sned ".$to_email_address[$i]." Done. \n";
}

$_SESSION['need_send_email_confirmed'] = array();

for($i=0; $i<count($to_email_address); $i++){
	if(tep_not_null($to_email_address[$i])){
		tep_mail($to_name[$i], $to_email_address[$i], $email_subject[$i], $email_text[$i], $from_email_name[$i], $from_email_address[$i], $action_type[$i], $email_charset);
		$rtn .= "sned ".$to_email_address[$i]." Done. " . '\n';
		sleep(5);
	}
}
//echo '[JS]alert("' . $rtn . '")[/JS]';
echo $rtn; 
print_r($_SESSION['need_send_email_confirmed']);
exit;
?>