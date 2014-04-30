<?php
/*
  $Id: ot_buytwogetone.php,v 1.1.1.1 2004/03/04 23:41:16 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
//买二送一优惠模块
  class ot_buytwogetone {
    var $title, $output;

    function ot_buytwogetone() {
      $this->code = 'ot_buytwogetone';
      $this->title = MODULE_ORDER_TOTAL_BUYTWOGETONE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_BUYTWOGETONE_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_BUYTWOGETONE_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_BUYTWOGETONE_SORT_ORDER;

      $this->output = array();
    }

    function process() {
		global $order, $currencies, $cart;
		$t_info = $cart->contents;
		//print_r($t_info);
		$carKeys = array_keys($t_info);
		$value=0;
		$price_preferences = 0;
		for ($i=0, $n=sizeof($carKeys); $i<$n; $i++) {
			$p_id = $carKeys[$i];
			$prod_sql = tep_db_query('SELECT products_id,products_single,products_double,products_triple,products_quadr,products_surcharge FROM `products` WHERE products_id="'.(int)$p_id.'" AND products_class_id="4" ');
			$prod_row = tep_db_fetch_array($prod_sql);
			if((int)$prod_row['products_id']){
				$roomattributes_total = $t_info[$p_id]['roomattributes'][0];
				$people_num_msn = $t_info[$p_id]['roomattributes'][3];
				$people_array = explode('###',$people_num_msn);
				if($people_array[0]>=1){	//必须是有房间才能参加买二送一活动
					for($ii=1; $ii<(count($people_array)-1); $ii++){
						$room_array = explode('!!', $people_array[$ii]);
						$room_people_num = $room_array[0]+$room_array[1];
						if($room_people_num==3){
							//第$II房间实际价格3人（总价）=双人/间单价X2 + 附加费+门票等费用
							$tmp_price = $prod_row['products_double'] *2 + $prod_row['products_surcharge'];
						}else if($room_people_num==4){
							//第$II房间实际价格4人（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
							$tmp_price = $prod_row['products_double'] *2 + $prod_row['products_triple'] + $prod_row['products_surcharge'];
						}else if($room_people_num==2){
							$tmp_price = $prod_row['products_double'] *2;
						}else{
							$tmp_price = $prod_row['products_single'];
						}
						//howard copy amit added to add extra 3% if agency is L&L Travel start
						$pro_agency_info_array = tep_get_tour_agency_information((int)$prod_row['products_id']);
						if($pro_agency_info_array['final_transaction_fee'] > 0){								
							$tmp_price = tep_get_total_fares_includes_agency($tmp_price,$pro_agency_info_array['final_transaction_fee']);									
						}
						//howard copy amit added to add extra 3% if agency is L&L Travel start
						$price_preferences += $tmp_price; 
					}

				}
				if((int)$price_preferences){
					$value += $roomattributes_total - $price_preferences;
				}
			}
		}
		
		
		if($value>0){
			$order->info['total'] = $order->info['total'] - $value;
			$this->output[] = array('title' => $this->title . ':',
									'text' =>'<span style="color:#FF0000">-'.$currencies->format($value, true, $order->info['currency'], $order->info['currency_value']).'</sapn>',
									'value' => $value);
		}

    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_BUYTWOGETONE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_BUYTWOGETONE_STATUS', 'MODULE_ORDER_TOTAL_BUYTWOGETONE_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display', 'MODULE_ORDER_TOTAL_BUYTWOGETONE_STATUS', 'true', 'Do you want to display the   value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_BUYTWOGETONE_SORT_ORDER', '792', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>