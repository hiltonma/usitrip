<?php
/*
$Id: password_forgotten.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);

if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
	if(tep_db_prepare_input($_POST['email_sms_post'])!=''){
		switch (tep_db_prepare_input($_POST['email_sms_post'])){
			case 'email':$email_address = tep_db_prepare_input($_POST['email_sms_input']);$check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
			break;
			case 'sms': $confirmphone = tep_db_prepare_input($_POST['email_sms_input']);$check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where confirmphone = '" .tep_db_input($confirmphone) . "'");
			break;
		}
	}else{
		exit;
	}

	//$check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
	if (tep_db_num_rows($check_customer_query)) {
		$check_customer = tep_db_fetch_array($check_customer_query);

		$new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
		$crypted_password = tep_encrypt_password($new_password);

		tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_db_input($crypted_password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");

		$SUCCESS_PASSWORD_SENT = SUCCESS_PASSWORD_SENT;
		//howard added new eamil tpl
		if(tep_db_prepare_input($_POST['email_sms_post'])=='email'){
			$patterns = array();
			$patterns[0] = '{CustomerName}';
			$patterns[1] = '{images}';
			$patterns[2] = '{HTTP_SERVER}';
			$patterns[3] = '{Password}';
			$patterns[4] = '{EMAIL}';
			$patterns[5] = '{CONFORMATION_EMAIL_FOOTER}';

			$replacements = array();
			$replacements[0] = db_to_html($check_customer['customers_firstname']. ' ' . $check_customer['customers_lastname']);
			$replacements[1] = HTTP_SERVER.'/email_tpl/images';
			$replacements[2] = HTTP_SERVER;
			$replacements[3] = $new_password;
			$replacements[4] = $email_address;
			$replacements[5] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));

			$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
			$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/new_password_table.tpl.html');
			$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
			
			$email_text = str_replace( $patterns ,$replacements, db_to_html($email_tpl));
			$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
			//howard added new eamil tpl end

			tep_mail(db_to_html($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname']), $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS,'true');
		}else if(tep_db_prepare_input($_POST['email_sms_post'])=='sms'){
			$strMobile = $confirmphone;
			$phone_query = tep_db_query('SELECT customers_email_address FROM `customers` WHERE confirmphone = "'.$strMobile.'"');
			$phone_query_rows = tep_db_fetch_array($phone_query);
			$customer_email_address = $phone_query_rows['customers_email_address'];
			$str_arr = explode("@", $customer_email_address);
			$str_arr[0] = substr_replace($str_arr[0],'***', 2,strlen($str_arr[0])-4);
			$customer_email_address = $str_arr[0].'@'.$str_arr[1];

			$_content = '亲爱的会员：'.$customer_email_address."您好！";
			$_content.= "感谢您使用走四方旅游网（208.109.123.18）！";
			$_content.= "根据您找回密码的申请，我们已自动为您变更了新的登录密码。";
			$_content.= "您的新密码为：".$new_password.'（注意区分大小写）';
			$_content.= '请使用新密码登录，或及时修改成便于您记住的密码，并妥善保管。';
			
			$cpunc_password = new cpunc_SMS();
			$cpunc_password->SendSMS($strMobile,$_content,'gb2312');
			$SUCCESS_PASSWORD_SENT = db_to_html("新密码已经成功发送到您 $strMobile 的手机上！");

		}

		//使用手机短信取得密码 start
		/*
		if(preg_match('/'.preg_quote('[找回密码]').'/',CPUNC_USE_RANGE)){
		$strMobile = '13183895460';
		$_content = '您在走四方网的新密码是：'.$new_password.'。';
		if($cpunc->SendSMS($strMobile,$content,'gb2312')){
		$SUCCESS_PASSWORD_SENT = db_to_html("新密码已经成功发送到您 $strMobile 的手机上！");
		}
		}
		*/
		//使用手机短信取得密码 end

		$messageStack->add_session('login', $SUCCESS_PASSWORD_SENT, 'success');

		tep_redirect(tep_href_link(FILENAME_LOGIN, 'email_address='.rawurlencode($email_address), 'SSL'));
	} else {
		if(tep_not_null($email_address)){$messageStack->add('global',db_to_html('邮箱错误！'));}
		if(tep_not_null($confirmphone)){$messageStack->add('global',db_to_html('手机号错误！'));}
	}
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

$close53kf = true; //关闭53KF

$content = CONTENT_PASSWORD_FORGOTTEN;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
