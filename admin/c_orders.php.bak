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
  $orders_ship_method_query = tep_db_query("select ship_method from orders_ship_methods");
  while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
    $orders_ship_method[] = array('id'   => $orders_ship_methods['ship_method'],
                                  'text' => $orders_ship_methods['ship_method']);
    $orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
  }

  $orders_pay_method = array();
  $orders_pay_method_array = array();
  $orders_pay_method_query = tep_db_query("select pay_method from orders_pay_methods");
  while ($orders_pay_methods = tep_db_fetch_array($orders_pay_method_query)) {
    $orders_pay_method[] = array('id'   => $orders_pay_methods['pay_method'],
                                  'text' => $orders_pay_methods['pay_method']);
    $orders_pay_method_array[$orders_pay_methods['pay_method']] = $orders_pay_methods['pay_method'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'edit');
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
$order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################

  if (tep_not_null($action)) {
    switch ($action) {

	// Update Order
	case 'update_order':

		$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
		$order = new order($oID);
		$status = tep_db_prepare_input($HTTP_POST_VARS['status']);
		$comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

		// Update Order Info
		$UpdateOrders = "update " . TABLE_ORDERS . " set
			customers_name = '" . tep_db_input(stripslashes($update_customer_name)) . "',
			customers_company = '" . tep_db_input(stripslashes($update_customer_company)) . "',
			customers_street_address = '" . tep_db_input(stripslashes($update_customer_street_address)) . "',
			customers_suburb = '" . tep_db_input(stripslashes($update_customer_suburb)) . "',
			customers_city = '" . tep_db_input(stripslashes($update_customer_city)) . "',
			customers_state = '" . tep_db_input(stripslashes($update_customer_state)) . "',
			customers_postcode = '" . tep_db_input($update_customer_postcode) . "',
			customers_country = '" . tep_db_input(stripslashes($update_customer_country)) . "',
			customers_telephone = '" . tep_db_input($update_customer_telephone) . "',
			customers_email_address = '" . tep_db_input($update_customer_email_address) . "',";

		if($SeparateBillingFields)
		{
		$UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($update_billing_name)) . "',
			billing_company = '" . tep_db_input(stripslashes($update_billing_company)) . "',
			billing_street_address = '" . tep_db_input(stripslashes($update_billing_street_address)) . "',
			billing_suburb = '" . tep_db_input(stripslashes($update_billing_suburb)) . "',
			billing_city = '" . tep_db_input(stripslashes($update_billing_city)) . "',
			billing_state = '" . tep_db_input(stripslashes($update_billing_state)) . "',
			billing_postcode = '" . tep_db_input($update_billing_postcode) . "',
			billing_country = '" . tep_db_input(stripslashes($update_billing_country)) . "',";
		}

		$UpdateOrders .= "delivery_name = '" . tep_db_input(stripslashes($update_delivery_name)) . "',
			delivery_company = '" . tep_db_input(stripslashes($update_delivery_company)) . "',
			delivery_street_address = '" . tep_db_input(stripslashes($update_delivery_street_address)) . "',
			delivery_suburb = '" . tep_db_input(stripslashes($update_delivery_suburb)) . "',
			delivery_city = '" . tep_db_input(stripslashes($update_delivery_city)) . "',
			delivery_state = '" . tep_db_input(stripslashes($update_delivery_state)) . "',
			delivery_postcode = '" . tep_db_input($update_delivery_postcode) . "',
			delivery_country = '" . tep_db_input(stripslashes($update_delivery_country)) . "',
			payment_method = '" . tep_db_input($update_info_payment_method) . "',
			account_name = '" . tep_db_input($account_name) . "',
			account_number = '" . tep_db_input($account_number) . "',
			po_number = '" . tep_db_input($po_number) . "',
			cc_type = '" . tep_db_input($update_info_cc_type) . "',
			cc_owner = '" . tep_db_input($update_info_cc_owner) . "',
			cc_cvv = '" . tep_db_input($update_info_cc_cvv) . "',
      last_modified = now(),";

		if(substr($update_info_cc_number,0,8) != "(Last 4)")
		$UpdateOrders .= "cc_number = '".scs_cc_encrypt($update_info_cc_number)."',";

		$UpdateOrders .= "cc_expires = '$update_info_cc_expires',
			orders_status = '" . tep_db_input($status) . "'";

		if(!$CommentsWithStatus)
		{
			$UpdateOrders .= ", comments = '" . tep_db_input($comments) . "'";
		}

		$UpdateOrders .= " where orders_id = '" . tep_db_input($oID) . "';";

		tep_db_query($UpdateOrders);

		$Query1 = "update orders set last_modified = now() where orders_id = '" . tep_db_input($oID) . "';";
		tep_db_query($Query1);
		$order_updated = true;


        	$check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        	$check_status = tep_db_fetch_array($check_status_query);

		// Update Status History & Email Customer if Necessary
		if ($order->info['orders_status'] != $status)
		{
			// Notify Customer
          		$customer_notified = '0';
			if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on'))
			{
			  $notify_comments = '';
			  if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
			  }
			  $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
			  tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			  $customer_notified = '1';
			}

			// "Status History" table has gone through a few
			// different changes, so here are different versions of
			// the status update.

			// NOTE: Theoretically, there shouldn't be a
			//       orders_status field in the ORDERS table. It
			//       should really just use the latest value from
			//       this status history table.

			if($CommentsWithStatus)
			{
			tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
				(orders_id, orders_status_id, date_added, customer_notified, comments)
				values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', now(), " . tep_db_input($customer_notified) . ", '" . tep_db_input($comments)  . "')");
			}
			else
			{
				if($OldNewStatusValues)
				{
				tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
					(orders_id, new_value, old_value, date_added, customer_notified)
					values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', '" . $order->info['orders_status'] . "', now(), " . tep_db_input($customer_notified) . ")");
				}
				else
				{
				tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
					(orders_id, orders_status_id, date_added, customer_notified)
					values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', now(), " . tep_db_input($customer_notified) . ")");
				}
			}
		}

		// check to see if there are products to update
    if (count($update_products) > 0)
		{
    // Update Products
		$RunningSubTotal = 0;
		$RunningTax = 0;
        // CWS EDIT (start) -- Check for existence of subtotals...
        // Do pre-check for subtotal field existence
		$ot_subtotal_found = false;
    	foreach($update_totals as $total_details)
		{
		    extract($total_details,EXTR_PREFIX_ALL,"ot");
			if($ot_class == "ot_subtotal")
			{
			    $ot_subtotal_found = true;
    			break;
			}
		}
		// CWS EDIT (end) -- Check for existence of subtotals...

		foreach($update_products as $orders_products_id => $products_details)
		{
			// Update orders_products Table
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			$order = tep_db_fetch_array($order_query);
			if ($products_details["qty"] != $order['products_quantity']){
				$differenza_quantita = ($products_details["qty"] - $order['products_quantity']);
					tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int)$order['products_id'] . "'");
					MCache::update_product($order['products_id']);//MCache update
			}
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if($products_details["qty"] > 0)
			{
				$Query = "update " . TABLE_ORDERS_PRODUCTS . " set
					products_model = '" . $products_details["model"] . "',
					products_name = '" . str_replace("'", "&#39;", $products_details["name"]) . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					where orders_products_id = '$orders_products_id';";
				tep_db_query($Query);

				// Update Tax and Subtotals
				$RunningSubTotal += $products_details["qty"] * $products_details["final_price"];
				$RunningTax += (($products_details["tax"]/100) * ($products_details["qty"] * $products_details["final_price"]));

				// Update Any Attributes
				if(IsSet($products_details[attributes]))
				{
					foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details)
					{
						$Query = "update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
							products_options = '" . $attributes_details["option"] . "',
							products_options_values = '" . $attributes_details["value"] . "'
							where orders_products_attributes_id = '{$orders_products_attributes_id}';";
						tep_db_query($Query);
					}
				}
			}
			else
			{
			
				//amit added to delete recored form flight and eticket table start				
				//$orders_product_id = tep_get_ordersid_productsid_from_orderproducts($orders_products_id); -hotel-extension
				$this_product_id = $orders_product_id['products_id'];
				$this_orders_id = $orders_product_id['orders_id'];
				$del_eticket_query = "delete from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_products_id = '".$orders_products_id."' and orders_products_id>0"; //orders_id = '".$this_orders_id."' and products_id= '".$this_product_id."'
			 	$del_flight_query =  "delete from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_products_id = '".$orders_products_id."' and orders_products_id>0"; //orders_id = '".$this_orders_id."' and products_id= '".$this_product_id."'
			
				tep_db_query($del_eticket_query);
				tep_db_query($del_flight_query);
			
//exit;
				//amit added to delete recored form flight and eticket table end
				// 0 Quantity = Delete
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '{$orders_products_id}';";
				tep_db_query($Query);
					//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
				$order = tep_db_fetch_array($order_query);
					if ($products_details["qty"] != $order['products_quantity']){
						$differenza_quantita = ($products_details["qty"] - $order['products_quantity']);
						tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int)$order['products_id'] . "'");
						MCache::update_product($order['products_id']);//MCache update
					}
					//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '{$orders_products_id}';";
				tep_db_query($Query);
			}
		}

		// Shipping Tax
			foreach($update_totals as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_shipping")
				{
					$RunningTax += (($AddShippingTax / 100) * $ot_value);
				}
			}

		// Update Totals

			$RunningTotal = 0;
			$sort_order = 0;

			// Do pre-check for Tax field existence
				$ot_tax_found = 0;
				foreach($update_totals as $total_details)
				{
					extract($total_details,EXTR_PREFIX_ALL,"ot");
					if($ot_class == "ot_tax")
					{
						$ot_tax_found = 1;
						break;
					}
				}

			foreach($update_totals as $total_index => $total_details)
			{
				extract($total_details,EXTR_PREFIX_ALL,"ot");

				if( trim(strtolower($ot_title)) == "tax" || trim(strtolower($ot_title)) == "tax:" )
				{
					if($ot_class != "ot_tax" && $ot_tax_found == 0)
					{
						// Inserting Tax
						$ot_class = "ot_tax";
						$ot_value = "x"; // This gets updated in the next step
						$ot_tax_found = 1;
					}
				}

				if( trim($ot_title) && trim($ot_value) )
				{
					$sort_order++;

					// Update ot_subtotal, ot_tax, and ot_total classes
						if($ot_class == "ot_subtotal")
						$ot_value = $RunningSubTotal;

						if($ot_class == "ot_tax")
						{
						$ot_value = $RunningTax;
						// print "ot_value = $ot_value<br>\n";
						}
//disocunt



     // CWS EDIT (start) -- Check for existence of subtotals...
						if($ot_class == "ot_total")
                        {

						    $ot_value = $RunningTotal ;
                            if ( !$ot_subtotal_found )
                            {
                                // There was no subtotal on this order, lets add the running subtotal in.
                                $ot_value = $ot_value + $RunningSubTotal;
                            }
                        }
     // CWS EDIT (end) -- Check for existence of subtotals...

					// Set $ot_text (display-formatted value)
						// $ot_text = "\$" . number_format($ot_value, 2, '.', ',');

						$order = new order($oID);
						$ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);

						if($ot_class == "ot_total")
						$ot_text = "<b>" . $ot_text . "</b>";

					if($ot_total_id > 0)
					{
						// In Database Already - Update
						$Query = "update " . TABLE_ORDERS_TOTAL . " set
							title = '$ot_title',
							text = '$ot_text',
							value = '$ot_value',
							sort_order = '$sort_order'
							where orders_total_id = '$ot_total_id'";
						tep_db_query($Query);
					}
					else
					{

						// New Insert
						$Query = "insert into " . TABLE_ORDERS_TOTAL . " set
							orders_id = '$oID',
							title = '$ot_title',
							text = '$ot_text',
							value = '$ot_value',
							class = '$ot_class',
							sort_order = '$sort_order'";
						tep_db_query($Query);
					}

					$RunningTotal += $ot_value;
				}
				elseif($ot_total_id > 0)
				{
					// Delete Total Piece
					$Query = "delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '$ot_total_id'";
					tep_db_query($Query);
				}

			}
		}
		if ($order_updated)
		{
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action')) . 'action=edit'));

	break;

	// Add a Product
	case 'add_product':
		if($step == 6)
		{
		
		
		//amit added to create orders start
				
				 // if ($HTTP_POST_VARS['action'] != 'process') {
				  //  tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL'));
				  //}
				  $customer_id = $_SESSION['customer_id'];
				  $gender = $_SESSION['gender'];
				  $firstname = $_SESSION['firstname'];
				  $lastname = $_SESSION['lastname'];
				  $dob = $_SESSION['dob'];
				  $email_address = $_SESSION['email_address'];
				  $telephone = $_SESSION['telephone'];
				  $fax = $_SESSION['fax'];
				  $newsletter = $_SESSION['newsletter'];
				  $password = $_SESSION['password'];
				  $confirmation = $_SESSION['confirmation'];
				  $street_address = $_SESSION['street_address'];
				  $company = $_SESSION['company'];
				  $suburb = $_SESSION['suburb'];
				  $postcode = $_SESSION['postcode'];
				  $city = $_SESSION['city'];
				  $zone_id = $_SESSION['zone_id'];
				  $state = $_SESSION['state'];
				  $country = $_SESSION['country'];
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
						  	'cc_type' => $_POST['cc_credit_card_type'],
						  	'cc_owner' => $_POST['cc_owner'],
						  	'cc_number' => scs_cc_encrypt($_POST['cc_number']),
						  	'cc_expires' => $_POST['cc_expires_month'].$_POST['cc_expires_year'],				
							'cc_cvv' => $_POST['cc_cvv'],					  
							'date_purchased' => 'now()',
                            'orders_status' => DEFAULT_ORDERS_STATUS_ID,
							'currency' => $currency,
							'currency_value' => $currency_value,
							'admin_id_orders' => $login_id
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
		
		//amit added to create orders end
		
		
		
		
	
		
		
			// Get Order Info
				$final_product_price = $_POST['final_product_price'];
				$final_product_price_cost = $_POST['final_product_price_cost'];
				$finaldate 			 = $_POST['finaldate'];
				$depart_time 		 = $_POST['depart_time'];
				$depart_location 	 = $_POST['depart_location'];
				$total_room_price 	 = $_POST['total_room_price'];
				$total_info_room 	 = $_POST['total_info_room'];
				$total_room_adult_child_info  = $_POST['total_room_adult_child_info'];
				$g_number  = $_POST['g_number'];
				
			$add_product_quantity = $_POST['add_product_quantity'];
			$add_product_options = $_POST['add_product_options'];
			//$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
			$order = new order($oID);

			$AddedOptionsPrice = 0;
			$AddedOptionsPrice_cost = 0;

			// Get Product Attribute Info
			if($add_product_options!='')
			{
				foreach($add_product_options as $option_id => $option_value_id)
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
			/*
			$Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set
				orders_id = $oID,
				products_id = $add_product_products_id,
				products_model = '$p_products_model',
				products_name = '" . str_replace("'", "&#39;", $p_products_name) . "',
				products_price = '$final_product_price',
				final_price = '" . ($final_product_price + $AddedOptionsPrice) . "',
				final_price_cost = '" . ($final_product_price_cost + $AddedOptionsPrice_cost) . "',
				products_tax = '$ProductsTax',
				products_quantity = $add_product_quantity,
				products_departure_date = '$finaldate',
				products_departure_time = '$depart_time',
				products_departure_location = '$depart_location',
				products_room_price = '$total_room_price',
				products_room_info = '$total_info_room'";
			tep_db_query($Query);
			*/
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
							'products_room_info' => $total_info_room
							);   
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_op_insert);
			$new_product_id = tep_db_insert_id();
			
			
			//amit added for cost cal history
			 $sql_data_array_original_insert = array(			  
								'orders_products_id' => $new_product_id,		
								'products_model' => $p_products_model,
								'products_name' => str_replace("'", "&#39;", $p_products_name),	
								'retail' => ($final_product_price + $AddedOptionsPrice),
								'cost' => ($final_product_price_cost + $AddedOptionsPrice_cost),
								'last_updated_date' => 'now()'																										  
								);
			tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
			//amit added for cost cal history
	
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");
			MCache::update_product($add_product_products_id);//MCache update
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if($add_product_options!='')
			{
				foreach($add_product_options as $option_id => $option_value_id)
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
				}
			}

			// Stock Update
			$stock_chk_query = tep_db_query("select products_quantity from products where products_id = '" . $add_product_products_id . "'");
			$stock_chk_values = tep_db_fetch_array($stock_chk_query);
    		$stock_chk_left = $stock_chk_values['products_quantity'] - $add_product_quantity;
			tep_db_query("update products set products_quantity = '" . $stock_chk_left . "' where products_id = '" . $add_product_products_id . "'");

			// Update products_ordered (for bestsellers list)
			tep_db_query("update products set products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");


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
						  'departure_time' => $departure_time);
  			tep_db_perform(TABLE_ORDERS_PRODUCTS_FLIGHT, $sql_data_array); 

  
			  //total_no_guest_tour
			  foreach($_POST as $key=>$val)
			  {
				if(strstr($key,'guest'))
				{
					$guest_name .= $val." <::>";
				}
			  }


			if($finaldate !=''){
				$display_departure_day_str = @convert_datetime($finaldate);
				$depature_full_address =  $finaldate.' '.$display_departure_day_str.' '.$depart_time.' &nbsp; '.$depart_location;
			  }else{
				$depature_full_address =  $finaldate.' '.$depart_time.' &nbsp; '.$depart_location;
			  }
			

			$sql_data_array = array('orders_id' => $oID,
								  'products_id' => $HTTP_POST_VARS['add_product_products_id'],
								  'guest_name' => $guest_name,
								  'guest_body_weight' => tep_db_input($guest_body_weight),						  
								  'depature_full_address' => tep_db_input($depature_full_address),	
								  'guest_number' => "0");
								 // 'guest_number' => $HTTP_POST_VARS['g_number']);
			tep_db_perform(TABLE_ORDERS_PRODUCTS_ETICKET, $sql_data_array);
  
 
			
			
			tep_redirect(tep_href_link("edit_orders.php", tep_get_all_get_params(array('action')) . 'action=edit'));
			exit();
			
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
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
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
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
      <tr>
        <td width="100%">
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
             <tr><td class=main><font color='#000000'><b><?php echo HEADING_STEP2 . $oID; ?></b></td></tr>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>


