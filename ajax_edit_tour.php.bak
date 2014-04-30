<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require('includes/application_top.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
 <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
  <title><?php echo TITLE ?></title>
<?php
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
$HTTP_GET_VARS['dp_old_select'] = $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][0];
//hotel-extension {
if(tep_check_product_is_hotel($HTTP_GET_VARS['products_id'])==3){
	list($pick_location,$return_value) = explode('|=|',$cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][2]);
	list($time,$add_loc)=explode('||',$return_value);
	list($fina_re_loc,$date) = explode('=|=',str_replace('||',' ',$return_value));
	$HTTP_GET_VARS['dp_old_select_return'] = substr($date,0,10);
}
$is_las_vegas_show = check_is_las_vegas_show($HTTP_GET_VARS['products_id']);
$iframe_cart_edit_update = (int)$HTTP_GET_VARS['products_id'] . '_' . (int)$_GET['product_number'];//$HTTP_GET_VARS['product_number'];
//}
function browser_detection( $which_test ) {
	$browser = '';$dom_browser = '';
	$navigator_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
	if (stristr($navigator_user_agent, "opera")){	$browser = 'opera';	$dom_browser = true;}
	elseif (stristr($navigator_user_agent, "msie 4")){	$browser = 'msie4';$dom_browser = false;}
	elseif (stristr($navigator_user_agent, "msie")){	$browser = 'msie';	$dom_browser = true;	}
	elseif ((stristr($navigator_user_agent, "konqueror")) || (stristr($navigator_user_agent, "safari"))){		$browser = 'safari';	$dom_browser = true;	}
	elseif (stristr($navigator_user_agent, "gecko")){		$browser = 'mozilla';		$dom_browser = true;	}
	elseif (stristr($navigator_user_agent, "mozilla/4"))	{		$browser = 'ns4';	$dom_browser = false;	}
	else{		$dom_browser = false;	$browser = false;	}
	if ( $which_test == 'browser' )	{	return $browser;}
	elseif ( $which_test == 'dom' ){	return $dom_browser;}
}
//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();
?>
<link href="<?php echo TEMPLATE_STYLE;?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/includes/javascript/calendar.js" charset="gb2312"></script>
<?php if(strtolower(CHARSET)=='gb2312'){?>
<script type="text/javascript" src="big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="gb_big5-min.js"></script>
<?php } ?>
</head>

