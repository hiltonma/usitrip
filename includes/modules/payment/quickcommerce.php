<?php
/*
  $Id: quickcommerce.php,v 1.4 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  This modules based on Quickcommerce Consolidated 1.7
   Author: Austin Renfroe (Austin519), some code thanks to dreamscape
  Email: Austin519@aol.com

*/

 define('FILENAME_QUICKCOMMERCE_HELP', 'quickcom_help.php');

  class quickcommerce {
    var $code, $title, $description, $enabled, $sort_order;
    var $accepted_cc, $card_types, $allowed_types;

// class constructor
  function quickcommerce() {
    global $order;
    $this->code = 'quickcommerce';
    $this->title = MODULE_PAYMENT_QUICKCOMMERCE_TEXT_TITLE;
    $this->description = MODULE_PAYMENT_QUICKCOMMERCE_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_PAYMENT_QUICKCOMMERCE_SORT_ORDER;
    $this->enabled = ((MODULE_PAYMENT_QUICKCOMMERCE_STATUS == 'True') ? true : false);
    $this->accepted_cc = MODULE_PAYMENT_QUICKCOMMERCE_ACCEPTED_CC;

    if ((int)MODULE_PAYMENT_QUICKCOMMERCE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_QUICKCOMMERCE_ORDER_STATUS_ID;
    }

    if (is_object($order)) $this->update_status();

    //array for credit card selection
    $this->card_types = array('Amex' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_AMEX,
				'Mastercard' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_MASTERCARD,
				'Discover' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_DISCOVER,
				'Visa' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_VISA);
    $this->allowed_types = array();

    // Credit card pulldown list
    $cc_array = explode(', ', MODULE_PAYMENT_QUICKCOMMERCE_ACCEPTED_CC);
    while (list($key, $value) = each($cc_array)) {
      $this->allowed_types[$value] = $this->card_types[$value];
    }

    // Processing via Quickcommerce AIM
    $this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false);
  }

