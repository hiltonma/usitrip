<?php
require_once('includes/application_top.php');

  if (!(int)$customer_id || !(int)$affiliate_id) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

if($action=='inputcode'){
	//echo '输入验证码！';
	$error = false;
	$validation_result = false;
	if(!tep_not_null($affiliate_email_address_verifi_code)){
		$error = true;
		$messageStack->add('validation',db_to_html('请输入验证码！'));
	}
	if($error == false){
		$sql = tep_db_query('SELECT affiliate_id, affiliate_email_address_verified FROM `affiliate_affiliate` WHERE affiliate_email_address_verifi_code="'.tep_db_input(tep_db_prepare_input($affiliate_email_address_verifi_code)).'" AND affiliate_id="'.(int)$affiliate_id.'" ');
		$row = tep_db_fetch_array($sql);
		if((int)$row['affiliate_id']){
			if(!(int)$row['affiliate_email_address_verified']){
				tep_db_query("UPDATE `affiliate_affiliate` SET `affiliate_email_address_verified` = '1' WHERE `affiliate_id` = '".(int)$affiliate_id."' LIMIT 1 ;");
				$messageStack->add('validation',db_to_html('邮箱验证成功！'),'success');
								
				$validation_result = true;
			}else{
				$validation_result = true;
				$messageStack->add('validation',db_to_html('您的帐号已经通过验证！请勿重复验证。'),'success');
			}
		}else{
			$error = true;
			$messageStack->add('validation',db_to_html('您输入的验证码错误！'));
		}
	}
}

if($action=='resend'){
	//echo '重新发送验证码！';
	$affiliate_email_address = tep_get_affiliate_email_address($affiliate_id);
	$re_send = send_affiliate_validation_mail($affiliate_email_address);
	if((int)$re_send){
		$messageStack->add('validation',db_to_html('邮箱验证邮件发送成功，请几分钟后到您的邮箱'.$affiliate_email_address.'查收验证码邮件！'),'success');
	}
}

$breadcrumb->add(db_to_html('邮箱验证'));
$content = 'affiliate_validation';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>