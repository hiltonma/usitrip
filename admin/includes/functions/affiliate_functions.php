<?php
/*
  $Id: affiliate_functions.php,v 1.1.1.1 2004/03/04 23:39:49 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

function affiliate_delete ($affiliate_id) {
  $affiliate_query = tep_db_query("SELECT affiliate_rgt, affiliate_lft, affiliate_root FROM " . TABLE_AFFILIATE . " WHERE affiliate_id = '" . $affiliate_id . "' ");
  if ($affiliate = tep_db_fetch_array($affiliate_query)) {
    if ($affiliate['affiliate_root'] == $affiliate_id) {
      // a root entry is deleted -> his childs get root
      $affiliate_child_query = tep_db_query("
                    SELECT aa1.affiliate_id, aa1.affiliate_lft, aa1.affiliate_rgt,  COUNT(*) AS level
	                  FROM affiliate_affiliate AS aa1, affiliate_affiliate AS aa2
	                  WHERE aa1.affiliate_root = " . $affiliate['affiliate_root'] . "
	                        AND aa2.affiliate_root = aa1.affiliate_root
	                        AND aa1.affiliate_lft BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
	                        AND aa1.affiliate_rgt BETWEEN aa2.affiliate_lft AND aa2.affiliate_rgt
	                  GROUP BY aa1.affiliate_id, aa1.affiliate_lft, aa1.affiliate_rgt
                    HAVING level = 2
	                  ORDER BY aa1.affiliate_id
                   ");

      while ($affiliate_child = tep_db_fetch_array($affiliate_child_query)) {
        tep_db_query ("UPDATE  " . TABLE_AFFILIATE . " SET affiliate_root = " . $affiliate_child['affiliate_id'] . " WHERE affiliate_root =  " . $affiliate['affiliate_root'] . "  AND affiliate_lft >= " . $affiliate_child['affiliate_lft']  . " AND affiliate_rgt <= " . $affiliate_child['affiliate_rgt']  . " ");
        $substract =  $affiliate_child['affiliate_lft'] -1;
        tep_db_query ("UPDATE  " . TABLE_AFFILIATE . " SET affiliate_lft = affiliate_lft - " . $substract . " WHERE  affiliate_root = " . $affiliate_child['affiliate_id']);
        tep_db_query ("UPDATE  " . TABLE_AFFILIATE . " SET affiliate_rgt = affiliate_rgt - " . $substract . " WHERE  affiliate_root = " . $affiliate_child['affiliate_id']) ;
      }
      tep_db_query("DELETE FROM " . TABLE_AFFILIATE . "  WHERE affiliate_id = " . $affiliate_id);
      tep_db_query("UNLOCK TABLES");
    } else {
      tep_db_query("LOCK TABLES " . TABLE_AFFILIATE . " WRITE");
      tep_db_query("DELETE FROM " . TABLE_AFFILIATE . "  WHERE affiliate_id = " . $affiliate_id . " AND affiliate_root = " . $affiliate['affiliate_root'] . " ");

      tep_db_query("UPDATE " . TABLE_AFFILIATE . "
                  SET affiliate_lft = affiliate_lft -1, affiliate_rgt=affiliate_rgt-1
	                WHERE affiliate_lft BETWEEN " . $affiliate['affiliate_lft'] . " and " . $affiliate['affiliate_rgt'] . "
	                AND affiliate_root =  " . $affiliate['affiliate_root'] . "
                ");
      tep_db_query("UPDATE " . TABLE_AFFILIATE . "
	                SET affiliate_lft = affiliate_lft-2
	                WHERE affiliate_lft > " . $affiliate['affiliate_rgt'] . "
	                AND affiliate_root =  " . $affiliate['affiliate_root'] . "
                 ");
      tep_db_query("UPDATE " . TABLE_AFFILIATE . "
                	SET affiliate_rgt = affiliate_rgt-2
                  WHERE affiliate_rgt > " . $affiliate['affiliate_rgt'] . "
                  AND affiliate_root =  " . $affiliate['affiliate_root'] . "
                 ");
      tep_db_query("UNLOCK TABLES");
    }
  }
}

////
// Compatibility to older Snapshots
  if (!function_exists('tep_round')) {
    function tep_round($value, $precision) {
      if (PHP_VERSION < 4) {
        $exp = pow(10, $precision);
        return round($value * $exp) / $exp;
      } else {
        return round($value, $precision);
      }
    }
  }

////
// Output a form
  if (!function_exists('tep_draw_form')) {
    function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
      $form = '<form name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '" action="' . tep_parse_input_field_data($action, array('"' => '&quot;')) . '" method="' . tep_parse_input_field_data($method, array('"' => '&quot;')) . '"';

      if (tep_not_null($parameters)) $form .= ' ' . $parameters;

      $form .= '>';

      return $form;
    }
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_affiliate_tax_rate($class_id, $country_id, $zone_id) {

    $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id left join " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '" . $country_id . "') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . $zone_id . "') AND tr.tax_class_id = '" . $class_id . "' GROUP BY tr.tax_priority");
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

 //amit added to get total affilate commision start
  function tep_get_affiliate_total_commission($affiliate_id){
  		$affiliate_sales_raw1 = "select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) where o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " and  a.affiliate_isvalid='1' and  affiliate_id = '" . $affiliate_id . "'";
        $affiliate_sales_values1 = tep_db_query($affiliate_sales_raw1);
        $affiliate_sales1 = tep_db_fetch_array($affiliate_sales_values1);
		return $affiliate_sales1['payment'];  
  }
  //amit added to get total affilate commision end
  
  //amit added to get paid commision start
  
  function tep_get_affiliate_paid_commission($affiliate_id){
  
  		$affiliate_paid_raw1 = "select  sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_PAYMENT . " where affiliate_payment_status = '1'  and  affiliate_id = '" . $affiliate_id . "'";
        $affiliate_paid_values1 = tep_db_query($affiliate_paid_raw1);
        $affiliate_paid1 = tep_db_fetch_array($affiliate_paid_values1);
		return $affiliate_paid1['payment'];  
  }
  //amit added to get paid commission end

  //amit added to get pending commision start
  
  function tep_get_affiliate_pending_commission($affiliate_id){
  
  		$affiliate_paid_raw1 = "select  sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_PAYMENT . " where affiliate_payment_status = '0'  and  affiliate_id = '" . $affiliate_id . "'"; 
        $affiliate_paid_values1 = tep_db_query($affiliate_paid_raw1);
        $affiliate_paid1 = tep_db_fetch_array($affiliate_paid_values1);
		return $affiliate_paid1['payment'];  
  }
  //amit added to get pending commission end
function tep_get_affiliate_percent_display(){
//
$affilatevalue = AFFILIATE_PERCENT;

return number_format($affilatevalue).'%';

}


function tep_get_count_affiliate_referrals($affilate_id){
	$res = tep_db_query("select count(*) as count  from " . TABLE_REBATES_REFERRALS_INFO . "  where customers_id = '".$affilate_id."'");
    if($tran_array = tep_db_fetch_array($res)) {   
	   $count = $tran_array['count'];
	}	
	return (int)$count; 
}
 
?>
