<?php
/*
$Id: contact_us.php,v 1.1.1.1 2004/03/04 23:37:58 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

$_POST['mail'] = preg_replace( "/\n/", " ", $_POST['mail'] );
$_POST['user-name'] = preg_replace( "/\n/", " ", $_POST['user-name'] );
$_POST['mail'] = preg_replace( "/\r/", " ", $_POST['mail'] );
$_POST['user-name'] = preg_replace( "/\r/", " ", $_POST['user-name'] );
$_POST['mail'] = str_replace("Content-Type:","",$_POST['mail']);
$_POST['user-name'] = str_replace("Content-Type:","",$_POST['user-name']);

// 用户邮箱
$email_address = tep_db_prepare_input($_POST['mail']);
// 用户姓名
$name = tep_db_prepare_input($HTTP_POST_VARS['user-name']);
$name = tep_db_output($name);
// 出游目的地
$to_city = tep_db_prepare_input($HTTP_POST_VARS['to_city']);
$to_city = tep_db_output($to_city);
// 出发日期
$year_month = tep_db_prepare_input($HTTP_POST_VARS['year-month']);
$year_month = tep_db_output($year_month);
//行程天数
$to_day = tep_db_prepare_input($HTTP_POST_VARS['to_day']);
$to_day = tep_db_output($to_day);
// 成人 小孩
$homan_child = tep_db_prepare_input($HTTP_POST_VARS['homan_child']);
$homan_child = tep_db_output($homan_child);
// 是否签证
$visa = tep_db_prepare_input($HTTP_POST_VARS['visa']);
$visa = tep_db_output($visa);
// 男士 女士
$gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
$gender = tep_db_output($gender);
// 电话
$telephone = tep_db_prepare_input($HTTP_POST_VARS['tel']);
$telephone = tep_db_output($telephone);
// 特别说明/其它需求
$content = tep_db_prepare_input($HTTP_POST_VARS['content']);
$content = tep_db_output($content);

$message .= "出游目的地:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$to_city)) . "\n";
$message .= "出发日期:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$year_month)) . "\n";
$message .= "行程天数:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$to_day)) . "\n";
$message .= "出游人数:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$homan_child)) . "\n";
$message .= "已有签证:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$visa)) . "\n";
$message .= "联系人:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$name)) . "\n";
$message .= "性别:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$gender)) . "\n";
$message .= "联系电话:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$telephone)) . "\n";
$message .= "特别说明:" . html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$content)) . "\n";

$name = html_to_db(iconv('utf-8',CHARSET . '//IGNORE',$name));

//$message .= "Reservation Number:" . $reservationnumber . "\n";
//$message .= $enquiry;

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
		echo db_to_html('1.验证码错误，请输入正确的验证码！');
		exit();
	}
	if (!tep_validate_email($email_address)) {
		$error = true;
		echo db_to_html('2.请输入正确的邮箱！');
		exit();
	}

	if ($error == false) {
		if (IS_LIVE_SITES === true) {
			$_mail = STORE_OWNER_EMAIL_ADDRESS;
		} else {
			//如果是测试站就把邮件发给测试本人的邮箱
			$_mail = $email_address;
		}
		$to_email_address = $_mail;//STORE_OWNER_EMAIL_ADDRESS;//'service@usitrip.com';
		//$to_email_address .= ', howard.zhou@usitrip.com';

		$message .= "\n\n".'---------------------------------------------------------'."\n".('发件人邮箱：').$email_address;
		$message .= "\n".('发送源位置：').tep_href_link('packet_group.php')."\n";

		$EMAIL_SUBJECT = db_to_html('个性化定制邮件')." ";
		tep_mail(db_to_html(STORE_OWNER . ' '), $to_email_address, $EMAIL_SUBJECT. ' ', db_to_html($message), db_to_html($name . ' '), 'automail@usitrip.com' );
		//tep_redirect(tep_href_link('packet_group.php', 'action=success'));
		echo '0.OK';
		exit();
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
$the_title = db_to_html('个性化定制-走四方旅游网');
$the_desc = db_to_html('　');
$the_key_words = db_to_html('　');
//seo信息 end

$add_div_footpage_obj = true;
$content = 'packet_group';
$breadcrumb->add(db_to_html('个性化定制'));
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
