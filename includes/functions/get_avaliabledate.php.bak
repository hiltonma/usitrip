<?php
/*
get tour departure date
return $array_avaliabledate_store is array
*/

function get_avaliabledate($products_id){
/////////////////////////////产品日期选择框start get $array_avaliabledate_store
	global $display_pricing_for_special_price, $display_pricing_for_reg_special_price;
	global $currencies;
	$advance = get_products_advance($products_id);
	$num_of_sections = regu_irregular_section_numrow((int)$products_id);
	if($num_of_sections > 0){
		$regu_irregular_array = regu_irregular_section_detail((int)$products_id);
		foreach($regu_irregular_array as $k=>$v){
			if(is_array($v)){
			
			$tourcatetype =	$regu_irregular_array[$k]['producttype']; //类型 0 为有具体日期  1为没具体日期
			$opestartdate =  $regu_irregular_array[$k]['operate_start_date']; // 区间开始时间
			$opeenddate =  $regu_irregular_array[$k]['operate_end_date'];     // 区间结束时间
			
			
				if($tourcatetype == 1){  //regular your 常规区间
						///////////new regu lar tour start
						$daycount = 1;
						$day1 = '';
					
						$extracharges = '';
						$extracharges_display_only = '';									
						$prifix = '';
						$add_spl_price_note_regular=array();
						$add_spl_products_start_day_id=array();
					// 产品出发日期和价格 常规价格
					$operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id."  and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by products_start_day");
					  while($operator_result = tep_db_fetch_array($operator_query)){
						
						if($operator_result['products_start_day'] == 1){
							$day1 .= 'Sun/';
						}
						if($operator_result['products_start_day'] == 2){
							$day1 .= 'Mon/';
						}
						if($operator_result['products_start_day'] == 3){
							$day1 .= 'Tue/';
						}
						if($operator_result['products_start_day'] == 4){
							$day1 .= 'Wed/';
						}
						if($operator_result['products_start_day'] == 5){
							$day1 .= 'Thu/';
						}
						if($operator_result['products_start_day'] == 6){
							$day1 .= 'Fri/';
						}
						if($operator_result['products_start_day'] == 7){
							$day1 .= 'Sat/';
						}
						$daycount++;
						
						if($operator_result['extra_charge']!='0.00' && $operator_result['extra_charge']!=''){
							//amit modified to make sure price on usd
							if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
								$operator_result['extra_charge'] = tep_get_tour_price_in_usd($operator_result['extra_charge'],$tour_agency_opr_currency);
							}
							//amit modified to make sure price on usd
							$extracharges[$operator_result['products_start_day']] = ' $'.$operator_result['extra_charge'].')';
							$extracharges_display_only[$operator_result['products_start_day']] = $currencies->format($operator_result['extra_charge']).')';
							$prifix[$operator_result['products_start_day']] = '('.$operator_result['prefix'];
							$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
						}else{
							$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
						}
						
						
						if($operator_result['spe_single'] > 0 || $operator_result['spe_single_pu'] > 0 || $operator_result['spe_double'] > 0 || $operator_result['spe_triple'] > 0 || $operator_result['spe_quadruple'] > 0 || $operator_result['spe_kids'] > 0){
							$add_spl_price_note_regular[$operator_result['products_start_day']] = ' ('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')';
							$display_pricing_for_reg_special_price = 'true';
							$add_spl_products_start_day_id[$operator_result['products_start_day']] = $operator_result['products_start_day_id'];
						}
						
					  }
					  
					  
						
							
							if($daycount == 8){
								$countprice = 0;
								for($adate=$advance;$adate<=365; $adate++){
									$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
									$from1 = date ("Y-m-d (D)", $from2);
									$formval = date ("Y-m-d", $from2);
									
									//amit addedd to modify for specific month start
									$isvaliddatecheck ='';
									$startDate = $opestartdate;									
									$checkDate = date("m-d-Y", $from2);									
									$endDate = $opeenddate;
									
									$isvaliddatecheck = check_valid_available_date($startDate,$checkDate,$endDate);
									//amit added to modify for specific month end
									
									$check = date ("D", $from2);
									if($countprice == 0){
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
									
									//amti added to check it's not irregular date start
									
									$check_irregular_date_available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id." and  available_date='".$formval."'";
									$check_irregular_date_available_query_sql = tep_db_query($check_irregular_date_available_query_sql);
									if(tep_db_num_rows($check_irregular_date_available_query_sql) == 0){
									  $array_avaliabledate_store[$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice]] = $from1.$prifix[$countprice].$extracharges_display_only[$countprice].$add_spl_price_note_regular[$countprice];
									 } 
									  
									  //amti added to check it's not irregular date end
									
									}
									$countprice++;
										if($countprice == 8){
											$countprice = 1;
										}
								}
								
								
							}else{
								$twnetycount = 0;
								$countprice = 0;
								for($adate=$advance;$adate<=365; $adate++){
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
									
									if($countprice == 0){
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
									if(strstr($day1, $check) && $isvaliddatecheck == "valid"){
										
										//amti added to check it's not irregular date start
									
										$check_irregular_date_available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id." and  available_date='".$formval."'";
									$check_irregular_date_available_query_sql = tep_db_query($check_irregular_date_available_query_sql);
									if(tep_db_num_rows($check_irregular_date_available_query_sql) == 0){
									
										$array_avaliabledate_store[$formval.'::'.$prifix[$countprice].'##'.$extracharges[$countprice].'!!!'.$add_spl_products_start_day_id[$countprice]] = $from1.$prifix[$countprice].$extracharges_display_only[$countprice].$add_spl_price_note_regular[$countprice];
									 }
									 
									 //amti added to check it's not irregular date end
									
									 
										$twnetycount++;
										//if($twnetycount==20)
										//break;
									}
										
									$countprice++;
									if($countprice == 8){
										$countprice = 1;
									}
									
								}// end of for($adate=7;$adate<=200; $adate++)
							}
						//new regular tour end									
				}else{ //irregular tours 特殊假日价格									
					$available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$products_id." and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by available_date";
					$available_query = tep_db_query($available_query_sql);
					while($available_result = tep_db_fetch_array($available_query)){
							//echo "in";
								//echo '<br />88--'.$available_result['available_date'];
								//exit;
								$y = substr($available_result['available_date'], 0, 4);
								$m = substr($available_result['available_date'], 5, 2);
								$d = substr($available_result['available_date'], 8, 2);
								
								$extracharges = '';
								$extracharges_display_only = '';
								$prifix = '';
								if($available_result['extra_charge']!='0.00' && $available_result['extra_charge']!=''){
									//amit modified to make sure price on usd
									if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
										$available_result['extra_charge'] = tep_get_tour_price_in_usd($available_result['extra_charge'],$tour_agency_opr_currency);
									}
									//amit modified to make sure price on usd														
									$extracharges = ' $'.$available_result['extra_charge'].')';
									$extracharges_display_only = $currencies->format($available_result['extra_charge']).')';
									$prifix = '('.$available_result['prefix'];
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
								//echo date('Y-m-d'),'<br/>';
								//echo date('Y-m-d',strtotime(date('Y-m-d') . ' +2Day')),"<br/>";
								//echo date("Y-m-d",mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d)+$advance, date($y)));
								if($isvaliddatecheck == "valid" && $formval >= date('Y-m-d',strtotime(date('Y-m-d') . '+' . $advance . 'Day'))){
										$add_spl_price_note = '';
										if($available_result['spe_single'] > 0 || $available_result['spe_single_pu'] > 0 || $available_result['spe_double'] > 0 || $available_result['spe_triple'] > 0 || $available_result['spe_quadruple'] > 0 || $available_result['spe_kids'] > 0){
											$add_spl_price_note = ' ('.TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE.')';//TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE 假日价格
											$display_pricing_for_special_price = 'true';
										}
										$array_avaliabledate_store[$formval.'::'.$prifix.'##'.$extracharges] = $from1.$prifix.$extracharges_display_only.$add_spl_price_note;
								
								}
					}
				
				
				
				}//reg-irregular tours end of ifelse
			
			
			} //end of if if array is blank
		} //end of foreach loop
	}//end of if for check section > 0
	//howard fixed Remove duplicate date of departure start
	$tmp_array = $array_avaliabledate_store;
	foreach((array)$tmp_array as $key => $val){
		if(preg_match('/(.{10,10}'.preg_quote('::##!!!').')\d+/',$key,$m)){
			unset($array_avaliabledate_store[$m[1]]);
		}
	}
	//howard fixed Remove duplicate date of departure end
	if(is_array($array_avaliabledate_store) && tep_not_null($array_avaliabledate_store)){ $array_avaliabledate_store = array_unique($array_avaliabledate_store);}
	return $array_avaliabledate_store;
/////////////////////////////产品日期选择框end get $array_avaliabledate_store
}
?>
