<?php
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'edit_orders.php');

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
require('includes/classes/OrdersOwnerChange.class.php');//订单更新状态的时候改变订单归属的类
$OOC=new OrdersOwnerChange;
include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);
//print_r($order->products);exit;

$products_id=$order->products[$_GET['product_number']]['id'];
$orders_products_id = $order->products[$_GET['product_number']]['orders_products_id'] ;//hotel-extension
$total_room_adult_child_info=$order->products[$_GET['product_number']]['total_room_adult_child_info'];
$display_room_option=get_total_room_from_str($total_room_adult_child_info);

function browser_detection( $which_test ) {
			$browser = '';
			$dom_browser = '';
			
			$navigator_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
		
			if (stristr($navigator_user_agent, "opera")) 
			{
				$browser = 'opera';
				$dom_browser = true;
			}
		
			elseif (stristr($navigator_user_agent, "msie 4")) 
			{
				$browser = 'msie4'; 
				$dom_browser = false;
			}
		
			elseif (stristr($navigator_user_agent, "msie")) 
			{
				$browser = 'msie'; 
				$dom_browser = true;
			}
		
			elseif ((stristr($navigator_user_agent, "konqueror")) || (stristr($navigator_user_agent, "safari"))) 
			{
				$browser = 'safari'; 
				$dom_browser = true;
			}
		
			elseif (stristr($navigator_user_agent, "gecko")) 
			{
				$browser = 'mozilla';
				$dom_browser = true;
			}
			
			elseif (stristr($navigator_user_agent, "mozilla/4")) 
			{
				$browser = 'ns4';
				$dom_browser = false;
			}
			
			else 
			{
				$dom_browser = false;
				$browser = false;
			}
		
			if ( $which_test == 'browser' )
			{
				return $browser;
			}
			elseif ( $which_test == 'dom' )
			{
				return $dom_browser;		
			}
		}
