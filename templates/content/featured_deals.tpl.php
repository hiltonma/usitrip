
<div class="gb_banner">
	<a href="#" onClick="return false;"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'languages/'.$language.'/' . 'featured_deal_banner.jpg', NAVBAR_TITLE, '', '', 'class="fleft"');?></a>
</div>
<div class="prod_dtl_left_mn">
	<div class="deal_page_lp_top"></div>
    <?php if(isset($_GET['products_id']) && $_GET['products_id']>0){ ?>
	<div class="deal_page_lp_mid">
		<?php
		$qry_featured_specials = "select ta.operate_currency_code, p.products_id, p.products_ordered, pd.products_name, pd.products_small_description, p.products_durations, p.products_durations_type, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, p.departure_city_id, p.products_video, p.agency_id, pd.products_small_description, p.tour_type_icon, s.featured_deals_new_products_price, s.expires_date, s.departure_restriction_date, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc, " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_FEATURED_DEALS . " s  where p.agency_id = ta.agency_id and ptoc.products_id = p.products_id and p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and s.products_id = '".(int)$_GET['products_id']."' and s.active_date <= '".date("Y-m-d")."' group by s.featured_deals_date_added DESC";
		$res_featured_specials=tep_db_query($qry_featured_specials);
		if(tep_db_num_rows($res_featured_specials) > 0){
			$i=0;
			while($row_featured_specials = tep_db_fetch_array($res_featured_specials)){
				$i++;
				//amit modified to make sure price on usd
				if($row_featured_specials['operate_currency_code'] != 'USD' && $row_featured_specials['operate_currency_code'] != ''){
					$row_featured_specials['products_price'] = tep_get_tour_price_in_usd($row_featured_specials['products_price'], $row_featured_specials['operate_currency_code']);
				}
				$featured_deal_price_array = tep_get_featured_deal_price($row_featured_specials['products_id']);
				if(sizeof($featured_deal_price_array)>1){
					$featured_deal_price_for_this_tour = $featured_deal_price_array[1]['text'];
				}else{
					$featured_deal_price_for_this_tour = $featured_deal_price_array[0]['text'];
				}
		?>
		<div id="featured_deal_info_div">
			<div class="gd_mn">
				<div class="gd_left">
                	<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_featured_specials['products_id']);?>"><?php echo tep_image(DIR_WS_IMAGES.$row_featured_specials['products_image'], '', '160', '', 'class="fleft"');?></a>
					<?php /*?><img src="image/temp_graph_img.jpg" alt="" title="" class="fleft" /><?php */
					
					$featured_total_guest_booked = tep_get_featured_total_guests_booked_this_deal($row_featured_specials['products_id']);
					$expected_price_people_array = tep_get_featured_expected_people_and_price($row_featured_specials['products_id']);
					$expected_people = $expected_price_people_array[0];
					$expected_price = $expected_price_people_array[1];
					
					$level_finished = 0;
					$featured_total_guest = $featured_total_guest_booked;
					//echo $featured_total_guest_booked = $featured_total_guest_booked + 11;
					?>                    
                    <table width="185" cellspacing="5" class="fleft">
                        <tr>
                        	<td valign="bottom"><?php echo $featured_deal_price_array[1]['id']. ' ' . TXT_BUYERS; ?><div style="height:40px; background-color: #FFCC00;">
                            <?php
							if($featured_total_guest > 0){
								if($featured_total_guest <= $featured_deal_price_array[1]['id']){
									$first_level_percent = (100 * $featured_total_guest)/(int)$featured_deal_price_array[1]['id'];
									$level_finished = 1;									
								}else{
									$first_level_percent = 100;		
									$featured_total_guest = $featured_total_guest - $featured_deal_price_array[1]['id'];							
								}
								
								?>
                                <div style="height:40px; background-color:#FF00FF; width:<?php echo $first_level_percent; ?>%">&nbsp;</div>
                                <?php
							}
							?>
                            </div></td>
                            <td valign="bottom"><?php echo $featured_deal_price_array[2]['id']. ' ' . TXT_BUYERS; ?><div style="height:70px; background-color: #FFCC00;">
                            <?php
							if($featured_total_guest > 0 && $level_finished == 0){
								if($featured_total_guest <= ($featured_deal_price_array[2]['id'] - $featured_deal_price_array[1]['id'])){
									$first_level_percent = (100 * $featured_total_guest)/(int)($featured_deal_price_array[2]['id'] - $featured_deal_price_array[1]['id']);
									$level_finished = 1;
								}else{
									$first_level_percent = 100;
									$featured_total_guest = $featured_total_guest - ($featured_deal_price_array[2]['id'] - $featured_deal_price_array[1]['id']);									
								}
								
								?>
                                <div style="height:70px; background-color:#FF00FF; width:<?php echo $first_level_percent; ?>%">&nbsp;</div>
                                <?php
							}
							?>
                            </div></td>
                            <td valign="bottom"><?php echo $featured_deal_price_array[3]['id']. ' ' . TXT_BUYERS; ?><div style="height:100px; background-color: #FFCC00;">
                            <?php
							if($featured_total_guest > 0 && $level_finished == 0){
								if($featured_total_guest <= ($featured_deal_price_array[3]['id'] - $featured_deal_price_array[2]['id'])){
									$first_level_percent = (100 * $featured_total_guest)/(int)($featured_deal_price_array[3]['id'] - $featured_deal_price_array[2]['id']);
									$level_finished = 1;
								}else{
									$first_level_percent = 100;
									$featured_total_guest = $featured_total_guest - $featured_deal_price_array[3]['id'];
								}
								
								?>
                                <div style="height:100px; background-color:#FF00FF; width:<?php echo $first_level_percent; ?>%">&nbsp;</div>
                                <?php
							}
							?>
                            </div></td>
                        </tr>
                        <tr>
                        	<td><b><?php echo $currencies->display_price($featured_deal_price_array[1]['text'], tep_get_tax_rate($row_featured_specials['products_tax_class_id'])); ?></b></td>
                            <td><b><?php echo $currencies->display_price($featured_deal_price_array[2]['text'], tep_get_tax_rate($row_featured_specials['products_tax_class_id'])); ?></b></td>
                            <td><b><?php echo $currencies->display_price($featured_deal_price_array[3]['text'], tep_get_tax_rate($row_featured_specials['products_tax_class_id'])); ?></b></td>
                        </tr>
                    </table>
					<p class="gd_bought"><?php echo db_to_html($featured_total_guest_booked) . ' ' . TXT_BOUGHT; ?></p>
					<?php
					if(strtotime($row_featured_specials['expires_date']) >= time() && $featured_total_guest_booked < $featured_deal_price_array[3]['id']){
					?>
                    <p class="gd_needed"><?php echo db_to_html($expected_people) . sprintf(TXT_MORE_NEED_FOR_DISC, $currencies->display_price($expected_price, tep_get_tax_rate($row_featured_specials['products_tax_class_id'])) ); ?></p>
                    <?php
					}
					?>
				</div>
				<div class="gd_right">
					<div class="gd_tour_name_mn">
						<div class="gd_tour_name_top">&nbsp;</div>
						<div class="gd_tour_name_mid">
							<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_featured_specials['products_id']);?>"><?php echo db_to_html(tep_db_output($row_featured_specials['products_name'])); ?></a>
						</div>
						<div class="gd_tour_name_bot">&nbsp;</div>
					</div>
					<div class="gd_tour_price_mn">
						<p class="gd_tour_amt"><sup>$</sup><?php echo str_replace("$", '', $currencies->display_price($expected_price, tep_get_tax_rate($row_featured_specials['products_tax_class_id']))); ?></p>
						<p class="gd_needed"><?php echo TXT_ORIGINAL_VAL . $currencies->display_price($row_featured_specials['products_price'], tep_get_tax_rate($row_featured_specials['products_tax_class_id'])) . TXT_YOU_SAVE . $currencies->display_price($row_featured_specials['products_price']-$expected_price, tep_get_tax_rate($row_featured_specials['products_tax_class_id'])); ?></p>
					</div>
					<div class="gd_buy_btn">
						<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_featured_specials['products_id']);?>" target="_blank"><?php echo tep_template_image_button('buy_now_btn.jpg', LNK_BOOK_NOW, ' class="fright"'); ?></a>

					</div>
                    
					<div class="gd_time_left_mn">
                        <div class="gd_time_bg">
							<p class="gd_time_left_txt"><?php echo TXT_TIME_LEFT_TO_BUY; ?></p>
							<p class="gd_time" id="cd"></p>
                            <p><?php echo date("Y-m-d H:i:s"); ?></p>
						</div>
                        <?php if(strtotime($row_featured_specials['expires_date']) >= time()){ ?>
                        <div class="fleft" style="padding:20px 0px 0px 10px">
                            <div class="sub_cat_orange_btn">
                               <a href="<?php echo tep_href_link(FILENAME_REFER_A_FRIEND, 'featured=true&products_id='.$row_featured_specials['products_id'], 'SSL'); ?>" target="_blank"><b><?php echo TXT_SHARE_DEAL; ?></b></a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>                    
                    <div class="fclear"></div>
                    <div class="main"><?php echo sprintf(NOTE_DEPARTURE_AFTER, date("m/d/y", strtotime($row_featured_specials['departure_restriction_date']))); ?></div>                    
                    <?php
					if(strtotime($row_featured_specials['expires_date'])+(24*60*60) < time()){
					?>
					<?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'languages/'.$language.'/missed_deal.png', '', '', '', 'class="featured_missed"'); ?>
					<?php
					}
					?>
				</div>
			</div>
            <div class="gd_mn padd_7px">
	            <br />
                <b><?php echo TITLE_TOUR_DETAILS; ?></b><br />
                <p class="pad_5px">
				<?php
				$display_small_dis = str_replace('?','&bull;&nbsp;',$row_featured_specials['products_small_description']);
				if(strlen(strip_tags($display_small_dis)) > 450){
					echo db_to_html(substr(strip_tags(enleve_accent($display_small_dis)        ),0,450).'...');
				}else{
					echo db_to_html($display_small_dis); 
				}
				?></p>
                <br />
                <b><?php echo TITLE_SPECIAL_NOTE; ?></b><br />
                <p class="pad_5px">
                <?php echo PARA_SPECIAL_NOTES; ?>                
                </p>
            </div>
		</div>
        <script type="text/javascript">
	
		<?php

			// Counting down to New Year's on 2020
			$countdown_to =  date("Y-m-d H:i:s", strtotime($row_featured_specials['expires_date'])+(24*60*60)); // 24-Hour Format: YYYY-MM-DD HH:MM:SS"
			
			// Getting the current time
			$count_from = date("Y-m-d H:i:s"); // current time -- NO NEED TO CHANGE
			
			// getting Date difference in SECONDS
			$diff = scs_datediff("s", $count_from, $countdown_to);
			?>
			
			// Here where the Javascript starts
			countdown = <?= db_to_html($diff);?>;
			
			// Converting date difference from seconds to actual time
			function convert_to_time(secs)
			{
				secs = parseInt(secs);	
				hh = secs / 3600;	
				hh = parseInt(hh);	
			
				mmt = secs - (hh * 3600);	
				mm = mmt / 60;	
				mm = parseInt(mm);	
				ss = mmt - (mm * 60);	
					
				if (hh > 23)	
				{	
				   dd = hh / 24;	
				   dd = parseInt(dd);	
				   hh = hh - (dd * 24);	
				} else { dd = 0; }	
					
				if (ss < 10) { ss = "0"+ss; }	
				if (mm < 10) { mm = "0"+mm; }	
				if (hh < 10) { hh = "0"+hh; }	
				if (dd == 0) { return (hh+" : "+mm+" : "+ss); }	
				else {
					/*	
					if (dd > 1) { return (dd+" days "+hh+":"+mm+":"+ss); }
					else { return (dd+" day "+hh+":"+mm+":"+ss); }
					*/
					hh = eval(parseInt(hh) + (dd*24));
					return (hh+" : "+mm+" : "+ss);
					
				}	
			}
			
			// Our function that will do the actual countdown
			function do_cd()
			{
				if (countdown < 0)	
				{ 	
					
					document.getElementById('cd').innerHTML = "-- : -- : --";
					
			
				}	
				else	
				{	
					document.getElementById('cd').innerHTML = convert_to_time(countdown);
					setTimeout('do_cd()', 1000);
				}	
				countdown = countdown - 1;	
			}
			
			do_cd();

		</script>        
		<?php
			}
		}else{
			echo '<div class="deal_page_tour_box">'.MSG_RECORDS_NOT_FOUND.'</div>';
		}
		?>
	</div>
    
    <?php }else{ ?>
	<div class="deal_page_lp_mid">
		<?php
		define('MAX_DISPLAY_SEARCH_SPECIAL_RESULTS','6');
		$qry_featured_specials = "select ta.operate_currency_code, p.products_id, p.products_ordered, pd.products_name, pd.products_small_description, p.products_durations, p.products_durations_type, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, p.departure_city_id, p.products_video, p.agency_id, pd.products_small_description, p.tour_type_icon, s.featured_deals_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc, " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_FEATURED_DEALS . " s  where p.agency_id = ta.agency_id and ptoc.products_id = p.products_id and p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and DATEDIFF(s.active_date, '".date('Y-m-d')."')<=0 and  DATEDIFF(s.expires_date, '".date('Y-m-d')."')>=0 group by s.featured_deals_date_added DESC";
		$res_featured_specials=tep_db_query($qry_featured_specials);
		if(tep_db_num_rows($res_featured_specials) > 0){
			if(tep_db_num_rows($res_featured_specials)==1){
				$row_featured_specials = tep_db_fetch_array($res_featured_specials);
				tep_redirect(tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $row_featured_specials['products_id']));
			}
			$i=0;
			while($row_featured_specials = tep_db_fetch_array($res_featured_specials)){
				$i++;
				//amit modified to make sure price on usd
				if($row_featured_specials['operate_currency_code'] != 'USD' && $row_featured_specials['operate_currency_code'] != ''){
					$row_featured_specials['products_price'] = tep_get_tour_price_in_usd($row_featured_specials['products_price'], $row_featured_specials['operate_currency_code']);
				}
				$featured_deal_price_array = tep_get_featured_deal_price($row_featured_specials['products_id']);
				if(sizeof($featured_deal_price_array)>1){
					$featured_deal_price_for_this_tour = $featured_deal_price_array[1]['text'];
				}else{
					$featured_deal_price_for_this_tour = $featured_deal_price_array[0]['text'];
				}
		?>
		<div class="deal_page_tour_box">
			<a href="<?php echo tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $row_featured_specials['products_id']);?>" target="_blank"><?php echo db_to_html(tep_image(DIR_WS_IMAGES . $row_featured_specials['products_image'], $row_featured_specials['products_name'], 198, 110, 'class="fleft"'));?></a>
			<div class="sub_cat_grid_view_tour_dtl">
				<h3><a href="<?php echo tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $row_featured_specials['products_id']);?>" title="<?php echo db_to_html(tep_db_output($row_featured_specials['products_name']));?>" target="_blank"><?php echo tep_substr(db_to_html(tep_db_output($row_featured_specials['products_name'])), 45);?></a></h3>
				<div class="deal_tour_price"><span><?php echo $currencies->format($row_featured_specials['products_price'], true, 'USD');?></span>&nbsp;<?php echo $currencies->format($featured_deal_price_for_this_tour, true, 'USD');?></div>
				<p><?php echo db_to_html(tep_substr(tep_db_output(strip_tags(str_replace('&bull;', '?', $row_featured_specials['products_small_description']))), 80));?></p>
			</div>
			<a href="<?php echo tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $row_featured_specials['products_id']);?>"><?php echo tep_template_image_button('book_now_btn.gif', LNK_BOOK_NOW, ' class="fright"'); ?></a>
		</div>
		<?php
			}
		}else{
			echo '<div class="deal_page_tour_box">'.MSG_RECORDS_NOT_FOUND.'</div>';
		}
		?>
	</div>
    
    <?php } ?>
	<div class="prod_discbox_bot">&nbsp;</div>
