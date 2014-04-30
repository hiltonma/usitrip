<?php
/*
  $Id: order.php,v 1.1.1.1 2004/03/04 23:40:45 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type, $states, $country, $s_states, $s_country, $b_states, $b_country ;

    function order($order_id = '') {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();
      $this->billing = array();
      
      
      if (tep_not_null($order_id)) {
        $this->query($order_id);
      } else {
        $this->cart();
      }
    }

    function query($order_id) {
      global $languages_id;

      $order_id = tep_db_prepare_input($order_id);

     // $order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
//echeck       $order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, accountholder, address, address2, phone, bank, bankcity, bankphone, checknumber, accountnumber, routingnumber, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
$order_query = tep_db_query("select customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, cc_cvv, currency, currency_value, date_purchased, orders_status, last_modified, orders_paid, guest_emergency_cell_phone, allow_pay_min_money, allow_pay_min_money_deadline, disabled_coupon_point,owner_id_change_str,owner_is_change from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
	  $order = tep_db_fetch_array($order_query);

      $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],'text' => $totals['text'],'class' => $totals['class'], 'value' => $totals['value']);
      }

// begin PayPal_Shopping_Cart_IPN V2.8 DMG
      $order_total_query = tep_db_query("select text, value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
// end PayPal_Shopping_Cart_IPN
      $order_total = tep_db_fetch_array($order_total_query);

//begin PayPal_Shopping_Cart_IPN V2.8 DMG
      $shipping_method_query = tep_db_query("select title, value from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
//end PayPal_Shopping_Cart_IPN
      $shipping_method = tep_db_fetch_array($shipping_method_query);

      $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$languages_id . "'");
      $order_status = tep_db_fetch_array($order_status_query);

      $this->info = array('customers_id'=>$order['customers_id'],
	  					  'currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => scs_cc_decrypt($order['cc_number']),
                          'cc_expires' => $order['cc_expires'],
						  'cc_cvv' => $order['cc_cvv'], 
						  
						  //echeck start
							/*
						   'accountholder' => $order['accountholder'],
                          'address' => $order['address'],
                          'address2' => $order['address2'],
                          'phone' => $order['phone'],
                          'bank' => $order['bank'],
                          'bankcity' => $order['bankcity'],
                          'bankphone' => $order['bankphone'],
                          'checknumber' => $order['checknumber'],
                          'accountnumber' => $order['accountnumber'],
                          'routingnumber' => $order['routingnumber'],
							*/
						  //echeck end
                          'date_purchased' => $order['date_purchased'],
//begin PayPal_Shopping_Cart_IPN
                          'orders_status_id' => $order['orders_status'],
                          'shipping_cost' => $shipping_method['value'],
                          'total_value' => $order_total['value'],
