<?php 

$product_query = "select * from ".TABLE_PRODUCTS." where products_id=".$add_product_products_id."";
$product_row = tep_db_query($product_query);
$product_result = tep_db_fetch_array($product_row);

$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($add_product_products_id);
			
				$forcecheck = '1';
				if($product_info['products_type'] == 3 || $forcecheck == '1'){ //amit added -- check regular irregular tour start
				
				        $num_of_sections = regu_irregular_section_numrow((int)$add_product_products_id);
						
						
						if($num_of_sections > 0){
						$regu_irregular_array = regu_irregular_section_detail((int)$add_product_products_id);
						foreach($regu_irregular_array as $k=>$v)
						{
							if(is_array($v))
							{
							
							 $tourcatetype =	$regu_irregular_array[$k]['producttype'];
							$opestartdate =  $regu_irregular_array[$k]['operate_start_date'];
							$opeenddate =  $regu_irregular_array[$k]['operate_end_date'];
							
							
								if($tourcatetype == 1){  //regular your
										///////////new regu lar tour start
										$daycount = 1;
										$day1 = '';
									
										$extracharges = '';
										$prifix = '';
									$operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$add_product_products_id."  and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by products_start_day");
									
									  //amit added to fixed year for exiting tour
									  /*
									  if(strlen($opestartdate) == 5){
										$opestartdate = $opestartdate."-".date("Y");
									  }										
									  if(strlen($opeenddate) == 5){
										$opeenddate = $opeenddate."-".(date("Y")+1);
									  }
									  if(strlen($opestartdate) == 6){
										$opestartdate = $opestartdate.date("Y");
									  }										
									  if(strlen($opeenddate) == 6){
										$opeenddate = $opeenddate.(date("Y")+1);
									  }
									  */
									  while($operator_result = tep_db_fetch_array($operator_query))
									  {
										if($operator_result['products_start_day'] == 1){$day1 .= 'Sun/';}
										if($operator_result['products_start_day'] == 2){$day1 .= 'Mon/';}
										if($operator_result['products_start_day'] == 3){$day1 .= 'Tue/';}
										if($operator_result['products_start_day'] == 4){$day1 .= 'Wed/';}
										if($operator_result['products_start_day'] == 5){$day1 .= 'Thu/';}
										if($operator_result['products_start_day'] == 6){$day1 .= 'Fri/';}
										if($operator_result['products_start_day'] == 7){$day1 .= 'Sat/';}
										$daycount++;										
										if($operator_result['extra_charge']!='0.00' && $operator_result['extra_charge']!='')
										{
											//amit modified to make sure price on usd
											if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
										    	$operator_result['extra_charge'] = tep_get_tour_price_in_usd($operator_result['extra_charge'],$tour_agency_opr_currency);
										    }
											//amit modified to make sure price on usd
											$extracharges[$operator_result['products_start_day']] = ' $'.$operator_result['extra_charge'].')';
											$prifix[$operator_result['products_start_day']] = '('.$operator_result['prefix'];
											$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
										
										}else{
											$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
										}
										
										if($operator_result['spe_single'] > 0 || $operator_result['spe_kids'] > 0){											
											$add_spl_price_note_regular[$operator_result['products_start_day']] = ' ('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')';
											$display_pricing_for_reg_special_price = 'true';
											$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
										}
										
									  }
									  
									  
										
											
											if($daycount == 8)
											{
												$countprice = 0;
												for($adate=2;$adate<=365; $adate++)
												{
													$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
													$from1 = date ("Y-m-d (D)", $from2);
													$formval = date ("Y-m-d", $from2);
													
													//amit addedd to modify for specific month start
													$isvaliddatecheck ='';
													$startDate = $opestartdate;									
													$checkDate = date ("m-d-Y", $from2);									
													$endDate = $opeenddate;

													$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
													//amit added to modify for specific month end
													
													$check = date ("D", $from2);
													if($countprice == 0)
													{
														if($check == 'Sun')	$countprice = 1;
														elseif($check == 'Mon')$countprice = 2;
														elseif($check == 'Tue')$countprice = 3;
														elseif($check == 'Wed')$countprice = 4;
														elseif($check == 'Thu')$countprice = 5;
														elseif($check == 'Fri')$countprice = 6;
														elseif($check == 'Sat')$countprice = 7;
													}
													if($isvaliddatecheck == "valid"){													
													$checkselected = '';
													if(isset($_POST['availabletourdate']) &&  $_POST['availabletourdate'] == $formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice]){
													$checkselected = ' selected ';
													}
													$avaliabledate .= '<option '.$checkselected.' value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].$add_spl_price_note_regular[$countprice].'</option>';
													}
													$countprice++;
														if($countprice == 8)
														{
															$countprice = 1;
														}
												}
											}
											else
											{
												$twnetycount = 0;
												$countprice = 0;
												for($adate=2;$adate<=365; $adate++)
												{
													$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
													$from1 = date ("Y-m-d (D)", $from2);
													$check = date ("D", $from2);
													$formval = date ("Y-m-d", $from2);
													
													//amit addedd to modify for specific month start
													$isvaliddatecheck ='';
													$startDate = $opestartdate;									
													$checkDate = date ("m-d-Y", $from2);									
													$endDate = $opeenddate;
													
													$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
													//amit added to modify for specific month end
													
													
													$getmonth = date ("m", $from2);
													
													
													if($countprice == 0)
													{
														if($check == 'Sun')$countprice = 1;
														elseif($check == 'Mon')$countprice = 2;
														elseif($check == 'Tue')$countprice = 3;
														elseif($check == 'Wed')$countprice = 4;
														elseif($check == 'Thu')$countprice = 5;
														elseif($check == 'Fri')$countprice = 6;
														elseif($check == 'Sat')$countprice = 7;
													}	
														// && $isvaliddatecheck == "valid"
													if(strstr($day1, $check) && $isvaliddatecheck == "valid")
													{
													
													$checkselected = '';													
													if(isset($_POST['availabletourdate']) &&  $_POST['availabletourdate'] == $formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice]){
													$checkselected = ' selected ';
													}
														$avaliabledate .= '<option '.$checkselected.' value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].$add_spl_price_note_regular[$countprice].'</option>';
														
														$twnetycount++;
														//if($twnetycount==20)
														//break;
													}
														
													$countprice++;
													if($countprice == 8)
													{
														$countprice = 1;
													}
													
												}// end of for($adate=7;$adate<=200; $adate++)
											}
										//new regular tour end									
								}else{ //irregular tours									
									$available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$add_product_products_id." and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by available_date";
									$available_query = tep_db_query($available_query_sql);
									while($available_result = tep_db_fetch_array($available_query))
									{
											//echo "in";
												//echo '<br>88--'.$available_result['available_date'];
												//exit;
												$y = substr($available_result['available_date'], 0, 4);
												$m = substr($available_result['available_date'], 5, 2);
												$d = substr($available_result['available_date'], 8, 2);
												
												$extracharges = '';
												$prifix = '';
												if($available_result['extra_charge']!='0.00' && $available_result['extra_charge']!='')
												{
													//amit modified to make sure price on usd
													if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
														$available_result['extra_charge'] = tep_get_tour_price_in_usd($available_result['extra_charge'],$tour_agency_opr_currency);
													}
													//amit modified to make sure price on usd
													$extracharges = ' $'.$available_result['extra_charge'].')';
													$prifix = '('.$available_result['prefix'];
												}
												$add_spl_price_note = '';
												if($available_result['spe_single'] > 0 || $available_result['spe_kids'] > 0){
													$add_spl_price_note = ' ('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')';
													$display_pricing_for_special_price = 'true';
												}
												
												$from2 =  mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
												$from1 = date ("Y-m-d (D)", $from2);
												$formval = date ("Y-m-d", $from2);
												
												//amit addedd to modify for specific month start
												$isvaliddatecheck ='';
												$startDate = $opestartdate;																						
												$checkDate = date ("m-d-Y", $from2);									
												 $endDate = $opeenddate;
												
												 $isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
												//amit added to modify for specific month end
												if($isvaliddatecheck == "valid" && $from1 >= date('Y-m-d')){
														$checkselected = '';
														
														$sel_date_irreg_array = explode('::',$_POST['availabletourdate']);
														if(isset($_POST['availabletourdate']) &&  $sel_date_irreg_array[0] == $formval){
															$checkselected = ' selected ';
														}
																										
														$avaliabledate .= '<option  '.$checkselected.' value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.$prifix.$extracharges.$add_spl_price_note.'</option>';
												}
									}
								
								
								
								}//reg-irregular tours end of ifelse
							
							
							} //end of if if array is blank
						} //end of foreach loop
						}//end of if for check section > 0
						
					
				}else{  //if tour is either regular or irregular
								$avaliabledate = '';
								$from2 = '';
								$from1 = '';
								if($product_info['products_is_regular_tour'] == 0)
								{	
									$available_query = tep_db_query("select * from ".TABLE_PRODUCTS_AVAILABLE." where products_id = ".(int)$add_product_products_id." order by available_date");
									while($available_result = tep_db_fetch_array($available_query))
									{
										//echo '<br>'.$available_result['available_date'];
										$y = substr($available_result['available_date'], 0, 4);
										$m = substr($available_result['available_date'], 5, 2);
										$d = substr($available_result['available_date'], 8, 2);
										
										$extracharges = '';
										$prifix = '';
										if($available_result['extra_charges']!='0.00' && $available_result['extra_charges']!='')
										{
											$extracharges = ' $'.$available_result['extra_charges'].')';
											$prifix = '('.$available_result['prefix'];
										}
										
										$from2 =  mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
										$from1 = date ("Y-m-d (D)", $from2);
										$formval = date ("Y-m-d", $from2);
										
										//amit addedd to modify for specific month start
													$isvaliddatecheck ='';
													$startDate = $product_info['operate_start_date'];									
													$checkDate = date ("m-d", $from2);									
													$endDate = $product_info['operate_end_date'];
													
													$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
													//amit added to modify for specific month end
													if($isvaliddatecheck == "valid" && $from1 >= date('Y-m-d')){
															$avaliabledate .= '<option value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.$prifix.$extracharges.'</option>';
													}
									}
								
								}elseif($product_info['products_is_regular_tour'] == 1)
								{
									$daycount = 1;
									$day1 = '';
									
										$extracharges = '';
										$prifix = '';
									$operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_START_DATE." where products_id = ".(int)$add_product_products_id." order by products_start_day");
									  while($operator_result = tep_db_fetch_array($operator_query))
									  {
										
										if($operator_result['products_start_day'] == 1)
										{
											$day1 .= 'Sun/';
										}
										if($operator_result['products_start_day'] == 2)
										{
											$day1 .= 'Mon/';
										}
										if($operator_result['products_start_day'] == 3)
										{
											$day1 .= 'Tue/';
										}
										if($operator_result['products_start_day'] == 4)
										{
											$day1 .= 'Wed/';
										}
										if($operator_result['products_start_day'] == 5)
										{
											$day1 .= 'Thu/';
										}
										if($operator_result['products_start_day'] == 6)
										{
											$day1 .= 'Fri/';
										}
										if($operator_result['products_start_day'] == 7)
										{
											$day1 .= 'Sat/';
										}
										$daycount++;
										
										if($operator_result['extra_charge']!='0.00' && $operator_result['extra_charge']!='')
										{
											$extracharges[$operator_result['products_start_day']] = ' $'.$operator_result['extra_charge'].')';
											$prifix[$operator_result['products_start_day']] = '('.$operator_result['prefix'];
										}
										
										
									  }
									  
									  
										
											
											if($daycount == 8)
											{
												$countprice = 0;
												for($adate=2;$adate<=365; $adate++)
												{
													$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
													$from1 = date ("Y-m-d (D)", $from2);
													$formval = date ("Y-m-d", $from2);
													
													//amit addedd to modify for specific month start
													$isvaliddatecheck ='';
													$startDate = $product_info['operate_start_date'];									
													$checkDate = date ("m-d", $from2);									
													$endDate = $product_info['operate_end_date'];
													
													$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
													//amit added to modify for specific month end
													
													$check = date ("D", $from2);
													if($countprice == 0)
													{
														if($check == 'Sun')
														$countprice = 1;
														elseif($check == 'Mon')
														$countprice = 2;
														elseif($check == 'Tue')
														$countprice = 3;
														elseif($check == 'Wed')
														$countprice = 4;
														elseif($check == 'Thu')
														$countprice = 5;
														elseif($check == 'Fri')
														$countprice = 6;
														elseif($check == 'Sat')
														$countprice = 7;
													}
													if($isvaliddatecheck == "valid"){
													$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
													}
													$countprice++;
														if($countprice == 8)
														{
															$countprice = 1;
														}
												}
												
												
											}
											else
											{
												$twnetycount = 0;
												$countprice = 0;
												for($adate=2;$adate<=365; $adate++)
												{
													$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
													$from1 = date ("Y-m-d (D)", $from2);
													$check = date ("D", $from2);
													$formval = date ("Y-m-d", $from2);
													
													//amit addedd to modify for specific month start
													$isvaliddatecheck ='';
													$startDate = $product_info['operate_start_date'];									
													$checkDate = date ("m-d", $from2);									
													$endDate = $product_info['operate_end_date'];
													
													$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
													//amit added to modify for specific month end
													
													
													$getmonth = date ("m", $from2);
													
													
													if($countprice == 0)
													{
														if($check == 'Sun')
														$countprice = 1;
														elseif($check == 'Mon')
														$countprice = 2;
														elseif($check == 'Tue')
														$countprice = 3;
														elseif($check == 'Wed')
														$countprice = 4;
														elseif($check == 'Thu')
														$countprice = 5;
														elseif($check == 'Fri')
														$countprice = 6;
														elseif($check == 'Sat')
														$countprice = 7;
													}	
														// && $isvaliddatecheck == "valid"
													if(strstr($day1, $check) && $isvaliddatecheck == "valid")
													{
														$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
														
														$twnetycount++;
														//if($twnetycount==20)
														//break;
													}
														
													$countprice++;
													if($countprice == 8)
													{
														$countprice = 1;
													}
													
												}// end of for($adate=7;$adate<=200; $adate++)
											}
								}
								
								
				} //end of reg-irreg check
				

				/*  amit commented old code
				$avaliabledate = '';
				$from2 = '';
				$from1 = '';
				if($product_result['products_is_regular_tour'] == 0)
			    {	
					$available_query = tep_db_query("select * from ".TABLE_PRODUCTS_AVAILABLE." where products_id = ".$add_product_products_id." order by available_date");
					while($available_result = tep_db_fetch_array($available_query))
					{
						//echo '<br>'.$available_result['available_date'];
						$y = substr($available_result['available_date'], 0, 4);
						$m = substr($available_result['available_date'], 5, 2);
						$d = substr($available_result['available_date'], 8, 2);
						
						$extracharges = '';
						$prifix = '';
						if($available_result['extra_charges']!='0.00' && $available_result['extra_charges']!='')
						{
							$extracharges = ' $'.$available_result['extra_charges'].')';
							$prifix = '('.$available_result['prefix'];
						}
						
						$from2 =  mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
						$from1 = date ("Y-m-d (D)", $from2);
						$formval = date ("Y-m-d", $from2);
						$avaliabledate .= '<option value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.$prifix.$extracharges.'</option>';
					}
				
				}elseif($product_result['products_is_regular_tour'] == 1)
				{
					$daycount = 1;
			  		$day1 = '';
					
						$extracharges = '';
						$prifix = '';
			  		$operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_START_DATE." where products_id = ".$add_product_products_id." order by products_start_day");
					  while($operator_result = tep_db_fetch_array($operator_query))
					  {
						
						if($operator_result['products_start_day'] == 1)
						{
							$day1 .= 'Sun/';
						}
						if($operator_result['products_start_day'] == 2)
						{
							$day1 .= 'Mon/';
						}
						if($operator_result['products_start_day'] == 3)
						{
							$day1 .= 'Tue/';
						}
						if($operator_result['products_start_day'] == 4)
						{
							$day1 .= 'Wed/';
						}
						if($operator_result['products_start_day'] == 5)
						{
							$day1 .= 'Thu/';
						}
						if($operator_result['products_start_day'] == 6)
						{
							$day1 .= 'Fri/';
						}
						if($operator_result['products_start_day'] == 7)
						{
							$day1 .= 'Sat/';
						}
						$daycount++;
						
						if($operator_result['extra_charge']!='0.00' && $operator_result['extra_charge']!='')
						{
							$extracharges[$operator_result['products_start_day']] = ' $'.$operator_result['extra_charge'].')';
							$prifix[$operator_result['products_start_day']] = '('.$operator_result['prefix'];
						}
						
						
					  }
					  
					  
						
							
							if($daycount == 8)
							{
								$countprice = 0;
								for($adate=7;$adate<=26; $adate++)
								{
									$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
									$from1 = date ("Y-m-d (D)", $from2);
									$formval = date ("Y-m-d", $from2);
									$check = date ("D", $from2);
									if($countprice == 0)
									{
										if($check == 'Sun')
										$countprice = 1;
										elseif($check == 'Mon')
										$countprice = 2;
										elseif($check == 'Tue')
										$countprice = 3;
										elseif($check == 'Wed')
										$countprice = 4;
										elseif($check == 'Thu')
										$countprice = 5;
										elseif($check == 'Fri')
										$countprice = 6;
										elseif($check == 'Sat')
										$countprice = 7;
									}
									if($_POST['availabletourdate'] == $formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice])
									$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'" selected>'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
									else
									$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
									
									$countprice++;
										if($countprice == 8)
										{
											$countprice = 1;
										}
								}
								
								
							}
							else
							{
								$twnetycount = 0;
								$countprice = 0;
								for($adate=7;$adate<=200; $adate++)
								{
									$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
									$from1 = date ("Y-m-d (D)", $from2);
									$check = date ("D", $from2);
									$formval = date ("Y-m-d", $from2);
									if($countprice == 0)
									{
										if($check == 'Sun')
										$countprice = 1;
										elseif($check == 'Mon')
										$countprice = 2;
										elseif($check == 'Tue')
										$countprice = 3;
										elseif($check == 'Wed')
										$countprice = 4;
										elseif($check == 'Thu')
										$countprice = 5;
										elseif($check == 'Fri')
										$countprice = 6;
										elseif($check == 'Sat')
										$countprice = 7;
									}	
										
									if(strstr($day1, $check))
									{
										if($_POST['availabletourdate'] == $formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice])
										$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'" selected>'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
										else
										$avaliabledate .= '<option value="'.$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'">'.$from1.$prifix[$countprice].$extracharges[$countprice].'</option>';
										
										$twnetycount++;
										if($twnetycount==20)
										break;
									}
										
									$countprice++;
									if($countprice == 8)
									{
										$countprice = 1;
									}
									
								}// end of for($adate=7;$adate<=200; $adate++)
							}
				}
				
				*/
				
				
				
				echo '<table border="0" cellspacing="0" cellpadding="2">';
				$is_start_date_required=is_tour_start_date_required((int)$_POST['add_product_products_id']);
				//if($is_start_date_required==true){
				if(tep_check_product_is_transfer((int)$_POST['add_product_products_id'])==1){
					$transfer_products_id =  $_POST['add_product_products_id'];
					echo '<tr><td align="left" valign="top" class="dataTableContent">Transfer <br/> Information:</td><td align="left" class="dataTableContent">';
					//include ('templates/transfer_route_form.php');
					echo db_to_html('<span style="color:red">(请在添加产品后到订单编辑页面设置线路选项)</span>');
					echo '</td></tr>';
					//transfer-service 
				
				}elseif($is_start_date_required==true && tep_check_product_is_hotel((int)$_POST['add_product_products_id'])!=1){
					echo '<tr>
						  <td align="left" class="dataTableContent">Date:</td>
						  <td align="left" class="dataTableContent"><select name="availabletourdate" style="width:250;">
							  '.$avaliabledate.'
							</select></td>
						</tr>
						';
				}else if(tep_check_product_is_hotel((int)$_POST['add_product_products_id'])==1){
					
					 /* Hotel Extensions - Early/Late check-in/out - start */
						/*if(isset($_GET['hotel_attribute']) && $_GET['hotel_attribute']>0){
							$get_hotel_attr = $_GET['hotel_attribute'];
						}else{*/
							$get_hotel_attr = 3;
						//}
						if($get_hotel_attr==2){
							$checkin_date_field_name = "late_hotel_checkin_date";
							$checkout_date_field_name = "late_hotel_checkout_date";							
							echo tep_draw_hidden_field('h_l_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', 2); 
							echo tep_draw_hidden_field('staying_late_hotels', (int)$_POST['add_product_products_id']); 
						}else{
							$checkin_date_field_name = "early_hotel_checkin_date";
							$checkout_date_field_name = "early_hotel_checkout_date";
							echo tep_draw_hidden_field('h_e_id['.HOTEL_EXT_ATTRIBUTE_OPTION_ID.']', $get_hotel_attr); 
							echo tep_draw_hidden_field('early_arrival_hotels', (int)$_POST['add_product_products_id']);
						}
						echo tep_draw_hidden_field('is_hotel', 1);
					 ?>
					 <div class="eabo_co">
					 <img src="<?php echo DIR_WS_TEMPLATE_IMAGES;?>ye<?php echo $image_ye_cntr++;?>.gif" class="fleft" alt="" />
					 <div class="eabo_co_rt">
						<label class="txt12graybo">Check-In: </label>
						<br />
						<input type="text" name="<?php echo $checkin_date_field_name; ?>" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?php echo $_POST[$checkin_date_field_name]; ?>" />&nbsp;(MM/DD/YYYY)
					 </div>
					 </div>
					 <div class="eabo_co">
					 <img src="<?php echo DIR_WS_TEMPLATE_IMAGES;?>ye<?php echo $image_ye_cntr++;?>.gif" class="fleft" alt="" />
					 <div class="eabo_co_rt">
						<label class="txt12graybo">Check-Out: </label>
						<br />
						<input type="text" name="<?php echo $checkout_date_field_name; ?>" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?php echo $_POST[$checkout_date_field_name]; ?>" />&nbsp;(MM/DD/YYYY)
						
					 </div>
					 </div>
					 <?php		
					 /* Hotel Extensions - Early/Late check-in/out - end */		
				
				}
			?>
          
