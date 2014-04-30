<?php
/*
$Id: logoff.php,v 1.1.1.1 2004/03/04 23:38:00 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

$breadcrumb->add(NAVBAR_TITLE);

tep_session_unregister('customer_id');
tep_session_unregister('customer_default_address_id');
tep_session_unregister('customer_default_ship_address_id');
tep_session_unregister('customer_first_name');
tep_session_unregister('customer_country_id');
tep_session_unregister('customer_zone_id');
tep_session_unregister('customer_validation');
//会员积分卡 begin
tep_session_unregister('pointcards_id');
tep_session_unregister('pointcards_id_string');
//会员积分卡 end
tep_session_unregister('comments');
tep_session_unregister('affiliate_id');
tep_session_unregister('affiliate_verified');
tep_session_unregister('affiliate_ref');
tep_session_unregister('authorizenet_failed_cntr');
//ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
tep_session_unregister('gv_id');
tep_session_unregister('cc_id');
//ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik

#### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/
if (tep_session_is_registered('customer_shopping_points')) tep_session_unregister('customer_shopping_points');
if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
if (tep_session_is_registered('customer_referral')) tep_session_unregister('customer_referral');
if (tep_session_is_registered('customer_review_process_without_login')) tep_session_unregister('customer_review_process_without_login');
if (tep_session_is_registered('total_pur_suc_nos_of_cnt')) tep_session_unregister('total_pur_suc_nos_of_cnt');
#### Points/Rewards Module V2.1rc2a balance customer points EOF ####*/
if (tep_session_is_registered('last_place_order_authorized_id')) tep_session_unregister('last_place_order_authorized_id');
$cart->reset();

//howard added

tep_session_unregister('billto');
tep_session_unregister('customer_email_address');
tep_session_unregister('travel_companion_ids');
tep_session_unregister('pay_order_id');
tep_session_unregister('sendto');

setcookie('LoginDate', '');
setcookie('customer_id','',time()-3600);
setcookie('customer_id','',time()-3600);

$content = CONTENT_LOGOFF;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
