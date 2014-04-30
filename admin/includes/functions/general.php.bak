<?php
/*
$Id: general.php,v 1.1.1.1 2004/03/04 23:39:53 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

//Return files stored in box that can be accessed by user
function tep_admin_files_boxes_newsdesk($filename, $sub_box_name) {
	global $login_groups_id;
	$sub_boxes = '';

	$dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . $filename . "'");
	if (tep_db_num_rows($dbquery)) {
		$sub_boxes = '<a href="' . tep_href_link($filename) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';
	}

	$configuration_groups_query = tep_db_query("
select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_NEWSDESK_CONFIGURATION_GROUP . " where visible = '1' order by sort_order
	");
	while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
		$sub_boxes .= '<a href="' . tep_href_link(FILENAME_NEWSDESK_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '
		" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
	}
	return $sub_boxes;
}

//Return files stored in box that can be accessed by user
function tep_admin_files_boxes_faqdesk($filename, $sub_box_name) {
	global $login_groups_id;
	$sub_boxes = '';

	$dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . $filename . "'");
	if (tep_db_num_rows($dbquery)) {
		$sub_boxes = '<a href="' . tep_href_link($filename) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';
	}

	$configuration_groups_query = tep_db_query("
select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_FAQDESK_CONFIGURATION_GROUP . " where visible = '1' order by sort_order
	");
	while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
		$sub_boxes .= '<a href="' . tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '
		" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
	}
	return $sub_boxes;
}


//Admin begin
////
//Check login and file access
function tep_admin_check_login() {
	global $PHP_SELF, $login_groups_id;
	if (!tep_session_is_registered('login_id')) {
		tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	} else {
		$filename = basename( $PHP_SELF );
		if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF && $filename != FILENAME_ADMIN_ACCOUNT && $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php' && $filename != 'categories_ajax_sections_edit_departure_data.php') {
			$db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_name = '" . $filename . "'");
			if (!tep_db_num_rows($db_file_query)) {
				tep_redirect(tep_href_link(FILENAME_FORBIDEN));
			}
		}
	}
}

////
//Return 'true' or 'false' value to display boxes and files in index.php and column_left.php
function tep_admin_check_boxes($filename, $boxes='') {
	global $login_groups_id;

	$is_boxes = 1;
	if ($boxes == 'sub_boxes') {
		$is_boxes = 0;
	}
	$dbquery = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '" . $is_boxes . "' and admin_files_name = '" . $filename . "'");

	$return_value = false;
	if (tep_db_num_rows($dbquery)) {
		$return_value = true;
	}
	return $return_value;
}

////
//Return files stored in box that can be accessed by user
function tep_admin_files_boxes($filename, $sub_box_name) {
	global $login_groups_id;
	$sub_boxes = '';
	$filename1 = explode('?',$filename.'');
	$filename1 = $filename1[0];
	$dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . $filename1 . "'");
	if (tep_db_num_rows($dbquery)) {
		$sub_boxes = '<a href="' . tep_href_link($filename) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';

	}
	return $sub_boxes;
}

////
//Get selected file for index.php
function tep_selected_file($filename) {
	global $login_groups_id;
	$randomize = FILENAME_ADMIN_ACCOUNT;

	$dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . $filename . "'");
	if (tep_db_num_rows($dbquery)) {
		$boxes_id = tep_db_fetch_array($dbquery);
		$randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "'");
		if (tep_db_num_rows($randomize_query)) {
			$file_selected = tep_db_fetch_array($randomize_query);
			$randomize = $file_selected['admin_files_name'];
		}
	}
	return $randomize;
}
//Admin end

////
// Redirect to another page or site
function tep_redirect($url) {
	global $logger;
	if (STORE_PAGE_PARSE_TIME == 'true') {
		if (!is_object($logger)) $logger = new logger;
		$logger->timer_stop();
	}
	if (!headers_sent()) {
		@header("Location: ".$url);
	}else {
		exit("<meta http-equiv='Refresh' content='0;URL={$url}'>");
	}
	exit;
}

////
// Parse the data used in the html tags to ensure the tags will not break
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
	$string = ereg_replace(' +', ' ', $string);

	return preg_replace("/[<>]/", '_', $string);
}

function tep_customers_name($customers_id) {
	$customers = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	return $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];
}
//amit added to affilate cat link start

function tep_get_path($current_category_id = '') {
	global $cPath_array;

	if ($current_category_id == '') {
		$cPath_new = implode('_', $cPath_array);
	} else {
		if (sizeof($cPath_array) == 0) {
			$cPath_new = $current_category_id;
		} else {
			$cPath_new = '';
			$last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
			$last_category = tep_db_fetch_array($last_category_query);

			$current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
			$current_category = tep_db_fetch_array($current_category_query);

			if ($last_category['parent_id'] == $current_category['parent_id']) {
				for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			} else {
				for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
					$cPath_new .= '_' . $cPath_array[$i];
				}
			}

			$cPath_new .= '_' . $current_category_id;

			if (substr($cPath_new, 0, 1) == '_') {
				$cPath_new = substr($cPath_new, 1);
			}
		}
	}

	return 'cPath=' . $cPath_new;
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
				$userbase = array_merge($userbase , $defaultAddress);
				$userbase['_default_address'] = $defaultAddress;
			}
		}
	}
	return $userbase;
}

/**
   * 根据当前GET参数构造新的Query String
   * @param string $newParams 新参数
   * @param mix $exclude_params 除要更新的新参数外还要剔除的参数
   */
function tep_get_all_get_params_fix($new_params  = '', $exclude_params=''){
	global $HTTP_GET_VARS;
	if(is_array($exclude_params)) {
		$exclude_params_arr = $exclude_params;
	}else if($exclude_params!='' && is_string($exclude_params)){
		$exclude_params_arr = (array)explode(',',$exclude_params);
	}else{
		$exclude_params_arr = array();
	}
	reset($exclude_params_arr);
	while (list($key, $value) = each($exclude_params_arr)) {
		$exclude_params_arr[$key] = trim($value);
	}
	if(is_string($new_params)){
		parse_str($new_params , $new_params_arr);
	}else  if(is_array($new_params)){
		$new_params_arr = $new_params;
	}else{
		$new_params_arr = array();
	}
	$exclude = array_merge($exclude_params_arr , array_keys($new_params_arr));
	$get_url =array();
	foreach($HTTP_GET_VARS as $key=>$value){
		if (($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude))){
			if(is_array($value)){
				foreach($value as $k=>$v){
					$get_url[]= $key . '[]=' . urlencode($v) ;
				}
			}else{
				$get_url[]= $key . '=' . urlencode($value) ;
			}
		}
	}
	foreach($new_params_arr as $key=>$value){
		$get_url[]= $key . '=' . urlencode($value) ;
	}
	return implode('&',$get_url);
}

function tep_get_all_get_params($exclude_array = '') {
	global $HTTP_GET_VARS;

	if ($exclude_array == '') $exclude_array = array();

	$get_url = '';

	reset($HTTP_GET_VARS);
	while (list($key, $value) = each($HTTP_GET_VARS)) {
		if (($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array))){
			if(is_array($value)){
				foreach($value as $k=>$v){
					$get_url .= $key . '[]=' . rawurlencode($v).'&' ;
				}
			}else{
				$get_url .= $key . '=' . $value . '&';
			}
		}
		
	}

	return $get_url;
}

function tep_date_long($raw_date) {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;
	$raw_date = date('Y-m-d H:i:s',strtotime($raw_date));//vincent fixed //_-
	$year = (int)substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
}

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
function tep_date_short($raw_date) {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

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

function tep_datetime_short($raw_datetime) {
	if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

	$year = (int)substr($raw_datetime, 0, 4);
	$month = (int)substr($raw_datetime, 5, 2);
	$day = (int)substr($raw_datetime, 8, 2);
	$hour = (int)substr($raw_datetime, 11, 2);
	$minute = (int)substr($raw_datetime, 14, 2);
	$second = (int)substr($raw_datetime, 17, 2);

	return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
}

function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
	global $languages_id;

	if (!is_array($category_tree_array)) $category_tree_array = array();
	if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

	if ($include_itself) {
		$category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
		$category = tep_db_fetch_array($category_query);
		$category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
	}

	$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
		if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
		$category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
	}

	return $category_tree_array;
}

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
// 取得所有上级目录id
function tep_get_parent_categories(&$categories, $categories_id) {
	$parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
	while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
		if ($parent_categories['parent_id'] == 0) return true;
		$categories[sizeof($categories)] = $parent_categories['parent_id'];
		if ($parent_categories['parent_id'] != $categories_id) {
			tep_get_parent_categories($categories, $parent_categories['parent_id']);
		}
	}
}

//取得所有下级目录id
function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
	global $languages_id;

	if (!is_array($categories_array)) $categories_array = array();

	$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
	while ($categories = tep_db_fetch_array($categories_query)) {
		$categories_array[] = array('id' => $categories['categories_id'],
		'text' => $indent . $categories['categories_name']);

		if ($categories['categories_id'] != $parent_id) {
			$categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
		}
	}

	return $categories_array;
}


