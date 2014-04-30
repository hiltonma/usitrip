<?php

  require('includes/application_top.php');

  $breadcrumb->add(db_to_html('旅游资讯'), tep_href_link('seo_news.php'));
  
	//class 1
	$class1_sql = tep_db_query('SELECT * FROM `seo_class` WHERE class_id ="'.(int)$_GET['class_id'].'" LIMIT 1');
	$class1_row = tep_db_fetch_array($class1_sql);

  $breadcrumb->add(db_to_html($class1_row['class_name']), tep_href_link('article_news_list.php','class_id='.(int)$class1_row['class_id']));

	//seo信息
	$the_desc = (tep_not_null($class1_row['meta_description'])) 
					? strip_tags($class1_row['meta_description'])
					: strip_tags($class1_row['class_name']);
	$the_key_words = (tep_not_null($class1_row['meta_keywords'])) 
						? strip_tags($class1_row['meta_keywords'])
						: strip_tags($class1_row['class_name']);
	$the_title = (tep_not_null($class1_row['meta_title'])) 
					? strip_tags($class1_row['meta_title'])
					: strip_tags($class1_row['class_name']);
	$the_desc = db_to_html($the_desc);
	$the_key_words = db_to_html($the_key_words);
	$the_title = db_to_html($the_title);
	//seo信息 end


  $content = 'article_news_list';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
