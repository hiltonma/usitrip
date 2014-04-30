<?php
/*
  $Id: contact_us.php,v 1.1.1.1 2004/03/04 23:37:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

	$_POST['email'] = preg_replace( "/\n/", " ", $_POST['email'] );
	$_POST['name'] = preg_replace( "/\n/", " ", $_POST['name'] );
	$_POST['email'] = preg_replace( "/\r/", " ", $_POST['email'] );
	$_POST['name'] = preg_replace( "/\r/", " ", $_POST['name'] );
	$_POST['email'] = str_replace("Content-Type:","",$_POST['email']);
	$_POST['name'] = str_replace("Content-Type:","",$_POST['name']);

    $email_address = tep_db_prepare_input($_POST['email']);
	$name = tep_db_prepare_input($HTTP_POST_VARS['name']);
	$name = tep_db_output($name);
	$tourname = tep_db_prepare_input($HTTP_POST_VARS['tourname']);
    $tourname = tep_db_output($tourname);
    $tourcode = tep_db_prepare_input($HTTP_POST_VARS['tourcode']);
	$tourcode = tep_db_output($tourcode);
    $reservationnumber = tep_db_prepare_input($HTTP_POST_VARS['reservationnumber']);
    $reservationnumber = tep_db_output($reservationnumber);
	$enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);
    $enquiry = tep_db_output($enquiry);
	
	$message = "Tour Name:" . $tourname . "\n";
	$message .= "Tour Code:" . $tourcode . "\n";
	$message .= "Reservation Number:" . $reservationnumber . "\n";
	$message .= $enquiry;

	//vincent 检查图形验证码 vvc
	if (isset($_GET['action']) && ($_GET['action'] == 'validate')) {
		if($_GET['validator'] == 'vvc'){
			if($_GET['data']!='' && strtolower($_GET['data'])==strtolower($_SESSION['captcha_key'])){
				echo general_to_ajax_string("0,ok");
			}else {
				echo general_to_ajax_string(db_to_html("1,验证码错误,如果看不清楚请点击图片换一张。")); //.$_GET['data']." = ".$_SESSION['captcha_key']
			}
		}
		exit();
	}
	
	  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send') ) {
		
		$error = false;
		if( strtolower($_POST['visual_verify_code'])!=strtolower($_SESSION['captcha_key'])){
			$error = true;
			$messageStack->add('contact', db_to_html('验证码错误，请输入正确的验证码！'),'error','verify_vvc');
		}
		if (!tep_validate_email($email_address)) {
			$error = true;
			$messageStack->add('contact', db_to_html('请输入正确的邮箱！'),'error','verify_vvc');
		}
		if ($enquiry == ''){
			$error = true;
			$messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
			$enquiry = "";
			$name = "";
			$email = "";
		}
		
		if ($error == false) {
		  $to_email_address = STORE_OWNER_EMAIL_ADDRESS;//'service@usitrip.com';
		  //$to_email_address .= ', howard.zhou@usitrip.com';
		  
		  $message .= "\n\n".'---------------------------------------------------------'."\n".db_to_html('发件人邮箱：').$email_address;
		  $message .= "\n".db_to_html('发送源位置：').tep_href_link(FILENAME_CONTACT_US)."\n";
	
		  tep_mail(STORE_OWNER, $to_email_address, EMAIL_SUBJECT, $message, $name, 'automail@usitrip.com' );
		  tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
		}
	 
	  } else {
		  $enquiry = "";
		  $name = "";
		  $email = "";
	
	}

if (isset($_GET['action']) && ($_GET['action'] == 'updateVVC')) {//更换验证码	
	$RandomStr = md5(microtime());// md5 to generate the random string										
	$_SESSION['captcha_key'] = substr($RandomStr,0,4);//trim 5 digit
	$RandomImg = 'php_captcha.php?code='. base64_encode($_SESSION['captcha_key']);
	echo $RandomImg;
	exit;
}
  //验证码
	$RandomStr = md5(microtime());// md5 to generate the random string										
	$_SESSION['captcha_key'] = substr($RandomStr,0,4);//trim 5 digit
	$RandomImg = 'php_captcha.php?code='. base64_encode($_SESSION['captcha_key']);
	
  
  $validation_include_js = 'true';

	//seo信息
	$the_title = db_to_html('联系我们-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $add_div_footpage_obj = true;
  $content = CONTENT_CONTACT_US;
	$breadcrumb->add(db_to_html('联系我们'), 'contact_us.php');
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