//BOF: this function for region and sub region
function tep_get_region_tree($parent_id = '0', $spacing = '', $exclude = '', $region_tree_array = '', $include_itself = false) {
	global $languages_id;

	if (!is_array($region_tree_array)) $region_tree_array = array();
	if ( (sizeof($region_tree_array) < 1) && ($exclude != '0') ) $region_tree_array[] = array('id' => '0', 'text' => TEXT_REGION);

	if ($include_itself) {
		$region_query = tep_db_query("select cd.regions_name from " . TABLE_REGIONS_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.regions_id = '" . (int)$parent_id . "'");
		$region = tep_db_fetch_array($region_query);
		$region_tree_array[] = array('id' => $parent_id, 'text' => $region['regions_name']);
	}

	$regions_query = tep_db_query("select c.regions_id, cd.regions_name, c.parent_id from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where c.regions_id = cd.regions_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.regions_name");
	while ($regions = tep_db_fetch_array($regions_query)) {
		if ($exclude != $regions['regions_id']) $region_tree_array[] = array('id' => $regions['regions_id'], 'text' => $spacing . $regions['regions_name']);
		$region_tree_array = tep_get_region_tree($regions['regions_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $region_tree_array);
	}

	return $region_tree_array;
}
//EOF: this function for region and sub region

function tep_draw_products_pull_down($name, $parameters = '', $exclude = '', $selected_val = '') {
	global $currencies, $languages_id;

	if ($exclude == '') {
		$exclude = array();
	}

	$select_string = '<select name="' . $name . '"';

	if ($parameters) {
		$select_string .= ' ' . $parameters;
	}

	$select_string .= '>';

	$products_query = tep_db_query("select ta.operate_currency_code, p.products_id, pd.products_name, p.products_price, p.products_model, p.products_margin from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TRAVEL_AGENCY . " ta where p.agency_id = ta.agency_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_name != '' and p.agency_id!='".GLOBUS_AGENCY_ID."' order by products_name");
	while ($products = tep_db_fetch_array($products_query)) {

		if($products['operate_currency_code'] != 'USD' && $products['operate_currency_code'] != ''){
			$display_tour_agency_opr_currency_note = $products['operate_currency_code'].': ';
		}else{
			$display_tour_agency_opr_currency_note = '';
		}


		if (!in_array($products['products_id'], $exclude)) {
			$selected = '';
			if($selected_val == $products['products_id']){
				$selected = 'selected = "selected"';
			}
			$select_string .= '<option value="' . $products['products_id'] . '" id="'.$products['products_id'].'##'.$products['products_price'].'##'.$products['products_margin'].'" '.$selected.'>' .$products['products_model'] .' ['. $products['products_name'] . '] (' .$display_tour_agency_opr_currency_note. $currencies->format($products['products_price']) . ')</option>';
		}
	}

	$select_string .= '</select>';

	return $select_string;
}

function tep_options_name($options_id) {
	global $languages_id;

	$options = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$options_id . "' and language_id = '" . (int)$languages_id . "'");
	$options_values = tep_db_fetch_array($options);

	return $options_values['products_options_name'];
}

function tep_values_name($values_id) {
	global $languages_id;

	$values = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_id . "' and language_id = '" . (int)$languages_id . "'");
	$values_values = tep_db_fetch_array($values);

	return $values_values['products_options_values_name'];
}

function tep_info_image($image, $alt, $width = '', $height = '') {
	if (tep_not_null($image) && (file_exists(DIR_FS_CATALOG_IMAGES . $image)) ) {
		$image = tep_image(DIR_WS_CATALOG_IMAGES . $image, $alt, $width, $height);
	} else {
		$image = TEXT_IMAGE_NONEXISTENT;
	}

	return $image;
}

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

function tep_get_country_name($country_id) {
	$country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");

	if (!tep_db_num_rows($country_query)) {
		return $country_id;
	} else {
		$country = tep_db_fetch_array($country_query);
		return $country['countries_name'];
	}
}

function tep_get_zone_name($country_id, $zone_id, $default_zone) {
	$zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
	if (tep_db_num_rows($zone_query)) {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_name'];
	} else {
		return $default_zone;
	}
}

function tep_not_null($value) {
	if (is_array($value)) {
		if (sizeof($value) > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
			return true;
		} else {
			return false;
		}
	}
}

function tep_browser_detect($component) {
	global $HTTP_USER_AGENT;

	return stristr($HTTP_USER_AGENT, $component);
}

function tep_tax_classes_pull_down($parameters, $selected = '') {
	$select_string = '<select ' . $parameters . '>';
	$classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($classes = tep_db_fetch_array($classes_query)) {
		$select_string .= '<option value="' . $classes['tax_class_id'] . '"';
		if ($selected == $classes['tax_class_id']) $select_string .= ' SELECTED';
		$select_string .= '>' . $classes['tax_class_title'] . '</option>';
	}
	$select_string .= '</select>';

	return $select_string;
}

function tep_geo_zones_pull_down($parameters, $selected = '') {
	$select_string = '<select ' . $parameters . '>';
	$zones_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
	while ($zones = tep_db_fetch_array($zones_query)) {
		$select_string .= '<option value="' . $zones['geo_zone_id'] . '"';
		if ($selected == $zones['geo_zone_id']) $select_string .= ' SELECTED';
		$select_string .= '>' . $zones['geo_zone_name'] . '</option>';
	}
	$select_string .= '</select>';

	return $select_string;
}

function tep_get_geo_zone_name($geo_zone_id) {
	$zones_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$geo_zone_id . "'");

	if (!tep_db_num_rows($zones_query)) {
		$geo_zone_name = $geo_zone_id;
	} else {
		$zones = tep_db_fetch_array($zones_query);
		$geo_zone_name = $zones['geo_zone_name'];
	}

	return $geo_zone_name;
}

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

	return $address;
}


////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function    : tep_get_country_iso_code_2
//
// Arguments   : country_id		country id number
//
// Return      : country_iso_code_2
//
// Description : Function to retrieve the country_iso_code_2 based on the country's id
//
////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 取得国家2位代码
 *
 * @param unknown_type $country_id
 * @return unknown
 */
function tep_get_country_iso_code_2($country_id) {

	$country_iso_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");

	if (!tep_db_num_rows($country_iso_query)) {
		return 0;
	}
	else {
		$country_iso_row = tep_db_fetch_array($country_iso_query);
		return $country_iso_row['countries_iso_code_2'];
	}
}


////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function    : tep_get_zone_code
//
// Arguments   : country           country code string
//               zone              state/province zone_id
//               def_state         default string if zone==0
//
// Return      : state_prov_code   state/province code
//
// Description : Function to retrieve the state/province code (as in FL for Florida etc)
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_get_zone_code($country, $zone, $def_state) {

	$state_prov_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . (int)$zone . "'");

	if (!tep_db_num_rows($state_prov_query)) {
		$state_prov_code = $def_state;
	}
	else {
		$state_prov_values = tep_db_fetch_array($state_prov_query);
		$state_prov_code = $state_prov_values['zone_code'];
	}

	return $state_prov_code;
}

/**
 * 取得州省代码
 *
 * @param unknown_type $zone_id
 * @return unknown
 */
function tep_get_zone_code_from_zone($zone_id) {

	$sql = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");
	$row = tep_db_fetch_array($sql);

	return $row['zone_code'];
}

/**
 * 取得城市或景点的英文代码
 *
 * @param unknown_type $city_id
 */
function tep_get_tour_city_code($city_id){
	$sql = tep_db_query('SELECT city_code FROM `tour_city` WHERE city_id='.(int)$city_id);
	$row = tep_db_fetch_array($sql);
	return trim($row['city_code']);
}

function tep_get_uprid($prid, $params) {
	$uprid = $prid;
	if ( (is_array($params)) && (!strstr($prid, '{')) ) {
		while (list($option, $value) = each($params)) {
			$uprid = $uprid . '{' . $option . '}' . $value;
		}
	}

	return $uprid;
}

function tep_get_prid($uprid) {
	$pieces = explode('{', $uprid);

	return $pieces[0];
}

function tep_get_languages($limit=false) {
	if($limit==true)
	{
		//echo 'here';
		$languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order limit 0,1");
	}
	else
	{
		$languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " group by languages_id order by sort_order desc ");
	}
	while ($languages = tep_db_fetch_array($languages_query)) {
		$languages_array[] = array('id' => $languages['languages_id'],
		'name' => $languages['name'],
		'code' => $languages['code'],
		'image' => $languages['image'],
		'directory' => $languages['directory']);
	}

	return $languages_array;
}

//function tep_get_languages

function tep_get_category_name($category_id, $language_id) {
	$category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
	$category = tep_db_fetch_array($category_query);

	return $category['categories_name'];
}