<!-- Begin Addresses Block -->
      <tr><?php echo tep_draw_form('edit_order', "edit_orders.php", tep_get_all_get_params(array('action','paycc')) . 'action=update_order'); ?>
	<td><table width="100%" border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td valign="top">
	    <!-- Customer Info Block -->
		<table border="0" cellspacing="0" cellpadding="2">
		<tr>
		<td colspan='2' class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
		<td colspan='2' class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
		</tr>
		<tr>
		<td colspan='2' class="main">
		<table border="0" cellspacing="0" cellpadding="2" class="infoBox">
		  <tr>
			<td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_NAME ?></b></font></td>
			<td><input name='update_customer_name' size='37' value='<?php echo tep_html_quotes($order->customer['name']); ?>'></td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COMPANY ?></b></font></td>
		    <td><input name='update_customer_company' size='37' value='<?php echo tep_html_quotes($order->customer['company']); ?>'></td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo CATEGORY_ADDRESS ?></b></font></td>
		    <td><input name='update_customer_street_address' size='37' value='<?php echo tep_html_quotes($order->customer['street_address']); ?>'></td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_SUBURB ?></b></font></td>
		    <td><input name='update_customer_surburb' size='37' value='<?php echo tep_html_quotes($order->customer['suburb']); ?>'></td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_CITY ?></b></font></td>
		    <td><input name='update_customer_city' size='15' value='<?php echo tep_html_quotes($order->customer['city']); ?>'> </td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_STATE ?></b></font></td>
		    <td><input name='update_customer_state' size='15' value='<?php echo tep_html_quotes($order->customer['state']); ?>'> </td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
		    <td><input name='update_customer_postcode' size='5' value='<?php echo $order->customer['postcode']; ?>'></td>
		  </tr>
		  <tr>
		    <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
		    <td><input name='update_customer_country' size='37' value='<?php echo tep_html_quotes($order->customer['country']); ?>'></td>
		  </tr>
		 </table>
		</td>