// class methods
  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_QUICKCOMMERCE_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_QUICKCOMMERCE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
   if(MODULE_PAYMENT_QUICKCOMMERCE_CCV	== 'True' ) {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.quickcommerce_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.quickcommerce_cc_number.value;' . "\n" .
            '    var cc_cvv = document.checkout_payment.cvv.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
             '    if (cc_cvv != "" && cc_cvv.length < "3") {' . "\n".
            '      error_message = error_message + "' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_JS_CC_CVV . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";
          }else{
    $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.quickcommerce_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.quickcommerce_cc_number.value;' . "\n" .
            '    var cc_cvv = document.checkout_payment.cvv.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";
    }

      return $js;
    }

    function selection() {
      global $order;
	reset($this->allowed_types);
	while (list($key, $value) = each($this->allowed_types)) {
		$card_menu[] = array('id' => $key, 'text' => $value);
	}

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
if(MODULE_PAYMENT_QUICKCOMMERCE_CCV	== 'True' ) {
	$selection = array('id' => $this->code,
		'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images(),
		'fields' => array(array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_TYPE,
			'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_OWNER,
			'field' => tep_draw_input_field('quickcommerce_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_NUMBER,
			'field' => tep_draw_input_field('quickcommerce_cc_number')),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_EXPIRES,
			'field' => tep_draw_pull_down_menu('quickcommerce_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('quickcommerce_cc_expires_year', $expires_year)),
	    array('title' => 'CVV number ' . ' ' .'<a href="javascript:CVVPopUpWindow(\'' . tep_href_link('cvv.html') . '\')">' . '<u><i>' . '(' . MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CVV_LINK . ')' . '</i></u></a>',
			'field' => tep_draw_input_field('cvv','',"SIZE=4, MAXLENGTH=4"))));
 }else{
$selection = array('id' => $this->code,
		'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images(),
		'fields' => array(array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_TYPE,
			'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_OWNER,
			'field' => tep_draw_input_field('quickcommerce_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_NUMBER,
			'field' => tep_draw_input_field('quickcommerce_cc_number')),
		array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_EXPIRES,
			'field' => tep_draw_pull_down_menu('quickcommerce_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('quickcommerce_cc_expires_year', $expires_year))));
 }
     return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS, $cvv;
      include(DIR_FS_CLASSES . 'cc_validation.php');
      $cc_validation = new cc_validation();
	$result = $cc_validation->validate($HTTP_POST_VARS['quickcommerce_cc_number'], $HTTP_POST_VARS['quickcommerce_cc_expires_month'], $HTTP_POST_VARS['quickcommerce_cc_expires_year'], $HTTP_POST_VARS['cvv'], $HTTP_POST_VARS['credit_card_type']);
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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&quickcommerce_cc_owner=' . urlencode($HTTP_POST_VARS['quickcommerce_cc_owner']) . '&quickcommerce_cc_expires_month=' . $HTTP_POST_VARS['quickcommerce_cc_expires_month'] . '&quickcommerce_cc_expires_year=' . $HTTP_POST_VARS['quickcommerce_cc_expires_year'];
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
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
                            'fields' => array(array('title' => 'CVV number',
                                                    'field' => $HTTP_POST_VARS['cvv']),
                                                    array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $HTTP_POST_VARS['quickcommerce_cc_owner']),
                                              array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['quickcommerce_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['quickcommerce_cc_expires_year'])))));
      $x_Card_Code=$HTTP_POST_VARS['cvv'];
      return $confirmation;
    }

    function process_button() {
	   // Change made by using ADC Direct Connection

      $process_button_string = tep_draw_hidden_field('x_Card_Code', $HTTP_POST_VARS['cvv']) .
                               tep_draw_hidden_field('x_Card_Num', $this->cc_card_number) .
                               tep_draw_hidden_field('x_Exp_Date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2));

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());
      return $process_button_string;
    }

    function before_process() {
  	  global $response;

	    // Change made by using ADC Direct Connection
		  $response_vars = explode(',', $response[0]);
  		$x_response_code = $response_vars[0];
	  	$x_response_subcode = $response_vars[1];
		  $x_response_reason_code = $response_vars[2];
  		$x_response_reason_text = $response_vars[3];

	    if ($x_response_code != '1') {
		    tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$insert_id . "'"); //Remove order
  		  if($x_response_code == '') {
	  		  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode('The server cannot connect to Quickcommerce.  Please check your cURL and server settings.'), 'SSL', true, false));
  		  } else if($x_response_code == '2') {
    			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode('Your credit card was declined ') . urlencode('(') . urlencode("$x_response_reason_code") . urlencode('): ') . urlencode("$x_response_reason_text"), 'SSL', true, false));
	    	} else if($x_response_code == '3') {
  		  	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode('There was an error processing your credit card ') . urlencode('(') . urlencode("$x_response_reason_code") . urlencode('): ') . urlencode("$x_response_reason_text"), 'SSL', true, false));
  	  	} else {
	  	    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode('There was an unspecified error processing your credit card.'), 'SSL', true, false));
		    }
  	  }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_QUICKCOMMERCE_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_QUICKCOMMERCE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) values ('Authorizenet - Setup Help', 'MODULE_PAYMENT_QUICKCOMMERCE_HELP', '<a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_QUICKCOMMERCE_HELP, '', 'NONSSL') . "\" target=\"authnetHelp\"> [Setup Help]</a><br>', '<a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_QUICKCOMMERCE_HELP, '', 'NONSSL') . "\" target=\"authnetHelp\"> [Setup Help]</a><br>', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Quickcommerce Module', 'MODULE_PAYMENT_QUICKCOMMERCE_STATUS', 'True', 'Do you want to accept payments through Quickcommerce?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_QUICKCOMMERCE_LOGIN', 'Your Login Name', 'The login username used for the Quickcommerce service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Transaction Key', 'MODULE_PAYMENT_QUICKCOMMERCE_TRANSKEY', 'Your Transaction Key', 'The transaction key used for the Quickcommerce service', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('cURL Setup', 'MODULE_PAYMENT_QUICKCOMMERCE_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', '6', '0', 'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Path', 'MODULE_PAYMENT_QUICKCOMMERCE_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_QUICKCOMMERCE_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_QUICKCOMMERCE_METHOD', 'Credit Card', 'Transaction method used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Processing Mode', 'MODULE_PAYMENT_QUICKCOMMERCE_CCMODE', 'Authorize And Capture', 'Credit card processing mode', '6', '0', 'tep_cfg_select_option(array(\'Authorize And Capture\', \'Authorize Only\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order Of Display', 'MODULE_PAYMENT_QUICKCOMMERCE_SORT_ORDER', '200', 'The order in which this payment type is dislayed. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_QUICKCOMMERCE_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_QUICKCOMMERCE_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', '6', '0', '_selectOptionsq(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Authorizenet - Payment Zone', 'MODULE_PAYMENT_QUICKCOMMERCE_ZONE', '0', 'Authorizenet - If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CCV code', 'MODULE_PAYMENT_QUICKCOMMERCE_CCV', 'True', 'Do you want to enable ccv code checking?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Authorizenet - Set Order Status', 'MODULE_PAYMENT_QUICKCOMMERCE_ORDER_STATUS_ID', '0', 'Authorizenet - Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
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
      return array('MODULE_PAYMENT_QUICKCOMMERCE_STATUS', 'MODULE_PAYMENT_QUICKCOMMERCE_LOGIN', 'MODULE_PAYMENT_QUICKCOMMERCE_TRANSKEY', 'MODULE_PAYMENT_QUICKCOMMERCE_CURL', 'MODULE_PAYMENT_QUICKCOMMERCE_CURL_PATH', 'MODULE_PAYMENT_QUICKCOMMERCE_TESTMODE', 'MODULE_PAYMENT_QUICKCOMMERCE_METHOD', 'MODULE_PAYMENT_QUICKCOMMERCE_CCMODE', 'MODULE_PAYMENT_QUICKCOMMERCE_SORT_ORDER', 'MODULE_PAYMENT_QUICKCOMMERCE_EMAIL_CUSTOMER', 'MODULE_PAYMENT_QUICKCOMMERCE_ACCEPTED_CC', 'MODULE_PAYMENT_QUICKCOMMERCE_ZONE', 'MODULE_PAYMENT_QUICKCOMMERCE_ORDER_STATUS_ID', 'MODULE_PAYMENT_QUICKCOMMERCE_CCV','MODULE_PAYMENT_QUICKCOMMERCE_HELP');
    }
  }

// Quickcommerce Consolidated Credit Card Checkbox Implementation
// Code from UPS Choice v1.7 - Fritz Clapp (aka dreamscape, thanks Fritz!)
function _selectOptionsq($select_array, $key_value, $key = '') {
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