//this function for region
function tep_get_region_name($regions_id, $language_id) {
	$region_query = tep_db_query("select regions_name from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . (int)$regions_id . "' and language_id = '" . (int)$language_id . "'");
	$region = tep_db_fetch_array($region_query);

	return $region['regions_name'];
}
//this function is for region

/**
   * 取得订单状态名称
   *
   * @param unknown_type $orders_status_id
   * @param unknown_type $language_id
   * @return unknown
   */
function tep_get_orders_status_name($orders_status_id, $language_id = '') {
	global $languages_id;

	if (!$language_id) $language_id = $languages_id;
	$orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
	$orders_status = tep_db_fetch_array($orders_status_query);

	return $orders_status['orders_status_name'];
}

/**
   * 取得订单状态名称（给客人看的名称）
   *
   * @param unknown_type $orders_status_id
   * @param unknown_type $language_id
   * @return unknown
   */
function tep_get_orders_status_name_1($orders_status_id, $language_id = '') {
	global $languages_id;

	if (!$language_id) $language_id = $languages_id;
	$orders_status_query = tep_db_query("select orders_status_name_1 from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
	$orders_status = tep_db_fetch_array($orders_status_query);

	return $orders_status['orders_status_name_1'];
}


function tep_get_orders_status() {
	global $languages_id;

	$orders_status_array = array();
	$orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_id");
	while ($orders_status = tep_db_fetch_array($orders_status_query)) {
		$orders_status_array[] = array('id' => $orders_status['orders_status_id'],
		'text' => $orders_status['orders_status_name']);
	}

	return $orders_status_array;
}


function tep_get_orders_status_default_subject($orders_status_id, $language_id = '') {
	global $languages_id;

	if (!$language_id) $language_id = $languages_id;
	$orders_status_query = tep_db_query("select orders_status_default_subject from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
	$orders_status = tep_db_fetch_array($orders_status_query);

	return $orders_status['orders_status_default_subject'];
}

function tep_get_orders_status_default_comment($orders_status_id, $language_id = '') {
	global $languages_id;

	if (!$language_id) $language_id = $languages_id;
	$orders_status_query = tep_db_query("select orders_status_default_comment from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
	$orders_status = tep_db_fetch_array($orders_status_query);

	return $orders_status['orders_status_default_comment'];
}



function tep_get_products_name($product_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_name'];
}

function tep_get_products_name_provider($product_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$product_query = tep_db_query("select products_name_provider from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_name_provider'];
}

function tep_get_products_model($product_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$product_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_model'];
}


function tep_get_products_name_short($product_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$product_query = tep_db_query("select products_name_short from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_name_short'];
}

function tep_get_infobox_file_name($infobox_id, $language_id = 0) {
	global $languages_id;

	if ($language_id == 0) $language_id = $languages_id;
	$infobox_query = tep_db_query("select infobox_file_name from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$infobox_id . "' and language_id = '" . (int)$language_id . "'");
	$infobox = tep_db_fetch_array($infobox_query);

	return $infobox['infobox_file_name'];
}

function tep_get_products_description($product_id, $language_id) {
	$product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_description'];
}

function tep_get_products_travel_tips($product_id, $language_id) {
	$product_query = tep_db_query("select travel_tips from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['travel_tips'];
}
function tep_get_products_notes($product_id, $language_id) {
	$product_query = tep_db_query("select products_notes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_notes'];
}

function tep_get_products_pricing_special_notes($product_id, $language_id) {
	$product_query = tep_db_query("select products_pricing_special_notes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	/*
	if($product['products_pricing_special_notes'] == ''){
	$product['products_pricing_special_notes'] = TOURS_DEFAULT_PRICING_NOTES;
	}
	*/

	return $product['products_pricing_special_notes'];
}


function tep_get_products_other_description($product_id, $language_id) {
	$product_query = tep_db_query("select products_other_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_other_description'];
}

function tep_get_products_package_includes($product_id, $language_id) {
	$product_query = tep_db_query("select products_package_includes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_package_includes'];
}

function tep_get_products_package_excludes($product_id, $language_id) {
	$product_query = tep_db_query("select products_package_excludes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_package_excludes'];
}

function tep_get_products_broadcast($product_id, $language_id) {
	$product_query = tep_db_query("select products_broadcast from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_broadcast'];
}

function tep_get_products_package_special_notes($product_id, $language_id) {
	$product_query = tep_db_query("select products_package_special_notes  from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	if($product['products_package_special_notes'] == ''){
		global $global_default_products_vacation_package;
		if($global_default_products_vacation_package == '1') {
			$product['products_package_special_notes'] = TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_SPECIAL_NOTES;
		}else{
			$product['products_package_special_notes'] = TOURS_DEFAULT_PACKAGE_SPECIAL_NOTES;
		}
	}
	return $product['products_package_special_notes'];
}

function tep_get_products_eticket_itinerary($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_itinerary from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['eticket_itinerary'];
}

function tep_get_products_eticket_hotel($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_hotel from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['eticket_hotel'];
}

function tep_get_products_eticket_notes($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_notes from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['eticket_notes'];
}


function tep_get_products_small_description($product_id, $language_id) {
	$product_query = tep_db_query("select products_small_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_small_description'];
}


function tep_get_products_head_title_tag($product_id, $language_id) {
	$product_query = tep_db_query("select products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_head_title_tag'];
}

function tep_get_products_head_desc_tag($product_id, $language_id) {
	$product_query = tep_db_query("select products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_head_desc_tag'];
}

function tep_get_products_head_keywords_tag($product_id, $language_id) {
	$product_query = tep_db_query("select products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_head_keywords_tag'];
}

function tep_get_products_url($product_id, $language_id) {
	$product_query = tep_db_query("select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['products_url'];
}

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
function tep_get_manufacturer_url($manufacturer_id, $language_id) {
	$manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
	$manufacturer = tep_db_fetch_array($manufacturer_query);

	return $manufacturer['manufacturers_url'];
}

////
// Wrapper for class_exists() function
// This function is not available in all PHP versions so we test it before using it.
function tep_class_exists($class_name) {
	if (function_exists('class_exists')) {
		return class_exists($class_name);
	} else {
		return true;
	}
}

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
function tep_products_in_category_count($categories_id, $include_deactivated = false) {
	$products_count = 0;

	if ($include_deactivated) {
		$products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$categories_id . "'");
	} else {
		$products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$categories_id . "'");
	}

	$products = tep_db_fetch_array($products_query);

	$products_count += $products['total'];

	$childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
	if (tep_db_num_rows($childs_query)) {
		while ($childs = tep_db_fetch_array($childs_query)) {
			$products_count += tep_products_in_category_count($childs['categories_id'], $include_deactivated);
		}
	}

	return $products_count;
}

////
// Count how many subcategories exist in a category
// TABLES: categories
function tep_childs_in_category_count($categories_id) {
	$categories_count = 0;

	$categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
	while ($categories = tep_db_fetch_array($categories_query)) {
		$categories_count++;
		$categories_count += tep_childs_in_category_count($categories['categories_id']);
	}

	return $categories_count;
}
//this is function for region count
function tep_childs_in_region_count($regions_id) {
	$regions_count = 0;

	$regions_query = tep_db_query("select regions_id from " . TABLE_REGIONS . " where parent_id = '" . (int)$regions_id . "'");
	while ($regions = tep_db_fetch_array($regions_query)) {
		$regions_count++;
		$regions_count += tep_childs_in_region_count($regions['regions_id']);
	}

	return $regions_count;
}
// this is function for region count


////
// Returns an array with countries
// TABLES: countries
/**
 * 取得国家数组，把美国、中国、加拿大优化排序到前面
 *
 * @param unknown_type $default
 * @return unknown
 */
function tep_get_countries($default = '') {
	$countries_array = array();
	if ($default) {
		$countries_array[] = array('id' => '',
		'text' => $default);
	}
	$countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by find_in_set(countries_id,'38,44,223') DESC, countries_name ASC ");
	while ($countries = tep_db_fetch_array($countries_query)) {
		$countries_array[] = array('id' => $countries['countries_id'],
		'text' => $countries['countries_name']);
	}

	return $countries_array;
}

////
// return an array with country zones
/**
 * 根据国家ID取得州省数组
 *
 * @param unknown_type $country_id
 * @return unknown
 */
function tep_get_country_zones($country_id) {
	$zones_array = array();
	$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
	while ($zones = tep_db_fetch_array($zones_query)) {
		$zones_array[] = array('id' => $zones['zone_id'],
		'text' => $zones['zone_name']);
	}

	return $zones_array;
}

/**
 * 根据国家ID取得州省数组，用于下拉菜单
 *
 * @param unknown_type $country_id
 * @return unknown
 */
function tep_prepare_country_zones_pull_down($country_id = '') {
	// preset the width of the drop-down for Netscape
	$pre = '';
	if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
		for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
	}

	$zones = tep_get_country_zones($country_id);

	if (sizeof($zones) > 0) {
		$zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
		$zones = array_merge($zones_select, $zones);
	} else {
		$zones = array(array('id' => '', 'text' => TYPE_BELOW));
		// create dummy options for Netscape to preset the height of the drop-down
		if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
			for ($i=0; $i<9; $i++) {
				$zones[] = array('id' => '', 'text' => $pre);
			}
		}
	}

	return $zones;
}

/**
 * 根据州省ID取得城市、景点数组
 *
 * @param unknown_type $zones_id
 */
function tep_prepare_zones_city_pull_down($zones_id = ''){
	$city = array(array('id' => '', 'text' => PLEASE_SELECT));
	if($zones_id!=''){
		$sql = tep_db_query('SELECT city_id,city FROM `tour_city` WHERE state_id = "'.(int)$zones_id.'" ');
		while ($rows = tep_db_fetch_array($sql)) {
			$city[] = array('id'=>$rows['city_id'], 'text'=>$rows['city']);
		}
	}
	return $city;
}

////
// Get list of address_format_id's
function tep_get_address_formats() {
	$address_format_query = tep_db_query("select address_format_id from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
	$address_format_array = array();
	while ($address_format_values = tep_db_fetch_array($address_format_query)) {
		$address_format_array[] = array('id' => $address_format_values['address_format_id'],
		'text' => $address_format_values['address_format_id']);
	}
	return $address_format_array;
}

////
// Alias function for Store configuration values in the Administration Tool
function tep_cfg_pull_down_country_list($country_id) {
	return tep_draw_pull_down_menu('configuration_value', tep_get_countries(), $country_id);
}

function tep_cfg_pull_down_zone_list($zone_id) {
	return tep_draw_pull_down_menu('configuration_value', tep_get_country_zones(STORE_COUNTRY), $zone_id);
}

function tep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
	$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

	$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
	$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
	while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		$tax_class_array[] = array('id' => $tax_class['tax_class_id'],
		'text' => $tax_class['tax_class_title']);
	}

	return tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
}

////
// Function to read in text area in admin
function tep_cfg_textarea($text) {
	return tep_draw_textarea_field('configuration_value', false, 35, 5, $text);
}

function tep_cfg_get_zone_name($zone_id) {
	$zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");

	if (!tep_db_num_rows($zone_query)) {
		return $zone_id;
	} else {
		$zone = tep_db_fetch_array($zone_query);
		return $zone['zone_name'];
	}
}

////
// Sets the status of a banner
function tep_set_banner_status($banners_id, $status) {
	if ($status == '1') {
		//return tep_db_query("update " . TABLE_BANNERS . " set status = '1', expires_impressions = NULL, expires_date = NULL, date_status_change = NULL where banners_id = '" . $banners_id . "'");
		return tep_db_query("update " . TABLE_BANNERS . " set status = '1', date_status_change = now() where banners_id = '" . $banners_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . $banners_id . "'");
	} else {
		return -1;
	}
}

////
// Sets the status of a product
function tep_set_product_status($products_id, $status) {
	if ($status == '1') {
		return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
	} else {
		return -1;
	}
}

////
// Sets the status of a product
function tep_set_category_status($categories_id, $status) {
	if ($status == '1') {
		$flag= tep_db_query("update " . TABLE_CATEGORIES . " set categories_status = '1', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
		if($flag) MCache::update_categories(array('method'=>'update' , 'categories_id'=>$categories_id));//更新缓存
		return $flag;
	} elseif ($status == '0') {
		$flag= tep_db_query("update " . TABLE_CATEGORIES . " set categories_status = '0', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
		if($flag) MCache::update_categories(array('method'=>'update' , 'categories_id'=>$categories_id));//更新缓存
		return $flag;
	} else {
		return -1;
	}
}

// Sets the status of a feature categories
function tep_set_category_feature_status($categories_id, $status) {
	if ($status == '1') {
		$flag= tep_db_query("update " . TABLE_CATEGORIES . " set categories_feature_status = '1', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
		if($flag) MCache::update_categories(array('method'=>'update' , 'categories_id'=>$categories_id));//更新缓存
		return $flag;
	} elseif ($status == '0') {
		$flag= tep_db_query("update " . TABLE_CATEGORIES . " set categories_feature_status = '0', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
		if($flag) MCache::update_categories(array('method'=>'update' , 'categories_id'=>$categories_id));//更新缓存
		return $flag;
	} else {
		return -1;
	}
}

////
// Sets the status of a product on special
function tep_set_specials_status($specials_id, $status) {
	if ($status == '1') {
		return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
	} else {
		return -1;
	}
}

////
// Sets timeout for the current script.
// Cant be used in safe mode.
function tep_set_time_limit($limit) {
	if (!get_cfg_var('safe_mode')) {
		set_time_limit($limit);
	}
}

////
// Alias function for Store configuration values in the Administration Tool
function tep_cfg_select_option($select_array, $key_value, $key = '') {
	$string = '';

	for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
		$name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');

		$string .= '<br><input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';

		if ($key_value == $select_array[$i]) $string .= ' CHECKED';

		$string .= '> ' . $select_array[$i];
	}

	return $string;
}
// 输出只读格式的输入框 in the Administration Tool
function tep_cfg_readonly_input_field($name, $key_value){
	$string = '';
	$string .= tep_draw_input_field($name, $key_value, ' readonly="true" ');
	return $string;
}

// 取得cpunc短信平台最新余额 in the Administration Tool
function tep_cfg_get_cpunc_balance($name, $key_value){
	global $cpunc;
	$key_value = "&#65509;".$cpunc->GetBalance();
	$string = '';
	$string .= tep_cfg_readonly_input_field($name, $key_value);
	return $string;
}



////
// Alias function for module configuration keys
function tep_mod_select_option($select_array, $key_name, $key_value) {
	reset($select_array);
	while (list($key, $value) = each($select_array)) {
		if (is_int($key)) $key = $value;
		$string .= '<br><input type="radio" name="configuration[' . $key_name . ']" value="' . $key . '"';
		if ($key_value == $key) $string .= ' CHECKED';
		$string .= '> ' . $value;
	}

	return $string;
}

////
// Retreive server information
function tep_get_system_information() {
	global $HTTP_SERVER_VARS;

	$db_query = tep_db_query("select now() as datetime");
	$db = tep_db_fetch_array($db_query);

	list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);

	return array('date' => tep_datetime_short(date('Y-m-d H:i:s')),
	'system' => $system,
	'kernel' => $kernel,
	'host' => $host,
	'ip' => gethostbyname($host),
	'uptime' => @exec('uptime'),
	'http_server' => $HTTP_SERVER_VARS['SERVER_SOFTWARE'],
	'php' => PHP_VERSION,
	'zend' => (function_exists('zend_version') ? zend_version() : ''),
	'db_server' => DB_SERVER,
	'db_ip' => gethostbyname(DB_SERVER),
	'db_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''),
	'db_date' => tep_datetime_short($db['datetime']));
}

function tep_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
	global $languages_id;

	if (!is_array($categories_array)) $categories_array = array();

	if ($from == 'product') {
		$categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$id . "'");
		while ($categories = tep_db_fetch_array($categories_query)) {
			if ($categories['categories_id'] == '0') {
				$categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
			} else {
				$category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
				$category = tep_db_fetch_array($category_query);
				$categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
				if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
				$categories_array[$index] = array_reverse($categories_array[$index]);
			}
			$index++;
		}
	} elseif ($from == 'category') {
		$category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
		$category = tep_db_fetch_array($category_query);
		$categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
		if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
	}

	return $categories_array;
}

function tep_output_generated_category_path($id, $from = 'category') {
	$calculated_category_path_string = '';
	$calculated_category_path = tep_generate_category_path($id, $from);
	for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
		for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
			//$calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
			$calculated_category_path_string = $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;' .$calculated_category_path_string;
		}
		$calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
	}
	$calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

	if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

	return $calculated_category_path_string;
}

function tep_get_generated_category_path_ids($id, $from = 'category') {
	$calculated_category_path_string = '';
	$calculated_category_path = tep_generate_category_path($id, $from);
	for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
		for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
			$calculated_category_path_string .= $calculated_category_path[$i][$j]['id'] . '_';
		}
		$calculated_category_path_string = substr($calculated_category_path_string, 0, -1) . '<br>';
	}
	$calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

	if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

	return $calculated_category_path_string;
}

function tep_remove_category($category_id) {
	$category_image_query = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
	$category_image = tep_db_fetch_array($category_image_query);

	$duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
	$duplicate_image = tep_db_fetch_array($duplicate_image_query);

	if ($duplicate_image['total'] < 2) {
		if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
			@unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
		}
	}

	tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
	tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
	MCache::update_categories(array('method'=>'delete','category_id'=>$category_id));//更新缓存

	//删除今日推荐列表
	tep_db_query("delete from categories_to_today_recommended_products where categories_id = '" . (int)$category_id . "'");

	if (USE_CACHE == 'true') {
		tep_reset_cache_block('categories');
		tep_reset_cache_block('also_purchased');
	}
}
//for region deletion
function tep_remove_region($regions_id) {
	$category_image_query = tep_db_query("select regions_image from " . TABLE_REGIONS . " where regions_id = '" . (int)$regions_id . "'");
	$category_image = tep_db_fetch_array($category_image_query);

	$duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_REGIONS . " where regions_image = '" . tep_db_input($category_image['regions_image']) . "'");
	$duplicate_image = tep_db_fetch_array($duplicate_image_query);

	if ($duplicate_image['total'] < 2) {
		if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['regions_image'])) {
			@unlink(DIR_FS_CATALOG_IMAGES . $category_image['regions_image']);
		}
	}

	tep_db_query("delete from " . TABLE_REGIONS . " where regions_id = '" . (int)$regions_id . "'");
	tep_db_query("delete from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . (int)$regions_id . "'");
	//tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");

	if (USE_CACHE == 'true') {
		tep_reset_cache_block('regions');
		tep_reset_cache_block('also_purchased');
	}
}
//for region

function tep_remove_product($product_id) {
	$product_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	$product_image = tep_db_fetch_array($product_image_query);

	$duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image = '" . tep_db_input($product_image['products_image']) . "'");
	$duplicate_image = tep_db_fetch_array($duplicate_image_query);

	if ($duplicate_image['total'] < 2) {
		if (file_exists(DIR_FS_CATALOG_IMAGES . $product_image['products_image'])) {
			@unlink(DIR_FS_CATALOG_IMAGES . $product_image['products_image']);
		}
	}

	tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where CONCAT(products_id,'{') Like '" . (int)$product_id . "{%'");
	tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where  CONCAT(products_id,'{') Like '" . (int)$product_id . "{%'");

	$product_reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");
	while ($product_reviews = tep_db_fetch_array($product_reviews_query)) {
		tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$product_reviews['reviews_id'] . "'");
	}
	tep_db_query("delete from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");


	//start : code by scs for delete   "TABLE_PRODUCTS_START_DATE" ,"TABLE_PRODUCTS_AVAILABLE","TABLE_PRODUCTS_DESTINATION","TABLE_PRODUCTS_DEPARTURE"

	tep_db_query("delete from " . TABLE_PRODUCTS_START_DATE . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_AVAILABLE . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_DESTINATION . " where products_id = '" . (int)$product_id . "'");
	tep_db_query("delete from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = '" . (int)$product_id . "'");

	//end : code by scs for delete   "TABLE_PRODUCTS_START_DATE" ,"TABLE_PRODUCTS_AVAILABLE","TABLE_PRODUCTS_DESTINATION","TABLE_PRODUCTS_DEPARTURE"

	if (USE_CACHE == 'true') {
		tep_reset_cache_block('categories');
		tep_reset_cache_block('also_purchased');
	}
}

function tep_remove_order($order_id, $restock) {
	if ($restock == 'on') {
		$order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
		while ($order = tep_db_fetch_array($order_query)) {
			tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
		}
	}

	//begin PayPal_Shopping_Cart_IPN 2.8 DMG
	include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php');
	paypal_remove_order($order_id);
	//end PayPal_Shopping_Cart_IPN

	tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '".(int)$order_id."'");
	//amit added to remove other related orders start
	tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_id = '".(int)$order_id."'");
	tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '".(int)$order_id."'");
	//amti added to remove other related orders end

	// howard added write log
	$sql = tep_db_query('SELECT admin_id,admin_firstname, admin_lastname FROM `admin` WHERE admin_id="'.(int)$_SESSION['login_id'].'" Limit 1');
	$row = tep_db_fetch_array($sql);
	$login_id = $row['admin_id'];
	$admin_name = $row['admin_firstname'].' '.$row['admin_lastname'];

	tep_db_query('INSERT INTO `orders_remove_log` (`orders_id`, `admin_id`, `admin_name`, `orders_remove_log_date` )VALUES ('.(int)$order_id.', '.(int)$login_id.', "'.$admin_name.'", "'.date('Y-m-d H:i:s').'");');
	// howard added write log

}

function tep_reset_cache_block($cache_block) {
	global $cache_blocks;

	for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
		if ($cache_blocks[$i]['code'] == $cache_block) {
			if ($cache_blocks[$i]['multiple']) {
				if ($dir = @opendir(DIR_FS_CACHE)) {
					while ($cache_file = readdir($dir)) {
						$cached_file = $cache_blocks[$i]['file'];
						$languages = tep_get_languages();
						for ($j=0, $k=sizeof($languages); $j<$k; $j++) {
							$cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
							if (ereg('^' . $cached_file_unlink, $cache_file)) {
								@unlink(DIR_FS_CACHE . $cache_file);
							}
						}
					}
					closedir($dir);
				}
			} else {
				$cached_file = $cache_blocks[$i]['file'];
				$languages = tep_get_languages();
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					$cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
					@unlink(DIR_FS_CACHE . $cached_file);
				}
			}
			break;
		}
	}
}

function tep_get_file_permissions($mode) {
	// determine type
	if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
		$type = 's';
	} elseif ( ($mode & 0x4000) == 0x4000) { // directory
		$type = 'd';
	} elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
		$type = 'l';
	} elseif ( ($mode & 0x8000) == 0x8000) { // regular file
		$type = '-';
	} elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
		$type = 'b';
	} elseif ( ($mode & 0x2000) == 0x2000) { // character special file
		$type = 'c';
	} elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
		$type = 'p';
	} else { // unknown
		$type = '?';
	}

	// determine permissions
	$owner['read']    = ($mode & 00400) ? 'r' : '-';
	$owner['write']   = ($mode & 00200) ? 'w' : '-';
	$owner['execute'] = ($mode & 00100) ? 'x' : '-';
	$group['read']    = ($mode & 00040) ? 'r' : '-';
	$group['write']   = ($mode & 00020) ? 'w' : '-';
	$group['execute'] = ($mode & 00010) ? 'x' : '-';
	$world['read']    = ($mode & 00004) ? 'r' : '-';
	$world['write']   = ($mode & 00002) ? 'w' : '-';
	$world['execute'] = ($mode & 00001) ? 'x' : '-';

	// adjust for SUID, SGID and sticky bit
	if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
	if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
	if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

	return $type .
	$owner['read'] . $owner['write'] . $owner['execute'] .
	$group['read'] . $group['write'] . $group['execute'] .
	$world['read'] . $world['write'] . $world['execute'];
}

