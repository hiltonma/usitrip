<?php
/*
  $Id: checkout_process.php,v 1.1.1.2 2004/03/04 23:37:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => 'orders_travel_companion.php'));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

//处理公共$_POST
	$error = false;
	$order_id = (int)$_POST['order_id'];
	$orders_travel_companion_ids = $_POST['orders_travel_companion_ids'];
	$guest_names = $_POST['guest_names'];
	$i_need_pay = ((int)$_POST['i_need_pay']) ? $_POST['i_need_pay'] : number_format($_POST['payables_total'],2);
	if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
	if(isset($_POST['payment'])) $payment = $_POST['payment'];
	$o_t_c_ids = $orders_travel_companion_ids;


// load selected payment module
  require(DIR_FS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  $selection = $payment_modules->selection();
  $payment_name = strip_tags($selection[0]['module']);
  $payment_name = str_replace('&nbsp;','',$payment_name);
//print_r($selection);


  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';

//************ amit moved authorized.net payment after insert order start
//信用卡付款 {
  if( (defined(MODULE_PAYMENT_AUTHORIZENET_STATUS) && MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True' && $payment == 'authorizenet') || (defined(MODULE_PAYMENT_AUTHORIZENET2_STATUS) && MODULE_PAYMENT_AUTHORIZENET2_STATUS == 'True' && $payment == 'authorizenet2')){
	
	$billto = ((int)$billto) ? $billto : $customer_default_address_id;
	$address_query = tep_db_query("select a.entry_firstname as firstname, a.entry_lastname as lastname, a.entry_company as company, a.entry_street_address as street_address, a.entry_suburb as suburb, a.entry_city as city, a.entry_postcode as postcode, a.entry_state as state, a.entry_zone_id as zone_id, a.entry_country_id as country_id, c.customers_telephone, c.customers_fax, c.customers_cellphone from " . TABLE_ADDRESS_BOOK . " a, ". TABLE_CUSTOMERS ." c where a.customers_id=c.customers_id and a.customers_id = '" . (int)$customer_id . "' and a.address_book_id = '" . (int)$billto . "'");
	$row_address = tep_db_fetch_array($address_query);

	//$billing_name = db_to_html($row_address['firstname'].$row_address['lastname']); 
	$billing_name = $cc_owner; 
	$billing_company = db_to_html($row_address['company']); 
	$billing_street_address = db_to_html($row_address['street_address']); 
	$billing_suburb = db_to_html($row_address['suburb']); 
	$billing_city = db_to_html($row_address['city']); 
	$billing_postcode = $row_address['postcode']; 
	$billing_telephone = $customers_telephone;
	if((int)$row_address['zone_id']){
		//$billing_state = tep_get_zone_code($row_address['country_id'], (int)$row_address['zone_id'], '');
		$billing_state = tep_get_zone_name($row_address['country_id'], (int)$row_address['zone_id'], '');
	}
	if(!tep_not_null($billing_state)){
		$billing_state = $row_address['state'];
	}
	
	$billing_state = db_to_html($billing_state);
	
	$billing_country = db_to_html(tep_get_country_name($row_address['country_id'])); 
	$cc_type = $_POST['cc_type'];

	$error_back_page = 'travel_companion_pay.php';
	$tmp_array = explode(',',$orders_travel_companion_ids);
	$error_back_page_get_parameters = '';
	for($i=0; $i<count($tmp_array); $i++){
		if((int)$tmp_array[$i]){
			$error_back_page_get_parameters .= '&orders_travel_companion_ids%5B%5D='.$tmp_array[$i];
		}
	}
	$error_back_page_get_parameters .= '&order_id='.(int)$order_id;

	if($payment == 'authorizenet2'){	
	}else{
		include(DIR_FS_MODULES . 'authorizenet_direct_travel_companion.php');
	}
  $insert_id = (int)$order_id;
	$before_process = $payment_modules->before_process();
	//如果付款成功需要在这里做点处理
	if((int)$before_process ==1){
		//修改同游订单状态
		//写信用卡资料到同游订单orders_travel_companion
		$cc_owner = ($authorizenet_cc_owner) ? $authorizenet_cc_owner : $cc_owner;
		$cc_owner=($cc_owner) ? $cc_owner : $authorizenet2_bank_owner;
		$cc_number = $x_Card_Num;
		$cc_expires = $x_Exp_Date;
		$cc_cvv = $x_Card_Code;
		$cc_bank_name=$authorizenet2_bank_name;
		$cc_bank_routing_num=$authorizenet2_bank_aba;
		$cc_bank_acct_num=$authorizenet2_bank_acct;
		$cc_bank_acct_type=$authorizenet2_bank_acct_type;

		$orders_travel_companion_status = "1";
		
		$payment_description = "\n[".date("Y-m-d H:i")."] ".$xx;
		
		tep_db_query('update orders_travel_companion set payment_description = concat(ifnull(payment_description,"") , "'.$payment_description.'")  where orders_id="'.(int)$order_id.'" AND orders_travel_companion_id in('.$orders_travel_companion_ids.') ');
		
		$sql_date_array = array('billing_name' => $billing_name,
								'billing_company' => $billing_company,
								'billing_street_address' => $billing_street_address,
								'billing_suburb' => $billing_suburb,
								'billing_city' => $billing_city,
								'billing_postcode' => $billing_postcode,
								'billing_state' => $billing_state,
								'billing_country' => $billing_country,
								'cc_type' => ($cc_bank_routing_num) ? 'eCheck' : $cc_type,
								//'cc_owner' => $cc_owner,
								//'cc_number' => scs_cc_encrypt($cc_number),
								'cc_expires' => $cc_expires,
								//'cc_cvv' => $cc_cvv,
								'cc_bank_name' => $cc_bank_name,
								'cc_bank_routing_num' => $cc_bank_routing_num,
								'cc_bank_acct_num' => $cc_bank_acct_num,
								'cc_bank_acct_type' => $cc_bank_acct_type,
								'last_modified' => date('Y-m-d H:i:s'),
								'orders_travel_companion_status' => $orders_travel_companion_status,
								'payment' => $payment,
								'payment_name' => $payment_name,
								'payment_customers_id' => (int)$customer_id
								);
		$sql_date_array = html_to_db($sql_date_array);
		tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.(int)$order_id.'" AND orders_travel_companion_id in('.$orders_travel_companion_ids.') ');
		
	//给结伴同游成员发邮件
	//send_travel_pay_staus_mail($order_id);
	
	//$messageStack->add_session('travel_companion', db_to_hmtl('信用卡付款信息成功提交，当前状态为付款审核中！'), 'success');
	//到处理结果页
	$_SESSION['travel_companion_ids'] = $orders_travel_companion_ids;
	$_SESSION['pay_order_id'] = (int)$order_id;
	
	tep_redirect(tep_href_link('orders_travel_companion_payment_results.php', 'orders_travel_companion_status=1', 'SSL'));
	
	}
  }
//信用卡付款 end }
//************ amit moved authorized.net payment after insert order end

//支付宝,银联在线,网银在线,paypal信用卡start{
if(in_array($payment, array('alipay_direct_pay','a_chinabank','paypal_nvp_samples','netpay', 'authorizenet2013')) ){
	$travelCompanionPay = array();	//此数组格式为utf-8
	$travelCompanionPay['i_need_pay'] = $i_need_pay;
	//$travelCompanionPay['order_id'] = (string)$order_id;
	$travelCompanionPay['orders_travel_companion_ids'] = $orders_travel_companion_ids;
	$travelCompanionPay['customer_id'] = $customer_id;
	//$travelCompanionPay['payment'] = $payment;
	//print_r($travelCompanionPay);
	//exit;
	if($payment == 'alipay_direct_pay'){
		tep_redirect( MODULE_PAYMENT_ALIPAY_DIRECT_PAY_API_WEB_DIR.'alipayto.php?order_id='. $order_id.'&travelCompanionPay='.base64_encode(json_encode($travelCompanionPay)) );
	}
	if($payment == 'netpay'){
		tep_redirect( MODULE_PAYMENT_NETPAY_API_WEB_DIR.'order_submit.php?order_id='. $order_id.'&travelCompanionPay='.base64_encode(json_encode($travelCompanionPay)) );
	}
	if($payment == 'a_chinabank'){
		tep_redirect( MODULE_PAYMENT_A_CHINABANK_API_WEB_DIR.'Send.php?order_id='. $order_id.'&travelCompanionPay='.base64_encode(json_encode($travelCompanionPay)) );
	}
	if($payment == 'paypal_nvp_samples'){
		tep_redirect( MODULE_PAYMENT_PAYPAL_NVP_SAMPLES_API_WEB_DIR.'DoDirectPayment.php?order_id='. $order_id.'&travelCompanionPay='.base64_encode(json_encode($travelCompanionPay)) );
	}
	if($payment == 'authorizenet2013'){
		tep_redirect( MODULE_PAYMENT_AUTHORIZENET2013_API_WEB_DIR.'index.php?order_id='. $order_id.'&travelCompanionPay='.base64_encode(json_encode($travelCompanionPay)) );
	}
	die();
}
//支付宝,银联在线,网银在线,paypal信用卡 end}

//银行转帐或其它支付方式
if($payment != 'authorizenet' && $payment != 'paypal' && $payment != 'alipay_direct_pay' && tep_not_null($payment)){
	$orders_travel_companion_status = '1';
	$sql_date_array = array(
							'last_modified' => date('Y-m-d H:i:s'),
							'payables' => $i_need_pay,
							'orders_travel_companion_status' => $orders_travel_companion_status,
							'payment' => $payment,
							'payment_name' => $payment_name,
							'payment_customers_id' => (int)$customer_id
							);
	$sql_date_array = html_to_db($sql_date_array);
	tep_db_perform('orders_travel_companion', $sql_date_array, 'update',' orders_id="'.(int)$order_id.'" AND orders_travel_companion_id in('.$orders_travel_companion_ids.') ');
	

}

  $breadcrumb->add(db_to_html('我的账号'), tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(db_to_html('结伴同游订单'), tep_href_link('orders_travel_companion.php', '', 'SSL'));
  $breadcrumb->add($payment_name);

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);
  require(DIR_FS_LANGUAGES . $language . '/modules/payment/banktransfer.php');
  require(DIR_FS_LANGUAGES . $language . '/modules/payment/cashdeposit.php');
  //require(DIR_FS_LANGUAGES . $language . '/modules/payment/transfer.php');


  $is_my_account = true;
  $content = 'checkout_process_travel_companion';
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
