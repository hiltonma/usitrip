<?php
/*
  $Id: checkout_paypalipn.php,v 1.1.1.1 2004/03/04 23:37:56 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Paypal IPN v0.981 for Milestone 2
  Copyright (c) 2003 Pablo Pasqualino
  pablo_osc@osmosisdc.com
  http://www.osmosisdc.com

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (!tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_FS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load selected payment module
  require(DIR_FS_CLASSES . 'payment.php');
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($payment);

// load the selected shipping module
  require(DIR_FS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_FS_CLASSES . 'order.php');
  $order = new order;
  
// load the before_process function from the payment modules
  $payment_modules->before_process();

  require(DIR_FS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

// BOF: WebMakers.com Added: Downloads Controller
  $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
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
                          'payment_method' => $order->info['payment_method'],
// BOF: Lango Added for print order mod 
                          'payment_info' => $GLOBALS['payment_info'],
// EOF: Lango Added for print order mod 
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'last_modified' => 'now()', 
                          'orders_status' => 99999, 
                          'currency' => $order->info['currency'], 
                          'currency_value' => $order->info['currency_value']);
// EOF: WebMakers.com Added: Downloads Controller
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'], 
                            'class' => $order_totals[$i]['code'], 
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => '99999', 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true' && MODULE_PAYMENT_PAYPALIPN_UPDATE_STOCK_BEFORE_PAYMENT=='True') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        $products_id =  tep_get_prid($order->products[$i]['id']);
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . $products_id. "'");
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $products_id . "'");
        }
        MCache::update_product($products_id);//MCache update
      }
    }

// Update products_ordered (for bestsellers list)	
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
	MCache::update_product(tep_get_prid($order->products[$i]['id']));//MCache update
    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
                            'products_price' => $order->products[$i]['price'], 
                            'group_buy_discount' => $order->products[$i]['group_buy_discount'], 
                            'final_price' => $order->products[$i]['final_price'], 
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty']);
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();
   
    $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
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

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'], 
                                'options_values_price' => $attributes_values['options_values_price'], 
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
	
        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }
  $order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM

 // Include OSC-AFFILIATE  DMG  
  require(DIR_FS_INCLUDES . 'affiliate_checkout_process.php');


// load the after_process function from the payment modules
  $payment_modules->after_process();

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');

  if (ereg($order->info['currency'],MODULE_PAYMENT_PAYPALIPN_ALLOWED_CURRENCIES)) {
    $paypal_ipn_currency = $order->info['currency'];
  } else {
    $paypal_ipn_currency = MODULE_PAYMENT_PAYPALIPN_DEFAULT_CURRENCY;
  };

  $paypal_ipn_order_amount = $order->info['total'];

  if (MODULE_PAYMENT_PAYPALIPN_ADD_SHIPPING_TO_AMOUNT=='True') {
    $paypal_ipn_shipping_amount = 0.00;
  } else {
    $paypal_ipn_order_amount -= $order->info['shipping_cost'];
    $paypal_ipn_shipping_amount = number_format($order->info['shipping_cost'] * $currencies->get_value($paypal_ipn_currency), 2);
  };

  if (MODULE_PAYMENT_PAYPALIPN_ADD_TAX_TO_AMOUNT=='True') {
    $paypal_ipn_tax_amount = 0.00;
  } else {
    $paypal_ipn_order_amount -= $order->info['tax'];
    $paypal_ipn_tax_amount = number_format($order->info['tax'] * $currencies->get_value($paypal_ipn_currency), 2);
  };

  $paypal_ipn_order_amount = number_format($paypal_ipn_order_amount * $currencies->get_value($paypal_ipn_currency), 2);

  tep_redirect("https://www.paypal.com/cgi-bin/webscr?cmd=_ext-enter&redirect_cmd=_xclick&business=".MODULE_PAYMENT_PAYPALIPN_ID."&item_name=".urlencode(STORE_NAME)."&item_number=".$insert_id."&currency_code=".$paypal_ipn_currency."&amount=".$paypal_ipn_order_amount."&shipping=".$paypal_ipn_shipping_amount."&tax=".$paypal_ipn_tax_amount."&first_name=".urlencode($order->customer['firstname'])."&last_name=".urlencode($order->customer['lastname'])."&address1=".urlencode($order->customer['street_address'])."&city=".urlencode($order->customer['city'])."&state=".urlencode($order->customer['state'])."&zip=".urlencode($order->customer['postcode'])."&email=".$order->customer['email_address']."&bn=oscommerce-osmosis-0.981&return=".tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')."&cancel_return=".tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL')."&notify_url=".MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
