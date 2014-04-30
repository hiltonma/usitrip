<?php
/*
  $Id: affiliate_banners.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD_CAT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD_CAT));

  //$affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title");
$affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title");

  $content = CONTENT_AFFILIATE_BANNERS_BUILD_CAT; 
  
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
