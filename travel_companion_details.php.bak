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

//$categories_id 
$where = ' where status = "1" ';

if((int)$t_companion_id){
	$where = ' where t_companion_id="'.(int)$t_companion_id.'" AND status = "1" ';
	
}else{
	if((int)$categories_id){
		$where .= ' AND categories_id="'.(int)$categories_id.'" ';
	}
	if((int)$products_id){
		$where .= ' AND products_id="'.(int)$products_id.'" ';
	}
}
//搜索的关键字
if(tep_not_null($_GET['s_c_keyword']) && html_to_db($_GET['s_c_keyword'])!='请输入关键字'){
	$_GET['s_c_keyword'] = tep_db_prepare_input($_GET['s_c_keyword']);
	$where .= ' AND (t_companion_title Like "%'.html_to_db($_GET['s_c_keyword']).'%" || t_companion_content  Like "%'.html_to_db($_GET['s_c_keyword']).'%" )';
}
if(tep_not_null($_GET['top_class'])){
	$categories='';
	if($top_class =='west'){ $categories='24';}
	if($top_class =='east'){ $categories='25';}
	if($top_class =='hawaii'){ $categories='33';}
	if($top_class =='canada'){ $categories='54';}
	if($top_class =='noid'){ $categories='0';}

	if($categories!='' && $categories!='0'){
		$cat_all = tep_get_categories($cat_all,$categories);
		$in_str = '';
		foreach((array)$cat_all as $key ){
			$in_str .= ','.(int)$key['id'];
		}
		$where_cate_in = ' AND categories_id in('.$categories. $in_str.') ';
	}elseif($categories=='0'){
		$where_cate_in = ' AND categories_id ="0" ';
	}

	$where .= $where_cate_in;
	//echo $where;
}

//取得当前的主题贴
$sql_str = 'SELECT * FROM `travel_companion` '.$where.' order by last_time DESC ';

$travel_split = new splitPageResults($sql_str, '3');

$travel_query = tep_db_query($travel_split->sql_query);
$travel_rows = tep_db_fetch_array($travel_query);

//取得所在的目录
if((int)$travel_rows['categories_id'] && !tep_not_null($top_class)){
	$cat_sql = tep_db_query('SELECT categories_name, categories_id FROM `categories_description` WHERE language_id=1 AND categories_id="'.(int)$travel_rows['categories_id'].'" ');
	$cat_row = tep_db_fetch_array($cat_sql);
	$categories_name = preg_replace('/ .+/','',$cat_row['categories_name']);
	
	$categories = array();
	tep_get_parent_categories($categories, (int)$travel_rows['categories_id']);
	
	if($categories[0]=='24'){ $top_class ='west'; }
	if($categories[0]=='25'){ $top_class ='east'; }
	if($categories[0]=='33'){ $top_class ='hawaii'; }
	if($categories[0]=='54'){ $top_class ='canada'; }
	$categories = $categories[0];
}

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


$content = 'travel_companion_details';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
