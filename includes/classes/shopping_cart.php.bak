<?php
/*
$Id: shopping_cart.php,v 1.1.1.1 2004/03/04 23:40:47 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
/**
 * 注意：由于简繁体转码原因造成中文字符有可能出现乱码的情况，以下内容中凡是有可能保存中文的变量都可能不可被程序前台使用
 * ['roomattributes'][1]因坑爹已被边缘化
 * Howard 
 */

class shoppingCart {
	var $contents, $total, $weight, $cartID, $content_type;
	function shoppingCart() {
		$this->reset();
	}
	/**
  	 * 恢复购物车内容，
  	 * 1程序将当前购物车中产品写入数据库，仅写入未保存的数据
  	 * 2读取数据库中购物车的产品写入Session的购物车对象
  	 * comment by vincent 2011.8.31
  	 */
	function restore_contents() {
		//ICW replace line
		global $customer_id, $gv_id, $REMOTE_ADDR;
		// global $customer_id;
		if (!tep_session_is_registered('customer_id')) return false;
		// insert current cart contents in database
		if (is_array($this->contents)) {
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) {
				$qty = $this->contents[$products_id]['qty'];
				$finaldate = $this->contents[$products_id]['dateattributes'][0];
				$depart_time = $this->contents[$products_id]['dateattributes'][1];
				$depart_location = $this->contents[$products_id]['dateattributes'][2];
				$prifix = $this->contents[$products_id]['dateattributes'][3];
				//$this->contents[$products_id]['dateattributes'][4] = $this->contents[$products_id]['dateattributes'][4]

				if($this->contents[$products_id]['roomattributes'][2]!=0){
					$date_price = @($this->contents[$products_id]['dateattributes'][4]/$this->contents[$products_id]['roomattributes'][2]);
					$date_price_cost = ($this->contents[$products_id]['dateattributes_cost'][4]/$this->contents[$products_id]['roomattributes'][2]);
				}else{
					$date_price = 0;
					$date_price_cost = 0;
				}

				// $date_price = $this->contents[$products_id]['roomattributes'][2]*$this->contents[$products_id]['dateattributes'][4];
				$total_room_price = $this->contents[$products_id]['roomattributes'][0];

				$date_price_cost = @($this->contents[$products_id]['dateattributes_cost'][4]/$this->contents[$products_id]['roomattributes'][2]);
				// $date_price_cost = $this->contents[$products_id]['roomattributes'][2]*$this->contents[$products_id]['dateattributes_cost'][4];
				$total_room_price_cost = $this->contents[$products_id]['roomattributes_cost'][0];

				//$total_info_room = $this->contents[$products_id]['roomattributes'][1];
				$total_info_room = $this->re_get_room_info_to_gb2312($this->contents[$products_id]['roomattributes'][3]); //这个值是简体的哈
				$total_no_guest_tour = $this->contents[$products_id]['roomattributes'][2];
				$total_room_adult_child_info = $this->contents[$products_id]['roomattributes'][3];
				$product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");

				if (!tep_db_num_rows($product_query)) {
					/*
					if(tep_not_null($this->contents[$products_id]['roomattributes'][4])){
						$total_info_room_input = iconv($this->contents[$products_id]['roomattributes'][4],CHARSET.'//IGNORE',$total_info_room);
					}else{
						
					}
					*/
					$total_info_room_input = $total_info_room;

					$travel_comp = $this->contents[$products_id]['roomattributes'][5];
					$hotel_extension_info = $this->contents[$products_id]['hotel_extension_info'];
					$transfer_info = $this->contents[$products_id]['transfer_info']; //transfer service
					$agree_single_occupancy_pair_up = $this->contents[$products_id]['roomattributes'][6];
					$bed_option_info = $this->contents[$products_id]['roomattributes'][7];
					$is_new_group_buy = $this->contents[$products_id]['is_new_group_buy']; //新团购
					$no_sel_date_for_group_buy = $this->contents[$products_id]['no_sel_date_for_group_buy']; //没有选择日期1为没选，0为已经选择
					$extra_values = $this->contents[$products_id]['extra_values'];
					
					$finaldate = date('Y-m-d', strtotime($finaldate));
					tep_db_query(("insert into " . TABLE_CUSTOMERS_BASKET . " set customers_id='" . (int)$customer_id . "', products_id='" . tep_db_input($products_id) . "', customers_basket_quantity='" . $qty . "', customers_basket_date_added='" . date('Ymd') . "', products_departure_date='" . $finaldate . "', products_departure_time='" . $depart_time . "', products_departure_location='" .tep_db_input($depart_location). "', products_prefix='" . $prifix . "', products_extra_charge='" . $date_price . "', products_room_price='" . $total_room_price . "', products_room_info='" . $total_info_room_input . "', total_no_guest_tour='".$total_no_guest_tour."', total_room_adult_child_info='".$total_room_adult_child_info."', products_extra_charge_cost='" . $date_price_cost . "', products_room_price_cost='" . $total_room_price_cost . "', travel_comp='".(int)$travel_comp."', agree_single_occupancy_pair_up='".(int)$agree_single_occupancy_pair_up."', bed_option_info='".$bed_option_info."', is_new_group_buy='".$is_new_group_buy."', no_sel_date_for_group_buy='".$no_sel_date_for_group_buy."',hotel_extension_info='".$hotel_extension_info."',transfer_info='".tep_db_input($transfer_info)."',extra_values='".$extra_values."'"));

					//tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
					if (isset($this->contents[$products_id]['attributes'])) {
						reset($this->contents[$products_id]['attributes']);
						while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
							tep_db_query(html_to_db ("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')"));
						}
					}
				} else {
					tep_db_query(html_to_db ("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'"));
				}
			}
			//ICW ADDDED FOR CREDIT CLASS GV - START
			if (tep_session_is_registered('gv_id')) {
				$gv_query = tep_db_query(html_to_db ("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_id . "', '" . (int)$customer_id . "', now(),'" . $REMOTE_ADDR . "')"));
				$gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_id . "'");
				tep_gv_account_update($customer_id, $gv_id);
				tep_session_unregister('gv_id');
			}
			//ICW ADDDED FOR CREDIT CLASS GV - END
		}//end save content to database

