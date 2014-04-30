<?php
/*
  $Id: authorizenet.php,v 1.4 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

 */

// define('FILENAME_AUTHORIZENET_HELP', 'authnet_help.html');

  class authorizenet {
    var $code, $title, $description, $enabled, $sort_order;
    var $accepted_cc, $card_types, $allowed_types, $currency;

// class constructor
  function authorizenet() {
    global $order;
    $this->code = 'authorizenet';
    $this->title = MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE;
	$this->titleaddon = MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE_ADDON;
    $this->description = MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER;
    $this->enabled = ((MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') ? true : false);
    $this->currency = MODULE_PAYMENT_AUTHORIZENET_CURRENCY;
    $this->accepted_cc = MODULE_PAYMENT_AUTHORIZENET_ACCEPTED_CC;

    if ((int)MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID;
    }

    if (is_object($order)) $this->update_status();

    //array for credit card selection
    $this->card_types = array('Amex' => MODULE_PAYMENT_AUTHORIZENET_TEXT_AMEX,
				'Mastercard' => MODULE_PAYMENT_AUTHORIZENET_TEXT_MASTERCARD,
				'Discover' => MODULE_PAYMENT_AUTHORIZENET_TEXT_DISCOVER,
				'Visa' => MODULE_PAYMENT_AUTHORIZENET_TEXT_VISA);
    $this->allowed_types = array();

    // Credit card pulldown list
    $cc_array = explode(', ', MODULE_PAYMENT_AUTHORIZENET_ACCEPTED_CC);
    while (list($key, $value) = each($cc_array)) {
      $this->allowed_types[$value] = $this->card_types[$value];
    }

    // Processing via Authorize.net AIM
    $this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false);
  }

// class methods
  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_AUTHORIZENET_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_AUTHORIZENET_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }
      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }


//concatenate to get CC images
function get_cc_images() {
	$cc_images = '';
	reset($this->allowed_types);
	while (list($key, $value) = each($this->allowed_types)) {
		$cc_images .= tep_image(DIR_WS_ICONS . $key . '.gif', $value);
	}
	return $cc_images;
}

function javascript_validation() {
   if(MODULE_PAYMENT_AUTHORIZENET_CCV	== 'True' ) {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.authorizenet_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet_cc_number.value;' . "\n" .
            '    var cc_cvv = document.checkout_payment.cvv.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
             '    if (cc_cvv != "" && cc_cvv.length < "3") {' . "\n".
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_CVV . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";
          }else{
    $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.authorizenet_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet_cc_number.value;' . "\n" .
            '    var cc_cvv = document.checkout_payment.cvv.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";
    }

      return $js;
    }

    function selection() {
      global $order;
	reset($this->allowed_types);
	$card_menu[0] = array('id' => '', 'text' => db_to_html('请选择'));
	while (list($key, $value) = each($this->allowed_types)) {
		$card_menu[] = array('id' => $key, 'text' => $value);
	}

      for ($i=1; $i<13; $i++) {
		  $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => tep_get_language_stringmonth($i));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+15; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
	  
	  $need_input_msn = db_to_html('必填项');
	  $need_sel_msn = db_to_html('请选择类型');
		if(MODULE_PAYMENT_AUTHORIZENET_CCV	== 'True' ) {
			$selection = array('id' => $this->code,
				'module' => $this->title,  
				'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_TYPE,
					'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu, '', ' class="required" title="'.$need_sel_msn.'" ')),
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER,
					'field' => tep_draw_input_field('authorizenet_cc_owner', db_to_html($order->info['cc_owner']),'class="required" title="'.$need_input_msn.'"')), 
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER,
					'field' => tep_draw_input_field('authorizenet_cc_number','','class="required " title="'.$need_input_msn.'"')),
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES,
					'field' => tep_draw_pull_down_menu('authorizenet_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet_cc_expires_year', $expires_year)),
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_NUMBER ,
					'field' => tep_draw_input_field('cvv','',"size='4' maxlength='4' class=required  title=".$need_input_msn." "). ' ' .'<a href="javascript:CVVPopUpWindow(\'' . tep_href_link('cvv.html') . '\')">' .  '(' . MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_LINK . ')' . '</a>')));
		 }else{
		$selection = array('id' => $this->code,
				'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images() ,
				'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_TYPE,
					'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER,
					
					'field' => tep_draw_input_field('authorizenet_cc_owner', db_to_html($order->info['cc_owner']),'class="required " title="'.$need_input_msn.'"')), 
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER,
					'field' => tep_draw_input_field('authorizenet_cc_number','','class="required "')),
				array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES,
					'field' => tep_draw_pull_down_menu('authorizenet_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet_cc_expires_year', $expires_year))));
		 }
     //温馨提示栏
	 //$selection['warm_tips'] = MODULE_PAYMENT_AUTHORIZENET_TEXT_WARM_TIPS;
	 $selection['warm_tips'] = 
	 '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color: #a7a7a7;">
  <tr>
    <td rowspan="8" style="border-left-width: 1px;	border-left-style: solid; border-left-color: #dadada;">&nbsp;</td>
    <td height="22"><font color="#111">信用卡类型</font> <img src="image/icons/visa_icon.gif" style=" vertical-align:middle;" /> <img src="image/icons/master_icon.gif" style=" vertical-align:middle;" /> <img src="image/icons/discover_icon.gif" style=" vertical-align:middle;" /><br/><b style="color:#ff6600;">温馨提示：</b></td>
  </tr>
  <tr>
    <td height="22" style="color:#333;">1）我们接受Visa、MasterCard、American Express、Discover及Debit卡，支持币种为美元<img src="'.MODULE_PAYMENT_PAYPAL_NVP_SAMPLES_API_WEB_DIR.'images/pay-usa.jpg" style=" vertical-align:middle;" /></td>
  </tr>
  <tr>
    <td height="22" style="color:#333;">2）本网站已安装<span style="color:#ff6600;text-decoration:underline;">SSL证书</span>，已安全认证。实时到账，无任何手续费；</td>
  </tr>
  <tr>
    <td height="22" style="color:#333;">3）请确保信用卡剩余额度足够本次消费，并开通网上支付功能。</td>
  </tr>
  <!--
  <tr>
    <td height="22" style="color:#F00">4.帐单地址是申办信用卡时填写的帐单寄送地<br>址,如不清楚可直接向发卡银行询问.</td>
  </tr>
  <tr>
    <td height="22">5. 信用卡支付币种为<b>美元</b>。</td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
  </tr>
  -->