function tep_remove($source) {
	global $messageStack, $tep_remove_error;

	if (isset($tep_remove_error)) $tep_remove_error = false;

	if (is_dir($source)) {
		$dir = dir($source);
		while ($file = $dir->read()) {
			if ( ($file != '.') && ($file != '..') ) {
				if (is_writeable($source . '/' . $file)) {
					tep_remove($source . '/' . $file);
				} else {
					$messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
					$tep_remove_error = true;
				}
			}
		}
		$dir->close();

		if (is_writeable($source)) {
			rmdir($source);
		} else {
			$messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
			$tep_remove_error = true;
		}
	} else {
		if (is_writeable($source)) {
			unlink($source);
		} else {
			$messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
			$tep_remove_error = true;
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

function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $send_type =EMAIL_USE_HTML , $email_charset=CHARSET ) {
	global $mail_isNoReplay;
	//Howard added for add test email notice for qa sites start
	if(IS_LIVE_SITES!=true){
		$email_subject = iconv('gb2312',$email_charset,"邮件测试 - ").$email_subject;
	}
	//Howard added for add test email notice for qa sites end
	if (SEND_EMAILS != 'true') return false;

	$_Mail_UploadFiles = array();
	if(is_array($_FILES['Mail_Attachment']['name']) && count($_FILES['Mail_Attachment']['name'])>0){
		foreach($_FILES['Mail_Attachment']['error'] as $mu_key=>$mu_value){
			if($mu_value == UPLOAD_ERR_OK){
				$_Mail_UploadFiles[$mu_key]['name']=$_FILES['Mail_Attachment']['name'][$mu_key];
				$_Mail_UploadFiles[$mu_key]['tmp_name']=$_FILES['Mail_Attachment']['tmp_name'][$mu_key];
				$_Mail_UploadFiles[$mu_key]['type']=$_FILES['Mail_Attachment']['type'][$mu_key];
				$_Mail_UploadFiles[$mu_key]['size']=$_FILES['Mail_Attachment']['size'][$mu_key];
			}
		}
	}

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
	
	if (EMAIL_TRANSPORT == 'smtp' || strpos($to_email_address,'@usitrip.com')!==false) {	//如果收件人邮箱是*@usitrip.com邮箱或EMAIL_TRANSPORT==smtp发送就用stmp方式发送
		require_once(DIR_FS_CATALOG.'phpmailer/send_mail_funtoin.php');
		$use_mail_address = $from_email_address;
		$TmpFileArray = $SetFileNameArray = array();

		if(is_array($_Mail_UploadFiles) && count($_Mail_UploadFiles)>0){
			foreach($_Mail_UploadFiles as $item){
				$TmpFileArray[] = $item['tmp_name'];
				$SetFileNameArray[] = $item['name'];
			}
		}
		$send_state = smtp_mail($to_email_address, $to_name, $email_subject, $email_text, $email_lang, $from_email_name,$SetFileNameArray,$TmpFileArray,$use_mail_address);
		return true;
	}else{
		// Instantiate a new mail object
		//$message = new email(array('X-Mailer: osCommerce Mailer'));	//usitrip [version 1.73]
		$message = new email(array('X-Mailer: usitrip [version 1.73]'));	//usitrip [version 1.73]
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
		if(is_array($_Mail_UploadFiles) && count($_Mail_UploadFiles)>0){
			foreach($_Mail_UploadFiles as $item){
				$message->add_attachment($item['tmp_name'], $item['name'], $item['type']);
			}
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
			$message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject, '', $email_lang );	//$email_charset
		}
	}
}

function tep_get_tax_class_title($tax_class_id) {
	if ($tax_class_id == '0') {
		return TEXT_NONE;
	} else {
		$classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$tax_class_id . "'");
		$classes = tep_db_fetch_array($classes_query);

		return $classes['tax_class_title'];
	}
}

