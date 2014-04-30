<?php
require_once("includes/application_top.php");
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_REVIEWS_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_INFO);

if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
		  	
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));   
			//echo "$key=>$value2<br>";  	   
		  }
		}
		if(isset($HTTP_GET_VARS['articles_id'])){
		$article_info['articles_id'] = $HTTP_GET_VARS['articles_id'];
		}
}	

?>
<tr>
        <td class="main">
 <div class="pr_b"><div class="pr_b_t sp10 sp6" style=" vertical-align:middle">
 <!--Read what other usitrip.com travelers think about the article. It is to help you plan your trip with us and make the most of your next trip. -->
 <span style="vertical-align:middle"><a style="CURSOR: pointer" onclick="javascript:toggel_div('write_review_form_id');" class="sp3" title="Click here to Submit Your Review"><img style="cursor: pointer; cursor: hand; " src="image/buttons/tchinese/button_write_review.gif" border="0" alt="Submit Your Review" /></a></span>
 </div></div>
 
 				<div id="review_result" style="width: 720px;float: left; margin-left:10px; display:inline;"></div> 
 				<div class="pr_b_form" id="write_review_form_id" style="DISPLAY: none">				
				    <div class="pr_b_form_1">
				      
 					 <?php 
					    echo tep_draw_form('product_reviews_write','','','id="product_reviews_write"'); ?>
					  
					  <table border="0" width="80%" align="left" class="automarginclass"  cellspacing="1" cellpadding="2" >
						<tr>
							<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="main"><b><?php //echo HEADING_TITLE; ?></b></td>
							   <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
							  </tr>
							</table></td>
						  </tr>
						<tr>
							<td>
							  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
							  <tr class="infoBoxContents">
							  <td>
										   <table  cellspacing="2" cellpadding="2">
												   <tr>
												   		<td width="20%" class="sp10 sp6"><?php echo FORM_FIELD_NAME; ?> </td>
												  		<td ><?php echo tep_draw_input_field('customers_name',tep_output_string_protected(ucfirst($customer_first_name) . ' ' . ucfirst($customer_last_name)),' class="pr_b_text" style="width: 50%;"'); ?><span class="sp1">*</span></td></tr>
												  <tr>
												  		<td  class="sp10 sp6"><?php echo FORM_FIELD_EMAIL; ?> </td>
												  		<td nowrap="nowrap"><?php echo tep_draw_input_field('customers_email',tep_get_customers_email($customer_id),' class="pr_b_text" style="width: 50%;"'); ?><span class="sp1">* (required but will never be displayed)</span></td></tr>
												  <tr>
												  		<td  class="sp10 sp6"><?php echo FORM_FIELD_REV_TITLE; ?></td>
												  		<td style="height:25px;"><?php echo tep_draw_input_field('review_title','','size="50" class="pr_b_text" style="width: 50%;"'); ?><span class="sp1"> *</span></td>
												  </tr>
												  <tr>
												  		<td  class="sp10 sp6"><?php echo FORM_FIELD_REVIEW; ?> </td>
												 		<td nowrap="nowrap"><?php echo tep_draw_textarea_field('review', 'soft', '', '','',' class="pr_b_text_1"'); ?><span class="sp1">* </span></td>
												  </tr>
												  <tr>
												  <td></td>
												  <td ><span class="sp1"><?php echo TEXT_NO_HTML; ?></span></td>
												  </tr>
												  <tr>
													<td class="sp10 sp6"><?php echo SUB_TITLE_RATING; ?>&nbsp;&nbsp;</td><td><?php echo  '<span class="sp1">'.TEXT_BAD. '</span> ';?>
													
													<?php echo tep_draw_radio_field('rating', 'false','' , "onclick=checkFormInput(this,'".FILENAME_ARTICLE_REVIEWS_WRITE."','product_reviews_write')") . ' ' . tep_draw_radio_field('rating', 'false','' , "onclick=checkFormInput(this,'".FILENAME_ARTICLE_REVIEWS_WRITE."','product_reviews_write')") . ' ' . tep_draw_radio_field('rating', 'false','' , "onclick=checkFormInput(this,'".FILENAME_ARTICLE_REVIEWS_WRITE."','product_reviews_write')") . ' ' . tep_draw_radio_field('rating', 'false','' , "onclick=checkFormInput(this,'".FILENAME_ARTICLE_REVIEWS_WRITE."','product_reviews_write')") . ' ' . tep_draw_radio_field('rating', 'false','' , "onclick=checkFormInput(this,'".FILENAME_ARTICLE_REVIEWS_WRITE."','product_reviews_write')"); ?>
													<?php
													echo ' <span class="sp1">' . TEXT_GOOD.'</span class="sp1">'; ?>
													<input type="hidden" name="ajxsub_send" value="true" />
													</td></tr>
												 
										   </table>
										   
										     </td>
											  </tr>
											</table>
											</td>
										</tr>
										<tr>
											<td height="15"></td>
										</tr>
										
										
										
										<tr>
										<td>
											
												<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
												  <tr class="infoBoxContents">
													<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
													   <tr>
													   <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
													   <td >
													  <?php //echo tep_template_image_submit('button_submit_reviews.gif', IMAGE_BUTTON_CONTINUE); ?>
													  <img style="cursor: pointer; cursor: hand; " src="image/buttons/tchinese/button_submit_reviews.gif" border="0" alt="Write Review" onclick="sendFormData('product_reviews_write','<?php echo tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, 'action=process&articles_id=' . $HTTP_GET_VARS['articles_id']);?>','review_result','true');"  title=" Write Review " />
													  </td>
													   <td class="main" align="right">
														 <img style="cursor: pointer; cursor: hand; " src="image/buttons/tchinese/button_cancel.gif" border="0" alt="<?php echo 'Cancel'; ?>" onclick="javascript:toggel_div('write_review_form_id');"  title=" <?php echo 'Cancel'; ?> " />
														</td>		
													  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
													  </tr>
												  
													</table></td>
												  </tr>
												</table>
											
										</td>
										</tr>
							</table>
					   
					   </form>
					</div>
					
				</div>
				
 
			  	
			  <?php											
				$reviews_query_raw = "select r.reviews_id, rd.reviews_title, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name, r.customers_email from " . TABLE_ARTICLE_REVIEWS . " r, " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " rd where r.articles_id = '" . (int)$article_info['articles_id'] . "' and r.reviews_id = rd.reviews_id and r.reviews_status='1' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";
				$reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS, 'r.reviews_id','rn');
				if ($reviews_split->number_of_rows > 0) {		
				?>  
				  
				  <?php
				  if ($reviews_split->number_of_rows > MAX_DISPLAY_NEW_REVIEWS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				 ?>
				<div class="tab1_2_2">
				<span class="sp10 sp6">
				<?php  //echo tep_draw_form('frm_slippage_ajax_product_review_top', '' ,"",'id=frm_slippage_ajax_product_review_top');	?>
				<?php //echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, 'mnu=reviews&'.tep_get_all_get_params(array('rn','page','mnu','info')),'product_reviews_tabs_ajax.php','frm_slippage_ajax_product_review_top','review_desc_body'); 
				
				?>
				<?php
				// echo '<input type="hidden" name="selfpagename_treview" value="products_detail_review">';
				// echo '<input type="hidden" name="ajxsub_send_treview_req" value="true">';
				//    echo '</form>';
				echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('rn','page','mnu','info')));
				?>
				</span>
				</div>
				  <?php
				  }
				  ?>
				  
				  <div id="review_new_added"></div>		
				<?php 																				
					$reviews_query = tep_db_query($reviews_split->sql_query);											
					while ($reviews = tep_db_fetch_array($reviews_query)) {							
					if( STORE_OWNER_EMAIL_ADDRESS == $reviews['customers_email'] ){				
				?>
					<div class="pr_b_q1">
					   <div class="pr_b_q_1 sp10 sp6">
						<table width="683">
						<tr><td width="18"><img src="image/q.gif" alt="" /></td>
						<td >
						<b><?php echo tep_output_string_protected($reviews['reviews_title']); ?> </b>
						</td>
						<td><?php
						 if($reviews['reviews_rating']){
						   echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']));
						  }
						?>
						</td>
						<td  align="right"><?php echo '<span class="sp1">'.$reviews['customers_name'].'</span>&nbsp;&nbsp;|&nbsp;&nbsp;'.sprintf(tep_date_long_review($reviews['date_added'])); ?></td> 
						</tr></table></div>
						<div class="pr_b_qq sp10 sp6">
							<?php 
							echo tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],0,240)), 80, '-<br />') . ((strlen($reviews['reviews_text']) >= 240) ? '<span id="span_id_dot_'.$reviews['reviews_id'].'">..</span><span style="DISPLAY: none" id="span_id_dot_more_'.$reviews['reviews_id'].'">'. tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],240,strlen($reviews['reviews_text']))), 80, '-<br />').'</span>' : '') ; 
							?>							
						</div>
					</div>
					<div class="pr_b_qing"><div class="pr_b_qimg"><img src="image/pr_s1.gif" alt="" /></div>					
					<?php					
					//echo ((strlen($reviews['reviews_text']) >= 240) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'articles_id=' . $product_info['articles_id'] . '&reviews_id=' . $reviews['reviews_id']) . '" class="pr_b_qimg_t sp3">Read full review</a> ' : ''); 

					if(strlen($reviews['reviews_text']) >= 240){
					?>
					 <a style="CURSOR: pointer" onclick="javascript:toggel_div('span_id_dot_<?php echo $reviews['reviews_id'];?>');toggel_div('span_id_dot_more_<?php echo $reviews['reviews_id'];?>');" class="pr_b_qimg_t sp3"><?php echo TEXT_FULL_REVIEW; ?></a>
					<?php }					
					?>
					
					
					</div>
					
					
					<?php }else{ ?>
					<div class="pr_b_q">
					    <div class="pr_b_q_1 sp10 sp6">
						<table width="683">
						<tr><td width="18"><img src="image/q.gif" alt="" /></td>
						<td >
						<b><?php echo tep_output_string_protected($reviews['reviews_title']); ?> </b>
						</td>
						<td><?php
						 if($reviews['reviews_rating']){
						   echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']));
						  }
						?>
						</td>
						<td  align="right"><?php echo $reviews['customers_name'].'&nbsp;&nbsp;|&nbsp;&nbsp;'.sprintf(tep_date_long_review($reviews['date_added'])); ?></td> 
						</tr></table></div>
						<div class="pr_b_qq sp10 sp6">
							<?php
								 //echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 240, '-<br>') . ((strlen($reviews['reviews_text']) >= 250) ? '..' : '') ; 
								 echo tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],0,240)), 80, '-<br />') . ((strlen($reviews['reviews_text']) >= 240) ? '<span id="span_id_dot_'.$reviews['reviews_id'].'">..</span><span style="DISPLAY: none" id="span_id_dot_more_'.$reviews['reviews_id'].'">'. tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],240,strlen($reviews['reviews_text']))), 80, '-<br />').'</span>' : '') ; 
							?>
						</div>
					</div>
					<div class="pr_b_qing"><div class="pr_b_qimg"><img src="image/pr_s.gif" alt="" /></div>					
					<?php					
					//echo ((strlen($reviews['reviews_text']) >= 240) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'articles_id=' . $product_info['articles_id'] . '&reviews_id=' . $reviews['reviews_id']) . '" class="pr_b_qimg_t sp3">Read full review</a> ' : ''); 
					
					?>
					<?php					
					if(strlen($reviews['reviews_text']) >= 240){
					?>
					 <a style="CURSOR: pointer" onclick="javascript:toggel_div('span_id_dot_<?php echo $reviews['reviews_id'];?>');toggel_div('span_id_dot_more_<?php echo $reviews['reviews_id'];?>');" class="pr_b_qimg_t sp3"><?php echo TEXT_FULL_REVIEW; ?></a>
					<?php }					
					?>
					</div>
					
					<?php } ?>
					<?php
											
					} //end of while loop
					
				}else{
				//now review of tour
				
				?>
				<div id="review_new_added"></div>
				<div id="noreview_id_div" class="pr_b_q">
					   <div class="pr_b_q_1 sp10 sp6">
					   <table width="683">
						  <tr><td>
					   <?php echo TEXT_NO_REVIEWS; ?>
					   </td></tr>					  
					   </table>
						</div>
				</div>
				<?php
				}
				?>
											
				<?php
				  if ($reviews_split->number_of_rows > MAX_DISPLAY_NEW_REVIEWS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				 ?>
				<div class="pr_b_h"></div>
				<div class="tab1_2_2">
				<span class="sp10 sp6">
				<?php  //echo tep_draw_form('frm_slippage_ajax_product_review', '' ,"",'id=frm_slippage_ajax_product_review');	?>
				<?php //echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, 'mnu=reviews&'.tep_get_all_get_params(array('rn','page','mnu','info')),'product_reviews_tabs_ajax.php','frm_slippage_ajax_product_review','review_desc_body'); 
				
				//echo '<input type="hidden" name="selfpagename" value="products_detail">';
				//echo '<input type="hidden" name="ajxsub_send_rev_req" value="true">';
				//echo '</form>';
				echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('rn','page','mnu','info')));
				?>
				</span>
				</div>
				<?php } ?>
