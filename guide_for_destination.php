<?php
/*
  $Id: specials.php,v 1.1.1.1 2004/03/04 23:38:03 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_TOUR_GUILD_FOR_DESTINATION);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TOUR_GUILD_FOR_DESTINATION));

	//seo信息
	$the_title = db_to_html('目的地指南-世界旅游景点目录-走四方旅游网');
	$the_desc = db_to_html('综合世界旅游景点目录,包括详细的旅游景点游记攻略,旅游景点评论,旅游景点介绍,旅游景点图片,旅游线路推荐等丰富的旅游资讯。');
	$the_key_words = db_to_html('旅游攻略,旅游景点,旅游游记');
	//seo信息 end

  $content = CONTENT_TOUR_GUILD_FOR_DESTINATION;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
