<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class authorizenet2 {
    var $code, $title, $description, $enabled, $sort_order, $order_status, $form_action_url;
    var $cc_card_type, $cc_card_number, $cc_expiry_month, $cc_expiry_year;

// class constructor
    function authorizenet2() {
      global $order;

      $this->code = 'authorizenet2';
      $this->title = MODULE_PAYMENT_AUTHORIZENET2_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_AUTHORIZENET2_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_AUTHORIZENET2_STATUS == 'True') ? true : false);
      $this->sort_order = MODULE_PAYMENT_AUTHORIZENET2_SORT_ORDER;

      if ((int)MODULE_PAYMENT_AUTHORIZENET2_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_AUTHORIZENET2_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      if (MODULE_PAYMENT_AUTHORIZENET2_GATEWAY_METHOD == 'SIM') {
        $this->form_action_url = 'https://secure.authorize.net/gateway/transact.dll';
      // } else $this->process_action_url = 'https://certification.authorize.net/gateway/transact.dll';
      } else $this->process_action_url = 'https://secure.authorize.net/gateway/transact.dll';
    }

// Authorize.net utility functions
// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.

// Main Interfaces:
//
// function InsertFP ($loginid, $txnkey, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp) - Returns Fingerprint.

// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
// function hmac ($key, $data) {
//   return (bin2hex (mhash(MHASH_MD5, $data, $key)));
//}

// Thanks is lance from http://www.php.net/manual/en/function.mhash.php
//lance_rushing at hot* spamfree *mail dot com
//27-Nov-2002 09:36 
// 
//Want to Create a md5 HMAC, but don't have hmash installed?
//
//Use this:

function hmac ($key, $data)
{
   // RFC 2104 HMAC implementation for php.
   // Creates an md5 HMAC.
   // Eliminates the need to install mhash to compute a HMAC
   // Hacked by Lance Rushing

   $b = 64; // byte length for md5
   if (strlen($key) > $b) {
       $key = pack("H*",md5($key));
   }
   $key  = str_pad($key, $b, chr(0x00));
   $ipad = str_pad('', $b, chr(0x36));
   $opad = str_pad('', $b, chr(0x5c));
   $k_ipad = $key ^ $ipad ;
   $k_opad = $key ^ $opad;

   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
}
// end code from lance (resume authorize.net code)

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp, $currency = "") {
  return ($this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
}

// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP ($loginid, $txnkey, $amount, $sequence, $currency = "") {
  $tstamp = time ();
  $fingerprint = $this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

  $vars = array('x_fp_sequence' => $sequence,
                'x_fp_timestamp' => $tstamp,
                'x_fp_hash' => $fingerprint);

  return $vars;
}
// end authorize.net code

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_AUTHORIZENET2_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_AUTHORIZENET2_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
            '    var cc_owner = document.checkout_payment.authorizenet2_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet2_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET2_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET2_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
        for ($i=1; $i<13; $i++) {
          $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }
        $selection = array('id' => $this->code,
                           'module' => $this->title,
                           'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_OWNER,
                                                   'field' => tep_draw_input_field('authorizenet2_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_NUMBER,
                                                   'field' => tep_draw_input_field('authorizenet2_cc_number')),
                                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_EXPIRES,
                                                   'field' => tep_draw_pull_down_menu('authorizenet2_cc_expires_month', $expires_month, $today['mon']) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet2_cc_expires_year', $expires_year))));
      } else { // eCheck
        $acct_types = array(array('id' => 'CHECKING', 'text' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_TYPE_CHECK),
                            array('id' => 'SAVINGS', 'text' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_TYPE_SAVINGS));
        $org_types = array(array('id' => 'I', 'text' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_ORG_PERSONAL),
                           array('id' => 'B', 'text' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_ORG_BUSINESS));
        $fields = array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_NAME,
                              'field' => tep_draw_input_field('authorizenet2_bank_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_TYPE,
                              'field' => tep_draw_pull_down_menu('authorizenet2_bank_acct_type', $acct_types)),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_NAME,
                              'field' => tep_draw_input_field('authorizenet2_bank_name')),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ABA_CODE,
                              'field' =>  tep_image('images/symbol_route.gif') . tep_draw_input_field('authorizenet2_bank_aba') . tep_image('images/symbol_route.gif')), 
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_NUM,
                              'field' => tep_draw_input_field('authorizenet2_bank_acct'). tep_image('images/symbol_account.gif')),
