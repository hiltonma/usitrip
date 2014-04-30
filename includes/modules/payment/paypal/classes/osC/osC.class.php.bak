<?php
/*
  $Id: osC.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class PayPal_osC {

  function PayPal_osC($orders_id = '', $txn_signature = '') {
    $this->orders_id =  tep_not_null($orders_id) ? $orders_id : '';
    $this->txn_signature = tep_not_null($txn_signature) ? $txn_signature : '';
  }

  function check_order_status($start = false) {
    global $PayPal_osC, $customers_id;
    include_once(DIR_FS_MODULES . 'payment/paypal/database_tables.inc.php');
    if(tep_session_is_registered('PayPal_osC')) {
      if ($start === true && PHP_VERSION < 4) {
        $PayPal_osC_backup = $PayPal_osC;
        $PayPal_osC = new PayPal_osC;
        $PayPal_osC->unserialize($PayPal_osC_backup);
      }
      $orders_session_query = tep_db_query("select payment_id from " . TABLE_ORDERS . " where orders_id ='" . (int)$PayPal_osC->orders_id . "'");
      $orders_session_check = tep_db_fetch_array($orders_session_query);
      if ($orders_session_check['payment_id'] > 0 ) {
        PayPal_osC::reset_checkout_cart_session();
        return true;
      }
      return false;
    }
    return false;
  }

  function reset_checkout_cart_session() {
    global $cart;
    $cart->reset(true);
    tep_session_unregister('sendto');
    tep_session_unregister('billto');
    tep_session_unregister('shipping');
    tep_session_unregister('payment');
    tep_session_unregister('comments');
//credit class support DMG 9/21/2004

if(tep_session_is_registered('credit_covers')) {
    tep_session_unregister('credit_covers');
     if(!class_exists('order_total')) {
       include_once(DIR_FS_CLASSES . 'order_total.php');
       }
  $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
    tep_session_unregister('PayPal_osC');
  }
}
  function unserialize($broken) {
    for(reset($broken);$kv=each($broken);) {
      $key=$kv['key'];
      if (gettype($this->$key)!="user function")
      $this->$key=$kv['value'];
    }
  }
}//end class
?>
