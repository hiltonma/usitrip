<?php
/*
  $Id: checkout_process.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  global $payment_modules, $shipping_modules, $order, $currencies, $cart, $PayPal_osC, $customer_id,
         $sendto, $billto, $shipping, $payment, $language, $currency, $languages_id, $order_totals, $customers_advertiser, $customers_ad_click_id;

  require_once(DIR_FS_INCLUDES . 'modules/payment/paypal/database_tables.inc.php');

  if(!class_exists('order_total')) {
    include_once(DIR_FS_CLASSES . 'order_total.php');
    $order_total_modules = new order_total;
    $order_totals = $order_total_modules->process();
  }

// howard added check guest can not null
if(!tep_not_null(preg_replace('/[[:space:]]+/','',$_SESSION['GuestEngXing0'][0]))){
	$messageStack->add_session('global', db_to_html("顾客名称不能为空！"), 'error');
	tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
}
// Howard added for New Gruop Buy check start {
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	if(!(int)check_process_for_group_buy((int)$order->products[$i]['id'])){
		$messageStack->add_session('global', db_to_html("您购买的团购行程已经卖完，请选择其它行程！"), 'error');
		tep_redirect(tep_href_link('shopping_cart.php'));
	}
  }
// Howard added for New Gruop Buy check end }

  $customers_name = tep_get_customer_name($customer_id);
	if(!tep_not_null($order->customer['email_address'])){
		$order->customer['email_address'] = tep_get_customers_email($customer_id);
		$order->customer['firstname'] = tep_get_customer_name($customer_id);
	}
  $_customers_ad_click_id = $customers_ad_click_id;
  $_customers_advertiser = $customers_advertiser?$customers_advertiser:(isset($_COOKIE['url_from'])?$_COOKIE['url_from']:'');
  if($_COOKIE['login_id']){	//客服人员下的单不计广告id，由客人的电脑点击时更新
	  $_customers_ad_click_id = 0;
	  $_customers_advertiser = '';
  }
  
  $sql_data_array = array('customers_id' => $customer_id,
                          //'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_name' => $customers_name,
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'],
                          'billing_suburb' => $order->billing['suburb'],
                          'billing_city' => $order->billing['city'],
                          'billing_postcode' => $order->billing['postcode'],
                          'billing_state' => $order->billing['state'],
                          'billing_country' => $order->billing['country']['title'],
                          'billing_address_format_id' => $order->billing['format_id'],
                          'payment_method' => $this->codeTitle,
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
						  'cc_cvv' => $order->info['cc_cvv'],		
						  'order_cost' => $order->info['order_cost'],	
						  'customers_advertiser' => $customers_advertiser,
						  'customers_ad_click_id' => $_customers_ad_click_id,		
                          'orders_status' => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
                          'last_modified' => 'now()',
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value'],
						  'us_to_cny_rate' => get_value_usd_to_cny(),
						  'is_top' => '1');
   $session_exists = false;
   $payment_name = $this->codeTitle; //结伴同游下单人付款方式
   
   /*
   if(tep_session_is_registered('PayPal_osC')) {
     $orders_session_query = tep_db_query("select osi.orders_id, o.payment_id from " . TABLE_ORDERS_SESSION_INFO . " osi left join " . TABLE_ORDERS . " o on osi.orders_id = o.orders_id where osi.txn_signature ='" . tep_db_input($PayPal_osC->txn_signature) . "'");
     $orders_check = tep_db_fetch_array($orders_session_query);
     //Now check to see whether order session info exists AND that this order
     //does not currently have an IPN.
     if ($orders_check['orders_id'] > 0 &&  $orders_check['payment_id'] == '0' ) {
       //=========================如果$session_exists为true将会更新之前未付款的订单资料信息而不是新创建订单======================
	   //$session_exists = true;
       //$this->orders_id = $orders_check['orders_id'];
	   
     }
   }
   */

   if($session_exists) {
    tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int)$this->orders_id . "'");
   } else {
    $sql_data_array['date_purchased'] = 'now()';
    tep_db_perform(TABLE_ORDERS, $sql_data_array);
    $this->orders_id = tep_db_insert_id();
   }
   
   //记录客服ID到订单
   //servers_sales_track::add_login_id_to_order($this->orders_id);
   



