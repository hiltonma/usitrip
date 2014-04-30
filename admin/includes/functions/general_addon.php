<?php
/*
 * $Id: general_addon.php,v 1.160 2003/07/12 08:32:47 hpdl Exp $ osCommerce,
 * Open Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */

// BOF Sets the status of a testimonial
function tep_set_testimonials_status($testimonials_id, $status) {
	if ($status == '1') {
		return tep_db_query("update " . TABLE_TESTIMONIALS . " set status = '1' where testimonials_id = '" . $testimonials_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_TESTIMONIALS . " set status = '0' where testimonials_id = '" . $testimonials_id . "'");
	} else {
		return -1;
	}
}
//EOF customer testimonials


//SEOurls:
function seo_generate_urlname($name) {
	return strtolower(preg_replace('/\s+/', '-', preg_replace('/[^a-z 0-9\/-]/i', '', $name)));
}

//AMIT ADDED FOR IMPORT EXPORT 
function tep_get_agencyid_from_name($name) {
	$agency_query = tep_db_query("select agency_id  from " . TABLE_TRAVEL_AGENCY . " where agency_name = '" . tep_db_prepare_input($name) . "'");
	$agency = tep_db_fetch_array($agency_query);
	return $agency['agency_id'];
}

function tep_get_agencyname_from_id($agency_id) {
	$agency_query = tep_db_query("select agency_name  from " . TABLE_TRAVEL_AGENCY . " where agency_id = '" . (int) $agency_id . "'");
	$agency = tep_db_fetch_array($agency_query);
	return $agency['agency_name'];
}

function tep_get_all_agency() {
	$agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " where 1 order by agency_name ");
	$data = array ();
	$i = 1; //必须是1，不要修改为0
	while ($agency = tep_db_fetch_array($agency_query)) {
		$data[$i] = array (
				'id' => $agency['agency_id'],
				'text' => $agency['agency_name'] 
		);
		$i ++;
	}
	return $data;
}

function tep_get_regionid_from_name($name) {
	$region_query = tep_db_query("select regions_id  from " . TABLE_REGIONS_DESCRIPTION . " where regions_name = '" . tep_db_prepare_input($name) . "'");
	$region = tep_db_fetch_array($region_query);
	return $region['regions_id'];
}

function tep_get_departureid_from_name($name) {
	$departure_query = tep_db_query("select city_id  from " . TABLE_TOUR_CITY . " where city = '" . tep_db_prepare_input($name) . "'");
	$departure = tep_db_fetch_array($departure_query);
	return $departure['city_id'];
}

//amit added to get customers_email address start  
function tep_get_customers_email($cust_id) {
	$cust_query = "select customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cust_id) . "'";
	$res = tep_db_query($cust_query);
	if ($customers_id = tep_db_fetch_array($res)) {
		$customers_email_address = $customers_id['customers_email_address'];
	}
	return trim($customers_email_address);
}
//amit added to get customers_email address end


