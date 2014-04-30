<?php
/*
  WebMakers.com Added: Additional Functions
  Written by Linda McGrath osCOMMERCE@WebMakers.com
  http://www.thewebmakerscorner.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

////
// Verify Free Shipping or Regular Shipping modules to show
  function tep_get_free_shipper($chk_shipper) {
  global $cart;
  $show_shipper =false;
    switch (true) {
      case ( ($chk_shipper =='freeshipper' and $cart->show_weight() == 0) ):
        $show_shipper=true;
        break;
      case ( ($chk_shipper !='freeshipper' and $cart->show_weight() == 0) ):
        $show_shipper=false;
        break;

      case ( ($chk_shipper =='freeshipper' and $cart->show_weight() != 0) ):
        $show_shipper=false;
        break;
      case ( ($chk_shipper !='freeshipper' and $cart->show_weight() != 0) ):
        $show_shipper=true;
        break;
      default:
        $show_shipper=false;
        break;
    }

  return $show_shipper;
  }


////
// Verify Free Charge or Regular Payment methods to show
  function tep_get_free_charger($chk_module) {
  global $cart;

  $show_it =false;
    switch (true) {
      case ( ($chk_module =='freecharger' and ($cart->show_total()==0 and $cart->show_weight() == 0)) ):
        $show_it=true;
        break;
      case ( ($chk_module !='freecharger' and ($cart->show_total()==0 and $cart->show_weight() == 0)) ):
        $show_it=false;
        break;

      case ( ($chk_module =='freecharger' and ($cart->show_total()!=0 or $cart->show_weight() != 0)) ):
        $show_it=false;
        break;
      case ( ($chk_module !='freecharger' and ($cart->show_total()!=0 or $cart->show_weight() != 0)) ):
        $show_it=true;
        break;
    }

  return $show_it;
  }

////

////
// Display Price Retail
// Specials and Tax Included
  function tep_get_products_display_price($products_id, $prefix_tag=false, $value_price_only=false, $include_units=true) {
    global $currencies;
    $product_check_query = tep_db_query("select products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call, product_is_showroom_only from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
    $product_check = tep_db_fetch_array($product_check_query);

    $display_price='';
    $value_price=0;
    // Price is either normal or priced by attributes
      if ($product_check['products_priced_by_attribute']) {
        $attributes_priced=tep_get_products_base_price($products_id, $include_units);
        $display_price=$currencies->display_price( ($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup']/100))),'',1);
        $value_price=($product_check['products_price'] + $attributes_priced + ($attributes_priced * ($product_check['products_price_markup']/100)));
      } else {
        if ($product_check['products_price'] !=0) {
          $display_price=$currencies->display_price($product_check['products_price'],tep_get_tax_rate($product_check['products_tax_class_id']),1);
        }
      }

      // If a Special, Show it
      if ($add_special=tep_get_products_special_price($products_id)) {
        //       $products_price = '<s>' . $currencies->display_price($product_info_values['products_price'], tep_get_tax_rate($product_info_values['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info_values['products_tax_class_id'])) . '</span>';
        $display_price = '<s>' . $display_price . '</s> <span class="productSpecialPrice"> ' . $currencies->display_price($add_special,tep_get_tax_rate($product_check['products_tax_class_id']),'1') .  '</span> ';
      }

      // If Free, Show it
      if ($product_check['product_is_free']) {
        if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON=='0') {
          $free_tag= ' ' . PRODUCTS_PRICE_IS_FREE_TEXT;
        } else {
          $free_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_FREE_IMAGE,PRODUCTS_PRICE_IS_FREE_TEXT);
        }

        if ($product_check['products_price'] !=0) {
          $display_price='<s>' . $display_price . '</s>' . '<br /><span class="ProductIsFree">' . $free_tag . '</span>';
        } else {
          $display_price='<span class="ProductIsFree">' . $free_tag . '</span>';
        }
      } // FREE

      // If Call for Price, Show it
      if ($product_check['product_is_call']) {
        if (PRODUCTS_PRICE_IS_FREE_IMAGE_ON=='0') {
          $call_tag=' ' . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT;
        } else {
          $call_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_CALL_FOR_PRICE_IMAGE,PRODUCTS_PRICE_IS_CALL_FOR_PRICE_TEXT);
        }

        if ($product_check['products_price'] !=0) {
          $display_price='<s>' . $display_price . '</s> ' .  $call_tag;
        } else {
          $display_price=$call_tag;
        }
      } // CALL

      // If Showroom, Show it
      if ($product_check['product_is_showroom_only']) {
        if (PRODUCTS_PRICE_IS_SHOWROOM_IMAGE_ON=='0') {
          $showroom_only_tag= ' ' . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT;
        } else {
          $showroom_only_tag= ' ' . tep_image(DIR_WS_IMAGES . PRODUCTS_PRICE_IS_SHOWROOM_ONLY_IMAGE,PRODUCTS_PRICE_IS_SHOWROOM_ONLY_TEXT);
        }

        if ($product_check['products_price'] !=0) {
//          $display_price='<s>' . $display_price . '</s>' . '<br /><span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
          $display_price=$display_price . '<br /><span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
        } else {
          $display_price='<span class="ProductIsShowroomOnly">' . $showroom_only_tag . '</span>';
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

 function tep_get_order_final_price_of_oid($orders_id){
   $totalprice = '';
   $orders_produts_query_raw = "select * from "  . TABLE_ORDERS_TOTAL . " where  orders_id =".$orders_id."  and  class = 'ot_total'";
   $orders_query = tep_db_query($orders_produts_query_raw);
		if(tep_db_num_rows($orders_query) > 0 ){			
  			$orders = tep_db_fetch_array($orders_query);
			$totalprice = $orders['value'];
  		}
		
	return $totalprice;				
  }
  //amit added to get product type from product id start
  
  function tep_get_product_type_of_product_id($products_id){  
   	 $product_type_query = tep_db_query("select products_type from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'" . " limit 1");
	 $product_type_info= tep_db_fetch_array($product_type_query);
	return (int)$product_type_info['products_type'];  
  }
  
  //amit added to get product type from product id end
 
//amit added to check departure valida month start 
  function check_valid_available_date($startDate,$checkDate,$endDate){
	//echo $startDate = date('m-d',$startDate);	
	//echo $checkDate=date('m-d',$checkDate);
	//echo $endDate=date('m-d',$endDate);		
		if(strlen($endDate) == 10){		
		
			if(tep_get_compareDates_mm_dd_yyyy($checkDate,$startDate) == "valid" &&  tep_get_compareDates_mm_dd_yyyy($endDate,$checkDate) == "valid") { 
			   return "valid";
			} else { 
			   return "invalid";
			}
		
		}else{
		
			if($startDate <= $checkDate && $checkDate <= $endDate) { 
			   return "valid";
			} else { 
			   return "invalid";
			}
	
		}
}

function tep_get_compareDates_mm_dd_yyyy($date1,$date2) {
$date1_array = explode("-",$date1);
$date2_array = explode("-",$date2);
$timestamp1 = mktime(0,0,0,$date1_array[0],$date1_array[1],$date1_array[2]);
$timestamp2 = mktime(0,0,0,$date2_array[0],$date2_array[1],$date2_array[2]);
	if ($timestamp1>$timestamp2 || $timestamp1 == $timestamp2) {
	$ret_str = 'valid';
	} else {
	$ret_str = 'invalid';
	}
return $ret_str;	
}
//-----Amit added to get regular_irregular section detail Start

function regu_irregular_section_detail_short($ppproducts_id)
{
 $product_query_sql="SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int)$ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY producttype desc, operate_start_date, operate_end_date";
	   $product_query = tep_db_query($product_query_sql);
	   $regular_row_cnt=tep_db_num_rows($product_query);
	   
    while($product[] = tep_db_fetch_array($product_query)){}

    return $product;
}

function regu_irregular_section_detail($ppproducts_id)
{
 $product_query_sql="SELECT count( * ) AS irregular_count, case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int)$ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	   $product_query = tep_db_query($product_query_sql);
	   $regular_row_cnt=tep_db_num_rows($product_query);
	   
    while($product[] = tep_db_fetch_array($product_query)){}

    return $product;
}

function regu_irregular_section_numrow($ppproducts_id)
{
 	 $product_query_sql="SELECT  case when products_start_day = '0' or products_start_day = '' then 0 else 1 end as producttype,  operate_start_date, operate_end_date FROM " . TABLE_PRODUCTS_REG_IRREG_DATE . " WHERE products_id = '" . (int)$ppproducts_id . "' GROUP BY operate_start_date, operate_end_date ORDER BY operate_start_date, operate_end_date";
	   $product_query = tep_db_query($product_query_sql);
	   $regular_row_cnt=tep_db_num_rows($product_query);
	   
  

    return $regular_row_cnt;
}

//-----Amit added to get regular_irregular section detail End

	function tep_get_irreg_products_duration_description($products_id, $operate_start_date, $operate_end_date ){

$irregulr_description_display_system = '';

//take date from regular field start
	$the_products_duration_description_query= tep_db_query("select products_durations_description  from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id='".$products_id."' and operate_start_date= '" . $operate_start_date . "' and operate_end_date= '" . $operate_end_date . "'");

    if($the_products_duration_description = tep_db_fetch_array($the_products_duration_description_query)){

      $irregulr_description_display_system = $the_products_duration_description['products_durations_description'].' ';	
	
	}
//take date from regular field end



	    $available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id." and  operate_start_date='".$operate_start_date."' and  operate_end_date='".$operate_end_date."'  order by available_date";
		$available_query = tep_db_query($available_query_sql);
		$in_cnt_no = 0;
		while($available_result = tep_db_fetch_array($available_query))
		{
					$y = substr($available_result['available_date'], 0, 4);
					$m = substr($available_result['available_date'], 5, 2);
					$d = substr($available_result['available_date'], 8, 2);
					
					$from2 =  mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
					$from1 = date ("Y-m-d (D)", $from2);
					$formval = date ("Y-m-d", $from2);					
					//amit addedd to modify for specific month start
					$isvaliddatecheck ='';
					$startDate = $operate_start_date;																						
					$checkDate = date ("m-d-Y", $from2);									
					$endDate = $operate_end_date;
					
					$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
					//amit added to modify for specific month end
					if($isvaliddatecheck == "valid" && $formval >= date('Y-m-d')){
							$in_cnt_no++;
							$irregulr_description_display_system .= date("m/d/y", $from2).', ';
					}
		}
		
		
		if($in_cnt_no > 0){		
			$irregulr_description_display_system = substr($irregulr_description_display_system, 0, -2) .'<br />';
			return $irregulr_description_display_system;				
		}else{
			return '';		
		}
} 

function tep_get_language_stringmonth($i){
	 	global $language;
		
		if($language == 'tchinese' || $language == 'schinese' ){
		//return $language.$strmonth;
			if($i == "1"){
			return TEXT_HEADING_MONTH_1;
			}else if($i == "2"){
			return TEXT_HEADING_MONTH_2;
			}else if($i == "3"){
			return TEXT_HEADING_MONTH_3;
			}else if($i == "4"){
			return TEXT_HEADING_MONTH_4;
			}else if($i == "5"){
			return TEXT_HEADING_MONTH_5;
			}else if($i == "6"){
			return TEXT_HEADING_MONTH_6;
			}else if($i == "7"){
			return TEXT_HEADING_MONTH_7;
			}else if($i == "8"){
			return TEXT_HEADING_MONTH_8;
			}else if($i == "9"){
			return TEXT_HEADING_MONTH_9;
			}else if($i == "10"){
			return TEXT_HEADING_MONTH_10;
			}else if($i == "11"){
			return TEXT_HEADING_MONTH_11;
			}else {
			return TEXT_HEADING_MONTH_12;
			}
		}else{
		return strftime('%B',mktime(0,0,0,$i,1,2000));
		}
	} 

function tep_get_total_of_room($i){
	 	
			if($i == "1"){
			return TEXT_TOTLE_OF_ROOM1;
			}else if($i == "2"){
			return TEXT_TOTLE_OF_ROOM2;
			}else if($i == "3"){
			return TEXT_TOTLE_OF_ROOM3;
			}else if($i == "4"){
			return TEXT_TOTLE_OF_ROOM4;
			}else if($i == "5"){
			return TEXT_TOTLE_OF_ROOM5;
			}else if($i == "6"){
			return TEXT_TOTLE_OF_ROOM6;
			}		
	}
	function tep_get_total_of_adult_in_room($i){
	 	
			if($i == "1"){
			return TEXT_OF_ADULTS_IN_ROOM1;
			}else if($i == "2"){
			return TEXT_OF_ADULTS_IN_ROOM2;
			}else if($i == "3"){
			return TEXT_OF_ADULTS_IN_ROOM3;
			}else if($i == "4"){
			return TEXT_OF_ADULTS_IN_ROOM4;
			}else if($i == "5"){
			return TEXT_OF_ADULTS_IN_ROOM5;
			}else if($i == "6"){
			return TEXT_OF_ADULTS_IN_ROOM6;
			}		
	}
	function tep_get_total_of_children_in_room($i){
	 	
			if($i == "1"){
			return TEXT_OF_CHILDREN_IN_ROOM1;
			}else if($i == "2"){
			return TEXT_OF_CHILDREN_IN_ROOM2;
			}else if($i == "3"){
			return TEXT_OF_CHILDREN_IN_ROOM3;
			}else if($i == "4"){
			return TEXT_OF_CHILDREN_IN_ROOM4;
			}else if($i == "5"){
			return TEXT_OF_CHILDREN_IN_ROOM5;
			}else if($i == "6"){
			return TEXT_OF_CHILDREN_IN_ROOM6;
			}		
	}

	
//amit added to check departure date start
function tep_get_compareDates($date1,$date2) {
$date1_array = explode("-",$date1);
$date2_array = explode("-",$date2);
$timestamp1 = mktime(0,0,0,$date1_array[1],$date1_array[2],$date1_array[0]);
$timestamp2 = mktime(0,0,0,$date2_array[1],$date2_array[2],$date2_array[0]);
	if ($timestamp1>$timestamp2 || $timestamp1 == $timestamp2) {
	$ret_str = 'valid';
	} else {
	$ret_str = 'invalid';
	}
return $ret_str;	
}

//amit added to check departure date end



//amit added to check guest by parse string start

function tep_get_rooms_adults_childern($roomsinfo_string,$room,$customer_type){
	$parse_child = tep_get_total_of_children_in_room($room);
	$parse_adult = tep_get_total_of_adult_in_room($room);
	
 switch ($customer_type) {
  case 'adult' :
  	 
 	if(preg_match('/<br />- '.$parse_adult.' ([0-9]+)/', $roomsinfo_string, $m)) {
	$rtn_val = $m[1];				
	}
  break;
  case 'children' :
 	if(preg_match('/<br />- '.$parse_child.' ([0-9]+)/', $roomsinfo_string, $m)) {
	$rtn_val = $m[1];				
	}
  break;
 }
 
return (int)$rtn_val;
}

function tep_get_no_adults_childern($roomsinfo_string,$customer_type){

 switch ($customer_type) {
  case 'adult' :
  	 
 	if(preg_match('/# adults : ([0-9]+)/', $roomsinfo_string, $m)) {
	$rtn_val = $m[1];				
	}
  break;
  case 'children' :
 	if(preg_match('/# of children : ([0-9]+)/', $roomsinfo_string, $m)) {
	$rtn_val = $m[1];				
	}
  break;
 }
 
return (int)$rtn_val;
}

function tep_get_total_nos_of_rooms($roomsinfo_string){
	if(preg_match('/- '.TEXT_TOTAL_OF_ROOMS.'([0-9]+)/', $roomsinfo_string, $m)) {
	$rtn_val = $m[1];						
	}
	
	return (int)$rtn_val;
}

//amit added to check guest by parse string end
//amit added for get new design backgound and footer start

function tep_get_design_body_header($headingtitle='usitrip',$isshowbardcurm=0 ){
global $breadcrumb;
$total_return_str = '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="pageHeading" width="100%">'.$headingtitle.'</td><td   nowrap="nowrap" class="pageHeading" align="right">';
	if($isshowbardcurm==1){
	$total_return_str .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="main"  nowrap="nowrap">'. $breadcrumb->trail(' &raquo; ').'</td>
		  </tr>
		</table>
		';	
	}
	
$total_return_str .= '</td>
  </tr>
  <tr>
    <td class="mainbodybackground" colspan="2">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr><td height="15"></td></tr>
			     <tr>
					 <td width="3%"></td>
					 <td width="94%" class="main">';
					 
return $total_return_str;		 

}


function tep_get_design_body_footer(){

$total_return_str_f = '</td><td width="3%"></td></tr><tr><td height="30"></td></tr></table></td></tr></table>';

return $total_return_str_f;		



}

//amit added for get new design backgound and footer end

function tep_get_display_operate_info($products_id){

		$operate = '';
															
		$num_of_sections = regu_irregular_section_numrow($products_id);
		if($num_of_sections > 0){
			$regu_irregular_array = regu_irregular_section_detail_short($products_id);
			
			foreach($regu_irregular_array as $k=>$v)
			{
				if(is_array($v))
				{
				
					$tourcatetype =	$regu_irregular_array[$k]['producttype'];
					$opestartdate =  $regu_irregular_array[$k]['operate_start_date'];
					$opeenddate =  $regu_irregular_array[$k]['operate_end_date'];
					$day1 ='';
					if($tourcatetype == 1){  //regular your
					 $operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".$products_id."  and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by products_start_day");
					 $numofrowregday = tep_db_num_rows($operator_query);
						  if($numofrowregday == 7)
						  {
									//$opestartdayarray = explode('-',$opestartdate);																								
									//$operatetomodistart = strftime('%b', mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opestartdayarray[1], 0));
									$operatetomodistart = str_replace('-','/',$opestartdate);
											
									//$opeenddayarray = explode('-',$opeenddate);													
									//$operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
									$operatetomodiend = str_replace('-','/',$opeenddate);
									
		
									if($opestartdate == '01-01-' && $opeenddate == '12-31-'){
										$operate .= TEXT_DAILY.'<br />';
									
									}else{
										//$operate .= $operatetomodistart.' '.$opestartdayarray[2].'-'.$operatetomodiend.' '.$opeenddayarray[2].': '.TEXT_DAILY.'<br />';
										$operate .= $operatetomodistart.' '.TEXT_TO.' '.$operatetomodiend.': '.TEXT_DAILY.'<br />';
						 
									}							
									
									
						  }
						  else
						  {
						
							  while($operator_result = tep_db_fetch_array($operator_query))
							  {
										if($operator_result['products_start_day'] == 1)
										{
											//$day1 .= 'Sun/';
											$day1 .= TEXT_WEEK_SUN.'/';
										}
										if($operator_result['products_start_day'] == 2)
										{
											//$day1 .= 'Mon/';
											$day1 .= TEXT_WEEK_MON.'/';
										}
										if($operator_result['products_start_day'] == 3)
										{
											//$day1 .= 'Tue/';
											$day1 .= TEXT_WEEK_TUE.'/';
										}
										if($operator_result['products_start_day'] == 4)
										{
											//$day1 .= 'Wed/';
											$day1 .= TEXT_WEEK_WED.'/';
										}
										if($operator_result['products_start_day'] == 5)
										{
											//$day1 .= 'Thu/';
											$day1 .= TEXT_WEEK_THU.'/';
										}
										if($operator_result['products_start_day'] == 6)
										{
											//$day1 .= 'Fri/';
											$day1 .= TEXT_WEEK_FRI.'/';
										}
										if($operator_result['products_start_day'] == 7)
										{
											//$day1 .= 'Sat/';
											$day1 .= TEXT_WEEK_SAT.'/';
										}
							  
							  }
									
									//$opestartdayarray = explode('-',$opestartdate);																								
									//$operatetomodistart = strftime('%b', mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opestartdayarray[1], 0));
									$operatetomodistart = str_replace('-','/',$opestartdate);
											
									//$opeenddayarray = explode('-',$opeenddate);													
									//$operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
									$operatetomodiend = str_replace('-','/',$opeenddate);
		
									if($opestartdate == '01-01-' && $opeenddate == '12-31-'){
											$operate .= $day1.'<br />';
									
									}else{
										//$operate .= $operatetomodistart.' '.$opestartdayarray[2].'-'.$operatetomodiend.' '.$opeenddayarray[2].': '.$day1.'<br />';
										$operate .= $operatetomodistart.' '.TEXT_TO.' '.$operatetomodiend.': '.$day1.'<br />';
									}		
								 }
					  
					  
					}else{ //irregular tours											
						
									//echo 'here';
									$irredis_select_description = tep_get_irreg_products_duration_description($products_id,$opestartdate,$opeenddate);
									$operate .= $irredis_select_description.'<br />';
									
									
					}
					
				}
			}
		}
	$operate = str_replace('<br /><br />','<br />',$operate);
	return $operate;
}

//amit added to get subcategory id's  start //including current categort to

  function tep_get_category_subcategories_ids($category_id) {
  /*
    $child_category_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
   
   $stored_cat_done_ids = "'" . (int)$category_id . "', ";
   while($child_category = tep_db_fetch_array($child_category_query)){
    $stored_cat_done_ids .= "'" . (int)$child_category['categories_id'] . "', ";
   }
   
   */
    $stored_cat_done_ids = "'" . (int)$category_id . "', ";
    $subcategories_array = array();
	tep_get_subcategories($subcategories_array, $category_id);	
    for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
	$stored_cat_done_ids .= "'" . (int)$subcategories_array[$i] . "', ";
	}
   
   $stored_cat_done_ids = substr($stored_cat_done_ids, 0, -2);
   return $stored_cat_done_ids;   
  }


