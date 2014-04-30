<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

$content = 'budget_calculation';
require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

//取得所有属于邮轮团的产品属性ID
$cruisesOptionIds = getAllCruisesOptionIds();
?>

<?php	

if(isset($HTTP_POST_VARS['aryFormData']))
{
	  $aryFormData = $HTTP_POST_VARS['aryFormData'];
		foreach ($aryFormData as $key => $value )
		{
			  if(is_array($value)){
			  foreach ($value as $key2 => $value2 )
			  {
				$value2 = iconv('utf-8',CHARSET.'//IGNORE',$value2);
				$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));    				
			  }
		  }
		}
	if(isset($HTTP_GET_VARS['action_calculate_price']) && $HTTP_GET_VARS['action_calculate_price']=='true')
	  { //for ajax purpose
			if (isset($products_id) && is_numeric($products_id)) 
			{
								$product_is_transfer = tep_check_product_is_transfer($products_id) ;
								$is_start_date_required=is_tour_start_date_required($products_id);//Fixed for passes/cards -- 
								/*
								$is_las_vegas_show = check_is_las_vegas_show($products_id);
								
								if($is_las_vegas_show==true || $is_start_date_required==false){
									$price_breakdown_desc = TXT_PRICE_BREAKDOWN_DESC_SHOW;
								}else{
									$price_breakdown_desc = TXT_PRICE_BREAKDOWN_DESC;
								}
								
								if(tep_check_product_is_hotel((int)$products_id)==1){
									$price_breakdown_desc = TXT_PRICE_BREAKDOWN_DESC_HOTEL;
								}*/
								if($HTTP_POST_VARS['availabletourdate'] == ""){
									$HTTP_POST_VARS['availabletourdate'] = $HTTP_POST_VARS['first_availabletourdate'];
								}
								if($HTTP_POST_VARS['availabletourdate'] != ""){
									//if not blank than check it greater than current date
									$tmp_dp_date = substr($HTTP_POST_VARS['availabletourdate'],0,10);
									$currect_sys_date = date('Y-m-d');									
									if(@tep_get_compareDates($tmp_dp_date,$currect_sys_date) == "invalid" ){
									//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_departure_date=true'));
									echo '<script type="text/javascript">alert("'.db_to_html('出发时间无效').'")</script>';
									//echo 'invalid date!!!';
									exit();
									}
								}
								
								if(isset($HTTP_POST_VARS['priority_mail_ticket_needed_date']) && $HTTP_POST_VARS['priority_mail_ticket_needed_date'] != ''){
									$current_sys_date = date('Y-m-d');
									$valid_ticket_needed_date = strtotime($current_sys_date) + (7*24*60*60);
									$priority_mail_db_date = tep_get_date_db($HTTP_POST_VARS['priority_mail_ticket_needed_date']);
									if((strtotime($priority_mail_db_date) < $valid_ticket_needed_date) || (strtotime($priority_mail_db_date) > strtotime($tmp_dp_date)) ){
										echo '<script type="text/javascript">alert("'.ERROR_CHECK_PRIORITY_MAIL_DATE.'")</script>'; //ERROR_CHECK_PRIORITY_MAIL_DATE = 所选日期为无效邮递日期
										exit();
									}
								}
								//hotel-extension 检查入住日期设置{
							if(tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date']))$HTTP_POST_VARS['early_hotel_checkin_date'] = substr($HTTP_POST_VARS['early_hotel_checkin_date'],0,10);
							if(tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']))$HTTP_POST_VARS['late_hotel_checkout_date'] = substr($HTTP_POST_VARS['late_hotel_checkout_date'],0,10);
							if(tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date'])){
									$temp_late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
									$temp_late_hotel_checkin_date = strtotime($temp_late_hotel_checkin_date_split[2].'-'.$temp_late_hotel_checkin_date_split[0].'-'.$temp_late_hotel_checkin_date_split[1]);
									$temp_late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date']);
									$temp_late_hotel_checkout_date = strtotime($temp_late_hotel_checkout_date_split[2].'-'.$temp_late_hotel_checkout_date_split[0].'-'.$temp_late_hotel_checkout_date_split[1]);
									if($temp_late_hotel_checkin_date > $temp_late_hotel_checkout_date){
										//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkout_date=true'));
										//echo $HTTP_POST_VARS['late_hotel_checkin_date'].'-'.$HTTP_POST_VARS['late_hotel_checkout_date'];
										echo '<script type="text/javascript">alert("'.db_to_html('延后离开的酒店退房日期不正确!请检查').'")</script>';//ERROR_CHECK_HOTEL_EXT_CHECKOUT_DATE;
										exit();										
									}
								}
								/* vincent fix 如果未设置chekout_date 认为用户不愿意进行酒店延住
								else if(tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date'])){ 
									$temp_late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date']);
									$temp_late_hotel_checkin_date = strtotime($temp_late_hotel_checkin_date_split[2].'-'.$temp_late_hotel_checkin_date_split[0].'-'.$temp_late_hotel_checkin_date_split[1]);
									$temp_late_hotel_checkout_date = $temp_late_hotel_checkin_date + (24*60*60);//vincent未设置退房日期默认延住一天
									$HTTP_POST_VARS['late_hotel_checkout_date'] = date("m/d/Y", $temp_late_hotel_checkout_date);
								}*/
								//echo $HTTP_POST_VARS['early_hotel_checkin_date'];
								if(tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])){
									//$temp_early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
									$temp_early_hotel_checkin_date_split = explode("-", $HTTP_POST_VARS['early_hotel_checkin_date']);
									$temp_early_hotel_checkin_date = strtotime($temp_early_hotel_checkin_date_split[2].'-'.$temp_early_hotel_checkin_date_split[0].'-'.$temp_early_hotel_checkin_date_split[1]);
									//$temp_early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
									$temp_early_hotel_checkout_date_split = explode("-", $HTTP_POST_VARS['early_hotel_checkout_date']);
									$temp_early_hotel_checkout_date = strtotime($temp_early_hotel_checkout_date_split[2].'-'.$temp_early_hotel_checkout_date_split[0].'-'.$temp_early_hotel_checkout_date_split[1]);
									if($HTTP_POST_VARS['early_hotel_checkout_date']==''){
										$temp_early_hotel_checkout_date = $temp_early_hotel_checkin_date + (24*60*60);
										$HTTP_POST_VARS['early_hotel_checkout_date'] = date("m/d/Y", $temp_early_hotel_checkout_date);
									}
									if($temp_early_hotel_checkin_date > $temp_early_hotel_checkout_date){
										//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_he_checkout_date=true'));
										//echo ERROR_CHECK_HOTEL_EXT_CHECKIN_DATE;
										echo '<script type="text/javascript">alert("'.db_to_html('提前到达的酒店入住日期不正确!请检查').'")</script>';
										exit();										
									}
								}
								//}								
								//transfer-service 检查数据有效性{
								if(is_numeric($HTTP_POST_VARS['transfer_products_id']) && tep_check_product_is_transfer((int)$HTTP_POST_VARS['transfer_products_id']) == '1' && (is_numeric($HTTP_POST_VARS['PickupId1'])||is_numeric($HTTP_POST_VARS['PickupId2']))){
									$msg = tep_transfer_validate((int)$HTTP_POST_VARS['transfer_products_id'],$HTTP_POST_VARS);
									if($msg != ''){
										echo '<script type="text/javascript">popAlert("'.db_to_html('对不起！<br/>'.$msg).'")</script>';
										exit;
									}
								}//}
								/* t4f座位检查
								//nirav added for remaining seats---start
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
									echo '<span class="validation-advice">'.TEXT_NO_ENOUGH_DATE.'</span>';exit;
								}
								//nirav added for remaining seats---end
								*/
								//amit added to serversite validation for departure date
								//amit modified to make sure price on usd
								$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$products_id);
								//amit modified to make sure price on usd		
								if($HTTP_POST_VARS['_1_H_hot3'] != "")
								{
									$HTTP_POST_VARS['departurelocation'] =  tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot3']).'::::'.tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot2']).' '.tep_db_prepare_input($HTTP_POST_VARS['_1_H_address']);
								}
								$product_hotel_price = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "' ");
								$product_hotel_result = tep_db_fetch_array($product_hotel_price);
								
								if(tep_check_product_is_hotel((int)$products_id)==0){
								/*// Howard added new group buy start {
								$group_buy_specials_sql = tep_db_query('SELECT * FROM `specials` WHERE products_id ="'.(int) $HTTP_POST_VARS['products_id'].'" AND status="1" AND start_date <="'.date('Y-m-d H:i:s').'" AND expires_date >"'.date('Y-m-d H:i:s').'" ');
								$group_buy_specials = tep_db_fetch_array($group_buy_specials_sql);
								if((int)$group_buy_specials['products_id']){
									if($group_buy_specials['specials_type'] =="1"){
										$tmpPurchasedNum = tep_get_product_orders_guest_count($group_buy_specials['products_id'],$group_buy_specials['start_date'],$group_buy_specials['expires_date']);
										$tmpBalanceNum = max(0,(int)($group_buy_specials['specials_max_buy_num']-$tmpPurchasedNum));
									}									
									//0为普通特价，1为限量团购，2为限时团购
									if(($group_buy_specials['specials_type'] =="1" && (int)$tmpBalanceNum) || $group_buy_specials['specials_type']=="2" || $group_buy_specials['specials_type']=="0"){
										if((int)$group_buy_specials['specials_new_products_single']){
											$product_hotel_result['products_single'] = $group_buy_specials['specials_new_products_single'];
										}
										if((int)$group_buy_specials['specials_new_products_single_pu']){
											$product_hotel_result['products_single_pu'] = $group_buy_specials['specials_new_products_single_pu'];
										}
										if((int)$group_buy_specials['specials_new_products_double']){
											$product_hotel_result['products_double'] = $group_buy_specials['specials_new_products_double'];
										}
										if((int)$group_buy_specials['specials_new_products_triple']){
											$product_hotel_result['products_triple'] = $group_buy_specials['specials_new_products_triple'];
										}
										if((int)$group_buy_specials['specials_new_products_quadr']){
											$product_hotel_result['products_quadr'] = $group_buy_specials['specials_new_products_quadr'];
										}
										if((int)$group_buy_specials['specials_new_products_kids']){
											$product_hotel_result['products_kids'] = $group_buy_specials['specials_new_products_kids'];
										}
									}
								}
								// Howard added new group buy end }	*/	
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
								}
								//amit modified to make sure price on usd
								 //howard added for single pair up{
								 $single_price_tmp = $product_hotel_result['products_single'];
								 if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$product_hotel_result['products_single_pu']){
								 	$single_price_tmp = $product_hotel_result['products_single_pu'];
								 }//}
								 $a_price[1] = $single_price_tmp; //single or single pair up
								 $a_price[2] = $product_hotel_result['products_double']; //double
								 $a_price[3] = $product_hotel_result['products_triple']; //triple
								 $a_price[4] = $product_hotel_result['products_quadr']; //quadr
								 $e = $product_hotel_result['products_kids']; //child Kid								 
								
								$get_reg_date_price_array = explode('!!!',$HTTP_POST_VARS['availabletourdate']);								
								$HTTP_POST_VARS['availabletourdate'] = $get_reg_date_price_array[0];	
								
								/* Price Displaying - standard price for different reg/irreg sections - start */ 
								 
								$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and products_id ='".(int)$products_id."' ");
								if(tep_db_num_rows($check_standard_price_dates)>0){
									$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
									$operate_start_date = $row_standard_price_dates['operate_start_date'];
									$operate_end_date = $row_standard_price_dates['operate_end_date'];
								}else{
									$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and products_id ='".(int)$products_id."' ");
									$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
									$operate_start_date = $row_standard_price_dates1['operate_start_date'];
									$operate_end_date = $row_standard_price_dates1['operate_end_date'];
								}
								
								$check_section_standard_price_query = "select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where operate_start_date ='".$operate_start_date."' and operate_end_date = '".$operate_end_date."' and products_id ='".(int)$products_id."' and products_single > 0 ";
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
									}
									 $a_price[1] = $row_section_standard_price['products_single']; //single
									 if((int)$HTTP_POST_VARS['agree_single_occupancy_pair_up'] && (int)$row_section_standard_price['products_single_pu']){
										$a_price[1] = $row_section_standard_price['products_single_pu'];
									 }
									 $a_price[2] = $row_section_standard_price['products_double']; //double
									 $a_price[3] = $row_section_standard_price['products_triple']; //triple
									 $a_price[4] = $row_section_standard_price['products_quadr']; //quadr
									 $e = $row_section_standard_price['products_kids'];									 
								}
								 
								/* Price Displaying - standard price for different reg/irreg sections - end */
								
								 //amit added to check if special price is available for specific date start {
								$special_dt_chk = 0;
								$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0) and products_id ='".(int)$products_id."' ";
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
								 	$special_dt_chk = 1;
								}
								//amit added to check if special price is available specific date end }
								//amit added to check if regular special price available start{							
								
								if($special_dt_chk == 0 && $get_reg_date_price_array[1] != ''){
									$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$products_id."' ";
									$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
									while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
										if($check_reg_specip_pride_date_row['extra_charge'] > 0){
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
										}
										$special_dt_chk = 1;
									}								
								}
								//amit added to check if regular special price avalilable end }
								}else{									
									$a_price[1] = 0; //single
									$a_price[2] = 0; //double
									$a_price[3] = 0; //triple
									$a_price[4] = 0; //quadr
									$e = 0; //child Kid	
								}
								
								//hotel-extension {
								/* Hotel Extensions - Early/Late check-in/out - start */
								if((isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])) || (isset($HTTP_POST_VARS['late_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date']) && isset($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['staying_late_hotels'])) ){
								$hotel_extension_price[1] = 0;
								$hotel_extension_price[2] = 0;
								$hotel_extension_price[3] = 0;
								$hotel_extension_price[4] = 0;
								$hotel_extension_price[0] = 0;
								
								$early_hotel_extension_price[1] = 0;
								$early_hotel_extension_price[2] = 0;
								$early_hotel_extension_price[3] = 0;
								$early_hotel_extension_price[4] = 0;
								$early_hotel_extension_price[0] = 0;
								
								$late_hotel_extension_price[1] = 0;
								$late_hotel_extension_price[2] = 0;
								$late_hotel_extension_price[3] = 0;
								$late_hotel_extension_price[4] = 0;
								$late_hotel_extension_price[0] = 0;
								
								if(isset($HTTP_POST_VARS['early_hotel_checkout_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date']) && isset($HTTP_POST_VARS['early_hotel_checkin_date']) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'])  && tep_not_null($HTTP_POST_VARS['early_arrival_hotels'])){
										$early_hotel_id = $HTTP_POST_VARS['early_arrival_hotels'];
										 $tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$early_hotel_id);
										//get default standard prices of hotel - start
										$hotel_price_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$early_hotel_id . "' ");
										$hotel_result = tep_db_fetch_array($hotel_price_query);
										//amit modified to make sure price on usd
										if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
											$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
											$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
											$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
											$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
											$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
										}
										//amit modified to make sure price on usd
										//get default standard prices of hotel - end	
										//$early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date']);
										$early_hotel_checkin_date_split = explode("-", $HTTP_POST_VARS['early_hotel_checkin_date']);
										$early_hotel_checkin_date = $early_hotel_checkin_date_split[2].'-'.$early_hotel_checkin_date_split[0].'-'.$early_hotel_checkin_date_split[1];
										//$early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date']);
										$early_hotel_checkout_date_split = explode("-", $HTTP_POST_VARS['early_hotel_checkout_date']);
										$early_hotel_checkout_date = $early_hotel_checkout_date_split[2].'-'.$early_hotel_checkout_date_split[0].'-'.$early_hotel_checkout_date_split[1];
										
										$early_total_dates_price = array();
										
										$loop_start = strtotime($HTTP_POST_VARS['early_hotel_checkin_date']);//strtotime($early_hotel_checkin_date);
										$loop_end = strtotime($HTTP_POST_VARS['early_hotel_checkout_date']) - (24*60*60);//strtotime($early_hotel_checkout_date) - (24*60*60);
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
											$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
											$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
											$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
											$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
											$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
											
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
													}else{
													$hotel_price[1] = $hotel_price[1] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[2] = $hotel_price[2] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[3] = $hotel_price[3] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[4] = $hotel_price[4] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[0] = $hotel_price[0] + $check_reg_specip_pride_date_row['extra_charge'];
													//$early_hotel_price_extra .= '(Special price '.date("D", $d).' + $'.$check_reg_specip_pride_date_row['extra_charge'].')';
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
													//$early_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' - $'.$check_specip_pride_date_row['extra_charge'].')';
													}else{
													$hotel_price[1] = $hotel_price[1] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[2] = $hotel_price[2] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[3] = $hotel_price[3] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[4] = $hotel_price[4] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[0] = $hotel_price[0] + $check_specip_pride_date_row['extra_charge'];													
													//$early_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' + $'.$check_specip_pride_date_row['extra_charge'].')';
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
													//$early_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' $'.$hotel_price[1].')';
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
										//echo $early_arrival_date.' '.$hotel_price[2];
										//echo '<br>';
										$total_early_stay_days++;
										$early_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[2], '1'=>$hotel_price[1], '2'=>(2*$hotel_price[2]), '3'=>(3*$hotel_price[3]), '4'=>(4*$hotel_price[4]), '0'=>$hotel_price[0]);
										
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
									//arsort($early_total_dates_price);
									//print_r($early_total_dates_price);
									
									
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
											
											$hotel_price[1] = 0;
											$hotel_price[2] = 0;
											$hotel_price[3] = 0;
											$hotel_price[4] = 0;
											$hotel_price[0] = 0;
											
											$hotel_price[1] = tep_get_tour_price_in_usd($hotel_result['products_single'],$tour_agency_opr_currency);
											$hotel_price[2] = tep_get_tour_price_in_usd($hotel_result['products_double'],$tour_agency_opr_currency);
											$hotel_price[3] = tep_get_tour_price_in_usd($hotel_result['products_triple'],$tour_agency_opr_currency);
											$hotel_price[4] = tep_get_tour_price_in_usd($hotel_result['products_quadr'],$tour_agency_opr_currency);
											$hotel_price[0] = tep_get_tour_price_in_usd($hotel_result['products_kids'],$tour_agency_opr_currency);
											
											//get sections standard price
											$hotel_standard_prices = tep_db_query("select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id ='".(int)$late_hotel_id."' and date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' ");
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
											}
											//get sections standard price
											
											$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where date_format(str_to_date(operate_start_date, '%m-%d-%Y'), '%Y-%m-%d') <= '".$late_arrival_date."' and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= '".$late_arrival_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and available_date = '' and products_id ='".(int)$late_hotel_id."' ";
											$check_reg_specip_pride_date_query = tep_db_query($check_reg_specip_pride_date_select);
											if(tep_db_num_rows($check_reg_specip_pride_date_query)>0){
											while($check_reg_specip_pride_date_row = tep_db_fetch_array($check_reg_specip_pride_date_query)){
												if($check_reg_specip_pride_date_row['extra_charge'] > 0 && $check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1) ){
													if($check_reg_specip_pride_date_row['prefix'] == '-'){
													$hotel_price[1] = $hotel_price[1] - $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[2] = $hotel_price[2] - $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[3] = $hotel_price[3] - $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[4] = $hotel_price[4] - $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[0] = $hotel_price[0] - $check_reg_specip_pride_date_row['extra_charge'];
													//$late_hotel_price_extra .= '(Special price '.date("D", $d).' - $'.$check_reg_specip_pride_date_row['extra_charge'].')';
													}else{
													$hotel_price[1] = $hotel_price[1] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[2] = $hotel_price[2] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[3] = $hotel_price[3] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[4] = $hotel_price[4] + $check_reg_specip_pride_date_row['extra_charge'];
													$hotel_price[0] = $hotel_price[0] + $check_reg_specip_pride_date_row['extra_charge'];
													//$late_hotel_price_extra .= '(Special price '.date("D", $d).' + $'.$check_reg_specip_pride_date_row['extra_charge'].')';
													}
													//$date_price_cost = $check_reg_specip_pride_date_row['extra_charge_cost'];
												}else if($check_reg_specip_pride_date_row['products_start_day'] == (date("w", strtotime($late_arrival_date))+1)){			
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
														//$late_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' $'.$hotel_price[1].')';
														
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
													//$late_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' - $'.$check_specip_pride_date_row['extra_charge'].')';
													}else{
													$hotel_price[1] = $hotel_price[1] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[2] = $hotel_price[2] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[3] = $hotel_price[3] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[4] = $hotel_price[4] + $check_specip_pride_date_row['extra_charge'];
													$hotel_price[0] = $hotel_price[0] + $check_specip_pride_date_row['extra_charge'];
													//$late_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' + $'.$check_specip_pride_date_row['extra_charge'].')';
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
													//$late_hotel_price_extra .= '(Special price '.date("m/d/Y", $d).' $'.$hotel_price[1].')';
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
											//echo $late_arrival_date.' '.$hotel_price;
											//echo '<br>';
											$total_late_stay_days++;
											$late_total_dates_price[] = array('id'=>date("m/d/y", $d), 'text'=>$hotel_price[2], '1'=>$hotel_price[1], '2'=>(2*$hotel_price[2]), '3'=>(3*$hotel_price[3]), '4'=>(4*$hotel_price[4]), '0'=>$hotel_price[0]);
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
										//arsort($late_total_dates_price);
										//print_r($late_total_dates_price);
								//$hotel_extension_price[1] = $total_hotel_extension_price = ($early_hotel_extension_price[1]*(int)$HTTP_POST_VARS['early_hotel_rooms']) + ($late_hotel_extension_price[1]*(int)$HTTP_POST_VARS['late_hotel_rooms']);
								/*
								$total_early_hotel_extension_price = $early_hotel_extension_price[1]*(int)$HTTP_POST_VARS['early_hotel_rooms'];
								$total_late_hotel_extension_price = $late_hotel_extension_price[1]*(int)$HTTP_POST_VARS['late_hotel_rooms'];
								$total_hotel_extension_price = $hotel_extension_price[1] = $total_early_hotel_extension_price + $total_late_hotel_extension_price;
								*/
								$hotel_extension_price[1] = $early_hotel_extension_price[1] + $late_hotel_extension_price[1];
								$hotel_extension_price[2] = $early_hotel_extension_price[2] + $late_hotel_extension_price[2];
								$hotel_extension_price[3] = $early_hotel_extension_price[3] + $late_hotel_extension_price[3];
								$hotel_extension_price[4] = $early_hotel_extension_price[4] + $late_hotel_extension_price[4];
								$hotel_extension_price[0] = $early_hotel_extension_price[0] + $late_hotel_extension_price[0];								
								}
								/* Hotel Extensions - Early/Late check-in/out - end */
								//}hotel-extension
								 
								 $total_no_guest_tour = 0;// Total no of guest for entering names 总人数
							
							if($HTTP_POST_VARS['numberOfRooms'])//if there is rooms 
							{
								/*****************************************/
								$validation_no = $product_hotel_result['maximum_no_of_guest'];				
								/*****************************************/
								$errormessageperson = "";
								
								if($language == 'tchinese' || $language == 'schinese' ){
									$total_info_room = "</br>".TEXT_TOTAL_OF_ROOMS."".$HTTP_POST_VARS['numberOfRooms'];
								}else{
									$total_info_room = "</br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$HTTP_POST_VARS['numberOfRooms'];
								}
								$total_room_adult_child_info = $HTTP_POST_VARS['numberOfRooms']."###";
								//$room_price_old 是原价
								$room_price_old = 0;
								//hotel-extension{
								$early_price_breakdown_roomtype = "";
								$late_price_breakdown_roomtype = "";
								//}
								$msn_text_array = array(); //提示信息数组
								for($i=0;$i<$HTTP_POST_VARS['numberOfRooms'];$i++)
								{
									$is_buy_two_get_one = check_buy_two_get_one($products_id, ($HTTP_POST_VARS['room-'.$i.'-adult-total']+$HTTP_POST_VARS['room-'.$i.'-child-total']), $tmp_dp_date);
										/*******************************************************************************************************/
										/****************************BOC: this is validation for the first room ********************************/
										if($HTTP_POST_VARS['room-'.$i.'-adult-total']!='')
										{
											/****************total number of person in room *****************/
											$totalinroom[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total'];
											if($HTTP_POST_VARS['room-'.$i.'-child-total']!='')
											{
												$totalinroom[$i] += $HTTP_POST_VARS['room-'.$i.'-child-total'];
											}
											/****************total number of person in room *****************/
											
											/****************validation for more than 4 person *****************/
											if($totalinroom[$i] > $validation_no)
											{
												$errormessageperson =  $validation_no;
											}
											else 
											{
												/****************total price for room no 1 *****************/
												/****************1个房间总价 *****************/												
												if($HTTP_POST_VARS['room-'.$i.'-child-total'] == '0')
												{
													$room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$a_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
													$he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
													$early_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$early_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
													$early_price_breakdown_roomtype .= '+'.$HTTP_POST_VARS['room-'.$i.'-adult-total'];
													$late_he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total']*$late_hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total']];
													$late_price_breakdown_roomtype .= '+'.$HTTP_POST_VARS['room-'.$i.'-adult-total'];
													//双人一房折扣团
													$room_price_old += $room_price[$i];
													if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='2' ){
														$double_room_pre = double_room_preferences($products_id,$tmp_dp_date);
														if((int)$double_room_pre){
															$msn_text_array[] = '双人一房折扣';
															$room_price[$i] = 2*($a_price[2]-$double_room_pre);
														}
													}else{													
														//买二送1价 无小孩
														if( (int)$is_buy_two_get_one){
															if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='3' ){
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																	$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
																	$msn_text_array[] = '买2送1';
																}
															}															
															if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='4' ){
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																	$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格4人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																	$msn_text_array[] = '买2送1';
																}																
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
																	$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));//买二送二
																	$msn_text_array[] = '买2送2';
																}
															}
														}
													}
												}
												elseif($HTTP_POST_VARS['room-'.$i.'-child-total'] != '0')
												{
													if($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '1' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '1')
													{
														$room_price[$i] = 2*$a_price[2];
														$room_price_old += $room_price[$i];
														$early_he_room_price[$i] = 2*$early_hotel_extension_price[2];//hotel-extension	
														$early_price_breakdown_roomtype .= '+2';
														$late_he_room_price[$i] = 2*$late_hotel_extension_price[2];
														$late_price_breakdown_roomtype .= '+2';														
														$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[2];//房间$i大人平均价
														$_SESSION['child_average'][$i][(int)$products_id] = $a_price[2];//房间$i小孩平均价														
														//双人一房折扣团 大人小孩同一价
														$double_room_pre = double_room_preferences($products_id,$tmp_dp_date);
														if((int)$double_room_pre){
															$msn_text_array[] = '双人一房折扣';
															$room_price[$i] = 2*($a_price[2]-$double_room_pre);															
															$_SESSION['adult_average'][$i][(int)$products_id] = $room_price[$i]/2;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $room_price[$i]/2;//房间$i小孩平均价
														}
													
													}
													elseif($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '1' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '2')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														$room_price_old += $room_price[$i];
														//hotel-extension {
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0];
														$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3];
														$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);
														if($early_he_room_price[$i] == $early_he_room_price_option1[$i]){
															$early_price_breakdown_roomtype .= '+2+0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option2[$i]){
															$early_price_breakdown_roomtype .= '+3';
														}
														
														$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + $late_hotel_extension_price[0];
														$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3];
														$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);														
														if($late_he_room_price[$i] == $late_he_room_price_option1[$i]){
															$late_price_breakdown_roomtype .= '+2+0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option2[$i]){
															$late_price_breakdown_roomtype .= '+3';
														}
														// }
														
														if($room_price_option1[$i] <= $room_price_option2[$i] ){
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = ($a_price[2]+$e)/2;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $a_price[3];//房间$i小孩平均价
														}
														
														//买二送1价 1大人 2小孩
														if( (int)$is_buy_two_get_one){															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
																$msn_text_array[] = '买2送1';
															}
															$tmp_var = number_format(($room_price[$i]/3),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $tmp_var;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价
														}
													}
													elseif($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '1' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '3')
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
														if($early_he_room_price[$i] == $early_he_room_price_option1[$i]){
															$early_price_breakdown_roomtype .= '+2+2*0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option2[$i]){
															$early_price_breakdown_roomtype .= '+3+0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option3[$i]){
															$early_price_breakdown_roomtype .= '+4';
														}
														
														$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0];
														$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
														$late_he_room_price_option3[$i] = 4*$late_hotel_extension_price[4];
														$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i],$late_he_room_price_option3[$i]);														
														
														if($late_he_room_price[$i] == $late_he_room_price_option1[$i]){
															$late_price_breakdown_roomtype .= '+2+2*0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option2[$i]){
															$late_price_breakdown_roomtype .= '+3+0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option3[$i]){
															$late_price_breakdown_roomtype .= '+4';
														}
														//}hotel-extension														
														if($room_price_option1[$i] == $room_price[$i]){	
															$tmp_var = number_format( (($a_price[2]+2*$e)/3),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价
														}
														if($room_price_option2[$i] == $room_price[$i]){	
															$tmp_var = number_format( ((2*$a_price[3]+$e)/3),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价
														}
														if($room_price_option3[$i] == $room_price[$i]){	
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[4];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $a_price[4];//房间$i小孩平均价
														}
														
														//买二送1价 1大人 3小孩
														if( (int)$is_buy_two_get_one){															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																$msn_text_array[] = '买2送1';
															}															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));//买二送二
																$msn_text_array[] = '买2送2';
															}
															$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $tmp_var;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价
														}														
													}
													elseif($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '2' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '1')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														$room_price_old += $room_price[$i];														
														//hotel-extension {
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														$early_he_room_price_option1[$i] = (2*$early_hotel_extension_price[2]) + $early_hotel_extension_price[0];
														$early_he_room_price_option2[$i] = 3*$early_hotel_extension_price[3];
														$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);
														if($early_he_room_price[$i] == $early_he_room_price_option1[$i]){
															$early_price_breakdown_roomtype .= '+2+0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option2[$i]){
															$early_price_breakdown_roomtype .= '+3';
														}
														
														$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + $late_hotel_extension_price[0];
														$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3];
														$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);						
														if($late_he_room_price[$i] == $late_he_room_price_option1[$i]){
															$late_price_breakdown_roomtype .= '+2+0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option2[$i]){
															$late_price_breakdown_roomtype .= '+3';
														}								
														//}hotel-extension
														
														if($room_price_option1[$i] <= $room_price_option2[$i]){
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $e;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $a_price[3];//房间$i小孩平均价
														}

														//买二送1价 2大人 1小孩
														if( (int)$is_buy_two_get_one){															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
																$msn_text_array[] = '买2送1';
															}
															$tmp_var = number_format( ($room_price[$i]/3),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $tmp_var;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价														
														}													
													}
													elseif($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '2' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '2')
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
														
														if($early_he_room_price[$i] == $early_he_room_price_option1[$i]){
															$early_price_breakdown_roomtype .= '+2+2*0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option2[$i]){
															$early_price_breakdown_roomtype .= '+3+0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option3[$i]){
															$early_price_breakdown_roomtype .= '+4';
														}
														
														$late_he_room_price_option1[$i] = (2*$late_hotel_extension_price[2]) + 2*$late_hotel_extension_price[0];
														$late_he_room_price_option2[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
														$late_he_room_price_option3[$i] = 4*$late_hotel_extension_price[4];
														$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i],$late_he_room_price_option3[$i]);
														
														if($late_he_room_price[$i] == $late_he_room_price_option1[$i]){
															$late_price_breakdown_roomtype .= '+2+2*0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option2[$i]){
															$late_price_breakdown_roomtype .= '+3+0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option3[$i]){
															$late_price_breakdown_roomtype .= '+4';
														}//}											
														
														if($room_price_option1[$i] == $room_price[$i]){	
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $e;//房间$i小孩平均价
														}
														if($room_price_option2[$i] == $room_price[$i]){	
															$tmp_var = number_format( ($a_price[3]+$e)/2,2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = ($a_price[3]+$e)/2;//房间$i小孩平均价
														}
														if($room_price_option3[$i] == $room_price[$i]){	
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[4];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $a_price[4];//房间$i小孩平均价
														}

														//买二送1价 2大人 2小孩
														if( (int)$is_buy_two_get_one){															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																$msn_text_array[] = '买2送1';
															}															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));//买二送二
																$msn_text_array[] = '买2送2';
															}
															$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $tmp_var;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价
														}													
													}
													elseif($HTTP_POST_VARS['room-'.$i.'-adult-total'] == '3' && $HTTP_POST_VARS['room-'.$i.'-child-total'] == '1')
													{
														$room_price_option1[$i] = 3*$a_price[3]+$e;
														$room_price_option2[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														$room_price_old += $room_price[$i];
														//hotel-extension
														$he_room_price_option1[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
														$he_room_price_option2[$i] = 4*$hotel_extension_price[4];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														$early_he_room_price_option1[$i] = 3*$early_hotel_extension_price[3]+$early_hotel_extension_price[0];
														$early_he_room_price_option2[$i] = 4*$early_hotel_extension_price[4];
														$early_he_room_price[$i] = min($early_he_room_price_option1[$i],$early_he_room_price_option2[$i]);
														if($early_he_room_price[$i] == $early_he_room_price_option1[$i]){
															$early_price_breakdown_roomtype .= '+3+0';
														}else if($early_he_room_price[$i] == $early_he_room_price_option2[$i]){
															$early_price_breakdown_roomtype .= '+4';
														}
														
														$late_he_room_price_option1[$i] = 3*$late_hotel_extension_price[3]+$late_hotel_extension_price[0];
														$late_he_room_price_option2[$i] = 4*$late_hotel_extension_price[4];
														$late_he_room_price[$i] = min($late_he_room_price_option1[$i],$late_he_room_price_option2[$i]);
														if($late_he_room_price[$i] == $late_he_room_price_option1[$i]){
															$late_price_breakdown_roomtype .= '+3+0';
														}else if($late_he_room_price[$i] == $late_he_room_price_option2[$i]){
															$late_price_breakdown_roomtype .= '+4';
														}
														//}
														
														if($room_price_option1[$i] <= $room_price_option2[$i]){
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $e;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][(int)$products_id] = $a_price[4];//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $a_price[4];//房间$i小孩平均价
														}
														
														//买二送1价 3大人 1小孩
														if( (int)$is_buy_two_get_one){															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge($products_id);//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																$msn_text_array[] = '买2送1';
															}															
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge($products_id));//买二送二
																$msn_text_array[] = '买2送2';
															}															
															$tmp_var = number_format( ($room_price[$i]/4),2,'.','');
															$_SESSION['adult_average'][$i][(int)$products_id] = $tmp_var;//房间$i大人平均价
															$_SESSION['child_average'][$i][(int)$products_id] = $tmp_var;//房间$i小孩平均价

														}												
													}
												}
												/****************total price for room no 1 *****************/
												$roomno = $i+1;
												if($language == 'tchinese' || $language == 'schinese' ){
													//language
													$total_info_room .= "<br>".tep_get_total_of_adult_in_room($roomno)." ".$HTTP_POST_VARS['room-'.$i.'-adult-total'];
													if($HTTP_POST_VARS['room-'.$i.'-child-total']!='0'){
															$total_info_room .= "<br>".tep_get_total_of_children_in_room($roomno)." ".$HTTP_POST_VARS['room-'.$i.'-child-total'];
													}
													$total_info_room .= "<br>".tep_get_total_of_room($roomno)." $".number_format($room_price[$i],2);
													$total_no_guest_tour = $total_no_guest_tour+$HTTP_POST_VARS['room-'.$i.'-adult-total']+$HTTP_POST_VARS['room-'.$i.'-child-total'];
													$total_room_adult_child_info .= $HTTP_POST_VARS['room-'.$i.'-adult-total'].'!!'.$HTTP_POST_VARS['room-'.$i.'-child-total'].'###';											
												}else{ //default language
														$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$HTTP_POST_VARS['room-'.$i.'-adult-total'];
														if($HTTP_POST_VARS['room-'.$i.'-child-total']!='0'){
															$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$HTTP_POST_VARS['room-'.$i.'-child-total'];
														}
														$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($room_price[$i],2);
														$total_no_guest_tour = $total_no_guest_tour+$HTTP_POST_VARS['room-'.$i.'-adult-total']+$HTTP_POST_VARS['room-'.$i.'-child-total'];
														$total_room_adult_child_info .= $HTTP_POST_VARS['room-'.$i.'-adult-total'].'!!'.$HTTP_POST_VARS['room-'.$i.'-child-total'].'###';
												}
												/*t4f
												if($room_price[$i] > 0){
												$room_wise_price_breakdown_str=$HTTP_POST_VARS['room-'.$i.'-adult-total']." ".($HTTP_POST_VARS['room-'.$i.'-adult-total']=='1' ? TXT_ADULT_TICKET : TXT_ADULT_TICKETS);
												if($HTTP_POST_VARS['room-'.$i.'-child-total']!='0'){
													$room_wise_price_breakdown_str.=" + ".$HTTP_POST_VARS['room-'.$i.'-child-total']." ".($HTTP_POST_VARS['room-'.$i.'-child-total']=='1' ? TXT_CHILDREN_TICKET : TXT_CHILDREN_TICKETS);
												}
												$room_wise_price_breakdown.= str_replace('?:', ' ', TXT_ROOM_X).($i+1)." : ".$room_wise_price_breakdown_str." = ".$currencies->format($room_price[$i], true, 'USD')." <br />";
												}*/
											}	
										}
										/****************************EOC: this is validation for the first room ********************************/
										/*******************************************************************************************************/
										/*******************************************************************************************************/
								}
								if($errormessageperson != '')
								{
									//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id.'&maxnumber='.$errormessageperson.''));
									echo TEXT_MAX_ALLOW_ROOM . $errormessageperson;
									exit();
								}else{
									//howard fixed 2010-08-10 start
									//vincent 2011-7 add hotel-extension 
									$total_room_price = 0;$total_room_price_cost = 0;
									$total_hotel_extension_price=0;$total_early_hotel_extension_price=0;$total_late_hotel_extension_price=0 ;
									for($n = 0; $n<count($room_price); $n++)$total_room_price += $room_price[$n];									
									for($n = 0; $n<count($room_price_cost); $n++)$total_room_price_cost += $room_price_cost[$n];
									for($n = 0; $n<count($he_room_price); $n++)$total_hotel_extension_price += $he_room_price[$n];
									for($n = 0; $n<count($early_he_room_price); $n++)$total_early_hotel_extension_price += $early_he_room_price[$n];
									for($n = 0; $n<count($late_he_room_price); $n++)$total_late_hotel_extension_price += $late_he_room_price[$n];
									//howard fixed 2010-08-10 end								
									//hotel-extension {
									if($total_early_hotel_extension_price>0){
										$previous_value = 0;
										$previous_day_price_str = "";
										$count = 0;
										for($p=0; $p<=sizeof($early_total_dates_price); $p++){
											
											$get_early_roomtypes_array = explode("+", $early_price_breakdown_roomtype);
											$particular_day_price = 0;
											$particular_day_price_str = "";
											
											for($ert=1; $ert<sizeof($get_early_roomtypes_array); $ert++){
												if($get_early_roomtypes_array[$ert] == '2*0'){
													$particular_day_price = $particular_day_price + (2*$early_total_dates_price[$p]['0']);											
												}else{
												$particular_day_price = $particular_day_price + $early_total_dates_price[$p][$get_early_roomtypes_array[$ert]];
												}
												$particular_day_price_str .= "$".$early_total_dates_price[$p][$get_early_roomtypes_array[$ert]]."+";
											}
											//echo $particular_day_price;
											
											//$early_total_dates_price[$p]['text'] = $particular_day_price;
											
											if($particular_day_price != $previous_value){
												if($p!=0){
													if($previous_date!=$previous_key){
														$early_hotel_price_extra .= '-'.$previous_key;													
													}
													$early_hotel_price_extra .= ':<br /> &nbsp; $'.$previous_value.(sizeof($get_early_roomtypes_array)>2 ? ' ('.substr($previous_day_price_str,0,-1).')' : '').' @ '.$count.' '.TXT_NIGHTS.' = $'.number_format(($previous_value*$count), 2).'<br />';												
													$count = 0;
												}
												$previous_date = $early_total_dates_price[$p]['id'];
												
												$early_hotel_price_extra .= ' &nbsp; '.$previous_date;
												
											}
											$count++;
											$previous_value = $particular_day_price;
											$previous_day_price_str = $particular_day_price_str;
											$previous_key = $early_total_dates_price[$p]['id'];
										}
										
										}//end if early hotel ext price > 0
										
										//echo $late_price_breakdown_roomtype;
										if($total_late_hotel_extension_price>0){
										$previous_value = 0;
										$previous_day_price_str = "";
										$count = 0;
										for($p=0; $p<=sizeof($late_total_dates_price); $p++){											
											$get_late_roomtypes_array = explode("+", $late_price_breakdown_roomtype);
											$particular_day_price = 0;
											$particular_day_price_str = "";											
											for($ert=1; $ert<sizeof($get_late_roomtypes_array); $ert++){
												if($get_late_roomtypes_array[$ert] == '2*0'){
													$particular_day_price = $particular_day_price + (2*$late_total_dates_price[$p]['0']);											
												}else{
													$particular_day_price = $particular_day_price + $late_total_dates_price[$p][$get_late_roomtypes_array[$ert]];
												}
												$particular_day_price_str .= "$".$late_total_dates_price[$p][$get_late_roomtypes_array[$ert]]."+";
											}
											//echo $particular_day_price;											
											//$late_total_dates_price[$p]['text'] = $particular_day_price;											
											if($particular_day_price != $previous_value){
												if($p!=0){
													if($previous_date!=$previous_key){
														$late_hotel_price_extra .= '-'.$previous_key;													
													}
													$late_hotel_price_extra .= ':<br /> &nbsp; $'.$previous_value.(sizeof($get_late_roomtypes_array)>2 ? ' ('.substr($previous_day_price_str,0,-1).')' : '').' @ '.$count.' '.TXT_NIGHTS.' = $'.number_format(($previous_value*$count), 2).'<br />';												
													$count = 0;
												}
												$previous_date = $late_total_dates_price[$p]['id'];												
												$late_hotel_price_extra .= ' &nbsp; '.$previous_date;												
											}
											$count++;
											$previous_value = $particular_day_price;
											$previous_day_price_str = $particular_day_price_str;
											$previous_key = $late_total_dates_price[$p]['id'];
										}										
									}//end if late hotel ext price > 0
								//} hotel-extension
									
									if(strlen($HTTP_POST_VARS['availabletourdate'])>14){
										$date_attribute_price = substr($HTTP_POST_VARS['availabletourdate'],13,-1);
										if(substr($date_attribute_price,0,1)=='-'){
											$dateprifix = '-';
										}else{
											$dateprifix = '+';
										}
										$dateprice = explode('## ',$date_attribute_price);
										if(!is_int(substr($dateprice[1],0,1))){
											$datePrice_cost = substr($dateprice[1],1);
										}else{
											$datePrice_cost = $dateprice[1];
										}
								
									   if ($dateprifix == '+'){
										  $total_room_price += $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
										  if($room_price_old>0){
											$room_price_old += $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
										  }										  
									   }else{
										  $total_room_price -= $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
										  if($room_price_old>0){
											$room_price_old -= $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
										  }
									   }
								 	}								
									//amit added to add extra 3% if agency is L&L Travel start
									$pro_agency_info_array = tep_get_tour_agency_information((int)$products_id);
									if($pro_agency_info_array['final_transaction_fee'] > 0){								
										$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);
										if($room_price_old>0){
											$room_price_old = tep_get_total_fares_includes_agency($room_price_old,$pro_agency_info_array['final_transaction_fee']);
										}			
									}								
									//amit added to add extra 3% if agency is L&L Travel end
								
									//howard added if total_no_guest_tour < min_num_guest display error
									if($product_is_transfer != '1'){
									$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.$products_id.'" limit 1');
									$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
									if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
										//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id.'&minnumber='.$check_min_guest_row['min_num_guest'].''));
										echo TEXT_PRODUCTS_MIN_GUEST.$check_min_guest_row['min_num_guest'];
										exit();	
									}
									}
									//howard added if total_no_guest_tour < min_num_guest display error end								
								
									$attribute_total = 0;	//产品属性价格
									foreach($aryFormData as $k=>$v){
										if (eregi('--leftbrack', $k)) {				
											$k = str_replace('--leftbrack','[',$k);
											$k = str_replace('rightbrack--',']',$k);				
										}
										if(substr($k,0,2)=='id'){
											//$v是具体产品选项值的ID
											$option_id = explode('[',$k);
											$option_name = tep_get_product_option_name_from_optionid((int)$option_id[1]);
											if(tep_check_product_is_hotel((int)$products_id)==1 && preg_match("/???x?裨绮皖??e/i", strtolower($option_name)) || preg_match("/请选择早餐类别/i", strtolower($option_name)) ){
												if($total_early_hotel_extension_price > 0){
													$hotel_stay_days = $total_early_stay_days;
												}else if($total_late_hotel_extension_price > 0){
													$hotel_stay_days = $total_late_stay_days;
												}
												$the_attribute_price = (attributes_price_display($products_id,$option_id[1],$v) * $hotel_stay_days);
											}else{
												$the_attribute_price = attributes_price_display($products_id,$option_id[1],$v);
											}
											$attribute_total += $the_attribute_price;
											/*if((int)$the_attribute_price >0 ){	//限制另外收费产品属性中标明的人数不能大于参团总人数，因为可能导致BOSS亏本。属性值名称格式如：2人接送 10:30pm-12:00am
												$option_guest_num = tep_db_get_field_value('products_options_values_name', 'products_options_values', 'products_options_values_id="'.(int)$v.'"');
												if(preg_match('/^\d+人/', preg_replace('/[[:space:]]+/','',$option_guest_num))){												
													$option_guest_num = (int)$option_guest_num;
													if((int)$total_no_guest_tour && ($total_no_guest_tour < $option_guest_num)){
														$error_msg = db_to_html('参团总人数'.$total_no_guest_tour.' 与'.$option_name.'人数'.$option_guest_num.'不匹配！');
														echo '<script>jQuery("#option_guest_num_error_msg").text("'.$error_msg.'").show();</script>';
														exit();
													}
												}
											}
											echo '<script>jQuery("#option_guest_num_error_msg").hide();</script>';*/
										}
									}
									if($attribute_total > 0){
										$room_wise_price_breakdown.="+ ".TXT_ATTRIBUTE_PRICE." = ".$currencies->format($attribute_total, true, 'USD')." <br />";
									}
									//echo $total_room_price+$attribute_total;									
									//echo $currencies->display_price($total_room_price+$attribute_total,2);
								$fianl_tour_total_price = $total_room_price + $attribute_total;
								
								//featured group deal discount - start
								$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products_id."' and departure_restriction_date <= '".$tmp_dp_date."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
								if(check_is_featured_deal($products_id) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){									
									$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($products_id);
									$total_featured_guests = $total_featured_guests+$total_no_guest_tour;
									$get_featured_deal_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$products_id."' and peple_no <= '".$total_featured_guests."' order by peple_no desc limit 1");
									if(tep_db_num_rows($get_featured_deal_discount_data)>0){
										$featured_deal_discounts_data = tep_db_fetch_array($get_featured_deal_discount_data);
										$featured_deal_discount_ratio = $featured_deal_discounts_data['discount_percent']/100;																		
									}else{
										$get_featured_special_price = tep_db_fetch_array($check_featured_dept_restriction);
										//$featured_deal_discount_ratio = number_format(100 - (($get_featured_special_price['featured_deals_new_products_price'] / $product_hotel_result['products_price']) * 100))/100;									
										if((int)$get_featured_special_price['featured_deals_new_products_price']){
											$featured_deal_discount_ratio = number_format((100 * ($product_hotel_result['products_price'] - $get_featured_special_price['featured_deals_new_products_price']))/($product_hotel_result['products_price'] * $product_hotel_result['products_margin']/100))/100;
										}else{
											$featured_deal_discount_ratio = 0;
										}
									}
									$featured_tour_gp = $product_hotel_result['products_margin']/100;
									$group_discount = round((($fianl_tour_total_price * $featured_tour_gp)*$featured_deal_discount_ratio),2);
									$fianl_tour_total_price = $fianl_tour_total_price - $group_discount;
									//$room_wise_price_breakdown.="- ".TXT_FEATURED_DEAL_DISCOUNT." = ".$currencies->format($group_discount, true, 'USD')." <br/>";
									$msn_text_array[] = '团购(FD)';
									
								}else{
									//普通团购 group buy start
									$is_long_trour = true;
									if(GROUP_BUY_ON==true && $total_no_guest_tour >= GROUP_MIN_GUEST_NUM && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true)){	//团购
										$discount_percentage = auto_get_group_buy_discount($products_id);
										$room_price_old = max($total_room_price,$room_price_old);
										$fianl_tour_total_price = $fianl_tour_total_price - round($fianl_tour_total_price * $discount_percentage, DECIMAL_DIGITS);
										$msn_text_array[] = '团购';
									}
									//普通团购 group buy end
								}
								//featured group deal discount - end
								
								//hotel-extension 酒店延住部分的计算信息{
								if($total_hotel_extension_price > 0){
									//$room_wise_price_breakdown.="+ ".TXT_HOTEL_EXTENSION." = ".$currencies->format($total_hotel_extension_price, true, 'USD')." <br />";
									$room_wise_price_breakdown .= '<br />+ '.TXT_HOTEL_EXTENSION.': <br />';
									if($total_early_hotel_extension_price > 0){
										//".$HTTP_POST_VARS['early_hotel_rooms']." Room(s) 
										$room_wise_price_breakdown .= TXT_EARLY_ARRIVAL.": ".TXT_FOR." ".$total_early_stay_days." ".TXT_NIGHTS." = $".number_format($total_early_hotel_extension_price,2)."<br />" . ($early_hotel_price_extra!='' ? $early_hotel_price_extra."<br />" : ""); // @ $".number_format($early_hotel_basic_price,2)."
									}
									if($total_late_hotel_extension_price > 0){										
										//".$HTTP_POST_VARS['late_hotel_rooms']." Room(s) 
										$room_wise_price_breakdown .= TXT_LATE_DEPARTURE.": ".TXT_FOR." ".$total_late_stay_days." ".TXT_NIGHTS." = $".number_format($total_late_hotel_extension_price,2)." <br />" . ($late_hotel_price_extra!='' ? $late_hotel_price_extra."<br />" : ""); // @ $".number_format($late_hotel_basic_price,2)."
									}									
								}
								//echo $room_wise_price_breakdown;
								
								//}

								//amit added for new design start

								//显示买二送一、双人间优惠的省钱信息
								$msn_text_array[] = '优惠后';
								$msn_text_array=array_unique($msn_text_array);
								$msn_text = implode(' ',$msn_text_array);
								$sheng_do_shao = 0;
								if($room_price_old > 0 && ($room_price_old > ($fianl_tour_total_price-$attribute_total) )){
									$sheng_do_shao = (($room_price_old+$attribute_total)-$fianl_tour_total_price);
									if($sheng_do_shao>0){
										$msn_b = '<span>原价：$'.($room_price_old+$attribute_total).'&nbsp;&nbsp;'.$msn_text.'即省<b>$'.$sheng_do_shao.'</b></span>';
										echo db_to_html($msn_b);
										echo "<script language='javascript'>
										document.getElementById('currecy_discount_usd').innerHTML = '".$currencies->format($sheng_do_shao, true, 'USD')."';
										document.getElementById('currecy_discount_cny').innerHTML = '".$currencies->format($sheng_do_shao, true, 'CNY')."';
										jQuery('#discount_info').fadeIn('fast');
										</script>";
									}
								}
								if($sheng_do_shao == 0){
									echo "<script language='javascript'>
									document.getElementById('currecy_discount_usd').innerHTML = '$0.00';
									document.getElementById('currecy_discount_cny').innerHTML = '￥0.00';
									jQuery('#discount_info').hide();
									</script>";
								}
								//+酒店延住的价格
								$final_hotel_extension_price = $total_early_hotel_extension_price+$total_late_hotel_extension_price;
								$fianl_tour_total_price = $fianl_tour_total_price+$final_hotel_extension_price;

								//transfer-service  接送服务价格计算{		
								$transfer_price = 0;
								if(tep_not_null($HTTP_POST_VARS['transfer_products_id']) &&  tep_check_product_is_transfer($HTTP_POST_VARS['transfer_products_id'])){
									$transfer_price = tep_transfer_calculation_price(intval($HTTP_POST_VARS['transfer_products_id']) ,$HTTP_POST_VARS); 
									if(!is_numeric($transfer_price)){
										echo db_to_html('<span>'.$transfer_price.'，不能预订请检查您的路线设置！</span>');
										exit();
									}	
									$fianl_tour_total_price += $transfer_price;
								}
								//}
								
								$attribute_total = 0;
								//如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
								$hasSelDeck = false;
								$isCruises = false;
								$final_cruises_each_room_prices = false;
								$fianl_cruises_room_total_price = 0;
								$fianl_cruises_tax_total_price = 0;
								
								require_once(DIR_FS_FUNCTIONS . 'cruises_functions.php');
								$cruises_id = getProductsCruisesId((int)$products_id);
								if((int)$cruises_id){
									$cruisesData = getProductsCruisesInfos($cruises_id,(int)$products_id);
								}
								foreach($aryFormData as $k=>$v){
									if(substr($k,0,2)=='id'){
										$option_id = explode('[',$k);
										$attributePrice = attributes_price_display($products_id,$option_id[1],$v);
										$attribute_total += $attributePrice;
										
										if((int)$attributePrice >0 ){	//限制另外收费产品属性中标明的人数不能大于参团总人数，因为可能导致BOSS亏本。属性值名称格式如：2人接送 10:30pm-12:00am
											$option_guest_num = tep_db_get_field_value('products_options_values_name', 'products_options_values', 'products_options_values_id="'.(int)$v.'"');
											if(preg_match('/^\d+人/', preg_replace('/[[:space:]]+/','',$option_guest_num))){
												$option_guest_num = (int)$option_guest_num;
												if((int)$total_no_guest_tour && ($total_no_guest_tour < $option_guest_num)){
													$option_name = tep_get_product_option_name_from_optionid((int)$option_id[1]);
													$error_msg = db_to_html('参团总人数'.$total_no_guest_tour.' 与'.$option_name.'人数'.$option_guest_num.'不匹配！');
													echo '<script>jQuery("#option_guest_num_error_msg").text("'.$error_msg.'").show();</script>';
													exit();
												}
											}
										}
										echo '<script>jQuery("#option_guest_num_error_msg").hide();</script>';

										if(in_array($option_id[1],(array)$cruisesOptionIds)){
											$isCruises = true;
											
											if($attributePrice > 0){
												$hasSelDeck = true;
												$fianl_cruises_room_total_price += $attributePrice;
											}
										}
										//税费
										if($option_id[1]==$cruisesData['tax_products_options_id']){
											$fianl_cruises_tax_total_price += $attributePrice;
										}
									}
								}
								
								
								//邮轮团选项检查{
								if( $isCruises==true && $hasSelDeck == false ){
									echo db_to_html('<span>由于此产品是邮轮团，所以您需要选择一个客舱的甲板！</span>');
									exit();
								}
								//}
								
								if( $fianl_tour_total_price<0.01 && ($attribute_total < 0.01 || $isCruises==false) ){
									echo db_to_html('<span>总价为'.$fianl_tour_total_price.'，不能预订请注意“价格明细”中的说明！</span>');
									exit();
								}	
								?>
								<?php
								/* 旧版
								<table width="99%" border="0" cellpadding="2" cellspacing="1" bgcolor="#BBDFEF" style="margin-top:3px; margin-bottom:4px;">
									<tr><td height="22" align="center" style="font-size:14px;"><?php echo TEXT_CURRECY_DISPLAY_USD;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_EUR;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_CNY;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_GBP;?></td>
									</tr>
									<tr><td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#F7860F"><?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></td>
									</tr>
								</table>
								//delete by vincent 2011.3.21								
								<span><strong><?php echo TEXT_CURRECY_DISPLAY_USD;?></strong><b><?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_CNY;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_EUR;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_GBP;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></b></span>
								
								*/ ?>
								<script language="javascript">
								
								var extraTxt="";
								<?php if($final_hotel_extension_price > 0 && tep_check_product_is_hotel((int)$products_id)!=1 && $final_hotel_extension_price!= $fianl_tour_total_price){ ?>
								extraTxt += '<?php echo db_to_html('包含酒店延住服务 ');echo $currencies->format($total_early_hotel_extension_price+$total_late_hotel_extension_price, true, 'USD');?>';
								var hotel_extension = true;
								<?php }?>
								<?php if($transfer_price > 0.1 && $transfer_price!=$fianl_tour_total_price){ ?>
								if(extraTxt!='') extraTxt += ",";
								extraTxt += '<?php echo db_to_html('包含接送服务 ');echo $currencies->format($transfer_price, true, 'USD');?>';
								<?php }?>

								var hotelExtensionDisplayUsd = document.getElementById('hotel_extension_display_usd');
								var currecyDisplayUsd = document.getElementById('currecy_display_usd');
								var currecyDisplayCny = document.getElementById('currecy_display_cny');
								if(hotelExtensionDisplayUsd!=null){
									if(extraTxt != "") 
										hotelExtensionDisplayUsd.innerHTML  = "("+extraTxt+")";
									else
										hotelExtensionDisplayUsd.innerHTML  = "";
								}
								if(currecyDisplayUsd!=null){
									currecyDisplayUsd.innerHTML = '<?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?>';
								}
								if(currecyDisplayCny!=null){
									currecyDisplayCny.innerHTML = '<?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?>';
								}
								jQuery('#PriceNull').hide();
								jQuery('#Price').fadeIn("fast");
								</script>
								<label><?php echo db_to_html("换算成其他货币价格为：")?></label>
						        <span><?php echo db_to_html("欧元：");?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></b></span>
						       <span><?php echo db_to_html("英镑：");?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></b></span>
								
								<?php
								//amit added for new design end
								/*echo '<br>';
								echo $total_room_price.'+'.$attribute_total;
								echo '<br>'.$total_no_guest_tour;*/
							
								/*
								邮轮团相关的计算，如果有无房间的游团则要把这些代码也复制到无房间代码块处理 by howard start {
									room-price-0,CruisesRoomPrice,CruisesTaxDiv
								*/
								if($isCruises==true){
									//邮轮总价,$fianl_cruises_room_total_price;
									//邮轮税,$fianl_cruises_tax_total_price;
									//邮轮各个房间价,$final_cruises_each_room_prices; 通过attributes_price_display()取得其值
								?>
								<script type="text/javascript">
								//alert("isCruises");
								
								<?php
								for($i=0;$i<$HTTP_POST_VARS['numberOfRooms'];$i++){								
								?>
								var _select_room_adult_<?= $i?> = document.getElementById("room-<?= $i?>-adult-total");
								var _select_room_child_<?= $i?> = document.getElementById("room-<?= $i?>-child-total");
								var _room_price_<?= $i?> = document.getElementById("room-price-<?= $i?>");
								if(_select_room_adult_<?= $i?>!=null && _select_room_child_<?= $i?>!=null && _room_price_<?= $i?>!=null){
									<?php foreach((array)$final_cruises_each_room_prices as $adult_chaild => $_price){?>
									if((_select_room_adult_<?= $i?>.value+"_"+_select_room_child_<?= $i?>.value)=="<?=$adult_chaild?>"){
										_room_price_<?= $i?>.innerHTML = '<label><?= $currencies->format($_price, true, 'USD');?></label>';
									}
									<?php }?>
								}
								
								<?php }?>
								
								var roomPriceTitle = document.getElementById("room_price_title");
								if(roomPriceTitle!=null){
									roomPriceTitle.innerHTML = '<?= db_to_html("房间价格");?>';
								}
								var cruisesRoomPrice = document.getElementById('CruisesRoomPrice');
								if(cruisesRoomPrice!=null){
									cruisesRoomPrice.innerHTML = '<?php echo db_to_html("邮轮：").'<b>'.$currencies->format($fianl_cruises_room_total_price, true, 'USD').'</b>';?>';
								}
								var cruisesTaxDiv = document.getElementById('CruisesTaxDiv');
								if(cruisesTaxDiv!=null){
									cruisesTaxDiv.innerHTML = '<?php echo db_to_html("税费：").'<b>'.$currencies->format($fianl_cruises_tax_total_price, true, 'USD').'</b>';?>';
								}
								</script>
								<?php
								}
								/*邮轮团相关的计算 by howard start }*/
							}
							
							}else{ //if there is no rooms 无房间的团
							
								$msn_text_array = array();
								
								$totaladultticket = $HTTP_POST_VARS['room-0-adult-total'];
								if($HTTP_POST_VARS['room-0-child-total']!='')
								$totalchildticket = $HTTP_POST_VARS['room-0-child-total'];
								
								$total_no_guest_tour = $totaladultticket+$totalchildticket;
								//$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($products_id) + $total_no_guest_tour;
								$total_room_adult_child_info = "0###".$totaladultticket."!!".$totalchildticket;
								$total_adult_ticket_price = $totaladultticket*$a_price[1];
								$total_child_ticket_price = $totalchildticket*$e;
								
								$total_room_price = $total_adult_ticket_price+$total_child_ticket_price;
								
								$_SESSION['adult_average'][0][(int)$products_id] = $a_price[1];//大人平均价
								$_SESSION['child_average'][0][(int)$products_id] = $e;//小孩平均价
								
								if(strlen($HTTP_POST_VARS['availabletourdate'])>14){
								$date_attribute_price = substr($HTTP_POST_VARS['availabletourdate'],13,-1);
								if(substr($date_attribute_price,0,1)=='-'){
									$dateprifix = '-';
								}else{
									$dateprifix = '+';
								}
								$dateprice = explode('## ',$date_attribute_price);
								if(!is_int(substr($dateprice[1],0,1))){
									$datePrice_cost = substr($dateprice[1],1);
								}else{
									$datePrice_cost = $dateprice[1];
								}
								
								   if ($dateprifix == '+') 
								   {
									  $total_room_price += $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
								   } else 
								   {
									  $total_room_price -= $total_no_guest_tour * tep_add_tax($datePrice_cost, $products_tax);
								   }
								 }
								 
								$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_NO." : ".$totaladultticket;
												if($HTTP_POST_VARS['room-'.$i.'-child-total']!='0')
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_NO." : ".$totalchildticket;
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." : $".number_format($total_room_price,2);
															
									//amit added to add extra 3% if agency is L&L Travel start
								$pro_agency_info_array = tep_get_tour_agency_information((int)$products_id);
								if($pro_agency_info_array['final_transaction_fee'] > 0){								
									$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);								
															
								}								
								//amit added to add extra 3% if agency is L&L Travel end
											
								//howard added if total_no_guest_tour < min_num_guest display error
								if($product_is_transfer != '1'){
								$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.$products_id.'" limit 1');
								$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
								if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
									//tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id.'&minnumber='.$check_min_guest_row['min_num_guest'].''));
									echo TEXT_PRODUCTS_MIN_GUEST.$check_min_guest_row['min_num_guest'];
									exit();
								}
								}
								//howard added if total_no_guest_tour < min_num_guest display error end

								$attribute_total = 0;
								//如果产品是邮轮团，那么必须选择一个价格>0的客舱甲板
								$hasSelDeck = false;
								$isCruises = false;
								foreach($aryFormData as $k=>$v){
									if(substr($k,0,2)=='id'){
										$option_id = explode('[',$k);
										$attributePrice = attributes_price_display($products_id,$option_id[1],$v);
										if((int)$attributePrice >0 ){	//限制另外收费产品属性中标明的人数不能大于参团总人数，因为可能导致BOSS亏本。属性值名称格式如：2人接送 10:30pm-12:00am
											$option_guest_num = tep_db_get_field_value('products_options_values_name', 'products_options_values', 'products_options_values_id="'.(int)$v.'"');
											if(preg_match('/^\d+人/', preg_replace('/[[:space:]]+/','',$option_guest_num))){
												$option_guest_num = (int)$option_guest_num;
												if((int)$total_no_guest_tour && ($total_no_guest_tour < $option_guest_num)){
													$option_name = tep_get_product_option_name_from_optionid((int)$option_id[1]);
													$error_msg = db_to_html('参团总人数'.$total_no_guest_tour.' 与'.$option_name.'人数'.$option_guest_num.'不匹配！');
													echo '<script>jQuery("#option_guest_num_error_msg").text("'.$error_msg.'").show();</script>';
													exit();
												}
											}
										}
										echo '<script>jQuery("#option_guest_num_error_msg").hide();</script>';
										
										$attribute_total += $attributePrice;
										if(in_array($option_id[1],(array)$cruisesOptionIds)){
											$isCruises = true;
											if($attributePrice > 0){
												$hasSelDeck = true;
											}
										}
									}
								}
								
								$fianl_tour_total_price = $total_room_price + $attribute_total;
								//邮轮团选项检查{
								if( $isCruises==true && $hasSelDeck == false ){
									echo db_to_html('<span>由于此产品是邮轮团，所以您需要选择一个客舱的甲板！</span>');
									exit();
								}
								//}
								
								//transfer-service {								
								$transfer_price =0;
								if(tep_not_null($HTTP_POST_VARS['transfer_products_id']) && tep_check_product_is_transfer($HTTP_POST_VARS['transfer_products_id'])){
									$transfer_price = tep_transfer_calculation_price(intval($HTTP_POST_VARS['transfer_products_id']) ,$HTTP_POST_VARS); 
									$fianl_tour_total_price += $transfer_price;
								}
								//}
								if( $fianl_tour_total_price<0.01 && ($attribute_total < 0.01 || $isCruises==false) ){
									echo db_to_html('<span>总价为'.$fianl_tour_total_price.'，不能预订请注意“价格明细”中的说明！</span>');
									exit();
								}
								
								//featured group deal discount - start
								$check_featured_dept_restriction = tep_db_query("select departure_restriction_date, featured_deals_new_products_price from ".TABLE_FEATURED_DEALS." where products_id = '".(int)$products_id."' and departure_restriction_date <= '".$tmp_dp_date."' and active_date <= '".date("Y-m-d")."' and expires_date >= '".date("Y-m-d")."'");
								if(check_is_featured_deal($products_id) == 1 && tep_db_num_rows($check_featured_dept_restriction)>0){
									
									$total_featured_guests = tep_get_featured_total_guests_booked_this_deal($products_id);
									$total_featured_guests = $total_featured_guests+$total_no_guest_tour;
									$get_featured_deal_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$products_id."' and peple_no <= '".$total_featured_guests."' order by peple_no desc limit 1");
									if(tep_db_num_rows($get_featured_deal_discount_data)>0){
										$featured_deal_discounts_data = tep_db_fetch_array($get_featured_deal_discount_data);
										$featured_deal_discount_ratio = $featured_deal_discounts_data['discount_percent']/100;																		
									}else{
										$get_featured_special_price = tep_db_fetch_array($check_featured_dept_restriction);
										//$featured_deal_discount_ratio = number_format(100 - (($get_featured_special_price['featured_deals_new_products_price'] / $product_hotel_result['products_price']) * 100))/100;									
										if((int)$get_featured_special_price['featured_deals_new_products_price']){
											$featured_deal_discount_ratio = number_format((100 * ($product_hotel_result['products_price'] - $get_featured_special_price['featured_deals_new_products_price']))/($product_hotel_result['products_price'] * $product_hotel_result['products_margin']/100))/100;
										}else{
											$featured_deal_discount_ratio = 0;
										}
									}
									$featured_tour_gp = $product_hotel_result['products_margin']/100;
									$group_discount = round((($fianl_tour_total_price * $featured_tour_gp)*$featured_deal_discount_ratio),2);
									$fianl_tour_total_price = $fianl_tour_total_price - $group_discount;
									//$room_wise_price_breakdown.="- ".TXT_FEATURED_DEAL_DISCOUNT." = ".$currencies->format($group_discount, true, 'USD')." <br/>";
									$msn_text_array[] = '团购(FD)';
									
								}else{
									//团购 group buy start 无房间
									$is_long_trour = false;
									if(GROUP_BUY_ON==true && $total_no_guest_tour >= GROUP_MIN_GUEST_NUM && (GROUP_BUY_INCLUDE_SUB_TOUR==true || $is_long_trour==true)){	//团购
										$discount_percentage = auto_get_group_buy_discount($products_id);
										$room_price_old = max($total_room_price,$room_price_old);
										$fianl_tour_total_price = $fianl_tour_total_price - round($fianl_tour_total_price * $discount_percentage, DECIMAL_DIGITS);
										$msn_text_array[] = '团购';
									}
									//团购 group buy end 无房间
								}
								//featured group deal discount - end
								
								//amit added for new design start

								//显示买二送一、双人间优惠的省钱信息
								$msn_text_array[] = '优惠后';
								$msn_text_array=array_unique($msn_text_array);
								$msn_text = implode(' ',$msn_text_array);
								$sheng_do_shao = 0;
								if($room_price_old > 0 && ($room_price_old > ($fianl_tour_total_price-$attribute_total) )){
									$sheng_do_shao = (($room_price_old+$attribute_total)-$fianl_tour_total_price);
									if($sheng_do_shao>0){
										$msn_b = '<span>原价：$'.($room_price_old+$attribute_total).'&nbsp;&nbsp;'.$msn_text.'即省<b>$'.$sheng_do_shao.'</b></span>';
										echo db_to_html($msn_b);
										echo "<script language='javascript'>
										document.getElementById('currecy_discount_usd').innerHTML = '".$currencies->format($sheng_do_shao, true, 'USD')."';
										document.getElementById('currecy_discount_cny').innerHTML = '".$currencies->format($sheng_do_shao, true, 'CNY')."';
										jQuery('#discount_info').fadeIn('fast');
										</script>";
									}
								}
								if($sheng_do_shao == 0){
									echo "<script language='javascript'>
									document.getElementById('currecy_discount_usd').innerHTML = '$0.00';
									document.getElementById('currecy_discount_cny').innerHTML = '￥0.00';
									jQuery('#discount_info').hide();
									</script>";
								}
								//amit added for new design start
								?>
								<?php 
								/* 旧版
								<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#BBDFEF" style="margin-top:3px; margin-bottom:4px;">
									<tr><td height="22" align="center" style="font-size:14px;"><?php echo TEXT_CURRECY_DISPLAY_USD;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_EUR;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_CNY;?></td>
									<td height="22" align="center" style="font-size:14px; color:#707070"><?php echo TEXT_CURRECY_DISPLAY_GBP;?></td>
									</tr>
									<tr><td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#F7860F"><?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?></td>
									<td height="22" align="center" bgcolor="#ffffff" style="font-size:14px; color:#707070"><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></td>
									</tr>
								</table>
								*/
								?>
								<script language="javascript">
								var currecyDisplayUsd = document.getElementById('currecy_display_usd');
								
								var currecyDisplayCny = document.getElementById('currecy_display_cny');
								if(currecyDisplayUsd!=null){
									currecyDisplayUsd.innerHTML = '<?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?>';
								}
								if(currecyDisplayCny!=null){
									currecyDisplayCny.innerHTML = '<?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?>';
								}
								jQuery('#PriceNull').hide();
								jQuery('#Price').fadeIn("fast");
								</script>
								<label><?php echo db_to_html("换算成其他货币价格为：")?></label>
						        <span><?php echo db_to_html("欧元：");?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></b></span>
						       <span><?php echo db_to_html("英镑：");?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></b></span>
						       <?php /*?>
								<span><strong><?php echo TEXT_CURRECY_DISPLAY_USD;?></strong><b><?php echo $currencies->format($fianl_tour_total_price, true, 'USD');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_CNY;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'CNY');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_EUR;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'EUR');?></b></span>
								<span><?php echo TEXT_CURRECY_DISPLAY_GBP;?><b><?php echo $currencies->format($fianl_tour_total_price, true, 'GBP');?></b></span> -->
								<?php */
								//amit added for new design end
								
							}
												
			}// end if products_id 
	  }//end if action

}//end if aryFormData

	
?>