?>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<?php
 switch ($HTTP_GET_VARS['action_ajax_add']) {
 
  case 'add_product' :    if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) 
							{
							
								
								//amit modified to make sure price on usd
								$tour_agency_opr_currency = tep_get_tour_agency_operate_currency((int)$HTTP_POST_VARS['products_id']);
								//amit modified to make sure price on usd
								if($HTTP_POST_VARS['_1_H_hot3'] != "")
								{
									$HTTP_POST_VARS['departurelocation'] =  tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot3']).'::::'.tep_db_prepare_input($HTTP_POST_VARS['_1_H_hot2']).' '.tep_db_prepare_input($HTTP_POST_VARS['_1_H_address']);
							 	// $HTTP_POST_VARS['departurelocation'] =  $HTTP_POST_VARS['_1_H_hot3'].'::::'.' '.$HTTP_POST_VARS['_1_H_address'];
								}
								$product_hotel_price = tep_db_query("select p.* from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "' ");
								$product_hotel_result = tep_db_fetch_array($product_hotel_price);
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
								 
								 
								/* Price Displaying - standard price for different reg/irreg sections - start */ 
								$get_reg_date_price_array = explode('!!!',$HTTP_POST_VARS['availabletourdate']);								
								$HTTP_POST_VARS['availabletourdate'] = $get_reg_date_price_array[0];		
								 
								$check_standard_price_dates = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and products_id ='".(int)$HTTP_POST_VARS['products_id']."' ");
								if(tep_db_num_rows($check_standard_price_dates)>0){
									$row_standard_price_dates = tep_db_fetch_array($check_standard_price_dates);
									$operate_start_date = $row_standard_price_dates['operate_start_date'];
									$operate_end_date = $row_standard_price_dates['operate_end_date'];
								}else{
									$check_standard_price_dates1 = tep_db_query("select operate_start_date, operate_end_date from ". TABLE_PRODUCTS_REG_IRREG_DATE." where products_start_day_id ='".$get_reg_date_price_array[1]."' and products_id ='".(int)$HTTP_POST_VARS['products_id']."' ");
									$row_standard_price_dates1 = tep_db_fetch_array($check_standard_price_dates1);
									$operate_start_date = $row_standard_price_dates1['operate_start_date'];
									$operate_end_date = $row_standard_price_dates1['operate_end_date'];
								}
								
								$check_section_standard_price_query = "select * from ". TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where operate_start_date ='".$operate_start_date."' and operate_end_date = '".$operate_end_date."' and products_id ='".(int)$_POST['add_product_products_id']."' and products_single > 0 ";
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
								$check_specip_pride_date_select = "select * from ". TABLE_PRODUCTS_REG_IRREG_DATE." where available_date ='".$tmp_dp_date."' and (spe_single > 0 or spe_double > 0 or spe_triple > 0 or spe_quadruple > 0 or spe_kids  > 0 ) and products_id ='".(int)$HTTP_POST_VARS['products_id']."' ";
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
								$total_info_room = "<br>".TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS.": ".$_POST['numberOfRooms'];
								$total_room_adult_child_info = $_POST['numberOfRooms']."###";
								$is_discount_available=1;
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
												
												/****************total price for room no 1 *****************/
												if($_POST['room-'.$i.'-child-total'] == '0')
												{
													$room_price[$i] = $_POST['room-'.$i.'-adult-total']*$a_price[$_POST['room-'.$i.'-adult-total']];
													//双人一房折扣团
													if($HTTP_POST_VARS['room-'.$i.'-adult-total']=='2' ){
														
													}else{
													
														//买二送1价 无小孩
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
												
												}
												elseif($_POST['room-'.$i.'-child-total'] != '0')
												{
													if($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '1')
													{
														
														$room_price[$i] = 2*$a_price[2];
														$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];
														$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];
														$room_price_cost[$i] = 2*$a_cost[2];														
														
														
													}
													elseif($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '2')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														if($room_price_option1[$i] <= $room_price_option2[$i] ){
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = ($a_price[2]+$e)/2;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i小孩平均价
														}
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
										
													}
													elseif($_POST['room-'.$i.'-adult-total'] == '1' && $_POST['room-'.$i.'-child-total'] == '3')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
														$room_price_option2[$i] = 3*$a_price[3]+$e;
														$room_price_option3[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
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
														
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
														
													}
													elseif($_POST['room-'.$i.'-adult-total'] == '2' && $_POST['room-'.$i.'-child-total'] == '1')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + $e;
														$room_price_option2[$i] = 3*$a_price[3];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														if($room_price_option1[$i] <= $room_price_option2[$i]){
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[2];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i小孩平均价
														}
														
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
													
													}
													elseif($_POST['room-'.$i.'-adult-total'] == '2' && $_POST['room-'.$i.'-child-total'] == '2')
													{
														$room_price_option1[$i] = (2*$a_price[2]) + 2*$e;
														$room_price_option2[$i] = 3*$a_price[3]+$e;
														$room_price_option3[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i],$room_price_option3[$i]);
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
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
													
													}
													elseif($_POST['room-'.$i.'-adult-total'] == '3' && $_POST['room-'.$i.'-child-total'] == '1')
													{
														$room_price_option1[$i] = 3*$a_price[3]+$e;
														$room_price_option2[$i] = 4*$a_price[4];
														$room_price[$i] = min($room_price_option1[$i],$room_price_option2[$i]);
														
														if($room_price_option1[$i] <= $room_price_option2[$i]){
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[3];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $e;//房间$i小孩平均价
														}else{
															$_SESSION['adult_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i大人平均价
															$_SESSION['child_average'][$i][$HTTP_POST_VARS['products_id']] = $a_price[4];//房间$i小孩平均价
														}
														
														if( (int)$is_buy_two_get_one){
															//价格3人间（总价）=双人/间单价X2+三人/间单价X1 + 附加费+门票等费用
															$room_price_old += $room_price[$i];
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
														
													}
										
												}
												/****************total price for room no 1 *****************/
												$roomno = $i+1;
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-adult-total'];
												if($_POST['room-'.$i.'-child-total']!='0')
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS." ".$roomno.": ".$_POST['room-'.$i.'-child-total'];
												
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM." ".$roomno.": $".number_format($room_price[$i],2);
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
										$error_min_guest = $check_min_guest_row['min_num_guest'];										
									}
								}
								//howard added if total_no_guest_tour < min_num_guest display error end
								
								if($errormessageperson != '' || $error_min_guest != '')
								{
									
								}
								else
								{
								
								
								$total_room_price = $room_price[0]+$room_price[1]+$room_price[2]+$room_price[3]+$room_price[4]+$room_price[5];
								$total_room_price_cost = $room_price_cost[0]+$room_price_cost[1]+$room_price_cost[2]+$room_price_cost[3]+$room_price_cost[4]+$room_price_cost[5];
								
								//amit added to add extra 3% if agency is L&L Travel start
								$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['products_id']);
								if($pro_agency_info_array['final_transaction_fee'] > 0){								
									$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);									
									$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,$pro_agency_info_array['final_transaction_fee']);												
								}								
								//amit added to add extra 3% if agency is L&L Travel end
								
								
								
								//update lodging change start here Nirav  0000-00-00
								$get_data_lodgin1=get_older_values_for_update($HTTP_GET_VARS['opID']);
								if($get_data_lodgin1['total_room_adult_child_info_old']!=$total_room_adult_child_info){
								
									$sql_check_new_entry2="SELECT op_order_products_ids from ".ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY." where op_order_products_ids=".$HTTP_GET_VARS['opID']." limit 1";
									$run_check_new_entry2=tep_db_query($sql_check_new_entry2);									
									if(tep_db_num_rows($run_check_new_entry2) == 0){
										create_update_history_orders($HTTP_GET_VARS['opID'],$get_data_lodgin1['depature_date_old'],$get_data_lodgin1['total_room_adult_child_info_old'],$get_data_lodgin1['guest_name_old'],$get_data_lodgin1['date_purchased']);
									}
								
									create_update_history_orders($HTTP_GET_VARS['opID'],$get_data_lodgin1['depature_date_old'],$total_room_adult_child_info,$get_data_lodgin1['guest_name_old']);
								}
								//update lodging change end here Nirav  0000-00-00
								
							$sql_data_array = array('products_room_info' => $total_info_room,
											'total_room_adult_child_info' => $total_room_adult_child_info,
											);
							if($OOC->checkIsChange(TABLE_ORDERS_PRODUCTS, 'products_room_info', $total_info_room," orders_products_id=".(int)$HTTP_POST_VARS['orders_products_id'])||$OOC->checkIsChange(TABLE_ORDERS_PRODUCTS, 'total_room_adult_child_info', $total_room_adult_child_info," orders_products_id=".(int)$HTTP_POST_VARS['orders_products_id'])){
								$OOC->addRecord($login_id,2,$_GET['oID']);
							}
							//tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array,"update"," orders_id=".(int)$oID." and products_id=".(int)$HTTP_POST_VARS['products_id']);
								tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array,"update"," orders_products_id=".(int)$HTTP_POST_VARS['orders_products_id']);//hotel_extension
								//tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
								
								
								
								
									$total_rooms = get_total_room_from_str($total_room_adult_child_info);
								
								    $room_total_adults = 0;
									$room_total_children = 0;
									$products_name ='<table width="100%" cellspacing="0" cellpadding="0" border="0">';
											$products_name .='<tr><td class="p_l1 tab_t_bg ">No. of  Room</td><td class="tab_t_bg ">Adult</td><td class="tab_t_bg ">Child</td></tr>';
											for($t=1;$t<=$total_rooms;$t++){
											 $chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,$t);
											 $room_total_adults = $room_total_adults + $chaild_adult_no_arr[0];
											 $room_total_children = $room_total_children + $chaild_adult_no_arr[1];
						
											$products_name .='<tr><td class="p_l1 order_default"><span>'.$t.'</span></td><td class="order_default">'.$chaild_adult_no_arr[0].'</td><td class="order_default">'.$chaild_adult_no_arr[1].'</td></tr>';
											}
											$products_name .='</table>';
									
									
								//write change div and update div code start
								echo $room_total_adults_old = $HTTP_GET_VARS['room_total_adults_old'];
								echo $room_total_children_old = $HTTP_GET_VARS['room_total_children_old'];
								
								if($room_total_adults_old != $room_total_adults || $room_total_children_old != $room_total_children){
									//tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set is_adjustments_needed = '1||1||1||1' where  orders_id='".(int)$oID."' and products_id='".(int)$HTTP_POST_VARS['products_id']."'");
									tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set is_adjustments_needed = '1||1||1||1' where  orders_products_id='".(int)$HTTP_POST_VARS['orders_products_id']."'");//hotel-extension
								}
							
							if($room_total_adults_old != $room_total_adults || $room_total_children_old != $room_total_children){
								/*
								<script type="text/javascript">
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_GUESTNAME_RED_BOX; ?>';
									parent.document.getElementById('done_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_LODGING_RED_BOX; ?>';
									parent.document.getElementById('done_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									if(parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id]; ?>')){
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_PROVIDER_RED_BOX; ?>';
									parent.document.getElementById('done_provider_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									}
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_PRICE_RED_BOX; ?>';
									parent.document.getElementById('done_price_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
								</script>*/
								//hotel-extension start
								?>
								<script>
									<script type="text/javascript">
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[orders_products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[orders_products_id];?>').title = '<?php echo TITLE_GUESTNAME_RED_BOX; ?>';
									parent.document.getElementById('done_guestname_<?php echo $HTTP_POST_VARS[orders_products_id];?>').style.display = '';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[orders_products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[orders_products_id];?>').title = '<?php echo TITLE_LODGING_RED_BOX; ?>';
									parent.document.getElementById('done_lodging_<?php echo $HTTP_POST_VARS[orders_products_id];?>').style.display = '';
									if(parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[orders_products_id]; ?>')){
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[orders_products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[orders_products_id];?>').title = '<?php echo TITLE_PROVIDER_RED_BOX; ?>';
									parent.document.getElementById('done_provider_<?php echo $HTTP_POST_VARS[orders_products_id];?>').style.display = '';
									}
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[orders_products_id];?>').className = 'tab_line1 p_t red_border';
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[orders_products_id];?>').title = '<?php echo TITLE_PRICE_RED_BOX; ?>';
									parent.document.getElementById('done_price_<?php echo $HTTP_POST_VARS[orders_products_id];?>').style.display = '';
								</script>
								<?php
									//hotel-extension end
							}
								?>
								<script type="text/javascript">
								var txtPickup = "<?php echo addslashes($products_name);?>";
								//parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").innerHTML=txtPickup;
								//parent.HideContent("edit_cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>");
								//parent.ShowContent("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>");
								parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['orders_products_id'];?>").innerHTML=txtPickup;
								parent.HideContent("edit_cart_product_data_<?php echo $HTTP_POST_VARS['orders_products_id'];?>");
								parent.ShowContent("cart_product_data_<?php echo $HTTP_POST_VARS['orders_products_id'];?>");//hotel-extension
								//reloadParent();
								//window.location="ajax_edit_room_info.php?products_id=<?php echo $HTTP_GET_VARS['products_id'];?>";
								window.location="ajax_edit_room_info.php?products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&product_number=<?php echo $HTTP_GET_VARS['product_number'];?>&oID=<?php echo $HTTP_GET_VARS['oID'];?>&room_total_adults_old=<?php echo $room_total_adults_old; ?>&room_total_children_old=<?php echo $room_total_children_old; ?>&opID=<?php echo $HTTP_GET_VARS['opID']; ?>";
								</script>
								<?php
								//write change div and update div code end
								}
							
							}else{  //if($_POST['numberOfRooms'])//if there is rooms 
							
								$totaladultticket = $_POST['room-0-adult-total'];
								if($_POST['room-0-child-total']!='')
								$totalchildticket = $_POST['room-0-child-total'];
								
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
											
								$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_ADULTS_NO." : ".$totaladultticket;
												if($_POST['room-'.$i.'-child-total']!='0')
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_CHILDREDN_NO." : ".$totalchildticket;
												$total_info_room .= "<br>".TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL." : $".number_format($total_room_price,2);
								
								//amit added to add extra 3% if agency is L&L Travel start
								$pro_agency_info_array = tep_get_tour_agency_information((int)$HTTP_POST_VARS['products_id']);
								if($pro_agency_info_array['final_transaction_fee'] > 0){								
									$total_room_price = tep_get_total_fares_includes_agency($total_room_price,$pro_agency_info_array['final_transaction_fee']);									
									$total_info_room .= "<br>".sprintf(TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,$pro_agency_info_array['final_transaction_fee']);												
								}								
								//amit added to add extra 3% if agency is L&L Travel end	
								
								//howard added if total_no_guest_tour < min_num_guest display error
								$error_min_guest = '';
								$check_min_guest_sql = tep_db_query('SELECT min_num_guest FROM `products` WHERE products_id="'.(int)$HTTP_POST_VARS['products_id'].'" limit 1');
								$check_min_guest_row = tep_db_fetch_array($check_min_guest_sql);
								if($total_no_guest_tour < 1 || $total_no_guest_tour < $check_min_guest_row['min_num_guest']){
									$error_min_guest = $check_min_guest_row['min_num_guest'];
									tep_redirect(tep_href_link('ajax_edit_room_info.php', 'products_id=' . $HTTP_POST_VARS['products_id'].'&error_min_guest='.$check_min_guest_row['min_num_guest'].''));
									exit();
								}
								//update lodging change start here Nirav  0000-00-00
								$get_data_lodgin=get_older_values_for_update($HTTP_GET_VARS['opID']);								
								if($get_data_lodgin['total_room_adult_child_info_old']!=$total_room_adult_child_info){
								
								$sql_check_new_entry3="SELECT op_order_products_ids from ".ORDER_PRODUCTS_DEPARTURE_GUEST_HISTOTY." where op_order_products_ids=".$HTTP_GET_VARS['opID']." limit 1 ";
								$run_check_new_entry3=tep_db_query($sql_check_new_entry3);
								if(tep_db_num_rows($run_check_new_entry3) == 0){
									create_update_history_orders($HTTP_GET_VARS['opID'],$get_data_lodgin['depature_date_old'],$get_data_lodgin['total_room_adult_child_info_old'],$get_data_lodgin['guest_name_old'],$get_data_lodgin['date_purchased']);
								}
								
								create_update_history_orders($HTTP_GET_VARS['opID'],$get_data_lodgin['depature_date_old'],$total_room_adult_child_info,$get_data_lodgin['guest_name_old']);
								}
								//howard added if total_no_guest_tour < min_num_guest display error end								
								
											
								$sql_data_array = array('products_room_info' => $total_info_room,
											'total_room_adult_child_info' => $total_room_adult_child_info,
											);
							tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array,"update"," orders_id=".(int)$oID." and products_id=".(int)$HTTP_POST_VARS['products_id']);
								
								
								 $chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info, 1);
									$total_adults = $chaild_adult_no_arr[0];
									$total_children = $chaild_adult_no_arr[1];
								
								$products_name ='<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td class="tab_t_bg ">Adult</td><td class="tab_t_bg ">Child</td></tr><tr><td class="order_default">'.$total_adults.'</td><td class="order_default">'.$total_children.'</td></tr></table>';
								$room_total_adults = 0;
								$room_total_children = 0;
								
								$room_total_adults = $total_adults;
								$room_total_children = $total_children;
								echo $room_total_adults_old = $HTTP_GET_VARS['room_total_adults_old'];
							echo $room_total_children_old = $HTTP_GET_VARS['room_total_children_old'];
							if($room_total_adults_old != $room_total_adults || $room_total_children_old != $room_total_children){
							tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set is_adjustments_needed = '1||1||1||1' where orders_id='".(int)$oID."' and products_id='".(int)$HTTP_POST_VARS['products_id']."'");
							}
							
							if($room_total_adults_old != $room_total_adults || $room_total_children_old != $room_total_children){
								?>
								<script type="text/javascript">
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '1px solid red';
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_GUESTNAME_RED_BOX; ?>';
									parent.document.getElementById('done_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '1px solid red';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_LODGING_RED_BOX; ?>';
									parent.document.getElementById('done_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									if(parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id]; ?>')){
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '1px solid red';
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_PROVIDER_RED_BOX; ?>';
									parent.document.getElementById('done_provider_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
									}
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '1px solid red';
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').title = '<?php echo TITLE_PRICE_RED_BOX; ?>';
									parent.document.getElementById('done_price_<?php echo $HTTP_POST_VARS[products_id];?>').style.display = '';
								</script>
								<?php			
							}/*else{
								?>
								<script type="text/javascript">
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '0px';
									parent.document.getElementById('red_alerts_guestname_<?php echo $HTTP_POST_VARS[products_id];?>').title = '';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '0px';
									parent.document.getElementById('red_alerts_lodging_<?php echo $HTTP_POST_VARS[products_id];?>').title = '';
									if(parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id]; ?>')){
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '0px';
									parent.document.getElementById('red_alerts_provider_<?php echo $HTTP_POST_VARS[products_id];?>').title = '';
									}
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').style.border = '0px';
									parent.document.getElementById('red_alerts_price_<?php echo $HTTP_POST_VARS[products_id];?>').title = '';
								</script>
								<?php
							}*/
								//write change div and update div code start
								?>
								<script type="text/javascript">
								var txtPickup = "<?php echo addslashes($products_name);?>";
								parent.document.getElementById("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>").innerHTML=txtPickup;
								parent.HideContent("edit_cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>");
								parent.ShowContent("cart_product_data_<?php echo $HTTP_POST_VARS['products_id'];?>");
								window.location="ajax_edit_room_info.php?products_id=<?php echo $HTTP_GET_VARS['products_id'];?>&product_number=<?php echo $HTTP_GET_VARS['product_number'];?>&oID=<?php echo $HTTP_GET_VARS['oID'];?>&guest_total_adults=<?php echo $guest_total_adults; ?>&guest_total_children=<?php echo $guest_total_children; ?>&opID=<?php echo $HTTP_GET_VARS['opID']; ?>";
								</script>
								<?php
								
								//write change div and update div code end
							} //elseif($_POST['numberOfRooms'])//if there is rooms 
							
						}                 
   break;
 }

// BOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_info_query = tep_db_query("select p.products_video, p.products_type, p.operate_start_date ,p.operate_end_date,p.products_single,p.products_double,p.products_triple,p.products_quadr,p.products_kids, p.display_room_option,p.maximum_no_of_guest,p.products_id, pd.products_name, pd.products_description, pd.products_pricing_special_notes,  pd.products_other_description, p.products_is_regular_tour, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.agency_id, p.display_pickup_hotels from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_info = tep_db_fetch_array($product_info_query);
	//amit modified to make sure price on usd
$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product_info['products_id']);
if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
    $product_info['products_price'] = tep_get_tour_price_in_usd($product_info['products_price'],$tour_agency_opr_currency);
    $product_info['products_single'] = tep_get_tour_price_in_usd($product_info['products_single'],$tour_agency_opr_currency);
	$product_info['products_double'] = tep_get_tour_price_in_usd($product_info['products_double'],$tour_agency_opr_currency);
	$product_info['products_triple'] = tep_get_tour_price_in_usd($product_info['products_triple'],$tour_agency_opr_currency);
    $product_info['products_quadr'] = tep_get_tour_price_in_usd($product_info['products_quadr'],$tour_agency_opr_currency);
    $product_info['products_kids'] = tep_get_tour_price_in_usd($product_info['products_kids'],$tour_agency_opr_currency);
}	
 //amit modified to make sure price on usd

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
   	MCache::update_product($HTTP_GET_VARS['products_id']);//MCache update
    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'];  // . '&nbsp;&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }

