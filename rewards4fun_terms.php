<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REWARDS4FUN_TERMS);
  $content = CONTENT_REWARDS4FUN_TERMS;
  if(isset($customer_id) && $customer_id !=''){
  $is_my_account = true;
  }
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php', '', 'SSL'));
  $breadcrumb->add(HEADING_TITLE, tep_href_link(FILENAME_REWARDS4FUN_TERMS, '', 'NONSSL'));

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