//amit added to get sub category id's end

function is_CheckvalidEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
	  
	  
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
	
	 
   }
   return $isValid;
}

function tep_get_categori_id_from_url($categories_urlname){
	/*global $languages_id;
	$category_query = tep_db_query("select cd.categories_name, c.categories_urlname,  c.categories_id, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and (c.categories_urlname = '" . $categories_urlname . "' || c.categories_urlname = '" . $categories_urlname . "/' ) and cd.language_id = '" . $languages_id . "'");
	$category = tep_db_fetch_array($category_query);*/
	
	$category = MCache::search_categories('categories_urlname', array($categories_urlname,$categories_urlname.'/'),'categories_name,categories_urlname,categories_id,parent_id');

	return $category;
}

function tep_get_paths($categories_array = '', $parent_id = '0', $indent = '', $path= '', $level = '') {
    global $languages_id;
    if (!is_array($categories_array)) $categories_array = array();
 	
	
	$where_extra_ignor_category = " and c.categories_urlname != 'tours-by-departure-city/' and c.categories_urlname not like '%-tour-packages/' ";
    
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name,c.categories_urlname from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' ".$where_extra_ignor_category." order by sort_order, cd.categories_name");
   
   if ( (sizeof($categories_array) < 1) && tep_db_num_rows($categories_query) > 0) $categories_array[] = array('id' => '', 'text' => TEXT_DROP_DOWN_SELECT_REGION);
   
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($parent_id=='0'){
				$categories_array[] = array('id' => str_replace('/','',$categories['categories_urlname']),
												  'text' => $indent . str_replace(' Tour','', str_replace(' Tours','',db_to_html($categories['categories_name']))));
      }
      else{
				$categories_array[] = array('id' => str_replace('/','',$categories['categories_urlname']),
												  'text' => $indent . str_replace(' Tour','', str_replace(' Tours','',db_to_html($categories['categories_name']))));
      }

      if ( ($categories['categories_id'] != $parent_id) && ($level != 1) ) {
			$this_path=$path;
			if ($parent_id != '0')
	 	 	$this_path = $path . $parent_id . '_';
        	$categories_array = tep_get_paths($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;', $this_path);
      }
	  
	  
    }

    return $categories_array;
  }


  function get_total_room_from_str($str){
	 $get_room_no_array = explode('###',$str);
	 return $get_room_no_array[0];
}