<?php if($SeparateBillingFields) { ?>
	    <td>
	     <!-- Billing Address Block -->
	     <table border="0" cellspacing="0" cellpadding="2">

		  <tr>
		    <td colspan='2' class="main">
		      <table border="0" cellspacing="0" cellpadding="2" class="infoBox">
			  	<tr>
			      <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_NAME ?></b></font></td>
		          <td><input name='update_billing_name' size='37' value='<?php echo tep_html_quotes($order->billing['name']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COMPANY ?></b></font></td>
		          <td><input name='update_billing_company' size='37' value='<?php echo tep_html_quotes($order->billing['company']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo CATEGORY_ADDRESS ?></b></font></td>
		          <td><input name='update_billing_street_address' size='37' value='<?php echo tep_html_quotes($order->billing['street_address']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_SUBURB ?></b></font></td>
		          <td><input name='update_billing_surburb' size='37' value='<?php echo tep_html_quotes($order->billing['suburb']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_CITY ?></b></font></td>
		          <td><input name='update_billing_city' size='15' value='<?php echo tep_html_quotes($order->billing['city']); ?>'> </td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_STATE ?></b></font></td>
		          <td><input name='update_billing_state' size='15' value='<?php echo tep_html_quotes($order->billing['state']); ?>'> </td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
		          <td><input name='update_billing_postcode' size='5' value='<?php echo $order->billing['postcode']; ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
		          <td><input name='update_billing_country' size='37' value='<?php echo tep_html_quotes($order->billing['country']); ?>'></td>
		        </tr>
		      </table>
		    </td>
		  </tr>
		</table>
	    </td>
<?php } ?>

		</tr>
		</table>
	    </td>
	    </tr>

	    <tr>
	    <td valign="top">
	    <!-- Shipping Address Block -->
		<table border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
		  </tr>
		  <tr>
		    <td colspan='1' class="main">
		      <table border="0" cellspacing="0" cellpadding="2" class="infoBox">
			  	<tr>
			      <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_NAME ?></b></font></td>
		          <td><input name='update_delivery_name' size='37' value='<?php echo tep_html_quotes($order->delivery['name']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COMPANY ?></b></font></td>
		          <td><input name='update_delivery_company' size='37' value='<?php echo tep_html_quotes($order->delivery['company']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo CATEGORY_ADDRESS ?></b></font></td>
		          <td><input name='update_delivery_street_address' size='37' value='<?php echo tep_html_quotes($order->delivery['street_address']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_SUBURB ?></b></font></td>
		          <td><input name='update_delivery_surburb' size='37' value='<?php echo tep_html_quotes($order->delivery['suburb']); ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_CITY ?></b></font></td>
		          <td><input name='update_delivery_city' size='15' value='<?php echo tep_html_quotes($order->delivery['city']); ?>'> </td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_STATE ?></b></font></td>
		          <td><input name='update_delivery_state' size='15' value='<?php echo tep_html_quotes($order->delivery['state']); ?>'> </td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
		          <td><input name='update_delivery_postcode' size='5' value='<?php echo $order->delivery['postcode']; ?>'></td>
		        </tr>
		        <tr>
		          <td><font size="1" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
		          <td><input name='update_delivery_country' size='37' value='<?php echo tep_html_quotes($order->delivery['country']); ?>'></td>
		        </tr>
		      </table>
		    </td>
		    <td class="main" align="center" valign="middle">
		      <font size="2" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" color="red"><b><?php echo HEADING_INSTRUCT1 ?></b></font><br><br>
		      <?php echo HEADING_INSTRUCT2 ?>

		    </td>
		   </tr>
		  </table>
	    </td>
	  </tr>
	</table></td>
      </tr>
