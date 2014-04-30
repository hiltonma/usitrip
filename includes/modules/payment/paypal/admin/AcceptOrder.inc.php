<?php
/*
  $Id: AcceptOrder.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/


  include(DIR_FS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['oID']);

  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages/' . $language . '/paypal.lng.php');

  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/TransactionDetails/TransactionDetails.class.php');
  $paypal = new PayPal_TransactionDetails(TABLE_PAYPAL,$order->info['payment_id']);

  if($HTTP_GET_VARS['digest'] != $paypal->digest()) {
    $messageStack->add_session(ERROR_UNAUTHORIZED_REQUEST);
  } elseif($paypal->info['payment_status'] === 'Completed' && $order->info['orders_status'] === MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID) {

    include(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/addressbook.func.php');
    include(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/osC/Order.class.php');
    $PayPal_osC_Order = new PayPal_osC_Order();
    $PayPal_osC_Order->setOrderID($HTTP_GET_VARS['oID']);
    $PayPal_osC_Order->loadOrdersSessionInfo();
    //$currency = $PayPal_osC_Order->currency;
    $PayPal_osC_Order->setAccountHistoryInfoURL(tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $PayPal_osC_Order->orderID, 'SSL', false));
    $PayPal_osC_Order->setCheckoutProcessLanguageFile(DIR_FS_CATALOG_LANGUAGES . $PayPal_osC_Order->language . '/' . 'checkout_process.php');
    $PayPal_osC_Order->updateProducts($order,$currencies);
    $PayPal_osC_Order->notifyCustomer($order);
    $PayPal_osC_Order->updateOrderStatus(MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID);
    $PayPal_osC_Order->removeOrdersSession();
    $messageStack->add_session(SUCCESS_ORDER_ACCEPTED, 'success');
  } else {
    $messageStack->add_session(ERROR_ORDER_UNPAID);
  }
  tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
?>