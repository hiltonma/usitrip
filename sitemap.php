<?php

  require('includes/application_top.php');

// define our link functions
  require(DIR_FS_FUNCTIONS . 'links.php');

 require(DIR_FS_LANGUAGES . $language . '/site-map.php');

	//seo信息
	$the_title = db_to_html('网站地图-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $content = 'sitemap';
$breadcrumb->add(db_to_html('网站地图'), 'sitemap.php');
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
