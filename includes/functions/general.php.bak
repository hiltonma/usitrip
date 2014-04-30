<?php
/**
 * $Id: general.php,v 1.1.1.1 2004/03/04 23:40:50 ccwjr Exp $
 * @package 页面级注释
 */

/**
 * 自动载入类      当前系统中不能使用
 * @param unknown_type $class_name
 * @author lwkai
 */
/*function __autoload($class_name) {
    $dirs = array(
        DIR_FS_CLASSES
    );
    foreach( $dirs as $dir ) {
        $file_dir = $dir . $class_name . '.php';
        if(file_exists($file_dir)) {
            require_once($file_dir);
        }
    }
}*/



/**
 * 遍历数据，对元数值进行转码
 * @param array $arr 需要转码的多数 [支持多元]
 * @param string $inCharset 当前编码[默认gb2312]
 * @param string $outCharset 转换后的编码[默认utf-8]
 * @return string
 */
function array_iconv($arr = array(),$inCharset='gb2312',$outCharset='utf-8'){
	foreach((array)$arr as $key => $val) {
		if (is_array($val) == true) {
			$arr[$key] = array_iconv($val,$inCharset, $outCharset);
		} else {
			$arr[$key] = iconv($inCharset,$outCharset.'//IGNORE',$val);
		}
	}
	return $arr;
}

/**
 * Stop from parsing any further PHP code
 *
 */
function tep_exit() {
	//tep_session_close();
	session_write_close();
	exit();
}


/**
 * 重定向到新的页面 Redirect to another page or site
 * @param $url
 */
function tep_redirect($url) {
	if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
		if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
			$url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
		}
	}
	if ($url == '/') $url =HTTP_SERVER;
	header('Location: ' . $url);

	tep_exit();
}

/**
 * Parse the data used in the html tags to ensure the tags will not break
 *
 * @param unknown_type $data
 * @param unknown_type $parse
 * @return unknown
 */
function tep_parse_input_field_data($data, $parse) {
	return strtr(trim($data), $parse);
}

function tep_output_string($string, $translate = false, $protected = false) {
	if ($protected == true) {
		return tep_htmlspecialchars($string);
	} else {
		if ($translate == false) {
			return tep_parse_input_field_data($string, array('"' => '&quot;'));
		} else {
			return tep_parse_input_field_data($string, $translate);
		}
	}
}

function tep_output_string_protected($string) {
	return tep_output_string($string, false, true);
}

function tep_sanitize_string($string) {
	$string = ereg_replace(' +', ' ', trim($string));

	return preg_replace("/[<>]/", '_', $string);
}

/**
 * Return a random row from a database query
 *
 * @param unknown_type $query
 * @return unknown
 */
function tep_random_select($query) {
	$random_product = '';
	$random_query = tep_db_query($query);
	$num_rows = tep_db_num_rows($random_query);
	if ($num_rows > 0) {
		$random_row = tep_rand(0, ($num_rows - 1));
		tep_db_data_seek($random_query, $random_row);
		$random_product = tep_db_fetch_array($random_query);
	}

	return $random_product;
}

/**
 * 取得产品名称
 * @param $product_id 产品ID号
 * @param $language
 * @param $verification 如果此开关为true则验证产品状态
 */
function tep_get_products_name($product_id, $language = '', $verification=false) {
	global $languages_id;
	if (empty($language)) $language = $languages_id;
	if($verification==true){
		$product_query = tep_db_query("select pd.products_name from " .TABLE_PRODUCTS." p, ". TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$language . "' AND p.products_status=1 AND pd.products_id=p.products_id ");

	}else{

		$product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
	}
	$product = tep_db_fetch_array($product_query);

	return $product['products_name'];
}

/**
 * 取得产品特价价格
 * @param $product_id 产品ID号
 */
function tep_get_products_special_price($product_id) {
	$exchangecurrency = tep_get_tour_agency_operate_currency($product_id);
	$product_query = tep_db_query("select products_price, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
	if (tep_db_num_rows($product_query)) {
		$product = tep_db_fetch_array($product_query);
		//amit modified to make sure price on usd
		if($exchangecurrency != 'USD' && $exchangecurrency != ''){
			$product['products_price'] = tep_get_tour_price_in_usd($product['products_price'],$exchangecurrency);
		}
		//amit modified to make sure price on usd
		$product_price = $product['products_price'];
	} else {
		return false;
	}

	$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status = '1'");
	if (tep_db_num_rows($specials_query)) {
		$special = tep_db_fetch_array($specials_query);
		//amit modified to make sure price on usd
		if($exchangecurrency != 'USD' && $exchangecurrency != ''){
			$special['specials_new_products_price'] = tep_get_tour_price_in_usd($special['specials_new_products_price'],$exchangecurrency);
		}
		//amit modified to make sure price on usd
		$special_price = $special['specials_new_products_price'];
	} else {
		$special_price = false;
	}

	if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
		return $special_price;
	}

	$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_id . "'");
	$product_to_categories = tep_db_fetch_array($product_to_categories_query);
	$category = $product_to_categories['categories_id'];

	$sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where sale_categories_all like '%," . $category . ",%' and sale_status = '1' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");
	if (tep_db_num_rows($sale_query)) {
		$sale = tep_db_fetch_array($sale_query);
	} else {
		return $special_price;
	}

	if (!$special_price) {
		$tmp_special_price = $product_price;
	} else {
		$tmp_special_price = $special_price;
	}

	switch ($sale['sale_deduction_type']) {
		case 0:
			$sale_product_price = $product_price - $sale['sale_deduction_value'];
			$sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
			break;
		case 1:
			$sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
			$sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
			break;
		case 2:
			$sale_product_price = $sale['sale_deduction_value'];
			$sale_special_price = $sale['sale_deduction_value'];
			break;
		default:
			return $special_price;
	}

	if ($sale_product_price < 0) {
		$sale_product_price = 0;
	}

	if ($sale_special_price < 0) {
		$sale_special_price = 0;
	}

	if (!$special_price) {
		return number_format($sale_product_price, 4, '.', '');
	} else {
		switch($sale['sale_specials_condition']){
			case 0:
				return number_format($sale_product_price, 4, '.', '');
				break;
			case 1:
				return number_format($special_price, 4, '.', '');
				break;
			case 2:
				return number_format($sale_special_price, 4, '.', '');
				break;
			default:
				return number_format($special_price, 4, '.', '');
		}
	}
}
/**
   * 输出js字符串 默认用双引号括起来
   * @param string $str 要处理的字符串
   * @param boolean $sQuote false 是否使用单引号括该字符串
   * @author vincent
   * @modify by vincent at 2011-5-6 下午01:58:18
   */
function format_for_js ($str,$sQuote = false) {
	$str= str_replace("\\","\\\\",$str);
	$str= $sQuote ? str_replace("'","\\'",$str):str_replace('"','\\"',$str);
	$str= str_replace("\r\n","\\r\\n",$str);
	return str_replace("\n","\\r\\n",$str);
}

/**
 * 检查产品是否为特价团
 * @param $product_id 产品ID号
 * TABLES: specials
 */

function check_is_specials($products_id,$check_expires=false, $check_status=false){
	$products_id = tep_get_prid($products_id);
	if(!(int)$products_id){ return false;}
	$where = ' WHERE products_id="'.$products_id.'" AND specials_type<1 ';
	if($check_expires!=false){ $where.= ' AND (expires_date > NOW() || expires_date is null || expires_date ="0000-00-00 00:00:00") ';}
	if($check_status!=false){ $where.= ' AND status="1" ';}
	$sql = tep_db_query('SELECT products_id FROM `specials`  '.$where);
	$row = tep_db_fetch_array($sql);
	return (int)$row['products_id'];
}

/**
 * 检查产品是否为买二送一团
 * @param $products_id 产品ID号
 * @param $pep_num 参团人数
 * @param $departure_date 出发日期
 * TABLES: products
 */
function check_buy_two_get_one($products_id,$pep_num='3',$departure_date=""){
	$products_id = tep_get_prid($products_id);
	if(!(int)$products_id){ return false; }

	//一、先要在产品设置为买二送一，以及使用买二送一价
	$check_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "' AND products_class_id=4 AND use_buy_two_get_one_price =1 ");
	$check_values = tep_db_fetch_array($check_query);
	if((int)$check_values['products_id'] == 0){
		return false;
	}
	//二、再在买二送一表中根据日期、人数等，判断是否是真正的买二送一产品
	if($departure_date==""){ $departure_date = date('Y-m-d');}
	if(tep_not_null($departure_date)){
		$departure_date = substr($departure_date,0,10);
		if(strlen($departure_date)!=10){
			echo "Date erroe";
		}
	}

	if($pep_num<3 || $pep_num > 4){	//对3人单和4人间有效
		return false;
	}
	if($pep_num==3){
		$one_or_two_option_where = ' AND (one_or_two_option="1" || one_or_two_option="0" ) ';
	}
	if($pep_num==4){
		$one_or_two_option_where = ' AND (one_or_two_option="2" || one_or_two_option="1" || one_or_two_option="0"  ) ';
	}
	$sql_str = ('SELECT * FROM `products_buy_two_get_one` WHERE products_id="'.(int)$check_values['products_id'].'" AND `status`="1" AND (products_departure_date_begin <= "'.$departure_date.' 00:00:00" || products_departure_date_begin="0000-00-00 00:00:00" || products_departure_date_begin="") AND (products_departure_date_end >="'.$departure_date.' 23:59:59" || products_departure_date_end="0000-00-00 00:00:00" || products_departure_date_end="" ) '.$one_or_two_option_where.' Order By one_or_two_option DESC ');
	$sql = tep_db_query($sql_str);
	//echo $sql_str;
	$row = tep_db_fetch_array($sql);
	if((int)$row['products_buy_two_get_one_id']){
		$tmp_array = array();
		$d = 0;
		do{
			$tmp_array[$d] = array('option_value' => $row['one_or_two_option'],'excluding_dates'=> $row['excluding_dates']);
			$d++;
		}while($row = tep_db_fetch_array($sql));

		for($i=0; $i<$d; $i++){
			//判断4人间应该按买二送一还是买二送二计算
			if($pep_num=='4' && ($tmp_array[$i]['option_value']=='0' || $tmp_array[$i]['option_value']=='2') && !strstr( $tmp_array[$i]['excluding_dates'],$departure_date)){
				//echo 'get 2';
				return '3';	//如果按买二送二计就返回3

			}elseif($pep_num=='4' && $tmp_array[$i]['option_value']=='1' && !strstr( $tmp_array[$i]['excluding_dates'],$departure_date)){
				//echo 'get 1';
				return '2';	//如果按买二送一计就返回2
			}

			if($pep_num=='3' && ($tmp_array[$i]['option_value']=='0' || $tmp_array[$i]['option_value']=='1') && !strstr( $tmp_array[$i]['excluding_dates'],$departure_date)){
				//echo 'get 1';
				return '2';	//如果总人数是3就返回2

			}

		}

	}

	return false;

}
//取得不能为买二送一的出发日期数组,已报废
/*
function get_no_buy_two_dates($chk_pid){
$date_check = false;
$chk_pid = (int)$chk_pid;
//$p_id = "370,104,232,382,110,189";	//在节假日不优惠的团
$p_id = "232,189";	//在节假日不优惠的团
$p_ids = explode(',',$p_id);
for($i=0; $i<count($p_ids); $i++){
if($chk_pid==$p_ids[$i]){
$date_check = true;
break;
}
}
$dates = array();
if($date_check==true){
$dates[0] = '01-01';
$dates[1] = '07-04';
$dates[2] = '09-05';
$dates[3] = '11-26';
$dates[4] = '12-24';
$dates[5] = '12-25';
}
return $dates;
}*/

/**
 * Return a product's surcharge	//取得买二送1产品附加费，免费的人数*附加费
 * TABLES: products
 * @param unknown_type $products_id
 * @return unknown
 */
