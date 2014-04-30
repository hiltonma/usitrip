<?php
require('includes/application_top.php');
require 'includes/classes/DownLoad.class.php';
if(isset($_GET['down_file'])){
	$downLoad=new DownLoad($_GET['down_file']);
	$downLoad->downFile();
	header('Location : down_load.php');
}
$downLoad=new DownLoad();
$file_array=$downLoad->getClickInfo();
  require(DIR_FS_FUNCTIONS . 'links.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ABOUT_US);

	//seo信息
	$the_title = db_to_html('下载专区-走四方网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $add_div_footpage_obj = true;
  $content = 'down_load';
	$breadcrumb->add(db_to_html('下载专区'), 'down_load.php');

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  
?>