$product_rank = $HTTP_GET_VARS['product_number'];
if (($product_rank/2) == floor($product_rank/2)) {
        $class_body = 'style="background-color:#fafafa"';				
      } else {
	  	$class_body = 'style="background-color:#ffffff"';			
	  }
?>
<body <?php echo $class_body; ?>>
<?php 
echo tep_draw_form('cart_quantity', 'ajax_edit_room_info.php', tep_get_all_get_params(array('action','maxnumber','update_frame','min_num_guest')). 'action_ajax_add=add_product','post','id="cart_quantity"');
 ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
			
			<table   border="0" width="100%" cellspacing="0" cellpadding="0">
							  <tr>
								<td valign="top">
			
					 <table  border="0" width="100%" cellspacing="0" cellpadding="0">
							 <?php
							 if($errormessageperson != ''){ 
							 ?>
							 <tr><td >
							 <table border="0" width="100%"   cellspacing="0" cellpadding="2">
							  <tr>
								<td class="errorBox1" colspan="2"><img src="<?php echo DIR_WS_IMAGES;?>icons/warning.gif" border="0" alt="Warning" title=" Warning " 
							
							width="10" height="10">&nbsp;&nbsp;<?php echo TEXT_MAX_ALLOW_ROOM.' '.$errormessageperson; ?></td> 
							  </tr>
							</table>
							
							 </td></tr>
							  <?php
							 }
							 ?>
							 <?php
							 // howard added error_min_guest
							 if($error_min_guest != ''){ 
							 ?>
							 <tr><td >
							 <table border="0" width="100%"   cellspacing="0" cellpadding="2">
							  <tr>
								<td class="errorBox1" colspan="2"><img src="<?php echo DIR_WS_IMAGES;?>icons/warning.gif" border="0" alt="<?php echo TXT_ALT_WARNING; ?>" title=" <?php echo TXT_ALT_WARNING; ?> " 
							
							width="10" height="10">&nbsp;&nbsp;<?php echo TEXT_PRODUCTS_MIN_GUEST;?> <?php echo $error_min_guest; ?></td> 
							  </tr>
							</table>
							
							 </td></tr>
							  <?php
							 }
							 // howard added error_min_guest end
							 ?>
							 
							</table>
				<div class="shopping_edit_main">
		        
