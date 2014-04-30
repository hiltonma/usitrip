<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products Listing
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'));

  $content = CONTENT_FEATURED_PRODUCTS;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
