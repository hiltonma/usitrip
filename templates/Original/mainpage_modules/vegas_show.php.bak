<div style=" width:100%; float:left;margin-top:10px; text-align:center;">
	<img src="image/lv_show_banner.jpg" />
</div>
<div class="cl_table">
	<form action="" method="get" name="show_search" id="show_search">
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>
			<?php
			$hotel_sql = tep_db_query('SELECT ps.products_hotel_id, pd.products_name FROM `products_show` ps, `products_description` pd WHERE pd.products_id = ps.products_hotel_id AND language_id ="'.$languages_id.'" AND ps.products_hotel_id > 0 Group By ps.products_hotel_id ');
			$hotel_array = array();
			$hotel_array[] = array('id'=>'', 'text'=> db_to_html('表演场地'));
			while($hotel_rows = tep_db_fetch_array($hotel_sql)){
				$hotel_array[] = array('id'=>$hotel_rows['products_hotel_id'], 'text'=> db_to_html(preg_replace('/ .+/','',$hotel_rows['products_name'])));
			}
			echo tep_draw_pull_down_menu('show_hotel_id', $hotel_array ,'',' id="show_hotel_id" style="margin-left:5px;" ');
			?>
		</td>
		<td>
			<?php
			$price_array = array();
			$price_array[] = array('id'=>'0', 'text'=> db_to_html('价格'));
			$price_array[] = array('id'=>'1,100','text'=>db_to_html('$100以下'));
			$price_array[] = array('id'=>'100,200','text'=>db_to_html('$100至$200'));
			$price_array[] = array('id'=>'200,400','text'=>db_to_html('$200至$400'));
			$price_array[] = array('id'=>'400,600','text'=>db_to_html('$400至$600'));
			$price_array[] = array('id'=>'600,10000','text'=>db_to_html('$600以上'));
			echo tep_draw_pull_down_menu('show_price', $price_array ,'',' id="show_price" style="margin-left:10px;" ');
			?>
		</td>
		<td>
			<?php
			echo tep_draw_input_field('show_keywords','',' class="input_search2" style="margin-left:10px;" ');
			?>
		</td>
		<td><?php echo tep_image_submit('show_button.gif', db_to_html('提交'),' style="margin-left:10px;" ')?></td>
		<td><input name="search_action" type="hidden" id="search_action" value="true"></td>
	  </tr>
	</table>
	</form>	
</div>
<?php 	
//Vegas Show行程
$search_where = '';
if($_GET['search_action']=="true"){
	if((int)$_GET['show_hotel_id']){
		$search_where .= ' and ps.products_hotel_id="'.(int)$_GET['show_hotel_id'].'" ';
	}
	if(preg_match('/,/',$_GET['show_price'])){
		$price_array = explode(',',$_GET['show_price']);
		$search_where .= ' and p.products_price >="'.(int)$price_array[0].'" and p.products_price <="'.(int)$price_array[1].'" ';
	}
	if(tep_not_null($_GET['show_keywords'])){
		$search_where .= ' and pd.products_name Like "%'.tep_db_prepare_input(html_to_db($_GET['show_keywords'])).'%" ';
	}
}
	
	if(strlen($this_cate_ids)<1){$this_cate_ids='0';}

   $vegas_show_query_sql =  "select ta.operate_currency_code, p.products_image, pd.products_name, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price , ps.products_hotel_id, ps.min_watch_age  from " . TABLE_PRODUCTS_DESCRIPTION . " pd, `products_show` ps, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta  where  p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id in (" . $this_cate_ids . ") and p.products_stock_status ='1' and p.products_info_tpl ='product_info_vegas_show' and ps.products_id=p.products_id ".$search_where." group by p.products_id ";
   //echo $search_where;
   //echo  $vegas_show_query_sql;
   
   $products_split = new splitPageResults($vegas_show_query_sql, 10);
   $vegas_show_query_raw = tep_db_query($products_split->sql_query);
   
  
	if(tep_db_num_rows($vegas_show_query_raw) > 0){		
	?>
	 
	 <div class="tab1_2"><div class="tab1_2_1"><?php echo $products_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div><div class="tab1_2_2"><?php echo TEXT_RESULT_PAGE . ' ' . $products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page'))); ?></div></div>
	   
	<?php  
	$rows=0;
	while ($listing = tep_db_fetch_array($vegas_show_query_raw)) {
	$rows++;
	 //amit modified to make sure price on usd
		if($listing['operate_currency_code'] != 'USD' && $listing['operate_currency_code'] != ''){
		 $listing['products_price'] = tep_get_tour_price_in_usd($listing['products_price'],$listing['operate_currency_code']);
		}
	  //amit modified to make sure price on usd
	
		$operate = tep_get_display_operate_info($listing['products_id']);


$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '".$listing['departure_city_id']."'  order by city");
$cityclass = tep_db_fetch_array($cityquery);
			
	
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
		 //显示Show标签，按买2送2、买2送1、双人Show、普通Show的优先次序处理
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
									 <tr><td height="20" colspan="2"><b><?php echo db_to_html('表演场地：');?></b><?php echo db_to_html(tep_get_products_name($listing['products_hotel_id']));?></td>
									 </tr>  
									 <tr><td height="21" colspan="2"><b><?php echo db_to_html('表演日期：');?></b><?php echo $operate;?></td>
									 </tr>
									 <tr><td height="21" colspan="2">
									 <b><?php echo db_to_html('观看年龄：');?></b>
									<?php
									if($listing['min_watch_age']<1){
										echo db_to_html('老少佳宜');
									}else{
										echo db_to_html($listing['min_watch_age'].'岁以上');
									}
									?>
									 
									 </td>
									 </tr>
									 <tr><td height="23"><b><?php echo db_to_html('表演时长：');?></b><?php
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
									 <tr><td height="18" colspan="2"><b><?php echo db_to_html('SHOW介绍：');?></b></td>
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

//取得当前的最顶级目录的所有Show行程 end	
?>	