</div> <!--# Prod_dtl_left div end -->
<div class="prod_dtl_right_mn">
<?php
$qry_specials = "select ta.operate_currency_code, p.products_id, p.products_ordered, pd.products_name, pd.products_small_description, p.products_durations, p.products_durations_type, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, p.departure_city_id, p.products_video, p.agency_id, pd.products_small_description, p.tour_type_icon, s.featured_deals_new_products_price, cd.categories_name, cd.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc, ".TABLE_CATEGORIES_DESCRIPTION ." as cd, " . TABLE_TRAVEL_AGENCY . " ta, " . TABLE_FEATURED_DEALS . " s  where p.agency_id = ta.agency_id and ptoc.products_id = p.products_id and p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id AND ptoc.categories_id = cd.categories_id and pd.language_id = '" . (int)$languages_id . "' and cd.language_id = '" . (int)$languages_id . "' and s.status = '1' and DATEDIFF(s.expires_date, '".date('Y-m-d')."')<0 group by s.featured_deals_date_added DESC";
			
$res_specials = tep_db_query($qry_specials);
if(tep_db_num_rows($res_specials)>0){
?>
	<div class="blue_box_mn">
		<div class="blue_box_heading">
			<div class="blue_box_heading_crv_left"></div>
			<div class="blue_box_heading_bg4">
				<h2><?php echo TXT_PAST_DEALS;?></h2>
			</div>
			<div class="blue_box_heading_crv_right"></div>
		</div>
		<div class="blue_box_con_whi6">
			<?php
			
			$ar_all_categories=array();
			while($row_specials = tep_db_fetch_array($res_specials)){
				$ar_all_categories[]=$row_specials['categories_id'];
			}
			$ar_all_categories_cntr=array_count_values($ar_all_categories);
			if(tep_db_num_rows($res_specials)>0){
				tep_db_data_seek($res_specials, 0);
			}
			$previous_cat_id="";
			while($row_specials = tep_db_fetch_array($res_specials)){
				$cat_cntr++;
				
				?>
			<div class="most_pop_deal">
				<?php
				if($previous_cat_id != $row_specials['categories_id']){
					$cat_cntr=1;
					$previous_cat_id = $row_specials['categories_id'];
					$patterns[0] = TXT_FEA_TOURS;
					$patterns[1] = TXT_FEA_TOUR;
					$patterns[2] = strtolower(TXT_FEA_TOURS);
					$patterns[3] = strtolower(TXT_FEA_TOUR);
					$patterns[4] = TXT_FEA_AIRPLANE;
					$patterns[5] = TXT_FEA_US;
					$replacements[0] = '';
					$replacements[1] = '';
					$replacements[2] = '';
					$replacements[3] = '';
					$replacements[4] = '';
					$replacements[5] = '';
					$categories_name = preg_replace($patterns, $replacements, $row_specials['categories_name']);
				?>
				
				<h3><?php echo db_to_html($categories_name);?></h3>
				<?php
				}
				?>
				<div class="customer_rel_tour">
					<?php echo tep_image(DIR_WS_IMAGES . $row_specials['products_image'], htmlentities($row_specials['products_name']), 70, 45, ' class="fleft"') ;?>
					<p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_specials['products_id']);?>" title="<?php echo db_to_html(tep_db_output($row_specials['products_name']));?>" target="_blank"><?php echo db_to_html(tep_substr(tep_db_output($row_specials['products_name']), 60));?></a><br/><span class="strike-line font_black"><?php echo $currencies->format($row_specials['products_price'], true, 'USD');?></span> <?php echo $currencies->format($row_specials['featured_deals_new_products_price'], true, 'USD');?></p>
                    <div class="fright"><a href="<?php echo tep_href_link(FILENAME_FEATURED_DEALS, 'products_id=' . $row_specials['products_id']);?>"><?php echo tep_template_image_button('small_view.gif', 'View', ' class="fright"'); ?></a></div>
				</div>
				<?php				
				if($ar_all_categories_cntr[$row_specials['categories_id']] == $cat_cntr){
					$previous_cat_id_close = $row_specials['categories_id'];
					?>
					<div class="most_pop_deal_blue_bo_bot"></div>
					<?php
				}
				?>				
			</div>
			<?php
			}
			?>
		</div>
		<div class="blue_box_crv_bot_whi6">&nbsp;</div>
	</div><!--#blue_box_mn -easy book* div end -->
    <?php
	}else{
	?>
    <?php
		$total_companions_sql = tep_db_query('select t_companion_id from '.TABLE_TRAVEL_COMPANION.' where status=1');
		
		$companion_sql = tep_db_query('select tc.t_companion_id, tc.customers_id, tc.t_companion_title, tc.add_time from '.TABLE_TRAVEL_COMPANION.' tc, '.TABLE_TRAVELER_PHOTOS.' tp where tc.status=1 and tc.customers_id = tp.profile_customers_id and tp.image_status="1" and tp.is_main_profile_photo="1" group by tc.t_companion_id Order By tc.last_time desc');
		$num_tc_posts = tep_db_num_rows($total_companions_sql);
	?>
    <div class="blue_box_mn"><!-- #blue_box_mn -travel companion div start -->
		<div class="blue_box_heading">
			<div class="blue_box_heading_crv_left"></div>
			<div class="blue_box_heading_bg4">
				<h2><?php echo TXT_FIND_TRAVEL_COMPANION;?></h2><span>(<a href="<?php echo tep_href_link('new_travel_companion_index.php');?>" title="<?php echo TXT_VIEW_ALL_TRAVEL_COMPENIONS;?>"><?php echo $num_tc_posts.'&nbsp;'.LNK_TRAVEL_COMPENIONS_POSTS;?></a>)</span>
			</div>
			<div class="blue_box_heading_crv_right"></div>
		</div>
		<div class="blue_box_con_whi6">
			<?php
			$cntr_tc_posts = 0;
			while($companion_rows=tep_db_fetch_array($companion_sql)){
				$cntr_tc_posts++;
				if($cntr_tc_posts > 3){
					break;
				}
				$tc_profile_picture = tep_get_tc_profile_pic($companion_rows['customers_id']);
				$tc_img_size = @getimagesize(DIR_FS_IMAGES.str_replace(DIR_WS_IMAGES, '', $tc_profile_picture));
				$tc_img_height = $tc_img_size[1];
				if($tc_img_height > 67){
					$tc_img_height = 67;
				}
			?>
			<div class="tc_find hi_74px">
				<a href="<?php echo tep_href_link(FILENAME_TRAVEL_COMPANION_PROFILE, 'profile_user_id='.$companion_rows['customers_id']); ?>"><?php echo tep_image($tc_profile_picture, 'User Image', '67', $tc_img_height, 'class="fleft"'); ?></a>
				<div class="tc_dtl">
					<p><a href="<?php echo tep_href_link(FILENAME_TRAVEL_COMPANION_DETAILS,'t_companion_id='.(int)$companion_rows['t_companion_id'])?>" title="<?php echo db_to_html(strip_tags($companion_rows['t_companion_title']));?>"><?php echo db_to_html(tep_substr($companion_rows['t_companion_title'], 65));?></a><span><?php echo TXT_DATE_POSTED . date('m-d-y', strtotime($companion_rows['add_time']));?></span></p>
				</div>
			</div>
			<?php
			}
			?>
			<div class="fclear"></div>
			<div class="blue_box_bot_link"><a href="<?php echo tep_href_link('new_travel_companion_index.php');?>"><?php echo TXT_VIEW_ALL; ?> (<?php echo $num_tc_posts; ?>)</a></div>
		</div>
		<div class="blue_box_crv_bot_whi6">&nbsp;</div>
		<div class="fclear"></div>
	</div><!-- #blue_box_mn -travel companion div end -->
    <?php
	}
	?>
</div> <!--# Prod_dtl_right div end -->