function get_products_surcharge($products_id){
	$products_id = tep_get_prid($products_id);
	$surcharge_query = tep_db_query("select products_surcharge from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
	$surcharge_values = tep_db_fetch_array($surcharge_query);

	return $surcharge_values['products_surcharge'];
}

/**
 * Return a product's stock
 * TABLES: products
 *
 * @param unknown_type $products_id
 * @return unknown
 */
function tep_get_products_stock($products_id) {
	$products_id = tep_get_prid($products_id);
	$stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
	$stock_values = tep_db_fetch_array($stock_query);

	return $stock_values['products_quantity'];
}

/**
 * Check if the required stock is available
 * <p>If insufficent stock is available return an out of stock message</p>
 * @param unknown_type $products_id
 * @param unknown_type $products_quantity
 * @return unknown
 */
function tep_check_stock($products_id, $products_quantity) {
	$stock_left = tep_get_products_stock($products_id) - $products_quantity;
	$out_of_stock = '';

	if ($stock_left < 0) {
		$out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
	}

	return $out_of_stock;
}

////
// Break a word in a string if it is longer than a specified length ($len)
function tep_break_string($string, $len, $break_char = '-') {
	$l = 0;
	$output = '';
	for ($i=0, $n=strlen($string); $i<$n; $i++) {
		$char = substr($string, $i, 1);
		if ($char != ' ') {
			$l++;
		} else {
			$l = 0;
		}
		if ($l > $len) {
			$l = 1;
			$output .= $break_char;
		}
		$output .= $char;
	}

	return $output;
}

////
// Return all HTTP GET variables, except those passed as a parameter
function tep_get_all_get_params($exclude_array = '', $use_rawurlencode = true) {
	global $HTTP_GET_VARS;

	if (!is_array($exclude_array)) $exclude_array = array();

	$get_url = '';
	if (is_array($HTTP_GET_VARS) && (sizeof($HTTP_GET_VARS) > 0)) {
		reset($HTTP_GET_VARS);
		while (list($key, $value) = each($HTTP_GET_VARS)) {
			if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
				if(is_array($value)){
					foreach($value as $k=>$v){
						$get_url .= $key . '[]=' . rawurlencode($v).'&' ;
					}
				}else{
					if($use_rawurlencode==true){
						$get_url .= $key . '=' . rawurlencode(ajax_to_general_string(stripslashes($value))) . '&';
	
					}else{
						$get_url .= $key . '=' . ajax_to_general_string(stripslashes($value)) . '&';
					}
				}
			}
		}
	}

	//$get_url = rawurldecode($get_url);
	return $get_url;
}

////
// Returns an array with countries
// TABLES: countries
function tep_get_countries($countries_id = '', $with_iso_codes = false) {
	$countries_array = array();
	if (tep_not_null($countries_id)) {
		if ($with_iso_codes == true) {
			$countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
			$countries_values = tep_db_fetch_array($countries);
			$countries_array = array('countries_name' => $countries_values['countries_name'],
			'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
			'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
		} else {
			$countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
			$countries_values = tep_db_fetch_array($countries);
			$countries_array = array('countries_name' => $countries_values['countries_name']);
		}
	} else {
		$countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where countries_iso_code_2 != 'US'  && countries_iso_code_2 != 'CN' && countries_iso_code_2 != 'CA' && countries_iso_code_2 != 'HK'  && countries_iso_code_2 != 'TW'  order by countries_name ASC");
		while ($countries_values = tep_db_fetch_array($countries)) {
			$countries_array[] = array('countries_id' => $countries_values['countries_id'],
			'countries_name' => $countries_values['countries_name']);
		}
	}

	return $countries_array;
}

//tep_get_countries_tel_code
function tep_get_countries_tel_code($countries_id){
	if(!(int)$countries_id) return false;
	$country_sql = tep_db_query('SELECT countries_tel_code FROM `countries` WHERE countries_id ="'.(int)$countries_id.'" limit 1');
	$country_row = tep_db_fetch_array($country_sql);
	return $country_row['countries_tel_code'];
}
/**
 * 取得所有国家的电话区码
 * @return array 返回数组用于下拉菜单
 */
function tep_get_countries_tel_code_array(){
	$orderby = '223,44,38,96,206';	//美国,中国,加拿大,香港,台湾排序
	$orderby = explode(',',$orderby);
	krsort($orderby);
	$orderby = implode(',', $orderby);
	$country_sql = tep_db_query('SELECT countries_name, countries_tel_code FROM `countries` WHERE countries_tel_code!="" order by FIND_IN_SET(countries_id, "'.$orderby.'") DESC, countries_name ASC ');
	$data = array();
	while ($rows = tep_db_fetch_array($country_sql)) {
		$data[] = array('id'=>$rows['countries_tel_code'], 'text'=>$rows['countries_name']);
	}
	return $data;
}

////
// Alias function to tep_get_countries, which also returns the countries iso codes
function tep_get_countries_with_iso_codes($countries_id) {
	return tep_get_countries($countries_id, true);
}

////
// Generate a path to categories
function tep_get_path($current_category_id = '') {
	global $cPath_array;

	if (tep_not_null($current_category_id)) {
		$cp_size = sizeof($cPath_array);
		if ($cp_size == 0) {
			$cPath_new = $current_category_id;
		} else {
			$cPath_new = '';
			/*
			$last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
			$last_category = tep_db_fetch_array($last_category_query);*/
			$last_category = MCache::fetch_categories((int)$cPath_array[($cp_size-1)]);

			/*$current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
			$current_category = tep_db_fetch_array($current_category_query);*/
			$current_category = MCache::fetch_categories((int)$current_category_id);

			if ($last_category['parent_id'] == $current_category['parent_id']) {
				for ($i=0; $i<($cp_size-1); $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			} else {
				for ($i=0; $i<$cp_size; $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			}
			$cPath_new .= '_' . $current_category_id;

			if (substr($cPath_new, 0, 1) == '_') {
				$cPath_new = substr($cPath_new, 1);
			}
		}
	} else {
		$cPath_new = implode('_', $cPath_array);
	}

	return 'cPath=' . $cPath_new;
}


////
// Returns the clients browser
function tep_browser_detect($component) {
	global $HTTP_USER_AGENT;

	return stristr($HTTP_USER_AGENT, $component);
}

////
// Alias function to tep_get_countries()
function tep_get_country_name($country_id) {
	$country_array = tep_get_countries($country_id);

	return $country_array['countries_name'];
}

////
// Returns the zone (State/Province) name
// TABLES: zones
function tep_get_zone_name($country_id, $zone_id, $default_zone) {
	$zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
	if (tep_db_num_rows($zone_query)) {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_name'];
	} else {
		return $default_zone;
	}
}

////
// Returns the zone (State/Province) code
// TABLES: zones
function tep_get_zone_code($country_id, $zone_id, $default_zone) {
	$zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
	if (tep_db_num_rows($zone_query)) {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_code'];
	} else {
		return $default_zone;
	}
}

////
// Wrapper function for round()
function tep_round($number, $precision) {
	if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
		$number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

		if (substr($number, -1) >= 5) {
			if ($precision > 1) {
				$number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
			} elseif ($precision == 1) {
				$number = substr($number, 0, -1) + 0.1;
			} else {
				$number = substr($number, 0, -1) + 1;
			}
		} else {
			$number = substr($number, 0, -1);
		}
	}

	return $number;
}

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
	global $customer_zone_id, $customer_country_id;

	if ( ($country_id == -1) && ($zone_id == -1) ) {
		if (!tep_session_is_registered('customer_id')) {
			$country_id = STORE_COUNTRY;
			$zone_id = STORE_ZONE;
		} else {
			$country_id = $customer_country_id;
			$zone_id = $customer_zone_id;
		}
	}

	$tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
	if (tep_db_num_rows($tax_query)) {
		$tax_multiplier = 1.0;
		while ($tax = tep_db_fetch_array($tax_query)) {
			$tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
		}
		return ($tax_multiplier - 1.0) * 100;
	} else {
		return 0;
	}
}

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
function tep_get_tax_description($class_id, $country_id, $zone_id) {
	$tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
	if (tep_db_num_rows($tax_query)) {
		$tax_description = '';
		while ($tax = tep_db_fetch_array($tax_query)) {
			$tax_description .= $tax['tax_description'] . ' + ';
		}
		$tax_description = substr($tax_description, 0, -3);

		return $tax_description;
	} else {
		return TEXT_UNKNOWN_TAX_RATE;
	}
}

////
// Add tax to a products price
function tep_add_tax($price, $tax) {
	global $currencies;

	if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
		return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
	} else {
		return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
	}
}

// Calculates Tax rounding the result
function tep_calculate_tax($price, $tax) {
	global $currencies;

	return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
}

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
function tep_count_products_in_category($category_id, $include_inactive = false) {
	$products_count = 0;
	if ($include_inactive == true) {
		$products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
	} else {
		$products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
	}
	$products = tep_db_fetch_array($products_query);
	$products_count += $products['total'];
	/*
	$child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
	if (tep_db_num_rows($child_categories_query)) {
	while ($child_categories = tep_db_fetch_array($child_categories_query)) {
	$products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
	}
	}*/
	$child_categories_list = MCache::search_categories('parent_id', (int)$category_id,true);
	if (!empty($child_categories_list)) {
		foreach($child_categories_list as $child_categories){
			$products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
		}
	}

	return $products_count;
}

////
// Return true if the category has subcategories
// TABLES: categories
function tep_has_category_subcategories($category_id) {
	/*
	$child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
	$child_category = tep_db_fetch_array($child_category_query);*/
	$child_category = MCache::search_categories('parent_id', (int)$category_id);
	if (!empty($child_category)) {
		return true;
	} else {
		return false;
	}
}

////
// Returns the address_format_id for the given country
// TABLES: countries;
function tep_get_address_format_id($country_id) {
	$address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
	if (tep_db_num_rows($address_format_query)) {
		$address_format = tep_db_fetch_array($address_format_query);
		return $address_format['format_id'];
	} else {
		return '1';
	}
}

////
// Return a formatted address
// TABLES: address_format
function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
	$address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
	$address_format = tep_db_fetch_array($address_format_query);

	$company = tep_output_string_protected($address['company']);
	if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
		$firstname = tep_output_string_protected($address['firstname']);
		$lastname = tep_output_string_protected($address['lastname']);
	} elseif (isset($address['name']) && tep_not_null($address['name'])) {
		$firstname = tep_output_string_protected($address['name']);
		$lastname = '';
	} else {
		$firstname = '';
		$lastname = '';
	}
	$street = tep_output_string_protected($address['street_address']);
	$suburb = tep_output_string_protected($address['suburb']);
	$city = tep_output_string_protected($address['city']);
	$state = tep_output_string_protected($address['state']);
	if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
		$country = tep_get_country_name($address['country_id']);

		if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
			$state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
		}
	} elseif (isset($address['country']) && tep_not_null($address['country'])) {
		$country = tep_output_string_protected($address['country']);
	} else {
		$country = '';
	}
	$postcode = tep_output_string_protected($address['postcode']);
	$zip = $postcode;

	if ($html) {
		// HTML Mode
		$HR = '<hr>';
		$hr = '<hr>';
		if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
			$CR = '<br>';
			$cr = '<br>';
			$eoln = $cr;
		} else { // Use values supplied
			$CR = $eoln . $boln;
			$cr = $CR;
		}
	} else {
		// Text Mode
		$CR = $eoln;
		$cr = $CR;
		$HR = '----------------------------------------';
		$hr = '----------------------------------------';
	}

	$statecomma = '';
	$streets = $street;
	if ($suburb != '') $streets = $street . $cr . $suburb;
	if ($country == '') $country = tep_output_string_protected($address['country']);
	if ($state != '') $statecomma = $state . ', ';

	$fmt = $address_format['format'];
	eval("\$address = \"$fmt\";");

	if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
		$address = $company . $cr . $address;
	}
	//过滤掉地址中每行出现的,xxx的情况	
	$newAddArr = array();
	foreach( explode($cr,$address) as $line){
		$newAddArr[] = trim($line," \n\t\r\0\x0B,，/\\");
	}
	$address = implode($cr,$newAddArr);
	return $address;
}

////
// Return a formatted address
// TABLES: customers, address_book
function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
	$address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
	$address = tep_db_fetch_array($address_query);
	//amit fixed to don't show name on billing
	$address['firstname'] = '';
	$address['lastname'] = '';
	//amit added to fixed don't show name on billing
	$format_id = tep_get_address_format_id($address['country_id']);

	return tep_address_format($format_id, $address, $html, $boln, $eoln);
}

function tep_row_number_format($number) {
	if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

	return $number;
}

//取得所有下级目录
function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
	global $languages_id;


	if (!is_array($categories_array)) $categories_array = array();

	/*
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
	$categories_array[] = array('id' => $categories['categories_id'], 'text' => $indent . $categories['categories_name']);

	if ($categories['categories_id'] != $parent_id) {
	$categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
	}
	}*/

	$list =  MCache::search_categories('parent_id', $parent_id,true);
	foreach($list as $categories){
		$categories_array[] = array('id' => $categories['categories_id'], 'text' => $indent . $categories['categories_name']);
		if ($categories['categories_id'] != $parent_id) {
			$categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
		}
	}
	return  $categories_array;
}

function tep_get_manufacturers($manufacturers_array = '') {
	if (!is_array($manufacturers_array)) $manufacturers_array = array();

	$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
	while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
		$manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
	}

	return $manufacturers_array;
}

////
// Return all subcategory IDs
// TABLES: categories
// 取得所有下级目录
function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
	/*
	$subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
	while ($subcategories = tep_db_fetch_array($subcategories_query)) {
	$subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
	if ($subcategories['categories_id'] != $parent_id) {
	tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
	}
	}  */
	$subcategories_list = MCache::search_categories('parent_id', (int)$parent_id,true,'categories_id');

	foreach($subcategories_list as $subcategories){
		$subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
		if ($subcategories['categories_id'] != $parent_id) {
			tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
		}
	}
}

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
function tep_date_long($raw_date) {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;
	$raw_date = date('Y-m-d H:i:s',strtotime($raw_date));//vincent fixed //_-
	$year = (int)substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
}

