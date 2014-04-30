<?php
include('includes/application_top.php');
$post = $_POST;
//paypal异步通知文件
function tep_paypal_success_update($post){
	if(($post['payment_status']=="Completed" || $post['payment_status']=="Pending")){
		//记录付款信息到客户付款记录，$post['payment_status']=="Completed"为已经安全收到款，"Pending"。//款项在途，目前Paypal有可能出现状态为Pending，实际上已经支付成功的情况。 
		$orders_id = (int)$post["invoice"];
		$usa_value = $post["payment_gross"];
		$orders_id_include_time = $post["invoice"];
		$comment = "交易状态：".$post['payment_status']."\n";
		$comment.= "美元：".$usa_value."\n";
		$comment.= "交易时间：".$post["payment_date"]."\n";
		$comment.= "流水号：".$post["txn_id"]."\n";
		$comment.= "订单号：".$post["invoice"]."\n";
		$comment.= "付款人的Payal账号：".$post["payer_email"]."\n";
		$comment.= "通知类型：（异步通知）\n".__FILE__;
		tep_payment_success_update($orders_id, $usa_value, 'paypal', $comment, 96, $orders_id_include_time);
	}
}
tep_paypal_success_update($post);

/*
  require_once('includes/modules/payment/paypal/application_top.inc.php');
  require_once(DIR_FS_MODULES . 'payment/paypal/classes/IPN/IPN.class.php');
  require_once(DIR_FS_MODULES . 'payment/paypal/classes/Debug/Debug.class.php');
  require_once(DIR_FS_MODULES . 'payment/paypal/functions/general.func.php');

  paypal_include_lng(DIR_WS_MODULES . 'payment/paypal/languages/', $language, 'ipn.lng.php');

  $debug = new PayPal_Debug(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL,MODULE_PAYMENT_PAYPAL_IPN_DEBUG);
  $ipn = new PayPal_IPN($_POST);
  $ipn->setTestMode(MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE);
  $post = $_POST;
  unset($_POST);

  //post back to PayPal system to validate
  if(!$ipn->authenticate(MODULE_PAYMENT_PAYPAL_DOMAIN) && $ipn->testMode('Off')) $ipn->dienice('500');

  //Check both the receiver_email and business ID fields match
  if (!$ipn->validateReceiverEmail(MODULE_PAYMENT_PAYPAL_ID,MODULE_PAYMENT_PAYPAL_BUSINESS_ID)) $ipn->dienice('500');

  if($ipn->uniqueTxnID() && $ipn->isReversal()) {

    //parent_txn_id is the txn_id of the original transaction
    $ipn_query = tep_db_query("select paypal_id from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($ipn->key['parent_txn_id']) . "'");
    if(tep_db_num_rows($ipn_query)) {
      $ipn_query_result = tep_db_fetch_array($ipn_query);
      $ipn->insert();
      // update the order's status
      switch ($ipn->reversalType()) {
        case 'Canceled_Reversal':
          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;
          break;
        case 'Reversed':
        case 'Refunded':
          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID;
		  $get_order_id_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_id = '" . (int)$ipn_query_result['paypal_id'] . "'");
		  if(tep_db_num_rows($get_order_id_query)>0){
		  	$get_order_id = tep_db_fetch_array($get_order_id_query);
			$is_travel_sql = tep_db_query('SELECT orders_travel_companion_id FROM '.TABLE_ORDERS_TRAVEL_COMPANION.' WHERE  orders_id="'.(int)$get_order_id['orders_id'].'"  LIMIT 1 ');
			if(tep_db_num_rows($is_travel_sql)>0){
				$order_status = MODULE_PAYMENT_PAYPAL_TRAVEL_COMPANION_ORDER_CANCELED_STATUS_ID; //  --  100038 	//AAA: Urgent
			}
		  }
          break;
      }
      $ipn->updateOrderStatus($ipn_query_result['paypal_id'],$order_status);
    }

  } elseif ($ipn->isCartPayment() && tep_not_null($PayPal_osC_Order->orderID)) {


    //$currency = $PayPal_osC_Order->currency;

    //actually not essential since 'orders_status_name' is not required
    $languages_id = $PayPal_osC_Order->languageID;

    include(DIR_FS_CLASSES . 'order.php');
    $order = new order($PayPal_osC_Order->orderID);

    //Check that txn_id has not been previously processed
    if ($ipn->uniqueTxnID()) { //Payment is either Completed, Pending or Failed
      $ipn->insert();

      
	  //tep_db_query("update " . TABLE_ORDERS . " set payment_id = '" . (int)$ipn->ID() . "' where orders_id = '" . (int)$PayPal_osC_Order->orderID . "'");
      tep_db_query("update " . TABLE_ORDERS . " set where orders_id = '" . (int)$PayPal_osC_Order->orderID . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$order->customer['id'] . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$order->customer['id'] . "'");

      switch ($ipn->paymentStatus()) {
        case 'Completed':
          tep_paypal_success_update($post);
		  if ($ipn->validPayment($PayPal_osC_Order->payment_amount,$PayPal_osC_Order->payment_currency)) {
            include(DIR_FS_MODULES . 'payment/paypal/catalog/checkout_update.inc.php');
          } else {
            $ipn->updateOrderStatus($ipn->ID(),MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);
          }
          break;
        case 'Failed':
          $ipn->updateOrderStatus($ipn->ID(),MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);
          break;
        case 'Pending':
          tep_paypal_success_update($post);
		  //Assumed to do nothing since the order is initially in a Pending ORDER Status
          break;
      }//end switch

    } else { // not a unique transaction => Pending Payment

      $ipn_query = tep_db_query("select paypal_id, payment_status, pending_reason from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($ipn->txnID()) . "' limit 0,1");
      //Assumes there is only one previous IPN transaction
      $ipn_query_result = tep_db_fetch_array($ipn_query);
      if ($ipn_query_result['payment_status'] === 'Pending') {
        $ipn->updateStatus($ipn_query_result['paypal_id'],$ipn_query_result['pending_reason']);
        switch ($ipn->paymentStatus()) {
          case 'Completed':
           tep_paypal_success_update($post);
		   if ($ipn->validPayment($PayPal_osC_Order->payment_amount,$PayPal_osC_Order->payment_currency)) {
            include(DIR_FS_MODULES . 'payment/paypal/catalog/checkout_update.inc.php');
           } else {
            $ipn->updateOrderStatus($ipn_query_result['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);
           }
           break;
          case 'Denied':
            $ipn->updateOrderStatus($ipn_query_result['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);
            break;
        }//end switch
      }//end if Pending Payment
    }

  } elseif ($ipn->isAuction()) {

    if ($ipn->uniqueTxnID()) $ipn->insert();
    if ($debug->enabled) $debug->add(PAYPAL_AUCTION,sprintf(PAYPAL_AUCTION_MSG));

  } elseif ($ipn->txnType('send_money')) {

    if ($ipn->uniqueTxnID()) $ipn->insert();
    if ($debug->enabled) $debug->add(PAYMENT_SEND_MONEY_DESCRIPTION,sprintf(PAYMENT_SEND_MONEY_DESCRIPTION_MSG,number_format($ipn->key['mc_gross'],2),$ipn->key['mc_currency']));

  } elseif ($debug->enabled && $ipn->testMode('On')) {
    $debug->raiseError(TEST_INCOMPLETE,sprintf(TEST_INCOMPLETE_MSG),true);
  }
  if ($ipn->testMode('On') &&  $ipn->validDigest()) {
    include(DIR_FS_MODULES . 'payment/paypal/classes/Page/Page.class.php');
    $page = new PayPal_Page();
    $page->setBaseDirectory(DIR_WS_MODULES . 'payment/paypal/');
    $page->setBaseURL(DIR_WS_MODULES . 'payment/paypal/');
    $page->includeLanguageFile('admin/languages','english','paypal.lng.php');
    $page->setTitle(HEADING_ITP_RESULTS_TITLE);
    $page->setContentFile(DIR_WS_MODULES . 'payment/paypal/admin/TestPanel/Results.inc.php');
    $page->addCSS($page->baseURL . 'templates/css/general.css');
    $page->addCSS($page->baseURL . 'templates/css/stylesheet.css');
    $page->setTemplate('default');
    include($page->template());
  }
  require(DIR_FS_MODULES . 'payment/paypal/application_bottom.inc.php');
error_log(print_r($post,true)."1", 3, "/var/www/html/173.255.216.188/tmp/test.txt");
*/

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>