		// reset per-session cart contents, but not the database contents
		$this->reset(false);
		//write all CUSTOMERS_BASKET product to session $cart object
		$products_query = tep_db_query("select products_id, customers_basket_quantity, products_departure_date, products_departure_time, products_departure_location,products_prefix, products_extra_charge, products_room_price, products_room_info, total_no_guest_tour, total_room_adult_child_info, products_extra_charge_cost, products_room_price_cost, travel_comp, agree_single_occupancy_pair_up, bed_option_info, is_new_group_buy, no_sel_date_for_group_buy,hotel_extension_info,transfer_info, extra_values  from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
		while ($products = tep_db_fetch_array($products_query)) {
			$this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
			$this->contents[$products['products_id']]['dateattributes'][0] = $products['products_departure_date'];
			$this->contents[$products['products_id']]['dateattributes'][1] = $products['products_departure_time'];
			$this->contents[$products['products_id']]['dateattributes'][2] = $products['products_departure_location'];
			if($products['products_extra_charge'] != '0.00')
			{
				$this->contents[$products['products_id']]['dateattributes'][3] = $products['products_prefix'];
				//$this->contents[$products['products_id']]['dateattributes'][4] = $products['products_extra_charge'];
				$this->contents[$products['products_id']]['dateattributes'][4] = number_format(($products['products_extra_charge']*$products['total_no_guest_tour']),2, '.', '');
				//$this->contents[$products['products_id']]['dateattributes_cost'][4] = $products['products_extra_charge_cost'];
				$this->contents[$products['products_id']]['dateattributes_cost'][4] = number_format(($products['products_extra_charge_cost']*$products['total_no_guest_tour']),2, '.', '');
			}
			$this->contents[$products['products_id']]['roomattributes'][0] = $products['products_room_price'];
			$this->contents[$products['products_id']]['roomattributes_cost'][0] = $products['products_room_price_cost'];
			$this->contents[$products['products_id']]['roomattributes'][1] = $products['products_room_info'];
			$this->contents[$products['products_id']]['roomattributes'][2] = $products['total_no_guest_tour'];
			$this->contents[$products['products_id']]['roomattributes'][3] = $products['total_room_adult_child_info'];

			if (!tep_session_is_registered('customer_id')){
				$this->contents[$products['products_id']]['roomattributes'][4] = CHARSET;
			}

			$this->contents[$products['products_id']]['roomattributes'][5] = (int)$products['travel_comp']; //结伴同游
			$this->contents[$products['products_id']]['roomattributes'][6] = (int)$products['agree_single_occupancy_pair_up']; //单人部分配房
			$this->contents[$products['products_id']]['roomattributes'][7] = $products['bed_option_info'];	//床型选择项，与['roomattributes'][3]的值对应，只是没有!!

			$this->contents[$products['products_id']]['is_new_group_buy'] = (int)$products['is_new_group_buy']; //新团购
			$this->contents[$products['products_id']]['no_sel_date_for_group_buy'] = (int)$products['no_sel_date_for_group_buy']; //新团购
			$this->contents[$products['products_id']]['hotel_extension_info'] = $products['hotel_extension_info'];//酒店延住hotel-extension
			$this->contents[$products['products_id']]['extra_values'] = $products['extra_values'];//酒店延住hotel-extension


			$this->contents[$products['products_id']]['transfer_info'] = $products['transfer_info'];//接送服务transfer-service
			// attributes
			$attributes_query = tep_db_query("select products_options_id, products_options_value_id from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
			while ($attributes = tep_db_fetch_array($attributes_query)) {
				$this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
			}
		}
		$this->cleanup();

	}
	/**
    * 重置购物车，清空    
    * @param boolean $reset_database 是否同时删除数据库中的购物车数据
    */
	function reset($reset_database = false) {
		global $customer_id;
		$this->contents = array();
		$this->total = 0;
		$this->weight = 0;
		$this->content_type = false;
		if (tep_session_is_registered('customer_id') && ($reset_database == true)) {
			tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
			tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
		}
		unset($this->cartID);
		if (tep_session_is_registered('cartID')) tep_session_unregister('cartID');
	}

	/**
     * 添加产品到购物车
     * @param int $products_id 产品编号
     * @param unknown_type $qty 数量
     * @param unknown_type $attributes 产品属性
     * @param unknown_type $notify  是否显示提醒信息 提醒用户购物车新加入了某产品？
     * @param unknown_type $availabletourdate 
     * @param unknown_type $departurelocation 出发时间和地点 用::::间隔
     * @param unknown_type $finaldate 当$departurelocation为空才使用
     * @param unknown_type $depart_time 当$departurelocation为空才使用
     * @param unknown_type $depart_location 当$departurelocation为空才使用
     * @param unknown_type $prifix 当$departurelocation为空才使用
     * @param unknown_type $date_price 当$departurelocation为空才使用
     * @param unknown_type $total_room_price
     * @param unknown_type $total_info_room
     * @param unknown_type $total_no_guest_tour
     * @param unknown_type $total_room_adult_child_info
     * @param unknown_type $date_price_cost 当$departurelocation为空才使用
     * @param unknown_type $total_room_price_cost
     * @param unknown_type $travel_comp
     * @param unknown_type $agree_single_occupancy_pair_up
     * @param unknown_type $bed_option_info 
     * @param unknown_type $is_new_group_buy
     * @param unknown_type $no_sel_date_for_group_buy
     * @param unknown_type $hotel_extension_info 酒店延住信息
     * @param unknown_type $transfer_info 接送服务信息（单独的接送服务产品）
     */
	function add_cart($products_id, $qty = '1', $attributes = '', $notify = true,$availabletourdate='',$departurelocation='', $finaldate='', $depart_time='', $depart_location='', $prifix='', $date_price='', $total_room_price='', $total_info_room='',$total_no_guest_tour='',$total_room_adult_child_info='', $date_price_cost='', $total_room_price_cost='',$travel_comp='0',$agree_single_occupancy_pair_up='0', $bed_option_info = '', $is_new_group_buy = 0, $no_sel_date_for_group_buy = 0,$hotel_extension_info = '',$transfer_info= '', $extra_values = '') {
		global $new_products_id_in_cart, $customer_id;
		//Howard added fixed $qty
		$qty = 1;
		
		$total_info_room = $this->re_get_room_info_to_gb2312($total_room_adult_child_info); //此值永远是简体的

		//vincent's debug code ,dont remove this
		/*
		$ref = new ReflectionMethod('shoppingCart' ,'add_cart');
		$para = $ref->getParameters ();
		$args= func_get_args();
		$out = "\n";
		for($i=0 ,$m=count($para);$i<$m;$i++){
		$out .= $para[$i]->getName()."  = ".$args[$i]."\n";
		}
		tep_log($out);*/


		$is_start_date_required=is_tour_start_date_required((int)$products_id);
		if(!preg_match('/###1!!0/',$total_room_adult_child_info))$agree_single_occupancy_pair_up='0';
		$this->contents[$products_id]['roomattributes'][5] = (int)$travel_comp;//结伴同游选项
		$this->contents[$products_id]['roomattributes'][6] = (int)$agree_single_occupancy_pair_up;//单人部分配房
		$this->contents[$products_id]['roomattributes'][7] = $bed_option_info;	//床型选择项，与['roomattributes'][3]的值对应，只是没有!!

		if($availabletourdate != "")
		{
			/** code for available date and departure time and location***/
			$availdate = explode('::',$availabletourdate);
			$finaldate = $availdate[0];

			$price_with_prefix = explode('##',$availdate[1]);
			if($price_with_prefix[1] != '')
			{
				if($price_with_prefix[0] == '')	{		 $prifix = '+';	}
				else	{	 $prifix = str_replace('(','',$price_with_prefix[0]);	}
				$date_price = str_replace(')','',$price_with_prefix[1]);
				$date_price = str_replace('$','',$date_price);
				$date_price = str_replace(' ','',$date_price);
				$date_price_cost = $date_price_cost;
			}else{
				$prifix = '';
				$date_price = '';
				$date_price_cost = '';
			}

			//echo '<br>'.$prifix;
			//echo '<br>'.$date_price;
			//echo $departurelocation;
			$departtimelocation = explode('::::',$departurelocation);
			$depart_time = $departtimelocation[0];
			$depart_location = $departtimelocation[1];

			//08:01am 删除使用时间和地址合并后的地址前面的时间
			if(FIRST_TIME_AFTER_LOCATION=='true'){
				$depart_location = preg_replace('/^(\d{1,2}:\d{2}(am|pm) )/U','',$depart_location);
			}
			/** code for available date and departure time and location***/

		}//end of if($availabletourdate != "")

		$products_id = tep_get_uprid($products_id, $attributes); //make UNIQID

		if ($notify == true) {
			$new_products_id_in_cart = $products_id;
			tep_session_register('new_products_id_in_cart');
		}

		if(tep_check_product_is_hotel($products_id)>=2){
			$cart_checkout_date = explode('|=|', $hotel_extension_info);
			$checkin_out_times = explode("-", $cart_checkout_date[3]);
			$attributes[strtotime($finaldate)] = $checkin_out_times[0];
			$products_id = $products_id.'{'.strtotime($finaldate).'}'.strtotime($checkin_out_times[0]);
		}else 	if(tep_check_product_is_transfer($products_id) == 1){
			//$transfer_info_array = tep_transfer_decode_info($transfer_info);
			$products_id = $products_id.'{rand}'.rand(1000000 , 9999999);
		}

		if ($this->in_cart($products_id)) {
			$this->update_quantity($products_id, $qty, $attributes, $finaldate, $depart_time, $depart_location, $prifix, $date_price,$total_room_price,$total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_room_price_cost, $travel_comp, $agree_single_occupancy_pair_up, $bed_option_info, $hotel_extension_info, $diy_tours_sort, $is_new_group_buy, $no_sel_date_for_group_buy,$hotel_extension_info,$transfer_info, $extra_values);
		} else {
			$this->contents[] = array($products_id);
			$this->contents[$products_id] = array('qty' => $qty);
			$this->contents[$products_id]['dateattributes'][0] = $finaldate;
			$this->contents[$products_id]['dateattributes'][1] = $depart_time;
			$this->contents[$products_id]['dateattributes'][2] = $depart_location;
			$this->contents[$products_id]['dateattributes'][3] = $prifix;
			//	$this->contents[$products_id]['dateattributes'][4] = $date_price;
			$this->contents[$products_id]['dateattributes'][4] = number_format(($date_price*$total_no_guest_tour),2, '.', '');

			$this->contents[$products_id]['dateattributes_cost'][4] = number_format(($date_price_cost*$total_no_guest_tour),2, '.', '');


			$this->contents[$products_id]['roomattributes'][0] = $total_room_price;

			$this->contents[$products_id]['roomattributes_cost'][0] = $total_room_price_cost;

			$this->contents[$products_id]['roomattributes'][1] = $total_info_room;
			$this->contents[$products_id]['roomattributes'][2] = $total_no_guest_tour;
			$this->contents[$products_id]['roomattributes'][3] = $total_room_adult_child_info;

			if (!tep_session_is_registered('customer_id')){
				$this->contents[$products_id]['roomattributes'][4] = CHARSET;
			}
			$this->contents[$products_id]['roomattributes'][5] = (int)$travel_comp; //结伴同游
			$this->contents[$products_id]['roomattributes'][6] = (int)$agree_single_occupancy_pair_up; //单人部分配房
			$this->contents[$products_id]['roomattributes'][7] = $bed_option_info;	//床型选择项，与['roomattributes'][3]的值对应，只是没有!!

			$this->contents[$products_id]['is_new_group_buy'] = $is_new_group_buy;	//新团购
			$this->contents[$products_id]['no_sel_date_for_group_buy'] = $no_sel_date_for_group_buy;	//新团购
			$this->contents[$products_id]['hotel_extension_info'] = $hotel_extension_info;//hotel-extension
			$this->contents[$products_id]['transfer_info'] = $transfer_info;//hotel-extension
			$this->contents[$products_id]['extra_values'] = $extra_values;//hotel-extension
			if (tep_session_is_registered('customer_id')) {
				if(tep_not_null($this->contents[$products_id]['roomattributes'][4])){
					$total_info_room_input = iconv($this->contents[$products_id]['roomattributes'][4],CHARSET.'//IGNORE', $total_info_room);
				}else{
					$total_info_room_input = $total_info_room;
				}
				
				//重置$total_info_room_input信息确保无乱码
				$total_info_room_input = $this->re_get_room_info_to_gb2312($total_room_adult_child_info);
				
				//tep_db_query(html_to_db ("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added, products_departure_date, products_departure_time, products_departure_location, products_prefix, products_extra_charge, products_room_price, products_room_info, total_no_guest_tour, total_room_adult_child_info, products_extra_charge_cost, products_room_price_cost, travel_comp, agree_single_occupancy_pair_up, bed_option_info, is_new_group_buy, no_sel_date_for_group_buy ,hotel_extension_info,transfer_info, extra_values) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "', '" . $finaldate . "', '" . $depart_time . "', '" . tep_db_input($depart_location) . "', '" . $prifix . "', '" . $date_price . "', '" . $total_room_price . "', '" . $total_info_room_input . "', '".$total_no_guest_tour."','".$total_room_adult_child_info."', '".$date_price_cost."','".$total_room_price_cost."','".(int)$travel_comp."','".(int)$agree_single_occupancy_pair_up."', '".$bed_option_info."', '".$is_new_group_buy."', '".$no_sel_date_for_group_buy."', '".$hotel_extension_info."','").$transfer_info.html_to_db("', '".$extra_values."')"));
				
				$finaldate = date('Y-m-d', strtotime($finaldate));
				tep_db_query(("insert into " . TABLE_CUSTOMERS_BASKET . " set customers_id='" . (int)$customer_id . "', products_id='" . tep_db_input($products_id) . "', customers_basket_quantity='" . $qty . "', customers_basket_date_added='" . date('Ymd') . "', products_departure_date='" . $finaldate . "', products_departure_time='" . $depart_time . "', products_departure_location='" . tep_db_input($depart_location) . "', products_prefix='" . $prifix . "', products_extra_charge='" . $date_price . "', products_room_price='" . $total_room_price . "', products_room_info='" . $total_info_room_input . "', total_no_guest_tour='".$total_no_guest_tour."', total_room_adult_child_info='".$total_room_adult_child_info."', products_extra_charge_cost='".$date_price_cost."', products_room_price_cost='".$total_room_price_cost."', travel_comp='".(int)$travel_comp."', agree_single_occupancy_pair_up='".(int)$agree_single_occupancy_pair_up."', bed_option_info='".$bed_option_info."', is_new_group_buy='".$is_new_group_buy."', no_sel_date_for_group_buy='".$no_sel_date_for_group_buy."' ,hotel_extension_info='".$hotel_extension_info."',transfer_info='".$transfer_info."', extra_values='".$extra_values."'"));
				
			}

			if (is_array($attributes)) {
				reset($attributes);
				while (list($option, $value) = each($attributes)) {
					$this->contents[$products_id]['attributes'][$option] = $value;
					// insert into database
					if (tep_session_is_registered('customer_id')) tep_db_query(html_to_db ("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "')"));
				}
			}
		}
		$this->cleanup();

		// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
		$this->cartID = $this->generate_cart_id();
	}

	/**
	 * 更新购物车某个产品信息，如数量、房间、出发日期、价格等（此方法只在内部使用）
	 *
	 * @param unknown_type $products_id
	 * @param unknown_type $quantity
	 * @param unknown_type $attributes
	 * @param unknown_type $finaldate
	 * @param unknown_type $depart_time
	 * @param unknown_type $depart_location
	 * @param unknown_type $prifix
	 * @param unknown_type $date_price
	 * @param unknown_type $total_room_price
	 * @param unknown_type $total_info_room
	 * @param unknown_type $total_no_guest_tour
	 * @param unknown_type $total_room_adult_child_info
	 * @param unknown_type $date_price_cost
	 * @param unknown_type $total_room_price_cost
	 * @param unknown_type $travel_comp
	 * @param unknown_type $agree_single_occupancy_pair_up
	 * @param unknown_type $bed_option_info
	 * @param unknown_type $hotel_extension_info
	 * @param unknown_type $diy_tours_sort
	 * @param unknown_type $is_new_group_buy
	 * @param unknown_type $no_sel_date_for_group_buy
	 * @param unknown_type $hotel_extension_info
	 * @param unknown_type $transfer_info
	 * @param unknown_type $extra_values
	 * @return unknown
	 */
	private function update_quantity($products_id, $quantity = '',$attributes = '', $finaldate='', $depart_time='', $depart_location='', $prifix='', $date_price='', $total_room_price='', $total_info_room='', $total_no_guest_tour='', $total_room_adult_child_info='', $date_price_cost='', $total_room_price_cost='', $travel_comp='0', $agree_single_occupancy_pair_up='0', $bed_option_info = '', $hotel_extension_info = '', $diy_tours_sort=0, $is_new_group_buy=0, $no_sel_date_for_group_buy=0,$hotel_extension_info= '' , $transfer_info, $extra_values = '') {

		global $customer_id;

		if (empty($quantity)) return true; // nothing needs to be updated if theres no quantity, so we return true..
		$this->contents[$products_id] = array('qty' => $quantity);
		$this->contents[$products_id]['dateattributes'][0] = $finaldate;
		$this->contents[$products_id]['dateattributes'][1] = $depart_time;
		$this->contents[$products_id]['dateattributes'][2] = $depart_location;
		$this->contents[$products_id]['dateattributes'][3] = $prifix;

		$this->contents[$products_id]['dateattributes'][4] =  number_format(($date_price*$total_no_guest_tour),2, '.', '');

		$this->contents[$products_id]['roomattributes'][0] = $total_room_price;

		$this->contents[$products_id]['dateattributes_cost'][4] = number_format(($date_price_cost*$total_no_guest_tour),2, '.', '');
		$this->contents[$products_id]['roomattributes_cost'][0] = $total_room_price_cost;

		$this->contents[$products_id]['roomattributes'][1] = $total_info_room;
		$this->contents[$products_id]['roomattributes'][2] = $total_no_guest_tour;
		$this->contents[$products_id]['roomattributes'][3] = $total_room_adult_child_info;

		if (!tep_session_is_registered('customer_id')){
			$this->contents[$products_id]['roomattributes'][4] = CHARSET;
		}
		$this->contents[$products_id]['roomattributes'][5] = (int)$travel_comp; //结伴同游
		$this->contents[$products_id]['roomattributes'][6] = (int)$agree_single_occupancy_pair_up; //单人部分配房
		$this->contents[$products_id]['roomattributes'][7] = $bed_option_info;	//床型选择项，与['roomattributes'][3]的值对应，只是没有!!
		$this->contents[$products_id]['hotel_extension_info'] = $hotel_extension_info;
		$this->contents[$products_id]['is_diy_tours_book'] = $diy_tours_sort;
		$this->contents[$products_id]['is_new_group_buy'] = $is_new_group_buy;
		$this->contents[$products_id]['no_sel_date_for_group_buy'] = $no_sel_date_for_group_buy;
		$this->contents[$products_id]['extra_values'] = $extra_values;
		$this->contents[$products_id]['hotel_extension_info'] = $hotel_extension_info;
		$this->contents[$products_id]['transfer_info'] = $transfer_info;

		// update database
		if (tep_session_is_registered('customer_id')) tep_db_query(html_to_db ("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "', travel_comp='".(int)$travel_comp."', agree_single_occupancy_pair_up='".(int)$agree_single_occupancy_pair_up."', bed_option_info='".$bed_option_info."' ,hotel_extension_info = '".$hotel_extension_info."', extra_values = '".$extra_values."'  where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'"));

		if (is_array($attributes)) {
			reset($attributes);
			while (list($option, $value) = each($attributes)) {
				$this->contents[$products_id]['attributes'][$option] = $value;
				// update database
				if (tep_session_is_registered('customer_id')) tep_db_query(html_to_db ("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int)$option . "'"));
			}
		}
	}

	//清空购物车，包括清空数据库中的购物车资料
	function cleanup() {
		global $customer_id;

		reset($this->contents);
		while (list($key,) = each($this->contents)) {
			if ($this->contents[$key]['qty'] < 1) {
				unset($this->contents[$key]);
				// remove from database
				if (tep_session_is_registered('customer_id')) {
					//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
					//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
					tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and CONCAT(products_id,'{') Like '" . tep_get_prid($key) . "{%'");
					tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and CONCAT(products_id,'{') Like '" . tep_get_prid($key) . "{%'");
				}
			}
		}
	}

	//计算购物车产品数量
	function count_contents() {  // get total number of items in cart
		$total_items = 0;
		if (is_array($this->contents)) {
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) {
				$total_items += $this->get_quantity($products_id);
			}
		}

		return $total_items;
	}

	//取得产品数量
	function get_quantity($products_id) {
		if (isset($this->contents[$products_id])) {
			return $this->contents[$products_id]['qty'];
		} else {
			return 0;
		}
	}

	//判断购物车内是否有编号为$products_id的产品
	function in_cart($products_id) {
		if (isset($this->contents[$products_id])) {
			return true;
		} else {
			return false;
		}
	}

	//从购物车中删除编号为$products_id产品
	function remove($products_id) {
		global $customer_id;

		unset($this->contents[$products_id]);
		// remove from database
		if (tep_session_is_registered('customer_id')) {
			//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
			//tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
			tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and CONCAT(products_id,'{') Like '" . tep_get_prid($products_id) . "{%'");
			tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and CONCAT(products_id,'{') Like '" . tep_get_prid($products_id) . "{%'");



		}

		// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
		$this->cartID = $this->generate_cart_id();
	}

	//删除购物车内的所有产品
	function remove_all() {
		$this->reset();
	}

	//取得购物车内产品ID列表，返回的是字符串
	function get_product_id_list() {
		$product_id_list = '';
		if (is_array($this->contents)) {
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) {
				$product_id_list .= ', ' . $products_id;
			}
		}

		return substr($product_id_list, 2);
	}

	//计算
	function calculate() {
		$this->total_virtual = 0; // ICW Gift Voucher System
		$this->total = 0;
		$this->weight = 0;
		if (!is_array($this->contents)) return 0;


		reset($this->contents);
		while (list($products_id, ) = each($this->contents)) {
			$tmp_fianl_price = 0;	//默认的当前产品最终价

			$qty = $this->contents[$products_id]['qty'];

			// products price
			$product_query = tep_db_query("select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
			if ($product = tep_db_fetch_array($product_query)) {
				// ICW ORDER TOTAL CREDIT CLASS Start Amendment
				$no_count = 1;
				$gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
				$gv_result = tep_db_fetch_array($gv_query);
				if (ereg('^GIFT', $gv_result['products_model'])) {
					$no_count = 0;
				}
				// ICW ORDER TOTAL  CREDIT CLASS End Amendment
				$prid = $product['products_id'];
				$products_tax = tep_get_tax_rate($product['products_tax_class_id']);
				$products_price = 0;//$product['products_price'];
				$products_weight = $product['products_weight'];

				$special_price = tep_get_products_special_price($prid);
				if ($special_price) {
					// $products_price = $special_price;
				}
				$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
				$this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
				$this->total += tep_add_tax($products_price, $products_tax) * $qty;
				$tmp_fianl_price += tep_add_tax($products_price, $products_tax) * $qty;
				$this->weight += ($qty * $products_weight);


			}


			// extra date price

			if ($this->contents[$products_id]['dateattributes'][4] != '')
			{
				$dateprifix = $this->contents[$products_id]['dateattributes'][3];
				$datePrice = $this->contents[$products_id]['dateattributes'][4];

				if ($dateprifix == '-')
				{
					$this->total -= $qty * tep_add_tax($datePrice, $products_tax);
					$tmp_fianl_price -= $qty * tep_add_tax($datePrice, $products_tax);
				} else
				$this->total += $qty * tep_add_tax($datePrice, $products_tax);
				$tmp_fianl_price += $qty * tep_add_tax($datePrice, $products_tax);
				{
				}
			}

			//room price
			if($this->contents[$products_id]['roomattributes'][0] != '')
			{
				$roomPrice = $this->contents[$products_id]['roomattributes'][0];
				$this->total += $qty * tep_add_tax($roomPrice, $products_tax);
				$tmp_fianl_price += $qty * tep_add_tax($roomPrice, $products_tax);
			}

			// attributes price start
			if (isset($this->contents[$products_id]['attributes'])) {
				reset($this->contents[$products_id]['attributes']);
				while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
					$attribute_price_query = tep_db_query("select options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
					$attribute_price = tep_db_fetch_array($attribute_price_query);
					//amit modified to make sure price on usd
					$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$prid);
					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$attribute_price['single_values_price'] = tep_get_tour_price_in_usd($attribute_price['single_values_price'],$tour_agency_opr_currency);
						$attribute_price['double_values_price'] = tep_get_tour_price_in_usd($attribute_price['double_values_price'],$tour_agency_opr_currency);
						$attribute_price['triple_values_price'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price'],$tour_agency_opr_currency);
						$attribute_price['quadruple_values_price'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price'],$tour_agency_opr_currency);
						$attribute_price['kids_values_price'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price'],$tour_agency_opr_currency);
						$attribute_price['options_values_price'] = tep_get_tour_price_in_usd($attribute_price['options_values_price'],$tour_agency_opr_currency);
					}
					//amit modified to make sure price on usd
					$att[1] = $attribute_price['single_values_price']; //single
					$att[2] = $attribute_price['double_values_price']; //double
					$att[3] = $attribute_price['triple_values_price']; //triple
					$att[4] = $attribute_price['quadruple_values_price']; //quadr
					$ett = $attribute_price['kids_values_price']; //child Kid

					// the attribute price per day for hotel code should be here
					$option_name = tep_get_product_option_name_from_optionid((int)$option);
					if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/".iconv("gb2312",'big5',"请选择早餐类别")."/i", strtolower((string)$option_name)) || preg_match("/请选择早餐类别/i", strtolower((string)$option_name)) ){
						$loop_start = strtotime($this->contents[$products_id]['dateattributes'][0]);
						$hotel_extension_info = explode('|=|', $this->contents[$products_id]['hotel_extension_info']);
						if($this->contents[$products_id]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
							$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
						}else{
							$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
						}
						$loop_end = strtotime($hotel_checkout_date);
						$loop_increment = (24*60*60);
						$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
						$att[1] = $attribute_price['single_values_price'] = $attribute_price['single_values_price'] * $hotel_stay_days; //single
						$att[2] = $attribute_price['double_values_price'] = $attribute_price['double_values_price'] * $hotel_stay_days; //single
						$att[3] = $attribute_price['triple_values_price'] = $attribute_price['triple_values_price'] * $hotel_stay_days; //single
						$att[4] = $attribute_price['quadruple_values_price'] = $attribute_price['quadruple_values_price'] * $hotel_stay_days; //single
						$ett = $attribute_price['kids_values_price'] = $attribute_price['kids_values_price'] * $hotel_stay_days; //single
						$attribute_price['options_values_price'] = $attribute_price['options_values_price'] * $hotel_stay_days;

					}
					//amit added to check apply attribute per order start
					$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
					//amit added to check apply attribute per order end
					if(($attribute_price['single_values_price'] > 0 || $attribute_price['double_values_price'] > 0 || $attribute_price['triple_values_price'] > 0 || $attribute_price['quadruple_values_price'] > 0) && $is_per_order_attribute != 1 ){
						$final_chk_price_c = 0;
						if($this->contents[$products_id]['roomattributes'][3] == ''){
							$final_chk_price_c = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
						}else{
							$tot_nn_roms_chked_c = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

							if($tot_nn_roms_chked_c > 0){

								for($ri=1;$ri<=$tot_nn_roms_chked_c;$ri++){

									$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

									$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

									if($chaild_adult_no_arr[1] == 0){
										$final_chk_price_c += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
									}else{

										if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
											$low_price_formula_option[0] = 2*$att[2];
											$low_price_formula_option[1] = $att[1]+$ett;
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
											$low_price_formula_option[0] = (2*$att[2]) + $ett;
											$low_price_formula_option[1] = 3*$att[3];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
											$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
											$low_price_formula_option[1] = 3*$att[3]+$ett;
											$low_price_formula_option[2] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
										}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
											$low_price_formula_option[0] = (2*$att[2]) + $ett;
											$low_price_formula_option[1] = 3*$att[3];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
											$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
											$low_price_formula_option[1] = 3*$att[3]+$ett;
											$low_price_formula_option[2] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
										}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
											$low_price_formula_option[0] = 3*$att[3]+$ett;
											$low_price_formula_option[1] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}

									}

									/*  old commented
									if($tt_persion_per_room_cal_price == 1){
									$final_chk_price_c += $attribute_price['single_values_price']*$tt_persion_per_room_cal_price;
									}else if($tt_persion_per_room_cal_price == 2){
									$final_chk_price_c += $attribute_price['double_values_price']*$tt_persion_per_room_cal_price;
									}else if($tt_persion_per_room_cal_price == 3){
									$final_chk_price_c += $attribute_price['triple_values_price']*$tt_persion_per_room_cal_price;
									}else if($tt_persion_per_room_cal_price == 4){
									$final_chk_price_c += $attribute_price['quadruple_values_price']*$tt_persion_per_room_cal_price;
									}else{
									$final_chk_price_c += $attribute_price['single_values_price']*$tt_persion_per_room_cal_price;
									}
									*/
								}
							}else{
								//tour with no room option but special price added
								/*
								$final_chk_price_c = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
								*/
								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);

								$final_chk_price_c = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;
							}

						}


						if ($attribute_price['price_prefix'] == '-') {
							$this->total -= $qty * tep_add_tax($final_chk_price_c, $products_tax);
							$tmp_fianl_price -= $qty * tep_add_tax($final_chk_price_c, $products_tax);
						} else {
							$this->total += $qty * tep_add_tax($final_chk_price_c, $products_tax);
							$tmp_fianl_price += $qty * tep_add_tax($final_chk_price_c, $products_tax);
						}

					}else{

						//amit added to check apply attribute per order start
						$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
						if($is_per_order_attribute == 1){
							$temp_default_per_count = 1; // per order attribute
						}
						//amit added to check apply attribute per order end

						if ($attribute_price['price_prefix'] == '-') {
							$this->total -= $qty * tep_add_tax($attribute_price['options_values_price']*$temp_default_per_count, $products_tax);
							$tmp_fianl_price -= $qty * tep_add_tax($attribute_price['options_values_price']*$temp_default_per_count, $products_tax);
						} else {
							$this->total += $qty * tep_add_tax($attribute_price['options_values_price']*$temp_default_per_count, $products_tax);
							$tmp_fianl_price += $qty * tep_add_tax($attribute_price['options_values_price']*$temp_default_per_count, $products_tax);
						}
					}


				}
			}
			// attributes price end

			//featured group deal discount - start
			$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products_id."' and departure_restriction_date <= '".$this->contents[$products_id]['dateattributes'][0]."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
			if(check_is_featured_deal($products_id) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
				$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($products_id);
				$total_featured_guests = $total_featured_guests + $this->contents[$products_id]['roomattributes'][2];

				$product_featured_price = tep_db_query("select p.products_price, p.products_margin from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "' ");
				$product_featured_result = tep_db_fetch_array($product_featured_price);

				$get_featured_deal_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$products_id."' and peple_no <= '".$total_featured_guests."' order by peple_no desc limit 1");

				if(tep_db_num_rows($get_featured_deal_discount_data)>0){
					$featured_deal_discounts_data = tep_db_fetch_array($get_featured_deal_discount_data);
					$featured_deal_discount_ratio = $featured_deal_discounts_data['discount_percent']/100;
				}else{
					$get_featured_special_price = tep_db_fetch_array($check_featured_dept_restriction);
					//$featured_deal_discount_ratio = number_format(100 - (($get_featured_special_price['featured_deals_new_products_price'] / $product_hotel_result['products_price']) * 100))/100;
					if((int)$get_featured_special_price['featured_deals_new_products_price']){
						$featured_deal_discount_ratio = number_format((100 * ($product_featured_result['products_price'] - $get_featured_special_price['featured_deals_new_products_price']))/($product_featured_result['products_price'] * $product_featured_result['products_margin']/100))/100;
					}
				}
				$featured_tour_gp = $product_featured_result['products_margin']/100;
				$group_buy_discount = round((($tmp_fianl_price * $featured_tour_gp)*$featured_deal_discount_ratio),2);
				$this->total -= $group_buy_discount;
			}else{
				//Group Ordering
				//团购 start
				$is_long_trour = false;
				if((int)substr($this->contents[$products_id]['roomattributes'][3],0,1)){
					$is_long_trour = true;
				}
				$is_group_discount = is_group_discount_allowed((int)$products_id);
				if(GROUP_BUY_ON==true && $this->contents[$products_id]['roomattributes'][2] >= GROUP_MIN_GUEST_NUM && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true) ){
					if($is_group_discount==true){
						$discount_percentage = auto_get_group_buy_discount($products_id);
						$group_buy_discount = round($tmp_fianl_price * $discount_percentage, DECIMAL_DIGITS);
						$this->total -= $group_buy_discount;
					}
				}
				//团购 end
				//Group Ordering
			}
			//featured group deal discount - end

		}
		// while loop end
	}

	function attributes_price($products_id) {
		$attributes_price = 0;

		if (isset($this->contents[$products_id]['attributes'])) {
			reset($this->contents[$products_id]['attributes']);
			while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
				$attribute_price_query = tep_db_query("select options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
				$attribute_price = tep_db_fetch_array($attribute_price_query);
				// $this->contents[$products_id]['roomattributes'][2];
				/* amit commented due to apply price per persion
				if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $attribute_price['options_values_price'];
				} else {
				$attributes_price += $attribute_price['options_values_price'];
				}
				*/
				//amit modified to make sure price on usd
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$attribute_price['single_values_price'] = tep_get_tour_price_in_usd($attribute_price['single_values_price'],$tour_agency_opr_currency);
					$attribute_price['double_values_price'] = tep_get_tour_price_in_usd($attribute_price['double_values_price'],$tour_agency_opr_currency);
					$attribute_price['triple_values_price'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price'],$tour_agency_opr_currency);
					$attribute_price['quadruple_values_price'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price'],$tour_agency_opr_currency);
					$attribute_price['kids_values_price'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price'],$tour_agency_opr_currency);
					$attribute_price['options_values_price'] = tep_get_tour_price_in_usd($attribute_price['options_values_price'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				//new change start
				$att[1] = $attribute_price['single_values_price']; //single
				$att[2] = $attribute_price['double_values_price']; //double
				$att[3] = $attribute_price['triple_values_price']; //triple
				$att[4] = $attribute_price['quadruple_values_price']; //quadr
				$ett = $attribute_price['kids_values_price']; //child Kid
				//need to change logic according to new added field

				// the attribute price per day for hotel code should be here
				$option_name = tep_get_product_option_name_from_optionid((int)$option);
				if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/".iconv("gb2312",'big5',"请选择早餐类别")."/i", strtolower((string)$option_name)) || preg_match("/请选择早餐类别/i", strtolower((string)$option_name)) ){
					$loop_start = strtotime($this->contents[$products_id]['dateattributes'][0]);
					$hotel_extension_info = explode('|=|', $this->contents[$products_id]['hotel_extension_info']);
					if($this->contents[$products_id]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
					}else{
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
					}
					$loop_end = strtotime($hotel_checkout_date);
					$loop_increment = (24*60*60);
					$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
					$att[1] = $attribute_price['single_values_price'] = $attribute_price['single_values_price'] * $hotel_stay_days; //single
					$att[2] = $attribute_price['double_values_price'] = $attribute_price['double_values_price'] * $hotel_stay_days; //single
					$att[3] = $attribute_price['triple_values_price'] = $attribute_price['triple_values_price'] * $hotel_stay_days; //single
					$att[4] = $attribute_price['quadruple_values_price'] = $attribute_price['quadruple_values_price'] * $hotel_stay_days; //single
					$ett = $attribute_price['kids_values_price'] = $attribute_price['kids_values_price'] * $hotel_stay_days; //single
					$attribute_price['options_values_price'] = $attribute_price['options_values_price'] * $hotel_stay_days;

				}
				//amit added to check apply attribute per order start
				$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
				//amit added to check apply attribute per order end
				if(($attribute_price['single_values_price'] > 0 || $attribute_price['double_values_price'] > 0 || $attribute_price['triple_values_price'] > 0 || $attribute_price['quadruple_values_price'] > 0) && $is_per_order_attribute != 1){

					$final_chk_price = 0;
					//check for old data stored on customer bascket start
					if($this->contents[$products_id]['roomattributes'][3] == ''){

						$final_chk_price = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
					}else{

						//check for old data stored on customer bascket end
						$tot_nn_roms_chked = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

						if($tot_nn_roms_chked > 0){
							for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

								if($chaild_adult_no_arr[1] == 0){
									$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
								}else{

									if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
										$low_price_formula_option[0] = 2*$att[2];
										$low_price_formula_option[1] = $att[1]+$ett;
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = 3*$att[3]+$ett;
										$low_price_formula_option[1] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}

								}

								/*  old commented
								if($tt_persion_per_room_cal_price == 1){
								$final_chk_price += $attribute_price['single_values_price']*$tt_persion_per_room_cal_price;
								}else if($tt_persion_per_room_cal_price == 2){
								$final_chk_price += $attribute_price['double_values_price']*$tt_persion_per_room_cal_price;
								}else if($tt_persion_per_room_cal_price == 3){
								$final_chk_price += $attribute_price['triple_values_price']*$tt_persion_per_room_cal_price;
								}else if($tt_persion_per_room_cal_price == 4){
								$final_chk_price += $attribute_price['quadruple_values_price']*$tt_persion_per_room_cal_price;
								}else{
								$final_chk_price += $attribute_price['single_values_price']*$tt_persion_per_room_cal_price;
								}
								*/
							}
						}else{
							//tour with no room option but special price added

							/*
							$final_chk_price = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
							*/
							$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
							$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);
							$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

						}

					}


					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $final_chk_price;
					} else {
						$attributes_price += $final_chk_price;
					}

					//echo (int)$products_id.'-->'.$attributes_price.'<br>';

				}else{

					//amit added to check apply attribute per order start
					$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
					if($is_per_order_attribute == 1){
						$temp_default_per_count = 1; // per order attribute
					}
					//amit added to check apply attribute per order end
					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $attribute_price['options_values_price']*$temp_default_per_count;
					} else {
						$attributes_price += $attribute_price['options_values_price']*$temp_default_per_count;
					}
				}
				//new change end

			}
		}

		return $attributes_price;
	}