//amit added to eticket infor start

if($session_exists) {
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_id = '" . (int)$this->orders_id . "'");
	tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_id = '" . (int)$this->orders_id . "'");
  }

//insert into flight and eticket  start 
$insert_id = (int)$this->orders_id;

//结伴同游
$i_need_pay = 0;
$orders_travel_companion_ids_str ='';
for ($i=0, $n=sizeof($order->products); $i<$n; $i++){ 
	
	$h=0;
	$guest_name='';
	$guest_body_weight='';
	$is_travel_companion = false;
	$GuestEmailArray = array();
  	$PayerMeArray = array();
	$guest_body_height = '';
	//amit added add extra customer information start
	  $guest_genger_str = '';
	  $need_add_extra_checkout_fields = false;	
	  if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_CHECKOUT_IDS.",") || in_array($order->products[$i]['agency_id'],explode(',','12,48')) || $order->products[$i]['is_birth_info'] == '1') {
			$need_add_extra_checkout_fields = true;
	  }
   //amit added add extra customer information end
	//this is for height
	$need_add_extra_checkout_fields_height = false;
	 if(preg_match("/,".(int)$order->products[$i]['id'].",/i", ",".TXT_ADD_EXTRA_FIELDS_HEIGHT_CHECKOUT_IDS.",")) {
			$need_add_extra_checkout_fields_height = true;
	 }
	  //end for height
	//更新products_remaining_seats
	//查询剩余座位
	$sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.tep_db_prepare_input($order->products[$i]['id']).'" AND departure_date="'.$order->products[$i]['dateattributes'][0].'"');
	if((int)tep_db_num_rows($sql)>'0'){
		$row = tep_db_fetch_array($sql);
		$now_seats = $row['remaining_seats_num']-$order->products[$i]['roomattributes'][2];
		//$sql_data_seats ='UPDATE `products_remaining_seats` SET `remaining_seats_num` ='2' WHERE products_id="'.tep_db_prepare_input($order->products[$i]['id']).'" and departure_date="'.tep_db_prepare_input($departure_date).'"';
		//echo $sql_data_seats;
		//tep_db_query('UPDATE products_remaining_seats SET `remaining_seats_num` =4 WHERE products_id="'.tep_db_prepare_input($order->products[$i]['id']).'" AND departure_date="'.tep_db_prepare_input($order->).'"');
		tep_db_query('UPDATE products_remaining_seats SET `remaining_seats_num` ="'.$now_seats.'",`update_date`=now() WHERE products_id="'.tep_db_prepare_input($order->products[$i]['id']).'" AND departure_date="'.$order->products[$i]['dateattributes'][0].'"');
	}
	//更新剩余座位结束
	
	foreach($_SESSION as $key=>$val){
	
		if(strstr($key,'GuestEngXing')){				
			if($_SESSION['GuestEngXing'.$h][$i] != ''){				
				//howard added 结伴同游
				if($_SESSION['GuestEmail'.$h][$i] != ''){
					$is_travel_companion = true;
				}
				//howard added 结伴同游
				$guest_name_english = "";
				if(strlen($_SESSION['GuestEngXing'.$h][$i])>0){ 
					$guest_name_english .= $_SESSION['GuestEngXing'.$h][$i];
				}
				if(strlen($_SESSION['GuestEngName'.$h][$i])>0){ 
					$guest_name_english .= ','.$_SESSION['GuestEngName'.$h][$i];
				}
				$guest_name_english = '['.$guest_name_english.']';
		
				$guest_child_age_input = '';
				if($_SESSION['guestchildage'.$h][$i] != ''){
					$guest_child_age_input = '||'.$_SESSION['guestchildage'.$h][$i];
				}else if($need_add_extra_checkout_fields == true && $_SESSION['guestdob'.$h][$i] != ''){
					$guest_child_age_input = '||'.$_SESSION['guestdob'.$h][$i];					
				}
				$single_gender ='';
				if($_SESSION['guestname'.$h][$i]==$_SESSION['SingleName'][$i] && tep_not_null($_SESSION['SingleName'][$i]) ){
					$tmp_g = tep_not_null($_SESSION['SingleGender'][$i]) ? $_SESSION['SingleGender'][$i] : "m";
					$single_gender ='('.$tmp_g.')';
				}
				//如果中文名为空就复制英文的姓名到中文名
				if(!tep_not_null(str_replace(' ','',$_SESSION['guestname'.$h][$i]))){
					$_SESSION['guestname'.$h][$i] = $_SESSION['GuestEngXing'.$h][$i];
					$_SESSION['guestsurname'.$h][$i] = $_SESSION['GuestEngName'.$h][$i];
				}
				if(!tep_not_null(str_replace(' ','',$_SESSION['GuestEngXing'.$h][$i]))){
					$messageStack->add_session('global', db_to_html("顾客名称填写不完整！"), 'error');
					tep_redirect(tep_href_link('checkout_info.php', '', 'SSL'));
				}
				$guest_name .= $_SESSION['guestname'.$h][$i].' '.$_SESSION['guestsurname'.$h][$i].' '.$guest_name_english.$guest_child_age_input.$single_gender."<::>";
				
				//结伴同游
				if($is_travel_companion == true){
					$GuestEmailArray[$h]['mail'] = $_SESSION['GuestEmail'.$h][$i];
					$GuestEmailArray[$h]['name'] = $_SESSION['guestname'.$h][$i].' '.$_SESSION['guestsurname'.$h][$i].' '.$guest_name_english.$guest_child_age_input;
					
					$PayerMeArray[$h] = $_SESSION['PayerMe'.$h][$i];
				}
			}
			if($_SESSION['guestbodyweight'.$h][$i]  != ''){
				$guest_body_weight .= $_SESSION['guestbodyweight'.$h][$i]." ".$_SESSION['guestweighttype'.$h][$i]."<::>";
			}
			
			if($_SESSION['guestgender'.$h][$i]  != '' && ($need_add_extra_checkout_fields == true  || $order->products[$i]['is_gender_info'] == '1')){
			$guest_genger_str .= $_SESSION['guestgender'.$h][$i]."<::>";
			}
			
			if($_SESSION['guestbodyheight'.$h][$i]  != '' && $need_add_extra_checkout_fields_height == true) {
				$guest_body_height .= stripslashes($_SESSION['guestbodyheight'.$h][$i])."<::>";
			}
		
			$h++;
		}
	}

	//替换“可不填”为“未知”
	$guest_name = str_replace(JS_MAY_NOT_ENTER_TEXT,JS_UNKNOWN_STRING,$guest_name);

	if($order->products[$i]['dateattributes'][0] !=''){
		$display_departure_day_str = @convert_datetime($order->products[$i]['dateattributes'][0]);
		$depature_full_address = $order->products[$i]['dateattributes'][0].' '.$display_departure_day_str.' &nbsp; '.$order->products[$i]['dateattributes'][1].' &nbsp; '.$order->products[$i]['dateattributes'][2];
	}else{
		$depature_full_address = $order->products[$i]['dateattributes'][0].' &nbsp; '.$order->products[$i]['dateattributes'][1].' &nbsp; '.$order->products[$i]['dateattributes'][2];
	}
	
	$sql_data_array = array('orders_id' => $insert_id,
						 	 'products_id' => $order->products[$i]['id'],
						 	 'guest_name' => tep_db_input($guest_name),
						 	 'guest_body_weight' => tep_db_input($guest_body_weight),		
							 'guest_gender' => tep_db_input($guest_genger_str),
							 'guest_body_height' => tep_db_input($guest_body_height),
						  	'depature_full_address' => db_to_html(tep_db_input($depature_full_address)),
							'agree_single_occupancy_pair_up'=> (int)$cart->contents[(int)$order->products[$i]['id']]['roomattributes'][6]
							);
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform(TABLE_ORDERS_PRODUCTS_ETICKET, $sql_data_array); 

 
	//if is travel companion, insert to
	//结伴同游
	if($is_travel_companion==true && (int)count($GuestEmailArray)){
		foreach($GuestEmailArray as $key => $val){
			$date_of_birth='';
			$is_child='false';
			$guest_long_name = $val['name'];
			$guest_long_name = str_replace(JS_MAY_NOT_ENTER_TEXT,JS_UNKNOWN_STRING,$guest_long_name);
			if(strstr($val['name'],'||')){
				$tmp_array = explode('||',$val['name']);
				$guest_long_name = $tmp_array[0];
				$date_of_birth = $tmp_array[1];
				if(tep_not_null($date_of_birth)){
					$is_child='true';
				}
			}
			
			$payables = str_replace(',','',$order->products[$i]['adult_average']);
			if($is_child=='true'){ $payables = str_replace(',','',$order->products[$i]['child_average']); }
			
			//谁付款
			$payment_customers_id = 0;
			if((int)$PayerMeArray[$key]){
				$payment_customers_id = $customer_id;
			}
			
			if(form_email_get_customers_id($val['mail'])==$customer_id || $payment_customers_id == $customer_id){
				$i_need_pay += $payables;	//下单人需付的款 如果使用积分则扣除积分或优惠券，目前未作处理，如果电子邮件为空者则下单人需要负责帮其付款
			}
			
			//设置订单最后付款期限
			$max_day_num = (int)TRAVEL_COMPANION_MAX_PAY_DAY;
			$expired_date = date("Y-m-d H:i:s", strtotime('+'.$max_day_num.' day'));
			$use_working_date = TRAVEL_COMPANION_MAX_PAY_DAY_USE_WORKING_DATE;
			if(strtolower($use_working_date)=='true'){
				$expired_date = get_date_working_date(date('Y-m-d H:i:s'),$max_day_num);
			}
			
			$customers_id = form_email_get_customers_id($val['mail']);
			$sql_data_array = array('orders_id' => $insert_id,
									'products_id' => $order->products[$i]['id'],
									'customers_id' => $customers_id,
									'guest_name' => tep_db_input($guest_long_name),
									'is_child' => $is_child,
									'date_of_birth' => tep_db_input($date_of_birth),
									'payables' => $payables,
									'orders_travel_companion_status' => '0',
									'last_modified' => date('Y-m-d H:i:s'),
									'expired_date' => $expired_date
									);
			if((int)$customers_id == (int)$customer_id){	//加入下单人的付款方式
				$payment_name = strip_tags($payment_name);
				$payment_name = str_replace('&nbsp;','',$payment_name);
				
				$sql_data_array['payment_name'] = $payment_name;
				$sql_data_array['payment'] = $payment;
			}

			tep_db_perform(TABLE_ORDERS_TRAVEL_COMPANION, html_to_db ($sql_data_array));
			
			if(form_email_get_customers_id($val['mail'])==$customer_id || $payment_customers_id == $customer_id){
				$orders_travel_companion_ids_str .= '&orders_travel_companion_ids%5B%5D='.tep_db_insert_id();
			}
			if(!strstr($to_email_address,$val['mail']) && form_email_get_customers_id($val['mail'])!=$customer_id){
				$to_email_address .= $val['mail'].',';
			}
		}
		
		//给结伴同游成员发邮件
		$to_email_address = substr($to_email_address,0,strlen($to_email_address)-1);
		if(strlen($to_email_address)>1){
			$email_subject = db_to_html("走四方网结伴同游订单创建成功！订单号：".(int)$insert_id."日期:".date('YmdHis')." ");
			$email_text = db_to_html('Dear 尊敬的顾客您好，')."\n\n";
			$to_name = $to_email_address;
			$email_text .= db_to_html('您有一个结伴同游订单，订单号为：<strong>').$insert_id."</strong>\n\n";
			
			$links = tep_href_link('orders_travel_companion_info.php','order_id='.(int)$insert_id,'SSL');
			$links = str_replace('/admin/','/',$links);
			$email_text .= db_to_html("订单详细信息：").'<a href="'.$links.'" target="_blank">'.$links."</a>\n";
			$email_text .= db_to_html("如果您还未付款，请尽快去确认付款，以免影响大家的行程，谢谢！\n");
			$email_text .= "<b>".JIEBANG_CART_NOTE_MSN."</b>\n";

			$email_text .= db_to_html("此邮件为系统自动发出，请勿直接回复！\n\n");
			
	
			$email_text .= db_to_html(CONFORMATION_EMAIL_FOOTER)."\n\n";
			
			$from_email_name = STORE_OWNER;
			//$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
			$from_email_address = 'automail@usitrip.com';
	
			$var_num = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
			$_SESSION['need_send_email'][$var_num]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$var_num]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$var_num]['from_email_name'] = $from_email_name;
			$_SESSION['need_send_email'][$var_num]['from_email_address'] = $from_email_address;
			$_SESSION['need_send_email'][$var_num]['action_type'] = 'true';
			//echo $to_email_address;
			//exit;
		}
		
	}else{
		$i_need_pay += $order->products[$i]['final_price'];
	}
	//结伴同游
	//if is travel companion, insert to end
 
 }			
