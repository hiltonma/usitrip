<?php
/*
*修改订单支付方式
*@author vincent
*/
require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/checkout_payment.php');

if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

  if (tep_session_is_registered('customer_id')) {
	  $is_my_account = true;
	  $breadcrumb->add(db_to_html('我的走四方'), tep_href_link('account.php', '', 'SSL'));
  }
  $breadcrumb->add(db_to_html('订单查询'), tep_href_link('account_history.php', '', 'SSL'));
  $breadcrumb->add(db_to_html('订单资讯'), tep_href_link('account_history_info.php', 'order_id='.$_GET['order_id'], 'SSL'));
  $breadcrumb->add(db_to_html('去付款'),tep_href_link('account_history_payment_method.php', 'order_id='.$_GET['order_id'], 'SSL'));

  require(DIR_FS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);
  
// load all enabled payment modules
  $travel_pay = true;
  require(DIR_FS_CLASSES . 'payment.php');
  $payment_modules = new payment;
  $validation_include_js = 'true';
  $content = 'account_history_payment_method';
  $javascript = CONTENT_CHECKOUT_PAYMENT . '.js.php';  
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>