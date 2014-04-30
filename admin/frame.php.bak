<?php
/*
  $Id: orders_status.php,v 1.1.1.1 2004/03/04 23:38:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require('includes/application_top.php');
//权限判定
$order_dispaly_status_control_access =$login_groups_id == '1' ? true:false;
//权限判定结束

$DOC_TITLE = '业务管理系统';

define('VIN_TMP_PATH' , realpath('./templates/ver1/').DIRECTORY_SEPARATOR); //模板路径

error_reporting(E_ALL);
ini_set('display_errors','1');
	
//框架页设置 BEGIN
$page = (isset($HTTP_GET_VARS['page']) ? $HTTP_GET_VARS['page'] : '');
if($page == ''){		
	$DOC_LEFT = 'frame.php?page=left';
	$DOC_MAIN = 'orders_pointcards.php';
	//载入模板以及layout
	include(VIN_TMP_PATH.'frame.php');
}else if($page == 'left'){
	include(VIN_TMP_PATH.'menu.php');
	
}
//框架页设置 END
//----------
require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>