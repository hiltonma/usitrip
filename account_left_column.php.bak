<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);
 /* if(USE_OLD_VERSION)  
 	require(DIR_FS_CONTENT .'account_left_column_old.tpl.php');
else */
 	require(DIR_FS_CONTENT .'account_left_column.tpl.php');
?>