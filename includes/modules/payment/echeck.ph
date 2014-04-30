<?php
/*
  $Id: echeck.php, v3.1 11/28/2004
  
  Online eCheck payment modual
  as we would call it in Holland.

  Acquire the customer's checking account info
  for processing an eCheck. Information is sent
  by e-mail to the address you provide in admin
  area. Then use your eChex 2000 Check software
  to deposit your new eCheck payment without the
  need of a signature.

  I recommend eChex 2000 - Print and deposit
  eCheck payents WITHOUT having to have a signature.
  Accept checks by phone, fax and e-mail. This is
  for US Residents and bank account holders only.

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class echeck {
    var $code, $title, $description, $enabled;

// class constructor
    function echeck() {
      global $order;

      $this->code = 'echeck';
      $this->title = MODULE_PAYMENT_ECHECK_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_ECHECK_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_ECHECK_SORT_ORDER;
      $this->email_footer = MODULE_PAYMENT_ECHECK_TEXT_EMAIL_FOOTER;
      $this->enabled = ((MODULE_PAYMENT_ECHECK_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_ECHECK_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_ECHECK_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// Class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_ECHECK_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_ECHECK_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      $jsvalidation = '  if (payment_value == "' . $this->code . '") {' . "\n" .
                      '  var accountholder = document.checkout_payment.accountholder.value;' . "\n" .
                      '  var address = document.checkout_payment.address.value;' . "\n" .
                      '  var address2 = document.checkout_payment.address2.value;' . "\n" .
                      '  var phone = document.checkout_payment.phone.value;' . "\n" .
                      '  var bank = document.checkout_payment.bank.value;' . "\n" .
                      '  var bankcity = document.checkout_payment.bankcity.value;' . "\n" . 
                      '  var bankphone = document.checkout_payment.bankphone.value;' . "\n" . 
                      '  var checknumber = document.checkout_payment.checknumber.value;' . "\n" .
                      '  var accountnumber = document.checkout_payment.accountnumber.value;' . "\n" .
                      '  var routingnumber = document.checkout_payment.routingnumber.value;' . "\n" .
                      '  if (accountholder.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ACCOUNTHOLDER . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (address.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ADDRESS . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (address2.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ADDRESS2 . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (phone.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_PHONE . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (bank.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_BANK . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (bankcity.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_BANKCITY . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (bankphone.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_BANKPHONE . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (checknumber.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_CHECKNUMBER . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (accountnumber.length < 3) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ACCOUNTNUMBER . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (routingnumber.length == 0) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ROUTINGNUMBER . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '  if (routingnumber.length < 9) {' . "\n" .
                      '    error_message = error_message + "' . MODULE_PAYMENT_ECHECK_TEXT_JS_ROUTINGNUMBER2 . '";' . "\n" .
                      '    error = 1;' . "\n" .
                      '  }' . "\n" .
                      '}' . "\n";
      return $jsvalidation;
    }

    function selection() {
      global $order;

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_ECHECK_TEXT_ACCOUNTHOLDER,
                                                 'field' => tep_draw_input_field('accountholder', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_ADDRESS,
                                                 'field' => tep_draw_input_field('address', $order->billing['street_address'])),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_ADDRESS2,
                                                 'field' => tep_draw_input_field('address2', $order->billing['city'] . ', ' . $order->billing['state'] . ' ' . $order->billing['postcode'])),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_PHONE,
                                                 'field' => tep_draw_input_field('phone')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_BANK,
                                                 'field' => tep_draw_input_field('bank')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_BANKCITY,
                                                 'field' => tep_draw_input_field('bankcity')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_BANKPHONE,
                                                 'field' => tep_draw_input_field('bankphone')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_CHECKNUMBER,
                                                 'field' => tep_draw_input_field('checknumber')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_ACCOUNTNUMBER,
                                                 'field' => tep_draw_input_field('accountnumber')),
                                           array('title' => MODULE_PAYMENT_ECHECK_TEXT_ROUTINGNUMBER,
                                                 'field' => tep_draw_input_field('routingnumber'))));

      return $selection;
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $HTTP_POST_VARS;

      $process_button_string = tep_draw_hidden_field('accountholder', $HTTP_POST_VARS['accountholder']) .
                               tep_draw_hidden_field('address', $HTTP_POST_VARS['address']) .
                               tep_draw_hidden_field('address2', $HTTP_POST_VARS['address2']) .
                               tep_draw_hidden_field('phone', $HTTP_POST_VARS['phone']) .
                               tep_draw_hidden_field('bank', $HTTP_POST_VARS['bank']) .
                               tep_draw_hidden_field('bankcity', $HTTP_POST_VARS['bankcity']) .
                               tep_draw_hidden_field('bankphone', $HTTP_POST_VARS['bankphone']) .
                               tep_draw_hidden_field('checknumber', $HTTP_POST_VARS['checknumber']) .
                               tep_draw_hidden_field('accountnumber', $HTTP_POST_VARS['accountnumber']) .
                               tep_draw_hidden_field('routingnumber', $HTTP_POST_VARS['routingnumber']);

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      global $HTTP_POST_VARS, $insert_id;

      if ( (defined('MODULE_PAYMENT_ECHECK_EMAIL')) && (tep_validate_email(MODULE_PAYMENT_ECHECK_EMAIL)) ) {
        $message = 'Order #' . $insert_id . "\n" . MODULE_PAYMENT_ECHECK_TEXT_EXTRA_INFO . "\n\nAccount Holder Info:\n===============\n" . tep_db_prepare_input($HTTP_POST_VARS['accountholder']) . "\n" . tep_db_prepare_input($HTTP_POST_VARS['address']) . "\n" . tep_db_prepare_input($HTTP_POST_VARS['address2']) . "\n" . tep_db_prepare_input($HTTP_POST_VARS['phone']) . "\n\nBank Info:\n========\n" . tep_db_prepare_input($HTTP_POST_VARS['bank']) . "\n" . tep_db_prepare_input($HTTP_POST_VARS['bankcity']) . "\n" . tep_db_prepare_input($HTTP_POST_VARS['bankphone']) . "\n\nAccount Info:\n==========\n" . MODULE_PAYMENT_ECHECK_TEXT_CHECKNUMBER . tep_db_prepare_input($HTTP_POST_VARS['checknumber']) . "\n" . MODULE_PAYMENT_ECHECK_TEXT_ACCOUNTNUMBER . tep_db_prepare_input($HTTP_POST_VARS['accountnumber']) . "\n" . MODULE_PAYMENT_ECHECK_TEXT_ROUTINGNUMBER . tep_db_prepare_input($HTTP_POST_VARS['routingnumber']);
        
        tep_mail('STORE_OWNER', MODULE_PAYMENT_ECHECK_EMAIL, 'eCheck Info for Order #' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_ECHECK_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ECHECK_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Online eChecks', 'MODULE_PAYMENT_ECHECK_STATUS', 'True', 'Do you want to accept online eChecks?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_ECHECK_EMAIL', '', 'Enter your e-mail address where you want to receive eCheck Payment information', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_ECHECK_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_ECHECK_ZONE', '0', 'Enable this payment method for a particular zone. Please note, this payment modual does not work with zones so do not choose one at this time.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_ECHECK_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " ADD (accountholder VARCHAR(64), address VARCHAR(64), address2 VARCHAR(64), phone VARCHAR(32), bank VARCHAR(64), bankcity VARCHAR(64), bankphone VARCHAR(64), checknumber VARCHAR(10), accountnumber VARCHAR(32), routingnumber VARCHAR(15))");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP accountholder");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP address");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP address2");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP phone");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP bank");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP bankcity");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP bankphone");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP checknumber");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP accountnumber");
      tep_db_query("ALTER TABLE " . TABLE_ORDERS . " DROP routingnumber");
    }

    function keys() {
      return array('MODULE_PAYMENT_ECHECK_STATUS', 'MODULE_PAYMENT_ECHECK_EMAIL', 'MODULE_PAYMENT_ECHECK_ZONE', 'MODULE_PAYMENT_ECHECK_ORDER_STATUS_ID', 'MODULE_PAYMENT_ECHECK_SORT_ORDER');
    }
  }
?>