// howard added form email get customers_id
function form_email_get_customers_id($email_address) {
	if (!tep_not_null($email_address)) {
		return false;
	}
	$sql = tep_db_query('select customers_id from ' . TABLE_CUSTOMERS . ' where customers_email_address="' . tep_db_input($email_address) . '" limit 1 ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['customers_id'];
}
// howard added form email get customers_id end


//amit added for get customer details start
function tep_get_customers_info($cust_id) {
	$sql = "select a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_country_id, a.entry_zone_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_telephone, c.customers_fax, ci.customers_info_number_of_logons, ci.customers_info_date_account_created , ci. customers_info_date_of_last_logon, ci.customers_info_date_account_last_modified  from " . TABLE_CUSTOMERS . " as c, " . TABLE_ADDRESS_BOOK . " as a, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_default_address_id=a.address_book_id  and c.customers_id=a.customers_id  and ci.customers_info_id  = c.customers_id and c.customers_id = '" . tep_db_input($cust_id) . "'";
	if (($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
		return tep_db_fetch_array($rs);
	}
	return array ();
}
//amit added for get customer details end


//howard added for get customer all phone info
function tep_get_customers_phones($cust_id) {
	$sql = tep_db_query('SELECT confirmphone, customers_telephone, customers_mobile_phone, customers_cellphone FROM ' . TABLE_CUSTOMERS . ' WHERE customers_id="' . (int) $cust_id . '" ');
	$phone_string = '';
	$phone_array = array ();
	while ($rows = tep_db_fetch_array($sql)) {
		if (tep_not_null($rows['confirmphone'])) {
			$phone_array[] = $rows['confirmphone'];
		}
		if (tep_not_null($rows['customers_telephone'])) {
			$phone_array[] = $rows['customers_telephone'];
		}
		if (tep_not_null($rows['customers_mobile_phone'])) {
			$phone_array[] = $rows['customers_mobile_phone'];
		}
		if (tep_not_null($rows['customers_cellphone'])) {
			$phone_array[] = $rows['customers_cellphone'];
		}
	}
	if (tep_not_null($phone_array)) {
		$phone_string = implode(', ', array_unique($phone_array));
	}
	return $phone_string;
}

//select disting category for cpc avarage calculation end  root
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
			$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
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

/**
 * 检查指定的分类是否有子类别 cached modify by vincent 2011-4-22
 * @param unknown_type $category_id
 */
function tep_has_category_subcategories($category_id) {
	/*
	 * $child_category_query = tep_db_query("select count(*) as count from " .
	 * TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
	 * $child_category = tep_db_fetch_array($child_category_query); if
	 * ($child_category['count'] > 0) { return true; } else { return false; }
	 */
	return count(MCache::search_categories('parent_id', $category_id)) > 0 ? true : false;
}

function tep_has_category_subcategories_tow($category_id) {
	$child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int) $category_id . "'");
	$child_category = tep_db_fetch_array($child_category_query);
	if ($child_category['count'] > 0) {
		return true;
	} else {
		return false;
	}
}
//amit added to affilate cat link start
function tep_products_in_category($category_id, $include_inactive = false, $cPath) {
	if ($cPath == $category_id) {
		if ($include_inactive) {
			$products_query = tep_db_query("select pd.products_name as product, pd.products_id as product_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = p2c.products_id and p2c.categories_id = '" . $category_id . "' and p.products_id = pd.products_id order by pd.products_name");
		} else {
			$products_query = tep_db_query("select pd.products_name as product, pd.products_id as product_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $category_id . "'and p.products_id = pd.products_id order by pd.products_name");
		}
	}
	
	return $products_query;
}
//amit added to affilate cat link end
//amit added to affilate all products cat link start
function tep_products_in_category_all($category_id, $include_inactive = false) {
	if ($include_inactive) {
		$products_query = tep_db_query("select pd.products_name as product, pd.products_id as product_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = p2c.products_id and p2c.categories_id = '" . $category_id . "' and p.products_id = pd.products_id order by pd.products_name");
	} else {
		$products_query = tep_db_query("select pd.products_name as product, pd.products_id as product_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . $category_id . "'and p.products_id = pd.products_id order by pd.products_name");
	}
	
	return $products_query;
}
//amit added to affilate all products cat link end


//amit added for get order and product id start
function tep_get_ordersid_productsid_from_orderproducts($orders_products_id) {
	$sql = "select orders_id, products_id from " . TABLE_ORDERS_PRODUCTS . "  where orders_products_id = '" . tep_db_input($orders_products_id) . "'";
	if (($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
		return tep_db_fetch_array($rs);
	}
	return array ();
}
//amit added for get order and product id end 


//amit added to forward array value start
function field_forwarder() {
	global $_POST, $rEM979, $FFoutputType;
	$fieldForwarder = '';
	/* get the arguments passed */
	$argList = func_get_args();
	
	/* globalize any other set of instructions */
	if (count($argList)) {
		eval('global $' . $argList[count($argList) - 1] . ';');
	}
	
	/* set the default set of values to convert */
	if (count($argList) == 0) {
		/*
		 * if the function is initially passed without parameter we're looking
		 * in $_POST
		 */
		$argList[0] = '_POST';
		$startValue = $_POST;
		if (sizeof($startValue) == 0) {
			return false;
		}
	} elseif (count($argList) == 1) {
		eval('$rEM979["' . $argList[0] . '"] = $' . $argList[0] . ';');
		$argList[0] = 'rEM979';
		$startValue = $rEM979;
	} elseif (count($argList) == 2) {
		eval('$startValue = $' . $argList[1] . '["' . $argList[0] . '"];');
	} else {
		for ($e = count($argList) - 2; $e >= 0; $e --) {
			$intersperse .= '["' . $argList[$e] . '"]';
		}
		eval('$startValue = $' . $argList[count($argList) - 1] . $intersperse . ';');
	}
	
	foreach ($startValue as $n => $v) {
		if (is_array($v)) {
			/* call the function again */
			$shiftArguments = '';
			for ($w = 0; $w <= count($argList) - 1; $w ++) {
				$shiftArguments .= '"' . $argList[$w] . '", ';
			}
			$shiftArguments = substr($shiftArguments, 0, strlen($shiftArguments) - 2);
			
			eval('$fieldForwarder .= field_forwarder("' . $n . '"' . substr(',', 0, strlen($shiftArguments)) . ' ' . $shiftArguments . ');');
		} else {
			/* we have an root value finally */
			if (count($argList) == 1) {
				/* actual output */
				flush();
				if ($FFoutputType == 'print') {
					$fieldForwarder .= "\$$n = '$v';\n";
				} else {
					$fieldForwarder .= "<input type=\"hidden\" " . "name=\"$n\" value=\"" . stripslashes2($v) . "\">\n";
				}
			} elseif (count($argList) > 1) {
				$indexString = '';
				for ($g = count($argList) - 3; $g >= 0; $g --) {
					$indexString .= '[' . ((!is_numeric($argList[$g]) and $FFoutputType == 'print') ? "'" : '') . $argList[$g] . ((!is_numeric($argList[$g]) and $FFoutputType == 'print') ? "'" : '') . ']';
				}
				$indexString .= '[' . ((!is_numeric($n) and $FFoutputType == 'print') ? "'" : '') . $n . ((!is_numeric($n) and $FFoutputType == 'print') ? "'" : '') . ']';
				/* actual output */
				flush();
				if ($FFoutputType == 'print') {
					$fieldForwarder .= "\${$argList[count($argList)-2]}" . "$indexString = '$v';\n";
				} else {
					$fieldForwarder .= "<input type=\"hidden\" name=\"" . "{$argList[count($argList)-2]}" . "$indexString\" value=\"" . stripslashes2($v) . "\">\n";
				}
			}
		}
	}
	return $fieldForwarder;
}

//amit added to forward array value end


//amit added to fixed select option display
function tep_cfg_select_option_change_display($select_array, $key_value, $key = '') {
	$string = '';
	
	for ($i = 0, $n = sizeof($select_array); $i < $n; $i ++) {
		$name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');
		
		$string .= '<br><input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';
		
		if ($key_value == $select_array[$i])
			$string .= ' CHECKED';
		
		$string .= '> ';
		
		if ($select_array[$i] == '0') {
			$string .= 'False';
		} elseif ($select_array[$i] == '1') {
			$string .= 'True';
		} else {
			$string .= $select_array[$i];
		}
		;
	}
	
	return $string;
}

//find  question count  BOF
function tep_get_question_anwers_count($que_id) {
	$question_query = tep_db_query("select count(*) as count from " . TABLE_QUESTION_ANSWER . " where que_id = '" . (int) $que_id . "'");
	$question = tep_db_fetch_array($question_query);
	
	$totalcount = $question['count'];
	
	return (int) $totalcount;
}

function tep_get_products_catagory_id($products_id) {
	global $languages_id;
	
	$the_products_catagory_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'" . " order by products_id,categories_id");
	$the_products_catagory = tep_db_fetch_array($the_products_catagory_query);
	
	return $the_products_catagory['categories_id'];
}
//find question count EOF
/**
 * 获取产品所属的所有分类,返回所有所属类得编号 例如 array(11,24,889) added by vincent 2011.6.28
 * @param int $products_id
 * @return array $categories
 */
function tep_db_get_products_catagories($products_id) {
	global $languages_id;
	$products_id = intval($products_id);
	$the_products_catagory_query = tep_db_query("SELECT categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'" . " order by products_id,categories_id");
	$categories = array ();
	while ($the_products_catagory = tep_db_fetch_array($the_products_catagory_query))
		$categories[] = $the_products_catagory['categories_id'];
	return $categories;
}

//amit added to get order payment method start
function tep_get_order_payment_method_name($order_id) {
	$status_query = tep_db_query("select payment_method from " . TABLE_ORDERS . " where orders_id = '" . (int) $order_id . "'");
	$status = tep_db_fetch_array($status_query);
	
	return trim($status['payment_method']);
}
//amit added to get order paymetn method end
function tep_get_date_of_departure($order_id) {
	$return_ad_name = '';
	$date_of_departure_status_query = tep_db_query("select products_departure_date,products_departure_time  from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int) $order_id . "' order by products_departure_date desc");
	
	while ($date_of_departure = tep_db_fetch_array($date_of_departure_status_query)) {
		$return_ad_name .= tep_date_short($date_of_departure['products_departure_date']) . '<br>' . $date_of_departure['products_departure_time'] . '<br> ';
	}
	
	return $return_ad_name;
}

/**
 * 取得订单的客户付款日期数据(已经财务确认)
 * @param int $order_id
 */
function tep_get_date_of_paid($order_id, $format = 'Y-m-d H:i:s') {
	$data = array ();
	$sql = tep_db_query('SELECT add_date, checked_time FROM `orders_payment_history` where orders_id="' . (int) $order_id . '" and has_checked="1" and orders_value > 0.00 order by orders_payment_history_id ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = date($format, strtotime($rows['add_date']));
	}
	return $data;
}

function tep_get_tours_codes_from($order_id) {
	$return_ad_name_model = '';
	$products_model_status_query = tep_db_query("select op.products_model  from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p  where p.products_id=op.products_id and op.orders_id = '" . (int) $order_id . "'  order by op.products_departure_date desc");
	
	while ($products_model_name_row = tep_db_fetch_array($products_model_status_query)) {
		$return_ad_name_model .= $products_model_name_row['products_model'] . '<br />';
	}
	
	return $return_ad_name_model;
}

/**
 * 获取分类名称
 * @param unknown_type $who_am_i
 * @author vincent @modify by vincent @ 2011-4-22 下午04:46:35
 */
function tep_get_categories_name($who_am_i) {
	global $languages_id;
	
	$the_categories_name_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . $who_am_i . "' and language_id= '" . $languages_id . "'");
	
	$the_categories_name = tep_db_fetch_array($the_categories_name_query);
	
	//$the_categories_name = MCache::fetch_categories($who_am_i);
	

	return $the_categories_name['categories_name'];
}
//amit added to get product type from product id start
function tep_get_product_type_of_product_id($products_id) {
	$product_type_query = tep_db_query("select products_type from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
	$product_type_info = tep_db_fetch_array($product_type_query);
	//amit added for fixed allow guest weight for airplan(3) too start
	if ($product_type_info['products_type'] == 3) {
		$product_type_info['products_type'] = 2;
	}
	//amit added for fixed allow guest weight for airplan too end
	return (int) $product_type_info['products_type'];
}

//amit added to get product type from product id end
//amit added to get lead status name start
function tep_get_tour_leads_staus_name($leads_id) {
	$tour_leads_query = tep_db_query("select tour_leads_status_name from " . TABLE_TOUR_LEADS_STATUS . " where tour_leads_status_id = '" . $leads_id . "'");
	$tour_leads_info = tep_db_fetch_array($tour_leads_query);
	return $tour_leads_info['tour_leads_status_name'];
}
//amit added to get lead status name end
function tep_get_current_lead_status_from_id($leads_id) {
	$tour_leads_query = tep_db_query("select lead_status_id from " . TABLE_TOUR_LEADS_INFO . " where lead_id = '" . $leads_id . "'");
	$tour_leads_info = tep_db_fetch_array($tour_leads_query);
	return $tour_leads_info['lead_status_id'];
}

/*
 * function tep_get_current_lead_status_anwers_from_id($leads_id){
 * $tour_leads_query = tep_db_query("select lead_status_id from " .
 * TABLE_TOUR_LEADS_INFO_ANSWER . " where lead_ans_id = '" . $leads_id . "'");
 * $tour_leads_info= tep_db_fetch_array($tour_leads_query); return
 * $tour_leads_info['lead_status_id']; }
 */
//-----Amit added to get regular_irregular section detail Start
function regu_irregular_section_detail($ppproducts_id) {
	$product_query_sql = "SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int) $ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	$product_query = tep_db_query($product_query_sql);
	$regular_row_cnt = tep_db_num_rows($product_query);
	
	while ($product[] = tep_db_fetch_array($product_query)) {
	}
	
	return $product;
}

function regu_irregular_section_numrow($ppproducts_id) {
	$product_query_sql = "SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int) $ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	$product_query = tep_db_query($product_query_sql);
	$regular_row_cnt = tep_db_num_rows($product_query);
	
	return $regular_row_cnt;
}

//-----Amit added to get regular_irregular section detail End
function tep_get_irreg_products_duration_description($products_id, $operate_start_date, $operate_end_date) {
	$the_products_duration_description_query = tep_db_query("select products_durations_description  from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id='" . $products_id . "' and operate_start_date= '" . $operate_start_date . "' and operate_end_date= '" . $operate_end_date . "'");
	
	$the_products_duration_description = tep_db_fetch_array($the_products_duration_description_query);
	
	return $the_products_duration_description['products_durations_description'];
}

//amit added to check departure valida month start 
function check_valid_available_date($startDate, $checkDate, $endDate) {
	//echo $startDate = date('m-d',$startDate);	
	//echo $checkDate=date('m-d',$checkDate);
	//echo $endDate=date('m-d',$endDate);		
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
//amit added to check departure valida month end
function tep_count_modules($modules = '') {
	$count = 0;
	
	if (empty($modules))
		return $count;
	
	$modules_array = split(';', $modules);
	
	for ($i = 0, $n = sizeof($modules_array); $i < $n; $i ++) {
		$class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));
		
		if (is_object($GLOBALS[$class])) {
			if ($GLOBALS[$class]->enabled) {
				$count ++;
			}
		}
	}
	
	return $count;
}

function tep_count_payment_modules() {
	return tep_count_modules(MODULE_PAYMENT_INSTALLED);
}

function tep_get_language_stringmonth($i) {
	global $language;
	
	if ($language == 'schinese' || $language == 'tchinese') {
		//return $language.$strmonth;
		if ($i == "1") {
			return TEXT_HEADING_MONTH_1;
		} elseif ($i == "2") {
			return TEXT_HEADING_MONTH_2;
		} elseif ($i == "3") {
			return TEXT_HEADING_MONTH_3;
		} elseif ($i == "4") {
			return TEXT_HEADING_MONTH_4;
		} elseif ($i == "5") {
			return TEXT_HEADING_MONTH_5;
		} elseif ($i == "6") {
			return TEXT_HEADING_MONTH_6;
		} elseif ($i == "7") {
			return TEXT_HEADING_MONTH_7;
		} elseif ($i == "8") {
			return TEXT_HEADING_MONTH_8;
		} elseif ($i == "9") {
			return TEXT_HEADING_MONTH_9;
		} elseif ($i == "10") {
			return TEXT_HEADING_MONTH_10;
		} elseif ($i == "11") {
			return TEXT_HEADING_MONTH_11;
		} else {
			return TEXT_HEADING_MONTH_12;
		}
	} else {
		return strftime('%B', mktime(0, 0, 0, $i, 1, 2000));
	}
}

////
// Return all subcategory IDs
// TABLES: categories
// 取得所有下级目录
/**
 * 取得所有下级目录 cached
 * @param unknown_type $subcategories_array
 * @param unknown_type $parent_id
 * @author vincent @modify by vincent @ 2011-4-22 下午04:50:53
 */
function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
	$subcategories_list = MCache::search_categories('parent_id', (int) $parent_id, true);
	foreach ($subcategories_list as $subcategories) {
		$subcategories_array[sizeof($subcategories_array)] = (int) $subcategories['categories_id'];
		if ($subcategories['categories_id'] != $parent_id) {
			tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
		}
	}
	/*
	 * $subcategories_query = tep_db_query("select categories_id from " .
	 * TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'"); while
	 * ($subcategories = tep_db_fetch_array($subcategories_query)) {
	 * $subcategories_array[sizeof($subcategories_array)] =
	 * $subcategories['categories_id']; if ($subcategories['categories_id'] !=
	 * $parent_id) { tep_get_subcategories($subcategories_array,
	 * $subcategories['categories_id']); } }
	 */
}

/**
 * 获取子类用于数据库的ID字符串
 * @param unknown_type $category_id
 * @author vincent @modify by vincent at 2011-4-22 下午05:11:01
 */
function tep_get_category_subcategories_ids($category_id) {
	$subcategories_array = array (
			$category_id 
	);
	tep_get_subcategories($subcategories_array, $category_id);
	return implode(",", $subcategories_array);
}
/*
 * function tep_get_category_subcategories_ids($category_id) {
 * $stored_cat_done_ids = "'" . (int)$category_id . "', "; $subcategories_array
 * = array(); tep_get_subcategories($subcategories_array, $category_id); for
 * ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) { $stored_cat_done_ids
 * .= "'" . (int)$subcategories_array[$i] . "', "; } $stored_cat_done_ids =
 * substr($stored_cat_done_ids, 0, -2); return $stored_cat_done_ids; }
 */

////
///普通字符替换成xml内容的字符
//
function to_xml_str($str) {
	$str = htmlspecialchars($str);
	//$str = @str_replace('<','&lt;',$str);
	//$str = @str_replace('>','&gt;',$str);
	return $str;
}

////
// 更新首页客户咨询的xml文件
//
function update_xml_for_index_page_customer_questions() {
	return false; //已经不用此功能了
	$xml_content_top = '<?xml version="1.0" encoding="gb2312"?>' . "\n" . '<object id="customer_questions" date="' . date('Y-m-d H:i:s') . '">' . "\n";
	$xml_content_foot = '</object>';
	$xml_content_center = '';
	
	$sql = tep_db_query('SELECT * FROM `tour_question` WHERE languages_id ="1" AND que_replied > 0 && replay_has_checked="1" ORDER BY date DESC Limit 5');
	while ($rows = tep_db_fetch_array($sql)) {
		$xml_content_center .= '<questions id="' . (int) $rows['que_id'] . '">
		<products_id>' . (int) $rows['products_id'] . '</products_id>
		<products_model>' . to_xml_str(tep_get_products_model((int) $rows['products_id'])) . '</products_model>
		<content>' . to_xml_str(strip_tags(ereg_replace('/[:space:]+/', ' ', $rows['question']))) . '</content>
	</questions>
' . "\n";
	}
	
	$xml_content = $xml_content_top . $xml_content_center . $xml_content_foot;
	$xml_file = DIR_FS_CATALOG . 'index_page_maps/customer_questions.xml';
	@chmod($xml_file, 0777);
	$fopen = fopen($xml_file, "w");
	fwrite($fopen, $xml_content);
	fclose($fopen);
	//@chmod($xml_file, 0644);
	/* echo '<script>alert(\'首页更新成功！\')</script>'; */
	return $xml_file;
}

////
// 更新首页照片分享的xml文件
//
function update_xml_for_index_page_photo_sharing() {
	return false; //已经不用此功能了
	

	$xml_content_top = '<?xml version="1.0" encoding="gb2312"?>' . "\n" . '<object id="photo_sharing" date="' . date('Y-m-d H:i:s') . '">' . "\n";
	$xml_content_foot = '</object>';
	$xml_content_center = '';
	
	//$sql = tep_db_query('SELECT * FROM '.TABLE_TRAVELER_PHOTOS.' WHERE image_status ="1" AND is_display_homepage="1" Group By products_id ORDER BY  traveler_photo_id DESC Limit 6');
	$sql = tep_db_query('SELECT * FROM ' . TABLE_TRAVELER_PHOTOS . ' WHERE image_status ="1" AND is_display_homepage="1" ORDER BY  traveler_photo_id DESC Limit 12');
	while ($rows = tep_db_fetch_array($sql)) {
		if (tep_not_null($rows['customers_name'])) {
			$text = $rows['customers_name'];
		}
		if (tep_not_null($rows['image_title'])) {
			//$text = $rows['image_title'];
		}
		
		$basename_image_name = basename($rows['image_name']);
		$image_name0 = str_replace($basename_image_name, 'thumb_' . $basename_image_name, $rows['image_name']);
		/*
		 * $xml_content_center .= '<photo
		 * id="'.(int)$rows['traveler_photo_id'].'">
		 * <products_id>'.(int)$rows['products_id'].'</products_id>
		 * <img>'.'images/reviews/'.$image_name0.'</img>
		 * <text>'.to_xml_str(strip_tags($text)).'</text> </photo> '."\n";
		 */
		/* 不生成姓名 */
		$xml_content_center .= '<photo id="' . (int) $rows['traveler_photo_id'] . '">
		<products_id>' . (int) $rows['products_id'] . '</products_id>
		<img>' . 'images/reviews/' . $image_name0 . '</img>		
	</photo>
' . "\n";
	}
	
	$xml_content = $xml_content_top . $xml_content_center . $xml_content_foot;
	$xml_file = DIR_FS_CATALOG . 'index_page_maps/photo_sharing.xml';
	@chmod($xml_file, 0777);
	$fopen = fopen($xml_file, "w");
	fwrite($fopen, $xml_content);
	fclose($fopen);
	//chmod($xml_file, 0644);
	/* echo '<script>alert(\'首页更新成功！\')</script>'; */
	return $xml_file;
}

//评论评分数组及分数
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

//补丁函数，用于在套餐团的products_special_note中自动添加一些文字。
function auto_add_note_to_products_special_note($products_id, $str) {
	if (!(int) $products_id) {
		return false;
	}
	$sql = tep_db_query('SELECT products_vacation_package FROM ' . TABLE_PRODUCTS . ' where products_id="' . (int) $products_id . '" ');
	$row = tep_db_fetch_array($sql);
	if ($row['products_vacation_package'] == '1') {
		$match_str = '--如果是第一天自行入住酒店且不需要接机服务的贵宾, 请务必当日在工作时间联络当地巴士部门或者走四方网以确认第二天的出发时间。';
		
		if (!preg_match('/' . substr($match_str, 2, 40) . '/', $str)) {
			$str = $match_str . "\n\n" . $str;
		}
	}
	return $str;
}

//自动更新全部的套餐团的添加文字
function auot_update_products_special_note() {
	$sql = tep_db_query('SELECT products_id, products_special_note FROM ' . TABLE_PRODUCTS . '  WHERE products_vacation_package ="1" ');
	$rows = tep_db_fetch_array($sql);
	if ((int) $rows['products_id']) {
		do {
			$parameters = array (
					'products_special_note' => '' 
			);
			$pInfo = new objectInfo($parameters);
			$pInfo->objectInfo($rows);
			
			$str = auto_add_note_to_products_special_note((int) $rows['products_id'], $pInfo->products_special_note);
			$sql_data_array_product = array (
					'products_special_note' => tep_db_prepare_input($str) 
			);
			tep_db_perform(TABLE_PRODUCTS, $sql_data_array_product, 'update', "products_id = '" . (int) $products_id . "'");
		} while ($rows = tep_db_fetch_array($sql));
	}
}

/**
 * 判断某订单下面是否存在套餐团
 * @param $orders_id
 * @author Howard
 */
function is_package_tour_on_order($orders_id) {
	$sql = tep_db_query('SELECT p.products_id FROM ' . TABLE_ORDERS_PRODUCTS . ' op, ' . TABLE_PRODUCTS . ' p  WHERE p.products_id = op.products_id AND p.products_vacation_package ="1" AND op.orders_id="' . (int) $orders_id . '" Limit 1');
	$rows = tep_db_fetch_array($sql);
	return (int) $rows['products_id'];
}

//取得对应景点tab标签对应的文字
function get_categories_tab_tags($tab_tag = '') {
	$tags = array ();
	$tags[] = array (
			'id' => 'introduction',
			'text' => '景点介绍' 
	);
	$tags[] = array (
			'id' => 'vcpackages',
			'text' => '度假休闲游' 
	);
	$tags[] = array (
			'id' => 'recommended',
			'text' => '推荐行程' 
	);
	$tags[] = array (
			'id' => 'special',
			'text' => '特价行程' 
	);
	$tags[] = array (
			'id' => 'diy',
			'text' => '自助游' 
	);
	$tags[] = array (
			'id' => 'show',
			'text' => '秀Show' 
	);
	$tags[] = array (
			'id' => 'maps',
			'text' => '地图Tab' 
	);
	if (!tep_not_null($tab_tag)) {
		return $tags;
	} else {
		for ($i = 0; $i < count($tags); $i ++) {
			if ($tags[$i]['id'] == $tab_tag) {
				return $tags[$i]['text'];
			}
		}
	}
}

//从词库中取出与$pat_content匹配的$num个关键词,结果是数组或false
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

//计算实际价格参考
//说明：如果Final Price Reference未有填充则先填充Final Price Reference到数据库。在2010-05-11之前的订单的Final Price Reference值都可能不准确。
function get_and_up_final_price_reference($oID, $ord_prod_history_id, $ratail_value) {
	global $order;
	
	if (sizeof($order->products) == 1) {
		$sql = tep_db_query('select * from ' . TABLE_ORDERS_TOTAL . ' where orders_id="' . $oID . '" and class!="ot_total" and class!="ot_subtotal" ');
		$preferential = 0;
		while ($row = tep_db_fetch_array($sql)) {
			if (preg_match('/-/', strip_tags($row['text']))) {
				$preferential -= abs($row['value']);
			} else {
				$preferential += abs($row['value']);
			}
		}
		$preferential = round($preferential, 2);
		$preferential_price = $ratail_value + $preferential;
	} elseif (sizeof($order->products) > 1) {
		
		$sql = tep_db_query('select * from ' . TABLE_ORDERS_TOTAL . ' where orders_id="' . $oID . '" and class!="ot_total" ');
		$preferential = 0;
		$subtotal = 0;
		while ($row = tep_db_fetch_array($sql)) {
			if ($row['class'] == 'ot_subtotal') {
				$subtotal = $row['value'];
			} else {
				if (preg_match('/-/', strip_tags($row['text']))) {
					$preferential -= abs($row['value']);
				} else {
					$preferential += abs($row['value']);
				}
			}
		}
		$preferential = round($preferential, 2);
		$preferential_price = $ratail_value + ($ratail_value / $subtotal * $preferential);
	}
	
	$final_price_reference = str_replace('.00', '', round($preferential_price, 2));
	
	tep_db_query("UPDATE `orders_product_update_history` SET `final_price_reference`  = '" . $final_price_reference . "' WHERE `ord_prod_history_id` = '" . $ord_prod_history_id . "' ;");
	
	return $final_price_reference;
}

/**
 * 使用手机短信发送电子参团凭证通知信息
 *
 * @param unknown_type $orders_id
 */
function send_eticket_sms($orders_id) {
	global $cpunc;
	if (preg_match('/' . preg_quote('[电子参团凭证通知]') . '/', CPUNC_USE_RANGE)) {
		//根据订单号取得手机号码
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
		 * tep_db_output($row['customers_mobile_phone']); $mobile_phone =
		 * preg_replace('/.*-/','',$mobile_phone); $mobile_phone =
		 * preg_replace('/^0+/','',$mobile_phone); $mobile_phone1 =
		 * tep_db_output($row['customers_cellphone']); $mobile_phone1 =
		 * preg_replace('/.*-/','',$mobile_phone1); $mobile_phone1 =
		 * preg_replace('/^0+/','',$mobile_phone1); $mobile_phone2 =
		 * tep_db_output($row['customers_telephone']); $mobile_phone2 =
		 * preg_replace('/.*-/','',$mobile_phone2); $mobile_phone2 =
		 * preg_replace('/^0+/','',$mobile_phone2); $strMobile = '';
		 * if(strlen($mobile_phone2) >= $len_array[0] && strlen($mobile_phone2)
		 * <= $len_array[1] || strlen($mobile_phone2)==$len_array[0]){
		 * $strMobile = $mobile_phone2; } if(strlen($mobile_phone1) >=
		 * $len_array[0] && strlen($mobile_phone1) <= $len_array[1] ||
		 * strlen($mobile_phone1)==$len_array[0]){ $strMobile = $mobile_phone1;
		 * } if(strlen($mobile_phone) >= $len_array[0] && strlen($mobile_phone)
		 * <= $len_array[1] || strlen($mobile_phone)==$len_array[0]){ $strMobile
		 * = $mobile_phone; }
		 */
		if ($strMobile != '') {
			$content = sprintf(E_TICKET_SMS, $orders_id);
			$cpunc->SendSMS($strMobile, $content, CHARSET);
		}
	}
}

function send_ticket_issued_sms($products_id, $striposproduct_name, $product_attributes, $orders_id, $strMobile = '') {
	global $cpunc;
	if (preg_match('/' . preg_quote('[度假行程确认完毕]') . '/', CPUNC_USE_RANGE)) {
		if (!tep_not_null($strMobile)) {
			//根据订单号取得手机号码
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
		}
		/*
		 * $striposname = stripos($striposproduct_name, '接送'); if ($striposname
		 * === false) { $striposname = stripos($striposproduct_name, '接机'); } if
		 * ($striposname === false) { $striposname =
		 * stripos($striposproduct_name, '送机'); } $checkairportpick = 0; for ($i
		 * = 0; $i < count($product_attributes); $i++) { if
		 * (stristr($product_attributes[$i]['option'], '机场') != '') {
		 * $checkairportpick = 1; } }
		 */
		$speciauery = tep_db_query("select products_special_note, products_vacation_package from " . TABLE_PRODUCTS . "  where products_id = '" . $products_id . "' ");
		$checkproductspackage = tep_db_fetch_array($speciauery);
		if ($checkproductspackage['products_vacation_package'] == '1') { // || $striposname !== false || $checkairportpick != 0
			$content = "您的度假行程（订单号：" . $orders_id . "）和机场接应确认完毕，已为您签发了电子客票。请登陆邮箱或“用户中心”查看详情。如有紧急情况请拨打电子客票上的紧急联络电话，我们会尽快协助处理。祝您旅途愉快！";
		} else {
			$content = "您的度假行程（订单号：" . $orders_id . "）确认完毕，已为您签发了电子客票。请登陆邮箱或“用户中心”查看详情。如有紧急情况请拨打电子客票上的紧急联络电话，我们会尽快协助处理。祝您旅途愉快！";
		}
		
		if ($strMobile != '' && $cpunc->SendSMS($strMobile, $content, CHARSET)) {
			return true;
		}
	}
	return false;
}

/**
 * 取得客户头像src
 *
 * @param unknown_type $customers_id
 * @param unknown_type $old_src
 * @return unknown
 */
function tep_customers_face($customers_id, $old_src) {
	$query = tep_db_query("select customers_face from " . TABLE_CUSTOMERS . " where customers_id = '" . (int) $customers_id . "' limit 1 ");
	$row = tep_db_fetch_array($query);
	if (tep_not_null($row['customers_face'])) {
		return 'face/' . $row['customers_face'];
	}
	return $old_src;
}

function out_thumbnails($input_file, $out_file, $max_width = 100, $max_height = 100) {
	// File and new size
	$filename = $input_file;
	$out_file = $out_file;
	// Get image sizes and mime type
	$image_array = getimagesize($filename);
	//如果图片是非png、gif和jpeg图片则停止继续
	if ($image_array[2] != 1 && $image_array[2] != 2 && $image_array[2] != 3) {
		return false;
	}
	
	list ($width, $height) = $image_array;
	$newwidth = $width;
	$newheight = $height;
	//长度比的最大值
	$max_value = intval(($max_height / $max_width) * 100) / 100;
	//计算图像长宽比
	$ratio_value = intval(($height / $width) * 100) / 100;
	if ($max_value >= $ratio_value) {
		if ($width > (int) $max_width) { //长比高大
			$newwidth = (int) $max_width;
			$newheight = (int) ($newwidth * $ratio_value);
		}
	} else {
		if ($height > (int) $max_height) {
			$newheight = (int) $max_height;
			$newwidth = (int) ($newheight / $ratio_value);
		}
	}
	
	// Load
	if (function_exists('imagecreatetruecolor')) {
		$thumb = imagecreatetruecolor($newwidth, $newheight); //创建新图片并指定大小
	} else {
		$thumb = imagecreate($newwidth, $newheight);
	}
	switch ($image_array[2]) { //取得图片类型
		case 1:
			$source = @imagecreatefromgif($filename);
			break;
		case 2:
			$source = @imagecreatefromjpeg($filename);
			break;
		case 3:
			$source = @imagecreatefrompng($filename);  /*imagesavealpha($filename, true);*/  break;
	}
	
	if (function_exists('imagecopyresampled')) {
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); //不失真
	} else {
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); //失真
	}
	
	switch ($image_array[2]) { //输出图片到文件$dstFile
		case 1:
			imagegif($thumb, $out_file);
			break;
		case 2:
			imagejpeg($thumb, $out_file, 100);
			break;
		case 3:   /*imagesavealpha($thumb, true);*/ imagepng($thumb, $out_file);
			break;
	}
	@imagedestroy($thumb);
	@imagedestroy($source);
	return $out_file;
}
//echo out_thumbnails($input_file, $out_file, $max_width, $max_height);


