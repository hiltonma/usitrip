<?php 
//此文件内容已经不再使用，不用修改它了。Howard
?>
			<?php /* video phot section start  ?>
				<div class="product_content2_n_l">				
				<div class="product_1_1">
								
				<div class="show936">
				
				  <!--团图片显示区-->
				  <div id="nav_v" class="show_tb_">
					<table border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td>
						   <!--团图片-->
							   <table border="0" width="100%" cellspacing="0" cellpadding="2">
											 <tr><td colspan="0">
														   <?php
															  //check for extra images
															  $check_ext_img_exist = tep_db_query("select prod_extra_image_id from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$product_info['products_id']."'");
															  if(tep_db_num_rows($check_ext_img_exist)>0){
															  ?>
														   <div id="m_zhanshi_pic">
															 <div id="show"> 
																 <?php
																   if($product_info['products_image_med']!=''){
																   ?>
																 <a rel="lightbox[roadtrip]" href="images/<?php echo $product_info['products_image_med']; ?>" title="<?php echo addslashes(db_to_html($product_info['products_name']))?>"><img src="images/<?php echo $product_info['products_image_med']; ?>" id="showpic" /></a>
																 
																 <?php
																   }
																   else
																   {
																	$extra_images_query = tep_db_query("select product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$product_info['products_id']."' order by image_sort_order limit 1");
																	$row_first_image = tep_db_fetch_array($extra_images_query);
																	$url_product_image_name = 'images/'.$row_first_image['product_image_name'];
																	if(preg_match('/^http:/',$row_first_image['product_image_name'])){
																		$url_product_image_name = $row_first_image['product_image_name'];
																	}
																	?>
																 
																 <a rel="lightbox[roadtrip]" href="<?php echo $url_product_image_name; ?>" title="<?php echo addslashes(db_to_html($product_info['products_name']))?>"><img src="<?php echo $url_product_image_name; ?>" id="showpic" /></a>
																 
																 
																 
																 <?php
																   }
																   ?>
															 </div> 
														  </div>
														<?php
															 }else{
														?>
														  <table border="0" width="100%" cellspacing="0" cellpadding="2">
															 <tr><td colspan="0">
																 <div id="m_zhanshi_pic">
															 		<div id="show">
																 <?php
																			
																	if ($product_info['products_image_med']!='') {
																		$new_image = $product_info['products_image_med'];
																	} else {
																		$new_image = $product_info['products_image'];
																	}
																	
																	if(!tep_not_null($new_image)){
																		$new_image = 'noimage_large.jpg';
																	}
																?>
																  
																<a rel="lightbox" title="<?php echo addslashes(db_to_html($product_info['products_name']))?>" href="images/<?php echo $new_image; ?>"><img src="images/<?php echo $new_image; ?>" id="showpic" /></a>
																
																	</div>
																</div>
																					
															 </td></tr> 
																
															   
											   </table>													
														  <?php
															 }
															 ?>
													 </td></tr>
													 </table>
										
							<!--团图片 end-->
						
						</td>
					  </tr>
					<?php
					if($content!='product_info_vegas_show'){
					?>
					  <tr>
						<td class="product_class_and_max_button">
						<div class="product_class">
						<!--团类型-->
						<?php if($product_info['products_class_id']>1 && $product_info['products_class_id']!=4){ //买二送一的不显示，已经用图标来显示?>
							<b title="<?php echo db_to_html(get_products_class_content($product_info['products_class_id'],$product_info['products_class_content'])); ?>"><?php echo db_to_html(get_products_class_name($product_info['products_class_id'])); ?></b>
						<?php }else{ echo "&nbsp;";}?>
						<!--团类型 end-->
						</div>
						
						<!--看大图按钮-->
						<div class="product_max_button">
						<?php
						if(tep_db_num_rows($check_ext_img_exist)>0){
							
							$extra_images_sql = tep_db_query("select product_image_name from ".TABLE_PRODUCTS_EXTRA_IMAGES." where products_id = '".$product_info['products_id']."' order by image_sort_order ");
							
							if($product_info['products_image_med']!=''){
								//echo tep_template_image_button('image_enlarge_array.gif', TEXT_CLICK_TO_ENLARGE, 'style="cursor: pointer; width:70px; height:12px;" ');
								echo '+ <a href="images/'.$product_info['products_image_med'].'" rel="lightbox[roadtrip]" title="'.addslashes(db_to_html($product_info['products_name'])).'" class="huise_di2">'.db_to_html("欣赏景点组图").'</a>';
								while($extra_images_rows = tep_db_fetch_array($extra_images_sql)){
									$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
									if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
										$url_product_image_name = $extra_images_rows['product_image_name'];
									}
									echo '<a href="'.$url_product_image_name.'" rel="lightbox[roadtrip]" title="'.addslashes(db_to_html($product_info['products_name'])).'"  style="display:none;"></a>';
								}
								
								
								
							}else{
								$tmp_num = 0;
								echo '+ ';
								while($extra_images_rows = tep_db_fetch_array($extra_images_sql)){
									$tmp_num++;
									$tmp_str = '';
									if($tmp_num!=1){
										$tmp_str = ' style="display:none;" ';
									}
									$url_product_image_name = 'images/'.$extra_images_rows['product_image_name'];
									if(preg_match('/^http:/',$extra_images_rows['product_image_name'])){
										$url_product_image_name = $extra_images_rows['product_image_name'];
									}
									echo '<a href="'.$url_product_image_name.'" rel="lightbox[roadtrip]" title="'.addslashes(db_to_html($product_info['products_name'])).'"  '.$tmp_str.'">'.db_to_html("欣赏景点组图").'</a>';
								}	
							}
						}else{
							if($new_image != 'noimage_large.jpg'){
								//echo tep_template_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE);
								echo '<a rel="lightbox" title="'.addslashes(db_to_html($product_info['products_name'])).'" href="images/'.$product_info['products_image_med'].'" class="huise_di2">'.db_to_html("欣赏景点大图").'</a>';
							}
						}
						?>
						
						</div>
						<!--看大图按钮 end-->
						

						</td>
					  </tr>
					<?php
					}
					?>
					</table>
				   
				   	<!--照片,map和评论统计-->
					<div class="data_statistics">
						<div class="data_statistics_question"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&'.tep_get_all_get_params(array('info','mnu','rn')));?>" ><?php echo sprintf(DATA_STATISTICS_QUESTION, get_product_question_num($product_info['products_id']));?></a>
						
						</div>
						<div class="data_statistics_reviews"><a href="javascript:void(0);" onClick="button_write_review_focus()" ><?php echo sprintf(DATA_STATISTICS_REVIEWS, get_product_reviews_num($product_info['products_id']));?></a></div>
						<script type="text/javascript">
						function button_write_review_focus(){
							if(document.getElementById('button_write_review')!=null){
								document.getElementById('button_write_review').focus();
							}
							if(document.getElementById('c_review')!=null){
								shows_product_detail_content('c_review');
							}
							
						}
						</script>
					
					<?php
					if($content!="product_info_vegas_show"){
					?>	
						<div class="data_statistics_share"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&'.tep_get_all_get_params(array('info','mnu','rn')));?>" ><?php echo sprintf(DATA_STATISTICS_SHARE, get_traveler_photos_num($product_info['products_id']));?></a></div>
						
						<?php
						if($product_info['products_map'] != ''){	
						?>
						<?php
							$new_image_map = $product_info['products_map'];
							echo '<div class="data_statistics_product_map"><a href="'.DIR_WS_IMAGES . $new_image_map.'"  rel="lightbox" target="_blank">'.db_to_html("行程地图").'</a></div>';
						}
						?>
					<?php
					}
					?>	
						
					</div> 

					<!--照片,map和评论统计 end-->
					
					<?php 
					//优惠标签显示
					?>
					<div id="Shows_Offer_Summary" style="padding-top:20px; display:block; float:none" >
					<table border="0" cellspacing="0" cellpadding="0" style="margin-top:10px; margin-left:-9px;">
					<tr>
					  

					<?php
					$is_buy2get1 = check_buy_two_get_one($product_info['products_id'],'3');

					if(($product_info['products_class_id']=='4' && ($is_buy2get1=='1' || $is_buy2get1=='2' )) || preg_match('/\bbuy2\-get\-1\b/i',$product_info['tour_type_icon'])){
					?>
							<td>
							<?php echo tep_template_image_button('buy2-get-1.gif',db_to_html('买二送一团').db_to_html(get_products_class_content($product_info['products_class_id'],$product_info['products_class_content'])),'');?>
							</td>
					<?php }?>
					<?php
					$is_buy2get2 = (int)check_buy_two_get_one($product_info['products_id'],'4');
					if(($product_info['products_class_id']=='4' && ($is_buy2get2=='3' || $is_buy2get2=='1')) || preg_match('/\bbuy2\-get\-2\b/i',$product_info['tour_type_icon'])){
					?>
					<td>
					<?php echo tep_template_image_button('buy2-get-2.gif',db_to_html('买二送二团'),'');?>
					</td>
					<?php }
					
					if(double_room_preferences($product_info['products_id']) || preg_match('/\b2\-pepole\-spe\b/i',$product_info['tour_type_icon'])){
						echo '<td>'.tep_template_image_button('2-pepole-spe.gif',db_to_html('双人折扣团'),'').'</td>';
					}
					
					if(check_is_specials($product_info['products_id'],true,true) || preg_match('/\bspecil\-jia\b/i',$product_info['tour_type_icon'])){
						echo '<td>'.tep_template_image_button('specil-jia.gif',db_to_html('特价团'),'').'</td>';
					}
					
					?>
					<?php
					//已经卖完标签 start
					if($product_info['products_stock_status']=='0'){
						echo '<td>'.tep_template_image_button('sale_over.gif',db_to_html('已经卖完'),'').'</td>';
					}
					//已经卖完标签 end
					?>
					</tr>
					</table>
					
					</div>
					<?php
					//优惠标签显示 end
					?>
					
					</div>
					
					<!--团图片显示区 end-->
				  
				  <div class="show_ctt" id="cont_v">
				 	 
					<!--团基本资料-->
					 <table width="100%" border="0" cellspacing="0" cellpadding="0">
								
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td nowrap="nowrap" class="products_content_title" ><b><b><?php echo TEXT_TOUR_CODE;?></b></b>&nbsp;</td>
								<td valign="top"><?php echo $product_info['products_model'];?></td>
								
								<td rowspan="4" valign="top" >
								
								   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="right" valign="middle" class="tufengjia_title" ><?php echo TEXT_HEADING_OUR_PRICE;?> 
									<?php
									if($content!="product_info_vegas_show"){
									?>
										<span>
										<?php if($display_fast==true){?>
											<a href="javascript:void(0)" onClick="shows_product_detail_content('c_book')"><?php echo db_to_html('明细')?></a>
										<?php
										}else{
											echo '<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=prices&'.tep_get_all_get_params(array('info','mnu','rn'))).'">'.db_to_html('明细').'</a>';
										}
										?>
										</span>
									<?php
									}
									?>
									</td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle" class="tufengjia" nowrap="nowrap">
									<span class="tufengjia_price"><?php echo $products_price; ?></span>
									
									<?php
									if($product_info['products_durations'] > 1 && !(int)$product_info['products_durations_type'] ){
									?>
									<span class="tufengjia_qi"><?php echo db_to_html('起');?></span>
									<?php
									}
									?>
									<?php
									////低价保证标签 start
									if(defined('LOW_PRICE_GUARANTEE_PRODUCTS') && tep_not_null(LOW_PRICE_GUARANTEE_PRODUCTS)){
										$tmp_array = explode(',',LOW_PRICE_GUARANTEE_PRODUCTS);
										for($i=0; $i<sizeof($tmp_array); $i++){
											if(trim($tmp_array[$i])==(int)$product_info['products_id']){
												echo '<tr><td align="center"><div class="jb_right_button" style="width:100%"><div class="note-jb-fb1" id="travel_companion_tips_20741" style="text-decoration:none; display:none;"><div class="note-jb-fb_11">'.db_to_html('走四方网将保证您以最低价格购买此产品。下单后，如发现此产品价格高于其他同类网站相同产品价格，将退还差价并给予3倍积分补偿。').'</div><div class="note-jb-fb_21"></div></div><img width="72" height="19" onmouseout="show_travel_companion_tips(0,20741)" onmousemove="show_travel_companion_tips(1,20741)" border="0" src="/image/buttons/'.$language.'/low_price_guarantee.gif"></td></tr>';
												break;
											}
										}
									}
									//低价保证标签 end
									?>

									</td>
                                  </tr>
                                  <tr>
									<td valign="middle" style="padding-left:15px;">
									
							<?php
							//howard added 加入购物车窗口 start
							$add_cart_msn = "add_cart_msn";
							$add_cart_msn_con_id = "add_cart_msn_con";
							$add_cart_msn_h4_contents = db_to_html("该团已成功添加到购物车！");
							$add_cart_msn_contents = '
								<table style="float:left" cellSpacing=0 cellPadding=0 width="100%" border=0>
										<tr><td height="25" align="center" ><p style="font-weight:normal;">'.db_to_html('购物车共 <span class="tell_f_a"  style="text-decoration:none">[Cart_Sum] </span>个团').'&nbsp;&nbsp;&nbsp;&nbsp;'.db_to_html('合计：<SPAN class="tell_f_a" style="text-decoration:none">[Cart_Total]</SPAN>').'</P></td></tr>
										<tr><td height="25" align="center"><a href="' . tep_href_link('shopping_cart.php') . '">' . tep_template_image_button('go-to-basket.gif', db_to_html('查看购物车或结账')) . '</a>&nbsp;<a href="javascript:closePopup(&quot;'.$add_cart_msn.'&quot;)">'.tep_template_image_button('button_continue_shopping.gif', db_to_html('继续购物')).'</a></td>
										</tr>
									</table>
							';
							$PopupObj[] = tep_popup($add_cart_msn, $add_cart_msn_con_id, "460", $add_cart_msn_h4_contents, $add_cart_msn_contents );
							//howard added 加入购物车窗口 end
							?>
									</td>
                                  </tr>
                                   </table></td>
								</tr>
								
							<?php if($content=='product_info_vegas_show'){?>
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td nowrap="nowrap" class="products_content_title"><b><?php echo db_to_html('城市');?></b>&nbsp;</td>
								<td valign="top">
								<?php 
								if($product_info['departure_city_id'] == '')$product_info['departure_city_id'] = 0;
								$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_city_id'] . ") order by c.city ");
								while($city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query)) 	echo  db_to_html($city_class_departure_at['city']).', '.$city_class_departure_at['zone_code'].', '.$city_class_departure_at['countries_iso_code_3'].'<br />';
								?>
								</td>
								</tr>
							<?php }else{?>
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td nowrap="nowrap" class="products_content_title"><b><?php echo HEDING_TEXT_TOUR_DEPARS_AT;?></b>&nbsp;</td>
								<td valign="top">
								<?php 
								if($product_info['departure_city_id'] == '')$product_info['departure_city_id'] = 0;
								$city_class_departure_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_city_id'] . ") order by c.city ");
								while($city_class_departure_at = tep_db_fetch_array($city_class_departure_at_query)) echo  db_to_html($city_class_departure_at['city']).', '.$city_class_departure_at['zone_code'].', '.$city_class_departure_at['countries_iso_code_3'].'<br />';
								?>	</td>
								</tr>								
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td class="products_content_title"><b><?php echo HEDING_TEXT_TOUR_DEPARS_ENDS_AT;?></b>&nbsp;</td>
								<td valign="top">
								<?php
								if($product_info['departure_end_city_id'] == '')	$product_info['departure_end_city_id'] = 0;								
								$city_class_departure_end_at_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3  from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, ".TABLE_COUNTRIES." as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city_id in (" . $product_info['departure_end_city_id'] . ")");
								while($city_class_departure_end_at = tep_db_fetch_array($city_class_departure_end_at_query)) echo  db_to_html($city_class_departure_end_at['city']).', '.$city_class_departure_end_at['zone_code'].', '.$city_class_departure_end_at['countries_iso_code_3'].'<br />';
								?>	
								</td>
								</tr>
							<?php
							}
							?>
								
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td class="products_content_title"><b><?php echo HEDING_TEXT_POINTS_MSN?></b>&nbsp;</td>
								  <td valign="top" nowrap="nowrap">
										<?php
										   // Points/Rewards system V2.1rc2a BOF
											if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
												if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
													$products_price_points = tep_display_points($new_price, tep_get_tax_rate($product_info['products_tax_class_id']));
												} else {
													$products_price_points = tep_display_points($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
												}
												$products_points = tep_calc_products_price_points($products_price_points);
												$products_points = get_n_multiple_points($products_points , $product_info['products_id']);												
												//$products_points_value = tep_calc_price_pvalue($products_points);
												if ((USE_POINTS_FOR_SPECIALS == 'true') || $new_price == false) {
													echo '<span class="smalltext brownfont_home">' . sprintf(TEXT_PRODUCT_POINTS ,number_format($products_points,POINTS_DECIMAL_PLACES)) . '</span>';										
												}
											}
										// Points/Rewards system V2.1rc2a EOF
										?>	
							</td>
                       </tr>
						<?php 
						if($content=='product_info_vegas_show'){
							$hotel_sql = tep_db_query('SELECT products_hotel_id FROM `products_show` WHERE products_id="'.$product_info['products_id'].'" Limit 1');
							$hotel_row = tep_db_fetch_array($hotel_sql);
						?>
						<tr>
							<td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
							<td valign="top" nowrap="nowrap" class="products_content_title" style="width:87px;"><b><?php echo db_to_html('表演场地'); ?></b>&nbsp;</td>
							<td valign="top"><?php echo db_to_html(tep_get_products_name($hotel_row['products_hotel_id']));?></td>
					   </tr>
						<tr>
							<td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>								  
							<td valign="top" nowrap="nowrap" class="products_content_title" style="width:87px;"><b><?php echo db_to_html('表演日期'); ?></b>&nbsp;</td>
							<td valign="top" id="operate_info"><?php echo tep_get_display_operate_info($product_info['products_id']);?></td>
					   	</tr>
						<?php
						}else {
							
						?>	
						<tr>
							<td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
							<td valign="top" nowrap="nowrap" class="products_content_title" ><b><?php echo substr(TEXT_OPERATE,0,8); ?></b>&nbsp;</td>
							<td valign="top">&nbsp;</td>
					   </tr>
					   <tr>
					   		<td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
					   		<td colspan="4"><p class="products_small_description" id="operate_info"><?php echo tep_get_display_operate_info($product_info['products_id']);?></p></td>
					   </tr>
					   <?php
					   }
					   ?>
					   
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td class="products_content_title" nowrap="nowrap"><b><?php if($content=='product_info_vegas_show'){ echo db_to_html('SHOW介绍'); }else { echo substr(TEXT_HIGHLIGHTS,0,8);} ?></b>&nbsp;</td>
								  <td valign="top">
<?php
//查看景点地图
$maps_file = DIR_FS_CATALOG."products_swf_maps/".$product_info['products_id'].".swf";
if(file_exists($maps_file)){
?>
<style>
.ckmap{ padding-left:20px; padding-top:2px; padding-bottom:2px; background:url(image/icons/map.gif) no-repeat; background-position:2px 0px;}
.ckmapLink{ color:#3180F6; text-decoration:none; font-size:12px;}
.ckmapLink:hover{ text-decoration:underline;}
</style>
<span class="ckmap" id="view_attractions_swf_maps"><a href="<?php echo tep_href_link('product_info_maps.php','products_id='.$product_info['products_id'])?>" class="ckmapLink"><?php echo db_to_html("查看地图");?></a></span>
<?php
}else{ echo "&nbsp;";}
?>								
								</td>
						        </tr>
								<tr>
								  <td align="right" valign="top" style="width:10px;" nowrap="nowrap">&nbsp;</td>
								  <td colspan="4" align="left" valign="top" >
								  <p class="products_small_description">
								  <?php
								  $products_small_description = $product_info['products_small_description'];
								  echo stripslashes2(db_to_html($products_small_description));
								  ?>
								  </p>
								  
								  </td>
					   </tr>
					</table>
					<!--团基本资料 end-->

				  </div>

                  <div class="clear"></div>
				  </div>
				
				<?php
				if($content=='product_info_vegas_show'){	//如果是show则这里显示购买模块
					//产品购买模块
					include('product_info_module_right_1_for_vegas_show.php');
				}
				?>
	
	           </div>
			   
			   	
				</div>
			<?php /* video phot section start */ ?>