function tep_get_room_total_persion_from_str($str,$room_no){
 $get_room_no_array = explode('###',$str);  
 $adu_and_chile_val_array =	explode('!!',$get_room_no_array[$room_no]);
 $total_ad_ch_val = $adu_and_chile_val_array[0] + $adu_and_chile_val_array[1];
 return $total_ad_ch_val;
}

function tep_get_room_adult_child_persion_on_room_str($str,$room_no){
 $get_room_no_array = explode('###',$str);  
 $adu_and_chile_val_array =	explode('!!',$get_room_no_array[$room_no]);
 
 $total_ad_ch_val_array[0] = $adu_and_chile_val_array[0];
 $total_ad_ch_val_array[1] = $adu_and_chile_val_array[1];
 
 return $total_ad_ch_val_array;
}
function tep_full_copy( $source, $target, $products_id, $insert_id, $customers_name, $customers_email, $front_title, $front_desc )
{

	if ( is_dir( $source ) )
	{
		@mkdir( $target );
	   
		$d = dir( $source );
	   
		$count_picture=0;
		/*echo $front_title;
		echo $front_desc;
		exit;*/
		
		$front_title_db = explode('/--title--/',$front_title);
		$front_desc_db = explode('/--desc--/',$front_desc);
		while ( FALSE !== ( $entry = $d->read() ) )
		{
		$insert_id = rand(1000,3000);
			if ( $entry == '.' || $entry == '..' )
			{
				continue;
			}
		   
		     $Entry = $source . '/' . $entry;           
			if ( is_dir( $Entry ) )
			{
				tep_full_copy( $Entry, $target . '/' . $entry, $insert_id );
				continue;
			}
			copy( $Entry, $target . '/' . $insert_id.'_'. $entry );
			$detail_image = 0;
			$thumb_image = 0;
			$imgfile = $target .'/'. $insert_id.'_'. $entry;
			$size = getimagesize($imgfile);
			$width = $size[0];
			//exit;
			if($width>425)
			{
				$detail_image = 1;
				imageCompression($imgfile,425,$target  .'detail_'. $insert_id.'_'. $entry);
			}
			
			if($width>70)
			{
				$thumb_image = 1;
				imageCompression($imgfile,70,$target  .'thumb_'. $insert_id.'_'. $entry);
			}
			
			//echo "insert into traveler_photos(products_id,customers_name,customers_email,image_name,image_title,image_desc) values('".$insert_id."','','','". $insert_id.'_'. $entry."','','')";
			
			if($front_desc_db[$count_picture]==HEDING_TEXT_ENTER_PHOTO_DESCRIPTION)
			{
				$desc = '';
			}
			else
			{
				$desc = $front_desc_db[$count_picture];
			}
			
			if($front_title_db[$count_picture]==HEDING_TEXT_ENTER_PHOTO_TITLE)
			{
				$title = '';
			}
			else
			{
				$title = $front_title_db[$count_picture];
			}
			
			/*
			tep_db_query("insert into traveler_photos(products_id,customers_name,customers_email,image_name,image_title,image_desc)
			 values('".$products_id."','".$customers_name."','".tep_db_input($customers_email)."','". $insert_id.'_'. $entry."','".$title."','".$desc."')");
			*/
			 $logo_file = explode(".",$entry);
				$i=sizeof($logo_file);
				$extension = $logo_file[$i-1];
				$front_ext = strtolower($extension);
				//exit;
				if($front_ext == "jpeg" || $front_ext == "jpg" || $front_ext == "png" || $front_ext == "gif" || $front_ext == "bmp")
				{
			 $insert_photo_sql_data_array = array(
			 				  'products_id' => $products_id,
                              'customers_name' => html_to_db($customers_name),
                              'customers_email' => tep_db_input($customers_email),
                              'image_name' => tep_db_input($insert_id.'_'. $entry),							 
                              'image_title' => html_to_db($title),
                              'image_desc' => html_to_db($desc),
							   );
			 tep_db_perform(TABLE_TRAVELER_PHOTOS, $insert_photo_sql_data_array);
			}
			
			
if($detail_image == 1){
				
				//move_uploaded_file($imgfile,$target.'detail_'.$insert_id.'_'. $entry); 
				@unlink($imgfile);
			}			//".$front_title_db[$count_picture]."
		$count_picture++;
		}
	   
		$d->close();
	}else
	{
		copy( $source, $target );
	}
}

