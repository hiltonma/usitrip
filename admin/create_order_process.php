<?php
/*
  $Id: create_order_process.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

 // if ($HTTP_POST_VARS['action'] != 'process') {
  //  tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL'));
  //}
  /*
  $customer_id = tep_db_prepare_input($HTTP_POST_VARS['customers_id']);
  $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
  $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
  $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
  $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
  $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
  $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
  $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
  $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
  $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
  $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
  $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
  $format_id = "1";
  $size = "1";
  $payment_method = "Change";
  $new_value = "1";
  $error = false; // reset error flag
  $temp_amount = "0";
  $temp_amount = number_format($temp_amount, 2, '.', '');
// modified to the system defaults
  $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
  $currency_value = $currencies->currencies[$currency]['value'];
//
*/

  $_SESSION['customer_id'] = tep_db_prepare_input($HTTP_POST_VARS['customers_id']);
  $_SESSION['gender'] = tep_db_prepare_input($HTTP_POST_VARS['gender']);
  $_SESSION['firstname'] = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
  $_SESSION['lastname'] = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
  $_SESSION['dob'] = tep_db_prepare_input($HTTP_POST_VARS['dob']);
  $_SESSION['email_address'] = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  $_SESSION['telephone'] = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $_SESSION['fax'] = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  $_SESSION['newsletter'] = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
  $_SESSION['password'] = tep_db_prepare_input($HTTP_POST_VARS['password']);
  $_SESSION['confirmation'] = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
  $_SESSION['street_address'] = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
  $_SESSION['company'] = tep_db_prepare_input($HTTP_POST_VARS['company']);
  $_SESSION['suburb'] = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
  $_SESSION['postcode'] = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
  $_SESSION['city'] = tep_db_prepare_input($HTTP_POST_VARS['city']);
  $_SESSION['zone_id'] = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
  $_SESSION['state'] = tep_db_prepare_input($HTTP_POST_VARS['state']);
  $_SESSION['country'] = tep_db_prepare_input($HTTP_POST_VARS['country']);

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);

?>
<?php

/* amit commented
    $sql_data_array = array('customers_id' => $customer_id,
							'customers_name' => $firstname . ' ' . $lastname,
							'customers_company' => $company,
                            'customers_street_address' => $street_address,
							'customers_suburb' => $suburb,
							'customers_city' => $city,
							'customers_postcode' => $postcode,
							'customers_state' => $state,
							'customers_country' => $country,
							'customers_telephone' => $telephone,
                            'customers_email_address' => $email_address,
							'customers_address_format_id' => $format_id,
							'delivery_name' => $firstname . ' ' . $lastname,
							'delivery_company' => $company,
                            'delivery_street_address' => $street_address,
							'delivery_suburb' => $suburb,
							'delivery_city' => $city,
							'delivery_postcode' => $postcode,
							'delivery_state' => $state,
							'delivery_country' => $country,
							'delivery_address_format_id' => $format_id,
							'billing_name' => $firstname . ' ' . $lastname,
							'billing_company' => $company,
                            'billing_street_address' => $street_address,
							'billing_suburb' => $suburb,
							'billing_city' => $city,
							'billing_postcode' => $postcode,
							'billing_state' => $state,
							'billing_country' => $country,
							'billing_address_format_id' => $format_id,
							'date_purchased' => 'now()',
                            'orders_status' => DEFAULT_ORDERS_STATUS_ID,
							'currency' => $currency,
							'currency_value' => $currency_value);




  //  }




  //old
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();


    $sql_data_array = array('orders_id' => $insert_id,
					'orders_status_id' => $new_value,
					'date_added' => 'now()');
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Sub-Total:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_subtotal",
                            'sort_order' => "1");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
   $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Customer Discount:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_customer_discount",
                            'sort_order' => "2");
   tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);


    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Tax:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_tax",
                            'sort_order' => "2");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);


    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Shipping:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_shipping",
                            'sort_order' => "3");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);



      $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Total:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_total",
                            'sort_order' => "4");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

//amit commetend end
*/
  /*$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id,
                          'new_value' => DEFAULT_ORDERS_STATUS_ID,
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/

    tep_redirect(tep_href_link(FILENAME_C_ORDERS, 'action=add_product', 'SSL'));
	//tep_href_link(update_order. '.'.php, 'OrderID=' . $oInfo->orders_id) .
  //}

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
