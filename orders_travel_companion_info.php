<?php
/*
  $Id: account_history_info.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (!isset($HTTP_GET_VARS['order_id']) || (isset($HTTP_GET_VARS['order_id']) && !is_numeric($HTTP_GET_VARS['order_id']))) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $HTTP_GET_VARS['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$HTTP_GET_VARS['order_id'], 'SSL'));

  require(DIR_FS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);
	//添加被销售破坏了的订单广告跟踪ID
	if(!(int)$Admin->login_id){
		tep_update_orders_ad_click_id($HTTP_GET_VARS['order_id'], $customers_ad_click_id);
		tep_update_orders_affiliate_info($order_id, $_COOKIE['affiliate_ref']);
		tep_update_orders_from($order_id);
	}
  $content = 'orders_travel_companion_info';
  $javascript = 'popup_window.js';
  
  $is_my_account = true;
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