//insert into flight and eticket end

//amit added to etcicket info end


 if($session_exists) {
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$this->orders_id . "'");
  }
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
  // this is for credit issu
  if($order_totals[$i]['code'] == 'ot_easy_discount' && $order_totals[$i]['title'] == TITLE_CREDIT_APPLIED){
		$customer_current_credit_bal = tep_get_customer_credits_balance($customer_id);
		$customer_new_credit_bal = $customer_current_credit_bal - $order_totals[$i]['value'];
			tep_db_query("update ".TABLE_CUSTOMERS." set customers_credit_issued_amt = '".$customer_new_credit_bal."' where customers_id = '".(int)$customer_id."'");
			$sql_data_credits_array = array('customers_id' => (int)$customer_id,
											'orders_id' => $insert_id,
											'credit_bal' => ($order_totals[$i]['value']*(-1)),
											'credit_comment' => 'Credit Applied',
											'date_added' => 'now()'
											);
			tep_db_perform(TABLE_CUSTOMERS_CREDITS, html_to_db($sql_data_credits_array));
	}
	if($order_totals[$i]['code'] == 'ot_coupon'){
		// 凡是用了优惠券的订单都不可以赠送积分！
		$not_points_toadd = true;
	}
   // this is for credit issu
    $sql_data_array = array('orders_id' => (int)$this->orders_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    $sql_data_array = html_to_db($sql_data_array);
	tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	
  }
  
