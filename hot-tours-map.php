<?php
require('includes/application_top.php');

//追加导航
$breadcrumb->add(db_to_html('走四方热门景点介绍'), tep_href_link('hot-tours.php'));
$breadcrumb->add(db_to_html('热门景点地图'), tep_href_link('hot-tours-map.php'));

$content = 'hot-tours-map';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>