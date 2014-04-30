<?php
/*
  $Id: general.php,v 1.1.1.1 2004/03/04 23:40:50 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
function tep_return_broken($bad_string) {
  $char_count = 35;
  $formatted_string = '';
  $countbefore = 0;
  $i = 0;
  while ($i < strlen($bad_string)) {
    $formatted_string .= $bad_string[$i];
    $countbefore ++;
    if ($countbefore > $char_count) {
      $formatted_string .= chr(13);
      $countbefore = 0;
    }
    $i ++;
  } // End of while loop on strlen of bad string
  return $formatted_string;
}
////
// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }

////
// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)) == HTTP_SERVER . DIR_WS_HTTP_CATALOG) { // NONSSL url
        $url = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . substr($url, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)); // Change it to SSL
      }
    }
    
    $url =  str_replace("&amp;", "&", $url);
    
    header('Location: ' . $url);
    
    tep_exit();
  }

////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
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
	
	return $string;

    //return preg_replace("/[<>]/", '_', $string);
  }

////
// Return a random row from a database query
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

////
// Return a product's name
// TABLES: products
  function tep_get_products_name($product_id, $language = '') {
    global $languages_id;

    if (empty($language)) $language = $languages_id;

    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }
//

/**
 * 取得产品的第一张图片
 *
 * @param unknown_type $product_id
 * @return unknown
 */
  function tep_get_products_image($product_id) {
    global $languages_id;

    $product_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_image'];
  }
  
//gets manufacurs name for a manufacture
 function tep_get_manufacturers_name($manufacturers_id) {

    $manufactures_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
    $manufactures = tep_db_fetch_array($manufactures_query);

    return $manufactures['manufacturers_name'];
  }
  
////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products
  function tep_get_products_special_price($product_id) {
    $product_query = tep_db_query("select products_price, products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    if (tep_db_num_rows($product_query)) {
      $product = tep_db_fetch_array($product_query);
    $product_price = $product['products_price'];
    } else {
    return false;
    }
// Eversun mod for sppc and qty price breaks
//    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status = '1'");
  global $sppc_customer_group_id;

    if(!tep_session_is_registered('sppc_customer_group_id')) {
    $customer_group_id = '0';
    } else {
      $customer_group_id = $sppc_customer_group_id;
    }
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status = '1' and customers_group_id = '" . (int)$customer_group_id . "'");
// Eversun mod for sppc and qty price breaks
    
    if (tep_db_num_rows($specials_query)) {
      $special = tep_db_fetch_array($specials_query);
    $special_price = $special['specials_new_products_price'];
    } else {
    $special_price = false;
    }

    if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
      return $special_price;
    }

    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
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

// Return a product ID from a product ID with attributes
/*function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
      return $pieces[0];
    } else {
      return false;
    }
  }

*/////


////
// Return a product's stock
// TABLES: products
  function tep_get_products_stock($products_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
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
  function tep_get_all_get_params($exclude_array = '') {
    global $HTTP_GET_VARS;

    if (!is_array($exclude_array)) $exclude_array = array();

    $get_url = '';
    if (is_array($HTTP_GET_VARS) && (sizeof($HTTP_GET_VARS) > 0)) {
      reset($HTTP_GET_VARS);
      while (list($key, $value) = each($HTTP_GET_VARS)) {
        if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&amp;';
        }
      }
    }

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
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
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
     
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);
		
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

    
//Eversun mod for sppc and qty price breaks
//  global $customer_zone_id, $customer_country_id;
    global $customer_zone_id, $customer_country_id, $sppc_customer_group_tax_exempt;

     if(!tep_session_is_registered('sppc_customer_group_tax_exempt')) {
     $customer_group_tax_exempt = '0';
     } else {
     $customer_group_tax_exempt = $sppc_customer_group_tax_exempt;
     }

     if ($customer_group_tax_exempt == '1') {
       return 0;
     }
//Eversun mod end for sppc and qty price breaks
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

    $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    if (tep_db_num_rows($child_categories_query)) {
      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
        $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }

////
// Return true if the category has subcategories
// TABLES: categories
  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

//// 取得地址薄格式id
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