function tep_date_long_review($raw_date, $datetype="I", $type="1") {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;
	/*
	$year = (int)substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	return strftime(DATE_FORMAT_LONG_REVIEW, mktime($hour,$minute,$second,$month,$day,$year));
	*/
	return db_to_html(chardate($raw_date,$datetype,$type));
}

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
function tep_date_short($raw_date) {
	if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

	$year = substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
		return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
	} else {
		return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
	}
}

////
// 取得加了内链的文章内容
/**
 * 对文字内容进行关键词加超连接
 * by lwkai modify 2012-07-24
 * @param string $text_content 需要加连接的文字内容
 * @param string $thesaurus_ids 关键词在数据表中对应的ID，多个请用逗号隔开
 * @param unknown_type $rep_limit 修改后不再需要，可不传
 * @return string
 */
function get_thesaurus_replace($text_content, $thesaurus_ids='',$rep_limit=1){

	$where_t_exc='';
	if(tep_not_null($thesaurus_ids)){
		$where_t_exc .= ' AND thesaurus_id in('.$thesaurus_ids.') ';
	}
	$inner_link_sql = tep_db_query('SELECT thesaurus_text FROM `keyword_thesaurus` WHERE use_inner_link=1 AND thesaurus_state=1 '.$where_t_exc.' ORDER BY thesaurus_text_length DESC, thesaurus_id DESC ');

	$key_replace_arr = array();
	while($inner_link_rows = tep_db_fetch_array($inner_link_sql)){
		$key_replace_arr[$inner_link_rows['thesaurus_text']] = '<a style="color:#223D6A; text-decoration: underline;" href="' . tep_href_link('advanced_search_result.php', 'w=' . rawurlencode(db_to_html($inner_link_rows['thesaurus_text']))) . '" target="_blank" >' . $inner_link_rows['thesaurus_text'] . '</a>';
	}
	$text_content = strtr($text_content,$key_replace_arr);
	return $text_content;
}


// 根据提供的文本内容取得关键词数组
/**
 * 从文字内容中查找是否有关键词，并返回找到的关键词数组
 * @param string $string 文字内容
 * @return boolean|multitype:Ambigous <>
 */
function get_thesaurus_string_array($string){
	if(!tep_not_null($string)) return false;
	$array = array();
	$inner_link_sql = tep_db_query('SELECT thesaurus_id,thesaurus_text FROM `keyword_thesaurus` WHERE use_inner_link=1 AND thesaurus_state=1 ORDER BY thesaurus_text_length DESC, thesaurus_id DESC ');
	while($inner_link_rows = tep_db_fetch_array($inner_link_sql)){
		if(preg_match('/'.preg_quote($inner_link_rows['thesaurus_text'],'/').'/',$string)){
			$array[] = $inner_link_rows['thesaurus_text'];
		}
	}
	return $array;
}

////
// Parse search string into indivual objects
function tep_parse_search_string($search_str = '', &$objects) {
	$search_str = trim(strtolower($search_str));

	// Break up $search_str on whitespace; quoted string will be reconstructed later
	$pieces = split('[[:space:]]+', $search_str);
	$pieces = array_unique($pieces);

	//插入词库分词关键字
	for ($t=0; $t<count($pieces); $t++) {
		if(tep_not_null($pieces[$t])){
			$inner_link_sql = tep_db_query('SELECT thesaurus_id,thesaurus_text FROM `keyword_thesaurus` WHERE use_search=1 AND thesaurus_state=1 ORDER BY thesaurus_text_length DESC, thesaurus_id DESC ');
			while($inner_link_rows = tep_db_fetch_array($inner_link_sql)){
				if(@preg_match('/'.preg_quote($inner_link_rows['thesaurus_text']).'/',html_to_db($pieces[$t]) ) && strlen($inner_link_rows['thesaurus_text'])< strlen($pieces[$t]) ){
					$pieces[] = db_to_html($inner_link_rows['thesaurus_text']);
				}
			}
		}
	}

	$objects = array();
	$tmpstring = '';
	$flag = '';

	for ($k=0; $k<count($pieces); $k++) {
		while (substr($pieces[$k], 0, 1) == '(') {
			$objects[] = '(';
			if (strlen($pieces[$k]) > 1) {
				$pieces[$k] = substr($pieces[$k], 1);
			} else {
				$pieces[$k] = '';
			}
		}

		$post_objects = array();

		while (substr($pieces[$k], -1) == ')')  {
			$post_objects[] = ')';
			if (strlen($pieces[$k]) > 1) {
				$pieces[$k] = substr($pieces[$k], 0, -1);
			} else {
				$pieces[$k] = '';
			}
		}

		// Check individual words

		if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
			$objects[] = trim($pieces[$k]);

			for ($j=0; $j<count($post_objects); $j++) {
				$objects[] = $post_objects[$j];
			}
		} else {
			/* This means that the $piece is either the beginning or the end of a string.
			So, we'll slurp up the $pieces and stick them together until we get to the
			end of the string or run out of pieces.
			*/

			// Add this word to the $tmpstring, starting the $tmpstring
			$tmpstring = trim(ereg_replace('"', ' ', $pieces[$k]));

			// Check for one possible exception to the rule. That there is a single quoted word.
			if (substr($pieces[$k], -1 ) == '"') {
				// Turn the flag off for future iterations
				$flag = 'off';

				$objects[] = trim($pieces[$k]);

				for ($j=0; $j<count($post_objects); $j++) {
					$objects[] = $post_objects[$j];
				}

				unset($tmpstring);

				// Stop looking for the end of the string and move onto the next word.
				continue;
			}

			// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
			$flag = 'on';

			// Move on to the next word
			$k++;

			// Keep reading until the end of the string as long as the $flag is on

			while ( ($flag == 'on') && ($k < count($pieces)) ) {
				while (substr($pieces[$k], -1) == ')') {
					$post_objects[] = ')';
					if (strlen($pieces[$k]) > 1) {
						$pieces[$k] = substr($pieces[$k], 0, -1);
					} else {
						$pieces[$k] = '';
					}
				}

				// If the word doesn't end in double quotes, append it to the $tmpstring.
				if (substr($pieces[$k], -1) != '"') {
					// Tack this word onto the current string entity
					$tmpstring .= ' ' . $pieces[$k];

					// Move on to the next word
					$k++;
					continue;
				} else {
					/* If the $piece ends in double quotes, strip the double quotes, tack the
					$piece onto the tail of the string, push the $tmpstring onto the $haves,
					kill the $tmpstring, turn the $flag "off", and return.
					*/
					$tmpstring .= ' ' . trim(ereg_replace('"', ' ', $pieces[$k]));

					// Push the $tmpstring onto the array of stuff to search for
					$objects[] = trim($tmpstring);

					for ($j=0; $j<count($post_objects); $j++) {
						$objects[] = $post_objects[$j];
					}

					unset($tmpstring);

					// Turn off the flag to exit the loop
					$flag = 'off';
				}
			}
		}
	}

	// add default logical operators if needed
	$temp = array();
	for($i=0; $i<(count($objects)-1); $i++) {
		$temp[] = $objects[$i];
		if ( ($objects[$i] != 'and') &&
		($objects[$i] != 'or') &&
		($objects[$i] != '(') &&
		($objects[$i+1] != 'and') &&
		($objects[$i+1] != 'or') &&
		($objects[$i+1] != ')') ) {
			$temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
		}
	}
	$temp[] = $objects[$i];
	$objects = $temp;

	$keyword_count = 0;
	$operator_count = 0;
	$balance = 0;
	for($i=0; $i<count($objects); $i++) {
		if ($objects[$i] == '(') $balance --;
		if ($objects[$i] == ')') $balance ++;
		if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
			$operator_count ++;
		} elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
			$keyword_count ++;
		}
	}

	if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
		return true;
	} else {
		return false;
	}
}

////
// Check date
function tep_checkdate($date_to_check, $format_string, &$date_array) {
	$separator_idx = -1;

	$separators = array('-', ' ', '/', '.');
	$month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
	$no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	$format_string = strtolower($format_string);

	if (strlen($date_to_check) != strlen($format_string)) {
		return false;
	}

	$size = sizeof($separators);
	for ($i=0; $i<$size; $i++) {
		$pos_separator = strpos($date_to_check, $separators[$i]);
		if ($pos_separator != false) {
			$date_separator_idx = $i;
			break;
		}
	}

	for ($i=0; $i<$size; $i++) {
		$pos_separator = strpos($format_string, $separators[$i]);
		if ($pos_separator != false) {
			$format_separator_idx = $i;
			break;
		}
	}

	if ($date_separator_idx != $format_separator_idx) {
		return false;
	}

	if ($date_separator_idx != -1) {
		$format_string_array = explode( $separators[$date_separator_idx], $format_string );
		if (sizeof($format_string_array) != 3) {
			return false;
		}

		$date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
		if (sizeof($date_to_check_array) != 3) {
			return false;
		}

		$size = sizeof($format_string_array);
		for ($i=0; $i<$size; $i++) {
			if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
			if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
			if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
		}
	} else {
		if (strlen($format_string) == 8 || strlen($format_string) == 9) {
			$pos_month = strpos($format_string, 'mmm');
			if ($pos_month != false) {
				$month = substr( $date_to_check, $pos_month, 3 );
				$size = sizeof($month_abbr);
				for ($i=0; $i<$size; $i++) {
					if ($month == $month_abbr[$i]) {
						$month = $i;
						break;
					}
				}
			} else {
				$month = substr($date_to_check, strpos($format_string, 'mm'), 2);
			}
		} else {
			return false;
		}

		$day = substr($date_to_check, strpos($format_string, 'dd'), 2);
		$year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
	}

	if (strlen($year) != 4) {
		return false;
	}

	if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
		return false;
	}

	if ($month > 12 || $month < 1) {
		return false;
	}

	if ($day < 1) {
		return false;
	}

	if (tep_is_leap_year($year)) {
		$no_of_days[1] = 29;
	}

	if ($day > $no_of_days[$month - 1]) {
		return false;
	}

	$date_array = array($year, $month, $day);

	return true;
}

////
// Check if year is a leap year
function tep_is_leap_year($year) {
	if ($year % 100 == 0) {
		if ($year % 400 == 0) return true;
	} else {
		if (($year % 4) == 0) return true;
	}

	return false;
}

////
// Return table heading with sorting capabilities
function tep_create_sort_heading($sortby, $colnum, $heading) {
	global $PHP_SELF;

	$sort_prefix = '';
	$sort_suffix = '';

	if ($sortby) {
		$sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
		$sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
	}

	return $sort_prefix . $heading . $sort_suffix;
}

//bbs travel 取得当前结伴同游回帖的所有引用父贴
function get_all_parent_for_quote($parent_id){
	if((int)$parent_id){
		$sql = tep_db_query('SELECT * FROM `travel_companion_reply` WHERE t_c_reply_id="'.(int)$parent_id.'" ');
		$row =  tep_db_fetch_array($sql);
	}

	if((int)$row['t_c_reply_id']){
		$string .= '<div class="yingyong">';
		$string .= '<b>[引用]'.tep_db_output($row['customers_name']).' 在 '.tep_db_output($row['last_time']).'的帖子:</b><br />';
	}

	if((int)$row['parent_id'] && (int)$row['parent_type'] && $row['parent_id']!=$row['t_c_reply_id'] ){	//parent_type==1 是引用
		$string .= get_all_parent_for_quote((int)$row['parent_id']);
	}

	if((int)$row['t_c_reply_id']){
		$string .= tep_db_output($row['t_c_reply_content']);
		$string .= '</div>';
	}
	return $string;
}


////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
// 取得所有上级目录id
/*function tep_get_parent_categories(&$categories, $categories_id) {
$parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
if ($parent_categories['parent_id'] == 0) return true;
$categories[sizeof($categories)] = $parent_categories['parent_id'];
if ($parent_categories['parent_id'] != $categories_id) {
tep_get_parent_categories($categories, $parent_categories['parent_id']);
}
}
}*/
/**
 * 取得所有上级目录id-该函数使用缓存
 * @param unknown_type $categories
 * @param unknown_type $categories_id
 */
function tep_get_parent_categories(&$categories, $categories_id){
	$parent_categories = MCache::fetch_categories($categories_id,'parent_id','parent_id');
	if ($parent_categories['parent_id'] == 0) return true;
	$categories[sizeof($categories)] = $parent_categories['parent_id'];
	if ($parent_categories['parent_id'] != $categories_id)
	tep_get_parent_categories($categories, $parent_categories['parent_id']);
}