<?php 			
				if($display_room_option>0)
				  {
				  echo'<div class="eabo_co_rt">
								<div id="hot-search-params" class="nopadtop"></div>
							</div>';
				  }elseif($display_room_option==0)
				  {
				  echo'<div class="eabo_co_rt">
								<div id="hot-search-params" class="nopadtop"></div>
							</div>';
				  }			
				  
	echo '<div class="eabo_co_rt notextpad">';
	if(TRAVEL_COMPANION_OFF_ON=='true'){
		echo '<input name="travel_comp" id="travel_comp" type="hidden" />';
	}
	/*
	echo tep_draw_hidden_field('products_id', $products_id) . tep_image_submit('button_update.gif', 'Update','onclick="return validate()"');//'onclick="return validate()" 
    echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.(int)$products_id.'\');parent.ShowContent(\'cart_product_data_'.(int)$products_id.'\');  return true;"  href="javascript:parent.HideContent(\'edit_cart_product_data_'.(int)$products_id.'\');parent.ShowContent(\'cart_product_data_'.(int)$products_id.'\');">'. tep_image_button('button_cancel.gif', IMAGE_BUTTON_CANCEL,'','') . '</a>';
	echo '</div>';*/
	echo tep_draw_hidden_field('products_id', $products_id) . tep_draw_hidden_field('orders_products_id', $orders_products_id) . tep_image_submit('button_update.gif', 'Update','onclick="return validate()"');//'onclick="return validate()" 
    echo '&nbsp;<a onClick="parent.HideContent(\'edit_cart_product_data_'.(int)$orders_products_id.'\');parent.ShowContent(\'cart_product_data_'.(int)$orders_products_id.'\');  return true;"  href="javascript:parent.HideContent(\'edit_cart_product_data_'.(int)$orders_products_id.'\');parent.ShowContent(\'cart_product_data_'.(int)$orders_products_id.'\');">'. tep_image_button('button_cancel.gif', IMAGE_BUTTON_CANCEL,'','') . '</a>'; //hotel-extension
	echo '</div>';
 ?> 
 			</div>
 			</td>
     		 </tr>
			</table>