</td>
      </tr>
	  <tr><td>
	  <div id="review_result1" style="width: 720px;float: left; margin-left:10px; display:inline;"></div> 			
	  </td></tr>
	  <tr>
	  	<td>			
		<?php
  if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true') {
    
	if (isset($HTTP_GET_VARS['articles_id'])) {
//echo '<a name="friend"></a>';
	 
	if (!tep_session_is_registered('customer_id')) 
	{
    	$tell_a_friend_text = TEXT_TELL_A_FRIEND . '<br />&nbsp;' .FORM_FIELD_FRIEND_EMAIL. tep_draw_input_field('to_email_address', '', 'size="28" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">*</span>' . '&nbsp;' .FORM_FIELD_CUSTOMER_EMAIL. tep_draw_input_field('from_email_address', '', 'size="28" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">*</span>' . '&nbsp;' . tep_draw_hidden_field('articles_id', $HTTP_GET_VARS['articles_id']) . tep_hide_session_id() . '<img style="cursor: pointer; cursor: hand; " src="image/buttons/tchinese/button_tell_a_friend.gif" border="0" alt="Write Review" onclick="sendFormData(\'email_friend_article\',\''.tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, "action=process_friend&articles_id=" . $HTTP_GET_VARS['articles_id']).'\',\'review_result1\',\'true\');"  title=" Write Review " />' ;
		//tep_template_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND)
		//
	}
	else
	{
	$account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $account = tep_db_fetch_array($account_query);
    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
	
		$tell_a_friend_text = TEXT_TELL_A_FRIEND . '<br />&nbsp;' .FORM_FIELD_FRIEND_EMAIL. tep_draw_input_field('to_email_address', '', 'size="28" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' . '&nbsp;' .FORM_FIELD_CUSTOMER_EMAIL. tep_draw_input_field('from_email_address', $from_email_address, 'size="28" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' . '&nbsp;' . tep_draw_hidden_field('articles_id', $HTTP_GET_VARS['articles_id']) . tep_hide_session_id() .  '<img style="cursor: pointer; cursor: hand; " src="image/buttons/tchinese/button_tell_a_friend.gif" border="0" alt="Write Review" onclick="sendFormData(\'email_friend_article\',\''.tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, "action=process_friend&articles_id=" . $HTTP_GET_VARS['articles_id']).'\',\'review_result1\',\'true\');"  title=" Write Review " />'  ;
	}	
	
      $info_box_contents1 = array();
      $info_box_contents1[] = array('text' => BOX_TEXT_TELL_A_FRIEND);

      new infoBoxHeading($info_box_contents1, false, false);

      $info_box_contents1 = array();
      $info_box_contents1[] = array('form' => tep_draw_form('email_friend_article','','','id="email_friend_article"'),
                                   'align' => 'left',
                                   'text' => $tell_a_friend_text);

      new infoBox($info_box_contents1);
    }
$info_box_contents1 = array();
  $info_box_contents1[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents1, true, true);
  }
?>
	</td></tr>	
				
					