function tep_banner_image_extension() {
	if (function_exists('imagetypes')) {
		if (imagetypes() & IMG_PNG) {
			return 'png';
		} elseif (imagetypes() & IMG_JPG) {
			return 'jpg';
		} elseif (imagetypes() & IMG_GIF) {
			return 'gif';
		}
	} elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
		return 'png';
	} elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
		return 'jpg';
	} elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
		return 'gif';
	}

	return false;
}

////
// Wrapper function for round() for php3 compatibility
function tep_round($value, $precision) {
	if (PHP_VERSION < 4) {
		$exp = pow(10, $precision);
		return round($value * $exp) / $exp;
	} else {
		return round($value, $precision);
	}
}

////
// Add tax to a products price
function tep_add_tax($price, $tax) {
	global $currencies;

	if (DISPLAY_PRICE_WITH_TAX == 'true') {
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

	$tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id left join " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '" . (int)$country_id . "') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . (int)$zone_id . "') AND tr.tax_class_id = '" . (int)$class_id . "' GROUP BY tr.tax_priority");
	if (tep_db_num_rows($tax_query)) {
		$tax_multiplier = 0;
		while ($tax = tep_db_fetch_array($tax_query)) {
			$tax_multiplier += $tax['tax_rate'];
		}
		return $tax_multiplier;
	} else {
		return 0;
	}
}

////
// Returns the tax rate for a tax class
// TABLES: tax_rates
function tep_get_tax_rate_value($class_id) {
	$tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$class_id . "' group by tax_priority");
	if (tep_db_num_rows($tax_query)) {
		$tax_multiplier = 0;
		while ($tax = tep_db_fetch_array($tax_query)) {
			$tax_multiplier += $tax['tax_rate'];
		}
		return $tax_multiplier;
	} else {
		return 0;
	}
}