function imageCompression($imgfile="",$thumbsize=0,$savePath=NULL) {
    /*echo $imgfile;
	echo '<br />';
	echo $thumbsize;
	echo '<br />';
	echo $savePath;
	echo '<br />';*/
	if($savePath==NULL) {
        header('Content-type: image/jpeg');
        /* To display the image in browser
       
        */
       
    }
    list($width,$height)=getimagesize($imgfile);
    /* The width and the height of the image also the getimagesize retrieve other information as well   */
    //echo $width;
    //echo $height;
    $imgratio=$width/$height; 
    /*
    To compress the image we will calculate the ration 
    For eg. if the image width=700 and the height = 921 then the ration is 0.77...
    if means the image must be compression from its height and the width is based on its height
    so the newheight = thumbsize and the newwidth is thumbsize*0.77...
    */
   
    if($imgratio>1) {
        $newwidth=$thumbsize;
        $newheight=$thumbsize/$imgratio;
    } else {
        $newheight=$thumbsize;       
        $newwidth=$thumbsize*$imgratio;
    }
   
    $thumb=imagecreatetruecolor($newwidth,$newheight); // Making a new true color image
    //$source=imagecreatefromjpeg($imgfile); // Now it will create a new image from the source
	
	$source=imagecreatefromfile($imgfile); // Now it will create a new image from the source
    imagecopyresampled($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);  // Copy and resize the image
	
	//imagejpeg($thumb,$savePath,100);
	imagejpeg($thumb,$savePath,93);
    /*
    Out put of image 
    if the $savePath is null then it will display the image in the browser
    */
    imagedestroy($thumb);
    /*
        Destroy the image
    */
   
}

