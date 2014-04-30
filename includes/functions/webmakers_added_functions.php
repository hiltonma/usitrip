<?php
/*
 * WebMakers.com Added: Additional Functions Written by Linda McGrath
 * osCOMMERCE@WebMakers.com http://www.thewebmakerscorner.com osCommerce, Open
 * Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2002
 * osCommerce Released under the GNU General Public License
 */

// //
// Verify Free Shipping or Regular Shipping modules to show
function tep_get_free_shipper($chk_shipper) {
	global $cart;
	$show_shipper = false;
	switch (true) {
		case (($chk_shipper == 'freeshipper' and $cart->show_weight() == 0)):
			$show_shipper = true;
			break;
		case (($chk_shipper != 'freeshipper' and $cart->show_weight() == 0)):
			$show_shipper = false;
			break;
		
		case (($chk_shipper == 'freeshipper' and $cart->show_weight() != 0)):
			$show_shipper = false;
			break;
		case (($chk_shipper != 'freeshipper' and $cart->show_weight() != 0)):
			$show_shipper = true;
			break;
		default:
			$show_shipper = false;
			break;
	}
	
	return $show_shipper;
}

// //
// Verify Free Charge or Regular Payment methods to show
function tep_get_free_charger($chk_module) {
	global $cart;
	
	$show_it = false;
	switch (true) {
		case (($chk_module == 'freecharger' and ($cart->show_total() == 0 and $cart->show_weight() == 0))):
			$show_it = true;
			break;
		case (($chk_module != 'freecharger' and ($cart->show_total() == 0 and $cart->show_weight() == 0))):
			$show_it = false;
			break;
		
		case (($chk_module == 'freecharger' and ($cart->show_total() != 0 or $cart->show_weight() != 0))):
			$show_it = false;
			break;
		case (($chk_module != 'freecharger' and ($cart->show_total() != 0 or $cart->show_weight() != 0))):
			$show_it = true;
			break;
	}
	
	return $show_it;
}

// //
// //
// Display Price Retail
// Specials and Tax Included
function tep_get_products_display_price($products_id, $prefix_tag = false, $value_price_only = false, $include_units = true) {
	global $currencies;
	$product_check_query = tep_db_query("select products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call, product_is_showroom_only from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
	$product_check = tep_db_fetch_array($product_check_query);
	
	$display_price = '';
	$value_price = 0;
	// Price is either normal or priced by attributes
	if ($product_check['products_priced_by_attribute']) {
		$attributes_priced = tep_get_products_base_price($products_id, $include_units);
		$display_price = $currencies->display_price(($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup'] / 100))), '', 1);
		$value_price = ($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup'] / 100)));
	} else {
		if ($product_check['products_price'] != 0) {
			$display_price = $currencies->display_price($product_check['products_price'], tep_get_tax_rate($product_check['products_tax_class_id']), 1);
		}
	}
	
	// If a Special, Show it
	if ($add_special = tep_get_products_special_price($products_id)) {
		// $products_price = '<s>' .
		// $currencies->display_price($product_info_values['products_price'],
		// tep_get_tax_rate($product_info_values['products_tax_class_id'])) .
		// '</s> <span class="productSpecialPrice">' .
		// $currencies->display_price($new_price,
		// tep_get_tax_rate($product_info_values['products_tax_class_id'])) .
		// '</span>';
		$display_price = '<s>' . $display_price . '</s> <span class="productSpecialPrice"> ' . $currencies->display_price($add_special, tep_get_tax_rate($product_check['products_tax_class_id']), '1') . '</span> ';
	}
	
	// If Free, Show it
	if ($product_check['product_is_free']) {
		if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON == '0') {
			$free_tag = ' ' . PRODUCTS_PRICE_IS_FREE_TEXT;
		} else {
			$free_tag = ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_FREE_IMAGE, PRODUCTS_PRICE_IS_FREE_TEXT);
		}
		
		if ($product_check['products_price'] != 0) {
			$display_price = '<s>' . $display_price . '</s>' . '<br /><span class="ProductIsFree">' . $free_tag . '</span>';
		} else {
			$display_price = '<span class="ProductIsFree">' . $free_tag . '</span>';
		}
	} // FREE
	

	// If Call for Price, Show it
	if ($product_check['product_is_call']) {
		if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON == '0') {
			$call_tag = ' ' . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT;
		} else {
			$call_tag = ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_IMAGE, PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT);
		}
		
		if ($product_check['products_price'] != 0) {
			$display_price = '<s>' . $display_price . '</s> ' . $call_tag;
		} else {
			$display_price = $call_tag;
		}
	} // CALL
	

	// If Showroom, Show it
	if ($product_check['product_is_showroom_only']) {
		if (PRODUCTS_PRICE_IS_SHOWROOM_IMAGE_ON == '0') {
			$showroom_only_tag = ' ' . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT;
		} else {
			$showroom_only_tag = ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_IMAGE, PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT);
		}
		
		if ($product_check['products_price'] != 0) {
			// $display_price='<s>' . $display_price . '</s>' . '<br /><span
			// class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
			$display_price = $display_price . '<br /><span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
		} else {
			$display_price = '<span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
		}
	} // FREE
	

	if ($value_price_only) {
		return $value_price;
	} else {
		if ($display_price) {
			return ($prefix_tag ? $prefix_tag . ' ' : '') . $display_price;
		} else {
			return false;
		}
	}
}

function tep_get_order_final_price_of_oid($orders_id) {
	$totalprice = '';
	$orders_produts_query_raw = "select * from " . TABLE_ORDERS_TOTAL . " where  orders_id =" . $orders_id . "  and  class = 'ot_total'";
	$orders_query = tep_db_query($orders_produts_query_raw);
	if (tep_db_num_rows($orders_query) > 0) {
		$orders = tep_db_fetch_array($orders_query);
		$totalprice = $orders['value'];
	}
	
	return $totalprice;
}
// amit added to get product type from product id start
function tep_get_product_type_of_product_id($products_id) {
	$product_type_query = tep_db_query("select products_type from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
	$product_type_info = tep_db_fetch_array($product_type_query);
	// amit added for fixed allow guest weight for airplan(3) too start
	if ($product_type_info['products_type'] == 3) {
		$product_type_info['products_type'] = 2;
	}
	// amit added for fixed allow guest weight for airplan too end
	return (int) $product_type_info['products_type'];
}

// amit added to get product type from product id end


// amit added to check departure valida month start
function check_valid_available_date($startDate, $checkDate, $endDate) {
	// echo $startDate = date('m-d',$startDate);
	// echo $checkDate=date('m-d',$checkDate);
	// echo $endDate=date('m-d',$endDate);
	if (strlen($endDate) == 10) {
		
		if (tep_get_compareDates_mm_dd_yyyy($checkDate, $startDate) == "valid" && tep_get_compareDates_mm_dd_yyyy($endDate, $checkDate) == "valid") {
			return "valid";
		} else {
			return "invalid";
		}
	} else {
		
		if ($startDate <= $checkDate && $checkDate <= $endDate) {
			return "valid";
		} else {
			return "invalid";
		}
	}
}

function check_valid_available_date_endate($endDate) {
	// echo $startDate = date('m-d',$startDate);
	// echo $checkDate=date('m-d',$checkDate);
	// echo $endDate=date('m-d',$endDate);
	$checkDate = date("m-d-Y");
	if (strlen($endDate) == 10) {
		
		if (tep_get_compareDates_mm_dd_yyyy($endDate, $checkDate) == "valid") {
			return "valid";
		} else {
			return "invalid";
		}
	} else {
		
		if ($checkDate <= $endDate) {
			return "valid";
		} else {
			return "invalid";
		}
	}
}

function tep_get_compareDates_mm_dd_yyyy($date1, $date2) {
	$date1_array = explode("-", $date1);
	$date2_array = explode("-", $date2);
	$timestamp1 = mktime(0, 0, 0, $date1_array[0], $date1_array[1], $date1_array[2]);
	$timestamp2 = mktime(0, 0, 0, $date2_array[0], $date2_array[1], $date2_array[2]);
	if ($timestamp1 > $timestamp2 || $timestamp1 == $timestamp2) {
		$ret_str = 'valid';
	} else {
		$ret_str = 'invalid';
	}
	return $ret_str;
}
// -----Amit added to get regular_irregular section detail Start
function regu_irregular_section_detail_short($ppproducts_id) {
	$product_query_sql = "SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date, str_to_date(operate_start_date,'%m-%d-%Y') as sortstartdate, str_to_date(operate_end_date,'%m-%d-%Y') as sortenddate FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int) $ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY producttype desc, sortstartdate, sortenddate";
	$product_query = tep_db_query($product_query_sql);
	$regular_row_cnt = tep_db_num_rows($product_query);
	
	while ($product[] = tep_db_fetch_array($product_query)) {
	}
	
	return $product;
}

/**
 * 取得有指定日期的假日价格与没有指定日期的价日假格日期区间与每个区间有几个数据
 * @param unknown_type $ppproducts_id
 * @return multitype:
 */
function regu_irregular_section_detail($ppproducts_id) {
	$product_query_sql = "SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int) $ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	$product_query = tep_db_query($product_query_sql);
	$regular_row_cnt = tep_db_num_rows($product_query);
	
	while ($product[] = tep_db_fetch_array($product_query)) {
	}
	
	return $product;
}

/**
 * 取得有指定日期的假日价格与没有指定日期的价日假格日期区间段数
 * @param unknown_type $ppproducts_id
 * @return number
 */
function regu_irregular_section_numrow($ppproducts_id) {
	// products_start_day 星期几。1表示星期日，7表达星期六,0表示不明确星期几。范围0-7  
	// operate_start_date 开始日期
	// operate_end_date   结束日期
	// products_start_day 为空或者零的 好像都有指定明确日期 available_date
	$product_query_sql = "SELECT  case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int) $ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	//print_r($product_query_sql);
	$product_query = tep_db_query($product_query_sql);
	$regular_row_cnt = tep_db_num_rows($product_query);
	
	return $regular_row_cnt;
}

// -----Amit added to get regular_irregular section detail End
function tep_get_irreg_products_duration_description($products_id, $operate_start_date, $operate_end_date) {
	$irregulr_description_display_system = '';
	
	// take date from regular field start
	$the_products_duration_description_query = tep_db_query("select products_durations_description  from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id='" . $products_id . "' and operate_start_date= '" . $operate_start_date . "' and operate_end_date= '" . $operate_end_date . "'");
	
	if ($the_products_duration_description = tep_db_fetch_array($the_products_duration_description_query)) {
		
		$irregulr_description_display_system = $the_products_duration_description['products_durations_description'] . ' ';
	}
	// take date from regular field end
	

	$available_query_sql = "select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = " . (int) $products_id . " and  operate_start_date='" . $operate_start_date . "' and  operate_end_date='" . $operate_end_date . "'  order by available_date";
	$available_query = tep_db_query($available_query_sql);
	$in_cnt_no = 0;
	while ($available_result = tep_db_fetch_array($available_query)) {
		$y = substr($available_result['available_date'], 0, 4);
		$m = substr($available_result['available_date'], 5, 2);
		$d = substr($available_result['available_date'], 8, 2);
		
		// $from2 = mktime (date ("H"), date ("i"), date ("s"), date($m), date
		// ($d), date($y));
		$from2 = @mktime(date("H"), date("i"), date("s"), date($m), date($d), date($y));
		$from1 = date("Y-m-d (D)", $from2);
		$formval = date("Y-m-d", $from2);
		// amit addedd to modify for specific month start
		$isvaliddatecheck = '';
		$startDate = $operate_start_date;
		$checkDate = date("m-d-Y", $from2);
		$endDate = $operate_end_date;
		
		$isvaliddatecheck = check_valid_available_date($startDate, $checkDate, $endDate);
		// amit added to modify for specific month end
		if ($isvaliddatecheck == "valid" && $formval >= date('Y-m-d')) {
			$in_cnt_no ++;
			// $irregulr_description_display_system .= date("m/d/y", $from2).',
			// ';
			$irregulr_description_display_system .= db_to_html(date("Y年m月d日", $from2) . '、');
		}
	}
	
	if ($in_cnt_no > 0) {
		$irregulr_description_display_system = substr($irregulr_description_display_system, 0, -2) . '<br />';
		return $irregulr_description_display_system;
	} else {
		return '';
	}
}

function tep_get_language_stringmonth($i) {
	global $language;
	
	if ($language == 'tchinese' || $language == 'schinese') {
		// return $language.$strmonth;
		if ($i == "1") {
			return TEXT_HEADING_MONTH_NUM_1;
		} else 
			if ($i == "2") {
				return TEXT_HEADING_MONTH_NUM_2;
			} else 
				if ($i == "3") {
					return TEXT_HEADING_MONTH_NUM_3;
				} else 
					if ($i == "4") {
						return TEXT_HEADING_MONTH_NUM_4;
					} else 
						if ($i == "5") {
							return TEXT_HEADING_MONTH_NUM_5;
						} else 
							if ($i == "6") {
								return TEXT_HEADING_MONTH_NUM_6;
							} else 
								if ($i == "7") {
									return TEXT_HEADING_MONTH_NUM_7;
								} else 
									if ($i == "8") {
										return TEXT_HEADING_MONTH_NUM_8;
									} else 
										if ($i == "9") {
											return TEXT_HEADING_MONTH_NUM_9;
										} else 
											if ($i == "10") {
												return TEXT_HEADING_MONTH_NUM_10;
											} else 
												if ($i == "11") {
													return TEXT_HEADING_MONTH_NUM_11;
												} else {
													return TEXT_HEADING_MONTH_NUM_12;
												}
	} else {
		return strftime('%B', mktime(0, 0, 0, $i, 1, 2000));
	}
}

function tep_get_total_of_room($i) {
	// if(defined('TEXT_TOTLE_OF_ROOM'.$i) && $i>=1 && $i<=15 ){
	if (defined('TEXT_TOTLE_OF_ROOM' . $i)) {
		return constant('TEXT_TOTLE_OF_ROOM' . $i);
	}
	/*
	 * if($i == "1"){ return TEXT_TOTLE_OF_ROOM1; }else if($i == "2"){ return
	 * TEXT_TOTLE_OF_ROOM2; }else if($i == "3"){ return TEXT_TOTLE_OF_ROOM3;
	 * }else if($i == "4"){ return TEXT_TOTLE_OF_ROOM4; }else if($i == "5"){
	 * return TEXT_TOTLE_OF_ROOM5; }else if($i == "6"){ return
	 * TEXT_TOTLE_OF_ROOM6; }
	 */
}

function tep_get_total_of_adult_in_room($i) {
	if (defined('TEXT_OF_ADULTS_IN_ROOM' . $i)) {
		return constant('TEXT_OF_ADULTS_IN_ROOM' . $i);
	}
	/*
	 * if($i == "1"){ return TEXT_OF_ADULTS_IN_ROOM1; }else if($i == "2"){
	 * return TEXT_OF_ADULTS_IN_ROOM2; }else if($i == "3"){ return
	 * TEXT_OF_ADULTS_IN_ROOM3; }else if($i == "4"){ return
	 * TEXT_OF_ADULTS_IN_ROOM4; }else if($i == "5"){ return
	 * TEXT_OF_ADULTS_IN_ROOM5; }else if($i == "6"){ return
	 * TEXT_OF_ADULTS_IN_ROOM6; }
	 */
}

function tep_get_total_of_children_in_room($i) {
	if (defined('TEXT_OF_CHILDREN_IN_ROOM' . $i)) {
		return constant('TEXT_OF_CHILDREN_IN_ROOM' . $i);
	}
	
	/*
	 * if($i == "1"){ return TEXT_OF_CHILDREN_IN_ROOM1; }else if($i == "2"){
	 * return TEXT_OF_CHILDREN_IN_ROOM2; }else if($i == "3"){ return
	 * TEXT_OF_CHILDREN_IN_ROOM3; }else if($i == "4"){ return
	 * TEXT_OF_CHILDREN_IN_ROOM4; }else if($i == "5"){ return
	 * TEXT_OF_CHILDREN_IN_ROOM5; }else if($i == "6"){ return
	 * TEXT_OF_CHILDREN_IN_ROOM6; }
	 */
}
// 取得$i床型标题
function tep_get_bed_of_room($i) {
	if (defined('TEXT_BED_OF_ROOM' . $i)) {
		return constant('TEXT_BED_OF_ROOM' . $i);
	}
	/*
	 * if($i == "1"){ return TEXT_BED_OF_ROOM1; }else if($i == "2"){ return
	 * TEXT_BED_OF_ROOM2; }else if($i == "3"){ return TEXT_BED_OF_ROOM3; }else
	 * if($i == "4"){ return TEXT_BED_OF_ROOM4; }else if($i == "5"){ return
	 * TEXT_BED_OF_ROOM5; }else if($i == "6"){ return TEXT_BED_OF_ROOM6; }
	 */
}

function tep_get_bed_name($n) {
	if ($n == "0") {
		return TEXT_BED_STANDARD;
	}
	if ($n == "1") {
		return TEXT_BED_KING;
	}
	if ($n == "2") {
		return TEXT_BED_QUEEN;
	}
}

// amit added to check departure date start
function tep_get_compareDates($date1, $date2) {
	$date1_array = explode("-", $date1);
	$date2_array = explode("-", $date2);
	$timestamp1 = mktime(0, 0, 0, $date1_array[1], $date1_array[2], $date1_array[0]);
	$timestamp2 = mktime(0, 0, 0, $date2_array[1], $date2_array[2], $date2_array[0]);
	if ($timestamp1 > $timestamp2 || $timestamp1 == $timestamp2) {
		$ret_str = 'valid';
	} else {
		$ret_str = 'invalid';
	}
	return $ret_str;
}

// amit added to check departure date end
	

// amit added to check guest by parse string start
function tep_get_rooms_adults_childern($roomsinfo_string, $room, $customer_type) {
	$parse_child = tep_get_total_of_children_in_room($room);
	$parse_adult = tep_get_total_of_adult_in_room($room);
	
	switch ($customer_type) {
		case 'adult':
			
			if (preg_match('/<br />- ' . $parse_adult . ' ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'children':
			if (preg_match('/<br />- ' . $parse_child . ' ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
	}
	
	return (int) $rtn_val;
}

function tep_get_no_adults_childern($roomsinfo_string, $customer_type) {
	switch ($customer_type) {
		case 'adult':
			
			if (preg_match('/# adults : ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
		case 'children':
			if (preg_match('/# of children : ([0-9]+)/', $roomsinfo_string, $m)) {
				$rtn_val = $m[1];
			}
			break;
	}
	
	return (int) $rtn_val;
}

function tep_get_total_nos_of_rooms($roomsinfo_string) {
	if (preg_match('/- ' . TEXT_TOTAL_OF_ROOMS . '([0-9]+)/', $roomsinfo_string, $m)) {
		$rtn_val = $m[1];
	}
	
	return (int) $rtn_val;
}

// amit added to check guest by parse string end
	// amit added for get new design backgound and footer start
function tep_get_design_body_header($headingtitle = 'usitrip', $isshowbardcurm = 0) {
	global $breadcrumb, $content;
	$total_return_str = '<table style="margin-top:8px;margin-left:20px;margin-right:20px;"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="pageHeading" width="100%" style="background:url(' . DIR_WS_TEMPLATE_IMAGES . 'nav/user_bg10.gif);color:#fff;height:33px;">' . $headingtitle . '</td><td style="background:url(' . DIR_WS_TEMPLATE_IMAGES . 'nav/user_bg10.gif);height:33px;color:#fff;"  nowrap="nowrap" class="pageHeading" align="right">&nbsp;';
	/*
	 * if($isshowbardcurm==1){ $total_return_str .= '<table width="100%"
	 * border="0" cellspacing="0" cellpadding="0"> <tr> <td class="main"
	 * nowrap="nowrap">'. $breadcrumb->trail(' &raquo; ').'</td> </tr> </table>
	 * '; }
	 */
	
	$total_return_str .= '</td>
  </tr>';
	
	if ($content == 'create_account' || $content == 'login') {
		$total_return_str .= '
  <tr>
    <td style="padding-bottom:8px; height:36px;" colspan="2"></td>
  </tr>';
	}
	
	$total_return_str .= '
  <tr>
    <td class="mainbodybackground" colspan="2" style="border:1px solid #AED5FF;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr><td height="15"></td></tr>
			     <tr>
					 <td width="2%"></td>
					 <td width="96%" class="main" >';
	
	return $total_return_str;
}

function tep_get_design_body_footer() {
	$total_return_str_f = '</td><td width="2%"></td></tr><tr><td height="30"></td></tr></table></td></tr></table>';
	
	return $total_return_str_f;
}

// amit added for get new design backgound and footer end


// howard changed English Date to China Date
function tep_get_display_operate_info($products_id, $toarray = 0) {
	$operate = '';
	
	$pattern = array ();
	$replacement = array ();
	$pattern[0] = '/Jan/';
	$replacement[0] = TEXT_HEADING_MONTH_NUM_1;
	$pattern[1] = '/Feb/';
	$replacement[1] = TEXT_HEADING_MONTH_NUM_2;
	$pattern[2] = '/Mar/';
	$replacement[2] = TEXT_HEADING_MONTH_NUM_3;
	$pattern[3] = '/Apr/';
	$replacement[3] = TEXT_HEADING_MONTH_NUM_4;
	$pattern[4] = '/May/';
	$replacement[4] = TEXT_HEADING_MONTH_NUM_5;
	$pattern[5] = '/Jun/';
	$replacement[5] = TEXT_HEADING_MONTH_NUM_6;
	$pattern[6] = '/Jul/';
	$replacement[6] = TEXT_HEADING_MONTH_NUM_7;
	$pattern[7] = '/Aug/';
	$replacement[7] = TEXT_HEADING_MONTH_NUM_8;
	$pattern[8] = '/Sep/';
	$replacement[8] = TEXT_HEADING_MONTH_NUM_9;
	$pattern[9] = '/Oct/';
	$replacement[9] = TEXT_HEADING_MONTH_NUM_10;
	$pattern[10] = '/Nov/';
	$replacement[10] = TEXT_HEADING_MONTH_NUM_11;
	$pattern[11] = '/Dec/';
	$replacement[11] = TEXT_HEADING_MONTH_NUM_12;
	
	$pattern[12] = '/(\d{4})/';
	$replacement[12] = '$1' . TEXT_YEAR;
	
	$pattern[13] = '/(\d+)st/';
	$replacement[13] = '$1' . TEXT_DAY;
	$pattern[14] = '/(\d+)th/';
	$replacement[14] = '$1' . TEXT_DAY;
	$pattern[15] = '/(\d+)nd/';
	$replacement[15] = '$1' . TEXT_DAY;
	$pattern[16] = '/(\d+)rd/';
	$replacement[16] = '$1' . TEXT_DAY;
	
	$num_of_sections = regu_irregular_section_numrow($products_id);
	if ($num_of_sections > 0) {
		$regu_irregular_array = regu_irregular_section_detail_short($products_id);
		
		foreach ($regu_irregular_array as $k => $v) {
			if (is_array($v)) {
				
				$tourcatetype = $regu_irregular_array[$k]['producttype'];
				$opestartdate = $regu_irregular_array[$k]['operate_start_date'];
				$opeenddate = $regu_irregular_array[$k]['operate_end_date'];
				$isvaliddatecheck = check_valid_available_date_endate($opeenddate);
				if ($isvaliddatecheck == "valid") {
					$day1 = '';
					if ($tourcatetype == 1) { // regular your
						$operator_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = " . $products_id . "  and  operate_start_date='" . $opestartdate . "' and  operate_end_date='" . $opeenddate . "'  order by products_start_day");
						$numofrowregday = tep_db_num_rows($operator_query);
						if ($numofrowregday == 7) {
							$opestartdayarray = explode('-', $opestartdate);
							$operatetomodistart = strftime('%b', mktime(0, 0, 0, $opestartdayarray[0], 15)) . date("jS", mktime(0, 0, 0, 0, $opestartdayarray[1], 0));
							$operatetomodistart = preg_replace($pattern, $replacement, $operatetomodistart);
							
							$opeenddayarray = explode('-', $opeenddate);
							$operatetomodiend = strftime('%b', mktime(0, 0, 0, $opeenddayarray[0], 15)) . date("jS", mktime(0, 0, 0, 0, $opeenddayarray[1], 0));
							$operatetomodiend = preg_replace($pattern, $replacement, $operatetomodiend);
							
							if ($opestartdate == '01-01' && $opeenddate == '12-31') {
								$operate .= TEXT_DAILY . '<br />';
							} else {
								$operate .= preg_replace($pattern, $replacement, $opestartdayarray[2]) . $operatetomodistart . ' - ' . preg_replace($pattern, $replacement, $opeenddayarray[2]) . $operatetomodiend . ' : ' . TEXT_DAILY . '<br />';
								// $operate .= $opestartdate.':
								// '.TEXT_DAILY.'<br />';
							}
						} else {
							
							while ($operator_result = tep_db_fetch_array($operator_query)) {
								if ($operator_result['products_start_day'] == 1) {
									$day1 .= SUNDAY . '/';
								}
								if ($operator_result['products_start_day'] == 2) {
									$day1 .= MONDAY . '/';
								}
								if ($operator_result['products_start_day'] == 3) {
									$day1 .= TUESDAY . '/';
								}
								if ($operator_result['products_start_day'] == 4) {
									$day1 .= WEDNESDAY . '/';
								}
								if ($operator_result['products_start_day'] == 5) {
									$day1 .= THURSDAY . '/';
								}
								if ($operator_result['products_start_day'] == 6) {
									$day1 .= FRIDAY . '/';
								}
								if ($operator_result['products_start_day'] == 7) {
									$day1 .= SATURDAY . '/';
								}
							}
							
							$opestartdayarray = explode('-', $opestartdate);
							$operatetomodistart = strftime('%b', mktime(0, 0, 0, $opestartdayarray[0], 15)) . date("jS", mktime(0, 0, 0, 0, $opestartdayarray[1], 0));
							
							$operatetomodistart = preg_replace($pattern, $replacement, $operatetomodistart);
							
							$opeenddayarray = explode('-', $opeenddate);
							$operatetomodiend = strftime('%b', mktime(0, 0, 0, $opeenddayarray[0], 15)) . date("jS", mktime(0, 0, 0, 0, $opeenddayarray[1], 0));
							$operatetomodiend = preg_replace($pattern, $replacement, $operatetomodiend);
							
							if ($opestartdate == '01-01' && $opeenddate == '12-31') {
								$operate .= $day1 . '<br />';
							} else {
								// echo 'here';
								$operate .= preg_replace($pattern, $replacement, $opestartdayarray[2]) . $operatetomodistart . ' - ' . preg_replace($pattern, $replacement, $opeenddayarray[2]) . $operatetomodiend . ' ' . ': ' . $day1 . '<br />';
								// echo $operate;
							}
						}
					} else { // irregular tours
						

						// echo 'here';
						$irredis_select_description = tep_get_irreg_products_duration_description($products_id, $opestartdate, $opeenddate);
						$operate .= $irredis_select_description . '<br />';
					}
				} // amit added to check if date range is valid
			}
		}
	}
	$operate = preg_replace('/(\d{2}\/\d{2}\/\d{2,4},*.*)/', '<span style="font-size:11px">[' . MONTH_DAY_YEAR . ']</span> $1', $operate);
	$operate = str_replace('/<br />', '<br />', $operate);
	$operate = str_replace('<br /><br />', '<br />', $operate);
	if ($toarray) {
		$operate = explode("<br />", $operate);
		if ($operate[count($operate) - 1] == '')
			unset($operate[count($operate) - 1]);
		foreach ($operate as $k => $v) {
			if (trim($v) == '')
				unset($operate[$k]);
		}
		if (!is_array($operate))
			$operate = array ();
	}
	return $operate;
}
// howard changed English Date to China Date end


// amit added to get subcategory id's start //including current categort to
function tep_get_category_subcategories_ids($category_id) {
	/*
	 * $child_category_query = tep_db_query("select categories_id from " .
	 * TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
	 * $stored_cat_done_ids = "'" . (int)$category_id . "', ";
	 * while($child_category = tep_db_fetch_array($child_category_query)){
	 * $stored_cat_done_ids .= "'" . (int)$child_category['categories_id'] . "',
	 * "; }
	 */
	$stored_cat_done_ids = "'" . (int) $category_id . "', ";
	$subcategories_array = array ();
	tep_get_subcategories($subcategories_array, $category_id);
	for ($i = 0, $n = sizeof($subcategories_array); $i < $n; $i ++) {
		$stored_cat_done_ids .= "'" . (int) $subcategories_array[$i] . "', ";
	}
	
	$stored_cat_done_ids = substr($stored_cat_done_ids, 0, -2);
	return $stored_cat_done_ids;
}

// amit added to get sub category id's end


/**
 * 验证邮箱地址是否正确，返回true或false
 *
 * @param unknown_type $email
 * @return boolean
 */
function is_CheckvalidEmail($email, $type = 'fast') {
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex) {
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex + 1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} else 
			if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else 
				if ($local[0] == '.' || $local[$localLen - 1] == '.') {
					// local part starts or ends with '.'
					$isValid = false;
				} else 
					if (preg_match('/\\.\\./', $local)) {
						// local part has two consecutive dots
						$isValid = false;
					} else 
						if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
							// character not valid in domain part
							$isValid = false;
						} else 
							if (preg_match('/\\.\\./', $domain)) {
								// domain part has two consecutive dots
								$isValid = false;
							} else 
								if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
									// character not valid in local part unless
									// local part is quoted
									if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
										$isValid = false;
									}
								}
		
		if ($type == 'fast') {
			return $isValid;
		}
		if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}

function tep_get_categori_id_from_url($categories_urlname) {
	/*
	 * global $languages_id; $category_query = tep_db_query("select
	 * cd.categories_name, c.categories_urlname, c.categories_id, c.parent_id
	 * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
	 * where c.categories_id = cd.categories_id and (c.categories_urlname = '" .
	 * $categories_urlname . "' || c.categories_urlname = '" .
	 * $categories_urlname . "/' ) and cd.language_id = '" . $languages_id .
	 * "'"); $category = tep_db_fetch_array($category_query);
	 */
	$category = MCache::search_categories('categories_urlname', array (
			$categories_urlname,
			$categories_urlname . '/' 
	), 'categories_name,categories_urlname,categories_id,parent_id');
	
	return $category;
}

function tep_get_paths($categories_array = '', $parent_id = '0', $indent = '', $path = '', $level = '') {
	global $languages_id;
	if (!is_array($categories_array))
		$categories_array = array ();
	
	$where_extra_ignor_category = " and c.categories_urlname != 'tours-by-departure-city/' and c.categories_urlname not like '%-tour-packages/' ";
	
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name,c.categories_urlname from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int) $parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int) $languages_id . "' " . $where_extra_ignor_category . " order by sort_order, cd.categories_name");
	
	if ((sizeof($categories_array) < 1) && tep_db_num_rows($categories_query) > 0)
		$categories_array[] = array (
				'id' => '',
				'text' => TEXT_DROP_DOWN_SELECT_REGION 
		);
	
	while ($categories = tep_db_fetch_array($categories_query)) {
		if ($parent_id == '0') {
			$categories_array[] = array (
					'id' => str_replace('/', '', $categories['categories_urlname']),
					'text' => $indent . str_replace(' Tour', '', str_replace(' Tours', '', db_to_html($categories['categories_name']))) 
			);
		} else {
			$categories_array[] = array (
					'id' => str_replace('/', '', $categories['categories_urlname']),
					'text' => $indent . str_replace(' Tour', '', str_replace(' Tours', '', db_to_html($categories['categories_name']))) 
			);
		}
		
		if (($categories['categories_id'] != $parent_id) && ($level != 1)) {
			$this_path = $path;
			if ($parent_id != '0')
				$this_path = $path . $parent_id . '_';
			$categories_array = tep_get_paths($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;', $this_path);
		}
	}
	
	return $categories_array;
}

function get_total_room_from_str($str) {
	$get_room_no_array = explode('###', $str);
	return $get_room_no_array[0];
}

function tep_get_room_total_persion_from_str($str, $room_no) {
	$get_room_no_array = explode('###', $str);
	$adu_and_chile_val_array = explode('!!', $get_room_no_array[$room_no]);
	$total_ad_ch_val = $adu_and_chile_val_array[0] + $adu_and_chile_val_array[1];
	return $total_ad_ch_val;
}

function tep_get_room_adult_child_persion_on_room_str($str, $room_no) {
	$get_room_no_array = explode('###', $str);
	$adu_and_chile_val_array = explode('!!', $get_room_no_array[$room_no]);
	
	$total_ad_ch_val_array[0] = $adu_and_chile_val_array[0];
	$total_ad_ch_val_array[1] = $adu_and_chile_val_array[1];
	
	return $total_ad_ch_val_array;
}

/**
 * 将房间信息字符串转成数组
 *
 * @param unknown_type $str 为房间信息的字符串 3###2!!0###2!!2###2!!1###或0###2!!1###
 * @return array
 * @author Howard
 */
function tep_get_room_info_array_from_str($str) {
	if (!tep_not_null($str))
		return false;
	$data = array ();
	$get_room_no_array = explode('###', $str);
	// 房间总数
	$data['room_total'] = (int) $get_room_no_array[0];
	// 各个房间成人和小孩数据
	if (!(int) $data['room_total']) { // 无房间
		$_tmp_array = explode('!!', $get_room_no_array[1]);
		$data['adult_num'] = (int) $_tmp_array[0];
		$data['child_num'] = (int) $_tmp_array[1];
	} else { // 有房间
		for ($i = 1, $n = count($get_room_no_array); $i < $n; $i ++) {
			$_tmp_array = array ();
			if (tep_not_null($get_room_no_array[$i])) {
				$_tmp_array = explode('!!', $get_room_no_array[$i]);
				$data[] = array (
						'adultNum' => (int) $_tmp_array[0],
						'childNum' => (int) $_tmp_array[1] 
				);
			}
		}
	}
	return $data;
}

function tep_full_copy($source, $target, $products_id, $insert_id, $customers_name, $customers_email, $front_title, $front_desc) {
	$return_files = '';
	if (is_dir($source)) {
		@mkdir($target);
		
		$d = dir($source);
		
		$count_picture = 0;
		/*
		 * echo $front_title; echo $front_desc; exit;
		 */
		$return_files = array ();
		$front_title_db = explode('/--title--/', $front_title);
		$front_desc_db = explode('/--desc--/', $front_desc);
		while (FALSE !== ($entry = $d->read())) {
			$insert_id = rand(1000, 3000);
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			
			$Entry = $source . '/' . $entry;
			if (is_dir($Entry)) {
				tep_full_copy($Entry, $target . '/' . $entry, $products_id, $insert_id, $customers_name, $customers_email, $front_title, $front_desc);
				continue;
			}
			copy($Entry, $target . '/' . $insert_id . '_' . $entry);
			$detail_image = 0;
			$thumb_image = 0;
			$imgfile = $target . '/' . $insert_id . '_' . $entry;
			$size = getimagesize($imgfile);
			$width = $size[0];
			// exit;
			if ($width > 500) {
				$detail_image = 1;
				imageCompression($imgfile, 500, $target . 'detail_' . $insert_id . '_' . $entry);
			}
			
			if ($width > 70) {
				$thumb_image = 1;
				imageCompression($imgfile, 70, $target . 'thumb_' . $insert_id . '_' . $entry);
			}
			
			// echo "insert into
			// traveler_photos(products_id,customers_name,customers_email,image_name,image_title,image_desc)
			// values('".$insert_id."','','','". $insert_id.'_'.
			// $entry."','','')";
			

			if ($front_desc_db[$count_picture] == HEDING_TEXT_ENTER_PHOTO_DESCRIPTION) {
				$desc = '';
			} else {
				$desc = $front_desc_db[$count_picture];
			}
			
			if ($front_title_db[$count_picture] == HEDING_TEXT_ENTER_PHOTO_TITLE) {
				$title = '';
			} else {
				$title = $front_title_db[$count_picture];
			}
			
			/*
			 * tep_db_query("insert into
			 * traveler_photos(products_id,customers_name,customers_email,image_name,image_title,image_desc)
			 * values('".$products_id."','".$customers_name."','".tep_db_input($customers_email)."','".
			 * $insert_id.'_'. $entry."','".$title."','".$desc."')");
			 */
			$logo_file = explode(".", $entry);
			$i = sizeof($logo_file);
			$extension = $logo_file[$i - 1];
			$front_ext = strtolower($extension);
			// exit;
			if ($front_ext == "jpeg" || $front_ext == "jpg" || $front_ext == "png" || $front_ext == "gif" || $front_ext == "bmp") {
				if ($title == HEDING_TEXT_ENTER_PHOTO_TITLE) {
					$title = '';
				}
				if ($desc == HEDING_TEXT_ENTER_PHOTO_DESCRIPTION || html_to_db($desc) == '在这里输入') {
					$desc = '';
				}
				$return_files[] = ($detail_image ? 'detail_' : '') . $insert_id . '_' . $entry;
				$insert_photo_sql_data_array = array (
						'products_id' => $products_id,
						'customers_name' => html_to_db($customers_name),
						'customer_id' => intval($_SESSION['customer_id']),
						'customers_email' => tep_db_input($customers_email),
						'image_name' => tep_db_input($insert_id . '_' . $entry),
						'image_title' => html_to_db($title),
						'image_desc' => html_to_db($desc),
						'image_status' => 0 
				);
				tep_db_perform(TABLE_TRAVELER_PHOTOS, $insert_photo_sql_data_array);
				$insert_photo_id = tep_db_insert_id();
				// ## Points/Rewards Module V2.1rc2a BOF ####*/
				if (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '') {
					if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_PHOTOS))) {
						$points_toadd = USE_POINTS_FOR_PHOTOS;
						$comment = 'TEXT_DEFAULT_REVIEWS_PHOTOS';
						$points_type = 'PH';
						tep_add_pending_points($_SESSION['customer_id'], (int) $insert_photo_id, $points_toadd, $comment, $points_type, '', (int) $products_id);
					}
				}
				// ## Points/Rewards Module V2.1rc2a EOF ####*/
			}
			
			if ($detail_image == 1) {
				
				// move_uploaded_file($imgfile,$target.'detail_'.$insert_id.'_'.
				// $entry);
				@unlink($imgfile);
			} // ".$front_title_db[$count_picture]."
			$count_picture ++;
		}
		
		$d->close();
	} else {
		$return_files = $target;
		copy($source, $target);
	}
	return $return_files;
}

