<?php echo tep_draw_form('product_queston_write', tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'action=process'), 'post', 'id="frm_product_queston_write"'); //onSubmit="return checkForm();" ?>

 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  
  <?php if((int)$products_id){?>
  <tr >
    <td class="pageHeading" width="90%"><?php echo db_to_html($products_name); ?></td>
	
    <td class="pageHeading" width="10%" nowrap align="right"><?php echo $products_price; ?></td>
  </tr>
  <?php }?>
  
   <tr>
    <td colspan="2">
		<table width="100%" class="mainbodybackground" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		   <td width="15"></td>
			<td>
			
			<table border="0" width="80%" class="automarginclass" align="center" cellspacing="1" cellpadding="2" >
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="main"><b><?php echo TEXT_PRODUCTS_QUESTION_ANSWERS; ?></b></td>
					   <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
					  </tr>
					</table></td>
				  </tr>
				<tr>
					<td>
					  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
					  <tr class="infoBoxContents">
					  <td>
					  
					  
					  
					  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
								
								 <?php 
								
									if($send == 'true'){
									?>
									<tr>
						
									<td  colspan="3" class="main"><b><?php echo TEXT_NO_QUESTION_ADDED_SUCCESS;?></b></td>
						
									</tr>
									<?php
									}else{
									
								?>
								
					    	   		<tr>
									<td  width="25%" class="sp10 sp6"><?php echo TEXT_YOUR_FNAME; ?> </td>
									
									<td ><?php echo tep_draw_input_field('lead_fname',ucfirst(db_to_html($customer_first_name)),'  class="required" id="lead_fname" title="'.TEXT_YOUR_FNAME_ERROR.'" style="width: 35%;"'); ?><span class="sp1"> * </span></td>
									</tr>
							    
									<!--英文姓名<tr>
									<td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_LNAME; ?> </td>
									
									<td><?php echo tep_draw_input_field('lead_lname',ucfirst($customer_last_name),' class="required" id="lead_lname" title="'.TEXT_YOUR_LNAME_ERROR.'" style="width: 35%;"'); ?><span class="sp1"> * </span></td>
									</tr>-->
							    
									<tr>
									<td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL; ?></td>
									
									<td><?php echo tep_draw_input_field('lead_email',tep_get_customers_email($customer_id),'class="required validate-email" id="lead_email" title="'.TEXT_YOUR_EMAIL_ERROR.'" style="width: 35%;"'); ?><span class="sp1"> * </span></td></tr>
							    
									<tr><td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL_CONFIRM; ?></td>
									
									<td><?php echo tep_draw_input_field('c_lead_email',tep_get_customers_email($customer_id),' class="required validate-email-confirm-que" title="'.TEXT_YOUR_EMAIL_CONFIRM_ERROR.'" style="width: 35%;" id="c_lead_email"'); ?><span class="sp1"> * </span></td></tr>
							    
								
									<tr><td height="19" class="sp10 sp6"><?php echo db_to_html('联系电话:');  //echo TEXT_YOUR_DAY_PHONE; ?></td>
					           		<td><?php echo tep_draw_input_field('lead_dayphone','',' class="required"  style="width: 35%;" id="lead_dayphone" title="'.TEXT_YOUR_DAY_PHONE_ERROR.'"'); ?><span class="sp1"> * </span></td></tr>
							    
									<!--晚间联系电话
									<tr><td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_EVENING_PHONE; ?></td>
					           		<td><?php echo tep_draw_input_field('lead_eveningphone','',' class="required" title="'.TEXT_YOUR_EVENING_PHONE_ERROR.'" style="width: 35%;" id="lead_eveningphone"'); ?><span class="sp1"> * </span></td></tr>-->
							    
									<tr><td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_BEST_TIME_CALL; ?></td>
					           		<td><?php echo tep_draw_input_field('lead_besttimetocall','',' class="pr_b_text_per"  style="width: 35%;"'); ?></td></tr>
									
									<tr><td height="19" class="sp10 sp6"><?php echo db_to_html('参加人数:'); ?></td>
					           		<td><?php echo tep_draw_input_field('lead_guest_num','',' class="pr_b_text_per"  style="width: 35%;"'); ?></td></tr>
									
									<tr><td height="19" class="sp10 sp6"><?php echo db_to_html('是否持有美国/加拿大签证:'); ?></td>
					           		<td><?php echo tep_draw_input_field('lead_have_visa','',' class="pr_b_text_per"  style="width: 35%;"'); ?></td></tr>
									
									<tr><td height="19" class="sp10 sp6"><?php echo db_to_html('预计出发日期(mm/dd/yy):'); ?></td>
					           		<td><?php echo tep_draw_input_field('lead_departure_date','',' class="pr_b_text_per"  style="width: 35%;"'); ?></td></tr>
									
									<tr><td height="19" class="sp10 sp6"><?php echo db_to_html('预计行程天数:'); ?></td>
					           		<td><?php echo tep_draw_input_field('lead_tour_day_num','',' class="pr_b_text_per"  style="width: 35%;"'); ?></td></tr>
								
								    <tr><td height="22" class="sp10 sp6"><?php echo TEXT_YOUR_QUESTION;?></td>
							  		<td nowrap><?php echo tep_draw_textarea_field('lead_comment', 'soft', '', '','',' class="required "  style="width: 70%; height: 116px; " id="lead_comment" title="'.TEXT_YOUR_COMMENT_ERROR.'"'); ?><span class="sp1"> * </span></td></tr>
									
									 <tr><td><input type="hidden" name="cPath" value="<?php echo $cPath;?>">
												<input type="hidden" name="products_id" value="<?php echo $HTTP_GET_VARS['products_id'];?>"></td><td><span class="sp1"><?php echo TEXT_NOTE; ?></span>&nbsp;&nbsp;</td></tr>
								
								<?php } ?>
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
								<td  class="main" align="left">
								
								<?php
								if((int)$products_id){
									echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
								}else{
									echo '<a href="' . tep_href_link('index.php') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
								}
								?>
								
								</td>
								<td  class="main" align="right" >
								<?php if($send != true){ 
									echo tep_image_submit('button_submit_question.gif', IMAGE_BUTTON_CONTINUE); } ?>
								</td>
								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
							  </tr>
							</table></td>
						  </tr>
						</table>
					
				</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				
				</table>
			
			
			</td>
			<td width="15"></td>
		  </tr>
		  
		</table>
		
		
	
	</td>
  </tr>
   <tr>
  <td height="5"></td>
  </tr>
  
</table>
</form>
<script type="text/javascript">
		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
		}
		
		var valid = new Validation('frm_product_queston_write', {immediate : true,useTitles:true, onFormValidate : formCallback});
			
		Validation.add('validate-email-confirm-que', 'Your confirmation email does not match your first email, please try again.', function(v){
				return (v == $F('lead_email'));
			});
</script>