<?php 			
  /*   $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) { */
?>
          
            <!--<tr>
              <td class="dataTableContent" colspan="2"><?php //echo TEXT_PRODUCT_OPTIONS; ?></td>
            </tr>-->
<?php
      /* $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $add_product_products_id . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_result['products_tax_class_id'])) .') ';
          }
        }

        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        } */
?>
            <!--<tr>
              <td class="dataTableContent"><?php //echo $products_options_name['products_options_name'] . ':'; ?></td>
              <td class="dataTableContent"><?php //echo tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute, 'style="width:200;"'); ?></td>
            </tr>-->
<?php
      //}

    //}
	 			/* $depart_option ='';
				$departure_query = tep_db_query("select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".$add_product_products_id." ");
				   while($departure_result = tep_db_fetch_array($departure_query))
				   {
				   	$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].'">'.substr($departure_result['departure_time'].' &nbsp; '.$departure_result['departure_address'],0,25).'</option>'; 
				   }  
				   
				   if($depart_option != '')
				   {
					echo'<tr>
					  <td align="left" class="dataTableContent">Departure:</td>
					  <td align="left" class="dataTableContent"><select name="departurelocation" style="width:200;">'.$depart_option.'</select></td>
					</tr>';
				  }			
	echo '</table>';  
	echo tep_draw_hidden_field('products_id', $product_result['products_id']) . tep_template_image_submit('addToCart.gif', IMAGE_BUTTON_IN_CART); */

	
