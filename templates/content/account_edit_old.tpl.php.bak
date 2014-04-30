
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">

							  <tr>
								<td class="main">

												<?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', ' id="account_edit" onSubmit="check_form(\'account_edit\'); return false;"') . tep_draw_hidden_field('action', 'process'); //onsubmit="return check_form(account_edit);" ?>
													<table border="0" width="75%" class="automarginclass"  align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">

												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_top(false, false, $header_text);
												}
												// EOF: Lango Added for template MOD
												?>

												<?php
												  if ($messageStack->size('account_edit') > 0) {
												?>
													  <tr>
														<td><?php echo $messageStack->output('account_edit'); ?></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
												<?php
												  }
												?>
													  <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
														  <tr>
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
																<td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
															  </tr>
															</table></td>
														  </tr>
														  <tr>
															<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
															  <tr class="infoBoxContents">
																<td><table border="0" cellspacing="2" cellpadding="2">
												<?php
												  if (ACCOUNT_GENDER == 'true') {
													if (isset($gender)) {
													  $male = ($gender == 'm') ? true : false;
													} else {
													  $male = ($account['customers_gender'] == 'm') ? true : false;
													}
													$female = !$male;
												?>
																  <tr>
																	<td class="main"><?php echo ENTRY_GENDER; ?></td>
																	<td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
																  </tr>
												<?php
												  }
												?>
												<?php if(tep_not_null($pointcards_id_string)){ ?>
																 <tr>
																	<td class="main"><?php echo db_to_html('会员卡号:'); ?></td>
																	<td class="main"><?php echo $pointcards_id_string;?></td>
																  </tr>
												<?php }?>
																  <tr>
																	<td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('firstname', db_to_html($account['customers_firstname']),' id="firstname" class="required validate-length-firstname" title="'.ENTRY_FIRST_NAME_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
																  </tr>
																  <tr>
																	<td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('lastname', db_to_html($account['customers_lastname']),'id="lastname" class="required validate-length-lastname" title="'.ENTRY_LAST_NAME_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
																  </tr>
												<?php
												  if (ACCOUNT_DOB == 'true') {
												?>
																  <tr>
																	<td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob'])) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
																  </tr>
												<?php
												  }
												?>
																  <tr>
																	<td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('email_address', $account['customers_email_address'],'id="email_address" class="required validate-email" title="'.ENTRY_EMAIL_ADDRESS_CHECK_ERROR.'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
																  </tr>
																  <tr>
																	<td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
																	<td class="main" nowrap="nowrap"><?php
																	if(eregi('-',$account['customers_telephone'])){
																	$customers_telephone_array = explode('-',$account['customers_telephone']);
																	}else{
																	$customers_telephone_array[1] = $account['customers_telephone'];
																	}

																	echo ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE.tep_draw_input_field('telephone_cc',$customers_telephone_array[0],'size="3" style="ime-mode:disabled" ').' - '.tep_draw_input_field('telephone', $customers_telephone_array[1],'id="telephone" class="required validate-number validate-length-telephone" title="'.ENTRY_TELEPHONE_NUMBER_ERROR.'"  style="ime-mode:disabled" ').'&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': '');
																	?>
																	</td>
                                                                                                                                        
																  </tr>
																   <tr>
																	<td class="main"><?php echo ENTRY_MOBILE_PHONE; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('mobile_phone', $account['customers_mobile_phone'],' style="ime-mode:disabled" '); ?></td>
																  </tr>
																  <tr>
																	<td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
																	<td class="main"><?php echo tep_draw_input_field('fax', $account['customers_fax'],' style="ime-mode:disabled" ') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
																  </tr>
                                                                  <tr>
                                                                  <?php if($account['customers_notice']){$notice = 1;$noticenot=0;}else{$notice = 0;$noticenot=1;}?>
																	<td class="main"><?php echo db_to_html('活动通知:'); ?></td>
																	<td class="main"><?php echo tep_draw_radio_field('customers_notice','1',$notice).'&nbsp';echo db_to_html('是').'&nbsp;&nbsp;&nbsp;';echo tep_draw_radio_field('customers_notice','0',$noticenot).'&nbsp';echo db_to_html('否')?></td>
																  </tr>
<?php
$bind_confirmphone = false;
if(tep_not_null($account['confirmphone'])){
	$bind_confirmphone = true;
}
if($bind_confirmphone){
	?>                                                                                                                        <td class="main"><?php echo db_to_html('已绑手机:'); ?></td>
																      <td class="main"><?php echo tep_draw_input_field('confirmphone_in_db', $account['confirmphone'],' style="ime-mode:disabled" disabled="disabled" ') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></td>
																      </tr>
                                                                                                                                    <?php }else{?>
                                                                                                                                         <tr>
                                                                                                                                        <td class="main"><?php echo db_to_html('已绑手机:'); ?></td>
                                                                                                                                        <td class="main"><font color="red"><?php echo db_to_html('你还未绑定手机')?></font></td>
                                                                                                                                   <?php }?>
                                                                                                                                  
                                                                                                                                  
																</table></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_bottom();
												}
												// EOF: Lango Added for template MOD
												?>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
                                                                                                         <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="2" cellpadding="2">

															  <tr>
                                                                                                                          <td class="main"><?php echo db_to_html('绑定手机:').'&nbsp;&nbsp;'.tep_draw_input_field('confirmphone','','id="confirmphone" size=10 style="ime-mode:disabled" onBlur="check_field(\'account_edit\',\'confirmphone\', \'chk-confirmphone\');"').'<input type="button" id="impwd" onclick="get_rndpwd_edit()" value="'.db_to_html('获取密码').'">&nbsp;<span id="pwd_send_edit" class=""></span>';?></td>
                                                                                                                          
                                                                                                                          
                                                                                                                          </tr>
                                                                                                                          <tr>
                                                                                                                          <td class="main"><?php echo db_to_html('验证码:').'&nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_input_field('yanzhengma2','','id="yanzhengma2" size=4 style="ime-mode:disabled"  onBlur="check_field(\'account_edit\',\'yanzhengma2\', \'chk-yanzhengma2\');"') . '&nbsp;' .'<span class="inputRequirement"></span>'?><span id="chk-yanzhengma2" class="create_default"></span></td>
                                                                                                                         
                                                                                                                          </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>

														<tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													    </tr>

													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
																<td align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>&nbsp;<span id="submit_msn2" style="color:#FF6600; display:<?php echo 'none';?>"></span></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
													</table></form>
							  </td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>


							</table><!-- content main body end -->
							<script type="text/javascript">
		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
		}

		var valid = new Validation('account_edit', {immediate : true,useTitles:true, onFormValidate : formCallback});

		Validation.addAllThese([
							['validate-length-firstname', '', {
								minLength : <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>
							}],
							['validate-length-lastname', '', {
								minLength : <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>
							}],
							['validate-length-telephone', '', {
								minLength : <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>
							}]
						]);
	</script>


