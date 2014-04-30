<?php
/*
  $Id: gv_redeem.php,v 1.1.1.1 2004/03/04 23:37:59 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Gift Voucher System v1.0
  Copyright (c) 2001, 2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// check for a voucher number in the url
  if (isset($HTTP_GET_VARS['gv_no'])) {
    $error = true;
    $gv_query = tep_db_query("select c.coupon_id, c.coupon_amount from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where coupon_code = '" . $HTTP_GET_VARS['gv_no'] . "' and c.coupon_id = et.coupon_id");
    if (tep_db_num_rows($gv_query) >0) {
      $coupon = tep_db_fetch_array($gv_query);
      $redeem_query = tep_db_query("select coupon_id from ". TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon['coupon_id'] . "'");
      if (tep_db_num_rows($redeem_query) == 0 ) {
// check for required session variables
        if (!tep_session_is_registered('gv_id')) {
          tep_session_register('gv_id');
        }
        $gv_id = $coupon['coupon_id'];
        $error = false;
      } else {
        $error = true;
      }
    }
  } else {
    tep_redirect(FILENAME_DEFAULT);
  }
  if ((!$error) && (tep_session_is_registered('customer_id'))) {
// Update redeem status
    $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon['coupon_id'] . "', '" . $customer_id . "', now(),'" . $REMOTE_ADDR . "')");
    $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon['coupon_id'] . "'");
    tep_gv_account_update($customer_id, $gv_id);
    tep_session_unregister('gv_id');   
  } 
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_GV_REDEEM);

  $breadcrumb->add(NAVBAR_TITLE); 
  $content = CONTENT_GV_REDEEM;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
