<?php
/*
  $Id: affiliate_contact.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // 网站联盟开关
  if (strtolower(AFFILIATE_SWITCH) === 'false') {
  	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
  	exit();
  }
  
  if (!tep_session_is_registered('affiliate_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
     tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  checkAffiliateVerified();
  

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_CONTACT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_CONTACT));

  $affiliate_values = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_id . "'");
  $affiliate = tep_db_fetch_array($affiliate_values);
  $validation_include_js = 'true';

  $content = CONTENT_AFFILIATE_CONTACT;
  
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