function imageCompression($imgfile = "", $thumbsize = 0, $savePath = NULL) {
	/*
	 * echo $imgfile; echo '<br />'; echo $thumbsize; echo '<br />'; echo
	 * $savePath; echo '<br />';
	 */
	if ($savePath == NULL) {
		header('Content-type: image/jpeg');
		/*
		 * To display the image in browser
		 */
	}
	list ($width, $height) = getimagesize($imgfile);
	/*
	 * The width and the height of the image also the getimagesize retrieve
	 * other information as well
	 */
	// echo $width;
	// echo $height;
	$imgratio = $width / $height;
	/*
	 * To compress the image we will calculate the ration For eg. if the image
	 * width=700 and the height = 921 then the ration is 0.77... if means the
	 * image must be compression from its height and the width is based on its
	 * height so the newheight = thumbsize and the newwidth is thumbsize*0.77...
	 */
	
	if ($imgratio > 1) {
		$newwidth = $thumbsize;
		$newheight = $thumbsize / $imgratio;
	} else {
		$newheight = $thumbsize;
		$newwidth = $thumbsize * $imgratio;
	}
	
	$thumb = imagecreatetruecolor($newwidth, $newheight); // Making a new true color
	// image
	// $source=imagecreatefromjpeg($imgfile);
	// // Now it will create a
	// new image from the source
	

	$source = imagecreatefromfile($imgfile); // Now it will create a new image from
	// the source
	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); // Copy
	// and
	// resize
	// the
	// image
	

	// imagejpeg($thumb,$savePath,100);
	imagejpeg($thumb, $savePath, 93);
	/*
	 * Out put of image if the $savePath is null then it will display the image
	 * in the browser
	 */
	imagedestroy($thumb);
	/*
	 * Destroy the image
	 */
}

// if the file is not in jpg format function for other files
function imagecreatefromfile($path, $user_functions = false) {
	$info = @getimagesize($path);
	
	if (!$info) {
		return false;
	}
	
	$functions = array (
			IMAGETYPE_GIF => 'imagecreatefromgif',
			IMAGETYPE_JPEG => 'imagecreatefromjpeg',
			IMAGETYPE_PNG => 'imagecreatefrompng',
			IMAGETYPE_WBMP => 'imagecreatefromwbmp',
			IMAGETYPE_XBM => 'imagecreatefromwxbm' 
	);
	
	if ($user_functions) {
		$functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
	}
	
	if (!$functions[$info[2]]) {
		return false;
	}
	
	if (!function_exists($functions[$info[2]])) {
		return false;
	}
	
	return $functions[$info[2]]($path);
}

function tep_recursive_remove_directory($directory, $empty = FALSE) {
	// if the path has a slash at the end we remove it here
	if (substr($directory, -1) == '/') {
		$directory = substr($directory, 0, -1);
	}
	
	// if the path is not valid or is not a directory ...
	if (!file_exists($directory) || !is_dir($directory)) {
		// ... we return false and exit the function
		return FALSE;
		
		// ... if the path is not readable
	} elseif (!is_readable($directory)) {
		// ... we return false and exit the function
		return FALSE;
		
		// ... else if the path is readable
	} else {
		
		// we open the directory
		$handle = opendir($directory);
		
		// and scan through the items inside
		while (FALSE !== ($item = readdir($handle))) {
			// if the filepointer is not the current directory
	// or the parent directory
			if ($item != '.' && $item != '..') {
				// we build the new path to delete
				$path = $directory . '/' . $item;
				
				// if the new path is a directory
				if (is_dir($path)) {
					// we call this function with the new path
					tep_recursive_remove_directory($path);
					
					// if the new path is a file
				} else {
					// we remove the file
					unlink($path);
				}
			}
		}
		// close the directory
		closedir($handle);
		
		// if the option to empty is not set to true
		if ($empty == FALSE) {
			// try to delete the now empty directory
			if (!rmdir($directory)) {
				// return false if not possible
				return FALSE;
			}
		}
		// return success
		return TRUE;
	}
}

// get order status which is notified
function tep_get_order_status_last_notify($order_id) {
	$status_query = tep_db_query("select s.orders_status_name from " . TABLE_ORDERS_STATUS . " s, " . TABLE_ORDERS_STATUS_HISTORY . " sh where s.orders_status_id = sh.orders_status_id and sh.orders_id = '" . (int) $order_id . "' and customer_notified = 1 order by date_added desc limit 1");
	if ($status = tep_db_fetch_array($status_query))
		return $status['orders_status_name'];
}

function tep_get_display_reg_special_picing_title($products_start_day, $opestartdate, $opeenddate) {
	$operate = '';
	
	$day1 = '';
	$operator_result['products_start_day'] = $products_start_day;
	
	if ($operator_result['products_start_day'] == 1) {
		$day1 .= SUNDAY . '/';
	}
	if ($operator_result['products_start_day'] == 2) {
		$day1 .= MONDAY . '/';
	}
	if ($operator_result['products_start_day'] == 3) {
		$day1 .= TUESDAY . '/';
	}
	if ($operator_result['products_start_day'] == 4) {
		$day1 .= WEDNESDAY . '/';
	}
	if ($operator_result['products_start_day'] == 5) {
		$day1 .= THURSDAY . '/';
	}
	if ($operator_result['products_start_day'] == 6) {
		$day1 .= FRIDAY . '/';
	}
	if ($operator_result['products_start_day'] == 7) {
		$day1 .= SATURDAY . '/';
	}
	
	$opestartdayarray = explode('-', $opestartdate);
	// $operatetomodistart = strftime('%b',
	// mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0,
	// 0,$opestartdayarray[1], 0));
	$operatetomodistart = $opestartdate;
	
	$opeenddayarray = explode('-', $opeenddate);
	// $operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).'
	// '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
	$operatetomodiend = $opeenddate;
	
	if ($opestartdate == '01-01' && $opeenddate == '12-31') {
		$operate .= $day1 . '<br />';
	} else {
		// $operate .= $operatetomodistart.'
	// '.$opestartdayarray[2].'-'.$operatetomodiend.'
	// '.$opeenddayarray[2].': '.$day1.'<br />';
		$tmp_date0 = $operatetomodistart . ' ' . $opestartdayarray[2];
		if (strlen($opestartdayarray[2]) == 4) {
			$tmp_date0 = $operatetomodistart . $opestartdayarray[2];
			$tmp_date0 = trim(preg_replace('/(' . $opestartdayarray[2] . ')+/', $opestartdayarray[2], $tmp_date0));
			if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $tmp_date0)) {
				$tmp_date0 = str_replace('-', '/', $tmp_date0);
				$tmp_date0 = endate_to_dbdate($tmp_date0);
			}
		}
		
		$tmp_date1 = $operatetomodiend . ' ' . $opeenddayarray[2];
		
		if (strlen($opeenddayarray[2]) == 4) {
			$tmp_date1 = $operatetomodiend . '' . $opeenddayarray[2];
			$tmp_date1 = trim(preg_replace('/(' . $opeenddayarray[2] . ')+/', $opeenddayarray[2], $tmp_date1));
			if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $tmp_date1)) {
				$tmp_date1 = str_replace('-', '/', $tmp_date1);
				$tmp_date1 = endate_to_dbdate($tmp_date1);
			}
		}
		$operate .= $tmp_date0 . ' -- ' . $tmp_date1 . ': ' . $day1 . '<br />';
	}
	
	return $operate;
}

/**
 * 将日期格式化成数美国的标准日期格式
 *
 * @param $date 日期字符 @function to change the date format from '2008-12-31' to
 *        	'12/31/2008'
 */
function tep_get_date_disp($date) {
	// echo 'here in function';
	if ($date != '') {
		$date_disp = strtotime($date);
		$date_return = date("m/d/Y", $date_disp);
	} else {
		$date_return = '';
	}
	// echo $date_return;
	return $date_return;
}

/**
 * 将日期格式化成数据库的标准日期格式
 *
 * @param $date 日期字符
 * @param $outFormat 输出的日期格式，默认为Y-m-d
 * @example tep_get_date_db('12/31/2008') change the date format from
 *          '12/31/2008' to '2008-12-31'
 * @author Howard
 */
function tep_get_date_db($date, $outFormat = 'Y-m-d') {
	/*
	 * if($date!='') { $date_disp = explode("/",$date); $date_return =
	 * $date_disp[2].'-'.$date_disp[0].'-'.$date_disp[1]; } else {
	 * $date_return=''; }
	 */
	$date_return = '';
	if (tep_not_null($date)) {
		$date_return = date($outFormat, strtotime($date));
	}
	
	return $date_return;
}

/**
 * 根据行程的出发日期计算结束日期
 *
 * @param string $length 行程长度
 * @param string $format 行程长度格式如d为天,m为月,y为年
 * @param date $date_passed 出发日期YYYY-MM-DD
 * @return string 结束日期
 */
function date_add_day_product($length, $format, $date_passed) {
	$new_timestamp = -1;
	if ($date_passed != '') {
		
		$date_passed_split_array = explode('::', $date_passed);
		
		$date_passed_array = explode('-', $date_passed_split_array[0]);
		$date_actual["mon"] = $date_passed_array[1];
		$date_actual["mday"] = $date_passed_array[2];
		$date_actual["year"] = $date_passed_array[0];
		
		switch (strtolower($format)) {
			case 'd':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"], $date_actual["mday"] + $length, $date_actual["year"]);
				break;
			case 'm':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"] + $length, $date_actual["mday"], $date_actual["year"]);
				break;
			case 'y':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"], $date_actual["mday"], $date_actual["year"] + $length);
				break;
			default:
				break;
		}
		
		return @date('m/d/Y (D)', $new_timestamp);
	} else {
		return '';
	}
}

/**
 * 货币转换
 *
 * @param unknown_type $price_value
 * @param unknown_type $operate_currency_cod
 */
function tep_get_tour_price_in_usd($price_value, $operate_currency_cod) {
	global $currencies;
	
	// $check_tour_agency_operate_currency_query = tep_db_query("select
	// ta.operate_currency_code from " . TABLE_TRAVEL_AGENCY . " as ta,
	// ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id =
	// '" .$products_id. "'");
	// $check_tour_agency_operate_currency =
	// tep_db_fetch_array($check_tour_agency_operate_currency_query);
	

	if ($operate_currency_cod != 'USD' && $operate_currency_cod != '') {
		$currency_rate = $currencies->currencies[$operate_currency_cod]['value'];
		// $price_value = number_format(tep_round($price_value / $currency_rate,
		// $currencies->currencies[$operate_currency_cod]['decimal_places']), 2,
		// '.', '');
		$price_value = number_format(tep_round($price_value / $currency_rate, 0), 2, '.', '');
	}
	
	return $price_value;
}

/**
 * 获取代理商结账货币类型 例如 USD RMB .
 * .. vincent
 *
 * @param int $products_id 产品ID
 */
function tep_get_tour_agency_operate_currency($products_id) {
	$check_tour_agency_operate_currency_query = tep_db_query("select ta.operate_currency_code  from " . TABLE_TRAVEL_AGENCY . " as ta, " . TABLE_PRODUCTS . " p where p.agency_id = ta.agency_id and p.products_id = '" . $products_id . "'");
	$check_tour_agency_operate_currency = tep_db_fetch_array($check_tour_agency_operate_currency_query);
	return $check_tour_agency_operate_currency['operate_currency_code'];
}

function GetDateDifference($StartDateString = NULL, $EndDateString = NULL) {
	$ReturnArray = array ();
	
	$SDSplit = explode('/', $StartDateString);
	$StartDate = mktime(0, 0, 0, $SDSplit[0], $SDSplit[1], $SDSplit[2]);
	
	$EDSplit = explode('/', $EndDateString);
	$EndDate = mktime(0, 0, 0, $EDSplit[0], $EDSplit[1], $EDSplit[2]);
	
	$DateDifference = $EndDate - $StartDate;
	
	$ReturnArray['YearsSince'] = $DateDifference / 60 / 60 / 24 / 365;
	$ReturnArray['MonthsSince'] = $DateDifference / 60 / 60 / 24 / 365 * 12;
	$ReturnArray['DaysSince'] = $DateDifference / 60 / 60 / 24;
	$ReturnArray['HoursSince'] = $DateDifference / 60 / 60;
	$ReturnArray['MinutesSince'] = $DateDifference / 60;
	$ReturnArray['SecondsSince'] = $DateDifference;
	
	$y1 = date("Y", $StartDate);
	$m1 = date("m", $StartDate);
	$d1 = date("d", $StartDate);
	$y2 = date("Y", $EndDate);
	$m2 = date("m", $EndDate);
	$d2 = date("d", $EndDate);
	
	$diff = '';
	$diff2 = '';
	if (($EndDate - $StartDate) <= 0) {
		// Start date is before or equal to end date!
		$diff = "0 days";
		$diff2 = "Days: 0";
	} else {
		
		$y = $y2 - $y1;
		$m = $m2 - $m1;
		$d = $d2 - $d1;
		$daysInMonth = date("t", $StartDate);
		if ($d < 0) {
			$m --;
			$d = $daysInMonth + $d;
		}
		if ($m < 0) {
			$y --;
			$m = 12 + $m;
		}
		$daysInMonth = date("t", $m2);
		
		// Nicestring ("1 year, 1 month, and 5 days")
		if ($y > 0)
			$diff .= $y == 1 ? "1" : "$y";
			// if ($y>0 && $m>0) $diff .= ", ";
		if ($m > 0)
			$diff .= $m == 1 ? ".1" : ".$m";
		
		if ($y == 1) {
			$diff .= " year";
		} else {
			$diff .= " years";
		}
		
		// if (($m>0||$y>0) && $d>0) $diff .= ", and ";
		// if ($d>0) $diff .= $d==1 ? "1 day" : "$d days";
		

		// Nicestring 2 ("Years: 1, Months: 1, Days: 1")
		if ($y > 0)
			$diff2 .= $y == 1 ? "Years: 1" : "Years: $y";
		if ($y > 0 && $m > 0)
			$diff2 .= ", ";
		if ($m > 0)
			$diff2 .= $m == 1 ? "Months: 1" : "Months: $m";
		if (($m > 0 || $y > 0) && $d > 0)
			$diff2 .= ", ";
		if ($d > 0)
			$diff2 .= $d == 1 ? "Days: 1" : "Days: $d";
	}
	$ReturnArray['NiceString'] = $diff;
	$ReturnArray['NiceString2'] = $diff2;
	// return $ReturnArray;
	return $ReturnArray['NiceString'];
}

function teg_get_validate_telephone_number($tel_str, $isreq = true) {
	$succmsg = true;
	/*
	 * if($isreq == true || $tel_str != ''){ if(!preg_match("/^[ ]*[(]{0,1}[
	 * ]*[0-9]{3,3}[ ]*[)]{0,1}[-]{0,1}[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[
	 * ]*$/s", $tel_str)) { $succmsg = false; } }
	 */
	if ($isreq == true && trim($tel_str) == '') {
		$succmsg = false;
	}
	return $succmsg;
}

function split_desk_numbers_display_in_two_parts($number_to_split) {
	$number_in_two_parts_array = explode('-', $number_to_split);
	$n = sizeof($number_in_two_parts_array);
	if ($n > 1) {
		for ($t = 1; $t < $n; $t ++) {
			$rest_number_in_two_parts_array .= $number_in_two_parts_array[$t] . '-';
		}
	}
	$number_in_two_parts_array[1] = substr($rest_number_in_two_parts_array, 0, -1);
	
	return $number_in_two_parts_array;
}

// howard added
function get_traveler_photos_num($produc_id) {
	if (!(int) $produc_id) {
		return false;
	}
	$query = tep_db_query("select count(traveler_photo_id) as rows from " . TABLE_TRAVELER_PHOTOS . " where products_id =" . $produc_id . " and image_status=1 ");
	$query = tep_db_fetch_array($query);
	$total = (int) $query["rows"];
	return $total;
}

function get_product_reviews_num($produc_id) {
	global $languages_id;
	if (!(int) $produc_id) {
		return false;
	}
	$query = tep_db_query("select count(r.reviews_id) as rows from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int) $produc_id . "' and r.reviews_id = rd.reviews_id and r.reviews_status='1' and rd.languages_id = '" . (int) $languages_id . "' ");
	$query = tep_db_fetch_array($query);
	$total = (int) $query["rows"];
	return $total;
}

function get_product_question_num($produc_id) {
	global $languages_id;
	if (!(int) $produc_id) {
		return false;
	}
	$query = tep_db_query("select count(que_id) as rows from " . TABLE_QUESTION . " where products_id = '" . (int) $produc_id . "' and question != '' and languages_id = '" . (int) $languages_id . "' and replay_has_checked = '1' ");
	$query = tep_db_fetch_array($query);
	$total = (int) $query["rows"];
	return $total;
}

function display_vote($v_s_id, $table_width = '100%', $form_name = '', $form_method = 'post', $form_action = '', $form_target = '', $submit_name = '', $charset = CHARSET, $orders_id = '', $submit_image = '') {
	global $error_string;
	
	if (!(int) $v_s_id) {
		return false;
	}
	$string = '';
	$string_foot = '';
	if (tep_not_null($form_name)) {
		$string .= '<form id="' . $form_name . '" name="' . $form_name . '" method="' . $form_method . '" action="' . $form_action . '" style="margin:0px;" target="' . $form_target . '">';
		if (tep_not_null($charset)) {
			$string .= tep_draw_hidden_field('vote_code', $charset);
		}
		$string_foot = '</form>';
	}
	
	if ((int) $orders_id) {
		$string .= tep_draw_hidden_field('orders_id', $orders_id);
	}
	$ToDay = date('Y-m-d');
	$Whiere_Ex = ' AND ( v_s_end_date >="' . $ToDay . '" || v_s_end_date="" || v_s_end_date="0000-00-00" )';
	$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="' . (int) $v_s_id . '" AND v_s_state ="1" AND  v_s_start_date <="' . $ToDay . '" ' . $Whiere_Ex . ' limit 1');
	if ($_GET['action'] == 'preview') {
		$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="' . (int) $v_s_id . '" limit 1');
	}
	// 取得vote_system
	while ($vote_rows = tep_db_fetch_array($vote_sql)) {
		$string .= '<table border="0" cellspacing="0" cellpadding="0" style="width:' . $table_width . ';"><tr><td align="right">' . iconv('gb2312', $charset . '//IGNORE', '调查编号：') . str_replace('-', '', $vote_rows['v_s_start_date']) . $vote_rows['v_s_id'] . '&nbsp;&nbsp;<a href="' . tep_href_link('vote_system_list.php') . '" class="sp3">' . iconv('gb2312', $charset . '//IGNORE', '参加所有调查') . '</a></td></tr></table><table  border="0" cellspacing="0" cellpadding="0" style="width:' . $table_width . '; float:left; margin-bottom:5px;">';
		
		if (tep_not_null($error_string)) {
			$string .= '<tr><td bgcolor="#FFE6E6" style="border:1px #FFCC00 solid; padding:4px 0px 4px 3px; color:#223C6A; font-size:12px;" align="left">' . $error_string . '</td></tr>';
		}
		
		$string .= '<tr><td style="height:5px;"></td></tr><tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; padding:4px 0px 4px 3px; color:#223C6A; font-size:14px;" align="left"><b>' . iconv('gb2312', $charset . '//IGNORE', $vote_rows['v_s_description']) . '</b></td></tr><tr><td style="border:1px #FFCC00 solid; border-top:0px; " align="left">';
		
		// 取得vote_system_item
		$item_sql = tep_db_query('SELECT * FROM `vote_system_item` WHERE v_s_id="' . (int) $vote_rows['v_s_id'] . '" Order By v_s_i_sort ASC, v_s_i_id ASC ');
		$item_num = 0;
		while ($item_rows = tep_db_fetch_array($item_sql)) {
			$item_num ++;
			$background_color = '#FFFFFF';
			if ($item_num % 2 == 0) {
				$background_color = '#FFFBEB';
			}
			$string .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;"><tr><td height="32" style="color:#223C6A; font-size:12px; background-color:' . $background_color . '" >&nbsp;<b>' . $item_num . '. ' . iconv('gb2312', $charset . '//IGNORE', $item_rows['v_s_i_title']) . '</b></td></tr></table>';
			// 取得答案选项
			$string .= '<table border="0" cellspacing="0" cellpadding="0" style="width:100%; background-color:' . $background_color . '"><tr>';
			if ((int) $item_rows['v_s_i_type'] != '2') {
				// 单选复选
				$options_sql = tep_db_query('SELECT * FROM `vote_system_item_options` WHERE v_s_i_id="' . $item_rows['v_s_i_id'] . '" Order By v_s_i_o_id ');
				$check_box_i = 0;
				while ($options_rows = tep_db_fetch_array($options_sql)) {
					
					if ((int) $item_rows['v_s_i_type'] == '0') {
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int) $item_rows['v_s_i_id']];
						if ($tmp_var == (int) $options_rows['v_s_i_o_id']) {
							$checked = true;
						}
						$input_box = tep_draw_radio_field('v_s_i_o_id[' . $vote_rows['v_s_id'] . '][' . (int) $item_rows['v_s_i_id'] . ']', (int) $options_rows['v_s_i_o_id'], $checked);
					} elseif ((int) $item_rows['v_s_i_type'] == '1') {
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int) $item_rows['v_s_i_id']][$check_box_i];
						if ($tmp_var == (int) $options_rows['v_s_i_o_id']) {
							$checked = true;
						}
						$input_box = tep_draw_checkbox_field('v_s_i_o_id[' . $vote_rows['v_s_id'] . '][' . (int) $item_rows['v_s_i_id'] . '][' . $check_box_i . ']', (int) $options_rows['v_s_i_o_id'], $checked);
						$check_box_i ++;
					}
					
					$string .= '<td width="1%" nowrap="nowrap" style="font-size:12px">&nbsp;' . $input_box . '&nbsp;</td><td style="font-size:12px" >' . iconv('gb2312', $charset . '//IGNORE', $options_rows['v_s_i_o_title']) . '</td>';
				}
			} else {
				// 文本
				$tmp_var = $_POST['text_vote'][$vote_rows['v_s_id']][(int) $item_rows['v_s_i_id']];
				if (tep_not_null($_POST['vote_code'])) {
					$tmp_var = iconv($_POST['vote_code'], CHARSET . '//IGNORE', $tmp_var);
				}
				// $string.= '<td
				// style="font-size:12px">&nbsp;'.tep_draw_input_field('text_vote['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']',
				// $tmp_var ,' style="width:400px; font-size:12px" ').'</td>';
				$string .= '<td style="font-size:12px">&nbsp;' . tep_draw_textarea_field('text_vote[' . $vote_rows['v_s_id'] . '][' . (int) $item_rows['v_s_i_id'] . ']', 'virtual', '50', '5', $tmp_var) . '</td>';
			}
			$string .= '</tr><tr><td>&nbsp;</td></tr></table>';
		}
		
		$string .= '</td></tr>';
		
		if (tep_not_null($submit_name)) {
			if (!tep_not_null($submit_image)) {
				$string .= '<tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; border-top-width:0px; padding:4px 0px 4px 3px; color:#223C6A; " align="center"><input type="submit" name="Submit" value=" ' . iconv('gb2312', $charset . '//IGNORE', '提交') . ' " style="font-size:14px;" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="' . iconv('gb2312', $charset . '//IGNORE', '重新选择') . '" style="font-size:14px;" />' . tep_draw_hidden_field('ation_vote', 'true') . '</td></tr>';
			} else {
				$string .= '<tr><td bgcolor="#FFF5CC" style="border:1px #FFCC00 solid; border-top-width:0px; padding:4px 0px 4px 3px; color:#223C6A; " align="center"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>' . tep_image_submit('vote_submit.gif', iconv('gb2312', $charset . '//IGNORE', '提交')) . '</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_hidden_field('ation_vote', 'true') . '</td>
    <td>' . tep_image_reset('reset.gif', '84', '25', iconv('gb2312', $charset . '//IGNORE', '重新选择')) . '</td>
  </tr>
</table></td></tr>';
			}
		}
		
		$string .= '</table>';
	}
	
	return $string . $string_foot;
}

