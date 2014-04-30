<?php
require_once("includes/application_top.php");
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_ANSWER_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);


if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
		    $value2 = iconv('utf-8',CHARSET.'//IGNORE',$value2);
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));   
			//echo "$key=>$value2<br>";  	   
		  }
		}
		
}	

$success = false;
$error = false;
if(isset($HTTP_GET_VARS['cPath']) &&  $HTTP_GET_VARS['cPath'] != ''){
 $cPath =  $HTTP_GET_VARS['cPath'];
}
if(isset($HTTP_GET_VARS['mnu']) &&  $HTTP_GET_VARS['mnu'] != ''){
 $mnu =  $HTTP_GET_VARS['mnu'];
}
if(isset($HTTP_GET_VARS['products_id']) &&  $HTTP_GET_VARS['products_id'] != ''){
 $products_id =  $HTTP_GET_VARS['products_id'];
}
if(isset($HTTP_GET_VARS['que_id']) &&  $HTTP_GET_VARS['que_id'] != ''){
 $que_id =  $HTTP_GET_VARS['que_id'];
}


 if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {

    $replay_name = $HTTP_POST_VARS['replay_name'];

	$replay_email = $HTTP_POST_VARS['replay_email'];
	
	$c_replay_email = $HTTP_POST_VARS['c_replay_email'];
	
	$anwers = $HTTP_POST_VARS['anwers'];
	
	
	if($replay_name == ''){
	  $error = true;
	  $error_msg .= TEXT_ERROR_MSG_YOUR_NAME;
	}
	
	if($replay_email == ''){
	  $error = true;
	  $error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL;
	}else if($replay_email != $c_replay_email){
	 	  $error = true;
		  $error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL_CONFIRMATION;
	}else {		
		if(is_CheckvalidEmail($replay_email) != true){
		  $error = true;
		  $error_msg .= TEXT_ERROR_MSG_VALID_EMAIL;
		}
	}
	
	
	
	if (strlen($anwers) == 0) {
      $error = true;
	  $error_msg .= TEXT_ERROR_MSG_YOUR_ANSWERS;
      //$messageStack->add('review', JS_REVIEW_TEXT);
    }
	
	$replay_name = ucfirst(substr($replay_name, 0, 1)).substr($replay_name, 1, strlen($replay_name));
	
   

	 if ($error == false) {
      tep_db_query(html_to_db ("insert into " . TABLE_QUESTION_ANSWER . " (que_id,ans,date,replay_name,replay_email,languages_id) values ('" . (int)$que_id . "', '" .  tep_db_input($anwers) ."', now(),'" . tep_db_input($replay_name) . "','" . tep_db_input($replay_email) . "','" . (int)$languages_id . "')"));
	  $ans_insert_id = tep_db_insert_id();
	  
	  #### Points/Rewards Module V2.1rc2a BOF ####*/
		 if(isset($customer_id) && $customer_id!=''){
			if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_ANSWER))) {
				$points_toadd = USE_POINTS_FOR_ANSWER;
				$comment = 'TEXT_DEFAULT_ANSWER';
				$points_type = 'QA';
				tep_add_pending_points($customer_id, (int)$que_id, $points_toadd, $comment, $points_type, '', (int)$products_id);
			}
		 }
	  #### Points/Rewards Module V2.1rc2a EOF ####*/
	  
	  $success = true;
	 
				$findcustomerdata = tep_db_query("select * from " . TABLE_QUESTION . " where que_id = '".(int)$que_id ."' and  languages_id='" . (int)$languages_id . "'");
				if($customerdata = tep_db_fetch_array($findcustomerdata)) {
					$c_email = $customerdata["customers_email"];
					$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)).substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
					
					$subject="您在usitrip.com提交的问题得到回复啦！";
					$message = db_to_html("<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
							"<tr>".
							"<td valign='top' colspan='2'>您好，$c_name,</td>".
							"</tr>".
							"<tr>".
							"<td valign='top' title='".$customerdata['question']."'></br>").$replay_name.db_to_html(" 已经就您提出关於 <b>".cutword($customerdata['question'],100)."</b> 的问题做了回复，请您点击以下地址以便查看我们的回复：</td>".
							"</tr>".
							"<tr>".
							"<td valign='top'><a href=".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda").">".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda")."</a></td>".
							"</tr>".
							"<tr>".
							"<td valign='top'><br><br>谢谢<br><a href='".HTTP_SERVER."'>208.109.123.18</a></td>".
							"</tr>".	
							"</table>");