//取得图片的缩略图
function get_thumbnails($src_file_name, $head_string = "thumb_") {
	$new_dir_file_src = dirname($src_file_name) . '/' . $head_string . basename($src_file_name);
	//echo $new_dir_file_src;
	$old_dir_file_src = $src_file_name;
	if (file_exists($new_dir_file_src)) {
		return $new_dir_file_src;
	} else {
		return $old_dir_file_src;
	}
	//return $new_dir_file_src;
}

//根据数字或字母值取得性别字符 get string for gender
function tep_get_gender_string($num_or_string, $show_type = 0) {
	$num_or_string = strtolower($num_or_string);
	$man = '男';
	$woman = '女';
	if ($show_type == 1) {
		$man = '先生';
		$woman = '女士';
	}
	
	if ($num_or_string == 'm' || $num_or_string == '1') {
		return $man;
	}
	if ($num_or_string == 'f' || $num_or_string == '2') {
		return $woman;
	}
	return false;
}

//howard added get customers sex
function tep_customer_gender($customers_id) {
	$customers = tep_db_query("select customers_gender from " . TABLE_CUSTOMERS . " where customers_id = '" . (int) $customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);
	return $customers_values['customers_gender'];
}

function getting_front_images_link() {
}

// Output a form
function tep_draw_form_front($name, $action, $method = 'post', $parameters = '') {
	$form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';
	
	if (tep_not_null($parameters))
		$form .= ' ' . $parameters;
	
	$form .= '>';
	
	return $form;
}