<?php
// products_id=1712&full_products_id=1712&product_number=0&departure_date=2012-05-19&cPath=25_55&action_ajax_add=add_product
switch ($HTTP_GET_VARS['action_ajax_add']) {
	case 'add_product' :
		if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id']))
		{

			//transfer-service {
			$product_transfer_info_sql = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "' ");
			$product_transfer_info = tep_db_fetch_array($product_transfer_info_sql);
			//}
			$extra_values = '';
			//hotel-extension  {Start Las Vegas Show
			$is_las_vegas_show = check_is_las_vegas_show((int)$HTTP_POST_VARS['products_id']);
			if($is_las_vegas_show==true){
				$txt_dept_date = TEXT_DEPT_DATE_SHOW;
				$txt_pickup_location = TEXT_PICKUP_LOCATION_SHOW;
			}else{
				if(tep_check_product_is_hotel((int)$HTTP_POST_VARS['products_id'])==1){
					$txt_dept_date = TEXT_HOTEL_CHECK_IN_DATE;
				}else{
					$txt_dept_date = TEXT_DEPT_DATE;
				}
				$txt_pickup_location = db_to_html('出发地点:');
			}//End Las Vegas Show}
			//amit added to serversite validation for departure date
			$is_start_date_required=is_tour_start_date_required((int)$HTTP_POST_VARS['products_id']);
			//var_dump($is_start_date_required);
			if($is_start_date_required==true && tep_check_product_is_hotel((int)$HTTP_POST_VARS['products_id'])==0){
				$error_depar_dt !='';
				if($HTTP_POST_VARS['availabletourdate'] == ""){
					$error_depar_dt = 'true';
				}else{
					//if not blank than check it greater than current date
					$tmp_dp_date = substr($HTTP_POST_VARS['availabletourdate'],0,10);
					$currect_sys_date = date('Y-m-d');
					if(@tep_get_compareDates($tmp_dp_date,$currect_sys_date) == "invalid" ){
						$error_depar_dt = 'true';
					}
				}
			}

			//priority mail date
			//echo $HTTP_POST_VARS['priority_mail_ticket_needed_date'];

			if(isset($HTTP_POST_VARS['priority_mail_ticket_needed_date']) && $HTTP_POST_VARS['priority_mail_ticket_needed_date'] != ''){
				$error_priority_mail_date != '';
				$current_sys_date = date('Y-m-d');
				$valid_ticket_needed_date = strtotime($current_sys_date) + (7*24*60*60);
				$priority_mail_db_date = tep_get_date_db($HTTP_POST_VARS['priority_mail_ticket_needed_date']);
				if((strtotime($priority_mail_db_date) < $valid_ticket_needed_date) || (strtotime($priority_mail_db_date) > strtotime($tmp_dp_date)) ){

					$error_priority_mail_date = 'true';
				}else{
					$extra_values = $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values'] = tep_get_cart_add_update_extra_values('priority_mail_ticket_needed_date', $HTTP_POST_VARS['priority_mail_ticket_needed_date'], $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);
				}
			}
			if(isset($HTTP_POST_VARS['priority_mail_delivery_address']) && $HTTP_POST_VARS['priority_mail_delivery_address'] != ''){
				$extra_values = $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values'] = tep_get_cart_add_update_extra_values('priority_mail_delivery_address', $HTTP_POST_VARS['priority_mail_delivery_address'], $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);
			}
			if(isset($HTTP_POST_VARS['priority_mail_recipient_name']) && $HTTP_POST_VARS['priority_mail_recipient_name'] != ''){
				$extra_values = $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values'] = tep_get_cart_add_update_extra_values('priority_mail_recipient_name', $HTTP_POST_VARS['priority_mail_recipient_name'], $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);
			}
			//priority mail date

			/*//nirav added for remaining seats---start
			$totalinroom_adult=0;
			if($HTTP_POST_VARS['numberOfRooms']>0){
			for($i=0;$i<$HTTP_POST_VARS['numberOfRooms'];$i++){
			$totalinroom_adult+=($HTTP_POST_VARS['room-'.$i.'-adult-total']+$HTTP_POST_VARS['room-'.$i.'-child-total']);
			}
			}else{
			$totalinroom_adult=($HTTP_POST_VARS['room-0-adult-total']+$HTTP_POST_VARS['room-0-child-total']);
			}
			if(is_seats_availavle((int)$HTTP_POST_VARS['products_id'], substr($HTTP_POST_VARS['availabletourdate'],0,10), $totalinroom_adult)==false && $is_gift_certificate_tour==false && $is_start_date_required==true){
			$run_available_seats = get_remaining_seats((int)$HTTP_POST_VARS['products_id']);
			tep_redirect(tep_href_link("ajax_edit_tour.php", 'products_id=' . (int)$HTTP_POST_VARS['products_id'].'&error_departure_date=true&seats_avail='.$run_available_seats[substr($HTTP_POST_VARS['availabletourdate'],0,10)]));
			}
			//nirav added for remaining seats---end
			*/
			//hotel-extension {
			$HTTP_POST_VARS['early_hotel_checkin_date'] = substr($HTTP_POST_VARS['early_hotel_checkin_date'],0,10);
			$HTTP_POST_VARS['late_hotel_checkout_date'] = substr($HTTP_POST_VARS['late_hotel_checkout_date'],0,10);

			if(tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date'])){
				$error_he_checkout_dt !='';
				$temp_late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
				$temp_late_hotel_checkin_date = strtotime($temp_late_hotel_checkin_date_split[2].'-'.$temp_late_hotel_checkin_date_split[0].'-'.$temp_late_hotel_checkin_date_split[1]);
				$temp_late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date']);
				$temp_late_hotel_checkout_date = strtotime($temp_late_hotel_checkout_date_split[2].'-'.$temp_late_hotel_checkout_date_split[0].'-'.$temp_late_hotel_checkout_date_split[1]);
				if($temp_late_hotel_checkin_date > $temp_late_hotel_checkout_date){
					//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkout_date=true'));
					$error_he_checkout_dt = 'true';
				}
			}
			if(tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])){
							
				$error_he_checkin_dt !='';
				//$temp_early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
				//$temp_early_hotel_checkin_date = strtotime($temp_early_hotel_checkin_date_split[2].'-'.$temp_early_hotel_checkin_date_split[0].'-'.$temp_early_hotel_checkin_date_split[1]);
				$temp_early_hotel_checkin_date = strtotime((string)$HTTP_POST_VARS['early_hotel_checkin_date']);
				//$temp_early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
				//$temp_early_hotel_checkout_date = strtotime($temp_early_hotel_checkout_date_split[2].'-'.$temp_early_hotel_checkout_date_split[0].'-'.$temp_early_hotel_checkout_date_split[1]);
				$temp_early_hotel_checkout_date = strtotime((string)$HTTP_POST_VARS['early_hotel_checkout_date']);
				if ($temp_early_hotel_checkin_date == strtotime('01/01/1970') || $temp_early_hotel_checkout_date == strtotime('01/01/1970')) {
					$error_he_checkin_dt = 'true';
				} else {
				
					if($temp_early_hotel_checkin_date > $temp_early_hotel_checkout_date){
						//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkout_date=true'));
						$error_he_checkin_dt = 'true';
					}
				}
			}
			//}hotel-extension
			//transfer-check{
			if(tep_check_product_is_transfer((int)$HTTP_POST_VARS['products_id'],$product_transfer_info) == '1'){
				$transfer_error_msg = tep_transfer_validate($HTTP_POST_VARS['products_id'] , $HTTP_POST_VARS,$product_transfer_info['transfer_type']);
				if($transfer_error_msg != ''){
					$error_transfer_info = 'true';
					tep_redirect(tep_href_link("ajax_edit_tour.php", 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&error_transfer_info=true&product_number='.(int)$_GET['product_number'].'&transfer_error_msg='.$transfer_error_msg));
					exit();
				}
			}
			//}

			//amit added to serversite validation for departure date
			//amit modified to make sure price on usd
			$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$HTTP_POST_VARS['products_id']);
			//amit modified to make sure price on usd
			if($HTTP_POST_VARS['_1_H_hot3'] != "")
			{
				$HTTP_POST_VARS['departurelocation'] =  tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot3']).'::::'.tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot2']).' '.tep_db_prepare_input($HTTP_POST_VARS['_1_H_address']);
				// $HTTP_POST_VARS['departurelocation'] =  $HTTP_POST_VARS['_1_H_hot3'].'::::'.' '.$HTTP_POST_VARS['_1_H_address'];
			}
			if($HTTP_POST_VARS['_1_H_hot3r'] != "")
			{
				$HTTP_POST_VARS['departurelocationreturn'] =  $HTTP_POST_VARS['_1_H_hot3r'].'||'.str_replace($HTTP_POST_VARS['_1_H_hot3r'],"",tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot2r'])).' '.tep_db_prepare_input($HTTP_POST_VARS['_1_H_addressr']);
				$HTTP_POST_VARS['departurelocationreturn'] = str_replace('#!#!#','',$HTTP_POST_VARS['departurelocationreturn']);
			}
			$HTTP_POST_VARS['departurelocationreturn'] = str_replace('#!#!#','||',$HTTP_POST_VARS['departurelocationreturn']);

			$product_hotel_price = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "' ");
			$product_hotel_result = tep_db_fetch_array($product_hotel_price);
			
			if((int)$product_hotel_result['is_hotel']==0){
				$is_new_group_buy = 0;
				/*// Howard added new group buy start {
				$group_buy_specials_sql = tep_db_query('SELECT * FROM `specials` WHERE products_id ="'.(int) $HTTP_POST_VARS['products_id'].'" AND status="1" AND start_date <="'.date('Y-m-d H:i:s').'" AND expires_date >"'.date('Y-m-d H:i:s').'" ');
				//echo("select ta.operate_currency_code  from " . TABLE_TRAVEL_AGENCY . " as ta, ".TABLE_PRODUCTS." p where p.agency_id = ta.agency_id and p.products_id = '" .$products_id. "'");
				$group_buy_specials = tep_db_fetch_array($group_buy_specials_sql);
				
				if((int)$group_buy_specials['products_id']){
					if($group_buy_specials['specials_type'] =="1"){
						$tmpPurchasedNum = tep_get_product_orders_guest_count($group_buy_specials['products_id'],$group_buy_specials['start_date'],$group_buy_specials['expires_date']);
						$tmpBalanceNum = max(0,(int)($group_buy_specials['specials_max_buy_num']-$tmpPurchasedNum));
					}
					//0为普通特价，1为限量团购，2为限时团购
					if(($group_buy_specials['specials_type'] =="1" && (int)$tmpBalanceNum) || $group_buy_specials['specials_type']=="2" || $group_buy_specials['specials_type']=="0"){
						if($group_buy_specials['specials_type'] =="1" || $group_buy_specials['specials_type']=="2"){
							$is_new_group_buy = 1;
						}
						if((int)$group_buy_specials['specials_new_products_single']){$product_hotel_result['products_single'] = $group_buy_specials['specials_new_products_single'];}
						if((int)$group_buy_specials['specials_new_products_single_pu']){$product_hotel_result['products_single_pu'] = $group_buy_specials['specials_new_products_single_pu'];}
						if((int)$group_buy_specials['specials_new_products_double']){$product_hotel_result['products_double'] = $group_buy_specials['specials_new_products_double'];	}
						if((int)$group_buy_specials['specials_new_products_triple']){$product_hotel_result['products_triple'] = $group_buy_specials['specials_new_products_triple'];}
						if((int)$group_buy_specials['specials_new_products_quadr']){$product_hotel_result['products_quadr'] = $group_buy_specials['specials_new_products_quadr'];}
						if((int)$group_buy_specials['specials_new_products_kids']){$product_hotel_result['products_kids'] = $group_buy_specials['specials_new_products_kids'];}
					}
				}
				// Howard added new group buy end }*/
				//Howard 取得通过特价和团购检查后的最终标准价
				tep_get_final_price_from_specials($HTTP_POST_VARS['products_id'], $product_hotel_result, $is_new_group_buy);

				//amit modified to make sure price on usd
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$product_hotel_result['products_single'] = tep_get_tour_price_in_usd($product_hotel_result['products_single'],$tour_agency_opr_currency);
					$product_hotel_result['products_single_pu'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_pu'],$tour_agency_opr_currency);
					$product_hotel_result['products_double'] = tep_get_tour_price_in_usd($product_hotel_result['products_double'],$tour_agency_opr_currency);
					$product_hotel_result['products_triple'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple'],$tour_agency_opr_currency);
					$product_hotel_result['products_quadr'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr'],$tour_agency_opr_currency);
					$product_hotel_result['products_kids'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids'],$tour_agency_opr_currency);

					$product_hotel_result['products_single_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_cost'],$tour_agency_opr_currency);
					$product_hotel_result['products_single_pu_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_pu_cost'],$tour_agency_opr_currency);
					$product_hotel_result['products_double_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_double_cost'],$tour_agency_opr_currency);
					$product_hotel_result['products_triple_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple_cost'],$tour_agency_opr_currency);
					$product_hotel_result['products_quadr_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
					$product_hotel_result['products_kids_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids_cost'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				//howard added for single pair up
				$single_price_tmp = $product_hotel_result['products_single'];
				if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$product_hotel_result['products_single_pu']){
					$single_price_tmp = $product_hotel_result['products_single_pu'];
				}

				$a_price[1] = $single_price_tmp; //single or single pair up
				$a_price[2] = $product_hotel_result['products_double']; //double
				$a_price[3] = $product_hotel_result['products_triple']; //triple
				$a_price[4] = $product_hotel_result['products_quadr']; //quadr
				$e = $product_hotel_result['products_kids']; //child Kid

				//howard added for single cost pair up
				$single_cost_tmp = $product_hotel_result['products_single_cost'];
				if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$product_hotel_result['products_single_pu_cost']){
					$single_cost_tmp = $product_hotel_result['products_single_pu_cost'];
				}
				$a_cost[1] = $single_cost_tmp; //single or single pair up
				$a_cost[2] = $product_hotel_result['products_double_cost']; //double
				$a_cost[3] = $product_hotel_result['products_triple_cost']; //triple
				$a_cost[4] = $product_hotel_result['products_quadr_cost']; //quadr
				$e_cost = $product_hotel_result['products_kids_cost']; //child Kid
				
				$get_reg_date_price_array = explode('!!!',$HTTP_POST_VARS['availabletourdate']);
				$HTTP_POST_VARS['availabletourdate'] = $get_reg_date_price_array[0];

				 /* Price Displaying - standard price for different reg/irreg sections - start */ 
				
				$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and products_id ='".(int)$_POST['products_id']."' ");
				
				if(tep_db_num_rows($check_standard_price_dates)>0){
					$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
					$operate_start_date = $row_standard_price_dates['operate_start_date'];
					$operate_end_date = $row_standard_price_dates['operate_end_date'];
				}else{
					$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and products_id ='".(int)$_POST['products_id']."' ");
					
					$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
					$operate_start_date = $row_standard_price_dates1['operate_start_date'];
					$operate_end_date = $row_standard_price_dates1['operate_end_date'];
				}
				
				$check_section_standard_price_query = "select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where operate_start_date ='".$operate_start_date."' and operate_end_date = '".$operate_end_date."' and products_id ='".(int)$_POST['products_id']."' and products_single > 0 ";
				
				$check_section_standard_price = tep_db_query($check_section_standard_price_query);
				if(tep_db_num_rows($check_section_standard_price)>0){
					$row_section_standard_price = tep_db_fetch_array($check_section_standard_price);

					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$row_section_standard_price['products_single'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single'],$tour_agency_opr_currency);
						$row_section_standard_price['products_single_pu'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_pu'],$tour_agency_opr_currency);
						$row_section_standard_price['products_double'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double'],$tour_agency_opr_currency);
						$row_section_standard_price['products_triple'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple'],$tour_agency_opr_currency);
						$row_section_standard_price['products_quadr'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr'],$tour_agency_opr_currency);
						$row_section_standard_price['products_kids'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids'],$tour_agency_opr_currency);
						$row_section_standard_price['products_roundtrip'] = tep_get_tour_price_in_usd($row_section_standard_price['products_roundtrip'],$tour_agency_opr_currency);//roundtrip price

						$row_section_standard_price['products_single_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_single_pu_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_pu_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_double_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_triple_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_quadr_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_kids_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids_cost'],$tour_agency_opr_currency);
						$row_section_standard_price['products_roundtrip_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_roundtrip_cost'],$tour_agency_opr_currency);//roundtrip price
					}
					 $a_price[1] = $row_section_standard_price['products_single']; //single
					  if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$row_section_standard_price['products_single_pu']){
						$a_price[1] = $row_section_standard_price['products_single_pu'];
					 }
					 $a_price[2] = $row_section_standard_price['products_double']; //double
					 $a_price[3] = $row_section_standard_price['products_triple']; //triple
					 $a_price[4] = $row_section_standard_price['products_quadr']; //quadr
					 $e = $row_section_standard_price['products_kids'];
					 
					 $a_cost[1] = $row_section_standard_price['products_single_cost']; //single
					 if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$row_section_standard_price['products_single_pu_cost']){
						$a_cost[1] = $row_section_standard_price['products_single_pu_cost'];
					 }
					 $a_cost[2] = $row_section_standard_price['products_double_cost']; //double
					 $a_cost[3] = $row_section_standard_price['products_triple_cost']; //triple
					 $a_cost[4] = $row_section_standard_price['products_quadr_cost']; //quadr
					 $e_cost = $row_section_standard_price['products_kids_cost']; //child Kid
				}
				 
				/* Price Displaying - standard price for different reg/irreg sections - start */

				//amit added to check if special price is available for specific date start
				$special_dt_chk = 0;
				$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 ) and products_id ='".(int)$HTTP_POST_VARS['products_id']."' ";
				
				$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
				while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){
					//amit modified to make sure price on usd
					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_pu'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);

						$check_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_single_pu_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_pu_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
						$check_specip_pride_date_row['extra_charge_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['extra_charge_cost'],$tour_agency_opr_currency);
					}
					//amit modified to make sure price on usd
					//howard added for single pair up
					$single_price_tmp = $check_specip_pride_date_row['spe_single'];
					if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$check_specip_pride_date_row['spe_single_pu']){
						$single_price_tmp = $check_specip_pride_date_row['spe_single_pu'];
					}
					$a_price[1] = $single_price_tmp; //single or single pair up
					$a_price[2] = $check_specip_pride_date_row['spe_double']; //double
					$a_price[3] = $check_specip_pride_date_row['spe_triple']; //triple
					$a_price[4] = $check_specip_pride_date_row['spe_quadruple']; //quadr
					$e = $check_specip_pride_date_row['spe_kids']; //child Kid

					//howard added for single pair up
					$single_cost_tmp = $check_specip_pride_date_row['spe_single_cost'];
					if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$check_specip_pride_date_row['spe_single_pu_cost']){
						$single_cost_tmp = $check_specip_pride_date_row['spe_single_pu_cost'];
					}
					$a_cost[1] = $single_cost_tmp; //single or single pair up
					$a_cost[2] = $check_specip_pride_date_row['spe_double_cost']; //double
					$a_cost[3] = $check_specip_pride_date_row['spe_triple_cost']; //triple
					$a_cost[4] = $check_specip_pride_date_row['spe_quadruple_cost']; //quadr
					$e_cost = $check_specip_pride_date_row['spe_kids_cost']; //child Kid
					$date_price_cost = $check_specip_pride_date_row['extra_charge_cost'];
					$special_dt_chk = 1;
				}
				//amit added to check if special price is available specific date end

				//amit added to check if regular special price available start
				if($special_dt_chk == 0 && $get_reg_date_price_array[1] != ''){
					$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$HTTP_POST_VARS['products_id']."' ";

					$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
					while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
						if($check_reg_specip_pride_date_row['extra_charge'] > 0){
							//amit modified to make sure price on usd
							if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
								$check_reg_specip_pride_date_row['extra_charge_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['extra_charge_cost'],$tour_agency_opr_currency);
							}
							//amit modified to make sure price on usd
							$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
						}else{
							//amit modified to make sure price on usd
							if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
								$check_reg_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_single_pu'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_pu'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);

								$check_reg_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_single_pu_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_pu_cost'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
								$check_reg_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
							}
							//amit modified to make sure price on usd
							//howard added for single pair up
							$single_price_tmp = $check_reg_specip_pride_date_row['spe_single'];
							if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$check_reg_specip_pride_date_row['spe_single_pu']){
								$single_price_tmp = $check_reg_specip_pride_date_row['spe_single_pu'];
							}
							$a_price[1] = $single_price_tmp; //single or single pair up
							$a_price[2] = $check_reg_specip_pride_date_row['spe_double']; //double
							$a_price[3] = $check_reg_specip_pride_date_row['spe_triple']; //triple
							$a_price[4] = $check_reg_specip_pride_date_row['spe_quadruple']; //quadr
							$e = $check_reg_specip_pride_date_row['spe_kids']; //child Kid

							$single_cost_tmp = $check_reg_specip_pride_date_row['spe_single_cost'];
							if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$check_reg_specip_pride_date_row['spe_single_pu_cost']){
								$single_cost_tmp = $check_reg_specip_pride_date_row['spe_single_pu_cost'];
							}
							$a_cost[1] = $check_reg_specip_pride_date_row['spe_single_cost']; //single or single pair up
							$a_cost[2] = $check_reg_specip_pride_date_row['spe_double_cost']; //double
							$a_cost[3] = $check_reg_specip_pride_date_row['spe_triple_cost']; //triple
							$a_cost[4] = $check_reg_specip_pride_date_row['spe_quadruple_cost']; //quadr
							$e_cost = $check_reg_specip_pride_date_row['spe_kids_cost']; //child Kid
						}
						$special_dt_chk = 1;
					}
				}
				//amit added to check if regular special price avalilable end
			}else{
				$a_price[1] = 0; //single
				$a_price[2] = 0; //double
				$a_price[3] = 0; //triple
				$a_price[4] = 0; //quadr
				$e = 0; //child Kid

				$a_cost[1] = 0; //single
				$a_cost[2] = 0; //double
				$a_cost[3] = 0; //triple
				$a_cost[4] = 0; //quadr
				$e_cost = 0; //child Kid
			}
			//hotel-extension {
			/* Hotel Extensions - Early/Late check-in/out - start */
			$hotel_extension_price[1] = 0;
			$hotel_extension_price[2] = 0;
			$hotel_extension_price[3] = 0;
			$hotel_extension_price[4] = 0;
			$hotel_extension_price[0] = 0;
			$hotel_extension_price_cost[1] = 0;
			$hotel_extension_price_cost[2] = 0;
			$hotel_extension_price_cost[3] = 0;
			$hotel_extension_price_cost[4] = 0;
			$hotel_extension_price_cost[0] = 0;
			$early_hotel_extension_price[1] = 0;
			$early_hotel_extension_price[2] = 0;
			$early_hotel_extension_price[3] = 0;
			$early_hotel_extension_price[4] = 0;
			$early_hotel_extension_price[0] = 0;
			$early_hotel_extension_price_cost[1] = 0;
			$early_hotel_extension_price_cost[2] = 0;
			$early_hotel_extension_price_cost[3] = 0;
			$early_hotel_extension_price_cost[4] = 0;
			$early_hotel_extension_price_cost[0] = 0;
			$late_hotel_extension_price[1] = 0;
			$late_hotel_extension_price[2] = 0;
			$late_hotel_extension_price[3] = 0;
			$late_hotel_extension_price[4] = 0;
			$late_hotel_extension_price[0] = 0;
			$late_hotel_extension_price_cost[1] = 0;
			$late_hotel_extension_price_cost[2] = 0;
			$late_hotel_extension_price_cost[3] = 0;
			$late_hotel_extension_price_cost[4] = 0;
			$late_hotel_extension_price_cost[0] = 0;
			$hotel_extension_info = "";
			if((isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])) || (isset($HTTP_POST_VARS['late_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date']) && isset($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['staying_late_hotels'])) ){
				if(isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])  && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])){
					$early_hotel_id = $HTTP_POST_VARS['early_arrival_hotels'];
					$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$early_hotel_id);
					//get default standard prices of hotel - start
					$hotel_price_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$early_hotel_id . "' ");
					$hotel_result = tep_db_fetch_array($hotel_price_query);
					//amit modified to make sure price on usd
					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
						$hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
						$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
						$hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
						$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
						$hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
						$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
						$hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
						$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
						$hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
					}
					//amit modified to make sure price on usd

					//get default standard prices of hotel - end
					//$early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
					//$early_hotel_checkin_date = $early_hotel_checkin_date_split[2].'-'.$early_hotel_checkin_date_split[0].'-'.$early_hotel_checkin_date_split[1];
					//$early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
					//$early_hotel_checkout_date = $early_hotel_checkout_date_split[2].'-'.$early_hotel_checkout_date_split[0].'-'.$early_hotel_checkout_date_split[1];

					$early_total_dates_price = array();
					$loop_start = strtotime((string)$HTTP_POST_VARS['early_hotel_checkin_date']);
					$loop_end = strtotime((string)$HTTP_POST_VARS['early_hotel_checkout_date']) - (24*60*60);
					$loop_increment = (24*60*60);
					$total_early_stay_days = 0;
					$early_hotel_price_extra = '';
					for($d=$loop_start; $d<=$loop_end; $d=$d + $loop_increment){
						$early_arrival_date = date("Y-m-d", $d);

						$hotel_price[1] = 0;
						$hotel_price[2] = 0;
						$hotel_price[3] = 0;
						$hotel_price[4] = 0;
						$hotel_price[0] = 0;
						$hotel_price_cost[1] = 0;
						$hotel_price_cost[2] = 0;
						$hotel_price_cost[3] = 0;
						$hotel_price_cost[4] = 0;
						$hotel_price_cost[0] = 0;

						$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
						$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
						$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
						$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
						$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
						$hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
						//get sections standard price
						$hotel_standard_prices = tep_db_query("select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id ='".(int)$early_hotel_id."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' ");
						if(tep_db_num_rows($hotel_standard_prices)>0){
							$hotel_std_price = tep_db_fetch_array($hotel_standard_prices);
							if(tep_not_null($hotel_std_price['products_single'])){
								$hotel_price[1] = $hotel_std_price['products_single'];
							}
							if(tep_not_null($hotel_std_price['products_double'])){
								$hotel_price[2] = $hotel_std_price['products_double'];
							}
							if(tep_not_null($hotel_std_price['products_triple'])){
								$hotel_price[3] = $hotel_std_price['products_triple'];
							}
							if(tep_not_null($hotel_std_price['products_quadr'])){
								$hotel_price[4] = $hotel_std_price['products_quadr'];
							}
							if(tep_not_null($hotel_std_price['products_kids'])){
								$hotel_price[0] = $hotel_std_price['products_kids'];
							}
							if(tep_not_null($hotel_std_price['products_single_cost'])){
								$hotel_price_cost[1] = $hotel_std_price['products_single_cost'];
							}
							if(tep_not_null($hotel_std_price['products_double_cost'])){
								$hotel_price_cost[2] = $hotel_std_price['products_double_cost'];
							}
							if(tep_not_null($hotel_std_price['products_triple_cost'])){
								$hotel_price_cost[3] = $hotel_std_price['products_triple_cost'];
							}
							if(tep_not_null($hotel_std_price['products_quadr_cost'])){
								$hotel_price_cost[4] = $hotel_std_price['products_quadr_cost'];
							}
							if(tep_not_null($hotel_std_price['products_kids_cost'])){
								$hotel_price_cost[0] = $hotel_std_price['products_kids_cost'];
							}
						}
						//get sections standard price

						$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and available_date = '' and products_id ='".(int)$early_hotel_id."' ";
						$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
						if(tep_db_num_rows($check_reg_specip_pride_date_query)>0){
							while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
								if($check_reg_specip_pride_date_row['extra_charge'] > 0 && $check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($early_arrival_date))+1) ){
									if($check_reg_specip_pride_date_row['prefix'] == '-'){
										$hotel_price[1] = $hotel_price[1] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] - $check_reg_specip_pride_date_row['extra_charge'];
										//$early_hotel_price_extra .= '(Special price '.date("D", $d).' - $'.$check_reg_specip_pride_date_row['extra_charge'].')';
										$hotel_price_cost[1] = $hotel_price_cost[1] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] - $check_reg_specip_pride_date_row['extra_charge_cost'];
									}else{
										$hotel_price[1] = $hotel_price[1] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price_cost[1] = $hotel_price_cost[1] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] + $check_reg_specip_pride_date_row['extra_charge_cost'];
									}
									//$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
								}else if($check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($early_arrival_date))+1) ){
									//amit modified to make sure price on usd
									$check_reg_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
									$hotel_price[1] = $check_reg_specip_pride_date_row['spe_single']; //single
									//$early_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' $'.$hotel_price[1].')';
									$check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
									$hotel_price[2] = $check_reg_specip_pride_date_row['spe_double']; //single
									$check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
									$hotel_price[3] = $check_reg_specip_pride_date_row['spe_triple']; //single
									$check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
									$hotel_price[4] = $check_reg_specip_pride_date_row['spe_quadruple']; //single
									$check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
									$hotel_price[0] = $check_reg_specip_pride_date_row['spe_kids']; //single

									$check_reg_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[1] = $check_reg_specip_pride_date_row['spe_single_cost']; //single
									$check_reg_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[2] = $check_reg_specip_pride_date_row['spe_double_cost']; //single
									$check_reg_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[3] = $check_reg_specip_pride_date_row['spe_triple_cost']; //single
									$check_reg_specip_pride_date_row['spe_quadr_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadr_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[4] = $check_reg_specip_pride_date_row['spe_quadruple_cost']; //single
									$check_reg_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[0] = $check_reg_specip_pride_date_row['spe_kids_cost']; //single
									//amit modified to make sure price on usd
								}
								$special_dt_chk = 1;
							}
						}
						$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$early_arrival_date."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$early_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$early_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$early_hotel_id."' ";
						$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
						if(tep_db_num_rows($check_specip_pride_date_query)>0){
							while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){
								//amit modified to make sure price on usd

								if($check_specip_pride_date_row['extra_charge'] > 0){
									if($check_specip_pride_date_row['prefix'] == '-'){
										$hotel_price[1] = $hotel_price[1] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] - $check_specip_pride_date_row['extra_charge'];

										$hotel_price_cost[1] = $hotel_price_cost[1] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] - $check_specip_pride_date_row['extra_charge_cost'];
									}else{
										$hotel_price[1] = $hotel_price[1] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] + $check_specip_pride_date_row['extra_charge'];

										$hotel_price_cost[1] = $hotel_price_cost[1] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] + $check_specip_pride_date_row['extra_charge_cost'];
									}

								}else{
									$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
									$hotel_price[1] = $check_specip_pride_date_row['spe_single']; //single
									$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
									$hotel_price[2] = $check_specip_pride_date_row['spe_double']; //single
									$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
									$hotel_price[3] = $check_specip_pride_date_row['spe_triple']; //single
									$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
									$hotel_price[4] = $check_specip_pride_date_row['spe_quadruple']; //single
									$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
									$hotel_price[0] = $check_specip_pride_date_row['spe_kids']; //single

									$check_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[1] = $check_specip_pride_date_row['spe_single_cost']; //single
									$check_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[2] = $check_specip_pride_date_row['spe_double_cost']; //single
									$check_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[3] = $check_specip_pride_date_row['spe_triple_cost']; //single
									$check_specip_pride_date_row['spe_quadr_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[4] = $check_specip_pride_date_row['spe_quadruple_cost']; //single
									$check_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[0] = $check_specip_pride_date_row['spe_kids_cost']; //single
								}
								//amit modified to make sure price on usd


								$special_dt_chk = 1;
							}
						}
						$early_hotel_extension_price[1] = $early_hotel_extension_price[1] + $hotel_price[1]; //single
						$early_hotel_extension_price[2] = $early_hotel_extension_price[2] + $hotel_price[2]; //double
						$early_hotel_extension_price[3] = $early_hotel_extension_price[3] + $hotel_price[3]; //triple
						$early_hotel_extension_price[4] = $early_hotel_extension_price[4] + $hotel_price[4]; //quadr
						$early_hotel_extension_price[0] = $early_hotel_extension_price[0] + $hotel_price[0]; //kids

						$early_hotel_extension_price_cost[1] = $early_hotel_extension_price_cost[1] + $hotel_price_cost[1]; //single
						$early_hotel_extension_price_cost[2] = $early_hotel_extension_price_cost[2] + $hotel_price_cost[2]; //double
						$early_hotel_extension_price_cost[3] = $early_hotel_extension_price_cost[3] + $hotel_price_cost[3]; //triple
						$early_hotel_extension_price_cost[4] = $early_hotel_extension_price_cost[4] + $hotel_price_cost[4]; //quadr
						$early_hotel_extension_price_cost[0] = $early_hotel_extension_price_cost[0] + $hotel_price_cost[0]; //kids

						//echo $early_arrival_date.' '.$hotel_price[2];
						//echo '<br>';
						$total_early_stay_days++;
						$early_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[1]);
					}
					/*
					$previous_value = 0;
					$count = 0;
					for($p=0; $p<=sizeof($early_total_dates_price); $p++){

					if($early_total_dates_price[$p]['text'] != $previous_value){
					if($p!=0){
					if($previous_date!=$previous_key){
					$early_hotel_price_extra .= '-'.$previous_key;
					}
					$early_hotel_price_extra .= ': $'.$previous_value.' @ '.$count.' Night(s) = '.number_format(($previous_value*$count), 2).'<br />';
					$count = 0;
					}
					$previous_date = $early_total_dates_price[$p]['id'];
					$early_hotel_price_extra .= ' &nbsp; '.$previous_date;

					}
					$count++;
					$previous_value = $early_total_dates_price[$p]['text'];
					$previous_key = $early_total_dates_price[$p]['id'];
					}
					*/
				}

				if(isset($HTTP_POST_VARS['late_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date']) && isset($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['staying_late_hotels'])){
					$late_hotel_id = $HTTP_POST_VARS['staying_late_hotels'];
					$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$early_hotel_id);
					//get default standard prices of hotel - start
					$hotel_price_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$late_hotel_id . "' ");
					$hotel_result = tep_db_fetch_array($hotel_price_query);
					//amit modified to make sure price on usd
					if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
						$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
						$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
						$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
						$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
						$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
						$hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);
					}
					//amit modified to make sure price on usd

					//get default standard prices of hotel - end
					$late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
					$late_hotel_checkin_date = $late_hotel_checkin_date_split[2].'-'.$late_hotel_checkin_date_split[0].'-'.$late_hotel_checkin_date_split[1];
					$late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date']);
					$late_hotel_checkout_date = $late_hotel_checkout_date_split[2].'-'.$late_hotel_checkout_date_split[0].'-'.$late_hotel_checkout_date_split[1];

					$late_total_dates_price = array();
					$loop_start = strtotime($late_hotel_checkin_date) + (24*60*60);
					$loop_end = strtotime($late_hotel_checkout_date);
					$loop_increment = (24*60*60);
					$total_late_stay_days = 0;
					$late_hotel_price_extra = '';
					for($d=$loop_start; $d<=$loop_end; $d=$d + $loop_increment){
						$late_arrival_date = date("Y-m-d", $d);
						$hotel_price[1] = 0;$hotel_price[2] = 0;$hotel_price[3] = 0;$hotel_price[4] = 0;$hotel_price[0] = 0;
						$hotel_price_cost[1] = 0;$hotel_price_cost[2] = 0;$hotel_price_cost[3] = 0;$hotel_price_cost[4] = 0;$hotel_price_cost[0] = 0;
						$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
						$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
						$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
						$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
						$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
						$hotel_price_cost[1] = tep_get_tour_price_in_usd($hotel_result['products_single_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[2] = tep_get_tour_price_in_usd($hotel_result['products_double_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[3] = tep_get_tour_price_in_usd($hotel_result['products_triple_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
						$hotel_price_cost[0] = tep_get_tour_price_in_usd($hotel_result['products_kids_cost'],$tour_agency_opr_currency);

						//get sections standard price
						$hotel_standard_prices = tep_db_query("select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id ='".(int)$late_hotel_id."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' ");
						if(tep_db_num_rows($hotel_standard_prices)>0){
							$hotel_std_price = tep_db_fetch_array($hotel_standard_prices);
							if(tep_not_null($hotel_std_price['products_single'])){$hotel_price[1] = $hotel_std_price['products_single'];}
							if(tep_not_null($hotel_std_price['products_double'])){$hotel_price[2] = $hotel_std_price['products_double'];}
							if(tep_not_null($hotel_std_price['products_triple'])){$hotel_price[3] = $hotel_std_price['products_triple'];}
							if(tep_not_null($hotel_std_price['products_quadr'])){$hotel_price[4] = $hotel_std_price['products_quadr'];}
							if(tep_not_null($hotel_std_price['products_kids'])){$hotel_price[0] = $hotel_std_price['products_kids'];}
							if(tep_not_null($hotel_std_price['products_single_cost'])){$hotel_price_cost[1] = $hotel_std_price['products_single_cost'];}
							if(tep_not_null($hotel_std_price['products_double_cost'])){$hotel_price_cost[2] = $hotel_std_price['products_double_cost'];}
							if(tep_not_null($hotel_std_price['products_triple_cost'])){$hotel_price_cost[3] = $hotel_std_price['products_triple_cost'];}
							if(tep_not_null($hotel_std_price['products_quadr_cost'])){$hotel_price_cost[4] = $hotel_std_price['products_quadr_cost'];}
							if(tep_not_null($hotel_std_price['products_kids_cost'])){$hotel_price_cost[0] = $hotel_std_price['products_kids_cost'];}
						}
						//get sections standard price

						$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and available_date = '' and products_id ='".(int)$late_hotel_id."' ";
						$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
						if(tep_db_num_rows($check_reg_specip_pride_date_query)>0){
							while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
								//echo $early_arrival_date . '---' . (date("w", strtotime($late_arrival_date))+1);
								if($check_reg_specip_pride_date_row['extra_charge'] > 0 && $check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1) ){
									if($check_reg_specip_pride_date_row['prefix'] == '-'){
										$hotel_price[1] = $hotel_price[1] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] - $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] - $check_reg_specip_pride_date_row['extra_charge'];

										$hotel_price_cost[1] = $hotel_price_cost[1] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] - $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] - $check_reg_specip_pride_date_row['extra_charge_cost'];
									}else{
										$hotel_price[1] = $hotel_price[1] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] + $check_reg_specip_pride_date_row['extra_charge'];
										$hotel_price_cost[1] = $hotel_price_cost[1] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] + $check_reg_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] + $check_reg_specip_pride_date_row['extra_charge_cost'];
									}

									//$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
								}else if($check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1) ){
									//amit modified to make sure price on usd
									$check_reg_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
									$hotel_price[1] = $check_reg_specip_pride_date_row['spe_single']; //single
									$check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
									$hotel_price[2] = $check_reg_specip_pride_date_row['spe_double']; //single
									$check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
									$hotel_price[3] = $check_reg_specip_pride_date_row['spe_triple']; //single
									$check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
									$hotel_price[4] = $check_reg_specip_pride_date_row['spe_quadruple']; //single
									$check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
									$hotel_price[0] = $check_reg_specip_pride_date_row['spe_kids']; //single

									$check_reg_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[1] = $check_reg_specip_pride_date_row['spe_single_cost']; //single
									$check_reg_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[2] = $check_reg_specip_pride_date_row['spe_double_cost']; //single
									$check_reg_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[3] = $check_reg_specip_pride_date_row['spe_triple_cost']; //single
									$check_reg_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[4] = $check_reg_specip_pride_date_row['spe_quadruple_cost']; //single
									$check_reg_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[0] = $check_reg_specip_pride_date_row['spe_kids_cost']; //single
									//amit modified to make sure price on usd
								}
								$special_dt_chk = 1;
							}
						}
						$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$late_arrival_date."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$early_hotel_id."' ";
						$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
						if(tep_db_num_rows($check_specip_pride_date_query)>0){
							while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){
								//amit modified to make sure price on usd

								if($check_specip_pride_date_row['extra_charge'] > 0){
									if($check_specip_pride_date_row['prefix'] == '-'){
										$hotel_price[1] = $hotel_price[1] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] - $check_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] - $check_specip_pride_date_row['extra_charge'];

										$hotel_price_cost[1] = $hotel_price_cost[1] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] - $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] - $check_specip_pride_date_row['extra_charge_cost'];
									}else{
										$hotel_price[1] = $hotel_price[1] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[2] = $hotel_price[2] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[3] = $hotel_price[3] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[4] = $hotel_price[4] + $check_specip_pride_date_row['extra_charge'];
										$hotel_price[0] = $hotel_price[0] + $check_specip_pride_date_row['extra_charge'];

										$hotel_price_cost[1] = $hotel_price_cost[1] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[2] = $hotel_price_cost[2] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[3] = $hotel_price_cost[3] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[4] = $hotel_price_cost[4] + $check_specip_pride_date_row['extra_charge_cost'];
										$hotel_price_cost[0] = $hotel_price_cost[0] + $check_specip_pride_date_row['extra_charge_cost'];
									}

								}else{
									$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
									$hotel_price[1] = $check_specip_pride_date_row['spe_single']; //single
									$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
									$hotel_price[2] = $check_specip_pride_date_row['spe_double']; //single
									$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
									$hotel_price[3] = $check_specip_pride_date_row['spe_triple']; //single
									$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
									$hotel_price[4] = $check_specip_pride_date_row['spe_quadruple']; //single
									$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
									$hotel_price[0] = $check_specip_pride_date_row['spe_kids']; //single

									$check_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[1] = $check_specip_pride_date_row['spe_single_cost']; //single
									$check_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[2] = $check_specip_pride_date_row['spe_double_cost']; //single
									$check_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[3] = $check_specip_pride_date_row['spe_triple_cost']; //single
									$check_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[4] = $check_specip_pride_date_row['spe_quadruple_cost']; //single
									$check_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
									$hotel_price_cost[0] = $check_specip_pride_date_row['spe_kids_cost']; //single
								}
								//amit modified to make sure price on usd


								$special_dt_chk = 1;
							}
						}
						$late_hotel_extension_price[1] = $late_hotel_extension_price[1] + $hotel_price[1]; //single
						$late_hotel_extension_price[2] = $late_hotel_extension_price[2] + $hotel_price[2]; //single
						$late_hotel_extension_price[3] = $late_hotel_extension_price[3] + $hotel_price[3]; //single
						$late_hotel_extension_price[4] = $late_hotel_extension_price[4] + $hotel_price[4]; //single
						$late_hotel_extension_price[0] = $late_hotel_extension_price[0] + $hotel_price[0]; //single

						$late_hotel_extension_price_cost[1] = $late_hotel_extension_price_cost[1] + $hotel_price_cost[1]; //single
						$late_hotel_extension_price_cost[2] = $late_hotel_extension_price_cost[2] + $hotel_price_cost[2]; //single
						$late_hotel_extension_price_cost[3] = $late_hotel_extension_price_cost[3] + $hotel_price_cost[3]; //single
						$late_hotel_extension_price_cost[4] = $late_hotel_extension_price_cost[4] + $hotel_price_cost[4]; //single
						$late_hotel_extension_price_cost[0] = $late_hotel_extension_price_cost[0] + $hotel_price_cost[0]; //single
						//echo $late_arrival_date.' '.$hotel_price;
						//echo '<br>';
						$total_late_stay_days++;
						$late_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[1]);
					}
					/*
					$previous_value = 0;
					$count = 0;
					for($p=0; $p<=sizeof($late_total_dates_price); $p++){

					if($late_total_dates_price[$p]['text'] != $previous_value){
					if($p!=0){
					if($previous_date!=$previous_key){
					$late_hotel_price_extra .= '-'.$previous_key;
					}
					$late_hotel_price_extra .= ': $'.$previous_value.' @ '.$count.' Night(s) = '.number_format(($previous_value*$count), 2).'<br />';
					$count = 0;
					}
					$previous_date = $late_total_dates_price[$p]['id'];
					$late_hotel_price_extra .= ' &nbsp; '.$previous_date;

					}
					$count++;
					$previous_value = $late_total_dates_price[$p]['text'];
					$previous_key = $late_total_dates_price[$p]['id'];
					}
					*/
				}

				/*$total_early_hotel_extension_price = $early_hotel_extension_price[1]*(int)$HTTP_POST_VARS['early_hotel_rooms'];
				$total_late_hotel_extension_price = $late_hotel_extension_price[1]*(int)$HTTP_POST_VARS['late_hotel_rooms'];
				$total_hotel_extension_price = $hotel_extension_price[1] = $total_early_hotel_extension_price + $total_late_hotel_extension_price;
				*/
				$hotel_extension_price[1] = $early_hotel_extension_price[1] + $late_hotel_extension_price[1];
				$hotel_extension_price[2] = $early_hotel_extension_price[2] + $late_hotel_extension_price[2];
				$hotel_extension_price[3] = $early_hotel_extension_price[3] + $late_hotel_extension_price[3];
				$hotel_extension_price[4] = $early_hotel_extension_price[4] + $late_hotel_extension_price[4];
				$hotel_extension_price[0] = $early_hotel_extension_price[0] + $late_hotel_extension_price[0];
				/*
				$total_early_hotel_extension_price_cost = $early_hotel_extension_price_cost[1]*(int)$HTTP_POST_VARS['early_hotel_rooms'];
				$total_late_hotel_extension_price_cost = $late_hotel_extension_price_cost[1]*(int)$HTTP_POST_VARS['late_hotel_rooms'];
				$total_hotel_extension_price_cost = $hotel_extension_price_cost[1] = $total_early_hotel_extension_price_cost + $total_late_hotel_extension_price_cost;
				*/
				$hotel_extension_price_cost[1] = $early_hotel_extension_price_cost[1] + $late_hotel_extension_price_cost[1];
				$hotel_extension_price_cost[2] = $early_hotel_extension_price_cost[2] + $late_hotel_extension_price_cost[2];
				$hotel_extension_price_cost[3] = $early_hotel_extension_price_cost[3] + $late_hotel_extension_price_cost[3];
				$hotel_extension_price_cost[4] = $early_hotel_extension_price_cost[4] + $late_hotel_extension_price_cost[4];
				$hotel_extension_price_cost[0] = $early_hotel_extension_price_cost[0] + $late_hotel_extension_price_cost[0];

			}
			/* Hotel Extensions - Early/Late check-in/out - end */
			//}
			$total_no_guest_tour = 0;// Total no of guest for entering names

			if($_POST['numberOfRooms'])//if there is rooms
			{
				if($_POST['numberOfRooms']==16 && $_POST['travel_comp']=='1'){
					$_POST['numberOfRooms']=1;
				}
				/*****************************************/
				$validation_no = $product_hotel_result['maximum_no_of_guest'];
				/*****************************************/

				$errormessageperson = "";
				if($language == 'tchinese' || $language == 'schinese'){
					$total_info_room = "</br>".TEXT_TOTAL_OF_ROOMS."".$_POST['numberOfRooms'];
					$early_he_total_info_room = "<br>".TEXT_TOTAL_OF_ROOMS." ".$_POST['numberOfRooms'];
					$late_he_total_info_room = "<br>".TEXT_TOTAL_OF_ROOMS." ".$_POST['numberOfRooms'];
				}else{
					$total_info_room = "</br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'];
					$early_he_total_info_room = "<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'];
					$late_he_total_info_room = "<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'];
				}
				$total_info_room .= $total_info_room_addon_feature_str;
				$bed_option_info = $total_room_adult_child_info =  $_POST['numberOfRooms']."###";
				//$room_price_old 是原价
				$room_price_old = 0;

				for($i=0;$i<$_POST['numberOfRooms'];$i++)
				{
					$is_buy_two_get_one = check_buy_two_get_one($products_id, ($_POST['room-'.$i.'-adult-total']+$_POST['room-'.$i.'-child-total']), $tmp_dp_date);

					/*******************************************************************************************************/
					/****************************BOC: this is validation for the first room ********************************/
					if($_POST['room-'.$i.'-adult-total']!='')
					{
						/****************total number of person in room *****************/
						$totalinroom[$i] = $_POST['room-'.$i.'-adult-total'];
						if($_POST['room-'.$i.'-child-total']!='')
						{
							$totalinroom[$i] += $_POST['room-'.$i.'-child-total'];
						}
						/****************total number of person in room *****************/

						/****************validation for more than 4 person *****************/
						if($totalinroom[$i] > $validation_no)
						{
							$errormessageperson =  $validation_no;
						}
						else
						{

							if($totalinroom[$i]==2){	//当前房间人数=2时，可以接收床型选项的值
								$bed_option_info .= (int)$_POST['room-'.$i.'-bed'].'###';
							}
							/****************total price for room no 1 *****************/
							if($_POST['room-'.$i.'-child-total'] == '0')
							{
								$room_price[$i] = $_POST['room-'.$i.'-adult-total']*$a_price[$_POST['room-'.$i.'-adult-total']];
								$room_price_old += $room_price[$i];
								$he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
								$early_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$early_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
								$late_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$late_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
								//双人一房折扣团
								if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='2' ){
									$double_room_pre = double_room_preferences($products_id,$tmp_dp_date);
									if((int)$double_room_pre){
										$room_price[$i] = 2*($a_price[2]-$double_room_pre);
									}
								}else{

									//买二送1价 无小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
										if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='3' ){
											if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
												$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);
											}
										}
										//价格4人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
										if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='4' ){
											if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
												$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);
											}
											if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
												//买二送二
												$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));
											}
										}

									}
								}
								$room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$a_cost[$_POST['room-'.$i.'-adult-total']];
								$he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
								$early_he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$early_hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
								$late_he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total']*$late_hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total']];
							}
							elseif($_POST['room-'.$i.'-child-total'] != '0')
							{
								if($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '1')
								{
									$room_price[$i] = 2*$a_price[2];
									$room_price_old += $room_price[$i];

									$he_room_price[$i] = 2*$hotel_extension_price[2];
									$early_he_room_price[$i] = 2*$early_hotel_extension_price[2];
									$late_he_room_price[$i] = 2*$late_hotel_extension_price[2];

									$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
									$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i小孩平均价

									$room_price_cost[$i] = 2*$a_cost[2];

									$he_room_price_cost[$i] = 2*$hotel_extension_price_cost[2];
									$early_he_room_price_cost[$i] = 2*$early_hotel_extension_price_cost[2];
									$late_he_room_price_cost[$i] = 2*$late_hotel_extension_price_cost[2];

									//双人一房折扣团 大人小孩同一价
									$double_room_pre = double_room_preferences($products_id,$tmp_dp_date);
									if((int)$double_room_pre){
										$room_price[$i] = 2*($a_price[2]-$double_room_pre);
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $room_price[$i]/2;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $room_price[$i]/2;//房间$i小孩平均价
									}

									/*
									$room_price_option1[$i] = $a_price[1] + $e;
									$room_price_option2[$i] = 2*$a_price[2];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);

									$room_price_option1_cost[$i] = $a_cost[1] + $e_cost;
									$room_price_option2_cost[$i] = 2*$a_cost[2];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
									*/

								}
								elseif($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '2')
								{
									$room_price_option1[$i] = (2*$a_price[2]) + $e;
									$room_price_option2[$i] = 3*$a_price[3];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
									$room_price_old += $room_price[$i];

									//hotel-extension  {
									$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
									$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
									$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);

									$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0];
									$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3];
									$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);

									$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + $late_hotel_extension_price[0];
									$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3];
									$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);
									//}

									if($room_price_option1[$i] <= $room_price_option2[$i] ){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = ($a_price[2]+$e)/2;//房间$i小孩平均价
									}else{
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i小孩平均价
									}

									//买二送1价 1大人 2小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
											$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);
										}

										$tmp_var = number_format(($room_price[$i]/3),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价
									}

									$room_price_option1_cost[$i] = (2*$a_cost[2]) + $e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
									//hotel-extension {
									$room_price_option1_cost[$i] = (2*$a_cost[2]) + $e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);

									$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + $hotel_extension_price_cost[0];
									$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3];
									$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);

									$early_he_room_price_option1_cost[$i] = (2*$early_hotel_extension_price_cost[2]) + $early_hotel_extension_price_cost[0];
									$early_he_room_price_option2_cost[$i] = 3*$early_hotel_extension_price_cost[3];
									$early_he_room_price_cost[$i] = min($early_he_room_price_option1_cost[$i],$early_he_room_price_option2_cost[$i]);

									$late_he_room_price_option1_cost[$i] = (2*$late_hotel_extension_price_cost[2]) + $late_hotel_extension_price_cost[0];
									$late_he_room_price_option2_cost[$i] = 3*$late_hotel_extension_price_cost[3];
									$late_he_room_price_cost[$i] = min($late_he_room_price_option1_cost[$i],$late_he_room_price_option2_cost[$i]);
									//}
								}
								elseif($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '3')
								{
									$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
									$room_price_option2[$i] = 3*$a_price[3]+$e;
									$room_price_option3[$i] = 4*$a_price[4];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
									$room_price_old += $room_price[$i];
									//hotel-extension {
									$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0];
									$he_room_price_option2[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
									$he_room_price_option3[$i] = 4*$hotel_extension_price[4];
									$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i],$he_room_price_option3[$i]);

									$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + 2*$early_hotel_extension_price[0];
									$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0];
									$early_he_room_price_option3[$i] = 4*$early_hotel_extension_price[4];
									$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i],$early_he_room_price_option3[$i]);

									$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0];
									$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
									$late_he_room_price_option3[$i] = 4*$late_hotel_extension_price[4];
									$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i],$late_he_room_price_option3[$i]);
									//}
									if($room_price_option1[$i] == $room_price[$i]){
										$tmp_var = number_format( (($a_price[2]+2*$e)/3),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价
									}
									if($room_price_option2[$i] == $room_price[$i]){
										$tmp_var = number_format( ((2*$a_price[3]+$e)/3),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价
									}
									if($room_price_option3[$i] == $room_price[$i]){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i小孩平均价
									}

									//买二送1价 1大人 3小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
											$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);
										}
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
											//买二送二
											$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));
										}
										$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价

									}

									$room_price_option1_cost[$i] = (2*$a_cost[2]) + 2*$e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3]+$e_cost;
									$room_price_option3_cost[$i] = 4*$a_cost[4];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i],$room_price_option3_cost[$i]);

									//hotel-extension {
									$room_price_option1_cost[$i] = (2*$a_cost[2]) + 2*$e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3]+$e_cost;
									$room_price_option3_cost[$i] = 4*$a_cost[4];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i],$room_price_option3_cost[$i]);

									$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0];
									$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
									$he_room_price_option3_cost[$i] = 4*$hotel_extension_price_cost[4];
									$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i],$he_room_price_option3_cost[$i]);

									$early_he_room_price_option1_cost[$i] = (2*$early_hotel_extension_price_cost[2]) + 2*$early_hotel_extension_price_cost[0];
									$early_he_room_price_option2_cost[$i] = 3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0];
									$early_he_room_price_option3_cost[$i] = 4*$early_hotel_extension_price_cost[4];
									$early_he_room_price_cost[$i] = min($early_he_room_price_option1_cost[$i],$early_he_room_price_option2_cost[$i],$early_he_room_price_option3_cost[$i]);

									$late_he_room_price_option1_cost[$i] = (2*$late_hotel_extension_price_cost[2]) + 2*$late_hotel_extension_price_cost[0];
									$late_he_room_price_option2_cost[$i] = 3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0];
									$late_he_room_price_option3_cost[$i] = 4*$late_hotel_extension_price_cost[4];
									$late_he_room_price_cost[$i] = min($late_he_room_price_option1_cost[$i],$late_he_room_price_option2_cost[$i],$late_he_room_price_option3_cost[$i]);
									//}
								}
								elseif($_POST['room-'.$i.'-adult-total'] == '2' && $_POST['room-'.$i.'-child-total'] == '1')
								{
									$room_price_option1[$i] = (2*$a_price[2]) + $e;
									$room_price_option2[$i] = 3*$a_price[3];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
									$room_price_old += $room_price[$i];
									//hotel-extension
									$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
									$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
									$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);

									$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0];
									$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3];
									$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);

									$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + $hotel_extension_price[0];
									$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3];
									$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);
									//}

									if($room_price_option1[$i] <= $room_price_option2[$i]){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e;//房间$i小孩平均价
									}else{
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i小孩平均价
									}

									//买二送1价 2大人 1小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
											$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);

										}

										$tmp_var = number_format( ($room_price[$i]/3),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价

									}

									$room_price_option1_cost[$i] = (2*$a_cost[2]) + $e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
									//hotel-extension {
									$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + $hotel_extension_price_cost[0];
									$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3];
									$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);

									$early_he_room_price_option1_cost[$i] = (2*$early_hotel_extension_price_cost[2]) + $early_hotel_extension_price_cost[0];
									$early_he_room_price_option2_cost[$i] = 3*$early_hotel_extension_price_cost[3];
									$early_he_room_price_cost[$i] = min($early_he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);

									$late_he_room_price_option1_cost[$i] = (2*$late_hotel_extension_price_cost[2]) + $late_hotel_extension_price_cost[0];
									$late_he_room_price_option2_cost[$i] = 3*$late_hotel_extension_price_cost[3];
									$late_he_room_price_cost[$i] = min($late_he_room_price_option1_cost[$i],$late_he_room_price_option2_cost[$i]);
									//}
								}
								elseif($_POST['room-'.$i.'-adult-total'] == '2' && $_POST['room-'.$i.'-child-total'] == '2')
								{
									$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
									$room_price_option2[$i] = 3*$a_price[3]+$e;
									$room_price_option3[$i] = 4*$a_price[4];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
									$room_price_old += $room_price[$i];
									//hotel-extension{
									$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0];
									$he_room_price_option2[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
									$he_room_price_option3[$i] = 4*$hotel_extension_price[4];
									$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i],$he_room_price_option3[$i]);

									$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + 2*$early_hotel_extension_price[0];
									$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0];
									$early_he_room_price_option3[$i] = 4*$early_hotel_extension_price[4];
									$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i],$early_he_room_price_option3[$i]);

									$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0];
									$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
									$late_he_room_price_option3[$i] = 4*$late_hotel_extension_price[4];
									$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i],$late_he_room_price_option3[$i]);
									//}

									if($room_price_option1[$i] == $room_price[$i]){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e;//房间$i小孩平均价
									}
									if($room_price_option2[$i] == $room_price[$i]){
										$tmp_var = number_format( ($a_price[3]+$e)/2,2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = ($a_price[3]+$e)/2;//房间$i小孩平均价
									}
									if($room_price_option3[$i] == $room_price[$i]){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i小孩平均价
									}

									//买二送1价 2大人 2小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
											$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);
										}
										//买二送二
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
											$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));
										}

										$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价
									}

									$room_price_option1_cost[$i] = (2*$a_cost[2]) + 2*$e_cost;
									$room_price_option2_cost[$i] = 3*$a_cost[3]+$e_cost;
									$room_price_option3_cost[$i] = 4*$a_cost[4];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i],$room_price_option3_cost[$i]);
									//hotel-extension {
									$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0];
									$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
									$he_room_price_option3_cost[$i] = 4*$hotel_extension_price_cost[4];
									$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i],$he_room_price_option3_cost[$i]);

									$early_he_room_price_option1_cost[$i] = (2*$early_hotel_extension_price_cost[2]) + 2*$early_hotel_extension_price_cost[0];
									$early_he_room_price_option2_cost[$i] = 3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0];
									$early_he_room_price_option3_cost[$i] = 4*$early_hotel_extension_price_cost[4];
									$early_he_room_price_cost[$i] = min($early_he_room_price_option1_cost[$i],$early_he_room_price_option2_cost[$i],$early_he_room_price_option3_cost[$i]);

									$late_he_room_price_option1_cost[$i] = (2*$late_hotel_extension_price_cost[2]) + 2*$late_hotel_extension_price_cost[0];
									$late_he_room_price_option2_cost[$i] = 3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0];
									$late_he_room_price_option3_cost[$i] = 4*$late_hotel_extension_price_cost[4];
									$late_he_room_price_cost[$i] = min($late_he_room_price_option1_cost[$i],$late_he_room_price_option2_cost[$i],$late_he_room_price_option3_cost[$i]);
									//}

								}
								elseif($_POST['room-'.$i.'-adult-total'] == '3' && $_POST['room-'.$i.'-child-total'] == '1')
								{
									$room_price_option1[$i] = 3*$a_price[3]+$e;
									$room_price_option2[$i] = 4*$a_price[4];
									$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
									$room_price_old += $room_price[$i];
									//hotel-extension{
									$he_room_price_option1[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
									$he_room_price_option2[$i] = 4*$hotel_extension_price[4];
									$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);

									$early_he_room_price_option1[$i] = 3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0];
									$early_he_room_price_option2[$i] = 4*$early_hotel_extension_price[4];
									$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);

									$late_he_room_price_option1[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
									$late_he_room_price_option2[$i] = 4*$late_hotel_extension_price[4];
									$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);
									//}

									if($room_price_option1[$i] <= $room_price_option2[$i]){
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e;//房间$i小孩平均价
									}else{
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i小孩平均价
									}

									//买二送1价 3大人 1小孩
									if( (int)$is_buy_two_get_one){
										//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
											$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);
										}
										//买二送二
										if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
											$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));
										}
										$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
										$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i大人平均价
										$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $tmp_var;//房间$i小孩平均价

									}

									$room_price_option1_cost[$i] = 3*$a_cost[3]+$e_cost;
									$room_price_option2_cost[$i] = 4*$a_cost[4];
									$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
									//hotel-extension {
									$he_room_price_option1_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
									$he_room_price_option2_cost[$i] = 4*$hotel_extension_price_cost[4];
									$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);

									$early_he_room_price_option1_cost[$i] = 3*$early_hotel_extension_price_cost[3]+$early_hotel_extension_price_cost[0];
									$early_he_room_price_option2_cost[$i] = 4*$early_hotel_extension_price_cost[4];
									$early_he_room_price_cost[$i] = min($early_he_room_price_option1_cost[$i],$early_he_room_price_option2_cost[$i]);

									$late_he_room_price_option1_cost[$i] = 3*$late_hotel_extension_price_cost[3]+$late_hotel_extension_price_cost[0];
									$late_he_room_price_option2_cost[$i] = 4*$late_hotel_extension_price_cost[4];
									$late_he_room_price_cost[$i] = min($late_he_room_price_option1_cost[$i],$late_he_room_price_option2_cost[$i]);
									//}

								}

							}
							/****************total price for room no 1 *****************/
							$roomno = $i+1;
							$total_info_room .= "<br>".tep_get_total_of_adult_in_room($roomno)." ".$_POST['room-'.$i.'-adult-total'];
							$early_he_total_info_room .=  "<br>".tep_get_total_of_adult_in_room($roomno)." ".$_POST['room-'.$i.'-adult-total'];
							$late_he_total_info_room .=  "<br>".tep_get_total_of_adult_in_room($roomno)." ".$_POST['room-'.$i.'-adult-total'];
							if($_POST['room-'.$i.'-child-total']!='0'){
								$total_info_room .= "<br>".tep_get_total_of_children_in_room($roomno)." ".$_POST['room-'.$i.'-child-total'];
								$early_he_total_info_room .=  "<br>".tep_get_total_of_children_in_room($roomno)." ".$_POST['room-'.$i.'-child-total'];
								$late_he_total_info_room .=  "<br>".tep_get_total_of_children_in_room($roomno)." ".$_POST['room-'.$i.'-child-total'];
							}
							if($totalinroom[$i]==2 && tep_not_null($_POST['room-'.$i.'-bed'])){	//如有床型信息才加入床型
								$total_info_room .= "<br>".tep_get_bed_of_room($roomno)." ".tep_get_bed_name($_POST['room-'.$i.'-bed']);
								$early_he_total_info_room .= "<br>".tep_get_bed_of_room($roomno)." ".tep_get_bed_name($_POST['room-'.$i.'-bed']);
								$late_he_total_info_room .= "<br>".tep_get_bed_of_room($roomno)." ".tep_get_bed_name($_POST['room-'.$i.'-bed']);
							}

							if($room_price[$i]>0){	//价格不为0才显示共计信息
								$total_info_room .= "<br>".tep_get_total_of_room($roomno)." ".$currencies->format($room_price[$i]);
							}

							if($he_room_price[$i] > 0){
								if($early_he_room_price[$i] > 0){
									$early_he_total_info_room .= "<br>".tep_get_total_of_room($roomno)." ".$currencies->format($early_he_room_price[$i]);
								}
								if($late_he_room_price[$i] > 0){
									$late_he_total_info_room .= "<br>".tep_get_total_of_room($roomno)." ".$currencies->format($late_he_room_price[$i]);
								}
							}
							$total_no_guest_tour = $total_no_guest_tour+$_POST['room-'.$i.'-adult-total']+$_POST['room-'.$i.'-child-total'];
							$total_room_adult_child_info .= $_POST['room-'.$i.'-adult-total'].'!!'.$_POST['room-'.$i.'-child-total'].'###';
						}
					}
					/****************************EOC: this is validation for the first room ********************************/
					/*******************************************************************************************************/
					/*******************************************************************************************************/
				}

				//howard added if total_no_guest_tour < min_num_guest display error
				$error_min_guest = '';
				if($errormessageperson == ''){
					$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.(int)$HTTP_POST_VARS['products_id'].'" limit 1');
					$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
					if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
						$error_min_guest = max(1,(int)$check_min_guest_row['min_num_guest']);
						tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&error_min_guest='.$check_min_guest_row['min_num_guest'].''));
						//exit();
					}
				}
				//howard added if total_no_guest_tour < min_num_guest display error end

				if($errormessageperson != '' || $error_min_guest != '')
				{
					/*echo '<script>alert("'.$errormessageperson.'");</script>' ;
					echo '<script>history.go(-1);</script>';
					exit();*/

					//tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['products_id'].'&maxnumber='.$errormessageperson.''));
					//exit();
				}
				else
				{
					//amit added to remove qty start
					$cart_products_array =  $cart->get_products();
					for ($i=0, $n=sizeof($cart_products_array); $i<$n; $i++) {
						if($cart_products_array[$i]['is_hotel']!="1" && $cart_products_array[$i]['is_transfer']!="1" && tep_get_prid($HTTP_POST_VARS['products_id']) == tep_get_prid($cart_products_array[$i]['id']) ){
							$cart->remove($cart_products_array[$i]['id']);
						}elseif(strval($HTTP_POST_VARS['full_products_id']) == strval($cart_products_array[$i]['id'])){
							$cart->remove($cart_products_array[$i]['id']);
						}
					}
					//amit added to remove qty end


					//howard fixed 2010-08-10 start
					//$total_room_price = $room_price[0]+$room_price[1]+$room_price[2]+$room_price[3]+$room_price[4]+$room_price[5];
					//$total_room_price_cost = $room_price_cost[0]+$room_price_cost[1]+$room_price_cost[2]+$room_price_cost[3]+$room_price_cost[4]+$room_price_cost[5];
					$total_room_price = 0;
					for($n = 0; $n<count($room_price); $n++){$total_room_price += $room_price[$n];}
					$total_room_price_cost = 0;
					for($n = 0; $n<count($room_price_cost); $n++){	$total_room_price_cost += $room_price_cost[$n];}
					$total_hotel_extension_price = 0;
					for($n = 0; $n<count($room_price_cost); $n++){$total_hotel_extension_price += $he_room_price[$n];}
					$total_hotel_extension_price_cost = 0;
					for($n = 0; $n<count($he_room_price_cost); $n++){$total_hotel_extension_price_cost += $he_room_price_cost[$n];}
					$total_early_hotel_extension_price = 0;
					for($n = 0; $n<count($early_he_room_price); $n++){$total_early_hotel_extension_price += $early_he_room_price[$n];}
					$total_early_hotel_extension_price_cost = 0;
					for($n = 0; $n<count($early_he_room_price_cost); $n++){$total_early_hotel_extension_price_cost += $early_he_room_price_cost[$n];}
					$total_late_hotel_extension_price = 0;
					for($n = 0; $n<count($late_he_room_price); $n++){$total_late_hotel_extension_price += $late_he_room_price[$n];}
					$total_late_hotel_extension_price_cost = 0;
					for($n = 0; $n<count($late_he_room_price_cost); $n++){$total_late_hotel_extension_price_cost += $late_he_room_price_cost[$n];}
					//howard fixed 2010-08-10 end

					//amit added to add extra 3% if agency is L&L Travel start
					$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['products_id']);
					if($pro_agency_info_array['final_transaction_fee'] > 0){
						$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);
						$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT,$pro_agency_info_array['final_transaction_fee']);
					}
					//amit added to add extra 3% if agency is L&L Travel end
					//transfer-service {
					$transfer_price =0;$transfer_cost = 0 ;
					if(tep_not_null($HTTP_POST_VARS['products_id']) && tep_check_product_is_transfer($HTTP_POST_VARS['products_id'])){
						$transfer_price_info = tep_transfer_calculation_price(intval($HTTP_POST_VARS['products_id']) ,$HTTP_POST_VARS,true);
						$transfer_price = $transfer_price_info['price'] ;
						$transfer_cost = $transfer_price_info['cost'] ;
					}
					//}
					//check $total_room_price start
					
					//属性价格统计;注意位置不能乱动
					$attribute_total = 0;
					//如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
					$hasSelDeck = false;
					$isCruises = false;
					foreach((array)$_POST['id'] as $option_id => $option_value_id){
							$attributePrice = attributes_price_display((int)$HTTP_POST_VARS['products_id'],$option_id,$option_value_id);
							$attribute_total += $attributePrice;
							if(in_array($option_id,(array)$cruisesOptionIds)){
								$isCruises = true;
								if($attributePrice > 0){
									$hasSelDeck = true;
								}
							}
					}
					
					//邮轮团选项检查{
					if( $isCruises==true && $hasSelDeck == false ){
						$messageStack->add_session('global', db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！'), 'error');
						tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&total_price_error='.$total_room_price));
						exit();
					}
					//}
					
					if(($total_room_price<1 &&$transfer_price<1 &&$total_early_hotel_extension_price<1&& $total_late_hotel_extension_price<1) && ($attribute_total < 1|| $isCruises == false) ){
						tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&total_price_error='.$total_room_price));
						exit();
					}
					//check $total_room_price end

					$hotel_extension_info .= $HTTP_POST_VARS['early_hotel_checkin_date'].'|=|'.$HTTP_POST_VARS['early_hotel_checkout_date'].'|=|'.$HTTP_POST_VARS['early_arrival_hotels'].'|=|'.$HTTP_POST_VARS['early_hotel_rooms'].'|=|'.$total_early_hotel_extension_price.'|=|'.$total_early_hotel_extension_price_cost.'|=|'.$HTTP_POST_VARS['late_hotel_checkin_date'].'|=|'.$HTTP_POST_VARS['late_hotel_checkout_date'].'|=|'.$HTTP_POST_VARS['staying_late_hotels'].'|=|'.$HTTP_POST_VARS['late_hotel_rooms'].'|=|'.$total_late_hotel_extension_price.'|=|'.$total_late_hotel_extension_price_cost;

					if (tep_session_is_registered('customer_id')) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");

					if(tep_check_product_is_hotel($HTTP_POST_VARS['products_id'])==1){
						if(isset($HTTP_POST_VARS['h_e_id'])){
							foreach($_POST['h_e_id'] as $attr_key=>$attr_val){
								$_POST['id'][$attr_key] = $attr_val;
							}
							$cart->add_cart($HTTP_POST_VARS['products_id'], 1, $_POST['id'],'',$early_hotel_checkin_date,$HTTP_POST_VARS['departurelocation'],'','','','','',$total_early_hotel_extension_price,$early_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_early_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info, $extra_values);							
						}else{
							foreach($_POST['h_l_id'] as $attr_key=>$attr_val){
								$_POST['id'][$attr_key] = $attr_val;
							}
							$cart->add_cart($HTTP_POST_VARS['products_id'], 1, $_POST['id'],'',$late_hotel_checkin_date,$HTTP_POST_VARS['departurelocation'],'','','','','',$total_late_hotel_extension_price,$late_he_total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_late_hotel_extension_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'],$hotel_extension_info, $extra_values);
						}
					}elseif(tep_check_product_is_transfer($HTTP_POST_VARS['products_id'],$product_transfer_info)==1){
						//接送服务单独预订
						$transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
						$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info, $extra_values);
					}else{
						$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'',$HTTP_POST_VARS['availabletourdate'],$HTTP_POST_VARS['departurelocation'],'','','','','',$total_room_price,$total_info_room,$total_no_guest_tour,$total_room_adult_child_info,$date_price_cost, $total_room_price_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info, $extra_values);
					}

					$cart_products_array =  $cart->get_products();
					//amit added to get string start
					for ($i=0, $n=sizeof($cart_products_array); $i<$n; $i++) {
						if($HTTP_POST_VARS['products_id'] == (int)$cart_products_array[$i]['id']){

							$HTTP_GET_VARS['products_id'] = $cart_products_array[$i]['id'];
							/////part1 start
							if (isset($cart_products_array[$i]['attributes']) && is_array($cart_products_array[$i]['attributes'])) {
								while (list($option, $value) = each($cart_products_array[$i]['attributes'])) {
									//  echo tep_draw_hidden_field('id[' . $cart_products_array[$i]['id'] . '][' . $option . ']', $value);
									$cart_products_array[$i][$option]['options_values_price'] = $cart->attributes_price_display($cart_products_array[$i]['id'],$option,$value);
									if($cart_products_array[$i][$option]['options_values_price']!=0 || ($cart_products_array[$i][$option]['options_values_price']==0 && !in_array($option, (array)$cruisesOptionIds))){	//不显示产品属性价格为0的邮轮团选项

										$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price
																			  from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
																			  where pa.products_id = '" . $cart_products_array[$i]['id'] . "'
																			   and pa.options_id = '" . $option . "'
																			   and pa.options_id = popt.products_options_id
																			   and pa.options_values_id = '" . $value . "'
																			   and pa.options_values_id = poval.products_options_values_id
																			   and popt.language_id = '" . $languages_id . "'
																			   and poval.language_id = '" . $languages_id . "'");
										$attributes_values = tep_db_fetch_array($attributes);

										$cart_products_array[$i][$option]['products_options_name'] = db_to_html($attributes_values['products_options_name']);
										$cart_products_array[$i][$option]['options_values_id'] = $value;
										$cart_products_array[$i][$option]['products_options_values_name'] = db_to_html($attributes_values['products_options_values_name']);
										/*
										$cart_products_array[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
										$cart_products_array[$i][$option]['single_values_price'] = $attributes_values['single_values_price'];
										$cart_products_array[$i][$option]['double_values_price'] = $attributes_values['double_values_price'];
										$cart_products_array[$i][$option]['triple_values_price'] = $attributes_values['triple_values_price'];
										$cart_products_array[$i][$option]['quadruple_values_price'] = $attributes_values['quadruple_values_price'];
										*/
										//amit change it take price form cart
										//$cart_products_array[$i][$option]['options_values_price'] = $cart->attributes_price($cart_products_array[$i]['id']);
										$cart_products_array[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
									}
								}
							}
							//part1 end
							//part2 start

							//howard added travel companion
							$jiebantongyong="";
							if($cart_products_array[$i]['roomattributes'][5]=='1'){
								$jiebantongyong = '<br><span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
							}

							//howard added single pair up
							$agree_single_occupancy_pair_up = '0';
							if($cart_products_array[$i]['roomattributes'][6]=='1'){
								$agree_single_occupancy_pair_up = '1';
							}

							//hotel-extension for hotel extension early/late
							$hotel_ext_addon_text = "";
							
							if($cart_products_array[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='1'){
								$hotel_ext_addon_text = '<br><span><b>'.db_to_html("(参团前加订酒店)").'</b></span>';
							}else if($cart_products_array[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
								$hotel_ext_addon_text = '<br><span><b>'.db_to_html("(参团后加订酒店)").'</b></span>';
							}//}
							//$products_obj_html = '<a class="cu dazi" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$cart_products_array[$i]['id']) . '"><b>' . db_to_html($cart_products_array[$i]['name']) . '</b></a>'.$jiebantongyong;
							$products_obj_html = '<table  border="0" cellspacing="2" cellpadding="2">' ;
							if(tep_not_null($cart_products_array[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID])){
								$extra_query_string = '?hotel_attribute='.$cart_products_array[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID];
							}else{
								$extra_query_string = '';
							}
							$products_obj_html.='<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$cart_products_array[$i]['id']).$extra_query_string.'" class="product_name_orange"><b>' . db_to_html($cart_products_array[$i]['name']) . '</b></a>'.$jiebantongyong . $hotel_ext_addon_text . '<br/>' . db_to_html('旅游团号：' . $cart_products_array[$i]['model']);

							if (STOCK_CHECK == 'true') {
								$stock_check = tep_check_stock($cart_products_array[$i]['id'], $cart_products_array[$i]['quantity']);
								if (tep_not_null($stock_check)) {
									$any_out_of_stock = 1;
									$products_obj_html .= $stock_check;
								}
							}

							$departuer_date_tags = tep_get_date_disp($cart_products_array[$i]['dateattributes'][0]);
							if($cart_products_array[$i]['no_sel_date_for_group_buy']=="1"){
								$departuer_date_tags = date('m/d/Y',strtotime($cart_products_array[$i]['dateattributes'][0])+86400).TEXT_BEFORE;
							}
							//hotel-extension {

							//
							//$products_obj_html .= '<br><span>'.TEXT_SHOPPING_CART_DEPARTURE_DATE.' <b style="color:#000000; font-size:18px;">'.$departuer_date_tags.tep_draw_hidden_field('finaldateone[]', $cart_products_array[$i]['dateattributes'][0], 'size="4"') . '</b></span>';

							//hotel-extension {
							if($cart_products_array[$i]['is_hotel']==1){
								$txt_dept_date = db_to_html('入住日期:');
							}else{
								$txt_dept_date = TEXT_SHOPPING_CART_DEPARTURE_DATE;
							}
							if($is_start_date_required==true){
								$products_obj_html .= '<br /><span>'.$txt_dept_date.'<b style="color:#ff6600; font-size:18px;"> ' . tep_get_date_disp($cart_products_array[$i]['dateattributes'][0]).tep_draw_hidden_field('finaldateone[]', $cart_products_array[$i]['dateattributes'][0], 'size="4"').'</b></span>';
								if($cart_products_array[$i]['dateattributes'][4] != '' && $cart_products_array[$i]['dateattributes'][4] != 0){
									$products_obj_html .=' '. $cart_products_array[$i]['dateattributes'][3].' '.tep_draw_hidden_field('prifixone[]', $cart_products_array[$i]['dateattributes'][3], 'size="4"') .' '. (($cart_products_array[$i]['dateattributes'][4]!='') ? '$' : '') .$cart_products_array[$i]['dateattributes'][4].tep_draw_hidden_field('date_priceone[]', $cart_products_array[$i]['dateattributes'][4], 'size="4"');
								}
								if($cart_products_array[$i]['is_hotel']==1){
									$hotel_extension_info = explode('|=|', $cart_products_array[$i]['hotel_extension_info']);									
									if($cart_products_array[$i]['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
										$hotel_checkout_date = tep_get_date_db($hotel_extension_info[7]);
									}else{
										$hotel_checkout_date = tep_get_date_db($hotel_extension_info[1]);
									}								
									$products_obj_html .= '<span style="margin-left:2em">'.db_to_html('离店日期:').'<b style="color:#ff6600; font-size:18px;"> ' . tep_get_date_disp($hotel_checkout_date) . '</b></span>';
									if(check_date($hotel_checkout_date) && check_date($cart_products_array[$i]['dateattributes'][0])){
										$products_obj_html .='<br><span>'.db_to_html('总共：'.date1SubDate2($hotel_checkout_date,$cart_products_array[$i]['dateattributes'][0]).'晚').'</span>';
									}
								}
							}//}


							if($cart_products_array[$i]['dateattributes'][1] != ''){
								// show title strart
								$text_shopping_cart_pickp_location = TEXT_SHOPPING_CART_PICKP_LOCATION;
							}
							//if((int)is_show((int)$cart_products_array[$i]['id'])){ // 原来的判断 是否是秀票
							if ($cart_products_array[$i]['products_type'] == 7){
								$text_shopping_cart_pickp_location = db_to_html('演出时间/地点：');//PERFORMANCE_TIME;
							}
							// show title end
							//hotel-extension {
							if($cart_products_array[$i]['dateattributes'][1] != ''){
								if($cart_products_array[$i]['is_hotel']==3){
									list($pick_uplocation, $return_location) = explode('|=|',$cart_products_array[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $cart_products_array[$i]['dateattributes'][1], 'size="4"')  . ' ' . $cart_products_array[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $cart_products_array[$i]['dateattributes'][2], 'size="4"'));
									list($return_final_location,$return_date_two) = explode('=|=',$return_location);
									$products_name .=  '<br><span>'.TEXT_RETURN_DATE.'  '.date('m/d/Y',strtotime(substr($return_date_two,0,10))).'</span><br/><span>'.$txt_pickup_location.' ' . $products[$i]['dateattributes'][1].' '.$pick_uplocation .'</span><br/><span>'.TEXT_RETURN_LOCATION.' '.str_replace('||',' ',$return_final_location).tep_draw_hidden_field('depart_timeone[]', $products[$i]['dateattributes'][1], 'size="4"').'</span>';
								}else{
									$products_name .= '<br/><span>'.$txt_pickup_location.'  ' . $cart_products_array[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $cart_products_array[$i]['dateattributes'][1], 'size="4"')  . ' ' . $cart_products_array[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $cart_products_array[$i]['dateattributes'][2], 'size="4"') . '</span>';
								}
							}
							$products_obj_html .=$products_name ;
							//$products_obj_html .=  ' <br><span>'.$text_shopping_cart_pickp_location . $cart_products_array[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $cart_products_array[$i]['dateattributes'][1], 'size="4"')  . ' ' . $cart_products_array[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $cart_products_array[$i]['dateattributes'][2], 'size="4"') . '</span>';
							//}

							if($cart_products_array[$i]['roomattributes'][1] != ''){
								//$roomInfoString = preg_replace('@(<[^<]*br[^<]*>[^<]+0.00)@','',$cart_products_array[$i]['roomattributes'][1]);	//此值在shopping_cart.php中已经被转成了gb2312格式
								$roomInfoString = $cart->re_get_room_info_to_gb2312($cart_products_array[$i]['roomattributes'][3]);
								$roomInfoString = db_to_html($roomInfoString);
								$roomInfoString = format_out_roomattributes_1($roomInfoString, (int)$cart_products_array[$i]['roomattributes'][3]);
								$products_obj_html .=  ' <nobr><br><span>' . $roomInfoString.tep_draw_hidden_field('roominfo[]', $cart_products_array[$i]['roomattributes'][1], 'size="4"') . tep_draw_hidden_field('roomprice[]', $cart_products_array[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $cart_products_array[$i]['roomattributes'][2]) . '</span>';

							}

							//New Group Buy 新团购优惠 {
							if($cart_products_array[$i]['is_new_group_buy']>0 ){
								$products_price = tep_db_query("select p.products_single, p.products_single_pu, p.products_double, p.products_triple, p.products_quadr, p.products_kids from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$cart_products_array[$i]['id'] . "' ");
								$products_result = tep_db_fetch_array($products_price);
								$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$cart_products_array[$i]['id']);
								if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
									$products_result['products_single'] = tep_get_tour_price_in_usd($products_result['products_single'], $tour_agency_opr_currency);
									$products_result['products_single_pu'] = tep_get_tour_price_in_usd($products_result['products_single_pu'], $tour_agency_opr_currency);
									$products_result['products_double'] = tep_get_tour_price_in_usd($products_result['products_double'], $tour_agency_opr_currency);
									$products_result['products_triple'] = tep_get_tour_price_in_usd($products_result['products_triple'], $tour_agency_opr_currency);
									$products_result['products_quadr'] = tep_get_tour_price_in_usd($products_result['products_quadr'], $tour_agency_opr_currency);
									$products_result['products_kids'] = tep_get_tour_price_in_usd($products_result['products_kids'], $tour_agency_opr_currency);
								}

								$a_price[1] = $products_result['products_single']; //single or single pair up
								if((int)$cart_products_array[$i]['roomattributes'][6] && (int)$products_result['products_single_pu']){
									$a_price[1] = $products_result['products_single_pu'];
								}
								$a_price[2] = $products_result['products_double']; //double
								$a_price[3] = $products_result['products_triple']; //triple
								$a_price[4] = $products_result['products_quadr']; //quadr
								$e = $products_result['products_kids']; //child Kid

								$oldPrice = 0;
								$g_array = explode('###',$cart_products_array[$i]['roomattributes'][3]);
								if($g_array[0]>0){ // room
									for($jj=1, $nn = sizeof($g_array); $jj<$nn; $jj++){
										if(strlen($g_array[$jj])>2){
											$n_array = explode('!!', $g_array[$jj]); //$n_array[0]大人 $n_array[1]小孩
											if($n_array[1]==0){
												$oldPrice += $a_price[$n_array[0]] * $n_array[0];
											}else{
												if($n_array[0] == '1' && $n_array[1] == '1') {
													$t_price[0] = 2*$a_price[2];
													$t_price[1] = $a_price[1]+$e;
													$oldPrice += min($t_price[0],$t_price[1]);
												}else if($n_array[0] == '1' && $n_array[1] == '2'){
													$t_price[0] = (2*$a_price[2]) + $e;
													$t_price[1] = 3*$a_price[3];
													$oldPrice += min($t_price[0],$t_price[1]);
												}else if($n_array[0] == '1' && $n_array[1] == '3'){
													$t_price[0] = (2*$a_price[2]) + 2*$e;
													$t_price[1] = 3*$a_price[3]+$e;
													$t_price[2] = 4*$a_price[4];
													$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);
												}else if($n_array[0] == '2' && $n_array[1] == '1'){
													$t_price[0] = (2*$a_price[2]) + $e;
													$t_price[1] = 3*$a_price[3];
													$oldPrice += min($t_price[0],$t_price[1]);
												}else if($n_array[0] == '2' && $n_array[1] == '2'){
													$t_price[0] = (2*$a_price[2]) + 2*$e;
													$t_price[1] = 3*$a_price[3]+$e;
													$t_price[2] = 4*$a_price[4];
													$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);
												}else if($n_array[0] == '3' && $n_array[1] == '1'){
													$t_price[0] = 3*$a_price[3]+$e;
													$t_price[1] = 4*$a_price[4];
													$oldPrice += min($t_price[0],$t_price[1]);
												}
											}
										}
									}
									$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE;
								}else{ // no room
									$n_array = explode('!!', $g_array[1]); //$n_array[0]大人 $n_array[1]小孩
									$oldPrice += $n_array[0]*$a_price[1] + $n_array[1]*$e;
									$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE_NOT_ROOM;
								}
								$SaveNum = $oldPrice - $cart_products_array[$i]['roomattributes'][0];
								if($SaveNum>0){
									$products_obj_html .= '<br /><span style="color:#F7860F">'.$TITLE_NEW_GROUP_BUY_OLD_PRICE.$currencies->display_price($oldPrice,0,$cart_products_array[$i]['quantity']).' '.TITLE_NEW_GROUP_BUY.' -'.$currencies->display_price($SaveNum,0,$cart_products_array[$i]['quantity']).'</span>';
								}
							}
							//New Group Buy 新团购优惠 }

							if($cart_products_array[$i]['dateattributes'][4] != '' && $cart_products_array[$i]['dateattributes'][4] != 0)
							{
								$products_obj_html .= '<br><span> '.TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS. $cart_products_array[$i]['dateattributes'][3].tep_draw_hidden_field('prifixone[]', $cart_products_array[$i]['dateattributes'][3], 'size="4"') .' '. (($cart_products_array[$i]['dateattributes'][4]!='') ? '' : '') .$currencies->format($cart_products_array[$i]['dateattributes'][4],true,$currency).tep_draw_hidden_field('date_priceone[]', $cart_products_array[$i]['dateattributes'][4], 'size="4"') . '</span>';
							}


							if (isset($cart_products_array[$i]['attributes']) && is_array($cart_products_array[$i]['attributes'])) {
								reset($cart_products_array[$i]['attributes']);
								while (list($option, $value) = each($cart_products_array[$i]['attributes'])) {
									if($cart_products_array[$i][$option]['options_values_price'] != 0) {
										$products_obj_html .=  '<br><span>' . $cart_products_array[$i][$option]['products_options_name'] . ': <b>' . $cart_products_array[$i][$option]['products_options_values_name'] . '</b>:</span><span> ' . $cart_products_array[$i][$option]['price_prefix'] . ' ' . $currencies->display_price($cart_products_array[$i][$option]['options_values_price'], tep_get_tax_rate($cart_products_array[$i]['tax_class_id']), 1) . '</span>';
									}else{
										if(trim($cart_products_array[$i][$option]['products_options_name'] )!=''){
											$products_obj_html .= '<br><span>' . $cart_products_array[$i][$option]['products_options_name'] . ': ' . $cart_products_array[$i][$option]['products_options_values_name'] . '</span>';
										}
									}

								}
							}
							//part2 end

							//团购优惠
							if($cart_products_array[$i]['group_buy_discount']>0){
								$products_obj_html .= '<br /><span>'.($cart_products_array[$i]['is_diy_tours_book']==2 ? TXT_FEATURED_DEAL_DISCOUNT : TITLE_GROUP_BUY).'</span><br /><span>-'.$currencies->display_price($cart_products_array[$i]['group_buy_discount'],0,$cart_products_array[$i]['quantity']).'</span>';
							}

							$cart_tour_price_div = '<b>'.$currencies->display_price($cart_products_array[$i]['final_price'], tep_get_tax_rate($cart_products_array[$i]['tax_class_id']), $cart_products_array[$i]['quantity']).'</b>';
						}
					}

					//amit added to get sting end

					//write change div and update div code start
								?>
								<script language="javascript">
								var txtPickup = "<?php echo addslashes($products_obj_html);?>";
								parent.document.getElementById("cart_product_data_<?php echo $iframe_cart_edit_update;?>").innerHTML=txtPickup;
								//parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['full_products_id'];?>").innerHTML=txtPickup;
								parent.document.getElementById("cart_product_data_price_<?php echo $iframe_cart_edit_update;?>").innerHTML="<?php echo $cart_tour_price_div;?>";
								<?php
								if(isset($total_pur_suc_nos_of_cnt) && $redeempointforceclose == 'true')
								{
									//echo '-->>'.$customer_shopping_points;
									$sub_total = $cart->show_total();
									//$order->info['total'] = $sub_total;
									if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
										if (($customer_shopping_points = tep_get_shopping_points($_SESSION['customer_id'])) && $customer_shopping_points > 0 && $total_pur_suc_nos_of_cnt>0) {
											if (get_redemption_rules($order) && (get_points_rules_discounted($order) || get_cart_mixed($order))) {
												if ($customer_shopping_points >= POINTS_LIMIT_VALUE) {
													if ((POINTS_MIN_AMOUNT == '') || ($cart->show_total() >= POINTS_MIN_AMOUNT) ) {
														if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');

														$max_points_string = calculate_max_points_shopping_display($customer_shopping_points);
														$max_points1 = explode("-#-",$max_points_string);
														$max_points = $max_points1[0];
														$total_allowable_discount = $max_points1[1];

														if ($sub_total > tep_calc_shopping_pvalue($max_points)) {
															$note = '<br /><span>' . TEXT_REDEEM_SYSTEM_NOTE .'</span>';
														}

														$customer_shopping_points_spending = $max_points;

														// printf(TEXT_REDEEM_SYSTEM_SPENDING, number_format($max_points,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue($max_points)));
														$od_amount_royal = tep_calc_shopping_pvalue($max_points);
														$sub_total = $sub_total - $od_amount_royal;

													}
												}
											}
										}
									}
									if(isset($od_amount_royal) && $od_amount_royal > 0){ ?>
									parent.document.getElementById("loyal_sub_product_total_div").innerHTML="<?php echo '-'.$currencies->format($od_amount_royal);?>";
									<?php } ?>

									parent.document.getElementById("sub_product_total_div").innerHTML="<?php echo $currencies->format($sub_total);?>";
									<?php
								}
								else
								{

									?>
									parent.document.getElementById("sub_product_total_div").innerHTML="<?php echo $currencies->format($cart->show_total());?>";
									<?php
								}
								?>

								//parent.document.getElementById("edit_cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").style.display == "none";
								//alert(parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").innerHTML);
								//parent.HideContent("edit_cart_product_data_<?php echo $HTTP_POST_VARS['full_products_id'];?>");
								//parent.ShowContent("cart_product_data_<?php echo $HTTP_POST_VARS['full_products_id'];?>");
								parent.HideContent("edit_cart_product_data_<?php echo $iframe_cart_edit_update;?>");
								parent.ShowContent("cart_product_data_<?php echo $iframe_cart_edit_update;?>");
								//reloadParent();
								window.location="ajax_edit_tour.php?products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&full_products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&product_number=<?php echo (int)$HTTP_GET_VARS['product_number'];?>";
								</script>
								<?php
								//write change div and update div code end
				}

			}else{  //if($_POST['numberOfRooms'])//if there is no rooms

				$totaladultticket = $_POST['room-0-adult-total'];
				if($_POST['room-0-child-total']!='')
				$totalchildticket = $_POST['room-0-child-total'];

				$total_no_guest_tour = $totaladultticket+$totalchildticket;
				$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($HTTP_POST_VARS['products_id']) + $total_no_guest_tour;
				
				$total_room_adult_child_info = "0###".$totaladultticket."!!".$totalchildticket;
				$bed_option_info = "0###";
				$total_adult_ticket_price = $totaladultticket*$a_price[1];
				$total_child_ticket_price = $totalchildticket*$e;

				$total_room_price = $total_adult_ticket_price+$total_child_ticket_price;

				$_SESSION['adult_average'][0][$HTTP_POST_VARS['products_id']] = $a_price[1];//大人平均价
				$_SESSION['child_average'][0][$HTTP_POST_VARS['products_id']] = $e;//小孩平均价

				//amit added for cost cal
				$total_adult_ticket_price_cost = $totaladultticket*$a_cost[1];
				$total_child_ticket_price_cost = $totalchildticket*$e_cost;
				$total_room_price_cost = $total_adult_ticket_price_cost+$total_child_ticket_price_cost;
				//amit added for cost cal

				$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_NO." : ".$totaladultticket;
				//if($_POST['room-'.$i.'-child-total']!='0')
                if ($totalchildticket > 0){
                    $total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_NO." : ".$totalchildticket;
                }
				
				if($total_room_price>0){	//价格不为0才显示共计信息
					$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." : ".$currencies->format($total_room_price);
				}



				//amit added to add extra 3% if agency is L&L Travel start
				$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['products_id']);

				if($pro_agency_info_array['final_transaction_fee'] > 0){
					$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);
					$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_PERSENT,$pro_agency_info_array['final_transaction_fee']);
				}
				//amit added to add extra 3% if agency is L&L Travel end

				//howard added if total_no_guest_tour < min_num_guest display error
				if($product_transfer_info ['is_transfer'] != '1'){
					$error_min_guest = '';
					$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.(int)$HTTP_POST_VARS['products_id'].'" limit 1');
					$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql); // min_num_guest = 1
					if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
						$error_min_guest = $check_min_guest_row['min_num_guest'];
						tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&error_min_guest='.$check_min_guest_row['min_num_guest'].''));
						exit();
					}
				}
				//howard added if total_no_guest_tour < min_num_guest display error end

				//amit added to remove qty start
				$cart_products_array =  $cart->get_products();

				for ($i=0, $n=sizeof($cart_products_array); $i<$n; $i++) {
					if($cart_products_array[$i]['is_hotel']!="1" && $cart_products_array[$i]['is_transfer']!="1" && tep_get_prid($HTTP_POST_VARS['products_id']) == tep_get_prid($cart_products_array[$i]['id']) ){
						$cart->remove($cart_products_array[$i]['id']);
					}elseif(strval($HTTP_POST_VARS['full_products_id']) == strval($cart_products_array[$i]['id'])){
						$cart->remove($cart_products_array[$i]['id']);
					}
				}
				//amit added to remove qty end
				//transfer-service {
				$transfer_price =0;$transfer_cost = 0 ;
				if(tep_not_null($HTTP_POST_VARS['products_id']) && tep_check_product_is_transfer($HTTP_POST_VARS['products_id'])){
					$transfer_price_info = tep_transfer_calculation_price(intval($HTTP_POST_VARS['products_id']) ,$HTTP_POST_VARS,true);
					$transfer_price = $transfer_price_info['price'] ;
					$transfer_cost = $transfer_price_info['cost'] ;
				}
				//}

				//属性价格统计;注意位置不能乱动
					$attribute_total = 0;
					//如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
					$hasSelDeck = false;
					$isCruises = false;
					foreach((array)$_POST['id'] as $option_id => $option_value_id){
							$attributePrice = attributes_price_display((int)$HTTP_POST_VARS['products_id'],$option_id,$option_value_id);
							$attribute_total += $attributePrice;
							if(in_array($option_id,(array)$cruisesOptionIds)){
								$isCruises = true;
								if($attributePrice > 0){
									$hasSelDeck = true;
								}
							}
					}
					
					//邮轮团选项检查{
					if( $isCruises==true && $hasSelDeck == false ){
						$messageStack->add_session('global', db_to_html('由于此产品是邮轮团，所以您需要选择一个客舱的甲板！'), 'error');
						tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&total_price_error='.$total_room_price));
						exit();
					}
					//}
					
				//check $total_room_price start
				if($total_room_price<1 && $transfer_price <1 && ($attribute_total < 1 || $isCruises==false) ){
					tep_redirect(tep_href_link('ajax_edit_tour.php', 'products_id=' . $HTTP_POST_VARS['full_products_id'].'&product_number='.(int)$_GET['product_number'].'&total_price_error='.$total_room_price));
					exit();
				}
				//check $total_room_price end

				if (tep_session_is_registered('customer_id')) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");
				if(tep_check_product_is_transfer($HTTP_POST_VARS['products_id'])==1){
					$transfer_info = tep_transfer_encode_info($HTTP_POST_VARS);
					$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'','','','','','','','',$transfer_price,'',0,'',$date_price_cost, $transfer_cost, $HTTP_POST_VARS['travel_comp'],$HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'], $hotel_extension_info,$transfer_info, $extra_values);
				}else {
					$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id'])) + 1, $HTTP_POST_VARS['id'], '', $HTTP_POST_VARS['availabletourdate'], $HTTP_POST_VARS['departurelocation'], '', '', '', '', '', $total_room_price, $total_info_room, $total_no_guest_tour, $total_room_adult_child_info, $date_price_cost, $total_room_price_cost, $HTTP_POST_VARS['travel_comp'], $HTTP_POST_VARS['agree_single_occupancy_pair_up'], $bed_option_info, $is_new_group_buy, $HTTP_POST_VARS['no_sel_date_for_group_buy'],'','', $extra_values);
				}
				//tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
				$cart_products_array =  $cart->get_products();
				//amit added to get string start
				for ($i=0, $n=sizeof($cart_products_array); $i<$n; $i++) {
					if($HTTP_POST_VARS['products_id'] == (int)$cart_products_array[$i]['id']){
						$HTTP_GET_VARS['products_id'] = $cart_products_array[$i]['id'];
						/////part1 start
						if (isset($cart_products_array[$i]['attributes']) && is_array($cart_products_array[$i]['attributes'])) {
							while (list($option, $value) = each($cart_products_array[$i]['attributes'])) {

								$cart_products_array[$i][$option]['options_values_price'] = $cart->attributes_price_display($cart_products_array[$i]['id'],$option,$value);
								if($cart_products_array[$i][$option]['options_values_price']!=0 || ($cart_products_array[$i][$option]['options_values_price']==0 && !in_array($option, (array)$cruisesOptionIds))){	//不显示产品属性价格为0的邮轮团选项
								echo tep_draw_hidden_field('id[' . $cart_products_array[$i]['id'] . '][' . $option . ']', $value);
								$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price
																			  from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
																			  where pa.products_id = '" . $cart_products_array[$i]['id'] . "'
																			   and pa.options_id = '" . $option . "'
																			   and pa.options_id = popt.products_options_id
																			   and pa.options_values_id = '" . $value . "'
																			   and pa.options_values_id = poval.products_options_values_id
																			   and popt.language_id = '" . $languages_id . "'
																			   and poval.language_id = '" . $languages_id . "'");
								$attributes_values = tep_db_fetch_array($attributes);

								$cart_products_array[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
								$cart_products_array[$i][$option]['options_values_id'] = $value;
								$cart_products_array[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];

								$cart_products_array[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
								}
							}
						}
						//part1 end
						//part2 start

						//howard added travel companion
						$jiebantongyong="";
						if($cart_products_array[$i]['roomattributes'][5]=='1'){
							$jiebantongyong = '<br><span style="color:#FF9900;font-weight: bold;">'.db_to_html('（结伴同游团）').'</span>';
						}
						//howard added single pair up
						$agree_single_occupancy_pair_up = '0';
						if($cart_products_array[$i]['roomattributes'][6]=='1'){
							$agree_single_occupancy_pair_up = '1';
						}

						$products_obj_html = '<a class="cu dazi" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$cart_products_array[$i]['id']) . '"><b>' . db_to_html($cart_products_array[$i]['name']) . '</b></a>'.$jiebantongyong . '<br/>' . db_to_html('旅游团号：' . $cart_products_array[$i]['model']);

						if (STOCK_CHECK == 'true') {
							$stock_check = tep_check_stock($cart_products_array[$i]['id'], $cart_products_array[$i]['quantity']);
							if (tep_not_null($stock_check)) {
								$any_out_of_stock = 1;
								$products_obj_html .= $stock_check;
							}
						}

						$departuer_date_tags = tep_get_date_disp($cart_products_array[$i]['dateattributes'][0]);
						if($cart_products_array[$i]['no_sel_date_for_group_buy']=="1"){
							$departuer_date_tags = date('m/d/Y',strtotime($cart_products_array[$i]['dateattributes'][0])+86400).TEXT_BEFORE;
						}

						if($cart_products_array[$i]['is_transfer'] == '1') {
							$transfer_info_arr = tep_transfer_decode_info($cart_products_array[$i]['transfer_info']);
							$products_obj_html .="<br>".db_to_html(tep_transfer_display_route($transfer_info_arr));
						}else{
							$products_obj_html .= '<br><span>' .TEXT_SHOPPING_CART_DEPARTURE_DATE. '<b style="color:#ff6600; font-size:18px;">'.$departuer_date_tags.'</b>'.tep_draw_hidden_field('finaldateone[]', $cart_products_array[$i]['dateattributes'][0], 'size="4"') . '</span>';
						}
						// show title strart
						$text_shopping_cart_pickp_location = TEXT_SHOPPING_CART_PICKP_LOCATION;
						//if((int)is_show((int)$cart_products_array[$i]['id'])){// 原来的判断 是否是秀票
						if ($cart_products_array[$i]['products_type'] == 7){
							$text_shopping_cart_pickp_location = db_to_html('演出时间/地点：');//PERFORMANCE_TIME;
						}
						// show title end

						if(tep_not_null($cart_products_array[$i]['dateattributes'][1]))$products_obj_html .=  ' <br><span>'.$text_shopping_cart_pickp_location . $cart_products_array[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $cart_products_array[$i]['dateattributes'][1], 'size="4"')  . ' ' . $cart_products_array[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $cart_products_array[$i]['dateattributes'][2], 'size="4"') . '</span>';
						if($cart_products_array[$i]['roomattributes'][1] != ''){
							//$roomInfoString = preg_replace('@(<[^<]*br[^<]*>[^<]+0.00)@','',$cart_products_array[$i]['roomattributes'][1]); //此值在shopping_cart.php中已经被转成了gb2312格式
							$roomInfoString = $cart->re_get_room_info_to_gb2312($cart_products_array[$i]['roomattributes'][3]);
							$roomInfoString = db_to_html($roomInfoString);
							$roomInfoString = format_out_roomattributes_1($roomInfoString, (int)$cart_products_array[$i]['roomattributes'][3]);
							$products_obj_html .=  ' <nobr><br><span>' . $roomInfoString.tep_draw_hidden_field('roominfo[]', $cart_products_array[$i]['roomattributes'][1], 'size="4"') . tep_draw_hidden_field('roomprice[]', $cart_products_array[$i]['roomattributes'][0]) . tep_draw_hidden_field('guestcount[]', $cart_products_array[$i]['roomattributes'][2]) . '</span>';
						}

						//New Group Buy 新团购优惠 {
						if($cart_products_array[$i]['is_new_group_buy']>0 ){
							$products_price = tep_db_query("select p.products_single, p.products_single_pu, p.products_double, p.products_triple, p.products_quadr, p.products_kids from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$cart_products_array[$i]['id'] . "' ");
							$products_result = tep_db_fetch_array($products_price);
							$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$cart_products_array[$i]['id']);
							if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
								$products_result['products_single'] = tep_get_tour_price_in_usd($products_result['products_single'], $tour_agency_opr_currency);
								$products_result['products_single_pu'] = tep_get_tour_price_in_usd($products_result['products_single_pu'], $tour_agency_opr_currency);
								$products_result['products_double'] = tep_get_tour_price_in_usd($products_result['products_double'], $tour_agency_opr_currency);
								$products_result['products_triple'] = tep_get_tour_price_in_usd($products_result['products_triple'], $tour_agency_opr_currency);
								$products_result['products_quadr'] = tep_get_tour_price_in_usd($products_result['products_quadr'], $tour_agency_opr_currency);
								$products_result['products_kids'] = tep_get_tour_price_in_usd($products_result['products_kids'], $tour_agency_opr_currency);
							}

							$a_price[1] = $products_result['products_single']; //single or single pair up
							if((int)$cart_products_array[$i]['roomattributes'][6] && (int)$products_result['products_single_pu']){
								$a_price[1] = $products_result['products_single_pu'];
							}
							$a_price[2] = $products_result['products_double']; //double
							$a_price[3] = $products_result['products_triple']; //triple
							$a_price[4] = $products_result['products_quadr']; //quadr
							$e = $products_result['products_kids']; //child Kid

							$oldPrice = 0;
							$g_array = explode('###',$cart_products_array[$i]['roomattributes'][3]);
							if($g_array[0]>0){ // room
								for($jj=1, $nn = sizeof($g_array); $jj<$nn; $jj++){
									if(strlen($g_array[$jj])>2){
										$n_array = explode('!!', $g_array[$jj]); //$n_array[0]大人 $n_array[1]小孩
										if($n_array[1]==0){
											$oldPrice += $a_price[$n_array[0]] * $n_array[0];
										}else{
											if($n_array[0] == '1' && $n_array[1] == '1') {
												$t_price[0] = 2*$a_price[2];
												$t_price[1] = $a_price[1]+$e;
												$oldPrice += min($t_price[0],$t_price[1]);
											}else if($n_array[0] == '1' && $n_array[1] == '2'){
												$t_price[0] = (2*$a_price[2]) + $e;
												$t_price[1] = 3*$a_price[3];
												$oldPrice += min($t_price[0],$t_price[1]);
											}else if($n_array[0] == '1' && $n_array[1] == '3'){
												$t_price[0] = (2*$a_price[2]) + 2*$e;
												$t_price[1] = 3*$a_price[3]+$e;
												$t_price[2] = 4*$a_price[4];
												$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);
											}else if($n_array[0] == '2' && $n_array[1] == '1'){
												$t_price[0] = (2*$a_price[2]) + $e;
												$t_price[1] = 3*$a_price[3];
												$oldPrice += min($t_price[0],$t_price[1]);
											}else if($n_array[0] == '2' && $n_array[1] == '2'){
												$t_price[0] = (2*$a_price[2]) + 2*$e;
												$t_price[1] = 3*$a_price[3]+$e;
												$t_price[2] = 4*$a_price[4];
												$oldPrice += min($t_price[0],$t_price[1],$t_price[2]);
											}else if($n_array[0] == '3' && $n_array[1] == '1'){
												$t_price[0] = 3*$a_price[3]+$e;
												$t_price[1] = 4*$a_price[4];
												$oldPrice += min($t_price[0],$t_price[1]);
											}
										}
									}
								}
								$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE;
							}else{ // no room
								$n_array = explode('!!', $g_array[1]); //$n_array[0]大人 $n_array[1]小孩
								$oldPrice += $n_array[0]*$a_price[1] + $n_array[1]*$e;
								$TITLE_NEW_GROUP_BUY_OLD_PRICE = TITLE_NEW_GROUP_BUY_OLD_PRICE_NOT_ROOM;
							}
							$SaveNum = $oldPrice - $cart_products_array[$i]['roomattributes'][0];
							if($SaveNum>0){
								$products_obj_html .= '<br /><span style="color:#F7860F">'.$TITLE_NEW_GROUP_BUY_OLD_PRICE.$currencies->display_price($oldPrice,0,$cart_products_array[$i]['quantity']).' '.TITLE_NEW_GROUP_BUY.' -'.$currencies->display_price($SaveNum,0,$cart_products_array[$i]['quantity']).'</span>';
							}
						}
						//New Group Buy 新团购优惠 }

						if($cart_products_array[$i]['dateattributes'][4] != '' && $cart_products_array[$i]['dateattributes'][4] != 0)
						{
							$products_obj_html .= '<br><span>'.TEXT_SHOPPING_CART_DEPARTURE_DATE_PRICE_FLUCTUATIONS. $cart_products_array[$i]['dateattributes'][3].tep_draw_hidden_field('prifixone[]', $cart_products_array[$i]['dateattributes'][3], 'size="4"') .' '. (($cart_products_array[$i]['dateattributes'][4]!='') ? '' : '') .$currencies->format($cart_products_array[$i]['dateattributes'][4],true,$currency).tep_draw_hidden_field('date_priceone[]', $cart_products_array[$i]['dateattributes'][4], 'size="4"') . '</span>';
						}

						if (isset($cart_products_array[$i]['attributes']) && is_array($cart_products_array[$i]['attributes'])) {
							reset($cart_products_array[$i]['attributes']);
							while (list($option, $value) = each($cart_products_array[$i]['attributes'])) {
								if($cart_products_array[$i][$option]['options_values_price'] != 0) {
									$products_obj_html .=  '<br><span>' . db_to_html($cart_products_array[$i][$option]['products_options_name']) . ': ' . db_to_html($cart_products_array[$i][$option]['products_options_values_name']) . ':</span><span> ' . $cart_products_array[$i][$option]['price_prefix'] . ' ' . $currencies->display_price($cart_products_array[$i][$option]['options_values_price'], tep_get_tax_rate($cart_products_array[$i]['tax_class_id']), 1) . '</span>';
								}else{
									if(trim($cart_products_array[$i][$option]['products_options_name'] )!=''){
										$products_obj_html .= '<br><span>' . db_to_html($cart_products_array[$i][$option]['products_options_name']) . ': ' . db_to_html($cart_products_array[$i][$option]['products_options_values_name']) . '</span>';
									}
								}
							}
						}
						//part2 end

						if(tep_check_priority_mail_is_active($cart_products_array[$i]['id']) == 1){
							$priority_mail_ticket_needed_date = tep_get_cart_get_extra_field_value('priority_mail_ticket_needed_date', $cart_products_array[$i]['extra_values']);
							if(tep_not_null($priority_mail_ticket_needed_date)){
								$products_obj_html .= '<br /><span>'.TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE.'</span><span>' . tep_get_date_disp($priority_mail_ticket_needed_date) . '</span>';
							}
							$priority_mail_delivery_address = tep_get_cart_get_extra_field_value('priority_mail_delivery_address', $cart_products_array[$i]['extra_values']);
							if(tep_not_null($priority_mail_delivery_address)){
								$products_obj_html .= '<br /><span>'.TXT_PRIORITY_MAIL_DELIVERY_ADDRESS.'</span><span>' . $priority_mail_delivery_address . '</span>';
							}
							$priority_mail_recipient_name = tep_get_cart_get_extra_field_value('priority_mail_recipient_name', $cart_products_array[$i]['extra_values']);
							if(tep_not_null($priority_mail_recipient_name)){
								$products_obj_html .= '<br /><span>'.TXT_PRIORITY_MAIL_RECIPIENT_NAME.'</span><span>' . $priority_mail_recipient_name . '</span>';
							}
						}

						//团购优惠
						if($cart_products_array[$i]['group_buy_discount']>0){
							$products_obj_html .= '<br /><span>'.($cart_products_array[$i]['is_diy_tours_book']==2 ? TXT_FEATURED_DEAL_DISCOUNT : TITLE_GROUP_BUY).'</span><span>-'.$currencies->display_price($cart_products_array[$i]['group_buy_discount'],0,$cart_products_array[$i]['quantity']).'</span>';
						}
						$cart_tour_price_div = '<b>'.$currencies->display_price($cart_products_array[$i]['final_price'], tep_get_tax_rate($cart_products_array[$i]['tax_class_id']), $cart_products_array[$i]['quantity']).'</b>';
					}
				}
				//amit added to get sting end


				//write change div and update div code start
								?>
								<script language="javascript">
								var txtPickup = "<?php echo addslashes($products_obj_html);?>";
								parent.document.getElementById("cart_product_data_<?php echo $iframe_cart_edit_update;?>").innerHTML=txtPickup;
								parent.document.getElementById("cart_product_data_price_<?php echo $iframe_cart_edit_update;?>").innerHTML="<?php echo $cart_tour_price_div;?>";
								<?php
								/*if(isset($repeat_royal_customer_discount))
								{
								$sub_total = $cart->show_total();
								$od_amount_royal = ($sub_total*5)/100;
								$sub_total = $sub_total - $od_amount_royal;*/

								if(isset($total_pur_suc_nos_of_cnt) && $redeempointforceclose == 'true')
								{
									//echo '-->>'.$customer_shopping_points;
									$sub_total = $cart->show_total();
									//$order->info['total'] = $sub_total;
									if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
										if (($customer_shopping_points = tep_get_shopping_points($_SESSION['customer_id'])) && $customer_shopping_points > 0 && $total_pur_suc_nos_of_cnt>0) {
											if (get_redemption_rules($order) && (get_points_rules_discounted($order) || get_cart_mixed($order))) {
												if ($customer_shopping_points >= POINTS_LIMIT_VALUE) {
													if ((POINTS_MIN_AMOUNT == '') || ($cart->show_total() >= POINTS_MIN_AMOUNT) ) {
														if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');

														$max_points_string = calculate_max_points_shopping_display($customer_shopping_points);
														$max_points1 = explode("-#-",$max_points_string);
														$max_points = $max_points1[0];
														$total_allowable_discount = $max_points1[1];
														if ($sub_total > tep_calc_shopping_pvalue($max_points)) {
															$note = '<br /><span>' . TEXT_REDEEM_SYSTEM_NOTE .'</span>';
														}
														$customer_shopping_points_spending = $max_points;
														// printf(TEXT_REDEEM_SYSTEM_SPENDING, number_format($max_points,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue($max_points)));
														$od_amount_royal = tep_calc_shopping_pvalue($max_points);
														$sub_total = $sub_total - $od_amount_royal;
													}
												}
											}
										}
									}

									if(isset($od_amount_royal) && $od_amount_royal > 0){ ?>
									parent.document.getElementById("loyal_sub_product_total_div").innerHTML="<?php echo '-'.$currencies->format($od_amount_royal);?>";
									<?php } ?>
									parent.document.getElementById("sub_product_total_div").innerHTML="<?php echo $currencies->format($sub_total);?>";
									<?php
								}
								else
								{
									?>
									parent.document.getElementById("sub_product_total_div").innerHTML="<?php echo $currencies->format($cart->show_total());?>";
									<?php
								}
								?>
								//parent.document.getElementById("edit_cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").style.display == "none";
								//alert(parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").innerHTML);
								//parent.HideContent("edit_cart_product_data_<?php echo $HTTP_POST_VARS['full_products_id'];?>");
								//parent.ShowContent("cart_product_data_<?php echo $HTTP_POST_VARS['full_products_id'];?>");
								parent.HideContent("edit_cart_product_data_<?php echo $iframe_cart_edit_update;?>");
								parent.ShowContent("cart_product_data_<?php echo $iframe_cart_edit_update;?>");
								//reloadParent();
								window.location="ajax_edit_tour.php?products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&full_products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&product_number=<?php echo (int)$HTTP_GET_VARS['product_number'];?>";
								</script>
								<?php
								//write change div and update div code end
			} //elseif($_POST['numberOfRooms'])//if there is rooms

		}
		break;
}