// 提交调查到数据库
function submit_vote(&$error_string, $customers_id) {
	// 以下调查提交过程如果没有客户ID则以IP地址为判断标准
	$error_string = '';
	$error = false;
	$repeat = false;
	$ip_address = get_client_ip();
	/*
	 * if(!(int)$customers_id){ $error = true; $error_string .=
	 * err_msn(db_to_html('无客户ID')); return false; }
	 */
	
	foreach ((array) $_POST['v_s_i_o_id'] as $v_s_id => $tmp) {
		$v_s_id = (int) $v_s_id;
	}
	
	// check error
	if (!(int) $v_s_id && isset($_POST['v_s_i_o_id'])) {
		$error = true;
		$error_string .= err_msn(db_to_html('请选择调查项目！'));
		return false;
	} elseif (isset($_POST['text_vote'])) {
		foreach ((array) $_POST['text_vote'] as $v_s_id => $tmp) {
			$v_s_id = (int) $v_s_id;
			if (is_array($tmp)) {
				foreach ($tmp as $key => $val) {
					if (!tep_not_null($val)) {
						$error = true;
						$error_string .= err_msn(db_to_html('请输入内容！'));
					}
				}
			}
		}
	}
	
	// repeat check1
	$check_repat_sql = tep_db_query('SELECT  v_s_repeat, v_s_points FROM `vote_system` WHERE  v_s_id="' . (int) $v_s_id . '" limit 1');
	$check_row = tep_db_fetch_array($check_repat_sql);
	if ((int) $check_row['v_s_repeat']) {
		$repeat = true;
	}
	$v_s_points = $check_row['v_s_points'];
	
	// check repeat orders
	$orders_id = 0;
	if ($repeat == true && (int) $_POST['orders_id']) {
		$orders_id = (int) $_POST['orders_id'];
		$check_oders = tep_db_query('SELECT * FROM `vote_system_results` WHERE v_s_id="' . (int) $v_s_id . '" AND orders_id="' . (int) $orders_id . '" AND  customers_id = "' . (int) $customers_id . '" limit 1 ');
		if (!(int) $customers_id) {
			$check_oders = tep_db_query('SELECT * FROM `vote_system_results` WHERE v_s_id="' . (int) $v_s_id . '" AND orders_id="' . (int) $orders_id . '" AND ip_address = "' . $ip_address . '" limit 1 ');
		}
		$check_row = tep_db_fetch_array($check_oders);
		if ((int) $check_row['orders_id']) {
			$error = true;
			$error_msn = ' 您已经参加过此调查，请勿重复提交调查。错误代码：' . (int) $check_row['orders_id'];
			$error_string .= err_msn(db_to_html($error_msn));
			return false;
		}
		
		// check orders_id for customers
		$cus_order_sql = tep_db_query('SELECT orders_id  FROM `orders` WHERE  customers_id="' . (int) $customers_id . '" AND orders_id= "' . (int) $orders_id . '" AND  orders_status ="100006" ');
		if (!(int) $customers_id) {
			$cus_order_sql = tep_db_query('SELECT orders_id  FROM `orders` WHERE orders_id= "' . (int) $orders_id . '" AND  orders_status ="100006" ');
		}
		
		$cus_order_row = tep_db_fetch_array($cus_order_sql);
		if (!(int) $cus_order_row['orders_id']) {
			$error = true;
			$error_msn = ' 错误代码：CO:' . (int) $cus_order_row['orders_id'] . '&nbsp;&nbsp;O:' . $orders_id . '&nbsp;&nbsp;C:' . $customers_id;
			$error_string .= err_msn(db_to_html($error_msn));
			return false;
		}
	}
	
	if ($repeat == false) {
		$results_sql = tep_db_query('SELECT v_s_r_id FROM `vote_system_results` WHERE  v_s_id="' . (int) $v_s_id . '" AND customers_id="' . (int) $customers_id . '" limit 1');
		if (!(int) $customers_id) {
			$results_sql = tep_db_query('SELECT v_s_r_id FROM `vote_system_results` WHERE  v_s_id="' . (int) $v_s_id . '" AND ip_address="' . $ip_address . '" limit 1');
		}
		$results_row = tep_db_fetch_array($results_sql);
		if ((int) $results_row['v_s_r_id']) {
			$error = true;
			$error_msn = ' 您已经参加过此调查，请勿重复提交调查。';
			$error_string .= err_msn(db_to_html($error_msn));
			return false;
		}
	}
	
	$check_sql = tep_db_query('SELECT * FROM `vote_system_item` WHERE v_s_id="' . (int) $v_s_id . '" AND v_s_i_type < 2 Order By v_s_i_sort ASC, v_s_i_id ');
	
	while ($check_rows = tep_db_fetch_array($check_sql)) {
		if (!(int) $_POST['v_s_i_o_id'][$v_s_id][$check_rows['v_s_i_id']]) {
			$error = true;
			$error_msn = ' 必须选择1个。';
			if ((int) $check_rows['v_s_i_type']) {
				$error_msn = ' 必须至少选择1个。';
			}
			$error_string .= err_msn(db_to_html($check_rows['v_s_i_title'] . $error_msn));
		}
	}
	
	$date_time = date('Y-m-d H:i:s');
	if ($error == false && (is_array($_POST['v_s_i_o_id']) && count($_POST['v_s_i_o_id']) || (is_array($_POST['text_vote']) && $v_s_id == 5))) {
		// $v_s_id=5 的调查只只需填写一项提交意见的信息即可
		if (is_array($_POST['v_s_i_o_id'])) {
			foreach ($_POST['v_s_i_o_id'] as $v_s_id => $tmp) {
				if (is_array($_POST['v_s_i_o_id'][$v_s_id]) && count($_POST['v_s_i_o_id'][$v_s_id])) {
					foreach ($_POST['v_s_i_o_id'][$v_s_id] as $v_s_i_id => $val) {
						if (!is_array($val)) {
							// 写单选和多选信息到 vote_system_results
							tep_db_query("INSERT INTO `vote_system_results`
										 (`v_s_id`,`v_s_i_id`,`v_s_i_o_id`,`customers_id`,`v_s_r_date`, `orders_id`, `ip_address`) VALUES
										 ('" . (int) $v_s_id . "', '" . (int) $v_s_i_id . "', '" . (int) $val . "', '" . (int) $customers_id . "', '" . $date_time . "','" . (int) $orders_id . "', '" . $ip_address . "');");
							$total_sql = tep_db_query('SELECT count(*) as total FROM `vote_system_results` WHERE v_s_i_o_id="' . (int) $val . '" ');
							$total_row = tep_db_fetch_array($total_sql);
							tep_db_query('UPDATE vote_system_item_options SET v_s_i_o_total="' . (int) $total_row['total'] . '" WHERE v_s_i_o_id="' . (int) $val . '" ');
						}
						if (is_array($val)) {
							// 写多选信息到 vote_system_results
							foreach ($val as $key_A => $val_A) {
								tep_db_query("INSERT INTO `vote_system_results`
											 (`v_s_id`,`v_s_i_id`,`v_s_i_o_id`,`customers_id`,`v_s_r_date`, `orders_id`, `ip_address`) VALUES
											 ('" . (int) $v_s_id . "', '" . (int) $v_s_i_id . "', '" . (int) $val_A . "', '" . (int) $customers_id . "', '" . $date_time . "','" . (int) $orders_id . "', '" . $ip_address . "');");
								$total_sql = tep_db_query('SELECT count(*) as total FROM `vote_system_results` WHERE v_s_i_o_id="' . (int) $val_A . '" ');
								$total_row = tep_db_fetch_array($total_sql);
								tep_db_query('UPDATE vote_system_item_options SET v_s_i_o_total="' . (int) $total_row['total'] . '" WHERE v_s_i_o_id="' . (int) $val_A . '" ');
							}
						}
					}
				}
			}
		}
		
		if (is_array($_POST['text_vote'])) {
			foreach ($_POST['text_vote'] as $v_s_id => $tmp) {
				if (is_array($_POST['text_vote'][$v_s_id]) && count($_POST['text_vote'][$v_s_id])) {
					foreach ($_POST['text_vote'][$v_s_id] as $v_s_i_id => $val) {
						// 写文本信息到 vote_system_results
						$sql_data_array = array (
								'v_s_id' => (int) $v_s_id,
								'v_s_i_id' => (int) $v_s_i_id,
								'text_vote' => tep_db_prepare_input($val),
								'customers_id' => (int) $customers_id,
								'orders_id' => (int) $orders_id,
								'v_s_r_date' => tep_db_prepare_input($date_time),
								'ip_address' => $ip_address 
						);
						
						if ($_POST['vote_code'] == 'big5') {
							$sql_data_array['text_vote'] = iconv($_POST['vote_code'], 'gb2312' . '//IGNORE', tep_db_prepare_input($val));
						}
						tep_db_perform('vote_system_results', $sql_data_array);
					}
				}
			}
		}
		
		// 给用户添加走四方积分
		// Points/Rewards system V2.1rc2a BOF
		if ((USE_POINTS_SYSTEM == 'true') && ($v_s_points > 0)) {
			if ((int) $customers_id) {
				tep_add_vote_points($customers_id, $v_s_points, $v_s_id);
			} else {
				
				$_SESSION['need_points'][count($_SESSION['need_points'])] = array (
						'v_s_id' => $v_s_id,
						'v_s_points' => $v_s_points,
						'ip_address' => $ip_address,
						'orders_id' => $orders_id 
				);
			}
		}
		return true;
	}
}

function add_session_points($customer_id) {
	$string = '';
	$added = false;
	if (!(int) $customer_id) {
		return false;
	}
	if (count($_SESSION['need_points'])) {
		foreach ($_SESSION['need_points'] as $key) {
			$check_sql = tep_db_query('SELECT v_s_id, orders_id FROM `vote_system_results` WHERE v_s_id="' . (int) $key['v_s_id'] . '" AND customers_id="' . (int) $customer_id . '" AND orders_id="' . (int) $key['orders_id'] . '" limit 1');
			$check_rows = tep_db_fetch_array($check_sql);
			
			$repeat = false;
			$sql = tep_db_query('SELECT v_s_id, v_s_start_date, v_s_points, v_s_repeat FROM `vote_system` WHERE  v_s_id="' . (int) $key['v_s_id'] . '" limit 1');
			while ($row = tep_db_fetch_array($sql)) {
				if ((int) $row['v_s_repeat']) {
					$repeat = true;
				}
				$v_s_code = str_replace('-', '', $row['v_s_start_date']) . $row['v_s_id'];
			}
			
			if (!(int) $check_rows['v_s_id'] || ($repeat == true && !(int) $check_rows['orders_id'])) {
				tep_add_vote_points($customer_id, $key['v_s_points'], $key['v_s_id']);
				$string .= '您参与编号为 ' . $v_s_code . ' 的调查获得' . $key['v_s_points'] . '积分，';
				$added = true;
			}
			
			tep_db_query('UPDATE `vote_system_results` SET `customers_id` = "' . (int) $customer_id . '" WHERE `v_s_id` = "' . (int) $key['v_s_id'] . '" AND ip_address="' . $key['ip_address'] . '";');
		}
		$_SESSION['need_points'] = array ();
		unset($_SESSION['need_points']);
		$string .= '已经记入您的帐号。';
		
		if ($added == true) {
			return $string;
		}
	}
}
// howard added end
function tep_get_tour_agency_information($products_id, $order_item_price = 0) {
	$check_tour_agency_operate_currency_query = tep_db_query("select p.agency_id, ta.provider_auto_charged, ta.provider_auto_charged_package, p.products_vacation_package, ta.agency_name, case when p.transaction_fee > 0 then p.transaction_fee else ta.default_transaction_fee end as final_transaction_fee, ta.auto_charged_stop_duration, ta.auto_charged_stop_duration_package, ta.max_auto_cap_amount, ta.max_auto_cap_amount_package from " . TABLE_TRAVEL_AGENCY . " as ta, " . TABLE_PRODUCTS . " p where p.agency_id = ta.agency_id and p.products_id = '" . $products_id . "'");
	$check_tour_agency_operate_currency = tep_db_fetch_array($check_tour_agency_operate_currency_query);
	
	// amit added to stop auto charged for specifict time start
	if ($check_tour_agency_operate_currency['provider_auto_charged_package'] == '1' || $check_tour_agency_operate_currency['provider_auto_charged'] == '1') {
		$total_auto_charged_time_str = '';
		$max_auto_cap_amount_package = 0;
		if ($check_tour_agency_operate_currency['provider_auto_charged_package'] == '1' && $check_tour_agency_operate_currency['products_vacation_package'] == '1') {
			$total_auto_charged_time_str = $check_tour_agency_operate_currency['auto_charged_stop_duration_package'];
			$max_amount_allow_for_auto_capture = $check_tour_agency_operate_currency['max_auto_cap_amount_package'];
		} else 
			if ($check_tour_agency_operate_currency['provider_auto_charged'] == '1' && $check_tour_agency_operate_currency['products_vacation_package'] == '0') {
				$total_auto_charged_time_str = $check_tour_agency_operate_currency['auto_charged_stop_duration'];
				$max_amount_allow_for_auto_capture = $check_tour_agency_operate_currency['max_auto_cap_amount'];
			}
		
		$day_times_exp_array = explode('!###!', $total_auto_charged_time_str);
		
		$todays_auto_stop_array_value = $day_times_exp_array[gmdate('w', time() - 28800)];
		
		$todays_final_to_from_val = explode('-', $todays_auto_stop_array_value);
		
		if ($todays_final_to_from_val[0] != '' && $todays_final_to_from_val[1] != '') {
			
			$current_time_check = gmdate('Hi', time() - 28800);
			$check_from_int_value_to = trim(str_replace(':', '', $todays_final_to_from_val[0]));
			$check_from_int_value_from = trim(str_replace(':', '', $todays_final_to_from_val[1]));
			
			if ($current_time_check >= $check_from_int_value_to && $current_time_check < $check_from_int_value_from) {
				$check_tour_agency_operate_currency['provider_auto_charged'] = '0';
				$check_tour_agency_operate_currency['provider_auto_charged_package'] = '0';
			}
		}
		// Also don't allow auto capture when max allow hight
		if ($max_amount_allow_for_auto_capture > 0 && $order_item_price > 0) {
			if ($order_item_price > $max_amount_allow_for_auto_capture) {
				$check_tour_agency_operate_currency['provider_auto_charged'] = '0';
				$check_tour_agency_operate_currency['provider_auto_charged_package'] = '0';
			}
		}
	}
	// amit added to stop auto charged for specifict time end
	

	return $check_tour_agency_operate_currency;
}

/**
 * 对$roomtotal 增加额外的 3% 费用 @_- vincent
 *
 * @param int $roomtotal
 * @param int $persiontage 百分比
 */
function tep_get_total_fares_includes_agency($roomtotal, $persiontage = 0) {
	$roomtotal = $roomtotal + ($roomtotal * ($persiontage / 100));
	return $roomtotal;
}

// howard added get_pord_index
function get_cat_pord_index($cat_id) {
	return false; //暂时关闭此取得产品关注数据的功能 by howard 2012-05-23
	if ((int) $cat_id) {
		
		$cat_a_sql = tep_db_query('SELECT categories_id FROM `categories` WHERE `parent_id` = "' . $cat_id . '" AND categories_status="1" ');
		$cat_id_string = $cat_id;
		while ($cat_rows = tep_db_fetch_array($cat_a_sql)) {
			$cat_id_string .= ',' . $cat_rows['categories_id'];
		}
		// echo $cat_id_string;
		// 统计的时间段
		$i_start_date = date('Y-m-d', strtotime("-1 week")); // 上周
		$i_back_date = date('Y-m-d', strtotime("-2 week")); // 上上周
		$i_end_date = date('Y-m-d 23:59:59'); // 今天
		

		$between_the_week = ' AND index_date BETWEEN "' . $i_start_date . '" AND "' . $i_end_date . '" ';
		$between_back_week = ' AND index_date BETWEEN "' . $i_back_date . '" AND "' . $i_start_date . ' 23:59:59" ';
		
		$group_by = ' Group By pi.products_index_id ';
		
		$prod_index_0_sql = 'SELECT pi.* FROM `products` p, `products_index` pi, `products_to_categories` c WHERE p.products_id = pi.products_id AND p.products_id = c.products_id AND p.products_status="1" AND c.categories_id IN(' . $cat_id_string . ') ';
		
		$prod_index_sql = tep_db_query($prod_index_0_sql . $between_the_week . $group_by);
		
		$index = array ();
		while ($prod_index_rows = tep_db_fetch_array($prod_index_sql)) {
			if ($prod_index_rows['index_type'] == 'click') { // 浏览量* 0.8
				$index[$prod_index_rows['products_id']] += 8;
			}
			if ($prod_index_rows['index_type'] == 'photos') { // 相片分享* 1.5
				$index[$prod_index_rows['products_id']] += 15;
			}
			if ($prod_index_rows['index_type'] == 'reviews') { // 留言* 1
				$index[$prod_index_rows['products_id']] += 10;
			}
			if ($prod_index_rows['index_type'] == 'question') { // 咨询* 3
				$index[$prod_index_rows['products_id']] += 30;
			}
		}
		// print_r($index).'<br>';
		

		arsort($index); // 根据数组值从高到低排序
		

		$f = 0;
		$prod_index = array ();
		
		$base_val = 0;
		
		foreach ($index as $key => $val) { // 取得指数最高的5个产品及其相对上上周的上升或下跌趋势
			if ($base_val == 0) {
				$base_val = $val;
			}
			$f ++;
			if ($f <= 5) {
				
				$trend = '<span style="color:#223D6A">-</span>';
				$index_num = 0;
				$sql = tep_db_query('SELECT index_type FROM `products_index` WHERE products_id ="' . $key . '" ' . $between_back_week);
				while ($rows = tep_db_fetch_array($sql)) {
					if ($rows['index_type'] == 'click') {
						$index_num += 8;
					}
					if ($rows['index_type'] == 'photos') {
						$index_num += 15;
					}
					if ($rows['index_type'] == 'reviews') {
						$index_num += 10;
					}
					if ($rows['index_type'] == 'question') {
						$index_num += 30;
					}
				}
				if ($val > $index_num) {
					$trend = '↑';
				}
				if ($val < $index_num) {
					$trend = '<span style="color:#1ABE39">↓</span>';
				}
				
				$val = round($val / $base_val, 2) * 100;
				
				$prod_index[] = array (
						'id' => $key,
						'value' => $val,
						'trend' => $trend 
				);
			}
		}
		$html_sring = '
			<div class="xihaian_neirong_l" style=" width:330px; margin:8px 0px 4px 0px; padding-left:9px;"><h3>' . db_to_html('本类本周关注度') . '</h3>
				 <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr><td width="80%" height="21" style="font-size:12px; font-weight:bold;">' . db_to_html('行程名称') . '</td><td width="10%" align="center" style=" font-size:12px; font-weight:bold;">' . db_to_html('趋势') . '</td><td width="10%" align="right" class="jiage" style=" font-size:12px; font-weight:bold; ">' . db_to_html('指数') . '</td>
					</tr>';
		
		foreach ($prod_index as $key) {
			$prod_sql = tep_db_query('SELECT products_id, products_name FROM `products_description` WHERE products_id="' . $key['id'] . '" ');
			while ($prod_row = tep_db_fetch_array($prod_sql)) {
				
				$html_sring .= '<tr><td  width="80%" height="18"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $prod_row['products_id']) . '" class="ff_a" title="' . db_to_html(tep_db_output($prod_row['products_name'])) . '">' . cutword(db_to_html(tep_db_output($prod_row['products_name'])), 40) . '</a></td><td width="10%" align="center" class="jiage" style="font-size:12px; font-family:宋体">' . db_to_html($key['trend']) . '</td>
				<td width="10%" align="right" class="jiage">' . $key['value'] . '</td></tr>';
			}
		}
		$html_sring .= '</table></div>';
		return $html_sring;
	}
}
// howard added get_pord_index end


// howard added get_cat_pord_question
function get_cat_pord_question($cat_id) {
	if (tep_not_null($cat_id)) {
		$cat_a_sql = tep_db_query('SELECT categories_id FROM `categories` WHERE `parent_id` = "' . $cat_id . '" AND categories_status="1" ');
		$cat_id_string = $cat_id;
		while ($cat_rows = tep_db_fetch_array($cat_a_sql)) {
			$cat_id_string .= ',' . $cat_rows['categories_id'];
		}
		
		$q_sql = tep_db_query('SELECT tq.products_id, tq.question FROM `tour_question` tq, `products_to_categories` c WHERE tq.products_id = c.products_id AND que_replied>0 AND c.categories_id IN(' . $cat_id_string . ') Group By tq.que_id Order By que_id DESC Limit 6');
		$string = '<div class="xihaian_neirong" style="width:290px; margin:8px 0px 4px 0px; padding:0px 0px 0px 10px; "><h3>' . db_to_html('本类客户谘询') . '</h3>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:4px;">';
		while ($q_rows = tep_db_fetch_array($q_sql)) {
			$string .= '<tr><td height="18"><a title="' . db_to_html(tep_db_output($q_rows['question'])) . '" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $q_rows['products_id'] . '&mnu=qanda') . '" class="ff_a">' . cutword(db_to_html(tep_db_output($q_rows['question'])), 40) . '</a></td></tr>';
		}
		$string .= '</table></div>';
		
		return $string;
	}
}
// howard added get_cat_pord_question end


// howard added auto_add_product_index
function auto_add_product_index($products_id, $index_type) {
	return false; //暂时关闭产品浏览量记录数据 by howard 2013-05-23
	if ((int) $products_id && (string) $index_type) {
		// 'click', 'photos', 'reviews', 'question'
		tep_db_query("INSERT INTO `products_index` (`products_id`,`index_type`, `index_date`) VALUES ('" . (int) $products_id . "', '" . (string) $index_type . "', NOW());");
	}
	return true;
}
// howard added auto_add_product_index end


// howard added auto_add_question_tag_id()
function auto_add_question_tag_id($que_id) {
	$action_res = false;
	if (!(int) $que_id) {
		return false;
	}
	$q_sql = tep_db_query('SELECT products_id FROM `tour_question` where que_id ="' . (int) $que_id . '" Limit 1');
	$q_row = tep_db_fetch_array($q_sql);
	if ((int) $q_row['products_id']) {
		$products_id = (int) $q_row['products_id'];
	} else {
		return false;
	}
	
	// 取cat id
	$tmp_c = array ();
	$categories_query = tep_db_query("select categories_id from products_to_categories where products_id ='" . (int) $products_id . "' ");
	while ($categories_rows = tep_db_fetch_array($categories_query)) {
		if (preg_match('/^(\d+)/', tep_get_category_patch($categories_rows['categories_id']), $m)) {
			$tmp_c[] = $m[1];
		}
	}
	$tmp_c = array_unique($tmp_c);
	for ($i = 0; $i < count($tmp_c); $i ++) {
		if ((int) $tmp_c[$i]) {
			$sql = tep_db_query('SELECT question_tab_id FROM `tour_question_tab` WHERE categories_id ="' . (int) $tmp_c[$i] . '" ');
			while ($rows = tep_db_fetch_array($sql)) {
				$check_sql = tep_db_query('SELECT que_id FROM `tour_question_to_tab` WHERE tour_question_tab_id ="' . (int) $rows['question_tab_id'] . '" and que_id="' . (int) $que_id . '" ');
				$check_row = tep_db_fetch_array($check_sql);
				if (!(int) $check_row['que_id']) {
					tep_db_query("INSERT INTO `tour_question_to_tab` (`tour_question_tab_id`,`que_id`) VALUES ('" . (int) $rows['question_tab_id'] . "', '" . (int) $que_id . "' );");
					$action_res = true;
				}
			}
		}
	}
	return $action_res;
}

// howard added auto_add_question_tag_id()


/**
 * //howard added send_validation_mail Enter description here .
 * ..
 *
 * @param string $mail_address 用户EMAIL
 * @param boolean $useAjaxAutoMail 用ajax延迟发送 vincent add
 */
function send_validation_mail($mail_address, $useAjaxAutoMail = true) {
	if (!tep_not_null($mail_address)) {
		return false;
	}
	
	$t1 = date("mdy");
	srand((float) microtime() * 10000000);
	$input = array (
			"A",
			"B",
			"C",
			"D",
			"E",
			"F",
			"G",
			"H",
			"J",
			"K",
			"L",
			"M",
			"N",
			"O",
			"P",
			"Q",
			"R",
			"S",
			"T",
			"U",
			"V",
			"W",
			"X",
			"Y",
			"Z" 
	);
	$rand_keys = array_rand($input, 3);
	$l1 = $input[$rand_keys[0]];
	$r1 = rand(0, 9);
	$l2 = $input[$rand_keys[1]];
	$l3 = $input[$rand_keys[2]];
	$r2 = rand(0, 9);
	$validation_code = $l1 . $r1 . $l2 . $l3 . $r2 . $r2;
	
	// input new code to db
	tep_db_query('UPDATE `customers` SET `customers_validation`=0 ,`customers_validation_code` = "' . $validation_code . '" WHERE `customers_email_address` = "' . $mail_address . '" LIMIT 1 ;');
	
	global $customer_first_name, $customer_id;
	
	$to_name = db_to_html($customer_first_name) . ' ';
	$to_email_address = $mail_address;
	$email_subject = db_to_html('您的走四方网验证码！') . ' ';
	$from_email_name = STORE_OWNER;
	$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
	$validation_url = HTTP_SERVER . '/customers_validation.php?customer_id=' . $customer_id . '&action=inputcode&customers_validation_code=' . $validation_code;
	
	// send mail
	$patterns = array ();
	$patterns[0] = '{CustomerName}';
	$patterns[1] = '{images}';
	$patterns[2] = '{HTTP_SERVER}';
	$patterns[3] = '{VCode}';
	$patterns[4] = '{ValidationUrl}';
	$patterns[5] = '{VALIDATION_ACCOUNT_POINT_AMOUNT}';
	$patterns[6] = '{EMAIL}';
	$patterns[7] = '{CONFORMATION_EMAIL_FOOTER}';
	
	$replacements = array ();
	$replacements[0] = $to_name;
	$replacements[1] = HTTP_SERVER . '/email_tpl/images';
	$replacements[2] = HTTP_SERVER;
	$replacements[3] = $validation_code;
	$replacements[4] = $validation_url;
	$replacements[5] = VALIDATION_ACCOUNT_POINT_AMOUNT;
	$replacements[6] = $to_email_address;
	$replacements[7] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
	
	$email_tpl = file_get_contents(DIR_FS_CATALOG . 'email_tpl/header.tpl.html');
	$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/customers_validation_code.tpl.html');
	$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/footer.tpl.html');
	
	$email_text = str_replace($patterns, $replacements, db_to_html($email_tpl)) . email_track_code('validation_code', $to_email_address);
	$email_text = preg_replace('/[[:space:]]+/', ' ', $email_text);
	if ($useAjaxAutoMail == true) {
		$s = count($_SESSION['need_send_email']);
		$_SESSION['need_send_email'][$s]['to_name'] = $to_name;
		$_SESSION['need_send_email'][$s]['to_email_address'] = $to_email_address;
		$_SESSION['need_send_email'][$s]['email_subject'] = $email_subject;
		$_SESSION['need_send_email'][$s]['email_text'] = $email_text;
		$_SESSION['need_send_email'][$s]['from_email_name'] = $from_email_name;
		$_SESSION['need_send_email'][$s]['from_email_address'] = $from_email_address;
		$_SESSION['need_send_email'][$s]['action_type'] = 'true';
	} else {
		tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
	}
	return true;
}

// howard added load_logo()
function load_logo() {
	$image_url = 'image/logo.png';
	$bg = "";
	$bgColor = "";
	$wrap_style = "";
	$year = date("Y");
	$date = date('Y-m-d');
	$date_min = date('m-d');
	$special_logo = 'image/special_logo';
	// 暂停使用节日背景start {
	/*
	 * $array = array('logo'=> '', 'wrap_style'=>''); return $array;
	 */
	// 暂停使用节日背景start }
	

	if (($date_min >= '04-01' && $date_min <= '09-01')) { // 夏天
		$image_url = $special_logo . '/logo.png';
		$bg = "";
	}
	
	if ($date_min == '05-12') { // 512
		$image_url = $special_logo . '/logo.png';
		$bg = "";
	}
	
	// 农历判断 start
	$lunar = chinese_lunar($date);
	// $lunar = "五月初五";
	// echo $lunar;
	if ($lunar == '十二月廿三' || $lunar == '十二月廿四' || $lunar == '十二月廿五' || $lunar == '十二月廿六' || $lunar == '十二月廿七' || $lunar == '十二月廿八' || $lunar == '十二月廿九' || $lunar == '十二月三十' || $lunar == '正月初一' || $lunar == '正月初二' || $lunar == '正月初三') { // 春节
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . "/bg_spring.jpg";
	}
	if ($lunar == '五月初三' || $lunar == '五月初四' || $lunar == '五月初五') { // 端午
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . "/bg_duanwu.jpg";
	}
	if ($lunar == '八月十三' || $lunar == '八月十四' || $lunar == '八月十五') { // 中秋
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . "/bg_mid_autumn_festival.jpg";
	}
	// 农历判断 end
	

	if ($date_min >= '02-12' && $date_min <= '02-14') { // 情人节
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_qingrenjie.jpg';
	}
	
	// 复活节
	/*
	 * 公式（以下公式好像没对）： 年份只限于1900年到2099年 NO.1 设要求的那一年是Y年，从Y减去1900，其差记为N。 NO.2
	 * 用19作除数去除N，余数记为A。 NO.3 用4作除数去除N，不管余数，把商记为Q。 NO.4 用19去除7A+1，把商记为B，不管余数。
	 * NO.5 用29去除11A+4-B，余数记为M。 NO.6 用7去除N+Q+31-M，余数记为W。 NO.7 计算25-M-W。
	 */
	if ($year == "2013" && $date_min >= '03-30' && $date_min <= '03-31') { // 复活节
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_easter.jpg';
	}
	
	if ($date_min >= '04-29' && $date_min <= '05-01') { // 劳动节
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_laborday.jpg';
	}
	
	if ($date_min >= '05-06' && $date_min <= '05-14') { // 母亲节(每年5月的第二个星期天)
		$motherDay = getWeekDateForMonth($year, 5, 2, 0);
		$date_start = date("Y-m-d", strtotime($motherDay) - (86400 * 2));
		if ($date >= $date_start && $date <= $motherDay) {
			$image_url = $special_logo . '/logo.png';
			$bg = $special_logo . '/bg_mother.jpg';
		}
	}
	
	if ($date_min >= '06-15' && $date_min <= '06-21') { // 父亲节(每年6月的第三个星期天)
		$fatherDay = getWeekDateForMonth($year, 6, 3, 0);
		$date_start = date("Y-m-d", strtotime($fatherDay) - (86400 * 2));
		if ($date >= $date_start && $date <= $fatherDay) {
			$image_url = $special_logo . '/logo.png';
			$bg = $special_logo . '/bg_father_day.jpg';
		}
	}
	
	if ($date_min >= '09-01' && $date_min <= '09-07') { // 美国劳动节(每年9月的第一个星期一)
		$laborDay = getWeekDateForMonth($year, 9, 1, 1);
		$date_start = date("Y-m-d", strtotime($laborDay) - (86400 * 2));
		if ($date >= $date_start && $date <= $laborDay) {
			$image_url = $special_logo . '/logo.png';
			$bg = $special_logo . '/bg_labordayusa.jpg';
		}
	}
	
	if ($date_min == '10-01') { // 国庆
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_china_national_day.jpg';
	}
	
	if ($date_min == '11-01' || $date_min == '11-02') { // 万圣节(每年的11月1日)
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_halloween.jpg';
		$bgColor = '#FFFFFF';
	}
	if ($date_min >= '11-20' && $date_min <= '11-30') { // 感恩节(每年11月的第4个星期四)
		$laborDay = getWeekDateForMonth($year, 11, 4, 4);
		$date_start = date("Y-m-d", strtotime($laborDay) - (86400 * 2));
		if ($date >= $date_start && $date <= $laborDay) {
			$image_url = $special_logo . '/logo.png';
			$bg = $special_logo . '/bg_thanksgiving.jpg';
		}
	}
	
	if ($date_min == '12-25' || ($date_min >= '12-20' && $date_min <= '12-25')) { // 圣诞
		// $image_url
		// =
		// $special_logo.'/logo_shengdanjie.png';
		// $bg
		// =
		// "";
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_shengdanjie.jpg';
	}
	if (($date_min >= '12-29' && $date_min <= '12-31') || ($date_min >= '01-01' && $date_min <= '01-01')) { // 元旦
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/bg_yuandan.jpg';
	}
	
	if ($_GET['load_logo']) { // 输入这个参数即可测试背景效果
		$image_url = $special_logo . '/logo.png';
		$bg = $special_logo . '/' . $_GET['load_logo'];
	}
	
	if ($bg != "") {
		$bgColor = $bgColor ? $bgColor : '#FFFFFF'; // 8CCF1
		$wrap_style = ' style="background:url(' . $bg . ') no-repeat scroll center top ' . $bgColor . '; " '; // padding-top:50px;
		// echo
		// 111;
		// exit;
	} else { // 如果没有特殊日子 则用默认图片
		$bgColor = $bgColor ? $bgColor : '#FFFFFF'; // 8CCF1
		// $wrap_style = '
		// style="background:url(DIR_WS_TEMPLATE_IMAGES.head/body_bg.jpg)
		// repeat-x scroll left top
		// '.$bgColor.'; "
		// ';//padding-top:50px;
		$wrap_style = ' style="background:url(' . DIR_WS_TEMPLATE_IMAGES . 'head/body_bg.jpg) no-repeat scroll center top ; " '; // padding-top:50px;
	}
	
	$array = array (
			'logo' => $image_url,
			'wrap_style' => $wrap_style 
	);
	return $array;
}
// howard added load_logo() end


/**
 * 取得某年某月的第几个星期几
 *
 * @param $year(1970-2038) 年 $month(1-12) 月 第$weekNo(1-5)个星期$week(0-6)
 * @author Howard
 * @return YYYY-MM-DD
 */
function getWeekDateForMonth($year, $month, $weekNo, $week) {
	for ($i = 1; $i < 8; $i ++) {
		if (date('w', strtotime($year . "-" . $month . "-" . $i)) == $week) {
			$_time = strtotime($year . "-" . $month . "-" . $i);
		}
	}
	$_time += ($weekNo - 1) * 7 * 86400;
	// return date("Y-m-d w",$_time);
	return date("Y-m-d", $_time);
}

// howard added email track code
function email_track_code($mail_type = 'newsletter', $mail_address = 'xmzhh2000@126.com', $id = 0, $id_key = 'orders_id', $orders_eticket_log_id = 0) {
	$img_rul = HTTP_SERVER . '/email_track.php';
	$img_rul .= '?mail_type=' . $mail_type;
	$img_rul .= '&mail_address=' . $mail_address;
	if ((int) $id) {
		$img_rul .= '&key_field=' . $id_key . '&key_id=' . $id;
		// $img_rul .='&'.$id_key.'='.$id;
	}
	// E-ticket Log Start
	if ((int) $orders_eticket_log_id > 0) {
		$img_rul .= '&orders_eticket_log_id=' . $orders_eticket_log_id;
	}
	// E-ticket Log End
	$img_str = '<img src="' . $img_rul . '" width="1" height="1" style="display:none" />';
	return $img_str;
}
// howard added email track code end


// howard added Order status name replace on shows
function order_status_replace($status_name) {
	$penten = array ();
	$penten[0] = '/^Book[[:space:]]+Issued[[:space:]]+订单已经签发$/i';
	$penten[1] = '/^Charged$/i';
	$penten[2] = '/^Charge[[:space:]]+Captured[[:space:]]+已收费$/i';
	$penten[3] = '/^Cancellation[[:space:]]+Confirmation[[:space:]]+Received[[:space:]]+供应商已确认取消订单$/i';
	$penten[4] = '/^Cancellation[[:space:]]+Request[[:space:]]+Sent[[:space:]]+取消订单请求发至供应商$/i';
	$penten[5] = '/^Hold[[:space:]]+to[[:space:]]+Charge$/i';
	$penten[6] = '/^weiyi[[:space:]]+to[[:space:]]+capture[[:space:]]+请Weiyi[[:space:]]+收费$/i';
	$penten[7] = '/^CSR Reservation[[:space:]]+Special[[:space:]]+Notes[[:space:]]+客服内部提示$/i';
	
	$repval = array ();
	$repval[0] = 'Processing 处理中';
	$repval[1] = 'Ticket Issue 电子客票已签发';
	$repval[2] = 'Ticket Issue 电子客票已签发';
	$repval[3] = 'In Process of Cancellation 订单取消进程中';
	$repval[4] = 'In Process of Cancellation 订单取消进程中';
	$repval[5] = 'Processing 处理中';
	$repval[6] = 'Ticket Issue 电子客票已签发';
	$repval[7] = 'Processing 处理中';
	return preg_replace($penten, $repval, $status_name);
}
// howard added Order status name replace on shows


// howard added 结伴同游 取得付款状态
function get_travel_companion_status($status_id) {
	$status_array = array ();
	$status_array[0] = '未付款';
	$status_array[1] = '付款审核中';
	$status_array[2] = '付款完成';
	$status_array[3] = '已部分付款';
	return $status_array[(int) $status_id];
}

// 检测结是否是结伴同游
function is_travel_comp($orders_id, $products_id = 0) {
	if (!(int) $orders_id) {
		return false;
	}
	$where_str = ' orders_id="' . (int) $orders_id . '" ';
	if ((int) $products_id) {
		$where_str .= ' AND products_id="' . (int) $products_id . '" ';
	}
	$sql = tep_db_query('SELECT orders_travel_companion_id FROM `orders_travel_companion` WHERE ' . $where_str . ' LIMIT 1 ');
	$row = tep_db_fetch_array($sql);
	if ((int) $row['orders_travel_companion_id']) {
		return true;
	}
	
	return false;
}

// 取得结伴同游订单的电子邮件列表
function get_order_travel_companion_email_list($order_id, $products_id = 0) {
	if (!(int) $order_id) {
		return false;
	}
	$where_p = "";
	if ((int) $products_id > 0) {
		$where_p = ' AND products_id="' . (int) $products_id . '" ';
	}
	
	$to_email_address = array ();
	$e_sql = tep_db_query('SELECT customers_id FROM `orders_travel_companion` WHERE  orders_id="' . (int) $order_id . '" ' . $where_p . ' Order By products_id ASC ');
	while ($e_rows = tep_db_fetch_array($e_sql)) {
		if ((int) $e_rows['customers_id']) {
			$mail_string = tep_get_customers_email((int) $e_rows['customers_id']);
			if (!in_array($mail_string, $to_email_address)) {
				$to_email_address[] = $mail_string;
			}
		}
	}
	if (count($to_email_address)) {
		$to_email_address = join(',', $to_email_address);
		return $to_email_address;
	}
	
	return false;
}
// 取得结伴同游帖子的类型
function tep_bbs_type_name($type_id) {
	if ($type_id == 100 || $type_id == 0) {
		return false;
	}
	$sql = tep_db_query('SELECT * FROM `travel_companion_bbs_type` WHERE type_id="' . (int) $type_id . '" LIMIT 1 ');
	$row = tep_db_fetch_array($sql);
	if (tep_not_null($row['type_name'])) {
		return $row['type_name'];
	}
	return false;
}

// 根据客户帐号取得客户使用的言语编码
function customers_language_code($customers_email_address) {
	$sql = tep_db_query('SELECT customers_char_set FROM `customers` WHERE customers_email_address ="' . tep_db_prepare_input($customers_email_address) . '" limit 1 ');
	$row = tep_db_fetch_array($sql);
	if (strtolower($row['customers_char_set']) == 'big5') {
		return $row['customers_char_set'];
	} else {
		return 'gb2312';
	}
}

/**
 * 判断某个产品是不是酒店
 *
 * @param unknown_type $products_id
 * @return unknown
 */
function product_is_hotel($products_id) {
	/*
	 * //酒店目录id号是182 $c_id = 182; $sql = tep_db_query('SELECT products_id FROM
	 * `products_to_categories` WHERE products_id="'.(int)$products_id.'" AND
	 * categories_id="'.(int)$c_id.'" limit 1'); $row =
	 * tep_db_fetch_array($sql); if($row['products_id']>0){ return true; }
	 */
	$sql = tep_db_query('SELECT is_hotel FROM `products` WHERE products_id="' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['is_hotel'];
}

// 根据产品id取得产品接送地的酒店ids,返回值是一个数组
function get_products_departure_hotel_ids($products_id) {
	$sql = tep_db_query('SELECT products_hotels_ids FROM `products_departure` WHERE products_id="' . (int) $products_id . '" ');
	$ids_str = '';
	while ($rows = tep_db_fetch_array($sql)) {
		if (tep_not_null($rows['products_hotels_ids'])) {
			$ids_str .= $rows['products_hotels_ids'] . ',';
		}
	}
	$ids_str = substr($ids_str, 0, strlen($ids_str) - 1);
	$ids = explode(',', $ids_str);
	$ids = array_unique($ids);
	// 重新整理数组，去除空值或不是酒店的id
	foreach ($ids as $key => $val) {
		if (!(int) $val || product_is_hotel((int) $val) != true) {
			unset($ids[$key]);
		}
	}
	return $ids;
}

function get_product_companion_post_num($produc_id) {
	if (!(int) $produc_id) {
		return false;
	}
	$query = tep_db_query("select count(t_companion_id) as rows from " . TABLE_TRAVEL_COMPANION . " where products_id = '" . (int) $produc_id . "' and status = '1'");
	$query = tep_db_fetch_array($query);
	$total = (int) $query["rows"];
	return $total;
}

function tep_get_onelevel_categories($parent_id = '0') {
	global $languages_id;
	
	$categories_array = array ();
	
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int) $parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int) $languages_id . "' order by c.sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
		$categories_array[] = array (
				'id' => $categories['categories_id'],
				'text' => $indent . $categories['categories_name'] 
		);
	}
	
	return $categories_array;
}

// 评论评分数组及分数
function get_reviews_array() {
	$array = array ();
	$array[0]['title'] = '预定';
	$array[0]['opction'] = array (
			'20' => '满意',
			'13' => '一般',
			'7' => '不满意' 
	);
	$array[1]['title'] = '客服';
	$array[1]['opction'] = array (
			'20' => '满意',
			'13' => '一般',
			'7' => '不满意' 
	);
	$array[2]['title'] = '住宿';
	$array[2]['opction'] = array (
			'15' => '满意',
			'10' => '一般',
			'5' => '不满意' 
	);
	$array[3]['title'] = '交通';
	$array[3]['opction'] = array (
			'15' => '满意',
			'10' => '一般',
			'5' => '不满意' 
	);
	$array[4]['title'] = '导游';
	$array[4]['opction'] = array (
			'15' => '满意',
			'10' => '一般',
			'5' => '不满意' 
	);
	$array[5]['title'] = '行程';
	$array[5]['opction'] = array (
			'15' => '满意',
			'10' => '一般',
			'5' => '不满意' 
	);
	return $array;
}

// 判断产品是不是秀Show
function is_show($produc_id) {
	$sql = tep_db_query("select products_info_tpl from products where products_id = '" . (int) $produc_id . "' limit 1 ");
	$row = tep_db_fetch_array($sql);
	if ($row['products_info_tpl'] == "product_info_vegas_show") {
		return true;
	}
	return false;
}

// 写错误日志到数据库
function write_error_log($content) {
	$data_array = array (
			'content' => tep_db_prepare_input($content),
			'add_date' => date('Y-m-d H:i:s') 
	);
	tep_db_perform('error_log', $data_array);
}

// 从词库中取出与$pat_content匹配的$num个关键词,结果是数组或false
function tep_add_meta_keywords_from_thesaurus($pat_content, $num = 5) {
	if (!tep_not_null($pat_content) || $num < 1) {
		return false;
	}
	$rest_array = array ();
	$sql = tep_db_query('SELECT thesaurus_text FROM `keyword_thesaurus` WHERE 1 Order By thesaurus_text_length ');
	while ($rows = tep_db_fetch_array($sql)) {
		if (tep_not_null($rows['thesaurus_text'])) {
			if (@preg_match_all('/' . preg_quote(strip_tags($rows['thesaurus_text'])) . '/', $pat_content, $tmp_array) >= $num) {
				$rest_array[] = strip_tags($rows['thesaurus_text']);
			}
		}
	}
	if (count($rest_array) > 0) {
		return $rest_array;
	}
	return false;
}

// 取得在线用户的ip状态
function check_ip_status() {
	$ip_address = get_client_ip();
	$BlackListIPsFlies = DIR_FS_CATALOG . "tmp/Black_List_Ip.txt";
	// 永久黑名单IP start
	if ($lines = @file($BlackListIPsFlies)) {
		// print_r($lines);
		foreach ((array) $lines as $key => $val) {
			if (preg_replace('/[[:space:]]+/', '', $val) == $ip_address) {
				return false;
			}
		}
	}
	// 永久黑名单IP end
	

	// 防cc攻击检查
	$cc_on = true;
	// 排除搜索引擎蜘蛛start
	$sub_ip_address = substr($ip_address, 0, strrpos($ip_address, '.')); // str_replace($ip_address);
	$spider_file = DIR_FS_CATALOG . 'search_engine_spider_ip.txt';
	if ($lines = @file($spider_file)) {
		foreach ($lines as $key => $val) {
			$val = db_to_html($val);
			if ($sub_ip_address == substr($val, 0, strrpos($val, '.'))) {
				$cc_on = false;
				break;
			}
		}
	}
	/*
	 * if(isCrawler()!=false){ //如是搜索引擎则让它过去 $cc_on = false; }
	 */
	// 排除搜索引擎蜘蛛end
	if ($cc_on == true) {
		$sql = tep_db_query('SELECT count(*) as total, full_name, ip_address FROM `whos_online` WHERE whos_status = "1" Group By ip_address order by total DESC LIMIT 0 , 20;');
		while ($rows = tep_db_fetch_array($sql)) {
			// if($rows['total']>=20 && tep_not_null($rows['ip_address']) &&
			// $rows['full_name']=="Guest"){
			if ($rows['total'] >= 20 && tep_not_null($rows['ip_address'])) {
				$sql = tep_db_query('SELECT count(*) as total, ip_address FROM `whos_online` WHERE ip_address="' . $rows['ip_address'] . '" Group By time_entry order by total DESC limit 1');
				$row = tep_db_fetch_array($sql);
				if ($row['total'] >= 3) {
					tep_db_query('update whos_online set whos_status="0" where ip_address ="' . $row['ip_address'] . '" ');
					// 记录日志
					$error_log_file = DIR_FS_CATALOG . "tmp/CC_Log.txt";
					$error_notes = 'Date:' . date("Y-m-d H:i:s") . " | ";
					$error_notes .= 'MaxConcurrent:' . $row['total'] . " | ";
					$error_notes .= 'IpAddress:' . $row['ip_address'] . "\n";
					if ($handle = fopen($error_log_file, 'ab')) {
						fwrite($handle, $error_notes);
						fclose($handle);
					}
					// 记录到永久黑名单
					if ($row['total'] >= 5) { // 并发数超过5次的将添加到永久黑名单
						$black_list_ip = $row['ip_address'] . "\n";
						if ($handle = fopen($BlackListIPsFlies, 'ab')) {
							if (flock($handle, LOCK_EX)) {
								fwrite($handle, $black_list_ip);
								flock($handle, LOCK_UN);
							}
							fclose($handle);
						}
						
						if ($lines = @file($BlackListIPsFlies)) {
							$lines = array_unique($lines);
							// $lines_str = implode("\n",$lines);
							$lines_str = implode("", $lines);
							if ($handle = fopen($BlackListIPsFlies, 'wb')) {
								if (flock($handle, LOCK_EX)) {
									fwrite($handle, $lines_str);
									flock($handle, LOCK_UN);
								}
								fclose($handle);
							}
						}
					}
				}
			}
		}
	}
	return true;
}

/**
 * 去掉已经卖完的产品日期
 * @param unknown_type $products_id
 * @param unknown_type $array_avaliabledate_store
 * @return multitype:
 */
function remove_soldout_dates($products_id, $array_avaliabledate_store) {
	$ar_soldout_dates = array ();
	$qry_sold_dates = "SELECT * FROM " . TABLE_PRODUCTS_SOLDOUT_DATES . " sd WHERE sd.products_id='" . (int) $products_id . "'";
	$res_sold_dates = tep_db_query($qry_sold_dates);
	while ($row_sold_dates = tep_db_fetch_array($res_sold_dates)) {
		$ar_searched = preg_grep("|" . $row_sold_dates['products_soldout_date'] . "|", (array) $array_avaliabledate_store);
		$ar_searched_key = key((array)$ar_searched);
		if ($ar_searched_key != "") {
			$ar_soldout_dates[$ar_searched_key] = $ar_searched[$ar_searched_key];
		}
	}
	$array_avaliabledate_store = array_diff((array) $array_avaliabledate_store, (array) $ar_soldout_dates);
	
	return $array_avaliabledate_store;
}
/* End - Remove sold dates */

