<?php
/*
  $Id: account_edit.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

	//$breadcrumb->add('View All Tours', tep_href_link('see-all-tours.php', '', 'SSL'));

	$cId = $_GET['cPath'];

  $content = 'categories_tours';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