//end PayPal_Shopping_Cart_IPN
                          'orders_status' => $order_status['orders_status_name'],
                          'last_modified' => $order['last_modified'],
                          'total' => strip_tags($order_total['text']),
						  'orders_paid' => $order['orders_paid'],
                          'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])),
						  'guest_emergency_cell_phone' => $order['guest_emergency_cell_phone'],
						  'allow_pay_min_money' => $order['allow_pay_min_money'],
						  'allow_pay_min_money_deadline' => $order['allow_pay_min_money_deadline'],
						  'disabled_coupon_point' => $order['disabled_coupon_point'],
      					  'owner_id_change_str'=>$order['owner_id_change_str'],
      					  'owner_is_change'=>$order['owner_is_change']
						  );

      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['customers_name'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;
      $orders_products_query = tep_db_query("SELECT p.max_allow_child_age, p.products_margin, p.products_type, op.orders_products_id, op.products_id, op.products_name, op.products_model, op.products_price, op.products_tax,op.is_hide, op.products_quantity, op.final_price, op.products_departure_date, op.products_departure_time, op.customer_seat_no, op.customer_bus_no, op.customer_confirmation_no, op.products_departure_location, op.products_room_price, op.products_room_info, op.total_room_adult_child_info, ta.default_max_allow_child_age, ta.operate_currency_code, op.group_buy_discount, op.is_new_group_buy, op.no_sel_date_for_group_buy, op.is_diy_tours_book, ta.is_birth_info, ta.is_gender_info, ta.is_hotel_pickup_info , op.is_hotel, op.hotel_checkout_date, p.agency_id ,op.is_transfer , op.orders_products_payment_status
	  FROM " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_PRODUCTS." p, " . TABLE_TRAVEL_AGENCY . " ta 
	  WHERE op.parent_orders_products_id='0' and op.products_id=p.products_id and p.agency_id = ta.agency_id and op.orders_id = '" . (int)$order_id . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
	    	//amit added to get default age value if not set start
		if($orders_products['max_allow_child_age'] == '' || $orders_products['max_allow_child_age'] == '0' ){
			$orders_products['max_allow_child_age'] = $orders_products['default_max_allow_child_age'];
		}
		//amit added to get default age value if not set end
        $this->products[$index] = array('qty' => $orders_products['products_quantity'],
	                                'id' => $orders_products['products_id'],
//begin PayPal_Shopping_Cart_IPN
                                  'orders_products_id' => $orders_products['orders_products_id'],
//end PayPal_Shopping_Cart_IPN
                                  'name' => $orders_products['products_name'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'group_buy_discount' => $orders_products['group_buy_discount'],
        								'is_hide' => $orders_products['is_hide'],
										'is_new_group_buy' => $orders_products['is_new_group_buy'],
										'no_sel_date_for_group_buy' => $orders_products['no_sel_date_for_group_buy'],
										'final_price' => $orders_products['final_price'],
										'products_margin' => $orders_products['products_margin'],
										'products_type' => $orders_products['products_type'],
										'max_allow_child_age' => $orders_products['max_allow_child_age'],
										'products_departure_date' => tep_date_short($orders_products['products_departure_date']),
										'products_departure_time' => $orders_products['products_departure_time'],
										'customer_seat_no' => $orders_products['customer_seat_no'],
										'customer_bus_no' => $orders_products['customer_bus_no'],
										'customer_confirmation_no' => $orders_products['customer_confirmation_no'],
										'products_departure_location' => $orders_products['products_departure_location'],
										'products_room_info' => $orders_products['products_room_info'],
										'total_room_adult_child_info' => $orders_products['total_room_adult_child_info'],
										'operate_currency_code' => $orders_products['operate_currency_code'],
										'is_diy_tours_book' => $orders_products['is_diy_tours_book'],
										'is_birth_info' => $orders_products['is_birth_info'],
										'is_gender_info' => $orders_products['is_gender_info'],
										'is_hotel_pickup_info' => $orders_products['is_hotel_pickup_info'],
										'is_hotel' => $orders_products['is_hotel'],
										'hotel_checkout_date' => $orders_products['hotel_checkout_date'],
										'agency_id' => $orders_products['agency_id'],					
										'is_transfer' => $orders_products['is_transfer'],
										'orders_products_payment_status' => $orders_products['orders_products_payment_status']	//订单产品付款状态
										);
		//transfer-service {
		//load transfer plan
		if( $orders_products['is_transfer'] == '1'){
			 $this->products[$index]['transfer_info_arr'] = tep_transfer_get_order_transfer($orders_products['orders_products_id']);
			 $this->products[$index]['transfer_info'] = tep_transfer_encode_info($this->products[$index]['transfer_info_arr']);
		}else{
			 $this->products[$index]['transfer_info_arr'] = array();
			 $this->products[$index]['transfer_info'] = '';
		}
		//}

        $subindex = 0;
//      $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        //begin PayPal_Shopping_Cart_IPN V2.8 DMG
        $attributes_query = tep_db_query("select products_options_id, products_options, products_options_values_id, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        //end PayPal_Shopping_Cart_IPN
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = array(
//begin PayPal_Shopping_Cart_IPN
                                  'option_id' => $attributes['products_options_id'],
                                  'value_id' => $attributes['products_options_values_id'],
//end PayPal_Shopping_Cart_IPN
                                  'option' => $attributes['products_options'],
                                  'value' => $attributes['products_options_values'],
                                  'prefix' => $attributes['price_prefix'],
                                  'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }
		//$this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';
		if(!is_array($this->info['tax_groups'])) $this->info['tax_groups'] = array();
        if($this->products[$index]['tax'] != 0) {
        	if(isset($this->info['tax_groups']["{$this->products[$index]['tax_description']}"])){
        		$this->info['tax_groups']["{$this->products[$index]['tax_description']}"] += ($this->products[$index]['tax']/100 )*$this->products[$index]['price'];
        	}
        	$this->info['tax_groups']["{$this->products[$index]['tax_description']}"] = ($this->products[$index]['tax']/100 )*$this->products[$index]['price'];
        }
        $index++;
      }
    }

	
    function cart() {
      // global $customer_id, $sendto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $payment, $statea, $country_title_s, $format_id_s;
       global $customer_id, $shipto, $billto, $cart, $languages_id, $currency, $currencies, $shipping, $payment, $statea, $country_title_s, $format_id_s;
 
       $this->content_type = $cart->get_content_type();
      $this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
                          'currency' => $currency,
                          'currency_value' => $currencies->currencies[$currency]['value'],
                          'payment_method' => $payment,
                          'cc_type' => (isset($GLOBALS['cc_type']) ? $GLOBALS['cc_type'] : ''),
                          'cc_owner' => (isset($GLOBALS['cc_owner']) ? $GLOBALS['cc_owner'] : ''),
                          'cc_number' => (isset($GLOBALS['cc_number']) ? $GLOBALS['cc_number'] : ''),
                          'cc_expires' => (isset($GLOBALS['cc_expires']) ? $GLOBALS['cc_expires'] : ''),
						 /*
						  'accountholder' => (isset($GLOBALS['accountholder']) ? $GLOBALS['accountholder'] : ''),
                          'address' => (isset($GLOBALS['address']) ? $GLOBALS['address'] : ''),
                          'address2' => (isset($GLOBALS['address2']) ? $GLOBALS['address2'] : ''),
                          'phone' => (isset($GLOBALS['phone']) ? $GLOBALS['phone'] : ''),
                          'bank' => (isset($GLOBALS['bank']) ? $GLOBALS['bank'] : ''),
                          'bankcity' => (isset($GLOBALS['bankcity']) ? $GLOBALS['bankcity'] : ''),
                          'bankphone' => (isset($GLOBALS['bankphone']) ? $GLOBALS['bankphone'] : ''),
                          'checknumber' => (isset($GLOBALS['checknumber']) ? $GLOBALS['checknumber'] : ''),
                          'accountnumber' => (isset($GLOBALS['accountnumber']) ? $GLOBALS['accountnumber'] : ''),
                          'routingnumber' => (isset($GLOBALS['routingnumber']) ? $GLOBALS['routingnumber'] : ''),
                          */
						   'cc_cvv' => (isset($GLOBALS['cc_cvv']) ? $GLOBALS['cc_cvv'] : ''),
						  'shipping_method' => $shipping['title'],
                          'shipping_cost' => $shipping['cost'],
						  'order_cost' => $cart->show_total_cost(),
                          'subtotal' => 0,
                          'tax' => 0,
                          'tax_groups' => array(),
						  'comments' => (isset($GLOBALS['comments']) ? db_to_html(str_replace('如您对行程有特殊要求，请务必在此留言，以便我们尽量安排。','',html_to_db($GLOBALS['comments']))) : ''),
						  'airline_name' => (isset($GLOBALS['airline_name']) ? $GLOBALS['airline_name'] : ''),
						  'flight_no' => (isset($GLOBALS['flight_no']) ? $GLOBALS['flight_no'] : ''),
						  'airline_name_departure' => (isset($GLOBALS['airline_name_departure']) ? $GLOBALS['airline_name_departure'] : ''),
						  'flight_no_departure' => (isset($GLOBALS['flight_no_departure']) ? $GLOBALS['flight_no_departure'] : ''),
						  'airport_name' => (isset($GLOBALS['airport_name']) ? $GLOBALS['airport_name'] : ''),
						  'airport_name_departure' => (isset($GLOBALS['airport_name_departure']) ? $GLOBALS['airport_name_departure'] : ''),
						  'arrival_time' => (isset($GLOBALS['arrival_time']) ? $GLOBALS['arrival_time'] : ''),
						  'departure_time' => (isset($GLOBALS['departure_time']) ? $GLOBALS['departure_time'] : ''));

      if (isset($GLOBALS[$payment]) && is_object($GLOBALS[$payment])) {
        $this->info['payment_method'] = $GLOBALS[$payment]->title;

        if ( isset($GLOBALS[$payment]->order_status) && is_numeric($GLOBALS[$payment]->order_status) && ($GLOBALS[$payment]->order_status > 0) ) {
          $this->info['order_status'] = $GLOBALS[$payment]->order_status;
        }
      }
// Get cutomeradress info
      //$customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_state, ab.entry_country_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab  where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_default_address_id = ab.address_book_id");
      if((int)$shipto){
	  	$customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_state, ab.entry_country_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab  where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_id = ab.customers_id AND ab.address_book_id =".(int)$shipto );
	  }else{
	  	$customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_state, ab.entry_country_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab  where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_default_ship_address_id = ab.address_book_id");
	  }
	   
	   
 while ( $customer_address = tep_db_fetch_array($customer_address_query) ){
 
$customer_country_query = tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_COUNTRIES . " co  where co.countries_id = '" . $customer_address['entry_country_id'] . "'");
 while ($customer_country = tep_db_fetch_array($customer_country_query) ) { 
                              $country_array = array('id' => $customer_country['countries_id'], 'title' => $customer_country['countries_name'], 'iso_code_2' => $customer_country['countries_iso_code_2'], 'iso_code_3' => $customer_country['countries_iso_code_3']); 
  $customer_zone_query = tep_db_query("select z.zone_name from " . TABLE_ZONES . " z where z.zone_id ='" . $customer_address['entry_zone_id'] . "' ");
               if (tep_not_null($customer_address['entry_state'])){
                   $states = $customer_address['entry_state']; 
                  }else{
       while ($customer_zone1 = tep_db_fetch_array($customer_zone_query) ) { 
                   $states = $customer_zone1['zone_name'];
                   }
                }
                
                
//build customer info array
  $this->customer = array('firstname' => $customer_address['customers_firstname'],
                              'lastname' => $customer_address['customers_lastname'],
                              'company' => $customer_address['entry_company'],
                              'street_address' => $customer_address['entry_street_address'],
                              'suburb' => $customer_address['entry_suburb'],
                              'city' => $customer_address['entry_city'],
                              'postcode' => $customer_address['entry_postcode'],
                              'state' => $states,
                              'zone_id' => $customer_address['entry_zone_id'],
                              'country' => $country_array, 
                              'country_id' => $customer_address['entry_country_id'],
                              'format_id' => $customer_country['address_format_id'],
                              'telephone' => $customer_address['customers_telephone'],
                              'email_address' => $customer_address['customers_email_address'],
                              );

}
}
     $shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_country_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$shipto . "'");
 while ($shipping_address = tep_db_fetch_array($shipping_address_query) ){

    $shipping_zone_query= tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from "  . TABLE_COUNTRIES . " co  where co.countries_id = '" . $shipping_address['entry_country_id'] ."'");
   while ($shipping_zone = tep_db_fetch_array($shipping_zone_query) ) {
                                   $s_country = array('id' => $shipping_zone['countries_id'], 'title' => $shipping_zone['countries_name'], 'iso_code_2' => $shipping_zone['countries_iso_code_2'], 'iso_code_3' => $shipping_zone['countries_iso_code_3']);
                    

      $shipping_zone_query1= tep_db_query("select  z.zone_name from " . TABLE_ZONES . " z where z.zone_id = '" . $shipping_address['entry_zone_id'] . "' ");
               if (tep_not_null($shipping_address['entry_state'])){
                            $s_states = $shipping_address['entry_state']; 
                                }else{
                   while ($shipping_zone1 = tep_db_fetch_array($shipping_zone_query1) ) {
                                  $s_states = $shipping_zone1['zone_name'];
                                  }
                                }  
                                
$this->delivery = array('firstname' => $shipping_address['entry_firstname'],
                              'lastname' => $shipping_address['entry_lastname'],
                              'company' => $shipping_address['entry_company'],
                              'street_address' => $shipping_address['entry_street_address'],
                              'suburb' => $shipping_address['entry_suburb'],
                              'city' => $shipping_address['entry_city'],
                              'postcode' => $shipping_address['entry_postcode'],
                              'state' => $s_states,
                              'zone_id' => $shipping_address['entry_zone_id'],
                              'country' =>  $s_country,
                              'country_id' => $shipping_address['entry_country_id'],
                              'format_id' => $shipping_zone['address_format_id']);
  
  }
 }
//}
      $billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_country_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$billto . "'");
      while ($billing_address = tep_db_fetch_array($billing_address_query) ){
 
     
        $billing_zone_query= tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_COUNTRIES . " co  where co.countries_id = '" . $billing_address['entry_country_id'] ."'");
        while ($billing_zone = tep_db_fetch_array($billing_zone_query) ){
                 $b_country = array('id' => $billing_zone['countries_id'], 'title' => $billing_zone['countries_name'], 'iso_code_2' => $billing_zone['countries_iso_code_2'], 'iso_code_3' => $billing_zone['countries_iso_code_3']);
                   
       $billing_zone_query1= tep_db_query("select z.zone_name from " . TABLE_ZONES . " z where z.zone_id ='" . $billing_address['entry_zone_id'] . "' ");
         if (tep_not_null($billing_address['entry_state'])){
                  $b_state = $billing_address['entry_state']; 
              }else{
          while ($billing_zone1 = tep_db_fetch_array($billing_zone_query1) ){
              $b_state = $billing_zone1['zone_name'];
              }
           }
      $this->billing = array('firstname' => $billing_address['entry_firstname'],
                             'lastname' => $billing_address['entry_lastname'],
                             'company' => $billing_address['entry_company'],
                             'street_address' => $billing_address['entry_street_address'],
                             'suburb' => $billing_address['entry_suburb'],
                             'city' => $billing_address['entry_city'],
                             'postcode' => $billing_address['entry_postcode'],
                              'state' => $b_state,
                              'zone_id' => $billing_address['entry_zone_id'],
                              'country' => $b_country,
                              'country_id' => $billing_address['entry_country_id'],
                              'format_id' => $billing_zone['address_format_id']);
  } 
}
      $tax_address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)($this->content_type == 'virtual' ? $billto : $shipto) . "'");
      $tax_address = tep_db_fetch_array($tax_address_query);
    
    $index = 0;
      $products = $cart->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	  	//echo $i.$products[$i]['dateattributes'][0].$products[$i]['quantity'];
		$tmp_final_price = $products[$i]['price'] + $cart->attributes_price($products[$i]['id']) + $cart->final_date_price($products[$i]['id']) + $cart->final_room_price($products[$i]['id']);
	  	
		//featured group deal discount - start
		$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products[$i]['id']."' and departure_restriction_date <= '".$products[$i]['dateattributes'][0]."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
		if(check_is_featured_deal((int)$products[$i]['id']) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
			$total_featured_guests = tep_get_featured_total_guests_booked_this_deal((int)$products[$i]['id']);
			$total_featured_guests = $total_featured_guests + $products[$i]['roomattributes'][2];
			$product_featured_price = tep_db_query("select p.products_price, p.products_margin from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products[$i]['id'] . "' ");
			$product_featured_result = tep_db_fetch_array($product_featured_price);
			
			$get_featured_deal_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$products[$i]['id']."' and peple_no <= '".$total_featured_guests."' order by peple_no desc limit 1");
			
			if(tep_db_num_rows($get_featured_deal_discount_data)>0){
				$featured_deal_discounts_data = tep_db_fetch_array($get_featured_deal_discount_data);
				$featured_deal_discount_ratio = $featured_deal_discounts_data['discount_percent']/100;																		
			}else{
				$get_featured_special_price = tep_db_fetch_array($check_featured_dept_restriction);
				//$featured_deal_discount_ratio = number_format(100 - (($get_featured_special_price['featured_deals_new_products_price'] / $product_hotel_result['products_price']) * 100))/100;													
				if((int)$get_featured_special_price['featured_deals_new_products_price']){
					$featured_deal_discount_ratio = number_format((100 * ($product_featured_result['products_price'] - $get_featured_special_price['featured_deals_new_products_price']))/($product_featured_result['products_price'] * $product_featured_result['products_margin']/100))/100;
				}else{
					$featured_deal_discount_ratio = 0;
				}
			}

			$featured_tour_gp = $product_featured_result['products_margin']/100;
			$group_buy_discount = round((($tmp_final_price * $featured_tour_gp)*$featured_deal_discount_ratio),2);
			$tmp_final_price = $tmp_final_price - $group_buy_discount;
		}else{
			//团购 start
			$group_buy_discount = 0;
			$total_no_guest_tour = $products[$i]['roomattributes'][2];
			$is_long_trour = false;
			if((int)substr($products[$i]['roomattributes'][3],0,1)){
				$is_long_trour = true;
			}
			if(GROUP_BUY_ON==true && $total_no_guest_tour >= GROUP_MIN_GUEST_NUM  && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true) ){
				$discount_percentage = auto_get_group_buy_discount($products[$i]['id']);
				$group_buy_discount = round($tmp_final_price * $discount_percentage, DECIMAL_DIGITS);
				$tmp_final_price = $tmp_final_price - $group_buy_discount;
			}
			//团购 end
		}
		//featured group deal discount - end
		
		
		$this->products[$index] = array('qty' => $products[$i]['quantity'],
                                        'name' => $products[$i]['name'],
                                        'model' => $products[$i]['model'],
                                        'tax' => tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
										'products_margin' => $products[$i]['products_margin'],
										'products_type' => $products[$i]['products_type'],
										'max_allow_child_age' => $products[$i]['max_allow_child_age'],	
										'operate_currency_code' => $products[$i]['operate_currency_code'],
                                        'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'price' => $products[$i]['price'],
										'group_buy_discount' => $group_buy_discount,
										'is_new_group_buy' => $products[$i]['is_new_group_buy'],
										'no_sel_date_for_group_buy' => $products[$i]['no_sel_date_for_group_buy'],
                                        'final_price' => $tmp_final_price,
                                        'final_price_cost' => $products[$i]['price'] + $cart->attributes_price_cost($products[$i]['id']) + $cart->final_date_price_cost($products[$i]['id']) + $cart->final_room_price_cost($products[$i]['id']),
                                      	'weight' => $products[$i]['weight'],
										'products_special_note' => $products[$i]['products_special_note'],
                                        'id' => $products[$i]['id'],
										'is_birth_info' => $products[$i]['is_birth_info'],
										'is_gender_info' => $products[$i]['is_gender_info'],
										'is_hotel_pickup_info' => $products[$i]['is_hotel_pickup_info'],
										'is_hotel' => $products[$i]['is_hotel'],
										'agency_id' => $products[$i]['agency_id'],
										'is_transfer' => $products[$i]['is_transfer'],
										'transfer_info' => $products[$i]['transfer_info']
										);
										
        if ($products[$i]['attributes']) {
          $subindex = 0;
          reset($products[$i]['attributes']);
          while (list($option, $value) = each($products[$i]['attributes'])) {
            $attributes_query = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
            $attributes = tep_db_fetch_array($attributes_query);

            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                     'value' => $attributes['products_options_values_name'],
                                                                     'option_id' => $option,
                                                                     'value_id' => $value,
                                                                     'prefix' => $attributes['price_prefix'],
                                                                    // 'price' => $attributes['options_values_price']
																	 'price' => $cart->attributes_price_display($products[$i]['id'],$option,$value),
																	 'price_cost' => $cart->attributes_price_display_cost($products[$i]['id'],$option,$value)
																	 );

            $subindex++;
          	}
		  }
		  
		  if ($products[$i]['dateattributes'][0] != '') {
			$this->products[$index]['dateattributes'][0] = $products[$i]['dateattributes'][0];
			$this->products[$index]['dateattributes'][1] = $products[$i]['dateattributes'][1];
			$this->products[$index]['dateattributes'][2] = $products[$i]['dateattributes'][2];
			$this->products[$index]['dateattributes'][3] = $products[$i]['dateattributes'][3];
			$this->products[$index]['dateattributes'][4] = $products[$i]['dateattributes'][4];
          }
		  if ($products[$i]['roomattributes'][1] != '') {
			$this->products[$index]['roomattributes'][0] = $products[$i]['roomattributes'][0];
			$this->products[$index]['roomattributes'][1] = $products[$i]['roomattributes'][1];
			$this->products[$index]['roomattributes'][2] = $products[$i]['roomattributes'][2];
			$this->products[$index]['roomattributes'][4] = $products[$i]['roomattributes'][4];
          }
		$this->products[$index]['roomattributes'][5] = $products[$i]['roomattributes'][5];
		$this->products[$index]['roomattributes'][6] = $products[$i]['roomattributes'][6];
		$this->products[$index]['roomattributes'][3] = $products[$i]['roomattributes'][3];
		$this->products[$index]['roomattributes'][7] = $products[$i]['roomattributes'][7];
		
		$this->products[$index]['is_diy_tours_book'] = $products[$i]['is_diy_tours_book'];
		$this->products[$index]['hotel_extension_info'] = $products[$i]['hotel_extension_info'];
		$this->products[$index]['extra_values'] = $products[$i]['extra_values'];
		
		$this->products[$index]['tour_cruise_type'] = $products[$i]['tour_cruise_type'];
		$this->products[$index]['is_new_group_buy'] = $products[$i]['is_new_group_buy'];
		$this->products[$index]['no_sel_date_for_group_buy'] = $products[$i]['no_sel_date_for_group_buy'];
		
		
	
        $shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax']) * $this->products[$index]['qty'];
		$this->info['subtotal'] += $shown_price;

        $products_tax = $this->products[$index]['tax'];
        $products_tax_description = $this->products[$index]['tax_description'];
        if (DISPLAY_PRICE_WITH_TAX == 'true') {
          $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          } else {
            $this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          }
        } else {
          $this->info['tax'] += ($products_tax / 100) * $shown_price;
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
          } else {
            $this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
          }
        }

        //取得每个产品的平均成人应付款和平均小孩应付款
		$roomnum_array = explode('###',$this->products[$index]['roomattributes'][3]);
		$is_room = $roomnum_array[0];	//如果$roomnum_array[0]大于0代表房间数
		$adult_num = 0;
		$child_num = 0;
		
		for($r=1; $r<count($roomnum_array); $r++){
			$adult_child = explode('!!',$roomnum_array[$r]);
			$adult_num+=(int)$adult_child[0];
			$child_num+=(int)$adult_child[1];
		}
		
		if(isset($this->products[$index]['roomattributes'][2])&&$adult_num==$this->products[$index]['roomattributes'][2] && $child_num==0){
			//全是成人
			$adult_averages = number_format(($shown_price/$adult_num),2);
			//$child_averages = 0;
			$this->products[$index]['adult_average'] = $adult_averages;
			$this->products[$index]['child_average'] = 0;
		}elseif($child_num>0){
			//含有小孩
			$additional_price = $products[$i]['price'] + $cart->attributes_price($products[$i]['id']) + $cart->final_date_price($products[$i]['id']);	//附加费
			$additional_price_average = number_format(($additional_price/($adult_num+$child_num)),2);
			
			if((int)$is_room && $is_room == '1'){	//有房间
				
				
				$adult_averages_tmp = 0;
				$child_averages_tmp = 0;
				for($ir=0; $ir<$is_room; $ir++){
					//$adult_averages_tmp += $_SESSION['adult_average'][$ir][(int)$products[$i]['id']]+$additional_price_average;
					//$child_averages_tmp += $_SESSION['child_average'][$ir][(int)$products[$i]['id']]+$additional_price_average;
					$adult_averages_tmp += $_SESSION['adult_average'][$ir][(int)$products[$i]['id']]+$additional_price_average;
					$child_averages_tmp += $_SESSION['child_average'][$ir][(int)$products[$i]['id']]+$additional_price_average;
					//echo $_SESSION['child_average'][$ir][$products[$i]['id']];
					//echo $_SESSION['child_average'][$ir][(int)$products[$i]['id']];
					//print_r($_SESSION['adult_average']);
					//print_r($_SESSION['child_average']);
					//exit;
				}
				
				//小孩平均价
				$child_averages = number_format(($child_averages_tmp/$is_room),2);
				//大人平均价
				//$adult_averages = number_format(($adult_averages_tmp/$is_room),2);
				$adult_averages = number_format((($shown_price-($child_averages*$child_num))/$adult_num) , 2);
				//echo $adult_averages."\n<br>";
				
				//应andy要求，所有大人小孩在结伴同游中都使用同一价，如果小孩价格为0就用统一平均价
				$equally_averages_price = true;
				if($equally_averages_price==true && ($child_averages < 1 || $adult_averages < 1)){
					$child_averages = $adult_averages = number_format(($shown_price / ($adult_num+$child_num)) , 2);
				}
			}else{	//无房间
				$adult_averages = $_SESSION['adult_average'][0][$products[$i]['id']]+$additional_price_average;
				$child_averages = $_SESSION['child_average'][0][$products[$i]['id']]+$additional_price_average;
			}
			
			$this->products[$index]['adult_average'] = $adult_averages;
			$this->products[$index]['child_average'] = $child_averages;
			//echo 'adult_average='.$index.' '.$this->products[$index]['adult_average'].'<br>'."\n";
			//echo 'child_average='.$index.' '.$this->products[$index]['child_average'].'<br>'."\n";
		}
		
		$index++;
      }

      if (DISPLAY_PRICE_WITH_TAX == 'true') {
        $this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
		//$this->info['adult_total'] = $this->info['total']/2;
		//echo 'adult_total:'.$this->info['adult_total'];
      } else {
        $this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
		//$this->info['adult_total'] = $this->info['total']/2;
		//echo 'adult_total:'.$this->info['adult_total'];
      }
    }

  }
?>