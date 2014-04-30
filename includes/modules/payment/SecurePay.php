<?php
/*
Based on payment Module of iongate.php (06/11/2003) Modified for Securepay.com by:

Tony Reynolds  <tonyr@securepay.com>

SecurePay.php version 1.2 06/11/2003

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class SecurePay {
    var $code, $title, $description, $enabled, $states;

// class constructor
    function SecurePay() {
      $this->code = 'SecurePay';
      $this->title = MODULE_PAYMENT_SECUREPAY_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_SECUREPAY_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_SECUREPAY_STATUS == 'True') ? true : false);

      $this->form_action_url = 'https://www.securepay.com/oscommerce/index.cfm';
      $this->states = $this->_state_list();
    }

// class methods
    // this method returns the javascript that will validate the form entry
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.SecurePay_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.SecurePay_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_SECUREPAY_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_SECUREPAY_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    // this method returns the html that creates the input form
    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      if (MODULE_PAYMENT_SECUREPAY_ACCEPT_VISA == 'True') {
        $creditCardTypes[] = array('id' => 'VISA', 'text' => 'Visa');
      }
      if (MODULE_PAYMENT_SECUREPAY_ACCEPT_MASTERCARD == 'True') {
        $creditCardTypes[] = array('id' => 'MASTERCARD', 'text' => 'MasterCard');
      }
      if (MODULE_PAYMENT_SECUREPAY_ACCEPT_AMEX == 'True') {
        $creditCardTypes[] = array('id' => 'AMEX', 'text' => 'American Express');
      }
      if (MODULE_PAYMENT_SECUREPAY_ACCEPT_DISCOVER == 'True') {
        $creditCardTypes[] = array('id' => 'DISCOVER', 'text' => 'Discover');
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('SecurePay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_TYPE,
                                                 'field' => tep_draw_pull_down_menu('SecurePay_cc_type', $creditCardTypes)),
                                           array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('SecurePay_cc_number')),
                                           array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('SecurePay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('SecurePay_cc_expires_year', $expires_year))));

      return $selection;
    }

    // this method is called before the data is sent to the credit card processor
    // here you can do any field validation that you need to do
    // we also set the global variables here from the form values
    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_FS_CLASSES . 'cc_validation1.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['SecurePay_cc_number'], $HTTP_POST_VARS['SecurePay_cc_expires_month'], $HTTP_POST_VARS['SecurePay_cc_expires_year']);

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

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&SecurePay_cc_owner=' . urlencode($HTTP_POST_VARS['SecurePay_cc_owner']) . '&SecurePay_cc_type=' . urlencode($HTTP_POST_VARS['SecurePay_cc_type']) . '&SecurePay_cc_expires_month=' . $HTTP_POST_VARS['SecurePay_cc_expires_month'] . '&SecurePay_cc_expires_year=' . $HTTP_POST_VARS['SecurePay_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      // check the type the user said the card was, versus the type that cc_validation
      // says it is.

      if (($cc_validation->cc_type ==  'Visa' &&
           $HTTP_POST_VARS['SecurePay_cc_type'] != 'VISA') ||
          ($cc_validation->cc_type ==  'Master Card' &&
           $HTTP_POST_VARS['SecurePay_cc_type'] != 'MASTERCARD') ||
          ($cc_validation->cc_type ==  'American Express' &&
           $HTTP_POST_VARS['SecurePay_cc_type'] != 'AMEX') ||
          ($cc_validation->cc_type ==  'Discover' &&
           $HTTP_POST_VARS['SecurePay_cc_type'] != 'DISCOVER')) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode(MODULE_PAYMENT_SECUREPAY_TEXT_WRONG_TYPE) . '&SecurePay_cc_owner=' . urlencode($HTTP_POST_VARS['SecurePay_cc_owner']) . '&SecurePay_cc_type=' . urlencode($HTTP_POST_VARS['SecurePay_cc_type']) . '&SecurePay_cc_expires_month=' . $HTTP_POST_VARS['SecurePay_cc_expires_month'] . '&SecurePay_cc_expires_year=' . $HTTP_POST_VARS['SecurePay_cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_owner = $HTTP_POST_VARS['SecurePay_cc_owner'];
      $this->cc_card_type = $HTTP_POST_VARS['SecurePay_cc_type'];
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    // this method returns the data for the confirmation page
    function confirmation() {
      global $HTTP_POST_VARS;

      $confirmation = array('title' => $this->title,
                            'fields' => array(array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $HTTP_POST_VARS['SecurePay_cc_owner']),
                                              array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_TYPE,
                                                    'field' => $HTTP_POST_VARS['SecurePay_cc_type']),
                                              array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['SecurePay_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['SecurePay_cc_expires_year'])))));

      return $confirmation;
    }

    // this method performs the authorization by sending the data to the processor, and getting the result
    function process_button() {
      global $HTTP_SERVER_VARS, $order, $customer_id, $insert_id;

      $process_button_string = tep_draw_hidden_field('login', ((MODULE_PAYMENT_SECUREPAY_TESTMODE == 'Production') ? MODULE_PAYMENT_SECUREPAY_LOGIN : '34704')) .
                               tep_draw_hidden_field('amount', number_format($order->info['total'], 2, '.', '')) .
                               tep_draw_hidden_field('cardtype', $this->cc_card_type) .
                               tep_draw_hidden_field('cardnum', $this->cc_card_number) .
                               tep_draw_hidden_field('expires', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               tep_draw_hidden_field('cardname', $this->cc_card_owner) .
                               tep_draw_hidden_field('address', $order->billing['street_address']) .
                               tep_draw_hidden_field('address2', $order->billing['suburb']) .
                               tep_draw_hidden_field('city', $order->billing['city']) .
                               // SecurePay expects 2 digit capilalized state codes
                               tep_draw_hidden_field('state', $this->states[strtoupper($order->billing['state'])]) .
                               tep_draw_hidden_field('zip', $order->billing['postcode']) .
                               tep_draw_hidden_field('country', $order->billing['country']['title']) .
                               tep_draw_hidden_field('phone', $order->customer['telephone']) .
                               tep_draw_hidden_field('email', $order->customer['email_address']) .
                               tep_draw_hidden_field('merchemail', ((MODULE_PAYMENT_SECUREPAY_EMAIL_MERCHANT == 'True') ? MODULE_PAYMENT_SECUREPAY_MERCHANT_EMAIL : 'NO')) .
                               tep_draw_hidden_field('custno', $customer_id) .
                               // I would like to get the order number here
                               // but it isn't available under after this code
                               // executes.  So, we'll put the date in there
                               // so that we have something to reference.  If
                               // we get more than one order per second we
                               // have problems, but I guess that would be
                               // a pretty good problem to have :)
                               tep_draw_hidden_field('invoiceno', MODULE_PAYMENT_SECUREPAY_LOGIN . (MODULE_PAYMENT_SECUREPAY_LOGIN  + date('Ymd') + date('his')) . $customer_id) .
                               tep_draw_hidden_field('description', 'Order Submitted from IP: ' .  $HTTP_SERVER_VARS['REMOTE_ADDR'] . "\n" .  $products_list) .
                               tep_draw_hidden_field('receipturl', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
                               // if you don't set this, SecurePay will display input forms for any missing
                               // or invalid data.
                               tep_draw_hidden_field('returnallerrors', 'YES') .

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }

    // this method gets called after the processing is done but before the app server
    // accepts the result.  It is used to check for errors.
    function before_process() {
      global $HTTP_GET_VARS;

      if ($HTTP_GET_VARS['RESPONSE_CODE'] != 'AA') {
       tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode($HTTP_GET_VARS['AUTH_RESPONSE']), 'SSL', true, false));
      }
      Elseif ($HTTP_GET_VARS['RETURN_CODE'] != 'Y' ) {
       tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . urlencode($HTTP_GET_VARS['AUTH_RESPONSE']), 'SSL', true, false));
      }
      }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;
      $error = array('title' => MODULE_PAYMENT_SECUREPAY_TEXT_ERROR,
       'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECUREPAY_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable SecurePay Module', 'MODULE_PAYMENT_SECUREPAY_STATUS', 'True', 'Do you want to accept credit card payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SecurePay ID:', 'MODULE_PAYMENT_SECUREPAY_LOGIN', '34704', 'Your 5 digit Securepay ID:', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_SECUREPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Merchant Notifications', 'MODULE_PAYMENT_SECUREPAY_EMAIL_MERCHANT', 'True', 'Should SecurePay e-mail a receipt to the store owner?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchants Email Address', 'MODULE_PAYMENT_SECUREPAY_MERCHANT_EMAIL', 'demo@securepay.com', 'The Merchants email address?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept Visa', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_VISA', 'True', 'Should we accept Visa?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept Mastercard', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_MASTERCARD', 'True', 'Should we accept Mastercard?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept American Express', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_AMEX', 'True', 'Should we accept American Express?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept Discover', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_DISCOVER', 'True', 'Should we accept Discover?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept Diners Club', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_DINERS', 'True', 'Should we accept Diners Club?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accept JCB', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_JCB', 'True', 'Should we accept JCB?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_SECUREPAY_STATUS', 'MODULE_PAYMENT_SECUREPAY_LOGIN', 'MODULE_PAYMENT_SECUREPAY_TESTMODE', 'MODULE_PAYMENT_SECUREPAY_EMAIL_MERCHANT','MODULE_PAYMENT_SECUREPAY_MERCHANT_EMAIL', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_VISA', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_MASTERCARD', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_AMEX', 'MODULE_PAYMENT_SECUREPAY_ACCEPT_DISCOVER');
    }

    // this internal method returns an array keyed by state name, with a value of state code
    function _state_list() {
      $list = array('ALABAMA' => 'AL' ,
                    'ALASKA' => 'AK' ,
                    'AMERICAN SAMOA' => 'AS' ,
                    'ARIZONA' => 'AZ' ,
                    'ARKANSAS' => 'AR' ,
                    'CALIFORNIA' => 'CA' ,
                    'COLORADO' => 'CO' ,
                    'CONNECTICUT' => 'CT' ,
                    'DELAWARE' => 'DE' ,
                    'DISTRICT OF COLUMBIA' => 'DC' ,
                    'FEDERATED STATES OF MICRONESIA' => 'FM' ,
                    'FLORIDA' => 'FL' ,
                    'GEORGIA' => 'GA' ,
                    'GUAM' => 'GU' ,
                    'HAWAII' => 'HI' ,
                    'IDAHO' => 'ID' ,
                    'ILLINOIS' => 'IL' ,
                    'INDIANA' => 'IN' ,
                    'IOWA' => 'IA' ,
                    'KANSAS' => 'KS' ,
                    'KENTUCKY' => 'KY' ,
                    'LOUISIANA' => 'LA' ,
                    'MAINE' => 'ME' ,
                    'MARSHALL ISLANDS' => 'MH' ,
                    'MARYLAND' => 'MD' ,
                    'MASSACHUSETTS' => 'MA' ,
                    'MICHIGAN' => 'MI' ,
                    'MINNESOTA' => 'MN' ,
                    'MISSOURI' => 'MS' ,
                    'MONTANA' => 'MT' ,
                    'NEBRASKA' => 'NE' ,
                    'NEVADA' => 'NV' ,
                    'NEW HAMPSHIRE' => 'NH' ,
                    'NEW JERSEY' => 'NJ' ,
                    'NEW MEXICO' => 'NM' ,
                    'NEW YORK' => 'NY' ,
                    'NORTH CAROLINA' => 'NC' ,
                    'NORTH DAKOTA' => 'ND' ,
                    'NORTHERN MARIANA ISLANDS' => 'MP' ,
                    'OHIO' => 'OH' ,
                    'OKLAHOMA' => 'OK' ,
                    'OREGON' => 'OR' ,
                    'PALAU' => 'PW' ,
                    'PENNSYLVANIA' => 'PA' ,
                    'PUERTO RICO' => 'PR' ,
                    'RHODE ISLAND' => 'RI' ,
                    'SOUTH CAROLINA' => 'SC' ,
                    'SOUTH DAKOTA' => 'SD' ,
                    'TENNESSEE' => 'TN' ,
                    'TEXAS' => 'TX' ,
                    'UTAH' => 'UT' ,
                    'VERMONT' => 'VT' ,
                    'VIRGIN ISLANDS' => 'VI' ,
                    'VIRGINIA' => 'VA' ,
                    'WASHINGTON' => 'WA' ,
                    'WEST VIRGINIA' => 'WV' ,
                    'WISCONSIN' => 'WI' ,
                    'WYOMING' => 'WY' ,
                    'ARMED FORCES AFRICA' => 'AE' ,
                    'ARMED FORCES EUROPE' => 'AE' ,
                    'ARMED FORCES CANADA' => 'AE' ,
                    'ARMED FORCES MIDDLE EAST' => 'AE' ,
                    'ARMED FORCES AMERICAS' => 'AA' ,
                    'ARMED FORCES PACIFIC' => 'AP');

      return $list;
    }
  }
?>