	//dispaly specific attribute  price start

	function attributes_price_display($products_id,$option,$value) {
		$attributes_price = 0;

		if (isset($this->contents[$products_id]['attributes'])) {
			reset($this->contents[$products_id]['attributes']);
			$attribute_price_query = tep_db_query("select options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
			while ($attribute_price = tep_db_fetch_array($attribute_price_query)) {
				//amit modified to make sure price on usd
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$attribute_price['single_values_price'] = tep_get_tour_price_in_usd($attribute_price['single_values_price'],$tour_agency_opr_currency);
					$attribute_price['double_values_price'] = tep_get_tour_price_in_usd($attribute_price['double_values_price'],$tour_agency_opr_currency);
					$attribute_price['triple_values_price'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price'],$tour_agency_opr_currency);
					$attribute_price['quadruple_values_price'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price'],$tour_agency_opr_currency);
					$attribute_price['kids_values_price'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price'],$tour_agency_opr_currency);
					$attribute_price['options_values_price'] = tep_get_tour_price_in_usd($attribute_price['options_values_price'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				//new change start
				$att[1] = $attribute_price['single_values_price']; //single
				$att[2] = $attribute_price['double_values_price']; //double
				$att[3] = $attribute_price['triple_values_price']; //triple
				$att[4] = $attribute_price['quadruple_values_price']; //quadr
				$ett = $attribute_price['kids_values_price']; //child Kid
				//need to change logic according to new added field

				// the attribute price per day for hotel code should be here
				$option_name = tep_get_product_option_name_from_optionid((int)$option);
				if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/???x?裨绮皖??e/i", strtolower($option_name)) || preg_match("/请选择早餐类别/i", strtolower($option_name)) ){
					$loop_start = strtotime($this->contents[$products_id]['dateattributes'][0]);
					$hotel_extension_info = explode('|=|', $this->contents[$products_id]['hotel_extension_info']);
					if($this->contents[$products_id]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
					}else{
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
					}
					$loop_end = strtotime($hotel_checkout_date);
					$loop_increment = (24*60*60);
					$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
					$att[1] = $attribute_price['single_values_price'] = $attribute_price['single_values_price'] * $hotel_stay_days; //single
					$att[2] = $attribute_price['double_values_price'] = $attribute_price['double_values_price'] * $hotel_stay_days; //single
					$att[3] = $attribute_price['triple_values_price'] = $attribute_price['triple_values_price'] * $hotel_stay_days; //single
					$att[4] = $attribute_price['quadruple_values_price'] = $attribute_price['quadruple_values_price'] * $hotel_stay_days; //single
					$ett = $attribute_price['kids_values_price'] = $attribute_price['kids_values_price'] * $hotel_stay_days; //single
					$attribute_price['options_values_price'] = $attribute_price['options_values_price'] * $hotel_stay_days;

				}
				//amit added to check apply attribute per order start
				$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
				//amit added to check apply attribute per order end
				if(($attribute_price['single_values_price'] > 0 || $attribute_price['double_values_price'] > 0 || $attribute_price['triple_values_price'] > 0 || $attribute_price['quadruple_values_price'] > 0) && $is_per_order_attribute != 1){

					$final_chk_price = 0;
					//check for old data stored on customer bascket start
					if($this->contents[$products_id]['roomattributes'][3] == ''){

						$final_chk_price = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
					}else{

						//check for old data stored on customer bascket end
						$tot_nn_roms_chked = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

						if($tot_nn_roms_chked > 0){
							for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

								if($chaild_adult_no_arr[1] == 0){
									$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
								}else{

									if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
										$low_price_formula_option[0] = 2*$att[2];
										$low_price_formula_option[1] = $att[1]+$ett;
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = 3*$att[3]+$ett;
										$low_price_formula_option[1] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}

								}


							}
						}else{
							//tour with no room option but special price added

							/*
							$final_chk_price = $attribute_price['single_values_price']*$this->contents[$products_id]['roomattributes'][2];
							*/
							$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
							$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);
							$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

						}

					}


					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $final_chk_price;
					} else {
						$attributes_price += $final_chk_price;
					}

					//echo (int)$products_id.'-->'.$attributes_price.'<br>';

				}else{
					//amit added to check apply attribute per order start
					$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
					if($is_per_order_attribute == 1){
						$temp_default_per_count = 1; // per order attribute
					}
					//amit added to check apply attribute per order end

					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $attribute_price['options_values_price']*$temp_default_per_count;
					} else {
						$attributes_price += $attribute_price['options_values_price']*$temp_default_per_count;
					}
				}
				//new change end

			}
		}

		return $attributes_price;
	}

