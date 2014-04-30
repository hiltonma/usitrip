<?php

include('includes/application_top.php');
// if the customer is not logged on, redirect them to the login page
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot(array('mode' => 'SSL', 'page' => 'account_history_payment_method.php'));
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

//处理公共$_POST
$error = false;
$order_id = (int)$_POST['order_id'];

//检查用户是否有权限修改该订单
$row =  tep_db_fetch_array(tep_db_query("SELECT COUNT(*) AS total FROM ".TABLE_ORDERS." WHERE orders_id = ".$order_id.' AND customers_id ='.$customer_id));
if($row['total']!=1){
	tep_redirect(tep_href_link('account_history_payment_method.php', 'order_id='.$order_id.'&error_message=' . urlencode('对不起！您不能修改订单#'.$order_id.'的资料！'), 'SSL'));
	exit;
}

$i_need_pay = ((int)$_POST['i_need_pay']) ? $_POST['i_need_pay'] : number_format($_POST['payables_total'],2);
if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
if (isset($_POST['payment'])) $payment = $_POST['payment'];

require(DIR_FS_CLASSES . 'order.php');
$order = new order($order_id);

// load selected payment module
require(DIR_FS_CLASSES . 'payment.php');


$payment_modules = new payment($payment);
$selection = $payment_modules->selection();
$payment_name = strip_tags($selection[0]['module']);
$payment_name = str_replace('&nbsp;','',$payment_name);

require(DIR_FS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->collect_posts();
$order_total_modules->pre_confirmation_check();
$result = $order_total_modules->process();

$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';

//根据不同的支付方式，采取不同的处理办法
$redirect_url = '';

if($payment=='') $payment = 'paypal_nvp_samples';

switch ($payment){
	case 'alipay_direct_pay':	//支付宝
		$redirect_url = MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR.'alipayto.php?order_id='.$order_id;
	break;
	case 'netpay':	//银联在线
		$redirect_url = MODULE_PAYMENT_NETPAY_API_WEB_DIR.'order_submit.php?order_id='.$order_id;
	break;
	case 'a_chinabank':	//网银在线
		$redirect_url = MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR.'Send.php?order_id='. $order_id;
	break;
	case 'paypal_nvp_samples':	//Paypal信用卡
		$redirect_url = MODULE_PAYMENT_PAYPAL_NVP_SAMPLES_API_WEB_DIR.'DoDirectPayment.php?order_id='. $order_id;
	break;
	case 'authorizenet2013':	//authorizenet2013信用卡
		$redirect_url = MODULE_PAYMENT_AUTHORIZENET2013_API_WEB_DIR.'index.php?order_id='. $order_id;
	break;
}
if($redirect_url!=''){
	tep_redirect($redirect_url);
	exit;
}

if (tep_session_is_registered('customer_id')) {
	$is_my_account = true;
	$breadcrumb->add(db_to_html('我的走四方'), tep_href_link('account.php', '', 'SSL'));
}
$breadcrumb->add(db_to_html('订单查询'), tep_href_link('account_history.php', '', 'SSL'));
$breadcrumb->add(db_to_html('订单资讯'), tep_href_link('account_history_info.php', 'order_id='.$order_id, 'SSL'));
$breadcrumb->add(db_to_html('修改付款方式'),tep_href_link('account_history_payment_method.php', 'order_id='.$order_id, 'SSL'));
$breadcrumb->add($payment_name);

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);
require(DIR_FS_LANGUAGES . $language . '/modules/payment/banktransfer.php');
require(DIR_FS_LANGUAGES . $language . '/modules/payment/cashdeposit.php');
//require(DIR_FS_LANGUAGES . $language . '/modules/payment/transfer.php');

$is_my_account = true;
$content = 'account_history_payment_process';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
