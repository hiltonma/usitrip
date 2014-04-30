<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
    $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum, admin_password_last_update_time as password_last from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $HTTP_GET_VARS['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      // Check that password is good
      if (!tep_validate_password($password, $check_admin['login_password'])) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else {
        if (tep_session_is_registered('password_forgotten')) {
          tep_session_unregister('password_forgotten');
        }
		include DIR_FS_CLASSES . 'AdminLoginLogs.class.php';
		$adminlogs = new AdminLoginLogs();
		$adminlogs->add($check_admin['login_id']);
        $login_id = $check_admin['login_id'];
        $login_groups_id = $check_admin['login_groups_id'];
        $login_firstname = $check_admin['login_firstname'];
        $login_email_address = $check_admin['login_email_address'];
        $login_logdate = $check_admin['login_logdate'];
        $login_lognum = $check_admin['login_lognum'];
        $login_modified = $check_admin['login_modified'];		

        tep_session_register('login_id');
        tep_session_register('login_groups_id');
        tep_session_register('login_first_name');
		tep_session_register('login_firstname');
		
		// 记录cookie信息给前台销售
        setcookie('login_id', $login_id, time()+(3600*24), '/', HTTP_COOKIE_DOMAIN);	//必须添加路径为/否则前台的程序找不到此变量
		
		
        // 每日必读提醒 start     
        //echo "SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE is_read=0 AND  admin_id=".$login_id;
        $unread = tep_db_query("SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE is_read='0' AND  admin_id='".$login_id."'");
        while($unread_array = tep_db_fetch_array($unread)){
            
            $adjective_query = tep_db_query("SELECT is_adjective FROM zhh_system_words WHERE words_id='".(int)$unread_array['words_id']."'");
            $adjective_result = tep_db_fetch_array($adjective_query);            
            $login_num = (int)$unread_array['login_num'] + 1;
            /*
            if ($adjective_result['is_adjective'] != 1){
                if ($login_num >= 4){
                    $data_array['login_num'] = $login_num;
                    $data_array['expiration_time'] = 'now()';
                    $data_array['is_expiration'] = 1;
                }
            }else{
                if ($login_num >= 2){
                    $data_array['login_num'] = $login_num;
                    $data_array['expiration_time'] = 'now()';
                    $data_array['is_expiration'] = 1;
                }
            }*/
            if ($adjective_result['is_adjective']){
                if ($login_num >= 3){
                    $is_expiration = '1';
                }else{
                    $is_expiration = '0';
                }
            }else{
                if ($login_num >= 5){
                    $is_expiration = '1';
                }else{
                    $is_expiration = '0';
                }
            }
            $data_array['login_num'] = $login_num;
            $data_array['is_expiration'] = $is_expiration;
            $data_array['expiration_time'] = 'now()';
            $id = (int)$unread_array['id'];
            tep_db_perform(TABLE_EVERYONE_TO_READ_REMIND, $data_array, 'update', 'id='.$id);
            //tep_db_query("UPDATE ". TABLE_EVERYONE_TO_READ_REMIND ." SET login_num='".$data_array['login_num']."', expiration_time=".$data_array['expiration_time'].",is_expiration=".$data_array['is_expiration']." WHERE id='".$unread_array['id']."'");
            //tep_db_perform(TABLE_EVERYONE_TO_READ_REMIND, $data_array, 'update', 'id='.$unread_array['id']);
        }
        // 每日必读提醒 end
        
        //$date_now = date('Ymd');
        tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
        
		//如果用户密码有2个月没改了就让用户去修改密码
		if((strtotime($check_admin['password_last'])+(86400*2*30)) < time() ){
			$messageStack->add_session('亲，您已经有两个月没有更新密码了，为了安全起强烈建议您修改密码！', 'error');
			tep_redirect(tep_href_link('admin_account.php','action=check_account'));
		}
		
		if (tep_not_null($session_old_full_url)) {
		  $origin_href = $session_old_full_url;
		  tep_session_unregister('session_old_full_url');
		  tep_redirect($origin_href);
		} elseif (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
          //tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
          tep_redirect(tep_href_link('index.php'));
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }

      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<style type="text/css"><!--
a { color:#000000; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #dddddd; text-decoration: none; }
A.sub:hover { color: #dddddd; text-decoration: underline; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; line-height: 1.5; color: #dddddd; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; color: #000000; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size:12px; }
.login_heading { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #ffffff;}
.login { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000;}
.loginfooter { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #000000; font-size: 10pt }
a:link.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none; }
a:visited.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none }
a:active.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: none; }
a:hover.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #8F020E; font-weight: bold; text-decoration: underline; }

//--></style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<table border="0" width="776" height="100%" cellspacing="0" cellpadding="0" align="center" valign="middle">
  <tr>
    <td><table border="0" width="776" cellspacing="0" cellpadding="1" align="center" valign="middle">
      <tr bgcolor="#ffffff">
        <td>


        <table border="0" width="100%" height="82" cellspacing="0" cellpadding="0" background="images/logo-banner_bg.gif">
		  <tr>
		    <td background="images/logo-banner_bg.gif"><a href="http://www.chainreactionweb.com"><img src="images/admin_logo.gif" border="0"></a>
		    </td>
    <td>&nbsp;</td>

<td>&nbsp;</td>
<td background="images/admin_logo_right.gif" width="462" height="82">
<table border="0" width="100%" height="72" cellspacing="0" cellpadding="0">
  <tr>
    <td class="headerBarContent" align="center">
    </td>
    </tr>
  <tr>
 <td class="headerBarContent" align="center">&nbsp;
    <!--<a href="http://www.creloaded.com/" target="_blank" class="headerLink">
    Help Desk</a>&nbsp; |&nbsp;
    <a href="http://www.chainreactionweb.com/" class="headerLink">Chainreactionweb</a>&nbsp;
    |&nbsp; <a href="http://www.oscommerce.com" class="headerLink">osCommerce</a>&nbsp;
    |-->&nbsp; <?php echo '<a href="' . tep_catalog_href_link() . '" class="headerLink">';?>Catalog</a>&nbsp;
    |&nbsp; <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>';?>&nbsp;
    |&nbsp; <?php echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>';?>&nbsp;
    </td>
    </tr>
	</table>
</td>
</table></td></tr>
          <tr bgcolor="#000000">
            <td colspan="2" align="center" valign="middle">
                          <?php echo tep_draw_form('login',FILENAME_LOGIN, 'action=process'); ?>
                           
							<!--<form name="login" action="https://www.usitrip.com/admin/login.php?action=process" method="post"> -->
							 <table width="280" border="0" cellspacing="0" cellpadding="2">
                              <tr>
                                <td class="login_heading" valign="top">&nbsp;<b><?php echo HEADING_RETURNING_ADMIN; ?></b></td>
                              </tr>
                              <tr>
                                <td height="100%" valign="top" align="center">
                                <table border="0" height="100%" cellspacing="0" cellpadding="1" bgcolor="#666666">
                                  <tr><td><table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF">
<?php
  if ($HTTP_GET_VARS['login'] == 'fail') {
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
                                  </table></td></tr>
                                </table>
                                </td>
                              </tr>
                              <tr>
                                <td valign="top" align="right">
								<?php
								//密码取回
								//echo '<a class="sub" href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a><span class="sub">&nbsp;</span>'; 
								?>
								</td>
                              </tr>
                            </table>
                          </form>

            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" class="loginfooter">
<?php
/*
  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
?>
E-Commerce Engine Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>
osCommerce provides no warranty and is redistributable under the <a href="http://www.fsf.org/licenses/gpl.txt" target="_blank">GNU General Public License</a>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?></td>
  </tr>
  <tr>
    <td align="center" class="loginfooter">Powered by <a href="http://www.usitrip.com" target="_blank">usitrip.com</a></td>
  </tr>
</table></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>

</html>
