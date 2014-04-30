<?php 	
//取得当前的最顶级目录的所有特价行程
	$top_cat = (int)$cPathOnly;
	//echo $top_cat;
	$cat_array = array(0 => $top_cat);
	tep_get_subcategories($cat_array, $top_cat);
	$cat_and_subcate_ids = implode(',',$cat_array);
	//echo $cat_and_subcate_ids;
	$where_exp = " AND ptc.categories_id in (" . $cat_and_subcate_ids . ") ";
	if($top_cat=='134'){	//如果是全景游则显示全部的特价
		$where_exp = ' ';
	}
	
	//champion top products start
	if(strlen($cat_and_subcate_ids)<1){$cat_and_subcate_ids='0';}
   	/*
	$champion_top_products_query_sql = "select ta.operate_currency_code, p.products_id, pd.products_name, p.products_durations, p.products_durations_type,p.products_price, p.products_tax_class_id, p.products_image, p.products_model,p.departure_city_id, p.products_video,  s.specials_last_modified  from ".TABLE_SPECIALS." s, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptc, " . TABLE_TRAVEL_AGENCY . " ta  where p.products_id = s.products_id AND s.status =1 AND p.agency_id = ta.agency_id and pd.products_id = p.products_id and ptc.products_id = p.products_id and pd.language_id = '" . $languages_id. "' and products_status = '1' ".$where_exp." group by pd.products_id order by s.specials_last_modified DESC, pd.products_name limit 1000";
	$champion_top_products_query_raw = tep_db_query($champion_top_products_query_sql);
   	*/
	//先查询出真正特价的pid 再加入买二送一和其它优惠的产品 start
   $all_special_ids = array();

	$authentic_special_sql = "select p.products_id from ".TABLE_SPECIALS." s, " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptc where p.products_id = s.products_id AND s.status =1 and ptc.products_id = p.products_id and p.products_status = '1'  and p.products_stock_status ='1' and (s.expires_date='0000-00-00 00:00:00' || s.expires_date IS NULL || s.expires_date ='' || s.expires_date > '".$Today_date." 23:59:59' ) ".$where_exp." group by p.products_id ";

   $authentic_special_query_raw = tep_db_query($authentic_special_sql);
   while ($authentic_special = tep_db_fetch_array($authentic_special_query_raw)) {
	if($authentic_special['products_id']){
		$all_special_ids[]= $authentic_special['products_id'];
	}
   }

   //echo $authentic_special_p_ids;
   //查询双人特价的团
   $double_special_sql = tep_db_query('SELECT pdrp.products_id FROM `products_double_room_preferences` pdrp,  ' . TABLE_PRODUCTS . ' p,' . TABLE_PRODUCTS_TO_CATEGORIES . ' ptc WHERE ptc.products_id = pdrp.products_id AND pdrp.products_id = p.products_id and p.products_status = "1"  and p.products_stock_status ="1" '.$where_exp.' AND pdrp.status="1" AND pdrp.people_number="2" AND (pdrp.products_departure_date_begin <= "'.$Today_date.' 00:00:00" || pdrp.products_departure_date_begin="0000-00-00 00:00:00" || pdrp.products_departure_date_begin="") AND (pdrp.products_departure_date_end >="'.$Today_date.' 23:59:59" || pdrp.products_departure_date_end="0000-00-00 00:00:00" || pdrp.products_departure_date_end="" ) Group By pdrp.products_id ');
   while($double_special = tep_db_fetch_array($double_special_sql)){
   		$all_special_ids[] = $double_special['products_id'];
   }

   //查询买二送一买二送二的团id
   $buy2get1_sql = tep_db_query('SELECT b.products_id FROM `products_buy_two_get_one` b, ' . TABLE_PRODUCTS . ' p,' . TABLE_PRODUCTS_TO_CATEGORIES . ' ptc  WHERE ptc.products_id = b.products_id AND b.products_id = p.products_id and p.products_status = "1"  and p.products_stock_status ="1" '.$where_exp.' and b.status="1" AND (b.products_departure_date_begin <= "'.$Today_date.' 00:00:00" || b.products_departure_date_begin="0000-00-00 00:00:00" || b.products_departure_date_begin="") AND (b.products_departure_date_end >="'.$Today_date.' 23:59:59" || b.products_departure_date_end="0000-00-00 00:00:00" || b.products_departure_date_end="" ) Group By b.products_id');
   while($buy2get1 = tep_db_fetch_array($buy2get1_sql)){
   		$all_special_ids[] = $buy2get1['products_id'];
   }
   
   $all_special_ids[] = "0";
   $special_unique_ids = array_unique($all_special_ids);
   
   $where_special = ' AND p.products_id in('.implode(',',$special_unique_ids).') ';

   $champion_top_products_query_sql =  "select ta.operate_currency_code, p.products_image, pd.products_name, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta  where  p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id in (" . $cat_and_subcate_ids . ") and p.products_stock_status ='1' ".$where_special." group by p.products_id ";
   
   $products_split = new splitPageResults($champion_top_products_query_sql, 10);
   $champion_top_products_query_raw = tep_db_query($products_split->sql_query);
   //$champion_top_products_query_raw = tep_db_query($champion_top_products_query_sql);
   //先查询出真正特价的pid 再加入买二送一和其它优惠的产品 end
   
  
	if(tep_db_num_rows($champion_top_products_query_raw) > 0){		
	?>
	 
	 <div class="tab1_2"><div class="tab1_2_1"><?php echo $products_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div><div class="tab1_2_2"><?php echo TEXT_RESULT_PAGE . ' ' . $products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page'))); ?></div></div>
	   
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


$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '".$listing['departure_city_id']."'  order by city");
$cityclass = tep_db_fetch_array($cityquery);
			
		if($listing['departure_city_id'] == ''){
		  $listing['departure_city_id'] = 0;
		}
		$display_str_departure_city = '';
		$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in (".$listing['departure_city_id'].")  order by city");
		while($cityclass = tep_db_fetch_array($cityquery)){
		$display_str_departure_city .= " " .$cityclass['city'] . ", ";
		}
		$display_str_departure_city = substr($display_str_departure_city, 0, -2);
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

		 <?php /*
		 <?php if($rows%2==0){$class_neirong='neirong_1';}else{$class_neirong='neirong';}?>
		 
		 <div class="<?php echo $class_neirong?>">
		 */?>
		 
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
	} //end of while					
	?>
	
	<div class="tab1_f"><div class="tab1_2_1"><?php echo $products_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div><div class="tab1_2_2"><?php echo TEXT_RESULT_PAGE . ' ' . $products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page'))); ?></div></div>

	 <div class="ladt_tt"><a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a></div>
	<?php
	
	}
	//campions top productst end

//取得当前的最顶级目录的所有特价行程 end	
?>	
