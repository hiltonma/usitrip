<div class="middle_left"> 					
					 
				    <div class="middle_left_1">				
					

					 <div class="form1">
					 <?php echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onsubmit="return adv_search_seo_url(\'advanced_search\', \''.tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'show_dropdown=true', 'NONSSL', false).'\');"') . tep_hide_session_id(); ?>
						  <div class="left_form">
						  				<div class="middle_l_form">
										<div class="text_22"><?php echo TEXT_HEDING_COUNTRY_SEARCH;?></div>
										<div class="form_1">
										<select name="country"  id="country"  style="width:190px;"  onchange="search_tour_ajax(this.value,1);">
										     <option value="" ><?php echo TEXT_DROP_DOWN_SELECT_COUNTRY;?></option>
											 <option value="us-tours" >USA</option>
											 <option value="canada-tours">Canada</option>																						
										</select>
										</div>
								    </div>
									
									<div id="search_tour_category_response">
											
									</div>
								
					 	 </div>
						  <div class="left_form2">
						  
						   <div class="middle_l_form">
									<div class="text_2"><?php echo  tep_draw_checkbox_field('start_date_ignore', '1','checked') ;?><?php echo IGNORE .' '.START_DATE;?></div>
									<div class="form_1">	
									<script type="text/javascript"><!--
						 						var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "advanced_search", "products_date_available","btnDate","",scBTNMODE_CUSTOMBLUE);
												dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";
									//--></script>											
									</div>
									
								</div>
								
							   <div class="middle_l_form">
										<div class="text_22"><?php echo DURATION; ?></div>										
										 <?php
											if($HTTP_GET_VARS['products_durations'] != '')
											$javavar ="document.advanced_search.products_durations.value='".$HTTP_GET_VARS['products_durations']."';"
											
											?><div class="form_1" id='advdurations' style='overflow:hidden;'>
										  <select name="products_durations" id="products_durations" style="width:200px;">
											<option value="" selected="selected"><?php echo TEXT_DURATION_OPTION_1;?></option>											
											<option value="0-1"><?php echo TEXT_DURATION_OPTION_LESS_ONE;?></option>
											<option value="1-1"><?php echo TEXT_DURATION_OPTION_2;?></option>
											<option value="2-2"><?php echo TEXT_DURATION_OPTION_3;?></option>
											<option value="2-3"><?php echo TEXT_DURATION_OPTION_4;?></option>
											<option value="3-3"><?php echo TEXT_DURATION_OPTION_5;?></option>
											<option value="3-4"><?php echo TEXT_DURATION_OPTION_6;?></option>
											<option value="4-4"><?php echo TEXT_DURATION_OPTION_7;?></option>
											<option value="4-"><?php echo TEXT_DURATION_OPTION_8;?></option>
											<option value="5-"><?php echo TEXT_DURATION_OPTION_9;?></option>
										  </select>
										</div>
										  <?php 	if($HTTP_GET_VARS['products_durations'] != '') { ?>
										  <script type="text/javascript">
											<?php echo $javavar; ?>
											</script>
										<?php } ?>
									    
							   </div>  
									
						  </div>
						  <div class="left_form3">
							   <div class="form_2">
							    <?php echo tep_draw_input_field('keywords',$HTTP_GET_VARS['keywords'],'style="width:200px;"'); ?>
							 	<input type="submit" class="sub1" style="cursor: pointer; cursor: hand;" name="submit" value=""/>				
								<input type="hidden" name="search" value="Search" />	 
						        </div>
						  </div>
						  
						  </form>
							
							<div class="depature_left_form3">
							   <div class="form_2">
								<table width="100%" cellspacing="2" cellpadding="0">
								  <tr>
									<td height="20" class="text_black"><?php echo TEXT_TITLE_DEPARTURE_CITY; ?></td>
								  </tr>
								  <tr>
									<td>
										<?php
											$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and c.departure_city_status = '1' and s.zone_country_id = co.countries_id and c.city !='' order by c.city");
											
											while ($city_class = tep_db_fetch_array($city_class_query)) 
											{
												$city_class_array[] = array('id' => tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'departure_city_id='.$city_class['city_id'].'&show_dropdown=true').'/',
												'text' => db_to_html($city_class['city']).', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
											}
											echo tep_draw_pull_down_menu('departure_city_id', $city_class_array, $_GET['departure_city_id'], 'style="width:200px; " onchange="Depature_JumpRedictUrl(this);"'); 											
										?>									</td>
								  </tr>										 
								</table>
							 </div>
						  </div>
						  
						  
					 </div> 									 
				  </div>
				  
				  <div class="middle_left_2">
					<div class="middle_l_2_1"><div class="biaoti1"><h5><?php echo TEXT_HEADING_SPECIAL_OFFERS;?></h5></div></div>					
					 <?php
						 
						  $new_arr_producs_array = explode(',',TOURS_HOMEPAGE_SPECIAL_OFFERS);
						  
						  foreach($new_arr_producs_array as $key => $val){
						  	
							 $product_query_select_new = "select distinct p.products_id,pd.products_name, p.products_image, p.products_price, p.products_model from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd where  p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and p.products_id = ".(int)$val."";
					
							  
							  $new_products_query_arrive = tep_db_query($product_query_select_new);
							  
							  while ($new_products_arrive = tep_db_fetch_array($new_products_query_arrive)) {
							 
								
							  ?>
							  <div class="middle_l_2_2">
								<div class="middle_l_2_2_img"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']);?>"><?php echo tep_image(DIR_WS_IMAGES . $new_products_arrive['products_image'], db_to_html($new_products_arrive['products_name']), 60, 40) ;?></a></div>
								<div class="font_5"><span class="sp2"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']);?>" class="fff_a_xin"><?php echo db_to_html(tep_db_prepare_input($new_products_arrive['products_name']));?></a></span> <br /> <span class="sp1_model">[<?php echo db_to_html('$'.tep_db_prepare_input($new_products_arrive['products_price']));?>]</span></div>
							  </div>
							  <?php
							 }
						  }
						  ?>						  
				  </div>
				  <div class="middle_left_3">
				      <div class="middle_l_2_1"><div class="biaoti1"><h5><?php echo BOX_INFORMATION_CONTACT;?></h5></div></div>
					  <div class="middle_l_3_1">
					    <div class="Cs_img"><img src="image/phone_b.gif" alt="" /></div><div class="font_1" style="margin-left:0px;">0086-4006-333-926 <?php echo db_to_html('[中]<br /><span style="color:#99b7d7">（周一-周五9:00am-11:30pm 北京时间）</span>') ?></div>
					</div>
					  <div class="middle_l_3_1">
					    <div class="Cs_img"><img src="image/phone_b.gif" alt="" /></div><div class="font_1"style="margin-left:0px;">86-28-80812050 <?php echo db_to_html('[中]<br /><span style="color:#99b7d7">（周六-周日 10:00am-5:00pm 北京时间）</span>') ?></div>
					  </div>
				    <div class="middle_l_3_1">
					    <div class="Cs_img"><img src="image/phone_b.gif" alt="" /></div><div class="font_1" style="margin-left:0px;">1-626-898-7800 <?php echo db_to_html('[美]<br /><span style="color:#99b7d7">（周一-周五9:00am-5:00pm 太平洋时间）</span>') ?></div>
					  </div>
					  <div class="middle_l_3_1">
					    <div class="Cs_img"><a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>" class="ff_a"><img src="image/mail_b.gif" alt="" /></a></div><div class="font_2"><a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>" class="ff_a">service@usitrip.com</a></div>
					    
						<?php if(CHARSET=='gb2312'){?>
						<div class="Cs_img" style="margin-right:0px;"><a href="http://sighttp.qq.com/cgi-bin/check?sigkey=83e7366cdf91d7f7b50757d39da2bdd25b3df054c74051d1cee69f7933e9b2e1"; target=_blank; onclick="var tempSrc='http://sighttp.qq.com/wpa.js?rantime='+Math.random()+'&sigkey=83e7366cdf91d7f7b50757d39da2bdd25b3df054c74051d1cee69f7933e9b2e1';var oldscript=document.getElementById('testJs');var newscript=document.createElement('script');newscript.setAttribute('type','text/javascript'); newscript.setAttribute('id', 'testJs');newscript.setAttribute('src',tempSrc);if(oldscript == null){document.body.appendChild(newscript);}else{oldscript.parentNode.replaceChild(newscript, oldscript);}return false;"><img border="0" SRC='http://wpa.qq.com/pa?p=1:727017621:1' alt="<?php echo db_to_html('点击这里给我发消息');?>" title="<?php echo db_to_html('点击这里给我发消息');?>"></a></div>
                        <?php }?>
						
					  </div>
				  </div>
				  <div class="middle_left_4">				
					 <div class="r_pic_3_img">
					  <?php 
					  if($language == 'tchinese' || $language == 'schinese'){
					  $tel_a_frd_home_img_name = "image/tell_friend_banner_".$language.".gif";
					  }else{
					  $tel_a_frd_home_img_name = "image/referafriend_home_banner.gif";
					  } 
					  ?>
				      <a href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND,'','SSL');?>" >
					  <img   src="<?php echo $tel_a_frd_home_img_name; ?>" alt="" />
					  </a>
				  	</div> 
				  </div>
		  </div>
		  <div class="middle_right">
		      <div class="r_img">
								  <div id="top_slider">
								    <?php 
									   $banner_query_select_top_b = "select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '500x220 Home Page' and   (banner_language_code_name = '' || banner_language_code_name='all' || banner_language_code_name ='".$language."') order by banner_sort_order"; // limit 1
										$banner_query_top_row=tep_db_query($banner_query_select_top_b);
										$banner_count = tep_db_num_rows($banner_query_top_row);
										if($banner_count > 0) {
											if($banner_count==1){
												if($banner_query_top_result = tep_db_fetch_array($banner_query_top_row)){
												 if ($banner_top = tep_banner_exists('static', $banner_query_top_result['banners_id'])) {
													echo tep_display_banner('static', $banner_top);
												  }
												}
											}else{
												?>
												<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>banner.js"></script>
												<div id="MainPromotionBanner">
												<div id="SlidePlayer">
													<ul class="Slides">
															
												<?php
												while($banner_query_top_result = tep_db_fetch_array($banner_query_top_row)){
													 if ($banner_top = tep_banner_exists('static', $banner_query_top_result['banners_id'])) {
														//echo tep_display_banner('static', $banner_top);
														if (tep_not_null($banner_query_top_result['banners_html_text'])) {
														  $banner_string = $banner_query_top_result['banners_html_text'];
														} else {
														  $banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&amp;goto=' . $banner_query_top_result['banners_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $banner_query_top_result['banners_image'], db_to_html($banner_query_top_result['banners_title'])) . '</a>';
														}
													
														tep_update_banner_display_count($banner_query_top_result['banners_id']);
														?>
															
															<li><?php echo $banner_string; ?></li>
														<?php
													 }
												}
												?>
													</ul>
												</div>
												<script type="text/javascript">
												  TB.widget.SimpleSlide.decoration('SlidePlayer', {eventType:'mouse', effect:'scroll'});
												</script>
												</div>
												<?php
											}
											
										}else{
										?>		
									<!--bbs s1 -->
									<div id="bbs_s1" class="solidbox">
									
									<div class="bbs_s1_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=136');?>"><?php echo db_to_html('年度买二送一特别优惠团，欲购从速') ?>   </a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>	
																	  
									<!--bbs s2 -->
									<div id="bbs_s2" class="solidbox" style="display: none">
									
									<div class="bbs_s2_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=134');?>"><?php echo db_to_html('四日旧金山, 优胜美地经典游') ?>   [USLA2-134]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>
									
									<!--bbs s3 -->
									<div id="bbs_s3" class="solidbox" style="display: none">
									<div class="bbs_s3_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=130');?>"><?php echo db_to_html('七日拉斯维加斯，旧金山，洛杉矶欢乐游') ?>    [USLA2-130]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>
									
									<!--bbs s4 -->
									<div id="bbs_s4" class="solidbox" style="display: none">
									<div class="bbs_s4_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=123');?>"><?php echo db_to_html("四日洛杉矶城市观光及主题乐园旅行套餐\'"); ?>     [USLA2-123]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>
									
									<!--bbs s5 -->
									<div id="bbs_s5" class="solidbox" style="display: none">
									<div class="bbs_s5_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=114');?>"><?php echo db_to_html('五日大峡谷, 迪斯尼促销经典游'); ?>  [USLA2-114]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>
									
									<?php
									/*
									<!--bbs s5 -->
									<div id="bbs_s5" class="solidbox" style="display: none">
									<div class="bbs_s5_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=154');?>"><?php echo db_to_html('六日拉斯维加斯, 大峡谷, 墨西哥浪漫游'); ?>   [USLA2-154]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>

											  </div>
										  </div>
									
									</div>
									
									<!--bbs s6 -->
									<div id="bbs_s6" class="solidbox" style="display: none">
									<div class="bbs_s6_bg">

											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=143');?>"><?php echo db_to_html("五日美国东岸四大名城豪华旅游套餐\'"); ?>   [USNY3-143]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>
											  </div>
										  </div>
									
									</div>
									
									<!--bbs s7 -->
									<div id="bbs_s7" class="solidbox" style="display: none">
									<div class="bbs_s7_bg">

											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=147');?>"><?php echo db_to_html("六日美国东海岸超值旅游套餐\'"); ?>   [USNY3-147]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>
											  </div>
										  </div>
									
									</div>
									<!--bbs s8 -->
									<div id="bbs_s8" class="solidbox" style="display: none">
									<div class="bbs_s8_bg">

											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=153');?>"><?php echo db_to_html("八日加拿大名胜美国东岸名城深度豪华超值旅游套餐\'"); ?>   [USNY3-153]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>
											  </div>
										  </div>
									
									</div>
									<!--bbs s9 -->
									<div id="bbs_s9" class="solidbox" style="display: none">
									<div class="bbs_s9_bg">
											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=132');?>"><?php echo db_to_html('十一日畅游墨西哥, 南北加州, 迪斯尼等两大主题公园'); ?>    [USLA2-132]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a>			  </div>
											  </div>
										  </div>
									
									</div>
									<!--bbs s10 -->
									<div id="bbs_s10" class="solidbox" style="display: none">
									<div class="bbs_s10_bg">

											  <div class="ie"><div class="bbs_jiantou"><img src="image/bbs_jiantou.gif" alt="" /></div><div class="bbs_bg_t"><a style="color:#FFFFFF; text-decoration:none;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=116');?>"><?php echo db_to_html('七日拉斯维加斯, 大峡谷, 加州三大主题公园经济精华游'); ?>    [USLA2-116]</a></div>
												  <div class="ba"><a href="javascript:next_img();"><img src="image/bbs_bg_next.gif" border="0" alt="" /></a></div>

											  </div>
											
										  </div>
									
									</div>
									
									*/ ?>
									
									<script type="text/javascript">
									setTimeout('change_img()',25000);
									</script>
									
									   <?php } //end of check banner available?>
																		
									</div>
									
									
									
			  
			  </div>
			  
			  <div class="r_TPDeals">
			    <div class="r_TPDeals_1">
			       <div class="biaoti2"><h5><?php echo TEXT_TOP_TOUR_PACKAGE_DEALS; ?></h5></div>
				</div>
				<div style="line-height:2px;">&nbsp;</div>	
				<div class="tab" id="tab">
				<ul>
				<?php
				$feature_sectiona_sql = "select fts.departure_city_id, fts.categories_id, rd.regions_name, r.regions_id, r.regions_image  from ".TABLE_FEATURE_TOUR_SECTION."  as fts, " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd  where fts.departure_city_id = rd.regions_id and r.regions_id = rd.regions_id  and    fts.tour_section = 'A' order by fts.feature_tour_section_id limit 4";
				$feature_sectiona_query = tep_db_query($feature_sectiona_sql);
				$feature_sectiona_details = $feature_sectiona_query;
				$topseccnt = 0;
				while($feature_result = tep_db_fetch_array($feature_sectiona_query)) {
				if($topseccnt == 0){
				echo '<li class="s"><a href="#" id="h_tops'.$feature_result['departure_city_id'].'">'.db_to_html(tep_db_prepare_input($feature_result['regions_name'])).'</a></li>';
				}else{
				echo '<li><a href="#" id="h_tops'.$feature_result['departure_city_id'].'">'.db_to_html(tep_db_prepare_input($feature_result['regions_name'])).'</a></li>';
				}		
				?>		
				<?php 
				$topseccnt++;
				} 	
				?>		
				</ul>
			</div>

