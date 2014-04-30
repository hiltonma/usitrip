<?php
/*
  $Id: edit_orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Written by Jonathan Hilgeman of SiteCreative.com (osc@sitecreative.com)

  Version History
  ---------------------------------------------------------------
  08/08/03
  1.2a - Fixed a query problem on osC 2.1 stores.

  08/08/03
  1.2 - Added more recommendations to the instructions.
        Added "Customer" fields for editing on osC 2.2.
        Corrected "Billing" fields so they update correctly.
        Added Company and Suburb Fields.
        Added optional shipping tax variable.
        First (and hopefully last) fix for currency formatting.

  08/08/03
  1.1 - Added status editing (fixed order status bug from 1.0).
        Added comments editing. (with compatibility for osC 2.1)
        Added customer notifications.
        Added some additional information to the instructions file.
        Fixed bug with product names containing single quotes.

  08/07/03
  1.0 - Original Release.

  To Do in Version 1.3
  ---------------------------------------------------------------

  Note from the author
  ---------------------------------------------------------------
  This tool was designed and tested on osC 2.2 Milestone 2.2,
  but may work for other versions, as well. Most database changes
  were minor, so getting it to work on other versions may just
  need some tweaking. Hope this helps make your life easier!

  - Jonathan Hilgeman, August 7th, 2003
*/

  require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('phone_booking');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
