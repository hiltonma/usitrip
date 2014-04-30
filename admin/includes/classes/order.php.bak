<?php
/*
  $Id: order.php,v 1.1.1.1 2004/03/04 23:39:45 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

class order {
	var $info, $totals, $products, $customer, $delivery;

	function order($order_id,$orderby = '') {
		$this->info = array();
		$this->totals = array();
		$this->products = array();
		$this->customer = array();
		$this->delivery = array();
		$this->query($order_id,$orderby);
	}

	/**
	 * 获取订单数据
	 * @param int $order_id 订单号
	 * @param string $orderby 排序
	 * @author lwkai modify 2013-06-03 增加可指定排序方式
	 */
	function query($order_id,$orderby = '') {
		//$order_query = tep_db_query("select customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
//begin PayPal_Shopping_Cart_IPN
		$order_query = tep_db_query("select customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, payment_methods, cc_type, cc_owner, cc_number, cc_expires, currency, currency_value, date_purchased, orders_status, last_modified, customers_id,cc_cvv, payment_id, order_cost, provider_cancellation_fee, provider_cancellation_fee_1, provider_cancellation_penalty, cancellation_fee, cancellation_history, sent_acb_mail, refund_total, orders_paid, next_admin_id, need_next_admin, need_next_urgency, order_up_no_change_status, orders_owners, is_blinking, guest_emergency_cell_phone, allow_pay_min_money, allow_pay_min_money_deadline, need_pick_up_sms, disabled_coupon_point, is_again_paid,owner_id_change_str,owner_is_change from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
		//echek	  $order_query = tep_db_query("select customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, cc_type, cc_owner, cc_number, cc_expires, cc_cvv, accountholder, address, address2, phone, bank, bankcity, bankphone, checknumber, accountnumber, routingnumber, currency, currency_value, date_purchased, orders_status, last_modified, customers_id, payment_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
		
		//end PayPal_Shopping_Cart_IPN
		$order = tep_db_fetch_array($order_query);

		$totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
		$ot_total = 0;
		while ($totals = tep_db_fetch_array($totals_query)) {
			$this->totals[] = array(
				'title' => $totals['title'],
				'text' => $totals['text'],
				'value'=> $totals['value'],
				'class'=> $totals['class'],
				'orders_total_id'=> $totals['orders_total_id']
			);
			if ($totals['class'] == 'ot_total') {
				$ot_total = $totals['value'];
			}
		}
		$this->info = array(
			'total' => $ot_total,
			'currency' => $order['currency'],
			'currency_value' => $order['currency_value'],
			'payment_method' => $order['payment_method'],
			'payment_methods' => $order['payment_methods'],
			'cc_type' => $order['cc_type'],
			'cc_owner' => $order['cc_owner'],
			'cc_number' => scs_cc_decrypt($order['cc_number']),
			'cc_expires' => $order['cc_expires'],
			'cc_cvv'=> $order['cc_cvv'],
			'order_cost'=> $order['order_cost'],		
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
			'payment_id' => $order['payment_id'],
			'provider_cancellation_fee' => $order['provider_cancellation_fee'],
			'provider_cancellation_fee_1' => $order['provider_cancellation_fee_1'],
			'provider_cancellation_penalty' => $order['provider_cancellation_penalty'],
			'cancellation_fee' => $order['cancellation_fee'],
			'cancellation_history' => $order['cancellation_history'],
			//end PayPal_Shopping_Cart_IPN
			'orders_status' => $order['orders_status'],
			'last_modified' => $order['last_modified'],
			'sent_acb_mail' => $order['sent_acb_mail'],
			'refund_total'=> $order['refund_total'],
			'orders_paid'=> $order['orders_paid'],
			'next_admin_id'=> $order['next_admin_id'],
			'need_next_admin'=> $order['need_next_admin'],
			'need_next_urgency' => $order['need_next_urgency'],
			'order_up_no_change_status' => $order['order_up_no_change_status'],
			'orders_owners' => $order['orders_owners'],
			'is_blinking' => $order['is_blinking'],
			'guest_emergency_cell_phone' => $order['guest_emergency_cell_phone'],
			'allow_pay_min_money' => $order['allow_pay_min_money'],
			'allow_pay_min_money_deadline' => $order['allow_pay_min_money_deadline'],
			'need_pick_up_sms' => $order['need_pick_up_sms'],
			'disabled_coupon_point' => $order['disabled_coupon_point'],
			'is_again_paid' => $order['is_again_paid'],
			'owner_id_change_str'=>$order['owner_id_change_str'],
			'owner_is_change'=>$order['owner_is_change']
		);
		
		$this->customer = array(
			'name' => $order['customers_name'],
			//begin PayPal_Shopping_Cart_IPN
			'id' => $order['customers_id'],
			//end PayPal_Shopping_Cart_IPN
			'company' => $order['customers_company'],
			'street_address' => $order['customers_street_address'],
			'suburb' => $order['customers_suburb'],
			'city' => $order['customers_city'],
			'postcode' => $order['customers_postcode'],
			'state' => $order['customers_state'],
			'country' => $order['customers_country'],
			'format_id' => $order['customers_address_format_id'],
			'telephone' => $order['customers_telephone'],
			'email_address' => $order['customers_email_address']
		);
		
		$this->delivery = array(
			'name' => $order['delivery_name'],
			'company' => $order['delivery_company'],
			'street_address' => $order['delivery_street_address'],
			'suburb' => $order['delivery_suburb'],
			'city' => $order['delivery_city'],
			'postcode' => $order['delivery_postcode'],
			'state' => $order['delivery_state'],
			'country' => $order['delivery_country'],
			'format_id' => $order['delivery_address_format_id']
		);
		
		$this->billing = array(
			'name' => $order['billing_name'],
			'company' => $order['billing_company'],
			'street_address' => $order['billing_street_address'],
			'suburb' => $order['billing_suburb'],
			'city' => $order['billing_city'],
			'postcode' => $order['billing_postcode'],
			'state' => $order['billing_state'],
			'country' => $order['billing_country'],
			'format_id' => $order['billing_address_format_id']
		);
		$index = 0;
		//	$orders_products_query = tep_db_query("select p.max_allow_child_age, p.products_margin, op.orders_products_id, op.products_id, op.products_name, op.products_model, op.products_price, op.products_tax, op.products_quantity, op.final_price, op.products_departure_date, op.products_departure_time, op.customer_seat_no, op.customer_bus_no, op.customer_confirmation_no, op.customer_invoice_no, op.customer_invoice_total, op.customer_invoice_comment, op.products_departure_location, op.products_room_price, op.products_room_info, ta.default_max_allow_child_age, ta.operate_currency_code from " . TABLE_ORDERS_PRODUCTS . " op, ".TABLE_PRODUCTS." p, " . TABLE_TRAVEL_AGENCY . " ta where op.products_id=p.products_id and p.agency_id = ta.agency_id and op.orders_id = '" . (int)$order_id . "'");
		
		$sql = "select op.*, gc.gc_id, gc.gc_code, gc.gc_status, oph.provider_order_status_id, oph.provider_comment, oph_adm.provider_order_status_id as admin_last_order_status_id, oph_adm.provider_comment as admin_last_comment, ps.provider_order_status_name, ps_adm.provider_order_status_name as admin_last_status_name,is_transfer, op.customer_invoice_files from ".TABLE_ORDERS_PRODUCTS." op 
			LEFT JOIN " . TABLE_GIFT_CERTIFICATES . " gc ON op.orders_id=gc.orders_id AND op.products_id=gc.products_id
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." oph ON op.orders_products_id=oph.orders_products_id AND oph.popc_user_type=1 AND oph.notify_usi4trip=1
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." oph2 ON oph.orders_products_id=oph2.orders_products_id AND oph.popc_id < oph2.popc_id AND oph2.popc_user_type=1 AND oph2.notify_usi4trip=1
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." oph_adm ON op.orders_products_id=oph_adm.orders_products_id AND oph_adm.popc_user_type=0 AND oph_adm.notify_usi4trip=1
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." oph2_adm ON oph_adm.orders_products_id=oph2_adm.orders_products_id AND oph_adm.popc_id < oph2_adm.popc_id AND oph2_adm.popc_user_type=0 AND oph2_adm.notify_usi4trip=1
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." ps ON oph.provider_order_status_id=ps.provider_order_status_id 
			LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." ps_adm ON oph_adm.provider_order_status_id=ps_adm.provider_order_status_id 
			WHERE oph2.provider_status_update_date IS NULL AND oph2_adm.provider_status_update_date IS NULL AND op.orders_id = '". (int)$order_id ."' GROUP BY op.orders_products_id";
		$order_by = " op.orders_products_id asc";//products_departure_date
		if (tep_not_null($orderby)) {
			$order_by = $orderby . ',' . $order_by;
		}
		$sql .= ' order by ' . $order_by;
		$orders_products_query = tep_db_query($sql);//WHERE oph2.provider_status_update_date IS NULL AND oph2_adm.provider_status_update_date IS NULL AND op.orders_id = '". (int)$order_id ."' GROUP BY op.products_id");
		while ($orders_products = tep_db_fetch_array($orders_products_query)) {
			//从产品表取得的数据 start
			$p_sql = tep_db_query('select agency_id from '.TABLE_PRODUCTS.' WHERE products_id="'.(int)$orders_products['products_id'].'" ');
			$p_row = tep_db_fetch_array($p_sql);
			//从产品表取得的数据 end
			$orders_products_update_history_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY . " where orders_products_id = '" . (int) $orders_products['orders_products_id'] . "' order by ord_prod_history_id desc limit 1");
			$orders_products_update_history = tep_db_fetch_array($orders_products_update_history_query);
			
			$eticket_sql = tep_db_query("SELECT * FROM `orders_product_eticket` where orders_products_id = '" . (int) $orders_products['orders_products_id'] . "' " );
			$eticket_array = tep_db_fetch_array($eticket_sql);
			
			$this->products[$index] = array(
				'eticket'=>$eticket_array,	//电子参团凭证信息数组
				'qty' => $orders_products['products_quantity'],
				'name' => str_replace("'", "&#39;", $orders_products['products_name']),
				'model' => $orders_products['products_model'],
				'tax' => $orders_products['products_tax'],
				'price' => $orders_products['products_price'],
				'group_buy_discount' => $orders_products['group_buy_discount'],
				'is_new_group_buy' => $orders_products['is_new_group_buy'],
				'no_sel_date_for_group_buy' => $orders_products['no_sel_date_for_group_buy'],
				'final_price' => $orders_products['final_price'],
				'is_hide'=>$orders_products['is_hide'],
				'id' => $orders_products['products_id'],
				'final_price_cost' => $orders_products['final_price_cost'],
				'orders_products_id' => $orders_products['orders_products_id'],
				'products_departure_date' => $orders_products['products_departure_date'],
				'products_departure_time' => $orders_products['products_departure_time'],
				'products_departure_location' => $orders_products['products_departure_location'],
				'products_room_info' => $orders_products['products_room_info'],
				'invoice_number' => $orders_products_update_history['invoice_number'],
				'invoice_amount' => $orders_products_update_history['invoice_amount'],
				'operate_currency_exchange_code' => $orders_products['operate_currency_exchange_code'],
				'operate_currency_exchange_value' => $orders_products['operate_currency_exchange_value'],
				'total_room_adult_child_info' => $orders_products['total_room_adult_child_info'],
				'customer_seat_no' => $orders_products['customer_seat_no'],
				'customer_bus_no' => $orders_products['customer_bus_no'],
				'customer_confirmation_no' => $orders_products['customer_confirmation_no'],
				'customer_invoice_no' => $orders_products['customer_invoice_no'],
				'customer_invoice_total' => $orders_products['customer_invoice_total'],
				'customer_invoice_comment' => $orders_products['customer_invoice_comment'],
				'provider_order_status_id' => $orders_products['provider_order_status_id'],
				'provider_comment' => $orders_products['provider_comment'],
				'admin_last_order_status_id' => $orders_products['admin_last_order_status_id'],
				'admin_last_comment' => $orders_products['admin_last_comment'],
				'provider_order_status_name' => $orders_products['provider_order_status_name'],
				'admin_last_status_name' => $orders_products['admin_last_status_name'],
				'gc_id' => $orders_products['gc_id'],
				'gc_code' => $orders_products['gc_code'],
				'gc_status' => $orders_products['gc_status'],
				'is_adjustments_needed' => $orders_products['is_adjustments_needed'],
				'tour_cruise_type' => $orders_products['tour_cruise_type'],
				'is_diy_tours_book' => $orders_products['is_diy_tours_book'],
				'hotel_pickup_info' => $orders_products['hotel_pickup_info'],
				'agency_id' => $p_row['agency_id'],
				'hotel_checkout_date' => $orders_products['hotel_checkout_date'],//hotel-extension,
				'hotel_extension_info' => $orders_products['hotel_extension_info'],
				'is_hotel' => $orders_products['is_hotel'],
				'is_early' => $orders_products['is_early'],//hotel-extension end,
				'is_new_group_buy' => $orders_products['is_new_group_buy'],
				'new_group_buy_type' => $orders_products['new_group_buy_type'],
				'no_sel_date_for_group_buy' => $orders_products['no_sel_date_for_group_buy'],
				'extra_values' => $orders_products['extra_values'],
				'is_transfer' => $orders_products['is_transfer'],
				'orders_products_payment_status' => $orders_products['orders_products_payment_status'],	//订单产品付款状态
				'orders_products_payment_status_remarks' => $orders_products['orders_products_payment_status_remarks'],	//付款状态备注
				'customer_invoice_files' => $orders_products['customer_invoice_files'],	//供应商上传的发票文件
				'parent_orders_products_id' => $orders_products['parent_orders_products_id'],	//父订单行程ID，用于组合团子团的订单产品，客人不能看到此种订单行程。
				'is_step3' => $orders_products['is_step3'],	//是否是生成订单后又被销售添加的产品
				'step3_admin_id' => $orders_products['step3_admin_id'],	//step3添加者管理员id号，如果为0则不是step3添加的产品
				'add_date' => $orders_products['add_date']
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
		$attributes_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
		if (tep_db_num_rows($attributes_query)) {
		  while ($attributes = tep_db_fetch_array($attributes_query)) {
			$this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
					'option_id' => $attributes['products_options_id'],
					'value_id' => $attributes['products_options_values_id'],
					'prefix' => $attributes['price_prefix'],
					'price' => $attributes['options_values_price'],
					'price_cost' => $attributes['options_values_price_cost'],
					'value' => $attributes['products_options_values'],
					'orders_products_attributes_id' => $attributes['orders_products_attributes_id']
					 );

			$subindex++;
		  }
		}
		$index++;
	  }
	}
  }
?>
