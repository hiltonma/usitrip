<?php
/**
 * 走四方网旅游体验大师大赛管理
 * 
 * @author Panda
 * @category Project
 * @copyright Copyright(c) 2011 
 * @version $Id$
 */
require('includes/application_top.php');
$DOCTYPE = ""; //取消检测W3C标准代码
$title = "走四方网旅游体验师大赛管理";

$lift_frame_src = tep_href_link('daren_system_left_menu.php');
$right_frame_src = tep_href_link('daren_system_works_list.php');

$body_style = ' style="overflow:hidden"; ';//清除浏览器的滚动条

$main_file_name = "daren_system_index";
$JavaScriptSrc[] = 'includes/javascript/global.js';
$CssArray = array();
$CssArray[] = "css/daren.css";
$CssArray[] = "css/global.css";
$CssArray[] = "css/new_sys_indexDdan.css";

/* 显示管理界面 */


unset($Bread);	//不显示导航
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');
$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');
