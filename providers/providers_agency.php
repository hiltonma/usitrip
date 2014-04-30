<?php
/*
  $Id: providers_agency.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id'))
{
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
if($_SESSION['parent_providers_id']!="0")
{
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_DEFAULT, '', 'SSL'));
}
$prov_id=$_SESSION['providers_id'];
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_AGENCY);
$is_provider_can_send_eticket=get_provider_can_send_eticket($_SESSION['providers_agency_id']);

function error_message($error) {
  global $warning;
  switch ($error) {
		case "40":return "<tr class=messageStackError><td>$warning " . ERROR_40_INFORMATION . "</td></tr>";break;
    case "80":return "<tr class=messageStackError><td>$warning " . ERROR_80_INFORMATION . "</td></tr>";break;
    default:return $error;
  }
}

function update_agency_info($data)
{
	$agency_id=$_SESSION['providers_agency_id'];
	$acc_payment_frequency=$data['acc_payment_frequency'];
	if($data['acc_payment_frequency']=='1' && $data['txt_acc_payment_frequency']!=""){
		$acc_payment_frequency=$data['txt_acc_payment_frequency'];
	}
	tep_db_query("UPDATE " . TABLE_TRAVEL_AGENCY . " SET agency_name='$data[agency_name]', emailaddress='$data[emailaddress]', agency_oper_lang='$data[agency_oper_lang]', website='$data[website]', next_update_due_date='$data[next_update_due_date]', operate_currency_code='$data[operate_currency_code]', default_max_allow_child_age='$data[default_max_allow_child_age]', default_transaction_fee='$data[default_transaction_fee]', provider_cxln_policy='$data[provider_cxln_policy]', agency_timezone='$data[agency_timezone]', major_categories='$data[major_categories]', last_update_by ='$data[last_update_by]', address='$data[address]', city='$data[city]', state='$data[state]', zip='$data[zip]', country='$data[country]', phone='$data[phone]', fax='$data[fax]', contactperson='$data[contactperson]', emerency_contactperson='$data[emerency_contactperson]', emerency_number='$data[emerency_number]', agency_code='$data[agency_code]', acc_phone='$data[acc_phone]', acc_fax='$data[acc_fax]', acc_email='$data[acc_email]', acc_address='$data[acc_address]', acc_payment_method='$data[acc_payment_method]', acc_payment_frequency='$acc_payment_frequency', acc_notes='$data[acc_notes]', accounting_contactperson='$data[accounting_contactperson]', providers_default_eticket_comment='$data[providers_default_eticket_comment]' WHERE agency_id='".$agency_id."'");
}

function read_data($agency_id) {
  $res_agency_detail=tep_db_query("SELECT * FROM ".TABLE_TRAVEL_AGENCY." t LEFT JOIN ".TABLE_PROVIDERS_LOGIN." l ON t.agency_id=l.providers_agency_id AND l.parent_providers_id=0 WHERE t.agency_id=$agency_id");
	$row_agency_detail=tep_db_fetch_array($res_agency_detail);
return $row_agency_detail;
}

if(tep_not_null($_POST['btnEdit'])){
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_AGENCY, tep_get_all_get_params(array("action", "msg")).'&action=edit_agency', "SSL"));
}

if(tep_not_null($_REQUEST['action'])){
	switch($_REQUEST['action'])
	{
		case 'edit_agency':
			if(tep_not_null($_POST['btnSubmit']))
			{
				if($agency_name && $address && $zip){
					if(tep_not_null($default_max_allow_child_age) && (!is_numeric($default_max_allow_child_age) || ((int)$default_max_allow_child_age>18))){
						$error="40";
					}else{
						update_agency_info($_POST);
						tep_redirect(FILENAME_PROVIDERS_AGENCY."?".tep_get_all_get_params(array("uID", "action"))."msg=1");
					}
				}else {
					$error="80";
				}
			}
			if($error){
				$_GET['action']='edit_agency';
			}
		break;
	}
}

require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);

$row_agency_detail=read_data($_SESSION['providers_agency_id']);

if(!tep_not_null($_SESSION['providers_agency_id']))
	$_SESSION['providers_agency_id']=0;

if(!tep_not_null($_POST['btnSubmit']))
{
	if(is_array($row_agency_detail))
	{
		foreach($row_agency_detail as $k=>$v)
		{
			$$k=tep_db_input($v);
		}
	}
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr valign="top">
		<td colspan="2" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr valign="top">
			<td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_TEXT; ?></b></td>
		</tr>
<?php 
	if($_GET['msg']=='1')	{
		$succ_msg=MSG_SUCCESS;
	}
	if(tep_not_null($succ_msg)){?>
		<tr valign="top">
			<td class="successMsg" valign="top" align="center">&nbsp;<?php echo $succ_msg; ?></td>
		</tr>
<?php }
	
	if($error)
	{
		$content=error_message($error);
		echo $content;
	}?>

		<tr valign="top">
        	<td height="100%" valign="top" align="center">
            	<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#666666" class="login">
				<tr><td>
			<table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF" class="login">
<?php 
		echo tep_draw_form('providers_agency', FILENAME_PROVIDERS_AGENCY.'?'.tep_get_all_get_params(array("mag", "action")), 'post');
		if($_GET['action']=='edit_agency')
		{
			echo tep_draw_hidden_field('action', $_REQUEST['action']);
			
			if($_SESSION['parent_providers_id']=="0")
			{
					require_once("providers_users_agency_detail.php");
			} 
		}else{?>
			<tr>
				<td class="formAreaTitle"><?php echo CATEGORY_GENERAL; ?></td>
			</tr>
			<tr>
				<td width="20%"><?php echo TEXT_AGENCY_NAME;?></td>
				<td><?php echo tep_db_prepare_input($agency_name);?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_ADDRESS;?></td>
				<td><?php echo tep_db_prepare_input($address); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_CITY;?></td>
				<td><?php echo tep_db_prepare_input($city); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_STATE;?></td>
				<td><?php echo tep_db_prepare_input($state); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_ZIP;?></td>
				<td><?php echo tep_db_prepare_input($zip); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_COUNTRY;?><br></td>
				<td><?php echo tep_db_prepare_input($country); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_TIME_ZONE;?></td>
				<td><?php echo tep_db_prepare_input($agency_timezone); ?></td>
			</tr>
			<tr>
				<td><?php echo TITLE_AGENCY_CODE; ?>:<br></td>
				<td><?php echo tep_db_prepare_input($agency_code); ?></td>
			</tr>
			<tr>
				<td><?php echo TITLE_OPERATE_CURRENCY; ?><br></td>
				<td><?php echo tep_db_prepare_input($operate_currency_code);?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_OPERATOR_LANGUAGE; ?><br></td>
				<td><?php echo tep_db_prepare_input($agency_oper_lang); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_WEBSITE_URL; ?><br></td>
				<td><?php echo tep_db_prepare_input($website); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php echo TEXT_AGENCY_MAJOR_CATEGORIES; ?>:<br></td>
				<td><?php echo '<pre class="login">'.tep_db_prepare_input($major_categories).'</pre>';?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_DEFUAL_MAX_ALLOW_CHILD_AGE; ?>:<br></td>
				<td><?php echo tep_db_prepare_input($default_max_allow_child_age)." ".TEXT_YEARS; ?></td>
			</tr>
			<tr>
				<td><?php echo TITLE_TRASACTION_FEE; ?>:<br></td>
				<td><?php echo tep_db_prepare_input($default_transaction_fee);?> %</td>
			</tr>
			<tr>
				<td valign="top"><?php echo TTL_PROVIDER_CXLN_POLICY; ?><br></td>
				<td><?php echo '<pre class="login">'.tep_db_prepare_input($provider_cxln_policy).'</pre>'; ?></td>
			</tr>
			
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_RESERVATION; ?></td>
			</tr>
			<tr>
				<td width="20%"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
				<td><?php echo tep_db_prepare_input($contactperson); ?></td>
			</tr>
			<tr>
				<td><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
				<td><?php echo tep_db_prepare_input($emailaddress); ?></td>
			</tr>
			<tr>
				<td width="20%"><?php echo TEXT_AGENCY_PHONE;?><br></td>
				<td><?php echo tep_db_prepare_input($phone); ?></td>
			</tr>
			<tr>
				<td><?php echo ENTRY_FAX_NUMBER; ?><br></td>
				<td><?php echo tep_db_prepare_input($fax); ?></td>
			</tr>
			<tr>
				<td><?php echo TTL_EMERGENCY_CONTACT_PERSON;?><br></td>
				<td><?php echo tep_db_prepare_input($emerency_contactperson); ?></td>
			</tr>
			<tr>
				<td><?php echo TTL_EMERGENCY_PHONE_NO; ?><br></td>
				<td><?php echo tep_db_prepare_input($emerency_number); ?></td>
			</tr>
			
			
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_ACCOUNTING; ?></td>
			</tr>
			<tr>
				<td width="20%"><?php echo TEXT_AGENCY_CONTACT_PERSON;?><br></td>
				<td><?php echo tep_db_prepare_input($accounting_contactperson); ?></td>
			</tr>
			<tr>
				<td><?php echo TXT_PAYMENT_METHOD;?><br></td>
				<td>
					<?php 
						switch($acc_payment_method){
							case '1':
								echo OPT_ACH;
								break;
							case '2':
								echo OPT_CHECK;
								break;
							case '3':
								echo OPT_WIRE_TRANSFER;
								break;
							default:
								echo "-";
								break;
						}
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo TXT_PAYMENT_FREQUENCY;?><br></td>
				<td><?php echo tep_db_prepare_input($acc_payment_frequency);?></td>
			</tr>
			<tr>
				<td><?php echo ENTRY_EMAIL_ADDRESS; ?><br></td>
				<td><?php echo tep_db_prepare_input($acc_email); ?></td>
			</tr>
			<tr>
				<td><?php echo TEXT_AGENCY_PHONE;?><br></td>
				<td><?php echo tep_db_prepare_input($acc_phone); ?></td>
			</tr>
			<tr>
				<td><?php echo ENTRY_FAX_NUMBER; ?><br></td>
				<td><?php echo tep_db_prepare_input($acc_fax); ?></td>
			</tr>
			<tr>
				<td valign="top"><?php echo TXT_ACC_NOTES; ?><br></td>
				<td><?php echo tep_db_prepare_input($acc_notes);?></td>
			</tr>
			
			<?php 
			if($is_provider_can_send_eticket=='1'){?>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="formAreaTitle" colspan="2"><?php echo CATEGORY_ETICKET; ?></td>
			</tr>
			<tr>
				<td valign="top"><?php echo TXT_ETICKET_DEFAULT_COMMENT; ?><br></td>
				<td><?php echo nl2br(tep_db_prepare_input($providers_default_eticket_comment));?></td>
			</tr>
		<?php 
			}?>
			
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo tep_draw_input_field('btnEdit', ICON_EDIT, '', 'submit');?></td>
			</tr>
<?php }?>
			</form>
				</table>
				</td>
 		</tr>
		</table>
		</td>
	</tr>
</table>
<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>