// zhh fix and added
					
					$to_name = db_to_html($c_name);
					$to_email_address = $c_email;
					$email_subject = db_to_html($subject);
					$email_text = $message;
					
					$from_email_name = db_to_html(STORE_OWNER);
					$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
					
					$product_link_page = '<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath&mnu=qanda&".tep_get_all_get_params(array('info','mnu','rn')),'NONSSL',false,true,"qanda").'">'.tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath&mnu=qanda&".tep_get_all_get_params(array('info','mnu','rn')),'NONSSL',false,true,"qanda").'</a>';
					
					//howard added new eamil tpl
					$patterns = array();
					$patterns[0] = '{CustomerName}';
					$patterns[1] = '{images}';
					$patterns[2] = '{HTTP_SERVER}';
					$patterns[3] = '{HTTPS_SERVER}';
					$patterns[4] = '{ProductInfoPage}';
					$patterns[5] = '{EMAIL}';
					$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';

					$replacements = array();
					$replacements[0] = $to_name;
					$replacements[1] = HTTP_SERVER.'/email_tpl/images';
					$replacements[2] = HTTP_SERVER;
					$replacements[3] = HTTPS_SERVER;
					$replacements[4] = $product_link_page;
					$replacements[5] = $to_email_address;
					$replacements[6] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
					
					$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
					$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/question_and_answer.tpl.html');
					$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
					
					$email_text = str_replace( $patterns ,$replacements, db_to_html($email_tpl));
					$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
					//howard added new eamil tpl end
					
					//tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
					
					//howard add use session+ajax send email
					$_SESSION['need_send_email'] = array();
					$_SESSION['need_send_email'][0]['to_name'] = $to_name;
					$_SESSION['need_send_email'][0]['to_email_address'] = $to_email_address;
					$_SESSION['need_send_email'][0]['email_subject'] = $email_subject;
					$_SESSION['need_send_email'][0]['email_text'] = $email_text;
					$_SESSION['need_send_email'][0]['from_email_name'] = $from_email_name;
					$_SESSION['need_send_email'][0]['from_email_address'] = $from_email_address;
					$_SESSION['need_send_email'][0]['action_type'] = 'true';
					$_SESSION['need_send_email'][0]['email_charset'] = CHARSET;
					
					//howard add use session+ajax send email end

				}
			   // send mail to merchant  --scs Bof
			   echo 'question_answer_new_added|###|';
			   ?>
			   <table border="0" width="98%" align="center" id="success_qa_ans_fad_out_id" class="automarginclass" cellspacing="2" cellpadding="2">
				<tr class="messageStackSuccess">
				<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo TEXT_REPLAY_SEND;?></td>
				</tr>
				</table>
					<div class="pr_b_a">
					<div class="pr_b_a_1 sp10 sp6">
					    <table  border="0" cellpadding="0" cellspacing="0">
					      <tr height="25" ><td width="25" ><div class="pr_q2_q sp1">A:</div></td>
						  <td nowrap width="90%">
						  <span class="sp1"><?php echo tep_db_prepare_input($replay_name);?></span><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?><font color="#B7E3FB">|</font><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?><?php echo sprintf(tep_date_long_review(date('Y-m-d H:i:s')));?></td>					     
						  <td valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '80', '1'); ?></td>
				      </tr></table>
					</div>
					<div class="pr_b_t_a sp10 sp6" ><?php echo tep_output_string_protected(tep_db_prepare_input($anwers)); ?></div>
					</div>	                 			
					<?php 			
  	}else { 
	?>	
			<table width="94%" style="margin-left:25px;"  border="0" cellspacing="5" cellpadding="5">
			  <tr>
				<td bgcolor="#FFE1E1" class="main"><?php echo $error_msg; ?></td>
			  </tr>
			</table>
	<?php
	} // end of  if error fales;

}
?>
<?php 
if($success != true){
?>
<?php 
$write_form_display = ''; 
if(!isset($customer_id)) { 
	if(!isset($customer_review_process_without_login)){
		$write_form_display = 'none'; 
	?>
	 <div class="sp10 sp6" id="div_singin_or_without_signin" style="float:left; margin-left:10px; margin-bottom:10px;">
			<a style="CURSOR: pointer" onclick="javascript:sendFormData('product_question_answer_write_<?php echo $que_id; ?>', '<?php echo tep_href_link('reward_login_ajax.php', 'action=show_login_form&successtoggeldiv=write_question_answer_form_id1_'.$que_id.'');?>','sign_in_form_id_<?php echo $que_id; ?>','true');" class="sp3" title="Click here to Sign-in"><?php echo TEXT_SIGN_IN; ?></a> <?php echo TEXT_AJAX_SIGN_IN_STRING_ANSWER; ?>  <a href="javascript: void(0);" onClick="javascript:toggel_div_show('write_question_answer_form_id1_<?php echo $que_id; ?>'); sendFormData('product_question_answer_write_<?php echo $que_id; ?>','<?php echo tep_href_link('reward_login_ajax.php', 'action=process_without_login');?>','sign_in_form_id_<?php echo $que_id; ?>','true');" class="sp3"><?php echo TEXT_AJAX_WITHOUT_SIGN_IN_STRING; ?></a>
	</div>		
	<div class="pr_b_form_1" id="sign_in_form_id_<?php echo $que_id; ?>" style="width:88%; margin-left:28px; "> </div>
	
	<?php 
	}
}						
//include_once('reward_login_link.php');
?>
<div class="pr_b_form_1" id="write_question_answer_form_id1_<?php echo $que_id; ?>" style="display:<?php echo $write_form_display; ?> ">
<?php 
echo tep_draw_form('product_question_answer_write_'.$que_id, '' ,"",'id =product_question_answer_write_'.$que_id);
?>
			<?php
			echo tep_draw_hidden_field('tab_name',TEXT_INPUT_TAB_NAME_VALUE_ANSWER);
			echo tep_draw_hidden_field('response_div_name','sign_in_form_id_'.$que_id.'');
	    	?>			
			<table border="0" width="90%" class="automarginclass" align="center" cellspacing="1" cellpadding="2" >			
				<tr>
					<td height="15"></td>
				</tr>
				<tr>
					<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="main"><b><?php //echo TEXT_QUESTION_AND_ANSWERS; ?></b></td>
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
								
									<tr><td width="20%"  class="sp10 sp6"><?php echo TEXT_YOUR_NAME; ?> </td><td><?php echo tep_draw_input_field('replay_name', stripslashes($HTTP_POST_VARS['replay_name']),' class="pr_b_text_per"  style="width: 40%;"'); ?><span class="sp1">*</span></td></tr>
							    
									<tr><td  class="sp10 sp6" nowrap><?php echo TEXT_YOUR_EMAIL; ?> </td><td><?php echo tep_draw_input_field('replay_email', stripslashes($HTTP_POST_VARS['replay_email']),' class="pr_b_text_per"  style="width: 40%;"'); ?><span class="sp1">* <?php echo TEXT_REQUERED_NOT_DISPLAYED;?></span></td></tr>
							    
									<tr><td  class="sp10 sp6"><?php echo TEXT_YOUR_EMAIL_CONFIRM; ?> </td><td><?php echo tep_draw_input_field('c_replay_email', stripslashes($HTTP_POST_VARS['c_replay_email']),' class="pr_b_text_per"  style="width: 40%;"'); ?><span class="sp1">*</span></td></tr>
							    
								    <tr><td  class="sp10 sp6" nowrap><?php echo TEXT_YOUR_ANSWERS;?> </td><td><?php echo tep_draw_textarea_field('anwers', 'soft', '', '', stripslashes($HTTP_POST_VARS['anwers']),' class="pr_b_text_1_per"  style="width: 95%;"'); ?><span class="sp1">* </span></td></tr>
							    
								  <tr><td></td><td ><span class="sp1"><?php echo TEXT_NO_HTML; ?></span>&nbsp;&nbsp;</td></tr>
								
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
								<?php // echo tep_image_submit('button_submit_answer.gif', IMAGE_BUTTON_CONTINUE); ?>								
								 <img style="cursor: pointer; cursor: hand; " src="image/buttons/<?php echo $language; ?>/button_submit_answer.gif" border="0" alt="<?php echo IMAGE_BUTTON_CONTINUE; ?>" onclick="sendFormData('product_question_answer_write_<?php echo $que_id; ?>','<?php echo tep_href_link('tour_question_answer_write_ajax.php', 'action=process&que_id='.$que_id.'&cPath='.$cPath.'&products_id=' . $products_id);?>','div_question_answer_form_<?php echo $que_id;?>','true');"  title=" <?php echo IMAGE_BUTTON_CONTINUE; ?> ">
								 <input type="hidden" name="action_answer_rt" value="create_form">
						 		<input type="hidden" name="qaanscall" value="true">
								</td>								
								<td class="main" align="right">
								 <img style="cursor: pointer; cursor: hand; " src="image/buttons/<?php echo $language; ?>/button_cancel.gif" border="0" alt="<?php echo 'Cancel'; ?>" onclick="javascript:toggel_div('div_question_answer_form_<?php echo $que_id;?>');"  title=" <?php echo 'Cancel'; ?> ">
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
</form>
<?php 
}
?>
