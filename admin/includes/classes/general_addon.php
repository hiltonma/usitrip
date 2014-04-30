<?php
/*
  $Id: general_addon.php,v 1.160 2003/07/12 08:32:47 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
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

function tep_get_agencyid_from_name($name){

 	$agency_query = tep_db_query("select agency_id  from " . TABLE_TRAVEL_AGENCY . " where agency_name = '" .tep_db_prepare_input($name). "'");
    $agency = tep_db_fetch_array($agency_query);
    return $agency['agency_id'];
}


function tep_get_regionid_from_name($name){

 	$region_query = tep_db_query("select regions_id  from " . TABLE_REGIONS_DESCRIPTION . " where regions_name = '" .tep_db_prepare_input($name). "'");
    $region = tep_db_fetch_array($region_query);
    return $region['regions_id'];
}

function tep_get_departureid_from_name($name){

 	$departure_query = tep_db_query("select city_id  from " . TABLE_TOUR_CITY . " where city = '" .tep_db_prepare_input($name). "'");
    $departure = tep_db_fetch_array($departure_query);
    return $departure['city_id'];
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

//amit added for get customer details start
	function tep_get_customers_info($cust_id) {
	 $sql = "select a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_country_id, a.entry_zone_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_telephone, c.customers_fax, ci.customers_info_number_of_logons, ci.customers_info_date_account_created , ci. customers_info_date_of_last_logon, ci.customers_info_date_account_last_modified  from " . TABLE_CUSTOMERS . " as c, " . TABLE_ADDRESS_BOOK . " as a, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_default_address_id=a.address_book_id  and c.customers_id=a.customers_id  and ci.customers_info_id  = c.customers_id and c.customers_id = '" . tep_db_input($cust_id) . "'";
		if(($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
			return tep_db_fetch_array($rs);
		}
		return array(); 
	}
//amit added for get customer details end


//select disting category for cpc avarage calculation end  root


function scs_datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */
  
  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds
   
  switch($interval) {
   
    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
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
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
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



function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

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

//amit added for get order and product id start
	function tep_get_ordersid_productsid_from_orderproducts($orders_products_id) {
	 $sql = "select orders_id, products_id from " . TABLE_ORDERS_PRODUCTS . "  where orders_products_id = '" . tep_db_input($orders_products_id) . "'";
		if(($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
			return tep_db_fetch_array($rs);
		}
		return array(); 
	}
//amit added for get order and product id end 
 
//amit added to forward array value start

function field_forwarder() { 
    global $_POST, $rEM979, $FFoutputType; 
    $fieldForwarder = ''; 
    /* get the arguments passed */ 
    $argList = func_get_args (); 

    /* globalize any other set of instructions */ 
    if (count ($argList)) { 
        eval ('global $' . $argList[count($argList)-1] . ';'); 
    } 
     
    /* set the default set of values to convert */ 
    if(count($argList)==0) { 
        /* if the function is initially passed without 
           parameter we're looking in $_POST */ 
        $argList[0] = '_POST'; 
        $startValue = $_POST;  
        if (sizeof ($startValue) == 0) { 
            return false; 
        } 
    } elseif (count ($argList) == 1) { 
        eval ('$rEM979["' . $argList[0] . '"] = $'  
              . $argList[0] . ';'); 
        $argList[0] = 'rEM979'; 
        $startValue = $rEM979; 
    } elseif (count ($argList) == 2) { 
        eval ('$startValue = $' . $argList[1] . '["'  
              . $argList[0] . '"];'); 
    } else { 
        for($e = count($argList) - 2; $e >= 0; $e--) { 
            $intersperse .= '["' . $argList[$e] . '"]'; 
        } 
        eval ('$startValue = $' . $argList[count($argList)-1]   
              . $intersperse . ';'); 
    } 

    foreach($startValue as $n => $v) { 
        if (is_array ($v)) { 
            /* call the function again */ 
            $shiftArguments = ''; 
            for($w = 0; $w <= count ($argList) - 1; $w++) { 
                $shiftArguments .= '"' . $argList[$w] . '", '; 
            } 
            $shiftArguments = substr ($shiftArguments, 0,  
                                     strlen ($shiftArguments) - 2); 
             
            eval ('$fieldForwarder .= field_forwarder("' . $n . '"'  
                  . substr(',',0,strlen($shiftArguments)) . ' '  
                  . $shiftArguments . ');'); 
                         
        } else { 
            /* we have an root value finally */ 
            if (count ($argList) == 1) { 
                /* actual output */ 
                flush(); 
                if ($FFoutputType == 'print') { 
                    $fieldForwarder .= "\$$n = '$v';\n"; 
                } else { 
                    $fieldForwarder .= "<input type=\"hidden\" " 
                                    . "name=\"$n\" value=\""  
                                    . htmlentities(stripslashes($v))  
                                    . "\">\n"; 
                } 
            } elseif (count ($argList) >1 ) { 
                $indexString = ''; 
                for($g = count ($argList) - 3; $g >= 0; $g--) { 
                    $indexString .= '['  
                                 . ((!is_numeric ($argList[$g]) 
                                 and $FFoutputType == 'print') 
                                 ? "'" : '') 
                                 . $argList[$g] 
                                 . ((!is_numeric ($argList[$g]) 
                                 and $FFoutputType == 'print') 
                                 ? "'" : '') 
                                 . ']'; 
                } 
                $indexString .= '['  
                             . ((!is_numeric ($n)  
                             and $FFoutputType == 'print')  
                             ? "'" : '') . $n  
                             . ((!is_numeric ($n)  
                             and $FFoutputType == 'print')  
                             ? "'" : '') . ']'; 
                /* actual output */ 
                flush(); 
                if ($FFoutputType == 'print') { 
                    $fieldForwarder .= "\${$argList[count($argList)-2]}" 
                                    . "$indexString = '$v';\n"; 
                } else { 
                    $fieldForwarder .= "<input type=\"hidden\" name=\"" 
                                    . "{$argList[count($argList)-2]}" 
                                    . "$indexString\" value=\""  
                                    . htmlentities(stripslashes($v))  
                                    . "\">\n"; 
                } 
            } 
        }        
    } 
    return $fieldForwarder; 
} 

//amit added to forward array value end

?>