////
// Construct a category path to the product
// TABLES: products_to_categories
function tep_get_product_path($products_id) {
	$cPath = '';

	$category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
	if (tep_db_num_rows($category_query)) {
		$category = tep_db_fetch_array($category_query);

		$categories = array();
		tep_get_parent_categories($categories, $category['categories_id']);

		$categories = array_reverse($categories);

		$cPath = implode('_', $categories);

		if (tep_not_null($cPath)) $cPath .= '_';
		$cPath .= $category['categories_id'];
	}

	return $cPath;
}

////
// Return a product ID with attributes
/**
 * 生成一个包含$prid 和$params 数组的字符串
 * ($prid包含 { 将将不会进行操作)
 * 格式为 $prid{params_key}params_value{params_key}params_value ....
 * comment by vincent 20110831
 * @param mix $prid
 * @param array $params
 */
function tep_get_uprid($prid, $params) {
	$uprid = $prid;
	if ( (is_array($params)) && (!strstr($prid, '{')) ) {
		while (list($option, $value) = each($params)) {
			$uprid = $uprid . '{' . $option . '}' . $value;
		}
	}

	return $uprid;
}

////
// Return a product ID from a product ID with attributes
function tep_get_prid($uprid) {
	$pieces = explode('{', $uprid);

	return $pieces[0];
}

////
// Return a customer greeting
function tep_customer_greeting() {
	global $customer_id, $customer_first_name;

	if (tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {
		$greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
	} else {
		$greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
	}

	return $greeting_string;
}

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $send_type =EMAIL_USE_HTML , $email_charset=CHARSET ) {
	global $mail_isNoReplay;

	//Howard added for add test email notice for qa sites start
	if(IS_LIVE_SITES!=true){
		$email_subject = "Test Email - ".$email_subject;
	}
	//Howard added for add test email notice for qa sites end

	if (SEND_EMAILS != 'true') return false;
	
	$email_lang = 'gbk';
	$to_name = iconv($email_charset,$email_lang.'//IGNORE', $to_name);
	$email_subject = iconv($email_charset,$email_lang.'//IGNORE', $email_subject);
	$email_text = nl2br(iconv($email_charset,$email_lang.'//IGNORE', $email_text));
	//$from_email_name = iconv($email_charset,$email_lang.'//IGNORE', $from_email_name).'<service@usitrip.com>';
	$from_email_name = iconv($email_charset,$email_lang.'//IGNORE', $from_email_name).' ';
	//1. 给客人的所有邮件都用automail@usitrip.com, 而且不能回复的， 客人若要实在回复到此邮件，我们事先设置好转移到service@usitrip.com， 请sofia设置。
	//2. 发邮件给供应商时用order@usitrip.com  收供应商回信也用此邮箱  登录密码: KLda$071233Order412$
	//3. 推广邮件用newsletter@usitrip.com
	if($from_email_address!='order@usitrip.com' && stripos($from_email_address,'usitrip.com')!==false && stripos($from_email_address,'newsletter')===false){
		$from_email_address = 'automail@usitrip.com';
		$from_email_name = iconv('gb2312',$email_lang.'//IGNORE', '走四方'." ").' ';//'usiTrip';
	}
	
	$use_mail_address = $from_email_address;
	
	if (EMAIL_TRANSPORT == 'smtp' || strpos($to_email_address,'@usitrip.com')!==false) {	//如果收件人邮箱是*@usitrip.com邮箱或EMAIL_TRANSPORT==smtp发送就用stmp方式发送
		require_once(DIR_FS_CATALOG.'phpmailer/send_mail_funtoin.php');
		$send_state = smtp_mail($to_email_address, $to_name, $email_subject, $email_text, $email_lang, $from_email_name,"","",$use_mail_address);
		return true;
	}else{
		// Instantiate a new mail object
		//$message = new email(array('X-Mailer: osCommerce Mailer'));
		$message = new email(array('X-Mailer: usitrip [version 1.73]'));
		
		// Build the text version
		$text = strip_tags($email_text);
		if ($send_type == 'true') {
			$email_text = preg_replace('/[[:space:]]+/', ' ', $email_text);
			if(preg_match('/^auto/', $from_email_address)||preg_match('/^order/', $from_email_address)||preg_match('/^jifen/', $from_email_address)||preg_match('/^newsletter/', $from_email_address) ){
				$email_text.= iconv('gb2312',$email_lang.'//IGNORE','<div style="width:100%; display:block; clear:both; line-height:25px;"><b>此邮件由系统自动发出，请勿直接回复。</b></div>');
			}
			$message->add_html($email_text);
		} else {
			$message->add_text($text);
		}
		// Send message
		$message->build_message();
		if(strpos($to_email_address,',')!==false){	//发多人
			$_to_email_address = explode(',',$to_email_address);
			$_to_name = explode(',',$to_name);
			foreach($_to_email_address as $key => $to_address){
				$message->send($_to_name[$key], $to_address, $from_email_name, $from_email_address, $email_subject, '', $email_lang );
			}
		}else{	//发单封
			return $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject, '', $email_lang );	//$email_charset
		}
	}
}

////
// Check if product has attributes
function tep_has_product_attributes($products_id) {
	$attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
	$attributes = tep_db_fetch_array($attributes_query);

	if ($attributes['count'] > 0) {
		return true;
	} else {
		return false;
	}
}
/**
   * 获取订单包含的产品快照信息（不含子订单行程）
   * @param int $order_id 订单编号
   * @author vincent
   * @modify by vincent at 2011-5-11 下午03:08:20
   */
function tep_get_products_by_order($order_id){
	$query = "SELECT * FROM ".TABLE_ORDERS_PRODUCTS.' WHERE parent_orders_products_id<1 and orders_id = '.intval($order_id);
	$q = tep_db_query($query);
	$rows = array();
	while($row = tep_db_fetch_array($q))$rows[] = $row ;
	return $rows;
}
////
// Get the number of times a word/character is present in a string
function tep_word_count($string, $needle) {
	$temp_array = split($needle, $string);

	return sizeof($temp_array);
}

function tep_count_modules($modules = '') {
	$count = 0;

	if (empty($modules)) return $count;

	$modules_array = split(';', $modules);

	for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
		$class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

		if (is_object($GLOBALS[$class])) {
			if ($GLOBALS[$class]->enabled) {
				$count++;
			}
		}
	}

	return $count;
}

function tep_count_payment_modules() {
	return tep_count_modules(MODULE_PAYMENT_INSTALLED);
}

function tep_count_shipping_modules() {
	return tep_count_modules(MODULE_SHIPPING_INSTALLED);
}

function tep_create_random_value($length, $type = 'mixed') {
	if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

	$rand_value = '';
	while (strlen($rand_value) < $length) {
		if ($type == 'digits') {
			$char = tep_rand(0,9);
		} else {
			$char = chr(tep_rand(0,255));
		}
		if ($type == 'mixed') {
			if (eregi('^[a-z0-9]$', $char)) $rand_value .= $char;
		} elseif ($type == 'chars') {
			if (eregi('^[a-z]$', $char)) $rand_value .= $char;
		} elseif ($type == 'digits') {
			if (ereg('^[0-9]$', $char)) $rand_value .= $char;
		}
	}

	return $rand_value;
}

function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
	if (!is_array($exclude)) $exclude = array();

	$get_string = '';
	if (sizeof($array) > 0) {
		while (list($key, $value) = each($array)) {
			if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
				$get_string .= $key . $equals . $value . $separator;
			}
		}
		$remove_chars = strlen($separator);
		$get_string = substr($get_string, 0, -$remove_chars);
	}

	return $get_string;
}

/**
   * 判断变量是否为空
   * @param $value 它可以是string 或 array
   */
function tep_not_null($value) {
	if (is_array($value)) {
		if (sizeof($value) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
			return true;
		} else {
			return false;
		}
	}
}

////
// Output the tax percentage with optional padded decimals
function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
	if (strpos($value, '.')) {
		$loop = true;
		while ($loop) {
			if (substr($value, -1) == '0') {
				$value = substr($value, 0, -1);
			} else {
				$loop = false;
				if (substr($value, -1) == '.') {
					$value = substr($value, 0, -1);
				}
			}
		}
	}

	if ($padding > 0) {
		if ($decimal_pos = strpos($value, '.')) {
			$decimals = strlen(substr($value, ($decimal_pos+1)));
			for ($i=$decimals; $i<$padding; $i++) {
				$value .= '0';
			}
		} else {
			$value .= '.';
			for ($i=0; $i<$padding; $i++) {
				$value .= '0';
			}
		}
	}

	return $value;
}

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
function tep_currency_exists($code) {
	$code = tep_db_prepare_input($code);

	$currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
	if (tep_db_num_rows($currency_code)) {
		return $code;
	} else {
		return false;
	}
}

function tep_string_to_int($string) {
	return (int)$string;
}

////
// Parse and secure the cPath parameter values
function tep_parse_category_path($cPath) {
	// make sure the category IDs are integers
	$cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

	// make sure no duplicate category IDs exist which could lock the server in a loop
	$tmp_array = array();
	$n = sizeof($cPath_array);
	for ($i=0; $i<$n; $i++) {
		if (!in_array($cPath_array[$i], $tmp_array)) {
			$tmp_array[] = $cPath_array[$i];
		}
	}
	return $tmp_array;
}
//取得所有上级目录id
function tep_get_parent_category_ids_string($category_id, &$ids){
	tep_get_parent_categories($ids, $category_id);
}

//取得当前目录串,用_连接如24_189_456
function tep_get_category_patch($category_id){
	if(!(int)$category_id) return false;
	$array = array();
	tep_get_parent_categories($array, $category_id);
	$patch_string='';
	for($i=sizeof($array)-1; $i>=0; $i--){
		$patch_string.= $array[$i].'_';
	}
	$patch_string .= $category_id;
	return $patch_string;
}

//定义目的地指南目录数
function tep_parse_gd_category_path($DgPath) {
	$DgPath_array = array_map('tep_string_to_int', explode('_', $DgPath));
	$tmp_array = array();
	$n = sizeof($DgPath_array);
	for ($i=0; $i<$n; $i++) {
		if (!in_array($DgPath_array[$i], $tmp_array)) {
			$tmp_array[] = $DgPath_array[$i];
		}
	}

	return $tmp_array;
}
//取得当前目的地指南目录串,用_连接如24_189_456
function tep_get_gd_category_patch($category_id){
	if(!(int)$category_id) return false;
	$array = array();
	tep_get_parent_dg_categories($array, $category_id);
	$patch_string='';
	for($i=sizeof($array)-1; $i>=0; $i--){
		$patch_string.= $array[$i].'_';
	}
	$patch_string .= $category_id;
	return $patch_string;
}
//取得当前目的所有上级目录（目的地指南）
function tep_get_parent_dg_categories(&$categories, $dg_categories_id) {
	$parent_categories_query = tep_db_query("select parent_id from destination_guide_categories where dg_categories_id = '" . (int)$dg_categories_id . "'");
	while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
		if ($parent_categories['parent_id'] == 0) return true;
		$categories[sizeof($categories)] = $parent_categories['parent_id'];
		if ($parent_categories['parent_id'] != $categories_id) {
			tep_get_parent_dg_categories($categories, $parent_categories['parent_id']);
		}
	}
}
//取得所有下级目录，返回的结果是数组（目的地指南）
function tep_get_dg_categories($categories_array = '', $parent_id = '0', $indent = '') {

	if (!is_array($categories_array)) $categories_array = array();

	$categories_query = tep_db_query("select dg_categories_id, dg_categories_name from destination_guide_categories where parent_id = '" . (int)$parent_id . "' order by sort_order, dg_categories_id ");
	while ($categories = tep_db_fetch_array($categories_query)) {
		$categories_array[] = array('id' => $categories['dg_categories_id'],
		'text' => $indent . $categories['dg_categories_name']);

		if ($categories['dg_categories_id'] != $parent_id) {
			$categories_array = tep_get_dg_categories($categories_array, $categories['dg_categories_id'], $indent . '&nbsp;&nbsp;');
		}
	}

	return $categories_array;
}
//取得所有下级目录的id,返回的结果是一个ids数组（目的地指南）
function tep_get_child_dg_categories_ids_string(&$ids_array, $dg_categories_id){
	if (!is_array($ids_array)) $ids_array = array();
	if(!(int)$dg_categories_id){ return false;}

	$categories_query = tep_db_query("select dg_categories_id from destination_guide_categories where parent_id = '" .(int)$dg_categories_id . "' order by sort_order, dg_categories_id ");
	while($categories = tep_db_fetch_array($categories_query)){
		$ids_array[]= $categories['dg_categories_id'];
		if($categories['dg_categories_id'] != (int)$dg_categories_id){
			tep_get_child_dg_categories_ids_string($ids_array,$categories['dg_categories_id']);
		}
	}
	return $ids_array;
}

