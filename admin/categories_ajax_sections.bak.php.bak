<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");  

require('includes/application_top.php');
require('includes/functions/categories_description.php');
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CATEGORIES);
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();  
$languages = tep_get_languages();
header("Content-type: text/html; charset=".CHARSET."");

if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
		  	   if (eregi('--leftbrack', $key)) {				
				$key = str_replace('--leftbrack','[',$key);
				$key = str_replace('rightbrack--',']',$key);				
			  }
			  
			  $value2 = str_replace('@@amp;','&',$value2);
			  $value2 = str_replace('@@plush;','+',$value2);
			 
			 
			 // $value2 = iconv('utf-8','gb2312'.'//IGNORE',$value2);
			  $value2 = mb_convert_encoding($value2, 'gb2312', 'UTF-8');
			  $value2 = str_replace('ooooo','&bull;',$value2);
			  
			  $_POST[$key] = $HTTP_POST_VARS[$key] = stripslashes2($value2);   

			  //echo "$key=>$value2<br>";  	   
		  }
		}
		
}	
//exit;

$products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);


$error='false';

if($HTTP_GET_VARS['section'] == 'tour_categorization' &&  $HTTP_GET_VARS['action'] == 'process'){

		


	if($HTTP_POST_VARS['regions_id'] == '0'){	
		$messageStack->add('Please select valid option for Tour Regions.', 'error');
		$error='true';
	}
	
	if($HTTP_POST_VARS['agency_id'] == ''){	
		$messageStack->add('Please select valid option for Tour Provider.', 'error');
		$error='true';
	}	
	
	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
		$language_id = $languages[$i]['id'];
		
		if($HTTP_POST_VARS['products_name['.$language_id.']'] == ''){
		$messageStack->add('Tour Name value should not be blank.', 'error');
		$error='true';
		}
		
	}

	if($HTTP_POST_VARS['products_model'] == '')
	{
		$messageStack->add('Tour Code value should not be blank.', 'error');
		$error='true';
	}
	
	if(isset($HTTP_POST_VARS['products_model']) && $HTTP_POST_VARS['products_model']!='')
	{
		$check_same_model =  tep_db_query("select products_model from ".TABLE_PRODUCTS."  where products_id not in (".(int)$products_id.") and products_model = '".$HTTP_POST_VARS['products_model']."' ");
		if(tep_db_num_rows($check_same_model) > 0) {		
				$messageStack->add('Please enter another products-model as this was already in use.', 'error');
				$error='true';
		}		
	}
	
	$urlname = tep_db_prepare_input($HTTP_POST_VARS['products_urlname']);
         		if(!tep_not_null($urlname)){ 
					$urlname = seo_generate_urlname(tep_db_prepare_input($HTTP_POST_VARS['products_name[1]']));
				}else{
				 	$urlname = seo_generate_urlname($urlname);
				}
	
	
	if($urlname!='')
	{
		$check_same_model =  tep_db_query("select products_urlname from ".TABLE_PRODUCTS."  where products_id not in (".(int)$products_id.") and products_urlname = '".$urlname."' ");
		if(tep_db_num_rows($check_same_model) > 0) {		
				$messageStack->add('Please enter another products-url as this was already in use.', 'error');
				$error='true';
		}		
	}
		
	if($HTTP_POST_VARS['products_price'] == ''){
		$messageStack->add('Tour Price value should not be blank.', 'error');
		$error='true';
	}
	
	
	if($error == 'false'){
		 if($HTTP_POST_VARS['regions_id']!=$HTTP_POST_VARS['prev_regions_id'] && $HTTP_POST_VARS['prev_regions_id']!=''){
			$messageStack->add('Note: You have changed tour region value. Please make sure all Attractions under Operation tab are correct.', 'error');	
			
		}

		 if($HTTP_POST_VARS['agency_id']!=$HTTP_POST_VARS['prev_agency_id'] && $HTTP_POST_VARS['prev_agency_id']!=''){	
			$messageStack->add('Note: You have changed tour provider value. Please make sure all Tour Attributes under Attribute tab and all Departure Time and Locations under operation tab are correct.', 'error');	
			 // BOF: WebMakers.com Added: Update Product Attributes and Sort Order			 
			  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
			  $rows = 0;		    
			  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$HTTP_POST_VARS['agency_id']."' order by products_options_sort_order, products_options_name");
			  while ($options = tep_db_fetch_array($options_query)) {				
				if($HTTP_POST_VARS['agency_id']!=''){
					  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$HTTP_POST_VARS['agency_id']."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
						 
				} else {
						$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
				} 
				while ($values = tep_db_fetch_array($values_query)) {
				  $rows ++;
				  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,  products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
				  if (tep_db_num_rows($attributes_query) > 0) {
					$attributes = tep_db_fetch_array($attributes_query);
					if ($HTTP_POST_VARS['option['.$rows.']']) {
					  if ( ($HTTP_POST_VARS['prefix['.$rows.']'] <> $attributes['price_prefix']) || ($HTTP_POST_VARS['price['.$rows.']'] <> $attributes['options_values_price']) || ($HTTP_POST_VARS['single_price['.$rows.']'] <> $attributes['single_values_price']) || ($HTTP_POST_VARS['double_price['.$rows.']'] <> $attributes['double_values_price']) || ($HTTP_POST_VARS['triple_price['.$rows.']'] <> $attributes['triple_values_price']) || ($HTTP_POST_VARS['quadruple_price['.$rows.']'] <> $attributes['quadruple_values_price']) || ($HTTP_POST_VARS['kids_price['.$rows.']'] <> $attributes['kids_values_price']) || ($HTTP_POST_VARS['products_options_sort_order['.$rows.']'] <> $attributes['products_options_sort_order']) ) {
						
						//tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $HTTP_POST_VARS['price['.$rows.']'] . "', single_values_price='". $HTTP_POST_VARS['single_price['.$rows.']'] ."', double_values_price='". $HTTP_POST_VARS['double_price['.$rows.']'] ."', triple_values_price='". $HTTP_POST_VARS['triple_price['.$rows.']'] ."', quadruple_values_price='". $HTTP_POST_VARS['quadruple_price['.$rows.']'] ."', kids_values_price='". $HTTP_POST_VARS['kids_price['.$rows.']'] ."', price_prefix = '" . $HTTP_POST_VARS['prefix['.$rows.']'] . "', products_options_sort_order = '" . $HTTP_POST_VARS['products_options_sort_order['.$rows.']'] . "'  where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
					  			$sql_data_array_update = array(
				 					'options_values_price' => $HTTP_POST_VARS['price['.$rows.']'],
									'single_values_price' => $HTTP_POST_VARS['single_price['.$rows.']'],
									'double_values_price' => $HTTP_POST_VARS['double_price['.$rows.']'],
									'triple_values_price' => $HTTP_POST_VARS['triple_price['.$rows.']'],
									'quadruple_values_price' => $HTTP_POST_VARS['quadruple_price['.$rows.']'],
									'kids_values_price' => $HTTP_POST_VARS['kids_price['.$rows.']'],
									'price_prefix' => $HTTP_POST_VARS['prefix['.$rows.']'],
									'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order['.$rows.']']
									);
									
							tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_update, 'update', "products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
        				
					  }
					} else {
					  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
					}
				  } elseif ($HTTP_POST_VARS['option['.$rows.']']) {
					//tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $HTTP_POST_VARS['price['.$rows.']'] . "', '" . $HTTP_POST_VARS['prefix['.$rows.']'] . "', '". $HTTP_POST_VARS['single_price['.$rows.']'] ."', '" . $HTTP_POST_VARS['double_price['.$rows.']'] . "', '" . $HTTP_POST_VARS['triple_price['.$rows.']'] . "', '" . $HTTP_POST_VARS['quadruple_price['.$rows.']'] . "','" . $HTTP_POST_VARS['kids_price['.$rows.']'] . "', '" . $HTTP_POST_VARS['products_options_sort_order['.$rows.']'] . "')");
				 					$sql_data_array_insert = array(
									'products_id' => $products_id,
									'options_id' => $options['products_options_id'],
									'options_values_id' => $values['products_options_values_id'],
				 					'options_values_price' => $HTTP_POST_VARS['price['.$rows.']'],																	
									'price_prefix' => $HTTP_POST_VARS['prefix['.$rows.']'],
									'single_values_price' => $HTTP_POST_VARS['single_price['.$rows.']'],
									'double_values_price' => $HTTP_POST_VARS['double_price['.$rows.']'],
									'triple_values_price' => $HTTP_POST_VARS['triple_price['.$rows.']'],
									'quadruple_values_price' => $HTTP_POST_VARS['quadruple_price['.$rows.']'],
									'kids_values_price' => $HTTP_POST_VARS['kids_price['.$rows.']'],									
									'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order['.$rows.']']
									);
									tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_insert);
				 
				  }
				}
			  }
			// EOF: WebMakers.com Added: Update Product Attributes and Sort Order		
		
		}
	
	}
	
	
	
}


if($HTTP_GET_VARS['section'] == 'tour_content' &&  $HTTP_GET_VARS['action'] == 'process'){

	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
		$language_id = $languages[$i]['id'];
		
		if($HTTP_POST_VARS['products_description['.$language_id.']'] == ''){
		$messageStack->add('Tour Itinerary should not be blank.', 'error');
		$error='true';
		}
		
	}
	
}

if($HTTP_GET_VARS['section'] == 'tour_meta_tag' &&  $HTTP_GET_VARS['action'] == 'process'){

	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
		$language_id = $languages[$i]['id'];
		
		if($HTTP_POST_VARS['products_head_title_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Page Title.', 'error');
		$error='true';
		}
		
		if($HTTP_POST_VARS['products_head_desc_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Header Description.', 'error');
		$error='true';
		}
		
		if($HTTP_POST_VARS['products_head_keywords_tag['.$language_id.']'] == ''){
		$messageStack->add('Please Enter the value for Products Mata Tag Keywords.', 'error');
		$error='true';
		}
		
	}	
		
}



if($HTTP_GET_VARS['section'] == 'tour_operation' &&  $HTTP_GET_VARS['action'] == 'process'){
	
	if($HTTP_POST_VARS['products_durations'] == ''){	
		$messageStack->add('Please Enter valid value for Products Duration.', 'error');
		$error='true';
	}
	
	if($HTTP_POST_VARS['departure_city_id'] == ''){	
		$messageStack->add('Please Enter the value for Products Start Departure City.', 'error');
		$error='true';
	}
	
}

