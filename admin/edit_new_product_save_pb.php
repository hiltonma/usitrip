<?php
$room_price = array();
$room_price_cost = array();
$total_info_room = '';
//$total_no_guest_tour_arr[$HTTP_POST_VARS['add_product_products_id']] = 0;
if (isset($HTTP_POST_VARS['add_product_products_id']) && is_numeric($HTTP_POST_VARS['add_product_products_id'])) 
{
								
								$product_hotel_price = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_POST_VARS['add_product_products_id'] . "' ");
								$product_hotel_result = tep_db_fetch_array($product_hotel_price);
								 if(tep_check_product_is_hotel((int)$HTTP_POST_VARS['add_product_products_id'])!=1){
								 //amit modified to make sure price on usd
								if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
									$product_hotel_result['products_single'] = tep_get_tour_price_in_usd($product_hotel_result['products_single'],$tour_agency_opr_currency);
									$product_hotel_result['products_double'] = tep_get_tour_price_in_usd($product_hotel_result['products_double'],$tour_agency_opr_currency);
									$product_hotel_result['products_triple'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple'],$tour_agency_opr_currency);
									$product_hotel_result['products_quadr'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr'],$tour_agency_opr_currency);
									$product_hotel_result['products_kids'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids'],$tour_agency_opr_currency);
									
									$product_hotel_result['products_single_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_single_cost'],$tour_agency_opr_currency);
									$product_hotel_result['products_double_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_double_cost'],$tour_agency_opr_currency);
									$product_hotel_result['products_triple_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_triple_cost'],$tour_agency_opr_currency);
									$product_hotel_result['products_quadr_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_quadr_cost'],$tour_agency_opr_currency);
									$product_hotel_result['products_kids_cost'] = tep_get_tour_price_in_usd($product_hotel_result['products_kids_cost'],$tour_agency_opr_currency);
								}
								//amit modified to make sure price on usd
								 $a_price[1] = $product_hotel_result['products_single']; //single
								 $a_price[2] = $product_hotel_result['products_double']; //double
								 $a_price[3] = $product_hotel_result['products_triple']; //triple
								 $a_price[4] = $product_hotel_result['products_quadr']; //quadr
								 $e = $product_hotel_result['products_kids']; //child Kid
								 
								  $a_cost[1] = $product_hotel_result['products_single_cost']; //single
								 $a_cost[2] = $product_hotel_result['products_double_cost']; //double
								 $a_cost[3] = $product_hotel_result['products_triple_cost']; //triple
								 $a_cost[4] = $product_hotel_result['products_quadr_cost']; //quadr
								 $e_cost = $product_hotel_result['products_kids_cost']; //child Kid
								 
								 
								$tmp_dp_date = substr($HTTP_POST_VARS['availabletourdate'][$add_product_products_id],0,10);
								
								if(is_tour_start_date_required($HTTP_POST_VARS['add_product_products_id']) == false){//Fixed for passes/cards -- 
									$tmp_dp_date = date('Y-m-d'); // for passes/cards, departure date is today date only
								}
								 
								/* Price Displaying - standard price for different reg/irreg sections - start */ 
								$get_reg_date_price_array = explode('!!!',$HTTP_POST_VARS['availabletourdate'][$add_product_products_id]);								
								$HTTP_POST_VARS['availabletourdate'][$add_product_products_id] = $get_reg_date_price_array[0];		
								 
								$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and products_id ='".(int)$HTTP_POST_VARS['add_product_products_id']."' ");
								if(tep_db_num_rows($check_standard_price_dates)>0){
									$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
									$operate_start_date = $row_standard_price_dates['operate_start_date'];
									$operate_end_date = $row_standard_price_dates['operate_end_date'];
								}else{
									$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and products_id ='".(int)$HTTP_POST_VARS['add_product_products_id']."' ");
									$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
									$operate_start_date = $row_standard_price_dates1['operate_start_date'];
									$operate_end_date = $row_standard_price_dates1['operate_end_date'];
								}
								
								$check_section_standard_price_query = "select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where operate_start_date ='".$operate_start_date."' and operate_end_date = '".$operate_end_date."' and products_id ='".(int)$HTTP_POST_VARS['add_product_products_id']."' and products_single > 0 ";
								$check_section_standard_price = tep_db_query($check_section_standard_price_query);
								if(tep_db_num_rows($check_section_standard_price)>0){
									$row_section_standard_price = tep_db_fetch_array($check_section_standard_price);
									
									if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
										$row_section_standard_price['products_single'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single'],$tour_agency_opr_currency);
										$row_section_standard_price['products_double'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double'],$tour_agency_opr_currency);
										$row_section_standard_price['products_triple'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple'],$tour_agency_opr_currency);
										$row_section_standard_price['products_quadr'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr'],$tour_agency_opr_currency);
										$row_section_standard_price['products_kids'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids'],$tour_agency_opr_currency);

										$row_section_standard_price['products_single_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_single_cost'],$tour_agency_opr_currency);
										$row_section_standard_price['products_double_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_double_cost'],$tour_agency_opr_currency);
										$row_section_standard_price['products_triple_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_triple_cost'],$tour_agency_opr_currency);
										$row_section_standard_price['products_quadr_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_quadr_cost'],$tour_agency_opr_currency);
										$row_section_standard_price['products_kids_cost'] = tep_get_tour_price_in_usd($row_section_standard_price['products_kids_cost'],$tour_agency_opr_currency);
									}
									 $a_price[1] = $row_section_standard_price['products_single']; //single
									 $a_price[2] = $row_section_standard_price['products_double']; //double
									 $a_price[3] = $row_section_standard_price['products_triple']; //triple
									 $a_price[4] = $row_section_standard_price['products_quadr']; //quadr
									 $e = $row_section_standard_price['products_kids'];
									 
									 $a_cost[1] = $row_section_standard_price['products_single_cost']; //single
									 $a_cost[2] = $row_section_standard_price['products_double_cost']; //double
									 $a_cost[3] = $row_section_standard_price['products_triple_cost']; //triple
									 $a_cost[4] = $row_section_standard_price['products_quadr_cost']; //quadr
									 $e_cost = $row_section_standard_price['products_kids_cost']; //child Kid
								}
								 
								/* Price Displaying - standard price for different reg/irreg sections - start */ 
								
								//amit added to check if special price is available for specific date start
								$special_dt_chk = 0;
								$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0) and products_id ='".(int)$HTTP_POST_VARS['add_product_products_id']."' ";
								$check_specip_pride_date_query = tep_db_query($check_specip_pride_date_select);
								while($check_specip_pride_date_row = tep_db_fetch_array($check_specip_pride_date_query)){
									//amit modified to make sure price on usd
									if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
										$check_specip_pride_date_row['spe_single'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
										
										$check_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
										$check_specip_pride_date_row['extra_charge_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['extra_charge_cost'],$tour_agency_opr_currency);
									}
									//amit modified to make sure price on usd
									$a_price[1] = $check_specip_pride_date_row['spe_single']; //single
									$a_price[2] = $check_specip_pride_date_row['spe_double']; //double
									$a_price[3] = $check_specip_pride_date_row['spe_triple']; //triple
									$a_price[4] = $check_specip_pride_date_row['spe_quadruple']; //quadr
									$e = $check_specip_pride_date_row['spe_kids']; //child Kid
									$a_cost[1] = $check_specip_pride_date_row['spe_single_cost']; //single
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
									$check_reg_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 or extra_charge > 0) and products_id ='".(int)$HTTP_POST_VARS['add_product_products_id']."' ";
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
												$check_reg_specip_pride_date_row['spe_double'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_triple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_quadruple'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_kids'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids'],$tour_agency_opr_currency);
												
												$check_reg_specip_pride_date_row['spe_single_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_single_cost'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_double_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_double_cost'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_triple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_triple_cost'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
												$check_reg_specip_pride_date_row['spe_kids_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_kids_cost'],$tour_agency_opr_currency);
											}
											//amit modified to make sure price on usd										
											$a_price[1] = $check_reg_specip_pride_date_row['spe_single']; //single
											$a_price[2] = $check_reg_specip_pride_date_row['spe_double']; //double
											$a_price[3] = $check_reg_specip_pride_date_row['spe_triple']; //triple
											$a_price[4] = $check_reg_specip_pride_date_row['spe_quadruple']; //quadr
											$e = $check_reg_specip_pride_date_row['spe_kids']; //child Kid
											
											$a_cost[1] = $check_reg_specip_pride_date_row['spe_single_cost']; //single
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
								if((isset($HTTP_POST_VARS['early_hotel_checkout_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date'][$add_product_products_id]) && isset($HTTP_POST_VARS['early_hotel_checkin_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['early_hotel_checkin_date'][$add_product_products_id])) || (isset($HTTP_POST_VARS['late_hotel_checkin_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date'][$add_product_products_id]) && isset($HTTP_POST_VARS['late_hotel_checkout_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['late_hotel_checkout_date'][$add_product_products_id])) ){
									if(isset($HTTP_POST_VARS['early_hotel_checkout_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['early_hotel_checkout_date'][$add_product_products_id])){
										$early_hotel_id = $HTTP_POST_VARS['early_arrival_hotels'][$add_product_products_id];
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
										$early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date'][$add_product_products_id]);
										$early_hotel_checkin_date = $early_hotel_checkin_date_split[2].'-'.$early_hotel_checkin_date_split[0].'-'.$early_hotel_checkin_date_split[1];
										$early_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkout_date'][$add_product_products_id]);
										$early_hotel_checkout_date = $early_hotel_checkout_date_split[2].'-'.$early_hotel_checkout_date_split[0].'-'.$early_hotel_checkout_date_split[1];
										
										$early_total_dates_price = array();
										$loop_start = strtotime($early_hotel_checkin_date);
										$loop_end = strtotime($early_hotel_checkout_date) - (24*60*60);
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
														$check_reg_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_reg_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
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
													$check_specip_pride_date_row['spe_quadruple_cost'] = tep_get_tour_price_in_usd($check_specip_pride_date_row['spe_quadruple_cost'],$tour_agency_opr_currency);
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
									
									if(isset($HTTP_POST_VARS['late_hotel_checkin_date'][$add_product_products_id]) && tep_not_null($HTTP_POST_VARS['late_hotel_checkin_date'][$add_product_products_id])){
										$late_hotel_id = $HTTP_POST_VARS['staying_late_hotels'][$add_product_products_id];
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
										$late_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkin_date'][$add_product_products_id]);
										$late_hotel_checkin_date = $late_hotel_checkin_date_split[2].'-'.$late_hotel_checkin_date_split[0].'-'.$late_hotel_checkin_date_split[1];
										$late_hotel_checkout_date_split = explode("/", $HTTP_POST_VARS['late_hotel_checkout_date'][$add_product_products_id]);
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
								 
								 $total_no_guest_tour = 0;// Total no of guest for entering names
								 
							if($_POST['numberOfRooms'][$add_product_products_id])//if there is rooms 
							{
								/*****************************************/
								$validation_no = $product_hotel_result['maximum_no_of_guest'];				
								/*****************************************/
								
								
								$errormessageperson = "";
								$total_info_room = "</br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'][$add_product_products_id];
								$early_he_total_info_room = "<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'][$add_product_products_id];
								$late_he_total_info_room = "<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'][$add_product_products_id];
								$total_room_adult_child_info = $_POST['numberOfRooms'][$add_product_products_id]."###";
								$is_discount_available=1;
								for($i=0;$i<$_POST['numberOfRooms'][$add_product_products_id];$i++)
								{
								$is_buy_two_get_one = check_buy_two_get_one((int)$HTTP_POST_VARS['add_product_products_id'], ($_POST['room-'.$i.'-adult-total'][$add_product_products_id]+$_POST['room-'.$i.'-child-total'][$add_product_products_id]), $tmp_dp_date);
										/*******************************************************************************************************/
										/****************************BOC: this is validation for the first room ********************************/
										if($_POST['room-'.$i.'-adult-total'][$add_product_products_id]!='')
										{
											/****************total number of person in room *****************/
											$totalinroom[$i] = $_POST['room-'.$i.'-adult-total'][$add_product_products_id];
											if($_POST['room-'.$i.'-child-total'][$add_product_products_id]!='')
											{
												$totalinroom[$i] += $_POST['room-'.$i.'-child-total'][$add_product_products_id];
												
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
												if($_POST['room-'.$i.'-child-total'][$add_product_products_id] == '0')
												{
													$room_price[$i] = $_POST['room-'.$i.'-adult-total'][$add_product_products_id]*$a_price[$_POST['room-'.$i.'-adult-total'][$add_product_products_id]];
													$he_room_price[$i] = $HTTP_POST_VARS['room-'.$i.'-adult-total'][$add_product_products_id]*$hotel_extension_price[$HTTP_POST_VARS['room-'.$i.'-adult-total'][$add_product_products_id]];
													if($HTTP_POST_VARS['room-'.$i.'-adult-total'][$add_product_products_id]=='2' ){
														/*$double_room_pre = double_room_preferences((int)$HTTP_POST_VARS['add_product_products_id'],$tmp_dp_date);
														if((int)$double_room_pre){
															$room_price_old += $room_price[$i];
															$room_price[$i] = 2*($a_price[2]-$double_room_pre);
														}*/
													}else{
														
														//买二送1价 无小孩
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
															
															if($HTTP_POST_VARS['room-'.$i.'-adult-total'][$add_product_products_id]=='3' ){
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){	
																	$room_price[$i] = 2*$a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
																}
															}
															//价格4人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
															if($HTTP_POST_VARS['room-'.$i.'-adult-total'][$add_product_products_id]=='4' ){
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																	$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
																}
																if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
																	//买二送二
																	$room_price[$i] = 2*($a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']));
																}
															}
															
														}
													}
													$room_price_cost[$i] = $_POST['room-'.$i.'-adult-total'][$add_product_products_id]*$a_cost[$_POST['room-'.$i.'-adult-total'][$add_product_products_id]];
													$he_room_price_cost[$i] = $_POST['room-'.$i.'-adult-total'][$add_product_products_id]*$hotel_extension_price_cost[$_POST['room-'.$i.'-adult-total'][$add_product_products_id]];
												
												}
												elseif($_POST['room-'.$i.'-child-total'][$add_product_products_id] != '0')
												{
													if($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '1' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '1')
													{
														$room_price[$i] = 2*$a_price[2];
														$he_room_price[$i] = 2*$hotel_extension_price[2];
														$room_price_cost[$i] = 2*$a_cost[2];
														$he_room_price_cost[$i] = 2*$hotel_extension_price_cost[2];
														
														/*$double_room_pre = double_room_preferences($products_id,$tmp_dp_date);
														if((int)$double_room_pre){
															$room_price_old += $room_price[$i];
															$room_price[$i] = 2*($a_price[2]-$double_room_pre);															
														}*/
													}
													elseif($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '1' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '2')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														if( (int)$is_buy_two_get_one){
															$room_price_old += $room_price[$i];
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
															}
														}
														$room_price_option1_cost[$i] = (2*$a_cost[2]) + $e_cost;
														$room_price_option2_cost[$i] = 3*$a_cost[3];
														$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
														$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + $hotel_extension_price_cost[0];
														$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3];
														$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);
										
										
													}
													elseif($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '1' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '3')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
														$room_price_option2[$i] = 3*$a_price[3]+$e;
														$room_price_option3[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
														
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
														$he_room_price_option3[$i] = 4*$hotel_extension_price[4];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i],$he_room_price_option3[$i]);
														
														if( (int)$is_buy_two_get_one){
															$room_price_old += $room_price[$i];
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
															}
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
															//买二送二价
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']));
															
															}															
														}
														$room_price_option1_cost[$i] = (2*$a_cost[2]) + 2*$e_cost;
														$room_price_option2_cost[$i] = 3*$a_cost[3]+$e_cost;
														$room_price_option3_cost[$i] = 4*$a_cost[4];
														$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i],$room_price_option3_cost[$i]);
														
														$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0];
														$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
														$he_room_price_option3_cost[$i] = 4*$hotel_extension_price_cost[4];
														$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i],$he_room_price_option3_cost[$i]);
														
													}
													elseif($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '2' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '1')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + $hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price_old += $room_price[$i];
																$room_price[$i] = 2*$a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
															}
														}
														$room_price_option1_cost[$i] = (2*$a_cost[2]) + $e_cost;
														$room_price_option2_cost[$i] = 3*$a_cost[3];
														$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
														
														$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + $hotel_extension_price_cost[0];
														$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3];
														$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);
													
													}
													elseif($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '2' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '2')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
														$room_price_option2[$i] = 3*$a_price[3]+$e;
														$room_price_option3[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
														
														$he_room_price_option1[$i] = (2*$hotel_extension_price[2]) + 2*$hotel_extension_price[0];
														$he_room_price_option2[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
														$he_room_price_option3[$i] = 4*$hotel_extension_price[4];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i],$he_room_price_option3[$i]);
													
														if( (int)$is_buy_two_get_one){
															$room_price_old += $room_price[$i];
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
															}
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
															//买二送二价
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']));
															}
														}
														$room_price_option1_cost[$i] = (2*$a_cost[2]) + 2*$e_cost;
														$room_price_option2_cost[$i] = 3*$a_cost[3]+$e_cost;
														$room_price_option3_cost[$i] = 4*$a_cost[4];
														$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i],$room_price_option3_cost[$i]);
														
														$he_room_price_option1_cost[$i] = (2*$hotel_extension_price_cost[2]) + 2*$hotel_extension_price_cost[0];
														$he_room_price_option2_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
														$he_room_price_option3_cost[$i] = 4*$hotel_extension_price_cost[4];
														$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i],$he_room_price_option3_cost[$i]);
													
													}
													elseif($_POST['room-'.$i.'-adult-total'][$add_product_products_id] == '3' && $_POST['room-'.$i.'-child-total'][$add_product_products_id] == '1')
													{
														$room_price_option1[$i] = 3*$a_price[3]+$e;
														$room_price_option2[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														
														$he_room_price_option1[$i] = 3*$hotel_extension_price[3]+$hotel_extension_price[0];
														$he_room_price_option2[$i] = 4*$hotel_extension_price[4];
														$he_room_price[$i] = min($he_room_price_option1[$i],$he_room_price_option2[$i]);
														
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='2'){
																$room_price[$i] = 2*$a_price[2] + $a_price[3] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']);
															}
															if($is_buy_two_get_one=='1' || $is_buy_two_get_one =='3'){
															//买二送二
																$room_price[$i] = 2*($a_price[2] + get_products_surcharge((int)$HTTP_POST_VARS['add_product_products_id']));
															}
														}
														$room_price_option1_cost[$i] = 3*$a_cost[3]+$e_cost;
														$room_price_option2_cost[$i] = 4*$a_cost[4];
														$room_price_cost[$i] = min($room_price_option1_cost[$i],$room_price_option2_cost[$i]);
														
														$he_room_price_option1_cost[$i] = 3*$hotel_extension_price_cost[3]+$hotel_extension_price_cost[0];
														$he_room_price_option2_cost[$i] = 4*$hotel_extension_price_cost[4];
														$he_room_price_cost[$i] = min($he_room_price_option1_cost[$i],$he_room_price_option2_cost[$i]);
														
													}
										
												}
												/****************total price for room no 1 *****************/
												/*
												$roomno = $i+1;
												$total_info_room .= "<br> # of adults in room ".$roomno.": ".$_POST['room-'.$i.'-adult-total'];
												if($_POST['room-'.$i.'-child-total']!='0')
												$total_info_room .= "<br> # of childs in room ".$roomno.": ".$_POST['room-'.$i.'-child-total'];
												$total_info_room .= "<br> Total of room ".$roomno.": $".number_format($room_price[$i],2);
												$total_no_guest_tour = $total_no_guest_tour+$_POST['room-'.$i.'-adult-total']+$_POST['room-'.$i.'-child-total'];
												*/
												$roomno = $i+1;
												//Start - double room preference discount
												if($i=='0'){
													$discount_available=double_room_preferences((int)$HTTP_POST_VARS['add_product_products_id'], $tmp_dp_date, '', '', '1');//Check if discount available
													if($discount_available=='0'){//Discount not available
														$is_discount_available=0;
													}else{
														$is_discount_available=1;
													}
												}
												if($is_discount_available=='1'){
													$total_guests=$_POST['room-'.$i.'-adult-total'][$add_product_products_id]+$_POST['room-'.$i.'-child-total'][$add_product_products_id];
													$room_price[$i]=double_room_preferences((int)$HTTP_POST_VARS['add_product_products_id'], $tmp_dp_date, $room_price[$i], $total_guests, 0);
												}
												//End - double room preference discount
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'][$add_product_products_id];
												$early_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'][$add_product_products_id];
												$late_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'][$add_product_products_id];
												if($_POST['room-'.$i.'-child-total'][$add_product_products_id]!='0'){
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'][$add_product_products_id];
												$early_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'][$add_product_products_id];
												$late_he_total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'][$add_product_products_id];
												}
												
												if($he_room_price[$i] > 0){
													$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($he_room_price[$i],2);
												}else{
													$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($room_price[$i],2);
												}
												$total_no_guest_tour = $total_no_guest_tour+$_POST['room-'.$i.'-adult-total'][$add_product_products_id]+$_POST['room-'.$i.'-child-total'][$add_product_products_id];
												$total_room_adult_child_info .= $_POST['room-'.$i.'-adult-total'][$add_product_products_id].'!!'.$_POST['room-'.$i.'-child-total'][$add_product_products_id].'###';
											
											}	
										}
										/****************************EOC: this is validation for the first room ********************************/
										/*******************************************************************************************************/
										/*******************************************************************************************************/
								
								
								}
								if($errormessageperson != '')
								{
									echo '<script>alert("you can enter maximum '.$errormessageperson.' person per-room");</script>' ;
									echo '<script>history.go(-1);</script>';
									exit();
									/* tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_POST_VARS['products_id'].'&maxnumber='.$errormessageperson.''));
									exit(); */
								}
								else
								{
								$total_room_price = $room_price[0]+$room_price[1]+$room_price[2]+$room_price[3]+$room_price[4]+$room_price[5];
								$total_hotel_extension_price = $he_room_price[0]+$he_room_price[1]+$he_room_price[2]+$he_room_price[3]+$he_room_price[4]+$he_room_price[5];
								$total_room_price_cost = $room_price_cost[0]+$room_price_cost[1]+$room_price_cost[2]+$room_price_cost[3]+$room_price_cost[4]+$room_price_cost[5];
								$total_hotel_extension_price_cost = $he_room_price_cost[0]+$he_room_price_cost[1]+$he_room_price_cost[2]+$he_room_price_cost[3]+$he_room_price_cost[4]+$he_room_price_cost[5];
								//Transaction fee - start
								$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['add_product_products_id']);
								if($pro_agency_info_array['final_transaction_fee'] > 0){
									$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);
									$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,$pro_agency_info_array['final_transaction_fee']);							
								}
								//Transaction fee - end
								if($total_hotel_extension_price > 0){
									$total_room_price = $total_room_price + $total_hotel_extension_price;
									$total_room_price_cost = $total_room_price_cost + $total_hotel_extension_price_cost;
								}

								
								//if (tep_session_is_registered('customer_id')) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");
								//$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'',$HTTP_POST_VARS['availabletourdate'],$HTTP_POST_VARS['departurelocation'],'','','','','',$total_room_price,$total_info_room,$total_no_guest_tour);
								
								
								}
							
							}else{  //if($_POST['numberOfRooms'])//if there is rooms 
							
								$totaladultticket = $_POST['room-0-adult-total'][$add_product_products_id];
								if($_POST['room-0-child-total'][$add_product_products_id]!='')
								$totalchildticket = $_POST['room-0-child-total'][$add_product_products_id];
								
								$total_no_guest_tour = $totaladultticket+$totalchildticket;
								$total_room_adult_child_info = "0###".$totaladultticket."!!".$totalchildticket;
								$total_adult_ticket_price = $totaladultticket*$a_price[1];
								$total_child_ticket_price = $totalchildticket*$e;
								
								$total_room_price = $total_adult_ticket_price+$total_child_ticket_price;
								
								
								//amit added for cost cal
								$total_adult_ticket_price_cost = $totaladultticket*$a_cost[1];
								$total_child_ticket_price_cost = $totalchildticket*$e_cost;								
								$total_room_price_cost = $total_adult_ticket_price_cost+$total_child_ticket_price_cost;
								//amit added for cost cal
								/*			
								$total_info_room .= "<br> # of adults :- ".$totaladultticket;
												if($_POST['room-'.$i.'-child-total']!='0')
												$total_info_room .= "<br> # of childs :- ".$totalchildticket;
												$total_info_room .= "<br> Total :- $".number_format($total_room_price,2);
								*/
								$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_NO." : ".$totaladultticket;
								if($_POST['room-'.$i.'-child-total'][$add_product_products_id]!='0')
								$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_NO." : ".$totalchildticket;
								$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." : $".number_format($total_room_price,2);
																		
											
								//if (tep_session_is_registered('customer_id')) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id=$products_id");
								//$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+1, $HTTP_POST_VARS['id'],'',$HTTP_POST_VARS['availabletourdate'],$HTTP_POST_VARS['departurelocation'],'','','','','',$total_room_price,$total_info_room,$total_no_guest_tour);
								
								//Transaction fee - start
								$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['add_product_products_id']);
								if($pro_agency_info_array['final_transaction_fee'] > 0){
									$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);
									$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,$pro_agency_info_array['final_transaction_fee']);							
								}
								//Transaction fee - end

								
							} //elseif($_POST['numberOfRooms'])//if there is rooms 
							
							
							
							
							$availabletourdate = $HTTP_POST_VARS['availabletourdate'][$add_product_products_id];
							if($total_hotel_extension_price > 0){
							$early_hotel_checkin_date_split = explode("/", $HTTP_POST_VARS['early_hotel_checkin_date'][$add_product_products_id]);
							$early_hotel_checkin_date = $early_hotel_checkin_date_split[2].'-'.$early_hotel_checkin_date_split[0].'-'.$early_hotel_checkin_date_split[1];
							
							$availabletourdate = $early_hotel_checkin_date;
							$is_hotel = 1;
							$early_hotel_checkout_date_split = explode("/", $_POST['early_hotel_checkout_date'][$add_product_products_id]);
							$early_hotel_checkout_date = $early_hotel_checkout_date_split[2].'-'.$early_hotel_checkout_date_split[0].'-'.$early_hotel_checkout_date_split[1];
							$hotel_checkout_date = $early_hotel_checkout_date;
							}
							$departurelocation = $HTTP_POST_VARS['departurelocation'][$add_product_products_id];
							$departtimelocation = explode('::::',$departurelocation);
							$depart_time = $departtimelocation[0];
							$depart_location = $departtimelocation[1];
							
							/* $total_room_price;
							$total_info_room;
							$total_no_guest_tour; */
							
		if($availabletourdate != "")
		{
			/** code for available date and departure time and location***/
			$availdate = explode('::',$availabletourdate);
			$finaldate = $availdate[0];
			
			$price_with_prefix = explode('##',$availdate[1]);
			if($price_with_prefix[1] != '')
			{
				if($price_with_prefix[0] == '')
				{
				 $prifix = '+';
				}
				else
				{
				 $prifix = str_replace('(','',$price_with_prefix[0]);
				}
				
				$date_price = str_replace(')','',$price_with_prefix[1]);
				$date_price = str_replace('$','',$date_price);
				$date_price = str_replace(' ','',$date_price);
			}
			else
			{
			$prifix = '';
			$date_price = '';
			}
		}


