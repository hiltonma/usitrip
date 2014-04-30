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

  $content = 'partner';
$breadcrumb->add(db_to_html('代理加盟'), 'partner.php');

// 网站联盟开关
if (strtolower(AFFILIATE_SWITCH) === 'true') {
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
} else {
	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
}
?>