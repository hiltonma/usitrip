<?php
/*
  $Id: affiliate_password_forgotten.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 -2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_PASSWORD_FORGOTTEN);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $check_affiliate_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_password, affiliate_id from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . $HTTP_POST_VARS['email_address'] . "'");
    if (tep_db_num_rows($check_affiliate_query)) {
      $check_affiliate = tep_db_fetch_array($check_affiliate_query);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($newpass);
      tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_password = '" . $crypted_password . "' where affiliate_id = '" . $check_affiliate['affiliate_id'] . "'");
      
      tep_mail($check_affiliate['affiliate_firstname'] . " " . $check_affiliate['affiliate_lastname'], $HTTP_POST_VARS['email_address'], EMAIL_PASSWORD_REMINDER_SUBJECT, nl2br(sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass)), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_AFFILIATE, 'info_message=' . urlencode(TEXT_PASSWORD_SENT), 'SSL', true, false));
    } else {
      tep_redirect(tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, 'email=nonexistent', 'SSL'));
    }
  } else {

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL'));

  $content = CONTENT_AFFILIATE_PASSWORD_FORGOTTEN;
  $javascript = 'popup_window.js';
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
}
?>