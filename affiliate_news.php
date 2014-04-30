<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
/*
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
*/
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_NEWS);

  $breadcrumb->add(db_to_html('利嫋選男巷御'), tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'SSL'));

  $content = CONTENT_AFFILIATE_NEWS;
  $javascript = $content . '.js';
  $other_css_base_name = 'mytours';


  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>