if((int)$_GET['products_id']){
	$cartProductArray = array();
	$cartProductsArray =  $cart->get_products();
	for($i=0 , $n=sizeof($cartProductsArray); $i<$n; $i++){
		if($cartProductsArray[$i]['id']==$_GET['full_products_id']){
		//if((int)$cartProductsArray[$i]['id']==(int)$_GET['products_id']){
			//echo intval($cartProductsArray[$i]['id'])." = ".intval($_GET['products_id']);
			$cartProductArray = $cartProductsArray[$i];	//当前选择的行程的原数据选项
			break;
		}
	}
}


require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

// BOF MaxiDVD: Modified For Ultimate Images Pack!
//coment by scs    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
//$product_info_query = tep_db_query("select p.products_video, p.products_type, p.operate_start_date ,p.operate_end_date,p.products_single,p.products_single_pu,p.products_double,p.products_triple,p.products_quadr,p.products_kids, p.display_room_option,p.maximum_no_of_guest,p.products_id, pd.products_name, pd.products_description, pd.products_pricing_special_notes,  pd.products_other_description, p.products_is_regular_tour, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.agency_id, p.display_pickup_hotels from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product_info_query = tep_db_query("select p.products_video, p.products_type, p.operate_start_date ,p.operate_end_date,p.products_single,p.products_double,p.products_triple,p.products_quadr,p.products_kids, p.display_room_option,p.maximum_no_of_guest,p.products_id, pd.products_name, pd.products_description, pd.products_pricing_special_notes,  pd.products_other_description, p.products_is_regular_tour, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.agency_id, p.display_pickup_hotels, p.hotels_for_early_arrival, p.hotels_for_late_departure, p.products_durations, p.products_durations_type ,p.is_hotel ,p.is_transfer,p.transfer_type, p.is_cruises from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
$product_info = tep_db_fetch_array($product_info_query);
//echo '<pre>';
//print_r($product_info);
//echo '</pre>';
//amit modified to make sure price on usd
$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product_info['products_id']);
if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
	$product_info['products_price'] = tep_get_tour_price_in_usd($product_info['products_price'],$tour_agency_opr_currency);
	$product_info['products_single'] = tep_get_tour_price_in_usd($product_info['products_single'],$tour_agency_opr_currency);
	$product_info['products_single_pu'] = tep_get_tour_price_in_usd($product_info['products_single_pu'],$tour_agency_opr_currency);
	$product_info['products_double'] = tep_get_tour_price_in_usd($product_info['products_double'],$tour_agency_opr_currency);
	$product_info['products_triple'] = tep_get_tour_price_in_usd($product_info['products_triple'],$tour_agency_opr_currency);
	$product_info['products_quadr'] = tep_get_tour_price_in_usd($product_info['products_quadr'],$tour_agency_opr_currency);
	$product_info['products_kids'] = tep_get_tour_price_in_usd($product_info['products_kids'],$tour_agency_opr_currency);
}
//amit modified to make sure price on usd
tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
	$products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
} else {
	$products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
}

