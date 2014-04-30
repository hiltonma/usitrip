<?php
/*
 // wishlist.php,v 2.0  2003/11/22 Jesse Labrocca

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

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
  
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST, '', 'NONSSL'));

  $content = CONTENT_WISHLIST;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