<!-- End Addresses Block -->

      <tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="infoBox">
      		<tr>
      		  <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
      		  <td class="main"><input name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>'></td>
      		</tr>
      		<tr>
      		  <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
      		  <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
      		</tr>
      	</table></td>
      </tr>
<!-- End Phone/Email Block -->

      <tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
	<td><table border="0" cellspacing="0" cellpadding="2" class="infoBox">
	  <tr valine="middle">
	    <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
	    <td class="main"><?php echo tep_draw_pull_down_menu('update_info_payment_method', $orders_pay_method, $order->info['payment_method']); ?>
	    <?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>
	    <?php
	    if($order->info['payment_method'] != "Credit Card")
	    echo TEXT_VIEW_CC;
	    ?>
	    <?php
		if($order->info['payment_method'] != "Purchase Order")
		echo TEXT_VIEW_PO;
	    ?>
	    </td>
	  </tr>

	<?php if ($order->info['cc_type'] || $order->info['cc_owner'] || $order->info['payment_method'] == "Credit Card" || $order->info['cc_number']) { ?>
	  <!-- Begin Credit Card Info Block -->
	  <tr>
	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	    <td class="main"><input name='update_info_cc_type' size='10' value='<?php echo $order->info['cc_type']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input name='update_info_cc_owner' size='20' value='<?php echo $order->info['cc_owner']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input name='update_info_cc_number' size='20' value='<?php echo "(Last 4) " . substr($order->info['cc_number'],-4); ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input name='update_info_cc_expires' size='4' value='<?php echo $order->info['cc_expires']; ?>'></td>
	  </tr>
	   <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_CVV; ?></td>
	    <td class="main"><input name='update_info_cc_cvv' size='4' value='<?php echo $order->info['cc_cvv']; ?>'></td>
	  </tr>
	  <!-- End Credit Card Info Block --> 


	  <?php
	  	  // purchaseorder start
	  	      } else if( (($order->info['account_name']) || ($order->info['account_number']) || $order->info['payment_method'] == "Purchase Order"|| ($order->info['po_number'])) ) {
	  	  ?>
	  <tr>
	  	    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	  <td class="main" valign="top" align="left"><b><?php echo TEXT_INFO_PO ?></b></td>
	  <td>
	  <table border="0" cellspacing="0" cellpadding="2">
		<tr>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><?php echo TEXT_INFO_NAME ?></td>
		  <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><input type="text" name="account_name" value='<?php echo $order->info['account_name']; ?>'></td></td>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		</tr>
		<tr>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><?php echo TEXT_INFO_AC_NR ?></td>
		  <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><input type="text" name="account_number" value='<?php echo $order->info['account_number']; ?>'></td>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		</tr>
		<tr>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><?php echo TEXT_INFO_PO_NR ?></td>
		  <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		  <td class="main"><input type="text" name="po_number" value='<?php echo $order->info['po_number']; ?>'></td>
		  <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
		</tr>
	 </table>
	 </td>
     </tr>
	<?php } ?>
	</table></td>
      </tr>