$total_no_guest_tour_arr[$HTTP_POST_VARS['add_product_products_id']] = $total_no_guest_tour;
$total_room_adult_child_info_arr[$HTTP_POST_VARS['add_product_products_id']] = $total_room_adult_child_info;
$total_info_room_arr[$HTTP_POST_VARS['add_product_products_id']] = $total_info_room;

$total_room_price_arr[$HTTP_POST_VARS['add_product_products_id']] = $total_room_price;
$total_room_price_cost_arr[$HTTP_POST_VARS['add_product_products_id']] = $total_room_price_cost_cost;

/* echo "<br>room price--> ". */
$sub_total1 = $add_product_quantity * tep_add_tax($total_room_price_arr[$HTTP_POST_VARS['add_product_products_id']], $products_tax);
/* echo "<br>room finalprice--> ". */
$sub_total2 = number_format(($date_price*$total_no_guest_tour_arr[$HTTP_POST_VARS['add_product_products_id']]),2);

$final_product_price = $sub_total1+$sub_total2;
$final_product_price_arr[$HTTP_POST_VARS['add_product_products_id']] = $final_product_price;


$sub_total1_cost = $add_product_quantity * tep_add_tax($total_room_price_cost, $products_tax);

$sub_total2_cost = number_format(($date_price_cost*$total_no_guest_tour),2);

$final_product_price_cost = $sub_total1_cost+$sub_total2_cost;
$final_product_price_cost_arr[$HTTP_POST_VARS['add_product_products_id']] = $final_product_price_cost;
$final_product_price_arr[$HTTP_POST_VARS['add_product_products_id']] = $final_product_price;
$finaldate_arr[$HTTP_POST_VARS['add_product_products_id']] = $finaldate;
$depart_time_arr[$HTTP_POST_VARS['add_product_products_id']] = $depart_time;
$depart_location_arr[$HTTP_POST_VARS['add_product_products_id']] = $depart_location;

		/* $this->contents[$products_id]['dateattributes'][0] = $finaldate;
		$this->contents[$products_id]['dateattributes'][1] = $depart_time;
		$this->contents[$products_id]['dateattributes'][2] = $depart_location;
		$this->contents[$products_id]['dateattributes'][3] = $prifix;
		$this->contents[$products_id]['dateattributes'][4] = number_format(($date_price*$total_no_guest_tour),2);
		$this->contents[$products_id]['roomattributes'][0] = $total_room_price;
		$this->contents[$products_id]['roomattributes'][1] = $total_info_room;
		$this->contents[$products_id]['roomattributes'][2] = $total_no_guest_tour; */
		
		
							
}
?>