<?php
/*
  $Id: c_orders.php,v 1.1.   

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/
?>
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


function sbs_get_zone_name($country_id, $zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

 // Returns an array with countries
// TABLES: countries
  function sbs_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if ($countries_id) {
      if ($with_iso_codes) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
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
function sbs_get_country_list($name, $selected = '', $parameters = '') {
   $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
   $countries = sbs_get_countries();
   $size = sizeof($countries);
   for ($i=0; $i<$size; $i++) {
     $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
   }

   return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
}


////
// Alias function to tep_get_countries, which also returns the countries iso codes
 /* function tep_get_countries_with_iso_codes($countries_id) {
    return tep_get_countries($countries_id, true);
  }*/

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_ship_method
  //
  // Arguments   : string	any string
  //
  // Return      : Array with the shipping methods  id, text
  //
  // Description : Function to get shipping methods
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  
 function tep_get_ship_method() {
  $orders_ship_method = array();
  $orders_ship_method_array = array();
  $orders_ship_method_query = tep_db_query("select ship_method from orders_ship_methods");
  while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
    $orders_ship_method[] = array('id'   => $orders_ship_methods['ship_method'],
                                  'text' => $orders_ship_methods['ship_method']);
    $orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
  }
        $return($orders_ship_method_array[$orders_ship_methods['ship_method']]) ;
     } 
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_pay_method
  //
  // Arguments   : string	any string
  //
  // Return      : Array with the payment methods  id, text
  //
  // Description : Function to get payment methods
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////

function tep_get_pay_method() {
  $orders_pay_method = array();
  $orders_pay_method_array = array();
  $orders_pay_method_query = tep_db_query("select pay_method from orders_pay_methods");
  while ($orders_pay_methods = tep_db_fetch_array($orders_pay_method_query)) {
    $orders_pay_method[] = array('id'   => $orders_pay_methods['pay_method'],
                                  'text' => $orders_pay_methods['pay_method']);
    $orders_pay_method_array[$orders_pay_methods['pay_method']] = $orders_pay_methods['pay_method'];
  }
      $return ($orders_pay_method_array[$orders_pay_methods['pay_method']]) ;
     }  
?>