//vincent add .fix hotel order type .{
if($cartProductArray['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='1'){
	$hotel_ext_addon_text = '<br><span><b>(提前到达预订的酒店)</b></span>';
}else if($cartProductArray['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]=='2'){
	$hotel_ext_addon_text = '<br><span><b>(延后离开预订的酒店)</b></span>';
}
//}

if (tep_not_null($product_info['products_model'])) {
	$products_obj_html = $product_info['products_name'].$hotel_ext_addon_text;  // . '&nbsp;&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
} else {
	$products_obj_html = $product_info['products_name'].$hotel_ext_addon_text;
}

$product_rank = $HTTP_GET_VARS['product_number'];
if (($product_rank/2) == floor($product_rank/2)) {
	$class_body = "body_ajax_even_new";
} else {
	$class_body = "body_ajax_odd_new";
}
?>
<?php
//海鸥团：增加选择床型这些选项。
if($product_info['agency_id']=="2"){
?>
<style type="text/css">
<!--
#body .sel2 { margin-right: 0px;}
-->
</style>

<?php
}
?>

<body id="body" class="<?php echo $class_body; ?>">

<script type="text/javascript">
<?php /* 修正IE下选择下拉列表的Bugs */?>
jQuery(document).ready(function() {
	jQuery("select").change(function(){ document.body.focus(); });
});
</script>

<script type="text/javascript">
<?php
$db_query =tep_db_query( 'SELECT is_transfer, transfer_type FROM '.TABLE_PRODUCTS." WHERE products_id = ".intval($cartProductArray['id']) );
$transfer_products_info = tep_db_fetch_array($db_query);
$locationJs = '';
$locationArray = tep_transfer_get_locations(intval($cartProductArray['id']));
foreach($locationArray as $key=>$row){
	$locationJs .= '{"id":"'.$row['products_transfer_location_id'].'","address":"'.format_for_js(db_to_html($row['short_address'])).'","zipcode":"'.format_for_js($row['zipcode']).'","type":"'.format_for_js($row['type']).'"},';
}
$locationJs = substr($locationJs,0,-1);
$routeJs = '';$routeCount = 0 ;
$routesArray = tep_transfer_get_routes(intval($cartProductArray['id']));
foreach($routesArray as $row){
	$routeJs .= '{"loc1":"'.$row['pickup_location_id'].'","loc2":"'.$row['dropoff_location_id'].'"},';
	$routeCount++;
}
$routeJs = substr($routeJs,0,-1);

echo 'var locations=['.$locationJs .'];';
echo 'var route=['.$routeJs .'];';
?>
function setTransferAddress(id,addrId,zipcodeId){
	var addr='';
	var zipcode ='';
	for(i=0;i<locations.length;i++){
		if(locations[i].id==id) {
			addr = locations[i].address;
			zipcode = locations[i].zipcode;
			break;
		}
	}
	jQuery('#'+addrId).val(addr);
	jQuery('#'+zipcodeId).val(zipcode);
}
function hasRoute(loc1,loc2){
	for(var i=0 ; i < route.length;i++){
		if((route[i].loc1 == loc1 && route[i].loc2 == loc2 )||(route[i].loc2 == loc1 && route[i].loc1 == loc2 )){
			return true;
		}
	}
	return false;
}
function getLocationTextById(id){
	for(var i=0 ; i<locations.length;i++){
		if(locations[i].id == id){
			if(locations[i].zipcode == '0') text = locations[i].address;
			else text = locations[i].address+"("+locations[i].zipcode+")";
			return text;
		}
	}
	return '';
}

function setLocationAvaliable(srcobj , targetId){
	//jQuery(src).parent().parent().find("input[]")
	<?php if($product_info['transfer_type']!='1') echo 'return true;' //只有固定线路的情况需要检查选项的可用性?>
	var v1 = jQuery(srcobj).val();
	jQuery("#"+targetId+" option").each(function(){
		var v2 = jQuery(this).attr("value");
		if(v2 == v1 || !hasRoute(v1,v2)){
			jQuery(this).attr("selected" ,false);
			jQuery(this).attr("disabled" ,true);
		}else{
			jQuery(this).removeAttr("disabled");
		}
	});
}

function setOption(objid , type ){
	var html = '<option value="0"> -------------- </option>';
	if(type == 'reset') {	jQuery("#"+objid).html("");return ;}
	var value = jQuery("#"+objid).val();
	for(i = 0 ;i<locations.length;i++){
		if( type=='all' || (type=='airport'&&locations[i].type == '0') || (type=='location'&&locations[i].type == '1') ){
			selected = value == locations[i].id ? ' selected  ':'';
			if(locations[i].type == '0') text = "(Airport)"+locations[i].address;
			else text = "("+locations[i].zipcode+")"+locations[i].address;
			html+= '<option value="'+locations[i].id+'" '+selected+'>'+text+'</option>';
		}
	}
	jQuery("#"+objid).html(html);
}

function resetRoute(routeIndex){
	jQuery("#PickupZipcode"+routeIndex).val(0);
	jQuery("#PickupAddress"+routeIndex).val("");	
	jQuery("#DropoffZipcode"+routeIndex).val(0);
	jQuery("#DropoffAddress"+routeIndex).val("");
	jQuery("#transfer_route_"+routeIndex+" input").val("");
	jQuery("#transfer_route_"+routeIndex+" textarea").html("");
}
var isFirstTimeToSet = true ;
function setTransferType(type){	
	minHeight = '450px';
	maxHeight = '800px';
	height = minHeight;

	jQuery("#DropoffAddress1").removeAttr('readonly');
	//jQuery("#DropoffAddress1").val("");
	jQuery("#DropoffAddress2").removeAttr('readonly');
	//jQuery("#DropoffAddress2").val("");
	jQuery("#PickupAddress1").removeAttr('readonly');
	//jQuery("#PickupAddress1").val('');
	jQuery("#PickupAddress2").removeAttr('readonly');
	//jQuery("#PickupAddress2").val('');
	if(type == 1) {
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();height = minHeight;
		setOption("PickupId1",'location');setOption("DropoffId1",'airport');jQuery("#DropoffAddress1").attr("readonly" ,true);
		setOption("PickupId2",'reset');	setOption("DropoffId2",'reset');

	}else if(type == 2 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();height = minHeight;
		setOption("PickupId1",'airport');	setOption("DropoffId1",'location');jQuery("#PickupAddress1").attr("readonly" ,true);
		setOption("PickupId2",'reset');	setOption("DropoffId2",'reset');
	}else if(type == 3 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = maxHeight;
		setOption("PickupId1",'airport');setOption("DropoffId1",'location');jQuery("#PickupAddress1").attr("readonly" ,true);
		setOption("PickupId2",'location');	setOption("DropoffId2",'airport');	jQuery("#DropoffAddress2").attr("readonly" ,true);
	}else if(type == 4 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = maxHeight;
		setOption("PickupId1",'location');setOption("DropoffId1",'airport');jQuery("#DropoffAddress1").attr("readonly" ,true);
		setOption("PickupId2",'airport');	setOption("DropoffId2",'location');jQuery("#PickupAddress2").attr("readonly" ,true);
	}else if(type == 5 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").hide();	height = minHeight;
		setOption("PickupId1",'all');setOption("DropoffId1",'all');
		setOption("PickupId2",'all');	setOption("DropoffId2",'all');
	}else if(type == 6 ){
		jQuery("#transfer_route_1").show();	jQuery("#transfer_route_2").show();	height = maxHeight;
		setOption("PickupId1",'all');setOption("DropoffId1",'all');
		setOption("PickupId2",'all');	setOption("DropoffId2",'all');
	}
	if(!isFirstTimeToSet){
		resetRoute("1");resetRoute("2");		
	}
	isFirstTimeToSet = false;
	window.parent.document.getElementById("iframe_prod_<?php echo $_GET['product_number']?>").style.height=height;
}

function updateMaxBaggageTotal(gtid,btid){
	var guesttotal = jQuery('#'+gtid).val();
	var lastCarPerson= guesttotal%6;
	var carTotal = Math.floor(guesttotal/6);
	var maxBaggageTotal = carTotal*4 ;
	if(lastCarPerson!=0){
		if(lastCarPerson <= 4)maxBaggageTotal+=6;
		else if(lastCarPerson == 5)maxBaggageTotal+=5;
	}
	var cbt=jQuery("#"+btid).val();
	var html = "";
	for(var i = 0 ;i<=maxBaggageTotal;i++){
		checked = i==cbt?" selected ": "";
		html+= '<option value="'+i+'"' + checked+'>'+i+'</option>';
	}
	jQuery("#"+btid).html(html);
}
</script>
<script type="text/javascript">
var radio_error = "<?php echo RADIO_ERROR?>";
var select_option_error = "<?php echo SELECT_OPTION_ERROR?>";
</script>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT.VALIDATION_JS;?>"></script>
<?php
echo tep_draw_form('cart_quantity', tep_href_link('ajax_edit_tour.php', tep_get_all_get_params(array('action','maxnumber','dp_old_select','update_frame')) . 'action_ajax_add=add_product', $request_type),'post','id="cart_quantity"'); ?>

<?php
	// howard added display global msn
	if ($messageStack->size('global') > 0){
	?>
		<div style="width:950px; margin:auto; padding-top:10px; padding-bottom:10px;"><?php echo $messageStack->output('global'); ?></div>
	<?php
	}
	// howard added display global end
?>
			
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:12px;">
							  <tr>
								<td valign="top">
									<table  border="0" width="100%" cellspacing="0" cellpadding="0">
								 
										 <?php if($errormessageperson != ''){ ?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning "	width="10" height="10">&nbsp;&nbsp;<?php echo TEXT_MAX_ALLOW_ROOM;?> <?php echo $errormessageperson; ?></td></tr></table></td></tr>
										 <?php	 }?>
										 
										 <?php if($error_transfer_info != ''){		// transfer-service validate ?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning "	width="10" height="10">&nbsp;&nbsp;<?php echo db_to_html($_GET['transfer_error_msg']);?></td></tr></table></td></tr>
										<?php	 }  ?>		

										 <?php if($error_min_guest != ''){		// howard added error_min_guest ?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning "	width="10" height="10">&nbsp;&nbsp;<?php echo TEXT_PRODUCTS_MIN_GUEST;?> <?php echo $error_min_guest; ?></td></tr></table></td></tr>
										<?php	 }  // howard added error_min_guest end?>		

										 <?php if( $error_he_checkout_dt == 'true'){  	// hotel-extension ?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning "	width="10" height="10">&nbsp;&nbsp;<?php echo db_to_html('退房日期无效,请检查！');?></td></tr></table></td></tr>
										<?php	 } 
										 if( $error_he_checkin_dt == 'true'){  	?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning "	width="10" height="10">&nbsp;&nbsp;<?php echo db_to_html('入住日期无效,请检查！');?></td></tr></table></td></tr>
										<?php	 }
										  // hotel-extension ?>		

										<?php if (isset($_GET['total_price_error'])) {		?>
										<tr><td><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning " width="10" height="10">&nbsp;&nbsp;<?php echo db_to_html('总价为'.$_GET['total_price_error'].'，不能预订请注意"价格明细"中的说明！')?></td></tr></table></td></tr>
										<?php	}	?>

										 <?php if( $error_depar_dt == 'true'){		 ?>
										 <tr><td ><table border="0" width="100%"   cellspacing="0" cellpadding="2"><tr><td class="errorBox1" colspan="2"><img src="images/icons/warning.gif" border="0" alt="Warning" title=" Warning " width="10" height="10">&nbsp;&nbsp;<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE;?></td></tr></table></td></tr>
										<?php	}	 ?>										
										 <tr><td class="productListing-data"  valign="top"  ><b><?php echo db_to_html($products_obj_html); ?></b></td></tr>
									</table>
					<?php
					$ar_soldout_dates=array();
					$qry_sold_dates="SELECT * FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".(int)$HTTP_GET_VARS['products_id']."'";
					$res_sold_dates=tep_db_query($qry_sold_dates);
					while($row_sold_dates=tep_db_fetch_array($res_sold_dates)){
						$ar_soldout_dates[]=$row_sold_dates['products_soldout_date'];
					}

					$num_of_sections = regu_irregular_section_numrow((int)$HTTP_GET_VARS['products_id']);

					$is_las_vegas_show = check_is_las_vegas_show((int)$HTTP_GET_VARS['products_id']);

					if($is_las_vegas_show==true){
						$select_departure_date = TEXT_SELECT_DEPARTURE_DATE_SHOW;
					}else{
						$select_departure_date = TEXT_SELECT_DEPARTURE_DATE;
					}

					$avaliabledate = '<option value="">'.$select_departure_date.'</option>';

					$array_avaliabledate_store = get_avaliabledate((int)$HTTP_GET_VARS['products_id']);
					if(is_array($array_avaliabledate_store)){
						// Remove sold dates
						$array_avaliabledate_store = remove_soldout_dates((int)$HTTP_GET_VARS['products_id'], $array_avaliabledate_store);
						array_multisort($array_avaliabledate_store,SORT_ASC);
						foreach($array_avaliabledate_store as $avaliabledate_key=>$avaliabledate_val){
							if (eregi('('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')', $avaliabledate_val)) {
								$dis_red_style_dep = " style='color:#F1740E;' ";
							}else{
								$dis_red_style_dep = "";
							}
							//$avaliabledate .= '<option '.$dis_red_style_dep.' value="'.$avaliabledate_key.'">'.$avaliabledate_val.'</option>';
							$date_split = substr($avaliabledate_val,0,10);
							$availabledate_val1 = tep_get_date_disp($date_split);
							$availabledate_val2 = en_to_china_weeks(substr($avaliabledate_val,10));
							$d_selected = "";
							$dp = tep_not_null($HTTP_GET_VARS['departure_date'])?$HTTP_GET_VARS['departure_date'] : $HTTP_GET_VARS['dp_old_select'];
							//if(preg_match('/'.$HTTP_GET_VARS['departure_date'].'/',$avaliabledate_key)){ $d_selected = ' selected="selected" '; }
							if($dp!='' && ($avaliabledate_key!='' && strpos($avaliabledate_key,$dp)!== false)) $d_selected = ' selected="selected" ';
							$avaliabledate .= '<option '.$dis_red_style_dep.' value="'.$avaliabledate_key.'" '.$d_selected.'>'.db_to_html($availabledate_val1).$availabledate_val2.'</option>';
						}
					}
					/*
					if($first_availabledate == 0)	{
					echo tep_draw_hidden_field('first_availabletourdate', $formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice]);
					}*/



					$no_sel_date_tags = '';

					//限时团新团购可不限出团日期 {
					if(is_group_buy_product((int)$HTTP_GET_VARS['products_id'])==2){
						$no_sel_date_for_group_buy = $cartProductArray['no_sel_date_for_group_buy'];
						/* 这是未定出发日期的复选框
						$no_sel_date_tags = '<label>'.tep_draw_checkbox_field('no_sel_date_for_group_buy', '1', '', 'onclick="to_end_availabletourdate()"').' '.NO_SEL_DATE_FOR_GROUP_BUY.'</label>';
						*/
					}
					//限时团新团购可不限出团日期 }

					//hotel-extension {
					$call_end_date_function = '';
					if(tep_not_null($product_info['hotels_for_early_arrival']) || tep_not_null($product_info['hotels_for_late_departure'])){
						if($product_info['products_durations_type']!=0){
							$product_info['products_durations'] = 1;
						}
						$call_end_date_function = ' onchange="get_tour_end_date(this.value, \''.$product_info['products_durations'].'\');"';
					}
					if(tep_check_product_is_hotel($product_info['products_id'])==1){
						$hotel_extension_info = $cart->contents[$HTTP_GET_VARS['products_id']]['hotel_extension_info'];

						$hotel_extension_values = explode('|=|', $hotel_extension_info);
						$checkin_out_times = explode("-", $hotel_extension_values[3]);
						if($checkin_out_times[0] == ''){
							$checkin_out_times[0] = "15:00:00";
							$checkin_out_times[1] = "12:00:00";
						}

						$late_checkin_out_times = explode("-", $hotel_extension_values[9]);
						if($late_checkin_out_times[0] == ''){
							$late_checkin_out_times[0] = "15:00:00";
							$late_checkin_out_times[1] = "12:00:00";
						}
						/*if(tep_not_null($hotel_extension_values[0]) || tep_not_null($hotel_extension_values[7])){
						$hotel_box_style = '';
						}else{
						$hotel_box_style = ' style="display:none; "';
						}

						<div id="hotel_reservation_box" <?php //echo $hotel_box_style; ?> style="width:200px">*/
					?>
                    				
					<table border="0"  width="250" cellspacing="0" cellpadding="2">				
							<?php 
							if($cartProductArray['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]==2){ //late
							?>
							<tr>
								
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("入住日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" name="late_hotel_checkin_date" id="late_hotel_checkin_date" value="<?php echo $hotel_extension_values[6]; ?>" />
								<?php 
								echo tep_draw_hidden_field('early_hotel_checkin_time', $checkin_out_times[0]);
								echo tep_draw_hidden_field('early_hotel_checkout_time', $checkin_out_times[1]);
								echo tep_draw_hidden_field('late_hotel_checkin_time', $late_checkin_out_times[0]);
								echo tep_draw_hidden_field('late_hotel_checkout_time', $late_checkin_out_times[1]);
								echo tep_draw_hidden_field('h_l_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 2);
								echo tep_draw_hidden_field('early_hotel_checkin_date', $hotel_extension_values[0]);
								echo tep_draw_hidden_field('early_hotel_checkout_date', $hotel_extension_values[1]);
								echo tep_draw_hidden_field('staying_late_hotels', $product_info['products_id']);
								?>
								</td>
							</tr>
							
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("退房日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" name="late_hotel_checkout_date" value="<?php echo $hotel_extension_values[7]; ?>" />&nbsp;(MM/DD/YYYY)</td>
							</tr>
							<?php							
							}else if($cartProductArray['attributes'][HOTEL_EXT_ATTRIBUTE_OPTION_ID]==1){ //early
							?>
							<TR>
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("入住日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" name="early_hotel_checkin_date" value="<?php echo $hotel_extension_values[0]; ?>" />&nbsp;(MM/DD/YYYY)</td>
							</tr>
							<TR>
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("退房日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" name="early_hotel_checkout_date" id="early_hotel_checkout_date" value="<?php echo $hotel_extension_values[1]; ?>" />
								<?php 
								echo tep_draw_hidden_field('early_hotel_checkin_time', $checkin_out_times[0]);
								echo tep_draw_hidden_field('early_hotel_checkout_time', $checkin_out_times[1]);
								echo tep_draw_hidden_field('late_hotel_checkin_time', $late_checkin_out_times[0]);
								echo tep_draw_hidden_field('late_hotel_checkout_time', $late_checkin_out_times[1]);
								echo tep_draw_hidden_field('h_e_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 1);
								echo tep_draw_hidden_field('late_hotel_checkin_date', $hotel_extension_values[6]);
								echo tep_draw_hidden_field('late_hotel_checkout_date', $hotel_extension_values[7]);
								echo tep_draw_hidden_field('early_arrival_hotels', $product_info['products_id']);
								?>
								</td>
							</tr>
							<?php
							}else{
							?>
							<TR>
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("入住日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" style="ime-mode: disabled;" onClick="GeCalendar.SetDate(this);" name="early_hotel_checkin_date" value="<?php echo $hotel_extension_values[0]; ?>" />&nbsp;<?php echo db_to_html('(月/日/年)	如：')?>05/12/2010</td>
							</tr>
							<TR>
								<td align="left" nowrap="nowrap" class="main"><?php echo db_to_html("退房日期："); ?></td>
								<td align="left" nowrap="nowrap" class="main"><input type="text" style="ime-mode: disabled;" onClick="GeCalendar.SetDate(this);" name="early_hotel_checkout_date" id="early_hotel_checkout_date" value="<?php echo $hotel_extension_values[1]; ?>" />
								<?php 
								//echo tep_draw_hidden_field('h_e_id[999]', 1);
								echo tep_draw_hidden_field('h_e_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 3);
								echo tep_draw_hidden_field('late_hotel_checkin_date', $hotel_extension_values[6]);
								echo tep_draw_hidden_field('late_hotel_checkout_date', $hotel_extension_values[7]);
								echo tep_draw_hidden_field('early_arrival_hotels', $product_info['products_id']);
								?>
								</td>
							</tr>							
							<?php
							}
							//</div>?>
						
					
					<?php
					}else{
						$is_start_date_required = is_tour_start_date_required((int)$product_info['products_id']);
						//echo "<tr><td>$is_start_date_required:".$is_start_date_required."</td></tr>";
						if($is_start_date_required==true){
							echo '<table border="0"  width="250" cellspacing="0" cellpadding="2">
							<tr>
							 
							  <td align="left" nowrap="nowrap" class="main"><b>'.TEXT_DATE.'</b></td>
							  <td align="left" nowrap="nowrap" class="main"><select name="availabletourdate" id="availabletourdate" class="validate-selection-blank-custom sel3" title="'.TEXT_SELECT_VALID_DEPARTURE_DATE.'">
								  '.$avaliabledate.'
								</select>'
							.$no_sel_date_tags;
							if(tep_check_product_is_hotel((int)$product_info['products_id'])>=2){
								$hotel_extension_info = $cart->contents[$HTTP_GET_VARS['products_id']]['hotel_extension_info'];

								$hotel_extension_values = explode('|=|', $hotel_extension_info);
								$checkin_out_times = explode("-", $hotel_extension_values[3]);
								if($checkin_out_times[0] == ''){
									$checkin_out_times[0] = "15:00:00";
									$checkin_out_times[1] = "12:00:00";
								}
								echo tep_draw_hidden_field('early_hotel_checkin_time', $checkin_out_times[0]);
								echo tep_draw_hidden_field('early_hotel_checkout_time', $checkin_out_times[1]);
							}
							echo '</td></tr>';
						}
					}

			?>

<?php
$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
$products_attributes = tep_db_fetch_array($products_attributes_query);
if ($products_attributes['total'] > 0) {
	/*

	<tr>
	<td></td>
	<td class="main" colspan="2"><?php //echo TEXT_PRODUCT_OPTIONS; ?></td>
	</tr>*/
	$att_cnt = 0;
	$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
	$cruises_products_options = false;
	while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
		$products_options_array = array();
		$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price, pa.kids_values_price  from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order ASC, pa.options_values_price ASC ");
		while ($products_options = tep_db_fetch_array($products_options_query)) {
			$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'value'=>$products_options['options_values_price'],'text' => db_to_html($products_options['products_options_values_name']));
			if ($products_options['options_values_price'] != '0') {
				//amit modified to make sure price on usd
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$products_options['options_values_price'] = tep_get_tour_price_in_usd($products_options['options_values_price'],$tour_agency_opr_currency);
				}
				//amit modified to make sure price on usd
				$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
			}
			//amit added to show Holiday Surcharge -- for special price start
			if($products_options['single_values_price'] > 0 || $products_options['kids_values_price'] > 0){
				if((int)$products_options_name['products_options_id'] == 189){	//189 - Add-on optional tour on Day2:
					$products_options_array[sizeof($products_options_array)-1]['text'] .= TEXT_HEADING_PRODUCT_ATTRIBUTE_OPTIONS_TOUR;
				}else{
					$products_options_array[sizeof($products_options_array)-1]['text'] .= ' ('.TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE.') ';
				}
			}
			//amit added to show Holiday Surcharge -- for special price end
		}
		if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
			$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
		} else {
			$selected_attribute = false;
		}
		$att_cnt++;
		
		if(in_array($products_options_name['products_options_id'],(array)$cruisesOptionIds)){
			//取得甲板的入住人数的上限和下限
			foreach((array)$products_options_array as $key => $val){
				$sql = tep_db_query('SELECT max_per_of_guest, min_num_guest FROM  `cruises_cabin_deck` WHERE products_options_values_id="'.$val['id'].'" LIMIT 1');
				$row = tep_db_fetch_array($sql);
				if($row['min_num_guest']>0){
					$products_options_array[$key]['min_num_guest'] = $row['min_num_guest'];
				}else{
					$products_options_array[$key]['min_num_guest'] = $product_info['min_num_guest'];
				}
				if($row['max_per_of_guest']>0){
					$products_options_array[$key]['max_per_of_guest'] = $row['max_per_of_guest'];
				}else{
					$products_options_array[$key]['max_per_of_guest'] = $product_info['maximum_no_of_guest'];
				}
				//去掉产品属性选项值中 升级价格 的字眼
				$products_options_array[$key]['text'] = str_replace(' (' .TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE. ') ','',$products_options_array[$key]['text']);
			}
			
			$cruises_products_options[] = array('id'=> $products_options_name['products_options_id'], 'text'=> $products_options_name['products_options_name'], 'products_options_value_obj'=>$products_options_array, 'selected_attribute' => $selected_attribute );
			
		}else{
?>
            <tr>
				
              <td class="main"><b><?php echo db_to_html($products_options_name['products_options_name']) . ':'; ?></b></td>
              <td ><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,'class="sel3" '.($products_options_name['products_options_id'] == PRIORITY_MAIL_PRODUCTS_OPTIONS_ID ? ' onchange="show_priority_mail_date(this.value);"' : '').''); 
              if($products_options_name['products_options_id'] == PRIORITY_MAIL_PRODUCTS_OPTIONS_ID && $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']] == PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID){
              	$div_priority_mail_date_field_style = '';
              }
			   ?></td>
            </tr>
<?php
		}
	}

}

/**
* 如果产品选项属于邮轮客舱的选项则收集所有客舱甲板选项用于单选
*/
if(is_array($cruises_products_options)){
	$includeSource = 'ajax_edit_tour';
	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'product_info_options_for_cruises.php');
}

if(tep_check_priority_mail_is_active($HTTP_GET_VARS['products_id']) == 1){
	$priority_mail_ticket_needed_date = tep_get_cart_get_extra_field_value('priority_mail_ticket_needed_date', $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);
	$priority_mail_delivery_address = tep_get_cart_get_extra_field_value('priority_mail_delivery_address', $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);
	$priority_mail_recipient_name = tep_get_cart_get_extra_field_value('priority_mail_recipient_name', $cart->contents[$HTTP_GET_VARS['products_id']]['extra_values']);

		?>
        <tr><td colspan="2">
        <div id="div_priority_mail_date_field" <?php echo $div_priority_mail_date_field_style; ?>>
        <table border="0"  width="250" cellspacing="0" cellpadding="2">	
        
            <tr><td class="main" ><b><?php echo TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE; ?></b></td>
     			      <td><?php echo tep_draw_input_field('priority_mail_ticket_needed_date', $priority_mail_ticket_needed_date); ?></td>
        	</tr>
        <?php if($product_info['agency_id'] == 89){ ?>
	        <tr>
            <td class="main" ><b><?php echo TXT_PRIORITY_MAIL_DELIVERY_ADDRESS; ?></b></td>
            <td><?php echo tep_draw_input_field('priority_mail_delivery_address', $priority_mail_delivery_address); ?></td>
            </tr>
			<tr>
			<td class="main" ><b><?php echo TXT_PRIORITY_MAIL_RECIPIENT_NAME; ?></b></td>
            <td><?php echo tep_draw_input_field('priority_mail_recipient_name', $priority_mail_recipient_name); ?></td>
            </tr>
        <?php } ?>        
        </table>
        </div>
        </td></tr>
        <?php
}
/*	$depart_option ='';
$departure_query = tep_db_query("select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." ");
while($departure_result = tep_db_fetch_array($departure_query))
{
$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].'">'.substr($departure_result['departure_time'].' &nbsp; '.$departure_result['departure_address'],0,25).'</option>';
}

if($depart_option != '')
{
echo'<tr>
<td align="left" class="main">Departure:</td>
<td align="left" class="main"><select name="departurelocation" style="width:200;">'.$depart_option.'</select></td>
</tr>';
}
echo '</table>';
echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_template_image_submit('addToCart.gif', IMAGE_BUTTON_IN_CART);
*/
//bhavik code for diaplay depature regions
$display_departure_region_combo = "";
$query_region = "select * from products_departure where departure_region<>'' and products_id = ".(int)$HTTP_GET_VARS['products_id']." group by departure_region";
$row_region = mysql_query($query_region);

//echo mysql_num_rows($row_region);
// amit commented old code start
/*
while($result_region = mysql_fetch_array($row_region))
{
$display_departure_region_combo = "true";
}
*/
//amit commented end

$totlaregioncount = mysql_num_rows($row_region);
//if((int)$totlaregioncount > 1 || $product_info['products_type'] == 2){
//if((int)$totlaregioncount > 1 || ($product_info['products_type'] == 2 && $product_info['display_pickup_hotels'] == '1') || ($product_info['products_type'] == 3 && $product_info['agency_id'] == 12 && $product_info['display_pickup_hotels'] == '1')){
if((int)$totlaregioncount > 1 || ($product_info['agency_id'] == 12 && $product_info['display_pickup_hotels'] == '1')){
	$display_departure_region_combo = "true";

}else if((int)$totlaregioncount == 1){

	$display_departure_region_combo = "true";
	$display_departure_region_onecount = "true";
}

// show title strart
$text_shopping_cart_pickp_location = TEXT_SHOPPING_CART_PICKP_LOCATION;
$TEXT_TICKETS = db_to_html('参团人数：');
if((int)is_show((int)$HTTP_GET_VARS['products_id'])){
	$text_shopping_cart_pickp_location = db_to_html('演出时间/地点：');//PERFORMANCE_TIME;
	$TEXT_TICKETS = WATCH_PEOPLE_NUM;
}
// show title end


if($display_departure_region_combo != "true")
{
	$qry ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." ";
	$qryset = mysql_query($qry);
	$pm = 0 ;
	$am = 0;
	$other = 0;
	while($qry_rel = mysql_fetch_array($qryset))
	{
		$len=strlen($qry_rel['departure_time']);
		if($len == 6){
			$depart_final = '0'.$qry_rel['departure_time'];
		}else{
			$depart_final = $qry_rel['departure_time'];
		}

		if(strstr($depart_final,'pm'))
		{
			$pma[$qry_rel['departure_id']] = $depart_final ;
		}
		if(strstr($depart_final,'am'))
		{
			$ama[$qry_rel['departure_id']] = $depart_final ;
		}

	}
	if($ama != '')
	array_multisort($ama,SORT_ASC);
	if($pma != '')
	array_multisort($pma,SORT_ASC);


	$depart_option = '';
	$finalid = 0;
	if($ama != '')
	{
		foreach($ama as $key => $val)
		{
			if(substr($val,0,1) == 0)
			$val = substr($val,1,7);
			$qryfinal ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_time Like '%".$val."' and departure_id not in(".$finalid.") ";
			$departure_query = mysql_query($qryfinal);
			$departure_result = tep_db_fetch_array($departure_query);
			if((int)$departure_result['departure_id']){
				$finalid .= ",".$departure_result['departure_id'];
			}
			$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].'">'.substr($departure_result['departure_time'].' &nbsp; '.$departure_result['departure_address'],0,100).'</option>';
		}
	}
	$finalidpm = 0;
	if($pma != '')
	{
		foreach($pma as $key => $val)
		{
			if(substr($val,0,1) == 0)
			$val = substr($val,1,7);
			$qryfinal ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_time Like '%".$val."' and departure_id not in(".$finalidpm.") ";
			$departure_query = mysql_query($qryfinal);
			$departure_result = tep_db_fetch_array($departure_query);
			if((int)$departure_result['departure_id']){
				$finalidpm .= ",".$departure_result['departure_id'];
			}
			$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].'">'.$departure_result['departure_time'].' &nbsp; '.substr($departure_result['departure_address'],0,2500).'</option>';
		}
	}
	if($depart_option != '')
	{
		echo'<tr>
			  <td align="left" class="main"><b>'.$text_shopping_cart_pickp_location.'</b></td>
			  <td align="left" class="main"><select name="departurelocation" class="sel3" style="width:240;">'.db_to_html($depart_option).'</select></td>
			</tr>';
	}

}elseif($display_departure_region_combo == "true" ) //else of if($display_departure_region_combo == "true")
{
	$departuredate_true = "in";

	$on_shopping_cart = true;
	echo'<tr><td colspan=2>';
	if($product_info['agency_id'] == 12 && $product_info['display_pickup_hotels'] == '1'){
		
		include("pickuplocation_helicopter.php");
	}else{

		$first_time_after_location = false;
		if(FIRST_TIME_AFTER_LOCATION=='true'){
			$first_time_after_location = true;	//先选接送时间再选接送地址
		}

		if($display_departure_region_onecount == "true"){
			
			if($first_time_after_location == true){
				// 乘车地址
				include("first_time_after_location_pickuplocation_one.php");
			}else{
				include("pickuplocation_one.php");
			}

		}else{
			if($first_time_after_location == true){
				include("first_time_after_location_pickuplocation.php");
			}else{
				include("pickuplocation.php");
			}

		}
		echo'</td></tr>';
	}




}  //end of if($display_departure_region_combo == "true")

if($product_info['display_room_option']==1 && $product_info['is_transfer'] != "1")
{
	echo'<tr>
					  <td align="left" class="main" valign="top"><b>'.db_to_html("房间人数：").'</b></td>
					  <td align="left" class="main"><DIV id=hot-search-params></DIV></td>
					</tr>';
}elseif($product_info['display_room_option']==0)
{
	echo'<tr>
					  <td align="left" class="main"  valign="top"><b>'.$TEXT_TICKETS.'</b></td>
					  <td align="left" class="main"><DIV id=hot-search-params></DIV></td>
					</tr>';
}
//transfer-service { 接送服务选项
if($product_info["is_transfer"] == '1') {
	$transfer_info_array = tep_transfer_decode_info($cartProductArray['transfer_info']);
	//用户更改了数据，但是更改的数据不合法,则使用用户数据不使用购物车的数据

	//print_vars($transfer_info_array);
	$transfer_type_options = array(
	array('id'=>'1','text'=>'居住地送往机场服务 (单次服务)'),
	array('id'=>'2','text'=>'机场接应送往居住地服务 (单次服务)'),
	array('id'=>'3','text'=>'机场接应送往居住地，居住地送往机场服务(两次服务)'),
	array('id'=>'4','text'=>'居住地送往机场服务，机场接应送往居住地(两次服务)'),
	array('id'=>'5','text'=>'区域接应服务（单次）'),
	array('id'=>'6','text'=>'区域接应服务（双次）')
	);
	if($product_info['transfer_type'] == '0'){
		unset($transfer_type_options[4]);unset($transfer_type_options[5]); //洛杉矶没有区域接送
	}
	$locations = tep_transfer_get_locations($products_id );
	$locationsPulldown = array(array('id'=>'0' , 'text'=>'----------'));
	foreach($locations as $v) {
		if($v['type'] == '0') $zipcode = 'Airport';else $zipcode = $v['zipcode'];
		$locationsPulldown[] = array('id'=>$v['products_transfer_location_id'] , 'text'=>'('.$zipcode.')'.$v['short_address']);
	}
	$guestPulldown = array();
	for($guest_num = 1 ;$guest_num <=18;$guest_num++){
		$guestPulldown[] = array('id'=>$guest_num,'text'=>$guest_num);
	}
	$bagPulldown = array();
	for($bag_num = 0 ;$bag_num <=36;$bag_num++){
		$bagPulldown[] = array('id'=>$bag_num,'text'=>$bag_num);
	}
	$routes = tep_transfer_get_routes($products_id);
	$output =  '<table width="100%">';
	$output.=  '<tr><td align="left" class="main" valign="top">接驳类型</td>
						<td align="left" class="main">'.tep_draw_pull_down_menu('transferType' , $transfer_type_options,$transfer_info_array['transferType'] , ' onchange="setTransferType(this.options[this.selectedIndex].value)"').'</td>
					</tr>';
	$output.= '<tr><td colspan="2" align="left" class="main">';
	for($routeIndex=1;$routeIndex<=2 ;$routeIndex++){
		$output.=  '<div id="transfer_route_'.$routeIndex.'" style="display:none"><span style="ont-family: Tahoma,SimSun,Arial,Helvetica,sans-serif;font-size:18px;color:#108BCD;font-weight:bold;font-style:italic">'.$routeIndex.'.</span>';
		$output.= '<b>起点</b>： '.tep_draw_pull_down_menu('pickup_id'.$routeIndex ,$locationsPulldown,$transfer_info_array['pickup_id'.$routeIndex] , ' id="PickupId'.$routeIndex.'" onchange="setTransferAddress(jQuery(this).val() ,\'PickupAddress'.$routeIndex.'\',\'PickupZipcode'.$routeIndex.'\');setLocationAvaliable(this,\'DropoffId'.$routeIndex.'\')"').'<br>';
		$output.= '<b>地址</b>： '.tep_draw_input_field("pickup_address".$routeIndex ,$transfer_info_array['pickup_address'.$routeIndex],' size="40" id="PickupAddress'.$routeIndex.'"'  ,'text',false).''.tep_draw_hidden_field("pickup_zipcode".$routeIndex ,$transfer_info_array['pickup_zipcode'.$routeIndex],' id="PickupZipcode'.$routeIndex.'"'  ).'<br/>';
		$output.= '<b>终点</b>：'.tep_draw_pull_down_menu('dropoff_id'.$routeIndex ,$locationsPulldown,$transfer_info_array['dropoff_id'.$routeIndex] , ' id="DropoffId'.$routeIndex.'" onchange="setTransferAddress(jQuery(this).val() ,\'DropoffAddress'.$routeIndex.'\',\'PickupZipcode'.$routeIndex.'\');setLocationAvaliable(this,\'PickupId'.$routeIndex.'\')"').'<br>';
		$output.= '<b>地址</b>：'.tep_draw_input_field("dropoff_address".$routeIndex ,$transfer_info_array['dropoff_address'.$routeIndex],' size="40"  id="DropoffAddress'.$routeIndex.'"' ,'text',false).'<br/>';
		$output.= '<b>航空公司名称/号码</b>：'.tep_draw_input_field("flight_number".$routeIndex ,$transfer_info_array['flight_number'.$routeIndex] ,' size="7"' ,'text',false).'&nbsp;&nbsp;';		
		$output.= '<br/><b>航班起飞/抵达时间</b>：'.tep_draw_input_field("flight_arrival_time".$routeIndex ,$transfer_info_array['flight_arrival_time'.$routeIndex]  ,'text',false)."(时间格式\"mm/dd/yyyy hh:mm AM\")";
		$output.='<br/><b>接应/送达详细地点</b>:'.tep_draw_input_field("flight_departure".$routeIndex ,$transfer_info_array['flight_departure'.$routeIndex],'','text',false);
		if(trim($transfer_info_array['flight_pick_time'.$routeIndex])!='') $fptstyle=' ';else $fptstyle=' style="display:none"';
		
		$output.= '<div id="fpt'.$routeIndex.'" '.$fptstyle.'><b>期待被接应时间</b>：'.tep_draw_input_field("flight_pick_time".$routeIndex ,$transfer_info_array['flight_pick_time'.$routeIndex]  ,'text',false)."(时间格式\"mm/dd/yyyy hh:mm AM\")</div>";
		
		$output.= '<div><b>人数</b>：'.tep_draw_pull_down_menu("guest_total".$routeIndex ,$guestPulldown,$transfer_info_array['guest_total'.$routeIndex] ,' id="GuestTotal'.$routeIndex.'" onchange="updateMaxBaggageTotal(\'GuestTotal'.$routeIndex.'\',\'BaggageTotal'.$routeIndex.'\')"').'人&nbsp;&nbsp; <b>行李</b>:'.tep_draw_pull_down_menu("baggage_total".$routeIndex ,$bagPulldown,$transfer_info_array['baggage_total'.$routeIndex] ,' id="BaggageTotal'.$routeIndex.'"  onchange="updateMaxBaggageTotal(\'GuestTotal'.$routeIndex.'\',\'BaggageTotal'.$routeIndex.'\')"');
		$output.= '</div><b>留言</b>：<br/>'.tep_draw_textarea_field("comment".$routeIndex, true, 50, 2,$transfer_info_array['comment'.$routeIndex],'',false);
		$output.= '</div>';

	}
	echo db_to_html($output);
	echo '<script language="javascript">setTransferType("'.$transfer_info_array['transferType'].'");updateMaxBaggageTotal(\'GuestTotal1\',\'BaggageTotal1\');updateMaxBaggageTotal(\'GuestTotal2\',\'BaggageTotal2\');</script>';
	echo '</td></tr>';

}

echo  '<tr>	<td align="left" colspan="2">&nbsp;</td></tr>';
//}

echo '<tr><td colspan="3" align="center" id="check_submit_edit">';
//结伴同游选项
if(TRAVEL_COMPANION_OFF_ON=='true'){
	echo '<input name="travel_comp" id="travel_comp" type="hidden" />';
}

//echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_hidden_field('full_products_id', $_GET['products_id']) . tep_template_image_submit('button_ok.gif', 'Update',' onmouseover="check_remaining_seats_edit()" onmouseout="clear_status()" id="check_edit_tour"');#onclick="return validate()
echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_hidden_field('full_products_id', $_GET['products_id']) . tep_template_image_submit('button_ok.gif', 'Update',' onmouseover="check_remaining_seats_edit()"  id="check_edit_tour"');#onclick="return validate()
echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');parent.ShowContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');  return true;"  href="javascript:parent.reload_ifr(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');parent.HideContent(\'edit_cart_product_data_'.$iframe_cart_edit_update.'\');parent.ShowContent(\'cart_product_data_'.$iframe_cart_edit_update.'\');">'. tep_template_image_button('button_cancel.gif', 'Cancel','','') . '</a>';
echo '</td></tr>';
echo '<tr id="div_display_notice_remaining_seats_edit" style="display:none">
							           <td width="1">&nbsp;</td>
							           <td nowrap="nowrap">&nbsp;</td>
							          <td class="buy_options_title"><b></b><div id="notice_remaining_seats_div_edit" class="sp1"></div></td>
							          </tr>';
echo '</table>';
 ?>

 			</td>
     		 </tr>
			</table>
</form>
<script type="text/javascript">

		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
			//parent.calcHeight_increase("iframe_prod_<?php echo (int)$product_info['products_id'];?>",50);
		}

		var valid = new Validation('cart_quantity', {immediate : true,useTitles:true, onFormValidate : formCallback});

						Validation.add('validate-select-custom-pickup', ' ', function(v) {
							//parent.calcHeight("iframe_prod_<?php echo (int)$product_info['products_id'];?>");
							parent.calcHeight_increase("iframe_prod_<?php echo$iframe_cart_edit_update;?>",50);
							return ((v != "none") && (v != "<?php echo TEXT_SELECT_NOTHING; ?>") && (v != '<?php echo TEXT_HEADING_NONE_AVAILABLE ?>') && (v != null) && (v != 'Please make a selection...') && (v.length != 0) && (v != "<?php echo TEXT_SELECT_PICKUP_REGION; ?>") && (v != 0));
						});
						Validation.add('validate-selection-blank-custom', '', function(v,elm){
							parent.calcHeight_increase("iframe_prod_<?php echo $iframe_cart_edit_update;?>",50);
							return ((v != '') && (v != null));
						});
				<!--
				<!--
				    // NOTE: customize variables in this javascript block as appropriate.					
				    var defaultAdults="2";
				    var cellStyle=" class='mainblue'";
				    var childHelp="Please provide the ages of children in each room. Children's ages should be their age at the time of travel.";
				    var adultHelp="";
				    var textRooms="<?php echo TEXT_ROOMS;?>";
				    var textAdults="<?php echo TEXT_ADULTS;?>";
				    var textChildren="<?php echo TEXT_CHILDREN;?>";
				    var textChildError="Please specify the ages of all children.";
				    var pad='';
				    // NOTE: Question marks ("?") get replaced with a numeric value
				    var textRoomX="<?php echo TEXT_ROOMS;?> ?:";
				    var textChildX="<?php echo TEXT_CHILDREN;?> ?:";

				//-->
</script>

<!-- NOTE: DO NOT MODIFY THIS JAVASCRIPT BLOCK -->
<script type="text/javascript">
				<!--
				    var adultsPerRoom=new Array(defaultAdults);
				    var childrenPerRoom=new Array();
				    var childAgesPerRoom=new Array();
					var numRooms=1;	//默认的房间数
					var maxChildren=0;

					<?php 
 //vincent add to parse default room person total number.{
 $adultsPerRoom = array();
 $childrenPerRoom = array();
 $bedType = array();
 $roomInfoArray = explode('###',$cartProductArray['roomattributes'][3]);
 $roomBedTypeArray = explode('###',$cartProductArray['roomattributes'][7]);
 $bedTypeIndex = 1 ;
 if($roomInfoArray[0]>0){
 	for($roomIndex=1, $maxRoomIndex = sizeof($roomInfoArray); $roomIndex<$maxRoomIndex; $roomIndex++){
 		if(strlen($roomInfoArray[$roomIndex])>2){
 			$person_array = explode('!!', $roomInfoArray[$roomIndex]); //$n_array[0]大人 $n_array[1]小孩
 			$adultsPerRoom[] = intval($person_array [0]);
 			$childrenPerRoom[] =  intval($person_array [1]);
 			if((intval($person_array [0]) + intval($person_array[1])) == 2) {
 				if(tep_not_null($roomBedTypeArray[$bedTypeIndex])){
 					$bedType[] = intval($roomBedTypeArray[$bedTypeIndex]);
 				}else{
 					$bedType[] = 0;
 				}
 				$bedTypeIndex++;
 			}else{
 				$bedType[] = 0 ;
 			}
 		}
 	}
 }
 echo 'adultsPerRoom= ['.implode(',',$adultsPerRoom).'];';
 echo 'childrenPerRoom= ['.implode(',',$childrenPerRoom).'];';
 echo 'var bedTypePerRoom =['.implode(',',$bedType).'];';
 //vincent add end }
					?>

					<?php
					/*
					请对我们目前房间最大人数做一个程序上的优化。
					对于一天以上有住宿的团，美国及加拿大团美国房间的最大人数限定为4人。也就是当客人选了一个房间时，那成人的下拉菜单，最多只显示到4。如果成人选择了4，那小孩的下拉菜单则为0；如果成人2，小孩下拉菜单可以有0，1，2的选择，确保可选的最多人说为4人。

					另外还房间最多人数还需要按照地区，国家做限制，目前具体为下：
					美国，加拿大，澳大利亚团：每个房间最多人数：4人。
					夏威夷，欧洲：3人。
					补充一点：夏威夷买二送二团每个房间最多人数为：4人。团号：733，727，728，731，732。

					//豪华补充，以上问题在后台设置maximum_no_of_guest的值即可
					*/
					$maxPerRoomPeopleNum = 4;
					$sql = tep_db_query('SELECT maximum_no_of_guest FROM '.TABLE_PRODUCTS.' WHERE products_id ="'.(int)$products_id.'" ');
					$rows = tep_db_fetch_array($sql);
					if((int)$rows['maximum_no_of_guest']){
						$maxPerRoomPeopleNum = (int)$rows['maximum_no_of_guest'];
					}
					?>
					<?php
					//删除没有价格的房间选项 start{
					$adult_options_filter = "";	//过滤成人房间选项
					$child_options_filter = "";	//过滤儿童选项
					$price_ckeck_sql = tep_db_query('SELECT products_id,agency_id,is_transfer,display_room_option,products_durations,products_single,products_double,products_triple,products_quadr,products_kids,is_cruises FROM '.TABLE_PRODUCTS.' WHERE products_id ="'.(int)$products_id.'" AND products_durations>1 AND products_durations_type="0" ');
					$price_ckeck_row = tep_db_fetch_array($price_ckeck_sql);
					if((int)$price_ckeck_row['products_id'] && $price_ckeck_row['is_cruises']!="1"){	//非油轮团才删除无价格的房间选项
						if($price_ckeck_row['products_single']<1){
							$adult_options_filter .= "\n
								for(j=(adult_options.length-1); j>=0; j--){
									if(adult_options[j].value==1){
										adult_select.remove(j);
									}
								}
							\n";
						}
						if($price_ckeck_row['products_double']<1){
							$adult_options_filter .=  "\n
								for(j=(adult_options.length-1); j>=0; j--){
									if(adult_options[j].value==2){
										adult_select.remove(j);
									}
								}
							\n";
						}
						if($price_ckeck_row['products_triple']<1){
							$adult_options_filter .=  "\n
								for(j=(adult_options.length-1); j>=0; j--){
									if(adult_options[j].value==3){
										adult_select.remove(j);
									}
								}
							\n";
						}
						if($price_ckeck_row['products_quadr']<1){
							$adult_options_filter .=  "\n
								for(j=(adult_options.length-1); j>=0; j--){
									if(adult_options[j].value==4){
										adult_select.remove(j);
									}
								}
							\n";
						}
						if($price_ckeck_row['products_kids']<1){
							$child_options_filter .=  "\n
								for(j=(child_options.length-1); j>=0; j--){
									child_select.remove(j);
								}
								child_options[0] = new Option(0,0);
							\n";
						}
					}
					//删除没有价格的房间选项 end}
					?>

					var maxPerRoomPeopleNum = <?=$maxPerRoomPeopleNum?>;	//该产品的每个房间最大人数
					// 去掉成人数和儿童数的多余的选项
					function sub_rooms_people_num(){
						var cart_quantity = document.getElementById("cart_quantity");
						if(cart_quantity==null){
							return false;
						}

						var numberOfRooms = cart_quantity.elements['numberOfRooms'];
						if(numberOfRooms!=null){
							var room_num = cart_quantity.elements['numberOfRooms'].value;
							for(i=0; i<room_num; i++){
								var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
								var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
								//删除4以后的选项 成人
								var adult_options = adult_select.options;
								for(var j=(adult_options.length-1); j>=maxPerRoomPeopleNum; j--){
									//alert(adult_options.length);
									adult_select.remove(j);
									//adult_options.selectedIndex = (adult_options.length-1);
								}
								/*重整最小人数*/
								if(typeof(min_num_guest)!='undefined'){
									for(var j=0; j<(min_num_guest-1); j++){
										adult_select.remove(0);
									}
								}
								
								//重新整理儿童的选项
								if(typeof(child_select.options) != 'undefined'){
								var child_options = child_select.options;
								for(var j=(child_options.length-1); j>=maxPerRoomPeopleNum; j--){
									child_select.remove(j);
								}
								}
								try{
								<?php
								echo $adult_options_filter;
								echo $child_options_filter;
								?>
								} catch (e){};
								if(room_num==16){
									//如果是结伴拼房则还要把1人的选项去掉
									if(adult_options[0].value=="1"){
										adult_select.remove(0);
									}
									break;
								}

							}
						}
					}


					//根据当前房间人数判断是否显示房型选择框的king大床等选项
					function set_bed_option(){
						var cart_quantity = document.getElementById("cart_quantity");
						if(cart_quantity==null){
							return false;
						}
						var numberOfRooms = cart_quantity.elements['numberOfRooms'];
						if(numberOfRooms!==undefined){
							var room_num = numberOfRooms.value;
							for(i=0; i<room_num; i++){
								var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
								var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
								var the_room_per_num = (Number(adult_select.value) + Number(child_select.value));
								//成人数+儿童数如果==2就显示大床选项，否则只保留标准型
								var bed_select = cart_quantity.elements['room-'+ i +'-bed'];
								if(bed_select===undefined){

								}else{
									var bed_selects = bed_select.options;
									var bed_value = bed_select.value;
									for(j=(bed_selects.length-1); j>=0; j--){
										bed_select.remove(j);
									}

									var options_array = new Array();
										options_array[0] = new Array(0,'<?php echo TEXT_BED_STANDARD;?>');
										options_array[1] = new Array(1,'<?php echo TEXT_BED_KING;?>');
										options_array[2] = new Array(2,'<?php echo TEXT_BED_QUEEN;?>');
									if(the_room_per_num==2 && room_num<16){
										for(n=0; n<options_array.length; n++){
											bed_select[n] = new Option(options_array[n][1], options_array[n][0]);
											if(bed_value==options_array[n][0]){
												bed_select.value = options_array[n][0];
											}
										}
									}else{
										bed_select[0] = new Option(options_array[0][1], options_array[0][0]);
									}
								}

								if(room_num==16){ break;}
							}
						}

					}

					//根据选择的成人数处理儿童选项是否需要减少选项
					function set_child_option(){
						var cart_quantity = document.getElementById("cart_quantity");
						if(cart_quantity==null){
							return false;
						}

						var numberOfRooms = cart_quantity.elements['numberOfRooms'];
						if(numberOfRooms!=null){
							var room_num = cart_quantity.elements['numberOfRooms'].value;
							for(i=0; i<room_num; i++){
								var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
								var child_select = cart_quantity.elements['room-'+ i +'-child-total'];								
								//成人数+儿童数 <=4

								//alert(adult_select.value);
								//重新整理儿童的选项(先清空选项再添加选项)								
								if(typeof(child_select.options) != 'undefined' ){
								var child_options = child_select.options;
								var child_value = child_select.value;
								for(j=(child_options.length-1); j>=0; j--){
									child_select.remove(j);
								}
								for(n=0; n<(maxPerRoomPeopleNum-adult_select.value)+1; n++){
									child_options[n] = new Option(n, n);
									if(child_value==n){
										child_select.value = n;
									}
								}
								}
								try{
								<?php
								echo $child_options_filter;
								?>
								} catch(e){};
								if(room_num==16){
									break;
								}
							}
						}
					}




					<?php
								if($product_info['display_room_option']==1){
									$ttl_rooms = get_total_room_from_str($cart->contents[$products_id]['roomattributes'][3]);
									if($ttl_rooms>0){

										echo ' setNumRooms('.$ttl_rooms.'); ';

										for($ir=0; $ir<$ttl_rooms; $ir++){

											$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($cart->contents[$products_id]['roomattributes'][3],$ir+1);


											echo ' setNumAdults('.$ir.','.$chaild_adult_no_arr[0].'); ';
											echo ' setNumChildren('.$ir.','.$chaild_adult_no_arr[1].'); ';


										}

									}


								}elseif(!tep_not_null($_GET['error_min_guest'])){

									$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($cart->contents[$products_id]['roomattributes'][3],1);
									echo ' setNumAdults(0,'.$chaild_adult_no_arr[0].'); ';
									echo ' setNumChildren(0,'.$chaild_adult_no_arr[1].'); ';


								}

								?>


				    refresh();




				    function setChildAge(room, child, age) {
				        if (childAgesPerRoom[room] == null) {
				            childAgesPerRoom[room] = new Array();
				        }
				        childAgesPerRoom[room][child] = age;
				    }

				    function setNumAdults(room, numAdults) {
				        adultsPerRoom[room] = numAdults;
						set_child_option();
						set_bed_option();
					}

				    function setNumChildren(room, numChildren) {
				        childrenPerRoom[room] = numChildren;
						set_bed_option();
				       // refresh();
				    }

				    function setNumRooms(x) {
				        numRooms = x;

						//parent.calcHeight("iframe_prod_<?php echo (int)$product_info['products_id'];?>");
						parent.calcHeight_increase("iframe_prod_<?php echo $iframe_cart_edit_update;?>",numRooms*10);
				        for (i = 0; i < x; i++) {
				            if (adultsPerRoom[i] == null) {
				                adultsPerRoom[i] = 2;
				            }
				            if (childrenPerRoom[i] == null) {
				                childrenPerRoom[i] = 0;
				            }
				        }
				        refresh();
						set_bed_option();
				    }




				    function renderRoomSelect() {
				        var x = '';
				        x += '<select  class="sel2" style="width:70px;"  name="numberOfRooms" onchange="setNumRooms(this.options[this.selectedIndex].value);">'; // id="numberOfRooms"
						for (var i = 1; i < 17; i++) {
							if(i==16){
								<?php
								//结伴同游选项
								if(TRAVEL_COMPANION_OFF_ON=='true'){
								?>
								x += '<option value="'+i+'"'+(numRooms == i ? ' selected' : '')+'>' + '<?php echo SHARE_ROOM_WITH_TRAVEL_COMPANION?>';
								<?php }?>
							}else{
								x += '<option value="'+i+'"'+(numRooms == i ? ' selected' : '')+'>' + i;
							}
						}
				        x += '</select>';
				        return x;
				    }




				    function refresh() {
				        maxChildren = 0;
				        for (var i = 0; i < numRooms; i++) {
				            if (childrenPerRoom[i] > maxChildren) {
				                maxChildren = childrenPerRoom[i];
				            }
							if(numRooms==16){
								break;
							}
				        }

				        var x = '';
				        if(typeof(adultHelp)!="undefined"){
							if (adultHelp.length > 0) {
				            	x = adultHelp + "<p>\n";
				       	 	}
						}

				        if (numRooms > 17) {
				            x += textRooms;
				            x += renderRoomSelect();

				        } else {
				            x += '<table border="0" cellspacing="2" cellpadding="0">\n';
							x += '<tr><td'+cellStyle+'>';
							<?php if($product_info['display_room_option']==1)
								{
							 ?>
				            x += textRooms+pad;
							<?php
								}
							 ?>
							 x += '</td>';
				            if (numRooms > 1 && numRooms < 16 ) {
				                x += '<td'+cellStyle+'>&nbsp;</td>';
				            }

							var title_bed_td = '';
							var min_num_guest = 1 ;
							<?php
							 //海鸥团：增加选择床型这些选项。
							 if($product_info['agency_id']=="2"){
							?>
								var options_array = new Array();
									options_array[0] = new Array(0,'<?php echo TEXT_BED_STANDARD;?>');
									options_array[1] = new Array(1,'<?php echo TEXT_BED_KING;?>');
									options_array[2] = new Array(2,'<?php echo TEXT_BED_QUEEN;?>');
								title_bed_td = '<td'+cellStyle+'><nobr>'+ '<?= db_to_html('床型');?>' +'</nobr></td>';

							<?php
							 }
							?>

				            x += '<td'+cellStyle+'><nobr>'+textAdults+pad+'</nobr></td><td'+cellStyle+'><nobr>'+textChildren+pad+'</nobr></td>'+ title_bed_td +'</tr>\n';
				            for (var i = 0; i < numRooms; i++) {
				                x += '<tr><td'+cellStyle+'>';
				                if (i == 0) {
									<?php if($product_info['display_room_option']==1)
							{
									 ?>
				                    x += renderRoomSelect();
									<?php
							}
									?>
				                } else {
				                    x += '&nbsp;';
				                }
				                x += '</td>';
				                if (numRooms > 1 && numRooms < 16 ) {
				                    x += '<td'+cellStyle+'><nobr>'+getValueChinese(textRoomX, i+1)+pad + '</nobr></td>';
				                }
				                
				                x += '<td'+cellStyle+'>';
				                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value)', 1, 20, adultsPerRoom[i]);
				                x += '</td><td'+cellStyle+'>';					              
				                <?php if($product_info['products_kids'] < 1 && $price_ckeck_row['is_cruises']!="1") { ////fix vincent ,修正当产品的小孩价格设置为0 禁止选择小孩数量?>		
				                x += '<select disabled ><option value="0">0</option></select><input type="hidden" name="'+'room-' + i + '-child-total'+'" value="0" />'	 ;    
				                <?php }else{?>           
				                x += buildSelect('room-' + i + '-child-total', 'setNumChildren(' + i + ', this.options[this.selectedIndex].value)', 0, 10, childrenPerRoom[i]);		
				                <?php }?>		    		
				                x += '</td>';

								if(title_bed_td!=''){	//床型选择框
									var max_n = 1;
									var sel_n = 0;
									if( (Number(adultsPerRoom[i])+Number(childrenPerRoom[i])) == 2 ){ max_n = options_array.length; sel_n = 1;}
									if(cart_quantity.elements['room-'+ i +'-bed']===undefined){
									}else{
										sel_n = cart_quantity.elements['room-'+ i +'-bed'].value;
									}

									sel_n = bedTypePerRoom[i];
									x += '<td'+cellStyle+'>'+ buildStrSelect('room-' + i + '-bed', '', options_array, max_n , sel_n) +'</td>';
								}

								x += '</tr>\n';

								var travel_comp = document.getElementById("travel_comp");
								if(travel_comp!=null){
									travel_comp.value = '0';
								}
								if(numRooms==16){
									if(travel_comp!=null){
										travel_comp.value = '1';
									}
									break;
								}
							}
				            x += '</table>\n';

							<?php
									//单人配房选项 start {
									if($product_info['products_single_pu'] > 0){
										$checkbox_checked = '';
										$agree_single_occupancy_pair_up = $cart->contents[$HTTP_GET_VARS['products_id']]['roomattributes'][6];
										if((int)$agree_single_occupancy_pair_up){
											$checkbox_checked = ' checked ';
										}
							?>
							x += '<div id="div_agree_single_occupancy_pair_up" style="padding-left:89px;"><label><input name="agree_single_occupancy_pair_up" type="checkbox" id="agree_single_occupancy_pair_up" value="1" <?=$checkbox_checked?>> <?php echo db_to_html('接受单人部分配房');?></label> <!--<a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span><?php echo TEXT_TOUR_SINGLE_PU_OCC_TIPS;?></span></a>--></div>\n';
							<?php
}
//单人配房选项 end }
							?>


				         var didHeader = false;
				            for (var i = 0; i < numRooms; i++) {
							    if (childrenPerRoom[i] > 50000) {
				                    if (!didHeader) {
				                        x += '<table width="100" border="0" cellpadding="0" cellspacing="2">\n';
				                        x += '<tr><td'+cellStyle+' colspan="'+(maxChildren+1)+'">';
				                        x += '<br>';
				                        x += childHelp;
				                        x += '<br>';
				                        x += '</td></tr>\n<tr><td'+cellStyle+'>&nbsp;</td>';
				                        for (var j = 0; j < maxChildren; j++) {
				                            x += '<td'+cellStyle+'><nobr>'+getValue(textChildX, j+1)+pad+'</nobr></td>\n';
				                        }
				                        didHeader = true;
				                    }
				                    x += '</tr>\n<tr><td'+cellStyle+'><nobr>'+getValue(textRoomX, i+1)+pad+'</nobr></td>';
				                    for (var j = 0; j < childrenPerRoom[i]; j++) {
				                        x += '<td'+cellStyle+'>';
				                        var def = -1;
				                        if (childAgesPerRoom[i] != null) {
				                            if (childAgesPerRoom[i][j] != null) {
				                                def = childAgesPerRoom[i][j];
				                            }
				                        }
				                        x += '<select  class="sel2"  name="room-'+i+'-child-'+j+'-age" onchange="setChildAge('+i+', '+j+', this.options[this.selectedIndex].value);">';
				                        x += '<option value="-1"'+(def == -1 ? ' selected' : '')+'>-?-';
				                        x += '<option value="0"'+(def == 0 ? ' selected' : '')+'>&lt;1';
				                        for (var k = 1; k <= 18; k++) {
				                            x += '<option value="'+k+'"'+(def == k ? ' selected' : '')+'>'+k;
				                        }
				                        x += '</td>';
				                    }
				                    if (childrenPerRoom[i] < maxChildren) {
				                        for (var j = childrenPerRoom[i]; j < maxChildren; j++) {
				                            x += '<td'+cellStyle+'>&nbsp;</td>';
				                        }
				                    }
				                    x += '</tr>\n';

				                }

								if(numRooms==16){
									break;
								}
				            }
				            if (didHeader) {
				                x += '</table>\n';
				            }
				        }

						//alert(x);
				        if( document.getElementById("hot-search-params"))document.getElementById("hot-search-params").innerHTML = x;
						//自动去掉多余的opction选项。
						sub_rooms_people_num();
						set_child_option();
				    }
					  function buildSelect(name, onchange, min, max, selected) {
				        var x = '<select  class="sel2"  name="' + name + '"'; //   id="' + name + '"
				        if (onchange != null) {
				            x += ' onchange="' + onchange + '"';
				        }
				        x +='>\n';
				        for (var i = min; i <= max; i++) {
				            x += '<option value="' + i + '"';
				            if (i == selected) {
				                x += ' selected';
				            }

				            x += '>' + i + '\n';
				        }
				        x += '</select>';
				        return x;
				    }

					function buildStrSelect(name, onchange, option_array, max_n, selected) {
						var option_array = option_array;
						var x = '<select class="sel2" name="' + name + '"';
						if (onchange != null) {
							x += ' onchange="' + onchange + '"';
						}
						x +='>\n';


						for (var i = 0; i < max_n; i++) {
							x += '<option value="' + option_array[i][0] + '"';
							if (option_array[i][0] == selected) {
								x += ' selected';
							}

							x += '>' + option_array[i][1] + '\n';
						}

						x += '</select>';
						return x;
					}

				    function validateGuests(form) {
				        if (numRooms < 18) {
				            var missingAge = false;
				            for (var i = 0; i < numRooms; i++) {
				                var numChildren = childrenPerRoom[i];
				                if (numChildren != null && numChildren > 0) {
				                    for (var j = 0; j < numChildren; j++) {
				                        if (childAgesPerRoom[i] == null || childAgesPerRoom[i][j] == null || childAgesPerRoom[i][j] == -1) {
				                            missingAge = true;
				                        }
				                    }
				                }
				            }

				            if (missingAge) {
				                alert(textChildError);
				                return false;
				            } else {
				                return true;
				            }
				        } else {
				            return true;
				        }
				    }

				    function submitGuestInfoForm(form) {
				        if (!validateGuests(form)) {
				            return false;
				        }

				        return true;
				    }

				    function getValue(str, val) {
				        return str.replace(/\?/g, val);
				    }

					function getValueChinese(str, val) {
						<?php
						for($i=1; $i<=15; $i++){
							if(defined('HEDING_TEXT_ROOM_NUMBER_'.$i)){
								echo '
							  if(parseInt(val) == '.$i.'){
								  str = "'.constant('HEDING_TEXT_ROOM_NUMBER_'.$i).'";
							  }'."\n";
							}
						}
						?>
						<?php
						/*
						if(parseInt(val) == 1){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_1;?>";
						}else if(parseInt(val) == 2){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_2;?>";
						}else if(parseInt(val) == 3){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_3;?>";
						}else if(parseInt(val) == 4){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_4;?>";
						}else if(parseInt(val) == 5){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_5;?>";
						}else if(parseInt(val) == 6){
						str = "<?=HEDING_TEXT_ROOM_NUMBER_6;?>";
						}
						*/?>
				        return str.replace(/\?/g, val);
				    }



				//-->
</script>




<?php
if($departuredate_true != "in"){
?>
<script language="javascript">
function validate(){
	/*if(document.cart_quantity.availabletourdate.value==""){
	alert("<?php echo TEXT_SELECT_VALID_DEPARTURE_DATE;?>")
	return false
	}*/
	return true
}
</script>
<?php
}
?>
<?php
$user_browser = browser_detection('browser');
if($att_cnt>0 && $user_browser=='mozilla'){
	?>
	<script language="javascript">
	parent.calcHeight_increase("iframe_prod_<?php echo $iframe_cart_edit_update;?>",<?php echo (int)$att_cnt*20;?>);
	</script>
	<?php
}
	?>

	<script language="javascript">
	<?php
	if (!tep_session_is_registered('customer_id')) {
		$tmp_dateattributes_val = $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][2];
	}else{
		$tmp_dateattributes_val = db_to_html($cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][2]);
	}
	if($product_info['products_type'] == 2){
		?>
		try{
			//document.forms[0]._1_H_hotel3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";
			//document.forms[0]._1_H_hot3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";


			var selectpickup = '<select  class="sel2"  name="_1_H_hot3">';

			selectpickup = selectpickup + '<option value="<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>"><?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?></option>';

			selectpickup = selectpickup + "</select>";
			document.getElementById("pickuptimediv").innerHTML=selectpickup;

			document.forms[0]._1_H_hot3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";
			document.forms[0]._1_H_address.value= "<?php  echo $tmp_dateattributes_val;?>";
			document.getElementById("divpickuplocation").innerHTML = "<?php echo $tmp_dateattributes_val;?>";
		}catch(e){}
		<?php
	}else{

		?>
		try{

			//document.forms[0]._1_H_hotel3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";
			//document.forms[0]._1_H_hot3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";
			document.forms[0]._1_H_hot3.value= "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?>";
			document.forms[0]._1_H_address.value= "<?php  echo $tmp_dateattributes_val;?>";
			document.getElementById("fulladdress").innerHTML = "<?php echo $cart->contents[$HTTP_GET_VARS['products_id']]['dateattributes'][1];?> <?php echo $tmp_dateattributes_val;?>";
		}catch(e){}
		<?php } ?>
		<?php if($_GET['update_frame'] == 'yes'){?>
		//alert('<?php echo $_GET['products_id'];?>');
		//document.forms[0].submit();
		document.cart_quantity.submit();
		<?php } ?>
	</script>