// 取得培训系统目录树zhh_system_dir
function tep_get_class_tree($parent_id = '0', $spacing = '', $exclude = '', $class_tree_array = '', $include_itself = false, $loop = false, $check_permissions = false) {
	global $login_groups_id;
	$where_permissions = "";
	if ($check_permissions != false) { //检测权限
		$tmp_where_start = ' and ( ';
		if ($check_permissions == "rwd") { //最严格
			$where_permissions = ' FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) ';
		} elseif ($check_permissions == "rw") { //次之
			$where_permissions = ' FIND_IN_SET( "' . $login_groups_id . '", rw_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) ';
		} else { //最松散
			$where_permissions = ' FIND_IN_SET( "' . $login_groups_id . '", r_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rw_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) ';
		}
		$tmp_where_end = ' ) ';
		$where_permissions = $tmp_where_start . $where_permissions . $tmp_where_end;
	} else {
		$check_permissions = false;
	}
	
	if (!is_array($class_tree_array))
		$class_tree_array = array ();
	if ((sizeof($class_tree_array) < 1) && $exclude != '0') {
		//$class_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
	}
	
	if ($include_itself && !(int) $parent_id) {
		$category_query = tep_db_query("select  dir_id,dir_name,parent_id from  `zhh_system_dir` where dir_id = '" . (int) $parent_id . "'" . $where_permissions . " order by sort_num, dir_id ");
		$category = tep_db_fetch_array($category_query);
		$class_tree_array[] = array (
				'id' => $parent_id,
				'text' => $category['dir_name'],
				'parent' => $category['parent_id'] 
		);
	}
	
	$categories_query = tep_db_query("select dir_id,dir_name,parent_id from `zhh_system_dir` where parent_id = '" . (int) $parent_id . "' " . $where_permissions . " order by sort_num, dir_id ");
	while ($categories = tep_db_fetch_array($categories_query)) {
		if ($exclude != $categories['dir_id'])
			$class_tree_array[] = array (
					'id' => $categories['dir_id'],
					'text' => $spacing . $categories['dir_name'],
					'parent' => $categories['parent_id'] 
			);
		if ($loop != false) {
			$class_tree_array = tep_get_class_tree($categories['dir_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $class_tree_array, $include_itself, $loop, $check_permissions);
		}
	}
	return $class_tree_array;
}

//取得TOP到当前目录的数组zhh_system_dir
function tep_get_top_to_now_class($dir_id, $class_array = '') {
	if (!is_array($class_array)) {
		$class_array = array ();
	}
	$array_count = count($class_array);
	if (!(int) $dir_id) {
		$class_array[$array_count] = array (
				'id' => '0',
				'text' => TEXT_TOP 
		);
	} else {
		$sql = tep_db_query('select * from `zhh_system_dir` where dir_id = "' . (int) $dir_id . '" ');
		$row = tep_db_fetch_array($sql);
		
		if ((int) $row['dir_id']) {
			$class_array[$array_count] = array (
					'id' => $row['dir_id'],
					'text' => $row['dir_name'],
					'parent_id' => $row['parent_id'] 
			);
			$class_array = tep_get_top_to_now_class((int) $row['parent_id'], $class_array);
		}
	}
	return $class_array;
}

//取得培训文章所属目录
function get_words_dirs($words_id) {
	if (!(int) $words_id) {
		return false;
	}
	$dirs = array ();
	$sql = tep_db_query('SELECT * FROM `zhh_words_to_dir` WHERE words_id ="' . $words_id . '" ');
	while ($rows = tep_db_fetch_array($sql)) {
		$dirs[] = tep_get_top_to_now_class($rows['dir_id']);
	}
	return $dirs;
}

//取得订单总金额的值value
function get_orders_ot_total_value($order_id) {
	$oid = (int) $order_id;
	if (!(int) $oid)
		return false;
	$sql = tep_db_query('SELECT value FROM `orders_total` WHERE orders_id="' . $oid . '" and class = "ot_total" limit 1 ');
	$row = tep_db_fetch_array($sql);
	return $row['value'];
}

//判断转账支付系统数据表是否有该订单
function get_order_has_domestic($order_id) {
	$oid = (int) $order_id;
	if (!(int) $oid)
		return false;
	$sql = tep_db_query('SELECT orders_id FROM `domestic_orders` WHERE orders_id="' . $oid . '" limit 1 ');
	$row = tep_db_fetch_array($sql);
	if ((int) $row['orders_id']) {
		return $row['orders_id'];
	}
	return false;
}
//根据条件更新订单状态为部分已收款
/* 如果订单历史记录有Payment Received的状态才修改 100027 to 100052 */
function auto_update_orders_status_set_partial_payment_received($order_id) {
	global $login_id;
	
	return false; //应陈总要求，此功能暂停
	

	if (!(int) get_order_has_domestic($order_id))
		return false;
	$row = tep_db_fetch_array(tep_db_query('SELECT orders_status FROM `orders` WHERE orders_id="' . (int) $order_id . '" Limit 1 '));
	$need_update = false;
	if ($row['orders_status'] == 100027) {
		$need_update = true;
	}
	if ($need_update == false) {
		$row1_num = tep_db_num_rows(tep_db_query('SELECT orders_status_id FROM `orders_status_history` WHERE orders_id="' . (int) $order_id . '" and orders_status_id ="100027" Limit 1 '));
		if ((int) $row1_num) {
			$need_update = true;
		}
	}
	if ($need_update == true) {
		if ($row['orders_status'] != 100052) {
			tep_db_query("update " . TABLE_ORDERS . " set orders_status ='100052', last_modified =now() WHERE orders_id='" . (int) $order_id . "' ");
			tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, updated_by)	values ('" . (int) $order_id . "', '100052', now(), '0', 'Auto changed status! 因订单总额有变动，更新状态为 partial payment received','" . $login_id . "')");
		}
		$total = tep_db_fetch_array(tep_db_query('select value from orders_total where orders_id="' . (int) $order_id . '" and class="ot_total" '));
		//value_rmb 清空
		tep_db_query("update domestic_orders set orders_status ='100052', value ='" . $total['value'] . "', value_rmb=NULL WHERE orders_id='" . (int) $order_id . "' ");
	}
	return true;
}

//增加订单银行转账支付系统
function auto_add_to_domestic_orders($order_id) {
	if ((int) is_travel_comp($order_id)) {
		return false;
	} //排除结伴同游
	$num = tep_db_num_rows(tep_db_query('SELECT orders_id FROM `domestic_orders` WHERE orders_id ="' . (int) $order_id . '" limit 1'));
	if (!(int) $num) {
		//以最简单的方式插入一条记录
		$orders = tep_db_fetch_array(tep_db_query('SELECT * FROM `orders` WHERE orders_id ="' . (int) $order_id . '" limit 1'));
		if ((int) $orders['orders_id'] && preg_match('/银行转账/', $orders['payment_method'])) {
			tep_db_query('insert into domestic_orders (orders_id, orders_status, customers_id) values ("' . $orders['orders_id'] . '", "' . $orders['orders_status'] . '", "' . $orders['customers_id'] . '") ');
		}
	}
}

function tep_get_max_allow_child_age_for_tour($products_id) {
	global $languages_id;
	$trun_age_max_id = '';
	$products_query = tep_db_query("select p.max_allow_child_age, ta.default_max_allow_child_age from " . TABLE_PRODUCTS . " p, " . TABLE_TRAVEL_AGENCY . " ta where p.agency_id = ta.agency_id");
	if ($products = tep_db_fetch_array($products_query)) {
		if ($products['max_allow_child_age'] == '' || $products['max_allow_child_age'] == '0') {
			$products['max_allow_child_age'] = $products['default_max_allow_child_age'];
		}
		$trun_age_max_id = $products['max_allow_child_age'];
	}
	
	return $trun_age_max_id;
}

/**
 * *记录对于QA的操作到statistics_qa_history表中供统计之用 *operate_type 1 回复 2修改回复 3修改问题
 */
function tep_add_qa_history($operate_type, $operator_id = null, $que_id = 0, $ans_id = 0) {
	if ($operator_id === null) {
		global $login_id;
		$operator_id = $login_id;
	}
	$sql = "INSERT INTO statistics_qa_history (que_id,ans_id,operate_type,operator_id,add_time)VALUES(" . intval($que_id) . "," . intval($ans_id) . " ," . intval($operate_type) . "," . intval($operator_id) . ",NOW())";
	tep_db_query($sql);
}

/**
 * 记录会计的操作 1.Number of “Report--Payment Report--Charge Capture Report ”updates
 * 2.Number of “Report--Payment Report-- Uncharged Report” updates 3.Number of
 * “Report--Accounting Report-- Payment History Report (current)” updates
 * @param int $operate_type
 * @param adminid $operator_id
 */
function tep_add_accountant_history($operate_type, $operator_id = null) {
	if ($operator_id === null) {
		global $login_id;
		$operator_id = $login_id;
	}
	$sql = "INSERT INTO statistics_accountant_history (admin_id,operate_type,add_time)VALUES(" . intval($operator_id) . "," . intval($operate_type) . " ,'" . date('Y-m-d H:i:s') . "')";
	tep_db_query($sql);
}

/**
 * 删除购物车数据库内某个产品，只要在管理后台修改了产品信息（特别是价格信息）都要将购物车中的相关产品清除
 * @param unknown_type $product_id
 * @author Howard
 */
function del_customers_basket_for_products($product_id) {
	if ((int) $product_id) {
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where CONCAT(products_id,'{') Like '" . (int) $product_id . "{%'");
		tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where CONCAT(products_id,'{') Like '" . (int) $product_id . "{%'");
	}
}

/**
 * 根据特价表的特价ID取得产品的ID号
 * @param $specials_id
 * @author Howard
 */
function specials_id_to_products_id($specials_id) {
	$sql = tep_db_query('SELECT products_id FROM `specials` WHERE specials_id="' . (int) $specials_id . '" Limit 1');
	$row = tep_db_fetch_array($sql);
	return (int) $row['products_id'];
}

/**
 * 取得订单所属客服的工号//和姓名
 *
 * @param unknown_type $orders_id
 */
function tep_get_order_owner_admin($orders_id) {
	$string = '';
	$sql = tep_db_query('SELECT orders_owner_admin_id FROM ' . TABLE_ORDERS . ' WHERE orders_id ="' . (int) $orders_id . '" ');
	$row = tep_db_fetch_array($sql);
	if (tep_not_null($row['orders_owner_admin_id'])) {
		$sql1 = tep_db_query('SELECT admin_job_number,admin_firstname,admin_lastname FROM `admin` WHERE admin_id="' . $row['orders_owner_admin_id'] . '" ');
		$row1 = tep_db_fetch_array($sql1);
		if (tep_not_null($row1['admin_job_number'])) {
			//$string = $row1['admin_firstname'].' '.$row1['admin_lastname'].' ['.$row1['admin_job_number'].']';
			$string = $row1['admin_job_number'];
		}
	}
	return $string;
}

/**
 * 取得订单的销售链接的工号。
 *
 * @param int $order_id
 * @return string number boolean
 */
function tep_get_order_owner_jobs_id($order_id) {
	$str_sql = 'select is_other_owner, orders_owner_admin_id from orders where orders_id=' . (int) $order_id;
	$sql_query = tep_db_query($str_sql);
	$arr = tep_db_fetch_array($sql_query);
	if ($arr) {
		if ($arr['is_other_owner'] == 1)
			return '19';
		elseif ($arr['orders_owner_admin_id'])
			return tep_get_job_number_from_admin_id($arr['orders_owner_admin_id']);
		else
			return '';
	} else
		return false;
}

/**
 * 取得后台管理人员列表
 *
 * @param unknown_type $groups_id
 */
function tep_get_admin_list($groups_id = '') {
	$array = array ();
	$where = ' where 1 ';
	if (tep_not_null($groups_id)) {
		$where .= ' and admin_groups_id in(' . $groups_id . ') ';
	}
	$sql = tep_db_query("SELECT admin_id, admin_job_number, admin_firstname, admin_lastname FROM `admin` {$where} ORDER BY admin_job_number ");
	while ($rows = tep_db_fetch_array($sql)) {
		//$array[] = array('id'=>$rows['admin_id'], 'text'=>$rows['admin_firstname'].' '.$rows['admin_lastname'].' ['.$rows['admin_job_number'].']');
		$array[] = array (
				'id' => $rows['admin_id'],
				'text' => $rows['admin_job_number'] 
		);
	}
	return $array;
}

/**
 * 取得某个管理员下要处理的订单总数
 *
 * @param unknown_type $admin_id
 */
function tep_get_orders_next_admin_count($admin_id) {
	$sql = tep_db_query('SELECT count(orders_id) as total FROM `orders` WHERE next_admin_id="' . (int) $admin_id . '" and need_next_admin="1" ');
	$row = tep_db_fetch_array($sql);
	return (int) $row['total'];
}

/**
 * 取得某个订单的付款记录，返回false或数组
 *
 * @param unknown_type $orders_id
 */
function tep_get_orders_payment_history($orders_id) {
	$data = false;
	$sql = tep_db_query('SELECT * FROM `orders_payment_history` where orders_id=' . (int) $orders_id);
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows;
	}
	return $data;
}

