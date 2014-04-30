<?php
  require('includes/application_top.php');
  moved_permanently_301(tep_href_link('seo_news.php'));
  
  $breadcrumb->add(db_to_html('旅游资讯'), tep_href_link('seo_news.php'));

  $seo_title = '到美国旅游，欧洲旅游，旅游资讯攻略-走四方旅游网';
  $seo_keyword = '美国旅游,欧洲旅游,旅游资讯';
  $seo_desc = '走四方网为您提供美国旅游与欧洲旅游行程预订，酒店预订，我们的顾客可以享受种类繁多，内容丰富，度身为您定制的旅游套餐 ，以及我们优质的服务。';
	//seo信息
	$the_desc = $seo_desc;
	$the_key_words = $seo_keyword;
	$the_title = $seo_title;
	$the_desc = db_to_html($the_desc);
	$the_key_words = db_to_html($the_key_words);
	$the_title = db_to_html($the_title);
	//seo信息 end

  $content = 'seo_news';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