// 下单成功使用手机短信通知客户
function send_orders_sms($orders_id) {
	global $cpunc;
	if (preg_match('/' . preg_quote('[下单成功]') . '/', CPUNC_USE_RANGE)) {
		// 根据订单号取得手机号码
		$sql = tep_db_query('SELECT c.customers_cellphone, c.customers_mobile_phone, c.customers_telephone FROM `orders` o,`customers` c WHERE o.orders_id ="' . (int) $orders_id . '" AND o.customers_id = c.customers_id Limit 1 ');
		$row = tep_db_fetch_array($sql);
		$strMobile = '';
		$result_phone = check_phone($row['customers_cellphone']);
		if (!empty($result_phone))
			$strMobile = $result_phone[0];
		else {
			$result_phone = check_phone($row['customers_mobile_phone']);
			if (!empty($result_phone))
				$strMobile = $result_phone[0];
			else {
				$result_phone = check_phone($row['customers_telephone']);
				if (!empty($result_phone))
					$strMobile = $result_phone[0];
			}
		}
		/*
		 * $len_array = explode('-',CPUNC_PHONE_LENGTH); $mobile_phone =
		 * tep_db_output($row['customers_cellphone']); $mobile_phone =
		 * preg_replace('/.*-/','',$mobile_phone); $mobile_phone =
		 * preg_replace('/^0+/','',$mobile_phone); $mobile_phone1 =
		 * tep_db_output($row['customers_mobile_phone']); $mobile_phone1 =
		 * preg_replace('/.*-/','',$mobile_phone1); $mobile_phone1 =
		 * preg_replace('/^0+/','',$mobile_phone1); $mobile_phone2 =
		 * tep_db_output($row['customers_telephone']); $mobile_phone2 =
		 * preg_replace('/.*-/','',$mobile_phone2); $mobile_phone2 =
		 * preg_replace('/^0+/','',$mobile_phone2); $strMobile = '';
		 * if(strlen($mobile_phone) >= $len_array[0] && strlen($mobile_phone) <=
		 * $len_array[1] || strlen($mobile_phone)==$len_array[0]){ $strMobile =
		 * $mobile_phone; } elseif(strlen($mobile_phone1) >= $len_array[0] &&
		 * strlen($mobile_phone1) <= $len_array[1] ||
		 * strlen($mobile_phone1)==$len_array[0]){ $strMobile = $mobile_phone1;
		 * } elseif(strlen($mobile_phone2) >= $len_array[0] &&
		 * strlen($mobile_phone2) <= $len_array[1] ||
		 * strlen($mobile_phone2)==$len_array[0]){ $strMobile = $mobile_phone2;
		 * }
		 */
		if ($strMobile != '') {
			$content = sprintf(BUY_SUCCESS_SMS, $orders_id);
			if ($cpunc->SendSMS($strMobile, $content, CHARSET)) {
				// 在当前状态下添加Comments信息
				$orders_history_sql = "select * from orders_status_history where orders_id = '" . $orders_id . "' order by orders_status_history_id desc Limit 1";
				$orders_history_query = tep_db_query($orders_history_sql);
				$orders_history = tep_db_fetch_array($orders_history_query);
				$comments = tep_db_input($orders_history['comments'] . "\n\n\n" . $strMobile . "客户下单成功的提醒短信在" . date('Y-m-d') . "发送成功！");
				tep_db_query("update orders_status_history set comments = '" . $comments . "' WHERE orders_status_history_id = " . $orders_history['orders_status_history_id']);
			}
		}
	}
}

// yichi added 2011-04-02
// 下单成功并填有飞机到达信息，且飞机到达时间与出团时间相差一至三天，则短信提醒旅客延住
function send_before_extension_sms($orders_id) {
	global $cpunc;
	if (preg_match('/' . preg_quote('[酒店延住提醒]') . '/', CPUNC_USE_RANGE)) {
		// 根据订单号取得手机号码
		$sql = tep_db_query('SELECT c.customers_cellphone, c.customers_mobile_phone, c.customers_telephone FROM `orders` o,`customers` c WHERE o.orders_id ="' . (int) $orders_id . '" AND o.customers_id = c.customers_id Limit 1 ');
		$row = tep_db_fetch_array($sql);
		$strMobile = '';
		$result_phone = check_phone($row['customers_cellphone']);
		if (!empty($result_phone))
			$strMobile = $result_phone[0];
		else {
			$result_phone = check_phone($row['customers_mobile_phone']);
			if (!empty($result_phone))
				$strMobile = $result_phone[0];
			else {
				$result_phone = check_phone($row['customers_telephone']);
				if (!empty($result_phone))
					$strMobile = $result_phone[0];
			}
		}
		/*
		 * $len_array = explode('-',CPUNC_PHONE_LENGTH); $mobile_phone =
		 * tep_db_output($row['customers_cellphone']); $mobile_phone =
		 * preg_replace('/.*-/','',$mobile_phone); $mobile_phone =
		 * preg_replace('/^0+/','',$mobile_phone); $mobile_phone1 =
		 * tep_db_output($row['customers_mobile_phone']); $mobile_phone1 =
		 * preg_replace('/.*-/','',$mobile_phone1); $mobile_phone1 =
		 * preg_replace('/^0+/','',$mobile_phone1); $mobile_phone2 =
		 * tep_db_output($row['customers_telephone']); $mobile_phone2 =
		 * preg_replace('/.*-/','',$mobile_phone2); $mobile_phone2 =
		 * preg_replace('/^0+/','',$mobile_phone2); $strMobile = '';
		 * if(strlen($mobile_phone) >= $len_array[0] && strlen($mobile_phone) <=
		 * $len_array[1] || strlen($mobile_phone)==$len_array[0]){ $strMobile =
		 * $mobile_phone; } elseif(strlen($mobile_phone1) >= $len_array[0] &&
		 * strlen($mobile_phone1) <= $len_array[1] ||
		 * strlen($mobile_phone1)==$len_array[0]){ $strMobile = $mobile_phone1;
		 * } elseif(strlen($mobile_phone2) >= $len_array[0] &&
		 * strlen($mobile_phone2) <= $len_array[1] ||
		 * strlen($mobile_phone2)==$len_array[0]){ $strMobile = $mobile_phone2;
		 * }
		 */
		if ($strMobile != '') {
			$content = BEFORE_EXTENSION_SMS;
			if ($cpunc->SendSMS($strMobile, $content, CHARSET)) {
				// 在当前状态下添加Comments信息
				$orders_history_sql = "select * from orders_status_history where orders_id = '" . $orders_id . "' order by orders_status_history_id desc Limit 1";
				$orders_history_query = tep_db_query($orders_history_sql);
				$orders_history = tep_db_fetch_array($orders_history_query);
				$comments = tep_db_input($orders_history['comments'] . "\n\n\n" . $strMobile . "客户提前到达的延住短信在" . date('Y-m-d') . "发送成功！");
				tep_db_query("update orders_status_history set comments = '" . $comments . "' WHERE orders_status_history_id = " . $orders_history['orders_status_history_id']);
			}
		}
	}
}

// yichi added 2011-04-02
// 下单成功并填有飞机离开信息，且飞机离开时间在旅行结束后第二天早上8:00以后，第五天早上8:00之前的，则发送短信提醒旅客延住
function send_after_extension_sms($orders_id) {
	global $cpunc;
	if (preg_match('/' . preg_quote('[酒店延住提醒]') . '/', CPUNC_USE_RANGE)) {
		// 根据订单号取得手机号码
		$sql = tep_db_query('SELECT c.customers_cellphone, c.customers_mobile_phone, c.customers_telephone FROM `orders` o,`customers` c WHERE o.orders_id ="' . (int) $orders_id . '" AND o.customers_id = c.customers_id Limit 1 ');
		$row = tep_db_fetch_array($sql);
		$strMobile = '';
		$result_phone = check_phone($row['customers_cellphone']);
		if (!empty($result_phone))
			$strMobile = $result_phone[0];
		else {
			$result_phone = check_phone($row['customers_mobile_phone']);
			if (!empty($result_phone))
				$strMobile = $result_phone[0];
			else {
				$result_phone = check_phone($row['customers_telephone']);
				if (!empty($result_phone))
					$strMobile = $result_phone[0];
			}
		}
		/*
		 * $len_array = explode('-',CPUNC_PHONE_LENGTH); $mobile_phone =
		 * tep_db_output($row['customers_cellphone']); $mobile_phone =
		 * preg_replace('/.*-/','',$mobile_phone); $mobile_phone =
		 * preg_replace('/^0+/','',$mobile_phone); $mobile_phone1 =
		 * tep_db_output($row['customers_mobile_phone']); $mobile_phone1 =
		 * preg_replace('/.*-/','',$mobile_phone1); $mobile_phone1 =
		 * preg_replace('/^0+/','',$mobile_phone1); $mobile_phone2 =
		 * tep_db_output($row['customers_telephone']); $mobile_phone2 =
		 * preg_replace('/.*-/','',$mobile_phone2); $mobile_phone2 =
		 * preg_replace('/^0+/','',$mobile_phone2); $strMobile = '';
		 * if(strlen($mobile_phone) >= $len_array[0] && strlen($mobile_phone) <=
		 * $len_array[1] || strlen($mobile_phone)==$len_array[0]){ $strMobile =
		 * $mobile_phone; } elseif(strlen($mobile_phone1) >= $len_array[0] &&
		 * strlen($mobile_phone1) <= $len_array[1] ||
		 * strlen($mobile_phone1)==$len_array[0]){ $strMobile = $mobile_phone1;
		 * } elseif(strlen($mobile_phone2) >= $len_array[0] &&
		 * strlen($mobile_phone2) <= $len_array[1] ||
		 * strlen($mobile_phone2)==$len_array[0]){ $strMobile = $mobile_phone2;
		 * }
		 */
		if ($strMobile != '') {
			$content = AFTER_EXTENSION_SMS;
			if ($cpunc->SendSMS($strMobile, $content, CHARSET)) {
				// 在当前状态下添加Comments信息
				$orders_history_sql = "select * from orders_status_history where orders_id = '" . $orders_id . "' order by orders_status_history_id desc Limit 1";
				$orders_history_query = tep_db_query($orders_history_sql);
				$orders_history = tep_db_fetch_array($orders_history_query);
				$comments = tep_db_input($orders_history['comments'] . "\n\n\n" . $strMobile . "客户延后离开的延住短信在" . date('Y-m-d') . "发送成功！");
				tep_db_query("update orders_status_history set comments = '" . $comments . "' WHERE orders_status_history_id = " . $orders_history['orders_status_history_id']);
			}
		}
	}
}

// 统计某个结伴同游的申请人数
/**
 * 统计某个结伴同游的申请人数
 *
 * @param unknown_type $t_companion_id
 * @param unknown_type $verify_status
 * @return boolean number
 */
function tep_count_travel_companion_app_num($t_companion_id, $verify_status = false) {
	if (!(int) $t_companion_id) {
		return false;
	}
	$where_exc = "";
	if ($verify_status != false) {
		$where_exc = ' AND tca_verify_status="' . (int) $verify_status . '" ';
	}
	$sql = tep_db_query('SELECT count(*) as total FROM `travel_companion_application` WHERE t_companion_id="' . (int) $t_companion_id . '" ' . $where_exc);
	$row = tep_db_fetch_array($sql);
	return (int) $row['total'];
}

// 检查某人是否在某贴申请了结伴
function tep_check_travel_companion_app($customers_id, $t_companion_id, $verify_status = false) {
	if (!(int) $t_companion_id) {
		return false;
	}
	if (!(int) $customers_id) {
		return false;
	}
	$where_exc = "";
	if ($verify_status != false) {
		$where_exc = ' AND tca_verify_status="' . (int) $verify_status . '" ';
	}
	$sql = tep_db_query('SELECT count(*) as total FROM `travel_companion_application` WHERE t_companion_id="' . (int) $t_companion_id . '" AND customers_id  	 ="' . $customers_id . '" ' . $where_exc);
	$row = tep_db_fetch_array($sql);
	return (int) $row['total'];
}

// 根据id号取得结伴同游标题
function tep_get_companion_title($t_id) {
	if (!(int) $t_id) {
		return false;
	}
	$sql = tep_db_query('SELECT `t_companion_title` FROM `travel_companion` WHERE t_companion_id = "' . (int) $t_id . '" ');
	$row = tep_db_fetch_array($sql);
	return $row['t_companion_title'];
}

function tep_get_providers_agency_id($providers_id) {
	$qry_get_agency_id = "SELECT providers_agency_id FROM " . TABLE_PROVIDERS_LOGIN . " WHERE providers_id='" . $providers_id . "'";
	$res_get_agency_id = tep_db_query($qry_get_agency_id);
	if (tep_db_num_rows($res_get_agency_id) > 0)
		$row_get_agency_id = tep_db_fetch_array($res_get_agency_id);
	else
		$row_get_agency_id = array ();
	
	return $row_get_agency_id;
}

function tep_get_providers_agency($providers_id, $from_agency_id = '0') // $from_agency_id=1
// for
// direct
// from
// agency
// id else
// from
// providers
// id
{
	$ret_agency = "";
	if ($from_agency_id == '1') {
		$row_get_agency_id = array (
				'providers_agency_id' => $providers_id 
		);
	} else {
		$row_get_agency_id = tep_get_providers_agency_id($providers_id);
	}
	$agency_id_in = "";
	if (count($row_get_agency_id) > 0) {
		if (tep_not_null($row_get_agency_id['providers_agency_id']))
			$agency_id_in = $row_get_agency_id['providers_agency_id'];
		else
			$agency_id_in = 0;
	} else
		$agency_id_in = 0;
	
	$qry_providers_agency = "select a.agency_id, a.agency_name, a.agency_name1 from " . TABLE_TRAVEL_AGENCY . " a where a.agency_id IN (" . $agency_id_in . ")";
	$res_providers_agency = tep_db_query($qry_providers_agency);
	while ($row_providers_agency = tep_db_fetch_array($res_providers_agency)) {
		if ($ret_agency != "")
			$ret_agency .= ", ";
			
			// $ret_agency.=tep_db_output($row_providers_agency['agency_name']);
		$ret_agency .= tep_db_output($row_providers_agency['agency_name1']);
	}
	
	return $ret_agency;
}

/**
 * 取得后台管理人员名称，默认是选工号
 *
 * @param $groups_id
 * @param $user_type=0是我们管理员，1是供应商的管理员
 * @param $type = 'job_num' 是列出工号
 */
function tep_get_admin_customer_name($admin_id = '', $user_type = '0', $type = 'job_num') { // 0=Admin,
	// 1=Providers
	if ($user_type == '1') {
		$the_admin_customer_query = tep_db_query("select p.providers_firstname as admin_firstname, p.providers_lastname as admin_lastname, p.providers_email_address as admin_email_address FROM " . TABLE_PROVIDERS_LOGIN . " p where p.providers_id = '" . $admin_id . "'");
	} else {
		if ($admin_id == CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID)
			return 'CSR System';
		$the_admin_customer_query = tep_db_query("select admin_firstname, admin_lastname, admin_email_address, admin_job_number  from " . TABLE_ADMIN . " where admin_id = '" . $admin_id . "'");
	}
	
	$the_admin_customer = tep_db_fetch_array($the_admin_customer_query);
	
	$str_admin = "";
	if ($type == 'job_num') { // 显示工号
		$str_admin = $the_admin_customer['admin_job_number'];
	}
	if (preg_replace('/[[:space:]]+/', '', $str_admin) == "") { // 工号为空时才使用姓名
		if ($the_admin_customer['admin_firstname'] != '') {
			$str_admin = $the_admin_customer['admin_firstname'] . ' ' . $the_admin_customer['admin_lastname'];
		} else {
			$str_admin = $the_admin_customer['admin_email_address'];
		}
	}
	return $str_admin;
}

function tep_count_providers_users($providers_agency_id) {
	$qry_users_cntr = "SELECT providers_id FROM " . TABLE_PROVIDERS_LOGIN . " WHERE providers_agency_id='" . $providers_agency_id . "'";
	$res_users_cntr = tep_db_query($qry_users_cntr);
	
	return tep_db_num_rows($res_users_cntr);
}

/**
 * 取得订单最新的参团人信息
 *
 * @param unknown_type $orders_products_id
 * @param unknown_type $p_departure_date
 * @return unknown
 */
function tep_get_products_guest_names_lists($orders_products_id, $p_departure_date = '') {
	$str_display_guest_name_list_dis = '';
	$orders_eticket_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_products_id=" . (int) $orders_products_id . " ");
	
	$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
	
	$guestnames = explode('<::>', $orders_eticket_result['guest_name']);
	$guestgenders = explode('<::>', $orders_eticket_result['guest_gender']);
	if ($orders_eticket_result['guest_number'] == 0) {
		foreach ($guestnames as $key => $val) {
			$loop = $key;
		}
	} else {
		$loop = $orders_eticket_result['guest_number'];
	}
	for ($noofguest = 1; $noofguest <= $loop; $noofguest ++) {
		$show_guest_gender = '';
		if (trim($guestgenders[($noofguest - 1)]) != '') {
			$show_guest_gender = ' (' . trim($guestgenders[($noofguest - 1)]) . ')';
		}
		$guest_name_incudes_child_age = explode('||', $guestnames[($noofguest - 1)]);
		if (isset($guest_name_incudes_child_age[1])) {
			
			if ($p_departure_date != '') {
				$di_childage_difference_in_year = @GetDateDifference(trim($guest_name_incudes_child_age[1]), tep_get_date_disp(substr($p_departure_date, 0, 10)));
			}
			$str_display_guest_name_list_dis .= $noofguest . '. ' . stripslashes(tep_filter_guest_chinese_name($guest_name_incudes_child_age[0]) . $show_guest_gender) . '(' . $di_childage_difference_in_year . ')<br />';
		} else {
			$str_display_guest_name_list_dis .= $noofguest . '. ' . stripslashes(tep_filter_guest_chinese_name($guestnames[($noofguest - 1)]) . $show_guest_gender) . '<br />';
		}
	}
	return $str_display_guest_name_list_dis;
}

function tep_datetime_short($raw_datetime) {
	if (($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == ''))
		return false;
	
	$year = (int) substr($raw_datetime, 0, 4);
	$month = (int) substr($raw_datetime, 5, 2);
	$day = (int) substr($raw_datetime, 8, 2);
	$hour = (int) substr($raw_datetime, 11, 2);
	$minute = (int) substr($raw_datetime, 14, 2);
	$second = (int) substr($raw_datetime, 17, 2);
	
	return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
}

function tep_get_provider_order_status_name($orders_status_id, $language_id = '') {
	global $languages_id;
	
	if (!$language_id)
		$language_id = $languages_id;
	$orders_status_query = tep_db_query("select provider_order_status_name from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " where provider_order_status_id = '" . (int) $orders_status_id . "'"); // and
	// language_id
	// =
	// '"
	// .
	// (int)$language_id
	// .
	// "'");
	$orders_status = tep_db_fetch_array($orders_status_query);
	
	return $orders_status['provider_order_status_name'];
}

function tour_code_encode($string) {
	return $string;
	/*
	 * $n = TEXT_TOUR_CODE_ENCODE_ROTATE_VALUE; $length = strlen($string);
	 * $result = ''; for($i = 0; $i < $length; $i++) { $ascii =
	 * ord($string{$i}); $rotated = $ascii; if ($ascii > 64 && $ascii < 91) {
	 * $rotated += $n; $rotated > 90 && $rotated += -90 + 64; $rotated < 65 &&
	 * $rotated += -64 + 90; } elseif ($ascii > 96 && $ascii < 123) { $rotated
	 * += $n; $rotated > 122 && $rotated += -122 + 96; $rotated < 97 && $rotated
	 * += -96 + 122; } $result .= chr($rotated); } return $result;
	 */
}

function tep_get_tour_agency_name_from_product_id($products_id) {
	$check_tour_agency_name_query = tep_db_query("select ta.agency_name  from " . TABLE_TRAVEL_AGENCY . " as ta, " . TABLE_PRODUCTS . " p where p.agency_id = ta.agency_id and p.products_id = '" . $products_id . "'");
	$check_tour_agency_name = tep_db_fetch_array($check_tour_agency_name_query);
	return $check_tour_agency_name['agency_name'];
}

function tep_get_products_eticket_itinerary($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_itinerary from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $product_id . "' and language_id = '" . (int) $language_id . "'");
	$product = tep_db_fetch_array($product_query);
	
	return $product['eticket_itinerary'];
}

function tep_get_products_eticket_hotel($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_hotel from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $product_id . "' and language_id = '" . (int) $language_id . "'");
	$product = tep_db_fetch_array($product_query);
	
	return $product['eticket_hotel'];
}

function tep_get_products_eticket_notes($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_notes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $product_id . "' and language_id = '" . (int) $language_id . "'");
	$product = tep_db_fetch_array($product_query);
	
	return $product['eticket_notes'];
}

function date_add_day($length, $format, $date_passed) {
	$new_timestamp = -1;
	if ($date_passed != '') {
		
		$date_passed_array = explode('/', $date_passed);
		$date_actual["mon"] = $date_passed_array[0];
		$date_actual["mday"] = $date_passed_array[1];
		$date_actual["year"] = $date_passed_array[2];
		
		switch (strtolower($format)) {
			case 'd':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"], $date_actual["mday"] + $length, $date_actual["year"]);
				break;
			case 'm':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"] + $length, $date_actual["mday"], $date_actual["year"]);
				break;
			case 'y':
				$new_timestamp = @mktime(0, 0, 0, $date_actual["mon"], $date_actual["mday"], $date_actual["year"] + $length);
				break;
			default:
				break;
		}
		
		return @date('m/d/Y', $new_timestamp);
	} else {
		return '';
	}
}

// howard added
/* 取得产品行程天数 */
function get_products_departure_date_num($products_id) {
	if (!(int) $products_id) {
		return 0;
	}
	$sql = tep_db_query('SELECT products_durations, products_durations_type FROM `products` WHERE products_id="' . (int) $products_id . '" limit 1');
	$row = tep_db_fetch_array($sql);
	if ((int) $row['products_durations_type'] == 0) {
		return $row['products_durations'];
	} elseif ((int) $row['products_durations_type'] == 1) {
		return $row['products_durations'] / 24;
	} elseif ((int) $row['products_durations_type'] == 2) {
		return $row['products_durations'] / 24 / 60;
	}
}

// 更新结伴同游帖子的订单是否已经下单,无任何返回值。
// 如果某人在该产品下面已发多贴，那么这些贴的orders id 将会被同时更新。
function tep_update_travel_companion_orders($customers_id, $t_companion_id, $products_id, $orders_id) {
	if (!(int) $customers_id || (!(int) $t_companion_id && !(int) $products_id)) {
		return false;
	}
	if ((int) $t_companion_id) {
		tep_db_query('update `travel_companion` set orders_id ="' . (int) $orders_id . '" WHERE t_companion_id ="' . (int) $t_companion_id . '" and products_id="' . (int) $products_id . '" and customers_id ="' . (int) $customers_id . '" ');
	} elseif ((int) $products_id) {
		tep_db_query('update `travel_companion` set orders_id ="' . (int) $orders_id . '" WHERE products_id="' . (int) $products_id . '" and customers_id ="' . (int) $customers_id . '" ');
	}
}

function get_if_display_provider_status_history($products_id, $orders_id) {
	$qry_is_provider_active = "SELECT ta.providers_display_status_hist, ta.providers_start_date, o.date_purchased, DATEDIFF(o.date_purchased, ta.providers_start_date) as date_diff_from_today FROM " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE ta.agency_id = p.agency_id AND p.products_id = op.products_id AND op.orders_id = o.orders_id AND op.orders_id = '" . $orders_id . "' AND p.products_id = '" . $products_id . "'";
	$res_is_provider_active = tep_db_query($qry_is_provider_active);
	$row_is_provider_active = tep_db_fetch_array($res_is_provider_active);
	if ($row_is_provider_active['providers_display_status_hist'] == '1' && $row_is_provider_active['date_diff_from_today'] >= 0) {
		$display_providers_comment = 1;
	} else {
		$display_providers_comment = 0;
	}
	
	return $display_providers_comment;
}

function get_provider_can_send_eticket($agency_id) {
	$qry_provider_can_send_eticket = "SELECT providers_can_send_eticket FROM " . TABLE_TRAVEL_AGENCY . " WHERE agency_id='" . $agency_id . "'";
	$res_provider_can_send_eticket = tep_db_query($qry_provider_can_send_eticket);
	$row_provider_can_send_eticket = tep_db_fetch_array($res_provider_can_send_eticket);
	
	return (int) $row_provider_can_send_eticket['providers_can_send_eticket'];
}

// 取得客户的电话号码是否公开
function tep_get_customers_show_phone($customers_id) {
	$sql = tep_db_query('SELECT telephone_secret FROM `customers` WHERE customers_id ="' . (int) $customers_id . '" limit 1 ');
	$row = tep_db_fetch_array($sql);
	return $row['telephone_secret'];
}
// 取得客户的电子邮箱是否公开
function tep_get_customers_show_email($customers_id) {
	$sql = tep_db_query('SELECT email_secret FROM `customers` WHERE customers_id ="' . (int) $customers_id . '" limit 1 ');
	$row = tep_db_fetch_array($sql);
	return $row['email_secret'];
}

// 为弹出层添加圆角样式$tag有top和foot两个值
function tep_pop_div_add_table($tag) {
	$top = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="jb_xj_t1"></td>
              <td class="jb_xj_t2"></td>
              <td class="jb_xj_t3"></td>
            </tr>
            <tr>
              <td class="jb_xj_m"></td>
              <td class="jb_xj_content">
	';
	$foot = '
		 </td>
	  <td class="jb_xj_m"></td>
	</tr>
	<tr>
	  <td class="jb_xj_b1"></td>
	  <td class="jb_xj_t2"></td>
	  <td class="jb_xj_b3"></td>
	</tr>
	</table>

	';
	if ($tag == 'top' || $tag == "0" || !tep_not_null($tag)) {
		return $top;
	}
	if ($tag == 'foot' || $tag == "1") {
		return $foot;
	}
	return false;
}
// 新版圆角提示层
function tep_pop_div_add_table_now($tag, $popupConCompare = "popupConCompare", $width = 528) {
	$top = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
			<tr>
			  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
				<td class="con">
				<div class="popupCon" id="' . $popupConCompare . '" style="width:' . $width . 'px; ">

	';
	$foot = '	</div>
			</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>

	';
	if ($tag == 'top' || $tag == "0" || !tep_not_null($tag)) {
		return $top;
	}
	if ($tag == 'foot' || $tag == "1") {
		return $foot;
	}
	return false;
}

// 自动载入结伴同游已经同意的申请客户
function auto_load_travel_companion_customers_info($product_id, $departure_date, $t_companion_id = 0) {
	global $customer_id;
	$t_array = array ();
	$product_id = tep_get_prid($product_id);
	$where_exc = "";
	if ((int) $t_companion_id) { // t_companion_id 比departure_date优先
		$where_exc .= " and t_companion_id='" . (int) $t_companion_id . "' ";
	} elseif ($departure_date) {
		$where_exc .= " and hope_departure_date='" . $departure_date . "' ";
	}
	
	$sql_string = 'SELECT * FROM `travel_companion` WHERE customers_id ="' . (int) $customer_id . '" and products_id="' . $product_id . '" ' . $where_exc . ' and orders_id<1 Order By t_companion_id DESC LIMIT 1';
	$sql = tep_db_query($sql_string);
	// echo $sql_string;
	$row = tep_db_fetch_array($sql);
	if ((int) $row['t_companion_id']) {
		$t_array['who_payment'] = (int) $row['who_payment'];
		$app_sql = tep_db_query('SELECT * FROM `travel_companion_application` where t_companion_id="' . (int) $row['t_companion_id'] . '" and tca_verify_status="1" and customers_id>1 ');
		while ($app = tep_db_fetch_array($app_sql)) {
			$t_array['app'][] = array (
					'customers_id' => $app['customers_id'],
					'customers_cn_name' => $app['tca_cn_name'],
					'customers_en_name' => $app['tca_en_name'],
					'customers_email' => tep_get_customers_email($app['customers_id']) 
			);
		}
		return $t_array;
	}
	return false;
}

function tep_is_gift_certificate_product($products_id) {
	/*
	 * //amit commented because no gift certificate on TFF $qry_is_gc="SELECT
	 * pc.* FROM ".TABLE_PRODUCTS_TO_CATEGORIES." pc WHERE
	 * pc.products_id='".(int)$products_id."' AND
	 * pc.categories_id='".GIFT_CERTIFICATE_CAT_ID."'";
	 * $res_is_gc=tep_db_query($qry_is_gc); if(tep_db_num_rows($res_is_gc))
	 * return true; else return false;
	 */
	return false;
}

