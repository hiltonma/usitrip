<?php
  class payjunction {
    var $code, $title, $description, $enabled;

    function payjunction() {
      global $order;

      $this->code = 'payjunction';
      $this->title = MODULE_PAYMENT_PAYJUNCTION_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYJUNCTION_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_PAYJUNCTION_STATUS == 'True') ? true : false);
      $this->sort_order = MODULE_PAYMENT_PAYJUNCTION_SORT_ORDER;

      if ((int)MODULE_PAYMENT_PAYJUNCTION_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYJUNCTION_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = 'https://payjunction.com/live/vendor/special/authorize_net';
    }

    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYJUNCTION_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYJUNCTION_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.pj_cardholder_name.value;' . "\n" .
            '    var cc_number = document.checkout_payment.pj_card_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_NAME_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_PAYJUNCTION_TEXT_JS_CC_NAME . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_PAYJUNCTION_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
		'';

	if (MODULE_PAYMENT_PAYJUNCTION_CODE == 'Yes')
	{
		$js =	'var cc_code = document.checkout_payment.pj_card_code.value;' . "\n" .
			'if (cc_code == "" || cc_code.length < 3) { error_message = error_message + "The CVV2/CVC2 code must be at least 3 digits."; error = 1; }' . "\n";

	}

	$js = $js .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_NAME,
                                                 'field' => tep_draw_input_field('pj_cardholder_name', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('pj_card_number')),
	(MODULE_PAYMENT_PAYJUNCTION_CODE == 'Yes' ?
		array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_CODE, 'field' => tep_draw_input_field('pj_card_code', '', 'size="4"')) :
		array()),
                                           array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('pj_card_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('pj_card_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_FS_CLASSES . 'cc_validation1.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['pj_card_number'], $HTTP_POST_VARS['pj_card_month'], $HTTP_POST_VARS['pj_card_year']);
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
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

	if ($result == false || $result < 1) {}
	elseif (MODULE_PAYMENT_PAYJUNCTION_CODE == 'Yes')
	{
      		$this->pj_card_code = $HTTP_POST_VARS['pj_card_code'];
		if ($this->pj_card_code == '' || strlen($this->pj_card_code) < 3)
		{
			$error = "The CVV2/CVC2 code must be at least 3 digits.";
			$result = false;
		}
	}

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&pj_cardholder_name=' . urlencode($HTTP_POST_VARS['pj_cardholder_name']) . '&pj_card_month=' . $HTTP_POST_VARS['pj_card_month'] . '&pj_card_year=' . $HTTP_POST_VARS['pj_card_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->pj_card_brand = $cc_validation->cc_type;
      $this->pj_card_number = $cc_validation->cc_number;
      $this->pj_card_month = $cc_validation->cc_expiry_month;
      $this->pj_card_year = $cc_validation->cc_expiry_year;
      $this->pj_card_code = $HTTP_POST_VARS['pj_card_code'];
    }

    function confirmation() {
      global $HTTP_POST_VARS;

      $confirmation = array('title' => $this->title . ': ' . $this->pj_card_brand,
                            'fields' => array(array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_NAME,
                                                    'field' => $HTTP_POST_VARS['pj_cardholder_name']),
                                              array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->pj_card_number, 0, 4) . str_repeat('X', (strlen($this->pj_card_number) - 8)) . substr($this->pj_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B %Y', mktime(0,0,0,$HTTP_POST_VARS['pj_card_month'], 1, '20' . $HTTP_POST_VARS['pj_card_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $HTTP_SERVER_VARS, $order, $customer_id;

      $sequence = rand(1, 1000);
      $process_button_string = tep_draw_hidden_field('x_Login', MODULE_PAYMENT_PAYJUNCTION_USERNAME) .
                               tep_draw_hidden_field('x_Card_Num', $this->pj_card_number) .
                               tep_draw_hidden_field('x_Exp_Date', $this->pj_card_month . substr($this->pj_card_year, -2)) .
                               tep_draw_hidden_field('x_Card_Code', $this->pj_card_code) .
                               tep_draw_hidden_field('x_Amount', number_format($order->info['total'], 2, '.', '')) .
                               tep_draw_hidden_field('x_Relay_URL', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
                               tep_draw_hidden_field('x_Relay_Response', 'TRUE') .
                               tep_draw_hidden_field('x_Method', 'CC') .
                               tep_draw_hidden_field('x_Version', '3.0') .
                               tep_draw_hidden_field('x_Cust_ID', $customer_id) .
                               tep_draw_hidden_field('x_first_name', $order->billing['firstname']) .
                               tep_draw_hidden_field('x_last_name', $order->billing['lastname']) .
                               tep_draw_hidden_field('x_address', $order->billing['street_address']) .
                               tep_draw_hidden_field('x_city', $order->billing['city']) .
                               tep_draw_hidden_field('x_state', $order->billing['state']) .
                               tep_draw_hidden_field('x_zip', $order->billing['postcode']) .
                               tep_draw_hidden_field('x_country', $order->billing['country']['title']) .
                               tep_draw_hidden_field('x_phone', $order->customer['telephone']) .
                               tep_draw_hidden_field('x_email', $order->customer['email_address']) .
                               tep_draw_hidden_field('x_ship_to_first_name', $order->delivery['firstname']) .
                               tep_draw_hidden_field('x_ship_to_last_name', $order->delivery['lastname']) .
                               tep_draw_hidden_field('x_ship_to_address', $order->delivery['street_address']) .
                               tep_draw_hidden_field('x_ship_to_city', $order->delivery['city']) .
                               tep_draw_hidden_field('x_ship_to_state', $order->delivery['state']) .
                               tep_draw_hidden_field('x_ship_to_zip', $order->delivery['postcode']) .
                               tep_draw_hidden_field('x_ship_to_country', $order->delivery['country']['title']) .
                               tep_draw_hidden_field('x_Customer_IP', $HTTP_SERVER_VARS['REMOTE_ADDR']) .
                               tep_draw_hidden_field('x_type', 'auth_capture');

      	if (MODULE_PAYMENT_PAYJUNCTION_TEST == 'Yes') $process_button_string .= tep_draw_hidden_field('x_Test_Request', 'TRUE');

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS;

      if ($HTTP_POST_VARS['x_response_code'] == '1') return;
      if ($HTTP_POST_VARS['x_response_code'] == '2') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_PAYJUNCTION_TEXT_DECLINED_MESSAGE), 'SSL', true, false));
      }
      // Code 3 is an error - but anything else is an error too (IMHO)
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_PAYJUNCTION_TEXT_ERROR_MESSAGE), 'SSL', true, false));
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_PAYJUNCTION_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYJUNCTION_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayJunction Module', 'MODULE_PAYMENT_PAYJUNCTION_STATUS', 'True', 'Do you want to accept PayJunction payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('username', 'MODULE_PAYMENT_PAYJUNCTION_USERNAME', 'commerce', 'The username for your PayJunction service', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Only test transactions?', 'MODULE_PAYMENT_PAYJUNCTION_TEST', 'Yes', 'Run only test transactions.', '6', '0', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Prompt for CVV2/CVC2 code?', 'MODULE_PAYMENT_PAYJUNCTION_CODE', 'No', 'Prompt for CVV2/CVC2 code for additional security.', '6', '0', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYJUNCTION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYJUNCTION_ZONE', '0', 'If a zone is selected, only enable this payment module for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYJUNCTION_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYJUNCTION_STATUS', 'MODULE_PAYMENT_PAYJUNCTION_USERNAME', 'MODULE_PAYMENT_PAYJUNCTION_CODE', 'MODULE_PAYMENT_PAYJUNCTION_TEST', 'MODULE_PAYMENT_PAYJUNCTION_ZONE', 'MODULE_PAYMENT_PAYJUNCTION_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYJUNCTION_SORT_ORDER');
    }
  }
?>