//// 格式化输出地址
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
	
	$telephone = tep_output_string_protected($address['telephone']);
	$mobile_phone = tep_output_string_protected($address['mobile_phone']);

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

//// 地址薄信息
// Return a formatted address
// TABLES: customers, address_book
  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n", $format_id='') {
    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id,
	entry_telephone as telephone, entry_mobile_phone as mobile_phone
	 from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
    $address = tep_db_fetch_array($address_query);
	if(!tep_not_null($format_id)){
		$format_id = tep_get_address_format_id($address['country_id']);
	}
    return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }

  function tep_row_number_format($number) {
    if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

    return $number;
  }

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
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
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

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
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
// Parse search string into indivual objects
  function tep_parse_search_string($search_str = '', &$objects) {
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = split('[[:space:]]+', $search_str);
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

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
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

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }

    // Send message
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
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

// Check if product has attributes
  function tep_has_product_subproducts($products_id) {
    $subproducts_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_parent_id  = '" . (int)$products_id . "'");
    $subproducts = tep_db_fetch_array($subproducts_query);

    if ($subproducts['count'] > 0) {
      return true;
    } else {
      return false;
    }
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
//require(DIR_WS_FUNCTIONS . 'downloads_controller.php');

 //require(DIR_WS_FUNCTIONS . FILENAME_DOWNLOADS_CONTROLLER);
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
// Decode string encoded with htmlspecialchars()
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
  return  (tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning);
  }
  
  // Draw a pulldown for Option Types
  function draw_optiontype_pulldown($name, $default = '') {
    $values = array();
    $values[] = array('id' => 0, 'text' => 'Select');
    $values[] = array('id' => 1, 'text' => 'Text');
    $values[] = array('id' => 2, 'text' => 'Radio');
    $values[] = array('id' => 3, 'text' => 'Checkbox');
    $values[] = array('id' => 4, 'text' => 'Text Area');
    
    return tep_draw_pull_down_menu($name, $values, $default);
  }
  
  //CLR 030312 add function to translate type_id to name
  // Translate option_type_values to english string
  function translate_type_to_name($opt_type) {
    if ($opt_type == 0) return 'Select';
    if ($opt_type == 1) return 'Text';
    if ($opt_type == 2) return 'Radio';
    if ($opt_type == 3) return 'Checkbox';
    if ($opt_type == 4) return 'Text Area';
    return 'Error ' . $opt_type;
  }

  function tep_get_box_heading($infobox_id, $languages_id) {
      $configuration_query12 = tep_db_query("select box_heading from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . (int)$infobox_id . "' and languages_id = '" . (int)$languages_id . "'");
      $configuration12 = tep_db_fetch_array($configuration_query12);
  
      return $configuration12['box_heading'];
    }
  
// Contact Us Email Subject : DMG
// PassionSeed Contact Us Email Subject begin
  function tep_get_email_subjects_list($subjects_array = '') {
    if (!is_array($subjects_array)) $subjects_array = array();

    $subjects_query = tep_db_query("select email_subjects_id, email_subjects_name, email_subjects_category from " . TABLE_EMAIL_SUBJECTS . " where email_subjects_category = '2' order by email_subjects_name");
    while ($subjects = tep_db_fetch_array($subjects_query)) {
      $subjects_array[] = array('id' => $subjects['email_subjects_name'], 'text' => $subjects['email_subjects_name']);
    }

    return $subjects_array;
  }
// PassionSeed Contact us Email Subject end

// Randomizer for specials modules ($products_id, $max_displayed)
  function cre_random_array($random,$i){
    srand((float) microtime() * 10000000);
    $rand_keys = array_rand($random, $i);
    $res = array();
    if($i > 1){
      for($a=0;$a<$i;$a++){
       $res[] = $random[$rand_keys[$a]];
      }
    }else{
      $res[] = $random[$rand_keys];   
    }
    return $res;
  }

// replace of HtmlSpecialChars 
	function tep_htmlspecialchars($str){
		return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
	}

//根据设计ID取得产品表的任何1个字段资料
	function tep_get_products_field($entries_id,$products_fields){
		if(!tep_not_null($entries_id) || !tep_not_null($products_fields)){ die("err:plaese check entries_id and products_fields"); }
		$products_query = tep_db_query("select $products_fields FROM " . TABLE_PRODUCTS . " WHERE entries_id = '".(int)$entries_id."' limit 1 ");
		$products = tep_db_fetch_array($products_query);
		if(tep_not_null($products[$products_fields])){
			return $products[$products_fields];
		}else{ return false; }
	}
//根据设计ID取得产品表缩略图
	function tep_get_products_image_med($entries_id){
		$products_query = tep_db_query("select products_image_med FROM " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_ATTRIBUTES." pa WHERE p.entries_id = '".(int)$entries_id."' AND p.products_image_med!='' AND pa.products_id = p.products_id ORDER BY products_options_sort_order ASC limit 1 ");
		$products = tep_db_fetch_array($products_query);
		if(tep_not_null($products['products_image_med'])){
			return $products['products_image_med'];
		}else{ return false; }
		
	}

//取得某个设计的作者资料
	function tep_get_entries_author($customers_id){
		$customers_query = tep_db_query('SELECT customers_lastname FROM '.TABLE_CUSTOMERS.' WHERE customers_id="'.(int)$customers_id.'" LIMIT 1 ');
		$customers_row = tep_db_fetch_array($customers_query);
		return $customers_row['customers_lastname'];
	}
//计算某个设计得分，目前暂计期总分 平均分
	function tep_get_entries_score($entries_id){
		$customers_query = tep_db_query('SELECT entries_score,entries_score_frequency FROM '.TABLE_ENTRIES.' WHERE entries_id="'.(int)$entries_id.'" LIMIT 1 ');
		$customers_row = tep_db_fetch_array($customers_query);
		
		$average_score = $customers_row['entries_score'] / max(1, (int)$customers_row['entries_score_frequency']);
		$average_score = (int)($average_score * 100) / 100;
		
		$average_int = $average_score;
		//$average_foat = $average_score - $average_int;
		
		//不管分数是多少，都有5个星形，灰色代表空。半灰代表小数。金星代表整数，1个金星一分。
		
		$img = '<div>';
		for($i=0; $i<5; $i++){
			$img_name = 'star_2.gif';
			$i_j = $average_int - $i;
			if( $i_j >= 1){ $img_name = 'star_1.gif';}
			elseif( $i_j < 1 && $i_j > 0 ){ $img_name = 'star_3.gif';}

			$img .= '<img src="'.DIR_WS_ICONS.$img_name.'" />';
		}
		$img .='</div>';
		
		$html = sprintf(Entries_Score_Text, tep_db_output($customers_row['entries_score']),tep_db_output($customers_row['entries_score_frequency']),$average_score );
		return $img.$html;
	}

//自动创建设计ID文件夹,返回false或文件夹路径
	function tep_create_dir($entries_id){
		if(is_dir(DIR_FS_FANG_AN_IMG.$entries_id)){
			return DIR_FS_FANG_AN_IMG.$entries_id;			
		}else{
			if(mkdir(DIR_FS_FANG_AN_IMG.$entries_id, 0777)){
				return DIR_FS_FANG_AN_IMG.$entries_id;
			}else{ return false;}
		}
	}
//自动添加某个设计的点击量
	function tep_auto_add_hits_of_entries($entries_id){
		$customers_query = tep_db_query('UPDATE '.TABLE_ENTRIES.' SET `entries_clicks` = `entries_clicks`+1 WHERE `entries_id` = "'.(int)$entries_id.'" LIMIT 1 ;');
	}

//我收藏的设计or产品函数,默认为收藏的设计1
function tep_my_fav_array( $favorite_type = "1", $startRow = '', $maxRow = '', $order='', $search_where=''){
	global $customer_id; 
	
	//准备存放结果的数组
	$favorite_array = array();
	//要使用的字段
	$Fields = 'e.entries_id ,
			   e.entries_name ,
			   e.entries_p_name ,
			   e.entries_pic_sm ,
			   e.entries_customers_id ,
			   e.entries_score_frequency ,
			   e.zuo_zhe_name ,
			   e.zuo_zhe_web ,
			   f.favorite_id
			   ';
	$Fields_array = explode(',',$Fields);
	
	$order_by = "";
	if(tep_not_null($order)){
		$order_by = $order;
	}
	
	$limit = "";
	if((int)$startRow >=0 && (int)$maxRow > 0 ){
		$limit = ' limit '.(int)$startRow.', '.($maxRow);
	}
	$favorite_e_query = tep_db_query('select '.$Fields.' from '.
		TABLE_FAVORITE.' f , '.
		//TABLE_CUSTOMERS.' c ,'.
		TABLE_ENTRIES. ' e  '.
		' where f.customers_id="'.(int)$customer_id.'" AND f.favorite_type="'.(int)$favorite_type.'" AND f.entries_id=e.entries_id '.$search_where.
		$order_by.$limit);
	$favorite_e_rows = tep_db_fetch_array($favorite_e_query);
	
 	if($favorite_e_rows['favorite_id'] > 0){
		$i = 0;
		do{
			for($j=0; $j<count($Fields_array); $j++){
				
				$field = trim(preg_replace('/(.*\.)/','',$Fields_array[$j]));
				$favorite_array[$i][$field] = tep_db_output($favorite_e_rows[$field]) ;
			}
			if($favorite_e_rows['entries_customers_id']>0){
				$favorite_array[$i]['customers_lastname'] = tep_db_output(tep_get_entries_author($favorite_e_rows['entries_customers_id']));	//设计师的昵称
			}else{
				$favorite_array[$i]['customers_lastname'] = tep_db_output($favorite_e_rows['zuo_zhe_name']);	//设计师的昵称
			}
			$i++;
		}while($favorite_e_rows = tep_db_fetch_array($favorite_e_query));
	}
 	return $favorite_array;
}

//自动计算某个用户提交的设计总数
function tep_get_customer_entries_sum($customers_id){
	$sql_query = tep_db_query('select count(*) as total from '. TABLE_ENTRIES. ' where entries_customers_id ="'.(int)$customers_id.'" ');
	$sum = tep_db_result($sql_query,"0","total");
	return $sum;
}

//自动计算某个用户提交的已生成成品的设计总数
function tep_get_customer_entries_to_p_sum($customers_id){
	$sql_query = tep_db_query('select count(*) as total from '. TABLE_ENTRIES. ' where entries_customers_id ="'.(int)$customers_id.'" AND entries_status = "5" ');
	$sum = tep_db_result($sql_query,"0","total");
	return $sum;
}

//自动计算某个用户提交的评论总数
function tep_get_customer_re_sum($customers_id){
	$sql_query = tep_db_query('select count(*) as total from '. TABLE_BBS_REVIEWS. ' where customers_id ="'.(int)$customers_id.'"  ');
	$sum = tep_db_result($sql_query,"0","total");
	return $sum;
}

//自动计算某个设计的成品种数，即设计生成了几个成品
function tep_get_entries2productsNumber($entries_id){
	$sql_query = tep_db_query('select count(*) as total from '. TABLE_PRODUCTS. ' where entries_id ="'.(int)$entries_id.'"  ');
	$sum = tep_db_result($sql_query,"0","total");
	return $sum;
}


//检测某个字符串是否是纯数字，本函数定义小数点不是数字。
	function tep_is_numeric($str){
		if(preg_match('/\./',$str)){
			return false;
		}else{
			if(is_numeric($str)){
				return true;
			}
			return false;
		}
	}

//取得标签库的标签组数可以是单个、多个、或全部，无分页功能
function get_db_tags($where='',$order_by = ' ORDER BY tags_hit DESC ', $limit =''){	
	//$where = ' ';
	//$order_by = ' ORDER BY tags_hit DESC ';  
	//$limit = ' ';
	$tags_query = tep_db_query('SELECT * FROM '.TABLE_TAGS.$where.$order_by.$limit);
	$tags_rows = tep_db_fetch_array($tags_query);
	
	$tags_db_array = array();
	$do = 0;
	if($tags_rows['tags_id']>0){
		do{
			$tags_db_array[$do]['id'] = (int)$tags_rows['tags_id'];
			$tags_db_array[$do]['name'] = tep_db_output($tags_rows['tags_name']);
			$tags_db_array[$do]['hit'] = (int)$tags_rows['tags_hit'];
			$do++;
		}while($tags_rows = tep_db_fetch_array($tags_query));
	}
	return $tags_db_array;
}

//给用户增加/减少T银
function add_ro_reduce_ty($customers_id, $t_y, $re_text , $date = ""){
		
		if($customers_id>0){
			if($date==""){ $date = date('Y-m-d H:i:s');}
			
			$sql_data_array = array(														
									'customers_id' => tep_db_prepare_input($customers_id),
									'botters_number' => (int)$t_y,
									'botters_content ' => tep_db_prepare_input($re_text),
									'botters_date' => $date
									);
			tep_db_perform(TABLE_CUSTOMERS_BANK_BOTTERS, $sql_data_array);
			
			$tmp_query = tep_db_query('SELECT sum( botters_number ) AS bank_deposits FROM '.TABLE_CUSTOMERS_BANK_BOTTERS.' where customers_id = "'.(int)$customers_id.'" ');
			$tep_row = tep_db_fetch_array($tmp_query);
	
			tep_db_query("update ".TABLE_CUSTOMERS_BANK. " set customers_bank_deposits =".(int)$tep_row['bank_deposits']." where customers_id='".(int)$customers_id."' ");
		}

}

// 查询某用户总积分
function get_customer_deposits($customer_id){
	$where = ' where customers_id="'.(int)$customer_id.'" ';
	$bank_query = tep_db_query('SELECT customers_bank_deposits FROM '.TABLE_CUSTOMERS_BANK. $where .' limit 1');
	$bank_rows = tep_db_fetch_array($bank_query);
	return (int)$bank_rows['customers_bank_deposits'];
}

//列出用户已消费的订单总额[已完成交易]
function ok_order_total($customers_id){
	$sql_qurey = tep_db_query(' select sum(ot.value) as total FROM orders o, orders_total ot where o.orders_id =  ot.orders_id AND o.customers_id ="'.(int)$customers_id.'" AND ot.class ="ot_total" AND o.orders_status ="3" ');
	$row = tep_db_fetch_array($sql_qurey);
	return $row['total'];
}		

//列出用户已购买的产品总件数[已完成交易]
function ok_order_products_total($customers_id){
	$sql_qurey = tep_db_query(' select sum(op.products_quantity) as total FROM orders o, orders_products op where o.orders_id =  op.orders_id AND o.customers_id ="'.(int)$customers_id.'" AND o.orders_status ="3" ');
	$row = tep_db_fetch_array($sql_qurey);
	return $row['total'];
}		



//添加用户提醒文字customers_notify
function tep_add_notify($customers_id, $html_text ){
	if($customers_id>0){
		$customers_id = (int)$customers_id;
		$text = tep_db_prepare_input($html_text);
		if($customers_id<1 || strlen($text)<1){ return false; }
		
		$insert_array = array('customers_id' => $customers_id,
								'notify_text' => $text,
								'notify_date' => date('Y-m-d H:i:s') );
		
		tep_db_perform(TABLE_CUSTOMERS_NOTIFY , $insert_array );
		$notify_id = tep_db_insert_id();
		$text = str_replace('$notify_id',$notify_id,$text);
		
		$update_array = array('notify_text' => $text);
		tep_db_perform(TABLE_CUSTOMERS_NOTIFY , $update_array ,'update',' notify_id = "'.$notify_id.'" ');
		
		return true;
	}else{
		return false;
	}
}



//如果当前php不支持iconv扩展则使用以下函数，只支持gb2312 to utf-8
if(!extension_loaded('iconv')){
	//iconv(CHARSET,'utf-8', $html_code)
	function iconv($char_from,$char_to,$str){
		if(strcasecmp(trim($char_from),"gb2312")==0 && strcasecmp(trim($char_to),"utf-8")==0 ){
			return gb2utf8($str);
			//echo "gb2utf8";
			//exit;
		}elseif(strcasecmp(trim($char_from),"utf-8")==0 && strcasecmp(trim($char_to),"gb2312")==0 ){
			return utf_to_gb($str);
			//echo "utf8_to_gb2312";
			//exit;
		}else{
			return $str;
			//echo "not change str";
			//exit;
		}
	}
}


//gb2312转utf-8
	
	
	function gb2utf8($gb)
	{
	$gb_filename="gb-unicode.table";
	$tmp_gbstr=file($gb_filename);
	$china_codetable=array();
	while(list($key,$value)=each($tmp_gbstr))
	{$china_codetable[hexdec(substr($value,0,6))]=substr($value,7,6);}

	//global $china_codetable;   //码表作为全局变量
	if(!trim($gb))
	return $gb;
	$ret="";
	$utf8="";
	while($gb)
	{
	if (ord(substr($gb,0,1))>127)
	{
	$_this=substr($gb,0,2);
	$gb=substr($gb,2,strlen($gb));
	$utf8=u2utf8(hexdec($china_codetable[hexdec(bin2hex($_this))-0x8080]));
	for($i=0;$i<strlen($utf8);$i+=3)
	$ret.=chr(substr($utf8,$i,3));
	}
	else
	{
	$ret.=substr($gb,0,1);
	$gb=substr($gb,1,strlen($gb));
	}
	}
	return $ret;
	}
	
	function u2utf8($c)
	{
		$str="";

		if ($c < 0x80) {
			$str.=$c;
		}

		else if ($c < 0x800) {
			$str.=(0xC0 | $c>>6);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x10000) {
			$str.=(0xE0 | $c>>12);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x200000) {
			$str.=(0xF0 | $c>>18);
			$str.=(0x80 | $c>>12 & 0x3F);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		return $str;
	
/* 	for($i=0;$i<count($c);$i++)
	$str="";
	if ($c < 0x80) {
	$str.=$c;
	}
	else if ($c < 0x800) {
	$str.=(0xC0 | $c>>6);
	$str.=(0x80 | $c & 0x3F);
	}
	else if ($c < 0x10000) {
	$str.=(0xE0 | $c>>12);
	$str.=(0x80 | $c>>6 & 0x3F);
	$str.=(0x80 | $c & 0x3F);
	}
	else if ($c < 0x200000) {
	$str.=(0xF0 | $c>>18);
	$str.=(0x80 | $c>>12 & 0x3F);
	$str.=(0x80 | $c>>6 & 0x3F);
	$str.=(0x80 | $c & 0x3F);
	}
	return $str;
 */	}


if(!function_exists('utf_to_gb')){
//utf-8转gb2312
 function utf_to_gb($instr) {
 $fp = fopen('unicode-gb.tab', 'r' );
 $len = strlen($instr);
 $outstr = '';
 for( $i = $x = 0 ; $i < $len ; $i++ ) {
  $b1 = ord($instr[$i]);
  if( $b1 < 0x80 ) {
   $outstr[$x++] = chr($b1);
#   printf( "[%02X]", $b1);
  }
  elseif( $b1 >= 224 ) { # 3 bytes UTF-8
   $b1 -= 224;
   $b2 = ord($instr[$i+1]) - 128;
   $b3 = ord($instr[$i+2]) - 128;
   $i += 2;
   $uc = $b1 * 4096 + $b2 * 64 + $b3 ;
   fseek( $fp, $uc * 2 );
   $gb = fread( $fp, 2 );
   $outstr[$x++] = $gb[0];
   $outstr[$x++] = $gb[1];
#   printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
  }
  elseif( $b1 >= 192 ) { # 2 bytes UTF-8
   printf( "[%02X%02X]", $b1, ord($instr[$i+1]) );
   $b1 -= 192;
   $b2 = ord($instr[$i]) - 128;
   $i++;
   $uc = $b1 * 64 + $b2 ;
   fseek( $fp, $uc * 2 );
   $gb = fread( $fp, 2 );
   $outstr[$x++] = $gb[0];
   $outstr[$x++] = $gb[1];
#   printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
  }
 }
 fclose($fp);
 if( $instr != '' ) {
#  echo '##' . $instr . " becomes " . join( '', $outstr) . "<br>n";
  return join( '', $outstr);
 }
}
}
?>