//判断某目录是否有热门景点（目的地指南），返回false或数组
function decide_hot_dg_categories_ids($dg_categories_id){
	$top_hot_sql = tep_db_query('SELECT hot_dg_categories_ids FROM `destination_guide_categories` WHERE dg_categories_id="'.(int)$dg_categories_id.'" ');
	$top_hot_row = tep_db_fetch_array($top_hot_sql);
	if(!tep_not_null($top_hot_row['hot_dg_categories_ids'])){ return false;}
	$hot_dg_categories_ids = explode(',',trim($top_hot_row['hot_dg_categories_ids']));
	return $hot_dg_categories_ids;
}

////
// Return a random value
function tep_rand($min = null, $max = null) {
	static $seeded;

	if (!isset($seeded)) {
		mt_srand((double)microtime()*1000000);
		$seeded = true;
	}

	if (isset($min) && isset($max)) {
		if ($min >= $max) {
			return $min;
		} else {
			return mt_rand($min, $max);
		}
	} else {
		return mt_rand();
	}
}

function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
	setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
}

function tep_get_ip_address() {
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} else {
			$ip = getenv('REMOTE_ADDR');
		}
	}

	return $ip;
}

/**
   * 取得某个客户的订单总数
   * @param $id
   * @param $check_session
   */
function tep_count_customer_orders($id = '', $check_session = true) {
	global $customer_id;

	if (is_numeric($id) == false) {
		if (tep_session_is_registered('customer_id')) {
			$id = $customer_id;
		} else {
			return 0;
		}
	}

	if ($check_session == true) {
		if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
			return 0;
		}
	}

	$orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
	$orders_check = tep_db_fetch_array($orders_check_query);

	return $orders_check['total'];
}
/**
   * 获取指定客户ID的客户所有已支付的订单总金额 美元
   */
function tep_paied_customer_orders($id = '' , $check_session = true){
	global $customer_id;
	if (is_numeric($id) == false) {
		if (tep_session_is_registered('customer_id')) {
			$id = $customer_id;
		} else {
			return 0;
		}
	}
	if ($check_session == true) {
		if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
			return 0;
		}
	}
	$orders_check_query = tep_db_query("select SUM(ot.value) as total from " . TABLE_ORDERS . " o , ".TABLE_ORDERS_TOTAL." ot  where o.orders_id = ot.orders_id
    AND o.customers_id = '" . (int)$id . "' AND class='ot_total' AND o.orders_status = 100006");
	$orders_check = tep_db_fetch_array($orders_check_query);
	return $orders_check['total'];
}

function tep_count_customer_address_book_entries($id = '', $check_session = true) {
	global $customer_id;

	if (is_numeric($id) == false) {
		if (tep_session_is_registered('customer_id')) {
			$id = $customer_id;
		} else {
			return 0;
		}
	}

	if ($check_session == true) {
		if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
			return 0;
		}
	}

	$addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
	$addresses = tep_db_fetch_array($addresses_query);

	return $addresses['total'];
}

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
function tep_convert_linefeeds($from, $to, $string) {
	if ((PHP_VERSION < "4.0.5") && is_array($from)) {
		return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
	} else {
		return str_replace($from, $to, $string);
	}
}

// BOF: WebMakers.com Added: Downloads Controller
////$inc_back_path_providers = (defined('BACK_PATH') ? BACK_PATH : '');
////require($inc_back_path_providers.DIR_WS_FUNCTIONS . 'downloads_controller.php');

////
// BOF: WebMakers.com Added: configuration key value lookup
  function tep_get_configuration_key_value($lookup) {
    $configuration_query_raw= tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='" . $lookup . "'");
    $configuration_query= tep_db_fetch_array($configuration_query_raw);
    $lookup_value= $configuration_query['configuration_value'];
    if ( !($lookup_value) ) {
      $lookup_value='<font color="FF0000">' . $lookup . '</font>';
    }
    return $lookup_value;
  }
// EOF: WebMakers.com Added: configuration key value lookup

// EOF: WebMakers.com Added: Downloads Controller
////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with tep_htmlspecialchars()
function tep_decode_specialchars($string){
	$string=str_replace('&gt;', '>', $string);
	$string=str_replace('&lt;', '<', $string);
	$string=str_replace('&#039;', "'", $string);
	$string=str_replace('&quot;', "\"", $string);
	$string=str_replace('&amp;', '&', $string);

	return $string;
}

