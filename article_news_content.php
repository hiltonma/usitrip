<?php

  require('includes/application_top.php');
  moved_permanently_301(tep_href_link('article_news_content.php','news_id='.(int)$_GET['news_id']));

  $breadcrumb->add(db_to_html('旅游资讯'), tep_href_link('seo_news.php'));
  $sql = tep_db_query('SELECT * FROM `seo_news` n,  `seo_news_description` nd WHERE n.news_id=nd.news_id AND n.news_id="'.(int)$_GET['news_id'].'" AND news_state="1" limit 1');
  $rows = tep_db_fetch_array($sql);
  if(!(int)$rows['news_id']){
  	echo 'no date!';
	exit;
  }
  
  //记录文章热度
  tep_db_query('update seo_news SET news_click_num=news_click_num+1 where news_id="'.(int)$_GET['news_id'].'" ');
  
  //取得路径
  $path_s = tep_db_query('SELECT * FROM `seo_news_to_class` ntc, `seo_class` c where c.class_id=ntc.class_id and ntc.news_id = "'.(int)$_GET['news_id'].'" limit 1 ');
  $path_row = tep_db_fetch_array($path_s);
  $breadcrumb->add(db_to_html($path_row['class_name']), tep_href_link('article_news_list.php','class_id='.(int)$path_row['class_id']));
  $breadcrumb->add(db_to_html($rows['news_title']), tep_href_link('article_news_content.php','news_id='.(int)$rows['news_id']));

	//seo信息
	$the_desc = (tep_not_null($rows['meta_description'])) 
					? strip_tags($rows['meta_description'])
					: strip_tags($rows['news_title']);
	$the_key_words = (tep_not_null($rows['meta_keywords'])) 
						? strip_tags($rows['meta_keywords'])
						: strip_tags($rows['news_title']);
	$the_title = (tep_not_null($rows['meta_title'])) 
					? strip_tags($rows['meta_title'])
					: strip_tags($rows['news_title']);
	$the_desc = db_to_html($the_desc);
	$the_key_words = db_to_html($the_key_words);
	$the_title = db_to_html($the_title);
	//seo信息 end


  $content = 'article_news_content';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