/**
 * 取得产品在旧站的电子参团凭证模板
 *
 * @param unknown_type $product_id
 */
function tep_get_eticket_old_tpl_for_usitrip($product_id) {
	$sql = tep_db_query('SELECT eticket_old FROM `products_description` where products_id ="' . (int) $product_id . '" ');
	$row = tep_db_fetch_array($sql);
	return $row['eticket_old'];
}

/**
 * 从产品中取得产品的供应商ID号
 *
 * @param unknown_type $products_id
 */
function tep_get_products_agency_id($products_id) {
	$data = false;
	$sql = tep_db_query("select agency_id from " . TABLE_PRODUCTS . " where products_id = '" . (int) $products_id . "' ");
	$row = tep_db_fetch_array($sql);
	$data = $row['agency_id'];
	return (int) $data;
}

/**
 * 取得订单某产品中供应商的最新状态ID $orders_products_id
 */
function tep_get_orders_products_provider_status_id($orders_products_id) {
	$data = false;
	$sql = tep_db_query('SELECT h.provider_order_status_id FROM `provider_order_products_status_history` h, `provider_order_products_status` s WHERE s.provider_order_status_for="1" and s.provider_order_status_id=h.provider_order_status_id and h.orders_products_id="' . (int) $orders_products_id . '" ORDER BY h.popc_id DESC Limit 1 ');
	$row = tep_db_fetch_array($sql);
	if ((int) $row['provider_order_status_id'])
		$data = $row['provider_order_status_id'];
	return $data;
}

