<?php $size='size="50"';?>
	<tr>
		<td class="formAreaTitle"><?php echo CATEGORY_GENERAL; ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_NAME;?></td>
		<td><?php echo tep_draw_input_field('agency_name', "$edit[agency_name]", 'size=50 maxlength=255').TEXT_FIELD_REQUIRED; ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_ADDRESS;?></td>
		<td><?php echo tep_draw_input_field('address',"$edit[address]",'size=50 ').TEXT_FIELD_REQUIRED; ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_CITY;?></td>
		<td><?php echo tep_draw_input_field('city', "$edit[city]", 'size=30  maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_STATE;?></td>
		<td><?php echo tep_draw_input_field('state', "$edit[state]", 'size=30 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_ZIP;?></td>
		<td><?php echo tep_draw_input_field('zip', "$edit[zip]", 'size=30 maxlength=255').TEXT_FIELD_REQUIRED; ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_COUNTRY;?><br></td>
		<td><?php echo tep_draw_input_field('country', "$edit[country]", 'size=30 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_TIME_ZONE;?></td>
		<td><?php echo tep_draw_input_field('agency_timezone', "$edit[agency_timezone]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TITLE_AGENCY_CODE; ?>:<br></td>
		<td><?php echo tep_draw_input_field('agency_code', "$edit[agency_code]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TITLE_OPERATE_CURRENCY; ?><br></td>
		<td>
		<?php 
		
		$currencies_array = array(array('id' => 'USD', 'text' => 'US Dollar [USD]'));
		$currency_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, last_updated, value from " . TABLE_CURRENCIES . " where code !='USD' order by title";
		$currency_query = tep_db_query($currency_query_raw);
		while ($currency = tep_db_fetch_array($currency_query)) {
		
			 $currencies_array[] = array('id' => $currency['code'],
																 'text' => $currency['title'] .'['.$currency['code'].']');
		}
			echo tep_draw_pull_down_menu('operate_currency_code', $currencies_array, $edit[operate_currency_code]);
		?>
		</td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_OPERATOR_LANGUAGE; ?><br></td>
		<td><?php echo tep_draw_input_field('agency_oper_lang', "$edit[agency_oper_lang]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_WEBSITE_URL; ?><br></td>
		<td><?php echo tep_draw_input_field('website',"$edit[website]", 'size=50 '); ?></td>
	</tr>
	<tr>
		<td valign="top"><?php echo TEXT_AGENCY_MAJOR_CATEGORIES; ?>:<br></td>
		<td><?php 
		//echo tep_draw_input_field('major_categories',"$edit[major_categories]");
		echo tep_draw_textarea_field('major_categories', 'soft', '100', '7',$edit[major_categories]);
		 ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_DEFUAL_MAX_ALLOW_CHILD_AGE; ?>:<br></td>
		<td><?php echo tep_draw_input_field('default_max_allow_child_age',"$edit[default_max_allow_child_age]","maxlength='3' size='4'"); ?> years</td>
	</tr>
	<tr>
		<td><?php echo TITLE_TRASACTION_FEE; ?>:<br></td>
		<td><?php
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
		<td valign="top"><?php echo TTL_PROVIDER_CXLN_POLICY; ?><br></td>
		<td>
		<?php echo tep_draw_textarea_field('provider_cxln_policy', 'soft', '100', '7',$edit[provider_cxln_policy]); ?>
		</td>
	</tr>
	
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_RESERVATION; ?></td>
	</tr>
	<tr>
		<td width="20%"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
		<td><?php echo tep_draw_input_field('contactperson', "$edit[contactperson]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
		<td><?php echo tep_draw_input_field('emailaddress', "$edit[emailaddress]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td width="20%"><?php echo TEXT_AGENCY_PHONE;?><br></td>
		<td><?php echo tep_draw_input_field('phone', "$edit[phone]", 'size=50 maxlength=64'); ?></td>
	</tr>
	<tr>
		<td><?php echo ENTRY_FAX_NUMBER; ?><br></td>
		<td><?php echo tep_draw_input_field('fax', "$edit[fax]", 'size=50 maxlength=210'); ?></td>
	</tr>
	<tr>
		<td><?php echo TTL_EMERGENCY_CONTACT_PERSON;?><br></td>
		<td><?php echo tep_draw_input_field('emerency_contactperson', "$edit[emerency_contactperson]", 'size=50  maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TTL_EMERGENCY_PHONE_NO; ?><br></td>
		<td><?php echo tep_draw_input_field('emerency_number', "$edit[emerency_number]", ' size=50 maxlength=64'); ?></td>
	</tr>
	
	
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_ACCOUNTING; ?></td>
	</tr>
	<tr>
		<td width="20%"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
		<td><?php echo tep_draw_input_field('accounting_contactperson', "$edit[accounting_contactperson]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TXT_PAYMENT_METHOD;?><br></td>
		<td>
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
		<td><?php echo TXT_PAYMENT_FREQUENCY;?><br></td>
		<td>
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
				echo tep_draw_pull_down_menu('acc_payment_frequency', $ar_payment_frequency, $edit[acc_payment_frequency], 'onchange="if(this.value==\'1\'){document.providers_agency.txt_acc_payment_frequency.style.display=\'\'}else{document.providers_agency.txt_acc_payment_frequency.style.display=\'none\'}"');
				echo "<br/>";
				echo tep_draw_input_field('txt_acc_payment_frequency', "", 'size=50 maxlength=255 id="txt_acc_payment_frequency" style="display:none;"');
			?>
		</td>
	</tr>
	<tr>
		<td><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
		<td><?php echo tep_draw_input_field('acc_email', "$edit[acc_email]", 'size=50 maxlength=255'); ?></td>
	</tr>
	<tr>
		<td><?php echo TEXT_AGENCY_PHONE;?><br></td>
		<td><?php echo tep_draw_input_field('acc_phone', "$edit[acc_phone]", 'size=50 maxlength=64'); ?></td>
	</tr>
	<tr>
		<td><?php echo ENTRY_FAX_NUMBER; ?><br></td>
		<td><?php echo tep_draw_input_field('acc_fax', "$edit[acc_fax]", 'size=50 maxlength=64'); ?></td>
	</tr>
	<tr>
	<td valign="top"><?php echo TXT_ACC_NOTES; ?><br></td>
	<td><?php echo tep_draw_textarea_field('acc_notes', 'soft', '100', '7', $edit[acc_notes]);?></td>
	</tr>
	
	<?php 
	if($is_provider_can_send_eticket=='1'){?>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_ETICKET; ?></td>
	</tr>
	<tr>
		<td valign="top"><?php echo TXT_ETICKET_DEFAULT_COMMENT; ?><br></td>
		<td><?php echo tep_draw_textarea_field('providers_default_eticket_comment', 'soft', '100', '7', $edit[providers_default_eticket_comment]);?></td>
	</tr>
<?php 
	}else{
		echo tep_draw_hidden_field('providers_default_eticket_comment', $edit[providers_default_eticket_comment]);
	}?>
	
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo tep_draw_input_field('btnSubmit', TEXT_UPDATE, '', 'submit');?>&nbsp;<?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_AGENCY, tep_get_all_get_params(array("action", "msg")), "SSL").'">'.TEXT_BACK.'</a>';?></td>
	</tr>