array('title' =>  MODULE_PAYMENT_AUTHORIZENET2_TEXT_SAMPLE_CHECK_NAME, 'field' => tep_image('images/checksample.gif')));


        if (MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO == 'Yes') { // Add extra fields
          $fields_wf = array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_ORG,
                                   'field' => tep_draw_pull_down_menu('wellsfargo_org_type', $org_types)),
                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_INTRO,
                                   'field' => ''),
                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_TAXID,
                                   'field' => tep_draw_input_field('wellsfargo_taxid')),
                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_DLNUM,
                                   'field' => tep_draw_input_field('wellsfargo_dlnum')),
                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_STATE,
                                   'field' => tep_draw_input_field('wellsfargo_state')),
                             array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_DOB,
                                   'field' => tep_draw_input_field('wellsfargo_dob')));
           $fields = array_merge($fields, $fields_wf);
        }
        $selection = array('id' => $this->code,
                           'module' => $this->title,
                           'fields' => $fields);
      }
      return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
        include(DIR_WS_CLASSES . 'cc_validation.php');

        $cc_validation = new cc_validation();
        $result = $cc_validation->validate($HTTP_POST_VARS['authorizenet2_cc_number'], $HTTP_POST_VARS['authorizenet2_cc_expires_month'], $HTTP_POST_VARS['authorizenet2_cc_expires_year']);
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
          $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet2_cc_owner=' . urlencode($HTTP_POST_VARS['authorizenet2_cc_owner']) . '&authorizenet2_cc_expires_month=' . $HTTP_POST_VARS['authorizenet2_cc_expires_month'] . '&authorizenet2_cc_expires_year=' . $HTTP_POST_VARS['authorizenet2_cc_expires_year'];

          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
        }

        $this->cc_card_type = $cc_validation->cc_type;
        $this->cc_card_number = $cc_validation->cc_number;
        $this->cc_expiry_month = $cc_validation->cc_expiry_month;
        $this->cc_expiry_year = $cc_validation->cc_expiry_year;
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS;

      if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
        $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                              'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_OWNER,
                                                      'field' => $HTTP_POST_VARS['authorizenet2_cc_owner']),
                                                array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_NUMBER,
                                                      'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                                array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_CREDIT_CARD_EXPIRES,
                                                      'field' => strftime('%B, %Y', mktime(0,0,0,$this->cc_expiry_month, 1, $this->cc_expiry_year)))));
      } else { // eCheck
        $fields = array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_NAME,
                              'field' => $HTTP_POST_VARS['authorizenet2_bank_owner']),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_TYPE,
                              'field' => $HTTP_POST_VARS['authorizenet2_bank_acct_type']),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_NAME,
                              'field' => $HTTP_POST_VARS['authorizenet2_bank_name']),
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ABA_CODE,
                              'field' => $HTTP_POST_VARS['authorizenet2_bank_aba']), 
                        array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_BANK_ACCT_NUM,
                              'field' => $HTTP_POST_VARS['authorizenet2_bank_acct']));  

        if (MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO == 'Yes') { // Add extra fields
          if (tep_not_null($HTTP_POST_VARS['wellsfargo_taxid'])) {
            $fields_wf = array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_TAXID,
                                     'field' => $HTTP_POST_VARS['wellsfargo_taxid']));
          } else {
            $fields_wf = array(array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_DLNUM,
                                     'field' => $HTTP_POST_VARS['wellsfargo_dlnum']),
                               array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_STATE,
                                     'field' => $HTTP_POST_VARS['wellsfargo_state']),
                               array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_WF_DOB,
                                     'field' => $HTTP_POST_VARS['wellsfargo_dob']));
          }
          $fields = array_merge($fields, $fields_wf);
        }
        $confirmation = array('title' => $this->title . ' : eCheck',
                              'fields' => $fields);
      }
      return $confirmation;
    }

    function make_gateway_vars() {
      global $HTTP_SERVER_VARS, $order, $customer_id, $HTTP_POST_VARS;

      if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
        $gw_pay_type = array('x_Card_Num' => $this->cc_card_number,
                             'x_Exp_Date' => $this->cc_expiry_month . substr($this->cc_expiry_year, -2),
                             'x_Type' => MODULE_PAYMENT_AUTHORIZENET2_CREDIT_CAPTURE,
                             'x_Method' => 'CC');
      }
      if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'eCheck') {
          $this->ec_bank_owner = $HTTP_POST_VARS['authorizenet2_bank_owner'];
          $this->ec_bank_acct_type = $HTTP_POST_VARS['authorizenet2_bank_acct_type'];
          $this->ec_bank_name = $HTTP_POST_VARS['authorizenet2_bank_name'];
          $this->ec_bank_aba = $HTTP_POST_VARS['authorizenet2_bank_aba'];
          $this->ec_bank_acct = $HTTP_POST_VARS['authorizenet2_bank_acct'];

        $gw_pay_type = array('x_bank_acct_name' => $this->ec_bank_owner,
                             'x_bank_acct_type' => $this->ec_bank_acct_type,
                             'x_bank_name' => $this->ec_bank_name,
                             'x_bank_aba_code' => $this->ec_bank_aba,
                             'x_bank_acct_num' => $this->ec_bank_acct,
                             'x_Type' => 'AUTH_CAPTURE',
                             'x_echeck_type' => 'WEB',
                             'x_Method' => 'ECHECK');

        if (MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO == 'Yes') { // Add extra fields
          if (tep_not_null($this->wf_taxid)) {
            $gw_pay_type2 = array('x_customer_tax_id' => $this->wf_taxid,
                                  'x_customer_organization_type' => $this->wf_org_type);
          } else {
            $gw_pay_type2 = array('x_drivers_license_number' => $this->wf_dlnum,
                                  'x_drivers_license_state' => $this->wf_state,
                                  'x_drivers_license_dob' => $this->wf_dob,
                                  'x_customer_organization_type' => $this->wf_org_type);
          }
          $gw_pay_type = array_merge($gw_pay_type, $gw_pay_type2);
        }
      }
      $gw_common= array('x_Login' => MODULE_PAYMENT_AUTHORIZENET2_LOGIN, 
//                        'x_tran_key' => MODULE_PAYMENT_AUTHORIZENET2_TXNKEY,
                        'x_Amount' => number_format($order->info['total'], 2),
                        'x_Version' => '3.0',
                        'x_Cust_ID' => $customer_id,
                        'x_Email_Customer' => ((MODULE_PAYMENT_AUTHORIZENET2_EMAIL_CUSTOMER == 'True') ? 'TRUE': 'FALSE'),
                        'x_first_name' => $order->billing['firstname'],
                        'x_last_name' => $order->billing['lastname'],
                        'x_company' => $order->billing['company'],
                        'x_address' => $order->billing['street_address'],
                        'x_city' => $order->billing['city'],
                        'x_state' => $order->billing['state'],
                        'x_zip' => $order->billing['postcode'],
                        'x_country' => $order->billing['country']['title'],
                        'x_phone' => $order->customer['telephone'],
                        'x_email' => $order->customer['email_address'],
                        'x_ship_to_first_name' => $order->delivery['firstname'],
                        'x_ship_to_last_name' => $order->delivery['lastname'],
                        'x_ship_to_address' => $order->delivery['street_address'],
                        'x_ship_to_city' => $order->delivery['city'],
                        'x_ship_to_state' => $order->delivery['state'],
                        'x_ship_to_zip' => $order->delivery['postcode'],
                        'x_ship_to_country' => $order->delivery['country']['title'],
                        'x_Customer_IP' => $HTTP_SERVER_VARS['REMOTE_ADDR']);

      $gw_vars = array_merge($gw_common, $gw_pay_type);
      return $gw_vars;
    }

    function process_button() {

      $process_button_string = '';

      if (MODULE_PAYMENT_AUTHORIZENET2_GATEWAY_METHOD == 'SIM') {
        $gw_vars = $this->make_gateway_vars();
        $sequence = rand(1, 1000);
        $gw_vars = array_merge($gw_vars, $this->InsertFP(MODULE_PAYMENT_AUTHORIZENET2_LOGIN, MODULE_PAYMENT_AUTHORIZENET2_TXNKEY, $gw_vars['x_Amount'], $sequence));
        $gw_vars['x_Relay_URL'] = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false);
        $gw_vars['x_Relay_Response'] = 'TRUE';
        $gw_vars['x_delim_data'] = 'TRUE';
        $gw_vars['x_delim_char'] = '|';
        if (MODULE_PAYMENT_AUTHORIZENET2_TESTMODE == 'Test') $gw_vars['x_Test_Request'] = 'TRUE';
        $gw_vars[tep_session_name()] = tep_session_id();
        reset($gw_vars);
        while (list($key, $value) = each($gw_vars)) {
          $process_button_string .= tep_draw_hidden_field($key, $value) . "\n";
        }
      } else {
        if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
          $process_button_string .= tep_draw_hidden_field('authorizenet2_cc_number', $this->cc_card_number) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_cc_expiry_month', $this->cc_expiry_month) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_cc_expiry_year', $this->cc_expiry_year) . "\n";
        } else { // eCheck
          $process_button_string .= tep_draw_hidden_field('authorizenet2_bank_owner', $HTTP_POST_VARS['authorizenet2_bank_owner']) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_bank_acct_type', $HTTP_POST_VARS['authorizenet2_bank_acct_type']) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_bank_name', $HTTP_POST_VARS['authorizenet2_bank_name']) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_bank_aba', $HTTP_POST_VARS['authorizenet2_bank_aba']) . "\n";
          $process_button_string .= tep_draw_hidden_field('authorizenet2_bank_acct', $HTTP_POST_VARS['authorizenet2_bank_acct']) . "\n";
          if (MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO == 'Yes') { // Add extra fields
            $process_button_string .= tep_draw_hidden_field('wellsfargo_taxid', $HTTP_POST_VARS['wellsfargo_taxid']) . "\n";
            $process_button_string .= tep_draw_hidden_field('wellsfargo_dlnum', $HTTP_POST_VARS['wellsfargo_dlnum']) . "\n";
            $process_button_string .= tep_draw_hidden_field('wellsfargo_state', $HTTP_POST_VARS['wellsfargo_state']) . "\n";
            $process_button_string .= tep_draw_hidden_field('wellsfargo_dob', $HTTP_POST_VARS['wellsfargo_dob']) . "\n";
            $process_button_string .= tep_draw_hidden_field('wellsfargo_org_type', $HTTP_POST_VARS['wellsfargo_org_type']) . "\n";
          }
        }
      }
      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS;

      if (MODULE_PAYMENT_AUTHORIZENET2_GATEWAY_METHOD == 'AIM') {
        if (MODULE_PAYMENT_AUTHORIZENET2_METHOD == 'Credit Card') {
          $this->cc_card_number = $HTTP_POST_VARS['authorizenet2_cc_number'];
          $this->cc_expiry_month = $HTTP_POST_VARS['authorizenet2_cc_expiry_month'];
          $this->cc_expiry_year = $HTTP_POST_VARS['authorizenet2_cc_expiry_year'];
        } else { // eCheck
          $this->ec_bank_owner = $HTTP_POST_VARS['authorizenet2_bank_owner'];
          $this->ec_bank_acct_type = $HTTP_POST_VARS['authorizenet2_bank_acct_type'];
          $this->ec_bank_name = $HTTP_POST_VARS['authorizenet2_bank_name'];
          $this->ec_bank_aba = $HTTP_POST_VARS['authorizenet2_bank_aba'];
          $this->ec_bank_acct = $HTTP_POST_VARS['authorizenet2_bank_acct'];
          if (MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO == 'Yes') { // Add extra fields
            $this->wf_taxid = $HTTP_POST_VARS['wellsfargo_taxid'];
            $this->wf_dlnum = $HTTP_POST_VARS['wellsfargo_dlnum'];
            $this->wf_state = $HTTP_POST_VARS['wellsfargo_state'];
            $this->wf_dob = $HTTP_POST_VARS['wellsfargo_dob'];
            $this->wf_org_type = $HTTP_POST_VARS['wellsfargo_org_type'];
          }
        }
        $gw_vars = $this->make_gateway_vars();
        $sequence = rand(1, 1000);
        // $gw_vars = array_merge($gw_vars, $this->InsertFP(MODULE_PAYMENT_AUTHORIZENET2_LOGIN, MODULE_PAYMENT_AUTHORIZENET2_TXNKEY, $gw_vars['X_Amount'], $sequence));
        if (MODULE_PAYMENT_AUTHORIZENET2_TESTMODE == 'Test') $gw_vars['x_Test_Request'] = 'TRUE';
        $gw_vars[tep_session_name()] = tep_session_id();
        $gw_vars['x_delim_data'] = 'TRUE';
        $gw_vars['x_delim_char'] = '|';
        $gw_vars['x_relay_response'] = 'FALSE';
        reset($gw_vars);
        $curl_opts = $this->process_action_url;
        while (list($key, $value) = each($gw_vars)) {
          $curl_opts .= " -d " . $key . "=" . urlencode($value);
        }
        $handle = popen("/usr/bin/curl " . $curl_opts, "r");
        $str = '';
        while (!feof($handle)) {
          $str .= fread($handle, 2048);
        }
        pclose($handle);
        $result = explode("|", urldecode($str));
        $x_response_code = $result[1];
        $x_response_reason_text = $result[4];
      } else {
        $x_response_code = $HTTP_POST_VARS['x_response_code'];
        $x_response_reason_text = $HTTP_POST_VARS['x_response_reason_text'];
      }
      if ($x_response_code == '1') return;
      if ($x_response_code == '2') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET2_TEXT_DECLINED_MESSAGE.$x_response_reason_text), 'SSL', true, false));
      }
      // Code 3 is an error - but anything else is an error too (IMHO)
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET2_TEXT_ERROR_MESSAGE.$x_response_reason_text) . '&error=' . urlencode($x_response_reason_text), 'SSL', true, false));
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_AUTHORIZENET2_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET2_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Authorize.net Module', 'MODULE_PAYMENT_AUTHORIZENET2_STATUS', 'True', 'Do you want to accept Authorize.net payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_AUTHORIZENET2_LOGIN', 'testing', 'The login username used for the Authorize.net service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Transaction Key', 'MODULE_PAYMENT_AUTHORIZENET2_TXNKEY', 'Test', 'Transaction Key used for encrypting TP data', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_AUTHORIZENET2_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_AUTHORIZENET2_METHOD', 'Credit Card', 'Transaction method used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Gateway Method', 'MODULE_PAYMENT_AUTHORIZENET2_GATEWAY_METHOD', 'AIM', 'Gateway transaction method used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'AIM\', \'SIM\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_AUTHORIZENET2_EMAIL_CUSTOMER', 'False', 'Should Authorize.Net e-mail a receipt to the customer?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_AUTHORIZENET2_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_AUTHORIZENET2_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_AUTHORIZENET2_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Credit Card Mode', 'MODULE_PAYMENT_AUTHORIZENET2_CREDIT_CAPTURE', 'AUTH_CAPTURE', 'Credit Card processing method. Authorize Only or Authorize and Capture (Collect Funds)', '6', '0', 'tep_cfg_select_option(array(\'AUTH_CAPTURE\', \'AUTH_ONLY\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Wells Fargo Secure Source Account?', 'MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO', 'No', 'Set to YES if your account is with Wells Fargo', '6', '0', 'tep_cfg_select_option(array(\'No\', \'Yes\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_AUTHORIZENET2_STATUS', 'MODULE_PAYMENT_AUTHORIZENET2_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET2_TXNKEY', 'MODULE_PAYMENT_AUTHORIZENET2_GATEWAY_METHOD', 'MODULE_PAYMENT_AUTHORIZENET2_TESTMODE', 'MODULE_PAYMENT_AUTHORIZENET2_METHOD', 'MODULE_PAYMENT_AUTHORIZENET2_CREDIT_CAPTURE', 'MODULE_PAYMENT_AUTHORIZENET2_WELLSFARGO', 'MODULE_PAYMENT_AUTHORIZENET2_EMAIL_CUSTOMER', 'MODULE_PAYMENT_AUTHORIZENET2_ZONE', 'MODULE_PAYMENT_AUTHORIZENET2_ORDER_STATUS_ID', 'MODULE_PAYMENT_AUTHORIZENET2_SORT_ORDER');
    }
  }
?>
