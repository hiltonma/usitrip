<?php echo tep_draw_form('product_queston_write', tep_href_link('tour_question.php', 'action=process'), 'post', 'id="frm_product_queston_write"'); //onSubmit="return checkForm();" ?>

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

					<?php
					  if ($messageStack->size('tour_question') > 0) {
					?>
						  <tr>
							<td><?php echo $messageStack->output('tour_question'); ?></td>
						  </tr>
						  <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
						  </tr>
					<?php
					  }
					?>

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
									
									<td ><?php echo tep_draw_input_field('customers_name',db_to_html(ucfirst($customer_first_name)),'  class="required" id="customers_name" title="'.TEXT_YOUR_FNAME_ERROR.'" style="width: 35%;"'); ?><span class="sp1"> * </span></td>
									</tr>
							    

									<tr>
									<td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL; ?></td>
									
									<td><?php echo tep_draw_input_field('customers_email',tep_get_customers_email($customer_id),'class="required validate-email" id="customers_email" title="'.TEXT_YOUR_EMAIL_ERROR.'" style="width: 35%;"'); ?><span class="sp1"> * </span></td></tr>
							    
								 	<tr><td height="19" class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL_CONFIRM; ?></td>
									
									<td><?php echo tep_draw_input_field('c_customers_email',tep_get_customers_email($customer_id),' class="required validate-email-confirm-que" title="'.TEXT_YOUR_EMAIL_CONFIRM_ERROR.'" style="width: 35%;" id="c_customers_email"'); ?><span class="sp1"> * </span></td></tr>
							   
								

								    <tr><td height="22" class="sp10 sp6"><?php echo TEXT_YOUR_QUESTION;?></td>
							  		<td nowrap><?php echo tep_draw_textarea_field('question', 'soft', '', '','',' class="required "  style="width: 400px; height: 116px; " id="question" title="'.TEXT_YOUR_COMMENT_ERROR.'"'); ?><span class="sp1"> * </span></td></tr>
								    
									
									 <tr><td><input type="hidden" name="cPath" value="<?php echo $cPath;?>">
												<input type="hidden" name="products_id" value="<?php echo $HTTP_GET_VARS['products_id'];?>"></td><td><span class="sp1"><?php echo TEXT_NOTE; ?></span>&nbsp;&nbsp;</td></tr>
								
								<tr>
								      <td height="22" class="sp10 sp6"><?php echo db_to_html('接受走四方网的旅游资讯:')?></td>
							      <td nowrap>
								    <input name="accept_newsletter" type="radio" value="1" checked> 
									  <?php echo db_to_html('是')?>&nbsp;&nbsp;&nbsp;&nbsp;
									  <input type="radio" name="accept_newsletter" value="0"> <?php echo db_to_html('否')?>
								  </td>
					      		</tr>
								
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
								if($HTTP_GET_VARS['action'] != 'process'){
									echo '<a href="' . tep_href_link('all_question_answers.php') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
								}else{
									echo '<a href="JavaScript:window.history.go(-1);">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
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
				return (v == $F('customers_email'));
			});
</script>