<!-- End Payment Block -->

      <tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
	<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr class="dataTableHeadingRow">
	    <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
	    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
	    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
	    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></td>
	    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></td>
		<td class="dataTableHeadingContent" align="center">Delete</td>
	  </tr>

	<!-- Begin Products Listings Block -->
	<?php
      	// Override order.php Class's Field Limitations
		$index = 0;
		$order->products = array();
		$orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
		while ($orders_products = tep_db_fetch_array($orders_products_query)) {
		$order->products[$index] = array('qty' => $orders_products['products_quantity'],
                                        'name' => str_replace("'", "&#39;", $orders_products['products_name']),
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'final_price' => $orders_products['final_price'],
                                        'orders_products_id' => $orders_products['orders_products_id']);

		$subindex = 0;
		$attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'";
		$attributes_query = tep_db_query($attributes_query_string);

		if (tep_db_num_rows($attributes_query)) {
		while ($attributes = tep_db_fetch_array($attributes_query)) {
		  $order->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
		                                                           'value' => $attributes['products_options_values'],
		                                                           'prefix' => $attributes['price_prefix'],
		                                                           'price' => $attributes['options_values_price'],
		                                                           'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);
		$subindex++;
		}
		}
		$index++;
		}

	for ($i=0; $i<sizeof($order->products); $i++) {
		$orders_products_id = $order->products[$i]['orders_products_id'];

		$RowStyle = "dataTableContent";

		echo '	  <tr class="dataTableRow">' . "\n" .
		   '	    <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
		   '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='25' value='" . $order->products[$i]['name'] . "'>";

		// Has Attributes?
		if (sizeof($order->products[$i]['attributes']) > 0) {
			for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
				$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>";
				echo '</i></small></nobr>';
			}
		}

		echo '	    </td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="center" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . tep_display_tax_value($order->products[$i]['tax']) . "'>" . '%</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>' . "\n" .
		     '	    <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			  '	    <td class="' . $RowStyle . '" align="center" valign="top">'.tep_image_button('button_delete.gif', IMAGE_DELETE,'onClick="deleteproducts('.$orders_products_id.');"').'</td>' . "\n" .
		     '	  </tr>' . "\n";
	}
	?>
	<!-- End Products Listings Block -->

	<!-- Begin Order Total Block -->
	  <tr>
	    <td align="right" colspan="6">
	    	<table border="0" cellspacing="0" cellpadding="2" width="100%">
	    	<tr>
	    	<td align='center' valign='top'><br><a href="<?php print $PHP_SELF . "?oID=$oID&action=add_product&step=1"; ?>"><u><b><font size='3'><?php echo HEADING_STEP3 ?></font></b></u></a></td>
	    	<td align='right'>
	    	<table border="0" cellspacing="0" cellpadding="2">