header("Content-type: text/html; charset=".CHARSET."");
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

  // New "Status History" table has different format.
  $OldNewStatusValues = (tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
  $CommentsWithStatus = tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
  $SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");

  // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $orders_ship_method = array();
  $orders_ship_method_array = array();
  $orders_ship_method_query = tep_db_query("select ship_method from ".TABLE_ORDERS_SHIP_METHODS."");
  while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
    $orders_ship_method[] = array('id'   => $orders_ship_methods['ship_method'],
                                  'text' => $orders_ship_methods['ship_method']);
    $orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
  }

  $orders_pay_method = array();
  $orders_pay_method_array = array();
  $orders_pay_method_query = tep_db_query("select pay_method from ".TABLE_ORDERS_PAY_METHODS."");
  while ($orders_pay_methods = tep_db_fetch_array($orders_pay_method_query)) {
    $orders_pay_method[] = array('id'   => $orders_pay_methods['pay_method'],
                                  'text' => $orders_pay_methods['pay_method']);
    $orders_pay_method_array[$orders_pay_methods['pay_method']] = $orders_pay_methods['pay_method'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'add_product');
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
$order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
if(IsSet($HTTP_POST_VARS['Customer']))
  {
	 $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_POST_VARS['Customer'] . "'");
	 $account = tep_db_fetch_array($account_query);
	 $customer = $account['customers_id'];
	 $customers_firstname = $account['customers_firstname'];
	 $customers_lastname = $account['customers_lastname'];
	 $customers_email_address = $account['customers_email_address'];
	 	 
	 $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_POST_VARS['Customer'] . "'");
	 $address = tep_db_fetch_array($address_query);
	 //$customer = $account['customers_id'];
  } elseif (IsSet($HTTP_POST_VARS['Customer_nr'])){
	 $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_POST_VARS['Customer_nr'] . "'");
	 $account = tep_db_fetch_array($account_query);
	 $customer = $account['customers_id'];
	 $customers_firstname = $account['customers_firstname'];
	 $customers_lastname = $account['customers_lastname'];
	 $customers_email_address = $account['customers_email_address'];
	 
	 $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_POST_VARS['Customer_nr'] . "'");
	 $address = tep_db_fetch_array($address_query);
	 //$customer = $account['customers_id'];
  }
  if (tep_not_null($action)) {
    switch ($action) {

	// Add a Product
	case 'add_product':
		
		if($step == 6)
		{
		
		
		  $gender = $_SESSION['gender'] = false;
		  $customer_id = $_SESSION['customer_id'] = tep_db_prepare_input($HTTP_POST_VARS['customer_id']);
		  $firstname = $_SESSION['firstname'] = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
		  $lastname = $_SESSION['lastname'] = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
		  $dob = $_SESSION['dob'] = tep_db_prepare_input($HTTP_POST_VARS['dob']);
		  $email_address = $_SESSION['email_address'] = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
		  $telephone = $_SESSION['telephone'] = tep_db_prepare_input($HTTP_POST_VARS['telephone_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['telephone']);
		  $fax = $_SESSION['fax'] = tep_db_prepare_input($HTTP_POST_VARS['fax_cc']).'-'.tep_db_prepare_input($HTTP_POST_VARS['fax']);
		  $newsletter = $_SESSION['newsletter'] = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
		  $confirmation = $_SESSION['confirmation'] = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
		  $street_address = $_SESSION['street_address'] = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
		  $company = $_SESSION['company'] = tep_db_prepare_input($HTTP_POST_VARS['company']);
		  $suburb = $_SESSION['suburb'] = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
		  $postcode = $_SESSION['postcode'] = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
		  $city = $_SESSION['city'] = tep_db_prepare_input($HTTP_POST_VARS['city']);
		  $zone_id = $_SESSION['zone_id'] = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
		  $state = $_SESSION['state'] = tep_db_prepare_input($HTTP_POST_VARS['state']);
		  $country = $_SESSION['country'] = tep_db_prepare_input($HTTP_POST_VARS['country']);
	  
		// if new customer - start
		$addon_new_customer_txt = 0;
		if(!isset($_SESSION['customer_id']) || (int)$_SESSION['customer_id'] == 0){
			////////////////      RAMDOMIZING SCRIPT BY PATRIC VEVERKA       \\\\\\\\\\\\\\\\\\
			
			$t1 = date("mdy");
			srand ((float) microtime() * 10000000);
			$input = array ("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z");
			$rand_keys = array_rand ($input, 3);
			$l1 = $input[$rand_keys[0]];
			$r1 = rand(0,9);
			$l2 = $input[$rand_keys[1]];
			$l3 = $input[$rand_keys[2]];
			$r2 = rand(0,9);
			
			$password = $l1.$r1.$l2.$l3.$r2;
			
			/////////////////    End of Randomizing Script   \\\\\\\\\\\\\\\\\\\
			
			  $error = false; // reset error flag
			
			  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
				$error = true;
				//$entry_firstname_error = true;
				$messageStack->add(sprintf(ENTRY_FIRST_NAME_ERROR, $oID), 'error');
			  } else {
				$entry_firstname_error = false;
			  }
			
			  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
				$error = true;
				//$entry_lastname_error = true;
				$messageStack->add(sprintf(ENTRY_LAST_NAME_ERROR, $oID), 'error');
			  } else {
				$entry_lastname_error = false;
			  }
			
			  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
				$error = true;
				//$entry_email_address_error = true;
				$messageStack->add(sprintf(ENTRY_EMAIL_ADDRESS_ERROR, $oID), 'error');
			  } else {
				$entry_email_address_error = false;
			  }
			
			 if (!tep_validate_email($email_address)) {
				$error = true;
				//$entry_email_address_check_error = true;
				$messageStack->add(sprintf(ENTRY_EMAIL_ADDRESS_CHECK_ERROR, $oID), 'error');
			  } else {
				$entry_email_address_check_error = false;
			  }
			
			  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
				$error = true;
				//$entry_street_address_error = true;
				$messageStack->add(sprintf(ENTRY_STREET_ADDRESS_ERROR, $oID), 'error');
			  } else {
				$entry_street_address_error = false;
			  }
			
			  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
				$error = true;
				//$entry_post_code_error = true;
				$messageStack->add(sprintf(ENTRY_POST_CODE_ERROR, $oID), 'error');
			  } else {
				$entry_post_code_error = false;
			  }
			
			  /*if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
				$error = true;
				$messageStack->add(sprintf(ENTRY_CITY_ERROR, $oID), 'error');
			  } else {
				$entry_city_error = false;
			  }
			*/
			  if (!$country) {
				$error = true;
				$messageStack->add(sprintf(ENTRY_COUNTRY_ERROR, $oID), 'error');
			  } else {
				$entry_country_error = false;
			  }
			
			  if (ACCOUNT_STATE == 'true') {
				if ($entry_country_error) {
				  $messageStack->add(sprintf(ENTRY_STATE_ERROR, $oID), 'error');
				} else {
				  $zone_id = 0;
				  $entry_state_error = false;
				  $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_country_id ='223' ");
				  $check_value = tep_db_fetch_array($check_query);
				  $entry_state_has_zones = ($check_value['total'] > 0);
				  if ($entry_state_has_zones) {
					$zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_name = '" . tep_db_input($state) . "'");
					if (tep_db_num_rows($zone_query) == 1) {
					  $zone_values = tep_db_fetch_array($zone_query);
					  $zone_id = $zone_values['zone_id'];
					} else {
					  $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_code = '" . tep_db_input($state) . "'");
					  if (tep_db_num_rows($zone_query) == 1) {
						$zone_values = tep_db_fetch_array($zone_query);
						$zone_id = $zone_values['zone_id'];
					  } else {
						$error = true;
						$messageStack->add(sprintf(ENTRY_STATE_ERROR, $oID), 'error');
					  }
					}
				  } else {
					if (!$state) {
					  $error = true;
					  $messageStack->add(sprintf(ENTRY_STATE_ERROR, $oID), 'error');
					}
				  }
				}
			  }
			
			  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
				$error = true;
				$messageStack->add(sprintf(ENTRY_TELEPHONE_NUMBER_ERROR, $oID), 'error');
			  } else {
				$entry_telephone_error = false;
			  }
			
			  $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id <> '" . tep_db_input($customer_id) . "'");
			  if (tep_db_num_rows($check_email)) {
				$error = true;
				$messageStack->add(sprintf(ENTRY_EMAIL_ADDRESS_ERROR_EXISTS, $oID), 'error');
			  } else {
				$entry_email_address_exists = false;
			  }
			  if ($error == false) {
				$sql_data_array = array('customers_firstname' => $firstname,
										'customers_lastname' => $lastname,
										'customers_email_address' => $email_address,
										'customers_telephone' => $telephone,
										'customers_fax' => $fax,
										'customers_newsletter' => $newsletter,
										'customers_password' => tep_encrypt_password($password),
										'customers_default_address_id' => 1);
			
				if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
				if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
			
				tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
			
				$customer = $customer_id = tep_db_insert_id();
			
				$sql_data_array = array('customers_id' => $customer_id,
										  'entry_firstname' => $firstname,
										  'entry_lastname' => $lastname,
										  'entry_street_address' => $street_address,
										  'entry_postcode' => $postcode,
										  'entry_city' => $city,
										  'entry_country_id' => $country);
			
				if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
				if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
				if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
				if (ACCOUNT_STATE == 'true') {
				  if ($zone_id > 0) {
					$sql_data_array['entry_zone_id'] = $zone_id;
					$sql_data_array['entry_state'] = '';
				  } else {
					$sql_data_array['entry_zone_id'] = '0';
					$sql_data_array['entry_state'] = $state;
				  }
				}
			
				tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
			
				$address_id = tep_db_insert_id();
			
				tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
			
				tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
			
				// build the message content
				$name = $firstname . " " . $lastname;
			
				$addon_new_customer_txt = 1;
				
				$account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer . "'");
				 $account = tep_db_fetch_array($account_query);
				 $customer = $account['customers_id'];
				 $customers_firstname = $account['customers_firstname'];
				 $customers_lastname = $account['customers_lastname'];
				 $customers_email_address = $account['customers_email_address'];
				 
				 $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer . "'");
				 $address = tep_db_fetch_array($address_query);
				 
			}
		}
		// if new customer - end
		if(isset($customer_id)){
		//amit added to create orders start
				
				 // if ($HTTP_POST_VARS['action'] != 'process') {
				  //  tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL'));
				  //}
				  
				  
				  $format_id = "1";
				  $size = "1";
				//  $payment_method = "Change";
				  $new_value = "1";
				  $error = false; // reset error flag
				  $temp_amount = "0";
				  $temp_amount = number_format($temp_amount, 2, '.', '');
				// modified to the system defaults
				//  $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
					  $currency = 'USD';	
				  $currency_value = $currencies->currencies[$currency]['value'];
				//


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
						 	'payment_method' => tep_db_prepare_input($_POST['order_product_method']),
						  	//'cc_type' => $_POST['cc_credit_card_type'],
						  	//'cc_owner' => $_POST['cc_owner'],
						  	//'cc_number' => scs_cc_encrypt($_POST['cc_number']),
						  	'cc_expires' => $_POST['cc_expires_month'].$_POST['cc_expires_year'],				
							//'cc_cvv' => $_POST['cc_cvv'],					  
							'date_purchased' => 'now()',
                            'orders_status' => DEFAULT_ORDERS_STATUS_ID,
							'currency' => $currency,
							'currency_value' => $currency_value,
							'admin_id_orders' => $login_id,
							'is_phonebooking' => '1'
							);
							
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
	$oID =  $insert_id;
	$HTTP_GET_VARS['oID'] = $oID;

	//amit added to unset session strat
	 unset($_SESSION['customer_id']);
  unset($_SESSION['gender']);
  unset($_SESSION['firstname']);
  unset($_SESSION['lastname']);
  unset($_SESSION['dob']);
  unset($_SESSION['email_address']);
  unset($_SESSION['telephone']);
  unset($_SESSION['fax']);
  unset($_SESSION['newsletter']);
  unset($_SESSION['password']);
  unset($_SESSION['confirmation']);
  unset($_SESSION['street_address']);
  unset($_SESSION['company']);
  unset($_SESSION['suburb']);
  unset($_SESSION['postcode']);
  unset($_SESSION['city']);
  unset($_SESSION['zone_id']);
  unset($_SESSION['state']);
  unset($_SESSION['country']);  
  unset($_SESSION['customer_first_name']);
  unset($_SESSION['customer_last_name']);
  
	//amit added to unset session end

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
   /*
   $sql_data_array = array('orders_id' => $insert_id,
                            'title' => "Customer Discount:",
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_customer_discount",
                            'sort_order' => "2");
   tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
   */
   //Added for gift certificates redeem - start
	//update gift certificates
	if(isset($HTTP_POST_VARS['gc_code']) && $HTTP_POST_VARS['gc_total'] > 0){
		$sql_data_array = array('gc_code' => $HTTP_POST_VARS['gc_code'], 
			'amount_redeemed' => $HTTP_POST_VARS['gc_total'],
			'redeemed_by' => $customer_id,
			'last_redeemed' => 'now()');
		tep_db_perform(TABLE_GIFT_CERTIFICATES, $sql_data_array, 'update', "gc_code='" . $HTTP_POST_VARS['gc_code'] . "'");
		//insert gift certificate redemptions
		$sql_data_array = array('gc_code' => $HTTP_POST_VARS['gc_code'],
			'redeemed_by' => $customer_id,
			'orders_id' => $insert_id,
			'amount_redeemed' => $HTTP_POST_VARS['gc_total'],
			'date_redeemed' => 'now()');
		tep_db_perform(TABLE_GIFT_CERTIFICATE_REDEMPTIONS, $sql_data_array);
		
		$sql_data_array = array('orders_id' => $insert_id,
						'title' => 'Gift Certificates',
						'text' => '<span class="pointWarning">-'.$currencies->format($HTTP_POST_VARS['gc_total']).'</span>',
						'value' => tep_round($HTTP_POST_VARS['gc_total'],2),
						'class' => 'ot_giftcertificates',
						'sort_order' => "2");
		tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}
	//Added for gift certificates redeem - end
	
	if(isset($HTTP_POST_VARS['gv_redeem_code']) && $HTTP_POST_VARS['dc_total'] > 0){
	$sql_data_array = array('orders_id' => $insert_id,
						'title' => 'Coupon:',
						'text' => '<font color="red"><b>-' . $currencies->format($HTTP_POST_VARS['dc_total']) . '</b></font>',
						'value' => tep_round($HTTP_POST_VARS['dc_total'],2),
						'class' => 'ot_coupon',
						'sort_order' => "2");
	tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	}


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
		
		//amit added to create orders end
		
		
		
		
	$products_ordered = '';
	
		$products_ids_array = explode(',', $add_product_categories_id);
		for ($iii=0, $n=sizeof($products_ids_array); $iii<$n; $iii++) {
			$products_id_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model = '" . $products_ids_array[$iii] . "'");
			$get_products_id = tep_db_fetch_array($products_id_query);					
			$_POST['add_product_products_id'] = $HTTP_POST_VARS['add_product_products_id'] = $add_product_products_id = $get_products_id['products_id'];
			// Get Order Info
				$final_product_price = $_POST['final_product_price'][$add_product_products_id];
				$final_product_price_cost = $_POST['final_product_price_cost'][$add_product_products_id];
				$finaldate 			 = $_POST['finaldate'][$add_product_products_id];
				$depart_time 		 = $_POST['depart_time'][$add_product_products_id];
				$depart_location 	 = $_POST['depart_location'][$add_product_products_id];
				$total_room_price 	 = $_POST['total_room_price'][$add_product_products_id];
				$total_info_room 	 = $_POST['total_info_room'][$add_product_products_id];
				$total_room_adult_child_info  = $_POST['total_room_adult_child_info'][$add_product_products_id];
				$g_number  = $_POST['g_number'][$add_product_products_id];
				
			$add_product_quantity = $_POST['add_product_quantity'][$add_product_products_id];
			$add_product_options[$add_product_products_id] = $_POST['add_product_options'][$add_product_products_id];
			//$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
			$order = new order($oID);

			$AddedOptionsPrice = 0;
			$AddedOptionsPrice_cost = 0;

			// Get Product Attribute Info
			if($add_product_options[$add_product_products_id]!='')
			{
				foreach($add_product_options[$add_product_products_id] as $option_id => $option_value_id)
				{
				  if (DOWNLOAD_ENABLED == 'true') {
				  $execute_query ="SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pad.products_attributes_id=pa.products_attributes_id WHERE products_id='$add_product_products_id' and options_id='$option_id' and options_values_id='$option_value_id'";
				  } 
				  else 
				  {
				   $execute_query = "select pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.options_values_price_cost, pa.price_prefix, popt.products_options_name, poval.products_options_values_name from
							" . TABLE_PRODUCTS_OPTIONS . " popt,
							" . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
							" . TABLE_PRODUCTS_ATTRIBUTES . " pa 
							where
							pa.products_id = '" . (int)$add_product_products_id. "'
							and pa.options_id = '" . (int)$option_id. "' 
							and pa.options_id = popt.products_options_id 
							and pa.options_values_id = '" . (int)$option_value_id. "' 
							and pa.options_values_id = poval.products_options_values_id 
							and popt.language_id = '" . (int)$languages_id . "' 
							and poval.language_id = '" . (int)$languages_id . "'
							order by pa.products_options_sort_order
							";
				 }

				$attributes_query = tep_db_query($execute_query);
				while  ($attributes = tep_db_fetch_array($attributes_query)){
					if($attributes['price_prefix'] == ''){
						$attributes['price_prefix'] = '+';
					}
					$option = $attributes['options_id'];
					$opt_products_options_name = $attributes['products_options_values_name'];
					$option_id = "'" . (int)$option_id. "'" ;
					$value_id = "'" . (int)$option_value_id. "'";
					$att_prefix[$option_value_id] = $attributes['price_prefix'];
					
					//$att_price[$option_value_id] = $attributes['options_values_price'];
					$att_price[$option_value_id] = attributes_price_display($add_product_products_id,$attributes['options_id'],$attributes['options_values_id']);
					$att_price_cost[$option_value_id] = attributes_price_display_cost($add_product_products_id,$attributes['options_id'],$attributes['options_values_id']);
					
					
					$option_names[$option_value_id] = $attributes['products_options_values_name'];
					$option_values_names[$option_value_id] = $attributes['products_options_name'];
					
					if($att_prefix[$option_value_id] == '+'){
					  $AddedOptionsPrice += $att_price[$option_value_id];
					  $AddedOptionsPrice_cost += $att_price_cost[$option_value_id];
					}else{
					  $AddedOptionsPrice -= $att_price[$option_value_id];
					  $AddedOptionsPrice_cost += $att_price_cost[$option_value_id];
					}
					  
					 $option_value_details[$option_id][$value_id] = array ("options_values_price" => $att_price[$option_value_id]);
					 $att_options_values_price = $att_price[$option_value_id];
				
				}




			}
			}


			// Get Product Info
			$InfoQuery = "select p.products_model,p.products_price,pd.products_name,p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='$add_product_products_id' and p.products_id=pd.products_id";
			$result = tep_db_query($InfoQuery);
			$row = tep_db_fetch_array($result);
			extract($row, EXTR_PREFIX_ALL, "p");

			// Following functions are defined at the bottom of this file
			$CountryID = tep_get_country_id($order->delivery["country"]);
			$ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);

			$ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
			$final_price_update = $final_product_price + $AddedOptionsPrice;// 
			$final_price_cost_update = $final_product_price_cost + $AddedOptionsPrice_cost; //
			$sql_data_array_op_insert = array(
			 				'orders_id' => $oID,
                            'products_id' => $add_product_products_id,
                            'products_model' => $p_products_model,
                            'products_name' => $p_products_name,
                            'products_price' => $final_product_price,
                            'final_price' => ($final_product_price + $AddedOptionsPrice),
							'final_price_cost' => ($final_product_price_cost + $AddedOptionsPrice_cost),
                            'products_tax' => $ProductsTax,
                            'products_quantity' => $add_product_quantity,
							'products_departure_date' => $finaldate,
							'products_departure_time' => $depart_time,
							'products_departure_location' => $depart_location,
							'products_room_price' => $total_room_price,
							'products_room_info' => $total_info_room,
							'total_room_adult_child_info' => $total_room_adult_child_info
							);   
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_op_insert);
			$new_product_id = tep_db_insert_id();
			
			
			//amit added for cost cal history
			 $sql_data_array_original_insert = array(			  
								'orders_products_id' => $new_product_id,		
								'products_model' => $p_products_model,
								'products_name' => str_replace("'", "&#39;", $p_products_name),	
								'retail' => ($final_product_price + $AddedOptionsPrice ),
								'cost' => ($final_product_price_cost + $AddedOptionsPrice_cost),
								'last_updated_date' => 'now()'																										  
								);
								//   
			tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
			//amit added for cost cal history
	
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			$products_ordered_attributes = '';
			if($add_product_options[$add_product_products_id]!='')
			{
				foreach($add_product_options[$add_product_products_id] as $option_id => $option_value_id)
				{
					$Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						orders_id = $oID,
						orders_products_id = $new_product_id,
						products_options = '" . $option_values_names[$option_value_id] . "',
						products_options_values = '" . $option_names[$option_value_id]. "',
						options_values_price = '" . (int)$att_price[$option_value_id] . "',
						options_values_price_cost = '" . (int)$att_price_cost[$option_value_id] . "',
						price_prefix = '" . $att_prefix[$option_value_id] . "' ";
					tep_db_query($Query);

          if ((DOWNLOAD_ENABLED == 'true') && isset($opt_products_attributes_filename) && tep_not_null($opt_products_attributes_filename)) {
            $sql_data_array = array('orders_id' => $oID,
                                    'orders_products_id' => $new_product_id,
                                    'orders_products_filename' => $opt_products_attributes_filename,
                                    'download_maxdays' => $opt_products_attributes_maxdays,
                                    'download_count' => $opt_products_attributes_maxcount);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
          }
				
				$products_ordered_attributes .= "\n\t" . $option_values_names[$option_value_id] . ' ' . $option_names[$option_value_id] . ' ' . $att_prefix[$option_value_id] . ' ' . $currencies->format((int)$att_price[$option_value_id]);
				
				}
			}

			// Stock Update
			$stock_chk_query = tep_db_query("select products_quantity from ".TABLE_PRODUCTS." where products_id = '" . $add_product_products_id . "'");
			$stock_chk_values = tep_db_fetch_array($stock_chk_query);
    		$stock_chk_left = $stock_chk_values['products_quantity'] - $add_product_quantity;
			tep_db_query("update ".TABLE_PRODUCTS." set products_quantity = '" . $stock_chk_left . "' where products_id = '" . $add_product_products_id . "'");

			// Update products_ordered (for bestsellers list)
			tep_db_query("update ".TABLE_PRODUCTS." set products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");


			

			
			//bhavik add you logic to insert recored for guest name and filght
			//filght information
			
				$airline_name           =addslashes($HTTP_POST_VARS['airline_name']);
				$flight_no              =addslashes($HTTP_POST_VARS['flight_no']);
				$airline_name_departure =addslashes($HTTP_POST_VARS['airline_name_departure']);
				$flight_no_departure    =addslashes($HTTP_POST_VARS['flight_no_departure']);
				$airport_name    		=addslashes($HTTP_POST_VARS['airport_name']);
				$airport_name_departure =addslashes($HTTP_POST_VARS['airport_name_departure']);
				$arrival_date           =addslashes($HTTP_POST_VARS['arrival_date']);
				$arrival_time           =addslashes($HTTP_POST_VARS['arrival_time']);
				$departure_date         =addslashes($HTTP_POST_VARS['departure_date']);
				$departure_time         =addslashes($HTTP_POST_VARS['departure_time']);
				
			$sql_data_array = array('orders_id' => $oID,
                          'products_id' => $HTTP_POST_VARS['add_product_products_id'],
						  'airline_name' => $airline_name,
						  'flight_no' => $flight_no,
						  'airline_name_departure' => $airline_name_departure,
						  'flight_no_departure' => $flight_no_departure,
						  'airport_name' => $airport_name,
						  'airport_name_departure' => $airport_name_departure,
						  'arrival_date' => $arrival_date,
						  'arrival_time' => $arrival_time,
						  'departure_date' => $departure_date,
						  'departure_time' => $departure_time
						  );
  			tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, $sql_data_array); 
			
			  //total_no_guest_tour
			  $guest_name = '';
			  $guest_name = $_POST['guest1'][$add_product_products_id]." <::>";
			  for($gi=2; $gi <= $_POST['g_number'][$add_product_products_id]; $gi++ ){
			  	$guest_name .= GUESTNAME_BLANK_DEFAULT_TEXT."<::>";
			  }
			  
			  /*foreach($_POST as $key=>$val)
			  {
				if(strstr($key,'guest'))
				{
					$guest_name .= $val[$add_product_products_id]." <::>";
				}
			  }*/


			$depature_full_address =  $finaldate.' &nbsp; '.$depart_time.' &nbsp; '.$depart_location;

			$sql_data_array = array('orders_id' => $oID,
								  'products_id' => $HTTP_POST_VARS['add_product_products_id'],
								  'guest_name' => $guest_name,
								  'guest_body_weight' => tep_db_input($guest_body_weight),						  
								  'depature_full_address' => tep_db_input($depature_full_address),	
								  'guest_number' => "0" //$HTTP_POST_VARS['g_number']
								  );
			tep_db_perform(TABLE_ORDERS_PRODUCTS_ETICKET, $sql_data_array);
			
			// Howard added updated orders status if ot total has changed 如果新总额比旧总额大则按需更新订单状态
			$last_total_value = get_orders_ot_total_value(tep_db_input($oID));
			if((int)$last_total_value){
				auto_update_orders_status_set_partial_payment_received(tep_db_input($oID));
			}
			
			$displayattributesinmail = get_roominfo_formatted_string($total_room_adult_child_info, $total_info_room);
			
			
			if(tep_is_gift_certificate_product($add_product_products_id)){
				$display_departure_time_location="";
			}else{
				$display_departure_time_location = "\n- ".TXT_DEPARTURE_TIME_LOCATION.": ".$finaldate.' '.$depart_time.' '.$depart_location;
			}
			
			$products_ordered .= "\n".$add_product_quantity . ' x ' . $p_products_name . ' (' . tour_code_encode($p_products_model) . ') = ' . $currencies->format($final_price_update, $ProductsTax, $add_product_quantity) . $products_ordered_attributes. $displayattributesinmail . $display_departure_time_location ."\n";
			}
  
 // Calculate Tax and Sub-Totals
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = 0;
			
			
			$RunningSubTotal_cost = 0;
			$RunningTax_cost = 0;


			for ($i=0; $i<sizeof($order->products); $i++)
			{
			$RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
			$RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
			
			$RunningSubTotal_cost += ($order->products[$i]['qty'] * $order->products[$i]['final_price_cost']);
			$RunningTax_cost += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price_cost']));
			
			}
			
			$RunningSubTotal = $RunningSubTotal - tep_round($HTTP_POST_VARS['gc_total'],2) - tep_round($HTTP_POST_VARS['dc_total'],2);
			
			
			$Query = "update " . TABLE_ORDERS . " set				
				order_cost = '" . $RunningSubTotal_cost . "'
				where orders_id=$oID";
			tep_db_query($Query);

			// Tax
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '\$" . number_format($RunningTax, 2, '.', ',') . "',
				value = '" . $RunningTax . "'
				where class='ot_tax' and orders_id=$oID";
			tep_db_query($Query);

			// Sub-Total
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '\$" . number_format($RunningSubTotal, 2, '.', ',') . "',
				value = '" . $RunningSubTotal . "'
				where class='ot_subtotal' and orders_id=$oID";
			tep_db_query($Query);

			// Total
			$Query = "select sum(value) as total_value from " . TABLE_ORDERS_TOTAL . " where class != 'ot_total' and orders_id=$oID";
			$result = tep_db_query($Query);
			$row = tep_db_fetch_array($result);
			$Total = $row["total_value"];

			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '<b>\$" . number_format($Total, 2, '.', ',') . "</b>',
				value = '" . $Total . "'
				where class='ot_total' and orders_id=$oID";
			tep_db_query($Query);
			
			/*
			if($_POST['order_product_method'] == 'Credit Card'){
				include(DIR_WS_MODULES . 'authorizenet_direct.php');
			}
			*/
			
			#### Points/Rewards Module V2.1rc2a balance customer points BOF ####
			  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
			// customer pending points added 
				  if ($Total > 0) {
					  $order->info['shipping_cost'] = 0;
					  $order_integer_total = str_replace(',','',$Total);
					  if ((USE_POINTS_FOR_SHIPPING == 'false') && (USE_POINTS_FOR_TAX == 'false'))
					  $points_toadd = $order_integer_total - $order->info['shipping_cost'] - $RunningTax;
					  else if ((USE_POINTS_FOR_SHIPPING == 'false') && (USE_POINTS_FOR_TAX == 'true'))
					  $points_toadd = $order_integer_total - $order->info['shipping_cost'];
					  else if ((USE_POINTS_FOR_SHIPPING == 'true') && (USE_POINTS_FOR_TAX == 'false'))
					  $points_toadd = $order_integer_total - $RunningTax;
					  else $points_toadd = $order_integer_total;
					  
					  
					  if (USE_POINTS_FOR_SPECIALS == 'false') {
						  for ($i=0; $i<sizeof($order->products); $i++) {
							  if (tep_get_products_special_price($order->products[$i]['id']) >0) {
								  if (USE_POINTS_FOR_TAX == 'true') {
									  $points_toadd = $points_toadd - (tep_add_tax($order->products[$i]['final_price'],$order->products[$i]['tax'])*$order->products[$i]['qty']);
								  } else {
									  $points_toadd = $points_toadd - ($order->products[$i]['final_price']*$order->products[$i]['qty']);
								  }
							  }
						  }
					  }
					  $points_toadd = $points_toadd;
					  $points_comment = 'TEXT_DEFAULT_COMMENT';
					  $points_type = 'SP';
					  tep_add_pending_points($customer_id, $insert_id, $points_toadd, $points_comment, $points_type);
				  }
			// customer shoppping points account balanced 
				  if ($customer_shopping_points_spending) {
					  tep_redeemed_points($customer_id, $insert_id, $customer_shopping_points_spending);
				  }
			  }
			#### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/
			
			// reservation email to customer - start
			$email_order = TXT_DEAR." ".$firstname . "\n" .
			EMAIL_SEPARATOR . "\n" . CONFORMATION_EMAIL_HEADER . "\n";
			
			
			if($addon_new_customer_txt == 1){
				$email_order .= "\n\n".'You can access your account with the following password. Once you access your account, please change your password!'."\n\n".'Website : <a href="'.tep_catalog_href_link(FILENAME_LOGIN, '', 'SSL').'">'.tep_catalog_href_link(FILENAME_LOGIN, '', 'SSL').'</a>'."\n\n".'Username: '.$email_address."\n".'Password: '.$password."\n\n".'Note: By booking over the phone you have agreed to usitrip\'s <a href="http://208.109.123.18/customer-agreement.php">Customer Agreement</a> and <a href="http://208.109.123.18/cancellation-and-refund-policy.php">Standard Amendment, Cancellation and Refund Policy</a>. Terms of the preceding policies apply upon completion of your phone booking. You have been advised by usitrip that you have the option to purchase <a href="http://208.109.123.18/tour_america_need.php">Travel Insurance</a> to protect yourself and your trip.'."\n\n".'Supporting documentation and/or related details are needed from you. '."\n\n".'To review details of supporting documentation requirement and how to send documents to us, please go to'."\n".'<a href="http://208.109.123.18/acknowledgement_of_card_billing.php">http://208.109.123.18/acknowledgement_of_card_billing.php</a>'."\n\n";
			}
			
			$email_order .= EMAIL_SEPARATOR . "\n" .
			'<b>'.EMAIL_TEXT_ORDER_NUMBER . ' ' .ORDER_EMAIL_PRIFIX_NAME. $insert_id . "</b>\n" .
			EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
			EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n";
			
			
			// EOF: daithik change for PWA
			
			  $email_order .= EMAIL_SEPARATOR . "\n" .
							  '<b>'.EMAIL_TEXT_PRODUCTS . "</b>\n" .
							  $products_ordered .
							  EMAIL_SEPARATOR . "\n";
			
			  //for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
			  
				   $email_order .= '<b>'.TXT_PAYMENT . '</b> ' . strip_tags('<b>$' . number_format($Total, 2, '.', ',') . '</b>') . "\n";				   
				   $email_order .= '<b>Gift Certificates: </b> '.strip_tags('<span class="pointWarning">-'.$currencies->format($HTTP_POST_VARS['gc_total']).'</span>')."\n";
				   $email_order .= '<b>Coupon: </b> '.strip_tags('<font color="red"><b>-' . $currencies->format($HTTP_POST_VARS['dc_total']) . '</b></font>')."\n";
				   $email_order .= '<b>'.TXT_GRAND_TOTAL . '</b> ' . strip_tags('$'.number_format($RunningSubTotal, 2, '.', ',') ) . "\n";
				   
			  //}
			  
			
			  $email_order .= "<b>" . EMAIL_TEXT_BILLING_ADDRESS . "</b>\n" .
							 // EMAIL_SEPARATOR . "\n" .
							  tep_address_label($customer, $billto, 0, '', "\n") . "\n";
			  if (is_object($$payment)) {
				$email_order .= EMAIL_SEPARATOR . "\n".
								'<b>'.EMAIL_TEXT_PAYMENT_METHOD . "</b>\n" ;
				$payment_class = $$payment;
				$email_order .= $payment_class->title . "\n\n";
				if ($payment_class->email_footer) {
				  $email_order .= $payment_class->email_footer . "\n";
				}
			  }
			$email_order .=  EMAIL_SEPARATOR."\n".CONFORMATION_EMAIL_FOOTER . "\n" ;
			
			  
			  $reservation_receipt_email = EMAIL_TEXT_SUBJECT_ORDER_CREATED.' # '.ORDER_EMAIL_PRIFIX_NAME.$insert_id;
			  tep_mail($firstname, $email_address, $reservation_receipt_email, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  // reservation email to customer - end
			//amit added for authorized.net auto charged start
			$order = new order($oID);
			$order->info['total'] = $Total;
			$order->billing['country']['title'] = $order->billing['country'];
			$order->delivery['country']['title'] = $order->delivery['country'];
			if($order->info['payment_method'] == 'Credit Card'){
				$order->info['cc_owner'] = $_POST['cc_owner'];
				$x_Card_Num = $order->info['cc_number'] = $_POST['cc_number'];
				$x_Exp_Date = $order->info['cc_expires'] = $_POST['cc_expires'] = $_POST['cc_expires_month'] . substr($_POST['cc_expires_year'], -2);
				$x_Card_Code = $order->info['cc_cvv'] = $_POST['cc_cvv'];
				$order->info['cc_type'] = $_POST['cc_type'] = $_POST['cc_credit_card_type'];
				include_once(DIR_FS_CATALOG_MODULES.'authorizenet_direct.php');	
				authorized_response_proccess();
			}
			//amit added for authorized.net auto charged end
			tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action')) . 'action=edit'));
			exit();
			}else{
				tep_redirect(tep_href_link("phone_booking.php", tep_get_all_get_params(array('action')) . 'action=add_product'));
			}
			//tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action')) . 'action=edit'));
	
		
		}
		
	break;
    }
	
	
	
	
	
  }

  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }




function split_desk_numbers_display_in_two_parts($number_to_split){

$number_in_two_parts_array = explode('-',$number_to_split);
$n = sizeof($number_in_two_parts_array);
if($n>1){
	for($t=1;$t<$n;$t++){
		$rest_number_in_two_parts_array .= $number_in_two_parts_array[$t].'-';
	}
}
$number_in_two_parts_array[1] = substr($rest_number_in_two_parts_array,0,-1);

return $number_in_two_parts_array;

}



function sbs_get_zone_name($country_id, $zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }
  
  

function calculate_discount_coupon_amount(){
  global $customer_id, $order, $subtotal, $dc_code, $currencies;
   //$cc_id = $_SESSION['cc_id']; //Fred commented out, do not use $_SESSION[] due to backward comp. Reference the global var instead.
	$qry_dc_id="";
	$od_amount = 0;
	$amount=$subtotal;
	$ret_err_msg="";
	if (isset($dc_code) ) {
		$coupon_get = tep_db_query("select coupon_amount, coupon_minimum_order, restrict_to_products, restrict_to_categories, coupon_type from " . TABLE_COUPONS ." where coupon_code = '". $dc_code . "'");
    $get_result = tep_db_fetch_array($coupon_get);
    $c_deduct = $get_result['coupon_amount'];
    if ($get_result['coupon_type']=='S') $c_deduct = $order->info['shipping_cost'];
			if ($get_result['coupon_minimum_order'] <= $subtotal) {
				if ($get_result['restrict_to_products'] || $get_result['restrict_to_categories']) {
					$discount_available_prod=0;
					$discount_available_cat=0;
					for ($i=0; $i<sizeof($order->products); $i++) {
						if ($get_result['restrict_to_products']) {
							$pr_ids = split("[,]", $get_result['restrict_to_products']);
							for ($ii = 0; $ii < count($pr_ids); $ii++) {
								if ($pr_ids[$ii] == tep_get_prid($order->products[$i]['id'])) {
									if ($get_result['coupon_type'] == 'P') {
										/* Fixes to Gift Voucher module 5.03
										=================================
										Submitted by Rob Cote, robc@traininghott.com

										original code: $od_amount = round($amount*10)/10*$c_deduct/100;
										$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
										$pod_amount = round($pr_c*10)/10*$c_deduct/100;
										*/
										$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
										//$pr_c = $this->product_price($pr_ids[$ii]); //Fred 2003-10-28, fix for the row above, otherwise the discount is calc based on price excl VAT!
										$pod_amount = round($pr_c*10)/10*$c_deduct/100;
										$od_amount = $od_amount + $pod_amount;
									} else {
										$od_amount = $c_deduct;
									}
									$discount_available_prod=1;
								}
							}
							if($discount_available_prod=="0"){
								$ret_err_msg = TXT_MSG_DISCOUNT_NOT_FOR_PRODUCTS;
							}
						} else {
							$cat_ids = split("[,]", $get_result['restrict_to_categories']);
							for ($i=0; $i<sizeof($order->products); $i++) {
								$my_path = tep_get_product_path(tep_get_prid($order->products[$i]['id']));
								$sub_cat_ids = split("[_]", $my_path);
								for ($iii = 0; $iii < count($sub_cat_ids); $iii++) {
									for ($ii = 0; $ii < count($cat_ids); $ii++) {
										if ($sub_cat_ids[$iii] == $cat_ids[$ii]) {
											if ($get_result['coupon_type'] == 'P') {
												/* Category Restriction Fix to Gift Voucher module 5.04
												Date: August 3, 2003
												=================================
												Nick Stanko of UkiDev.com, nick@ukidev.com

												original code:
												$od_amount = round($amount*10)/10*$c_deduct/100;
												$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
												$pod_amount = round($pr_c*10)/10*$c_deduct/100;
												*/
												//$od_amount = round($amount*10)/10*$c_deduct/100;
												//$pr_c = $order->products[$i]['final_price']*$order->products[$i]['qty'];
												$pr_c = $this->product_price(tep_get_prid($order->products[$i]['id'])); //Fred 2003-10-28, fix for the row above, otherwise the discount is calc based on price excl VAT!
												$pod_amount = round($pr_c*10)/10*$c_deduct/100;
												$od_amount = $od_amount + $pod_amount;
											} else {
												$od_amount = $c_deduct;
											}
											$discount_available_cat=1;
										}
									}
								}
							}
							if($discount_available_cat=="0"){
								$ret_err_msg = TXT_MSG_DISCOUNT_NOT_FOR_CATEGORIES;
							}
						}
					}
				} else {
					if ($get_result['coupon_type'] !='P') {
						$od_amount = $c_deduct;
					} else {
						$od_amount = $amount * $get_result['coupon_amount'] / 100;
					}
				}
			}else{
				$ret_err_msg = sprintf(TXT_MSG_MIN_AMOUNT_SHOULD, $currencies->format($get_result['coupon_minimum_order'], ''));
			}
      if ($od_amount>$amount) $od_amount = $amount;
    }
		
		if($ret_err_msg!=""){
			return 'err_'.$ret_err_msg;
		}else{
    	return $od_amount;
		}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('phone_booking');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
//if($action == "add_product"){

// #### Get Available Customers


?>
	  <tr>
      	<td class="main" valign="top">
		<?php
		if($customer == ''){
			$query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname ASC");
			$result = $query;
			
			
			if (tep_db_num_rows($result) > 0)
			{
			// Query Successful
			 $SelectCustomerBox = "<select name='Customer'><option value=''>". BUTTON_TEXT_CHOOSE_CUST . "</option>\n";
			 while($db_Row = tep_db_fetch_array($result))
			 { $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
			   if(isset($HTTP_GET_VARS['Customer']) && $db_Row["customers_id"]==$customer)
				$SelectCustomerBox .= " SELECTED ";
			  //$SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . " - " . $db_Row["customers_id"] . "</option>\n";
			   $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";
			
				}
			
				$SelectCustomerBox .= "</select>\n";
			}

        echo  '<form name="CusForm" id="CusForm" action="' . FILENAME_PHONE_BOOKING . '" method="POST">' . "\n";
        echo  '<table border="0"><tr>' . "\n";
        echo  '<td><font class="main"><b>' . TEXT_SELECT_CUST . '</b></font><br>' . $SelectCustomerBox . '</td>' . "\n";
        echo  '<td valign="bottom" class=main><input type="submit" value="' . BUTTON_TEXT_SELECT_CUST . '"></td>' . "\n";
        echo  '</tr></table></form>' . "\n";
		}
        ?>
        <?php
        echo  '<form action="' . FILENAME_PHONE_BOOKING . '" method="POST">' . "\n";
        echo  '<table border="0"><tr>' . "\n";
        echo  '<td><font class="main"><b>' . ( ($customer == '') ? TEXT_OR_BY : str_replace('or ',' ',TEXT_OR_BY)) . '</b></font><br><input type="text" name="Customer_nr" value="'.$customer.'"></td>' . "\n";
        echo  '<td valign="bottom"><input type="submit" value="' . BUTTON_TEXT_CHOOSE_CUST . '"></td>' . "\n";
        echo  '</tr></table></form>' . "\n";
        ?>
		</td>
      </tr> 

<?php
	  
	// ############################################################################
	//   Get List of All Products
	// ############################################################################

		$result = tep_db_query("SELECT products_name, p.products_id, cd.categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc, " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.categories_id=ptc.categories_id and ptc.products_id=p.products_id  and p.products_id=pd.products_id and pd.language_id = '" . (int)$languages_id . "' ORDER BY cd.categories_name");
		while($row = tep_db_fetch_array($result))
		{
			extract($row,EXTR_PREFIX_ALL,"db");
			$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			$CategoryList[$db_categories_id] = $db_categories_name;
			$LastCategory = $db_categories_name;
		}

		// ksort($ProductList);

		$LastOptionTag = "";
		$ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
		$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
		foreach($ProductList as $Category => $Products)
		{
			$ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
			$ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
			asort($Products);
			foreach($Products as $Product_ID => $Product_Name)
			{
				$ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
			}

			if($Category != $LastCategory)
			{
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			}
		}


	// ############################################################################
	//   Add Products Steps
	// ############################################################################
	
	print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST' name='frm_step1'>\n";
			//<td class='dataTableContent' align='right'><b>". TEXT_ADD_PROD_STEP1 ."</b></td>
			print "
			<td valign='top'>";
			
			?>
            <table width="100%">
              <tr>
                <td class="main"><b>Customer Information</b></td>
              </tr>
              <tr>
                <td>
                    <?php echo tep_draw_hidden_field('customer_id', $customer); ?>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="main" align="right" width="20%"><?php echo ENTRY_FIRST_NAME; ?></td>
                            <td align="left" class="main" width="80%"><?php echo tep_draw_input_field('firstname', $customers_firstname) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                        </tr>
                        <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        <tr>
                            <td class="main" align="right"><?php echo ENTRY_LAST_NAME; ?></td>
                            <td align="left" class="main"><?php echo tep_draw_input_field('lastname', $customers_lastname) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                        </tr>
                        <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        <tr>
                            <td align="right" class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                            <td align="left" class="main"><?php echo tep_draw_input_field('email_address', $customers_email_address) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                        </tr>
                        <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        <tr>
                            <td align="right" class="main"><?php echo 'E-Mail Confirmation: '; ?></td>
                            <td align="left" class="main"><?php echo tep_draw_input_field('c_email_address', $customers_email_address) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                        </tr>
                    </table>
                </td>
              </tr>
              <tr>
                <td class="main"><b>Billing Information</b></td>
              </tr>
              <tr>
                <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="main" width="20%" align="right"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                            <td class="main" width="80%"><?php echo tep_draw_input_field('street_address', $address['entry_street_address']) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                          <tr>
                            <td class="main" align="right"><?php echo ENTRY_CITY; ?></td>
                            <td class="main"><?php echo tep_draw_input_field('city', $address['entry_city']) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                          <?php
                          if (ACCOUNT_STATE == 'true') {
                        ?>
                          <tr>
                            <td class="main" align="right"><?php echo ENTRY_STATE; ?></td>
                            <td class="main">
                        <?php
                            echo tep_draw_input_field('state', $address['entry_state']) . '&nbsp;' . TEXT_FIELD_REQUIRED;
                        ?>
                            </td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        <?php
                          }
                        ?>
                        <?php
                          if (ACCOUNT_SUBURB == 'true') {
                        ?>
                          <tr>
                            <td class="main" align="right"><?php echo ENTRY_SUBURB; ?></td>
                            <td class="main"><?php echo tep_draw_input_field('suburb', $address['entry_suburb']) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        <?php
                          }
                        ?>
                          <tr>
                            <td class="main" align="right"><?php echo ENTRY_POST_CODE; ?></td>
                            <td class="main"><?php echo tep_draw_input_field('postcode', $address['entry_postcode']) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                        
                          <tr>
                            <td class="main" align="right"><?php echo ENTRY_COUNTRY; ?></td>
                            <td class="main"><?php echo tep_draw_input_field('country', tep_get_country_name($address['entry_country_id'])) . '&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
                          </tr>
                          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                          <tr>
                            <td colspan="2">
                                <table width="100%" align="left" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="20%">&nbsp;</td>
                                    <td width="11%" class="main"><?php echo 'Country code'; ?></td>
                                    <td width="1%">&nbsp;</td>
                                    <td width="68%" class="main"><?php echo 'Area code'; ?></td>
                                </tr>
                                <tr>
                                    <?php	
                                    
                                    $select_phone_fax = tep_db_query("select customers_telephone, customers_fax from ".TABLE_CUSTOMERS." where customers_id = '" . (int)$customer . "'");
                                    $row_phone_fax = tep_db_fetch_array($select_phone_fax);
                                    
                                           /*$telephone_disp = explode('-',tep_db_prepare_input($row_phone_fax['customers_telephone']));
                                           $fax_disp = explode('-',tep_db_prepare_input($row_phone_fax['customers_fax']));*/
                                           
                                           $db_call_telephone_disp = tep_db_prepare_input($row_phone_fax['customers_telephone']);																			
                                            if(eregi('-',$db_call_telephone_disp)){																									
                                                $telephone_disp = split_desk_numbers_display_in_two_parts($db_call_telephone_disp);
                                            }else{
                                                $telephone_disp[1] = $db_call_telephone_disp;
                                            }	
                                            
                                           $db_call_fax_disp = tep_db_prepare_input($row_phone_fax['customers_fax']);																			
                                            if(eregi('-',$db_call_fax_disp)){																			
                                                $fax_disp = split_desk_numbers_display_in_two_parts($db_call_fax_disp);
                                            }else{
                                                $fax_disp[1] = $db_call_fax_disp;
                                            }	
                                            
                                            
                                           
                                    ?>
                                    <td class="main" align="right"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                                    <td class="main"><?php echo tep_draw_input_field('telephone_cc',$telephone_disp[0],'size=10  maxlength="4"'); ?></td>
                                    <td class="main"> - </td>
                                    <td class="main">
                                    <?php		
                                        echo tep_draw_input_field('telephone',$telephone_disp[1],'id="telephone" class="required"') . '&nbsp;' . TEXT_FIELD_REQUIRED; //validate-telephone-us?>
                                     </td>
                                </tr>
                                <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td> </tr>
                                <tr>
                                    <td class="main" align="right"><?php echo ENTRY_FAX_NUMBER; ?></td>
                                    <td class="main"><?php echo tep_draw_input_field('fax_cc',$fax_disp[0],'size=10 maxlength="4"'); ?></td>
                                    <td class="main"> - </td>
                                    <td class="main"><?php echo tep_draw_input_field('fax',$fax_disp[1],' class="skyborder"') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
                                </tr>
                                </table>
                            </td>
                          </tr>
                    </table>
                </td>
              </tr>
              <tr>
              	 <td>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <?php
                      $newsletter_array = array(array('id' => '1',
                                              'text' => ENTRY_NEWSLETTER_YES),
                                        array('id' => '0',
                                              'text' => ENTRY_NEWSLETTER_NO));
                      ?> 
                      <tr>
                        <td class="main" align="right" width="20%">&nbsp;<?php echo ENTRY_NEWSLETTER; ?></td>
                        <td class="main" width="80%">
                        <?php
                            echo tep_draw_pull_down_menu('newsletter', $newsletter_array, $account['customers_newsletter']) . '&nbsp;' . ENTRY_NEWSLETTER_TEXT . '&nbsp;<font color="#FF0000">Please explain the benefits of Newseletter</font>';
                        
						?>
                        </td>
                      </tr>
                   </table>
                </td>
              </tr>
              <tr><td height="20">&nbsp;</td></tr>
              <tr>
              	<td  class="dataTableContent">
                <?php
				//$tree = tep_get_category_tree();
				//$dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
				//echo $dropdown;
				$add_product_categories_id_select = $add_product_categories_id;

				$product_model_select_array = array();
				$product_model_select_array[] = array('id' => '', 'text' => '-- Select Tour Code --');
				$product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and  pd.language_id = '" . (int)$languages_id . "' and p.agency_id !='".GLOBUS_AGENCY_ID."' order by p.products_id ");
				while($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql))
				{
						$tour_code_last_digits = explode("-", $product_model_sel_row['products_model']);
						$product_model_select_array[] = array('id' => $product_model_sel_row['products_model'], 'text' => $tour_code_last_digits[1].' ['.$product_model_sel_row['products_name'].']');
				}

				echo tep_draw_pull_down_menu('add_product_categories_id_select', $product_model_select_array , $add_product_categories_id, ' style="width:700px;" onchange="pb_set_product_ids_to_hidden(this.value);"');
	
				echo tep_draw_hidden_field('add_product_categories_id', $add_product_categories_id);			 
				//$CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
				//print $CategoryOptions;
				?>
                </td>
              </tr>
              <tr>
              	<td class="dataTableContent" align="right"><input type="submit" value="<?php echo TEXT_SELECT_TOUR_CODE; ?>" /></td>
              </tr>             
	        </table>
            <?php

			
			print "</td>";
			print "<input type='hidden' name='step' value='2'><input type='hidden' name='Customer_nr' value='".$customer."'>";
			foreach($_POST as $post_key=>$post_val){
				if(is_array($post_val)){
					foreach($post_val as $subpostkey=>$subpostval){
						if(is_array($subpostval)){
							foreach($subpostval as $subpostkey1=>$subpostval1){
							print "<input type='hidden' name='".$post_key."[".$subpostkey."][".$subpostkey1."]' value='".$subpostval1."'>";
							}
						}else{
							print "<input type='hidden' name='".$post_key."[".$subpostkey."]' value='".$subpostval."'>";
						}
					}
				}
			}
			print '<input type="hidden" name="order_product_method" value="'.tep_db_prepare_input($HTTP_POST_VARS['order_product_method']).'">';		
			if (tep_not_null($HTTP_POST_VARS['cc_owner']) || tep_not_null($HTTP_POST_VARS['cc_number'])) {
			print "<input type='hidden' name='cc_credit_card_type' value='".$HTTP_POST_VARS['cc_credit_card_type']."'>";
			print "<input type='hidden' name='cc_owner' value='".$HTTP_POST_VARS['cc_owner']."'>";	
			print "<input type='hidden' name='cc_number' value='".$HTTP_POST_VARS['cc_number']."'>";	
			print "<input type='hidden' name='cc_expires_month' value='".$HTTP_POST_VARS['cc_expires_month']."'>";	
			print "<input type='hidden' name='cc_expires_year' value='".$HTTP_POST_VARS['cc_expires_year']."'>";	
			print "<input type='hidden' name='cc_cvv' value='".$HTTP_POST_VARS['cc_cvv']."'>";
			}
			print "<input type='hidden' name='gc_code' value='".$HTTP_POST_VARS['gc_code']."'>";
			print "<input type='hidden' name='gc_total' value='".$HTTP_POST_VARS['gc_total']."'>";
			print "<input type='hidden' name='gv_redeem_code' value='".$HTTP_POST_VARS['gv_redeem_code']."'>";
			print "<input type='hidden' name='dc_total' value='".$HTTP_POST_VARS['dc_total']."'>";
			print "</td>";
			print "</form></tr>";
			print "<tr><td colspan='3'>&nbsp;</td></tr>";
		
		if(isset($add_product_categories_id) && $add_product_categories_id != ''){
		
			$check_prod_model_sql = tep_db_query("SELECT products_name, p.products_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and products_model in ('".str_replace(',', '\',\'', $add_product_categories_id)."') and pd.language_id = '" . (int)$languages_id . "'");
			$add_product_products_id = '';
			$ProductName = '';
			while($check_prod_model_row = tep_db_fetch_array($check_prod_model_sql))
			{
				$valid_product_model = 'true';				
				$ProductName .= $check_prod_model_row['products_name'].'|#|';
				$add_product_products_id .= $check_prod_model_row['products_id'].',';
			}
			$ProductName = substr($ProductName, 0, -3);
			$add_product_products_id = substr($add_product_products_id, 0, -1);
			$force_exit = 'true';
		}

		print "<tr><td><table width='100%' border='0'>\n";

		// Set Defaults
			if(!IsSet($add_product_categories_id))
			$add_product_categories_id = 0;

			if(!IsSet($add_product_products_id))
			$add_product_products_id = 0;

		// Step 1: Choose Category
		/*
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b><?php echo TEXT_ADD_PROD_STEP1 ?></b></td><td class='dataTableContent' valign='top'>";

			$tree = tep_get_category_tree();
			$dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
			echo $dropdown;


			
			$CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
			print $CategoryOptions;
			print "</td>";
			print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_CAT . "'>";
			print " <input type='hidden' name='step' value='2'>";
			print "</td>";
			print "</form></tr>";
			*/
			if(isset($add_product_categories_id) && $add_product_categories_id != '0' && $add_product_categories_id != ''){
				//print "<tr><td class='dataTableContent' valign='top'>";
					//echo $add_product_categories_id;
					$products_ids_array = explode(',', $add_product_categories_id);
					print "<tr><td colspan='2'><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'><table width='100%' style='border:1px solid gray;'>";
					$cart_grand_total = 0;
					for ($iii=0, $n=sizeof($products_ids_array); $iii<$n; $iii++) {
						$products_id_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model = '" . $products_ids_array[$iii] . "'");
						$get_products_id = tep_db_fetch_array($products_id_query);					
						$products_id = $get_products_id['products_id'];
						//if($iii%2 == 0){
						print "<tr>";
						//}
						
						print "<td valign='top'><table width='100%'>";
						
						
						
			//$add_product_products_id = $prod_val;
			// Get Options for Products
			$Options = array();
			$ProductOptionValues = array();
			$result = tep_db_query("SELECT distinct po.products_options_id, po.products_options_name,  pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price, pa.kids_values_price FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$products_id'");
 			$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($products_id);
			// Skip to Step 4 if no Options
			print "<tr><td height='5'>&nbsp;</td></tr><tr><td class='main'><b>".tep_get_products_name($products_id)." [".tep_get_products_model($products_id)."]</b></td></tr><tr><td height='5'>&nbsp;</td></tr>";
			if(tep_db_num_rows($result) == 0){
				
				/*print "<tr>\n";
				print "<td class='dataTableContent' valign='top'><i>" . TEXT_SELECT_OPT_SKIP . "</i></td>";
				print "</tr>\n";
				*/
			}else{
				while($row = tep_db_fetch_array($result))
				{
				  
					extract($row,EXTR_PREFIX_ALL,"db");
					$Options[$db_products_options_id] = $db_products_options_name;
					
					
				  if ($db_options_values_price != '0') {
				  	//amit modified to make sure price on usd
						if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){									
						 $db_options_values_price = tep_get_tour_price_in_usd($db_options_values_price,$tour_agency_opr_currency);
						}
					//amit modified to make sure price on usd
					$db_products_options_values_name .= ' (' . $db_price_prefix. number_format($db_options_values_price, 2, '.', '') .') ';
				  }
				  //amit added to show Holiday Surcharge -- for special price start
				  if($db_single_values_price > 0 || $db_kids_values_price > 0){
					 $db_products_options_values_name .=  ' ('.TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE.') ';				 
				  }
				  //amit added to show Holiday Surcharge -- for special price end
					
					$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
				
				
				}

				//print "<tr><td height='5'>&nbsp;</td></tr><tr><td class='main'><b>".tep_get_products_name($products_id)."</b></td></tr><tr><td height='5'>&nbsp;</td></tr>";
				print "<tr class=\"dataTableRow\"><td class='dataTableContent' colspan='2' valign='top'>";
				foreach($ProductOptionValues as $OptionID => $OptionValues)
				{
					if($OptionID != ''){
						$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[".$products_id."][$OptionID]'>";
						foreach($OptionValues as $OptionValueID => $OptionValueName)
						{
						
						$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
						}
						$OptionOption .= "</select><br>\n";
					}	
					if(IsSet($add_product_options[$products_id]))
					$OptionOption = str_replace("value='" . $add_product_options[$products_id][$OptionID] . "'","value='" . $add_product_options[$products_id][$OptionID] . "' selected",$OptionOption);

					print $OptionOption;
				}
				print "</td>";
				//print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'>";
				
				print "</td></tr>";
				
				
				
			}
			
			print "<tr class=\"dataTableRow\">";
			/*
			print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP4 . "</b></td>";
			print "<td class='dataTableContent' valign='top'>".TEXT_ADD_QUANTITY.":  <input name='add_product_quantity' size='2' value='".$add_product_quantity."'>" ;
			*/
			//print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP4 . "</b></td>";
			print "<td class='dataTableContent'  valign='top'>";
			print "<input type='hidden' name='add_product_quantity' value='1'>";
			//print "</td>";
			/*
			if(IsSet($add_product_options))
			{
				foreach($add_product_options as $option_id => $option_value_id)
				{
					print "<input type='hidden' name='add_product_options[$option_id]' value='$option_value_id'>";
				}
			}
			
			print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			*/
			//amit added to add departure info start
				//bhavik add your code to diplay lodgin info here
			
			$add_product_products_id_str = $add_product_products_id;
			$total_product_ids = explode(',', $add_product_products_id_str);
			//foreach($total_product_ids as $prod_key=>$prod_val){
				$add_product_products_id = $products_id;
				//echo $_POST['room-0-adult-total'][$add_product_products_id];
				//print_r($_POST);
				/*
				foreach($_POST as $post_key=>$post_val){
					if(is_array($post_val) && isset($post_val[$add_product_products_id])){
						$fwd_arr[$post_key] = array();
						$fwd_arr[$post_key] = array_shift($post_val);
						//echo  field_forwarder($post_key);
						$_POST[$post_key] = $HTTP_POST_VARS[$post_key] = $post_val[$add_product_products_id];
					}
				}
				*/
				$_POST['add_product_products_id'] = $HTTP_POST_VARS['add_product_products_id'] = $add_product_products_id;
				if($_POST['room-0-adult-total'][$add_product_products_id])
				{
					//echo 'here in '.$add_product_products_id;
					include("edit_new_product_save_pb.php");
				}	
				//echo 'here in '.$add_product_products_id;
				include("edit_new_product_pb.php");
			//}
			//amit added to add departure info end
			print "</td>";
			echo '<td align="right" class="main">'.$currencies->format($final_product_price_arr[$add_product_products_id]).'<br /><input type="button" name="tour_delete" value="Delete" onclick="tourcode_delete(\''.$products_ids_array[$iii].'\');" /></td>';
			$cart_grand_total = $cart_grand_total + $final_product_price_arr[$add_product_products_id];
			print "<input type='hidden' name='step' value='5'><input type='hidden' name='Customer_nr' value='".$customer."'>";			
			echo  field_forwarder('add_product_categories_id');
			echo  field_forwarder('add_product_products_id');
			//echo  field_forwarder('add_product_options');			
			
			echo  field_forwarder('order_product_method');
			echo  field_forwarder('cc_credit_card_type');
			echo  field_forwarder('cc_owner');
			echo  field_forwarder('cc_number');
			echo  field_forwarder('cc_expires_month');
			echo  field_forwarder('cc_expires_year');			
			echo  field_forwarder('cc_cvv');
			
			/* customer details forward - start */
			echo  field_forwarder('customer_id');
			echo  field_forwarder('firstname');
			echo  field_forwarder('lastname');
			echo  field_forwarder('email_address');
			echo  field_forwarder('street_address');
			echo  field_forwarder('city');
			echo  field_forwarder('state');
			echo  field_forwarder('suburb');
			echo  field_forwarder('postcode');
			echo  field_forwarder('country');
			echo  field_forwarder('telephone_cc');
			echo  field_forwarder('telephone');
			echo  field_forwarder('fax_cc');
			echo  field_forwarder('fax');
			echo  field_forwarder('newsletter');
			/* customer details forward - start */
			
			foreach($_POST as $post_key=>$post_val){
				if(is_array($post_val)){
					foreach($post_val as $subpostkey=>$subpostval){
						if(is_array($subpostval)){
							foreach($subpostval as $subpostkey1=>$subpostval1){
							print "<input type='hidden' name='".$post_key."[".$subpostkey."][".$subpostkey1."]' value='".$subpostval1."'>";
							}
						}else{
							print "<input type='hidden' name='".$post_key."[".$subpostkey."]' value='".$subpostval."'>";
						}
					}
				}
			}
			
			
			print "</td>\n";
			print "</tr>";
						
						
						print "</table></td>";
						//if($iii%2 != 0){
							print "</tr>";	
						//}
						
					}
					print "<tr><td align='right' class='main'><b>".$currencies->format($cart_grand_total)."</b></td></tr>";
					
					print "<tr><td>";
					/* new functionality */ 
					//Start - gift certificate discount
					$subtotal = $cart_grand_total;
					if($_POST['gc_code']!=""){
						$gc_code = $_POST['gc_code'];
						
						$gc_query = tep_db_query("SELECT amount_purchased, amount_purchased-amount_redeemed as current_value, amount_redeemed FROM " . TABLE_GIFT_CERTIFICATES . " where gc_code='" . $gc_code . "' AND gc_status=1");
						if (tep_db_num_rows($gc_query) != 0) {
							$gcvalue=tep_db_fetch_array($gc_query);
							if($gcvalue['current_value'] <= 0){
								$ret_err_gc='<font color="#FF0000">'.ERROR_NO_INVALID_REDEEM_GC.'</font>';
							}else{
								if($gcvalue['current_value']>$subtotal){
									$gc_total=$subtotal;
								}else{
									$gc_total=$gcvalue['current_value'];
								}
								$gc_redeemed=$gcvalue['amount_redeemed'];
								$gc_redeemed=tep_round($gcvalue['amount_redeemed'],2);
								$subtotal=$subtotal - $gc_total;
							//	$grand_total=$subtotal-$gc_total;
							//	$ret_val=$currencies->format($gc_total)."||".$currencies->format($grand_total);
								//$ret_val=Total discount value."||".Total remained after discount
							}
						}else{
							$ret_err_gc='<font color="#FF0000">'.ERROR_NO_INVALID_REDEEM_GC.'</font>';
						}
					}else{
						//$ret_val=$currencies->format($subtotal);
					}
					//End - gift certificate discount
					
					//Start - discount coupon
					$dc_code = $_POST['gv_redeem_code'];
					if($dc_code!=""){
						//Validate coupon
						// get some info from the coupon table ICW change 5.10b
							$ret_err_dc="";
								$coupon_query=tep_db_query("select coupon_id, coupon_amount, coupon_type, coupon_minimum_order,uses_per_coupon, uses_per_user, restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='".$dc_code."' and coupon_active='Y'");
								$coupon_result=tep_db_fetch_array($coupon_query);
								if ($coupon_result['coupon_type'] != 'G') {
									if (tep_db_num_rows($coupon_query)==0 && $ret_err_dc=="") {
										$ret_err_dc='<font color="#FF0000">'.ERROR_NO_INVALID_REDEEM_COUPON.'</span>';
									}
								 // below line changed for ICW 5.10b
								$date_query=tep_db_query("select coupon_start_date from " . TABLE_COUPONS . " where coupon_start_date <= now() and coupon_code='".$dc_code."'");
									if (tep_db_num_rows($date_query)==0 && $ret_err_dc=="") {
										$ret_err_dc='<font color="#FF0000">'.ERROR_INVALID_STARTDATE_COUPON.'</span>';
									}
								// below line changed for ICW 5.10b
								$date_query=tep_db_query("select coupon_expire_date from " . TABLE_COUPONS . " where coupon_expire_date >= now() and coupon_code='".$dc_code."'");
					
									if (tep_db_num_rows($date_query)==0 && $ret_err_dc=="") {
										$ret_err_dc='<font color="#FF0000">'.ERROR_INVALID_FINISDATE_COUPON.'</span>';
									}
							//below two lines changed for ICW 5.10b
								$coupon_count = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id']."'");
								$coupon_count_customer = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id']."' and customer_id = '" . $customer_id . "'");
					
								if (tep_db_num_rows($coupon_count)>=$coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0 && $ret_err_dc=="") {
									$ret_err_dc='<font color="#FF0000">'.ERROR_INVALID_USES_COUPON . $coupon_result['uses_per_coupon'] . TIMES . '</span>';
								}
					
								if (tep_db_num_rows($coupon_count_customer)>=$coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0 && $ret_err_dc=="") {
									$ret_err_dc='<font color="#FF0000">'.ERROR_INVALID_USES_USER_COUPON . $coupon_result['uses_per_user'] . TIMES . '</span>';
								}
								if ($coupon_result['coupon_type']=='S') {
									$coupon_amount = $order->info['shipping_cost'];
								} else {
									$coupon_amount = $currencies->format($coupon_result['coupon_amount']) . ' ';
								}
								if ($coupon_result['coupon_type']=='P') $coupon_amount = $coupon_result['coupon_amount'] . '% ';
								if ($coupon_result['coupon_minimum_order']>0) $coupon_amount .= 'on orders greater than ' . $coupon_result['coupon_minimum_order'];
								//if (!tep_session_is_registered('cc_id')) tep_session_register('cc_id'); //Fred - this was commented out before
							// $_SESSION['cc_id'] = $coupon_result['coupon_id']; //Fred commented out, do not use $_SESSION[] due to backward comp. Reference the global var instead.
							 }
							 
							if(trim($ret_err_dc)==""){
							$dc_total=calculate_discount_coupon_amount();
								if(substr($dc_total, 0, 4)=='err_'){
									$ret_err_dc='<font color="#FF0000">'.substr($dc_total, 4).'</span>';
									$dc_total=0;
								}
								$subtotal=$subtotal - $dc_total;
							}
					}
					//End - discount coupon
					
					?>
                    <table width="100%">				
                        
                        <tr>
                        <td class="main">
                        
                        <?php //echo TEXT_GIFT_CERTIFICATE_CODE; ?></td>
                        <td class="main">
                        <?php 
                        //echo (($gc_code!="" && $ret_err_gc=='')?'<div class="fleft">'.$gc_code.'</div>'.tep_draw_hidden_field("gc_code", $selected_gc_code, "id='gc_code'"):tep_draw_input_field("gc_code", $selected_gc_code, "id='gc_code'".$disable_discount_fields, "text", false));
						echo tep_draw_hidden_field("gc_code", $gc_codes, "id='gc_code'".$disable_discount_fields, "", false);
						?>
                        </td>
                        <td class="main" align="right">
                        <?php //echo $image_submit = ($gc_code!="")?' ':'<input type="image" name="submit_redeem" onclick="getInfo(\''.tep_href_link('ajax_discount.php', 'callajax=truesend', 'NONSSL').'\'); return false;" src="' . DIR_WS_TEMPLATE_IMAGES . 'buttons/' . $language . '/apply_btn.jpg" border="0" alt="' . IMAGE_REDEEM_VOUCHER . '" title = "' . IMAGE_REDEEM_VOUCHER . '" style="border:0 none; " class="apply_btn" />';?>
                        <div id="ajax_resp_err_gc_code">
						<?php 
						if($gc_code != ''){
							if($ret_err_gc!=""){
								echo $ret_err_gc;
								$gc_total = 0;
							}else{
								echo  '<font color="#FF0000"> -'.$currencies->format($gc_total).'</font>';	
							}
						}
						echo tep_draw_hidden_field('gc_total', $gc_total);
						?>
                        </div>
                        
                        <div class="cart_gift_certi_disc_amt" id="gift_certificate_discount_amt"></div>
                        </td>
                        </tr>
                        <tr>
                        <td class="main"><?php echo TEXT_ENTER_COUPON_CODE; ?></td>
                        <td class="main">
                        <?php
                        if($total_group_discount>0){
                            $disable_discount_fields='disabled="disabled" style="background-color:#CCCCCC;"';
                        }else{
                            $disable_discount_fields='';
                            $image_submit = '<input type="image" name="submit_redeem" onclick="getInfo(\''.tep_href_link('ajax_discount.php', 'callajax=truesend', 'NONSSL').'\'); return false;" src="' . DIR_WS_TEMPLATE_IMAGES . 'buttons/' . $language . '/apply_btn.jpg" style="border:0px;" alt="' . IMAGE_REDEEM_VOUCHER . '" title = "' . IMAGE_REDEEM_VOUCHER . '" class="apply_btn" />';
                        }
                        if(isset($gv_redeem_code) && tep_not_null($gv_redeem_code)){
                            $selected_gv_redeem_code = $gv_redeem_code;
                        }else{
                            $selected_gv_redeem_code = $_SESSION['last_updated_gv_redeem_code'];
                        }
                        echo tep_draw_input_field('gv_redeem_code', $selected_gv_redeem_code, $disable_discount_fields);
                        //echo $image_submit;
                        ?>	
                        </td>
                        <td class="main" align="right">			
                        <div id="ajax_resp_err_gv_redeem_code">
                        	<?php 
							if($dc_code != ''){
								if($ret_err_dc!=""){
									echo $ret_err_dc;
									$dc_total = 0;
								}else{
									echo '<font color="#FF0000"> -'.$currencies->format($dc_total).'</font>';	
								}
							}
							echo tep_draw_hidden_field('dc_total', strval($dc_total));
							?>
                        </div>
                        <div class="cart_gift_certi_disc_amt" id="coupon_discount_amt"></div>
                        </td>
                        </tr>
                        <tr><td colspan="3" align="right">
                        <?php
                            $disp_order_total = $cart_grand_total;
                            echo tep_draw_hidden_field('disp_order_total', $disp_order_total, 'id="disp_order_total"');
                        ?>
                        <div class="main" align="right" id="ajax_resp_gc"><b>TOTAL: &nbsp; <?php echo $currencies->format($subtotal); ?></b></div>
                        </td>
                        </tr>
                    </table>
                    <?php 
					/*new functionality */					 
					print "</td></tr>";
					
					print "<tr><td class='dataTableContent'>";
					require(DIR_WS_CLASSES . 'payment.php');
					$payment_modules = new payment;
					
					$payment_method_array = array();
					//$payment_method_array[] = array('id' => '', 'text' => TEXT_SELECT_PAYMENT_METHOD);
					$selection = $payment_modules->selection();
					
					for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
						if($selection[$i]['id'] == 'authorizenet'){
							 $selection[$i]['module'] = 'Credit Card';
						}else if($selection[$i]['id'] == 'cc_cvc'){
							 $selection[$i]['module'] = 'Credit Card';
						}else if($selection[$i]['id'] == 'moneyorder'){
							$selection[$i]['module'] = "Money Order/Traveler's Check/Cashier's Check (Please note we do NOT accept personal check.)";
						}
					 $payment_method_array[] = array('id' => $selection[$i]['module'], 'text' => $selection[$i]['module']);
					
					}
					if($step == 5){
					?>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					 <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					  <tr>
						<td class="main">Payment Method:</td>
						<td>
						<?php				
						echo tep_draw_pull_down_menu('order_product_method',  $payment_method_array, '', 'onchange="show_cc_fields(this.value);"');
						?>
						</td>
					  </tr>
					   <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					  <tr>
						
						<td class="2" colspan="2">
						<?php
						
						  for ($i=1; $i<13; $i++) {
							$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
						  }
					
						  $today = getdate();
						  for ($i=$today['year']; $i < $today['year']+10; $i++) {
							$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
						  }
					 ?>
							<div id="cc_fields_show">	
                                <table  border="0" cellspacing="0" cellpadding="2">
								 <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Type:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><select name="cc_credit_card_type"><option value="Mastercard">Mastercard</option><option value="Visa">Visa</option></select></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
								  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Owner:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo tep_draw_input_field('cc_owner');?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
								  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Number:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo tep_draw_input_field('cc_number') ;?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
								  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Expiry Date:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo  tep_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year);?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
								  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">CVV number</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo tep_draw_input_field('cc_cvv','',"SIZE=4, MAXLENGTH=4"); ?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
								</table>
                             </div>
						
						</td>
					  </tr>
					 
					</table>
		
					<?php
					
					}else{
					if(isset($HTTP_POST_VARS['order_product_method']) && $HTTP_POST_VARS['order_product_method'] != ''){
					?>
					<table  border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					  <tr>
						<td class="main">Payment Method:</td>
						<td class="main">
						<?php			
						echo tep_db_prepare_input($HTTP_POST_VARS['order_product_method']);			
						print '<input type="hidden" name="order_product_method" value="'.tep_db_prepare_input($HTTP_POST_VARS['order_product_method']).'">';		
						?>
						</td>
					  </tr>
					  
					  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					  <?php
					
					  
					if (tep_not_null($HTTP_POST_VARS['cc_owner']) || tep_not_null($HTTP_POST_VARS['cc_number'])) {
					
					 print "<input type='hidden' name='cc_credit_card_type' value='".$HTTP_POST_VARS['cc_credit_card_type']."'>";	
					 print "<input type='hidden' name='cc_owner' value='".$HTTP_POST_VARS['cc_owner']."'>";	
					 print "<input type='hidden' name='cc_number' value='".$HTTP_POST_VARS['cc_number']."'>";	
					 print "<input type='hidden' name='cc_expires_month' value='".$HTTP_POST_VARS['cc_expires_month']."'>";	
					 print "<input type='hidden' name='cc_expires_year' value='".$HTTP_POST_VARS['cc_expires_year']."'>";	
					 print "<input type='hidden' name='cc_cvv' value='".$HTTP_POST_VARS['cc_cvv']."'>";
					 
						
					  ?>
		
					  <tr>
						
						<td class="2" colspan="2">
						
								<table  border="0" cellspacing="0" cellpadding="2">
								 <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Type:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">
									<?php echo $HTTP_POST_VARS['cc_credit_card_type']; ?>
									</td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
																				  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Owner:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo $HTTP_POST_VARS['cc_owner']; ?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
																				  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Number:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo $HTTP_POST_VARS['cc_number']; ?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
																				  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">Credit Card Expiry Date:</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo $HTTP_POST_VARS['cc_expires_month'].$HTTP_POST_VARS['cc_expires_year']; ?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
																				  <tr>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main">CVV number</td>
									<td><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
									<td class="main"><?php echo $HTTP_POST_VARS['cc_cvv']; ?></td>
									<td width="10"><img src="<?php echo DIR_WS_IMAGES;?>pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
								  </tr>
							   </table>
						
						</td>
					  </tr>
						<?php } ?>
					</table>
		
		
					<?			
					}
					}
					print "</td></tr>";
					print "<tr><td align='right'><input type='submit' value='Select These Options'></td></tr>";
					print "</table></form></td></tr>";
					//print "<td class='dataTableContent' align='center'>";
				//print "</td></tr>";
			}
			
			
			
			print "<tr><td height='20'>&nbsp;</td></tr>";
			if($step == 5 && $HTTP_POST_VARS['order_product_method']!='')
			{
				if(($HTTP_POST_VARS['order_product_method'] == 'Credit Card' && $HTTP_POST_VARS['cc_number']!='') || $HTTP_POST_VARS['order_product_method'] != 'Credit Card'){
				
				if($HTTP_POST_VARS['order_product_method'] != 'Credit Card'){
					$HTTP_POST_VARS['cc_credit_card_type'] = '';
					$HTTP_POST_VARS['cc_owner'] = '';
					$HTTP_POST_VARS['cc_number'] = '';
					$HTTP_POST_VARS['cc_expires_month'] = '';
					$HTTP_POST_VARS['cc_expires_year'] = '';			
					$HTTP_POST_VARS['cc_cvv'] = '';
				}
				define('TEXT_ADD_STEP5', 'STEP 5:');
				define('TEXT_ADD_STEP6', 'STEP 6:');
				
				print "<tr><td colspan='3' class='main' align='center'>&nbsp;&nbsp;<font color='#FF0000'>Please verify billing and order information with customer and adjust info before submitting charge.</font></td></tr>\n";
				print "<tr class=\"dataTableRow\">\n";
				print "<td class='dataTableContent' align='center' colspan='2'>";
				include "create_orders_guestname_pb.php";
				print "</td></tr>\n"; 
				}
				
			}	
			
		print "</table></td></tr>";