#### Points/Rewards Module V2.1rc2a balance customer points BOF ####
  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
// customer pending points added 
	  if ($order->info['total'] > 0 && $not_points_toadd !== true) {
		  $points_toadd = get_points_toadd($order);
		  $points_comment = 'TEXT_DEFAULT_COMMENT';
		  $points_type = 'SP';
		  if ((get_redemption_awards($customer_shopping_points_spending) == true) && ($points_toadd >0)) {
			  tep_add_pending_points($customer_id, $insert_id, $points_toadd, $points_comment, $points_type);
		  }
	  }
	  if ($customer_shopping_points_spending) {	//兑换积分
		  tep_redeemed_points($customer_id, $insert_id, $customer_shopping_points_spending);
	  }
  }
#### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/

  $sql_data_array = array('orders_status_id' => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
                          'date_added' => 'now()',
                          'customer_notified' => 0,
                          'comments' => $order->info['comments']);
	  if((int)count($_SESSION['SingleName'])){
	  	if(tep_not_null($sql_data_array['comments'])){
			$sql_data_array['comments'] .= "\n";
		}
		$sql_data_array['comments'] .= db_to_html('同意单人部分配房！');
	  }

  $sql_data_array = html_to_db($sql_data_array);
  if($session_exists) {
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', "orders_id = '" . (int)$this->orders_id . "'");

    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$this->orders_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$this->orders_id . "'");
  } else {
    $sql_data_array['orders_id'] = $this->orders_id;
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  }
  $get_currency_value = tep_db_query("select value from ".TABLE_CURRENCIES." where code = '".$order->products[$i]['operate_currency_code']."'");
	if($row_currency_value = tep_db_fetch_array($get_currency_value)){
		$operate_currency_exchange_value = $row_currency_value['value'];
	}else{
		$operate_currency_exchange_value = '';
	}

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	$product_date_day_month = '';
	if($order->products[$i]['dateattributes'][0] != ''){
		$product_date_day_month = @convert_datetime($order->products[$i]['dateattributes'][0]).' ';
	}
	
	$productsId = tep_get_prid($order->products[$i]['id']);
	$new_group_buy_type = get_specials_type($order->products[$i]['is_new_group_buy'], $productsId );
	
	$sql_data_array = array('orders_id' => (int)$this->orders_id,
                            'products_id' => tep_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
							'group_buy_discount' => $order->products[$i]['group_buy_discount'],
							'final_price' => $order->products[$i]['final_price'],
							'final_price_cost' => $order->products[$i]['final_price_cost'],
                            'products_tax' => $order->products[$i]['tax'],
                            'products_quantity' => $order->products[$i]['qty'],
							'products_departure_date' => $order->products[$i]['dateattributes'][0],
							'products_departure_time' => $product_date_day_month.$order->products[$i]['dateattributes'][1],
							'products_departure_location' => $order->products[$i]['dateattributes'][2],
							'products_departure_location_sent_to_provider_confirm' => '1',
							'products_room_price' => $order->products[$i]['roomattributes'][0],
							'products_room_info' => $order->products[$i]['roomattributes'][1],
							'total_room_adult_child_info' => $order->products[$i]['roomattributes'][3],
							'operate_currency_exchange_code' => $order->products[$i]['operate_currency_code'],
							'operate_currency_exchange_value' => $operate_currency_exchange_value,
							'is_diy_tours_book' =>$order->products[$i]['is_diy_tours_book'],
							'hotel_pickup_info' =>html_to_db($_SESSION['is_hotel_pickup_info_'.$i.'_'.(int)$order->products[$i]['id']]),
							'is_new_group_buy' => $order->products[$i]['is_new_group_buy'],
							'new_group_buy_type' => $new_group_buy_type,
							'no_sel_date_for_group_buy' => $order->products[$i]['no_sel_date_for_group_buy'],
							'extra_values' => $order->products[$i]['extra_values'],
							'products_price_last_modified'=> tep_db_get_field_value('products_price_last_modified','products',' products_id="'.tep_get_prid($order->products[$i]['id']).'" '),
							'add_date' => date('Y-m-d H:i:s')
							);

    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

    $order_products_id = tep_db_insert_id();
    
    //写入销售跟踪记录
    tep_add_salestrack_when_checkout($this->orders_id);

	//写航班信息 start{
	$arrival_date = "";
	$departure_date = "";
	foreach($_SESSION as $key=>$val){
		if(strstr($key,'arrival_date'.$i)){
			 $arrival_date = $val;
		}
		
		if(strstr($key,'departure_date'.$i)){
			$departure_date = $val;
		}	
	}

	$sql_data_array = array('orders_id' => $insert_id,
							'products_id' => $order->products[$i]['id'],
							'airline_name' => $order->info['airline_name'][$i],
							'flight_no' => $order->info['flight_no'][$i],
							'airline_name_departure' => $order->info['airline_name_departure'][$i],
							'flight_no_departure' => $order->info['flight_no_departure'][$i],
							'airport_name' => $order->info['airport_name'][$i],
							'airport_name_departure' => $order->info['airport_name_departure'][$i], 	  
							'arrival_date' => tep_get_date_db($arrival_date),
							'arrival_time' => $order->info['arrival_time'][$i],
							'departure_date' => tep_get_date_db($departure_date),
							'departure_time' => $order->info['departure_time'][$i],
							'orders_products_id' => $order_products_id);
	tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, html_to_db($sql_data_array));
	
	//记录航班更新历史
	tep_add_orders_product_flight_history(html_to_db($sql_data_array),$order_products_id,0,$customer_id,1);
	//写航班信息 end}
	//写上车地址历史记录 start{
	//if(tep_not_null($order->products[$i]['dateattributes'][2])){
		tep_add_departure_location_history($order_products_id, (tep_not_null($order->products[$i]['dateattributes'][2]) ? $order->products[$i]['dateattributes'][2] : $order->products[$i]['dateattributes'][0]) );
	//}
	//写上车地址历史记录 end}
	//howard added updated travel companion orders start
	tep_update_travel_companion_orders($customer_id, 0, tep_get_prid($order->products[$i]['id']), (int)$this->orders_id );
	//howard added updated travel companion orders end
	
	//howard added if date $order->products[$i]['dateattributes'][0] <= today send mail to howard.zhou@usitrip.com
	if(check_date(substr($order->products[$i]['dateattributes'][0],0,10))==false){
		$error_conten = "订单号orders_id:".(int)$this->orders_id." ，产品订单号orders_products_id: $order_products_id ，数据库表orders_products。\n";
		$error_conten .= "错误的出发日期:".$order->products[$i]['dateattributes'][0];
		write_error_log($error_conten);
	}
	