</form>

<script type="text/javascript">
				<!--
				    // NOTE: customize variables in this javascript block as appropriate.
				    var defaultAdults="2";
				    var cellStyle=" class='main' style='padding:0 3px 0 3px;'";
				    var childHelp="<?php echo TXT_CHILDHELP; ?>";
				    var adultHelp="";
				    var textRooms="<?php echo TXT_ROOMS; ?>";
				    var textAdults="<?php echo TXT_ADULTS; ?>";
				    var textChildren="<?php echo TXT_CHILDREN; ?>";
				    var textChildError="<?php echo TXT_CHILD_ERROR; ?>";
				    var pad='';
				    // NOTE: Question marks ("?") get replaced with a numeric value
				    var textRoomX="<?php echo TXT_ROOM_X; ?>";
				    var textChildX="<?php echo TXT_CHILD_X; ?>";

				//--> 
				</SCRIPT>
								
<!-- NOTE: DO NOT MODIFY THIS JAVASCRIPT BLOCK -->


      <SCRIPT language=javascript>
				<!--
				    var adultsPerRoom=new Array(defaultAdults);
				    var childrenPerRoom=new Array();
				    var childAgesPerRoom=new Array();						
				    var numRooms=1;
				    var maxChildren=0;
<?php
$maxPerRoomPeopleNum = 4;
$sql = tep_db_query('SELECT maximum_no_of_guest FROM '.TABLE_PRODUCTS.' WHERE products_id ="'.(int)$products_id.'" ');
$rows = tep_db_fetch_array($sql);
if((int)$rows['maximum_no_of_guest']){
	$maxPerRoomPeopleNum = (int)$rows['maximum_no_of_guest'];
}
?>

