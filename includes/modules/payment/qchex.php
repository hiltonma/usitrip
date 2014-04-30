<?php
/*
  $Id: qchex.php,v 3.0 2003/11/10 2:21:32 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

   $orders_query = tep_db_query("select max(orders_id) as order_id from " . TABLE_ORDERS);
   $orders = tep_db_fetch_array($orders_query);
   $oID = ($orders['order_id'] + 1);

   class qchex {
    var $code, $title, $description, $enabled;

// class constructor
    function qchex() {
      global $order;

      $this->code = 'qchex';
      $this->title = MODULE_PAYMENT_QCHEX_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_QCHEX_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_QCHEX_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_QCHEX_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_QCHEX_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_QCHEX_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = 'https://www.qchex.com/pay.asp';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_QCHEX_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_QCHEX_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $order, $currencies, $currency;

      if (MODULE_PAYMENT_PAYPAL_CURRENCY == 'Selected Currency') {
        $my_currency = 'USD';
      } else {
        $my_currency = substr(MODULE_PAYMENT_PAYPAL_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD'))) {
        $my_currency = 'USD';
      }

      $process_button_string = tep_draw_hidden_field('MerchantID', MODULE_PAYMENT_QCHEX_ID) .
                               tep_draw_hidden_field('Memo', 'Online Purchase') .
                               tep_draw_hidden_field('RefNo', ($order->info['order_id'] + 1)) .
                               tep_draw_hidden_field('PayorName', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
                               tep_draw_hidden_field('PayorEmail', $order->customer['email_address']) .
                               tep_draw_hidden_field('Amount', number_format(($order->info['total']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency))) .
                               tep_draw_hidden_field('ReturnTo', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_QCHEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable QChex Module', 'MODULE_PAYMENT_QCHEX_STATUS', 'True', 'Do you want to accept QCHEX payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MerchantID', 'MODULE_PAYMENT_QCHEX_ID', '', 'Your QChex MerchantID', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_QCHEX_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_QCHEX_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_QCHEX_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
              $keys = '';
              $keys_array = $this->keys();
              for ($i=0; $i<sizeof($keys_array); $i++) {
                $keys .= "'" . $keys_array[$i] . "',";
              }
              $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_QCHEX_STATUS', 'MODULE_PAYMENT_QCHEX_ID', 'MODULE_PAYMENT_QCHEX_ZONE', 'MODULE_PAYMENT_QCHEX_ORDER_STATUS_ID', 'MODULE_PAYMENT_QCHEX_SORT_ORDER');
    }
  }
?>
