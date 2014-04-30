<?php
//酒店详细信息
$hotel_id = intval($_GET['hotel_id']);
if(!(int)$hotel_id){
	$hotel_id = 1;
}
$hotel_sql = tep_db_query('SELECT * FROM `hotel` WHERE hotel_id="'.(int)$hotel_id.'" LIMIT 1');
$hotel_row = tep_db_fetch_array($hotel_sql);
?>
<table width="99%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="pageHeading" width="100%"><?php echo NAVBAR_TITLE_TOELT?></td><td  nowrap="nowrap" class="pageHeading" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td class="mainbodybackground" colspan="2">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			
			     <tr>
					 <td width="100%" class="main" >  

	<table cellspacing="0" cellpadding="0" width="100%" border="0">
				  <tbody>
				  <tr>
					<td class="main" style="BACKGROUND-COLOR: rgb(255,255,255)" 
					valign="top" width="72%">
					  <table cellspacing="0" cellpadding="0" width="96%" border="0" style="margin-left:14px; margin-top:10px;">
						<tbody>
						<tr>
						  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td width="11%" height="22"><span class="title_customer2"><?php echo TITLE_SCORE?></span></td>
							  <td width="59%">
							  <?php
							    $hotel_reviews_rating  = (int)$hotel_row['hotel_reviews_rating'];
							  	if ($hotel_reviews_rating > 0){								
									echo sprintf(SB_REVIEWS_RATING, $hotel_row['hotel_reviews_rating'])
									
							  ?>
							  <?php 
							  	}else {
									echo db_to_html('暂无评分');
								}
							  ?>
							  
							  </td>
							  <td width="30%" align="right"><a href="<?php echo tep_href_link('hotel-reviews.php', 'hotel_id=' . (int)$hotel_id.'&products_id='.(int)$products_id);?>#reviews_input"class="title_customer2" style="color:#108BCD" ><?php echo A_I_WILL_REVIEW?></a></td>
							</tr>
							<tr>
							  <td height="22" nowrap="nowrap"><span class="title_customer2"><?php echo TITLE_TOELT_NAME?></span></td>
							  <td width="59%" class="dazi yingwen"><?php echo db_to_html(tep_db_output($hotel_row['hotel_name']))?> </td>
							  <td width="30%" align="right"><a href="<?php echo tep_href_link('hotel-reviews.php', 'hotel_id=' . (int)$hotel_id.'&products_id='.(int)$products_id);?>" style=" font-size:14px"><?php echo A_VIEW_ALL_REVIEW?></a></td>
							</tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_STARS?></span></td>
							  <td >
							  <img src="image/stars_<?= (int)$hotel_row['hotel_stars']?>.gif" alt="<?= (int)$hotel_row['hotel_stars']?>">
							  </td>
							  <td align="right">
							  <?php if((int)$products_id){?>
							  <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products_id);?>"><?php echo A_BACK_TOUR?></a>&nbsp;<img src="<?php echo DIR_WS_ICONS?>back_hotel.gif">
							  <?php }?>
							  </td>
							</tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo db_to_html('餐饮服务：')?></span></td>
							  <td width="59%" class="dazi yingwen"><?php echo db_to_html(tep_db_output(getHotelMealsOptions($hotel_row['meals_id'])))?></td>
							  <td width="30%" rowspan="3"></td>
							</tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo db_to_html('网络服务：')?></span></td>
							  <td width="59%" class="dazi yingwen"><?php echo db_to_html(tep_db_output(getHotelInternetOptions($hotel_row['internet_id'])))?></td>
							  <td width="30%" rowspan="3"></td>
							</tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_ADDRESS?></span></td>
							  <td width="59%" class="dazi yingwen"><?php echo db_to_html(tep_db_output($hotel_row['hotel_address']))?></td>
							  <td width="30%" rowspan="3"></td>
							</tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_PHONE?></span></td>
							  <td width="59%" class="dazi" ><?php echo db_to_html(tep_db_output($hotel_row['hotel_phone']))?></td>
							  </tr>
							<tr>
							  <td height="22"><span class="title_customer2"><?php echo TITLE_TOELT_DESCRIPTION?></span></td>
							  <td width="59%">&nbsp;</td>
							  </tr>
							<tr>
							  <td colspan="3"><div class="dazi"><?php echo db_to_html($hotel_row['hotel_description'])?></div></td>
							  </tr>
							
						  </table></td>
						</tr>
						<tr>
						  <td colspan="2" style="padding-top:15px" ><div class="product_tab_b_n" style="width:657px; margin-left:0px;"><div class="tab1"><ul><li class="s"><a><?php echo TITLE_TOELT_PIC?></a></li></ul></div></div>
						  <?php
						  //列出所有图片
						  $imagesInfos = getHotelImagesInfos($hotel_id);
						  if($imagesInfos!=false){
						  ?>
						  <div id="album">
							<div id="pic"><a href="<?= $imagesInfos[0]['src']?>" rel="lightbox" id="ShowLightBox" target="_blank" title="<?= ($imagesInfos[0]['alt'])?>"><img src="<?= $imagesInfos[0]['src']?>" alt="<?= ($imagesInfos[0]['alt'])?>" id="placeholder"></a> </div>
							<p id="desc"><?php echo db_to_html($imagesInfos[0]['desc']);?></p>
							
							<div id="thumbs">
							  <ul>
								<?php
								for($i=1, $n=sizeof($imagesInfos); $i<$n; $i++){
								?>
								<li><a onclick="return showPic(this);" href="<?= $imagesInfos[$i]['src']?>" title="<?= db_to_html($imagesInfos[$i]['alt']);?>"><img src="<?= $imagesInfos[$i]['src']?>" alt="<?= db_to_html($imagesInfos[$i]['alt']);?>"></a></li>
								<?php
								}
								?>
								
							  </ul>
							</div>
	  </div>
	  					  <?php
						  }
						  //列出所有图片 end
						  ?>
	  						
							</td>
						</tr>
						
						<?php if(tep_not_null($hotel_row['hotel_map'])){?>
						<tr>
						  <td colspan="2" style="padding-top:15px;"><div class="product_tab_b_n" style="width:657px; margin-left:0px;"><div class="tab1"><ul><li class="s"><a><?php echo TITLE_TOELT_MAP?></a></li></ul>
						  </div></div>
						  <div class="hotel_map"><?php echo db_to_html(tep_db_prepare_input($hotel_row['hotel_map']))?></div></td>
						</tr>
						<?php }?>
						
						<tr>
						  <td colspan="0" style="padding-top:15px;">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td width="20%"><span style="font-weight:bold;"><?php echo TITLE_TOELT_SEND_TO_FRIEND?></span></td>
							  <td width="80%"><hr width="100%" noshade="noshade" size="1" color="#B8B8B8"></td>
							</tr>
							<tr>
							  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px;  padding-bottom:15px;">
								<tr>
								  <td width="50%">
								  <form action="" method="post" name="form_send_f" id="form_send_f" onsubmit="Check_Submit('form_send_f','submit_send_msn'); return false;">
									
									<input name="action" type="hidden" id="action" value="send_mail_to_friends" />
									<input name="hotel_id" type="hidden" id="hotel_id" value="<?php echo (int)$hotel_id?>" />
									<textarea name="email_address_strong" cols="35" rows="4" id="email_address_strong" style="color:6f6f6f" onfocus="Check_Onfocus(this,'<?php echo TEXT_NOTE_MSN?>')" onblur="Check_Onblur(this,'<?php echo TEXT_NOTE_MSN?>')"><?php echo TEXT_NOTE_MSN?></textarea>
									
									<?php
									$email_address = $customer_email_address;
									if(!tep_not_null($email_address)){
										$email_address = TEXT_NOTE_MSN_1;
									}
									?>
									<br><input name="email_address" type="text" value="<?php echo $email_address?>" size="51" onfocus="Check_Onfocus(this,'<?php echo TEXT_NOTE_MSN_1?>')" onblur="Check_Onblur(this,'<?php echo TEXT_NOTE_MSN_1?>')"/>
									<br>
								  	<div id="submit_send_msn"></div>
									<br><?php echo tep_image_submit('fasong.gif', 'send mail') ?>
								  </form>
	                                  </td>
								  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="14%" align="center">&nbsp;</td>
									  <td width="22%" height="26" align="center" style="color:#6f6f6f"><?php echo TEXT_COPY_HTML_CODE?></td>
									  <td width="44%">
									      <input name="r_hotel_url" type="text" id="r_hotel_url" style=" height:19px" value="<?php echo tep_href_link('hotel.php','hotel_id='.(int)$hotel_row['hotel_id']);?>" />
									    </td>
									  <td width="20%" valign="middle">&nbsp;<a href="javascript::" onclick="copy_input('r_hotel_url', '<?php echo JS_COPY_SUCCESS?>')"><?php echo tep_template_image_button('fuzhi.gif', TEXT_COPY_HTML_CODE) ?></a></td>
									</tr>
									<tr>
									  <td align="center">&nbsp;</td>
									  <td height="26" align="center" style="color:#6f6f6f"><?php echo TEXT_SEND_QQ?></td>
									  <td>
									      <input name="r_hotel_msn" type="text" id="r_hotel_msn" style=" height:19px" value="<?php echo sprintf(RECOMMEND_A_HOTEL_FOR_YOU, db_to_html(tep_db_output($hotel_row['hotel_name'])), tep_href_link('hotel.php','hotel_id='.(int)$hotel_row['hotel_id']))?>">

									    </td>
									  <td valign="middle">&nbsp;<a href="javascript::" onclick="copy_input('r_hotel_msn', '<?php echo JS_SEND_QQ_SUCCESS?>')"><?php echo tep_image_submit('fuzhi.gif', TEXT_SEND_QQ) ?></a></td>
									</tr>
								  </table></td>
								</tr>
							  </table></td>
							  </tr>
						  </table>                      </td>
						</tr>
						</tbody></table></td>
					<td valign="top" bgcolor="#e3f5ff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <?php /*
					  <tr>
						<td><div style="background:#FFCC00; text-align:center; padding:5px 0px 5px 0px;"><?php echo tep_template_image_submit('yuding.gif', B_BOOK_NOW); ?><br><span style="color:#6f6f6f">
	酒店预定将以全站谘询的方式直接提交申请</span></div></td>
					  </tr>
					  */?>
					  <tr>
						<td>
						<div class="help_phone_call"><?php echo db_to_html('有任何问题请拨打')?><br>
	<span style="font-weight:bold;">1-626-898-7800 888-887-2816</span><br>
	<?php echo db_to_html('或')?><span style="font-weight:bold;">0086-4006-333-926</span><?php echo db_to_html('或客服邮箱')?><br>
	<span style="font-weight:bold;"><a href="mailto:service@usitrip.com">service@usitrip.com</a></span></div>
						
						</td>
					  </tr>
					  <tr>
						<td><table width="94%" border="0" cellpadding="0" cellspacing="0" style="margin-left:12px; margin-top:15px;">
							<?php
							//相关行程酒店
							if(tep_not_null($hotel_row['related_hotel'])){
								$related_hotel_ids = $hotel_row['related_hotel'];
								$r_h_sql = tep_db_query('SELECT * FROM `hotel` WHERE hotel_id in ('.$related_hotel_ids.') ');
								$r_h_rows = tep_db_fetch_array($r_h_sql);
								if((int)$r_h_rows['hotel_id']){
							?>
						  <tr>
							<td width="41%"><span style="font-weight:bold; color:#6f6f6f"><?php echo RELATED_HOTEL?></span></td>
							<td width="59%"><hr width="100%" noshade="noshade" size="1" color="#B8B8B8"></td>
						  </tr>
						  <tr>
							<td colspan="2">
							
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
							  		<?php do{?>
							  <tr><td width="6%"><img src="image/icons/biao_jiantou.gif">&nbsp;</td>
								<td width="94%" style="color:#6f6f6f"><?php echo '<a href="'.tep_href_link('hotel.php', 'hotel_id=' . (int)$r_h_rows['hotel_id'].'&products_id='.(int)$products_id).'" style="font-weight:bold;">'.db_to_html(tep_db_output($r_h_rows['hotel_name'])).'</a>'?></td>
							  </tr>
							  		<?php }while($r_h_rows = tep_db_fetch_array($r_h_sql));?>
							  
							  <tr>
								<td>&nbsp;</td>
								<td align="right" >
								<?php /*
								+<a href="usitrip-hotel.htm" style="color:#6f6f6f"><?php echo ALL_RELATED_HOTEL?></a>
								*/?>
								</td>
							  </tr>
							</table>
							<?php
								}
							}
							//相关行程酒店 end
							?>
							
							</td>
						  </tr>
	
						</table>
						<table width="94%" border="0" cellpadding="0" cellspacing="0" style="margin-left:12px; margin-top:15px;">
							<?php
							//相关行程升级酒店
							if(tep_not_null($hotel_row['related_high_hotel'])){
								$related_high_hotel_ids = $hotel_row['related_high_hotel'];
								$r_h_highsql = tep_db_query('SELECT * FROM `hotel` WHERE hotel_id in ('.$related_high_hotel_ids.') ');
								$r_h_highrows = tep_db_fetch_array($r_h_highsql);
								if((int)$r_h_highrows['hotel_id']){
							?>
						  <tr>
							<td width="41%"><span style="font-weight:bold; color:#6f6f6f"><?php echo RELATED_HIGH_HOTEL?></span></td>
							<td width="59%"><hr width="100%" noshade="noshade" size="1" color="#B8B8B8"></td>
						  </tr>
						  <tr>
							<td colspan="2">
							
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
							  		<?php do{?>
							  <tr><td width="6%"><img src="image/icons/biao_jiantou.gif">&nbsp;</td>
								<td width="94%" style="color:#6f6f6f"><?php echo '<a href="'.tep_href_link('hotel.php', 'hotel_id=' . (int)$r_h_highrows['hotel_id'].'&products_id='.(int)$products_id).'" style="font-weight:bold;">'.db_to_html(tep_db_output($r_h_highrows['hotel_name'])).'</a>'?></td>
							  </tr>
							  		<?php }while($r_h_highrows = tep_db_fetch_array($r_h_highsql));?>
							  
							  <tr>
								<td>&nbsp;</td>
								<td align="right" >
								<?php /*
								+<a href="usitrip-hotel.htm" style="color:#6f6f6f"><?php echo ALL_RELATED_HOTEL?></a>
								*/?>
								</td>
							  </tr>
							</table>
							<?php
								}
							}
							//相关行程升级酒店 end
							?>
							
							</td>
						  </tr>
	
						</table>
						
						</td>
					  </tr>
					</table></td>
	  </tr></tbody></table></td></tr></table></td></tr></table>