////
// saved from old code
function tep_output_warning($warning) {
	new errorBox(array(array('text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning)));
}


/**
 * 取得当前目录名
 *
 * @param unknown_type $categories_id
 * @param unknown_type $lang
 * @return unknown
 */
function tep_get_categories_name($categories_id, $lang=1) {
	return tep_get_category_name($categories_id, $lang);
}

/**
 * 取得出发城市名称
 *
 * @param int $city_id 
 * @param boolean $check_status 检查是否是可做为出发地的城市,默认false
 * @return string
 */
function tep_get_city_name($city_id, $check_status = false){
	$where = " where city_id='".(int)$city_id."' ";
	if($check_status!=false){
		$where .= " AND departure_city_status = '1' ";
	}
	$departure_city_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . $where);
	$departure_city_row = tep_db_fetch_array($departure_city_query);
	return $departure_city_row['city'];
}

/**
 * 从景点或城市名称中取得城市或景点ID
 */
function tep_get_city_id($db_code_str){
	$where = " where city='".tep_db_input(tep_db_prepare_input((string)$db_code_str))."' ";	
	$sql = tep_db_query("select DISTINCT city_id from " . TABLE_TOUR_CITY . $where);
	$row = tep_db_fetch_array($sql);
	return $row['city_id'];
}

function tep_get_regions_name($regions_id) {

	$regions_query = tep_db_query("select c.regions_id, cd.regions_name from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where  c.regions_id = cd.regions_id and c.regions_id = '" . (int)$regions_id . "' order by sort_order, cd.regions_name");
	$regions = tep_db_fetch_array($regions_query);
	$regions_name = $regions['regions_name'];
	return $regions_name;
}
//amit added to get customers_email address start
function tep_get_customers_email($cust_id){
	$cust_query = "select customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cust_id) . "'";
	$res = tep_db_query($cust_query);
	if($customers_id = tep_db_fetch_array($res)) {
		$customers_email_address = $customers_id['customers_email_address'];
	}
	return trim($customers_email_address);
}
//amit added to get customers_email address end

// howard added form email get customers_id
function form_email_get_customers_id($email_address){
	if(!tep_not_null($email_address)){ return false; }
	$sql = tep_db_query('select customers_id from '.TABLE_CUSTOMERS.' where customers_email_address="'.tep_db_input($email_address).'" limit 1 ');
	$row = tep_db_fetch_array($sql);
	return (int)$row['customers_id'];
}
// howard added form email get customers_id end

/**
 * 取得客户姓名 优先显示中文名
 *
 * @param unknown_type $customers_id
 * @param unknown_type $need_not_null 如果此值大于0就同时 如果没有中文名时 同时 英文名，若无数据则显示邮箱号才对
 * @return unknown
 */
function tep_customers_name($customers_id, $need_not_null = 0) {
	$name = '';
	$customers = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	//return $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];
	if(tep_not_null($customers_values['customers_firstname'])){
		$name = $customers_values['customers_firstname'];
	}elseif($need_not_null>0){
		if(tep_not_null($customers_values['customers_lastname'])){
			$name = $customers_values['customers_lastname'];
		}else {
			$name = $customers_values['customers_email_address'];
		}
	}
	return $name;
}

//howard added get customers sex
function tep_customer_gender($customers_id){
	$customers = tep_db_query("select customers_gender from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);
	return $customers_values['customers_gender'];
}

//根据数字或字母值取得性别字符 get string for gender
function tep_get_gender_string($num_or_string, $show_type = 0){
	$num_or_string = strtolower($num_or_string);
	$man = '男';
	$woman = '女';
	if($show_type==1){ $man = '先生'; $woman = '女士';}

	if($num_or_string == 'm' || $num_or_string=='1'){ return $man; }
	if($num_or_string == 'f' || $num_or_string=='2'){ return $woman; }
	return false;
}

/**
 * 取得客户头像src
 *
 * @param unknown_type $customers_id
 * @param unknown_type $old_src
 * @return unknown
 */
function tep_customers_face($customers_id, $old_src){
	$query = tep_db_query("select customers_face from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "' limit 1 ");
	$row = tep_db_fetch_array($query);
	if(tep_not_null($row['customers_face'])){
		return 'images/face/'.$row['customers_face'];
	}
	return $old_src;
}

//howard added get customers sex end


//amit added to affilate cat link start
function tep_products_in_category($category_id, $include_inactive = false,$cPath) {

	if($cPath == $category_id){
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

//geting customer cellphoneno
function tep_customers_cellphone($customers_id) {
	$customers = tep_db_query("select customers_cellphone from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	return $customers_values['customers_cellphone'];
}

function tep_get_order_paymentmethod_orderid($order_id)
{

	$status_query = tep_db_query("select  payment_method from " . TABLE_ORDERS . " where orders_id = '".(int)$order_id."'");
	if($status = tep_db_fetch_array($status_query))
	return $status['payment_method'];
}

function tep_get_travel_companion_paymentmethod($orders_travel_companion_ids)
{

	$status_query = tep_db_query("select  payment from orders_travel_companion where orders_travel_companion_id in( ".$orders_travel_companion_ids.") Limit 1 ");
	if($status = tep_db_fetch_array($status_query))
	return $status['payment'];
}



function tep_get_category_name($categories_id, $language_id = 0, $substr_en=true){
	/*global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '". $categories_id . "' and language_id = '" . $language_id . "'");
	$category = tep_db_fetch_array($category_query);*/
	$category = MCache::fetch_categories($categories_id,'categories_name');
	if($substr_en==true){ return preg_replace('/ .+/','',$category['categories_name']);}
	return $category['categories_name'];
}


function tep_get_category_image_seo_name($categories_id, $language_id = 0){
	/*global $languages_id;
	if ($language_id == 0) $language_id = $languages_id;
	//$category_query = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '". $categories_id . "'");
	$category_query = tep_db_query("select cd.categories_name,c.categories_image,c.categories_urlname  from " . TABLE_CATEGORIES_DESCRIPTION . " as cd, " . TABLE_CATEGORIES . " as c where c.categories_id = cd.categories_id and c.categories_id = '". $categories_id . "' and cd.language_id = '" . $language_id . "'");

	$category = tep_db_fetch_array($category_query);*/

	$category= MCache::fetch_categories($categories_id,'categories_name,categories_image,categories_urlname');
	return $category;
}

//取得团号
function tep_get_products_model($product_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$product_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_model'];
}


function tep_format_weekday2another($weekday = 'mon'){
	$weekday = substr(strtolower($weekday),0,3);
	switch($weekday){
		case "mon":
			$cn_weekday = TEXT_WEEK_MON;
			break;
		case "tue":
			$cn_weekday = TEXT_WEEK_TUE;
			break;
		case "wed":
			$cn_weekday = TEXT_WEEK_WED;
			break;
		case "thu":
			$cn_weekday = TEXT_WEEK_THU;
			break;
		case "fri":
			$cn_weekday = TEXT_WEEK_FRI;
			break;
		case "sat":
			$cn_weekday = TEXT_WEEK_SAT;
			break;
		case "sun":
			$cn_weekday = TEXT_WEEK_SUN;
			break;
	}
	return $cn_weekday;
}

// replace of HtmlSpecialChars
function tep_htmlspecialchars($str){
	//return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
	return preg_replace("/&amp;/", "&", htmlspecialchars($str,ENT_QUOTES));
}

/**
 * 字串转ASCII 
 * @param string $str
 * @return string
 */
function str_ascii($str){
	for($i=0;$i<strlen($str);$i++){
		$ascii.= ord($str[$i])." ";
	}
	return trim($ascii);
}

//ASCII转字串
function asscii_str($asscii){
	$array=explode(" ",$asscii);
	foreach ($array as $value){
		$str.=chr($value);
	}
	return $str;
}

require_once(DIR_FS_CATALOG.'includes/functions/gb2312_zhi.php');
require_once(DIR_FS_CATALOG.'includes/functions/big5_zhi.php');

function db_to_html($str_array){	//$str_array的编码是简体的gb2312
	if(CHARSET=='gb2312'){ return $str_array;}
	if(is_string($str_array)){
		$gb = 'gb2312';
		$str_array = special_string_replace_for_gb2312($str_array);
		$str_array = iconv($gb,CHARSET.'//IGNORE',$str_array);
		//$str_array = special_string_replace_for_gb2312($str_array,'1');
	}elseif(is_array($str_array)){
		reset($str_array);
		while (list($key, $value) = each($str_array)) {
			$str_array[$key] = db_to_html($value);
		}
	}
	return $str_array;
}

/**
 * 把网页上提交过来的编码转换成gb2312的编码,
 * @param string $str_array
 * @return string
 */
function html_to_db($str_array){
	if(CHARSET=='gb2312'){ return $str_array;}

	$p=array("许\\","功\\","盖\\","谷\\","餐\\","泪\\","枯\\","娉\\");
	$r=array("许","功","盖","谷","餐","泪","枯","娉");

	if(is_string($str_array)){
		$str_array = special_string_replace_for_big5($str_array);
		$str_array =iconv(CHARSET,'gb2312'.'//IGNORE',$str_array);
		for($i=0; $i<count($p); $i++){
			$str_array = str_replace($p[$i],$r[$i],$str_array);
		}
		//$str_array = special_string_replace_for_big5($str_array,'1');
	}elseif(is_array($str_array)){
		foreach((array)$str_array as $key => $val){
			$val = special_string_replace_for_big5($val);
			$val = iconv(CHARSET,'gb2312'.'//IGNORE',$val);
			for($i=0; $i<count($p); $i++){
				$val = str_replace($p[$i],$r[$i],$val);
			}
			//$val = special_string_replace_for_big5($val,'1');

			$str_array[$key] = $val;
		}
	}
	return $str_array;
}

/**
 * 当结伴同游支付成功后通知成员客户
 * @author Howard
 */
function send_travel_pay_staus_mail($order_id){
	global $currencies;
	//howard add use session+ajax send email
	$e_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE  orders_id="'.(int)$order_id.'" Order By products_id ASC, orders_travel_companion_status ASC ');
	$e_rows = tep_db_fetch_array($e_sql);

	$to_email_address ='';
	$html_text = '<table border="1" cellspacing="0" cellpadding="5"><tr><td style="font-size:14px"><b>'.db_to_html('团号').'</b></td><td style="font-size:14px"><b>'.db_to_html('旅客帐号').'</b></td><td style="font-size:14px"><b>'.db_to_html('旅客姓名').'</b></td><td style="font-size:14px"><b>'.db_to_html('应付款').'</b></td><td style="font-size:14px"><b>'.db_to_html('状态').'</b></td></tr>';

	$n_pid='';

	$pay_done = true;
	do{
		if((int)$e_rows['customers_id']){
			$mail_string = tep_get_customers_email((int)$e_rows['customers_id']);
			if(!strstr($to_email_address,$mail_string)){
				$to_email_address .= $mail_string.',';
			}
		}
		if($e_rows['orders_travel_companion_status']<2){
			$pay_done = false;
		}

		$html_text .='<tr><td style="font-size:14px">';
		if($n_pid != $e_rows['products_id']){
			$html_text .= tep_get_products_model($e_rows['products_id']);
		}
		$html_text .='&nbsp;</td><td style="font-size:14px">'.tep_get_customers_email((int)$e_rows['customers_id']).'&nbsp;</td>';
		$html_text .='<td style="font-size:14px">'.db_to_html(tep_db_output($e_rows['guest_name'])).'</td>';
		$html_text .='<td style="font-size:14px">'.$currencies->format($e_rows['payables'], true, 'USD', 1).'</td>';
		$html_text .='<td style="font-size:14px">'.db_to_html(get_travel_companion_status($e_rows['orders_travel_companion_status'])).'</td>';
		$html_text .='</tr>';

		$n_pid = $e_rows['products_id'];

	}while($e_rows = tep_db_fetch_array($e_sql));

	$html_text .='</table>';
	$html_text = $html_text;

	$to_email_address = substr($to_email_address,0,strlen($to_email_address)-1);
	if(strlen($to_email_address)>1){
		
		$links = tep_href_link('orders_travel_companion_info.php','order_id='.(int)$order_id,'SSL');
		$links = str_replace('/admin/','/',$links);
		
		$to_email_address_arr = explode(',',$to_email_address);
		for($to_i = 0,$to_len = count($to_email_address_arr); $to_i < $to_len; $to_i ++) {
			$email_subject = db_to_html("走四方网结伴同游-");
			if ($pay_done == true) {
				$email_subject .= db_to_html("付款完毕") . " ";
			} else {
				$email_subject .= db_to_html("有人付款") . " ";
			}
			//付款完毕！订单号：".(int)$order_id."日期:".date('YmdHis')." ");
			$to_name = $to_email_address_arr[$to_i]; //$to_email_address;
			$to_email_address = $to_name;
			$email_text = db_to_html('尊敬的『' . $to_email_names[$to_i] . '』您好：')."\n\n";
			$email_text .= db_to_html('非常感谢您预订美国走四方(Usitrip.com)的旅游产品！') . "\n";
			$email_text .= db_to_html('您和『' . trim(str_replace(array($to_email_names[$to_i],',,'),array('',','),join(',',$to_email_names)),",") . '』的结伴同游订单');
			if ($pay_done == true) {
				$email_text .= db_to_html('所有成员已经付款完毕，我们正在为您处理订单事宜，请留意于近日查收电子参团凭证。走四方预祝您旅途愉快！');
			} else {
				$email_text .= db_to_html('部分成员已经付款完毕，如果您还未付款，请及时完成付款。走四方预祝您旅途愉快！');
			}
			$email_text .= "\n" . db_to_html("订单号：" . (int)$order_id . "\n");
			$email_text .= "\n" . db_to_html('订单详情：' . $links) . "（注：如果点击打不开链接，请复制该地址到浏览器地址栏打开。）\n";
			$email_text .= db_to_html('付款详情：')."\n";
			$email_text .= $html_text."\n";
		
			// 			if($pay_done==true){
			// 				$email_text .= db_to_html('<span style="color: #009900;	font-weight: bold;">恭喜！所有成员均已经付款完毕，我们正在为您处理订单事宜！</span>'."\n");
			// 			}else{
			// 				$email_text .= db_to_html("如果您还未付款，请尽快去付款，以免影响大家的行程！\n");
			// 			}
		
				
			// 			$email_text .= db_to_html("订单详细信息：").'<a href="'.$links.'" target="_blank">'.$links."</a>\n";
			$email_text .= db_to_html("此邮件为系统自动发出，请勿直接回复！\n\n");
		
			$email_text .= db_to_html(CONFORMATION_EMAIL_FOOTER)."\n\n";
		
		
			$from_email_name = STORE_OWNER;
			//$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
			$from_email_address = 'automail@usitrip.com';
			$var_num = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
			$_SESSION['need_send_email'][$var_num]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$var_num]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$var_num]['from_email_name'] = $from_email_name;
			$_SESSION['need_send_email'][$var_num]['from_email_address'] = $from_email_address;
			$_SESSION['need_send_email'][$var_num]['action_type'] = 'true';
			//tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
		}
		
// 		$email_subject = db_to_html("走四方网结伴同游通知！订单号：".(int)$order_id."日期:".date('YmdHis')." ");
// 		$to_name = $to_email_address;
// 		$email_text = db_to_html('Dear 尊敬的顾客您好，')."\n\n";
// 		$email_text .= db_to_html('您的结伴同游订单最新付款消息如下：')."\n";
// 		$email_text .= db_to_html('订单号：<strong>').(int)$order_id."</strong>\n";
// 		$email_text .= db_to_html('参团人员付款信息如下：')."\n";
// 		$email_text .= $html_text."\n";

// 		if($pay_done==true){
// 			$email_text .= db_to_html('<span style="color: #009900;	font-weight: bold;">恭喜！所有成员均已经付款完毕，我们正在为您处理订单事宜！</span>'."\n");
// 		}else{
// 			$email_text .= db_to_html("如果您还未付款，请尽快去付款，以免影响大家的行程！\n");
// 		}

// 		$links = tep_href_link('orders_travel_companion_info.php','order_id='.(int)$order_id,'SSL');
// 		$links = str_replace('/admin/','/',$links);
// 		$email_text .= db_to_html("订单详细信息：").'<a href="'.$links.'" target="_blank">'.$links."</a>\n";
// 		$email_text .= db_to_html("此邮件为系统自动发出，请勿直接回复！\n\n");

// 		$email_text .= db_to_html(CONFORMATION_EMAIL_FOOTER)."\n\n";


// 		$from_email_name = STORE_OWNER;
// 		//$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
// 		$from_email_address = 'automail@usitrip.com';

// 		$var_num = count($_SESSION['need_send_email']);
// 		$_SESSION['need_send_email'][$var_num]['to_name'] = $to_name;
// 		$_SESSION['need_send_email'][$var_num]['to_email_address'] = $to_email_address;
// 		$_SESSION['need_send_email'][$var_num]['email_subject'] = $email_subject;
// 		$_SESSION['need_send_email'][$var_num]['email_text'] = $email_text;
// 		$_SESSION['need_send_email'][$var_num]['from_email_name'] = $from_email_name;
// 		$_SESSION['need_send_email'][$var_num]['from_email_address'] = $from_email_address;
// 		$_SESSION['need_send_email'][$var_num]['action_type'] = 'true';
	}
	//echo 'use session+ajax send email';
	//exit;
	//howard add use session+ajax send email end
}

//取得当前产品的所在目录 return array
function get_product_categories($product_id){
	$c_array = array();
	$sql = tep_db_query('SELECT categories_id FROM `products_to_categories` WHERE products_id ="'.(int)$product_id.'" ');
	while($rows = tep_db_fetch_array($sql)){
		$c_array[] = $rows['categories_id'];
	}
	return $c_array;
}

/**
 * 取得订单的当前状态名称
 *
 * @param unknown_type $orders_id 订单号
 * @param unknown_type $to 给谁看的？customer为给客人看，其它值为给内部人员看
 */
function tep_get_orders_status_name($orders_id, $to='customer'){
	$sql = tep_db_query('SELECT os.orders_status_name,os.orders_status_name_1 FROM `orders` o , `orders_status` os where o.orders_id="'.(int)$orders_id.'" and os.orders_status_id=o.orders_status ');
	$row = tep_db_fetch_array($sql);
	if($to=='customer'){
		return $row['orders_status_name_1'];
	}
	return $row['orders_status_name'];
}
/**
 * 根据订单状态ID取得订单状态名称
 *
 * @param int $status_id 状态ID
 * @param string $to 给谁看的？customer为给客人看，其它值为给内部人员看
 */
function tep_get_orders_status_name_from_status_id($status_id, $to='customer'){
	$sql = tep_db_query('SELECT os.orders_status_name,os.orders_status_name_1 FROM `orders_status` os where os.orders_status_id="'.(int)$status_id.'" ');
	$row = tep_db_fetch_array($sql);
	if($to=='customer'){
		return $row['orders_status_name_1'];
	}
	return $row['orders_status_name'];
}

/**
 * 客人账户内订单状态更新优化：目前的功能是，当状态更新时勾选notify customer，客人能看到的状态自动更新为当前状态；当状态更新时不勾选notify customer，客人能看到的状态为pending。功能优化为：客人能看到的状态保持最后一个勾选notify customer的状态。
 *
 * @param unknown_type $order_id_bak
 * @param unknown_type $order_status_history
 * @return unknown
 */
function get_customer_notified_tpl($order_id_bak,$order_status_history){
	$no_history_pending_text = html_to_db(TEXT_ORDER_STATUS_PENDING);
	$history_status_query = tep_db_query('SELECT os.orders_status_name FROM `orders_status_history` osh , `orders_status` os WHERE os.orders_status_id=osh.orders_status_id AND osh.orders_id = "'.(int)$order_id_bak.'"  AND  osh.customer_notified = "1" ORDER BY osh.date_added DESC LIMIT 1');
	$row = tep_db_fetch_array($history_status_query);
	if(tep_not_null($row)){
		return $row['orders_status_name'];
	}else{
		return $no_history_pending_text;
	}
}
//tom added
//取得当前订单的customer_notified状态，并且返回输出

function get_customer_notified_tpl_old($order_id_bak,$order_status_history)
{
	//参数说明$order_id_bak 订单号，$order_status_history 订单状态历史,
	//$order_id_bak = (int)$order_id_bak;
	$result_echo = html_to_db(TEXT_ORDER_STATUS_PENDING);
	$history_status_query = tep_db_query('SELECT orders_status_history_id, customer_notified FROM `orders_status_history` WHERE orders_id = "'.(int)$order_id_bak.'" ORDER BY orders_status_history_id desc LIMIT 1');
	$orders_status_history = tep_db_fetch_array($history_status_query);
	if((int)$orders_status_history['orders_status_history_id']){
		if($orders_status_history['customer_notified'] == '1'){
			$result_echo = $order_status_history;
		}
	}
	return $result_echo;
}
//根据英文星期返回中文的星期 tom added
function get_chinese_week($english_week){
	switch($english_week){
		case 'Monday':return db_to_html('一');break;
		case 'Tuesday':return db_to_html('二');break;
		case 'Wednesday':return db_to_html('三');break;
		case 'Thursday':return db_to_html('四');break;
		case 'Friday':return db_to_html('五');break;
		case 'Saturday':return db_to_html('六');break;
		case 'Sunday':return db_to_html('日');break;
	}

}
//简繁转换utf8 tom added
function get_Convertutf8($language){
	switch($language){
		case 'schinese':function Convertutf8($str){return iconv("GB2312","UTF-8",$str);};break;
		case 'tchinese':function Convertutf8($str){return iconv("BIG5","UTF-8",$str);};break;
	}
}

/**
 * 简单的可逆加密解密函数，函数来自UCenter
 * @param $string
 * @param $operation 默认为encode(加密)，可选DECODE(解密)
 * @param $key 引子
 * @author vincent
 */
function vin_crypt($string , $operation='encode',$key='TFF_VINCENT'){
	$expiry = 0 ;
	$operation = strtoupper($operation);
	$ckey_length = 4;
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
//检查手机号码
function check_mobilephone($strMobile){
	if(strlen($strMobile)!=11)return false;
	if(defined('CPUNC_PHONE_NUMBER_HEADER') && CPUNC_PHONE_NUMBER_HEADER!=""){
		$numbers_header = explode(',',CPUNC_PHONE_NUMBER_HEADER);
		$numbers = explode(',',$strMobile);
		$strMobile = '';
		$strMobiles = array();
		for($i=0; $i<count($numbers); $i++){
			for($j=0; $j<count($numbers_header); $j++){			
				if(preg_match('/^'.preg_quote($numbers_header[$j]).'/',$numbers[$i])){
					$strMobiles[]= $numbers[$i];
					break;
				}else{
					$split_h = explode('-',$numbers_header[$j]);
					if(count($split_h)==2){
						$substr_num = substr($numbers[$i],0,strlen($split_h[0]));
						if($substr_num>=$split_h[0] && $substr_num<=$split_h[1]){
							$strMobiles[]= $numbers[$i];
							break;
						}
					}
				}
			}
		}
		$strMobiles = array_unique($strMobiles);
		$strMobile = implode(',',$strMobiles);
	}else{
		$strMobiles = explode(',',$strMobile);
		$strMobiles = array_unique($strMobiles);
		$strMobile = implode(',',$strMobiles);
	}
	//检查手机号码是否在指定范围 end

	//移除被过滤的号码start
	if(defined('CPUNC_PHONE_FLITER_NUMBERS') && CPUNC_PHONE_FLITER_NUMBERS!=""){
		$f = ereg_replace('[:space:]','',CPUNC_PHONE_FLITER_NUMBERS);
		$f = explode(',',$f);
		$strMobiles = explode(',',$strMobile);
		$strMobiles = array_diff($strMobiles,$f);
		$strMobile = implode(',',$strMobiles);
	}
	//移除被过滤的号码end

	if($strMobile==''){
		return false;
	}else{
		return true;
	}
}
//函数检查是否是积分卡登录 BEGIN
//vincent 2011-4-2
function is_pointcard($string){
	return preg_match("/^(tff)?\d\d\d\d\d\d$/i",$string) == 1;
}
/**
*检查手机号码，用于我的走四方中手机号码检查
**/
function check_mobilephone_for_profile_update($strMobile){
	$strMobile = trim($strMobile);
	if(strlen($strMobile) >= 6 && is_numeric($strMobile)){
		return true;
	}else{
		return false;
	}
}
//格式化会员积分卡
function format_pointcard($cardid){
	$cardid = strval($cardid);
	$len =  strlen($cardid);
	return str_repeat('0', 6-$len).$cardid;
}
//检查是否是积分卡登录  END
//tom added
//判断用户输入邮箱是否正确.
function checkregemail($email){
	return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$email));
}
//检查是否是日期 tom added
function check_date_prs($date_prs){
	$pattern = "/^(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))-02-29)$/";
	if(preg_match($pattern,$date_prs)){
		return true;
	}else{
		return false;
	}
}
function tep_get_products_eticket_pickup($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_pickup from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['eticket_pickup'];
}

//tom added 国内收款订单状态 start
function tep_get_domestic_orders_status_name($orders_status_id){
	if((int)$orders_status_id=='100006'||(int)$orders_status_id=='1'||(int)$orders_status_id=='100010'){
		switch((int)$orders_status_id){
			case '100006';return '已收费';break;
			case '1';return '未支付';break;
			case '100010';return '已支付';break;
		}
	}else{
		return tep_get_orders_status_name($orders_status_id);
	}
}

//tom added 国内收款订单状态 end

function tep_date_short_with_day($raw_date) {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

	$year = substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
		return date(DATE_FORMAT_WITH_DAY, mktime($hour, $minute, $second, $month, $day, $year));
	} else {
		return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
	}
}

/**
 * 将字符串中正确的手机号码提取出来，并存放于数组返回
 *
 * @param string $str
 * @return array
 */
function check_phone($str){
	if(empty($str))return array();
	$str = str_replace(' ', '', $str);
	preg_match_all('/\b[0-9]{11,}\b/', $str, $result);
	foreach($result[0] as $k => $v){
		$v_len = strlen($v);
		if($v_len<11 || $v_len>16){
			unset($result[0][$k]);
			continue;
		}
		$phone = $v;
		$sub_str4 = substr($v, 0, 4);
		$sub_str3 = substr($v, 0, 3);
		$sub_str2 = substr($v, 0, 2);
		if($sub_str4 == '0086'){
			$phone = substr($v, 4);
		}
		elseif($sub_str3 == '086'){
			$phone = substr($v, 3);
		}
		elseif($sub_str2 == '86'){
			$phone = substr($v, 2);
		}
		$sub_str1 = substr($phone, 0, 1);
		if($sub_str1 == '0'){
			$phone = substr($phone, 1);
		}
		if(strlen($phone)==11){
			$sub_str = substr($phone, 0, 2);
			if($sub_str == '13' || $sub_str == '15' || $sub_str == '18'){
				if(check_mobilephone($phone)){
					$result[0][$k] = $phone;
					continue;
				}
			}
		}
		unset($result[0][$k]);
	}
	return $result[0];
}

/**
 * 将字符串中以小时开头的时间（形如：11:05pm）提取出来，并处理后以24小时进制返回
 *
 * @param string $str
 * @return time
 */
function query_time($str){
	$time = '00:00';
	$str = str_replace('：', ':', $str);
	$str = strtolower(str_replace(' ', '', $str));
	preg_match('/^([0-9]{1,2})(((p|a)[\.]{0,1}m)|([\:]{1,1}([0-9]{0,2})((p|a)[\.]{0,1}m){0,1}))\b/', $str, $result);
	if(!empty($result)){
		if($result[4] == 'a'){
			$time = $result[1].':00';
		}
		elseif($result[4] == 'p'){
			if(intval($result[1])>=12){
				$time = $result[1].':00';
			}
			else{
				$time = (intval($result[1])+12).':00';
			}
		}
		elseif(isset($result[8])){
			if($result[8] == 'a'){
				$time = $result[1].':'.$result[6];
			}
			elseif($result[8] == 'p'){
				if(intval($result[1])>=12){
					$time = $result[1].':'.$result[6];
				}
				else{
					$time = (intval($result[1])+12).':'.$result[6];
				}
			}
		}
		else{
			$time = $result[1].':'.$result[6];
		}
	}
	return $time;
}

/**
 * 将字符串中形如 月/日/年 或者 年-月-日 的日期提取出来，并处理后以长格式返回
 *
 * @param string $str
 * @return date
 */
function query_date($str){
	$year = '0000';
	$month = '00';
	$day = '00';
	$str = strtolower(str_replace(' ', '', $str));
	$str = trim($str, '-');
	$str = trim($str, '/');
	$sub_arr = explode('/', $str);
	if(count($sub_arr)==3){
		$m2 = substr($sub_arr[0], -2);
		$m1 = substr($sub_arr[0], -1);
		if(strlen($m2)==2 && is_numeric($m2) && intval($m2)>0){
			if(intval($m2)<=12){
				$month = strval($m2);
			}
		}
		elseif(is_numeric($m1) && intval($m1)>0){
			$month = '0'.strval($m1);
		}

		if(is_numeric($sub_arr[1]) && intval($sub_arr[1])>0 && intval($sub_arr[1])<=31){
			if(strlen($sub_arr[1])==2){
				$day = strval($sub_arr[1]);
			}
			elseif(strlen($sub_arr[1])==1){
				$day = '0'.strval($sub_arr[1]);
			}
		}

		$y4 = substr($sub_arr[2], 0, 4);
		$y2 = substr($sub_arr[2], 0, 2);
		if(strlen($y4)==4 && is_numeric($y4) && intval($y4)>0){
			if(intval($y4)>2000 && intval($y4)<2100){
				$year = strval($y4);
			}
		}
		elseif(is_numeric($y2) && intval($y2)>0){
			$year = '20'.strval($y2);
		}
	}
	if(intval($year)==0 || intval($month)==0 || intval($day)==0){
		$sub_arr = explode('-', $str);
		if(count($sub_arr)==3){
			$y4 = substr($sub_arr[0], -4);
			$y2 = substr($sub_arr[0], -2);
			if(strlen($y4)==4 && is_numeric($y4) && intval($y4)>0){
				if(intval($y4)>2000 && intval($y4)<2100){
					$year = strval($y4);
				}
			}
			elseif(is_numeric($y2) && intval($y2)>0){
				$year = '20'.strval($y2);
			}

			if(is_numeric($sub_arr[1]) && intval($sub_arr[1])>0 && intval($sub_arr[1])<=12){
				if(strlen($sub_arr[1])==2){
					$month = strval($sub_arr[1]);
				}
				elseif(strlen($sub_arr[1])==1){
					$month = '0'.strval($sub_arr[1]);
				}
			}

			$d2 = substr($sub_arr[2], 0, 2);
			$d1 = substr($sub_arr[2], 0, 1);
			if(strlen($d2)==2 && is_numeric($d2) && intval($d2)>0){
				if(intval($d2)<=31){
					$day = strval($d2);
				}
			}
			elseif(is_numeric($d1) && intval($d1)>0){
				$day = '0'.strval($d2);
			}
		}
	}
	$date = '0000-00-00';
	if(intval($year)!=0 && intval($month)!=0 && intval($day)!=0){
		$date = $year.'-'.$month.'-'.$day;
	}
	return $date;
}
/**
 * 获取用户的基本信息
 * @param unknown_type $cust_id
 * @author vincent
 * @modify by vincent at 2011-5-13 下午01:40:34
 */
function tep_get_customers_info($customer_id) {
	$sql = "SELECT
	 			a.*,
	 			c.*,
	 			ci.*
	 				 			  
	 			FROM " . TABLE_CUSTOMERS . " as c, " . TABLE_ADDRESS_BOOK . " as a, " . TABLE_CUSTOMERS_INFO . " ci 
	 			WHERE c.customers_default_address_id=a.address_book_id  AND c.customers_id=a.customers_id  AND ci.customers_info_id  = c.customers_id 
	 			AND c.customers_id = '" . tep_db_input($customer_id) . "'";

	if(($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
		return tep_db_fetch_array($rs);
	}
	return array();
}

function tep_get_customers_info_fix($cust_id,$load_customers_info = true , $load_customers_default_address=true){
	$cust_id = intval($cust_id);
	$sql = "SELECT * FROM  ".TABLE_CUSTOMERS." WHERE customers_id = ".$cust_id;
	$userbase = array();
	if(($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
		$userbase  = tep_db_fetch_array($rs);
		if(empty($userbase)) return array();
	}
	if($load_customers_info){
		$sql = "SELECT * FROM ".TABLE_CUSTOMERS_INFO." WHERE customers_info_id=".$cust_id;
		if(($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
			$cinfo = tep_db_fetch_array($rs);
			if(!empty($cinfo)){
				$userbase = array_merge($userbase , $cinfo);
				$userbase['_customer_info'] = $cinfo;
			}
		}
	}
	if($load_customers_default_address){
		$sql = "SELECT * FROM ".TABLE_ADDRESS_BOOK." WHERE address_book_id=".intval($userbase['customers_default_address_id']).' AND customers_id = '.$cust_id;
		$rs = tep_db_query($sql);
		if(tep_db_num_rows($rs) > 0) {
			$defaultAddress = tep_db_fetch_array($rs);
			if(!empty($defaultAddress)){				
				$row =tep_db_fetch_array( tep_db_query("SELECT  countries_name,countries_tel_code FROM ".TABLE_COUNTRIES.' WHERE countries_id = '.$defaultAddress['entry_country_id']));
				$defaultAddress['entry_country_name'] = $row['countries_name'];
				$defaultAddress['entry_country_telcode'] = $row['countries_tel_code'];
				
				$row = tep_db_fetch_array(tep_db_query("SELECT zone_name FROM ".TABLE_ZONES.' WHERE zone_id = '.$defaultAddress['entry_zone_id']));
				$defaultAddress['entry_zone_name'] = $row['zone_name'];
				$city = tep_db_prepare_input($defaultAddress['entry_city']);
				
				$row = tep_db_fetch_array(tep_db_query('SELECT city_name,city_tel_code FROM zones_city WHERE city_name = \''.tep_db_input($city).'\''));
				
				$defaultAddress['entry_city_name'] = $row['city_name'];
				$defaultAddress['entry_city_telcode'] = $row['city_tel_code'];
				$full_telcode = $defaultAddress['entry_country_telcode'].'-';
				if(trim($defaultAddress['entry_city_telcode'])!=''){
					$full_telcode.=$defaultAddress['entry_city_telcode'].'-';
				}
				$defaultAddress['entry_full_telcode']  = $full_telcode;

				$userbase = array_merge($userbase , $defaultAddress);
				$userbase['_default_address'] = $defaultAddress;
			}
		}
	}
	return $userbase;
}
/**
 * 检查订单是否是 结伴同游订单
 * @param unknown_type $orders_id
 * @author vincent
 * @modify by vincent at 2011-5-24 下午05:50:01
 */
function tep_is_companion_order($orders_id){
	$sql = tep_db_query("select COUNT(*) AS total  from orders_travel_companion  where orders_id = '" . (int)$orders_id . "'");
	$row = tep_db_fetch_array($sql);
	return intval($row['total']);
}
/**
 * 获取当前页面URL
 * 
 * @param string $parameter 将要覆盖掉的GET参数使用QueryString格式
 * @param boolean $clearUrl 是否去掉QueryString信息
 * @author vincent
 * @modify by vincent at 2011-5-25 下午01:53:49
 */
function tep_current_url($parameter='' , $clearUrl = false){
	$querystr = '';
	if (isset($_SERVER['argv'])){
		$uri = $_SERVER['PHP_SELF'] ;
		$querystr = $_SERVER['argv'][0];
	}
	else {
		$uri = $_SERVER['PHP_SELF'] ;
		$querystr =  $_SERVER['QUERY_STRING'];
	}

	if (ENABLE_SSL == 'true') {
		$uri = HTTPS_SERVER.$uri;
	}else{
		$uri = HTTP_SERVER .$uri;
	}
	if($clearUrl) return $uri;
	parse_str($querystr ,$arr);
	parse_str($parameter ,$arr2);
	$arr2keys = array_keys($arr2);
	foreach($arr as $key=>$value){
		if(in_array($key,$arr2keys)){}
		else $arr2[$key] = $value;
	}
	$newarrstr = array();
	foreach($arr2 as $key=>$value){
		$newarrstr[] = urlencode(strval($key)).'='.urlencode(strval($value));
	}
	if(count($newarrstr)>0){
		$uri = $uri.'?'.implode("&",$newarrstr);
	}
	return $uri;
}

/**
 * 取得某个团的订单总数
 * @param $product_id 产品ID，必填项
 * @param $type 如果值为fast将直接从产品表取得订单数
 * @param $start_date 可选
 * @param $end_date 可选
 * @author Howard
 */
function tep_get_product_orders_count($product_id, $type='fast', $start_date='', $end_date=''){
	if(!(int)$product_id){ return false; }
	if($type=='fast'){
		$sql = tep_db_query('select products_ordered from products where products_id="'.(int)$product_id.'" ');
		$row = tep_db_fetch_array($sql);
		return $row['products_ordered'];
	}else{
		$table = ' `orders_products` op ';
		$where = ' where op.products_id="'.(int)$product_id.'" and o.orders_id=op.orders_id ';
		if(tep_not_null($start_date)){$where.= ' and o.date_purchased >="'.$start_date.'" ';}
		if(tep_not_null($end_date)){$where.= ' and o.date_purchased <="'.$end_date.'" ';}
		$table .= ' , `orders` o ';
		$sql_str = 'SELECT count(*) as total FROM '.$table.$where.' group by o.orders_id ';
		$sql = tep_db_query($sql_str);
		$row = tep_db_fetch_array($sql);
		return (int)$row['total'];
	}
	return false;
}

/**
 * 取得某个团的订单总参团人数
 * @param $product_id
 * @param $check_clid 小孩是否被排除
 * @author Howard
 */
function tep_get_product_orders_guest_count($product_id, $start_date='', $end_date='', $check_clid=false) {
	if(!(int)$product_id){ return false;}
	$table = ' `orders` o, `orders_product_eticket` ope ';
	$where = ' where ope.products_id="'.(int)$product_id.'" and o.orders_id=ope.orders_id ';
	if(tep_not_null($start_date)){$where.= ' and o.date_purchased >="'.$start_date.'" ';}
	if(tep_not_null($end_date)){$where.= ' and o.date_purchased <="'.$end_date.'" ';}
	$sql_str = 'SELECT ope.guest_name FROM '.$table.$where.' group by o.orders_id ';
	$sql = tep_db_query($sql_str);
	$per_num = 0;
	while($rows = tep_db_fetch_array($sql)){
		if($check_clid==false){
			$per_num += substr_count($rows['guest_name'],'<::>');
		}else{
			$tmp_array = explode('<::>',$rows['guest_name']);
			for($i=0; $i<count($tmp_array)-1; $i++){
				if(!preg_match('/\|\|\d{2,2}\/\d{2,2}\/\d{2,4}/',trim($tmp_array[$i]))){
					$per_num++;
				}
			}
		}
	}
	return (int)$per_num;
}

/**
 * 检查倒计时团购行程是否还有几个可买
 * @param $product_id
 * @author Howard
 */
function check_process_for_group_buy($product_id){
	$balanceNum = 1;
	$g_sql = tep_db_query('SELECT start_date, expires_date, specials_max_buy_num FROM `specials` WHERE products_id="'.(int)$product_id.'" AND specials_type="1" AND status="1" ');
	$g_row = tep_db_fetch_array($g_sql);
	if((int)$g_row['specials_max_buy_num']){
		$purchasedNum = tep_get_product_orders_guest_count((int)$product_id,$g_row['start_date'],$g_row['expires_date']);
		$balanceNum = max(0,(int)($g_row['specials_max_buy_num']-$purchasedNum));
	}
	return (int)$balanceNum;
}
/**
 * 解析日期字符串为UNIX时间戳 2011-12-25
 * @param unknown_type $src 源字符串
 * @param unknown_type $srcFormat 默认空 会根据年份判断 为ymd或者 mdy格式
 */
function tep_parse_date_string($src , $srcFormat = ''){
	$src = trim(preg_replace('/[-\\/\:]+/', ' ', $src));
	$srcArr = explode(" ",$src);
	$t = 0 ;
	if($srcFormat == ''){
		if(strlen($srcArr[0]) == 4) $srcFormat = 'ymd';
		elseif(strlen($srcArr[2]) == 4) $srcFormat = 'mdy';
		else $srcFormat = 'mdy';
	}
	if($srcFormat == 'ymd'){
		$str = intval($srcArr[0])."-".intval($srcArr[1])."-".intval($srcArr[2])." 0:0:0";
	}else if($srcFormat == 'dmy'){
		$str = intval($srcArr[2])."-".intval($srcArr[1])."-".intval($srcArr[0])." 0:0:0";
	}else{
		$str = intval($srcArr[2])."-".intval($srcArr[0])."-".intval($srcArr[1])." 0:0:0";
	}
	return strtotime($str); ;
}

/**
 *记录一条日志信息
 *只能在 IS_DEV_SITES使用
 *如果 $arg 不是字符串会自动打印变量的字符串信息
 *@author vincent
 *@version 0.2
  **/
function tep_log($arg , $logfile ='', $catchContext = false){
	if(IS_DEV_SITES !== true) return ;
	if(!is_string($arg)){
		if(is_null($arg)){
			$msg = 'Null';
		}elseif(is_bool($arg)){
			$msg = $arg===true ? 'True' : 'False';
		}elseif(is_resource($arg)){
			$msg = "Resource:".@get_resource_type($arg)." ".$arg;
		}else{
			$msg = print_r($arg,true);
		}
	}else{
		$msg = $arg ;
	}
	if(empty($logfile)){
		$logfile = DIR_FS_CATALOG.'/tep_log.txt';
	}
	$fp = @fopen($logfile , 'a');
	if($catchContext == true){
		$content = "\n".date('Y-m-d H:i:s ' ) . ' File:'.$_SERVER['PHP_SELF']. ' Content:'.$GLOBALS['content'];
		$content .= "\n".$msg;
		$content .= "\n==============================================================";
		$content .= "\n POST:\n".print_r($_POST,true);
		$content .= "\n GET:\n".print_r($_GET,true);
		$content .= "\n SESION:\n".print_r($_SESSION,true);
		$content .= "\n COOKIE:\n".print_r($_COOKIE,true);
	}else{
		$content = "\n".date('Y-m-d H:i:s ' )."  ".$msg ;
	}
	@fwrite($fp ,$content);
}


/**
 * 自动更新用户账号邮箱(包括博客，affiliate)
 * @author Howard
 * @param $affiliate_email_address 要更新的affiliate邮箱
 * @return true ro false
 */
function update_affiliate_customers_email_address($affiliate_email_address){
	global $affiliate_id;
	/*
	if($_SESSION['customer_email_address']!=$affiliate_email_address){
		if(tep_validate_email_for_register($affiliate_email_address,false,$_SESSION['customer_id']) !="" ){
			return false;
		}
		
		tep_db_query('update affiliate_affiliate set affiliate_email_address="'.$affiliate_email_address.'" where affiliate_id ="'.(int)$affiliate_id.'" ');
		tep_db_query('update customers set customers_email_address="'.$affiliate_email_address.'" where customers_id ="'.(int)$affiliate_id.'" ');
		tep_db_query('update tffblog_users set user_email ="'.$affiliate_email_address.'" where user_email ="'.$_SESSION['customer_email_address'].'" ');
		
		tep_session_unregister('customer_email_address');
		$customer_email_address = $affiliate_email_address;
		tep_session_register('customer_email_address');
	}*/
	return true;
}


/**
 * 取得我们的联系电话信息
 *@return array
 */
function tep_get_us_contact_phone(){
	global $content;	//模板名称
	$data = array();
	$data[] = array('name'=>'国际热线：','phone'=>'1-626-898-7800', 'class'=>'s_2');
	$data[] = array('name'=>'美加免费：','phone'=>'1-888-887-2816', 'class'=>'s_1');
	$data[] = array('name'=>'中国免费：','phone'=>'4006-333-926', 'class'=>'s_3');
	//if($content=='index_default'){	//sofia说只是首页台湾联系方式暂不删除，其他全部删除；但2013-09-25日又要求全部去掉
	//	$data[] = array('name'=>'台北：','phone'=>'886-2-87721729', 'class'=>'s_4');
	//	$data[] = array('name'=>'高雄：','phone'=>'886-7-2515689', 'class'=>'s_4');
	//}
	return $data;
}

/**
 * 取得订单内产品的客人姓名等信息，返回数组
 *
 * @param int $orders_products_id 订单产品快照id
 * @return array
 */
function tep_get_orders_product_guest($orders_products_id){
	$data = false;
	//$sql = tep_db_query('SELECT guest_name FROM `orders_product_eticket` where orders_id ="'.(int)$orders_id.'" and products_id="'.(int)$products_id.'" ');
	$sql = tep_db_query('SELECT guest_name FROM `orders_product_eticket` where orders_products_id ="'.(int)$orders_products_id.'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['guest_name'])){
		$data = explode('<::>', $row['guest_name']);
		unset($data[(sizeof($data)-1)]);
	}
	return $data;
}

/**
 * 取得订单内产品的客人性别，返回数组
 * @param int $orders_products_id 订单产品快照id
 * @author lwkai 2012-04-14 Howard updated by 2012-11-16
 * @return array
 */
function tep_get_orders_product_guest_gender($orders_products_id){
	$data = false;
	//$sql = tep_db_query('SELECT guest_gender FROM `orders_product_eticket` where orders_id ="'.(int)$orders_id.'" and products_id="'.(int)$products_id.'" ');
	$sql = tep_db_query('SELECT guest_gender FROM `orders_product_eticket` where orders_products_id ="'.(int)$orders_products_id.'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['guest_gender'])){
		$data = explode('<::>', $row['guest_gender']);
		unset($data[(sizeof($data)-1)]);
	}
	return $data;
}

/**
 * 取得目录图片
 *
 * @param unknown_type $what_am_i 目录id
 * @return unknown
 */
  function tep_get_categories_image($what_am_i) {
  	//$the_categories_name =  MCache::fetch_categories($who_am_i);
  	//return  $the_categories_name['categories_image'];
    $the_categories_image_query= tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id= '" . $what_am_i . "'");

    $the_categories_image = tep_db_fetch_array($the_categories_image_query);
    return $the_categories_image['categories_image'];
  }

  
/**
 * 取得产品的第一张图片
 *
 * @param unknown_type $product_id
 * @return unknown
 */
  function tep_get_products_image($product_id) {
    $product_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product = tep_db_fetch_array($product_query);
    return $product['products_image'];
  }
  
  /**
   * 取得供应商代号 agency_code
   *
   * @param unknown_type $agency_id
   */
function tep_get_agency_code($agency_id){
	$data = false;
	$sql = tep_db_query('SELECT agency_code FROM `tour_travel_agency` WHERE agency_id="'.$agency_id.'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['agency_code'])) $data = $row['agency_code'];
	return $data;
}


?>