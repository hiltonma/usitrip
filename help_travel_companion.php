<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

	//seo信息
	$the_title = db_to_html('结伴同游帮助-走四方网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $add_div_footpage_obj = false;
  $content = 'help_travel_companion';
  
  $breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
  $breadcrumb->add(db_to_html('结伴同游帮助'), tep_href_link('companions_process.php'));


  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
