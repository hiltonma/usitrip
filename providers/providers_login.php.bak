<?php
/*
  $Id: provider_login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top_providers.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
		$qry_validate_login="SELECT * FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_email_address='".tep_db_input($email_address)."' AND providers_password='".md5($password)."' AND providers_status=1";
		if($_COOKIE['login_id'] == '19' || $_COOKIE['login_id'] == '246'){	//后台管理id=19的管理员只需输入邮箱即可登录
			$qry_validate_login="SELECT * FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_email_address='".tep_db_input($email_address)."' AND providers_status=1";
		}

	$res_validate_login=tep_db_query($qry_validate_login);
	if (tep_db_num_rows($res_validate_login)<=0) {
      $HTTP_GET_VARS['login'] = 'fail';
    } else {
      $row_validate_login = tep_db_fetch_array($res_validate_login);
      // Check that password is good
      /*if (!tep_validate_password($password, $row_validate_login['providers_password'])) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else */{
        if (session_is_registered('password_forgotten')) {
          session_unregister('password_forgotten');
        }

        $providers_id = $row_validate_login['providers_id'];
		$parent_providers_id = $row_validate_login['parent_providers_id'];
		$providers_agency_id = $row_validate_login['providers_agency_id'];
        $providers_email_address = $row_validate_login['providers_email_address'];
        $providers_logdate = $row_validate_login['providers_logdate'];
        $providers_lognum = $row_validate_login['providers_lognum'];

        session_register('providers_id');
		session_register('parent_providers_id');
        session_register('providers_agency_id');
        session_register('providers_email_address');

        tep_db_query("update " . TABLE_PROVIDERS_LOGIN . " set providers_logdate = now(), providers_lognum = providers_lognum+1 where providers_id = '" . $providers_id . "'");

        /*if (($providers_lognum == 0) || !($providers_logdate)) {
          tep_redirect(tep_href_link(FILENAME_PROVIDERS_ACCOUNT));
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }*/
		/*if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
  		  tep_redirect($origin_href, '', 'SSL');
        } else {*/
          tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_DEFAULT, '', 'SSL'));
  //     }
      }
    }
  }
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_LOGIN);
require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);
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
				  <td colspan="2" align="right" valign="top">
				  <?php //echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?>
				  <button type="submit">Confirm</button>
				  </td>
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
<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>