//if the file is not in jpg format function for other files
function imagecreatefromfile($path, $user_functions = false)
{
    $info = @getimagesize($path);
   
    if(!$info)
    {
        return false;
    }
   
    $functions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromwxbm',
        );
   
    if($user_functions)
    {
        $functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
    }
   
    if(!$functions[$info[2]])
    {
        return false;
    }
   
    if(!function_exists($functions[$info[2]]))
    {
        return false;
    }
   
    return $functions[$info[2]]($path);
}

function tep_recursive_remove_directory($directory, $empty=FALSE)
{
     // if the path has a slash at the end we remove it here
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
  
     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... else if the path is readable
     }else{
  
         // we open the directory
         $handle = opendir($directory);
  
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
  
                 // if the new path is a directory
                 if(is_dir($path)) 
                 {
                     // we call this function with the new path
                     tep_recursive_remove_directory($path);
  
                 // if the new path is a file
                 }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
  
         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
 }


// get order status which is notified
function tep_get_order_status_last_notify($order_id)
{
$status_query = tep_db_query("select s.orders_status_name from " . TABLE_ORDERS_STATUS . " s, ".TABLE_ORDERS_STATUS_HISTORY . " sh where s.orders_status_id = sh.orders_status_id and sh.orders_id = '" . (int)$order_id . "' and customer_notified = 1 order by date_added desc limit 1");
if($status = tep_db_fetch_array($status_query))
return $status['orders_status_name'];
}


