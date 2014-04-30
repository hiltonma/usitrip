<?php //echo tep_get_design_body_header(HEADING_TITLE,1); ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="2" cellpadding="2" style="border:1px solid #AED5FF">
							  <tr>
								<td>
								<?php
								require('includes/rewards4fun_page_navi.php');
								?>
								</td>
							  </tr>
							  <tr>
								<td class="main"><?php echo INTRO_TEXT; ?></td>
							  </tr>
							   <tr>
							   	<td class="main">
									<?php
									if(isset($HTTP_GET_VARS['msg']) && $HTTP_GET_VARS['msg']=='success'){
										?>
										<table border="0" width="100%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">
											<tr class="messageStackSuccess">
											<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo ENTER_URL_SUCCESS_MESSAGE; ?></td>
											</tr>
										</table>
										<?php
									}
									?>
									<?php echo tep_draw_form('feedback_approval', tep_href_link(FILENAME_FEEDBACK_APPROVAL, 'action=process', 'SSL'), 'post', ' id="feedback_approval"'); ?>
									<table width="100%" align="left" border="0" cellpadding="2" cellspacing="2">										
									   <tr>
									   <style type="text/css">
											.validation-advice{ display:inline-block; background-position:0 center;}
									   </style>
										<td class="main">									
											<?php echo ENTRY_URL_LINK .'<br />'. tep_draw_input_field('feedback_link_url','','id="feedback_link_url" class="required validate-url text" title="'.TEXT_CHECK_URL_ERROR.'"  size="70"') .'&nbsp;'. TEXT_HTTP_REQUIRED; ?>									
										</td>
									   </tr>
									   <tr>
										<td class="main">									
											<?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
										</td>
									   </tr>
									</table>
									<?php echo '</form>'; ?>
								</td>
							   </tr>
							  
							</table><!-- content main body end -->
							<script type="text/javascript">
function formCallback(result, form) {
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}

var valid = new Validation('feedback_approval', {immediate : true,useTitles:true, onFormValidate : formCallback});						
</script>		
<?php echo tep_get_design_body_footer();?>