	//display specific attribute price end

	//dispaly specific attribute price cost

	function attributes_price_display_cost($products_id,$option,$value) {
		$attributes_price = 0;


		if (isset($this->contents[$products_id]['attributes'])) {
			reset($this->contents[$products_id]['attributes']);
			$attribute_price_query = tep_db_query("select options_values_price_cost, price_prefix, single_values_price_cost, double_values_price_cost, triple_values_price_cost, quadruple_values_price_cost, kids_values_price_cost  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
			while ($attribute_price = tep_db_fetch_array($attribute_price_query)) {
				//amit modified to make sure price on usd
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$attribute_price['single_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['single_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['double_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['double_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['triple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['quadruple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['kids_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['options_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['options_values_price_cost'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				//new change start
				$att[1] = $attribute_price['single_values_price_cost']; //single
				$att[2] = $attribute_price['double_values_price_cost']; //double
				$att[3] = $attribute_price['triple_values_price_cost']; //triple
				$att[4] = $attribute_price['quadruple_values_price_cost']; //quadr
				$ett = $attribute_price['kids_values_price_cost']; //child Kid
				//need to change logic according to new added field

				// the attribute price per day for hotel code should be here
				$option_name = tep_get_product_option_name_from_optionid((int)$option);
				if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/???x?裨绮皖??e/i", strtolower($option_name)) || preg_match("/请选择早餐类别/i", strtolower($option_name)) ){
					$loop_start = strtotime($this->contents[$products_id]['dateattributes'][0]);
					$hotel_extension_info = explode('|=|', $this->contents[$products_id]['hotel_extension_info']);
					if($this->contents[$products_id]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
					}else{
						$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
					}
					$loop_end = strtotime($hotel_checkout_date);
					$loop_increment = (24*60*60);
					$hotel_stay_days = (int)($loop_end-$loop_start)/$loop_increment;
					$att[1] = $attribute_price['single_values_price_cost'] = $attribute_price['single_values_price_cost'] * $hotel_stay_days; //single
					$att[2] = $attribute_price['double_values_price_cost'] = $attribute_price['double_values_price_cost'] * $hotel_stay_days; //single
					$att[3] = $attribute_price['triple_values_price_cost'] = $attribute_price['triple_values_price_cost'] * $hotel_stay_days; //single
					$att[4] = $attribute_price['quadruple_values_price_cost'] = $attribute_price['quadruple_values_price_cost'] * $hotel_stay_days; //single
					$ett = $attribute_price['kids_values_price_cost'] = $attribute_price['kids_values_price_cost'] * $hotel_stay_days; //single
					$attribute_price['options_values_price_cost'] = $attribute_price['options_values_price_cost'] * $hotel_stay_days;

				}
				//amit added to check apply attribute per order start
				$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
				//amit added to check apply attribute per order end
				if(($attribute_price['single_values_price_cost'] > 0 || $attribute_price['double_values_price_cost'] > 0 || $attribute_price['triple_values_price_cost'] > 0 || $attribute_price['quadruple_values_price_cost'] > 0) && $is_per_order_attribute != 1){

					$final_chk_price = 0;
					//check for old data stored on customer bascket start
					if($this->contents[$products_id]['roomattributes'][3] == ''){
						$final_chk_price = $attribute_price['single_values_price_cost']*$this->contents[$products_id]['roomattributes'][2];
					}else{
						//check for old data stored on customer bascket end
						$tot_nn_roms_chked = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

						if($tot_nn_roms_chked > 0){
							for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

								if($chaild_adult_no_arr[1] == 0){
									$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
								}else{

									if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
										$low_price_formula_option[0] = 2*$att[2];
										$low_price_formula_option[1] = $att[1]+$ett;
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = 3*$att[3]+$ett;
										$low_price_formula_option[1] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}

								}


							}
						}else{
							//tour with no room option but special price added

							$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
							$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);
							$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

						}

					}


					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $final_chk_price;
					} else {
						$attributes_price += $final_chk_price;
					}

					//echo (int)$products_id.'-->'.$attributes_price.'<br>';

				}else{

					//amit added to check apply attribute per order start
					$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
					if($is_per_order_attribute == 1){
						$temp_default_per_count = 1; // per order attribute
					}
					//amit added to check apply attribute per order end
					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $attribute_price['options_values_price_cost']*$temp_default_per_count;
					} else {
						$attributes_price += $attribute_price['options_values_price_cost']*$temp_default_per_count;
					}
				}
				//new change end

			}

		}
		return $attributes_price;
	}
	//display specifc attribute price cost




