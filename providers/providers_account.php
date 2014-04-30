<?php
/*
  $Id: providers_account.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

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
$provider_id=$_SESSION['providers_id'];

if($_GET['action']=='process')
{
	$password = tep_db_prepare_input($HTTP_POST_VARS['providers_password']);
	$confirmation = tep_db_prepare_input($HTTP_POST_VARS['providers_password_conf']);
	$error=false;
	$error_stack=array();
	if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      //$messageStack->add('providers_account', ENTRY_PASSWORD_ERROR);
	  $error_stack[]=ENTRY_PASSWORD_ERROR;
    } elseif ($password != $confirmation) {
      $error = true;
      //$messageStack->add('providers_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
	  $error_stack[]=ENTRY_PASSWORD_ERROR_NOT_MATCHING;
    }
	
	if ($error == false)
	{
		if(tep_not_null($providers_password))
		{
			$sql_data_array = array('providers_password' => md5($providers_password));
			tep_db_perform(TABLE_PROVIDERS_LOGIN, $sql_data_array, 'update', 'providers_id = "' . $providers_id . '"');
			tep_redirect(FILENAME_PROVIDERS_ACCOUNT."?msg=1");
		}
	}
}

$qry_account_detail="SELECT * FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_id='".$provider_id."'";
$res_account_detail=tep_db_query($qry_account_detail);
$row_account_detail=tep_db_fetch_array($res_account_detail);

if(is_array($row_account_detail))
{
	foreach($row_account_detail as $k=>$v)
		$$k=$v;
}

require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_ACCOUNT);
require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);
?>
<table border="0" width="100%" height="72" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr>
		<td colspan="2" align="center" valign="middle">
		<?php echo tep_draw_form('account', FILENAME_PROVIDERS_ACCOUNT.'?action=process', 'post'); ?>
		<table width="330" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_TEXT; ?></b></td>
		</tr>
		<tr>
        	<td height="100%" valign="top" align="center">
            	<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#666666">
                <tr><td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF">
				<?php
//  if ($messageStack->size('providers_account') > 0) {
if (tep_not_null($error_stack)) {
/*$class='class="messageBox"';
for ($i=0, $n=sizeof($error_stack); $i<$n; $i++) {
          $output[] = $this->messages[$i];
        
      }*/
?>
		<?php 
			if(is_array($error_stack))
			{
				foreach($error_stack as $k=>$v)
				{
					if($v!="")
					{
						echo '<tr class="messageStackError"><td colspan="2">' . $v . '</td></tr>';
					}
				}
			}
		?>
<?php
  }
?>
		<?php
		if ($HTTP_GET_VARS['msg'] == '1')
		{
			$info_message = TEXT_PASSWORD_CHANGED;
		}
		if (isset($info_message)) {?>
				<tr>
				  <td colspan="2" class="successMsg" align="center"><?php echo $info_message; ?></td>
				</tr>
	<?php
		} else {?>
				<tr>
				  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
	<?php }?>
				<tr>
				  <td width="40%" class="login"><strong><?php echo ENTRY_NAME; ?></strong></td>
				  <td class="login"><?php echo tep_db_output($providers_firstname." ".$providers_lastname); ?></td>
				</tr>
				<tr>
				  <td width="40%" class="login"><strong><?php echo ENTRY_EMAIL_ADDRESS; ?></strong></td>
				  <td class="login"><?php echo tep_db_output($providers_email_address); ?></td>
				</tr>
				<tr valign="top">
				  <td class="login"><strong><?php echo TEXT_AGENCY; ?></strong></td>
				  <td class="login"><?php echo tep_get_providers_agency($providers_id); ?></td>
				</tr>
				<!-- <tr>
				  <td class="login"><strong><?php echo TEXT_ACCOUNT_CREATED; ?></strong></td>
				  <td class="login"><?php echo tep_db_output($providers_created); ?></td>
				</tr>
				<tr>
				  <td class="login"><strong><?php echo TEXT_LOG_NUMBER; ?></strong></td>
				  <td class="login"><?php echo tep_db_output($providers_lognum); ?></td>
				</tr> -->
				<tr>
				  <td class="login"><strong><?php echo TEXT_LAST_ACCESS; ?></strong></td>
				  <td class="login"><?php echo tep_db_output($providers_logdate); ?></td>
				</tr>
				<tr id="tr_psw">
				  <td class="login"><strong><?php echo TEXT_PASSWORD; ?></strong></td>
				  <td class="login"><?php $providers_password="";echo tep_draw_input_field('providers_password', '', '', 'password'); ?></td>
				</tr>
				<tr id="tr_conf_psw">
				  <td class="login"><strong><?php echo TEXT_PASSWORD_CONF; ?></strong></td>
				  <td class="login"><?php echo tep_draw_input_field('providers_password_conf', '', '', 'password'); ?></td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td align="right"><?php echo tep_draw_input_field('btnSubmit', TEXT_CHANGE_PASSWORD, '', 'submit');?></td>
				</tr>
			</table>
		</td>
		</tr>
 	</table>
    </td>
 	</tr>
</table>
	</form>
</td>
</tr>
</table>
<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>