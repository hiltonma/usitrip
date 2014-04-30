<?php
/*
$Id: affiliate_contact.php,v 1.1.1.1 2004/03/04 23:38:08 ccwjr Exp $

OSC-Affiliate

Contribution based on:

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 - 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('affiliate_contact');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
ini_set("max_execution_time", 2592000); //三天
set_time_limit(0);

if ( ($HTTP_GET_VARS['action'] == 'send_email_to_user') && ($HTTP_POST_VARS['affiliate_email_address']) && (!$HTTP_POST_VARS['back_x']) ) {
	switch ($HTTP_POST_VARS['affiliate_email_address']) {
		case '***':	//发已验证的联盟会员邮件
		// amit commented    $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " ");
		$mail_query = tep_db_query("select c.customers_firstname as affiliate_firstname,c.customers_lastname as affiliate_lastname, c.customers_email_address as affiliate_email_address from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id and a.verified='1' order by c.customers_lastname");
		$mail_sent_to = TEXT_ALL_AFFILIATES;
		break;
		case '**D':
			//$mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_newsletter = '1'");
			$mail_query = tep_db_query("select c.customers_firstname as affiliate_firstname,c.customers_lastname as affiliate_lastname, c.customers_email_address as affiliate_email_address from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id and a.affiliate_newsletter = '1' order by c.customers_lastname");
			$mail_sent_to = TEXT_NEWSLETTER_AFFILIATES;
			break;
		case '**AA':
			//$mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_newsletter = '1'");
			$mail_query = tep_db_query("select c.customers_firstname as affiliate_firstname,c.customers_lastname as affiliate_lastname, c.customers_email_address as affiliate_email_address from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id and a.affiliate_homepage != '' order by c.customers_lastname");
			$mail_sent_to = TEXT_ACQUIRED_AFFILIATES;
			break;
		default:
			$affiliate_email_address = tep_db_prepare_input($HTTP_POST_VARS['affiliate_email_address']);

			// $mail_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($affiliate_email_address) . "'");
			$mail_query = tep_db_query("select c.customers_firstname as affiliate_firstname,c.customers_lastname as affiliate_lastname, c.customers_email_address as affiliate_email_address from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id and c.customers_email_address = '" . tep_db_input($affiliate_email_address) . "'");
			$mail_sent_to = $HTTP_POST_VARS['affiliate_email_address'];
			break;
	}

	$from = tep_db_prepare_input($HTTP_POST_VARS['from']);
	$subject = tep_db_prepare_input($HTTP_POST_VARS['subject']);
	$message = tep_db_prepare_input($HTTP_POST_VARS['message']);

	// Instantiate a new mail object
	//$mimemessage = new email(array('X-Mailer: osC mailer'));

	// Build the text version
	//$text = strip_tags($text);
	//if (EMAIL_USE_HTML == 'true') {
		//$mimemessage->add_html($message);
	//} else {
		//$mimemessage->add_text($message);
	//}

	// Send message
	//$mimemessage->build_message();
	while ($mail = tep_db_fetch_array($mail_query)) {
		//$mimemessage->send($mail['affiliate_firstname'] . ' ' . $mail['affiliate_lastname'], $mail['affiliate_email_address'], '', $from, $subject);
		tep_mail($mail['affiliate_firstname'] . ' ' . $mail['affiliate_lastname'], $mail['affiliate_email_address'], $subject, $message, 'usitrip', $from);
	}

	tep_redirect(tep_href_link(FILENAME_AFFILIATE_CONTACT, 'mail_sent_to=' . urlencode($mail_sent_to)));
}

if ( ($HTTP_GET_VARS['action'] == 'preview') && (!$HTTP_POST_VARS['affiliate_email_address']) ) {
	$messageStack->add(ERROR_NO_AFFILIATE_SELECTED, 'error');
}

if (tep_not_null($HTTP_GET_VARS['mail_sent_to'])) {
	$messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $HTTP_GET_VARS['mail_sent_to']), 'notice');
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('affiliate_contact');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
if ( ($HTTP_GET_VARS['action'] == 'preview') && ($HTTP_POST_VARS['affiliate_email_address']) ) {
	switch ($HTTP_POST_VARS['affiliate_email_address']) {
		case '***':
			$mail_sent_to = '所有已验证的销售联盟会员';//TEXT_ALL_AFFILIATES;
			break;
		case '**D':
			$mail_sent_to = TEXT_NEWSLETTER_AFFILIATES;
			break;
		case '**AA':
			$mail_sent_to = TEXT_ACQUIRED_AFFILIATES;
			break;
		default:
			$mail_sent_to = $HTTP_POST_VARS['affiliate_email_address'];
			break;
	}
?>
          <tr><?php echo tep_draw_form('mail', FILENAME_AFFILIATE_CONTACT, 'action=send_email_to_user'); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_AFFILIATE; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo tep_htmlspecialchars(stripslashes($HTTP_POST_VARS['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo tep_htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(tep_htmlspecialchars(stripslashes($HTTP_POST_VARS['message']))); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
reset($HTTP_POST_VARS);
while (list($key, $value) = each($HTTP_POST_VARS)) {
	if (!is_array($HTTP_POST_VARS[$key])) {
		echo tep_draw_hidden_field($key, tep_htmlspecialchars(stripslashes($value)));
	}
}
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"'); ?></td>
                    <td align="left">
					<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> '; ?>
					<button type="submit" style="font-size:24px" onClick="this.disabled=true; this.innerHTML='邮件发送中，请勿动，请勿刷新页面……'">发送邮件</button>
					</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
<?php
} else {
?>
          <tr><?php echo tep_draw_form('mail', FILENAME_AFFILIATE_CONTACT, 'action=preview'); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
$affiliate = array();
$affiliate[] = array('id' => '', 'text' => TEXT_SELECT_AFFILIATE);
$affiliate[] = array('id' => '***', 'text' => '所有已验证的销售联盟会员'); //TEXT_ALL_AFFILIATES
$affiliate[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_AFFILIATES);
$affiliate[] = array('id' => '**AA', 'text' => TEXT_ACQUIRED_AFFILIATES);

//amit commented    $mail_query = tep_db_query("select affiliate_email_address, affiliate_firstname, affiliate_lastname from " . TABLE_AFFILIATE . " order by affiliate_lastname");
$sendmailarray = "select c.customers_firstname as affiliate_firstname,c.customers_lastname as affiliate_lastname, c.customers_email_address as affiliate_email_address from " . TABLE_AFFILIATE ." as a, ".TABLE_CUSTOMERS." as c  where c.customers_id = a.affiliate_id and c.customers_email_address='".tep_db_prepare_input(tep_db_input($HTTP_GET_VARS['affiliate']))."' order by c.customers_lastname";

$mail_query = tep_db_query($sendmailarray);

while($affiliate_values = tep_db_fetch_array($mail_query)) {
	$affiliate[] = array('id' => $affiliate_values['affiliate_email_address'],
	'text' => $affiliate_values['affiliate_lastname'] . ', ' . $affiliate_values['affiliate_firstname'] . ' (' . $affiliate_values['affiliate_email_address'] . ')');
}
?>
              <tr>
                <td class="main"><?php echo TEXT_AFFILIATE; ?></td>
                <td><?php echo tep_draw_pull_down_menu('affiliate_email_address', $affiliate, $HTTP_GET_VARS['affiliate']);?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FROM; ?></td>
                <td><?php echo tep_draw_input_field('from', AFFILIATE_EMAIL_ADDRESS, 'size="60"'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject', '', 'size="60"'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table></td>
          </form></tr>
<?php
}
?>
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
