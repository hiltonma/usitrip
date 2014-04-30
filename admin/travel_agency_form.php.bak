<?PHP
  /*
  Module: Information Pages Unlimited
  		  File date: 2003/03/02
		  Based on the FAQ script of adgrafics
  		  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */
?>

<?php 
$extra_update_button = "";
if(isset($_GET['agency_id']) && tep_not_null($_GET['agency_id']) && $_GET['adgrafics_information']=='Edit'){
	$extra_update_button = tep_image_submit('button_update.gif', IMAGE_UPDATE);
}
if($display_delete_confirmation_form=="1"){
	$title=str_replace('Edit', 'Delete', $title);
}?>

<tr><td align="right">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td colspan="2"><table width="100%"><tr><td align="left" class="pageHeading"><?php echo $title ?></td><td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('agency_id','adgrafics_information')), 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_CANCEL) . '</a>'; ?></td></tr></table></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<?php 
//Start - Confirmation for delete providers account
if($display_delete_confirmation_form=="1"){?>
	<tr>
		<td colspan="2" class="formArea">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
				<tr id="tr_psw">
					<td class="main">&nbsp;</td>
					<td class="main"><?php echo '<br />'.TXT_WARNING_DELETE_PROVIDER.tep_draw_input_field('btnDeleteConfirmed', DELETE_INFORMATION, "", false, "submit").'&nbsp; &nbsp;'.tep_draw_input_field('btnDeleteCancel', TXT_CANCEL_DEL, "", false, "submit").'<br /><br />';?></td>
				</tr>
			</table>
		</td>
	</tr>
<?php 
//End - Confirmation for delete providers account
}else{?>
<?php //!-- Added for login deatil - Start--?>
<tr>
	<td class="formAreaTitle"><?php echo TEXT_LOGIN_DETAIL; ?></td>
</tr>
<tr>
  <td  colspan="2" class="formArea">
	<?php
 	    $select_check_providers_email_notification_sql = "SELECT providers_email_notification FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_agency_id ='".$agency_id."' and parent_providers_id=0";
		$select_check_providers_email_notification_res = tep_db_query($select_check_providers_email_notification_sql);
		$select_check_providers_email_notification_row = tep_db_fetch_array($select_check_providers_email_notification_res);
		$email_notification_value = $select_check_providers_email_notification_row['providers_email_notification'];
		if($email_notification_value=='0'){$email_notification='';}else{$email_notification='true';}
	?>
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr id="tr_email">
			<td width="20%" class="main"><?php echo TEXT_PROVIDERS_EMAIL;?><br></td>
			<td width="80%" class="main"><?php echo tep_draw_input_field('providers_email_address', $edit['providers_email_address'], 'size="30"', false);?>&nbsp;&nbsp;&nbsp;<?php echo tep_draw_checkbox_field('providers_email_notification','',$email_notification).TXT_EMAIL_NOTICE;?></td>
		</tr>
		<tr id="tr_psw">
			<td class="main">&nbsp;</td>
			<td class="main"><?php ($edit['providers_email_address']=="")?$val=IMAGE_CREATE:$val=IMAGE_RESET_PSW;
				echo tep_draw_input_field('btnPassword', $val, "", false, "submit");
				echo ($edit['providers_email_address']=="")?'':'&nbsp; &nbsp;'.tep_draw_input_field('btnDeleteAccount', TXT_DEL_PROVIDERS_ACC, "", false, "submit");?></td>
		</tr>
		<tr height="10px"><td></td></tr>
		<?php //check for sub account
		$select_check_prod_sub_account_sql="SELECT providers_email_address, providers_email_notification FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_agency_id ='".$agency_id."' and parent_providers_id > 0 ";
		$select_check_prod_sub_account_query=tep_db_query($select_check_prod_sub_account_sql);
		if(tep_db_num_rows($select_check_prod_sub_account_query)>0) {
		?>
		 <tr>
			<td class="main" valign="top"><?php echo TEXT_PROVIDERS_SUB_ACCOINTS;?><br></td>
			<td class="main">
			
			<table width="100%" border="0" cellspacing="1" cellpadding="2">
                   	 <tr class="dataTableHeadingRow"><td class="main"><strong>Users</strong></td><td style="padding-left:10px" class="main"><strong><?php echo TXT_EMAIL_NOTICE?></strong></td></tr>
				<?php
				$sub_i = 1;
				while($select_check_prod_sub_account=tep_db_fetch_array($select_check_prod_sub_account_query)){
				if($select_check_prod_sub_account['providers_email_notification']==0)
				{$emil_notice='No';}
				else
				{$emil_notice='Yes';}
				echo "<tr><td class=\"main\">".$sub_i.'. '.$select_check_prod_sub_account['providers_email_address']."</td><td style=\"padding-left:10px\"  align=\"left\" class=\"main\">".$emil_notice."</td></tr>";
				$sub_i++;
				}				
				?>
			 
			</table>
			
			</td>
		</tr>	
		<tr height="10px"><td></td></tr>
		<?php
		} //sub account display
		?>
		<tr>
			<td class="main"><?php echo TEXT_PROVIDERS_START_DATE;?><br></td>
			<td class="main">
				<?php 
					if($error && $_POST['providers_start_date'] !=''){
						$edit['providers_start_date']=tep_get_date_db($_POST['providers_start_date']);
					}
					if(trim($edit['providers_start_date'])=="" || $edit['providers_start_date']=="0000-00-00"){
						$providers_start_date="";
					}else{
						$providers_start_date=tep_get_date_disp($edit['providers_start_date']);
					}
				?>
				<script type="text/javascript" src="includes/javascript/calendar.js"></script>
				<script type="text/javascript"><!--
					//var ac_start_date = new ctlSpiffyCalendarBox("ac_start_date", "travel_agency_frm", "providers_start_date","btnDate3","<?php echo $providers_start_date;?>",scBTNMODE_CUSTOMBLUE);
					//ac_start_date.writeControl(); ac_start_date.dateFormat="MM/dd/yyyy";
				//--></script>
				<?php echo tep_draw_input_field('providers_start_date', tep_get_date_disp($_GET['providers_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
			</td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_PROVIDERS_DISPLAY_STATUS_HIST;?><br></td>
			<td class="main">
				<?php 
					if($edit['providers_display_status_hist']=='1'){
						$chk_val="on";
						$chk_selected=true;
					}else{
						$chk_val=0;
						$chk_selected=false;
					}
					echo tep_draw_checkbox_field('providers_display_status_hist', $chk_val, $chk_selected);?>
			</td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_SEPARATE_TOUR_AND_TOUR_PACKAGE;?><br></td>
			<td class="main">
				<?php 
					if($edit['providers_separate_tours_package']=='1'){
						$chk_val="on";
						$chk_selected=true;
					}else{
						$chk_val=0;
						$chk_selected=false;
					}
					echo tep_draw_checkbox_field('providers_separate_tours_package', $chk_val, $chk_selected);?>
			</td>
		</tr>
			<tr>
				<td class="main"><?php echo TXT_CAN_ISSUE_E_TICKET;?><br></td>
				<td class="main">
					<?php 
						if($edit['providers_can_send_eticket']=='1'){
							$chk_val="on";
							$chk_selected=true;
						}else{
							$chk_val=0;
							$chk_selected=false;
						}
						echo tep_draw_checkbox_field('providers_can_send_eticket', $chk_val, $chk_selected);?>
				</td>
			</tr>
			<tr>
				<td class="main"><?php echo TXT_ETICKET_DEFAULT_COMMENT;?><br></td>
				<td class="main">
					<?php echo tep_draw_textarea_field('providers_default_eticket_comment', 'soft', '100', '7', $edit['providers_default_eticket_comment']);?>
				</td>
			</tr>
			<tr>
			<td class="main"><?php echo TEXT_ORDER_AUTO_CHARGE_HEAD;?><br></td>
			<td class="main">
			<?php 
			if((int)$edit['provider_auto_charged']==1){
				$order_charge_selected_yes=true;
				$order_charge_selected_no=false;
			}else{
				$order_charge_selected_yes=false;
				$order_charge_selected_no=true;
			}
			echo tep_draw_radio_field('provider_auto_charged','1',$order_charge_selected_yes,'','onClick="show_auto_charge_days(this.value);" id="provider_auto_charged_yes"').'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('provider_auto_charged','0',$order_charge_selected_no,'','onClick="show_auto_charge_days(this.value);"').'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
			?>				
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<?php
					if($order_charge_selected_yes == true){
						$style_charge_div = '';
					}else{
						$style_charge_div ='style="display:none;"';
					}
				?>
				<div id="auto_charged_stop" <?php echo $style_charge_div; ?> >
				<?php 
				list($sun,$mon,$tue,$wedn,$thur,$fri,$sat) = explode('!###!',$edit['auto_charged_stop_duration']);
				list($sun_s,$sun_e)= explode('-',$sun);
				list($mon_s,$mon_e)= explode('-',$mon);
				list($tue_s,$tue_e)= explode('-',$tue);
				list($wedn_s,$wedn_e)= explode('-',$wedn);
				list($thur_s,$thur_e)= explode('-',$thur);
				list($fri_s,$fri_e)= explode('-',$fri);
				list($sat_s,$sat_e)= explode('-',$sat); 
				?>
					
					<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="260" class="main" valign="top">Auto Charge Time Restriction</td>
						<td class="main">
							<table width="100%" bgcolor="#DADADA" border="0" cellspacing="2" cellpadding="2">
							  <tr>
								<td height="25" class="main" width="130" valign="top"><strong>Day</strong></td>
								<td class="main" width="60"><strong>From:</strong><br><small>HH:MM</small></td>
								<td class="main" width="60"><strong>To:</strong><br><small>HH:MM</small></td>
							  </tr>
							  <tr>
								<td class="main">Sunday</td>
								<td class="main"><input type="text" value="<?php echo $sun_s; ?>" maxlength="5" size="5" name="sunday_s" id="sunday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $sun_e; ?>" name="sunday_e" id="sunday_e" onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Monday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $mon_s; ?>" name="monday_s" id="monday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="monday_e"  value="<?php echo $mon_e; ?>" id="monday_e"  onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Tuesday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $tue_s; ?>" name="tuesday_s" id="tuesday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="tuesday_e" id="tuesday_e" value="<?php echo $tue_e; ?>" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
								<td class="main">Wednesday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $wedn_s; ?>" name="wednesday_s" id="wednesday_s" onchange="check_form(this);" /></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="wednesday_e" id="wednesday_e" value="<?php echo $wedn_e; ?>"  onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Thursday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $thur_s; ?>" name="thursday_s" id="thursday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="thursday_e"  value="<?php echo $thur_e; ?>" id="thursday_e" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
								<td class="main">Friday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $fri_s; ?>" name="friday_s" id="friday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="friday_e" value="<?php echo $fri_e; ?>" id="friday_e" onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Saturday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $sat_s; ?>" name="saturday_s" id="saturday_s" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="saturday_e" id="saturday_e" value="<?php echo $sat_e; ?>" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
							  <td colspan="3"><span class="fieldRequired">Format should be HH:MM (eg. 06:01 or 23:59)</span></td>
							  </tr>
							</table>
						</td>
						<td class="main" valign="top">								
							  <table width="100%" border="0" cellspacing="3" cellpadding="1">
							  <tr>
								<td class="main" style="padding-left:20px;">Maximum Auto Charge Capture Amount:</td>
							  </tr>
							  <tr>
								<td class="main" style="padding-left:20px;"><input type="text" size="8" name="max_auto_cap_amount" id="max_auto_cap_amount" value="<?php if($edit['max_auto_cap_amount'] == 0){ echo '';}else{ echo $edit['max_auto_cap_amount']; } ?>"  /> <span class="fieldRequired">(eg. 500.00 Leave blank if no restriction on amount)</span></td>
							  </tr>
							</table>

							
						</td>
						</tr>
					</table>					
				</div>
			</td>
		</tr>
		<tr>
			<td class="main" nowrap="nowrap"><?php echo TEXT_ORDER_AUTO_CHARGE_HEAD_PACKAGES;?><br></td>
			<td class="main">
			<?php 
			if((int)$edit['provider_auto_charged_package']==1){
				$order_charge_selected_yes_packages=true;
				$order_charge_selected_no_packages=false;
			}else{
				$order_charge_selected_yes_packages=false;
				$order_charge_selected_no_packages=true;
			}
			echo tep_draw_radio_field('provider_auto_charged_package','1',$order_charge_selected_yes_packages,'','onClick="show_auto_charge_days_package(this.value);" id="provider_auto_charged_package_yes"').'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('provider_auto_charged_package','0',$order_charge_selected_no_packages,'','onClick="show_auto_charge_days_package(this.value);"').'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
			?>				
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
					if($order_charge_selected_yes_packages == true){
						$style_charge_div = '';
					}else{
						$style_charge_div ='style="display:none;"';
					}
				?>
				<div id="auto_charged_stop_package" <?php echo $style_charge_div; ?> >
				<?php 
				list($sun,$mon,$tue,$wedn,$thur,$fri,$sat) = explode('!###!',$edit['auto_charged_stop_duration_package']);
				list($sun_s,$sun_e)= explode('-',$sun);
				list($mon_s,$mon_e)= explode('-',$mon);
				list($tue_s,$tue_e)= explode('-',$tue);
				list($wedn_s,$wedn_e)= explode('-',$wedn);
				list($thur_s,$thur_e)= explode('-',$thur);
				list($fri_s,$fri_e)= explode('-',$fri);
				list($sat_s,$sat_e)= explode('-',$sat); 
				?>
					
					<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="260" class="main" valign="top">Auto Charge Time Restriction</td>
						<td class="main">
							<table width="100%" bgcolor="#DADADA" border="0" cellspacing="2" cellpadding="2">
							  <tr>
								<td height="25" class="main" width="130" valign="top"><strong>Day</strong></td>
								<td class="main" width="60"><strong>From:</strong><br><small>HH:MM</small></td>
								<td class="main" width="60"><strong>To:</strong><br><small>HH:MM</small></td>
							  </tr>
							  <tr>
								<td class="main">Sunday</td>
								<td class="main"><input type="text" value="<?php echo $sun_s; ?>" maxlength="5" size="5" name="sunday_s_p" id="sunday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $sun_e; ?>" name="sunday_e_p" id="sunday_e_p" onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Monday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $mon_s; ?>" name="monday_s_p" id="monday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="monday_e_p"  value="<?php echo $mon_e; ?>" id="monday_e_p"  onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Tuesday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $tue_s; ?>" name="tuesday_s_p" id="tuesday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="tuesday_e_p" id="tuesday_e_p" value="<?php echo $tue_e; ?>" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
								<td class="main">Wednesday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $wedn_s; ?>" name="wednesday_s_p" id="wednesday_s_p" onchange="check_form(this);" /></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="wednesday_e_p" id="wednesday_e_p" value="<?php echo $wedn_e; ?>"  onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Thursday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $thur_s; ?>" name="thursday_s_p" id="thursday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="thursday_e_p"  value="<?php echo $thur_e; ?>" id="thursday_e_p" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
								<td class="main">Friday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $fri_s; ?>" name="friday_s_p" id="friday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="friday_e_p" value="<?php echo $fri_e; ?>" id="friday_e_p" onchange="check_form(this);"/></td>
							  </tr>
							  <tr>
								<td class="main">Saturday</td>
								<td class="main"><input type="text" maxlength="5" size="5" value="<?php echo $sat_s; ?>" name="saturday_s_p" id="saturday_s_p" onchange="check_form(this);"/></td>
								<td class="main"><input type="text" maxlength="5" size="5" name="saturday_e_p" id="saturday_e_p" value="<?php echo $sat_e; ?>" onchange="check_form(this);" /></td>
							  </tr>
							  <tr>
							  <td colspan="3"><span class="fieldRequired">Format should be HH:MM (eg. 06:01 or 23:59)</span></td>
							  </tr>
							</table>
						</td>
						<td class="main" valign="top">								
							  <table width="100%" border="0" cellspacing="3" cellpadding="1">
							  <tr>
								<td class="main" style="padding-left:20px;">Maximum Auto Charge Capture Amount:</td>
							  </tr>
							  <tr>
								<td class="main" style="padding-left:20px;"><input type="text" size="8" name="max_auto_cap_amount_package" id="max_auto_cap_amount_package" value="<?php if($edit['max_auto_cap_amount_package'] == 0){ echo '';}else{ echo $edit['max_auto_cap_amount_package']; } ?>"  /> <span class="fieldRequired">(eg. 1000.00 Leave blank if no restriction on amount)</span></td>
							  </tr>
							</table>

							
						</td>
						</tr>
					</table>					
				</div>
			</td>
		</tr>
	</table>
	
  </td>
</tr>
<tr><td colspan="2" align="right"><div style="margin:10px 10px 0px 0px;"><?php echo $extra_update_button; ?></div></td></tr>
<?php //!-- Added for login deatil - END--?>

<tr>
<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_GENERAL; ?></td>
</tr>
<tr>
  <td colspan="2" class="formArea">

	<table width="100%" border="0" cellspacing="2" cellpadding="2">
	 <tr>
	 	<td width="20%" class="main"><?php echo TEXT_AGENCY_NAME;?></td>
	    <td width="80%" class="main"><?php echo tep_draw_input_field('agency_name', "$edit[agency_name]", 'size=50 maxlength=255', true); ?></td>
	 </tr>
	 <tr>
	 	<td width="20%" class="main"><?php echo TEXT_AGENCY_NAME.' Chinese';?></td>
	    <td width="80%" class="main"><?php echo tep_draw_input_field('agency_name1', "$edit[agency_name1]", 'size=50 maxlength=255', true); ?></td>
	 </tr>
	 <tr>
			<td class="main"><?php echo TEXT_AGENCY_ADDRESS;?></td>
			<td class="main"><?php echo tep_draw_input_field('address',"$edit[address]",'size=50 ', true); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_CITY;?></td>
			<td class="main"><?php echo tep_draw_input_field('city', "$edit[city]", 'size=30  maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_STATE;?></td>
			<td class="main"><?php echo tep_draw_input_field('state', "$edit[state]", 'size=30 maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_ZIP;?></td>
			<td class="main"><?php echo tep_draw_input_field('zip', "$edit[zip]", 'size=30 maxlength=255', true); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_COUNTRY;?><br></td>
			<td class="main"><?php echo tep_draw_input_field('country', "$edit[country]", 'size=30 maxlength=255'); ?></td>
		</tr>
		<tr>
	 		<td class="main"><?php echo TEXT_AGENCY_TIME_ZONE;?></td>
	    <td  class="main"><?php echo tep_draw_input_field('agency_timezone', "$edit[agency_timezone]", 'size=50 maxlength=255'); ?></td>
	 </tr>
		<tr>
			<td class="main"><?php echo TITLE_AGENCY_CODE; ?>:<br></td>
			<td class="main"><?php echo tep_draw_input_field('agency_code', "$edit[agency_code]", 'size=50 maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TITLE_OPERATE_CURRENCY; ?><br></td>
			<td class="main">
			<?php 
			
			$currencies_array = array(array('id' => 'USD', 'text' => 'US Dollar [USD]'));
			/* 强制使用美元不用其它货币与供应商结账
			$currency_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, last_updated, value from " . TABLE_CURRENCIES . " where code !='USD' order by title";
			$currency_query = tep_db_query($currency_query_raw);
			while ($currency = tep_db_fetch_array($currency_query)) {
				 $currencies_array[] = array('id' => $currency['code'],	'text' => $currency['title'] .'['.$currency['code'].']');
			}*/
				echo tep_draw_pull_down_menu('operate_currency_code', $currencies_array, $edit[operate_currency_code]);
			?>
			</td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_OPERATOR_LANGUAGE; ?><br></td>
			<td class="main"><?php echo tep_draw_input_field('agency_oper_lang', "$edit[agency_oper_lang]", 'size=50 maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_WEBSITE_URL; ?><br></td>
			<td class="main"><?php echo tep_draw_input_field('website',"$edit[website]", 'size=50 '); ?></td>
		</tr>
		<tr>
			<td class="main" valign="top"><?php echo TEXT_AGENCY_MAJOR_CATEGORIES; ?>:<br></td>
			<td class="main"><?php 
			//echo tep_draw_input_field('major_categories',"$edit[major_categories]");
			echo tep_draw_textarea_field('major_categories', 'soft', '100', '7',$edit[major_categories]);
			 ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_DEFUAL_MAX_ALLOW_CHILD_AGE; ?>:<br></td>
			<td class="main"><?php echo tep_draw_input_field('default_max_allow_child_age',"$edit[default_max_allow_child_age]","maxlength='3' size='4'"); ?> years</td>
		</tr>
        
        
          <?php //Howard added 2010-07-29 start?>
          <tr>
		  <td class="main"><?php echo BOOK_LIMIT_DAYS_NUMBER_TITLE; ?></td>
		  <td class="main">
		  <?php
			$days_hours_number = 48;
			$days_hours_type = "hours";
			
			if((int)$edit['book_limit_days']){ $days_hours_number = $edit['book_limit_days']; }
			if(tep_not_null($edit['book_limit_days_type'])){ $days_hours_type = $edit['book_limit_days_type']; }
			
			echo tep_draw_input_num_en_field('book_limit_days', $days_hours_number, ' size="4" ');
			$days_hours_types_array = array();
			$days_hours_types_array[]=array('id'=>"days",'text'=>"days");
			$days_hours_types_array[]=array('id'=>"hours",'text'=>"hours");
			echo tep_draw_pull_down_menu('days_hours_types', $days_hours_types_array ,$days_hours_type );
		  ?></td>
		  </tr>
          <?php //Howard added 2010-07-29 end?>
          <?php //Howard added 2010-10-26 start?>
          <tr>
		  <td class="main"><?php echo BOOK_LIMIT_DAYS_NUMBER_TITLE_WITCH_AIR; ?></td>
		  <td class="main">
		  <?php
			$days_hours_number_air = 48;
			$days_hours_type_air = "hours";
			
			if((int)$edit['book_limit_days_air']){ $days_hours_number_air = $edit['book_limit_days_air']; }
			if(tep_not_null($edit['book_limit_days_type_air'])){ $days_hours_type_air = $edit['book_limit_days_type_air']; }
			
			echo tep_draw_input_num_en_field('book_limit_days_air', $days_hours_number_air, ' size="4" ');
			$days_hours_types_array_air = array();
			$days_hours_types_array_air[]=array('id'=>"days",'text'=>"days");
			$days_hours_types_array_air[]=array('id'=>"hours",'text'=>"hours");
			echo tep_draw_pull_down_menu('days_hours_types_air', $days_hours_types_array_air ,$days_hours_type_air );
		  ?></td>
		  </tr>
          <?php //Howard added 2010-10-26 end?>
          <?php //Howard added 2010-10-27 start?>
          <tr>
		  <td class="main">Formula:</td>
		  <td class="main">
		  <?php
		  if(!tep_not_null($edit['formula'])){
		  	$edit['formula'] = 'Admin setting=(departure date - limited booking date)*24 - cut-off time - time difference';
		  }
		  $formula_size = max(50,(strlen($edit['formula'])+2));
		  
		  echo tep_draw_input_field('formula',$edit['formula'],'size="'.$formula_size.'"');
		  ?>
		  </td>
		  </tr>
          <tr>
		  <td class="main">Time difference from China:</td>
		  <td class="main"><?php echo tep_draw_input_field('time_difference_from_china',$edit['time_difference_from_china']);?></td>
		  </tr>
          <?php //Howard added 2010-10-27 end?>

		<tr>
			<td class="main"><?php echo TITLE_TRASACTION_FEE; ?>:<br></td>
			<td class="main"><?php
			$default_transaction_fee_array = array(array('id' => '', 'text' => '--None--'));		
			$default_transaction_fee_array[] = array('id' => '1', 'text' => '1');
			$default_transaction_fee_array[] = array('id' => '2', 'text' => '2');
			$default_transaction_fee_array[] = array('id' => '3', 'text' => '3');
			$default_transaction_fee_array[] = array('id' => '4', 'text' => '4');
			$default_transaction_fee_array[] = array('id' => '5', 'text' => '5');
			echo tep_draw_pull_down_menu('default_transaction_fee', $default_transaction_fee_array, $edit[default_transaction_fee]);
			?> %</td>
		</tr>
		<tr>
			<td class="main" valign="top"><?php echo TTL_PROVIDER_CXLN_POLICY; ?><br></td>
			<td class="main">
			<?php echo tep_draw_textarea_field('provider_cxln_policy', 'soft', '100', '7',$edit[provider_cxln_policy]); ?>
			</td>
		</tr>
		<tr>
			<td class="main" valign="top"><?php echo TTL_CXLN_POLICY; ?><br></td>
			<td class="main">
			<?php echo tep_draw_textarea_field('store_cxln_policy', 'soft', '100', '7',$edit[store_cxln_policy]); ?>
			</td>
		</tr>
</table>
	
  </td>
</tr>
<tr><td colspan="2" align="right"><div style="margin:10px 10px 0px 0px;"><?php echo $extra_update_button; ?></div></td></tr>
<tr>
<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_RESERVATION; ?></td>
</tr>

<tr>
  <td colspan="2" class="formArea">
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td width="20%" class="main"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
			<td class="main"><?php echo tep_draw_input_field('contactperson', "$edit[contactperson]", 'size=50 maxlength=255'); ?></td>
		</tr>
		<tr>
			<td width="20%" class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
			<td class="main"><?php echo tep_draw_input_field('emailaddress', "$edit[emailaddress]", 'size=50 maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TEXT_AGENCY_PHONE;?><br></td>
			<td class="main"><?php echo tep_draw_input_field('phone', "$edit[phone]", 'size=50 maxlength=64'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo ENTRY_FAX_NUMBER; ?><br></td>
			<td class="main"><?php echo tep_draw_input_field('fax', "$edit[fax]", 'size=50 maxlength=210'); ?></td>
		</tr>
        <tr>
				<td class="main"><?php echo TTL_LOCAL_OPERATOR_PHONE_NO;?><br></td>
				<td class="main"><?php echo tep_draw_input_field('local_operator_phone', "$edit[local_operator_phone]", 'size=50  maxlength=255'); ?></td>
			</tr>
		<tr>
			<td class="main"><?php echo TTL_EMERGENCY_CONTACT_PERSON;?><br></td>
			<td class="main"><?php echo tep_draw_input_field('emerency_contactperson', "$edit[emerency_contactperson]", 'size=50  maxlength=255'); ?></td>
		</tr>
		<tr>
			<td class="main"><?php echo TTL_EMERGENCY_PHONE_NO; ?><br></td>
			<td class="main"><?php echo tep_draw_input_field('emerency_number', "$edit[emerency_number]", ' size=50 maxlength=64'); ?></td>
		</tr>
        <tr>
            <td class="main"><?php echo 'Date of birth Info Needed?'; ?><br></td>
            <td class="main">
                <?php 
                if((int)$edit['is_birth_info']==1){
                    $is_birth_info_selected_yes=true;
                    $is_birth_info_selected_no=false;
                }else{
                    $is_birth_info_selected_yes=false;
                    $is_birth_info_selected_no=true;
                }
                echo tep_draw_radio_field('is_birth_info','1',$is_birth_info_selected_yes).'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('is_birth_info','0',$is_birth_info_selected_no).'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
                ?>
             </td>
        </tr>
        <tr>
            <td class="main"><?php echo 'Gender Info Needed?'; ?><br></td>
            <td class="main">
                <?php 
                if((int)$edit['is_gender_info']==1){
                    $is_gender_info_selected_yes=true;
                    $is_gender_info_selected_no=false;
                }else{
                    $is_gender_info_selected_yes=false;
                    $is_gender_info_selected_no=true;
                }
                echo tep_draw_radio_field('is_gender_info','1',$is_gender_info_selected_yes).'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('is_gender_info','0',$is_gender_info_selected_no).'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
                ?>
             </td>
        </tr>
        <tr>
            <td class="main"><?php echo 'Hotel Pick Up Info Needed?'; ?><br></td>
            <td class="main">
            <?php 
                if((int)$edit['is_hotel_pickup_info']==1){
                    $is_hotel_pickup_info_selected_yes=true;
                    $is_hotel_pickup_info_selected_no=false;
                }else{
                    $is_hotel_pickup_info_selected_yes=false;
                    $is_hotel_pickup_info_selected_no=true;
                }
                echo tep_draw_radio_field('is_hotel_pickup_info','1',$is_hotel_pickup_info_selected_yes).'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('is_hotel_pickup_info','0',$is_hotel_pickup_info_selected_no).'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
                ?>
            </td>
		</tr>
        <tr>
            <td class="main"><?php echo 'Customer signature is required on E-Ticket?'; ?><br></td>
            <td class="main">
                <?php 
                if((int)$edit['is_customer_signature']==1){
                    $is_customer_signature_selected_yes=true;
                    $is_customer_signature_selected_no=false;
                }else{
                    $is_customer_signature_selected_yes=false;
                    $is_customer_signature_selected_no=true;
                }
                echo tep_draw_radio_field('is_customer_signature','1',$is_customer_signature_selected_yes).'&nbsp;'.TEXT_ORDER_CHARGE_YES.'&nbsp;'.tep_draw_radio_field('is_customer_signature','0',$is_customer_signature_selected_no).'&nbsp;'.TEXT_ORDER_CHARGE_NO ; 
                ?>
             </td>
        </tr>
		<tr>
			<td class="main" valign="top">Notes:<br></td>
			<td class="main"><?php echo tep_draw_textarea_field('res_notes', 'soft', '100', '7', $edit[res_notes]);?></td>
		</tr>
	</table>
  </td>
</tr>
<tr><td colspan="2" align="right"><div style="margin:10px 10px 0px 0px;"><?php echo $extra_update_button; ?></div></td></tr>
	
<tr>
	<td class="formAreaTitle"><?php echo CATEGORY_ACCOUNTING; ?></td>
</tr>
<tr>
  <td  colspan="2" class="formArea">
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td width="20%" class="main"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
			<td class="main"><?php echo tep_draw_input_field('accounting_contactperson', "$edit[accounting_contactperson]", 'size=50 maxlength=255'); ?></td>
		</tr>
		<tr>
				<td class="main"><?php echo TXT_PAYMENT_METHOD;?><br></td>
				<td class="main">
					<?php 
						$ar_payment_method = array(array('id' => '0', 'text' => TXT_SELECT),
																				array('id' => '1', 'text' => OPT_ACH),
																				array('id' => '2', 'text' => OPT_CHECK),
																				array('id' => '3', 'text' => OPT_WIRE_TRANSFER));
						echo tep_draw_pull_down_menu('acc_payment_method', $ar_payment_method, $edit[acc_payment_method]);
					?>
				</td>
			</tr>
			<tr>
				<td class="main"><?php echo TXT_PAYMENT_FREQUENCY;?><br></td>
				<td class="main">
					<?php 
						$ar_payment_frequency = array(array('id' => '', 'text' => TXT_SELECT),
																				array('id' => OPT_MONTHLY, 'text' => OPT_MONTHLY),
																				array('id' => OPT_SEMI_MONTHLY, 'text' => OPT_SEMI_MONTHLY));
						$qry_payment_frequency="SELECT DISTINCT(acc_payment_frequency) FROM ".TABLE_TRAVEL_AGENCY." WHERE acc_payment_frequency NOT IN ('', '".OPT_MONTHLY."', '".OPT_SEMI_MONTHLY."') ORDER BY acc_payment_frequency ASC";
						$res_payment_frequency=tep_db_query($qry_payment_frequency);
						while($row_payment_frequency=tep_db_fetch_array($res_payment_frequency)){
							$ar_payment_frequency[] = array('id' => $row_payment_frequency['acc_payment_frequency'], 'text' => $row_payment_frequency['acc_payment_frequency']);
						}
						$ar_payment_frequency[] = array('id' => '1', 'text' => OPT_OTHER);
						echo tep_draw_pull_down_menu('acc_payment_frequency', $ar_payment_frequency, $edit[acc_payment_frequency], 'onchange="if(this.value==\'1\'){document.travel_agency_frm.txt_acc_payment_frequency.style.display=\'\'}else{document.travel_agency_frm.txt_acc_payment_frequency.style.display=\'none\'}"');
						echo "<br/>";
						echo tep_draw_input_field('txt_acc_payment_frequency', "", 'size=50 maxlength=255 id="txt_acc_payment_frequency" style="display:none;"');
					?>
				</td>
			</tr>
			<tr>
				<td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
				<td class="main"><?php echo tep_draw_input_field('acc_email', "$edit[acc_email]", 'size=50 maxlength=255'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo TEXT_AGENCY_PHONE;?><br></td>
				<td class="main"><?php echo tep_draw_input_field('acc_phone', "$edit[acc_phone]", 'size=50 maxlength=64'); ?></td>
			</tr>
			<tr>
				<td class="main"><?php echo ENTRY_FAX_NUMBER; ?><br></td>
				<td class="main"><?php echo tep_draw_input_field('acc_fax', "$edit[acc_fax]", 'size=50 maxlength=64'); ?></td>
			</tr>
			<tr>
				<td class="main" valign="top"><?php echo TXT_ACC_NOTES; ?><br></td>
				<td class="main"><?php echo tep_draw_textarea_field('acc_notes', 'soft', '100', '7', $edit[acc_notes]);?></td>
			</tr>
	</table>
  </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td align=right>
<?php
	if($allow_travle_agency_edit == true){
		if($_GET['adgrafics_information']=='Edit'){
			echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
		}else{
			echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
		}
	}
	echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('agency_id','adgrafics_information')), 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
	 ?>
</td>
<td></td>
</tr>
</table>
<?php }//End if - delete confirmation?>
</form>
</td></tr>