function tep_get_display_reg_special_picing_title($products_start_day,$opestartdate,$opeenddate){

		$operate = '';
															
		
					$day1 ='';
					$operator_result['products_start_day'] = $products_start_day;
					
										if($operator_result['products_start_day'] == 1)
										{
											//$day1 .= 'Sun/';
											$day1 .= TEXT_WEEK_SUN.'/';
										}
										if($operator_result['products_start_day'] == 2)
										{
											//$day1 .= 'Mon/';
											$day1 .= TEXT_WEEK_MON.'/';
										}
										if($operator_result['products_start_day'] == 3)
										{
											//$day1 .= 'Tue/';
											$day1 .= TEXT_WEEK_TUE.'/';
										}
										if($operator_result['products_start_day'] == 4)
										{
											//$day1 .= 'Wed/';
											$day1 .= TEXT_WEEK_WED.'/';
										}
										if($operator_result['products_start_day'] == 5)
										{
											//$day1 .= 'Thu/';
											$day1 .= TEXT_WEEK_THU.'/';
										}
										if($operator_result['products_start_day'] == 6)
										{
											//$day1 .= 'Fri/';
											$day1 .= TEXT_WEEK_FRI.'/';
										}
										if($operator_result['products_start_day'] == 7)
										{
											//$day1 .= 'Sat/';
											$day1 .= TEXT_WEEK_SAT.'/';
										}
							  
								
									//$opestartdayarray = explode('-',$opestartdate);																								
									$operatetomodistart = str_replace('-','/',$opestartdate);
									//$operatetomodistart = strftime('%b', mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opestartdayarray[1], 0));
											
									//$opeenddayarray = explode('-',$opeenddate);													
									//$operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
									$operatetomodiend = str_replace('-','/',$opeenddate);
		
									if($opestartdate == '01-01-' && $opeenddate == '12-31-'){
											$operate .= $day1.'<br />';
									
									}else{
										//$operate .= $operatetomodistart.' '.$opestartdayarray[2].'-'.$operatetomodiend.' '.$opeenddayarray[2].': '.$day1.'<br />';
										$operate .= $operatetomodistart.' '.TEXT_TO.' '.$operatetomodiend.': '.$day1.'<br />';
						 
									}		
				
				
	return $operate;
}
//function to change the date format from '2008-12-31' to '12/31/2008'
function tep_get_date_disp($date)
{
	//echo 'here in function';
	if($date!='')
	{
	$date_disp = strtotime($date);
	$date_return = date("m/d/Y",$date_disp);

	}
	else
	{
		$date_return='';
	}
	//echo $date_return;
	return $date_return;
}

//function to change the date format from '12/31/2008' to '2008-12-31'
function tep_get_date_db($date)
{
	if($date!='')
	{
	$date_disp = explode("/",$date);
	$date_return = $date_disp[2].'-'.$date_disp[0].'-'.$date_disp[1];
	}
	else
	{
		$date_return='';
	}

	return $date_return;
}

function date_add_day($length,$format,$date_passed){
 $new_timestamp = -1;
	if($date_passed != ''){
	
	
			 $date_passed_split_array = explode('::',$date_passed);
			 
			 $date_passed_array = explode('-',$date_passed_split_array[0]);
			 $date_actual["mon"] = $date_passed_array[1];
			 $date_actual["mday"] = $date_passed_array[2];
			 $date_actual["year"] = $date_passed_array[0];


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

			 return @date('m/d/Y (D)',$new_timestamp);

	}else{
		return '';
	}
} 
?>