<?php

      	// Override order.php Class's Field Limitations
		$totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
		$order->totals = array();
		while ($totals = tep_db_fetch_array($totals_query)) { $order->totals[] = array('title' => $totals['title'], 'text' => $totals['text'], 'class' => $totals['class'], 'value' => $totals['value'], 'orders_total_id' => $totals['orders_total_id']); }

	$TotalsArray = array();
	for ($i=0; $i<sizeof($order->totals); $i++) {
		$TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
		$TotalsArray[] = array("Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
	}

	array_pop($TotalsArray);
	foreach($TotalsArray as $TotalIndex => $TotalDetails)
	{
		$TotalStyle = "smallText";
		if(($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total"))
		{
			echo	'	      <tr>' . "\n" .
				'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
						"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
				'	      </tr>' . "\n";
		}
		elseif($TotalDetails["Class"] == "ot_customer_discount")
				{
					echo	'	      <tr>' . "\n" .
						'		<td class="main" align="right">' . ENTRY_CUSTOMER_DISCOUNT . '<b>' . $TotalDetails["Name"] . '</b></td>' .
						'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value=' " . $TotalDetails["Price"] . "'>" .
						        "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
								"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
								"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
						'	      </tr>' . "\n";
		}


		elseif($TotalDetails["Class"] == "ot_tax")
		{
			echo	'	      <tr>' . "\n" .
				'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
				        "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' >" .
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
				'	      </tr>' . "\n";
		}
        //  Shipping
		elseif($TotalDetails["Class"] == "ot_shipping")
		{
			echo	'	<tr>' . "\n" .
			    '       <td align="right" class="' . $TotalStyle . '"><b><?php echo HEADING_SHIPPING ?></b>' . tep_draw_pull_down_menu('update_totals[$TotalIndex][title]', $orders_ship_method, $TotalDetails["Name"]) . '</td>' . "\n";



			echo	'	<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</td>' .
				'	      </tr>' . "\n";
		}
		// End Shipping
		else
		{
			echo	'	      <tr>' . "\n" .
			    '		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input type='hidden' name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][title]' value='" . trim($TotalDetails["Name"]) . "' >" .
						"<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" .
						'</td>' . "\n" .
				'	      </tr>' . "\n";
		}
	}
?>
	    	</table>
	    	</td>
	    	</tr>
	    	</table>
	    </td>
	  </tr>
	<!-- End Order Total Block -->

	</table></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <?php if($CommentsWithStatus) { ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
            <?php } ?>
          </tr>
<?php
    $orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";

        if($CommentsWithStatus) {
        echo '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
        }

        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>

      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main">
        <?php
        if($CommentsWithStatus) {
        	echo tep_draw_textarea_field('comments', 'soft', '60', '5');
	}
	else
	{
		echo tep_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']);
	}
	?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
          </tr>
          <?php if($CommentsWithStatus) { ?>
          <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
          </tr>
          <?php } ?>
        </table></td>
      </tr>

      <tr>
	<td align='center' valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>
<?php
  }

if($action == "add_product")
{
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
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

		print "<tr><td><table border='0'>\n";

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
			
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b>". TEXT_ADD_PROD_STEP1 ."</b></td>
			<td class='dataTableContent' valign='top'>";

			//$tree = tep_get_category_tree();
			//$dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
			//echo $dropdown;
			
			$product_model_select_array = array();
			$product_model_select_array[] = array('id' => '', 'text' => '-- Select Tour Code --');
			$product_model_sel_sql = tep_db_query("SELECT pd.products_name, p.products_id, p.products_model FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where  p.products_id=pd.products_id and  pd.language_id = '" . (int)$languages_id . "' order by products_model ");
			while($product_model_sel_row = tep_db_fetch_array($product_model_sel_sql))
			{
 					$product_model_select_array[] = array('id' => $product_model_sel_row['products_model'], 'text' => $product_model_sel_row['products_model'].' ['.$product_model_sel_row['products_name'].']');
			}

				echo tep_draw_pull_down_menu('add_product_categories_id', $product_model_select_array , $add_product_categories_id);
			 
			 //echo tep_draw_input_field('add_product_categories_id',$add_product_categories_id);


			
			//$CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
			//print $CategoryOptions;
			print "</td>";
			print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_TOUR_CODE . "'>";
			print "<input type='hidden' name='step' value='2'>";
			print "</td>";
			print "</form></tr>";
			print "<tr><td colspan='3'>&nbsp;</td></tr>";
		
		
		if(isset($add_product_categories_id) && $add_product_categories_id != ''){
		
			$check_prod_model_sql = tep_db_query("SELECT products_name, p.products_id FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id=pd.products_id and products_model ='".$add_product_categories_id."' and pd.language_id = '" . (int)$languages_id . "'");
			while($check_prod_model_row = tep_db_fetch_array($check_prod_model_sql))
			{
				$valid_product_model = 'true';				
				$ProductName = $check_prod_model_row['products_name'];
				$add_product_products_id = $check_prod_model_row['products_id'];
			}
		
		}
		// Step 2: Choose Product
		if(($step > 1) && $valid_product_model =='true' )
		{
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>";
			print "<td class='dataTableContent' align='right'><b>". TEXT_ADD_STEP2 ."</b></td>
			<td class='dataTableContent' valign='top'>";
			
			
			echo tep_draw_input_field('add_product_name',$ProductName, ' size="60" readonly ');
						
			require(DIR_WS_CLASSES . 'payment.php');
  			$payment_modules = new payment;
			
			$payment_method_array = array();
			//$payment_method_array[] = array('id' => '', 'text' => TEXT_SELECT_PAYMENT_METHOD);
			$selection = $payment_modules->selection();
			
			for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
				if($selection[$i]['id'] == 'authorizenet'){
					 $selection[$i]['module'] = 'Credit Card : U.S. Credit Card (Preferred)';
				}else if($selection[$i]['id'] == 'cc_cvc'){
					 $selection[$i]['module'] = 'Credit Card : International Credit Card';
				}else if($selection[$i]['id'] == 'moneyorder'){
					$selection[$i]['module'] = "Money Order/Traveler's Check/Cashier's Check";
				}
			 $payment_method_array[] = array('id' => $selection[$i]['module'], 'text' => $selection[$i]['module']);
			
			}
			
			if($step == 2){
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
				echo tep_draw_pull_down_menu('order_product_method',  $payment_method_array, '', '');
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
						<table  border="0" cellspacing="0" cellpadding="2">
																	 <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Type:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><select name="cc_credit_card_type"><option value="Mastercard">Mastercard</option><option value="Visa">Visa</option></select></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Owner:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo tep_draw_input_field('cc_owner');?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Number:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo tep_draw_input_field('cc_number') ;?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Expiry Date:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo  tep_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year);?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">CVV number</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo tep_draw_input_field('cc_cvv','',"SIZE=4, MAXLENGTH=4"); ?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													</table>
				
				</td>
			  </tr>
			 
			</table>

			<?php
			
			}else{
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
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Type:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">
																		<?php echo $HTTP_POST_VARS['cc_credit_card_type']; ?>
																		</td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Owner:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo $HTTP_POST_VARS['cc_owner']; ?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Number:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo $HTTP_POST_VARS['cc_number']; ?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">Credit Card Expiry Date:</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo $HTTP_POST_VARS['cc_expires_month'].$HTTP_POST_VARS['cc_expires_year']; ?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													  <tr>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main">CVV number</td>
																		<td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																		<td class="main"><?php echo $HTTP_POST_VARS['cc_cvv']; ?></td>
																		<td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
																	  </tr>
																													</table>
				
				</td>
			  </tr>
			 	<?php } ?>
			</table>


			<?			
			}
			
			print "</td>";
			print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_PROD . "'>";
			print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			print "<input type='hidden' name='step' value='3'>";
			print "</td>";
			print "</form></tr>";

			print "<tr><td colspan='3'>&nbsp;</td></tr>";
		}
		
		

		// Step 3: Choose Options
		echo TEXT_ADD_PROD . $add_product_products_id;
		if(($step > 2) && ($add_product_products_id > 0))
		{
			// Get Options for Products
			$result = tep_db_query("SELECT distinct po.products_options_id, po.products_options_name,  pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price, pa.kids_values_price FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$add_product_products_id'");
			$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($add_product_products_id);
			// Skip to Step 4 if no Options
			if(tep_db_num_rows($result) == 0)
			{
				print "<tr class=\"dataTableRow\">\n";
				print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP3  . "</b></td><td class='dataTableContent' valign='top' colspan='2'><i>" . TEXT_SELECT_OPT_SKIP . "</i></td>";
				print "</tr>\n";
				if($_POST['step'] <= '3' ){
				$step = 4;
				}
			}
			else
			{
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

				print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
				print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP3 . "</b></td><td class='dataTableContent' valign='top'>";
				foreach($ProductOptionValues as $OptionID => $OptionValues)
				{
					if($OptionID != ''){
						$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
						foreach($OptionValues as $OptionValueID => $OptionValueName)
						{
						
						$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
						}
						$OptionOption .= "</select><br>\n";
					}	
					if(IsSet($add_product_options))
					$OptionOption = str_replace("value='" . $add_product_options[$OptionID] . "'","value='" . $add_product_options[$OptionID] . "' selected",$OptionOption);

					print $OptionOption;
				}
				print "</td>";
				print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'>";
				print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
				print "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
				print '<input type="hidden" name="order_product_method" value="'.tep_db_prepare_input($HTTP_POST_VARS['order_product_method']).'">';		
				if (tep_not_null($HTTP_POST_VARS['cc_owner']) || tep_not_null($HTTP_POST_VARS['cc_number'])) {
				print "<input type='hidden' name='cc_credit_card_type' value='".$HTTP_POST_VARS['cc_credit_card_type']."'>";
				print "<input type='hidden' name='cc_owner' value='".$HTTP_POST_VARS['cc_owner']."'>";	
				print "<input type='hidden' name='cc_number' value='".$HTTP_POST_VARS['cc_number']."'>";	
				print "<input type='hidden' name='cc_expires_month' value='".$HTTP_POST_VARS['cc_expires_month']."'>";	
			 	print "<input type='hidden' name='cc_expires_year' value='".$HTTP_POST_VARS['cc_expires_year']."'>";	
				print "<input type='hidden' name='cc_cvv' value='".$HTTP_POST_VARS['cc_cvv']."'>";
				}
				print "<input type='hidden' name='step' value='4'>";
				print "</td>";
				print "</form></tr>";
			}

			print "<tr><td colspan='3'>&nbsp;</td></tr>";
		}

		// Step 4: Confirm
		if($step > 3)
		{
			if($_POST['add_product_quantity']=='') $add_product_quantity = '1'; else $add_product_quantity = $_POST['add_product_quantity'];
			print "<tr><td colspan='3'><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'><table width='100%' cellspacing='1'>
			<tr class=\"dataTableRow\">";
			/*
			print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP4 . "</b></td>";
			print "<td class='dataTableContent' valign='top'>".TEXT_ADD_QUANTITY.":  <input name='add_product_quantity' size='2' value='".$add_product_quantity."'>" ;
			*/
			print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP4 . "</b></td>";
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
				if($_POST['room-0-adult-total'])
				{
					include("edit_new_product_save.php");
				}				
				include("edit_new_product.php");
			//amit added to add departure info end
			print "</td>";
			print "<input type='hidden' name='step' value='5'>";			
			echo  field_forwarder('add_product_categories_id');
			echo  field_forwarder('add_product_products_id');
			echo  field_forwarder('add_product_options');			
			
			echo  field_forwarder('order_product_method');
			echo  field_forwarder('cc_credit_card_type');
			echo  field_forwarder('cc_owner');
			echo  field_forwarder('cc_number');
			echo  field_forwarder('cc_expires_month');
			echo  field_forwarder('cc_expires_year');			
			echo  field_forwarder('cc_cvv');
			
			print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'>";
			
			print "</td>\n";
			print "</tr></table></form></td></tr>\n";
		}
		
		//amit added to next step 5 start
		
		if($step > 4)
		{
			define('TEXT_ADD_STEP5', 'STEP 5:');
			define('TEXT_ADD_STEP6', 'STEP 6:');
			
			//print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
			print "<tr class=\"dataTableRow\">\n";
			print "<td class='dataTableContent' align='right' valign='top'><b>" . TEXT_ADD_STEP5 . "</b>";
			
			//guest name air departure login here 
						
			/* echo  field_forwarder('add_product_categories_id');
			echo  field_forwarder('add_product_products_id');
			echo  field_forwarder('add_product_options');
			echo  field_forwarder('add_product_quantity'); */
			//echo  field_forwarder('availabletourdate');
			//echo  field_forwarder('departurelocation');
			//echo  field_forwarder('numberOfRooms');
			//echo  field_forwarder('room-0-adult-total');
			//echo  field_forwarder('room-0-child-total');
			
			/* availabletourdate
			departurelocation
			numberOfRooms
			room-0-adult-total
			room-0-child-total
			 */
			//bhavik add your departure select name here like above
			
			
			print "</td>";
			print "<td class='dataTableContent' align='center' colspan='2'>";
			include "create_orders_guestname.php";
			print "</td></tr>\n"; 
			//print "<td class='dataTableContent' align='center'><input type='submit' value='Select These Options'></td>";
			//print "<input type='hidden' name='step' value='6'>";
			//print "</form></tr>\n";
			
		}	
		//amit added to next sete 5 end
		
		/*
		//amit added to next step 6 start
		if($step > 5)
		{
			print "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			print "<td class='dataTableContent' align='right'><b>" . TEXT_ADD_STEP5 . "</b></td>";
			
			//guest name air departure login here 
			
			echo  field_forwarder('add_product_categories_id');
			echo  field_forwarder('add_product_products_id');
			echo  field_forwarder('add_product_options');
			//bhavik add your departure select name here like above
			print "<input type='hidden' name='step' value='6'>";
			print "</form></tr>\n";
			}
		//amit added to next sete 6 end
		*/
		print "</table></td></tr>";
}
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