$qry ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".$add_product_products_id." ";
$qryset = tep_db_query($qry);
$pm = 0 ;
$am = 0;
$other = 0;
while($qry_rel = tep_db_fetch_array($qryset))
{
	/*
	$len=strlen($qry_rel['departure_time']);
	if($len == 6)
	$depart_final = '0'.$qry_rel['departure_time'];
	else
	$depart_final = $qry_rel['departure_time'];
	*/
	$depart_final = $qry_rel['departure_time'];
	
	
	if(strstr($depart_final,'pm'))
	{
		$pma[$qry_rel['departure_id']] = $depart_final ;
	}else if(strstr($depart_final,'am'))
	{
		$ama[$qry_rel['departure_id']] = $depart_final ;
	}else{
		$pma[$qry_rel['departure_id']] = $depart_final ;
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
		//if(substr($val,0,1) == 0)
		//$val = substr($val,1,7);
		$qryfinal ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".$add_product_products_id." and departure_time = '".$val."' and departure_id not in(".$finalid.") ";
		$departure_query = tep_db_query($qryfinal);
		$departure_result = tep_db_fetch_array($departure_query);
		$finalid .= ",".(int)$departure_result['departure_id'];		
		if(stripslashes($_POST['departurelocation']) == $departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'])
		$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'" selected>'.$departure_result['departure_time'].' '.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'</option>';
		else
		$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'">'.$departure_result['departure_time'].' '.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'</option>';		
	}
}	
$finalidpm = 0;
if($pma != '')
{
	foreach($pma as $key => $val)
	{
		//if(substr($val,0,1) == 0)
		//$val = substr($val,1,7);
		$qryfinal ="select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".$add_product_products_id." and departure_time = '".$val."' and departure_id not in(".$finalidpm.") ";
		$departure_query = tep_db_query($qryfinal);
		$departure_result = tep_db_fetch_array($departure_query);
		$finalidpm .= ",".(int)$departure_result['departure_id'];
		if(stripslashes($_POST['departurelocation']) == $departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'])
		$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'" selected>'.$departure_result['departure_time'].' '.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'</option>';
		else
		$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'">'.$departure_result['departure_time'].' '.$departure_result['departure_address'].', '.$departure_result['departure_full_address'].'</option>';
	}
}
				 if($depart_option != '')
				   {
   						echo'<tr>
					  <td align="left" class="dataTableContent">Departure:</td>
					  <td align="left" class="dataTableContent"><select name="departurelocation" style="width:400px;">'.$depart_option.'</select></td>
					</tr>';
				  }
				  $display_room_combo = $product_result['display_room_option'];
				if(tep_check_product_is_transfer((int)$_POST['add_product_products_id'])!=1){
				if($_POST['room-0-adult-total']=='')
				{ 
					  if($display_room_combo==1)
					  {
					  echo'<tr>
						  <td align="left" class="dataTableContent" valign="top">Lodging:</td>
						  <td align="left" class="dataTableContent" nowrap="nowrap"><DIV id=hot-search-params class="fleft"></DIV></td>
						</tr>';						
					  }else if($display_room_combo==0)
					  {
					  echo'<tr>
						  <td align="left" class="dataTableContent" valign="top">Tickets:</td>
						  <td align="left" class="dataTableContent"><DIV id=hot-search-params></DIV></td>
						</tr>';
					  }
			   }
			   else
			   {
			   echo'<tr>
						  <td align="left" class="dataTableContent" valign="top"><br>Lodging:</td>
						  <td align="left" class="dataTableContent">'.$total_info_room.'</td>
						</tr>';
			   }
				}
				  		
	echo '</table>';  

 ?>
 <SCRIPT language=javascript>
				<!--
				    // NOTE: customize variables in this javascript block as appropriate.
				    
					var defaultAdults="2";
				    var cellStyle=" class='dataTableContent'";
				    var childHelp="Please provide the ages of children in each room. Children's ages should be their age at the time of travel.";
				    var adultHelp="";
				    var textRooms="Rooms";
				    var textAdults="Adults";
				    var textChildren="Children";
				    var textChildError="Please specify the ages of all children.";
				    var pad='';
				    // NOTE: Question marks ("?") get replaced with a numeric value
				    var textRoomX="Room ?:";
				    var textChildX="Child ?:";

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
					if($_POST['room-0-adult-total']=='')
					{
					?>
				    refresh();
					<?php 
					}
					?>
				    function setChildAge(room, child, age) {
				        if (childAgesPerRoom[room] == null) {
				            childAgesPerRoom[room] = new Array();
				        }
				        childAgesPerRoom[room][child] = age;
				    }

				    function setNumAdults(room, numAdults) {
				        adultsPerRoom[room] = numAdults;
				    }

				    function setNumChildren(room, numChildren) {
				        childrenPerRoom[room] = numChildren;
				        refresh();
				    }

				    function setNumRooms(x) {
				        numRooms = x;
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
				        x += '<select name="numberOfRooms" id="numberOfRooms" onchange="setNumRooms(this.options[this.selectedIndex].value);">';
				        for (var i = 1; i < 21; i++) {
				            x += '<option value="'+i+'"'+(numRooms == i ? ' selected' : '')+'>' + i;
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
				        }

				        var x = '';
				        if (adultHelp.length > 0) {
				            x = adultHelp + "<p>\n";
				        }

				        if (numRooms > 22) {
				            x += textRooms;
				            x += renderRoomSelect();

				        } else {
				            x += '<table border="0" cellspacing="2" cellpadding="0">\n';
							x += '<tr><td'+cellStyle+'>';
							<?php if($display_room_combo==1)
							  {
							 ?>
				            x += textRooms+pad;
							<?php 
							  }
							 ?>
							 x += '</td>';
				            if (numRooms > 1) {
				                x += '<td'+cellStyle+'>&nbsp;</td>';
				            }
				            x += '<td'+cellStyle+'><nobr>'+textAdults+pad+'</nobr></td><td'+cellStyle+'><nobr>'+textChildren+pad+'</nobr></td></tr>\n';
				            for (var i = 0; i < numRooms; i++) {
				                x += '<tr><td'+cellStyle+'>';
				                if (i == 0) {
									<?php if($display_room_combo==1)
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
				                if (numRooms > 1) {
				                    x += '<td'+cellStyle+'><nobr>'+getValue(textRoomX, i+1)+pad + '</nobr></td>';
				                }
				                x += '<td'+cellStyle+'>';
				                x += buildSelect('room-' + i + '-adult-total', 'setNumAdults(' + i + ', this.options[this.selectedIndex].value)', 1, 20, adultsPerRoom[i]);
				                x += '</td><td'+cellStyle+'>';
				                x += buildSelect('room-' + i + '-child-total', '', 0, 10, childrenPerRoom[i]);//setNumChildren(' + i + ', this.options[this.selectedIndex].value)
				                x += '</td></tr>\n';
				            }
				            x += '</table>\n';

				            var didHeader = false;
				            for (var i = 0; i < numRooms; i++) {
				                if (childrenPerRoom[i] > 0) {
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
				                        x += '<select name="room-'+i+'-child-'+j+'-age" onchange="setChildAge('+i+', '+j+', this.options[this.selectedIndex].value);">';
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
				            }
				            if (didHeader) {
				                x += '</table>\n';
				            }
				        }

				        document.getElementById("hot-search-params").innerHTML = x;
				    }
					  function buildSelect(name, onchange, min, max, selected) {
				        var x = '<select name="' + name + '"';
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

				    function validateGuests(form) {
				        if (numRooms < 9) {
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