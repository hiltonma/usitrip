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

// calculate link category path
  if (isset($HTTP_GET_VARS['lPath'])) {
    $lPath = $HTTP_GET_VARS['lPath'];
    $current_category_id = $lPath;
    $display_mode = 'links';
  } elseif (isset($HTTP_GET_VARS['links_id'])) {
    $lPath = tep_get_link_path($HTTP_GET_VARS['links_id']);
  } else {
    $lPath = '';
    $display_mode = 'categories';
  }

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_LINKS);

  // links breadcrumb
  $link_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$lPath . "' and language_id = '" . (int)$languages_id . "'");
  $link_categories_value = tep_db_fetch_array($link_categories_query);

  if ($display_mode == 'links') {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
    $breadcrumb->add(db_to_html($link_categories_value['link_categories_name']), FILENAME_LINKS . '?lPath=' . $lPath);
  } else {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
  }

  $cId = ($_GET['cId'])?($_GET['cId']):($_POST['cId']);
  $cId = max(1,$cId);

	//seo信息
	$the_title = db_to_html('友情链接-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end


/* 取友情链接的列表 */
	/*$links_sql = "select l2lc.link_categories_id,l.links_id,l.links_url,l.links_reciprocal_url,l.links_image_url,l.links_contact_name,l.links_contact_email,l.links_status,l.links_rating,ld.links_id,ld.language_id,ld.links_title,ld.links_description,ls.links_status_id,ls.language_id,ls.links_status_name from links l,links_to_link_categories l2lc,links_description ld,links_status ls where l.links_id=l2lc.links_id and ld.links_id=l.links_id and ls.links_status_name='Approved' and l.links_status=ls.links_status_id order by l2lc.links_id";
	$links_query = tep_db_query($links_sql);
	//display links:
	$lrow = 0;
	while($links = tep_db_fetch_array($links_query)){
		
	}*/

  $content = CONTENT_LINKS;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
