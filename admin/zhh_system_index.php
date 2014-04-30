<?php
require('includes/application_top.php');
$DOCTYPE = ""; //取消检测W3C标准代码
$title = "职员培训系统";


$lift_frame_src = tep_href_link('zhh_system_left_menu.php');
$right_frame_src = tep_href_link('zhh_system_words_list.php');

$body_style = ' style="overflow:hidden"; ';//清除浏览器的滚动条

$main_file_name = "zhh_system_index";
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$CssArray = array();
$CssArray[] = "css/new_sys_main.css";

unset($Bread);	//不显示导航

include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');
$smarty->display($main_file_name.'.tpl.html');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>