/**
 * 更新某个订单的状态 此函数同时更新订单的状态和状态历史
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
 * 自动分单函数，用于组合团分单
 * 分出来的行程价格和成本均为0，第一个子团的出发日期就是大团的出发日期，第二个子团的出发日期是第一个子团的结束日期，依次类推，分出来的子团客户在前台是查看不到的，若后台出现错误，也不能显示在前台，分出来的子团的所有信息都是自动调取.
 * 第一个团要加接的航班信息或上车地址信息，最后一个团要加送的航班信息 要添加的数据表有 orders_products,
 * orders_product_eticket, orders_product_flight
 * @param unknown_type $orders_products_id 要分单的订单组合团产品id
 */
function tep_split_orders_products($orders_products_id) {
	//先清除旧的子订单信息
	$del_sql = tep_db_query('SELECT orders_products_id FROM `orders_products` where parent_orders_products_id="' . (int) $orders_products_id . '" ');
	while ($rows = tep_db_fetch_array($del_sql)) {
		tep_db_query('DELETE FROM `orders_product_eticket` WHERE `orders_products_id` = "' . (int) $rows['orders_products_id'] . '" ');
		tep_db_query('DELETE FROM `orders_product_flight` WHERE `orders_products_id` = "' . (int) $rows['orders_products_id'] . '" ');
		tep_db_query('DELETE FROM `orders_products` WHERE `orders_products_id` = "' . (int) $rows['orders_products_id'] . '" ');
	}
	
	//调航班信息(为防止以后可能有一个订单产品有2个以上的航班信息的情况，这里航班信息单独查询)
	$flight_sql = tep_db_query('SELECT * FROM `orders_product_flight` where orders_products_id="' . (int) $orders_products_id . '" ');
	$flightDatas = false;
	while ($flight_rows = tep_db_fetch_array($flight_sql)) {
		$flightDatas[] = $flight_rows;
	}
	
	//写新的子订单信息	
	$sql = tep_db_query('SELECT p.products_model_sub, op.*, ope.* FROM `orders_products` op, products p, orders_product_eticket ope where op.products_id=p.products_id and ope.orders_products_id = op.orders_products_id and op.orders_products_id="' . (int) $orders_products_id . '" ');
	$row = tep_db_fetch_array($sql);
	if (tep_not_null($row['products_model_sub'])) {
		//orders_products
		$orders_id = $row['orders_id'];
		$products_room_info = $row['products_room_info'];
		$total_room_adult_child_info = $row['total_room_adult_child_info'];
		
		$products_departure_date = $row['products_departure_date']; //第一个子团的出发日期		
		$products_departure_time = $row['products_departure_time'];
		$products_departure_location = $row['products_departure_location'];
		$orders_products_payment_status = $row['orders_products_payment_status'];
		//orders_product_eticket
		$guest_name = $row['guest_name'];
		$guest_number = $row['guest_number'];
		$depature_full_address = $row['depature_full_address'];
		$guest_body_weight = $row['guest_body_weight'];
		$guest_gender = $row['guest_gender'];
		$guest_body_height = $row['guest_body_height'];
		$agree_single_occupancy_pair_up = $row['agree_single_occupancy_pair_up'];
		
		//orders_products
		$products_model_subs = explode(';', trim($row['products_model_sub'], ';'));
		$loop = 0;
		$sub_total = count($products_model_subs);
		foreach ($products_model_subs as $products_model) {
			$loop ++;
			$products_model = trim($products_model);
			$products_id = tep_get_products_id_from_model($products_model);
			//判断产品是不是酒店
			$is_hotel = product_is_hotel($products_id);
			$hotel_checkout_date = '0000-00-00 00:00:00';
			
			$products_name = tep_get_products_name($products_id);
			if ($loop == 1) {
				$products_end_date = tep_get_products_end_date($products_id, date("Y-m-d H:i:s", strtotime($products_departure_date))); //第一个子团的结束日期
			} elseif ($loop > 1) {
				$products_departure_date = $products_end_date; //第二个团以后的出发日期是上一个团的结束日期
				//求第二个子团以后的出发日期			
				$products_end_date = tep_get_products_end_date($products_id, date("Y-m-d H:i:s", strtotime($products_departure_date))); //求第二个子团以后的结束日期
				$depature_full_address = ''; //出发地址信息,第二个团以后的信息为空
				$products_departure_time = '';
				$products_departure_location = '';
			}
			
			if ($is_hotel == "1") { //酒店退房日期要加+1天
				$hotel_checkout_date = $products_end_date = date('Y-m-d H:i:s', strtotime($products_end_date) + 24 * 3600);
			}
			
			//填写orders_products表数据
			$parent_orders_products_id = $orders_products_id;
			//自动分单后上车地址信息可以让供应商看到
			$products_departure_location_sent_to_provider_confirm = '1';
			$o_keys = array (
					'parent_orders_products_id',
					'orders_id',
					'products_id',
					'products_model',
					'products_name',
					'products_departure_date',
					'products_departure_time',
					'products_departure_location',
					'products_room_info',
					'total_room_adult_child_info',
					'is_hotel',
					'hotel_checkout_date',
					'orders_products_payment_status',
					'products_departure_location_sent_to_provider_confirm'
			);
			$sql_data = array ();
			foreach ($o_keys as $key) {
				$sql_data[$key] = $$key;
			}
			$sql_data['products_price_last_modified'] = tep_db_get_field_value('products_price_last_modified', 'products', ' products_id="' . (int) $products_id . '" ');
			tep_db_perform('orders_products', $sql_data); //写 orders_products 表
			$new_orders_products_id = tep_db_insert_id();
			//填写orders_product_eticket表数据
			$e_keys = array (
					'orders_id',
					'products_id',
					'guest_name',
					'depature_full_address',
					'guest_number',
					'guest_body_weight',
					'guest_gender',
					'guest_body_height',
					'orders_products_id',
					'agree_single_occupancy_pair_up' 
			);
			$ope_sql_data = array ();
			foreach ($e_keys as $_key) {
				$ope_sql_data[$_key] = $$_key;
				if ($_key == 'orders_products_id') {
					$ope_sql_data[$_key] = $new_orders_products_id;
				}
			}
			tep_db_perform('orders_product_eticket', $ope_sql_data); //写 orders_product_eticket 表
			//填写航班信息
			$flight_sql_data = array (
					'orders_id' => $orders_id,
					'products_id' => $products_id,
					'orders_products_id' => $new_orders_products_id 
			);
			if ($loop == 1 || $loop == $sub_total) {
				//第一个团写接机航班
				if ($loop == 1)
					$write_keys = array (
							'airline_name',
							'flight_no',
							'airport_name',
							'arrival_date',
							'arrival_time' 
					);
					//最后一个团写送机航班
				if ($loop == $sub_total)
					$write_keys = array (
							'airline_name_departure',
							'flight_no_departure',
							'airport_name_departure',
							'departure_date',
							'departure_time' 
					);
				for ($i = 0, $n = sizeof($flightDatas); $i < $n; $i ++) {
					foreach ($write_keys as $key) {
						$flight_sql_data[$key] = $flightDatas[$i][$key];
					}
					tep_db_perform('orders_product_flight', $flight_sql_data);
				}
			} else { //其它团写空的航班信息
				tep_db_query('INSERT INTO orders_product_flight SET orders_id="' . (int) $orders_id . '", products_id="' . $products_id . '", orders_products_id="' . $new_orders_products_id . '" ');
			}
		}
	}
	return true;
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
	//products_durations_type 0为天,1为小时,2为分钟
	if (!(int) $row['products_durations_type'] && (int) $row['products_durations']) {
		//$end_date_time = strtotime($departure_date) + ((int)$row['products_durations'] + 1) *24*3600;
		//$end_date_time = strtotime($departure_date) + (int)$row['products_durations']*24*3600;
		//$end_date_time = strtotime($departure_date) + (($row['products_durations']) - 1)*86400;	//如果是一天团，结束日期是当天，两天团，结束日期是第二天。所以减1
		$end_date_time = strtotime($departure_date . ' +' . max(0, (intval($row['products_durations']) - 1)) . 'days'); //如果是一天团，结束日期是当天，两天团，结束日期是第二天。所以减1
	}
	$end_date = date('Y-m-d H:i:s', $end_date_time);
	return $end_date;
}

/**
 * 检查某个订单行程产品的子订单数据返回子orders_products_id的数组
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_orders_products_sub($orders_products_id) {
	$data = false;
	$sql = tep_db_query('SELECT orders_products_id FROM `orders_products` where parent_orders_products_id="' . (int) $orders_products_id . '" ');
	while ($rows = tep_db_fetch_array($sql)) {
		$data[] = $rows['orders_products_id'];
	}
	return $data;
}

/**
 * 取得某订单行程的团号(在订单快照中取)
 *
 * @param unknown_type $orders_products_id
 */
function tep_get_orders_products_model($orders_products_id) {
	$sql = tep_db_query('SELECT products_model FROM `orders_products` where orders_products_id="' . (int) $orders_products_id . '" ');
	$row = tep_db_fetch_array($sql);
	return $row['products_model'];
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

?>