function tep_call_function($function, $parameter, $object = '') {
	if ($object == '') {
		return call_user_func($function, $parameter);
	} elseif (PHP_VERSION < 4) {
		return call_user_method($function, $object, $parameter);
	} else {
		return call_user_func(array($object, $function), $parameter);
	}
}

function tep_get_zone_class_title($zone_class_id) {
	if ($zone_class_id == '0') {
		return TEXT_NONE;
	} else {
		$classes_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$zone_class_id . "'");
		$classes = tep_db_fetch_array($classes_query);

		return $classes['geo_zone_name'];
	}
}

function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
	$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

	$zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
	$zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
	while ($zone_class = tep_db_fetch_array($zone_class_query)) {
		$zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
		'text' => $zone_class['geo_zone_name']);
	}

	return tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
}

function tep_cfg_pull_down_order_statuses($order_status_id, $key = '') {
	global $languages_id;

	$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

	$statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));
	$statuses_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");
	while ($statuses = tep_db_fetch_array($statuses_query)) {
		$statuses_array[] = array('id' => $statuses['orders_status_id'],
		'text' => $statuses['orders_status_name']);
	}

	return tep_draw_pull_down_menu($name, $statuses_array, $order_status_id);
}

function tep_get_order_status_name($order_status_id, $language_id = '') {
	global $languages_id;

	if ($order_status_id < 1) return TEXT_DEFAULT;

	if (!is_numeric($language_id)) $language_id = $languages_id;

	$status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$order_status_id . "' and language_id = '" . (int)$language_id . "'");
	$status = tep_db_fetch_array($status_query);

	return $status['orders_status_name'];
}

