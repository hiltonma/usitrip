<?php
require('includes/application_top.php');

//追加导航
$breadcrumb->add(db_to_html('走四方热门景点介绍'), tep_href_link('hot-tours.php'));

$info_sql = tep_db_query('SELECT * FROM `information` WHERE information_id="'.(int)$_GET['information_id'].'" AND visible=1');
$info_row = tep_db_fetch_array($info_sql);
if(!(int)$info_row['information_id']){
	tep_redirect(tep_href_link('index.php'));
}

//seo信息
$the_desc = (tep_not_null($info_row['meta_description'])) 
				? strip_tags($info_row['meta_description'])
				: strip_tags($info_row['description']);
$the_key_words = (tep_not_null($info_row['meta_keywords'])) 
					? strip_tags($info_row['meta_keywords'])
					: strip_tags($info_row['info_keyword']);
$the_title = (tep_not_null($info_row['meta_title'])) 
				? strip_tags($info_row['meta_title'])
				: strip_tags($info_row['info_title']);
$the_desc = db_to_html($the_desc);
$the_key_words = db_to_html($the_key_words);
$the_title = db_to_html($the_title);
//seo信息 end


$breadcrumb->add(db_to_html($info_row['info_title']), '');


$content = 'hot-tours-content';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>