function tep_get_products_provider_name($product_id, $language = '') {
	global $languages_id;
	
	if (empty($language))
		$language = $languages_id;
	$product_query = tep_db_query("select products_name_provider from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $product_id . "' and language_id = '" . (int) $language . "'");
	$product = tep_db_fetch_array($product_query);
	
	return preg_replace('/(.+)\*\*.+/', '$1', $product['products_name_provider']); // 给供应商显示的产品名称不含副标题
}

/**
 * 自动计算团购产品的优惠折扣
 *
 * @param $product_id
 * @author Howard
 */
function auto_get_group_buy_discount($product_id) {
	// 20% > GP ≥13%时，团购折扣为3%，GP≥20%时，团购折扣为5%，GP<13%时没有优惠
	$product_id = tep_get_prid($product_id);
	if (!(int) $product_id) {
		return 0;
	}
	$sql = tep_db_query('SELECT products_margin FROM `products` WHERE `products_id` = "' . (int) $product_id . '" limit 1');
	$row = tep_db_fetch_array($sql);
	if ($row['products_margin'] >= 20) {
		return 0.05;
	} elseif ($row['products_margin'] >= 13) {
		return 0.03;
	} else {
		return 0;
	}
}

/**
 * 判断某个产品是否属于团购产品
 *
 * @param $products_id
 * @return false or int 1,2
 * @author Howard
 */
function is_group_buy_product($products_id) {
	$Today_date = date("Y-m-d H:i:s");
	if (!(int) $products_id) {
		return false;
	}
	$sql = tep_db_query('SELECT s.specials_type FROM specials s, products p WHERE s.products_id = "' . (int) $products_id . '" AND s.products_id = p.products_id AND  p.products_stock_status="1" AND s.status="1" AND s.specials_type>=1 AND s.start_date <="' . $Today_date . '" AND s.expires_date >"' . $Today_date . '" ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['specials_type'];
}

/**
 * 取得在下单时某个产品团购的类型，只用于checkout_process.php和checkout_process.inc.php
 *
 * @param $products_id
 * @author Howard
 */
function get_specials_type($is_new_group_buy, $products_id) {
	if (!(int) $products_id || !(int) $is_new_group_buy) {
		return false;
	}
	$sql = tep_db_query('SELECT s.specials_type FROM specials s WHERE s.products_id = "' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['specials_type'];
}

function is_double_booked($orders_id, $products_id = '') {
	$is_double_book_bool = false;
	$where_extra_products = "";
	if ((int) $products_id > 0) {
		$where_extra_products = " and op.products_id='" . (int) $products_id . "'";
	}
	$order_prod_sql = "SELECT op.products_departure_date, op.products_model, op.orders_id, op.products_id, o.customers_id, ope.guest_name FROM " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_ORDERS . " AS o, " . TABLE_ORDERS_PRODUCTS_ETICKET . " AS ope WHERE o.orders_id= op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND o.orders_status != 6 AND op.orders_id='" . (int) $orders_id . "' " . $where_extra_products . " ";
	$order_prod_query = tep_db_query($order_prod_sql);
	while ($order_prod_row = tep_db_fetch_array($order_prod_query)) {
		
		$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_id FROM " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_ORDERS . " AS o, " . TABLE_ORDERS_PRODUCTS_ETICKET . " AS ope WHERE o.orders_id=op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.products_departure_date ='" . $order_prod_row['products_departure_date'] . "' AND o.customers_id='" . $order_prod_row['customers_id'] . "' AND ope.guest_name='" . addslashes($order_prod_row['guest_name']) . "' AND op.products_departure_date!='0000-00-00 00:00:00' AND op.products_model='" . $order_prod_row['products_model'] . "' AND op.orders_id !='" . (int) $orders_id . "' AND o.orders_status != 6 AND op.orders_id > 0 " . $where_extra_products . " ";
		$check_double_book_query = tep_db_query($check_double_book_sql);
		if ($check_double_book_row = tep_db_fetch_array($check_double_book_query)) {
			$is_double_book_bool = true;
			$is_double_book_bool_array[1] = '<a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . (int) $check_double_book_row['customers_id']) . '"><img src="' . DIR_WS_IMAGES . 'dbl_book_txt.gif" border="0" /></a>';
		}
	}
	$is_double_book_bool_array[0] = $is_double_book_bool;
	return $is_double_book_bool_array;
}

// 取得并判断产品的子供应商
function get_provider_tour_code_sub($products_id) {
	$sql = tep_db_query('SELECT provider_tour_code_sub FROM `products` WHERE products_id ="' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	if (tep_not_null($row['provider_tour_code_sub'])) {
		$array = explode(';', $row['provider_tour_code_sub']);
		return $array;
	}
	return false;
}

// amit added to autho charged autorized.net order start
function auto_charged_authorized_net_order($x_type_req = 'PRIOR_AUTH_CAPTURE') {
	global $response_auth_trans_id;
	$response_charged[0] = '0';
	if ($response_auth_trans_id != 0 && $response_auth_trans_id != '') {
		unset($auto_form_data);
		// Austin519 - added transaction key, ccmode
		$auto_form_data = array (
				x_Login => MODULE_PAYMENT_AUTHORIZENET_LOGIN,
				x_Tran_Key => MODULE_PAYMENT_AUTHORIZENET_TRANSKEY,
				x_Delim_Data => 'TRUE',
				x_Version => '3.1',
				x_trans_id => $response_auth_trans_id,
				x_Type => $x_type_req,
				x_Method => 'CC',
				x_Relay_Response => 'FALSE' 
		);
		
		// concatenate order information variables to $charge_request_data
		while (list ($key, $value) = each($auto_form_data)) {
			$charge_request_data .= $key . '=' . urlencode(ereg_replace(',', '', $value)) . '&';
		}
		
		// take the last & out for the string
		$charge_request_data = substr($charge_request_data, 0, -1);
		unset($response_charged);
		
		if (MODULE_PAYMENT_AUTHORIZENET_CURL == 'Not Compiled') {
			if (function_exists('exec')) {
				exec('which curl', $curl_output);
				if ($curl_output) {
					$curl_path = $curl_output[0];
				} else {
					$curl_path = MODULE_PAYMENT_AUTHORIZENET_CURL_PATH;
				}
			}
			if ((MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') || (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug')) {
				exec("$curl_path -d \"$charge_request_data\" https://certification.authorize.net/gateway/transact.dll", $response_charged);
			} else {
				exec("$curl_path -d \"$charge_request_data\" https://secure.authorize.net/gateway/transact.dll", $response_charged);
			}
		} else {
			if ((MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') || (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test And Debug')) {
				$charged_req_url = "https://certification.authorize.net/gateway/transact.dll";
			} else {
				$charged_req_url = "https://secure.authorize.net/gateway/transact.dll";
			}
			$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
			$ch_req = curl_init();
			curl_setopt($ch_req, CURLOPT_URL, $charged_req_url);
			curl_setopt($ch_req, CURLOPT_VERBOSE, 1);
			curl_setopt($ch_req, CURLOPT_POST, 1);
			curl_setopt($ch_req, CURLOPT_POSTFIELDS, $charge_request_data);
			curl_setopt($ch_req, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch_req, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch_req, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch_req, CURLOPT_SSL_VERIFYPEER, FALSE); // Windows 2003
			// Compatibility
			$auto_charged_authorize = curl_exec($ch_req);
			curl_close($ch_req);
			$response_charged = explode(',', $auto_charged_authorize);
		}
	} // end of if of transation id
	

	return $response_charged;
}
// amit added to autho charged autorized.net order end
function get_double_booked_orders_ids_payment_method($orders_id, $products_id = '') {
	$where_extra_products = "";
	if ((int) $products_id > 0) {
		$where_extra_products = " and op.products_id='" . (int) $products_id . "'";
	}
	$order_prod_sql = "SELECT op.products_departure_date, op.products_model, op.orders_id, op.products_id, o.customers_id, ope.guest_name FROM " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_ORDERS . " AS o, " . TABLE_ORDERS_PRODUCTS_ETICKET . " AS ope WHERE o.orders_id= op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND o.orders_status != 6 AND op.orders_id='" . (int) $orders_id . "'  " . $where_extra_products . " ";
	$order_prod_query = tep_db_query($order_prod_sql);
	$li = 0;
	while ($order_prod_row = tep_db_fetch_array($order_prod_query)) {
		
		$check_double_book_sql = "SELECT op.orders_id, o.customers_name, o.customers_id, o.orders_status, o.payment_method FROM " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_ORDERS . " AS o, " . TABLE_ORDERS_PRODUCTS_ETICKET . " AS ope WHERE o.orders_id=op.orders_id AND op.orders_id= ope.orders_id AND op.products_id= ope.products_id AND op.products_departure_date ='" . $order_prod_row['products_departure_date'] . "' AND o.customers_id='" . $order_prod_row['customers_id'] . "' AND ope.guest_name='" . addslashes($order_prod_row['guest_name']) . "' AND op.products_departure_date!='0000-00-00 00:00:00' AND op.products_model='" . $order_prod_row['products_model'] . "' AND op.orders_id !='" . (int) $orders_id . "' AND o.orders_status != 6 AND op.orders_id > 0 " . $where_extra_products . " ";
		$check_double_book_query = tep_db_query($check_double_book_sql);
		while ($check_double_book_row = tep_db_fetch_array($check_double_book_query)) {
			$get_double_book_list_array[$li]['orders_id'] = $check_double_book_row['orders_id'];
			$get_double_book_list_array[$li]['orders_status'] = $check_double_book_row['orders_status'];
			$get_double_book_list_array[$li]['payment_method'] = $check_double_book_row['payment_method'];
			$li ++;
		}
	}
	
	return $get_double_book_list_array;
}

/**
 * 显示客人信息和发出日期上车地址等信息的更新历史
 *
 * @param unknown_type $orders_products_id
 * @return unknown
 */
function show_histories_action($orders_products_id) {
	$sql_select_history = "select has_confirmed, op_histoty_id, op_order_products_ids,op_history_departure_date_loc,op_history_guest_name,op_history_lodging,op_history_modify_by_id,op_modify_date from " . ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY . " where op_order_products_ids = '" . $orders_products_id . "' order by op_histoty_id DESC";
	$get_histories = tep_db_query($sql_select_history);
	$ret_show_history = '';
	$inner_table = '';
	$inner_table_lodging = '';
	$k = 0;
	$ret_show_history .= '<span><a class="thumbnail2 col_a1_popup" href="javascript:void(0);"><nobr><u>客人信息更新历史记录</u></nobr><span style="width:850px;text-align:left;">
				<table class="dataTableContent" border="0" width="100%">
					<tr>
						<td valign="top" nowrap="nowrap"><b>序号</b></td>
						<td valign="top"><b>游客</b></td>
						<td valign="top"><b>住宿</b></td>
						
						<td valign="top"><b>最后修改时间</b></td>
					</tr>';
	$haveRow = false;
	while ($row_updated_history = tep_db_fetch_array($get_histories)) {
		$k ++;
		$haveRow = true;
		// # GUEST NAME START HERE
		$guestnames = explode('<::>', $row_updated_history['op_history_guest_name']);
		$inner_table = '<table border="0" cellspacing="0" cellpadding="0">';
		for ($i = 0; $i < sizeof($guestnames); $i ++) {
			$cust_name_bdate_arr = explode('||', $guestnames[$i]);
			if ($cust_name_bdate_arr[0] != '') {
				$inner_table .= '<tr><td>' . stripslashes(tep_filter_guest_chinese_name($cust_name_bdate_arr[0])) . '&nbsp;&nbsp;' . $cust_name_bdate_arr[1];
				if ($cust_name_bdate_arr[1] != '') {
					$inner_table .= '(Birth Date)';
				}
				$inner_table .= '</td></tr>';
			}
		}
		$inner_table .= '</table>';
		// # GUEST NAME END HERE
		// # LODING START HERE
		$room_total_adults = 0;
		$room_total_children = 0;
		$total_room = get_total_room_from_str($row_updated_history['op_history_lodging']);
		if ($total_room > 0) {
			$inner_table_lodging = '<table width="100%" cellspacing="0" cellpadding="0" border="0" >';
			$inner_table_lodging .= '<tr><td class="p_l1 tab_t_bg">' . TXT_SHOW_HISTORY_ROOMS . '</td><td class="tab_t_bg ">' . TXT_SHOW_HISTORY_ADULT . '</td><td class="tab_t_bg">' . TXT_SHOW_HISTORY_CHILD . '</td><td>&nbsp;</td><tr>';
			for ($t = 1; $t <= $total_room; $t ++) {
				$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_updated_history['op_history_lodging'], $t);
				$room_total_adults = $room_total_adults + $chaild_adult_no_arr[0];
				$room_total_children = $room_total_children + $chaild_adult_no_arr[1];
				$inner_table_lodging .= '<tr><td class="p_l1 order_default">第' . $t . '房间</td><td class="order_default">' . $chaild_adult_no_arr[0] . '</td><td class="order_default">' . $chaild_adult_no_arr[1] . '</td><td>&nbsp;</td></tr>';
			}
			$inner_table_lodging .= '</table>';
		} else {
			$inner_table_lodging = '<table width="100%" cellspacing="0" cellpadding="0" border="0">
		 <tr><td class="tab_t_bg ">' . TXT_SHOW_HISTORY_ADULT . '</td><td class="tab_t_bg ">' . TXT_SHOW_HISTORY_CHILD . '</td></tr>';
			$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_updated_history['op_history_lodging'], 1);
			$total_adults = $chaild_adult_no_arr[0];
			$total_children = $chaild_adult_no_arr[1];
			
			$room_total_adults = $room_total_adults + $total_adults;
			$room_total_children = $room_total_children + $total_children;
			$inner_table_lodging .= '<tr><td class="order_default">' . $total_adults . '</td><td class="order_default">' . $total_children . '</td></tr>';
			$inner_table_lodging .= '</table>';
		}
		// # LODING END HERE
		$_style = 'display:none;';
		if ($row_updated_history['has_confirmed'] == '1')
			$_style = '';
		$_class = ' class="history1" ';
		$_sort_str = (($k == 1) ? '最后一次更新' : $k);
		if ($k > 1) {
			$_class = ' class="history2" ';
		}
		
		$ret_show_history .= '<tr ' . $_class . ' style="' . $_style . '">
								<td valign="top"><b>' . $_sort_str . '</b>&nbsp;</td>
								<td valign="top">' . $inner_table . '&nbsp;</td>
								<td valign="top">' . $inner_table_lodging . '&nbsp;</td>
								
								<td valign="top">' . tep_datetime_short($row_updated_history['op_modify_date']) . '&nbsp;</td>
							</tr>';
	}
	$ret_show_history .= '</table></span></a></span>';
	
	if ($haveRow === false) {
		$ret_show_history = '';
	}
	return $ret_show_history;
}

function tep_get_departure_location_history_str($orders_products_id) {
	
	// 取得上车时间地址历史数据 {
	$departure_location_history_html = '';
	$departure_location_history = tep_get_departure_location_history($orders_products_id);
	if ($departure_location_history != false && sizeof($departure_location_history) > 1) {
		$departure_location_history_html .= '<a href="javascript:void(0);" class="thumbnail2"><strong><nobr><u>[出团日期上车地址历史记录]</u></nobr></strong><span>';
		$departure_location_history_html .= '
		<table width="100%" border="0" class="dataTableContent">
			<tbody>
			<tr>
			<th scope="col" nowrap>时间地址(最新在最前)</th>

			<th scope="col" nowrap class="col_h">添加日期</th>
			</tr>
			';
		
		$_class = ' class="history1" ';
		foreach ($departure_location_history as $_key => $_val) {
			$_style = 'display:none';
			if ($_val['has_confirmed'] == '1')
				$_style = '';
			$departure_location_history_html .= '
			<tr ' . $_class . ' style="' . $_style . '">
				<td align="left" nowrap>' . tep_db_output($_val['departure_location']) . '</td>

				<td align="center" nowrap class="col_h">' . date('Y-m-d', strtotime($_val['added_time'])) . '</td>
			</tr>
			';
			$_class = ' class="history2" ';
		}
		$departure_location_history_html .= '</tbody></table>';
		$departure_location_history_html .= '</span></a>';
	}
	// 取得上车时间地址历史数据 }
	return $departure_location_history_html;
}

// 取得美元兑人民币的比率
function get_value_usd_to_cny() {
	$confirm = false;
	$rate = 0;
	$sql = tep_db_query('SELECT code, value FROM `currencies` WHERE code="USD" || code="CNY" ');
	while ($rows = tep_db_fetch_array($sql)) {
		if ($rows['code'] == "USD" && (int) $rows['value'] == 1) {
			$confirm = true;
		}
		if ($rows['code'] == "CNY" && (int) $rows['value'] != 0) {
			$rate = $rows['value'];
		}
	}
	if ($confirm == true) {
		return $rate;
	}
	return false;
}

//
/**
 * 取得某产品至少要提前几天或几个小时购买，返回int
 *
 * @param $products_id
 * @author Howard
 */
function get_products_advance($products_id) {
	// howard added advance set start
	$advance = 2; // 默认可提前几天购买
	$advance_sql = tep_db_query("select agency_id, book_limit_days_number, with_air_transfer from " . TABLE_PRODUCTS . " where products_id = " . (int) $products_id . " limit 1 ");
	$advance_row = tep_db_fetch_array($advance_sql);
	if ((int) $advance_row['book_limit_days_number']) {
		$advance = (int) $advance_row['book_limit_days_number'];
	} elseif ((int) $advance_row['agency_id']) {
		
		$agency_sql = tep_db_query('select book_limit_days, book_limit_days_type, book_limit_days_air, book_limit_days_type_air from ' . TABLE_TRAVEL_AGENCY . ' where agency_id="' . (int) $advance_row['agency_id'] . '" limit 1');
		$agency = tep_db_fetch_array($agency_sql);
		
		if ($advance_row['with_air_transfer'] == "1") { // 接机团
			if ((int) $agency['book_limit_days_air']) {
				$advance = (int) $agency['book_limit_days_air'];
				if ($agency['book_limit_days_type_air'] == "hours") {
					$time = time();
					$to_day = date("Y-m-d H:i:s", $time);
					$min_can_book_date = date('Y-m-d H:i:s', $time + (int) $agency['book_limit_days_air'] * 60 * 60);
					$final_min_can_book_date = date("Y-m-d", strtotime($min_can_book_date) + 24 * 60 * 60 - 1);
					$advance = (int) (strtotime($final_min_can_book_date) + 24 * 60 * 60 - $time) / (24 * 60 * 60);
					if (!(int) $advance) {
						$advance = 1;
					}
				}
			}
		} else { // 非接机团
			if ((int) $agency['book_limit_days']) {
				$advance = (int) $agency['book_limit_days'];
				if ($agency['book_limit_days_type'] == "hours") {
					$time = time();
					$to_day = date("Y-m-d H:i:s", $time);
					$min_can_book_date = date('Y-m-d H:i:s', $time + (int) $agency['book_limit_days'] * 60 * 60);
					$final_min_can_book_date = date("Y-m-d", strtotime($min_can_book_date) + 24 * 60 * 60 - 1);
					$advance = (int) (strtotime($final_min_can_book_date) + 24 * 60 * 60 - $time) / (24 * 60 * 60);
					if (!(int) $advance) {
						$advance = 1;
					}
				}
			}
		}
	}
	return (int) $advance;
	// howard added advance set end
}

// 自动将用户登录前收藏的产品更新到数据库
function auto_add_session_to_favorites() {
	global $customer_id;
	
	for ($i = 0; $i < sizeof($_SESSION['favorites']); $i ++) {
		$product_id = (int) $_SESSION['favorites'][$i]['products_id'];
		
		$check_sql = tep_db_query('SELECT customers_favorites_id FROM `customers_favorites` WHERE products_id="' . $product_id . '" and customers_id="' . $customer_id . '" Limit 1 ');
		$check = tep_db_fetch_array($check_sql);
		if ((int) $check['customers_favorites_id']) {
			tep_db_query('UPDATE `customers_favorites` SET `updated_time`=NOW() WHERE products_id="' . $product_id . '" and customers_id="' . $customer_id . '" ;');
		} else {
			tep_db_query('INSERT INTO `customers_favorites` (`products_id`,`customers_id`,`added_time`,`updated_time`) VALUES ( ' . $product_id . ', ' . $customer_id . ', NOW(), NOW());');
		}
	}
	unset($_SESSION['favorites']);
}

// 取得满意度信息返回数组
function get_ratings_datas($rating_avg) {
	$max_star_num = 5;
	$star_num = $max_star_num;
	$rating_str = RATING_STR_5;
	if ($rating_avg < 100) {
		$star_num = 4;
		$rating_str = RATING_STR_4;
	}
	if ($rating_avg < 80) {
		$star_num = 3;
		$rating_str = RATING_STR_3;
	}
	if ($rating_avg < 60) {
		$star_num = 2;
		$rating_str = RATING_STR_2;
	}
	if ($rating_avg < 40) {
		$star_num = 1;
		$rating_str = '';
		if ((int) $rating_avg) {
			$rating_str = RATING_STR_1;
		}
	}
	$rating_img_stars = "";
	for ($i = 1; $i <= $max_star_num; $i ++) {
		if ($star_num >= $i) {
			$rating_img_stars .= '<img src="image/icons/icon_star_1.gif" alt="" title="" />';
		} else {
			$rating_img_stars .= '<img src="image/icons/icon_star_2.gif" alt="" title=""/>';
		}
	}
	return array (
			$rating_img_stars,
			$rating_str 
	);
}

// 检测用户是否购买过某个产品
function check_customers_purchase($customers_id, $product_id) {
	if (!(int) $customers_id || !(int) $product_id) {
		return false;
	}
	$sql = tep_db_query('SELECT o.orders_id FROM `orders` o, orders_products op WHERE o.orders_id = op.orders_id and o.customers_id = "' . (int) $customers_id . '" and op.products_id = "' . (int) $product_id . '" Limit 1');
	$row = tep_db_fetch_array($sql);
	return (int) $row['orders_id'];
}

function tep_auto_cancelled_zero_price($orders_id) {
	$orders_products_query = tep_db_query("select op.products_id, op.orders_products_id, op.products_model, op.products_name , op.final_price, op.final_price_cost, op.customer_invoice_no, op.customer_invoice_total from " . TABLE_ORDERS_PRODUCTS . " op where op.orders_id = '" . (int) $orders_id . "' and (op.final_price > 0 || op.final_price_cost > 0) order by op.orders_products_id asc");
	while ($orders_products_detail = tep_db_fetch_array($orders_products_query)) {
		
		// update pricing history table start
		$sql_data_array_original_insert = array (
				'orders_products_id' => $orders_products_detail['orders_products_id'],
				'products_model' => $orders_products_detail['products_model'],
				'products_name' => tep_db_input($orders_products_detail['products_name']),
				'retail' => ($orders_products_detail['final_price'] * -1),
				'cost' => ($orders_products_detail['final_price_cost'] * -1),
				'invoice_number' => $orders_products_detail["customer_invoice_no"],
				'invoice_amount' => $orders_products_detail["customer_invoice_total"],
				'comment' => 'Auto Updated Zero due to Double Booking!!###!!Auto Updated Zero due to Double Booking',
				'updated_by' => CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,
				'last_updated_date' => 'now()' 
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, $sql_data_array_original_insert);
		// update pricing history table end
		

		// update order total and order cost to 0 start
		$sql_data_array = array (
				'order_cost' => '0' 
		);
		tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id='" . (int) $orders_id . "'");
		
		$sql_data_array = array (
				'text' => '$0.00',
				'value' => '0' 
		);
		tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array, 'update', "orders_id='" . (int) $orders_id . "'");
		// update order total and order cost to 0 end
		

		// canclled reward points of order start
		if ((USE_POINTS_SYSTEM == 'true') && !tep_not_null(POINTS_AUTO_ON)) {
			$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status != 3 and points_status != 4 and orders_id = '" . (int) $orders_id . "' limit 1");
			$customer_points = tep_db_fetch_array($customer_query);
			if (tep_db_num_rows($customer_query)) {
				$set_comment = ", points_comment = 'Cancelled'";
				tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 3 " . $set_comment . " where orders_id = '" . (int) $orders_id . "' and unique_id = '" . $customer_points['unique_id'] . "'");
				tep_auto_fix_customers_points((int) $customer_points['customer_id']); // 自动校正用户积分
			}
		}
		// canclled reward points of order end
		

		// auto confirm refunded used redeem points start
		$customer_query = tep_db_query("select unique_id, customer_id, points_pending, points_status from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_type = 'SP' and points_status = 4 and points_pending < 0 and orders_id = '" . (int) $orders_id . "' limit 1");
		$customer_points = tep_db_fetch_array($customer_query);
		if (tep_db_num_rows($customer_query)) {
			
			$set_comment = ", points_pending='0.00' ";
			
			tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 4 " . $set_comment . " where orders_id = '" . (int) $orders_id . "' and unique_id = '" . $customer_points['unique_id'] . "'");
			
			tep_auto_fix_customers_points((int) $customer_points['customer_id']); // 自动校正用户积分
		}
		// auto confirm refunded used redeem points end
		

		// update order product table start
		$sql_data_array = array (
				'final_price' => '0.00',
				'final_price_cost' => '0' 
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array, 'update', "orders_products_id='" . $orders_products_detail['orders_products_id'] . "'");
		// update order product table end
		

		// update order product attribute start
		$sql_data_array = array (
				'options_values_price' => '0',
				'options_values_price_cost' => '0' 
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', " orders_id='" . (int) $orders_id . "' and orders_products_id='" . $orders_products_detail['orders_products_id'] . "'");
		// update order product attribute end
	}
	return true;
}

function tep_get_product_name_by_order_id($order_id) {
	$display_all_tours_name_code = '';
	$product_query = tep_db_query("select op.products_id, op.products_name,op.products_model from " . TABLE_ORDERS . " o,  " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id = op.orders_id and op.orders_id = '" . $order_id . "'");
	while ($rows = tep_db_fetch_array($product_query)) {
		$display_all_tours_name_code .= '<p><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $rows['products_id']) . '" target="_blank">' . db_to_html($rows['products_name']) . ' [' . db_to_html($rows['products_model']) . ']</a></p>';
	}
	return $display_all_tours_name_code;
}

/**
 * 取得客户存在我们网站的信用余额
 *
 * @author amit
 */
function tep_get_customer_credits_balance($customer_id) {
	$get_customer_credits = tep_db_query("select customers_credit_issued_amt from " . TABLE_CUSTOMERS . " where customers_id = '" . (int) $customer_id . "'");
	$customer_credits_balance = 0;
	if (tep_db_num_rows($get_customer_credits) > 0) {
		$row_customer_credits = tep_db_fetch_array($get_customer_credits);
		$customer_credits_balance = $row_customer_credits['customers_credit_issued_amt'];
	}
	return $customer_credits_balance;
}

// 取得某个客户的推荐人ID
function get_recommend_customer_id($customer_id) {
	$sql = tep_db_query("select recommend_customer_id from " . TABLE_CUSTOMERS . " where customers_id = '" . (int) $customer_id . "'");
	$row = tep_db_fetch_array($sql);
	return $row['recommend_customer_id'];
}

function check_is_featured_deal($products_id) {
	$check_featured_exist = tep_db_query("select featured_deals_new_products_price from " . TABLE_FEATURED_DEALS . " where products_id = '" . (int) $products_id . "' and featured_products='1'");
	if (tep_db_num_rows($check_featured_exist) > 0) {
		$is_exist = 1;
	} else {
		$is_exist = 0;
	}
	return $is_exist;
}

function tep_get_featured_deal_price($products_id) {
	$get_featured_deal_price = tep_db_query("select featured_deals_new_products_price from " . TABLE_FEATURED_DEALS . " where products_id = '" . (int) $products_id . "'");
	$featured_deal_new_price = tep_db_fetch_array($get_featured_deal_price);
	$featured_deal_price = array ();
	$featured_deal_price[0] = array (
			'id' => '0',
			'text' => $featured_deal_new_price['featured_deals_new_products_price'] 
	);
	
	$get_featured_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '" . (int) $products_id . "' order by peple_no asc");
	if (tep_db_num_rows($get_featured_discount_data) > 0) {
		while ($featured_discount_data = tep_db_fetch_array($get_featured_discount_data)) {
			$featured_deal_price[] = array (
					'id' => $featured_discount_data['peple_no'],
					'text' => $featured_discount_data['adj_price'] 
			);
		}
	}
	return $featured_deal_price;
}

function tep_get_featured_total_guests_booked_this_deal($products_id) {
	$featured_orders_info_sql = tep_db_query("select products_room_info, total_room_adult_child_info, orders_id, final_price from " . TABLE_ORDERS_PRODUCTS . " where is_diy_tours_book = '2' and group_buy_discount > 0 and products_id = '" . (int) $products_id . "' group by orders_id");
	$featured_total_guest_booked = 0;
	while ($featured_orders_info = tep_db_fetch_array($featured_orders_info_sql)) {
		$featured_orders_info['total_room_adult_child_info'];
		$total_rooms = get_total_room_from_str($featured_orders_info['total_room_adult_child_info']);
		$total_guest = 0;
		if ($total_rooms > 0) {
			for ($t = 1; $t <= $total_rooms; $t ++) {
				$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($featured_orders_info['total_room_adult_child_info'], $t);
				$total_guest = $total_guest + $chaild_adult_no_arr[0];
				$total_guest = $total_guest + $chaild_adult_no_arr[1];
			}
		} else {
			$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($featured_orders_info['total_room_adult_child_info'], 1);
			$total_guest = $chaild_adult_no_arr[0] + $chaild_adult_no_arr[1];
		}
		$featured_total_guest_booked = $featured_total_guest_booked + $total_guest;
	}
	return $featured_total_guest_booked;
}

function tep_get_featured_expected_people_and_price($products_id) {
	$featured_deal_price_array = tep_get_featured_deal_price($products_id);
	$featured_total_guest_booked = tep_get_featured_total_guests_booked_this_deal($products_id);
	$expected_people = $featured_deal_price_array[1]['id'];
	$expected_price = $featured_deal_price_array[1]['text'];
	
	if ($featured_total_guest_booked < $featured_deal_price_array[1]['id']) {
		$expected_people = $featured_deal_price_array[1]['id'] - $featured_total_guest_booked;
		$expected_price = $featured_deal_price_array[1]['text'];
	} else 
		if ($featured_total_guest_booked >= $featured_deal_price_array[1]['id'] && $featured_total_guest_booked < $featured_deal_price_array[2]['id']) {
			$expected_people = $featured_deal_price_array[2]['id'] - $featured_total_guest_booked;
			$expected_price = $featured_deal_price_array[2]['text'];
		} else 
			if ($featured_total_guest_booked >= $featured_deal_price_array[2]['id'] && $featured_total_guest_booked < $featured_deal_price_array[3]['id']) {
				$expected_people = $featured_deal_price_array[3]['id'] - $featured_total_guest_booked;
				$expected_price = $featured_deal_price_array[3]['text'];
			}
	return array (
			'0' => $expected_people,
			'1' => $expected_price 
	);
}

function scs_datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
	/*
	 * $interval can be: yyyy - Number of full years q - Number of full quarters
	 * m - Number of full months y - Difference between day numbers (eg 1st Jan
	 * 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
	 * d - Number of full days w - Number of full weekdays ww - Number of full
	 * weeks h - Number of full hours n - Number of full minutes s - Number of
	 * full seconds (default)
	 */
	if (!$using_timestamps) {
		$datefrom = strtotime($datefrom, 0);
		$dateto = strtotime($dateto, 0);
	}
	$difference = $dateto - $datefrom; // Difference in seconds
	

	switch ($interval) {
		
		case 'yyyy': // Number of full years
			

			$years_difference = floor($difference / 31536000);
			if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {
				$years_difference --;
			}
			if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {
				$years_difference ++;
			}
			$datediff = $years_difference;
			break;
		
		case "q": // Number of full quarters
			

			$quarters_difference = floor($difference / 8035200);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference ++;
			}
			$quarters_difference --;
			$datediff = $quarters_difference;
			break;
		
		case "m": // Number of full months
			

			$months_difference = floor($difference / 2678400);
			while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
				$months_difference ++;
			}
			$months_difference --;
			$datediff = $months_difference;
			break;
		
		case 'y': // Difference between day numbers
			

			$datediff = date("z", $dateto) - date("z", $datefrom);
			break;
		
		case "d": // Number of full days
			

			$datediff = floor($difference / 86400);
			break;
		
		case "w": // Number of full weekdays
			

			$days_difference = floor($difference / 86400);
			$weeks_difference = floor($days_difference / 7); // Complete weeks
			$first_day = date("w", $datefrom);
			$days_remainder = floor($days_difference % 7);
			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or
			// Sunday in the remainder?
			if ($odd_days > 7) { // Sunday
				$days_remainder --;
			}
			if ($odd_days > 6) { // Saturday
				$days_remainder --;
			}
			$datediff = ($weeks_difference * 5) + $days_remainder;
			break;
		
		case "ww": // Number of full weeks
			

			$datediff = floor($difference / 604800);
			break;
		
		case "h": // Number of full hours
			

			$datediff = floor($difference / 3600);
			break;
		
		case "n": // Number of full minutes
			

			$datediff = floor($difference / 60);
			break;
		
		default: // Number of full seconds (default)
			

			$datediff = $difference;
			break;
	}
	
	return $datediff;
}

function check_web_url($url) {
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_HEADER, 1); // get the header
	curl_setopt($c, CURLOPT_NOBODY, 1); // and only get the header
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); // get the response as a string
	// from curl_exec(), rather than
	// echoing it
	curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of
	// the url
	if (!curl_exec($c)) {
		return false;
	}
	
	$httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
	return ($httpcode < 400);
}

function tep_get_is_apply_price_per_order_option_value($value_id) {
	global $languages_id;
	$values = tep_db_query("select is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int) $value_id . "' and language_id = '" . $languages_id . "'");
	$values_values = tep_db_fetch_array($values);
	
	return (int) $values_values['is_per_order_option'];
}

/**
 * 网站自动更新脚本检测
 *
 * @author Howard
 */
function site_will_update_check() {
	$chk_file = DIR_FS_CATALOG . 'UpdateWebSiteAfterNumSeconds.txt';
	if (!file_exists($chk_file)) {
		return false;
	}
	$seconds = (int) trim(file_get_contents($chk_file));
	if ($seconds == "0") {
		return false;
	}
	$now_time = time();
	$start_time = filemtime($chk_file) + $seconds;
	$time_str = date('Y-m-d H:i:s', $start_time);
	$_seconds = $start_time - $now_time;
	if (!(int) $_seconds) {
		return false;
	}
	$time_str1 = $_seconds . '秒';
	if ($_seconds >= 60) {
		$tmp_var = floor($_seconds / 60);
		$tmp_var1 = $_seconds - ($tmp_var * 60);
		$time_str1 = $tmp_var . '分钟';
		if ($tmp_var1 > 0) {
			$time_str1 .= $tmp_var1 . '秒';
		}
	}
	
	$updateStr = "网站正在更新中，请稍候……";
	
	$text_str = '<div id="ServerSuspendTipCon">网站将在' . $time_str . '更新（<b id="UpdateCountdown">' . $time_str1 . '后开始更新，持续时间约为20秒</b>），请注意保存您的操作！由于网络时间有延迟，建议您提前1分钟停止您的操作！</div>';
	$js_str = '<script type="text/javascript">';
	$js_str .= 'var UpdateCountdownSeconds = ' . $_seconds . '; ';
	$js_str .= 'var SiteUpdatedSeconds = 0;
				var SiteUpdatedStopSumit = false;
				function tmpAjaxCheckFileForUpdateSite(){
					SiteUpdatedSeconds++;
					var DivObj = document.getElementById("ServerSuspendTipCon");
					if(SiteUpdatedSeconds %5 ==0 ){
						var ajaxTmp = false;
						if(window.XMLHttpRequest) {
							 ajaxTmp = new XMLHttpRequest();
						}
						else if (window.ActiveXObject) {
							try{
									ajaxTmp = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
							try{
									ajaxTmp = new ActiveXObject("Microsoft.XMLHTTP");
								} catch (e) {}
							}
						}
						if (!ajaxTmp) {
							window.alert("不能创建XMLHttpRequest对象实例");
						}
						var httpHead = (document.location.protocol=="https:") ? "https://" : "http://";
						var url = httpHead + "' . $_SERVER['HTTP_HOST'] . '/ajax_site_update_page.tpl.php?ajax=true&method=get&submit=true&UniqueIdentifier=UpdateCountdown";
							url += "&randnumforajaxaction=" + Math.random();

						ajaxTmp.open("GET", url, true);
						ajaxTmp.send(null);
						ajaxTmp.onreadystatechange = function() {
							if (ajaxTmp.readyState == 4 && ajaxTmp.status == 200) {
								if(ajaxTmp.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){
									DivObj.innerHTML = "网站更新成功，您可以继续进行正常使用网站了！";
									SiteUpdatedStopSumit = true;
									jQuery("#ServerSuspendTip").fadeOut(5000);
								}
							}
						}
					}
					if(SiteUpdatedStopSumit == false){
						DivObj.innerHTML = "' . $updateStr . '"+SiteUpdatedSeconds;
						window.setTimeout(\'tmpAjaxCheckFileForUpdateSite()\',1000);
					}
				}

				var usedSeconds = 0;
				function tmpUpdateCountdownSeconds(){
					usedSeconds++;
					var obj = document.getElementById("UpdateCountdown");
					var RemainingSeconds = UpdateCountdownSeconds - usedSeconds;
					var time_str1 = RemainingSeconds+"秒";
					if(RemainingSeconds>=60){
						var tmp_var = Math.floor(RemainingSeconds/60);
						var tmp_var1 = RemainingSeconds - (tmp_var*60);
						time_str1 = tmp_var+"分钟";
					}
					if(tmp_var1 > 0){
						time_str1+= tmp_var1+"秒";
					}
					obj.innerHTML = time_str1+"后开始更新，持续时间约为20秒";
					if(RemainingSeconds>0){
						window.setTimeout(\'tmpUpdateCountdownSeconds()\',1000);
					}else{
						document.getElementById("ServerSuspendTipCon").innerHTML = "' . $updateStr . '";
						tmpAjaxCheckFileForUpdateSite();
					}
				}
				';
	$js_str .= 'window.setTimeout(\'tmpUpdateCountdownSeconds()\',1000);';
	
	$js_str .= '</script>';
	return $text_str . $js_str;
}

function tep_get_acb_required_or_not($orders_products_id, $orders_id, $products_id, $order_total_value = 0) {
	// amit added to take avs info in log start
	$autho_address_verification_status['A'] = "Address (Street) matches, ZIP does not";
	$autho_address_verification_status['B'] = "Address information not provided for AVS check";
	$autho_address_verification_status['E'] = "AVS error";
	$autho_address_verification_status['G'] = "Non-U.S. Card Issuing Bank";
	$autho_address_verification_status['N'] = "No Match on Address (Street) or ZIP";
	$autho_address_verification_status['P'] = "AVS not applicable for this transaction";
	$autho_address_verification_status['R'] = "Retry ?System unavailable or timed out";
	$autho_address_verification_status['S'] = "Service not supported by issuer";
	$autho_address_verification_status['U'] = "Address information is unavailable";
	$autho_address_verification_status['W'] = "Nine digit ZIP matches, Address (Street) does not";
	$autho_address_verification_status['X'] = "Address (Street) and nine digit ZIP match";
	$autho_address_verification_status['Y'] = "Address (Street) and five digit ZIP match";
	$autho_address_verification_status['Z'] = "Five digit ZIP matches, Address (Street) does not";
	
	$autho_avs_card_code_status['M'] = "Match";
	$autho_avs_card_code_status['N'] = "No Match";
	$autho_avs_card_code_status['P'] = "Not Processed";
	$autho_avs_card_code_status['S'] = "Should have been present";
	$autho_avs_card_code_status['U'] = "Issuer unable to process request";
	// amit added to take avs info in log end
	

	$is_acb_required = true;
	
	if ($order_total_value >= 1800) {
		return $is_acb_required;
	}
	
	$select_orders_history_acb_sql = "select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id='" . (int) $orders_id . "' and orders_status_id='100062'"; // cc
	// authorized
	// success
	// comment
	$select_orders_history_acb_query = tep_db_query($select_orders_history_acb_sql);
	
	if ($select_orders_history_acb_row = tep_db_fetch_array($select_orders_history_acb_query)) {
		foreach ($autho_address_verification_status as $avs_key => $avs_val) {
			if (stristr($select_orders_history_acb_row['comments'], $avs_val)) {
				$match_avs = $avs_key;
			}
		}
		// echo $match_avs;
		

		foreach ($autho_avs_card_code_status as $avs_code_key => $avs_code_val) {
			if (stristr($select_orders_history_acb_row['comments'], $avs_code_val)) {
				$match_avs_card_code = $avs_code_key;
			}
		}
		// echo $match_avs_card_code;
		

		// get customer on tour or guest crt match
		$is_card_holder_on_tour = false;
		$orders_eticket_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_id = '" . (int) $orders_id . "'  and products_id= '" . (int) $products_id . "'"); // orders_products_id
		// =
		// '"
		// .
		// (int)$orders_products_id
		// .
		// "'
		$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
		$orders_eticket_result['guest_name'] = strtolower(trim($orders_eticket_result['guest_name']));
		$guestnames_array = explode('<::>', $orders_eticket_result['guest_name']);
		
		foreach ($guestnames_array as $gkey => $gval) {
			if (trim($gval) != '') {
				$guest_full_name = explode('||', $gval);
				// get chinese fullname
				$splicated_final_chinese_name = preg_split('/\[.*?\]/', $guest_full_name[0]);
				$guest_chinese_full_name = trim($splicated_final_chinese_name[0]);
				// get egnlish fullname
				$matches = array ();
				$t = preg_match('/\[(.*?)\]/s', $guest_full_name[0], $matches);
				$guest_english_full_name = str_replace('[', '', $matches[0]);
				$guest_english_full_name = str_replace(']', '', $guest_english_full_name);
				
				list ($first, $middle, $last) = split(' ', $guest_english_full_name);
				if (!$last) {
					$last = $middle;
					unset($middle);
				}
				$reverse_english_name = $last . " " . $first;
				
				if (stristr(strtolower($select_orders_history_acb_row['comments']), $guest_chinese_full_name) || stristr(strtolower($select_orders_history_acb_row['comments']), $guest_english_full_name) || stristr(strtolower($select_orders_history_acb_row['comments']), $reverse_english_name)) {
					$is_card_holder_on_tour = true;
					break;
				}
			}
		}
		// Guest Surname Match CC Holder Surname
		$is_guest_suname_match_holder_cc = false;
		foreach ($guestnames_array as $gkey => $gval) {
			if (trim($gval) != '') {
				$guest_full_name = explode('||', $gval);
				// get chinese fullname
				$splicated_final_chinese_name = preg_split('/\[.*?\]/', $guest_full_name[0]);
				$guest_chinese_full_name = trim($splicated_final_chinese_name[0]);
				
				list ($first, $middle, $last) = split(' ', $guest_chinese_full_name);
				if (!$last) {
					$last = $middle;
					unset($middle);
				}
				
				if (stristr(strtolower($select_orders_history_acb_row['comments']), $last) || stristr(strtolower($select_orders_history_acb_row['comments']), $first)) {
					$is_guest_suname_match_holder_cc = true;
					break;
				}
				
				// get egnlish fullname
				$matches = array ();
				$t = preg_match('/\[(.*?)\]/s', $guest_full_name[0], $matches);
				$guest_english_full_name = str_replace('[', '', $matches[0]);
				$guest_english_full_name = str_replace(']', '', $guest_english_full_name);
				
				list ($first, $middle, $last) = split(' ', $guest_english_full_name);
				if (!$last) {
					$last = $middle;
					unset($middle);
				}
				
				if (stristr(strtolower($select_orders_history_acb_row['comments']), $last) || stristr(strtolower($select_orders_history_acb_row['comments']), $first)) {
					$is_guest_suname_match_holder_cc = true;
					break;
				}
			}
		}
		
		if ($match_avs != 'Y' && $match_avs != 'A' && $match_avs != 'Z') { // Non-US
			// Credit
			// Card -G
			if ($order_total_value < 500) {
				if ($match_avs_card_code != 'M' && $is_card_holder_on_tour == true) {
					$is_acb_required = false;
				} else 
					if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == false && $is_guest_suname_match_holder_cc == false) {
						$is_acb_required = false;
					} else 
						if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == true && $is_guest_suname_match_holder_cc == false) {
							$is_acb_required = false;
						} else 
							if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == false && $is_guest_suname_match_holder_cc == true) {
								$is_acb_required = false;
							} else 
								if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == true && $is_guest_suname_match_holder_cc == true) {
									$is_acb_required = false;
								}
			} else 
				if ($order_total_value >= 500 && $order_total_value < 1800) {
					if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == true && $is_guest_suname_match_holder_cc == false) {
						$is_acb_required = false;
					} else 
						if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == false && $is_guest_suname_match_holder_cc == true) {
							$is_acb_required = false;
						} else 
							if ($match_avs_card_code == 'M' && $is_card_holder_on_tour == true && $is_guest_suname_match_holder_cc == true) {
								$is_acb_required = false;
							}
				}
		} else { // US credit card
			

			$is_address_match = false;
			if ($match_avs == 'Y' || $match_avs == 'A') {
				$is_address_match = true;
			}
			$is_zip_match = false;
			if ($match_avs == 'Y' || $match_avs == 'Z') {
				$is_zip_match = true;
			}
			
			if ($order_total_value < 500) {
				if ($match_avs_card_code == 'M') {
					$is_acb_required = false;
				}
			} else 
				if ($order_total_value >= 500 && $order_total_value < 1800) {
					if ($is_address_match == true && $is_zip_match == true && $match_avs_card_code == 'M') {
						$is_acb_required = false;
					} else 
						if ($is_card_holder_on_tour == true && $is_address_match == true && $is_zip_match == false && $match_avs_card_code == 'M') {
							$is_acb_required = false;
						} else 
							if ($is_card_holder_on_tour == true && $is_address_match == false && $is_zip_match == true && $match_avs_card_code == 'M') {
								$is_acb_required = false;
							}
				}
		}
	}
	return $is_acb_required;
}

/**
 * hotel-extension 酒店延住相关函数 检查产品是否为酒店产品是酒店返回1不是返回0
 *
 * @param int $product_id 产品ID
 * @return int 0,1
 */