if(isset($HTTP_GET_VARS['action']) &&  $HTTP_GET_VARS['action'] == 'process' && $error=='false'){
		
		switch($HTTP_GET_VARS['section']) {
			
			case 'tour_categorization':
				//start of tour categorization section 
											
				$urlname = tep_db_prepare_input($HTTP_POST_VARS['products_urlname']);
         		if(!tep_not_null($urlname)){ 
					$urlname = seo_generate_urlname(tep_db_prepare_input($HTTP_POST_VARS['products_name[1]']));
				}else{
				 	$urlname = seo_generate_urlname($urlname);
				}
				
				 $sql_data_array_product = array(	
				 				  	'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),	
								  	'products_vacation_package' => tep_db_prepare_input($HTTP_POST_VARS['products_vacation_package']), 
								   	'products_type' => tep_db_prepare_input($HTTP_POST_VARS['products_type']),		
								    'regions_id' => tep_db_prepare_input($HTTP_POST_VARS['regions_id']),
									'agency_id' => tep_db_prepare_input($HTTP_POST_VARS['agency_id']),
									'products_urlname' => $urlname,					
									'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
									'provider_tour_code' => tep_db_prepare_input($HTTP_POST_VARS['provider_tour_code']),
                                 	'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
								   	'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),								                                
								  	'products_last_modified' => 'now()');
								  
				
            	tep_db_perform(TABLE_PRODUCTS,  $sql_data_array_product, 'update', "products_id = '" . (int)$products_id . "'");
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
				 	$language_id = $languages[$i]['id'];
					
				 	$sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name['.$language_id.']']));
		
					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					
				  }
				
				 $messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_CATEGORIZATION.' Section', 'success');
				//end of tour categorization section
			break;
			case 'room_and_price':
				//start of tour room and price
				if($HTTP_POST_VARS['display_room_option'] == 0)
				  {
					$HTTP_POST_VARS['products_single'] = $HTTP_POST_VARS['products_single1'];
					$HTTP_POST_VARS['products_kids'] = $HTTP_POST_VARS['products_kids1'];
					$HTTP_POST_VARS['products_double'] = '';
					$HTTP_POST_VARS['products_triple'] = '';
					$HTTP_POST_VARS['products_quadr'] = '';
					
					if($access_full_edit == 'true') { 	
						$HTTP_POST_VARS['products_single_cost'] = $HTTP_POST_VARS['products_single1_cost'];
						$HTTP_POST_VARS['products_kids_cost'] = $HTTP_POST_VARS['products_kids1_cost'];
						$HTTP_POST_VARS['products_double_cost'] = '';
						$HTTP_POST_VARS['products_triple_cost'] = '';
						$HTTP_POST_VARS['products_quadr_cost'] = '';
					}
					
					$HTTP_POST_VARS['maximum_no_of_guest'] = '';
					
				  }
		  
				 $sql_data_array_product = array(	
				 				  	'display_hotel_upgrade_notes' => tep_db_prepare_input($HTTP_POST_VARS['display_hotel_upgrade_notes']),
								  	'display_room_option' => tep_db_prepare_input($HTTP_POST_VARS['display_room_option']),
									'maximum_no_of_guest' => tep_db_prepare_input($HTTP_POST_VARS['maximum_no_of_guest']),
									'products_margin' => tep_db_prepare_input($HTTP_POST_VARS['products_margin']),
									'products_single' => tep_db_prepare_input($HTTP_POST_VARS['products_single']),
									'products_double' => tep_db_prepare_input($HTTP_POST_VARS['products_double']),
									'products_triple' => tep_db_prepare_input($HTTP_POST_VARS['products_triple']),
									'products_quadr' => tep_db_prepare_input($HTTP_POST_VARS['products_quadr']),
									'products_kids' => tep_db_prepare_input($HTTP_POST_VARS['products_kids']),	 
								  	'products_last_modified' => 'now()');
				
				
				if($access_full_edit == 'true') { 				
				$sql_data_array_product_cost = array(	
									'products_single_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_single_cost']),
									'products_double_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_double_cost']),
									'products_triple_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_triple_cost']),
									'products_quadr_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_quadr_cost']),
									'products_kids_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_kids_cost'])
									);									
            	$sql_data_array_product = array_merge($sql_data_array_product, $sql_data_array_product_cost);
				}				  
				
            	tep_db_perform(TABLE_PRODUCTS,  $sql_data_array_product, 'update', "products_id = '" . (int)$products_id . "'");
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
				 	$language_id = $languages[$i]['id'];
					
				 	$sql_data_array = array('products_pricing_special_notes' => tep_db_prepare_input($HTTP_POST_VARS['products_pricing_special_notes['.$language_id.']']));
		
					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
					
				  }
				
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_ROOM_AND_PRICE.' Section', 'success');	
				//end of tour room and price
			break;
			case 'tour_operation':
				//start of tour operation
				
					//**********first fetch from the product table where it is first regular of irregular ***********//
							$product_regular_query = tep_db_query("select * from ".TABLE_PRODUCTS." where products_id = '" . (int)$products_id . "' ");
							$product_regular_result = tep_db_fetch_array($product_regular_query);	
							
							//**************************************** START CODING FOR UPDATING "products_is_regular_tour"   *************/
					  		//********** Code for inserrt update delete  product_start_date/products_available tables*****************//
								$newproductypecode = 1;
								if($HTTP_POST_VARS['products_type']==3 || $newproductypecode == 1)
								{
									
									
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
											
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id = '" . $product_regular_result['products_id'] . "'");
											
									for($no_main_sec=1;$no_main_sec<=$HTTP_POST_VARS['numberofsection'];$no_main_sec++)
									{
										
										
										if($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==0)
										{							
										
										   $sql_data_irregular_description = array('products_id' => $products_id,
																				'products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																				'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																				'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														
											tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_irregular_description);
										
												
												for($no_dates=1;$no_dates<=$HTTP_POST_VARS['numberofdates'.$no_main_sec];$no_dates++)
												{									
													
														if($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]!='')
															{
															
															if($access_full_edit == 'true') { 
															
															$sql_data_irregular_date = array('products_id' => $products_id,
																					'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
																					'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'extra_charge_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_cost_'.$no_main_sec.'_'.$no_dates]),																				
																					'spe_single_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_single_price_cost_'.$no_main_sec.'_'.$no_dates]),
																					'spe_double_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_double_price_cost_'.$no_main_sec.'_'.$no_dates]),
																					'spe_triple_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_triple_price_cost_'.$no_main_sec.'_'.$no_dates]),
																					'spe_quadruple_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_quadruple_price_cost_'.$no_main_sec.'_'.$no_dates]),
																					'spe_kids_cost' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_kids_price_cost_'.$no_main_sec.'_'.$no_dates]),																				
																					'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
																					'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
																					'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																					'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
															
															
															}else{
																$sql_data_irregular_date = array('products_id' => $products_id,
																					'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
																					'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
																					'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
																					'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																					'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
															}		
															tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_date);
														}
													
												}
										}
										elseif($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==1)
										{										
											
											
											for($daysofweek=1;$daysofweek<8;$daysofweek++)
											{
												
												if(isset($HTTP_POST_VARS['weekday_option'.$no_main_sec."_".$daysofweek]))
												{
												
														if($access_full_edit == 'true') { 
															$sql_data_irregular_weeday = array('products_id' => $products_id,
																						//products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$no_main_sec.$daysofweek]),
																						'extra_charge_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price_cost'.$no_main_sec.$daysofweek]),
																						'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['weekday_single_price'.$no_main_sec.$daysofweek]),
																						'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['weekday_double_price'.$no_main_sec.$daysofweek]),
																						'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['weekday_triple_price'.$no_main_sec.$daysofweek]),
																						'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['weekday_quadruple_price'.$no_main_sec.$daysofweek]),
																						'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['weekday_kids_price'.$no_main_sec.$daysofweek]),																				
																						'spe_single_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_single_price_cost'.$no_main_sec.$daysofweek]),
																						'spe_double_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_double_price_cost'.$no_main_sec.$daysofweek]),
																						'spe_triple_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_triple_price_cost'.$no_main_sec.$daysofweek]),
																						'spe_quadruple_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_quadruple_price_cost'.$no_main_sec.$daysofweek]),
																						'spe_kids_cost' => tep_db_prepare_input($HTTP_POST_VARS['weekday_kids_price_cost'.$no_main_sec.$daysofweek]),		
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$no_main_sec.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$no_main_sec.$daysofweek]),
																						'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																						'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														}else{
																$sql_data_irregular_weeday = array('products_id' => $products_id,
																						//products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$no_main_sec.$daysofweek]),
																						'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['weekday_single_price'.$no_main_sec.$daysofweek]),
																						'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['weekday_double_price'.$no_main_sec.$daysofweek]),
																						'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['weekday_triple_price'.$no_main_sec.$daysofweek]),
																						'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['weekday_quadruple_price'.$no_main_sec.$daysofweek]),
																						'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['weekday_kids_price'.$no_main_sec.$daysofweek]),																				
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$no_main_sec.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$no_main_sec.$daysofweek]),
																						'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																			'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));														
														
														}
																tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_weeday);
												 } 
											 }
										}
										
										
									}
									
								}								
				//**************************************** end CODING FOR UPDATING "products_is_regular_tour"  *************/
							
				//***************************************** start code for updating "Destination" *******************//
							if(isset($HTTP_POST_VARS['selectedcityid']) && ($HTTP_POST_VARS['selectedcityid'] != ''))
							{
								tep_db_query("delete from " . TABLE_PRODUCTS_DESTINATION . " where products_id = '" . (int)$products_id . "'");

									$selectedcityinsert = explode("::",$HTTP_POST_VARS['selectedcityid']);
									foreach($selectedcityinsert as $key => $val)
									{
									//echo "$key => $val <br>";
										$sql_data_duration_days = array('products_id' => $products_id,
																		'city_id' => $val);
										tep_db_perform(TABLE_PRODUCTS_DESTINATION, $sql_data_duration_days);
									}
							}
				//***************************************** end code for updating "Destination" *******************//
				
				//***************************************** start code for updating "Departure" *******************//
							if(isset($HTTP_POST_VARS['numberofdaparture']) && ($HTTP_POST_VARS['numberofdaparture'] != '1'))
							{
								tep_db_query("delete from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = '" . (int)$products_id . "'");
								for($departure_places=1;$departure_places<$HTTP_POST_VARS['numberofdaparture'];$departure_places++)
								{
									if(tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['regioninsertid_'.$departure_places]) == $departure_places )
									{
										$sql_data_departure_places = array('products_id' => $products_id,
																	'departure_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]),
																	'departure_full_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_full_address_'.$departure_places]),
																	'departure_time' => tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]),
																	'departure_region' => tep_db_prepare_input($HTTP_POST_VARS['depart_region_'.$departure_places]),
																	'map_path' => tep_db_prepare_input($HTTP_POST_VARS['departure_map_path_'.$departure_places]));
													
										tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
										
									}
								 }
							 }
							
				//***************************************** end code for updating "Departure" *******************//
				
				
				if(!isset($HTTP_POST_VARS['display_pickup_hotels'])){
					 $HTTP_POST_VARS['display_pickup_hotels'] = 0;
				  }
				
				$product_sql_data_array = array(	
				 				  	'departure_city_id' => tep_db_prepare_input($HTTP_POST_VARS['departure_city_id']),
								  	'departure_end_city_id' => tep_db_prepare_input($HTTP_POST_VARS['departure_end_city_id']),			
									'products_durations' => tep_db_prepare_input($HTTP_POST_VARS['products_durations']),
                                  	'products_durations_type' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_type']),
									'display_pickup_hotels' => tep_db_prepare_input($HTTP_POST_VARS['display_pickup_hotels']),                                
								  	'products_last_modified' => 'now()'
									);								  
				
            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {		  
				 $language_id = $languages[$i]['id'];
				 $sql_data_array = array(
				 					'products_small_description' => tep_db_prepare_input($HTTP_POST_VARS['products_small_description['.$language_id.']']),
         							'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description['.$language_id.']']),
         							'products_other_description' => tep_db_prepare_input($HTTP_POST_VARS['products_other_description['.$language_id.']']),
									'products_package_excludes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_excludes['.$language_id.']']),
									'products_package_special_notes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_special_notes['.$language_id.']'])									
									);
									
				//tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        		}			
				
				
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_OPERATION.' Section', 'success');
				//end of tour operation
			break;
			case 'tour_content':
				//start of tour content
				
				$product_sql_data_array = array(	
				 				  	'display_itinerary_notes' => tep_db_prepare_input($HTTP_POST_VARS['display_itinerary_notes']),				                                
								  	'products_last_modified' => 'now()'
									);								  
				
            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {		  
				 $language_id = $languages[$i]['id'];
				 $sql_data_array = array(
				 					'products_small_description' => tep_db_prepare_input($HTTP_POST_VARS['products_small_description['.$language_id.']']),
         							'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description['.$language_id.']']),
         							'products_other_description' => tep_db_prepare_input($HTTP_POST_VARS['products_other_description['.$language_id.']']),
									'products_package_excludes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_excludes['.$language_id.']']),
									'products_package_special_notes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_special_notes['.$language_id.']'])									
									);
									
				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        		}			
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_CONTENT.' Section', 'success');
				//end of tour content
			break;
			case 'tour_eticket':
				//start of tour eticket
				
				$product_sql_data_array = array(	
				 				  	'products_special_note' => tep_db_prepare_input($HTTP_POST_VARS['products_special_note']),			                                
								  	'products_last_modified' => 'now()'
									);								  
				
            	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
				
				
				if($HTTP_POST_VARS['products_durations_type'] == 0 && $HTTP_POST_VARS['products_durations'] != 0 && $HTTP_POST_VARS['products_durations'] > 0 ){
					$duration_count = $HTTP_POST_VARS['products_durations'];
				}else if($HTTP_POST_VARS['products_durations_type'] != 0){
					$duration_count = 1;
				}
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {		  
				 $language_id = $languages[$i]['id'];
				 
				 
				
				 
				 $eticket_itinerary_separated = '';
				 $eticket_hotel_separated = '';
				 $eticket_notes_separated = '';
					for($dj=1; $dj<=$duration_count; $dj++){
					//echo $HTTP_POST_VARS['eticket_notes['.$language_id.']['.$dj.']'].'<br>'; !##!
					
					  	if($eticket_itinerary_separated == ''){						
							if($HTTP_POST_VARS['eticket_itinerary['.$language_id.']['.$dj.']'] == ''){
								$eticket_itinerary_separated = ' ';
							}else{	
					    		$eticket_itinerary_separated = $HTTP_POST_VARS['eticket_itinerary['.$language_id.']['.$dj.']'];
							}
						}else{
							$eticket_itinerary_separated .= '!##!'.$HTTP_POST_VARS['eticket_itinerary['.$language_id.']['.$dj.']'];
						}
						
						if($eticket_hotel_separated == ''){
							if($HTTP_POST_VARS['eticket_hotel['.$language_id.']['.$dj.']'] == ''){
							$eticket_hotel_separated = ' ';
							}else{
					    	$eticket_hotel_separated = $HTTP_POST_VARS['eticket_hotel['.$language_id.']['.$dj.']'];
							}
						
						}else{
							$eticket_hotel_separated .= '!##!'.$HTTP_POST_VARS['eticket_hotel['.$language_id.']['.$dj.']'];
						}
						
						if($eticket_notes_separated == ''){
							if($HTTP_POST_VARS['eticket_notes['.$language_id.']['.$dj.']'] == ''){
							$eticket_notes_separated = ' ';
							}else{
					    	$eticket_notes_separated = $HTTP_POST_VARS['eticket_notes['.$language_id.']['.$dj.']'];
							}
						}else{
							$eticket_notes_separated .= '!##!'.$HTTP_POST_VARS['eticket_notes['.$language_id.']['.$dj.']'];
						}
						
					 }
										 
					
				 $sql_data_array = array(
				 					'eticket_itinerary' => tep_db_prepare_input($eticket_itinerary_separated),
									'eticket_hotel' => tep_db_prepare_input($eticket_hotel_separated),
									'eticket_notes' => tep_db_prepare_input($eticket_notes_separated)
									);
									
				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        		}			
				$messageStack->add('Updated '.TEXT_HEADING_ETICKET_INFORMATION.' Section', 'success');
				//end of tour eticket
			break;
			case 'tour_image_video':
				//start of tour image video
				
				$messageStack->add('Updated '.TEXT_HEADING_IMAGES_VIDEOS.' Section', 'success');
				//end of tour image video
			break;
			case 'tour_meta_tag':
				//start of tour meta tag
				
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {		  
				 $language_id = $languages[$i]['id'];
				 $sql_data_array = array(
				 					'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag['.$language_id.']']),
         							'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag['.$language_id.']']),
         							'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag['.$language_id.']'])
									);
									
				tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
        		}			
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_TAG_INFORMATION.' Section', 'success');
				//end of tour meta tag
			break;
		
			case 'tour_attribute':
				//start of tour attribute
				
		  // BOF: WebMakers.com Added: Update Product Attributes and Sort Order
         $rows = 0;		  
		
		 $agency_option_ids_string = "'0', ";
		 $select_optionsid_of_agencyid_query = tep_db_query("select products_options_id from " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . "  where agency_id = '".$HTTP_POST_VARS['agency_id']."' group by products_options_id");
		 
		 while($select_optionsid_of_agencyid_row = tep_db_fetch_array($select_optionsid_of_agencyid_query)) {		
			 $agency_option_ids_string .= "'" . $select_optionsid_of_agencyid_row['products_options_id'] . "', ";		 	
		 }
		 $agency_option_ids_string = substr($agency_option_ids_string, 0, -2);
		  
		$select_optionid_with_agencyid = "select options_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pavtp where products_id = '" . (int)$products_id . "' and options_id not in (".$agency_option_ids_string.") ";
		$select_optionid_with_agencyid_query = tep_db_query($select_optionid_with_agencyid);					
		while($select_optionid_with_agencyid_row = tep_db_fetch_array($select_optionid_with_agencyid_query)){
		 tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_id='".$select_optionid_with_agencyid_row['options_id']."' and products_id = '" . (int)$products_id . "'");
		}
			
			      
		   $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$HTTP_POST_VARS['agency_id']."' order by products_options_sort_order, products_options_name");
		  while ($options = tep_db_fetch_array($options_query)) {				
			if($HTTP_POST_VARS['agency_id']!=''){
				  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$HTTP_POST_VARS['agency_id']."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
					 
			} else {
					$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
			} 
			while ($values = tep_db_fetch_array($values_query)) {
			  $rows ++;
			  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,single_values_price_cost, double_values_price_cost,triple_values_price_cost,quadruple_values_price_cost,kids_values_price_cost,  products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
			  if (tep_db_num_rows($attributes_query) > 0) {
				$attributes = tep_db_fetch_array($attributes_query);
				if ($HTTP_POST_VARS['option['.$rows.']']) {				
				 
				  //if ( ($HTTP_POST_VARS['prefix['.$rows.']'] <> $attributes['price_prefix']) || ($HTTP_POST_VARS['price['.$rows.']'] <> $attributes['options_values_price']) || ($HTTP_POST_VARS['single_price['.$rows.']'] <> $attributes['single_values_price']) || ($HTTP_POST_VARS['double_price['.$rows.']'] <> $attributes['double_values_price']) || ($HTTP_POST_VARS['triple_price['.$rows.']'] <> $attributes['triple_values_price']) || ($HTTP_POST_VARS['quadruple_price['.$rows.']'] <> $attributes['quadruple_values_price']) || ($HTTP_POST_VARS['kids_price['.$rows.']'] <> $attributes['kids_values_price']) || ($HTTP_POST_VARS['products_options_sort_order['.$rows.']'] <> $attributes['products_options_sort_order']) ) {
					
					 $sql_data_array_update = array(
				 					'options_values_price' => $HTTP_POST_VARS['price['.$rows.']'],
									'single_values_price' => $HTTP_POST_VARS['single_price['.$rows.']'],
									'double_values_price' => $HTTP_POST_VARS['double_price['.$rows.']'],
									'triple_values_price' => $HTTP_POST_VARS['triple_price['.$rows.']'],
									'quadruple_values_price' => $HTTP_POST_VARS['quadruple_price['.$rows.']'],
									'kids_values_price' => $HTTP_POST_VARS['kids_price['.$rows.']'],
									'price_prefix' => $HTTP_POST_VARS['prefix['.$rows.']'],
									'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order['.$rows.']']
									);
									
					
						if($access_full_edit == 'true') { 									
							$sql_data_array_product_cost = array(	
										'options_values_price_cost' => $HTTP_POST_VARS['price_cost['.$rows.']'],	
										'single_values_price_cost' => $HTTP_POST_VARS['single_price_cost['.$rows.']'],
										'double_values_price_cost' => $HTTP_POST_VARS['double_price_cost['.$rows.']'],
										'triple_values_price_cost' => $HTTP_POST_VARS['triple_price_cost['.$rows.']'],
										'quadruple_values_price_cost' => $HTTP_POST_VARS['quadruple_price_cost['.$rows.']'],
										'kids_values_price_cost' => $HTTP_POST_VARS['kids_price_cost['.$rows.']']
										);									
							$sql_data_array_update = array_merge($sql_data_array_update, $sql_data_array_product_cost);
						}				
					 
					tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_update, 'update', "products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
        		
				 // }
				  
				} else {
				  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
				}
			  } elseif ($HTTP_POST_VARS['option['.$rows.']']) {
				
				
			   		$sql_data_array_insert = array(
									'products_id' => $products_id,
									'options_id' => $options['products_options_id'],
									'options_values_id' => $values['products_options_values_id'],
				 					'options_values_price' => $HTTP_POST_VARS['price['.$rows.']'],																	
									'price_prefix' => $HTTP_POST_VARS['prefix['.$rows.']'],
									'single_values_price' => $HTTP_POST_VARS['single_price['.$rows.']'],
									'double_values_price' => $HTTP_POST_VARS['double_price['.$rows.']'],
									'triple_values_price' => $HTTP_POST_VARS['triple_price['.$rows.']'],
									'quadruple_values_price' => $HTTP_POST_VARS['quadruple_price['.$rows.']'],
									'kids_values_price' => $HTTP_POST_VARS['kids_price['.$rows.']'],									
									'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order['.$rows.']']
									);
									
					
						if($access_full_edit == 'true') { 									
							$sql_data_array_product_cost_insert = array(	
										'options_values_price_cost' => $HTTP_POST_VARS['price_cost['.$rows.']'],	
										'single_values_price_cost' => $HTTP_POST_VARS['single_price_cost['.$rows.']'],
										'double_values_price_cost' => $HTTP_POST_VARS['double_price_cost['.$rows.']'],
										'triple_values_price_cost' => $HTTP_POST_VARS['triple_price_cost['.$rows.']'],
										'quadruple_values_price_cost' => $HTTP_POST_VARS['quadruple_price_cost['.$rows.']'],
										'kids_values_price_cost' => $HTTP_POST_VARS['kids_price_cost['.$rows.']']
										);									
							$sql_data_array_insert = array_merge($sql_data_array_insert, $sql_data_array_product_cost_insert);
						}				
			   			tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_insert);
			  
			  
			  }
			}
		  }
				
				$messageStack->add('Updated '.TEXT_HEADING_TITLE_TOUR_ATTRIBUTES.' Section', 'success');
				//end of tour attribute
			break;
		
		 } //end of swich 
			
} //end of check proccess




