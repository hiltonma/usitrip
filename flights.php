<?php
/*
 * $Id: contact_us.php,v 1.1.1.1 2004/03/04 23:37:58 ccwjr Exp $ osCommerce,
 * Open Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
require ('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);
function gump_repalce($pare) {
	foreach ( $pare as $key => $value )
		if (is_array($value)) {
			$pare[$key] = gump_repalce($value);
		} else {
			// $pare[$key]=html_to_db(iconv('utf-8',CHARSET .
			// '//IGNORE',tep_db_prepare_input(tep_db_output($value))));
			$pare[$key] = html_to_db(tep_db_prepare_input(tep_db_output($value)));
		}
	return $pare;
}
if ($_POST) {
// 	print_r($_POST);
	$_POST = gump_repalce($_POST);
	$i = 0;
	$message = '';
	foreach ( $_POST['from_city'] as $k => $v ) {
		if ($v) {
			$i ++;
			$message .= '飞离城市： ' . $v . '    飞抵城市：' . $_POST['to_city'][$k];
			$message.=" \n 出发日期：" . $_POST['go_time'][$k] ;
			if ($_POST['go_time_change'][$k]) {
				$message .= '(';
				$message .= $_POST['b_e_g_b'][$k]? '可提前  ' : '';
				$message .= $_POST['b_e_g_a'][$k]?'可延后':'';
				$message .= $_POST['go_days'][$k] . "天)";
			}
			$message .= "\n";
			if ($_POST['back_time'][$k]) {
				$message .= '返回日期为：' . $_POST['back_time'][$k];
				if ($_POST['back_time_change'][$k]) {
					$message .= '(';
					$message .= $_POST['b_e_b_b'][$k]? '可提前   ' : '';
					$message .= $_POST['b_e_b_a'][$k]? '可延后' : '';
					$message .= $_POST['back_day'][$k] . "天) ";
				}
				$message .= "\n";
			}
		}
	}
	$message .= '航空公司：';
	foreach ( $_POST['flights_company'] as $value){
		$message.='  '.$value;
	}
	$message .= "\n机舱:" . $_POST['cabin'];
	$message .= "\n  飞行方式：";
	$message.=$_POST['zhifei'] ? '直飞  ' : '' ;
	$message.=$_POST['yici'] ? '   可转乘一次' : '';
	$message.=$_POST['moretimes'] ? '   可有一次以上转机':'';
	$message.=$_POST['allway'] ? '   都可以' : '';
	$message .= "\n客户信息：";
	$i=0;
	foreach ( $_POST['lastName'] as $key => $value ) {
		$i++;
		if ($_POST['lastName'][$key]) {
			$message .= '第'.$i.'个乘客:    First Name:' . $_POST['firstName'][$key] . '   Middle Name:' . $_POST['middleName'][$key] . '   Last Name:' . $_POST['lastName'][$key] . "\n";
		}
	}
	$message .= '备注：' . $_POST['remark'];
	$message .= "\n联系电话:" . $_POST['phone'];
	$message .= "\n Email:" . $_POST['email'];
	if (IS_LIVE_SITES === true) {
		$_mail = STORE_OWNER_EMAIL_ADDRESS;
	} else {
		// 如果是测试站就把邮件发给测试本人的邮箱
		$_mail = '53027327@qq.com';
	}
	$to_email_address = $_mail; // STORE_OWNER_EMAIL_ADDRESS;//'service@usitrip.com';
	                            // $to_email_address .= ',
	                            // howard.zhou@usitrip.com';
	
	$message .= "\n\n" . '---------------------------------------------------------' . "\n";
	$message .= "\n" . ('发送源位置：') . tep_href_link('flights.php') . "\n";
	$EMAIL_SUBJECT = db_to_html('机票查询') . " ";
	if(tep_mail(db_to_html(STORE_OWNER . ' '), $to_email_address, $EMAIL_SUBJECT . ' ', db_to_html($message), db_to_html('test' . ' '), 'automail@usitrip.com')){
		$messageStack->add_session('global', db_to_html('邮件发送成功,请保持电话畅通。'), 'success');
		tep_redirect(tep_href_link('flights.php','success=1'));
	}else{
		$messageStack->add('global', db_to_html('邮件发送不成功，请重新发送。'), 'error');
	}
}

$validation_include_js = 'true';

// seo信息
$the_title = db_to_html('机票查询-走四方旅游网');
$the_desc = db_to_html('　');
$the_key_words = db_to_html('　');
// seo信息 end

$add_div_footpage_obj = true;
$content = 'flights';
$breadcrumb->add(db_to_html('机票查询'));
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