	//date price
	function final_date_price($products_id) {
		$datePrice = 0;
		if ($this->contents[$products_id]['dateattributes'][3] == '-') {
			$datePrice -= $this->contents[$products_id]['dateattributes'][4];
		} else {
			$datePrice += $this->contents[$products_id]['dateattributes'][4];
		}

		return $datePrice;
	}
	//room price
	function final_room_price($products_id) {
		$roomPrice = 0;
		$roomPrice += $this->contents[$products_id]['roomattributes'][0];

		return $roomPrice;
	}

	/**
	 * 取得产品信息
	 *
	 * @return unknown
	 */
	function get_products() {
		global $languages_id;

		if (!is_array($this->contents)) return false;

		$products_array = array();
		reset($this->contents);
		while (list($products_id, ) = each($this->contents)) {
			$products_query = tep_db_query("select p.max_allow_child_age, p.products_id, pd.products_name, p.products_model,p.products_type, p.products_image, p.products_price, p.products_weight, p.products_tax_class_id, p.products_margin, ta.default_max_allow_child_age, ta.operate_currency_code, ta.is_birth_info, ta.is_gender_info, ta.is_hotel_pickup_info , p.is_hotel, p.agency_id, p.is_transfer from " . TABLE_PRODUCTS . " p, " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$products_id . "' and pd.products_id = p.products_id and p.agency_id = ta.agency_id and pd.language_id = '" . (int)$languages_id . "'");
			if ($products = tep_db_fetch_array($products_query)) {
				$prid = $products['products_id'];
				$products_price = 0;//$products['products_price'];

				$special_price = tep_get_products_special_price($prid);
				if ($special_price) {
					// $products_price = $special_price;
				}
				//amit added to get default age value if not set start
				if($products['max_allow_child_age'] == '' || $products['max_allow_child_age'] == '0' ){
					$products['max_allow_child_age'] = $products['default_max_allow_child_age'];
				}
				//amit added to get default age value if not set end
				//echo $this->contents[$products_id]['dateattributes'][3].$this->contents[$products_id]['dateattributes'][4];

				$tmp_final_price = ($products_price + $this->attributes_price($products_id) + $this->final_date_price($products_id) + $this->final_room_price($products_id));

				//featured group deal discount - start
				$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products_id."' and departure_restriction_date <= '".$this->contents[$products_id]['dateattributes'][0]."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
				if(check_is_featured_deal($products_id) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
					$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($products_id);
					$total_featured_guests = $total_featured_guests + $this->contents[$products_id]['roomattributes'][2];
					$product_featured_price = tep_db_query("select p.products_price, p.products_margin from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "' ");
					$product_featured_result = tep_db_fetch_array($product_featured_price);

					$get_featured_deal_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$products_id."' and peple_no <= '".$total_featured_guests."' order by peple_no desc limit 1");

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
					//Group Ordering
					//团购 start
					$group_buy_discount = 0;
					$total_no_guest_tour = $this->contents[$products_id]['roomattributes'][2];
					$is_long_trour = false;
					if((int)substr($this->contents[$products_id]['roomattributes'][3],0,1)){
						$is_long_trour = true;
					}
					$is_group_discount = is_group_discount_allowed((int)$products_id);
					if(GROUP_BUY_ON==true && $total_no_guest_tour >= GROUP_MIN_GUEST_NUM  && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true) ){
						/*
						$discount_percentage = auto_get_group_buy_discount($products_id);
						$group_buy_discount = round($tmp_final_price * $discount_percentage, DECIMAL_DIGITS);
						$tmp_final_price = $tmp_final_price - $group_buy_discount;
						*/
						if($is_group_discount==true){
							$group_buy_discount = round($tmp_final_price * DISCOUNT_PERCENTAGE, DECIMAL_DIGITS);
							$tmp_final_price = $tmp_final_price - $group_buy_discount;
						}
					}
					//团购 end
					//Group Ordering
				}
				//featured group deal discount - end

				$products_array[] = array('id' => $products_id,
											'name' => $products['products_name'],
											'model' => $products['products_model'],
											'products_margin' => $products['products_margin'],
											'products_type' => $products['products_type'],
											'max_allow_child_age' => $products['max_allow_child_age'],
											'image' => $products['products_image'],
											'price' => $products_price,
											'quantity' => $this->contents[$products_id]['qty'],
											'dateattributes' => $this->contents[$products_id]['dateattributes'],
											'roomattributes' => $this->contents[$products_id]['roomattributes'],
											'weight' => $products['products_weight'],
											'group_buy_discount' => $group_buy_discount,
											'is_new_group_buy' => $this->contents[$products_id]['is_new_group_buy'],
											'no_sel_date_for_group_buy' => $this->contents[$products_id]['no_sel_date_for_group_buy'],
											'final_price' => $tmp_final_price,
											'final_price_cost' => ($products_price + $this->attributes_price_cost($products_id) + $this->final_date_price_cost($products_id) + $this->final_room_price_cost($products_id)),
											'tax_class_id' => $products['products_tax_class_id'],
											'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''),
											'operate_currency_code' => $products['operate_currency_code'],
											'is_diy_tours_book' => $this->contents[$products_id]['is_diy_tours_book'],
											'is_birth_info' => $products['is_birth_info'],
											'is_gender_info' => $products['is_gender_info'],
											'is_hotel_pickup_info' => $products['is_hotel_pickup_info'],
											'hotel_extension_info' => $this->contents[$products_id]['hotel_extension_info'],
											'is_hotel' => $products['is_hotel'],
											'agency_id' => $products['agency_id'],
											'extra_values' => $this->contents[$products_id]['extra_values'],
											'transfer_info' => $this->contents[$products_id]['transfer_info'],
											'is_transfer' => $products['is_transfer'],
											'agency_id' => $products['agency_id']

				);
			}
		}
		return $products_array;
	}

	function show_total() {
		$this->calculate();

		return $this->total;
	}

	function show_weight() {
		$this->calculate();

		return $this->weight;
	}
	// CREDIT CLASS Start Amendment
	function show_total_virtual() {
		$this->calculate();

		return $this->total_virtual;
	}

	function show_weight_virtual() {
		$this->calculate();

		return $this->weight_virtual;
	}
	// CREDIT CLASS End Amendment

	function generate_cart_id($length = 5) {
		return tep_create_random_value($length, 'digits');
	}

	function get_content_type() {
		$this->content_type = false;

		if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) {
				if (isset($this->contents[$products_id]['attributes'])) {
					reset($this->contents[$products_id]['attributes']);
					while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
						$virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
						$virtual_check = tep_db_fetch_array($virtual_check_query);

						if ($virtual_check['total'] > 0) {
							switch ($this->content_type) {
								case 'physical':
									$this->content_type = 'mixed';

									return $this->content_type;
									break;
								default:
									$this->content_type = 'virtual';
									break;
							}
						} else {
							switch ($this->content_type) {
								case 'virtual':
									$this->content_type = 'mixed';

									return $this->content_type;
									break;
								default:
									$this->content_type = 'physical';
									break;
							}
						}
					}
					// ICW ADDED CREDIT CLASS - Begin
				} elseif ($this->show_weight() == 0) {
					reset($this->contents);
					while (list($products_id, ) = each($this->contents)) {
						$virtual_check_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
						$virtual_check = tep_db_fetch_array($virtual_check_query);
						if ($virtual_check['products_weight'] == 0) {
							switch ($this->content_type) {
								case 'physical':
									$this->content_type = 'mixed';

									return $this->content_type;
									break;
								default:
									$this->content_type = 'virtual_weight';
									break;
							}
						} else {
							switch ($this->content_type) {
								case 'virtual':
									$this->content_type = 'mixed';

									return $this->content_type;
									break;
								default:
									$this->content_type = 'physical';
									break;
							}
						}
					}
					// ICW ADDED CREDIT CLASS - End
				} else {
					switch ($this->content_type) {
						case 'virtual':
							$this->content_type = 'mixed';

							return $this->content_type;
							break;
						default:
							$this->content_type = 'physical';
							break;
					}
				}
			}
		} else {
			$this->content_type = 'physical';
		}

		return $this->content_type;
	}

	function unserialize($broken) {
		for(reset($broken);$kv=each($broken);) {
			$key=$kv['key'];
			if (gettype($this->$key)!="user function")
			$this->$key=$kv['value'];
		}
	}
	// ------------------------ ICWILSON CREDIT CLASS Gift Voucher Addittion-------------------------------Start
	// amend count_contents to show nil contents for shipping
	// as we don't want to quote for 'virtual' item
	// GLOBAL CONSTANTS if NO_COUNT_ZERO_WEIGHT is true then we don't count any product with a weight
	// which is less than or equal to MINIMUM_WEIGHT
	// otherwise we just don't count gift certificates

	function count_contents_virtual() {  // get total number of items in cart disregard gift vouchers
		$total_items = 0;
		if (is_array($this->contents)) {
			reset($this->contents);
			while (list($products_id, ) = each($this->contents)) {
				$no_count = false;
				$gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
				$gv_result = tep_db_fetch_array($gv_query);
				if (ereg('^GIFT', $gv_result['products_model'])) {
					$no_count=true;
				}
				if (NO_COUNT_ZERO_WEIGHT == 1) {
					$gv_query = tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($products_id) . "'");
					$gv_result=tep_db_fetch_array($gv_query);
					if ($gv_result['products_weight']<=MINIMUM_WEIGHT) {
						$no_count=true;
					}
				}
				if (!$no_count) $total_items += $this->get_quantity($products_id);
			}
		}
		return $total_items;
	}
	// ------------------------ ICWILSON CREDIT CLASS Gift Voucher Addittion-------------------------------End


	//--------------------- Amit added for cost calculation start ------------------------------------------------//
	function calculate_cost() {
		$this->total_virtual = 0; // ICW Gift Voucher System
		$this->total = 0;
		$this->weight = 0;
		if (!is_array($this->contents)) return 0;


		reset($this->contents);
		while (list($products_id, ) = each($this->contents)) {
			$qty = $this->contents[$products_id]['qty'];

			// products price
			$product_query = tep_db_query("select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
			if ($product = tep_db_fetch_array($product_query)) {
				// ICW ORDER TOTAL CREDIT CLASS Start Amendment
				$no_count = 1;
				$gv_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
				$gv_result = tep_db_fetch_array($gv_query);
				if (ereg('^GIFT', $gv_result['products_model'])) {
					$no_count = 0;
				}
				// ICW ORDER TOTAL  CREDIT CLASS End Amendment
				$prid = $product['products_id'];
				$products_tax = tep_get_tax_rate($product['products_tax_class_id']);
				$products_price = 0;//$product['products_price'];
				$products_weight = $product['products_weight'];

				$special_price = tep_get_products_special_price($prid);
				if ($special_price) {
					//$products_price = $special_price;
				}
				$this->total_virtual += tep_add_tax($products_price, $products_tax) * $qty * $no_count;// ICW CREDIT CLASS;
				$this->weight_virtual += ($qty * $products_weight) * $no_count;// ICW CREDIT CLASS;
				$this->total += tep_add_tax($products_price, $products_tax) * $qty;
				$this->weight += ($qty * $products_weight);


			}


			// extra date price

			if ($this->contents[$products_id]['dateattributes_cost'][4] != '')
			{
				$dateprifix = $this->contents[$products_id]['dateattributes'][3];
				$datePrice_cost = $this->contents[$products_id]['dateattributes_cost'][4];

				if ($dateprifix == '-')
				{
					$this->total -= $qty * tep_add_tax($datePrice_cost, $products_tax);
				} else
				{
					$this->total += $qty * tep_add_tax($datePrice_cost, $products_tax);
				}
			}

			//room price
			if($this->contents[$products_id]['roomattributes_cost'][0] != '')
			{
				$roomPrice_cost = $this->contents[$products_id]['roomattributes_cost'][0];
				$this->total += $qty * tep_add_tax($roomPrice_cost, $products_tax);
			}

			// attributes price
			if (isset($this->contents[$products_id]['attributes'])) {
				reset($this->contents[$products_id]['attributes']);
				while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
					$attribute_price_query = tep_db_query("select options_values_price_cost, price_prefix, single_values_price_cost, double_values_price_cost, triple_values_price_cost, quadruple_values_price_cost, kids_values_price_cost  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
					$attribute_price = tep_db_fetch_array($attribute_price_query);
					//amit modified to make sure price on usd
					$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$prid);
					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$attribute_price['single_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['single_values_price_cost'],$tour_agency_opr_currency);
						$attribute_price['double_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['double_values_price_cost'],$tour_agency_opr_currency);
						$attribute_price['triple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price_cost'],$tour_agency_opr_currency);
						$attribute_price['quadruple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price_cost'],$tour_agency_opr_currency);
						$attribute_price['kids_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price_cost'],$tour_agency_opr_currency);
						$attribute_price['options_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['options_values_price_cost'],$tour_agency_opr_currency);
					}
					//amit modified to make sure price on usd
					$att[1] = $attribute_price['single_values_price_cost']; //single
					$att[2] = $attribute_price['double_values_price_cost']; //double
					$att[3] = $attribute_price['triple_values_price_cost']; //triple
					$att[4] = $attribute_price['quadruple_values_price_cost']; //quadr
					$ett = $attribute_price['kids_values_price_cost']; //child Kid

					//amit added to check apply attribute per order start
					$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
					//amit added to check apply attribute per order end
					if(($attribute_price['single_values_price_cost'] > 0 || $attribute_price['double_values_price_cost'] > 0 || $attribute_price['triple_values_price_cost'] > 0 || $attribute_price['quadruple_values_price_cost'] > 0) && $is_per_order_attribute != 1 ){
						$final_chk_price_c = 0;
						if($this->contents[$products_id]['roomattributes'][3] == ''){
							$final_chk_price_c = $attribute_price['single_values_price_cost']*$this->contents[$products_id]['roomattributes'][2];
						}else{
							$tot_nn_roms_chked_c = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

							if($tot_nn_roms_chked_c > 0){

								for($ri=1;$ri<=$tot_nn_roms_chked_c;$ri++){

									$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

									$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

									if($chaild_adult_no_arr[1] == 0){
										$final_chk_price_c += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
									}else{

										if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
											$low_price_formula_option[0] = 2*$att[2];
											$low_price_formula_option[1] = $att[1]+$ett;
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
											$low_price_formula_option[0] = (2*$att[2]) + $ett;
											$low_price_formula_option[1] = 3*$att[3];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
											$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
											$low_price_formula_option[1] = 3*$att[3]+$ett;
											$low_price_formula_option[2] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
										}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
											$low_price_formula_option[0] = (2*$att[2]) + $ett;
											$low_price_formula_option[1] = 3*$att[3];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
											$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
											$low_price_formula_option[1] = 3*$att[3]+$ett;
											$low_price_formula_option[2] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
										}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
											$low_price_formula_option[0] = 3*$att[3]+$ett;
											$low_price_formula_option[1] = 4*$att[4];
											$final_chk_price_c += min($low_price_formula_option[0],$low_price_formula_option[1]);
										}

									}


								}
							}else{
								//tour with no room option but special price added

								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);

								$final_chk_price_c = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;
							}

						}


						if ($attribute_price['price_prefix'] == '-') {
							$this->total -= $qty * tep_add_tax($final_chk_price_c, $products_tax);
						} else {
							$this->total += $qty * tep_add_tax($final_chk_price_c, $products_tax);
						}

					}else{
						//amit added to check apply attribute per order start
						$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
						if($is_per_order_attribute == 1){
							$temp_default_per_count = 1; // per order attribute
						}
						//amit added to check apply attribute per order end
						if ($attribute_price['price_prefix'] == '-') {
							$this->total -= $qty * tep_add_tax($attribute_price['options_values_price_cost']*$temp_default_per_count, $products_tax);
						} else {
							$this->total += $qty * tep_add_tax($attribute_price['options_values_price_cost']*$temp_default_per_count, $products_tax);
						}
					}


				}
			}
		}
	}

	function attributes_price_cost($products_id) {
		$attributes_price = 0;

		if (isset($this->contents[$products_id]['attributes'])) {
			reset($this->contents[$products_id]['attributes']);
			while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
				$attribute_price_query = tep_db_query("select options_values_price_cost, price_prefix, single_values_price_cost, double_values_price_cost, triple_values_price_cost, quadruple_values_price_cost, kids_values_price_cost  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
				$attribute_price = tep_db_fetch_array($attribute_price_query);

				//amit modified to make sure price on usd
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$attribute_price['single_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['single_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['double_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['double_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['triple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['quadruple_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['kids_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price_cost'],$tour_agency_opr_currency);
					$attribute_price['options_values_price_cost'] = tep_get_tour_price_in_usd($attribute_price['options_values_price_cost'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				//new change start
				$att[1] = $attribute_price['single_values_price_cost']; //single
				$att[2] = $attribute_price['double_values_price_cost']; //double
				$att[3] = $attribute_price['triple_values_price_cost']; //triple
				$att[4] = $attribute_price['quadruple_values_price_cost']; //quadr
				$ett = $attribute_price['kids_values_price_cost']; //child Kid
				//need to change logic according to new added field
				//amit added to check apply attribute per order start
				$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
				//amit added to check apply attribute per order end
				if(($attribute_price['single_values_price_cost'] > 0 || $attribute_price['double_values_price_cost'] > 0 || $attribute_price['triple_values_price_cost'] > 0 || $attribute_price['quadruple_values_price_cost'] > 0) && $is_per_order_attribute != 1 ){

					$final_chk_price = 0;
					//check for old data stored on customer bascket start
					if($this->contents[$products_id]['roomattributes'][3] == ''){

						$final_chk_price = $attribute_price['single_values_price_cost']*$this->contents[$products_id]['roomattributes'][2];
					}else{

						//check for old data stored on customer bascket end
						$tot_nn_roms_chked = get_total_room_from_str($this->contents[$products_id]['roomattributes'][3]);

						if($tot_nn_roms_chked > 0){
							for($ri=1;$ri<=$tot_nn_roms_chked;$ri++){

								$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],$ri);

								$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],$ri);

								if($chaild_adult_no_arr[1] == 0){
									$final_chk_price += $chaild_adult_no_arr[0]*$att[$chaild_adult_no_arr[0]];
								}else{

									if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
										$low_price_formula_option[0] = 2*$att[2];
										$low_price_formula_option[1] = $att[1]+$ett;
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = (2*$att[2]) + $ett;
										$low_price_formula_option[1] = 3*$att[3];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}else if($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2'){
										$low_price_formula_option[0] = (2*$att[2]) + 2*$ett;
										$low_price_formula_option[1] = 3*$att[3]+$ett;
										$low_price_formula_option[2] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1],$low_price_formula_option[2]);
									}else if($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1'){
										$low_price_formula_option[0] = 3*$att[3]+$ett;
										$low_price_formula_option[1] = 4*$att[4];
										$final_chk_price += min($low_price_formula_option[0],$low_price_formula_option[1]);
									}
								}
							}
						}else{
							//tour with no room option but special price added

							$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($this->contents[$products_id]['roomattributes'][3],1);
							$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($this->contents[$products_id]['roomattributes'][3],1);
							$final_chk_price = $chaild_adult_no_arr[0]*$att[1] + $chaild_adult_no_arr[1]*$ett;

						}

					}


					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $final_chk_price;
					} else {
						$attributes_price += $final_chk_price;
					}

					//echo (int)$products_id.'-->'.$attributes_price.'<br>';

				}else{

					//amit added to check apply attribute per order start
					$temp_default_per_count = $this->contents[$products_id]['roomattributes'][2];
					if($is_per_order_attribute == 1){
						$temp_default_per_count = 1; // per order attribute
					}
					//amit added to check apply attribute per order end
					if ($attribute_price['price_prefix'] == '-') {
						$attributes_price -= $attribute_price['options_values_price_cost']*$temp_default_per_count;
					} else {
						$attributes_price += $attribute_price['options_values_price_cost']*$temp_default_per_count;
					}
				}
				//new change end

			}
		}

		return $attributes_price;
	}


	//date price
	function final_date_price_cost($products_id) {
		$datePrice_cost = 0;
		if ($this->contents[$products_id]['dateattributes'][3] == '-') {
			$datePrice_cost -= $this->contents[$products_id]['dateattributes_cost'][4];
		} else {
			$datePrice_cost += $this->contents[$products_id]['dateattributes_cost'][4];
		}

		return $datePrice_cost;
	}
	//room price
	function final_room_price_cost($products_id) {
		$roomPrice_cost = 0;
		$roomPrice_cost += $this->contents[$products_id]['roomattributes_cost'][0];

		return $roomPrice_cost;
	}

	function show_total_cost() {
		$this->calculate_cost();

		return $this->total;
	}


	//--------------------- Amit added for cost calculation end ------------------------------------------------//

	/**
	 * 重新取得房间人数等信息字符串，返回gb2312格式的字符
	 * 输出的字符串如：</br>总房间数：3<br>房间一成人数： 2<br>房间一儿童数： 1<br>房间一小计： $810.00<br>房间二成人数： 2<br>房间二儿童数： 2<br>房间二小计： $812.00<br>房间三成人数： 2<br>房间三儿童数： 1<br>房间三小计： $810.00
	 * 或：<br>&nbsp;&nbsp;# 成人 : 20<br>&nbsp;&nbsp;# 小孩 : 10<br>共计 : $1050.00
	 * @param unknown_type $total_room_adult_child_info 格式如 3###2!!0###2!!2###2!!1###或0###2!!1### 的字符串
	 */
	public function re_get_room_info_to_gb2312($total_room_adult_child_info){
		if(!tep_not_null($total_room_adult_child_info))  return '';
		$output_str = '';
		$data = tep_get_room_info_array_from_str($total_room_adult_child_info);
		if((int)$data['room_total']>0){	//有房间
			$output_str .='</br>总房间数：'.$data['room_total'];
			for($i=0, $n=count($data); $i<$n; $i++){
				if((int)$data[$i]['adultNum']){
					$output_str .='<br>'.html_to_db(constant('TEXT_OF_ADULTS_IN_ROOM'.($i+1))).$data[$i]['adultNum'];
					if((int)$data[$i]['childNum']){
						$output_str .='<br>'.html_to_db(constant('TEXT_OF_CHILDREN_IN_ROOM'.($i+1))).$data[$i]['childNum'];
					}
				}
			}
		}else{ //无房间
			if((int)$data['adult_num']>0){
				$output_str .='</br>成人：'.(int)$data['adult_num'];
			}
			if((int)$data['child_num']>0){
				$output_str .='</br>小孩：'.(int)$data['child_num'];
			}
		}
		return $output_str;
	}
}

?>