$parameters = array('products_name' => '',
						'products_model' => '',
						'provider_tour_code' => '',		
						'products_urlname' => '',
                       'products_description' => '',
					    'products_pricing_special_notes' => '',
					   'products_other_description' => '',
					   'products_package_excludes' => '',
					   'products_package_special_notes' => '',
					   'eticket_itinerary' => '',
					   'eticket_hotel' => '',
					   'eticket_notes' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_is_regular_tour' => '',
                       'products_image' => '',
					   'products_map' => '',
                       'products_image_med' => '',
                       'products_image_lrg' => '',
                       'products_price' => '',
                       'products_durations' => '',
                       'products_durations_type' => '',
                       'products_durations_description' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
					   'display_room_option' => '',
                       'products_status' => '',					   
					   'display_itinerary_notes' => '',
					   'display_hotel_upgrade_notes' => '',
					   'products_type' => '',
					   'operate_start_date' => '',
					   'operate_end_date' => '',
                       'products_tax_class_id' => '1',
					   'agency_id' => '',
					   'products_margin' => '',
					   'display_pickup_hotels' => '1',
                       'manufacturers_id' => '');

$pInfo = new objectInfo($parameters);
 $product_query = tep_db_query("select p.products_margin, p.products_video, p.operate_start_date, p.operate_end_date, p.products_type, p.maximum_no_of_guest,p.products_single,p.products_double,p.products_triple,p.products_quadr,p.products_kids,p.products_single_cost, p.products_double_cost, p.products_triple_cost, p.products_quadr_cost, p.products_kids_cost, p.products_special_note,p.regions_id, p.agency_id, p.departure_city_id, p.departure_end_city_id, p.display_pickup_hotels, p.products_model, p.provider_tour_code, p.products_urlname, pd.products_name, pd.products_description, pd.products_other_description, pd.products_package_excludes , pd.products_package_special_notes, pd.eticket_itinerary, pd.eticket_hotel, pd.eticket_notes, pd.products_pricing_special_notes, pd.products_small_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_is_regular_tour,p.products_map ,p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_durations_type, p.products_durations_description, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status,p.products_vacation_package, p.display_room_option, p.products_tax_class_id, p.manufacturers_id, p.display_itinerary_notes, p.display_hotel_upgrade_notes from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
$product = tep_db_fetch_array($product_query);

$pInfo->objectInfo($product);


/*
if($HTTP_GET_VARS['upimage']=='true'){
$messageStack->add('Updated '.TEXT_HEADING_IMAGES_VIDEOS.' Section', 'success');

}
*/


 if ($messageStack->size > 0) {
        echo $messageStack->output();				
 } 

switch ($HTTP_GET_VARS['section']) {
	
	case 'tour_categorization':
		//start of tour categorization section 		
		
		
		if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
		switch ($pInfo->products_status) {
			  case '0': $in_status = false; $out_status = true; break;
			  case '1':
			  default: $in_status = true; $out_status = false;
		}
		
		if (!isset($pInfo->products_vacation_package)) $pInfo->products_vacation_package = '0';
		switch ($pInfo->products_vacation_package) {
		  case '0': $in_status_products_vacation_package = false; $out_status_products_vacation_package = true; break;
		  case '1':
		  default: $in_status_products_vacation_package = true; $out_status_products_vacation_package = false;
		}
		
		//$products_type_array = array(array('id' => '', 'text' => TEXT_NONE));
		$products_type_array = tep_db_query("select products_type_id, products_type_name from " . TABLE_PRODUCTS_TYPES . " order by products_type_id");
		while ($products_type_info = tep_db_fetch_array($products_type_array)) {
		  $products_type_arrays[] = array('id' => $products_type_info['products_type_id'],
										 'text' => $products_type_info['products_type_name']);
		}				
		
		
		$agency_array = array(array('id' => '', 'text' => TEXT_NONE));
		$agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
		while ($agency_result = tep_db_fetch_array($agency_query)) {
		  $agency_array[] = array('id' => $agency_result['agency_id'],
										 'text' => $agency_result['agency_name']);
		}	
		
		 $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
		$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
		while ($tax_class = tep_db_fetch_array($tax_class_query)) {
		  $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
									 'text' => $tax_class['tax_class_title']);
		}
		?>		
		<?php
		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}
		
		
		 if($display_room_yes) {
		 ?>		
		<div id="optionyes" style="visibility: visible; display: inline;"></div>
		  
		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php
		 }elseif($display_room_no){
		 ?>		
		<div id="optionyes" style=" visibility: hidden; display: none;"></div>
		  
		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php }?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_CATEGORIZATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		 <form name="new_product"  id="new_product">		 		
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
         </tr>
		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		    <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_VACATION_PACKAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
			<?php
			if($pInfo->products_id == '')
			{
				$products_pricing_special_notes[1] = TOURS_DEFAULT_PRICING_NOTES;
				echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package);
			}else{
				echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package);
			}
			
			echo '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE_VACATION_PACKAGE; 
			
			?></td>
         </tr>		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
            <td class="main"><?php echo HEADING_TITLE_PRODUCTS_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('products_type', $products_type_arrays, $pInfo->products_type); //'onChange="show_go(this.value);"'?></td>
         </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>		
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_REGION; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), $pInfo->regions_id); //'id="regions_id" onChange="javascript: generate();"' ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_AGENCY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
			<?php 
			
			echo tep_draw_pull_down_menu('agency_id', $agency_array, $pInfo->agency_id,'id="agency_id" onChange="change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);" '); //onChange="getInfo(\'\',\'-2\'); change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);"
			
			echo tep_draw_hidden_field('products_id', $pInfo->products_id);
			echo tep_draw_hidden_field('prev_agency_id', $pInfo->agency_id);
			echo tep_draw_hidden_field('prev_regions_id', $pInfo->regions_id);
			
			?>

			</td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		 
			<?php
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
					  <tr>
						<td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
						<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])),'size="60"'); ?></td>
					  </tr>
			<?php
				}
			?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>

            <td class="main"><?php echo TEXT_PRODUCTS_URL_NAME; ?></td>

            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_urlname', $pInfo->products_urlname, 'size="60"'); ?></td>

          </tr>
		  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL;    $products_id_model = $pInfo->products_id;?></td>
            <td class="main"><?php 
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_model;
			
			echo tep_draw_hidden_field('products_model', $pInfo->products_model); 
			
			?></td>
          </tr>
		  
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<tr>
			<td class="main"><?php echo TEXT_PRODUCTS_PROVIDER_MODEL;?>
			</td>
			<td class="main"><?php 
			
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('provider_tour_code', $pInfo->provider_tour_code); 
			
			?></td>
		  </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
		  <td colspan="2">
		  <div id="tour_attribute_list">
		  
		  </div>
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		   if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_categorization">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		<?php
		//end of tour categorization section
    break;

	case 'room_and_price':
		//start of tour room and price
		if (!isset($pInfo->display_hotel_upgrade_notes)) $pInfo->display_hotel_upgrade_notes = '1';
		  switch ($pInfo->display_hotel_upgrade_notes) {
		  case '0': $display_hotel_upgrade_notes_in_status = false; $display_hotel_upgrade_notes_out_status = true; break;
		  case '1':
		  default: $display_hotel_upgrade_notes_in_status = true; $display_hotel_upgrade_notes_out_status = false;
		}
	
		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}
		
		 if($display_room_yes)
		 {
		  $div_first_room_yes_display = '<div id="optionyes" style="visibility: visible; display: inline;">';		  
		  $div_first_room_no_display =  '<div id="optionno" style="visibility: hidden; display: none;">';
		 }elseif($display_room_no){
		  $div_first_room_yes_display = '<div id="optionyes" style=" visibility: hidden; display: none;">';		  
		  $div_first_room_no_display =  '<div id="optionno" style="visibility: visible; display: inline;">';
		}
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_ROOM_AND_PRICE; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		 <form name="new_product"  id="new_product">		
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		 
		 
		 
		  <tr>		  
            <td class="main"><?php echo 'Display Room'; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_room_option', '1', $display_room_yes,'', 'onclick="toggleBox_edit(\'optionyes\',\'optionno\');"') . '&nbsp; Yes &nbsp;' . tep_draw_radio_field('display_room_option', '0', $display_room_no,'', 'onclick="toggleBox_edit(\'optionno\',\'optionyes\');"') . '&nbsp; No'; ?></td>
		</tr>
		<?php		
   		$maximum_no_of_guest_array[] = array('id' => '1', 'text' => '1');
		$maximum_no_of_guest_array[] = array('id' => '2', 'text' => '2');
		$maximum_no_of_guest_array[] = array('id' => '3', 'text' => '3');
		$maximum_no_of_guest_array[] = array('id' => '4', 'text' => '4');
		if($pInfo->maximum_no_of_guest!='')
		$selected_guest = $pInfo->maximum_no_of_guest;
		else 
		$selected_guest = '4';		
		?>
		
		
		<?php if($access_full_edit == 'true') { ?>
		 <tr>		  
			<td class="main"><?php echo TEXT_HEADING_GROSS_PROFIT; ?></td>
			<td class="main">			
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %</td>
		 </tr>
		
		  <tr class="dataTableRow">
			<td class="dataTableContent" colspan="2">
			  <?php echo $div_first_room_yes_display;?>			  
			  	 <table width="100%">  
				 
				 
				  <tr>
					<td></td>
					<td><table   border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' ;?></td>
						<td class="main">Retail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="main">Cost</td>
					  </tr>
					</table>
					</td>
				  </tr>  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_single_cost', $pInfo->products_single_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_cost, document.new_product.products_single);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_DOUBLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_double_cost', $pInfo->products_double_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_double_cost, document.new_product.products_double);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_TRIPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_triple_cost', $pInfo->products_triple_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_triple_cost, document.new_product.products_triple);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>				  
				  <tr>
					<td class="main" nowrap><?php echo TEXT_HEADING_QUADRUPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_quadr_cost', $pInfo->products_quadr_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_quadr_cost, document.new_product.products_quadr);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_kids_cost', $pInfo->products_kids_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_kids_cost, document.new_product.products_kids);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>
				    <tr>	
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>				  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo 'Maximum no of Guest '; ?></td>					
            		<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest); ?></td>
				  </tr>				
				  </table>
		  		</div>	
			</td>	
		  </tr>
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <?php echo  $div_first_room_no_display;?>
			  	 <table width="100%">  
				 	
				   <tr>
					<td></td>
					<td><table   border="0" cellspacing="0" cellpadding="0">
					  <tr>
					  	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' ;?></td>
						<td class="main">Retail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="main">Cost</td>
					  </tr>
					</table>
					</td>
				  </tr>  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo TEXT_HEADING_ADULT_PRICE; ?></td>
					<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_single1_cost', $pInfo->products_single_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single1_cost, document.new_product.products_single1);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'). '&nbsp;' . tep_draw_input_field('products_kids1_cost', $pInfo->products_kids_cost, 'size="7"'); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_kids1_cost, document.new_product.products_kids1);"  type=button value="<?php echo TEXT_CALCULATE_RETAIL_PRICE;?>"></td>
				  </tr>				  
				  </table>
		  </div>	
			</td>	
		  </tr>
		  
		  <?php  }else{ ?>
		   
		    <tr class="dataTableRow">
			<td class="dataTableContent" colspan="2">
			  <?php echo $div_first_room_yes_display;?>
			  
			  	 <table width="100%">  
				   <tr>	
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo 'Maximum no of Guest '; ?></td>
					<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest ); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_SINGLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_DOUBLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_TRIPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main" nowrap><?php echo TEXT_HEADING_QUADRUPLE_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_OCC_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'); ?></td>
				  </tr>
				  </table>
		  </div>	
			</td>	
		  </tr>
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <?php echo  $div_first_room_no_display;?>
			  	 <table width="100%">  
				   <tr>	
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo TEXT_HEADING_ADULT_PRICE; ?></td>
					<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'size="7"'); ?></td>
				  </tr>
				  
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo TEXT_HEADING_KIDS_PRICE; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'); ?></td>
				  </tr>
				  
				  </table>
		  </div>	
			</td>	
		  </tr>
		  
		   <?php } ?>		  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		  <?php
			for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
			<tr>
            <td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_SPECIAL_RPICING_NOTE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_pricing_special_notes[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_pricing_special_notes[$languages[$i]['id']]) ? $products_pricing_special_notes[$languages[$i]['id']] : tep_get_products_pricing_special_notes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_pricing_special_notes[' . $languages[$i]['id'] . ']'); ?></td>

              </tr>
            </table></td>
          </tr>

			<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<?php } ?>         
  
		  <tr>
            <td class="main" nowrap="nowrap"><?php echo TEXT_PRODUCTS_DISPLAY_HOTEL_UPGRADE_NOTES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '1', $display_hotel_upgrade_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '0', $display_hotel_upgrade_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
         </tr>		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>		  
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';		 
		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  ?>		 
		  <input type="hidden" name="req_section" value="tour_room_and_price">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
				
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php		
		//end of tour room and price
    break;

	case 'tour_operation':
		//start of tour operation
		
		
		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}
		
		
		
		 if($display_room_yes) { ?>		
			<div id="optionyes" style="visibility: visible; display: inline;"></div>		  
		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php }elseif($display_room_no) { ?>		
			<div id="optionyes" style=" visibility: hidden; display: none;"></div>		  
		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php 
		}
		
		//amit added to check operatedate start
		  $months_operate_array = array(); 
		  for ($i=1; $i<13; $i++) { 
			$months_operate_array[] = array('id' => sprintf("%02d", $i),
									'text' => strftime('%b', mktime(0,0,0,$i,15))); 
		  } 
		  
		  $days_operate_array = array(); 
		
					  for ($i=1; $i<32; $i++) { 
						$days_operate_array[] = array('id' => sprintf("%02d", $i), 
									'text' => $i); 
					  } 			  
		  $operate_years_array = array(array('id' => '', 'text' => ''));
		  $operate_years_array[] = array('id' => '2007', 'text' => '2007');
		  $operate_years_array[] = array('id' => '2008', 'text' => '2008');
		  $operate_years_array[] = array('id' => '2009', 'text' => '2009');
		  $operate_years_array[] = array('id' => '2010', 'text' => '2010'); 		  
		 //amit added to check operatedate end
		 
		$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
		$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and (c.is_attractions='0' or c.is_attractions='2') order by c.city");
		while ($city_class = tep_db_fetch_array($city_class_query)) {
		  $city_class_array[] = array('id' => $city_class['city_id'],
									 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
		}
 
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_OPERATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		 <form name="new_product"  id="new_product">		
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <?php if($access_full_edit == 'true') { ?>
		 <tr>		  
			<td class="main" colspan="2"><?php echo TEXT_HEADING_GROSS_PROFIT.'&nbsp;'; ?>
			
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %</td>
		 </tr>
		 <tr>		  
		<td class="errorText" colspan="2">
		<?php echo TEXT_NOTICE_CP_AND_RP;?>
		</td>
		</tr>	
		<?php } ?> 
		 <?php						
			$no_of_sec=regu_irregular_section_numrow($pInfo->products_id);
		 ?>
		  <tr >
			<td class="main" colspan="2"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
		</tr>	
		<tr id="on_change_show">
		  <td align="left" valign="top" class="main"># of regular/irregular sections</td>
			<td nowrap width="980"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15');?>
			  <div id="reg_irrg_id">
			  <input type="text" name="numberofsection" value="<?php echo $no_of_sec;?>">
			  <input type="button" name="go" value="Go" onClick="getInfoRegIrreg('createreg_illregulartour.php?product_id=<?=$pInfo->products_id;?>&numberofsection='+document.new_product.numberofsection.value,'-1');">
			  
			  </div>
			  <div id="div_id_reg_irreg_input">
				<?php
					$_GET['numberofsection']=$no_of_sec;
					$_GET['product_id']=$pInfo->products_id; 
					include("createreg_illregulartour_edit.php");
				?>
				  </div>
				
			  </td>
		  </tr>
		   <tr>
		   <td colspan="2"><?php echo tep_draw_hidden_field('regions_id', $pInfo->regions_id); ?>
		   <div id="country_state_start_city_id">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td class="main" width="13%"><?php echo TEXT_HEADING_CITIES_BY_COUNTRY; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '21', '10'); ?>
			<?php
			echo tep_draw_pull_down_menu('countries_search_id', tep_get_countries('select country'), '', 'onChange="change_region_state_list_edit(this.value,'.$HTTP_GET_VARS['pID'].','.$pInfo->regions_id.');"');
			?>		
			</td>
		   </tr>
			<tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
				<?php
			if($pInfo->departure_city_id == ''){
			$pInfo->departure_city_id = 0;
			}
				$city_start_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_start_class_left = tep_db_fetch_array($city_start_class_query_left)) {
				  $city_start_class_array_left[] = array('id' => $city_start_class_left['city_id'],
											 'text' => $city_start_class_left['city'].', '.$city_start_class_left['zone_code'].', '. $city_start_class_left['countries_iso_code_3']);
				}
				
				$city_start_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_start_class_right = tep_db_fetch_array($city_start_class_query_right)) {
				  $city_start_class_array_right[] = array('id' => $city_start_class_right ['city_id'],
											 'text' => $city_start_class_right ['city'].', '.$city_start_class_right['zone_code'].', '. $city_start_class_right['countries_iso_code_3']);
				}
			?>
			<tr>
			<td class="main" nowrap ><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
			<td class="main">
				<table border="0">
				  <tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_city_id_temp', $city_start_class_array_left, '',' id="departure_city_id_temp" multiple="multiple" size="10"'); ?></td>
					<td><INPUT onClick="moveOptions(this.form.departure_city_id_temp, this.form.departure_city_id_temp1);" type=button value="-->">
					<BR><INPUT onClick="moveOptions(this.form.departure_city_id_temp1, this.form.departure_city_id_temp);" type=button value="<--"></td>
					<td>
					<?php echo  tep_draw_pull_down_menu('departure_city_id_temp1', $city_start_class_array_right, '',' id="departure_city_id_temp1" multiple="multiple" size="10"'); ?>
					<input type="hidden" name="departure_city_id" value="">
					</td>
				  </tr>
				</table>
				<?php //echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array, $pInfo->departure_city_id); ?>
			</td>
			</tr>
			<tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
					  
		   <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_DEPARTURE_END_CITY; ?></td>
			<td class="main">			
			<?php
			if($pInfo->departure_end_city_id == ''){
			$pInfo->departure_end_city_id = 0;
			}
				$city_end_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_end_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_class_left = tep_db_fetch_array($city_end_class_query_left)) {
				  $city_end_class_array_left[] = array('id' => $city_class_left['city_id'],
											 'text' => $city_class_left['city'].', '.$city_class_left['zone_code'].', '. $city_class_left['countries_iso_code_3']);
				}
				
				$city_end_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_end_city_id.") and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
				while ($city_class_right = tep_db_fetch_array($city_end_class_query_right)) {
				  $city_end_class_array_right[] = array('id' => $city_class_right ['city_id'],
											 'text' => $city_class_right ['city'].', '.$city_class_right['zone_code'].', '. $city_class_right['countries_iso_code_3']);
				}
			?>
			<table border="0">
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_end_city_id_temp', $city_end_class_array_left, '',' id="departure_end_city_id_temp" multiple="multiple" size="10"'); ?></td>
				<td><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp, this.form.departure_end_city_id_temp1);" type=button value="-->">
				<BR><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp1, this.form.departure_end_city_id_temp);" type=button value="<--"></td>
				<td>
				<?php echo  tep_draw_pull_down_menu('departure_end_city_id_temp1', $city_end_class_array_right, '',' id="departure_end_city_id_temp1" multiple="multiple" size="10"'); ?>
				<input type="hidden" name="departure_end_city_id" value="">
				</td>
			  </tr>
			</table>
			</td>
		  </tr>
		  <?php $k =1;
				if($pInfo->products_id != "")
				{
						
						$products_departure_query = tep_db_query("select * from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = ".$pInfo->products_id." order by departure_region");
						while ($products_departure_result = tep_db_fetch_array($products_departure_query)) 
						{
							$edit_extra_lines = "";
							if($region_name_display != $products_departure_result['departure_region'])
							{
							$selecthisid = $k;
							$region_name_display = $products_departure_result['departure_region'];
							$edit_extra_lines = '<tr><td colspan="6" class="main"><input type="checkbox" checked name="chkalldasda'.$selecthisid.'" onClick="chkallregion(this.form,this.checked,\'selece_'.$selecthisid.'\')" ><b>'.$region_name_display.'</b></td></tr>';
							}
 $edit_departure_data .= '<table id="table_id_departure'.$k.'" cellpadding="2" cellspacing="2" width="100%"><tbody>'.$edit_extra_lines.'<tr class="'.(floor($k/2) == ($k/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent" valign="top" width="110" nowrap><input class="buttonstyle" checked  id="selece_'.$selecthisid.'" name="regioninsertid_'.$k.'" type="checkbox" value="'.$k.'">'.$k.'.<input name="depart_region_'.$k.'" value="'.addslashes($products_departure_result['departure_region']).'" size="12" type="hidden" ></td><td class="dataTableContent" valign="top" width="100" nowrap><input name="depart_time_'.$k.'" value="'.addslashes($products_departure_result['departure_time']).'" size="12" type="text"></td><td class="dataTableContent" align="center"  ><input size="20" name="departure_address_'.$k.'" value="'.addslashes($products_departure_result['departure_address']).'" type="text"></td><td class="dataTableContent" align="center" ><input size="30" name="departure_full_address_'.$k.'" value="'.addslashes($products_departure_result['departure_full_address']).'" type="text"></td><td class="dataTableContent" align="center" width="100"><input size="30" name="departure_map_path_'.$k.'" value="'.addslashes($products_departure_result['map_path']).'" type="text"></td><td align="center" width="70"></td></tr></tbody></table>';
			
		
							$k++;
						}
					  
				}
		?>
		  		  
		 <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		  <tr>
			<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESTINATION; ?></td>
			<td class="main">
			<table border="0">
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '20', '12'); 
					
					if($pInfo->products_id != "")
						{
						$sel2values = "";
						$countingcity = 0;
							//$destination_query = tep_db_query("select * from ".TABLE_PRODUCTS_DESTINATION." where products_id = ".$pInfo->products_id." order by city_id");
							//while ($destination_result = tep_db_fetch_array($destination_query))
							//{
								$city_edit_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co, ".TABLE_PRODUCTS_DESTINATION." as pde  where pde.products_id = ".$pInfo->products_id." and pde.city_id = c.city_id and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !=''  and (c.is_attractions='1' or c.is_attractions='2')  order by c.city");
								while($city_edit_result = tep_db_fetch_array($city_edit_query)){
									$sel2values .= "<option value=".$city_edit_result['city_id'].">".$city_edit_result['city'].', '.$city_edit_result['zone_code'].', '.$city_edit_result['countries_iso_code_3']."</option>";
															
										if($countingcity == 0){
											$allready_product_city = $city_edit_result['city_id'];
										}else{
											$allready_product_city .= ",".$city_edit_result['city_id'];
										}
										$countingcity++;														
								}
																		
							//}
							$tempsolution="";
						if($allready_product_city == '') $allready_product_city=0;
						
						$city_new_query = tep_db_query("select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and  ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") and ttc.city!=''  and ttc.city!='' and (ttc.is_attractions='1' or ttc.is_attractions='2') order by ttc.city");
						 while ($city_new_result = tep_db_fetch_array($city_new_query)) 
						{
						 $tempsolution .=  "<option value=".$city_new_result['city_id'].">".$city_new_result['city'].', '.$city_new_result['zone_code'].', '.$city_new_result['countries_iso_code_3']."</option>";
						} 

						}
						
					?>
						<select name="sel1" id="sel1" size="10" multiple="multiple">
						<?php 
						echo $tempsolution;						
						?>
						</select>
					</td>
					<td align="center" valign="middle">
						<input type="button" value="--&gt;"
						 onclick="moveOptions(this.form.sel1, this.form.sel2);" /><br />
						<input type="button" value="&lt;--"
						 onclick="moveOptions(this.form.sel2, this.form.sel1);" />
					</td>
					<td>
						<select name="sel2" size="10" multiple="multiple">
						<?php 
						if($pInfo->products_id != "")
						{
							echo $sel2values;
						}
						?>
						</select>
						<input type="hidden" name="selectedcityid" value="">
					</td>
				</tr>
				
			</table>
			</td>
		  </tr>
		  </table>			
		   </div>
		   
		   </td>
		   </tr>
		   <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
				<?php
						$arr_times_type = array();
						$arr_times_type[] = array('id' => 0,'text' => 'days');
						$arr_times_type[] = array('id' => 1,'text' => 'hours');
						$arr_times_type[] = array('id' => 2,'text' => 'minutes');
				?>				
		  <tr>
			<td class="main"><?php echo TEXT_PRODUCTS_DURATION; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_durations', $pInfo->products_durations, 'size="2" '); //onChange="change_itenerary_hotel('.$pInfo->products_id.');" ?>
			<?php echo tep_draw_pull_down_menu('products_durations_type',$arr_times_type,$pInfo->products_durations_type); //,'onchange="change_itenerary_hotel('.$pInfo->products_id.');"'?>
			</td>
		  </tr>
		  <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		 <tr>
			 <td class="main" nowrap><?php echo TEXT_PRODUCTS_DISPLAY_PICKUP_HOTELS; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
			<?php echo tep_draw_checkbox_field('display_pickup_hotels', '1', $pInfo->display_pickup_hotels); ?></td>
		  </tr>
		 <tr>
			<td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		  <tr>
			<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DEPARTURE_PLACE; ?></td>
			<td class="main">
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
						  
						  <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr>
							<td colspan="6">
							 <table border="0" cellpadding="0" cellspacing="0" width="100%">
								  <tr class="dataTableHeadingRow">
									<td class="dataTableHeadingContent" width="110" align="left">Region</td>
									<td class="dataTableHeadingContent" width="100" align="left">Departure Time</td>
									<td class="dataTableHeadingContent" width="60" align="center">Location</td>
									<td class="dataTableHeadingContent" width="80" align="center">Address</td>
									<td class="dataTableHeadingContent" width="80" align="center">Map Path</td>
									<td class="dataTableHeadingContent" width="70" align="center"></td>
								  </tr>
								 </table>
							</td>
						  </tr>
						  <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>
						
						<tr class="dataTableRow">
						<td class=dataTableContent colspan="6">
						  <div id="div_id_departure"><?php echo $edit_departure_data; ?></div>	
						  </td>					  
						</tr>						
									
						 <tr>
							<td colspan="6"><?php echo tep_black_line(); ?></td>
						  </tr>
						  
						  <tr class="dataTableRow">
							<td class="dataTableContent" width="110" valign=top> <div id="div_id_region_select"></div>&nbsp;
									<?php 																										
									$options_region = '';
									$region_name_display = '';									
										$regionquery = 'select * from '.TABLE_TOUR_PROVIDER_REGIONS.' where agency_id = "'.$pInfo->agency_id.'" order by region';	 
										$regionrow = mysql_query($regionquery);	
										while($products_departure_result = mysql_fetch_array($regionrow))
										{											
											if($region_name_display != $products_departure_result['region'])
											{
											$region_name_display = $products_departure_result['region'];
											$options_region .= '<option value="'.$region_name_display.'">'.$region_name_display.'</option>';
											}
																					
										}	
									
									$select_region = '<select name="region">'.$options_region.'</select>';
									echo $select_region;
									?>
							</td>
							<td class="dataTableContent" width="100" valign=top><input type="hidden" name="numberofdaparture" value="<?php echo $k; ?>">&nbsp;<?php echo tep_draw_input_field('depart_time', '', 'size="12"'); ?><br>(HH:MMam <br> e.g:- 9:00am)</td>
							<td class="dataTableContent" width="60" align="center"><?php echo tep_draw_input_field('departure_address', '', 'size="20"'); ?></td>
							<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_full_address', '', 'size="30"'); ?></td>
							<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_map_path', '', 'size="30"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_image_submit('button_insert.gif', '', 'onClick="return add_departure()"'); ?></td>
						   </tr>
						<tr>
						<td colspan="6"><?php echo tep_black_line(); ?></td>
					   </tr>
					 </table> 					  				
			</td>
		 </tr>
		 <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		  </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		 echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="SelectAll_ajax(sel2,departure_end_city_id_temp1,departure_city_id_temp1); sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '')  .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		 echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		 echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  echo tep_draw_hidden_field('display_room_option',$pInfo->display_room_option);
		  ?>
		  <input type="hidden" name="req_section" value="tour_operation">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour operation
    break;
	case 'tour_content':
		//start of tour content
		if (!isset($pInfo->display_itinerary_notes)) $pInfo->display_itinerary_notes = '1';
		  switch ($pInfo->display_itinerary_notes) {
		  case '0': $display_itinerary_notes_in_status = false; $display_itinerary_notes_out_status = true; break;
		  case '1':
		  default: $display_itinerary_notes_in_status = true; $display_itinerary_notes_out_status = false;
		}
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_CONTENT; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 <form name="new_product"  id="new_product">		
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
			<?php			
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {				
			?>
			<tr>
            <td class="main" valign="top" nowrap><?php echo TEXT_PRODUCTS_SMALL_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
                <td class="main">
				<?php echo tep_draw_textarea_field('products_small_description[' . $languages[$i]['id'] . ']', 'soft', '80', '4', (isset($products_small_description[$languages[$i]['id']]) ? $products_small_description[$languages[$i]['id']] : tep_get_products_small_description($pInfo->products_id, $languages[$i]['id']))); ?>
				</td>
				</tr>
				<tr>
				<td class="main"></td>
				<td class="main"><?php echo '<a target="_blank" href="' . HTTP_CATALOG_SERVER.'/tours-search/keywords/'.tep_get_products_model($HTTP_GET_VARS['pID']) . '"><b>Click here to Preview Highlights</b></a>'; ?></td>
				</tr>
            </table></td>
          	</tr>		  
		    <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
			<?php
				}
			?> 		
			<tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DISPLAY_ITINERARY_NOTES; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '1', $display_itinerary_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '0', $display_itinerary_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
         	</tr>		 
		  	<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          	</tr>
			<?php
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			?>
			  <tr>
				<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '80', '30', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
	
				  </tr>
				</table></td>
			  </tr>
				<tr>
				<td class="main" valign="top"><?php echo TEXT_PRODUCTS_OTHER_DESCRIPTION; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_other_description[' . $languages[$i]['id'] . ']', 'soft', '80', '8', (isset($products_other_description[$languages[$i]['id']]) ? $products_other_description[$languages[$i]['id']] : tep_get_products_other_description($pInfo->products_id, $languages[$i]['id'])),'id=id_products_other_description[' . $languages[$i]['id'] . ']'); ?></td>
	
				  </tr>
				</table></td>
			  </tr>
		  
			   <tr>
				<td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_PACKAGE_EXCLUDES; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_package_excludes[' . $languages[$i]['id'] . ']', 'soft', '80', '8', (isset($products_package_excludes[$languages[$i]['id']]) ? $products_package_excludes[$languages[$i]['id']] : tep_get_products_package_excludes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_package_excludes[' . $languages[$i]['id'] . ']'); ?></td>
	
				  </tr>
				</table></td>
			  </tr>
		  
			   <tr>
				<td class="main" valign="top"><?php echo TEXT_PRODUCTS_PACKAGE_SPECIAL_NOTES; ?></td>
				<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
					<td class="main"><?php echo tep_draw_textarea_field('products_package_special_notes[' . $languages[$i]['id'] . ']', 'soft', '80', '20', (isset($products_package_special_notes[$languages[$i]['id']]) ? $products_package_special_notes[$languages[$i]['id']] : tep_get_products_package_special_notes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_package_special_notes[' . $languages[$i]['id'] . ']'); ?></td>
	
				  </tr>
				</table></td>
			  </tr>		 
		<?php } ?>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';		 
		   if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_content">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour content
    break;

	case 'tour_eticket':
		//start of tour eticket
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_ETICKET_INFORMATION; ?></b></td></tr>
		 <tr><td class="main" colspan="2">		 
		 <form name="new_product"  id="new_product">		
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
		  <td colspan="2">
		  		<table width="100%" border="0" cellspacing="0" cellpadding="2">  			
				<tr>			
				<td  valign="top">
				<div id="eticket_div" >
			
							<?php			
							
							if($pInfo->products_durations_type == 0 && $pInfo->products_durations !=0 && $pInfo->products_durations > 0 ){
							$count = $pInfo->products_durations;
							}else if($pInfo->products_durations_type != 0){
							$count = 1;
							}
							
							?>
							
							<table width = "100%"  >
								<tr>

									<td colspan=2 >
										
											</td>

											<td  >
												
														<table width="100%"   border="0" >
															<tr>
							<td width="10%"></td>
							
							<td class="main"  align="left"  width="30%">
							<b>	<?php echo TEXT_HEADING_ETICKET_ITINERARY; ?></b>
							</td>
							<td class="main" align="left" width="30%">
								<b><?php echo TEXT_HEADING_ETICKET_HOTEL; ?> </b>
							</td>
							<td class="main" align="left" width="30%" >
								<b><?php echo TEXT_HEADING_ETICKET_NOTES; ?> </b>
							</td>
						</tr>
						</table></td></tr>


					<?php
					 for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					 ?>
					 <tr>
						<td colspan=2 class="main" >
						
							<?php echo TEXT_HEADING_ETICKET. ' ' .  tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
						</td>
			
						<td>
							<table width="60%"  border="0" >
								<tr>
									<td>		
		
						<table width="100%">				
							<?php 
							for($j=1; $j<=$count; $j++)
							{
								$content_prod=tep_get_products_eticket_itinerary($products_id, $languages[$i]['id']);
								$splite_content =  explode("!##!", $content_prod);
							
							
								$content_prod_hotel=tep_get_products_eticket_hotel($products_id, $languages[$i]['id']);
								$splite_content_hotel =  explode("!##!", $content_prod_hotel);
							
										$content_prod_notes=tep_get_products_eticket_notes($products_id, $languages[$i]['id']);
										$splite_content_notes =  explode("!##!", $content_prod_notes);
								
							?>				
									  <tr>
										   <td class="main" align="center" width="10%" nowrap>Day <?php echo $j ?></td> 
											<td class="main" align="center" width="30%"><?php echo tep_draw_textarea_field('eticket_itinerary[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_itinerary[$languages[$i]['id']]) ? $eticket_itinerary[$languages[$i]['id']] : $splite_content[$j-1]),'id=eticket_itinerary[' . $languages[$i]['id'] . ']'); ?>
											</td>
							<td class="main" width="30%" align="center"><?php echo tep_draw_textarea_field('eticket_hotel[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_hotel[$languages[$i]['id']]) ? $eticket_hotel[$languages[$i]['id']] : $splite_content_hotel[$j-1]),'id=eticket_hotel[' . $languages[$i]['id'] . ']'); ?></td>
							
										 <td class="main" width="30%" align="center">
											<?php echo tep_draw_textarea_field('eticket_notes[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_notes[$languages[$i]['id']]) ? $eticket_notes[$languages[$i]['id']] : $splite_content_notes[$j-1]),'id=eticket_notes[' . $languages[$i]['id'] . ']'); ?>
											</td>
							
									  </tr>
									  
									  
									<?php		
									}	
									?>
									</table>
										</td></tr></table></td></tr>
							
							<?php	
									}?>				
											</table>
				
				
				</div>	
				</td></tr></table>
				
				 
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
				  <tr>
					<td class="main" width="15%" nowrap><?php echo TEXT_HEADING_SPL_NOTES; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_special_note', 'soft', '90', '15',$pInfo->products_special_note); ?></td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  </table>
		  
		  </td>
		  </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';		 
		   if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '').(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  echo tep_draw_hidden_field('products_durations_type', $pInfo->products_durations_type);
		  echo tep_draw_hidden_field('products_durations', $pInfo->products_durations);
		  ?>
		  <input type="hidden" name="req_section" value="tour_eticket">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour eticket
    break;

	case 'tour_image_video':
		//start of tour image video
		?>
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_IMAGES_VIDEOS; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		<?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') . '&action=upload_product_video', 'post', 'enctype="multipart/form-data" '); ?>
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image_med', $pInfo->products_image_med,'') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_med) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_med . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) .tep_draw_hidden_field('products_previous_image', $pInfo->products_image). '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>
		  <tr class="dataTableRow">
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '30'); ?></td>
          </tr>
		  <tr class="dataTableRow">
			 <td class="main" valign="top" colspan="2"><b>Extra Images:</b></td>
		  </tr>
		  
		  <tr class="dataTableRow">
            <td class="main" valign="top" colspan="2">
								<div>
								<table width="70%"  border="1" cellspacing="3" cellpadding="3">
								  <tr class="dataTableHeadingRow">
									<td width="250" class="dataTableHeadingContent">Image</td>
									<td width="150" nowrap="nowrap" class="dataTableHeadingContent">Sort Order</td>
									<td width="150" class="dataTableHeadingContent">Remove</td>
								  </tr>
								</table>
								</div>				
								<div id="myDiv">
								<?php
								
								$category_intro_query_sql  = "select *  from " . TABLE_PRODUCTS_EXTRA_IMAGES . " where products_id = '" . $HTTP_GET_VARS['pID'] . "' order by image_sort_order";
								$category_intro_query = tep_db_query($category_intro_query_sql);
								
								$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
								//$category_intro = tep_db_fetch_array($category_intro_query);
								if($tt_into_cnt_row > 0){
									?>
									<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValue" />
									<?php
									$row = 1;
									while($category_intro = tep_db_fetch_array($category_intro_query)){
									?>
									<div id="my<?php echo $row;?>Div">
									<table width="70%" border="0" cellspacing="3" cellpadding="3">
									  <tr >
										<td  valign="top"  width="250">
										<?php
										if($category_intro['product_image_name']!= '') {
										?>
										<img src="<?php echo HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES.$category_intro['product_image_name'];?>" alt="<?php echo $category_intro['product_image_name'];?>" width="100" height="100"><br/>
										<?php } ?>
										<input type='file' name='image_introfile[]'>
										<input type="hidden" name="previouse_image_introfile[]" value="<?php echo $category_intro['product_image_name'];?>">
										</td>
										<td valign='top' width="150"><input type="text" name='cat_intro_sort_order[]' size="10" value="<?php echo $category_intro['image_sort_order'];?>"></td>
										<td valign='top' width="150"><input type="hidden" name="db_categories_introduction_id[]" value="<?php echo $category_intro['prod_extra_image_id'];?>"> <input type="hidden" id="remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>" name="remove_id_form_db[]"  value="off"><input type="checkbox" name="delete_frm_db[]" onClick="document.getElementById('remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>').value='on'"></td>
									  </tr>
									</table>
									</div>		
									<?php		
									$row++;
									}
									?>
									<?	
								}else{
								?>
								<input type="hidden" value="1" id="theValue" />
								<div id="my1Div">
								<table width="70%" border="0" cellspacing="3" cellpadding="3">
								  <tr>
									<td valign="top"  width="250"><input type='file' name='image_introfile[]'>		
									</td>
									<td valign='top' width="150"><input type="text" name='cat_intro_sort_order[]' size="10" value="1"></td>
									<td valign='top' width="150"><a href="javascript:;" onClick="removeEvent('my1Div')">Remove</a></td>
								  </tr>
								</table>
								</div>
								<?php
								}
								?>
								</div>
								<p><a href="javascript:;" onClick="addEventExtra();"><b><font color="#000099">Add More Images</font></b></a></p>
			
			</td>
          </tr>
		  
          <!--<tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php //echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span></td>
           <?php //if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>'; ?>
           <?php //} else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image', $pInfo->products_image,'') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
           //} if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image) != '')
           //echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>-->
		  <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_MAP_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_map') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_map', $pInfo->products_map,'') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_map) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_map . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_map, $pInfo->products_map, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_map" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_map" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
		   </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		    <tr>
            <td  class="main"><?php echo TEXT_PRODUCTS_VIDEO; ?></td>
			<td class="main">
			<small>
			Direct URL
			</small>&nbsp;&nbsp;<?php echo tep_draw_input_field('products_video_url', '', 'size="30"'); ?>
			<?php
			if(substr($pInfo->products_video,0,5) == 'http:'){
			echo "<br>".$pInfo->products_video;
			$directvurl="show";
			}
			?>						
			<br><small>or</small><br>
			<small>Upload Video</small>&nbsp;&nbsp;
			<?php echo tep_draw_file_field('products_video').tep_draw_hidden_field('products_previous_video', $pInfo->products_video); ?>
			<br>
			<?php
			if($directvurl != "show" && $pInfo->products_video != '' ){
			echo $pInfo->products_video;
			}
			?>
			</td>
          </tr>
		    <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  //echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';		 
		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  ?>
		  <input type="hidden" name="req_section" value="tour_image_video">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php
		//end of tour image video
    break;

	case 'tour_meta_tag':
		//start of tour meta tag
		?>		
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_PRODUCT_METTA_INFO; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		 <form name="new_product"  id="new_product">		 		
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				 <?php
					for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				?>
				 <tr>
				 <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
					  </tr>
					</table></td>
				  </tr>
		
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				   <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
					  </tr>
					</table></td>
				  </tr>
				  <tr>	
							  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				   <tr>
					<td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></td>
					<td><table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
						<td class="main"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
					  </tr>
		
					</table></td>
				  </tr>
				  <?php } ?>						  
				  <tr>
				  <td colspan="2" align="center">
				  <?php
				  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '').(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
				  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
				  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';
		  		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
				  }else{
						echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
				  }
				 ?>
				  <input type="hidden" name="req_section" value="tour_metataginformation">
				  <input type="hidden" name="qaanscall" value="true">	
				  
				  </td>
				  </tr>
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				
		  		</table>
			</form>
		 </td></tr>
		 </table>
		 
		 <?php
		//end of tour meta tag
    break;

	case 'tour_attribute':
		//start of tour attribute
		
		if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
		switch ($pInfo->display_room_option) {
		  case '0': $display_room_yes = false; $display_room_no = true; break;
		  case '1':
		  default: $display_room_yes = true; $display_room_no = false;
		}
		
		
		?>
		
		 <?php 
		 if($display_room_yes)
		 {
		 ?>		
		<div id="optionyes" style="visibility: visible; display: inline;"></div>
		  
		   <div id="optionno" style="visibility: hidden; display: none;"></div>
		 <?php
		 }elseif($display_room_no)
		 {
		 ?>
		 <script type="text/javascript">
		 	option_value_yn = <?php echo $display_room_no;?>;			
		 </script>		
		<div id="optionyes" style=" visibility: hidden; display: none;"></div>
		  
		   <div id="optionno" style="visibility: visible; display: inline;"></div>
		<?php 
		}?>
		  
		 
		 <table border="0" cellspacing="0" cellpadding="2">
         <tr><td class="main" colspan="2"><b><?php echo TEXT_HEADING_TITLE_TOUR_ATTRIBUTES; ?></b></td></tr>
		 <tr><td class="main" colspan="2">
		 
		 <form name="new_product"  id="new_product">		
		 <table width="100%" border="0" cellspacing="0" cellpadding="2">
		 
		 <?php if($access_full_edit == 'true') { ?>
		 <tr>		  
			<td class="main" colspan="2"><?php echo TEXT_HEADING_GROSS_PROFIT.'&nbsp;'; ?>
			
			<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_margin', $pInfo->products_margin, 'size="7"'); ?> %</td>
		 </tr>
		 <tr>		  
			<td class="errorText" colspan="2">
			<?php echo TEXT_NOTICE_CP_AND_RP; ?>
			</td>
		</tr>	
		<?php } ?> 
		<tr>
        <td colspan="2">
		<div id="tour_attribute_list"><table border="3" cellspacing="5" cellpadding="2" align="center" bgcolor="000000">
		
			<?php
				$rows = 0;
				
			   if($pInfo->agency_id!='')
				{	
				  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$pInfo->agency_id."' order by products_options_sort_order, products_options_name");
				  
				}
				else
				{
				$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
				}	
				while ($options = tep_db_fetch_array($options_query)) {
				  
				if($pInfo->agency_id!='')
				{
				
				
				  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$pInfo->agency_id."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
					 
				 
				 } 
				 else
				 {
					$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
				 } 
				  $header = false;
				  while ($values = tep_db_fetch_array($values_query)) {
					$rows ++;
					if (!$header) {
					  $header = true;
			?>
					  <tr valign="top">
						<td><table border="2" cellpadding="2" cellspacing="2" bgcolor="FFFFFF">
						  <tr class="dataTableHeadingRow">
						  <td colspan="10" class="attributeBoxContent" align="center">Active Attributes</td>
						 </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left"><?php echo $options['products_options_name']; ?></td>
							<td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php if($display_room_yes){ echo 'Single'; }else{echo 'Adult';}?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Double'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Triple'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Quadruple'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Kids'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
							<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Special'; ?></td>
						  </tr>
			<?php
					}
						$attributes = array();
					
						  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, options_values_price_cost, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,single_values_price_cost, double_values_price_cost,triple_values_price_cost,quadruple_values_price_cost,kids_values_price_cost, products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $pInfo->products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
						  if (tep_db_num_rows($attributes_query) > 0) {
							$attributes = tep_db_fetch_array($attributes_query);
						  }
										
						if($attributes['single_values_price']>0 || $attributes['double_values_price']>0 || $attributes['triple_values_price']>0 || $attributes['quadruple_values_price']>0 || $attributes['kids_values_price']>0)
						{
							$check_spe_price_attri=true;
						}
						else
						{
							$check_spe_price_attri=false;
						}
					
					
			?>
						  <tr class="dataTableRow">
							<td class="dataTableContent"><?php echo tep_draw_checkbox_field('option[' . $rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id']) . '&nbsp;' . $values['products_options_values_name']; ?>&nbsp;</td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('prefix[' . $rows . ']', $attributes['price_prefix'], 'size="2"'); ?></td>
							<td class="dataTableContent" width="80" align="center" nowrap>
							<?php 
							if($access_full_edit == 'true') {	
							echo 'RP'.tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], ' id="price'.$rows.'"  size="7" '.($check_spe_price_attri?'disabled="disabled"':'').''); 
							echo '<br>CP'.tep_draw_input_field('price_cost[' . $rows . ']', $attributes['options_values_price_cost'], ' id="price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'price_cost'.$rows.'\'), document.getElementById(\'price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">';
							
							}else{
							echo tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], 'size="7" '.($check_spe_price_attri?'disabled="disabled"':'').''); 
							}
							?></td>
							
							<?php if($access_full_edit == 'true') {	?>
							<td class="dataTableContent"  align="center" width="70">
							<?php 
							if($check_spe_price_attri){ 
							echo tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], ' id="single_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('single_price_cost[' . $rows . ']', $attributes['single_values_price_cost'], ' id="single_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'single_price_cost'.$rows.'\'), document.getElementById(\'single_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">'; 
							}else {
							echo '<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'id="single_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('single_price_cost[' . $rows . ']', $attributes['single_values_price_cost'], ' id="single_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'single_price_cost'.$rows.'\'), document.getElementById(\'single_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'"></div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';
							}
							?>
							</td>							
							<td class="dataTableContent"  align="center" width="70">
							<?php 
							if($check_spe_price_attri){
							echo tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], ' id="double_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('double_price_cost[' . $rows . ']', $attributes['double_values_price_cost'], ' id="double_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'double_price_cost'.$rows.'\'), document.getElementById(\'double_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">';
							}else { 
							echo '<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], ' id="double_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('double_price_cost[' . $rows . ']', $attributes['double_values_price_cost'], 'id="double_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'double_price_cost'.$rows.'\'), document.getElementById(\'double_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'"></div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; 
							} 
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php  
							if($check_spe_price_attri){
							echo tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], ' id="triple_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('triple_price_cost[' . $rows . ']', $attributes['triple_values_price_cost'], ' id="triple_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'triple_price_cost'.$rows.'\'), document.getElementById(\'triple_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">';
							}else { 
							echo '<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'id="triple_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('triple_price_cost[' . $rows . ']', $attributes['triple_values_price_cost'], 'id="triple_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'triple_price_cost'.$rows.'\'), document.getElementById(\'triple_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'"></div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; 
							} 
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php  
							if($check_spe_price_attri){
							echo tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], ' id="quadruple_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('quadruple_price_cost[' . $rows . ']', $attributes['quadruple_values_price_cost'], ' id="quadruple_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'quadruple_price_cost'.$rows.'\'), document.getElementById(\'quadruple_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">';
							}else { echo '<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'id="quadruple_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('quadruple_price_cost[' . $rows . ']', $attributes['quadruple_values_price_cost'], 'id="quadruple_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'quadruple_price_cost'.$rows.'\'), document.getElementById(\'quadruple_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'"></div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; 
							} 
							?>
							</td>
							<td class="dataTableContent"  align="center" width="70">
							<?php  
							if($check_spe_price_attri){
							echo tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], ' id="kids_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('kids_price_cost[' . $rows . ']', $attributes['kids_values_price_cost'], ' id="kids_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'kids_price_cost'.$rows.'\'), document.getElementById(\'kids_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'">';
							}else { 
							echo '<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'id="kids_price'.$rows.'" size="7"').'<br>'.tep_draw_input_field('kids_price_cost[' . $rows . ']', $attributes['kids_values_price_cost'], 'id="kids_price_cost'.$rows.'" size="7"').'<br>'.'<input onClick="calculate_retail_price(document.new_product.products_margin, document.getElementById(\'kids_price_cost'.$rows.'\'), document.getElementById(\'kids_price'.$rows.'\'));"  type="button" value="'. TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE.'"></div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; 
							} ?>
							</td>
							<?php }else { ?>
							<td class="dataTableContent"  align="center" width="70"><?php if($check_spe_price_attri){ tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"');}else {echo '<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>';}	?></td>
							<td class="dataTableContent"  align="center" width="70"><?php if($check_spe_price_attri){tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; } ?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; }?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; }?></td>
							<td class="dataTableContent"  align="center" width="70"><?php  if($check_spe_price_attri){tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"');}else { echo '<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'</div>'; } ?></td>
							<?php } ?> 
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center">			
							<?php				
							echo  ($check_spe_price_attri?'<a href="javascript:void(0)" onclick="javascript:delete_spe_price_attri('.$rows.');">Delete spe. Price</a>':'<a href="javascript:void(0)" onclick="javascript:enter_spec_price_attri('.$rows.')">Sepecial Price</a>');
							?>				
							</td>
							</tr>
			<?php
				  }
				  if ($header) {
			?>
						</table></td>
			
			<?php
				  }
				}
			?>
					  </tr>
					</table></div></td>
				  </tr>
			<?php
			// EOF: WebMakers.com Added: Draw Attribute Tables
			?>
		 
		 
		  <tr>
		  <td colspan="2" align="center">
		  <?php
		  echo tep_image_submit('button_update.gif', IMAGE_UPDATE, ' onclick="sendFormData(\'new_product\',\''. tep_href_link('categories_ajax_sections.php', 'action=process&section='.$HTTP_GET_VARS['section'].'&pID=' . $HTTP_GET_VARS['pID'].'&cPath=' . $HTTP_GET_VARS['cPath'].(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')).'\',\'countrydivcontainer\',\'true\');" ');
		  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
		  echo '&nbsp;&nbsp;<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>';		 
		  if($HTTP_GET_VARS['searchkey'] != '' || $HTTP_GET_VARS['search'] != ''){
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'backsearch=true'.(isset($HTTP_GET_VARS['searchkey']) ? '&search=' . $HTTP_GET_VARS['searchkey'] . '' : '') .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&search=' . $HTTP_GET_VARS['search'] . '' : '')) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 	
		  }else{
				echo '&nbsp;&nbsp;<a  href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['pID'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '&cPath=' . $HTTP_GET_VARS['cPath'])) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';		 		
		  }
		  echo tep_draw_hidden_field('prev_agency_id', $pInfo->agency_id);
		  echo tep_draw_hidden_field('agency_id', $pInfo->agency_id);
		  ?>
		  <input type="hidden" name="req_section" value="tour_attribute">
		  <input type="hidden" name="qaanscall" value="true">	
		  
		  </td>
		  </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
				
		  </table>
			</form>
		 </td></tr>
		 </table>
		 <?php


		//end of tour attribute
    break;

 }
?>