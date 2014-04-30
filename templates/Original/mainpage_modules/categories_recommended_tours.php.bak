<?php //echo $category['categories_recommended_tours']; ?>
				
				 <?php 	
				 //recommented tour listng start
				  if($category['categories_recommended_tours_ids'] != '') {  ?>						
						  						  
				 	<div class="tab_biaoti"><div class="tab_biaoti_img"><img src="image/xiaogou.gif" /></div><div class="tab_biaoti_t"><h4>Recommended Tours</h4></div></div>
					<div class="xunhuanCopy">
					 <?php
					 
					   $new_arr_producs_array = explode(',',$category['categories_recommended_tours_ids']);
						  
						  foreach($new_arr_producs_array as $key => $val){
						  	
							 $product_query_select_new = "select distinct p.products_id, ta.operate_currency_code, pd.products_name, p.products_image, p.products_model, p.products_price, p.products_tax_class_id from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd, " . TABLE_TRAVEL_AGENCY . " ta  where   p.agency_id = ta.agency_id and p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and p.products_id = ".(int)$val."";
					        
							 $new_products_query_arrive = tep_db_query($product_query_select_new);
							  
							  while ($new_products_arrive = tep_db_fetch_array($new_products_query_arrive)) {							  
							   //amit modified to make sure price on usd
								if($new_products_arrive['operate_currency_code'] != 'USD' && $new_products_arrive['operate_currency_code'] != ''){
								 $new_products_arrive['products_price'] = tep_get_tour_price_in_usd($new_products_arrive['products_price'],$new_products_arrive['operate_currency_code']);
								}
							  //amit modified to make sure price on usd							  
							  ?>
							<div class="nr_leftCopy">
									<div class="nr_img_1"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']);?>"><?php echo tep_image(DIR_WS_IMAGES . $new_products_arrive['products_image'], db_to_html($new_products_arrive['products_name']), 124, 78) ;?></a></div>
									<div class="nr_img_2Copy"><div class="tt"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']);?>" class="sp3"><?php echo db_to_html(tep_db_prepare_input($new_products_arrive['products_name'])); ?></a> <span class="sp6">[<?php echo tep_db_prepare_input($new_products_arrive['products_model']);?>]</span></div></div>
									<div class="nr_img_2Copy_bottom"><div class="tt1">
									<?php 
									 if (tep_get_products_special_price($new_products_arrive['products_id'])) 
										{
										  echo '<s>' .  $currencies->display_price($new_products_arrive['products_price'], tep_get_tax_rate($new_products_arrive['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price(tep_get_products_special_price($new_products_arrive['products_id']), tep_get_tax_rate($new_products_arrive['products_tax_class_id'])) . '</span>&nbsp;';
										} 
										else 
										{
										  echo  $currencies->display_price($new_products_arrive['products_price'], tep_get_tax_rate($new_products_arrive['products_tax_class_id'])) ;
										}
									 ?>
									</div></div>
									
							</div>
							  <?php
							 }
						  }
						  ?>
							
						</div>	
					<?php } //end of check recommented not blank
					
					//recommented tour listng end 
					
					//champion top products start
					
					if(strlen($cat_and_subcate_ids)<1){$cat_and_subcate_ids='0';}
				   $champion_top_products_query_sql = "select ta.operate_currency_code, p.products_id, p.products_ordered, pd.products_name, p.products_durations, p.products_durations_type,p.products_price, p.products_tax_class_id, p.products_image, p.products_model,p.departure_city_id, p.products_video from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc, " . TABLE_TRAVEL_AGENCY . " ta  where p.agency_id = ta.agency_id and pd.products_id = p.products_id and ptoc.products_id = p.products_id AND ptoc.categories_id in (" . $cat_and_subcate_ids . ") and pd.language_id = '" . $languages_id. "' and p.products_ordered > 0 and products_status = '1' group by pd.products_id order by p.products_ordered DESC, pd.products_name limit 10";
 				   $champion_top_products_query_raw = tep_db_query($champion_top_products_query_sql);
				  
					if(tep_db_num_rows($champion_top_products_query_raw) > 0){		
					?>
					 <div class="tab_biaoti"><div class="tab_biaoti_img"><img src="image/xiaogou.gif" /></div><div class="tab_biaoti_t"><h4><?php echo db_to_html('热销路线')?></h4></div></div>
                       
					<?php  
					$rows=0;
					while ($listing = tep_db_fetch_array($champion_top_products_query_raw)) {
					$rows++;
					 //amit modified to make sure price on usd
						if($listing['operate_currency_code'] != 'USD' && $listing['operate_currency_code'] != ''){
						 $listing['products_price'] = tep_get_tour_price_in_usd($listing['products_price'],$listing['operate_currency_code']);
						}
					  //amit modified to make sure price on usd
					
						$operate = tep_get_display_operate_info($listing['products_id']);
						//amit added for number of sections start
						/*
						$operate = '';
						
						$num_of_sections = regu_irregular_section_numrow($listing['products_id']);
						if($num_of_sections > 0){
							$regu_irregular_array = regu_irregular_section_detail_short($listing['products_id']);
							
							foreach($regu_irregular_array as $k=>$v)
							{
								if(is_array($v))
								{
								
									$tourcatetype =	$regu_irregular_array[$k]['producttype'];
									$opestartdate =  $regu_irregular_array[$k]['operate_start_date'];
									$opeenddate =  $regu_irregular_array[$k]['operate_end_date'];
									$day1 ='';
									if($tourcatetype == 1){  //regular your
									  $operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".$listing['products_id']."  and  operate_start_date='".$opestartdate."' and  operate_end_date='".$opeenddate."'  order by products_start_day");
									 $numofrowregday = tep_db_num_rows($operator_query);
										  if($numofrowregday == 7)
										  {
										  			$opestartdayarray = explode('-',$opestartdate);																								
													$operatetomodistart = strftime('%b', mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opestartdayarray[1], 0));
															
													$opeenddayarray = explode('-',$opeenddate);													
													$operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
													

													if($opestartdate == '01-01' && $opeenddate == '12-31'){
														$operate .= 'Daily<br>';
													
													}else{
														$operate .= $operatetomodistart.'-'.$operatetomodiend.': Daily<br>';
									     
													}							
													
													
										  }
										  else
										  {
										
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
											  
											  }
											  		
											  		$opestartdayarray = explode('-',$opestartdate);																								
													$operatetomodistart = strftime('%b', mktime(0,0,0,$opestartdayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opestartdayarray[1], 0));
															
													$opeenddayarray = explode('-',$opeenddate);													
													$operatetomodiend = strftime('%b', mktime(0,0,0,$opeenddayarray[0],15)).' '.date("jS", mktime(0, 0, 0, 0,$opeenddayarray[1], 0));
		
													if($opestartdate == '01-01' && $opeenddate == '12-31'){
															$operate .= $day1.'<br>';
													
													}else{
														$operate .= $operatetomodistart.'-'.$operatetomodiend.': '.$day1.'<br>';
									     
													}		
												 }
									  
									  
									}else{ //irregular tours											
										
													$irredis_select_description = tep_get_irreg_products_duration_description($listing['products_id'],$opestartdate,$opeenddate);
													$operate .= $irredis_select_description.'<br>';
													
													
									}
									
								}
							}
						}
						
						*/
				
				//amit added for number of section end


				$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '".$listing['departure_city_id']."'  order by city");
				$cityclass = tep_db_fetch_array($cityquery);
							
					
						?>
						 <div class="xunhuan">
					 	
						 <?php if($rows%2==0){$class_neirong='neirong_1';}else{$class_neirong='neirong';}?>
						 <div class="<?php echo $class_neirong?>">
						    <div class="nr_left">
	                            <div class="nr_img_1"><a href="<?php echo  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']); ?>"><?php echo tep_image(DIR_WS_IMAGES . $listing['products_image'], db_to_html($listing['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ;?></a></div>
	  							<?php
								if($listing['products_video'] != '') { ?>
								<div class="nr_img_2">
								<div class="nr_img_2_wz">
								<a href="<?php echo  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id'].'&mnu=video&'.tep_get_all_get_params(array('info','mnu','rn'))); ?>">
								<img src="image/vido.gif" />
								</a></div>								
								</div>
								<?php } ?>								
	                        </div>
							<div class="nr_right">
							    <div class="nr_l_table">
								<table width="100%" height="124">
									 <tr><td height="20" ><?php echo '<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']).'"  class="xiao_btext">' . db_to_html($listing['products_name']) . ' <b> </b></a>';?> <?php echo '[' . $listing['products_model'] . ']'; ?> </td></tr>  
									 <tr><td height="18"><b><?php echo TEXT_DEPART_FROM;?></b><?php echo db_to_html($cityclass['city']);?></td></tr>
									 <tr>
									   <td  height="14"><b><?php echo TEXT_OPERATE;?></b><?php echo $operate;?></td>
									 </tr>
									 <tr><td height="17"><b><?php echo TEXT_DURATION;?></b><?php
									 /*
									  if($listing['products_durations'] > 1){
											if($listing['products_durations_type'] == 0){
												$str_day =   TEXT_DURATION_DAYS;
											}else{
												$str_day =  TEXT_DURATION_HOURS;
											}
										}
									  else{
											if($listing['products_durations_type'] == 0){
													$str_day =  TEXT_DURATION_DAYS;
												}else{
													$str_day =  TEXT_DURATION_HOURS;
												}
										}
										*/
										$str_day = '';
										if($listing['products_durations_type'] == 0){
												$str_day =  TEXT_DURATION_DAYS;
										}else if($listing['products_durations_type'] == 1){
												$str_day =  TEXT_DURATION_HOURS;
										}else if($listing['products_durations_type'] == 2){
												$str_day =  TEXT_DIRATION_MINUTES;
										}
										echo $listing['products_durations'].$str_day;
									 ?></td>
									 </tr>
									 <tr><td height="17"><b><?php echo TEXT_PRICE;?></b><span class="sp2"><?php 
									 if (tep_get_products_special_price($listing['products_id'])) 
										{
										  echo '<span class="sp8">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price(tep_get_products_special_price($listing['products_id']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
										} 
										else 
										{
										  echo  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) ;
										}
									 ?></span></td>
									 </tr>
									</table>
								</div>
	                            <div class="nr_img_3"><a href="<?php echo  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']); ?>" class="a_img_1"><?php echo tep_template_image_button('button_view_detail_more.gif')?></a></div>
							</div>
						 </div>
						 <div class="xiao_bt_bottom" style="height:0px; line-height:0px;"></div>
			    </div>
					  
						<?php 
					} //end of while					
					?>
					
					 <div class="ladt_tt"><a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a></div>
					<?php
					
					}
					//campions top productst end
					
					 ?>	
