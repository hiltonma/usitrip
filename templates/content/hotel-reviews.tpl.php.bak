<?php
//酒店详细信息
$hotel_id = intval($_GET['hotel_id']);
//$hotel_id = 4;
$hotel_sql = tep_db_query('SELECT * FROM `hotel` WHERE hotel_id="'.(int)$hotel_id.'" LIMIT 1');
$hotel_row = tep_db_fetch_array($hotel_sql);
?>


<?php echo tep_get_design_body_header(NAVBAR_TITLE_TOELT); ?> 
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			
			     <tr>
					 <td width="78%" class="main" >  

	<table cellspacing="0" cellpadding="0" width="100%" border="0">
				  <tbody>
				  <tr>
					<td class="main" style="BACKGROUND-COLOR: rgb(255,255,255)" 
					valign="top" width="72%">
					  <table cellspacing="0" cellpadding="0" width="100%" border="0" >
						<tbody>
						<tr>
						  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td width="24%" rowspan="10" valign="top">
						<?php
						  //列出图片
						  $hotel_pic_sql = tep_db_query('SELECT * FROM `hotel_pic` WHERE hotel_id ="'.(int)$hotel_id.'" ORDER BY `hotel_pic_sort` ASC limit 1');
						  $hotel_pic_rows = tep_db_fetch_array($hotel_pic_sql);
						  if((int)$hotel_pic_rows['hotel_pic_id']){
						  	$first_img_src = $hotel_pic_rows['hotel_pic_url'];
							if(!preg_match('/^http:\/\//',$first_img_src)){
								$first_img_src = DIR_WS_IMAGES.'hotel/'.$first_img_src;
							}
							$first_img_alt = tep_db_output($hotel_pic_rows['hotel_pic_alt']);
							echo '<img src="'.$first_img_src.'" width="154" alt="'.$first_img_alt.'">';
						  }
						  ?>							  </td>
							  <td width="5%" rowspan="10"></td>
							  <td height="22" nowrap="nowrap"><span class="title_customer2"><?php echo TITLE_TOELT_NAME?></span></td>
							  <td width="59%" class="yingwen"><?php echo db_to_html(tep_db_output($hotel_row['hotel_name']))?> </td>
						    </tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_STARS?></span></td>
							  <td >
							  <img src="image/stars_<?= (int)$hotel_row['hotel_stars']?>.gif" alt="<?= (int)$hotel_row['hotel_stars']?>">							  </td>
						    </tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_ADDRESS?></span></td>
							  <td width="59%" class="yingwen"><?php echo db_to_html(tep_db_output($hotel_row['hotel_address']))?></td>
						    </tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_SCORE?></span></td>
							  <td width="59%">
							  <?php 
							   $hotel_reviews_rating = (int)$hotel_row['hotel_reviews_rating'];
							   if ($hotel_reviews_rating > 0){ 							   	
  								   echo sprintf(SB_REVIEWS_RATING, $hotel_row['hotel_reviews_rating']);
							   }else{
							   	   echo  db_to_html('暂无评分');
							   }
							   ?>
							  
							  </td>
							  </tr>
							
							<tr>
							  <td colspan="2"><span class="title_customer2"><?php echo TITLE_TOELT_DESCRIPTION?></span>&nbsp;<?php echo cutword(db_to_html(strip_tags($hotel_row['hotel_description'])),110)?></td>
							  </tr>
							
						  </table></td>
						</tr>
						</tbody></table></td>
				  </tr>
				  </tbody></table></td>
					 <td width="22%" valign="top" class="main"  style="border-left:1px dashed #828282">
					 
					 <table width="96
                %" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%">&nbsp;</td>
                    <td width="89%"><a href="<?php echo tep_href_link('hotel-reviews.php', 'hotel_id=' . (int)$hotel_id.'&products_id='.(int)$products_id);?>#reviews_input" class="title_customer2" style="color:#108BCD; font-size:12px" ><?php echo A_I_WILL_REVIEW?></a></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><span style="color:#6f6f6f"><?php echo sprintf(HOTEL_POINTS_MSN ,db_to_html(tep_db_output($hotel_row['hotel_name'])));?> </span></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><a href="<?php echo tep_href_link('hotel.php', 'hotel_id=' . (int)$hotel_id.'&products_id='.(int)$products_id);?>"class="title_customer2" style="color:#108BCD; font-size:12px" ><?php echo A_VIEW_HOTEL?></a></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>
					<?php if((int)$products_id){?>
							    <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products_id);?>"><?php echo A_BACK_TOUR?></a>&nbsp;<img src="<?php echo DIR_WS_ICONS?>back_hotel.gif">
					<?php }?>					</td>
                  </tr>
                </table>					 </td>
			     </tr>
			     <tr><td colspan="2" class="main" >&nbsp;</td></tr>
				 
				 <!--BBS-->
				 <?php 
				 //取得BBS内容
				 $hotel_reviews_query_raw = 'SELECT * FROM hotel_reviews WHERE hotel_id ='.(int)$hotel_id .' AND reviews_status=1 order by hotel_reviews_id DESC';
				 $max_display_rows = MAX_DISPLAY_NEW_REVIEWS;
				 //$max_display_rows = 2;
				 $hotel_reviews_split = new splitPageResults($hotel_reviews_query_raw, $max_display_rows);
				 if($hotel_reviews_split->number_of_rows > 0){
					 $hotel_reviews_query = tep_db_query($hotel_reviews_split->sql_query);
					 while ($hotel_reviews_rows = tep_db_fetch_array($hotel_reviews_query)) {
				 
				 ?>
				 <tr>
			       <td colspan="2" class="main" >
				   
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:5px; margin-bottom:5px; border-top:1px solid #108BCD;">
					  <tr bgcolor="#D9F0FD"  >
						<td width="3%" height="28" bgcolor="#D9F0FD" style="padding-left:4px;"><img src="image/icons/touxiang1.gif" width="14" height="14"></td>
						<td width="24%" nowrap="nowrap"><?php echo sprintf(CUSTOMERS_REVIEWS_TO_HOTEL, db_to_html(tep_db_output($hotel_reviews_rows['customers_name'])), '<a href="'.tep_href_link('hotel.php', 'hotel_id=' . (int)$hotel_id.'&products_id='.(int)$products_id).'" style="font-weight:bold;">'.db_to_html(tep_db_output($hotel_row['hotel_name'])).'</a>')?> </td>
						<td>&nbsp;</td>
						<td width="3%"><img src="image/icons/hongqi.gif" width="14" height="14"></td>
						<td width="19%"><?php echo sprintf(REVIEWS_RATING, (int)$hotel_reviews_rows['reviews_rating']);?></td>
						<td width="3%"><img src="image/icons/shijian1.gif" width="14" height="14"></td>
						<td width="27%"><span style="color:#F1740E"><?php echo db_to_html(chardate($hotel_reviews_rows['date_added'], 'I','1'))?></span></td>
					  </tr>
					  <tr>
						<td colspan="7" style="padding-top:5px; padding-bottom:5px;"><p><?php echo db_to_html(nl2br(tep_db_output($hotel_reviews_rows['reviews_text'])));?></p>						 </td>
					  </tr>
					</table>				   </td>
	          </tr>
			  	 <?php
					 }
				 }
				 ?>
			  
			  <!--BBS end-->
			  
			  	<?php if($hotel_reviews_split->number_of_pages>1){?>
				<!--BBS分页-->
				<tr><td colspan="2" class="main" >
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td class="smallText"><?php echo $hotel_reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
						<td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $hotel_reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></td>
					</tr>
				</table>
				</td></tr>
				<!--BBS分页end-->
				<?php }?>
				
				<tr>
				  <td colspan="2" class="main" style="padding-top:10px;" >&nbsp;<a name="reviews_input" id="reviews_input"></a></td>
			  </tr>
				<tr>
				  <td colspan="2" class="main" style="padding-top:10px;" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="10%">&nbsp;</td>
                      <td>
					  <form action="" method="post" enctype="multipart/form-data" name="form_reviews" id="form_reviews">
					  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="infoBox">
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
					<?php
					  if ($messageStack->size('reviews') > 0) {
					?>
						  <tr>
							<td colspan="3"><?php echo $messageStack->output('reviews'); ?></td>
						  </tr>
						  <tr class="infoBoxContents">
							<td colspan="3">&nbsp;</td>
						  </tr>
					<?php
					  }
					?>

                        <tr class="infoBoxContents">
                          <td width="25%" align="right" class="sp10 sp6"><input name="action" type="hidden" id="action" value="reviews_process" />
                            <input name="hotel_id" type="hidden" id="hotel_id" value="<?php echo (int)$hotel_id?>" />
                          <?php echo TEXT_YOUR_NAME?></td>
                          <td width="50%" align="left"><?php echo tep_draw_input_field('customers_name','','size="46"')?></td>
                          <td width="25%" align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL?></td>
                          <td align="left"><?php echo tep_draw_input_field('customers_email','','size="46"')?></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL_CONFIRM?></td>
                          <td align="left"><?php echo tep_draw_input_field('c_customers_email','','size="46"')?></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6"><?php echo TEXT_YOUR_REVIEWS?></td>
                          <td align="left">
                          <?php echo tep_draw_textarea_field('reviews_text', 'virtual', 100, 7)?>						  </td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6">&nbsp;</td>
                          <td align="left">
						  <?php
						  echo ONE_RATING;
						  echo tep_draw_radio_field('reviews_rating','1', '', 'title="'.ONE_RATING.'"');
						  echo tep_draw_radio_field('reviews_rating','2', '', 'title="'.TWO_RATING.'"');
						  echo tep_draw_radio_field('reviews_rating','3', '', 'title="'.THREE_RATING.'"');
						  echo tep_draw_radio_field('reviews_rating','4', '', 'title="'.FOUR_RATING.'"');
						  echo tep_draw_radio_field('reviews_rating','5', '', 'title="'.FIVE_RATING.'"');
						  echo FIVE_RATING;
						  ?>	
						  </td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6">&nbsp;</td>
                          <td align="left"><?php echo tep_image_submit('submit_reviews.gif', 'Submit')?></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <tr class="infoBoxContents">
                          <td align="right" class="sp10 sp6">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                          <td align="left">&nbsp;</td>
                        </tr>
                      </table>
					  </form>
					  
					  </td>
                      <td width="10%">&nbsp;</td>
                    </tr>
                    
                  </table></td>
			  </tr>
			</table>
				  
<?php echo tep_get_design_body_footer();?>