</table>';
	$selection['warm_tips'] = db_to_html($selection['warm_tips']);
	$selection['currency'] = (tep_not_null($this->currency) ? $this->currency : 'USD');
	
	 return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS, $cvv, $error_back_page;
      include(DIR_FS_CLASSES . 'cc_validation.php');
      $cc_validation = new cc_validation();
	$result = $cc_validation->validate($HTTP_POST_VARS['authorizenet_cc_number'], $HTTP_POST_VARS['authorizenet_cc_expires_month'], $HTTP_POST_VARS['authorizenet_cc_expires_year'], $HTTP_POST_VARS['cvv'], $HTTP_POST_VARS['credit_card_type']);
      $error = '';
	switch ($result) {
		case -1:
			$error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
			break;
		case -2:
		case -3:
		case -4:
			$error = TEXT_CCVAL_ERROR_INVALID_DATE;
			break;
		case -5:
			$error = TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH;
			break;
		case -6;
			$error = TEXT_CCVAL_ERROR_CVV_LENGTH;
			break;
		case false:
			$error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
			break;
	}

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet_cc_owner=' . urlencode($HTTP_POST_VARS['authorizenet_cc_owner']) . '&authorizenet_cc_expires_month=' . $HTTP_POST_VARS['authorizenet_cc_expires_month'] . '&authorizenet_cc_expires_year=' . $HTTP_POST_VARS['authorizenet_cc_expires_year'];
        if(tep_not_null($error_back_page)){
			//结伴同游信用卡错误处理
			$tmp_array = explode(',',$HTTP_POST_VARS['orders_travel_companion_ids']);
			$error_back_page_get_parameters = '';
			for($i=0; $i<count($tmp_array); $i++){
				if((int)$tmp_array[$i]){
					$error_back_page_get_parameters .= '&orders_travel_companion_ids%5B%5D='.$tmp_array[$i];
				}
			}
			$error_back_page_get_parameters .= '&order_id='.(int)$HTTP_POST_VARS['order_id'];
			$payment_error_return .=$error_back_page_get_parameters;
			tep_redirect(tep_href_link($error_back_page, $payment_error_return, 'SSL', true, false));
			//结伴同游信用卡错误处理end
			
		}else{
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		}
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
      $x_Card_Code = $HTTP_POST_VARS['cvv'];
    }

    function confirmation() {
      global $HTTP_POST_VARS, $x_Card_Code;
       $x_Card_Code=$HTTP_POST_VARS['cvv'];
       $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CVV_NUMBER,
                                                    'field' => $HTTP_POST_VARS['cvv']),
                                                    array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $HTTP_POST_VARS['authorizenet_cc_owner']),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['authorizenet_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['authorizenet_cc_expires_year'])))));
      return $confirmation;
    }

    function process_button() {
	global $HTTP_POST_VARS;
	   // Change made by using ADC Direct Connection

      $process_button_string = tep_draw_hidden_field('x_Card_Code', $HTTP_POST_VARS['cvv']) .
                               tep_draw_hidden_field('x_Card_Num', $this->cc_card_number) .
                               tep_draw_hidden_field('x_Exp_Date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2));
 	  $process_button_string .= tep_draw_hidden_field('cc_owner', $HTTP_POST_VARS['authorizenet_cc_owner']) .
                               tep_draw_hidden_field('cc_expires', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               tep_draw_hidden_field('cc_type', $this->cc_card_type) .
                               tep_draw_hidden_field('cc_number', $this->cc_card_number).
							   tep_draw_hidden_field('cc_cvv', $HTTP_POST_VARS['cvv']); 
      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());
      return $process_button_string;
    }

    function before_process() {
  	  global $response , $insert_id, $order, $i_need_pay, $o_t_c_ids, $error_back_page, $error_back_page_get_parameters;
	  global $x_Amount,$authorizenet_failed_cntr, $response_auth_trans_id;

		//howard added 结伴同游
		//$paypal_usd = number_format($order->info['total'], 2, '.', '');
		$paypal_usd = 0;
		if($x_Amount>0){
			$paypal_usd = $x_Amount;
		}else{
			//die('Plx Check authorizenet.php, $x_Amount value null. ');
		}
		//howard added 结伴同游 end
		
		// Change made by using ADC Direct Connection
		$response_vars = explode(',', $response[0]);
 		$x_response_code = $response_vars[0];
	  	$x_response_subcode = $response_vars[1];
		$x_response_reason_code = $response_vars[2];
  		$x_response_reason_text = $response_vars[3];
		
		/*Response Code
		The overall status of the transaction
		1 = Approved
		2 = Declined
		3 = Error
		4 = Held for Review
		*/


		//信用卡付款失败处理
		if ($x_response_code != '1') {
		
		if($authorizenet_failed_cntr==""){
			$authorizenet_failed_cntr=0;
			}
			
			$authorizenet_failed_cntr++;
			if (!tep_session_is_registered('authorizenet_failed_cntr')) {
				tep_session_register('authorizenet_failed_cntr');
			}
			$x_response_text = '您的信用卡支付没有通过系统审核，支付失败！'.$x_response_code;
			if($x_response_code == '') {
				$x_response_text = '重要提示：因网络通讯问题，无法连接Authorize.net信用卡支付服务器，支付失败！请与客服人员联系。';
			}
			if($x_response_code == '2') {
				$x_response_text = '重要提示：您的信用卡支付没有通过系统审核，系统拒收！错误代码：'.$x_response_code.'请仔细阅读信用卡支付温馨提示后，重新尝试。';
			}
			if($x_response_code == '3') {
				$x_response_text = '重要提示：您的信用卡支付没有通过系统审核，信用卡信息有误！错误代码：'.$x_response_code.'请仔细阅读信用卡支付温馨提示后，重新尝试。';
			}
			
			//tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '".(int)$insert_id."' "); //Remove order
			//update orders status for current insert order
			
			$sql_data_array_ord_status = array('orders_status' => '100060'); //create cc faild			
			tep_db_perform(TABLE_ORDERS, $sql_data_array_ord_status, 'update', "orders_id = '".(int)$insert_id."'");
			
			$sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => '100060',
                          'date_added' => 'now()',
                          'customer_notified' => '0',
                          'comments' => db_to_html($x_response_text).$order->info['comments']);
  			tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, html_to_db($sql_data_array));
			//amit added to fixed recover pending status when cc failed for TC order start			
			if($i_need_pay>0 && tep_not_null($o_t_c_ids) && basename($_SERVER['PHP_SELF']) == 'checkout_process_travel_companion.php'){				
				$sql_date_array = array(
										'orders_id' => (int)$insert_id,
										'orders_status_id' => '1',
										'date_added' => 'now()',
										'customer_notified' => '1',
										'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,	
										'comments' => 'Travel Companion Order CC Auth Failed. Recover Order Status to Pending'
									);
				tep_db_perform('orders_status_history', $sql_date_array);				
				$sql_data_array = array('orders_status' => '1');
     			tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int)$insert_id . "'");
			}
			//amit added to fixed recover pending status when cc failed for TC order end
			//update orders status for current insert order
			
		  
		  if(!tep_not_null($error_back_page)){
		  	$error_back_page = FILENAME_CHECKOUT_PAYMENT;
		  }
		  if($x_response_code == '') {
	  		  //tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode('The server cannot connect to Authorize.net.  Please check your cURL and server settings.').$error_back_page_get_parameters, 'SSL', true, false));
	  		  tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode(db_to_html($x_response_text)).$error_back_page_get_parameters, 'SSL', true, false));
  		  } else if($x_response_code == '2') {
				tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode(db_to_html($x_response_text)) . urlencode('(') . urlencode("$x_response_reason_code") . urlencode('): ') . urlencode("$x_response_reason_text").$error_back_page_get_parameters , 'SSL', true, false));
	    	} else if($x_response_code == '3') {
  		  	//tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode('There was an error processing your credit card ') . urlencode('(') . urlencode("$x_response_reason_code") . urlencode('): ') . urlencode("$x_response_reason_text").$error_back_page_get_parameters, 'SSL', true, false));
  		  	tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode(db_to_html($x_response_text)) . urlencode('(') . urlencode("$x_response_reason_code") . urlencode('): ') . urlencode("$x_response_reason_text").$error_back_page_get_parameters, 'SSL', true, false));
  	  	} else {
	  	    tep_redirect(tep_href_link($error_back_page, 'addextra=true&error_message=' . urlencode(db_to_html($x_response_text)).$error_back_page_get_parameters, 'SSL', true, false));
		    }
  	  }else{ // success code
	  //信用卡付款成功处理
	  		//update orders table to cc success 
				//amit added to take avs info in log start
				$autho_address_verification_status['A'] = "Address (Street) matches, ZIP does not";
				$autho_address_verification_status['B'] = "Address information not provided for AVS check";
				$autho_address_verification_status['E'] = "AVS error";
				$autho_address_verification_status['G']= "Non-U.S. Card Issuing Bank";
				$autho_address_verification_status['N'] = "No Match on Address (Street) or ZIP";
				$autho_address_verification_status['P'] = "AVS not applicable for this transaction";
				$autho_address_verification_status['R'] = "Retry ?System unavailable or timed out";
				$autho_address_verification_status['S']= "Service not supported by issuer";
				$autho_address_verification_status['U'] = "Address information is unavailable";
				$autho_address_verification_status['W'] = "Nine digit ZIP matches, Address (Street) does not";
				$autho_address_verification_status['X'] = "Address (Street) and nine digit ZIP match";
				$autho_address_verification_status['Y'] = "Address (Street) and five digit ZIP match";
				$autho_address_verification_status['Z'] = "Five digit ZIP matches, Address (Street) does not";
				
				
				$autho_avs_card_code_status['M'] = "Match"; 
				$autho_avs_card_code_status['N'] = "No Match"; 
				$autho_avs_card_code_status['P'] = "Not Processed";  
				$autho_avs_card_code_status['S'] = "Should have been present";  
				$autho_avs_card_code_status['U'] = "Issuer unable to process request"; 
				//amit added to take avs info in log end
				
				$cc_type = (tep_not_null($_POST['cc_type'])) ? $_POST['cc_type'] :$order->info['cc_type'];
				$cc_number = (tep_not_null($_POST['cc_number'])) ? $_POST['cc_number'] :$order->info['cc_number'];
				$cc_expires = (tep_not_null($_POST['cc_expires'])) ? $_POST['cc_expires'] :$order->info['cc_expires'];
				$cc_owner = (tep_not_null($_POST['cc_owner'])) ? $_POST['cc_owner'] :$order->info['cc_owner'];
				//amit added for auto charged start
			   $response_auth_trans_id = $response[6];
			   if (!tep_session_is_registered('response_auth_trans_id')) {
					tep_session_register('response_auth_trans_id');
				}
			   //amit added for auto charged end
			   $avs_authorized_db_insert_note = "Address Verification Status: ".$autho_address_verification_status[''.$response[5].'']." 
													Card Code Status: ".$autho_avs_card_code_status[''.$response[38].'']."
													Card Type: ".$cc_type."
													Card Number: xxxx".substr($cc_number,-4)."
													Expiration Date: ".$cc_expires."
													Total Amount: USD ".$paypal_usd."
													Transaction ID: ".$response_auth_trans_id."
													Name: ".html_to_db($cc_owner)."";	
			
				if($i_need_pay>0 && tep_not_null($o_t_c_ids)){	//结伴同游
					$sql = tep_db_query('SELECT guest_name FROM `orders_travel_companion` WHERE orders_id="'.(int)$insert_id.'" AND orders_travel_companion_id in('.$o_t_c_ids.') ');
					$r_guest_name = '';
					while($rows = tep_db_fetch_array($sql)){
						$r_guest_name .= ','.$rows['guest_name'];
					}
					$r_guest_name = substr($r_guest_name,1);
					$avs_authorized_db_insert_note.= "\n".'结伴同游付款：'.$r_guest_name."\n请注意信用卡交易金额！";
				}
				
				$sql_data_array = array('orders_id' => $insert_id,
							  'orders_status_id' => '100062',
							  'date_added' => 'now()',
							  'customer_notified' => '0',
							  'comments' => tep_db_input($avs_authorized_db_insert_note)							  
							  );
				tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);																		
			
			//update orders table to cc success 
		return true;
	  
	  }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) values ('Authorizenet - Setup Help', 'MODULE_PAYMENT_AUTHORIZENET_HELP', '<a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_AUTHORIZENET_HELP, '', 'NONSSL') . "\" target=\"authnetHelp\"> [Setup Help]</a><br>', '<a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_AUTHORIZENET_HELP, '', 'NONSSL') . "\" target=\"authnetHelp\"> [Setup Help]</a><br>', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', 'Do you want to accept payments through Authorize.net?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'Your Login Name', 'The login username used for the Authorize.net service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET_TRANSKEY', 'Your Transaction Key', 'The transaction key used for the Authorize.net service', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('cURL Setup', 'MODULE_PAYMENT_AUTHORIZENET_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', '6', '0', 'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Path', 'MODULE_PAYMENT_AUTHORIZENET_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', 'Transaction method used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Processing Mode', 'MODULE_PAYMENT_AUTHORIZENET_CCMODE', 'Authorize And Capture', 'Credit card processing mode', '6', '0', 'tep_cfg_select_option(array(\'Authorize And Capture\', \'Authorize Only\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order Of Display', 'MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER', '200', 'The order in which this payment type is dislayed. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_AUTHORIZENET_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', '6', '0', '_selectOptions(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Authorizenet - Payment Zone', 'MODULE_PAYMENT_AUTHORIZENET_ZONE', '0', 'Authorizenet - If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CCV code', 'MODULE_PAYMENT_AUTHORIZENET_CCV', 'True', 'Do you want to enable ccv code checking?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Authorizenet - Set Order Status', 'MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID', '0', 'Authorizenet - Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added ) values ('币种', 'MODULE_PAYMENT_AUTHORIZENET_CURRENCY', 'USD', '币种', '6', '9', now())");
   }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_PAYMENT_AUTHORIZENET_STATUS', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_TRANSKEY', 'MODULE_PAYMENT_AUTHORIZENET_CURL', 'MODULE_PAYMENT_AUTHORIZENET_CURL_PATH', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'MODULE_PAYMENT_AUTHORIZENET_CCMODE', 'MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'MODULE_PAYMENT_AUTHORIZENET_ACCEPTED_CC', 'MODULE_PAYMENT_AUTHORIZENET_ZONE', 'MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID', 'MODULE_PAYMENT_AUTHORIZENET_CCV','MODULE_PAYMENT_AUTHORIZENET_HELP','MODULE_PAYMENT_AUTHORIZENET_CURRENCY');
    }
  }

// Authorize.net Consolidated Credit Card Checkbox Implementation
// Code from UPS Choice v1.7 - Fritz Clapp (aka dreamscape, thanks Fritz!)
function _selectOptions($select_array, $key_value, $key = '') {
	for ($i=0; $i<(sizeof($select_array)); $i++) {
		$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
		$string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
		$key_values = explode(", ", $key_value);
		if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
		$string .= '> ' . $select_array[$i];
	}
	return $string;
}
?>
