<?php

  require('includes/application_top.php');
 
 //追加导航
$breadcrumb->add(db_to_html('最新首创6人包团新概念'), "");

  
 $force_include_index_js = 'true';

  $content = 'a-package-tour-of-pax6';

	//require('advanced_search.php');
 require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

 require(DIR_FS_INCLUDES . 'application_bottom.php');
?>