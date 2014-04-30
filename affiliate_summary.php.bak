<?php
/*
  $Id: affiliate_summary.php,v 1.1.1.1 2004/03/04 23:37:55 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  header('Location:' . tep_href_link('affiliate_my_info.php'));
  exit;
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

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SUMMARY);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SUMMARY));
  $AffiliateInfo = getAffiliateInfo($affiliate_id);
  
  $content = CONTENT_AFFILIATE_SUMMARY;
  $javascript = $content . '.js.php';
  
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
