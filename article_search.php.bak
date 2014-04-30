<?php
/*
  $Id: article_search.php
  by AlDaffodil:  aldaffodil@hotmail.com
  Allows you to search articles using article_manager v 1.0

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_SEARCH);

  $akeywords = '';

    if (isset($HTTP_GET_VARS['akeywords'])) {
      $akeywords = $HTTP_GET_VARS['akeywords'];
    }
  
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'NONSSL', true, false));
  $content = CONTENT_ARTICLE_SEARCH;
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);  
  require(DIR_FS_INCLUDES . 'application_bottom.php'); 
?>