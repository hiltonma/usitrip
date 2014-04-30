<?php
/*
  $Id: account_history.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
	
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $content = CONTENT_ACCOUNT_HISTORY;
  
  $javascript = 'popup_window.js';
  
  $is_my_account = true;
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  //tep_statistics_addpage(tep_current_url('',true).'?version='.USE_OLD_VERSION,$customer_id);//记录页面访问情况
?>
