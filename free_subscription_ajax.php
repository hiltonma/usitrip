<?php
require_once("includes/application_top.php");
$error = false;

if(tep_validate_email($_POST['email_address']) == false){
	$error = true;
	$messageStack->add('free_sub', 'your mail is error.');
}

if($error == false){
	//check db
	write_subscribe_email_address($_POST['email_address']);	
	$messageStack->add('free_sub', NEWS_LETTER_EMAIL_SUBMIT_OK, 'success');
}

if ($messageStack->size('free_sub') > 0) {
	//echo iconv(CHARSET,'utf-8'.'//IGNORE',$messageStack->output('free_sub'));
	echo $messageStack->output('free_sub');
}
?>