function tep_check_product_is_hotel($product_id) {
	$product_id = intval($product_id);
	$check_hotel_query = tep_db_query("select is_hotel from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
	$check_hotel = tep_db_fetch_array($check_hotel_query);
	if ($check_hotel['is_hotel'] == '1') {
		$check_is_hotel = 1;
	} else {
		$check_is_hotel = 0;
	}
	return $check_is_hotel;
}

function is_group_discount_allowed($products_id) {
	$ret_is_group_discount = true;
	if (GROUP_BUY_ON == true) {
		$qry_group_discount = 'select p.products_durations, p.products_durations_type, p.products_type, p.is_hotel from ' . TABLE_PRODUCTS . ' p WHERE p.products_id = "' . (int) $products_id . '"';
		$res_group_discount = tep_db_query($qry_group_discount);
		$row_group_discount = tep_db_fetch_array($res_group_discount);
		
		if ($row_group_discount['products_durations'] > 0 && $row_group_discount['products_durations_type'] == 0) {
			$prod_dura_day = $row_group_discount['products_durations'] - 1;
		} else {
			$prod_dura_day = 0;
		}
		
		$is_gift_certificate_tour = tep_is_gift_certificate_product($products_id);
		if ($prod_dura_day < 1 || $row_group_discount['products_type'] == CAT_ID_PASSES_CARDS || $is_gift_certificate_tour == true || $row_group_discount['is_hotel'] == 1) {
			$ret_is_group_discount = false;
		}
	} else {
		$ret_is_group_discount = false;
	}
	
	return $ret_is_group_discount;
}

/**
 * 根据城市ID号串取得城市名称，返回array
 *
 * @param $cities_id_string
 * @author Howard
 */
function tep_get_city_names($cities_id_string) {
	$array = array ();
	$ids = explode(',', $cities_id_string);
	for ($i = 0, $n = sizeof($ids); $i < $n; $i ++) {
		$sql = tep_db_query('SELECT city FROM ' . TABLE_TOUR_CITY . ' where city_id="' . (int) $ids[$i] . '" ');
		$row = tep_db_fetch_array($sql);
		if (tep_not_null($row['city'])) {
			$array[] = $row['city'];
		}
	}
	return $array;
}

/**
 * 判断行程是否需要出发日期 检查 products_type 的值 英文站是 14..中文站先留接口
 *
 * @author vincent
 * @param int $products_id
 * @return boolean
 */
function is_tour_start_date_required($products_id) {
	if (!defined('CAT_ID_PASSES_CARDS'))
		define('CAT_ID_PASSES_CARDS', 14);
	$ret_is_start_date_required = true;
	$qry_date_req = 'select p.products_type,p.is_transfer from ' . TABLE_PRODUCTS . ' p WHERE p.products_id = "' . (int) $products_id . '"';
	$res_date_req = tep_db_query($qry_date_req);
	$row_date_req = tep_db_fetch_array($res_date_req);
	if (tep_not_null($row_date_req['products_type'])) {
		if ($row_date_req['products_type'] == CAT_ID_PASSES_CARDS) {
			$ret_is_start_date_required = false;
		}
	}
	
	// priority mail 'true' tours need start date
	if (tep_check_priority_mail_is_active($products_id) == 1) {
		$ret_is_start_date_required = true;
	}
	
	if ($row_date_req['is_transfer'] == '1')
		return false;
	return $ret_is_start_date_required;
}

/**
 * 判断产品是否是拉斯维加斯秀团
 *
 * @author t4f
 * @param unknown_type $products_id
 */
function check_is_las_vegas_show($products_id) {
	// Start Las Vegas Show
	$is_ls_show = false;
	$p_tpl_sql = tep_db_query('select products_info_tpl from ' . TABLE_PRODUCTS . ' WHERE products_id ="' . (int) $products_id . '" ');
	$p_tpl_row = tep_db_fetch_array($p_tpl_sql);
	if (tep_not_null($p_tpl_row['products_info_tpl'])) {
		if ($p_tpl_row['products_info_tpl'] == 'product_info_vegas_show') {
			$is_ls_show = true;
		}
	}
	
	return $is_ls_show;
}

function tep_get_order_start_stop($order_id, $blinking = 0) { // 0 stop, 1 start
	$sql_data_array = array (
			'is_blinking' => $blinking,
			'is_blinking_date' => date('Y-m-d H:i:s', time()) 
	);
	tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id='" . (int) $order_id . "'");
	return true;
}
// E-ticket Log Start
function tep_get_eticket_log_content($oID, $opid, $updated_by, $is_provider = 0, $is_notify = 0) { // 0=Admin,
	// 1=Providers
	global $login_id;
	
	require_once (BACK_PATH . DIR_WS_CLASSES . 'order.php');
	$order = new order($oID);
	
	$_GET['orders_products_id'] = $opid;
	ob_start();
	require_once ("eticket_email.php");
	$email_order = ob_get_contents();
	ob_end_clean();
	
	$eticket_log_data_array = array (
			'orders_products_id' => $opid,
			'orders_eticket_last_modified' => 'now()',
			'orders_eticket_content' => $email_order,
			'orders_eticket_is_customer_notified' => $is_notify,
			'orders_eticket_updated_by' => $updated_by,
			'orders_eticket_updator_type' => $is_provider 
	);
	tep_db_perform(TABLE_ORDERS_ETICKET_LOG, $eticket_log_data_array);
	
	return true;
}
// E-ticket Log End
function tep_get_product_hotel_detail_array($prod_id) {
	$product_hotel_info_query = tep_db_query("select hotel_address, hotel_name, hotel_phone from hotel where products_id = '" . $prod_id . "'");
	$product_hotel_info = tep_db_fetch_array($product_hotel_info_query);
	return $product_hotel_info;
}
/*
 * Copy from t4f list all date in date span
 */
function list_all_dates_btwn_two_dates_in_array($strDateFrom, $strDateTo) {
	// Y-m-d format parameter
	$aryRange = array ();
	
	$iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
	$iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), (substr($strDateTo, 8, 2) - 1), substr($strDateTo, 0, 4));
	
	if ($iDateTo >= $iDateFrom) {
		array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
		while ($iDateFrom < $iDateTo) {
			$iDateFrom += 86400; // add 24 hours
			array_push($aryRange, date('Y-m-d', $iDateFrom));
		}
	}
	return $aryRange;
}

/**
 * 记录订阅电子邮箱地址到数据库
 *
 * @author Howard
 */
function write_subscribe_email_address($email_address) {
	$check_sql = tep_db_query('select * from newsletters_email where newsletters_email_address="' . tep_db_prepare_input($email_address) . '" limit 1 ');
	$check_row = tep_db_fetch_array($check_sql);
	if (!(int) $check_row['newsletters_email_id']) {
		tep_db_query('INSERT INTO `newsletters_email` (`newsletters_email_address` ) VALUES ("' . tep_db_prepare_input($email_address) . '");');
		return true;
	}
	return false;
}

/**
 * 取得从当前产品取得与之匹配的团购产品ID
 *
 * @param $related_product_id 当前的产品ID
 * @author Howard
 * @return products_id 或 false
 */
function getGroupBuyProductIdFromRelated($related_product_id) {
	if (!(int) $related_product_id)
		return false;
	$theTime = date('Y-m-d H:i:s');
	$sql = tep_db_query('SELECT products_id FROM `specials` WHERE related_product_id="' . (int) $related_product_id . '" and specials_type="1" and status="1" and start_date<"' . $theTime . '" and expires_date>="' . $theTime . '" Order By specials_id DESC Limit 1');
	$rows = tep_db_fetch_array($sql);
	if ((int) $rows['products_id']) {
		return $rows['products_id'];
	}
	return false;
}

/*
 * 接送服务 获取产品的有地点 group =true会 分组为airport 和location
 */
function tep_transfer_get_locations($products_id, $group = false) {
	$locationQuery = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TRANSFER_LOCATION . " WHERE products_id = '" . intval($products_id) . "' ORDER BY type ASC,products_transfer_location_id ASC");
	$locations = array ();
	$airport = array ();
	$loc = array ();
	while ($row = tep_db_fetch_array($locationQuery)) {
		if ($row['zipcode'] == '0')
			$airport[] = $row;
		else
			$loc[] = $row;
		$locations[] = $row;
	}
	if ($group) {
		return array (
				'airport' => $airport,
				'location' => $loc 
		);
	} else {
		return $locations;
	}
}

/**
 * 获取全部路线
 */
function tep_transfer_get_routes($products_id) {
	$query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TRANSFER_ROUTE . "  WHERE products_id = " . intval($products_id) . ' ORDER BY products_transfer_route_id ASC');
	$routeCount = 0;
	$routes = array ();
	while ($row = tep_db_fetch_array($query))
		$routes[] = $row;
	return $routes;
}

/**
 * 接送服务相关函数 ，检查产品是否为接送服务 如果产品的数据已经查询出来请设置 $data 减少一次数据库操作
 *
 * @param int $product_id
 * @param array [$data]
 */
function tep_check_product_is_transfer($product_id, $data = array()) {
	$product_id = intval($product_id);
	if (!isset($data['is_transfer'])) {
		$chec_query = tep_db_query("select is_transfer from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
		$check = tep_db_fetch_array($chec_query);
	} else {
		$check = $data;
	}
	if ($check['is_transfer'] == '1') {
		$check_result = 1;
	} else {
		$check_result = 0;
	}
	return $check_result;
}
/*
 * 检查线路信息是否有效
 */
function tep_transfer_validate($products_id, $params, $product_transfer_type = null) {
	$products_id = intval($products_id);
	$errorMsg = '';
	// 检查产品是否是正常状态
	$query = tep_db_query('SELECT products_status,products_stock_status ,transfer_type,is_transfer FROM ' . TABLE_PRODUCTS . ' WHERE products_id = ' . $products_id . ' LIMIT 1');
	$product_info = tep_db_fetch_array($query);
	$product_transfer_type = intval($product_info['transfer_type']);
	if ($product_info['products_status'] != '1') {
		$errorMsg = '该产品暂时无法进行预订！'; //
	} elseif ($product_info['products_stock_status'] == '0') {
		$errorMsg = '该产品已经售完,无法进行预订！';
	}
	if ($errorMsg != '')
		return $errorMsg;
		// 读取soldout信息
	$soldout_dates = array ();
	$soldout_dates_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_SOLDOUT_DATES . " sd WHERE sd.products_id='" . $products_id . "'");
	while ($row_sold_dates = tep_db_fetch_array($soldout_dates_query)) {
		$soldout_dates[] = date('Y-m-d', strtotime($row_sold_dates['products_soldout_date'] . ' 00:00:00'));
	}
	// 读取operate信息
	$operate_info = array ();
	$sql = tep_db_query('SELECT operate_start_date,operate_end_date,products_start_day,available_date  FROM ' . TABLE_PRODUCTS_REG_IRREG_DATE . ' WHERE products_id = ' . $products_id);
	
	while ($row = tep_db_fetch_array($sql)) {
		if (check_valid_available_date_endate($row['operate_end_date']) == 'valid') {
			$row['operate_start'] = intval(date('Ymd', strtotime(str_replace('-', '/', $row['operate_start_date']))));
			$row['operate_end'] = intval(date('Ymd', strtotime(str_replace('-', '/', $row['operate_end_date']))));
			$operate_info[] = $row;
		}
	}
	$locations1 = tep_transfer_get_locations($products_id, false);
	$locations = array ();
	foreach ($locations1 as $l)
		$locations[] = $l['products_transfer_location_id'];
	$route = $product_transfer_type == '1' ? tep_transfer_get_routes($products_id) : array ();
	$route_is_set = false;
	$validRouteCount = 0;
	if ($errorMsg == '') {
		for ($i = 1; $i <= 2; $i ++) {
			if (is_numeric($params['pickup_id' . $i]) && is_numeric($params['dropoff_id' . $i]) && intval($params['guest_total' . $i]) > 0) {
				$validRouteCount ++;
				$arrival_time = strtotime($params['flight_arrival_time' . $i]);
				$arrival_time_str_short = date('Y-m-d', $arrival_time);
				$arrival_time_int = intval(date('Ymd', $arrival_time));
				$arrival_time_str_w = intval(date('w', $arrival_time)) + 1;
				if (in_array($arrival_time_str_short, $soldout_dates)) {
					$errorMsg = $arrival_time_str_short . ' 的接送服务已经预订完了，请选择其他日期！'; //
					break;
				}
				// check operate
				$matchOperate = false;
				foreach ($operate_info as $op) {
					if ($op['products_start_day'] == '0' && trim($op['available_date']) == $arrival_time_str_short) {
						$matchOperate = true;
						break;
					} elseif ($arrival_time_int >= $op['operate_start'] && $arrival_time_int <= $op['operate_end']) {
						if ($op['products_start_day'] == $arrival_time_str_w) {
							$matchOperate = true;
							break;
						}
					}
				}
				if ($matchOperate === false) {
					$errorMsg = '预订的日期无效，请更换预订日期！';
					break;
				}
				//
				if ($arrival_time <= time() + 3600 * 24) {
					$errorMsg = '接送服务需要提前24小时预订！'; //
					break;
				} else 
					if ($params['pickup_id' . $i] == $params['dropoff_id' . $i]) {
						$errorMsg = '接送服务的起点和终点不能设置为相同地点, 请检查！';
						break;
					} else 
						if (!in_array($params['pickup_id' . $i], $locations) || !in_array($params['dropoff_id' . $i], $locations)) {
							$errorMsg = '错误的接送服务起点或终点， 请检查！';
							break;
						}
				if ($product_transfer_type == '1') {
					$in_route = false;
					foreach ($route as $r) {
						if (($params['pickup_id' . $i] == $r['pickup_location_id'] && $params['dropoff_id' . $i] == $r['dropoff_location_id']) || ($params['pickup_id' . $i] == $r['dropoff_location_id'] && $params['dropoff_id' . $i] == $r['pickup_location_id'])) {
							$in_route = true;
						}
					}
					if ($in_route !== true) {
						$errorMsg = '指定的线路不存在！';
						break;
					}
				}
			}
		}
		
		if ($validRouteCount == 0)
			$errorMsg = '接送线路信息设置有误请检查！';
	}
	// tep_log(print_vars2($route,$locations,$params));
	return $errorMsg;
}
/*
 * 根据参数计算接送服务价格
 */
function tep_transfer_calculation_price($products_id, $params, $return_cost = false) {
	$products_id = intval($products_id);
	$query = tep_db_query("SELECT transfer_type,is_transfer,products_single as price_1_3,products_double as price_4_6 ,products_triple AS price_overtime,products_single_cost as cost_1_3,products_double_cost as cost_4_6 ,products_triple_cost AS cost_overtime FROM " . TABLE_PRODUCTS . ' WHERE products_id = ' . $products_id . " LIMIT 1");
	$product_price = tep_db_fetch_array($query);
	
	$locations = array ();
	$locationQuery = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TRANSFER_LOCATION . " WHERE products_id = '" . $products_id . "' ORDER BY type ASC,products_transfer_location_id ASC");
	while ($row = tep_db_fetch_array($locationQuery)) {
		$locations[$row['products_transfer_location_id']] = $row;
	}
	
	$price = 0;
	$cost = 0;
	// 线路价格固定
	if ($product_price['transfer_type'] == '0') {
		for ($i = 1; $i <= 2; $i ++) {
			$guest_total = intval($params['guest_total' . $i]);
			$flight_arrival_time = strtotime($params['flight_arrival_time' . $i]);
			$flight_arrival_time_int = intval(date('Hi', $flight_arrival_time));
			$pickup = $params['pickup_id' . $i];
			$dropoff = $params['dropoff_id' . $i];
			if (array_key_exists($pickup, $locations) && array_key_exists($dropoff, $locations) && $guest_total > 0) {
				// 进行执行价格的查询
				$carAmount = floor($guest_total / 6);
				$exPerson = $guest_total % 6;
				if ($exPerson > 0 && $exPerson <= 3) {
					$exPrice = $product_price['price_1_3'];
					$exCost = $product_price['cost_1_3'];
				} elseif ($exPerson > 3) {
					$exPrice = $product_price['price_4_6'];
					$exCost = $product_price['cost_4_6'];
				} else {
					$exPrice = 0;
					$exCost = 0;
				}
				$price = $price + $carAmount * $product_price['price_4_6'] + $exPrice;
				$cost = $cost + $carAmount * $product_price['cost_4_6'] + $exCost;
				
				if ($flight_arrival_time_int < 830 || $flight_arrival_time_int > 2230) {
					$price = $price + ceil($guest_total / 6) * $product_price['price_overtime'];
					$cost = $cost + ceil($guest_total / 6) * $product_price['cost_overtime'];
				}
			}
		}
	} else 
		if ($product_price['transfer_type'] == '1') { // 线路价格不固定
			

			$routeQuery = tep_db_query("SELECT pickup_location_id AS pickup , dropoff_location_id AS dropoff ,price1 AS price_1_3, price2 AS price_4_6 ,price1_cost AS cost_1_3, price2_cost AS cost_4_6 FROM " . TABLE_PRODUCTS_TRANSFER_ROUTE . " WHERE products_id = '" . $products_id . "' ");
			$routelist = array ();
			while ($row = tep_db_fetch_array($routeQuery)) {
				$routelist[] = $row;
			}
			
			for ($i = 1; $i <= 2; $i ++) {
				$guest_total = intval($params['guest_total' . $i]);
				$flight_arrival_time = strtotime($params['flight_arrival_time' . $i]);
				$flight_arrival_time_int = intval(date('Hi', $flight_arrival_time));
				$pickup = $params['pickup_id' . $i];
				$dropoff = $params['dropoff_id' . $i];
				if (array_key_exists($pickup, $locations) && array_key_exists($dropoff, $locations) && $guest_total > 0) {
					// find route
					$matchedRoute = array ();
					foreach ($routelist as $route) {
						if (($route['pickup'] == $pickup && $route['dropoff'] == $dropoff) || ($route['dropoff'] == $pickup && $route['pickup'] == $dropoff)) {
							$matchedRoute = $route;
							break;
						}
					}
					if (empty($matchedRoute)) {
						return '对不起!我们暂时未提供你指定线路的接送服务。';
					} else {
						$carAmount = floor($guest_total / 6);
						$exPerson = $guest_total % 6;
						if ($exPerson > 0 && $exPerson <= 3) {
							$exPrice = $matchedRoute['price_1_3'];
							$exCost = $matchedRoute['cost_1_3'];
						} elseif ($exPerson > 3) {
							$exPrice = $matchedRoute['price_4_6'];
							$exCost = $matchedRoute['cost_4_6'];
						} else {
							$exPrice = 0;
							$exCost = 0;
						}
						$price = $price + $carAmount * $matchedRoute['price_4_6'] + $exPrice;
						$cost = $cost + $carAmount * $matchedRoute['cost_4_6'] + $exCost;
						if ($flight_arrival_time_int < 830 || $flight_arrival_time_int > 2230) {
							$price = $price + ceil($guest_total / 6) * $product_price['price_overtime'];
							$cost = $cost + ceil($guest_total / 6) * $product_price['cost_overtime'];
						}
					}
				}
			}
		}
	if ($return_cost === true) {
		return array (
				'price' => $price,
				'cost' => $cost 
		);
	} else {
		return $price;
	}
}

/**
 * 将输入的接送服务参数编码为存放到购物车的字符串形式 返回的字符串使用GB2312编码
 *
 * @param unknown_type $param
 */
function tep_transfer_encode_info($params, $ajax_trans = true) {
	// tep_log($params);
	$sep1 = "|==|";
	$sep2 = "||";
	foreach ($params as $key => $value) {
		if (!is_array($value) && $value != '') {
			$params[$key] = str_replace(array (
					$sep1,
					$sep2 
			), '', $value);
			if ($ajax_trans) {
				$params[$key] = ajax_to_general_string($params[$key]);
			}
		}
	}
	$output = $params['transferType'] . $sep1;
	$route = array (
			'0' => array (
					$params['pickup_id1'],
					$params['pickup_zipcode1'],
					$params['pickup_address1'],
					$params['dropoff_id1'],
					$params['dropoff_zipcode1'],
					$params['dropoff_address1'],
					$params['flight_number1'],
					$params['flight_departure1'],
					$params['flight_arrival_time1'],
					$params['guest_total1'],
					$params['baggage_total1'],
					$params['comment1'],
					$params['flight_pick_time1'] 
			),
			'1' => array (
					$params['pickup_id2'],
					$params['pickup_zipcode2'],
					$params['pickup_address2'],
					$params['dropoff_id2'],
					$params['dropoff_zipcode2'],
					$params['dropoff_address2'],
					$params['flight_number2'],
					$params['flight_departure2'],
					$params['flight_arrival_time2'],
					$params['guest_total2'],
					$params['baggage_total2'],
					$params['comment2'],
					$params['flight_pick_time2'] 
			) 
	);
	for ($i = 0; $i < 2; $i ++) {
		$output .= implode($sep2, $route[$i]) . $sep1;
	}
	return html_to_db(substr($output, 0, strlen($output) - strlen($sep1)));
}

/**
 * 将输入的接送服务的字符串转成数组
 *
 * @param unknown_type $param
 */
function tep_transfer_decode_info($param_string) {
	$keys = array (
			'pickup_id',
			'pickup_zipcode',
			'pickup_address',
			'dropoff_id',
			'dropoff_zipcode',
			'dropoff_address',
			'flight_number',
			'flight_departure',
			'flight_arrival_time',
			'guest_total',
			'baggage_total',
			'comment',
			'flight_pick_time' 
	);
	$sep1 = "|==|";
	$sep2 = "||";
	$route_parts = explode($sep1, $param_string);
	$para_arr = array ();
	$para_arr['transferType'] = intval($route_parts[0]);
	$routeArray = array ();
	for ($i = 1; $i < 3; $i ++) {
		$route = array ();
		$rparam = explode($sep2, $route_parts[$i]);
		for ($j = 0, $k = count($keys); $j < $k; $j ++) {
			if ($keys[$j] == 'flight_arrival_time' && trim($rparam[$j]) != '') {
				$value = date('m/d/Y h:i A', strtotime($rparam[$j]));
			} else 
				if ($keys[$j] == 'flight_pick_time' && trim($rparam[$j]) != '') {
					$value = date('m/d/Y h:i A', strtotime($rparam[$j]));
				} else {
					$value = $rparam[$j];
				}
			$para_arr[$keys[$j] . $i] = $value;
			$route[$keys[$j]] = $value;
		}
		$routeArray[] = $route;
	}
	$para_arr['routes'] = $routeArray;
	return $para_arr;
}
/*
 * 输出接送服务路线信息文本
 */
function tep_transfer_display_route($transfer_info_array, $forMail = false) {
	$output = ''; // .$transfer_info_array['transferType'];
	$routeIndex = 1;
	
	foreach ($transfer_info_array['routes'] as $route) {
		if ($route['guest_total'] > 0 && is_numeric($route['pickup_id']) && is_numeric($route['dropoff_id'])) {
			if ($forMail == true) {
				$prefix = '-';
			} else {
				$prefix = '';
				$output .= "<span style=\"font-size:12px;color:#000;font-style:italic\"><strong>" . $routeIndex . " .</strong> </span>";
			}
			$output .= $prefix . "<strong>起点：</strong> " . tep_db_output($route['pickup_address']) . ($route['pickup_zipcode'] != 0 && $route['pickup_zipcode'] != '' ? '(' . $route['pickup_zipcode'] . ')' : '');
			$output .= "&nbsp;&nbsp;<strong>终点：</strong> " . tep_db_output($route['dropoff_address']) . ($route['dropoff_zipcode'] != 0 && $route['dropoff_zipcode'] != '' ? '(' . $route['dropoff_zipcode'] . ')' : '');
			$output .= "<br>" . $prefix . "<strong>航空公司名称/号码：</strong>" . $route['flight_number'];
			$output .= "<br>" . $prefix . "<strong>航班起飞/抵达时间：</strong>" . $route['flight_arrival_time'];
			$output .= "<br>" . " <strong>接应/送达详细地点：</strong>" . $route['flight_departure'];
			if ($route['flight_pick_time'] != '') {
				$output .= "<br>" . $prefix . "<strong>期待被接应时间：</strong>" . $route['flight_pick_time'];
			}
			$output .= " <br><strong>人数：</strong>" . $route['guest_total'] . "人 <strong>行李：</strong>" . $route['baggage_total'] . "件";
			if ($route['comment'] != '')
				$output .= "<br>" . $prefix . "<strong>留言：</strong>" . tep_db_output($route['comment']);
			$output .= "<br><br>";
			$routeIndex ++;
		}
	}
	if ($forMail == true) {
		return strip_tags(str_replace(array (
				'&nbsp;',
				'<br>' 
		), array (
				' ',
				"\n" 
		), $output));
	}
	return $output;
}

/**
 * 读取订单产品的路线设置信息
 */
function tep_transfer_get_order_transfer($order_products_id) {
	$query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS_TRANSFER . ' WHERE orders_products_id = ' . intval($order_products_id));
	$route_array = array (
			'routes' => array () 
	);
	$index = 1;
	while ($row = tep_db_fetch_array($query)) {
		$route_array['orders_products_transfer_id'] = $row['orders_products_transfer_id'];
		$route_array['orders_products_id'] = $row['orders_products_id'];
		$route_array['created_date'] = $row['created_date'];
		$route_array['orders_id'] = $row['orders_id'];
		
		$flight_arrival_time = date('m/d/Y h:i A', strtotime($row['flight_arrival_time']));
		$flight_pick_time = $row['flight_pick_time'] == '' || $row['flight_pick_time'] == "1970-1-1 0:0:0" ? '' : date('m/d/Y h:i A', strtotime($row['flight_pick_time']));
		
		$route_array['flight_number' . $index] = $row['flight_number'];
		$route_array['flight_departure' . $index] = $row['flight_departure'];
		$route_array['flight_arrival_time' . $index] = $flight_arrival_time;
		$route_array['pickup_id' . $index] = $row['pickup_id'];
		$route_array['pickup_address' . $index] = $row['pickup_address'];
		$route_array['pickup_zipcode' . $index] = $row['pickup_zipcode'];
		$route_array['dropoff_id' . $index] = $row['dropoff_id'];
		$route_array['dropoff_address' . $index] = $row['dropoff_address'];
		$route_array['dropoff_zipcode' . $index] = $row['dropoff_zipcode'];
		$route_array['guest_total' . $index] = $row['guest_total'];
		$route_array['baggage_total' . $index] = $row['baggage_total'];
		$route_array['comment' . $index] = $row['comment'];
		$tmp = array (
				'flight_number' => $row['flight_number'],
				'flight_departure' => $row['flight_departure'],
				'flight_arrival_time' => $flight_arrival_time,
				'pickup_id' => $row['pickup_id'],
				'pickup_address' => $row['pickup_address'],
				'pickup_zipcode' => $row['pickup_zipcode'],
				'dropoff_id' => $row['dropoff_id'],
				'dropoff_address' => $row['dropoff_address'],
				'dropoff_zipcode' => $row['dropoff_zipcode'],
				'guest_total' => $row['guest_total'],
				'baggage_total' => $row['baggage_total'],
				'comment' => $row['comment'] 
		);
		if ($flight_pick_time != '') {
			$route_array['flight_pick_time' . $index] = $flight_pick_time;
			$tmp['flight_pick_time'] = $flight_pick_time;
		}
		$route_array['routes'][] = $tmp;
		$index ++;
	}
	return $route_array;
}

/**
 * 打印变量信息 ，在生产站和QA 站无效
 *
 * @param [$var ....]
 */
function print_vars() {
	if (IS_LIVE_SITES === true || IS_QA_SITES === true) {
		return;
	}
	$args = func_get_args();
	$output = call_user_func_array("print_vars2", $args);
	echo '<pre style="font-family: Courier New;font-size:12px;text-align:left">';
	echo htmlspecialchars($output);
	echo '</pre>';
}

/**
 * print_r变量信息返回字符串
 *
 * @param [$var ....]
 */
function print_vars2() {
	$output = '';
	$args = func_get_args();
	foreach ($args as $arg) {
		$type = gettype($arg);
		if ($type == 'boolean')
			$output .= $arg == true ? 'true' : 'false';
		else 
			if (in_array($type, array (
					'integer',
					'double',
					'string' 
			)))
				$output .= $arg;
			else 
				if (in_array($type, array (
						'array',
						'object' 
				)))
					$output .= print_r($arg, true);
				else 
					if (in_array($type, array (
							'NULL',
							'unknown type' 
					)))
						$output .= $type;
					else 
						if ($type == 'resource')
							$output .= '[' . get_resource_type($arg) . ']' . $arg;
						else
							$output .= 'unknow';
		$output .= "\n";
	}
	return $output;
}

function tep_check_priority_mail_is_active($prod_id) {
	// $check_tour_agency_name_query = tep_db_query("select ta.agency_id,
	// ta.is_priority_mail_note from " . TABLE_TRAVEL_AGENCY . " as ta,
	// ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id =
	// '" .$prod_id. "'");
	// $check_tour_agency_name =
	// tep_db_fetch_array($check_tour_agency_name_query);
	$priority_mail_is_active = 0;
	/*
	 * if($check_tour_agency_name['is_priority_mail_note'] == 1){
	 * $priority_mail_is_active = 1; }
	 */
	
	$products_attributes_query = tep_db_query("select products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int) $prod_id . "' and options_id = '" . PRIORITY_MAIL_PRODUCTS_OPTIONS_ID . "'");
	if (tep_db_num_rows($products_attributes_query) > 0) {
		$priority_mail_is_active = 1;
	}
	return $priority_mail_is_active;
}

function tep_get_cart_add_update_extra_values($fieldname, $fieldvalue, $extra_values_str = '') {
	if (tep_not_null($extra_values_str)) {
		$extra_values_array = explode("|##!##|", $extra_values_str);
		$total_ext_values = sizeof($extra_values_array);
		$new_extra_values_str = '';
		$update_count = 0;
		for ($ext = 0; $ext < $total_ext_values; $ext ++) {
			$get_field_info = explode("|#|", $extra_values_array[$ext]);
			if ($get_field_info[0] == $fieldname) {
				$get_field_info[1] = $fieldvalue;
				$update_count ++;
			}
			if (tep_not_null($get_field_info[0]) && tep_not_null($get_field_info[1])) {
				$new_extra_values_str .= $get_field_info[0] . '|#|' . $get_field_info[1] . '|##!##|';
			}
		}
		if ($update_count == 0) {
			if (tep_not_null($fieldname) && tep_not_null($fieldvalue)) {
				$new_extra_values_str .= $fieldname . '|#|' . $fieldvalue . '|##!##|';
			}
		}
	} else {
		$new_extra_values_str = $fieldname . '|#|' . $fieldvalue . '|##!##|';
	}
	return $new_extra_values_str;
}

function tep_get_cart_get_extra_field_value($fieldname, $extra_values_str = '') {
	$fieldvalue = '';
	if (tep_not_null($extra_values_str)) {
		$extra_values_array = explode("|##!##|", $extra_values_str);
		$total_ext_values = sizeof($extra_values_array);
		$new_extra_values_str = '';
		for ($ext = 0; $ext < $total_ext_values; $ext ++) {
			$get_field_info = explode("|#|", $extra_values_array[$ext]);
			if ($get_field_info[0] == $fieldname) {
				$fieldvalue = $get_field_info[1];
			}
		}
	}
	return $fieldvalue;
}

function GetWorkingDays($fromDate, $interval) {
	$date_array = explode('-', $fromDate);
	$day = $date_array[2];
	$month = $date_array[1];
	$year = $date_array[0];
	// $working_date = array();
	for ($i = 1; $i <= $interval; $i ++) {
		$day_text = date("D", mktime(0, 0, 0, $month, $day + (int) $i, $year));
		if ($day_text == 'Sat' || $day_text == 'Sun') {
			$interval ++;
			continue;
		}
		$working_date = date("Y-m-d", mktime(0, 0, 0, $month, $day + (int) $i, $year));
	}
	return $working_date;
}

/**
 * 创建图片的缩略图 根据指定的尺寸 按比例裁切 不指定$dest则显示到浏览器
 *
 * @author vincent
 */
function tep_image_makethumb($src, $dest = 'auto', $width = 120, $height = 90) {
	$info = getimagesize($src);
	$sRate = (float) $info[0] / $info[1];
	$dRate = (float) $width / $height;
	
	if ($dest == 'auto') {
		$pos = strrpos($src, '.');
		$dest = substr($src, 0, $pos) . '_' . $width . 'x' . $height . substr($src, $pos);
	}
	
	$cutWidth = $dRate * $info[1];
	if ($cutWidth <= $info[0]) {
		$cutHeight = $cutWidth / $dRate;
		$posX = floor(($info[0] - $cutWidth) / 2);
		$posY = floor(($info[1] - $cutHeight) / 2);
	} else {
		$cutHeight = $info[0] / $dRate;
		$cutWidth = $cutHeight * $dRate;
		$posX = floor(($info[0] - $cutWidth) / 2);
		$posY = floor(($info[1] - $cutHeight) / 2);
	}
	
	switch ($info[2]) {
		case IMG_GIF:
			$src_img = imagecreatefromgif($src);
			break;
		case IMG_JPG:
			$src_img = imagecreatefromjpeg($src);
			break;
		case IMG_JPEG:
			$src_img = imagecreatefromjpeg($src);
			break;
		case IMG_PNG:
			$src_img = imagecreatefrompng($src);
			break;
		default:
			die('unsupported image format');
	}
	$dest_img = imagecreatetruecolor($width, $height) or die('no gd lib install');
	imagecopyresampled($dest_img, $src_img, 0, 0, $posX, $posY, $width, $height, $cutWidth, $cutHeight);
	
	switch ($info[2]) {
		case IMG_GIF:
			$srcImg = imagecreatefromgif($src);
			break;
		case IMG_JPG:
			$srcImg = imagecreatefromjpeg($src);
			break;
		case IMG_JPEG:
			$srcImg = imagecreatefromjpeg($src);
			break;
		case IMG_PNG:
			$srcImg = imagecreatefrompng($src);
			break;
		default:
			die('unsupported image format');
	}
	if ($dest != '') {
		$pos = strrpos($dest, '.');
		$ext = strtolower(substr($dest, $pos + 1, strlen($dest) - $pos - 1));
		switch ($ext) {
			case 'jpeg':
				imagejpeg($dest_img, $dest);
				break;
			case 'jpg':
				imagejpeg($dest_img, $dest);
				break;
			case 'gif':
				imagegif($dest_img, $dest);
				break;
			case 'png':
				imagepng($dest_img, $dest);
				break;
			default:
				{
					switch ($info[2]) {
						case IMG_GIF:
							imagegif($dest_img, $dest);
							break;
						case IMG_JPG:
							imagejpeg($dest_img, $dest);
							break;
						case IMG_JPEG:
							imagejpeg($dest_img, $dest);
							break;
						case IMG_PNG:
							imagepng($dest_img, $dest);
							break;
					}
				}
		}
		return $dest;
	} else {
		header("Content-type: image/PNG");
		imagepng($dest_img);
	}
	imagedestroy($dest_img);
	imagedestroy($src_img);
}

function tep_get_product_option_name_from_optionid($opt_id) {
	global $languages_id;
	$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt where popt.products_options_id='" . (int) $opt_id . "' and popt.language_id = '" . (int) $languages_id . "'");
	if (tep_db_num_rows($products_options_name_query) > 0) {
		$products_options_name = tep_db_fetch_array($products_options_name_query);
		return $products_options_name['products_options_name'];
	} else {
		return false;
	}
}

/**
 * 取得当前选中产品的属性价格 用于产品详细页面，注意全局变量 *
 */
function attributes_price_display($products_id, $option, $value) {
	global $total_no_guest_tour, $total_room_adult_child_info, $final_cruises_each_room_prices;
	$attributes_price = 0;
	
	$attribute_price_query = tep_db_query("select options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int) $products_id . "' and options_id = '" . (int) $option . "' and options_values_id = '" . (int) $value . "'");
	
	while ($attribute_price = tep_db_fetch_array($attribute_price_query)) {
		
		// amit modified to make sure price on usd
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int) $products_id);
		if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			$attribute_price['single_values_price'] = tep_get_tour_price_in_usd($attribute_price['single_values_price'], $tour_agency_opr_currency);
			$attribute_price['double_values_price'] = tep_get_tour_price_in_usd($attribute_price['double_values_price'], $tour_agency_opr_currency);
			$attribute_price['triple_values_price'] = tep_get_tour_price_in_usd($attribute_price['triple_values_price'], $tour_agency_opr_currency);
			$attribute_price['quadruple_values_price'] = tep_get_tour_price_in_usd($attribute_price['quadruple_values_price'], $tour_agency_opr_currency);
			$attribute_price['kids_values_price'] = tep_get_tour_price_in_usd($attribute_price['kids_values_price'], $tour_agency_opr_currency);
			$attribute_price['options_values_price'] = tep_get_tour_price_in_usd($attribute_price['options_values_price'], $tour_agency_opr_currency);
		}
		// amit modified to make sure price on usd
		

		// new change start
		$att[1] = $attribute_price['single_values_price']; // single
		$att[2] = $attribute_price['double_values_price']; // double
		$att[3] = $attribute_price['triple_values_price']; // triple
		$att[4] = $attribute_price['quadruple_values_price']; // quadr
		$ett = $attribute_price['kids_values_price']; // child Kid
		// need to change logic
		// according to new added field
		// amit added to check apply
		// attribute per order start
		$is_per_order_attribute = tep_get_is_apply_price_per_order_option_value($value);
		// amit added to check apply attribute per order end
		if (($attribute_price['single_values_price'] > 0 || $attribute_price['double_values_price'] > 0 || $attribute_price['triple_values_price'] > 0 || $attribute_price['quadruple_values_price'] > 0) && $is_per_order_attribute != 1) {
			
			$final_chk_price = 0;
			// check for old data stored on customer bascket start
			if ($total_room_adult_child_info == '') {
				
				$final_chk_price = $attribute_price['single_values_price'] * $total_no_guest_tour;
			} else {
				
				// check for old data stored on customer bascket end
				$tot_nn_roms_chked = get_total_room_from_str($total_room_adult_child_info);
				
				if ($tot_nn_roms_chked > 0) {
					for ($ri = 1; $ri <= $tot_nn_roms_chked; $ri ++) {
						
						$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info, $ri);
						
						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info, $ri);
						
						if ($chaild_adult_no_arr[1] == 0) {
							$_tmp_val = $chaild_adult_no_arr[0] * $att[$chaild_adult_no_arr[0]];
							$final_chk_price += $_tmp_val;
						} else {
							
							if ($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '1') {
								$low_price_formula_option[0] = 2 * $att[2];
								$low_price_formula_option[1] = $att[1] + $ett;
								$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1]);
								$final_chk_price += $_tmp_val;
							} else 
								if ($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '2') {
									$low_price_formula_option[0] = (2 * $att[2]) + $ett;
									$low_price_formula_option[1] = 3 * $att[3];
									$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1]);
									$final_chk_price += $_tmp_val;
								} else 
									if ($chaild_adult_no_arr[0] == '1' && $chaild_adult_no_arr[1] == '3') {
										$low_price_formula_option[0] = (2 * $att[2]) + 2 * $ett;
										$low_price_formula_option[1] = 3 * $att[3] + $ett;
										$low_price_formula_option[2] = 4 * $att[4];
										$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1], $low_price_formula_option[2]);
										$final_chk_price += $_tmp_val;
									} else 
										if ($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '1') {
											$low_price_formula_option[0] = (2 * $att[2]) + $ett;
											$low_price_formula_option[1] = 3 * $att[3];
											$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1]);
											$final_chk_price += $_tmp_val;
										} else 
											if ($chaild_adult_no_arr[0] == '2' && $chaild_adult_no_arr[1] == '2') {
												$low_price_formula_option[0] = (2 * $att[2]) + 2 * $ett;
												$low_price_formula_option[1] = 3 * $att[3] + $ett;
												$low_price_formula_option[2] = 4 * $att[4];
												$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1], $low_price_formula_option[2]);
												$final_chk_price += $_tmp_val;
											} else 
												if ($chaild_adult_no_arr[0] == '3' && $chaild_adult_no_arr[1] == '1') {
													$low_price_formula_option[0] = 3 * $att[3] + $ett;
													$low_price_formula_option[1] = 4 * $att[4];
													$_tmp_val = min($low_price_formula_option[0], $low_price_formula_option[1]);
													$final_chk_price += $_tmp_val;
												}
						}
						// 邮轮每种房间价格数组。下面的数组的$key由成每房间[人数_小孩]数组成(默认值全是正值)
						$final_cruises_each_room_prices[$chaild_adult_no_arr[0] . '_' . $chaild_adult_no_arr[1]] = $_tmp_val;
					}
				} else {
					// tour with no room option but special price added
					

					/*
					 * $final_chk_price =
					 * $attribute_price['single_values_price']*$total_no_guest_tour;
					 */
					$tt_persion_per_room_cal_price = tep_get_room_total_persion_from_str($total_room_adult_child_info, 1);
					$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info, 1);
					$_tmp_val = $chaild_adult_no_arr[0] * $att[1] + $chaild_adult_no_arr[1] * $ett;
					$final_chk_price = $_tmp_val;
					// 邮轮每种房间价格数组。下面的数组的$key由成每房间[人数_小孩]数组成(默认值全是正值)
					$final_cruises_each_room_prices[$chaild_adult_no_arr[0] . '_' . $chaild_adult_no_arr[1]] = $_tmp_val;
				}
			}
			
			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $final_chk_price;
			} else {
				$attributes_price += $final_chk_price;
			}
			
			// echo (int)$products_id.'-->'.$attributes_price.'<br>';
		} else {
			
			// amit added to check apply attribute per order start
			$temp_default_per_count = $total_no_guest_tour;
			if ($is_per_order_attribute == 1) {
				$temp_default_per_count = 1; // per order attribute
			}
			// amit added to check apply attribute per order end
			

			if ($attribute_price['price_prefix'] == '-') {
				$attributes_price -= $attribute_price['options_values_price'] * $temp_default_per_count;
			} else {
				$attributes_price += $attribute_price['options_values_price'] * $temp_default_per_count;
			}
		}
		// new change end
	}
	
	return $attributes_price;
}