var maxPerRoomPeopleNum = <?=$maxPerRoomPeopleNum?>;
function sub_rooms_people_num(){
	var cart_quantity = document.getElementById("cart_quantity");
	if(cart_quantity==null){
		return false;
	}
	
	var numberOfRooms = cart_quantity.elements['numberOfRooms'];
	if(numberOfRooms!=null){
		if(numberOfRooms.value==16){
			var room_num = 1;
		}else{
			var room_num = cart_quantity.elements['numberOfRooms'].value;
		}
		for(i=0; i<room_num; i++){
			var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
			var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
			var adult_options = adult_select.options;
			for(j=(adult_options.length-1); j>=maxPerRoomPeopleNum; j--){
				//alert(adult_options.length);
				adult_select.remove(j);
				//adult_options.selectedIndex = (adult_options.length-1);
			}
			var child_options = child_select.options;
			for(j=(child_options.length-1); j>=maxPerRoomPeopleNum; j--){
				child_select.remove(j);
			}
			
		}
	}
}

function set_child_option(){
	var cart_quantity = document.getElementById("cart_quantity");
	if(cart_quantity==null){
		return false;
	}
	
	var numberOfRooms = cart_quantity.elements['numberOfRooms'];
	if(numberOfRooms!=null){
		if(numberOfRooms.value==16){
			var room_num = 1;
		}else{
			var room_num = cart_quantity.elements['numberOfRooms'].value;
		}
		for(i=0; i<room_num; i++){
			var adult_select = cart_quantity.elements['room-'+ i +'-adult-total'];
			var child_select = cart_quantity.elements['room-'+ i +'-child-total'];
			
			//alert(adult_select.value);
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
	}
}

					
					<?php								
								if($display_room_option>0){
									
											$ttl_rooms = get_total_room_from_str($total_room_adult_child_info);
											if($ttl_rooms>0){

													if(is_travel_comp((int)$oID, (int)$products_id) > 0 ){
													echo ' setNumRooms(16); ';													
													}else{
													echo ' setNumRooms('.$ttl_rooms.'); ';												
													}

													for($ir=0; $ir<$ttl_rooms; $ir++){

													 $chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,$ir+1);


													echo ' setNumAdults('.$ir.','.$chaild_adult_no_arr[0].'); ';
													echo ' setNumChildren('.$ir.','.$chaild_adult_no_arr[1].'); ';


													}

											}
											
								}else{
								
								
											$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($total_room_adult_child_info,1);
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
				    }

				    function setNumChildren(room, numChildren) {
				        childrenPerRoom[room] = numChildren;
				       // refresh();
				    }

				    function setNumRooms(x) {
				        numRooms = x;
						
						//parent.calcHeight("iframe_prod_<?php echo (int)$product_info['products_id'];?>");
						parent.calcHeight_increase("iframe_prod_<?php echo (int)$orders_products_id;?>",numRooms*10);//hotel-extension parent.calcHeight_increase("iframe_prod_<?php echo (int)$products_id;?>",numRooms*10);
				        for (i = 0; i < x; i++) {
				            if (adultsPerRoom[i] == null) {
				                adultsPerRoom[i] = 2;
				            }
				            if (childrenPerRoom[i] == null) {
				                childrenPerRoom[i] = 0;
				            }
				        }
				        refresh();
				    }
					
			
				    function renderRoomSelect() {
				        var x = '';
				        x += '<select  class="sel2" style="width:70px;"  name="numberOfRooms" onchange="setNumRooms(this.options[this.selectedIndex].value);">'; // id="numberOfRooms"
						for (var i = 1; i < 17; i++) {
							if(i==16){
								<?php
								//travel companion order
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
				        if (adultHelp.length > 0) {
				            x = adultHelp + "<p>\n";
				        }

				        if (numRooms > 17) {
				            x += textRooms;
				            x += renderRoomSelect();

				        } else {
				            x += '<table border="0" cellspacing="0" cellpadding="0">\n';
							x += '<tr><td'+cellStyle+'>';
							<?php if($display_room_option>0)
							  {
							 ?>
				            x += textRooms+pad;
							<?php 
							  }
							 ?>
							 x += '</td>';
				            if (numRooms > 1 && numRooms < 16) {
				                x += '<td'+cellStyle+'>&nbsp;</td>';
				            }
				            x += '<td'+cellStyle+'><nobr>'+textAdults+pad+'</nobr></td><td'+cellStyle+'><nobr>'+textChildren+pad+'</nobr></td></tr>\n';
				            for (var i = 0; i < numRooms; i++) {
				                x += '<tr><td'+cellStyle+'>';
				                if (i == 0) {
									<?php if($display_room_option>0)
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
				                    x += '<td'+cellStyle+'><nobr>'+getValue(textRoomX, i+1)+pad + '</nobr></td>';
				                }
				                x += '<td'+cellStyle+'>';
				                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value)', 1, 20, adultsPerRoom[i]);
				                x += '</td><td'+cellStyle+'>';
				                x += buildSelect('room-' + i + '-child-total', '', 0, 10, childrenPerRoom[i]);//setNumChildren(' + i + ', this.options[this.selectedIndex].value)
				                x += '</td></tr>\n';
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

				         var didHeader = false;
				            for (var i = 0; i < numRooms; i++) {
							    if (childrenPerRoom[i] > 50000) {
				                    if (!didHeader) {
				                        x += '<table width="100" border="0" cellpadding="0" cellspacing="0">\n';
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
				                        x += '<option value="-1"'+(def == -1 ? ' selected="selected"' : '')+'>-?-';
				                        x += '<option value="0"'+(def == 0 ? ' selected="selected"' : '')+'>&lt;1';
				                        for (var k = 1; k <= 18; k++) {
				                            x += '<option value="'+k+'"'+(def == k ? ' selected="selected"' : '')+'>'+k;
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
				        document.getElementById("hot-search-params").innerHTML = x;
								sub_rooms_people_num();
								set_child_option();
				    }
					  function buildSelect(name, onchange, min, max, selected) {
				        var x = '<select class="sel2" name="' + name + '"'; //id="' + name + '"
				        if (onchange != null) {
				            x += ' onchange="' + onchange + '"';
				        }
				        x +='>\n';
				        for (var i = min; i <= max; i++) {
				            x += '<option value="' + i + '"';
				            if (i == selected) {
				                x += ' selected="selected"';
				            }

				            x += '>' + i + '\n';
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
					
				//-->
				</SCRIPT>
				
				
<script type="text/javascript">
function validate(){
	return true;
}
</script>
<?php

    $user_browser = browser_detection('browser');
	if($att_cnt>0 && $user_browser=='mozilla'){	
	?>
	<script type="text/javascript">
	//parent.calcHeight_increase("iframe_prod_<?php echo (int)$products_id;?>",<?php echo (int)$att_cnt*20;?>);
	parent.calcHeight_increase("iframe_prod_<?php echo (int)$orders_products_id;?>",<?php echo (int)$att_cnt*20;?>);
	</script>
	<?php	
	}
	?>