<div class="content2">
	<?php
	 $duration_query = tep_db_query("select * from ".TABLE_FEATURE_TOUR_SECTION_DURATION." where tour_section = 'A'");
	$duration_result = tep_db_fetch_array($duration_query);
	$fromduration = $duration_result['duration_from'];
	$toduration = $duration_result['duration_to'];
	
	function tep_total_category_count($category_id)
	{
		$category_id_query = tep_db_query("select * from ".TABLE_CATEGORIES." where parent_id = ".$category_id." order by categories_id ");
		while($category_id_query_result = tep_db_fetch_array($category_id_query))
		{
			if($category_id_query_result['categories_id'] != '')
			{
				$category_id_store .= ','.$category_id_query_result['categories_id'];
				$category_id_store .= tep_total_category_count($category_id_query_result['categories_id']); 
			}
		}
		return $category_id_store;
	}

				
				
				
	$feature_sectiona_details = tep_db_query($feature_sectiona_sql);
	$product_not_repeate = 0;
	while($feature_result_details = tep_db_fetch_array($feature_sectiona_details)) {
	?>
	<div id="c_tops<?php echo $feature_result_details['departure_city_id'];?>">
	     	 <div class="r_TPDeals_2_2">
				        <div class="middle_l_2_2_img">						
						<?php		
						/*				
						if($feature_result_details['regions_id'] == 1){
							//west
							echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=51').'"><img src="images/'.$feature_result_details['regions_image'].'" width="70" height="70" alt="" /></a>';
						}else if($feature_result_details['regions_id'] == 2){
							//east					
							echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=71').'"><img src="images/'.$feature_result_details['regions_image'].'" width="70" height="70" alt="" /></a>';
						}else if($feature_result_details['regions_id'] == 6){
							//hawaii
							echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=77').'"><img src="images/'.$feature_result_details['regions_image'].'" width="70" height="70" alt="" /></a>';
						}else if($feature_result_details['regions_id'] == 7){
							//florida
							echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=104').'"> <img src="images/'.$feature_result_details['regions_image'].'" width="70" height="70" alt="" /></a>';
						}else{	
						*/
							echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$feature_result_details['categories_id']).'"><img src="images/'.$feature_result_details['regions_image'].'" width="70" height="70" alt="" /></a>';
						//}
						?>	
						</div>
						<div class="r_TPDeals_2_l">
						<ul class="list1">
						<?php
								if($feature_result_details['categories_id'] != '')
								$total_category_id = $feature_result_details['categories_id'].tep_total_category_count($feature_result_details['categories_id']);
								else
								$total_category_id = 0;
								$product_query = tep_db_query("select distinct p.products_id,pd.products_name from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as cat where cat.categories_id in (".$total_category_id.") and p.products_id = cat.products_id and p.products_id = pd.products_id and p.featured_products = 1 and p.products_durations <= ".$toduration." and p.products_durations >= ".$fromduration." and p.products_status=1 order by rand() LIMIT 5 ");
								while($product_result = tep_db_fetch_array($product_query))
								{
								$product_not_repeate .= "," . $product_result['products_id'];
								echo '<li>-  <a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_result['products_id']).'" class="fff_a_xin">'.db_to_html($product_result['products_name']).'</a></li>';
								} 
						?>
						</ul>
						</div>
						<div class="r_TPDeals_2_j" >
								    <div class="r_jia_img"><img src="image/jia.gif" alt="" /></div>
									<div class="font_2">
									<?php
									/*
									if($feature_result_details['regions_id'] == 1){
										//west
										echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=51').'" class="ff_a"> '.db_to_html('更多'.$feature_result_details['regions_name']).' </a>';
									}else if($feature_result_details['regions_id'] == 2){
										//east					
										echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=71').'" class="ff_a"> '.db_to_html('更多'.$feature_result_details['regions_name']).' </a>';
									}else if($feature_result_details['regions_id'] == 6){
										//hawaii
										echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=77').'" class="ff_a"> '.db_to_html('更多'.$feature_result_details['regions_name']).' </a>';
									}else if($feature_result_details['regions_id'] == 7){
										//florida
										echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath=104').'" class="ff_a"> '.db_to_html('更多'.$feature_result_details['regions_name']).' </a>';
									}else{
									*/	
										echo	'<a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$feature_result_details['categories_id']).'" class="ff_a"> '.db_to_html('更多'.$feature_result_details['regions_name']).' </a>';
									//}
									?>								
									</div>
					    </div>
		     		 </div> 
	</div>   
	<?php
	}
	?>	
						 <div class="r_TPDeals_2_3">
				         <div class="biaoti4"><h5><?php echo TEXT_HEADING_BY_DEPARTURE_CITY;?></h5></div>
						 <div class="r_TPDeals_2_BDC">
						 <table width="460" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:12px; height:40px;">
						   <tr>
						   <td>
						   
						   <a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=105');?>" class="ff_a"><?php echo MENU_HONOLUU_TOURS;?></a>&nbsp;|
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=100');?>" class="ff_a"><?php echo MENU_LAS_VEGAS_TOURS;?></a>&nbsp;| 
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=97');?>" class="ff_a"><?php echo MENU_LOSANGELES_TOURS;?></a>&nbsp;| 
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=70');?>" class="ff_a"><?php echo MENU_NEW_YORK_TOURS;?></a>&nbsp;| <br />
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=126');?>" class="ff_a"><?php echo MENU_ORLANDO_TOURS;?></a>&nbsp;|									
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=128');?>" class="ff_a"><?php echo MENU_PHILADEPHIA_TOURS;?></a>&nbsp;| 										
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=98');?>" class="ff_a"><?php echo MENU_SALT_LAKE_CITY_TOURS;?></a>&nbsp;| 
							<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=125');?>" class="ff_a"><?php echo MENU_SAN_FRANCISCO_TOURS;?></a>
			   
						  	</td>						 
						   </tr>
						   </table>
				     </div>
				   </div>	
	   </div>

				<div class="r_pic_3">
				   <div class="r_pic_3_img">
				   <?php 
				   $banner_query_select = "select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '490x73 Home Page' order by banners_id desc limit 1";
					$banner_query_row=tep_db_query($banner_query_select);
					if(tep_db_num_rows($banner_query_row) > 0) {
						if($banner_query_result = tep_db_fetch_array($banner_query_row)){
									 if ($banner = tep_banner_exists('static', $banner_query_result['banners_id'])) {
										echo tep_display_banner('static', $banner); 
									  }
						}
					}else{
					?>
				   <?php 	if(strtolower(CHARSET)=='gb2312'){ //bannar?>
				   <a target="_blank" href="los-anges-las-vegas-shuttle-tours/"><img src="images/sc_la_lv_grand_canyon.jpg" alt="<?php echo db_to_html('旅游特价团，特价旅游团')?>" /></a>
				   <?php 	}else{?>
				   <a target="_blank" href="los-anges-las-vegas-shuttle-tours/"><img src="images/tc_la_lv_grand_canyon.jpg" alt="<?php echo db_to_html('旅游特价团，特价旅游团')?>" /></a>
				   <?php 	}?>
				   <?php } ?>
				   </div>
				</div> 
				<div class="r_RW_4">
				    <div class="r_RW_4_R">
								 <div class="biaoti3"><h5><?php //echo BOX_HEADING_REVIEWS;?><?php echo db_to_html('走四方网首创六人包团新概念');?></h5></div>
								 <div class="r_RW_4_R_1">
								     <p>
									 <?php //echo TEXT_NOTES_REVIEW_HOME_PAGE ;?>
									 <?php echo db_to_html('包团自由行套餐，超低价格，豪华享受！') ;?>
									 <br />
									 <?php echo db_to_html('美国西海岸最低价格$85/天') ;?>
									 <br />
									 <?php echo db_to_html('美国东海岸最低价格$100/天') ;?>
									 <br />
									 <?php echo db_to_html('(均含车/住宿/司导)') ;?>
									 <br />
									 
									 </p> 
							     </div>
								 <div class="r_RW_4_R_2">
								    <div class="r_jia_img"><img src="image/jia.gif" alt="" /></div>
									<div class="font_2"><a href="<?php echo tep_href_link('a-package-tour-of-pax6.php');//echo tep_href_link(FILENAME_REVIEWS); ?>" class="ff_a"><?php echo db_to_html('更多详情');//echo TEXT_HEADING_SEE_MORE;?></a></div>
								 </div>
					</div>
					
					<div class="r_RW_4_W">
					    <div class="biaoti3"><h5><?php echo db_to_html('旅美常识')?></h5></div>
						<div class="r_RW_4_W_list" >
						   <ul class="list1">
								<li style="margin:0px 0px 4px 0px;">- <a href="<?php echo tep_href_link('tours-faq.php') ?>" class="ff_a"><?php echo db_to_html('预订及支付问题')?></a><span style="color:#99B7D7"><?php echo db_to_html('(付款，电子参团凭证等问题)')?></span></li>
								<li style="margin:4px 0px 4px 0px;">- <a href="<?php echo tep_href_link('tours-faq2.php') ?>" class="ff_a"><?php echo db_to_html('常见问题')?></a><span style="color:#99B7D7"> <?php echo db_to_html('(签证，接机，海关等问题)')?></span></li>
								<li style="margin:4px 0px 3px 0px;">- <a href="<?php echo tep_href_link('tours-faq3.php') ?>" class="ff_a"><?php echo db_to_html('旅美须知')?></a><span style="color:#99B7D7"><?php echo db_to_html(' (时差，打电话，气候等问题)')?></span></li>
						   </ul>
                           <div class="r_RW_4_R_2">
								    <div class="r_jia_img"><img src="image/jia.gif" alt=""></div>
									<div class="font_2"><a href="<?php echo tep_href_link('tours-faq.php')?>" class="ff_a"><?php echo db_to_html('全部问题')?></a></div>
								 </div>
						</div>
					</div>

					
					<!--<div class="r_RW_4_W">
					    <div class="biaoti3"><h5><?php echo TEXT_TOP_HEADING_BOOK;?></h5></div>
						<div class="r_RW_4_W_list">
						   <ul class="list1">
								<li>- <a href="<?php echo tep_href_link(FILENAME_ABOUT_US,'#book'); ?>" class="ff_a"><?php echo TAB_SPECIALLY_DESIGN_TOURS; ?></a></li>
								<li>- <a href="<?php echo tep_href_link(FILENAME_ABOUT_US,'#book'); ?>" class="ff_a"><?php echo TAB_LOW_PRICE_GUANRANTEED; ?></a></li>
								<li>- <a href="<?php echo tep_href_link(FILENAME_ABOUT_US,'#book'); ?>" class="ff_a"><?php echo TAB_EXPERIENCED_DRIVER; ?></a></li>
								<li>- <a href="<?php echo tep_href_link(FILENAME_ABOUT_US,'#book'); ?>" class="ff_a"><?php echo TAB_PROFESSIONAL_TOUR_DUIDE; ?></a></li>
								<li>- <a href="<?php echo tep_href_link(FILENAME_ABOUT_US,'#book'); ?>" class="ff_a"><?php echo TAB_EXCELLETN_CUSTOMER_SERVICES; ?></a></li>
						   </ul>
						</div>
					</div>-->
				</div>
			  </div>
		  </div>