/**
 * 取得所有属于邮轮选项的产品属性ID
 */
function getAllCruisesOptionIds() {
	$data = false;
	$sql = tep_db_query('SELECT DISTINCT products_options_id FROM `cruises_cabin` WHERE 1 ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows['products_options_id'];
	}
	return $data;
}
/*
 * 获取产品属性，该函数用于获取order中product 的attribute cart的是这样 Array( [attributeId] =>
 * attributeValue) order中的是 Array( [0] => Array ( [option] => [value] =>
 * [option_id] => attributeId [value_id] => attributeValue [prefix] => [price]
 * => 0 price_cost] => 0 )) 该函数用于酒店延住　（返回　1为参团前　2为参团后 by lwkai add） @author
 * vincent
 */
function getProductAttribute($dataSrc, $attributeId, $defaultValue = '') {
	$dataSrc = (array) $dataSrc;
	if (isset($dataSrc[$attributeId])) {
		return $dataSrc[$attributeId];
	} else {
		$v = '';
		foreach ($dataSrc as $index => $att) {
			
			if (is_array($att) && isset($att['option_id']) && $att['option_id'] == $attributeId) {
				if (trim($att['value']) != '') {
					$v = $att['value'];
					break;
				} else 
					if (trim($att['value_id']) != '') {
						$v = $att['value_id'];
						break;
					}
			}
		}
		return $v;
	}
}

/**
 * 取得订单产品的付款状态数组 0未付款，1已付款，2已部分付款
 */
function tep_get_orders_products_payment_status_array() {
	$array = array ();
	$array[] = array (
			'id' => '0',
			'text' => '未付款',
			'color' => '' 
	);
	$array[] = array (
			'id' => '1',
			'text' => '已付款',
			'color' => '#F00' 
	); // 已付款是红字
	$array[] = array (
			'id' => '2',
			'text' => '已部分付款',
			'color' => '#090' 
	); // 已部分付款是绿色
	return $array;
}

/**
 * 取得订单产品的付款状态名称
 *
 * @param unknown_type $status_id
 */
function tep_get_orders_products_payment_status_name($status_id, $need_style = true) {
	$status_id = (int) $status_id;
	$array = tep_get_orders_products_payment_status_array();
	if ($need_style == true && $array[$status_id]['color'] != '') {
		return '<b style="color:' . $array[$status_id]['color'] . '">' . $array[$status_id]['text'] . '</b>';
	}
	return $array[$status_id]['text'];
}

/**
 * 更新客服对订单的所有权
 *
 * @param unknown_type $login_id
 * @param unknown_type $orders_id
 * @param unknown_type $commission
 */
function tep_update_order_orders_owner($login_id, $orders_id, $commission = '1') {
	if (tep_check_service_account($login_id)) { // 是客服的账号才能更新
		tep_db_query('update ' . TABLE_ORDERS . ' set orders_owner_admin_id="' . (int) $login_id . '", orders_owner_commission="' . tep_db_input(tep_db_prepare_input($commission)) . '" where orders_id="' . (int) $orders_id . '" and (orders_owner_commission="" || orders_owner_commission="0" || orders_owner_commission IS NULL ); ');
	}
}

/**
 * 检查某个登录id是不是客服，返回true或false admin_groups_id = 7或5和48的就是客服，其它不是，其中操作员42也不能算
 *
 * @param unknown_type $login_id
 *
 */
function tep_check_service_account($login_id) {
	$sql = tep_db_query('SELECT admin_groups_id FROM `admin` WHERE admin_id ="' . (int) $login_id . '" ');
	$row = tep_db_fetch_array($sql);
	if (tep_not_null($row['admin_groups_id'])) {
		$_tmp_array = explode(',', $row['admin_groups_id']);
		if (in_array('5', $_tmp_array) || in_array('7', $_tmp_array) || in_array('48', $_tmp_array)) {
			return true;
		}
	}
	return false;
}

/**
 * 从工号取得客服的id
 *
 * @param unknown_type $admin_job_number
 */
function tep_get_admin_id_from_job_number($admin_job_number) {
	$sql = tep_db_query('SELECT admin_id FROM `admin` WHERE admin_job_number ="' . tep_db_prepare_input(tep_db_input($admin_job_number)) . '" ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['admin_id'];
}

/**
 * 取得某产品余座数据
 *
 * @param int $product_id
 * @return false array
 * @author Howard
 */
function tep_get_remaining_seats($product_id) {
	$data = false;
	$sql = tep_db_query('SELECT departure_date, remaining_seats_num	FROM `products_remaining_seats` WHERE `products_id` = "' . (int) $product_id . '"');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[$rows['departure_date']] = $rows['remaining_seats_num']; // key为日期,值为具体的座位数
	}
	return $data;
}

/**
 * 取得产品的月份价格数组，用于大日历框和合作API，输出的是json格式字符
 *
 * @param unknown_type $product_id
 */
function tep_get_product_month_price_datas($product_id) {
	global $currency, $currencies;
	$remaining_seats_date = tep_get_remaining_seats($product_id); // 取得余座数据
	$data = false;
	include_once (DIR_FS_FUNCTIONS . 'get_avaliabledate.php');
	$date_p = get_avaliabledate($product_id);
	$date_p = remove_soldout_dates((int) $product_id, $date_p);
	array_multisort((array) $date_p, SORT_ASC);
	if (sizeof($date_p) > 0) {
		foreach ($date_p as $date_string => $val) {
			$_date = explode('::', $date_string);
			$strtotime = strtotime($_date[0]);
			$y = date('Y', $strtotime); // 年
			$m = date('n', $strtotime); // 月
			$d = date('j', $strtotime); // 日
			$prices = tep_get_product_date_price($product_id, $date_string); // 当天价格数组
			$data[$y][$m][$d] = array ();
			
			// 起价与tips提示 {
			$tips = '';
			$_YMD = date('Y-m-d', $strtotime);
			if (array_key_exists($_YMD, (array) $remaining_seats_date)) {
				if ($remaining_seats_date[$_YMD] >= 1) {
					$tips = ' <br><span class="red">紧张</span>';
				} else {
					$tips = ' <br><span class="red">已售完</span>';
				}
			}
			$data[$y][$m][$d]['p'] = iconv(CHARSET, 'utf-8', db_to_html(preg_replace("/\.00$/", "", $currencies->format($prices['lowest'], true, $currency)) . ' ' . $tips)); // 日期起价
			// 起价与tips提示
			// }
			

			$single_str = '单人一房';
			if (!isset($prices['double'])) {
				$single_str = '成人';
			}
			$price_type = array (
					'double' => "双人一房",
					'triple' => "三人一房",
					'quadruple' => "四人一房",
					'single' => $single_str,
					'single_pu' => "单人配房",
					'kids' => "小孩" 
			);
			foreach ($price_type as $type => $price_name) {
				if (tep_not_null($prices[$type])) {
					$data[$y][$m][$d]['detail'][$type] = array (
							'title' => iconv(CHARSET, 'utf-8', db_to_html($price_name)),
							'text' => preg_replace("/\.00$/", "", $currencies->format($prices[$type], true, $currency)) 
					);
				}
			}
		}
		$data = json_encode($data);
	}
	
	if ($data == false) { // 自动设置产品无库存
		tep_set_products_not_stock($product_id);
	}
	
	return $data == false ? '{}' : $data;
}

/**
 * 设置产品库存状态为已卖完
 *
 * @param int $product_id
 */
function tep_set_products_not_stock($product_id) {
	$query_str = 'update products set products_stock_status="0" where products_id="' . (int) $product_id . '" ';
	tep_db_query($query_str);
}

/**
 * 取得某产品的某一天的价格数据
 *
 * @param unknown_type $product_id
 * @param unknown_type $availabletourdate 2012-02-19::##!!!216594
 */
function tep_get_product_date_price($product_id, $availabletourdate) {
	global $product_info;
	$get_reg_date_price_array = explode('!!!', $availabletourdate);
	$tmp_dp_date = date('Y-m-d', strtotime(substr($get_reg_date_price_array[0], 0, 10)));
	
	$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int) $product_id); // 获取代理商结算货币
	// 标准价{
	$lowest = $single = $single_pu = $double = $triple = $quadruple = $kids = 0;
	if (!tep_not_null($product_info)) {
		$sql = tep_db_query("SELECT	p.products_single,p.products_single_pu,p.products_double,p.products_triple,p.products_quadr,p.products_kids FROM " . TABLE_PRODUCTS . " p WHERE p.products_id = '" . (int) $product_id . "'");
		$product_info = tep_db_fetch_array($sql);
	}
	// 标准价}
	// Howard 取得通过特价和团购检查后的最终标准价
	tep_get_final_price_from_specials($product_id, $product_info, $is_new_group_buy);
	// $lowest = $product_info['products_price'];
	$single = $product_info['products_single'];
	$single_pu = $product_info['products_single_pu'];
	$double = $product_info['products_double'];
	$triple = $product_info['products_triple'];
	$quadruple = $product_info['products_quadr'];
	$kids = $product_info['products_kids'];
	
	/*
	 * // Howard added new group buy start { $group_buy_specials_sql =
	 * tep_db_query('SELECT * FROM `specials` WHERE products_id
	 * ="'.(int)$product_id.'" AND status="1" AND start_date <="'.date('Y-m-d
	 * H:i:s').'" AND expires_date >"'.date('Y-m-d H:i:s').'" ');
	 * $group_buy_specials = tep_db_fetch_array($group_buy_specials_sql);
	 * if((int)$group_buy_specials['products_id']){
	 * if($group_buy_specials['specials_type'] =="1"){ $tmpPurchasedNum =
	 * tep_get_product_orders_guest_count($group_buy_specials['products_id'],$group_buy_specials['start_date'],$group_buy_specials['expires_date']);
	 * $tmpBalanceNum =
	 * max(0,(int)($group_buy_specials['specials_max_buy_num']-$tmpPurchasedNum));
	 * } //0为普通特价，1为限量团购，2为限时团购 if(($group_buy_specials['specials_type'] =="1"
	 * && (int)$tmpBalanceNum) || $group_buy_specials['specials_type']=="2" ||
	 * $group_buy_specials['specials_type']=="0"){
	 * if($group_buy_specials['specials_type'] =="1" ||
	 * $group_buy_specials['specials_type']=="2"){ $is_new_group_buy = 1; }
	 * if((int)$group_buy_specials['specials_new_products_single']) $single =
	 * $group_buy_specials['specials_new_products_single'];
	 * if((int)$group_buy_specials['specials_new_products_single_pu'])
	 * $single_pu = $group_buy_specials['specials_new_products_single_pu'];
	 * if((int)$group_buy_specials['specials_new_products_double']) $double =
	 * $group_buy_specials['specials_new_products_double'];
	 * if((int)$group_buy_specials['specials_new_products_triple'])$triple =
	 * $group_buy_specials['specials_new_products_triple'];
	 * if((int)$group_buy_specials['specials_new_products_quadr'])	$quadruple =
	 * $group_buy_specials['specials_new_products_quadr'];
	 * if((int)$group_buy_specials['specials_new_products_kids'])	$kids =
	 * $group_buy_specials['specials_new_products_kids']; } } // Howard added
	 * new group buy end }
	 */
	// 假日价格计算start {
	// 行程价格计算
	// amit modified to make sure price on usd{
	if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
		$single = tep_get_tour_price_in_usd($single, $tour_agency_opr_currency);
		$single_pu = tep_get_tour_price_in_usd($single_pu, $tour_agency_opr_currency);
		$double = tep_get_tour_price_in_usd($double, $tour_agency_opr_currency);
		$triple = tep_get_tour_price_in_usd($triple, $tour_agency_opr_currency);
		$quadruple = tep_get_tour_price_in_usd($quadruple, $tour_agency_opr_currency);
		$kids = tep_get_tour_price_in_usd($kids, $tour_agency_opr_currency);
	}
	// amit modified to make sure price on usd}
	

	/*
	 * Price Displaying - standard price for different reg/irreg sections -
	 * start {
	 */
	
	$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where available_date ='" . $tmp_dp_date . "' and products_id ='" . (int) $product_id . "' ");
	if (tep_db_num_rows($check_standard_price_dates) > 0) {
		$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
		$operate_start_date = $row_standard_price_dates['operate_start_date'];
		$operate_end_date = $row_standard_price_dates['operate_end_date'];
	} else {
		$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_start_day_id ='" . $get_reg_date_price_array[1] . "' and products_id ='" . (int) $product_id . "' ");
		$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
		$operate_start_date = $row_standard_price_dates1['operate_start_date'];
		$operate_end_date = $row_standard_price_dates1['operate_end_date'];
	}
	
	$check_section_standard_price_query = "select * from " . TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE . " where operate_start_date ='" . $operate_start_date . "' and operate_end_date = '" . $operate_end_date . "' and products_id ='" . (int) $product_id . "' and products_single > 0 ";
	$check_section_standard_price = tep_db_query($check_section_standard_price_query);
	if (tep_db_num_rows($check_section_standard_price) > 0) {
		$row_section_standard_price = tep_db_fetch_array($check_section_standard_price);
		
		if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			$row_section_standard_price['products_single'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single'], $tour_agency_opr_currency);
			$row_section_standard_price['products_single_pu'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_pu'], $tour_agency_opr_currency);
			$row_section_standard_price['products_double'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double'], $tour_agency_opr_currency);
			$row_section_standard_price['products_triple'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple'], $tour_agency_opr_currency);
			$row_section_standard_price['products_quadr'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr'], $tour_agency_opr_currency);
			$row_section_standard_price['products_kids'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids'], $tour_agency_opr_currency);
		}
		if ((int) $row_section_standard_price['products_single']) {
			$single = $row_section_standard_price['products_single'];
		}
		if ((int) $row_section_standard_price['products_single_pu']) {
			$single_pu = $row_section_standard_price['products_single_pu'];
		}
		if ((int) $row_section_standard_price['products_double']) {
			$double = $row_section_standard_price['products_double'];
		}
		if ((int) $row_section_standard_price['products_triple']) {
			$triple = $row_section_standard_price['products_triple'];
		}
		if ((int) $row_section_standard_price['products_quadr']) {
			$quadruple = $row_section_standard_price['products_quadr'];
		}
		if ((int) $row_section_standard_price['products_kids']) {
			$kids = $row_section_standard_price['products_kids'];
		}
	}
	
	/*
	 * Price Displaying - standard price for different reg/irreg sections - end
	 * }
	 */
	
	// amit added to check if special price is available for specific date start
	// {
	$special_dt_chk = 0;
	$check_specip_pride_date_select = "select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where available_date ='" . $tmp_dp_date . "' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0) and products_id ='" . (int) $product_id . "' ";
	$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
	while ($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)) {
		// amit modified to make sure price on usd{
		if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'], $tour_agency_opr_currency);
			$check_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_pu'], $tour_agency_opr_currency);
			$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'], $tour_agency_opr_currency);
			$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'], $tour_agency_opr_currency);
			$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'], $tour_agency_opr_currency);
			$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'], $tour_agency_opr_currency);
		}
		if ((int) $check_specip_pride_date_row['spe_single']) {
			$single = $check_specip_pride_date_row['spe_single'];
		}
		if ((int) $check_specip_pride_date_row['spe_single_pu']) {
			$single_pu = $check_specip_pride_date_row['spe_single_pu'];
		}
		if ((int) $check_specip_pride_date_row['spe_double']) {
			$double = $check_specip_pride_date_row['spe_double'];
		}
		if ((int) $check_specip_pride_date_row['spe_triple']) {
			$triple = $check_specip_pride_date_row['spe_triple'];
		}
		if ((int) $check_specip_pride_date_row['spe_quadruple']) {
			$quadruple = $check_specip_pride_date_row['spe_quadruple'];
		}
		if ((int) $check_specip_pride_date_row['spe_kids']) {
			$kids = $check_specip_pride_date_row['spe_kids'];
		}
		
		// amit modified to make sure price on usd}
		$special_dt_chk = 1;
	}
	// }amit added to check if special price is available specific date end
	// amit added to check if regular special price available start {
	

	if ($special_dt_chk == 0 && $get_reg_date_price_array[1] != '') {
		$check_reg_specip_pride_date_select = "select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_start_day_id ='" . $get_reg_date_price_array[1] . "' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='" . (int) $product_id . "' ";
		$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
		
		// echo $check_reg_specip_pride_date_select."<hr>";
		// echo $operate_start_date.':'.$operate_end_date; exit;
		while ($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)) {
			
			if ($check_reg_specip_pride_date_row['extra_charge'] > 0) {
			} else {
				// amit modified to make sure price on usd {
				if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
					$check_reg_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'], $tour_agency_opr_currency);
					$check_reg_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_pu'], $tour_agency_opr_currency);
					$check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'], $tour_agency_opr_currency);
					$check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'], $tour_agency_opr_currency);
					$check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'], $tour_agency_opr_currency);
					$check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'], $tour_agency_opr_currency);
				}
				// amit modified to make sure price on usd }
				if ((int) $check_reg_specip_pride_date_row['spe_single']) {
					$single = $check_reg_specip_pride_date_row['spe_single'];
				}
				if ((int) $check_reg_specip_pride_date_row['spe_single_pu']) {
					$single_pu = $check_reg_specip_pride_date_row['spe_single_pu'];
				}
				if ((int) $check_reg_specip_pride_date_row['spe_double']) {
					$double = $check_reg_specip_pride_date_row['spe_double'];
				}
				if ((int) $check_reg_specip_pride_date_row['spe_triple']) {
					$triple = $check_reg_specip_pride_date_row['spe_triple'];
				}
				if ((int) $check_reg_specip_pride_date_row['spe_quadruple']) {
					$quadruple = $check_reg_specip_pride_date_row['spe_quadruple'];
				}
				if ((int) $check_reg_specip_pride_date_row['spe_kids']) {
					$kids = $check_reg_specip_pride_date_row['spe_kids'];
				}
			}
			$special_dt_chk = 1;
		}
	}
	// }amit added to check if regular special price avalilable end
	// 假日价格计算end }
	

	$prices = false;
	if ((int) $single)
		$lowest = $prices['single'] = $single; // 单人价（成人价）
	if ((int) $single_pu)
		$lowest = $prices['single_pu'] = $single_pu; // 单人配房价
	if ((int) $double)
		$lowest = $prices['double'] = $double; // 双人均价
	if ((int) $triple)
		$lowest = $prices['triple'] = $triple; // 三人均价
	if ((int) $quadruple)
		$lowest = $prices['quadruple'] = $quadruple; // 四人均价
	if ((int) $kids)
		$prices['kids'] = $kids; // 小孩价
		

	// 取得除了小孩之外的最低价格
	if ((int) $single && $lowest > $single) {
		$lowest = $single;
	}
	if ((int) $single_pu && $lowest > $single_pu) {
		$lowest = $single_pu;
	}
	if ((int) $double && $lowest > $double) {
		$lowest = $double;
	}
	if ((int) $triple && $lowest > $triple) {
		$lowest = $triple;
	}
	if ((int) $quadruple && $lowest > $quadruple) {
		$lowest = $quadruple;
	}
	$prices['lowest'] = $lowest; // 最低价
	

	return $prices;
}

/**
 * 当支付成功时记录客户的支付过程并更新订单已付款的数据（必须是已经成功支付了的）,并使此订单置顶
 *
 * @param unknown_type $orders_id
 * @param unknown_type $usa_value
 * @param unknown_type $payment_method
 * @param unknown_type $comment （如果是人民币支付的则要记录当时的汇率，人民币数值等）
 * @param unknown_type $orders_id_include_time 带时间值的订单号（用于在线付款）
 */
function tep_payment_success_update($orders_id, $usa_value, $payment_method = '', $comment = '', $admin_id = 0, $orders_id_include_time) {
	$dateTime = date('Y-m-d H:i:s');
	if (strlen($orders_id_include_time) < 1) {
		die('no $orders_id_include_time');
	}
	if ((int) $orders_id && $usa_value != "" && $payment_method != "") {
		$sql = tep_db_query('SELECT orders_payment_history_id FROM `orders_payment_history` where orders_id="' . (int) $orders_id . '" and orders_id_include_time="' . (string) $orders_id_include_time . '" ');
		$row = tep_db_fetch_array($sql);
		if (!(int) $row['orders_payment_history_id']) { // 无此历史ID时才记录以防重复
			$data_array = array (
					'orders_id' => (int) $orders_id,
					'orders_id_include_time' => $orders_id_include_time,
					'orders_value' => $usa_value,
					'payment_method' => $payment_method,
					'comment' => $comment,
					'add_date' => $dateTime,
					'admin_id' => $admin_id 
			);
			tep_db_perform('orders_payment_history', $data_array);
			$sql = tep_db_query('select sum(orders_value) as total FROM orders_payment_history WHERE orders_id="' . (int) $orders_id . '" ');
			$row = tep_db_fetch_array($sql);
			tep_db_query('update `orders` set orders_paid="' . $row['total'] . '" WHERE orders_id ="' . (int) $orders_id . '" ');
			// 更新订单付款状态
			$orders_total_sql = tep_db_query('SELECT value FROM `orders_total` where orders_id="' . (int) $orders_id . '" and class="ot_total" ');
			$orders_row = tep_db_fetch_array($orders_total_sql);
			if (tep_round($orders_row['value'], 2) <= tep_round($row['total'], 2)) {
				tep_db_query('update `orders_products` set orders_products_payment_status="1" WHERE orders_id ="' . (int) $orders_id . '" ');
			} elseif ((int) $row['total']) {
				tep_db_query('update `orders_products` set orders_products_payment_status="2" WHERE orders_id ="' . (int) $orders_id . '" ');
			}
			tep_db_query('update `orders` set last_modified="' . $dateTime . '", is_top="1" where orders_id ="' . (int) $orders_id . '" ');
			//检查和设置客人再次付款订单(付款记录是负值的不记)
			if((int)$usa_value > 0){
				tep_set_or_close_again_paid_orders($orders_id, '1');
			}
			// 此处发电子邮件通知财务人员，未做
			return true;
		}
	}
	
	return false;
}


/**
 * 设置或取消客人再次付款订单
 * @param int $orders_id 订单号
 * @param int $val 设置的值1为设置，0为取消设置
 */
function tep_set_or_close_again_paid_orders($orders_id, $val='1'){
	$orders_id = (int)$orders_id;
	if($val=="1"){ //订单有发送地接历史，客人再次付款的才可以设置为再次付款订单
		$sql = tep_db_query('SELECT count(*) as num FROM orders_payment_history where orders_id="'.$orders_id.'" and orders_value>0 ');
		$row = tep_db_fetch_array($sql);
		if((int)$row['num']<2){
			return false;
		}
		$psql = tep_db_query('SELECT popsh.popc_id FROM `orders_products` op, provider_order_products_status_history popsh where op.orders_products_id=popsh.orders_products_id and op.orders_id="'.$orders_id.'" Limit 1');
		$prow = tep_db_fetch_array($psql);
		if(!$prow['popc_id']){
			return false;
		}
	}
	tep_db_query('update orders set is_again_paid="'.(int)$val.'" where orders_id="'.(int)$orders_id.'" ');
	return tep_db_affected_rows();
}

/**
 * 发送在线支付成功的邮件（PayPal\信用卡\支付宝\贝宝）
 *
 * @param unknown_type $orders_id
 * @param unknown_type $payment
 */
function tep_send_payment_success_email($orders_id, $payment) {
	if (!in_array($payment, array (
			'paypal',
			'alipay_direct_pay',
			'paypal_nvp_samples' 
	))) {
		return false;
	}
	$title = array (
			'paypal' => 'PayPal',
			'alipay_direct_pay' => '支付宝',
			'paypal_nvp_samples' => '信用卡（美元）' 
	);
	if (!class_exists('order')) {
		require_once (DIR_FS_CLASSES . 'order.php');
		$order = new order($orders_id);
	}
	$_url = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders_id, 'SSL', false);
	
	$to_email_address = tep_get_customers_email($order->info['customers_id']);
	$to_name = tep_get_customer_name($order->info['customers_id']);
	$email_subject = sprintf('走四方(Usitrip.com)订单-%s支付(订单号：%s)', $title[$payment], $orders_id);
	$from_email_name = STORE_OWNER;
	$from_email_address = 'automail@usitrip.com';
	$action_type = EMAIL_USE_HTML;
	$email_order = "尊敬的 " . $to_name . " 先生/女士： \n" . '您好！非常感谢您预订美国走四方(Usitrip.com)的旅游产品！' . "\n\n";
	$email_order .= '<strong>您的订单<a href="' . $_url . '" target="_blank">' . $orders_id . "</a>已收到。</strong>\n" . '我们会在收到您的付款后， 3-4个工作日内发送行程确认单到您的注册邮箱（收到该行程确认单，表明您的行程已订购成功），请留意查收邮件。' . "\n\n";
	$products_ordered = ''; // 产品信息省略了，产品属性的信息，如果需要可参考checkout_process.php文件
	for ($i = 0, $n = sizeof($order->products); $i < $n; $i ++) {
		$products_ordered .= '线路名称：' . $order->products[$i]['name'] . "\n";
		$products_ordered .= '旅游团号：' . $order->products[$i]['model'] . "\n";
		$products_ordered .= '出发日期：' . $order->products[$i]['products_departure_date'] . "\n";
		if (tep_not_null($order->products[$i]['products_departure_location'])) {
			$products_ordered .= '出发地点：' . $order->products[$i]['products_departure_time'] . ' ' . $order->products[$i]['products_departure_location'] . "\n";
		}
	}
	$email_order .= EMAIL_SEPARATOR . "\n" . ('<strong>订单详情：<a href="' . $_url . '" target="_blank">' . $orders_id . '</a>（您可以使用预订时注册的Email登录您的账户来查询订单详情）</strong>') . "\n" . str_replace('$0.00', '', $products_ordered); // 移除金额为0的字样
	

	$orderTotals = $order->totals;
	for ($i = 0, $n = sizeof($orderTotals); $i < $n; $i ++) {
		if ($i == 0) {
			$email_order .= EMAIL_SEPARATOR . "\n";
		}
		$email_order .= strip_tags($orderTotals[$i]['title']) . ' ' . strip_tags($orderTotals[$i]['text']) . "\n";
	}
	$email_order .= EMAIL_SEPARATOR . "\n" . "<strong>支付方式</strong> " . $title[$payment] . "\n";
	$email_order .= EMAIL_SEPARATOR . "\n";
	$email_order .= "\n<b>" . '走四方旅游网法律声明：此邮件仅作走四方网预订的系统收据，不能将此作为签证等其他任何用途！若需要办理签证所需的邀请函，请联系我们在线专业顾问，谢谢！' . "</b>\n";
	$email_order .= CONFORMATION_EMAIL_FOOTER . "\n";
	$email_order .= email_track_code('NewOrder', $to_email_address, $orders_id);
	
	$var_num = (int) count($_SESSION['need_send_email']);
	$_SESSION['need_send_email'][$var_num]['to_name'] = db_to_html($to_name);
	$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
	$_SESSION['need_send_email'][$var_num]['email_subject'] = db_to_html($email_subject);
	$_SESSION['need_send_email'][$var_num]['email_text'] = db_to_html($email_order);
	$_SESSION['need_send_email'][$var_num]['from_email_name'] = db_to_html($from_email_name);
	$_SESSION['need_send_email'][$var_num]['from_email_address'] = $from_email_address;
	$_SESSION['need_send_email'][$var_num]['action_type'] = $action_type;
	
	// print_r($email_order);
}

/**
 * 取得某个订单需要支付的金额。[0]为美元，[1]为人民币 注意：支付模块会用到此函数
 *
 * @param unknown_type $orders_id
 */
function tep_get_need_pay_value($orders_id) {
	$data = false;
	$sql = tep_db_query('SELECT ot.orders_id, ot.value, o.orders_paid, o.us_to_cny_rate FROM `orders_total` ot, `orders` o WHERE o.orders_id = ot.orders_id and ot.class="ot_total" and o.orders_id=' . (int) $orders_id);
	$row = tep_db_fetch_array($sql);
	if ((int) $row['orders_id']) {
		$data[0] = $row['value'] - $row['orders_paid'];
		$data[1] = number_format(($data[0]) * max(1, number_format($row['us_to_cny_rate'], 6, '.', '')), 2, '.', '');
	}
	return $data;
}

/**
 * 将人民币转成美元
 *
 * @param unknown_type $orders_id
 * @param unknown_type $cny_total
 */
function tep_cny_to_usd($orders_id, $cny_total) {
	$data = $cny_total;
	$sql = tep_db_query('SELECT o.us_to_cny_rate FROM `orders` o WHERE o.orders_id=' . (int) $orders_id);
	$row = tep_db_fetch_array($sql);
	if ($row['us_to_cny_rate'] > 1) {
		$data = number_format($data / max(1, number_format($row['us_to_cny_rate'], 6, '.', '')), 2, '.', '');
	}
	return $data;
}

/**
 * 将美元转成人民币
 *
 * @param unknown_type $orders_id
 * @param unknown_type $usd_total
 * @return unknown
 */
function tep_usd_to_cny($orders_id, $usd_total) {
	$data = $usd_total;
	$sql = tep_db_query('SELECT o.us_to_cny_rate FROM `orders` o WHERE o.orders_id=' . (int) $orders_id);
	$row = tep_db_fetch_array($sql);
	if ($row['us_to_cny_rate'] > 1) {
		$data = number_format($data * max(1, number_format($row['us_to_cny_rate'], 6, '.', '')), 2, '.', '');
	}
	return $data;
}

/**
 * 根据邮箱获取用户姓名
 *
 * @param string $customer_email 用户邮箱
 * @param string $type
 * @return string
 */
function tep_get_customer_name_from_email($customer_email, $type = 'cn') {
	$rtn = '';
	if (tep_not_null($customer_email) == true) {
		$sql = tep_db_query('SELECT customers_firstname, customers_lastname FROM `customers` where customers_email_address="' . $customer_email . '"');
		$row = tep_db_fetch_array($sql);
		if ($type == 'cn') {
			$rtn = $row['customers_firstname'];
		} else {
			$rtn = $row['customers_lastname'];
		}
	}
	return $rtn;
}

/**
 * 取得用户的名字
 *
 * @param $customer_id 用户ID
 * @param $type 名字类型，cn是中文名, en是英文名
 */
function tep_get_customer_name($customer_id, $type = 'cn') {
	$sql = tep_db_query('SELECT customers_firstname, customers_lastname FROM `customers` where customers_id="' . (int) $customer_id . '"');
	$row = tep_db_fetch_array($sql);
	if ($type == 'cn') {
		return $row['customers_firstname'];
	}
	return $row['customers_lastname'];
}

/**
 * 取得网站顶部子菜单列表的目录IDs
 *
 * @param unknown_type $category_id
 */
function tep_get_header_categorys($category_id) {
	$data = false;
	$sql = tep_db_query('SELECT categories_id FROM `categories` WHERE parent_id="' . (int) $category_id . '" and categories_status="1" and categories_feature_status="1" Order By sort_order ASC ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[$rows['categories_id']] = ''; // 此值如果是tours就是连接到短期旅游，如果为空就到套餐列表
	}
	return $data;
}

/**
 * 更新某个订单的状态 此函数同时更新订单的状态和状态历史
 *
 * @param $orders_id 订单ID
 * @param $orders_status_id 状态ID
 * @param $updated_by 管理员ID号，如果是系统更新则应是96
 */
function tep_update_orders_status($orders_id, $orders_status_id, $updated_by = CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID, $comments = '', $notifyCustomer = '0') {
	$date = date('Y-m-d H:i:s');
	$data_array = array (
			'orders_id' => (int) $orders_id,
			'orders_status_id' => (int) $orders_status_id,
			'date_added' => $date,
			'customer_notified' => (int) $notifyCustomer,
			'comments' => tep_db_prepare_input($comments),
			'updated_by' => (int) $updated_by 
	);
	tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $data_array);
	$orders_status_history_id = tep_db_insert_id();
	if ((int) $orders_status_history_id) {
		$_ex = '';
		if ($updated_by == CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID) {
			$_ex = ', next_admin_id=0, need_next_admin=0, need_next_urgency="" ';
		}
		tep_db_query('update ' . TABLE_ORDERS . ' set orders_status="' . (int) $orders_status_id . '", admin_id_orders="' . (int) $updated_by . '", last_modified="' . $date . '" ' . $_ex . ' where orders_id="' . (int) $orders_id . '" ');
	}
}

/**
 * 根据供应商更新的状态来取得对应的订单状态
 *
 * @param unknown_type $provider_order_status_id
 */
function tep_get_orders_status_id_form_provider_order_status($provider_order_status_id) {
	$data = false;
	$sql = tep_db_query('SELECT orders_status_id FROM `provider_order_products_status` WHERE provider_order_status_id="' . (int) $provider_order_status_id . '" and provider_order_status_for="1" ');
	$row = tep_db_fetch_array($sql);
	if ((int) $row['orders_status_id']) {
		$data = (int) $row['orders_status_id'];
	}
	return $data;
}

/**
 * 取得我们与供应商的订单行程交流历史信息，并自动分组
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_provider_order_products_status_history($orders_products_id) {
	$qry_order_prod_history = "select h.popc_id, h.provider_comment, h.provider_status_update_date, h.popc_user_type, s.provider_order_status_name, op.products_id,
							op.products_model, if(h.popc_user_type=0, h.popc_updated_by, p.agency_id) as updated_by, popc_updated_by, p.agency_id
							FROM " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY . " h, " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " s, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p
							WHERE
							h.provider_order_status_id=s.provider_order_status_id AND
							h.orders_products_id=op.orders_products_id AND
							op.products_id=p.products_id AND
							h.notify_usi4trip=1 AND
							h.orders_products_id='" . (int) $orders_products_id . "' ORDER BY p.products_id ASC, h.popc_id ASC";
	
	$res_order_prod_history = tep_db_query($qry_order_prod_history);
	$agency_prod_historys = false;
	$data = false;
	while ($rows = tep_db_fetch_array($res_order_prod_history)) {
		$agency_prod_historys[] = $rows;
	}
	// 按左边我们，右边地接的一一对应形式填充数据
	if ($agency_prod_historys) {
		for ($i = 0; $i < sizeof($agency_prod_historys); $i ++) {
			$data[][$agency_prod_historys[$i]['popc_user_type']] = $agency_prod_historys[$i];
			
			if (!tep_not_null($data['heardTitie'][0]))
				$data['heardTitie'][0] = STORE_NAME; // 列标题
			if (!tep_not_null($data['heardTitie'][1]))
				$data['heardTitie'][1] = tep_get_providers_agency($agency_prod_historys[$i]['agency_id'], 1);
		}
	}
	return $data;
}

/**
 * 取得产品满意度信息,返回array
 *
 * @param unknown_type $products_id
 */
