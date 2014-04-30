<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/bbs_travel_companion_content.php');

$breadcrumb->add(TRAVEL_COMPANION_TITLE, tep_href_link('travel_companion_list.php'));


$top_class = $_GET['top_class'];


//取得所在的海岸
$li_class0 = '';
$li_class1 = '';
$li_class2 = '';
$li_class3 = '';
switch($top_class){
	case 'west': $li_class0 = 'class=s'; if(!(int)$_GET['products_id']){$breadcrumb->add(USA_WEST, tep_href_link('travel_companion_list.php','top_class=west'));} break;
	case 'east': $li_class1 = 'class=s'; if(!(int)$_GET['products_id']){$breadcrumb->add(USA_EAST, tep_href_link('travel_companion_list.php','top_class=east'));} break;
	case 'hawaii': $li_class2 = 'class=s'; if(!(int)$_GET['products_id']){$breadcrumb->add(USA_HAWAII, tep_href_link('travel_companion_list.php','top_class=hawaii'));} break;
	case 'canada': $li_class3 = 'class=s'; if(!(int)$_GET['products_id']){$breadcrumb->add(USA_CANADA, tep_href_link('travel_companion_list.php','top_class=canada'));} break;
}

$categories='';
if($top_class =='west'){ $categories='24';}
if($top_class =='east'){ $categories='25';}
if($top_class =='hawaii'){ $categories='33';}
if($top_class =='canada'){ $categories='54';}

$where_exc = '';
if($categories!=''){
	$cat_all = tep_get_categories($cat_all,$categories);
	$in_str = '';
	foreach((array)$cat_all as $key ){
		$in_str .= ','.(int)$key['id'];
	}
	$where_cate_in = ' AND ct.categories_id in('.$categories. $in_str.') ';
}

$content = 'travel_companion_list';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
