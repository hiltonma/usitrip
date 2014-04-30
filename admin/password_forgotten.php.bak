<?php
/*
$Id: password_forgotten.php,v 1.1.1.1 2004/03/04 23:38:51 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
echo '密码取回功能已经停用！';
exit;

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
	$email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
	$firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
	$log_times = $HTTP_POST_VARS['log_times']+1;
	if ($log_times >= 4) {
		tep_session_register('password_forgotten');
	}

	// Check if email exists
	$check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
	if (!tep_db_num_rows($check_admin_query)) {
		$HTTP_GET_VARS['login'] = 'fail';
	} else {
		$check_admin = tep_db_fetch_array($check_admin_query);
		if (0 && $check_admin['check_firstname'] != $firstname) {	//不比较用户姓名了
			$HTTP_GET_VARS['login'] = 'fail';
		} else {
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

			tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
		}
	}
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #ffffff; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #dddddd; text-decoration: none; }
A.sub:hover { color: #dddddd; text-decoration: underline; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; line-height: 1.5; color: #dddddd; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; color: #000000; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size:12px; }
.login_heading { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #ffffff;}
.login { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000;}
a:link.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none; }
a:visited.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none }
a:active.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none; }
a:hover.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: underline; }
//--></style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<table border="0" width="776" height="100%" cellspacing="0" cellpadding="0" align="center" valign="middle">
  <tr>
    <td><table border="0" width="755" cellspacing="0" cellpadding="1" align="center" valign="middle">
      <tr bgcolor="#ffffff">
        <td><table border="0" width="755" cellspacing="0" cellpadding="0">
           <tr>    <td background="images/logo-banner_bg.gif">
    	<?php
    	// #CP - point logos to come from selected template's images directory
    	$template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
    	$template = tep_db_fetch_array($template_query);
    	$CURR_TEMPLATE = $template['configuration_value'] . '/';

    	echo '<a href="http://phesis.cool-networks.net/index.php">' . tep_image(DIR_WS_TEMPLATES . $CURR_TEMPLATE . DIR_WS_IMAGES . 'logo.gif') . '</a>';
        ?>
    </td>
    <td class="headerBarContent" align="right" background="<?php echo DIR_WS_TEMPLATES . $CURR_TEMPLATE . DIR_WS_IMAGES;?>admin_logo_right.gif" width="462" height="82"><?php echo '&nbsp;&nbsp;<a href="http://phesis.cool-networks.net/index.php" target="_blank" class="headerLink">Help Desk</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.chainreactionweb.com/" class="headerLink">' . HEADER_TITLE_CHAINREACTION . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.oscommerce.com" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="' . tep_catalog_href_link() . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a> &nbsp;|&nbsp; <a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>'; ?>&nbsp;&nbsp;


    </td>
    </tr>
</table></td></tr>
          <tr bgcolor="#000000">
            <td colspan="2" align="center" valign="middle">
                          <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process'); ?>
                            <table width="280" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_PASSWORD_FORGOTTEN; ?></b></td>
                              </tr>
                              <tr>
                                <td height="100%" width="100%" valign="top" align="center">
                                <table border="0" height="100%" width="100%" cellspacing="0" cellpadding="1" bgcolor="#666666">
                                  <tr><td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF">

<?php
if ($HTTP_GET_VARS['login'] == 'success') {
	$success_message = TEXT_FORGOTTEN_SUCCESS;
} elseif ($HTTP_GET_VARS['login'] == 'fail') {
	$info_message = TEXT_FORGOTTEN_ERROR;
}
if (tep_session_is_registered('password_forgotten')) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo TEXT_FORGOTTEN_FAIL; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                                    </tr>
<?php
} elseif (isset($success_message)) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo $success_message; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
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
<?php
	}
?>
                                    <!--
									<tr>
                                      <td class="login"><?php echo ENTRY_FIRSTNAME; ?></td>
                                      <td class="login"><?php echo tep_draw_input_field('firstname'); ?></td>
                                    </tr>
									-->
                                    <tr>
                                      <td class="login"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                                      <td class="login"><?php echo tep_draw_input_field('email_address'); ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?>&nbsp;</td>
                                    </tr>
<?php
}
?>
                                  </table></td></tr>
                                </table>
                                </td>
                              </tr>
                            </table>
                          </form>

            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>

</html>