////
// Return a random value
function tep_rand($min = null, $max = null) {
	static $seeded;

	if (!$seeded) {
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

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
function tep_convert_linefeeds($from, $to, $string) {
	if ((PHP_VERSION < "4.0.5") && is_array($from)) {
		return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
	} else {
		return str_replace($from, $to, $string);
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


// Alias function for array of configuration values in the Administration Tool
function tep_cfg_select_multioption($select_array, $key_value, $key = '') {
	for ($i=0; $i<sizeof($select_array); $i++) {
		//$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
		$name = 'configuration_value[]';
		$string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
		$key_values = explode( ", ", $key_value);
		if ( in_array($select_array[$i], $key_values) ) $string .= 'CHECKED';
		$string .= '> ' . $select_array[$i];
	}
	return $string;
}

//create a select list to display list of themes available for selection
function tep_cfg_pull_down_template_list($template_id, $key = '') {
	$name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

	$template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_name");
	while ($template = tep_db_fetch_array($template_query)) {
		$template_array[] = array('id' => $template['template_name'],
		'text' => $template['template_name']);
	}

	return tep_draw_pull_down_menu($name, $template_array, $template_id);
}

function tep_get_link_category($languages_id){
	$arr_link_category[] = array('text' => '','id' => '');
	$link_category_sql = "select distinct lang.code,lang.languages_id,lcd.link_categories_id,lcd.link_categories_name,lcd.language_id from languages lang,link_categories_description lcd where lang.languages_id='".(int)$languages_id."' and lang.languages_id=lcd.language_id Group By lcd.link_categories_id ";
	$link_catetory_query = tep_db_query($link_category_sql);
	while($link_catetory = tep_db_fetch_array($link_catetory_query)){
		$arr_link_category[] = array('id' =>  $link_catetory['link_categories_id'],'text' => $link_catetory['link_categories_name']);}
		return $arr_link_category;
}

//geting customer cellphoneno
function tep_customers_cellphone($customers_id) {
	$customers = tep_db_query("select customers_cellphone from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	return $customers_values['customers_cellphone'];
}

//geting Travel Agency
function tep_get_travel_agency_name($agency_id) {
	$customers = tep_db_query("select agency_name from " . TABLE_TRAVEL_AGENCY . " where agency_id = '" . (int)$agency_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	return $customers_values['agency_name'];
}

//geting products options Name
function tep_get_products_options_name($products_options_id) {
	$customers = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where  products_options_id = '" . (int)$products_options_id . "'");
	$customers_values = tep_db_fetch_array($customers);

	return $customers_values['products_options_name'];
}
//geting products options value Name
function tep_get_products_options_value_name($products_options_values_id){
	$sql = tep_db_query('SELECT products_options_values_name FROM `products_options_values` WHERE products_options_values_id="'.(int)$products_options_values_id.'" ');
	$row = tep_db_fetch_array($sql);
	return $row['products_options_values_name'];
}

/// get the how many character (specific) in it is
function find_title($body)
{
	$size=strlen($body);
	$string=0;
	if($size)
	{

		for($i=0;$i<$size;$i++)
		{
			$char=substr($body,$i,4);
			if($char=="!##!"){
				$string=$string+1;

			}

		}
	}else{
		$string=0;
	}
	return 	$string;
}

function date_add_day($length,$format,$date_passed){
	$new_timestamp = -1;
	if($date_passed != ''){
		$date_passed_array = explode('/',$date_passed);
		$date_actual["mon"] = $date_passed_array[0];
		$date_actual["mday"] = $date_passed_array[1];
		$date_actual["year"] = $date_passed_array[2];


		switch(strtolower($format)){
			case 'd':
				$new_timestamp = @mktime(0,0,0,$date_actual["mon"],$date_actual["mday"]+$length,$date_actual["year"]);
				break;
			case 'm':
				$new_timestamp = @mktime(0,0,0,$date_actual["mon"]+$length,$date_actual["mday"],$date_actual["year"]);
				break;
			case 'y':
				$new_timestamp = @mktime(0,0,0,$date_actual["mon"],$date_actual["mday"],$date_actual["year"]+$length);
				break;
			default:
				break;
		}

		return @date('m/d/Y',$new_timestamp);

	}else{
		return '';
	}
}


// encode the big5 tchinese.
function tep_encode_tchinese($string){
	$CharEncoding=new Encoding();
	$CharEncoding->SetGetEncoding("BIG5")||die("The encode name is error!");
	$CharEncoding->SetToEncoding("GBK")||die("The encode name is error!");
	return $CharEncoding->EncodeString($string);
}




// BOF: WebMakers.com Added: Downloads Controller
require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
// EOF: WebMakers.com Added: Downloads Controller


require(DIR_WS_FUNCTIONS . 'general_addon.php');
// ** GOOGLE CHECKOUT**
// Function to store configuration values(shipping options) using
// checkboxes in the Administration Tool

function gc_cfg_select_multioption($select_array, $key_value, $key = '') {

	for ($i=0; $i<sizeof($select_array); $i++) {
		$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
		$string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
		$key_values = explode( ", ", $key_value);
		if ( in_array($select_array[$i], $key_values) ) $string .= ' CHECKED';
		$string .= '>' . $select_array[$i];
	}
	$string .= '<input type="hidden" name="' . $name . '" value="--none--">';
	return $string;
}


// Custom Function to store configuration values (shipping default values)
function gc_compare($key, $data)
{
	foreach($data as $value) {
		list($key2, $valor) = explode("_VD:", $value);
		if($key == $key2)
		return $valor;
	}
	return '0';
}
// perhaps this function must be moved to googlecheckout class, is not too general
function gc_cfg_select_shipping($select_array, $key_value, $key = '') {

	//add ropu
	// i get all the shipping methods available!
	global $PHP_SELF,$language,$module_type;

	$module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';

	$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
	$directory_array = array();
	if ($dir = @dir($module_directory)) {
		while ($file = $dir->read()) {

			if (!is_dir($module_directory . $file)) {
				if (substr($file, strrpos($file, '.')) == $file_extension) {
					$directory_array[] = $file;
				}
			}
		}
		sort($directory_array);
		$dir->close();
	}

	$installed_modules = array();
	$select_array = array();
	for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
		$file = $directory_array[$i];

		include_once(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
		include_once($module_directory . $file);

		$class = substr($file, 0, strrpos($file, '.'));
		if (tep_class_exists($class)) {
			$module = new $class;
			//echo $class;
			if ($module->check() > 0) {

				$select_array[$module->code] = array('code' => $module->code,
				'title' => $module->title,
				'description' => $module->description,
				'status' => $module->check());
			}
		}
	}
	require_once (DIR_FS_CATALOG . 'includes/modules/payment/googlecheckout.php');
	$googlepayment = new googlecheckout();

	$ship_calcualtion_mode = (count(array_keys($select_array)) > count(array_intersect($googlepayment->shipping_support, array_keys($select_array)))) ? true : false;
	if(!$ship_calcualtion_mode) {
		return '<br/><i>'. GOOGLECHECKOUT_TABLE_NO_MERCHANT_CALCULATION . '</i>';
	}

	$javascript = "<script language='javascript'>
						
					function VD_blur(valor, code, hid_id){
						var hid = document.getElementById(hid_id);
						valor.value = isNaN(parseFloat(valor.value))?'':parseFloat(valor.value);
						if(valor.value != ''){ 
							hid.value = code + '_VD:' + valor.value;
					//		valor.value = valor.value;	
					//		hid.disabled = false;
						}else {		
							hid.value = code + '_VD:0';
							valor.value = '0';			
						}
			
			
					}
			
					function VD_focus(valor, code, hid_id){
						var hid = document.getElementById(hid_id);		
					//	valor.value = valor.value.substr((code  + '_VD:').length, valor.value.length);
						hid.value = valor.value.substr((code  + '_VD:').length, valor.value.length);				
					}
	
					</script>";


	$string .= $javascript;

	$key_values = explode( ", ", $key_value);

	foreach($select_array as $i => $value){
		if ( $select_array[$i]['status'] && !in_array($select_array[$i]['code'], $googlepayment->shipping_support) ) {
			$name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
			$string .= "<br><b>" . $select_array[$i]['title'] . "</b>"."\n";
			if (is_array($googlepayment->mc_shipping_methods[$select_array[$i]['code']]['domestic_types'])) {
				foreach($googlepayment->mc_shipping_methods[$select_array[$i]['code']]['domestic_types'] as $method => $method_name) {
					$string .= '<br>';

					// default value
					$value = gc_compare($select_array[$i]['code'] . $method, $key_values);
					$string .= '<input size="5"  onBlur="VD_blur(this, \'' . $select_array[$i]['code']. $method . '\', \'hid_' . $select_array[$i]['code'] . $method . '\' );" onFocus="VD_focus(this, \'' . $select_array[$i]['code'] . $method . '\' , \'hid_' . $select_array[$i]['code'] . $method .'\');" type="text" name="no_use' . $method . '" value="' . $value . '"';
					$string .= '>';
					$string .= '<input size="10" id="hid_' . $select_array[$i]['code'] . $method . '" type="hidden" name="' . $name . '" value="' . $select_array[$i]['code'] . $method . '_VD:' . $value . '"';
					$string .= '>'."\n";
					$string .= $method_name;
				}
			}
		}
	}
	return $string;
}

// ** END GOOGLE CHECKOUT **

// replace of HtmlSpecialChars
function tep_htmlspecialchars($str){
	//return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
	return preg_replace("/&amp;/", "&", htmlspecialchars($str,ENT_QUOTES));
}

////
// Creates a pull-down list of countries
function tep_get_country_list($name, $selected = '', $parameters = '') {
	global $country;
	/*
	if(!tep_not_null($selected)){
	if(!(int)$country){
	$selected="44";	//China
	}else{
	$selected = $country;
	}
	}*/

	$countries_selected = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where countries_iso_code_2 = 'US' || countries_iso_code_2 = 'CN' || countries_iso_code_2 = 'CA' || countries_iso_code_2 = 'HK' || countries_iso_code_2 = 'TW' order by countries_name ASC");
	$countries_array = array();

	if(!(int)$country){
		$countries_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
	}
	while($countries_selected_values = tep_db_fetch_array($countries_selected)){
		$countries_array[] = array('id' => $countries_selected_values['countries_id'],
		'text' => $countries_selected_values['countries_name']);
	}
	/*$countries_array = array(array('id' => $countries_selected_values['countries_id'],
	'text' => $countries_selected_values['countries_name']));
	*/
	$countries_array[] = array('id' => '0',
	'text' => '');

	$countries = tep_get_countries();

	for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
		if($countries[$i]['id']!=38 && $countries[$i]['id']!=44 && $countries[$i]['id']!=96 && $countries[$i]['id']!=206 &&  $countries[$i]['id']!=223){
			$countries_array[] = array('id' => $countries[$i]['id'], 'text' => $countries[$i]['text']);
		}
	}
	return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
}

//取得soe类别目录树
function tep_get_seo_class_tree($parent_id = '0', $spacing = '', $exclude = '', $class_tree_array = '', $include_itself = false, $loop = false) {

	if (!is_array($class_tree_array)) $class_tree_array = array();
	if ( (sizeof($class_tree_array) < 1) && $exclude!='0'){
		//$class_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
	}

	if ($include_itself && !(int)$parent_id ) {
		$category_query = tep_db_query("select  class_id,class_name from  `seo_class` where class_id = '" . (int)$parent_id . "'");
		$category = tep_db_fetch_array($category_query);
		$class_tree_array[] = array('id' => $parent_id, 'text' => $category['class_name']);
	}

	$categories_query = tep_db_query("select  class_id,class_name from `seo_class` where parent_id = '" . (int)$parent_id . "' order by class_id ");
	while ($categories = tep_db_fetch_array($categories_query)) {
		if ($exclude != $categories['class_id']) $class_tree_array[] = array('id' => $categories['class_id'], 'text' => $spacing . $categories['class_name']);
		if($loop!=false){
			$class_tree_array = tep_get_seo_class_tree($categories['class_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $class_tree_array, $include_itself, $loop);
		}
	}
	return $class_tree_array;
}
//取得当前seo目录路径
function tep_get_seo_top_to_now_class($class_id, $class_array=''){
	if(!is_array($class_array)){
		$class_array = array();
	}
	$array_count = count($class_array);
	if(!(int)$class_id){
		$class_array[$array_count] = array('id' => '0', 'text' => TEXT_TOP);
	}else{
		$sql = tep_db_query('select * from `seo_class` where class_id = "'.(int)$class_id.'" ');
		$row = tep_db_fetch_array($sql);

		if((int)$row['class_id']){
			$class_array[$array_count] = array('id' => $row['class_id'], 'text' => $row['class_name']);
			$class_array = tep_get_seo_top_to_now_class((int)$row['parent_id'], $class_array);
		}
	}
	return $class_array;
}

////
//检查产品是否为买二送一团
//TABLES: products
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
 * // Return a product's surcharge	//取得买二送1产品附加费
// TABLES: products
 */
function get_products_surcharge($products_id){
	$products_id = tep_get_prid($products_id);
	$surcharge_query = tep_db_query("select products_surcharge from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
	$surcharge_values = tep_db_fetch_array($surcharge_query);

	return $surcharge_values['products_surcharge'];
}

require_once(DIR_FS_CATALOG.'includes/functions/gb2312_zhi.php');
require_once(DIR_FS_CATALOG.'includes/functions/big5_zhi.php');

function db_to_html($str){	//$str的编码是简体的gb2312
	if(CHARSET=='gb2312'){ return $str;}
	$gb = 'gb2312';
	$str = special_string_replace_for_gb2312($str);
	$str = iconv($gb,CHARSET.'//IGNORE',$str);
	//$str = special_string_replace_for_gb2312($str,'1');
	return $str;
}

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

//当结伴同游支付成功后或状态更改后通知成员客户
function send_travel_pay_staus_mail($order_id){
	global $currencies;
	//howard add use session+ajax send email
	$e_sql = tep_db_query('SELECT * FROM `orders_travel_companion` WHERE  orders_id="'.(int)$order_id.'" Order By products_id ASC, orders_travel_companion_status ASC ');
	$e_rows = tep_db_fetch_array($e_sql);

	$to_email_address ='';
	$to_email_names = array();
	$html_text = '<table border="1" cellspacing="0" cellpadding="5"><tr><td style="font-size:14px"><b>'.db_to_html('团号').'</b></td><td style="font-size:14px"><b>'.db_to_html('旅客帐号').'</b></td><td style="font-size:14px"><b>'.db_to_html('旅客姓名').'</b></td><td style="font-size:14px"><b>'.db_to_html('应付款').'</b></td><td style="font-size:14px"><b>'.db_to_html('状态').'</b></td></tr>';

	$n_pid='';

	$pay_done = true;
	do{
		if((int)$e_rows['customers_id']){
			$mail_string = tep_get_customers_email((int)$e_rows['customers_id']);
			if(!strstr($to_email_address,$mail_string)){
				$to_email_address .= $mail_string.',';
				if (preg_match("/[^\[]+\[([^\]]+)\]/", $e_rows['guest_name'],$matchs)) {
					$to_email_names[] = $matchs[1];
				} else {
					$to_email_names[] = '';
				}
				 
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
		//echo $to_email_address;
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
			//echo '$to_name=' . $to_name;
			tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
		}

	}
	//howard add use session+ajax send email end
}

//更新各个景点目录的结伴同游贴子总数
function update_categories_tc_bbs_total(){
	$sql = tep_db_query('SELECT categories_id FROM `travel_companion` WHERE categories_id>0 AND `status`="1" ');
	$cat_array = array();
	while($rows=tep_db_fetch_array($sql)){
		$categories = array();
		tep_get_parent_categories($categories, $rows['categories_id']);
		if(count($categories)){
			foreach((array)$categories as $key => $value){
				if(!tep_not_null($cat_array[$value])){
					$cat_array[$value] = 1;
				}else{
					$cat_array[$value]++;
				}
			}

		}

		if(!tep_not_null($cat_array[$rows['categories_id']])){
			$cat_array[$rows['categories_id']] = 1;
		}else{
			$cat_array[$rows['categories_id']]++;
		}

	}
	foreach((array)$cat_array as $key => $value){
		tep_db_query("UPDATE `categories` SET `tc_bbs_total` = '".(int)$value."' WHERE `categories_id` = '".$key."' ;");
	}
	tep_db_query('OPTIMIZE TABLE `categories`');
	return true;
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

//从文章内容中取得词库的复选框html代码
function get_thesaurus_checkbox($thesaurus_ids, $full_description, $checkbox_name ){
	$thesaurus_text_list ='';
	$thesaurus_ids_arr = explode(',',$thesaurus_ids);
	$inner_link_sql = tep_db_query('SELECT thesaurus_id,thesaurus_text FROM `keyword_thesaurus` WHERE use_inner_link=1 AND thesaurus_state=1 ORDER BY thesaurus_text_length DESC, thesaurus_id DESC ');
	while($inner_link_rows = tep_db_fetch_array($inner_link_sql)){
		if(preg_match('/'.preg_quote($inner_link_rows['thesaurus_text'],'/').'/',$full_description)){
			$checked='';
			for($i=0; $i<count($thesaurus_ids_arr); $i++){
				if($thesaurus_ids_arr[$i]==$inner_link_rows['thesaurus_id'] && (int)$thesaurus_ids_arr[$i]){
					$checked=' checked="checked" ';
					break;
				}
			}
			$thesaurus_text_list .= '<label><input name="'.$checkbox_name.'[]" type="checkbox" value="'.$inner_link_rows['thesaurus_id'].'" '.$checked.' />'.$inner_link_rows['thesaurus_text']."</label>&nbsp;&nbsp;";
		}
	}
	return $thesaurus_text_list;
}
function getimgHW3hw_wh($PathFile,$dstW,$dstH) //参数分别是路径档,设宽度和高度上限
{
	$info = @getimagesize($PathFile);
	if ( ($info[0] > $dstW) || ($info[1] > $dstH) ) //宽度比设置的大,或高度比设置的大
	{
		if(($info[0] - $info[1]) >= 0) //宽比高大
		{
			$H = round($info[1]/$info[0]*$dstW);
			$W = $dstW;
		}
		elseif(($info[0] - $info[1]) < 0) //宽比高小
		{
			$W = round($info[0]/$info[1]*$dstH);
			$H = $dstH;
		}
	}
	else
	{
		$W = $info[0];
		$H = $info[1];
	}
	// $W = $dstW;
	// $H = $dstH;
	//$wh="@".$W."@".$H;
	$wh=$W."@".$H;
	return $wh;
}

function tep_get_products_eticket_pickup($product_id, $language_id) {
	$product_query = tep_db_query("select eticket_pickup from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return $product['eticket_pickup'];
}

function tep_date_short_with_day($raw_date, $date_forate_type = DATE_FORMAT_WITH_DAY) {
	if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

	$year = substr($raw_date, 0, 4);
	$month = (int)substr($raw_date, 5, 2);
	$day = (int)substr($raw_date, 8, 2);
	$hour = (int)substr($raw_date, 11, 2);
	$minute = (int)substr($raw_date, 14, 2);
	$second = (int)substr($raw_date, 17, 2);

	if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
		return date($date_forate_type, mktime($hour, $minute, $second, $month, $day, $year));
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
				$result[0][$k] = $phone;
				continue;
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
function check_time($str){
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
function check_date($str){
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
 * 
 * 判断当前订单的followUp Team ,
 * 0 NotSet 1 US 2 CN Day 3 CN Night
 * @param unknown_type $orderId
 * @param unknown_type $returnIcon 是否返回 为ICON
 */
function tep_get_order_followup($orderId , $returnIcon = false){
	$followup_exclude_arr = array('100002','100097','100095','6','100005','100006','100077');
	$followup_array_us =array('100009','100072','100004','100019','100085','100088','100089','100092','100021','100046','100045','100094');
	$followup_array_cn=array(/*'100094',*/'100083','100036','100012','100026','100084','100086','100087','100090','100091','100057','100075','100020','100054','100060');

	$q = tep_db_query("SELECT followup_team_type , orders_status FROM ".TABLE_ORDERS." WHERE orders_id = ".intval($orderId));
	$info = tep_db_fetch_array($q);

	if(in_array($info['orders_status'],$followup_exclude_arr)) {
		return ""; //todo:Task-13_订无论这些订单之前处在何标签下，当状态更新为以上7种中任何一种时则不显示图标
	}

	$doUpdate = false;
	$followTeam = -1 ;
	$info['followup_team_type'] = intval($info['followup_team_type']);
	if(!in_array($info['followup_team_type'],array(0,1,2,3))){
		$info['followup_team_type'] = 0;
	}
	if(in_array($info['orders_status']	,$followup_array_us)){
		if($info['followup_team_type'] == 0){
			$doUpdate = true ;
			$followTeam = 1 ;
		}else{
			$followTeam = $info['followup_team_type'];
		}
	}elseif(in_array($info['orders_status']	,$followup_array_cn)){
		if($info['followup_team_type'] == 0){
			$doUpdate = true ;
			$followTeam = 2 ;
		}else{
			$followTeam = $info['followup_team_type'];
		}
	}else{
		$followTeam = $info['followup_team_type'] ;
	}
	//如果需要更新则自动更新followUp
	if($doUpdate==true && $followTeam != 0 ){
		tep_db_query("UPDATE ".TABLE_ORDERS." SET followup_team_type = '".$followTeam."' WHERE orders_id = '".intval($orderId)."'");
	}
	if($returnIcon){
		if($followTeam == 1) $icon = '<img src="images/icons/us.png" title="US Follow Up"  alt="US Follow Up" />';
		elseif($followTeam == 2)$icon = '<img src="images/icons/cnds.png" title="China Day Shift Follow Up"  alt="China Day Shift Follow Up" />';
		elseif($followTeam == 3)$icon = '<img src="images/icons/cnns.png" title="China Night Shift Follow Up"  alt="China Night Shift Follow Up" />';
		else $icon ='';
		return $icon ;
	}else{
		return $followTeam;
	}
}
/**
 *获取指定产品的nearby_attraction信息
 * @param int $product_id 产品编码
 * @param int $language_id 语言编码
 **/
function tep_get_products_hotel_nearby_attractions($product_id, $language_id) {
	$product_query = tep_db_query("select products_hotel_nearby_attractions from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
	$product = tep_db_fetch_array($product_query);

	return enleve_accent($product['products_hotel_nearby_attractions']);
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

/**
 * 取得我们的联系电话信息
 *@return array
 */
function tep_get_us_contact_phone(){
	$data = array();
	$data[] = array('name'=>'美加免费：','phone'=>'1-888-887-2816', 'class'=>'s_1');
	$data[] = array('name'=>'国际热线：','phone'=>'001-626-898-7800', 'class'=>'s_2');
	$data[] = array('name'=>'中国免费：','phone'=>'0086-4006-333-926', 'class'=>'s_3');
	$data[] = array('name'=>'台湾：','phone'=>'(02)40502999转8991020868', 'class'=>'s_4');
	$data[] = array('name'=>'Fax：','phone'=>'001-626-569-0580', 'class'=>'s_4');
	
	return $data;
}


/**
 * 取得出发城市名称
 *
 * @param int $city_id 
 * @param boolean $check_status 检测当前传进来的ID所对应的城市，是否可做为出发城市
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
?>