function tep_get_products_rating($products_id) {
	$data = false;
	$ratings_count_query = tep_db_query("select count(*) as total, AVG(rating_total) as rating_total_avg, AVG(booking_rating) as booking_rating_avg, AVG(travel_rating) as travel_rating_avg from " . TABLE_REVIEWS . " where products_id = '" . (int) $products_id . "' and reviews_status='1' and parent_reviews_id='0' ");
	$ratings_count = tep_db_fetch_array($ratings_count_query);
	$data = $ratings_count;
	return $data;
}

/**
 * 格式化输出房间信息字符串，把每个房间的信息显示在一行 格式化前 => 总房间数：2 房间一成人数： 3 房间一总费用： $1494 房间二成人数： 2
 * 房间二儿童数： 2 房间二总费用： $1792 格式化后 ==> 总房间数：2 房间一成人数： 3	房间一总费用： $1494 房间二成人数：
 * 2	房间二儿童数： 2	房间二总费用： $1792
 *
 * @param string $string
 * @param int $display_roon 1代表有房间，0代表无房间
 * @return string
 * @author Howard
 */
function format_out_roomattributes_1($string, $display_room = '0') {
	// return $string;
	$string = strip_tags($string);
	$string = preg_replace('/&#(\d+);/e', 'num2NUM("＆＃$1；")', $string);
	$string = str_replace(array (
			'#',
			'&nbsp;' 
	), '', $string);
	// $string = str_replace(' : ','：',$string);
	$string = preg_replace('/(\d+\.*\d*)/', '$1<::>', $string);
	$string = preg_replace('/[[:space:]]+/', '', $string);
	if ((int) $display_room) { // 格式化有房间数的数据
		$_array = explode('<::>', $string);
		$string = '';
		foreach ($_array as $key => $val) {
			$br = '&nbsp;&nbsp;&nbsp;&nbsp;';
			if (tep_not_null(trim($val))) {
				if ($key == 0 || preg_match('/' . db_to_html("总房间数") . '|' . db_to_html("总费用") . '|' . db_to_html("小计") . '|总房间数|总费用|小计/', $val))
					$br = '<br>';
				$string .= $val . $br;
			}
		}
		$string = trim($string);
		$string = preg_replace('/' . preg_quote('<br>') . '$/', '', $string);
	} else {
		$string = str_replace('<::>', ' ', $string);
	}
	$string = trim($string);
	$string = preg_replace('/＆＃([０１２３４５６７８９]+)；/e', 'NUM_num("&#$1;")', $string);
	$string = NUM_num($string);
	return $string;
}

/**
 * 添加航班信息更新历史记录
 */
function tep_add_orders_product_flight_history($data_array, $orders_products_id, $admin_id = 0, $customers_id = 0, $sent_confirm_email_to_provider_value = 0) {
	$will_add = true;
	$check_sql = tep_db_query('select * FROM orders_product_flight_history where orders_products_id ="' . (int) $orders_products_id . '" order by history_id DESC Limit 1 ');
	$check_row = tep_db_fetch_array($check_sql);
	if ((int) $check_row['history_id']) {
		if ($data_array['airline_name'] == $check_row['airline_name'] && $data_array['flight_no'] == $check_row['flight_no'] && $data_array['airline_name_departure'] == $check_row['airline_name_departure'] && $data_array['flight_no_departure'] == $check_row['flight_no_departure'] && $data_array['airport_name'] == $check_row['airport_name'] && $data_array['airport_name_departure'] == $check_row['airport_name_departure'] && $data_array['arrival_date'] == $check_row['arrival_date'] && $data_array['arrival_time'] == $check_row['arrival_time'] && $data_array['departure_date'] == $check_row['departure_date'] && $data_array['departure_time'] == $check_row['departure_time']) {
			$will_add = false;
		}
	}
	if ($will_add === true) {
		$sql_data_array = array (
				'orders_products_id' => (int) $orders_products_id,
				'airline_name' => $data_array['airline_name'],
				'flight_no' => $data_array['flight_no'],
				'airline_name_departure' => $data_array['airline_name_departure'],
				'flight_no_departure' => $data_array['flight_no_departure'],
				'airport_name' => $data_array['airport_name'],
				'airport_name_departure' => $data_array['airport_name_departure'],
				'arrival_date' => $data_array['arrival_date'],
				'arrival_time' => $data_array['arrival_time'],
				'departure_date' => $data_array['departure_date'],
				'departure_time' => $data_array['departure_time'],
				'add_date' => date('Y-m-d H:i:s') 
		);
		if ((int) $admin_id)
			$sql_data_array['admin_id'] = (int) $admin_id;
		if ((int) $customers_id)
			$sql_data_array['customers_id'] = (int) $customers_id;
		tep_db_perform('orders_product_flight_history', $sql_data_array);
		
		tep_db_query('update ' . TABLE_ORDERS_PRODUCTS_FLIGHT . ' set sent_confirm_email_to_provider="' . (int) $sent_confirm_email_to_provider_value . '" where orders_products_id="' . (int) $orders_products_id . '" ');
	}
}

/**
 * 取得订单产品的结束日期
 *
 * @param unknown_type $products_id
 * @param unknown_type $products_departure_date 出发日期
 */
function tep_get_products_end_date($products_id, $products_departure_date) {
	$departure_date = date('Y-m-d H:i:s', strtotime($products_departure_date));
	$end_date_time = strtotime($departure_date);
	$sql = tep_db_query('SELECT products_durations, products_durations_type FROM `products` WHERE products_id = "' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	// products_durations_type 0为天,1为小时,2为分钟
	if (!(int) $row['products_durations_type'] && (int) $row['products_durations']) {
		// $end_date_time = strtotime($departure_date) +
		// ((int)$row['products_durations'] + 1) *24*3600;
		// $end_date_time = strtotime($departure_date) +
		// (int)$row['products_durations']*24*3600;
		// $end_date_time = strtotime($departure_date) +
		// ((int)$row['products_durations'] - 1) *24*3600;
		// //如果是一天团，结束日期是当天，两天团，结束日期是第二天。所以减1
		$end_date_time = strtotime($departure_date . ' +' . max(0, (intval($row['products_durations']) - 1)) . 'days');
	}
	$end_date = date('Y-m-d H:i:s', $end_date_time);
	return $end_date;
}

/**
 * 取得产品的出发城市
 *
 * @param unknown_type $products_id
 * @param unknown_type $type 如果类型不是str的话则返回id串
 */
function tep_get_product_departure_city($products_id, $type = "str") {
	$data = false;
	$sql = tep_db_query('SELECT departure_city_id FROM `products` where products_id="' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	if ($type != "str") {
		$data = $row['departure_city_id'];
	} else {
		$array = tep_get_city_names($row['departure_city_id']);
		$data = implode(', ', $array);
	}
	return $data;
}

/**
 * 取得产品对应的目的景点或城市ID
 *
 * @param unknown_type $products_id
 * @return unknown
 */
function tep_get_product_destination_city_ids($products_id) {
	$data = false;
	$sql = tep_db_query('SELECT city_id FROM ' . TABLE_PRODUCTS_DESTINATION . ' where products_id="' . (int) $products_id . '" ');
	while ($row = tep_db_fetch_array($sql)) {
		if ((int) $row['city_id']) {
			$data[] = $row['city_id'];
		}
	}
	return $data;
}

/**
 * 过滤电子参团凭证客人的中文名字 格式：中文名 [zhou haohua]
 *
 * @param unknown_type $customers_name 中文名 [zhou haohua]
 */
function tep_filter_guest_chinese_name($customers_name = '') {
	$customers_name = trim($customers_name);
	if (preg_match('/\[(.+)\]/', $customers_name, $m)) {
		$customers_name = $m[1];
	}
	return $customers_name;
}

/**
 * 添加上车地址更新历史记录
 *
 * @param unknown_type $orders_products_id
 * @param unknown_type $departure_location
 *        	注意：这个地址是不需要经过tep_db_prepare_input和tep_db_input转码的
 * @param unknown_type $updated_by
 */
function tep_add_departure_location_history($orders_products_id, $departure_location, $updated_by = 96) {
	$old_location = tep_db_query('SELECT history_id, departure_location FROM `orders_products_departure_location_history` WHERE orders_products_id="' . (int) $orders_products_id . '" order By history_id DESC Limit 1');
	$old_location = tep_db_fetch_array($old_location);
	if (!(int) $old_location['history_id'] || trim($departure_location) != trim($old_location['departure_location'])) {
		tep_db_query('INSERT INTO `orders_products_departure_location_history` set orders_products_id="' . (int) $orders_products_id . '", departure_location="' . tep_db_prepare_input(tep_db_input($departure_location)) . '", added_time="' . date('Y-m-d H:i:s') . '", updated_by="' . $updated_by . '", has_confirmed="' . (($updated_by == 96) ? '1' : '0') . '", confirmed_admin_id="' . (($updated_by == 96) ? $updated_by : '0') . '" ');
	}
}

/**
 * 取得上车地址的历史记录
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_departure_location_history($orders_products_id) {
	$data = false;
	$sql = tep_db_query('SELECT * FROM `orders_products_departure_location_history` WHERE orders_products_id = "' . (int) $orders_products_id . '" order by history_id DESC ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows;
	}
	return $data;
}

/**
 * 取得通过特价和团购检查后的最终标准价，返回数组
 *
 * @param $products_price_array 产品原价格数组
 * @param $is_new_group_buy 是否是新团购
 */
function tep_get_final_price_from_specials($products_id, &$products_price_array, &$is_new_group_buy) {
	$is_new_group_buy = 0;
	$group_buy_specials_sql = tep_db_query('SELECT * FROM `specials` WHERE products_id ="' . (int) $products_id . '" AND status="1" AND start_date <="' . date('Y-m-d H:i:s') . '" AND expires_date >"' . date('Y-m-d H:i:s') . '" ');
	$group_buy_specials = tep_db_fetch_array($group_buy_specials_sql);
	if ((int) $group_buy_specials['products_id']) {
		if ($group_buy_specials['specials_type'] == "1") {
			$tmpPurchasedNum = tep_get_product_orders_guest_count($group_buy_specials['products_id'], $group_buy_specials['start_date'], $group_buy_specials['expires_date']);
			$tmpBalanceNum = max(0, (int) ($group_buy_specials['specials_max_buy_num'] - $tmpPurchasedNum));
		}
		// 0为普通特价，1为限量团购，2为限时团购
		if (($group_buy_specials['specials_type'] == "1" && (int) $tmpBalanceNum) || $group_buy_specials['specials_type'] == "2" || $group_buy_specials['specials_type'] == "0") {
			if ($group_buy_specials['specials_type'] == "1" || $group_buy_specials['specials_type'] == "2") {
				$is_new_group_buy = 1;
			}
			if ((int) $group_buy_specials['specials_new_products_single'])
				$products_price_array['products_single'] = $group_buy_specials['specials_new_products_single'];
			if ((int) $group_buy_specials['specials_new_products_single_pu'])
				$products_price_array['products_single_pu'] = $group_buy_specials['specials_new_products_single_pu'];
			if ((int) $group_buy_specials['specials_new_products_double'])
				$products_price_array['products_double'] = $group_buy_specials['specials_new_products_double'];
			if ((int) $group_buy_specials['specials_new_products_triple'])
				$products_price_array['products_triple'] = $group_buy_specials['specials_new_products_triple'];
			if ((int) $group_buy_specials['specials_new_products_quadr'])
				$products_price_array['products_quadr'] = $group_buy_specials['specials_new_products_quadr'];
			if ((int) $group_buy_specials['specials_new_products_kids'])
				$products_price_array['products_kids'] = $group_buy_specials['specials_new_products_kids'];
		}
	}
}

/**
 * 下单时,如果有带销售链接a的,则自动将该订单的资料写入销售跟踪
 *
 * @param $orders_id订单号
 */
function tep_add_salestrack_when_checkout($orders_id) {
	$data = false;
	$sql = 'SELECT 0 AS is_important,date_purchased AS add_date,customers_name AS customer_name,customers_email_address AS customer_email, orders_owner_admin_id AS login_id FROM orders WHERE orders_id=' . $orders_id . ' AND orders_owner_admin_id > 0';
	$sql_query = tep_db_query($sql);
	while ($rows = tep_db_fetch_array($sql_query)) {
		$data = $rows;
	}
	if (is_array($data)) {
		$data['order_id'] = $orders_id;
		$data['customer_info'] = 'auto add when checkout';
		$id = tep_db_fast_insert('salestrack', $data);
		$data_email = false;
		$data_email['salestrack_id'] = $id;
		$data_email['email'] = $data['customer_email'];
		$data_email['add_date'] = $data['add_date'];
		$data_email['login_id'] = $data['login_id'];
		tep_db_fast_insert('salestrack_email_history', $data_email);
		$data_code = false;
		$data_code['salestrack_id'] = $id;
		$data_code['add_date'] = $data['add_date'];
		$data_code['login_id'] = $data['login_id'];
		$sql2 = 'SELECT products_model FROM orders_products WHERE orders_id=' . $orders_id;
		$sql2_query = tep_db_query($sql2);
		$my_code = '';
		while ($rows2 = tep_db_fetch_array($sql2_query)) {
			$data_code['code'] = $rows2['products_model'];
			tep_db_fast_insert('salestrack_code_history', $data_code);
			$my_code .= ',' . $rows2['products_model'];
		}
		tep_db_query('update salestrack set code="' . substr($my_code, 1) . '" where salestrack_id=' . $id);
	}
}

/**
 * check_proceess页面用到的添加销售跟踪的
 *
 * @param unknown_type $orders_id
 * @param unknown_type $login_id
 */
function tep_add_salestrack_when_checkout_at_check_proceess($orders_id, $login_id = false) {
	add_auto_history($orders_id, $login_id); //添加一个记录，看到底执行了什么。
	$sql_insert = '';
	if (!$login_id || $login_id == 19)
		return;
	$data = false;
	$sql_insert .= $sql = 'SELECT 0 AS is_important,date_purchased AS add_date,customers_name AS customer_name,customers_email_address AS customer_email, orders_owner_admin_id AS login_id FROM orders WHERE orders_id=' . $orders_id;
	$sql_query = tep_db_query($sql);
	$data = tep_db_fetch_array($sql_query);
	if (is_array($data)) {
		$data['orders_id'] = $orders_id;
		$data['customer_info'] = 'auto add when checkout33';
		$data['login_id'] = $login_id;
		$id = tep_db_fast_insert('salestrack', $data);
		$data_email = false;
		$data_email['salestrack_id'] = $id;
		$data_email['email'] = $data['customer_email'];
		$data_email['add_date'] = $data['add_date'];
		$data_email['login_id'] = $login_id;
		tep_db_fast_insert('salestrack_email_history', $data_email);
		
		$data_code = false;
		$data_code['salestrack_id'] = $id;
		$data_code['add_date'] = $data['add_date'];
		$data_code['login_id'] = $login_id;
		$sql_insert .= $sql2 = 'SELECT products_model FROM orders_products WHERE orders_id=' . $orders_id;
		$sql2_query = tep_db_query($sql2);
		$my_code = '';
		while ($rows2 = tep_db_fetch_array($sql2_query)) {
			$data_code['code'] = $rows2['products_model'];
			tep_db_fast_insert('salestrack_code_history', $data_code);
			$my_code .= ',' . $rows2['products_model'];
		}
		$sql_insert .= $str_sql = 'update salestrack set code="' . substr($my_code, 1) . '" where salestrack_id=' . $id;
		tep_db_query($str_sql);
		add_auto_sql_history(addslashes($sql_insert), $orders_id);
	}
}

/**
 * 添加销售跟踪的一个历史，证明执行了插入操作，用于判断哪个字段没有传入。
 * @param int $orders_id 订单ID
 * @param int $login_id 登录ID
 */
function add_auto_history($orders_id, $login_id) {
	$str_sql = "insert into salestrack_auto_history set login_id=$login_id,orders_id=$orders_id,insert_time='" . date('Y-m-d H:i:s') . "'";
	tep_db_query($str_sql);
}

/**
 * 添加销售跟踪自动生成的时候，执行SQL的详细情况
 * @param string $sql 用于保存的SQL语句
 * @param int $orders_id 订单ID
 */
function add_auto_sql_history($sql, $orders_id) {
	$str_sql = "insert into salestrack_auto_sql_histoty set query_sql='$sql',orders_id=$orders_id,insert_time='" . date('Y-m-d H:i:s') . "'";
	tep_db_query($str_sql);
}

/**
 * 判断是否可以显示客人信息，用于供应商后台
 *
 * @param $orders_products_id
 */
function tep_can_show_guest_info($orders_products_id, $histories_action = '') {
	$can_show_guest_info = true;
	if ($histories_action == '') {
		$histories_action = show_histories_action($orders_products_id);
	}
	$_tmp_sql = tep_db_query('SELECT has_confirmed FROM `order_products_departure_guest_histoty` where op_order_products_ids="' . $orders_products_id . '" Order BY op_histoty_id DESC Limit 1');
	$_tmp_row = tep_db_fetch_array($_tmp_sql);
	if (tep_not_null($histories_action) && $_tmp_row['has_confirmed'] != "1") { // 如果最新的客人信息历史记录未被主管OP审核也不能显示客人信息
		$can_show_guest_info = false;
	}
	return $can_show_guest_info;
}

/**
 * 取得订单产品字符串（用于供应商系统） 如果是酒店的话就在订单号中添加-H
 *
 * @param int $orders_id
 * @param int $is_hotel
 */
function tep_get_products_orders_id_str($orders_id, $is_hotel) {
	if ($is_hotel == "1")
		return $orders_id . '-H';
	return $orders_id;
}

/**
 * 网站前台导航栏一级标题产品排序
 *
 * @param string $cPath
 * @param string $cat_mnu_sel 当前列表Tag字符，vcpackages=度假休闲游、tours=周边热点游
 */
function tep_get_products_list_default_order_by($cPath, $cat_mnu_sel) {
	$dataOrder = '';
	if (in_array($cPath, array (
			24,
			25,
			33,
			54,
			157 
	))) {
		$order_by_array = array (
				'24' => array (
						'vcpackages' => array (
								3045,3063,3064,2976,3046,3050,3038,3049,3051,2982,3070,3071,2977,2989,3072,2990,3080
						),
						'tours' => array (
								564,576,577,575,3014,585,584,3029,3030,3031,3034,3024,3027,15,69,13,43,1650,3017,3020,3022,3026,3028,3032,3033,3035,2727,2396,16,19,506,578,574,586,566,587
						) 
				),
				'25' => array (
						'vcpackages' => array (32,137,322,349,328,1552,329,323,282,1555),
						'tours' => array (
								714,
								26,
								28,
								1465,
								31,
								86,
								1891,
								301,
								303,
								302,
								333,
								341,
								2093,
								2089,
								2890,
								2090,
								2891,
								2092,
								2091,
								2086,
								2892,
								2088,
								2889,
								2888 
					)		 
				), 
				'33' => array (
						'vcpackages' => array (
								2771,
								2783,
								2769,
								2784,
								2770,
								2785,
								573,
								2786,
								572,
								2787,
								571,
								2788,
								570,
								2789 
						),
						'tours' => array (
								2772,
								2782,
								2781,
								2780,
								2779,
								2778,
								2777,
								2776,
								2775,
								2774 
						) 
				),
				'54' => array (
						'vcpackages' => array (
								2901,
								2902,
								2903,
								2904,
								2905,
								2906,
								2907,
								2908,
								2911,
								2912,
								2909,
								2910,
								2946,
								2947,
								2948 
						),
						'tours' => array (
								2899,
								2900,
								2944,
								2945 
						) 
				),
				'157' => array (
						'vcpackages' => array (
								"100",
								"103",
								"104",
								"105",
								"107",
								"108",
								"109",
								"110",
								"111",
								"112",
								"199",
								"201",
								"203",
								"703",
								"704",
								"705",
								"706",
								"707",
								"708",
								"709",
								"710" 
						),
						'tours' => null 
				) 
		);
		if (tep_not_null($order_by_array[$cPath][$cat_mnu_sel])) {
			krsort($order_by_array[$cPath][$cat_mnu_sel], SORT_NUMERIC);
			$dataOrder = ' ORDER BY p.products_stock_status DESC, find_in_set(p.products_id, "' . implode(',', $order_by_array[$cPath][$cat_mnu_sel]) . '")  DESC ';
		}
	}
	return $dataOrder;
}

/**
 * 根据不同的出发城市和目的城市取得产品列表的排序SQL语句
 *
 * @param int $fc 出发城市id
 * @param int $vc 目的城市id
 */
function tep_get_products_list_default_order_by_from_city($fc = 0, $vc = 0) {
	$dataOrder = '';
	// 已选出发城市（洛杉矶、旧金山、拉斯、盐湖城、丹佛）到“黄石公园”的排序
	if ($vc == 9 && in_array($fc, array (
			1,
			2,
			3,
			10,
			37 
	))) {
		$order_by_array = array (
				'1' => array (
						2975,2974,2973,2972,2981,2982,2977,2978,174,180,608,603,178,1585,607,1587,620,622,623,2801,2799,2800,2802,2803,2804,2805,2806,601,1606,351,1608,228,1609,1626,596,230,592,1611,354,1636,1599,589,1597,348,1594,357,360,599,1926
						),
				'2' => array (
						2991,2990,2989,2987,2988,2983,2984,2985,2986,1593,1639,1592,1589,1590,1591,626,197,194,1560,193,198,1558,1559,2798,2797,2898
				),
				'3' => array (
						2969,2968,2967,2976,2979,2980,2970,2971,175,616,628,604,179,1586,606,1588,619,621,624,600,1600,595,1607,597,1610,1798,594,593,1796,1797,358,591,1598,350,1596,588,1595,359,368,609,610
						),
				'10' => array (
						2993,2992,173,316,712,1561,356,355,353,1615
				),
				'37' => array (
						1575,1364,617,618,334,1614,229,1604
				) 
		);
		// 如果出发城市是纽约、洛杉矶、拉斯维加斯、旧金山则按以下顺序排序
	} elseif (in_array($fc, array (
			66,
			1,
			3,
			2 
	))) {
		$order_by_array = array (
				'66' => array (
						"32",
						"137",
						"140",
						"346",
						"166",
						"319",
						"349",
						"322",
						"352",
						"347",
						"325",
						"148",
						"328",
						"246",
						"331" 
				),
				'1' => array (
						3045,3060,3064,3050,3061,3051,3070,3076,3077,3071,3078,3072,3079,3080,2115,2113,934,2127,935,516,2117,967,2098,2119,2130,2131
				),
				'3' => array (
						2976,3047,3049,2970,2151,2143,2156,2306,2289,2319
				),
				'2' => array (
						3046,3048,2990,2168,2185,2373,2380,652,2357,2342,2377,649,2357,2337,2353
				) 
		);
		// 只选目的地的排序（纽约、黄石公园、洛杉矶、拉斯维加斯、旧金山）
	} elseif ((int) $vc > 0) {
		$order_by_array = array (
				'66' => array (
						"32",
						"137",
						"140",
						"352",
						"166",
						"1047",
						"36",
						"35",
						"33",
						"155",
						"319",
						"347",
						"325",
						"328",
						"214",
						"133",
						"378",
						"153",
						"147",
						"156" 
				),
				'9' => array (
						"180",
						"608",
						"1875",
						"1586",
						"604",
						"1874",
						"1589",
						"1878",
						"316",
						"1854",
						"603",
						"178",
						"1873",
						"606",
						"175",
						"1876",
						"1590",
						"1593",
						"626",
						"173",
						"1862" 
				),
				'1' => array (
						3045,3060,3064,3050,3061,3051,3070,3076,3077,3071,3078,3072,3079,3080,2115,2113,934,2127,935,516,2117,967,2098,2119,2130,2131
				),
				'3' => array (
						2976,3047,3049,2970,2151,2143,2156,2306,2289,2319
				),
				'2' => array (
						3046,3048,2990,2168,2185,2373,2380,652,2357,2342,2377,649,2357,2337,2353
				) 
		);
	}
	
	if (is_array($order_by_array[$fc])) {
		krsort($order_by_array[$fc], SORT_NUMERIC);
		$dataOrder = ' ORDER BY p.products_stock_status DESC, find_in_set(p.products_id, "' . implode(',', $order_by_array[$fc]) . '")  DESC ';
	} elseif (is_array($order_by_array[$vc])) {
		krsort($order_by_array[$vc], SORT_NUMERIC);
		$dataOrder = ' ORDER BY p.products_stock_status DESC, find_in_set(p.products_id, "' . implode(',', $order_by_array[$vc]) . '")  DESC ';
	}
	return $dataOrder;
}

/**
 * 在已载入的网页内容中获取我们需要的那些内容
 *
 * @param string $htmlContent 源内容
 * @param string $charset 源内容编码 如utf-8
 * @param string $tagName 目标的标签
 * @param string $attrName 目标的属性 如id、class等
 * @param string $attrValue目标的属性的值
 * @param $filterTagName排除一些标签(支持数组)
 * @param $filterAttrName被排除的那些标签的属性(支持数组)
 * @param $filterAttrValue被排除的那些标签的属性值(支持数组)
 * @param $getRange 如果出现重复的标签内容是否抓取所有内容，如果该为0则抓取全部，默认为0；否则根据$getRange的值来抓取，格式如1,3,5-9,12等
 */
function getHtmlTagsContent($htmlContent, $charset, $tagName, $attrName = "", $attrValue = "", $filterTagName = "", $filterAttrName = "", $filterAttrValue = "", $getRange = 0) {
	$html = '';
	// $dom = new DOMDocument("1.0","utf-8");
	// $dom->preserveWhiteSpace = false;
	// @$dom->loadHTMLFile($fileURL);
	

	$dom = new DOMDocument();
	$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', $charset));
	
	$domxpath = new DOMXPath($dom);
	$newDom = new DOMDocument();
	$newDom->formatOutput = true;
	if ($attrName != "") {
		$filtered = $domxpath->query("//" . $tagName . '[@' . $attrName . "='" . $attrValue . "']");
	} else {
		$filtered = $domxpath->query("//" . $tagName);
	}
	
	// $filtered = $domxpath->query('//div[@class="className"]');
	// '//' when you don't know 'absolute' path
	

	// since above returns DomNodeList Object
	// I use following routine to convert it to string(html); copied it from
	// someone's post in this site. Thank you.
	

	$ns = array ();
	if ($getRange != 0 && $getRange != "") {
		$tmpArray = explode(',', $getRange);
		foreach ($tmpArray as $key => $val) {
			$val = trim($val);
			if (preg_match('/^\d+$/', $val)) {
				$ns[] = ($val - 1);
			} else {
				$nArray = explode('-', $val);
				$st = min($nArray[0], $nArray[1]);
				$et = max($nArray[0], $nArray[1]);
				for ($j = $st; $j <= $et; $j ++) {
					$ns[] = ($j - 1);
				}
			}
		}
	} else {
		$ns[0] = 0;
	}
	
	$i = 0;
	while ($myItem = $filtered->item($i ++)) {
		$_i = $i - 1;
		if (!in_array($_i, $ns) && $getRange != "0" && $getRange != "") {
			continue;
		}
		
		$node = $newDom->importNode($myItem, true); // import node
		$newDom->appendChild($node); // append node
	}
	$html = $newDom->saveHTML();
	// 过滤部分元素(支持字符及数组)
	if ($filterTagName != "") {
		if (is_array($filterTagName)) {
			$filterTagNames = $filterTagName;
			$filterAttrNames = $filterAttrName;
			$filterAttrValues = $filterAttrValue;
		} else {
			$filterTagNames[0] = $filterTagName;
			$filterAttrNames[0] = $filterAttrName;
			$filterAttrValues[0] = $filterAttrValue;
		}
		for ($i = 0, $n = sizeof($filterTagNames); $i < $n; $i ++) {
			$fDom = new DOMDocument();
			@$fDom->loadHTML($html);
			$root = $fDom->documentElement;
			if (is_object($root)) {
				foreach ($root->getElementsByTagName($filterTagNames[$i]) as $elem) {
					if ($filterAttrNames[$i] == "" || $elem->getAttribute($filterAttrNames[$i]) == $filterAttrValues[$i]) {
						$elem->parentNode->removeChild($elem);
						$i --;
						// echo
						// $filterTagName.":".$filterAttrName.":".$_AttrVal.":".$filterAttrValue."<hr
						// />";
					}
				}
			}
			
			$html = $fDom->saveHTML();
		}
	}
	
	$html = mb_convert_encoding($html, 'utf-8', 'HTML-ENTITIES');
	if (strtolower($charset) != "utf-8" && $charset != "") {
		$html = iconv('utf-8', $charset . '//IGNORE', $html);
	}
	
	return $html;
}

/**
 * 根据订单产品id号、订单号和供应商id号返回用于供应商页面显示的订单号（不包括酒店和组合团）。
 * 源需求：当一个订单号中有一个以上的行程，并且是同一个地接商的行程时，给每一个行程分配后标如：
 * 50772中产品1旁边添加50772-A。并且每个行程单独下给地接商时，在地接商系统中显示的订单为50772-A,50772-B等，而不是之前的纯订单号。
 * 实现方法：从产品订单id中找到供应商，然后计算此供应商在该订单中有几个产品
 *
 * @param int $orders_products_id 订单产品快照id
 * @param int $orders_id 订单id
 * @param int $agency_id 供应商id
 * @return string;
 */
function getFormatOrdersIdTagToAgency($orders_products_id, $orders_id, $agency_id) {
	$tag_str = '';
	$array = array ();
	$array[0] = '-A';
	$array[1] = '-B';
	$array[2] = '-C';
	$array[3] = '-D';
	$array[4] = '-E';
	$array[5] = '-F';
	$array[6] = '-G'; // 由于-H被用作酒店的专门标识字符，所以普通产品最大的标识只能到G了。
	$sql = tep_db_query('SELECT op.orders_products_id FROM `orders_products` op, `products` p WHERE p.is_hotel="0" AND p.products_id = op.products_id AND p.products_model_sub="" AND op.orders_id="' . (int) $orders_id . '" and p.agency_id="' . (int) $agency_id . '" ORDER BY op.orders_products_id;');
	if (tep_db_num_rows($sql) > 1) {
		$n = 0;
		while ($rows = tep_db_fetch_array($sql)) {
			if ((int) $rows['orders_products_id'] && $orders_products_id == $rows['orders_products_id']) {
				$tag_str = $array[$n];
				break;
			}
			$n ++;
		}
	}
	return $tag_str;
}

// howard added for 单人配房标记
function get_single_pu_tags($order_id, $pord_id) {
	$orders_eticket_query = tep_db_query("select guest_name, agree_single_occupancy_pair_up from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_id = '" . (int) $order_id . "' and products_id=" . (int) $pord_id . " ");
	$result = tep_db_fetch_array($orders_eticket_query);
	
	$gender_tags = '';
	if ($result['agree_single_occupancy_pair_up'] == "1") {
		$gender_tags = '系统不知客人性别';
	}
	
	if (tep_not_null($result['guest_name'])) {
		$guestnames = explode('<::>', $result['guest_name']);
		foreach ((array) $guestnames as $key => $val) {
			if (preg_match('/^.*\((f|m)\)$/', $val)) {
				$gender_tags = str_replace('(f)', '(Female)', $val);
				$gender_tags = str_replace('(m)', '(Male)', $val);
				break;
			}
		}
		// return $result['guest_name'];
	}
	return $gender_tags;
}

/**
 * 添加或删除已下单地接未回复的记录到数据表
 *
 * @param int $orders_products_id 订单快照id
 * @param int $login_id 管理员id
 * @param int $action 动作'add'为添加,'sub'为删除
 */
function tep_add_or_sub_sent_provider_not_re_rows($orders_products_id, $login_id, $action = 'add') {
	$orders_products_id = (int) $orders_products_id;
	if ($action == 'add') {
		if (!(int) tep_db_get_field_value('orders_products_id', 'orders_products_sent_provider_not_re', ' orders_products_id="' . $orders_products_id . '" ')) {
			tep_db_query('INSERT INTO `orders_products_sent_provider_not_re` SET orders_products_id="' . $orders_products_id . '", admin_id="' . (int) $login_id . '", added_date="' . date('Y-m-d H:i:s') . '" ');
		}
	} elseif ($action == 'sub') {
		tep_db_query('DELETE FROM `orders_products_sent_provider_not_re` WHERE `orders_products_id` = "' . $orders_products_id . '" ');
	}
}

/**
 * 取得订单的出团日期
 *
 * @param int $order_id 订单id
 */
function tep_get_date_of_departure($order_id) {
	$return_ad_name = '';
	$date_of_departure_status_query = tep_db_query("select products_departure_date,products_departure_time  from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int) $order_id . "' order by products_departure_date desc");
	
	while ($date_of_departure = tep_db_fetch_array($date_of_departure_status_query)) {
		$return_ad_name .= tep_date_short($date_of_departure['products_departure_date']) . '<br> ';
	}
	return $return_ad_name;
}

/**
 * 更新订单的广告跟踪ID地址 注意：未付任何款项的才更新
 * @param int $order_id 订单id
 * @param int $ad_click_id 广告id地址
 */
function tep_update_orders_ad_click_id($order_id, $ad_click_id) {
	if (!(int) $order_id || !(int) $ad_click_id)
		return false;
	$customers_advertiser = tep_db_get_field_value('customers_advertiser', 'ad_source_clicks_stores', 'clicks_id="' . (int) $ad_click_id . '" ');
	tep_db_query('update orders set customers_ad_click_id="' . (int) $ad_click_id . '", customers_advertiser="' . tep_db_prepare_input(tep_db_output($customers_advertiser)) . '" where orders_id="' . (int) $order_id . '" and customers_ad_click_id=0 and orders_paid < "1" ');
	return tep_db_affected_rows();
}

function tep_update_orders_from($order_id) {
	if (isset($_COOKIE['url_from']))
		tep_db_query('update orders set customers_advertiser="' . $_COOKIE['url_from'] . '" where orders_id=' . (int) $order_id . ' and customers_advertiser=""');
}

/**
 * 更新订单的销售联盟信息 注意：未付任何款项的才更新，自己也不能推荐自己
 * @param int $order_id 订单id
 * @param int $affiliate_id 销售联盟成员id
 */
function tep_update_orders_affiliate_info($order_id, $affiliate_id) {
	$order_id = (int)$order_id;
	if (!(int) $order_id || !(int) $affiliate_id || ($affiliate_id == $_SESSION['customer_id']))
		return false;
	$check = tep_db_get_field_value('affiliate_id', 'affiliate_sales ', 'affiliate_orders_id="' . (int)$order_id . '" ');
	$paid_check = tep_db_get_field_value('orders_paid', 'orders', 'orders_id="' . (int)$order_id . '" ');
	//$ad_check = tep_db_get_field_value('customers_ad_click_id', 'orders', 'orders_id="'.$order_id.'" ');
	if ((int)$check < 1 && (int)$paid_check < 1) { //未付任何款项的才更新
		$affiliate_percent = AFFILIATE_PERCENT; //佣金比例3%
		$affiliate_total = tep_db_get_field_value('value', 'orders_total', 'orders_id="' . $order_id . '" and class="ot_total" ');
		$affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
		$affiliate_clickthroughs_id = (int) $_SESSION['affiliate_clickthroughs_id']; //点击来源id，如果没有就为0
		$sql_data_array = array (
				'affiliate_id' => $affiliate_id,
				'affiliate_date' => date("Y-m-d H:i:s"),
				'affiliate_browser' => $_SERVER["HTTP_USER_AGENT"],
				'affiliate_ipaddress' => tep_get_ip_address(),
				'affiliate_value' => $affiliate_total,
				'affiliate_payment' => $affiliate_payment,
				'affiliate_orders_id' => $order_id,
				'affiliate_clickthroughs_id' => $affiliate_clickthroughs_id,
				'affiliate_percent' => $affiliate_percent,
				'affiliate_salesman' => $affiliate_id,
				'affiliate_isvalid' => 1,
				'affiliate_purchase_supplement' => 1 
		);
		tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
		tep_db_query('update orders set customers_advertiser="' . $affiliate_id . '" where orders_id="' . $order_id . '"; ');
	}
}

/**
 * 上传地接提供的发票文件
 * @param string $_files_name 文件域的名称
 * @param int $orders_id 订单号
 * @param int $products_id 产品id号
 * @param int $orders_products_id 订单产品id号
 * @param array $allow_type 允许上传的类型
 * @return true false
 */
function uploadInvoices($_files_name, $orders_id, $products_id, $orders_products_id, $allow_type = array('pdf','rar','zip')) {
	$save_path = DIR_FS_CATALOG . 'images/invoices/';
	$file_type = get_file_type($_FILES[$_files_name]['tmp_name']);
	
	if (in_array(strtolower($file_type), $allow_type)) {
		//允许用户文件，文件名格式invoice_订单号_产品id_时间.扩展名
		$ex_name = preg_replace('/.+\./', '', $_FILES[$_files_name]['name']);
		$save_file_name = 'invoice_' . $orders_id . '_' . $products_id . '_' . date('mdHis') . '.' . $ex_name;
		
		if (!move_uploaded_file($_FILES[$_files_name]["tmp_name"], $save_path . $save_file_name)) {
			echo "CANNOT MOVE {$_FILES[$_files_name]['name']}" . PHP_EOL;
			exit();
		}
		tep_db_query('update ' . TABLE_ORDERS_PRODUCTS . ' set customer_invoice_files=CONCAT("' . $save_file_name . '", ";", customer_invoice_files ) WHERE orders_products_id="' . $orders_products_id . '" ');
		return true;
	} else {
		//删除不适合的文件
		@unlink($_FILES[$_files_name]['tmp_name']);
	}
	return false;
}

?>