//amit added for cost cal history
	 $sql_data_array_original_insert = array(			  
						'orders_products_id' => $order_products_id,	
						'products_model' => $order->products[$i]['model'],
                        'products_name' => $order->products[$i]['name'],			
						'retail' => $order->products[$i]['final_price'],
						'cost' => $order->products[$i]['final_price_cost'],
						'last_updated_date' => 'now()'																										  
						);
	tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
	//amit added for cost cal history
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "'
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and popt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => (int)$this->orders_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_id' => $order->products[$i]['attributes'][$j]['option_id'],
                                'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
							    'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id'],
								'options_values_price_cost' => $order->products[$i]['attributes'][$j]['price_cost'],
                                'options_values_price' => $order->products[$i]['attributes'][$j]['price'],
                                //'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);

        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
      }
    }
  }

// store the session info for notification update - gsb
  $sql_data_array_session = array('sendto' => $sendto,
                          'billto' => $billto,
                          'firstname' => $order->billing['firstname'],
                          'lastname' =>  $order->billing['lastname'],
                          'payment' => $payment,
                          'payment_title' => $this->codeTitle,
                          'payment_amount' => $this->grossPaymentAmount($this->currency()),
                          'payment_currency' => $this->currency(),
                          'payment_currency_val' => $currencies->get_value($this->currency()),
                          'language' => $language,
                          'language_id' => $languages_id,
                          'currency' => $currency,
                          'currency_value' => $currencies->get_value($currency),
                          'content_type' => $order->content_type,
                          'txn_signature' => $this->setTransactionID());
  // Include OSC-AFFILIATE
  require(DIR_FS_INCLUDES . 'affiliate_checkout_process.php');
  /*添加销售跟踪*/
  $tmp_a='';