<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
		ajax = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>
<SCRIPT language=javascript><!--

function clearStart(obj){
	obj.setAttribute('look','');
}

    //var can_submit_check_remaining_seats_edit = true;
    function check_remaining_seats_edit(_objbtn){
	<?php 
//酒店和接送服务不需要座位检查
if($product_info['is_hotel'] == '1' || $product_info['is_transfer'] == '1'){
	echo "return  ;";
}
	?>
	var formobj = jQuery('#cart_quantity');
	var lock = formobj.attr('lock');
	if (lock != '' && lock != undefined) {
		return;
	} else {
		formobj.attr('lock','true');
	}
    var form_obj = document.getElementById("cart_quantity");
    var msg_check_submit = document.getElementById("check_submit_edit");
	if(typeof(form_obj.elements["numberOfRooms"]) == 'undefined') return ;
	var room_num = form_obj.elements["numberOfRooms"];
	var total_adult =0;
	for(var i=0;i<room_num.value;i++){
		total_adult=total_adult+Number(form_obj.elements['room-' + i + '-adult-total'].value);

	}
	for(var j=0;j<room_num.value;j++){
		total_adult=total_adult+Number(form_obj.elements['room-' + j + '-child-total'].value);

	}
	var products_id = form_obj.elements["products_id"].value;
	var departure_time = document.getElementById("availabletourdate").value;
	departure_time=departure_time.split("::");
	var departure_date=departure_time[0];
	if(departure_date!=''){
	var url ="check_neworder_remaining_seats_edit.php?total_adult="+total_adult+"&products_id="+products_id+"&departure_date="+departure_date;
	ajax.open('GET',url,true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4 && ajax.status == 200){
			if(ajax.responseText.search(/alert/)!=-1){
				var error_msn = "<?php echo db_to_html('该团剩余座位小于订购人数,请选其它日期的团');?>";
		    	document.getElementById("notice_remaining_seats_div_edit").innerHTML ="<b>"+error_msn+"</b>";
	    		document.getElementById("div_display_notice_remaining_seats_edit").style.display="";
	    		msg_check_submit.innerHTML = ajax.responseText;
			}else{
				document.getElementById("div_display_notice_remaining_seats_edit").style.display="none";
				msg_check_submit.innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send(null);
    }
}

/* 团购团默认为的出发日期为最后一天 */
function to_end_availabletourdate(){
	indexN = jQuery('#availabletourdate option:last').attr("index");
	jQuery('#availabletourdate').get(0).options[indexN].selected = true;
}

function to_first_availabletourdate(){
	if(jQuery('#availabletourdate').get(0).options[0].value!=""){
		jQuery('#availabletourdate').get(0).options[0].selected = true;
	}else{
		jQuery('#availabletourdate').get(0).options[1].selected = true;
	}
}

//解决_1_H_hotel2、_1_H_hotel3和_1_H_hot3的传值问题(参照产品1517)
jQuery(document).ready(function($){
	$("select[name='_1_H_hotel2']").change(function(){
		$("input[name='_1_H_hot3']").val( $("select[name='_1_H_hotel3']").text() );	//_1_H_hotel3 _1_H_hot3
	});
});
//--></script>