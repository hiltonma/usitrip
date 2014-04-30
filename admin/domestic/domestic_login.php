<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('includes/application_top_domestic.php');

require(DIR_WS_INCLUDES . FILENAME_DOMESTIC_HEADER);
?>
<table border="0" width="100%" height="72" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr>
		<td colspan="2" align="center" valign="middle">
		<?php echo tep_draw_form('login', FILENAME_PROVIDERS_LOGIN.'?action=process', 'post'); ?>
		<table width="280" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_TEXT; ?></b></td>
		</tr>
		<tr>
        	<td height="100%" valign="top" align="center">
            	<table border="0" height="100%" cellspacing="0" cellpadding="1" bgcolor="#666666">
                <tr><td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF">
		<?php
		if ($HTTP_GET_VARS['login'] == 'fail')
		{
			$info_message = TEXT_LOGIN_ERROR;
		}

		if (isset($info_message)) {
?>
				<tr>
				  <td colspan="2" class="smallText" align="center"><?php echo $info_message; ?></td>
				</tr>
<?php
  } else {
?>
				<tr>
				  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
				</tr>
<?php
  }
?>
				<tr>
				  <td class="login"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
				  <td class="login"><?php echo tep_draw_input_field('email_address'); ?></td>
				</tr>
				<tr>
				  <td class="login"><?php echo ENTRY_PASSWORD; ?></td>
				  <td class="login"><?php echo tep_draw_password_field('password'); ?></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" valign="top"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?></td>
				</tr>
			</table>
		</td>
		</tr>
 	</table>
    </td>
 	</tr>
    <tr>
    	<td valign="top" align="right"><?php echo '<a class="sub" href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_FORGOT_PASSWORD, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a><span class="sub">&nbsp;</span>'; ?></td>
    </tr>
</table>
	</form>
</td>
</tr>
</table>
<?php
require(DIR_WS_INCLUDES . FILENAME_DOMESTIC_FOOTER);

?>