if(!$Admin->parent_orders_id) $tmp_a=servers_sales_track::add_login_id_to_order($insert_id);
  if($tmp_a)
  tep_add_salestrack_when_checkout_at_check_proceess($insert_id,$tmp_a);
  //根据sofia要求，生成一次单后清除销售联盟变量
  servers_sales_track::clear_ref_info();

  if($session_exists) {
  	
    tep_db_perform(TABLE_ORDERS_SESSION_INFO, $sql_data_array_session, 'update', "orders_id = '" . (int)$this->orders_id . "'");
    $PayPal_osC->txn_signature = $this->digest;
   
  } else {
    $sql_data_array_session['orders_id'] = (int)$this->orders_id;
    tep_db_perform(TABLE_ORDERS_SESSION_INFO, $sql_data_array_session);
    
    $PayPal_osC = new PayPal_osC($this->orders_id,$this->digest);
    tep_session_register('PayPal_osC');
  }
  // load the after_process function from the payment modules
	$payment_modules->after_process();
	
	$cart->reset(true);
	
  // unregister session variables used during checkout
	tep_session_unregister('sendto');
	tep_session_unregister('billto');
	tep_session_unregister('shipping');
	tep_session_unregister('comments');
	tep_session_unregister('airline_name');
	tep_session_unregister('flight_no');
	tep_session_unregister('airline_name_departure');
	tep_session_unregister('flight_no_departure');
	tep_session_unregister('airport_name');
	tep_session_unregister('airport_name_departure');
	tep_session_unregister('arrival_date');
	tep_session_unregister('arrival_time');
	tep_session_unregister('departure_date');
	tep_session_unregister('departure_time');
	$guest_name = "";
	foreach($_SESSION as $key=>$val)
	{
	  if(strstr($key,'GuestEmail') || strstr($key,'PayerMe'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'guestname'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'SingleName'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'SingleGender'))
	  {
	  tep_session_unregister($key);
	  }

	  if(strstr($key,'guestsurname'))
	  {
	  tep_session_unregister($key);
	  }
	  
	  if(strstr($key,'GuestEngXing'))
	  {
	  tep_session_unregister($key);
	  }		
	  if(strstr($key,'GuestEngName'))
	  {
	  tep_session_unregister($key);
	  }		
	  if(strstr($key,'guestchildage'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'guestbodyweight'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'guestweighttype'))
	  {
	  tep_session_unregister($key);
	  }
	  if(strstr($key,'arrival_date'.$i))
	  {
		  tep_session_unregister($key);
	  }
	  if(strstr($key,'departure_date'.$i))
	  {
		   tep_session_unregister($key);
	  }
	  
	  if(strstr($key,'guestgender'.$i))
		{
			 tep_session_unregister($key);
		}
		
		if(strstr($key,'guestdob'.$i))
		{
			 tep_session_unregister($key);
		}
		if(strstr($key,'guestbodyheight'))
	  	{
	  		tep_session_unregister($key);
	  	}
   
	}

  //如果是结伴同游订单则直接到结伴同游结帐处结帐
  if(isset($i_need_pay) && $i_need_pay>0 && tep_not_null($orders_travel_companion_ids_str)){
	
		tep_db_query('DELETE FROM `orders_session_info` WHERE `orders_id` = "'.(int)$this->orders_id.'" LIMIT 1 ');
		tep_redirect(tep_href_link('travel_companion_pay.php','order_id='.(int)$this->orders_id. $orders_travel_companion_ids_str, 'SSL'));
		//echo $i_need_pay;
		exit;
  }
  
  //tep_db_query('DELETE FROM `orders_session_info` WHERE `orders_id` = "'.(int)$this->orders_id.'" LIMIT 1 ');

  require(DIR_FS_INCLUDES . 'modules/payment/paypal/catalog/checkout_splash.inc.php');
?>
