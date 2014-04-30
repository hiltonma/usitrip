<?php
$pack_tj = " products_durations >= ".TOURS_PACKAGE_MIN_DAY_NUM." and products_durations_type < 1 ";
if(!tep_not_null($top_cat_and_subcate_ids)){
	$top_cat_and_subcate_ids = tep_get_category_subcategories_ids($cPathOnly);
}

if(!isset($_GET['mnu'])){
$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "vacationpackages";
}
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id','page1','true');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
 <div class="tab1_2">
	   <div class="tab1_2_1"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
	   <div class="tab1_2_2"><?php
		 echo tep_draw_form('frm_slippage_ajax_vackation_package', '' ,"",'id=frm_slippage_ajax_vackation_package');		
		 ?>
	   <?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('osCsid','sort','page','products_durations' , 'departure_city_id' , 'tours_type','page1', 'info', 'x', 'y')),'product_listing_index_vackation_packages.php','frm_slippage_ajax_vackation_package','div_product_vackation_packages'); ?>
	    <?php
		   
		  // if($sort !='' && !isset($_GET['sort'])){
				//$_GET['sort'] = $sort;
				echo '<input type="hidden" name="sort1" value="'.$sort.'">';
			//}
			//if($tours_type !='' && !isset($_GET['tours_type'])){
				//$_GET['tours_type'] = $tours_type;
				echo '<input type="hidden" name="tours_type1" value="'.$tours_type.'">';
			//}
			//if($products_durations !='' && !isset($_GET['products_durations'])){
				//$_GET['products_durations'] = $products_durations;
				echo '<input type="hidden" name="products_durations1" value="'.$products_durations.'">';
			//}
			//if($departure_city_id !='' && !isset($_GET['departure_city_id'])){
				//$_GET['departure_city_id'] = $departure_city_id;
				echo '<input type="hidden" name="departure_city_id1" value="'.$departure_city_id.'">';
			//}
		    echo '<input type="hidden" name="selfpagename" value="index_vackation_packages">';
			echo '<input type="hidden" name="ajxsub_send_vackation_packages" value="true">';
			echo '</form>';
			?>
	   </div>
