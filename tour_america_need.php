<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// define our link functions
  require(DIR_FS_FUNCTIONS . 'links.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ABOUT_US);

	//seo信息
	$the_title = db_to_html('旅美须知-走四方网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $add_div_footpage_obj = true;
  $content = 'tour_america_need';
	$breadcrumb->add(db_to_html('旅美须知'), 'tour_america_need.php');

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  
?>
