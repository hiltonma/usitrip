<?php
//结伴同游 支付 访问本页面不用登录，只要把结伴同游的id和订单号取得即可。$_GET和$_POST均可，以$_POST优先

require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/checkout_payment.php');

// if the customer is not logged on, redirect them to the login page
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
	  $breadcrumb->add(db_to_html('我的账号'), tep_href_link('account.php', '', 'SSL'));
  }
  $breadcrumb->add(db_to_html('结伴同游订单'), tep_href_link('orders_travel_companion.php', '', 'SSL'));
  $breadcrumb->add(db_to_html('结伴同游付款'), tep_href_link('travel_companion_pay.php', '', 'SSL'));

// load all enabled payment modules
  $travel_pay = true;
  require(DIR_FS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  $validation_include_js = 'true';

  $content = 'travel_companion_pay';

  $javascript = CONTENT_CHECKOUT_PAYMENT . '.js.php';

  
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