</div>
<?php
  }


  if ($listing_split->number_of_rows > 0) {
  
 // echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" > <tr>';
  
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;
	   //amit modified to make sure price on usd
	  	if($listing['operate_currency_code'] != 'USD' && $listing['operate_currency_code'] != ''){
		 $listing['products_price'] = tep_get_tour_price_in_usd($listing['products_price'],$listing['operate_currency_code']);
		}
	  //amit modified to make sure price on usd

/*
      if (($rows/2) == floor($rows/2)) 
	  {
		//echo '<td>';
      } else 
	  {
       // echo '<td>';        
      }
	  
	  */
	 					
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

				//$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '".$listing['departure_city_id']."'  order by city");
				//$cityclass = tep_db_fetch_array($cityquery);
			
				if($listing['departure_city_id'] == ''){
				  $listing['departure_city_id'] = 0;
				}
				$display_str_departure_city = '';
				$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in (".$listing['departure_city_id'].")  order by city");
				while($cityclass = tep_db_fetch_array($cityquery)){
				$display_str_departure_city .= " " .$cityclass['city'] . ", ";
				}
				$display_str_departure_city = substr($display_str_departure_city, 0, -2);
			/*  new design section start */
			?>
			 <div class="xunhuan">
					     
						 <?php
						 if($rows%2==0){
						 $xiao_bt_class = "xiao_bt xiao_bt_1";
						 $neirong_class='neirong_1';
						 }else{
						 $xiao_bt_class = "xiao_bt";
						 $neirong_class='neirong'; 
						 }
						 ?>
						 
						 <?php
						 //显示特价标签，按买2送2、买2送1、双人特价、普通特价的优先次序处理
						 $specials_num = 0;
						 $special_str = '';
						 $is_buy2get2 = check_buy_two_get_one($listing['products_id'],'4');
						 $is_buy2get1 = check_buy_two_get_one($listing['products_id'],'3');
						 $is_double_special = double_room_preferences($listing['products_id']);
						 $is_special = check_is_specials($listing['products_id'],true,true);
						 //tour_type_icon
						 $tour_type_icon_sql = tep_db_query("select tour_type_icon from " . TABLE_PRODUCTS . " where products_id= '".$listing['products_id']."' ");
						 $tour_type_icon_row = tep_db_fetch_array($tour_type_icon_sql);
						if((int)$is_special || preg_match('/\bspecil\-jia\b/i',$tour_type_icon_row['tour_type_icon'])){
							$specials_num++;
							$special_str = '特价';
						}
						if((int)$is_double_special || preg_match('/\b2\-pepole\-spe\b/i',$tour_type_icon_row['tour_type_icon'])){
							$specials_num++;
							$special_str = '双人折扣';
						}
						if(($listing['products_class_id']=='4' && ($is_buy2get1=='1' || $is_buy2get1=='2') || preg_match('/\bbuy2\-get\-1\b/i',$tour_type_icon_row['tour_type_icon'])) ){
							$specials_num++;
							$special_str = '买2送1';
						}
						if(($listing['products_class_id']=='4' && ($is_buy2get2=='1' || $is_buy2get2=='3')) || preg_match('/\bbuy2\-get\-2\b/i',$tour_type_icon_row['tour_type_icon'])){
							$specials_num++;
							$special_str = '买2送2';
						}
						if($specials_num>1){
							$special_str .= '<span>更多优惠</span>';
						}
						
						$te_jia_on_list_div = '';
						if(tep_not_null($special_str)){
							$te_jia_on_list_div = '<div class="te_jia_on_list"><p>'.$special_str.'</p></div>';
							$xiao_bt_class .= ' list_bt_add';
						}
						?>
						
						<div class="<?= $xiao_bt_class?>">
						
						<?php echo db_to_html($te_jia_on_list_div);?>

						 <div class="xiao_bt_t"><?php echo '<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']).'"  class="xiao_btext">' . db_to_html($listing['products_name']) . ' <b> </b>[' . $listing['products_model'] . ']</a>';?></div></div>
						 
						 
						 <div class="<?php echo $neirong_class?>">
						    <div class="nr_left">
							<div class="nr_left1">
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
							<?php
							$num_produ_reveiw_cnts = (int)get_product_reviews_num($listing['products_id']);
							$num_produ_qanda_cnts = (int)get_product_question_num($listing['products_id']);
							$num_produ_traveler_photo_cnts = (int)get_traveler_photos_num($listing['products_id']);
							$num_produ_companion_post_cnts = (int)get_product_companion_post_num($listing['products_id']);
							if($num_produ_reveiw_cnts > 0 || $num_produ_qanda_cnts > 0 || $num_produ_traveler_photo_cnts > 0 || $num_produ_companion_post_cnts > 0){
							?>							
							<div class="nr_review_qa_photo_icon">
							<table width="170" border="0" cellspacing="0" cellpadding="0">
							  <tr>
							  <?php if($num_produ_companion_post_cnts > 0) { ?>
							  	<td align="left"><span class="highline-txt"><?php echo $num_produ_companion_post_cnts;?></span> <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$listing['products_id']);?>"><?php echo TEXT_TRAVEL_COMPANION_POSTS ?></a></td>
								<td width="15"></td>
							  <?php } ?>							  
							  <?php if($num_produ_qanda_cnts > 0) { ?>
								<td align="left"><span class="highline-txt"><?php echo $num_produ_qanda_cnts;?></span> <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&products_id='.$listing['products_id']);?>"><?php echo TEXT_QANDA ?></a></td>
								<td width="15"></td>
							  <?php } ?>
							  <?php if(($num_produ_companion_post_cnts <= 0 || $num_produ_qanda_cnts <= 0) && $num_produ_traveler_photo_cnts > 0) { ?>
							  	<td align="left"><span class="highline-txt"><?php echo $num_produ_traveler_photo_cnts;?></span> <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&products_id='.$listing['products_id']);?>"><?php echo TEXT_PHOTOS ?></a></td>
								<td width="15"></td>
							  <?php } ?>
							  <?php if($num_produ_companion_post_cnts <= 0 && $num_produ_qanda_cnts <= 0 && $num_produ_reveiw_cnts > 0) { ?>
								<td align="left"><span class="highline-txt"><?php echo $num_produ_reveiw_cnts;?></span> <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&products_id='.$listing['products_id']);?>"><?php echo TEXT_REVIEW ?></a></td>
								<td width="15"></td>
							  <?php } ?>
							  </tr>							  
							</table>
							</div>
							<?php
							}
							?>							
							</div>
							<div class="nr_right">
							    <div class="nr_l_table">
									<table >
									 <tr><td height="20" colspan="2"><b><?php echo TEXT_DEPART_FROM;?></b><?php echo db_to_html($display_str_departure_city);?></td>
									 </tr>  
									 <tr><td height="21" colspan="2"><b><?php echo TEXT_OPERATE;?></b><?php echo $operate;?></td>
									 </tr>
									 <tr><td height="23"><b><?php echo TEXT_DURATION;?></b><?php
									 /*
									  if($listing['products_durations'] > 1){
											if($listing['products_durations_type'] == 0){
												$str_day =  TEXT_DURATION_DAYS;
											}else{
												$str_day =  TEXT_DURATION_HOURS;
											}
										}
									  else{
											if($listing['products_durations_type'] == 0){
													$str_day = TEXT_DURATION_DAYS;
												}else{
													$str_day = TEXT_DURATION_HOURS;
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
										echo db_to_html($listing['products_durations']).$str_day;
									 ?></td>
									 <td><b><?php echo TEXT_PRICE;?></b><span class="sp2"><?php 
									 if (tep_get_products_special_price($listing['products_id'])) 
										{
										  echo '<span class="sp8">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="sp2">' . $currencies->display_price(tep_get_products_special_price($listing['products_id']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
										} 
										else 
										{
										  echo  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) ;
										}
									 ?></span></td></tr>
									 <tr><td height="18" colspan="2"><b><?php echo TEXT_HIGHLIGHTS;?></b></td>
									 </tr>
									 <tr><td  colspan="2">
									 <p class="nr_right_table_p">
									<?php //echo str_replace('?,' ',str_replace('?,'&bull;',$listing['products_small_description'])); ?>
									<?php echo db_to_html($listing['products_small_description']); ?>
									 </p>
									  </td>
									 </tr>
									</table>
									</div>
	                            <div class="nr_img_3"><a href="<?php echo  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']); ?>" class="a_img_1"><?php echo tep_template_image_button('button_view_detail_more.gif')?></a></div>
							</div>
						 </div>
			    </div>
			
			<?php
			
			/*new design section end*/
			
			//echo '</td></tr>';			
    }
	
	//echo ' </td> </tr>	</table>';
		
  } else {
  /*
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="productListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                   'text' => TEXT_NO_PRODUCTS);

    new productListingBox($list_box_contents);
	*/
	?>
	<?php
	if(!isset($ajax)){
		$error_not_trour_pack = '很抱歉此目录无度假套餐，请点击“短期旅行”查找更多行程！';
		//echo $ajax;
	}
	?>
	<table border="0" width="98%" cellspacing="0" cellpadding="2" class="automarginclass" align="center" style="float:left">
	  <tr>
		<td class="main">
		<?php //echo TEXT_NO_PRODUCTS;?>
		<?php include(dirname(__FILE__).'/search_null.php');?>
		</td>
	  </tr>
	</table>
	<?php
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
				  <div class="tab1_f">
							   <div class="tab1_2_1"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
							   <div class="tab1_2_2"><?php
									 echo tep_draw_form('frm_slippage_ajax_vackation_package', '' ,"",'id=frm_slippage_ajax_vackation_package');		
									 ?>
							   <?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('osCsid','sort','page','products_durations' , 'departure_city_id' , 'tours_type','page1', 'info', 'x', 'y')),'product_listing_index_vackation_packages.php','frm_slippage_ajax_vackation_package','div_product_vackation_packages'); ?>
							   <?php		   
								  // if($sort !='' && !isset($_GET['sort'])){
										//$_GET['sort'] = $sort;
										echo '<input type="hidden" name="sort1" value="'.$sort.'">';
									//}
									//if($tours_type !='' && !isset($_GET['tours_type'])){
										//$_GET['tours_type'] = $tours_type;
										echo '<input type="hidden" name="tours_type1" value="'.$tours_type.'">';
									//}
									//if($products_durations !='' && !isset($_GET['products_durations'])){
										//$_GET['products_durations'] = $products_durations;
										echo '<input type="hidden" name="products_durations1" value="'.$products_durations.'">';
									//}
									//if($departure_city_id !='' && !isset($_GET['departure_city_id'])){
										//$_GET['departure_city_id'] = $departure_city_id;
										echo '<input type="hidden" name="departure_city_id1" value="'.$departure_city_id.'">';
									//}
									echo '<input type="hidden" name="selfpagename" value="index_vackation_packages">';
									echo '<input type="hidden" name="ajxsub_send_vackation_packages" value="true">';
									echo '</form>';
									?>
							   </div>
							  
			   		 </div>
				 <div class="ladt_tt"><br/>
				
				  <a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a>
				 <br/>
				 </div>
				
<?php
  }
?>