//}
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<script type="text/javascript">
function deleteproducts(index){
	
		for ( i= 0 ; i < window.document.forms[0].elements.length ; i ++)
					{
						if(window.document.forms[0].elements[i].name == "update_products["+index+"][qty]" )
						{
							window.document.forms[0].elements[i].value = 0;
							edit_order.submit(); 
							
						}
						
					}
}

</script>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_country_id
  //
  // Arguments   : country_name		country name string
  //
  // Return      : country_id
  //
  // Description : Function to retrieve the country_id based on the country's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_country_id($country_name) {

    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");

    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_zone_id
  //
  // Arguments   : country_id		country id string
  //               zone_name		state/province name
  //
  // Return      : zone_id
  //
  // Description : Function to retrieve the zone_id based on the zone's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_zone_id($country_id, $zone_name) {

    $zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");

    if (!tep_db_num_rows($zone_id_query)) {
      return 0;
    }
    else {
      $zone_id_row = tep_db_fetch_array($zone_id_query);
      return $zone_id_row['zone_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_field_exists
  //
  // Arguments   : table	table name
  //               field	field name
  //
  // Return      : true/false
  //
  // Description : Function to check the existence of a database field
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_field_exists($table,$field) {

    $describe_query = tep_db_query("describe $table");
    while($d_row = tep_db_fetch_array($describe_query))
    {
      if ($d_row["Field"] == "$field")
      return true;
    }

    return false;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_html_quotes
  //
  // Arguments   : string	any string
  //
  // Return      : string with single quotes converted to html equivalent
  //
  // Description : Function to change quotes to HTML equivalents for form inputs.
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_html_unquote
  //
  // Arguments   : string	any string
  //
  // Return      : string with html equivalent converted back to single quotes
  //
  // Description : Function to change HTML equivalents back to quotes
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
<script type="text/javascript">
function pb_set_product_ids_to_hidden(tourcode){
	if(document.frm_step1.add_product_categories_id.value ==''){
		document.frm_step1.add_product_categories_id.value = tourcode;
	}else{
		var tourcodes_str = document.frm_step1.add_product_categories_id.value;
		var tourcodes_arr = tourcodes_str.split(',');
		var tourcodes_str1 = '';
		var add_tourcode_to_str = 1;
		for(i=0; i<tourcodes_arr.length; i++){
			if(tourcodes_arr[i] == tourcode){
				add_tourcode_to_str = 0;
			}
		}
		if(add_tourcode_to_str == 1){
			document.frm_step1.add_product_categories_id.value = document.frm_step1.add_product_categories_id.value+','+tourcode;
		}
	}
}
function tourcode_delete(tourcode){
	var tourcodes_str = document.frm_step1.add_product_categories_id.value;
	var tourcodes_arr = tourcodes_str.split(',');
	var tourcodes_str1 = '';
	for(i=0; i<tourcodes_arr.length; i++){
		if(tourcodes_arr[i] == tourcode){
			tourcodes_arr[i] = '';
		}else{
			tourcodes_str1 = tourcodes_str1+tourcodes_arr[i]+',';
		}
	}
	document.frm_step1.add_product_categories_id.value = tourcodes_str1.substr(0, (tourcodes_str1.length-1));
	document.frm_step1.submit();
}
function show_cc_fields(pmethod){
	if(pmethod == 'Credit Card'){
		document.getElementById('cc_fields_show').style.display = '';
	}else{
		document.getElementById('cc_fields_show').style.display = 'none';
	}
}
</script>