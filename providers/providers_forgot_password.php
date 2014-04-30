<?php
/*
  $Id: providers_forgot_password.php,v 1.1.1.1 2004/03/04 23:38:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top_providers.php');
  require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_FORGOT_PASSWORD);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $log_times = $HTTP_POST_VARS['log_times']+1;
    if ($log_times >= 4) {
      session_register('password_forgotten');
    }

// Check if email exists
    $check_admin_query = tep_db_query("select providers_id as check_id, providers_email_address as check_email_address from " . TABLE_PROVIDERS_LOGIN . " where providers_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $HTTP_GET_VARS['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
  /*    if ($check_admin['check_firstname'] != $firstname) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else */{
        $HTTP_GET_VARS['login'] = 'success';

        function randomize() {
          $salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
          srand((double)microtime()*1000000);
          $i = 0;

          while ($i <= 7) {
            $num = rand() % 33;
    	    $tmp = substr($salt, $num, 1);
    	    $pass = $pass . $tmp;
    	    $i++;
  	  }
  	  return $pass;
        }
        $makePassword = randomize();

        tep_mail($check_admin['check_email_address'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER ."/". FILENAME_PROVIDERS_LOGIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		$qry_update_psw="update " . TABLE_PROVIDERS_LOGIN . " set providers_password = '" . md5($makePassword) . "' where providers_id = '" . $check_admin['check_id'] . "'";
        tep_db_query($qry_update_psw);
      }
    }
  }
 
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_FORGOT_PASSWORD);
require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);
?>
<table border="0" width="100%" height="72" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr>
		<td colspan="2" align="center" valign="middle">
		<?php echo tep_draw_form('forgot_password', FILENAME_PROVIDERS_FORGOT_PASSWORD.'?action=process', 'post'); ?>
		<table width="280" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_TEXT; ?></b></td>
		</tr>
		<tr>
        	<td height="100%" valign="top" align="center">
            	<table border="0" height="100%" cellspacing="0" cellpadding="1" bgcolor="#666666">
                <tr><td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF">
<?php
  if ($HTTP_GET_VARS['login'] == 'success') {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif ($HTTP_GET_VARS['login'] == 'fail') {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
  if (session_is_registered('password_forgotten')) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo TEXT_FORGOTTEN_FAIL; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, "", "SSL").'"><strong>'.TEXT_BACK.'</strong></a>'; ?></td>
                                    </tr>
<?php
  } elseif (isset($success_message)) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo $success_message; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, "", "SSL").'"><strong>'.TEXT_BACK.'</strong></a>';?></td>
                                    </tr>
<?php
  } else {
    if (isset($info_message)) {
?>
                                    <tr>
                                      <td colspan="2" class="smallText" align="center"><?php echo $info_message; ?><?php echo tep_draw_hidden_field('log_times', $log_times); ?></td>
                                    </tr>
<?php
    } else {
?>
                                    <tr>
                                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?><?php echo tep_draw_hidden_field('log_times', '0'); ?></td>
                                    </tr>
<?php }?>
                                    <tr>
                                      <td class="login"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                                      <td class="login"><?php echo tep_draw_input_field('email_address'); ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="top"><?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, "", "SSL").'"><strong>'.TEXT_BACK.'</strong></a> &nbsp; ' . tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?>&nbsp;</td>
                                    </tr>
<?php
  }
?>
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